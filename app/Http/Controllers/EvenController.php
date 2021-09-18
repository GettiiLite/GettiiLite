<?php

namespace App\Http\Controllers;
use DateTime;
use Illuminate\Http\Request;
use App\Services\EvenManageServices;
use App\Services\AdminManageServices; //STS 2021/08/09 task 44
use Faker\Factory as Faker;
use function GuzzleHttp\json_decode;
use Log;
use GLHelpers;

class EvenController extends Controller
{
    const browseable = 0; //閲覧可能
    const selable    = 1; //販売可能
    
    /** @var LoginServices */
    protected $LoginServices;

    /**
     * UserController constructor.
     * @param EvenManageServices $EvenManageServices
     */ 
    //STS 2021/08/09 task 44 --COMMENT
    // public function __construct(EvenManageServices $EvenManageServices)
    // {
    //     $this->EvenManageServices = $EvenManageServices;
    // } 
    //STS 2021/08/09 task 44 --COMMENT
      //STS 2021/08/09 task 44 --START 
     public function __construct(EvenManageServices $EvenManageServices, AdminManageServices $AdminManageServices)
    {
        $this->EvenManageServices = $EvenManageServices;
        $this->AdminManageServices = $AdminManageServices;
    }  
    //STS 2021/08/09 task 44 --END
  /**
   * 發佈活動資料
   * 
   * @param Request $request
   */
  public function republish(Request $request){
    $data = array(
      'performance_id'  => $request['performance_id'],
    );

    $result = $this->EvenManageServices->republish($data);
   
    return redirect('/events')->with(['republish' => $result]);
  }

  /**
   * make csv files for GETTIIS
   *
   * @param  int  $performanceId
   * @return 
   */
  public function transport(Request $reques)
  {
    return $this->republish($reques);
    // if($reques->performance_id){

    //   $transmit_data = array(
    //     'performance_id' => $reques->performance_id,
    //   );

    //   // temporary_info 資料更新資料庫
    //   $this->EvenManageServices->updateUndisclosedEvent($transmit_data);

    //   $this->EvenManageServices->transportPerfomanceInfo($reques->performance_id);  
    // }
    // return redirect() ->back();
  }

  /**
   * delete event
   * 
   * @param Request $request
   * @return result
   */
    public function eventDelete(Request $request){
      $result = $this->EvenManageServices->eventDelete($request->all());

      if($result){
        session([
          'performance_delete_result' => trans('events.S_eventDelSucceeded') ,
        ]);
      }else{
        session([
          'performance_delete_result' => trans('events.S_eventDelfailed') ,
        ]);
      }

      return redirect('/events');
    }
   /**
     * 
     * 
     */
    public function editorImangeUpload(Request $request)
    {    
      Log::debug('Call editorImangeUpload.');
      $upload_result = $this->EvenManageServices->editorImangeUpload($request);
     
      if(!$upload_result['result']){
        return response()->json(
          [
            'uploaded' => false,
            'error' => [
              'message' => $upload_result['message']
            ]
          ]
        );
      }else{
        return response()->json([
          'uploaded' => true,
          'url' => $upload_result['url']
        ]);
  
      }
    }
     /**
     * get seat setting immage upload
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return $imageUrl
     */
    public function eventUploadImage(Request $request){
      
      Log::debug('Call eventUploadImage.');
      $imageUrl = $this->EvenManageServices->eventUploadImage($request);
      
      return $imageUrl;
    }
    /**
     * get seat setting immage upload
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return $imageUrl
     */
    public function uploadImage(Request $request){
      
      Log::debug('Call uploadImage.');
      $imageUrl = $this->EvenManageServices->blockImageUpload($request);
      
      return $imageUrl;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $page = (int)$request->input("page", 1);
        $eventData = $this->EvenManageServices->performanceList($page);
      
        return view('frontend.event.index', ['events' => $eventData]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFilter(Request $request)
    {   
        $page = (int)$request->input("page",1);
        $eventData = $this->EvenManageServices->performanceFilterList($request->all(), $page);
       
        return view('frontend.event.index', ['events' => $eventData]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $eventData = [];
        if(($json = old('json')) && null !== session('errors')) {
            Log::debug('[create] It is old data');
            $eventData = $this->EvenManageServices->parseOldJson(null,$json,false);
        }
        else {
            Log::debug('[Create]The data form DB');
            $eventData = $this->EvenManageServices->newEvent();
        }

        // todo : [7/11][James] : Need to remove befort bata release.
        $data = array("bid"=>"","direction"=>1,"stock"=>0,"map"=>'');
        $map = array("info"=>(object)$data,"statusCode"=>"200",'venueAreaName'=>'');
        $seatMap = json_encode($map); 
        $seatMap = json_decode($seatMap);
        $eventData['fbgCtrl'] = 'fbg';
      
        return view('frontend.event.edit', [
            'seatDirection' => $seatMap->info->direction ,
            'eventData' => $eventData, 
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      return $request->file('image');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($performanceId,Request $request)
    {   
      $eventData = [];
      if(($json = old('json')) && null !== session('errors')) {
          Log::debug('[edit]It is old data');
          $errJson = json_decode(session('errors')->first());
          $readDraftData = false;
          if(isset($errJson->status->update_status) && $errJson->status->update_status ) {
            $readDraftData = true;
            Log::debug('[edit]Reading data form draft.');
          }
          $eventData = $this->EvenManageServices->parseOldJson($performanceId,$json,$readDraftData);
          if(!array_key_exists('fbgCtrl', $eventData))
            $eventData['fbgCtrl'] = 'fbg';
      }
      else {
          Log::debug('[edit]The data from DB');
          $eventData = $this->EvenManageServices->show($performanceId);
          // 取得時の公演情報をsession保存する
          $request->session()->put('oldinfo'.$performanceId, $eventData);
      }
      $prefecture = \Config::get('constant.prefecture');
   
      return view('frontend.event.edit', [
            'prefecture' => $prefecture,
            'eventData' => $eventData, 
      ]);
    }
     /**
     * add new event data   
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addData(Request $request)
    {  
        Log::debug('ler.addData()');
        $request->flash(); //リクエストの入力値をセッションに保存
        
        $data = json_decode($request->all()['json']);
        $settingData = json_decode($data[0]->basisData)[0]; //基本情報
        $timeData = json_decode($data[0]->timeData)[0];     //時間・回数情報
        $ticketData = json_decode($data[0]->ticketData)[0]; //席種・券種情報
        $sellData = json_decode($data[0]->sellData);        //販売条件       
        $ticketViewData = json_decode($data[0]->ticketView);//チケットレイアウト
        $mapData = json_decode($data[0]->mapData);          //座席配置図
        $floorData = $mapData[0]->data[0]->mapData;

        $ps_ret = self::_setPerfmormanceStatus($settingData,$timeData,$ticketData,$sellData[0],$mapData, $ticketViewData, 0,$request->session()->get('event_publishable'));
        $performancestatus = $ps_ret['status'];
        $statusMsg = $ps_ret['msg'];
        \Log::debug('*** PERFORMANCE STATUS ***');
        \Log::debug($performancestatus);       

        $errors = self::_validationCheck($request,$settingData,$timeData,$ticketData,$sellData,$performancestatus,$ticketViewData);
       
        $event_id_check_result = $this->EvenManageServices->eventIdcheck(session('GLID'), $settingData->eventId);
        
        if($event_id_check_result){
          $errors[] = self::_errorsInfo(trans('events.S_basicErrMsg_042'));
        }
// if(count($errors) > 0)
         if (is_countable($errors) && count($errors) > 0) {
          Log::debug('[EventCpntroller] _validationCheck fail.');
          $error_json = [];

          $status  = array(
            'update_status' => false,
            'msn_status'    => \Config::get('constant.message_status.error'),
          );

          $data  = array(
            'msn'    => $errors,
          );

          $result  = array(
            'status' => $status,
            'data'    => $data,
          );

          $error_json[] = json_encode($result);

          return redirect() ->back() ->withInput()->withErrors($error_json);  
        }
        // 取得時の公演情報削除 sessionに残っている可能性がある為
        //$request->session()->forget('oldinfo');        
      
        //insert method
        $result = $this->EvenManageServices->addData($request,$settingData,$timeData,$ticketData,$sellData,$ticketViewData,$mapData,$performancestatus, $ps_ret);

        $error_json = [];
        if(count($statusMsg)>0){
            $status  = array(
              'update_status' => true,
              'msn_status'    => \Config::get('constant.message_status.warning'),
            );       
            
            $data  = array(
                           'msn' => $statusMsg,
                          );
            
           $result  = array(
                            'status' => $status,
                            'data'    => $data,
                           );

          $error_json[] = json_encode($result);
          //return redirect() ->back() ->withInput()->withErrors($error_json);  
        }

        return redirect('/events')->with(['performance_add_result' => $result, 'msginfo' => $error_json]);
    }
    /**
     * get seeting data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($performanceId, Request $request)
    {
      Log::debug('[EventController] update');
        $request->flash(); //リクエストの入力値をセッションに保存
        $data = json_decode($request->all()['json']);
        $basisData = json_decode($data[0]->basisData)[0]; //基本情報
        $timeData = json_decode($data[0]->timeData)[0];     //時間・回数情報
        $ticketData = json_decode($data[0]->ticketData)[0]; //席種・券種情報
        $sellSetting = json_decode($data[0]->sellData);        //販売条件    
        $mapData = json_decode($data[0]->mapData); 
        $ticketViewData = json_decode($data[0]->ticketView);
        $performancestatus_old = $request->session()->get('oldinfo'.$performanceId)['performanceStatus'];
        $transFlg = $request->session()->get('oldinfo'.$performanceId)['transFlg'];
        $fbgCtrl = $data[0]->fbgCtrl;
        $entry_time = $data[0]->entry_time;

        $ps_ret = self::_setPerfmormanceStatus($basisData,$timeData,$ticketData,$sellSetting[0],$mapData,$ticketViewData,$performancestatus_old,$request->session()->get('event_publishable'));
        $performancestatus = $ps_ret['status'];
        $statusMsg = $ps_ret['msg'];
        // $performancestatus = self::_setPerfmormanceStatus($basisData,$timeData,$ticketData,$sellSetting[0],$mapData,$performancestatus_old);
        \Log::debug('*** PERFORMANCE STATUS ***'); 
        \Log::debug($performancestatus);   
        //31/05
        $errors = self::_validationCheck($request,$basisData,$timeData,$ticketData,$sellSetting,$performancestatus,$ticketViewData);
        $error_json = [];
        // if(count($errors) > 0)
        if (is_countable($errors) && count($errors) > 0) {
          Log::debug('[EventCpntroller] _validationCheck fail.');
          

          $status  = array(
            'update_status' => false,
            'msn_status'    => \Config::get('constant.message_status.error'),
          );
          
          $data  = array(
            'msn'    => $errors,
          );
          
          // return redirect() ->back() ->withInput()->withErrors($error_json);  
        }else {
          $update_date = array(
            'performanceId'     => $performanceId,
            'performancestatus' => $performancestatus,
            'transFlg'          => $transFlg,
            'basisData'         => $basisData,
            'timeSetting'       => $timeData,
            'ticketSeeting'     => $ticketData,
            'mapData'           => $mapData,
            'ticketViewData'    => $ticketViewData,
            'sellSetting'       => $sellSetting,
            'fbgCtrl'           => $fbgCtrl,
            'ps_ret'            => $ps_ret,
            'entry_time'        => $entry_time,
          );
         
          //update method
          //$eventData = $this->EvenManageServices->update($update_date);
          $eventData = $this->EvenManageServices->updateJson($update_date);

          if($eventData){
            // 取得時の公演情報削除
            $request->session()->forget('oldinfo'.$performanceId);
            if(count($statusMsg)>0){
              $status  = array(
                'update_status' => true,
                'msn_status'    => \Config::get('constant.message_status.warning'),
                // 'title_custom'  => true,
              );
    
              $data  = array(
                // 'title'  => 'MSG title',
                'msn'    => $statusMsg,
              );
            }
            else {
              $status  = array(
                'update_status' => true,
                'msn_status'    => \Config::get('constant.message_status.information'),
              );
    
              $data  = array(
                'msn'    => [],
              );
            }
          }else{
            return redirect('/events/info/'.$performanceId)->with(['entryTimeOver' => true]);
          }
        }

        $result  = array(
          'status' => $status,
          'data'    => $data,
        );

        $error_json[] = json_encode($result);
        return redirect()->back()->withInput()->withErrors($error_json); 
    }
     /**
     * preview 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request,$performanceId)
    { 
      $data = json_decode($request->all()['json']);
      $settingData = json_decode($data[0]->basisData)[0];         //基本情報
      $timeData = json_decode($data[0]->timeData)[0];             //ステージ情報
      $ticketData = json_decode($data[0]->ticketData)[0];         //席種・券種情報
      $sellSetting = json_decode($data[0]->sellData)[0];          //販売条件
      $settingData->article = json_decode($request->article);     //記事情報

      //type      
      $settingData->eventType = $settingData->eventType?:'900';
      //image
      //$settingData->image = (empty($request->file('basisContent')))?null:sprintf('data:/image/%s;base64,%s', $request->file('basisContent')->extension(), base64_encode(file_get_contents($request->file('basisContent')->getRealPath()))); //top画像
      $settingData->image = null;
      //$settingData->editContent = $this->unescape($settingData->editContent);

      //支払方法
      $settingData->payment_credit = $sellSetting->sellSetting->payCredit->creditCard; //card
      $settingData->payment_seven  = $sellSetting->sellSetting->paySEJ->status;        //seven
      
      foreach ($settingData->article as $articleInfo) 
      {
        //$articleInfo->text = $this->unescape($articleInfo->text);
        if(isset($articleInfo->video_url))
        {    
          $articleInfo->video_url = $this->getVideoId($articleInfo->video_url);  
        }
      }  
      $settingData->article = json_encode($settingData->article);
      
      //youtube
      if(isset($settingData->contentVidioUrl))
      { 
        $settingData->contentVidioUrl = $this->getVideoId($settingData->contentVidioUrl);  
      }

      //st date - end date
      $settingData->performance_st_dt = substr($settingData->performance_st_dt,0,10) . $this->get_weekday(substr($settingData->performance_st_dt,0,10));
      $settingData->performance_end_dt = substr($settingData->performance_end_dt,0,10) . $this->get_weekday(substr($settingData->performance_end_dt,0,10));

      //開演時間 １ステージかつ単日設定の公演で、表示公演日時で表示
      $settingData->kaientm = null;
      if($timeData->status[0]->status == 'spec')
      {
        if(count($timeData->calenderDate) == 1 || substr($settingData->performance_st_dt,0,10) == substr($settingData->performance_end_dt,0,10))
        {
          $settingData->kaientm =$timeData->specDate[0]->specDate;
        }
      }elseif($timeData->status[0]->status == 'normal'){
        if(count($timeData->calenderDate) == 1 && count($timeData->calenderDate[0]->date->rule) == 1)
        {    
          $settingData->kaientm = $timeData->calenderDate[0]->date->rule[0]->time;
        }elseif(substr($settingData->performance_st_dt,0,10) == substr($settingData->performance_end_dt,0,10)){
          //新規作成
          $cnt = 0;
          foreach($timeData->calenderDate as $calenderDate) 
          {
            if(count($calenderDate->date->rule) > 0 && $calenderDate->date->hadEvens)
            {
              $settingData->kaientm = $calenderDate->date->rule[0]->time;
              $cnt = $cnt + count($calenderDate->date->rule);
            }
          }
          if($cnt > 1)$settingData->kaientm = null;
        }
      }
      
      //price
      $price = array();
      /*foreach ($ticketData->ticketSetting->data as $SeatClass) 
      {
          
        if(isset($SeatClass->data ))
        {    
          foreach ($SeatClass->data as $TicketClass) 
          {
            $price[] = $TicketClass->ticketPrice;    
          }
        }
      }*/
      //31/05
      if($ticketData->ticketSetting->settingType === 'freeSeat')
      {
        //全席自由の場合
        foreach($ticketData->ticketSetting->data->data as $TicketClass) 
        {  
           if($TicketClass->ticketStatus == 'D') continue;
           $price[] = $TicketClass->ticketPrice;
        }  
      }else if($ticketData->ticketSetting->settingType === "selectSeat"){
        //自由/指定の場合
        foreach($ticketData->ticketSetting->data as $SeatClassArr) 
        {
          foreach($SeatClassArr->data as $TicketClass)
          {
            if($TicketClass->ticketStatus == 'D') continue;
            $price[] = $TicketClass->ticketPrice;
          }
        }
      }  

      if(count($price) > 0)
      { 
        $settingData->maxPrice = number_format((empty(max($price)) ? 0 : max($price))); 
        $settingData->minPrice = number_format((empty(min($price)) ? 0 : min($price))); 
      }else{
        $settingData->maxPrice = 0; 
        $settingData->minPrice = 0;             
      }

      //主催者情報
      $user = \DB::table('GL_USER')->select('disp_name')
                                   ->where('GLID',  session('GLID'))
                                   ->first(); 
      $settingData->disp_name = $user->disp_name; 
      
      $settingData->erlybird = false; 
      $settingData->salesPeriod = false;  
      // 先行or一般 表示ルール
      // ・先行期間中
      if($settingData->earlyBirdDateChecked && strtotime($settingData->earlyBirdDateStart) <= strtotime(date("Y/m/d H:i")) && strtotime($settingData->earlyBirdDateEnd) >= strtotime(date("Y/m/d H:i")))
      { 
        $settingData->erlybird = true; 
        $settingData->salesPeriod = true;           
      }    

      // ・先行期間前は先行の表示を出して「※販売期間外です」で告知
      if($settingData->earlyBirdDateChecked && strtotime($settingData->earlyBirdDateStart) > strtotime(date("Y/m/d H:i")))
      {
        $settingData->erlybird = true; 
        $settingData->salesPeriod = false;           
      }    
      // ・先行期間中は一般の表示を出さない
      // ・先行が終わり一般開始までの期間は先行の表示を消し、一般を「※販売期間外です」で表示
      if($settingData->earlyBirdDateChecked && strtotime($settingData->earlyBirdDateEnd) < strtotime(date("Y/m/d H:i")))
      {
        $settingData->erlybird = false; 
        $settingData->salesPeriod = false;                   
      }    
      
      // ・一般期間中は一般のみを通常表示
      if($settingData->normalDateChecked && strtotime($settingData->normalDateStart) <= strtotime(date("Y/m/d H:i")))
      {
        $settingData->erlybird = false; 
        $settingData->salesPeriod = true;                   
      }    

      // ・一般が終わったら一般を「※販売期間外です」で表示
      if($settingData->normalDateChecked && strtotime($settingData->normalDateEnd) < strtotime(date("Y/m/d H:i")))
      {
        $settingData->erlybird = false; 
        $settingData->salesPeriod = false;                   
      }    
      
      //sales period
      if($settingData->erlybird)
      {
        $settingData->earlyBirdDateStart = $settingData->earlyBirdDateStart?substr($settingData->earlyBirdDateStart,0,10) . $this->get_weekday(substr($settingData->earlyBirdDateStart,0,10)) ." " . substr($settingData->earlyBirdDateStart,12,5):'';
        $settingData->earlyBirdDateEnd = $settingData->earlyBirdDateEnd?substr($settingData->earlyBirdDateEnd,0,10) . $this->get_weekday(substr($settingData->earlyBirdDateEnd,0,10)) ." " . substr($settingData->earlyBirdDateEnd,12,5):'';
      }else{
        $settingData->normalDateStart = $settingData->normalDateStart?substr($settingData->normalDateStart,0,10) . $this->get_weekday(substr($settingData->normalDateStart,0,10)) ." " . substr($settingData->normalDateStart,12,5):'';
        $settingData->normalDateEnd = $settingData->normalDateEnd?substr($settingData->normalDateEnd,0,10) . $this->get_weekday(substr($settingData->normalDateEnd,0,10)) ." " . substr($settingData->normalDateEnd,12,5):'';
      }
      return view('frontend.event.preview', [
                   'settingData' => $settingData,
                 ]);
    }
    private function isSejFormat($text, $num){
      $rule = '/[^\x{4E00}-\x{9FFF}\x{FF00}-\x{FF65}\x{FF9E}-\x{FFEF}\x{3000}-\x{30FC}]{'.$num.'}/u';
   
      return preg_match($rule, $text);
    }
     /**
     * set Perfmormance Status 
     *
     * @param  \$settingData,$timeData,$ticketData,$sellData
     * @return $status
     */
    private function _setPerfmormanceStatus($settingData,$timeData,$ticketData,$sellData,$mapData, $ticketViewData, $performancestatus=null, $publishable = 0){
       $status =  \Config::get('constant.performance_status.going'); //登録中（基本情報）
       $retmsg = [];
    
       $forSell = (isset($settingData->sale_type)?$settingData->sale_type:0) == '1';

       if($forSell){
        //基本資料
        $basis_status = true;
        //時間・回数情報
        $blsch = true;
        $onlyUnreservedSeat = true;
        $hasUnreservedSeat = false;
        //席種・券種情報
        $ticket_seat_status = null;
        $blSeat = true;
        $blReserve = true;
        $cntSeat = 0; //設定席数
       }else{
        //基本資料
        $basis_status = true;
        //時間・回数情報
        $blsch = true;
        $onlyUnreservedSeat = true;
        $hasUnreservedSeat = false;
        //席種・券種情報
        $ticket_seat_status = null;
        $blSeat = false;
        $blReserve = false;
        $cntSeat = 0; //設定席数
       }
        //STS 2021/07/29 Task 43 --START
       for($i=0; $i< count($ticketViewData); $i++) {
        $data = $ticketViewData[$i]->data;
        for($a=0; $a< count($data); $a++) {
          $preview = $data[$a]->sevenEleven;
          for($b=0; $b< count($preview); $b++) {
            if(self::isSejFormat($preview[$b]->title, 21)){
              $retmsg[] = ['title' => trans('events.S_errorOther'), 'msn' => ''];
              $basis_status = false;
            }
          }
          
        }
        
       }
        //STS 2021/07/29 Task 43 -- END

        //基本情報
        if(self::isSejFormat($settingData->eventTitle, 21)){
          $retmsg[] = ['title' => trans('events.S_basicErrMsg_054'), 'msn' => ''];
          $basis_status = false;
        }
        
        if(self::isSejFormat($settingData->eventSubTitle, 21)){
          $retmsg[] = ['title' => trans('events.S_basicErrMsg_055'), 'msn' => ''];
          $basis_status = false;
        }

       //時間・回数情報
       if(empty($timeData->status[0]->status) ) {
          \Log::debug('***ステージ未設定***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_stageErrMsg_008'), 'msn' => ''];
          }
          $blsch = false;
       }
       else {
        if($timeData->status[0]->status == 'spec')
        {    
          //特定スケジュール不要の場合、開演時間が入力されていること
          if(empty($timeData->specDate[0]->specDate)){
            \Log::debug('***(spec)開演時間未設定***');
            if($forSell){
              $retmsg[] = ['title' => trans('events.S_stageErrMsg_009'), 'msn' => ''];
            }
            $blsch = false;
          }
          //表示公演日時不能為空，但可存
          if(empty($timeData->specDate[0]->specTitle)){
            \Log::debug('***(spec)表示公演日時***');
            if($forSell){
              $retmsg[] = ['title' => trans('events.S_stageErrMsg_010'), 'msn' => ''];
            }
            $blsch = false;
          }
          if(self::isSejFormat($timeData->specDate[0]->specTitle, 8)){
            \Log::debug('***(spec)表示公演日時 半角文字***');
            if($forSell){
              $retmsg[] = ['title' => trans('events.S_stageErrMsg_011'), 'msn' => ''];
            }
            $blsch = false;
          }
        }elseif($timeData->status[0]->status == 'normal'){
          //スケジュール設定の場合、すべてのステージの開始時間が設定されていること
          $hasSchedule = false;
          $isFail = false;
          foreach($timeData->calenderDate as $cDate => $detail) 
          {
            foreach($detail->date->rule as $shcData => $sch) 
            {
               if($sch->status != 'D'){
                 if(empty($sch->time)) {
                  \Log::debug('***(normal)開演時間未設定***');
                  if($forSell){
                    $retmsg[] = ['title' => trans('events.S_stageErrMsg_009'), 'msn' => ''];
                  }
                  $blsch = false;
                  break;
                 }else {
                  $hasSchedule = true;
                 }  
                if(self::isSejFormat($sch->title, 8)){
                  \Log::debug('***表示公演日時***');
                  if($forSell){
                    $retmsg[] = ['title' => trans('events.S_stageErrMsg_011'), 'msn' => ''];
                  }
                  $blsch = false;
                  $isFail = true;
                  break;
                }
              }
            }
            if($isFail){
              break;
            }
          }  
          if(!$hasSchedule) {
            \Log::debug('***(normal)ステージ未設定***');
            if($forSell){
              $retmsg[] = ['title' => trans('events.S_stageErrMsg_008'), 'msn' => ''];
            }
            $blsch = false;
          }
        }
       }
       //席種・券種情報
       if(empty($settingData->normalDateChecked) && empty($settingData->earlyBirdDateChecked)) {
        \Log::debug('***販売期間未設定***');
        if($forSell){
          $retmsg[] = ['title' => trans('events.S_basicErrMsg_056'), 'msn' => ''];
        }
        $blSeat = false;            
       }
       else {
        if($settingData->normalDateChecked &&  empty($settingData->normalDateStart) && empty($settingData->normalDateEnd)) {
          \Log::debug('***一般販売期間未設定***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_057'), 'msn' => ''];
          }
          $blSeat = false;              
        }
        if($settingData->earlyBirdDateChecked && empty($settingData->earlyBirdDateStart) && empty($settingData->earlyBirdDateEnd)) {
          \Log::debug('***先行販売期間未設定***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_058'), 'msn' => ''];
          }
          $blSeat = false;              
        }
      }


       if(empty($ticketData->ticketSetting->settingType))
       {
         \Log::debug('***席種・券種未設定***');
         if($forSell){
          $retmsg[] = ['title' => trans('events.S_ticketErrMsg_030'), 'msn' => ''];
         }
         $blSeat = false;            
       }else if($ticketData->ticketSetting->settingType   == 'freeSeat'){
        \Log::debug('***全席自由***');
         //全席自由
         //席種入力チェック
                     
         if(empty($ticketData->ticketSetting->data->seatName))
         {
           \Log::debug('***席種名未入力***');
           if($forSell){
            $retmsg[] = ['title' => trans('events.S_ticketErrMsg_031'), 'msn' => ''];
           }
           $blSeat = false;
         } 

        if(self::isSejFormat($ticketData->ticketSetting->data->seatName, 21)){
          \Log::debug('***席種名未入力***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_ticketErrMsg_032'), 'msn' => ''];
          }
          $blSeat = false;
        }

        $hasTixSetup = false;
        foreach($ticketData->ticketSetting->data->data as $TicketClass){
          //券種入力チェック
          //券種、料金
          if($TicketClass->ticketStatus != 'D'){

             //STS 2021/06/11 : task 17 : 料金 入力 -- START
              if($TicketClass->ticketPrice === '' && $TicketClass->ticketStatus != 'D'){
              \Log::debug('***料金 入力。***');
              if($forSell){
                $retmsg[] = ['title' => '料金未入力', 'msn' => ''];
                $blSeat = false;
                break;
              }
            }
            // END
            // STS 2021/06/01
            // if((empty($TicketClass->ticketName) || $TicketClass->ticketPrice < 0))
             if($TicketClass->ticketPrice < 0){
               \Log::debug('***券種or料金未入力***');
               if($forSell){
                 $retmsg[] = ['title' => trans('events.S_ticketErrMsg_033'), 'msn' => ''];
               }
               $blSeat = false;
               break;
             }
             if(self::isSejFormat($TicketClass->ticketName, 21)){
               \Log::debug('***券種or料金未入力***');
               if($forSell){
                 $retmsg[] = ['title' => trans('events.S_ticketErrMsg_034'), 'msn' => ''];
               }
               $blSeat = false;
             }

            //先行、一般、当日のいずれかが入力されていること
            if(empty($TicketClass->ticketEarlyBird) && empty($TicketClass->ticketNormal) && empty($TicketClass->ticketOnSite)){
              \Log::debug('***先行or一般or当日未入力***');
               // if($forSell){
               //   $retmsg[] = ['title' => '席種：先行／一般／当日未入力', 'msn' => ''];
               // }
               // $blSeat = false;
               // break;
            }
            else {
              $hasTixSetup = true;
            }
            
            $cntSeat = $cntSeat + 1;
          }
        }
        if(!$hasTixSetup) {
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_ticketErrMsg_035'), 'msn' => ''];
          }
          $blSeat = false;
        }
       }else if($ticketData->ticketSetting->settingType   == 'selectSeat' && $forSell){ 
          $hasTixSetup = false;
         foreach($ticketData->ticketSetting->data as $key => $SeatClassArr){
            if($SeatClassArr->seatStatus != 'D'){
              //席種入力チェック
              if(empty($SeatClassArr->seatName)){ 
                \Log::debug('***席種名未入力***');
                $retmsg[] = ['title' => trans('events.S_ticketErrMsg_031'), 'msn' => ''];
                $blSeat = false;
                break;
              }
              if(self::isSejFormat($SeatClassArr->seatName, 21)){
                \Log::debug('***席種名未入力***');
                if($forSell){
                  $retmsg[] = ['title' => trans('events.S_ticketErrMsg_032'), 'msn' => ''];
                }
                $blSeat = false;
              }
              //判斷票名是否相同
              if(!empty($SeatClassArr->seatName)){
                $had_sama = false;
                foreach($ticketData->ticketSetting->data as $now_key => $now_SeatClassArr){
                  if($now_SeatClassArr->seatStatus != 'D'){ 
                    if($key !== $now_key && $SeatClassArr->seatName === $now_SeatClassArr->seatName){
                      $had_sama = true;
                      break;
                    }
                  }
                }
                if($had_sama){
                  \Log::debug('***席種名未入力***');
                  $retmsg[] = ['title' => trans('events.S_ticketErrMsg_036'), 'msn' => ''];
                  $blSeat = false;
                  break;
                }
              }
              //カラー設定チェック
              if(!$SeatClassArr->seatFree && empty($SeatClassArr->seatColor)){
                \Log::debug('***カラー未入力***');
                $retmsg[] = ['title' => trans('events.S_ticketErrMsg_037'), 'msn' => ''];
                $blSeat = false;
                break;            
              }    
              
              //設定席数
              if($SeatClassArr->seatFree && $SeatClassArr->seatTotal == 0) {
                // $cntSeat++;
                $hasUnreservedSeat = true;
              }
              else {
                $cntSeat += $SeatClassArr->seatTotal;
              }
              if(!$SeatClassArr->seatFree) {
                $onlyUnreservedSeat = false;
              }
              
              
              //券種入力チェック
              foreach($SeatClassArr->data as $TicketClassArr => $TicketClass) 
              {
                if($TicketClass->ticketStatus != 'D' ){

                  // STS 2021/06/11: task 17: 料金 入力 --START
                  if($TicketClass->ticketPrice === ''){
                    \Log::debug('***料金 入力。***');
                    if($forSell){
                      $retmsg[] = ['title' => '料金未入力', 'msn' => ''];
                    }
                    $blSeat = false;
                     break;
                   }
                 // END
                    //券種、料金
                  // STS 2021/06/01
//                     if((empty($TicketClass->ticketName) || $TicketClass->ticketPrice < 0))
                    if($TicketClass->ticketPrice < 0)
                     {
                       \Log::debug('***券種or料金未入力***');
                       $retmsg[] = ['title' => trans('events.S_ticketErrMsg_033'), 'msn' => ''];
                       $blSeat = false;
                       break;
                     }
                     if(self::isSejFormat($TicketClass->ticketName, 21)){
                       \Log::debug('***券種or料金未入力***');
                       if($forSell){
                         $retmsg[] = ['title' => trans('events.S_ticketErrMsg_032'), 'msn' => ''];
                       }
                       $blSeat = false;
                       break;
                     }
                    //先行、一般、当日のいずれかが入力されていること
                    if(empty($TicketClass->ticketEarlyBird) && empty($TicketClass->ticketNormal) && empty($TicketClass->ticketOnSite)&& $forSell && !$hasTixSetup)
                    {
                      \Log::debug('***先行or一般or当日未入力***');
                       // $retmsg[] = ['title' => '席種：先行／一般／当日未入力', 'msn' => ''];
                       // $blSeat = false;
                       // break;
                    }
                    else {
                      // \Log::debug('***先行or一般or当日入力した***');
                      $hasTixSetup = true;
                    }
                }
              }
            } 
         } 
         if($hasTixSetup !== true) {
           $retmsg[] = ['title' => trans('events.S_ticketErrMsg_035'), 'msn' => ''];
           $blSeat = false;
         }
       }else{
        if($forSell) {
          \Log::debug('***席種・券種設定Error***');
          $retmsg[] = ['title' => trans('events.S_ticketErrMsg_038'), 'msn' => ''];
          $blSeat = false;           
         }
       }
       //設定席数
       if($cntSeat === 0 && !$hasUnreservedSeat )
       {
         \Log::debug('***席種・券種未設定***');
         if($forSell){
          $retmsg[] = ['title' => trans('events.S_ticketErrMsg_030'), 'msn' => ''];
         }
         $blSeat = false;
       }
       
       //押さえ 押さえ記号とカラーのチェック
       if(!empty($ticketData->specTicketSetting->data))
       {
         foreach($ticketData->specTicketSetting->data as $RserveArr => $Reserve) 
         {
          if($Reserve->ticketStatus != 'D'){
            if(!empty($Reserve->ticketName) && (empty($Reserve->ticketText) || empty($Reserve->ticketColor)))
            {
              \Log::debug('***押さえ不備***');
              if($forSell){
                $retmsg[] = ['title' => trans('events.S_ticketErrMsg_039'), 'msn' => ''];
              }
              $blReserve = false;
              break;
            }
          }
        }       
      }
       
      if($blSeat && $blReserve){
        //席種・券種 OK、押え OK
        $ticket_seat_status = self:: selable; //販売可能
      }else{
        //席種・券種 NG、押え OK
        $ticket_seat_status = self:: browseable; //閲覧可能           
      }
      
      //販売条件
      $blSalesTerm = true;
      if($sellData->sellSetting->payCredit->creditCard == 1)
      {
        //クレジットカード支払いの場合
        //上限枚数
        if(empty($sellData->sellSetting->payCredit->creditCardLimit)){
           \Log::debug('*** クレジットカード支払い-上限未入力  ***');
           if($forSell){
            $retmsg[] = ['title' => trans('events.S_sellErrMsg_018'), 'msn' => ''];
           }
           $blSalesTerm = false;
        }
        //引取方法：電子チケット　or コンビニ
        if(!($sellData->sellSetting->payCredit->onlineGetTicket ||
          $sellData->sellSetting->payCredit->getTicket ||
          $sellData->sellSetting->payCredit->qrPassEmail ||
          $sellData->sellSetting->payCredit->qrPassSms ||
          $sellData->sellSetting->payCredit->resuq ||
          $sellData->sellSetting->payCredit->ibon ||
          $sellData->sellSetting->payCredit->sevenEleven ||
          $sellData->sellSetting->payCredit->noTicketing 
          )
        ){
          \Log::debug('*** クレジットカード支払い-引取方法未入力  ***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_sellErrMsg_019'), 'msn' => ''];
          }
          $blSalesTerm = false; 
        }   
       }
       // 7-11 付款
       if($sellData->sellSetting->paySEJ->status)
       {
         //引取方法：セブンイレブン　or 発券なし
         if(
           !($sellData->sellSetting->paySEJ->sevenElevenSEJ ||
            $sellData->sellSetting->paySEJ->noTicketingSEJ ||
            $sellData->sellSetting->paySEJ->resuqSEJ) 
         ){
            \Log::debug('*** セブンイレブン支払-引取方法未入力  ***');
            if($forSell){
              $retmsg[] = ['title' => trans('events.S_sellErrMsg_020'), 'msn' => ''];
            }
            $blSalesTerm = false; 
         } 

       }
       if($sellData->sellSetting->payIbon->status)
       {
        //コンビニ支払いの場合
        //上限枚数
        if(empty($sellData->sellSetting->payIbon->ibonTicketLimit)){    
          \Log::debug('*** コンビニ支払い-引取方法未入力  ***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_sellErrMsg_021'), 'msn' => ''];
          }
          $blSalesTerm = false;
        }
        //取扱期間
        //  if(empty($sellData->sellSetting->payIbon->cashDate))
        //  {    
        //    \Log::debug('*** コンビニ支払い-取扱期間未入力  ***');
        //    $blSalesTerm = false;    
        //  }
         //有効期限
        if(empty($sellData->sellSetting->payIbon->ibonDateLimit)){    
          \Log::debug('*** コンビニ支払い-有効期限未入力  ***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_sellErrMsg_022'), 'msn' => ''];
          }
          $blSalesTerm = false;    
        } 
      }      
      
      if(empty($sellData->sellSetting->payCredit->creditCard) && empty($sellData->sellSetting->payIbon->status) && empty($sellData->sellSetting->paySEJ->status)){
        \Log::debug('*** クレジットカード支払い or コンビニ支払い 未入力  ***');
        if($forSell){
          $retmsg[] = ['title' => trans('events.S_sellErrMsg_023'), 'msn' => ''];
        }
        $blSalesTerm = false;
      }
       
       //会場・座席図 
       $blMap =  true;
       if($timeData->status[0]->status == 'normal' && $ticketData->ticketSetting->settingType   == 'selectSeat' && !$onlyUnreservedSeat)
       {
        //スケジュール設定有、指定設定有の場合、座席図チェック
        $floorData = $mapData[0]->data[0]->mapData;
        if(!$mapData[0]->data[0]->createSeat && empty($floorData)){ 
          //座席図委託無しで座席図未登録
          \Log::debug('*** 座席図未登録 ***');
          if($forSell){
            $retmsg[] = ['title' => trans('events.S_excelErrMsg_018'), 'msn' => ''];
          }
          $blMap = false;
        }
        foreach($floorData as $floor ){
          if(!$mapData[0]->data[0]->createSite && empty($floor->imageUrl)){
            //会場図委託無しで会場図未登録
            \Log::debug('*** 座席配置図未登録 ***');
            if($forSell){
              $retmsg[] = ['title' => trans('events.S_excelErrMsg_019'), 'msn' => ''];
            }
            $blMap = false;
            break;
          }    
        }
         
        //設定席数
        if($cntSeat === 0)
        {
          //指定席設定なし
          \Log::debug('*** 指定席または自由席設定なし ***');
          if($forSell) {
            $retmsg[] = ['title' => trans('events.S_excelErrMsg_020'), 'msn' => ''];
          }
          $blMap = false;            
        } 
        //※階～座席向きの入力チェックはファイル取り込み時に行うためここでは不要。 
      }

       \Log::debug('*** sales_term_status  ***');
       \Log::debug(var_export($blSalesTerm,true));

       /**
        * 檢查自由席是否設定
        */
       $freeSeatHadSet = false;
       if($ticketData->ticketSetting->settingType   == 'selectSeat'){
          foreach($ticketData->ticketSetting->data as $SeatClassArr){ 
            if($SeatClassArr->seatFree && isset($SeatClassArr->data[0]) && $SeatClassArr->seatStatus != 'D'){
              $freeSeatHadSet = true;
              break;
            }
          }
        }
      
      if(!$freeSeatHadSet){
        if(!$blMap && $ticket_seat_status === self::selable)
        {
          //map情報が不完全で、席種・券種情報が販売可の場合、表示可にする
          $ticket_seat_status = self:: browseable;  
        }
      }
       \Log::debug('*** ticket_seat_status  ***');
       \Log::debug($ticket_seat_status);

       if(
        !empty($settingData->eventTitle)         &&   //title
        !empty($settingData->eventContact)       &&   //問い合わせ-表示名称
        !empty($settingData->infOpenDate)        &&   //情報公開開始
        !empty($settingData->performance_st_dt)  &&   //開催期間(from)
        !empty($settingData->performance_end_dt) &&   //開催期間(to)
        !empty($settingData->locationName)       &&   //会場-名称
        !empty($settingData->hallName)           &&   //会場-表示名称
        !empty($settingData->country)            &&   //会場-都道府県  
        !empty($settingData->city)                    //会場-市区町村 
       ) {
         if(
            //!empty($settingData->normalDateStart)     //一般販売期間(from)      
            //&& !empty($settingData->normalDateEnd)    //一般販売期間(to)
               $blsch                                 //時間・回数
            && $ticket_seat_status === self::selable  //席種・券種
            && $ticket_seat_status === self::selable //販売条件
            && $blMap                                 //座席・会場図情報
           // && $forSell
         ) {
          $status = \Config::get('constant.performance_status.sale');  
         }
         elseif ( 
            $publishable
            // && $ticket_seat_status === self::browseable //席種・券種
            // && $blSalesTerm       //販売条件
         ) {
            $status = \Config::get('constant.performance_status.browse'); 
          }
          else {
            $status = \Config::get('constant.performance_status.complete');    
          }
       }
       else {
          if(!empty($performancestatus) && $performancestatus !== \Config::get('constant.performance_status.going'))
          {
            //既に登録中以外のステータスで登録しているの場合、登録中には戻さない
            $status = $performancestatus;  
          }else{
            //登録中（基本情報）
            $status = \Config::get('constant.performance_status.going');
            if(empty($settingData->eventTitle))
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_059'), 'msn' => ''];
            if(empty($settingData->eventContact))
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_060'), 'msn' => ''];
            if(empty($settingData->infOpenDate))
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_061'), 'msn' => ''];
            if(empty($settingData->performance_st_dt) || empty($settingData->performance_end_dt))
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_062'), 'msn' => ''];
            if( empty($settingData->locationName) || empty($settingData->hallName) || empty($settingData->country) || empty($settingData->city))
            $retmsg[] = ['title' => trans('events.S_basicErrMsg_053'), 'msn' => ''];
          } 
       }
      
       $ret = ['status' => $status, 
                'msg'   => $retmsg,
                ];
      //  return $status;
      return $ret;
    }
    
    /**
     * 錯誤信息格式
     * 
     * @param $title, $msn
     * @return  $error_info
     */
    private function _errorsInfo($title, $msn=''){
      $error_info = array(
        'title' => $title,
        'msn'   => $msn,
      );
      return $error_info;
    }
     /**
     * _validationChec
     *
     * @param  $settingData,$timeData,$ticketData,$sellData
     * @return $error
     */
    private function _validationCheck($request,$settingData,$timeData,$ticketData,$sellData,$performancestatus,$ticketViewData)
    { 
          $errors = array();
		  $enInformation = json_decode($settingData->enInformation); // STS 2021/07/23 Task 37
          $forSell = (isset($settingData->sale_type)?$settingData->sale_type:0) == '1';

          //基本情報画面
          //イベントID
          if (!preg_match("/^[a-zA-Z0-9]+$/", $settingData->eventId))
            $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_001'));  
          
          if(mb_strlen($settingData->eventId) > 10 || mb_strlen($settingData->eventId) < 4)
            $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_002'));  

          //イベント名
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            //公演ステータス：登録中（基本情報）以外
            if(empty($settingData->eventTitle))
              $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_003')); 
          }

          if(mb_strlen($settingData->eventTitle )> 255) 
            $errors[] = self::_errorsInfo(trans('events.S_basicErrMsg_004')); 

          if(GLHelpers::SJISCheck($settingData->eventTitle))
            $errors[] = self::_errorsInfo(trans('events.S_TextErrMsg_001',['func' => 'イベント名'])); 

          //副題
          if(mb_strlen($settingData->eventSubTitle) > 255) 
            $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_005')); 

          if(GLHelpers::SJISCheck($settingData->eventSubTitle))
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '副題'])); 


          //活動類型
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            if(empty($settingData->eventType)) 
              $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_038')); 
          }          
          
          //公式URL
          if(mb_strlen($settingData->eventUrl) > 200)
            $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_006')); 

          //公式URL檢查   LS#1268
          if(!empty($settingData->eventUrl)){
            preg_match('@^(?:http://)?([^/]+)@i',$settingData->eventUrl, $eventUrl);
           
            if(isset($eventUrl[1])){
              if($eventUrl[1] == "https:"){
                  preg_match('@^(?:https://)?([^/]+)@i',$settingData->eventUrl, $eventUrl);
              }
            }

            if(isset($eventUrl[1])){
              $last_word = substr($eventUrl[1], -1);
              $url_error = preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $eventUrl[1]);
              
              if(!$url_error){
                $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_041')); 
              }
            } 
          }

          //表示名称
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            //公演ステータス：登録中（基本情報）以外
            if(empty($settingData->eventContact))
              $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_007')); 
          }
          if(mb_strlen($settingData->eventContact) > 80)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_008'));
          if(GLHelpers::SJISCheck($settingData->eventContact))
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '表示名称'])); 

          if(!empty($settingData->eventContact) && strpos($settingData->eventContact,'"')) //check "
            $errors[] = self::_errorsInfo(trans('events.S_TextErrMsg_002',['func' => '表示名称', 'symbol' => '\"'])); 
          
          if(!empty($settingData->eventContact) && strpos($settingData->eventContact,'\\')) // check \
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_002',['func' => '表示名称', 'symbol' => '\\\\'])); 
          // STS 2021/07/23 Task 37 validate english information start   
     
          if(GLHelpers::isInvalidateCharacterContains($enInformation->data->performanceName)  && $enInformation->status->performanceStatus ) // STS 2021/07/28 Task 37
          $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => 'イベント名(英)']));

          if(GLHelpers::isInvalidateCharacterContains($enInformation->data->performanceNameSub)  && $enInformation->status->performanceStatus) // STS 2020/07/28 Task 37
          $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '副題(英)']));

          if(GLHelpers::isInvalidateCharacterContains($enInformation->data->hallDispName)  && $enInformation->status->hallStatus) // STS 2020/07/28 Task 37
          $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '会場表示名称(英)'])); 

          if(GLHelpers::isInvalidateCharacterContains($enInformation->data->informationNm)  && $enInformation->status->informationStatus) // STS 2020/07/28 Task 37
          $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '表示名称(英)']));
          // STS 2021/07/23 Task 37 validate english information end 
          //メールアドレス
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            //公演ステータス：登録中（基本情報）以外
            if(empty($settingData->eventContactMail))$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_009')) ;
          }
          if(GLHelpers::isInvalidateCharacterContains($settingData->eventContactMail) )$errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => 'メールアドレス'])); //STS 2021/07/28 Task 37
          if(!empty($settingData->eventContactMail))if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $settingData->eventContactMail))$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_010')) ;
          if(mb_strlen($settingData->eventContactMail) > 80)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_011')) ;
          if(!empty($settingData->eventContactMail))if(!strpos($settingData->eventContactMail,'@'))$errors[]=self::_errorsInfo(trans('events.S_basicErrMsg_012')) ;
        
          //電話番号
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            //公演ステータス：登録中（基本情報）以外
            if(empty($settingData->eventContactTel))$errors[] = self::_errorsInfo(trans('events.S_basicErrMsg_013')) ;
          }
          if(!empty($settingData->eventContactTel))
          {
            if(mb_strlen(str_replace('-','',$settingData->eventContactTel)) < 8 || 
               mb_strlen(str_replace('-','',$settingData->eventContactTel)) > 15)$errors[]=self::_errorsInfo(trans('events.S_basicErrMsg_014')) ;
            if (!preg_match("/^[0-9]+$/", str_replace('-','',$settingData->eventContactTel)))$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_015')) ;
          }

          //情報公開日
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            //公演ステータス：登録中（基本情報）以外
            if(empty($settingData->infOpenDate))$errors[]  =self::_errorsInfo( trans('events.S_basicErrMsg_016')) ;
          }
          
          //情報公開終了日
          if($performancestatus != \Config::get('constant.performance_status.going')){    
            //公演ステータス：登録中（基本情報）以外
            if($settingData->dateEnd->setFlg == 'EndDate'){ 
              if(empty($settingData->dateEnd->date)){ 
                $errors[] = self::_errorsInfo(trans('events.S_basicErrMsg_044')) ;
              }
              if(!empty($settingData->dateEnd->date) &&  !empty($settingData->infOpenDate)){ 
                //開催(from) > 開催(to) ...NG
                if($settingData->infOpenDate > $settingData->dateEnd->date)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_046')) ;
              }
              if(!empty($settingData->dateEnd->date) &&  !empty($settingData->performance_end_dt)){ 
                //開催(from) > 開催(to) ...NG
                if($settingData->performance_st_dt > $settingData->dateEnd->date)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_045')) ;
              }
            }
          }       

          //開催期間
          if($performancestatus != \Config::get('constant.performance_status.going'))
          {    
            //公演ステータス：登録中（基本情報）以外
            if(empty($settingData->performance_st_dt) || empty($settingData->performance_end_dt))$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_017')) ;
          }
          
          //LS#1395 開催開始23:59固定
          $performance_st_dt = str_replace('00:00','23:59', $settingData->performance_st_dt);
          if(!empty($settingData->infOpenDate) &&  !empty($settingData->performance_st_dt ))
          {
            //[TODO] James 08/06 : 此處為string的比對，應為datetime, 並且只比對日期即可。
            // Fixed @ 10/08 by James
            if(strtotime($settingData->infOpenDate) > strtotime($performance_st_dt) )$errors[]  =self::_errorsInfo( trans('events.S_basicErrMsg_018')) ;
          }
          
          if(!empty($settingData->performance_st_dt) &&  !empty($settingData->performance_end_dt ))
          { //開催(from) > 開催(to) ...NG
            if($settingData->performance_st_dt > $settingData->performance_end_dt)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_019')) ;
          }
          
          //*****一般/先行共通*****s
          //先行販売期間
          if($settingData->earlyBirdDateChecked)
          {
            if(empty($settingData->earlyBirdDateEnd) || empty($settingData->earlyBirdDateEnd))$errors[] =self::_errorsInfo(trans('events.S_basicErrMsg_047')) ;
            
            //先行販売期間(From) > 開催期間(From) ・・NG
            //『先行販売開始日』は『開催開始日』以前の日付を入力してください。
            if(!empty($settingData->earlyBirdDateStart) && !empty($settingData->performance_st_dt) && date("Y/m/d", strtotime($settingData->earlyBirdDateStart)) > date("Y/m/d", strtotime($performance_st_dt)))$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_020')) ;

            //先行販売期間(To) > 開催期間(To)...NG
            //『先行販売終了日』は『公演終了日』以前の日付を入力してください。
            if(!empty($settingData->earlyBirdDateEnd) && !empty($settingData->performance_end_dt) && date("Y/m/d", strtotime($settingData->earlyBirdDateEnd)) > $settingData->performance_end_dt)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_021')) ;
            
          }
          //一般販売
          if($settingData->normalDateChecked)
          {
            if(empty($settingData->normalDateStart) || empty($settingData->normalDateEnd))$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_048')) ;

            //一般販売期間(From) > 開催期間(From) ・・NG
            //『一般販売開始日』は『開催開始日』以前の日付を入力してください。 removed by LS#1395
            //if(date("Y/m/d", strtotime($settingData->normalDateStart)) > $settingData->performance_st_dt)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_022')) ;

            //一般販売期間(To) > 一般開催期間(To)...NG
            //『一般販売終了日』は『公演終了日』以前の日付を入力してください。
            if(!empty($settingData->normalDateEnd) && !empty($settingData->performance_end_dt) && date("Y/m/d", strtotime($settingData->normalDateEnd)) > $settingData->performance_end_dt)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_023')) ;
          }
          //*****一般/先行共通*****e

          //*****一般/先行比較*****s
          if($settingData->normalDateChecked && $settingData->earlyBirdDateChecked && !empty($settingData->normalDateEnd) && !empty($settingData->earlyBirdDateStart))
          {    
            //一般販売期間(To) <= 先行期間(From)...NG
            //『一般販売終了日』は『先行販売開始日』より後の日付を入力してください。
            if($settingData->normalDateEnd <= $settingData->earlyBirdDateStart)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_024')) ;
          }         
          //*****一般/先行比較*****e
          
          //一般販売期間　販売可までは任意
          //if($performancestatus >= \Config::get('constant.performance_status.sale'))
          //{    
            //公演ステータス：登録中（基本情報）以外
          //  if(empty($settingData->normalDateStart) || empty($settingData->normalDateEnd))$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_022')) ;
          //}

          /* remove by LS#1395
           *           //一般販売期間(From)<=開催期間(From)
          if(!empty($settingData->normalDateStart) && !empty($settingData->performance_st_dt))
          {    
            if(date("Y/m/d", strtotime($settingData->normalDateStart)) > $settingData->performance_st_dt)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_024')) ;
          }
          if($settingData->normalDateChecked && $settingData->earlyBirdDateChecked && !empty($settingData->earlyBirdDateEnd) && !empty($settingData->normalDateStart))
          {    
            //先行販売期間(To)<=一般販売期間(From)
            if($settingData->earlyBirdDateEnd > $settingData->normalDateStart)$errors[] =self::_errorsInfo( trans('events.S_basicErrMsg_025')) ;
          }
          */

          if(( $settingData->localId == null || empty($settingData->localId)) && ($settingData->localStatus != 'N' && !empty($settingData->localStatus)) && (empty($settingData->locationName) || empty($settingData->country) || empty($settingData->city)))
            $errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_053')) ;

           //会場名
          if($performancestatus != \Config::get('constant.performance_status.going'))
            if(empty($settingData->locationName))
              $errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_026')) ;
          
          if(mb_strlen($settingData->locationName) > 120)
            $errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_027')) ;
          if(GLHelpers::isInvalidateCharacterContains($settingData->locationName)) //STS 2021/07/28 Task 37
            $errors[] = self::_errorsInfo(trans('events.S_TextErrMsg_001',['func' => '会場名'])); 
          //表示会場表示名
          if($performancestatus != \Config::get('constant.performance_status.going'))if(empty($settingData->hallName))$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_028')) ;
          if(mb_strlen($settingData->hallName) > 80)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_029')) ;
          if(GLHelpers::SJISCheck($settingData->hallName))
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '会場表示名称'])); 
          //都道府県
          if($performancestatus != \Config::get('constant.performance_status.going'))if(empty($settingData->country))$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_030')) ;
          //市区
          if($performancestatus != \Config::get('constant.performance_status.going'))if(empty($settingData->city))$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_031')) ;
          //会場URL
          if(mb_strlen($settingData->localUrl) > 200)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_032')) ;
          //会場備考
          if(mb_strlen($settingData->locationDescription) > 80)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_033')) ;

      if(GLHelpers::isInvalidateCharacterContains($settingData->locationDescription) ) //STS 2021/07/28 Task 37
          $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '備考']));
          //フリーアンケート 2021/04/09 LS-Itabashi
          foreach ($settingData->questionnaires as $questionnaire) {
            if (!$questionnaire->use) {
              continue;
            }
            $langs = get_object_vars($questionnaire->langs);
            foreach($langs as $key => $val) {
              if (!$val->selected) {
                continue;
              }
              //質問タイトル
              if (255 < mb_strlen($val->title)) {
                $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_049'));
              } elseif (GLHelpers::isInvalidateCharacterContains($val->title)) {
                $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => trans('events.S_FreeQuestionTitle') . ' - ' . trans('events.S_FreeQuestionCapt')])); 
              }
              //質問内容
              if ($performancestatus != \Config::get('constant.performance_status.going') && mb_strlen($val->text) == 0) {
                $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_050'));
              } elseif (2000 < mb_strlen($val->text)) {
                $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_051'));
              } elseif (GLHelpers::isInvalidateCharacterContains($val->text) ) {
                $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => trans('events.S_FreeQuestionTitle') . ' - ' . trans('events.S_FreeQuestionDesc')])); 
              }
              //回答例
              if (255 < mb_strlen($val->placeholder)) {
                $errors[] = self::_errorsInfo( trans('events.S_basicErrMsg_052'));
              } elseif (GLHelpers::isInvalidateCharacterContains($val->placeholder) ) {
                $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => trans('events.S_FreeQuestionTitle') . ' - ' . trans('events.S_FreeQuestionAns')])); 
              }
            }
          }

           //動画URL
           if(!empty($settingData->contentVidioUrl))
           { 
             //$settingData->contentVidioUrl = $this->getVideoId($settingData->contentVidioUrl); 
             $id = $this->getVideoId($settingData->contentVidioUrl); 
             if(empty($id))$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_034')) ;
           }
           
          //content comment
          if(mb_strlen($settingData->contentComment) > 255)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_035')) ;

          if(GLHelpers::SJISCheck($settingData->contentComment))
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => 'コメント'])); 


          //content comment
          if(mb_strlen($this->unescape($settingData->editContent)) > 1500)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_036')) ;
          
          if(GLHelpers::SJISCheckHasTag(GLHelpers::unescape($settingData->editContent)) 
          || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($settingData->editContent))
          ) //STS STS 2021/07/28 Task 37
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '説明文'])); 
           
          //イベント画像 画像ファイル(jpeg or png)であること。
          //イベント内容-Top画像
          if(!empty($request->file('logo')))
          {              
            if($request->file('logo')->getClientMimeType() !== 'image/jpeg' &&
               $request->file('logo')->getClientMimeType() !== 'image/png' ) {
              $errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_037')) ;
            }   
          }

          //記事 GETTIIS article,contentがtext typeため65535文字をmaxとする。
          if(mb_strlen($settingData->article) > 65535)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_043')) ;
          $article = json_decode($settingData->article);
          $articleimage = array();
          foreach ($article as $i => $articleInfo) 
          {
            if($i == 0) {
              if(GLHelpers::SJISCheck(GLHelpers::unescape2($articleInfo->title))
                || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($articleInfo->title))
                ) //STS 2021/07/28 Task 37 
                $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '記事タイトル'])); 
            }
            //STS 2021/07/29 - Task 37 -START
            if(GLHelpers::SJISCheckHasTag(GLHelpers::unescape($articleInfo->text)) 
            || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($articleInfo->text))
            )           
              $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '公演記事-'.($i+1)])); 
            //STS 2021/07/29 - Task 37 -END

            $index = $i + 1;
            if($articleInfo->type == '1')
            {
              if(!empty($articleInfo->image_url))
              {
                //画像   画像ファイル(jpeg or png)であること。
                //  --> Check file type when ajax uploading
                //list($w, $h, $type) = getimagesize($articleInfo->image_url);
                //$mime = image_type_to_mime_type($type);
                //if(!($mime == 'image/jpeg' ||$mime == 'image/png'))
                //{
                //  $errors[]=sprintf('活動說明[%s]',$index). self::_errorsInfo( trans('events.S_basicErrMsg_037')) ;                  
                //}
              }
            }elseif($articleInfo->type == '2'){
              //動画
              //動画URL
              if(!empty($articleInfo->video_url))
              {  
                $articleInfo->video_url = $this->getVideoId($articleInfo->video_url); 
                if(empty($articleInfo->video_url))$errors[] = self::_errorsInfo( sprintf('活動說明[%s]',$index).trans('events.S_basicErrMsg_034'));
              }               
            }  
            $articleimage[]=str_replace(url('/').'/storage',storage_path('app/public'),$articleInfo->image_url);
          }

          //keywords
          if(mb_strlen($settingData->keywords) > 500)$errors[]=self::_errorsInfo( trans('events.S_basicErrMsg_039')) ;
          if(GLHelpers::SJISCheck($settingData->keywords) ) 
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => 'KEYWORDS'])); 

          
          //時間、回数
          if($forSell){
          if($timeData->status[0]->status == 'spec')
          {
            //特定スケジュール不要の場合
            //表示公演日時
            if(mb_strlen($timeData->specDate[0]->specTitle) > 80)$errors[]=self::_errorsInfo( trans('events.S_stageErrMsg_001'));
            if(GLHelpers::SJISCheck($timeData->specDate[0]->specTitle) ) 
              $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '表示公演日時'])); 

            //開演時間  販売可までは任意
            if($performancestatus >= \Config::get('constant.performance_status.sale'))if(empty($timeData->specDate[0]->specDate)) $errors[]=self::_errorsInfo( trans('events.S_stageErrMsg_002'));
            //ステージ名
            if(isset($timeData->specDate[0]->specEvenTitle))if(mb_strlen($timeData->specDate[0]->specEvenTitle) > 20)$errors[]=self::_errorsInfo( trans('events.S_stageErrMsg_003'));
          }else if($timeData->status[0]->status == 'normal'){  
            //スケジュール設定の場合
            $firstTimeForCheckSESticket = true; //STS 2021/07/23 Task 37
            foreach($timeData->ruleList as $ruleListArr => $rule) 
            {
              if($rule->del){
                continue;
              }
              else{
                if(mb_strlen($rule->title) > 20)
                  $errors[]=self::_errorsInfo( trans('events.S_stageErrMsg_004',['id' => $rule->id]));
                if(GLHelpers::SJISCheck($rule->title) ) 
                  $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => 'ステージ名-'.$rule->id.' '])); 
              }
              
              $stageId = $rule->id;
              foreach($timeData->calenderDate as $cDate => $detail) 
              {
                $prvTime = null;
                $blchk   = false;
                foreach($detail->date->rule as $shcData => $sch) 
                {
                  if(isset($sch->status) && ($sch->status === 'D' || $sch->status === 'DD')) {
                    continue;
                  }
                  //開演時間 販売可までは任意
                  if($performancestatus >= \Config::get('constant.performance_status.sale'))if(empty($sch->time))$errors[]=self::_errorsInfo( trans('events.S_stageErrMsg_005',['date' => $sch->date]));
                  //表示公演日時
                  if(mb_strlen($sch->title) > 80)$errors[]=self::_errorsInfo( trans('events.S_stageErrMsg_006',['date' => $sch->date]));
                  if((GLHelpers::SJISCheck($sch->title) ) && $sch->id == $stageId ) //STS 2021/07/28 Task 37
                    $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => $sch->date.' '.$sch->time.'の表示公演日時'])); 
          
                  //當日場次時間不可重複檢查
                  $timeNow = new DateTime($sch->date.' '.$sch->time);
                  for($ruleNum = $shcData+1; $ruleNum < count($detail->date->rule); $ruleNum++){
                    $ruleOther = $detail->date->rule[$ruleNum];
                    if(isset($ruleOther->status) && ($ruleOther->status === 'D'|| $ruleOther->status === 'DD')) {
                      continue;
                    }
                    $timeOther = new DateTime($ruleOther->date.' '.$ruleOther->time);
                    if($timeNow == $timeOther){
                      $errors[]= self::_errorsInfo( trans('events.S_stageErrMsg_007',['date' => $ruleOther->date]));
                      break;
                    }
                  }
                //STS 2021/07/23 Task 37 start 
                  if($firstTimeForCheckSESticket){
                     if(isset($sch->ticketMsm->sevenEleven->message1) 
                      && (GLHelpers::SJISCheck(GLHelpers::unescape($sch->ticketMsm->sevenEleven->message1)) 
                      || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($sch->ticketMsm->sevenEleven->message1))
                      ) )
                      $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '（'.$sch->date.' '.$sch->time.'）セブンイレブンチケット表示文言'])); 
        
                    if(
                        isset($sch->ticketMsm->sevenEleven->message2) 
                        && (GLHelpers::SJISCheck(GLHelpers::unescape($sch->ticketMsm->sevenEleven->message2))
                            || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($sch->ticketMsm->sevenEleven->message2))
                            )
                      ) 
                      $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '（'.$sch->date.' '.$sch->time.'）セブンイレブンチケット半券表示文言'])); 
                  }
                  //STS 2021/07/23 Task 37 end
                  
                  //各公演日の開演時間が、ステージ1 < ステージ2 < ステージ3 < .....であること
                  // if(!empty($prvTime) && !$blchk)
                  // {
                  //   $Time = new DateTime($sch->date.$sch->time);
                  //   if($prvTime>= $Time)
                  //   {
                  //     $errors[]='【時間・回数】'.$sch->date.'の開演時間に誤りがあります。';    
                  //     $blchk = true;
                  //   }
                  // }
                  // $prvTime = new DateTime($sch->date. ' ' .$sch->time);

                }
              }
              $firstTimeForCheckSESticket = false; // STS 2021/07/23 Task 37
            }
          }
        }

          //席種・券種 
          $blSetEarlyTicket  = false;
          $blSetNormalTicket = false;
          if($forSell){
          if(mb_strlen($ticketData->ticketSetting->seatQty) > 5)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_001'));
          if (!preg_match("/^[0-9]+$/", str_replace('-','',$ticketData->ticketSetting->seatQty)))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_002'));
          if($ticketData->ticketSetting->settingType === 'freeSeat'){
            //全席自由の場合
            if($performancestatus >= \Config::get('constant.performance_status.sale'))if(empty($ticketData->ticketSetting->data->seatName))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_003'));
            if(mb_strlen($ticketData->ticketSetting->data->seatName) > 25)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_004'));
            if(GLHelpers::SJISCheck($ticketData->ticketSetting->data->seatName) )
              $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '席種名'])); 

            if($performancestatus >= \Config::get('constant.performance_status.sale'))if(count($ticketData->ticketSetting->data->data) == 0)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_005'));
            $alreadyErrTicket  = array();
            foreach($ticketData->ticketSetting->data->data as $index => $TicketClass) 
            {
              if($TicketClass->ticketStatus !== 'D'){
                //券種
                //STS - 2021/6/10 - Task 10
                // if($performancestatus >= \Config::get('constant.performance_status.sale'))
                   //STS 2021/06/01
                    //if(empty($TicketClass->ticketName))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_006'));

                if(mb_strlen($TicketClass->ticketName) > 30)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_007'));
                if(GLHelpers::SJISCheck($TicketClass->ticketName) ) 
                  $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '券種名'])); 
  
                //料金
                if($performancestatus >= \Config::get('constant.performance_status.sale'))if($TicketClass->ticketPrice < 0)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_008'));
                if(mb_strlen($TicketClass->ticketPrice) > 7)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_009'));
                
                //先行販売/一般販売設定チェック
                if($TicketClass->ticketEarlyBird)$blSetEarlyTicket = true;
                if($TicketClass->ticketNormal)$blSetNormalTicket = true;
                
                //先行 or 一般
                //[Todo] James 7/19 : 判斷有誤，先行跟一般可以設在一起
                
                //  if($performancestatus >= \Config::get('constant.performance_status.sale') && 
                //     $TicketClass->ticketEarlyBirdId != '0' && $TicketClass->ticketNormalId != '0') {
                //       $errors[]='【席種・券種】席種：'.$TicketClass->ticketName.'の前売り当日区分を選択してください。';
                //     }

                //一席種内に同一券種名で同一の前売り当日区分（先行、一般、当日）が設定されていないこと
                foreach($ticketData->ticketSetting->data->data as $index2 => $TicketClass2) 
                {
                  if($TicketClass2->ticketStatus !== 'D'){
                    if(in_array($TicketClass->ticketName, $alreadyErrTicket))continue; //既に前売り当日区分に重複の有る券種はcontinue
                    if($index == $index2)continue; //同じ配列の要素はcontinue
                    if($TicketClass->ticketName !== $TicketClass2->ticketName)continue;
                    //同一券種
                    // STS 2021/06/01
                    // if((!$TicketClass->ticketEarlyBird && !$TicketClass2->ticketEarlyBird) ||
                    //     (!$TicketClass->ticketNormal && !$TicketClass2->ticketNormal) ||
                    //     (empty($TicketClass->ticketOnSiteId) && empty($TicketClass2->ticketOnSiteId)))
                    // {
                    //   $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_010',['ticketName' => addslashes($TicketClass->ticketName)]));
                    //   $alreadyErrTicket[]=$TicketClass->ticketName;
                    // }
                  }              
                }
              }
            }  
          }
          else if($ticketData->ticketSetting->settingType === "selectSeat"){
            //自由/指定の場合
            $ticketClsNum = 1;
            $colors = array();
            // $hasTixSetup = false; STS 2021/06/17 Task 22
            foreach($ticketData->ticketSetting->data as $SeatClassArr) 
            {
              $hasTixSetup = true; //STS 2021/06/17 Task 22
              if($SeatClassArr->seatStatus !== 'D'){
                //席種 
                if($performancestatus >= \Config::get('constant.performance_status.sale'))if(empty($SeatClassArr->seatName))$errors[]=self::_errorsInfo(trans('events.S_ticketErrMsg_011',['ticketClsNum' => $ticketClsNum]));
                if(mb_strlen($SeatClassArr->seatName) > 25)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_012',['ticketClsNum' => $ticketClsNum]));

                if(GLHelpers::SJISCheck($SeatClassArr->seatName) )  //need to merge 
                  $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '席種名（'.addslashes($SeatClassArr->seatName).'）'])); //need to merge

                if($performancestatus >= \Config::get('constant.performance_status.sale'))if(count($SeatClassArr->data) == 0)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_013'));
                //カラーが設定されていること。
                if ($performancestatus >= \Config::get('constant.performance_status.sale') && !$SeatClassArr->seatFree && empty($SeatClassArr->seatColor))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_014',['ticketClsNum' => $ticketClsNum]));
                //席種内で同一カラーが設定されていないこと。
                if ( !$SeatClassArr->seatFree ) {
                  if (in_array($SeatClassArr->seatColor, $colors)) 
                    $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_015',['ticketClsNum' => $SeatClassArr->seatName]));
                  $colors[] = $SeatClassArr->seatColor;
                }
                else {
                  if($SeatClassArr->seatTotal <= 0) {
                    $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_029'));
                  }
                }

                $seatClsNum = 1;
                $alreadyErrTicket  = array();
                foreach($SeatClassArr->data as $index => $TicketClass)
                {
                  if($TicketClass->ticketStatus == 'D') 
                    continue;
                  //券種
                  //STS 2021/06/01
                  //STS 2021/06/10 Task 10
                  // if($performancestatus >= \Config::get('constant.performance_status.sale')){
                  //   if(empty($TicketClass->ticketName)) {
                  //     $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_016',['ticketClsNum' => $ticketClsNum, 'seatClsNum' => $seatClsNum]));
                  //   }
                  // }

                  if(mb_strlen($TicketClass->ticketName) > 30)
                    $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_017',['ticketClsNum' => $ticketClsNum, 'seatClsNum' => $seatClsNum]));

                  if(GLHelpers::SJISCheck($TicketClass->ticketName))  //need to merge 
                    $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '券種名（'.addslashes($TicketClass->ticketName).'）'])); //need to merge
  
                  //料金
                  if($performancestatus >= \Config::get('constant.performance_status.sale'))
                    if($TicketClass->ticketPrice < 0)
                      $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_018',['ticketClsNum' => $ticketClsNum, 'seatClsNum' => $seatClsNum]));

                  if(mb_strlen($TicketClass->ticketPrice) > 7)
                    $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_019',['ticketClsNum' => $ticketClsNum, 'seatClsNum' => $seatClsNum]));

                  //先行 or 一般
                  // STS 2021/06/17 Task 22 
                  if ($hasTixSetup){
                    $hasTixSetup = $performancestatus >= \Config::get('constant.performance_status.sale') && !$TicketClass->ticketEarlyBird && !$TicketClass->ticketNormal && !$TicketClass->ticketOnSite ? false : true;
                  // if($performancestatus >= \Config::get('constant.performance_status.sale') && !$TicketClass->ticketEarlyBird && !$TicketClass->ticketNormal && !$TicketClass->ticketOnSite && !$hasTixSetup) {
                  //   // $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_020',['ticketName' => addslashes($TicketClass->ticketName)]));
                  // } 
                  // else {
                  //   $hasTixSetup = true;
                  // } STS 2021/06/17 Task 22 

                  //先行販売/一般販売設定チェック
                  if($TicketClass->ticketEarlyBird)$blSetEarlyTicket = true;
                  if($TicketClass->ticketNormal)$blSetNormalTicket = true;
                  
                  //一席種内に同一券種名で同一の前売り当日区分（先行、一般、当日）が設定されていないこと
                  // Disable @ 2020/12/01 LST#1690
                  // foreach($SeatClassArr->data as $index2 => $TicketClass2)
                  // {   
                  //   if($TicketClass2->ticketStatus == 'D') 
                  //   continue;
                  //   if(in_array($TicketClass->ticketName, $alreadyErrTicket))continue; //既に前売り当日区分に重複の有る券種はcontinue
                  //   if($index == $index2)continue; //同じ配列の要素はcontinue
                  //   if($TicketClass->ticketName !== $TicketClass2->ticketName)continue;
                  //   //同一券種
                  //   if((!$TicketClass->ticketEarlyBird && !$TicketClass2->ticketEarlyBird) ||
                  //     (!$TicketClass->ticketNormal && !$TicketClass2->ticketNormal)/* ||
                  //     (empty($TicketClass->ticketOnSiteId) && empty($TicketClass2->ticketOnSiteId))*/)
                  //   {
                  //     //[TODO] James 8/21 : 需檢查變數是否有誤。
                  //     $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_021', ['seatName' => addslashes($SeatClassArr->seatName)]));
                  //     $alreadyErrTicket[]=$TicketClass->ticketName;
                  //   }
                  // }
                  $seatClsNum++;
                }
                $ticketClsNum++;
                if(!$hasTixSetup && $forSell){
                  $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_020',['ticketName' => addslashes($TicketClass->ticketName)]));
                  $hasTixSetup = true;
                }  
              }
              // if(!$hasTixSetup && $forSell){
              //   $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_020',['ticketName' => '']));
              // }  STS 2021/06/17 Task 22 
            // }
            }
          }
          //先行販売/一般販売設定チェック
          if($performancestatus >= \Config::get('constant.performance_status.sale'))
          {
            if(($settingData->earlyBirdDateChecked == true && !$blSetEarlyTicket) || ($settingData->earlyBirdDateChecked != true && $blSetEarlyTicket))
            {    
              //基本情報・先行〇/券種・先行×
              //基本情報・先行×/券種・先行〇
              $errors[]=self::_errorsInfo(  trans('events.S_ticketErrMsg_027'));
            }
            if(($settingData->normalDateChecked == true && !$blSetNormalTicket) || ($settingData->normalDateChecked != true && $blSetNormalTicket))
            {    
              //基本情報・一般〇/券種・一般×
              //基本情報・一般×/券種・一般〇
              $errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_028'));
            }
          }

          //押さえ
          if(isset($ticketData->specTicketSetting->data))
          {
            $reserveNum = 1;
            $colors = array();
            foreach($ticketData->specTicketSetting->data as $value)
            {
              if($value->ticketStatus !== 'D'){
                //押え名
                if(empty($value->ticketName))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_022', ['reserveNum' => $reserveNum]));
                if(mb_strlen($value->ticketName) > 25)$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_023', ['reserveNum' => $reserveNum]));
                if(GLHelpers::SJISCheck($value->ticketName)) 
                $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '押え席名称（'.addslashes($value->ticketName).'）'])); 
                //押え記号
                if($performancestatus >= \Config::get('constant.performance_status.browse') && empty($value->ticketText))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_024', ['reserveNum' => $reserveNum]));
                if(GLHelpers::SJISCheck($value->ticketText) 
                || GLHelpers::isInvalidateCharacterContains($value->ticketText) 
                ) //STS 2021/07/28 Task 37
                $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '押え席 - 表示記号']));
                //カラー
                if($performancestatus >= \Config::get('constant.performance_status.browse') && empty($value->ticketColor))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_025', ['reserveNum' => $reserveNum]));
                //押さえ内で同一カラーが設定されていないこと。
                if (in_array($value->ticketColor, $colors))$errors[]=self::_errorsInfo( trans('events.S_ticketErrMsg_026', ['reserveNum' => $reserveNum]));
                $colors[] = $value->ticketColor;
                $reserveNum++;
              }
            }
          }
        }
        /*STS 2021/08/06 Task 44 - Add Start
          以下の条件すべてに一致する場合のみ、保存時にエラーにする。 
            ・会員情報の「高度な会員設定」の「無料チケットを許可」がチェックOFF
            ・支払方法でセブンの選択あり
            ・有料の券種が1つもない
        */
       if($forSell){
          $freeTix = $this->AdminManageServices->detail(session('GLID'))['data']['freeTix'];
          $checkTicketPrice = false;
          $totalPrice  = 0;
          $isHasTicket = false;
          if(!$freeTix) {
            if ($ticketData->ticketSetting->settingType === 'freeSeat') {
              $checkPrice = $ticketData->ticketSetting->data->data;
              foreach ($checkPrice as $ticket) {
                if($ticket->ticketStatus === 'D')continue;
                $isHasTicket = true;
                if ($ticket->ticketPrice == 0) {
                  $checkTicketPrice = true;
                }else{
                  $totalPrice += $ticket->ticketPrice;
                }
              }
            }
            if ($ticketData->ticketSetting->settingType === 'selectSeat') {
              $checkPrice = $ticketData->ticketSetting->data;
              foreach ($checkPrice as $seat) {
                if($seat->seatStatus === 'D') continue;
                foreach ($seat->data as $ticket) {
                  if($ticket->ticketStatus === 'D') continue;
                  $isHasTicket = true;
                  if ($ticket->ticketPrice == 0 ) {
                  $checkTicketPrice = true;
                  }else{
                    $totalPrice += $ticket->ticketPrice;
                  }
                }
              }
            }
          $sevenElevenPayCredit = $sellData[0]->sellSetting->payCredit->sevenEleven;
          $statusSEJ = $sellData[0]->sellSetting->paySEJ->status;
          $sevenElevenSEJ = $sellData[0]->sellSetting->paySEJ->sevenElevenSEJ;
          $resuqSEJ = $sellData[0]->sellSetting->paySEJ->resuqSEJ;
          $noTicketingSEJ = $sellData[0]->sellSetting->paySEJ->noTicketingSEJ;
          
          if($isHasTicket){
            if (($statusSEJ || $sevenElevenSEJ || $resuqSEJ || $noTicketingSEJ ) && (!$freeTix) && $totalPrice == 0 ) {
              $errors[]=self::_errorsInfo('無料チケットでセブン-イレブン決済は指定できません。');
            }
             if (($sevenElevenPayCredit || $sevenElevenSEJ ) && (!$freeTix) && $checkTicketPrice) {
              $errors[]=self::_errorsInfo('無料のチケットが含まれる場合は、引取方法にセブン-イレブンを選択することはできません。');
            }
          }
          } else {
            //STS 2021/09/09 Task 44 - add Start
              if ($ticketData->ticketSetting->settingType === 'freeSeat') {
              $checkPrice = $ticketData->ticketSetting->data->data;
              foreach ($checkPrice as $ticket) {
                if($ticket->ticketStatus === 'D')continue;
                $isHasTicket = true;
                  $totalPrice += $ticket->ticketPrice;
              }
            }
            if ($ticketData->ticketSetting->settingType === 'selectSeat') {
              $checkPrice = $ticketData->ticketSetting->data;
              foreach ($checkPrice as $seat) {
                if($seat->seatStatus === 'D') continue;
                foreach ($seat->data as $ticket) {
                  if($ticket->ticketStatus === 'D') continue;
                  $isHasTicket = true;
                    $totalPrice += $ticket->ticketPrice;
                }
              }
            }

          $sevenElevenPayCredit = $sellData[0]->sellSetting->payCredit->sevenEleven;
          $resuqPayCredit = $sellData[0]->sellSetting->payCredit->resuq;
          $noTicketingPayCredit = $sellData[0]->sellSetting->payCredit->noTicketing;
          $sevenElevenSEJ = $sellData[0]->sellSetting->paySEJ->sevenElevenSEJ;
          $resuqSEJ = $sellData[0]->sellSetting->paySEJ->resuqSEJ;
          $noTicketingSEJ = $sellData[0]->sellSetting->paySEJ->noTicketingSEJ; $sevenElevenSEJ = $sellData[0]->sellSetting->paySEJ->sevenElevenSEJ;
          $checkSEJ = $sevenElevenSEJ && !$resuqSEJ && !$noTicketingSEJ && !$sevenElevenPayCredit && !$resuqPayCredit && !$noTicketingPayCredit;
          $checkPayCredit = $sevenElevenPayCredit && !$resuqPayCredit && !$noTicketingPayCredit && !$sevenElevenSEJ && !$resuqSEJ && !$noTicketingSEJ;
          $checkFull = $sevenElevenSEJ && !$resuqSEJ && !$noTicketingSEJ && $sevenElevenPayCredit && !$resuqPayCredit && !$noTicketingPayCredit;
           if($isHasTicket){
            if ($checkSEJ && ($freeTix) && $totalPrice == 0) {
                $errors[]=self::_errorsInfo('無料チケットでセブン-イレブン引取は指定できません');
              }  
              if ($checkPayCredit && ($freeTix) && $totalPrice == 0) {
                $errors[]=self::_errorsInfo('無料チケットでセブン-イレブン引取は指定できません');
              }  
               if ($checkFull && ($freeTix) && $totalPrice == 0) {
                $errors[]=self::_errorsInfo('無料チケットでセブン-イレブン引取は指定できません');
              }  
           }
              
          }
          //STS 2021/09/09 Task 44 - add End
        }
        //STS 2021/08/06 Task 44 - Add End

        if($forSell){
        //STS 2021/08/20 Task 46 --START
        for($i=0; $i< count($ticketViewData); $i++) {
          $data = $ticketViewData[$i]->data;
          for($a=0; $a< count($data); $a++) {
            $preview = $data[$a]->sevenEleven;
            for($b=0; $b< count($preview); $b++) {
              // dd(mb_strlen($preview[$b]->title) >= 255);
              if(mb_strlen($preview[$b]->title) > 255){
                $errors[]=self::_errorsInfo('【チケットレイアウト】イベント名称 - 255文字以下で入力してください。');
              }
            }      
          }      
        }
      //STS 2021/08/20 Task 46 --END
          
        // STS 2021/06/11: slack 3: check duplicate ticketName & ticketPrice and error screen message : 券種名と料金が重複しています。 -- START
        if ($ticketData->ticketSetting->settingType === 'freeSeat') {
          $checkData = $ticketData->ticketSetting->data->data;
          $flg = true;
            for ($i=0; $i < count($checkData); $i++) { 
              for ($j=$i; $j < count($checkData); $j++) { 
                if($flg && $i!=$j && $checkData[$i]->ticketName == $checkData[$j]->ticketName && $checkData[$i]->ticketPrice == $checkData[$j]->ticketPrice && $checkData[$i]->ticketPrice > 0 && $checkData[$j]->ticketPrice > 0 && $checkData[$i]->ticketStatus !== 'D'){
                  $errors[]=self::_errorsInfo('003', trans('events.S_ErrMsge_03'));
                  $flg = false;
                }
              }
          }
        }
        if ($ticketData->ticketSetting->settingType === 'selectSeat') {
            $checkData = $ticketData->ticketSetting->data;
            $flg = true;
            for ($i=0; $i < count($checkData); $i++) { 
              if ($checkData[$i]->data !== [] && $checkData[$i]->seatStatus !== 'D') { //STS 2021/06/18 Edit Task 17: Add $checkData[$i]->seatStatus !== 'D'
                $check = $checkData[$i]->data;
                for ($a=0; $a < count($check); $a++) { 
                  for ($b=$a; $b < count($check); $b++) { 
                    if($flg && $a!=$b && $check[$a]->ticketName == $check[$b]->ticketName && $check[$a]->ticketPrice == $check[$b]->ticketPrice && $check[$a]->ticketPrice > 0 && $check[$b]->ticketPrice > 0 && $check[$a]->ticketStatus !== 'D'){
                      $errors[]=self::_errorsInfo('003', trans('events.S_ErrMsge_03'));
                      $flg = false;
                    }
                  }
                }
              }
            }
        }
        // STS 2021/06/11: slack 3: check duplicate ticketName & ticketPrice and error screen message : 券種名と料金が重複しています。 END
          //販売条件
          if($performancestatus >= \Config::get('constant.performance_status.browse') && 
             !$sellData[0]->sellSetting->payCredit->creditCard &&
             !$sellData[0]->sellSetting->payIbon->status
             && !$sellData[0]->sellSetting->paySEJ->status)
             $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_001'));
              
          if($sellData[0]->sellSetting->payCredit->creditCard)
          {
            //creditcard
            //上限枚数
            if($performancestatus >= \Config::get('constant.performance_status.browse') && empty($sellData[0]->sellSetting->payCredit->creditCardLimit)) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_002'));
            if(mb_strlen($sellData[0]->sellSetting->payCredit->creditCardLimit) > 2) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_003'));
            if (!preg_match("/^[0-9]+$/", str_replace('-','',$sellData[0]->sellSetting->payCredit->creditCardLimit))) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_004'));
            //引取方法：電子チケット　or コンビニ
            if($performancestatus >= \Config::get('constant.performance_status.browse')
                && !$sellData[0]->sellSetting->payCredit->onlineGetTicket
                && !$sellData[0]->sellSetting->payCredit->qrPassEmail 
                && !$sellData[0]->sellSetting->payCredit->qrPassSms
                && !$sellData[0]->sellSetting->payCredit->ibon 
                && !$sellData[0]->sellSetting->payCredit->sevenEleven
                && !$sellData[0]->sellSetting->payCredit->resuq
                && !$sellData[0]->sellSetting->payCredit->noTicketing) 
               $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_005'));
          }
          if($sellData[0]->sellSetting->paySEJ->status)
          {
            //引取方法：セブンイレブン　or 発券なし
            if(

              $performancestatus >= \Config::get('constant.performance_status.browse') && 
              !$sellData[0]->sellSetting->paySEJ->sevenElevenSEJ && 
              !$sellData[0]->sellSetting->paySEJ->noTicketingSEJ &&
              !$sellData[0]->sellSetting->paySEJ->resuqSEJ
            ){
              $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_005'));
            } 
          }
          if($sellData[0]->sellSetting->payIbon->status)
          {
            //store
            //上限枚数
            // STS 2021/06/01
//          if($performancestatus >= \Config::get('constant.performance_status.browse') && empty($sellData[0]->sellSetting->payIbon->ibonTicketLimit))$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_006'));


            if(mb_strlen($sellData[0]->sellSetting->payIbon->ibonTicketLimit) > 4) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_007'));
            if (!preg_match("/^[0-9]+$/", str_replace('-','',$sellData[0]->sellSetting->payIbon->ibonTicketLimit))) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_008'));
              
            //取扱期間
            // if($performancestatus >= \Config::get('constant.performance_status.browse') && empty($sellData[0]->sellSetting->payCash->cashDate)) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_009');
            // //販売期間(to)以降の日付であること
            // if($sellData[0]->sellSetting->payCash->cashDate < $settingData->normalDateEnd) $errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_010');
            
            //有効期限
            if($performancestatus >= \Config::get('constant.performance_status.browse'))if(empty($sellData[0]->sellSetting->payIbon->ibonDateLimit))$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_011'));
            if(mb_strlen($sellData[0]->sellSetting->payIbon->ibonDateLimit) > 4)$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_012'));
            if (!preg_match("/^[0-9]+$/", str_replace('-','',$sellData[0]->sellSetting->payIbon->ibonDateLimit)))$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_013'));
          }
          //購入可能累計枚数
          //if($performancestatus >= \Config::get('constant.performance_status.browse'))if(empty($sellData[0]->sellSetting->buyLimit))$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_014');
          
          // Start STS 2021/05/28 

          // if(!empty($sellData[0]->sellSetting->buyLimit))
          // {
          //   if($sellData[0]->sellSetting->buyLimit <= 0)$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_015'));
          //   // if(mb_strlen($sellData[0]->sellSetting->buyLimit) > 2)$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_016'));
          //   if (!preg_match("/^[0-9]+$/", str_replace('-','',$sellData[0]->sellSetting->buyLimit)))$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_017'));
          // }

          if(!empty($sellData[0]->sellSetting->buyLimit) || $sellData[0]->sellSetting->buyLimit == 0)
          {
            if($sellData[0]->sellSetting->buyLimit < 1)$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_015'));
            // if(mb_strlen($sellData[0]->sellSetting->buyLimit) > 2)$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_016'));
            if (!preg_match("/^[0-9]+$/", str_replace('-','',$sellData[0]->sellSetting->buyLimit)))$errors[]=self::_errorsInfo( trans('events.S_sellErrMsg_017'));
          }

          // End STS 2021/05/28

          if(isset($ticketViewData[0]->data[0]->sevenEleven[0]->titleCustom) && $ticketViewData[0]->data[0]->sevenEleven[0]->titleCustom) {
            if(GLHelpers::SJISCheck($ticketViewData[0]->data[0]->sevenEleven[0]->title))
              $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => 'セブンイレブンチケットイベント表示名称'])); 
          }
          if(GLHelpers::SJISCheck(GLHelpers::unescape($ticketViewData[0]->data[0]->sevenEleven[0]->message1)) 
          || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($ticketViewData[0]->data[0]->sevenEleven[0]->message1))
          ) //STS 2021/07/28 Task 37
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '（全ステージ）セブンイレブンチケット表示文言'])); 

          if(GLHelpers::SJISCheck(GLHelpers::unescape($ticketViewData[0]->data[0]->sevenEleven[0]->message2)) 
          || GLHelpers::isInvalidateCharacterContains(GLHelpers::unescape2($ticketViewData[0]->data[0]->sevenEleven[0]->message2))
          ) //STS 2021/07/28 Task 37
            $errors[] = self::_errorsInfo( trans('events.S_TextErrMsg_001',['func' => '（全ステージ）セブンイレブンチケット半券表示文言'])); 

          
          if(count($errors) == 0)
          {
            // [article]Delete unnecessary image files
            $dir = glob(storage_path('app/public').'/event-data/' . $settingData->imageLocation .'/article/*');
            foreach ($dir as $file) 
            { 
              if (!in_array($file, $articleimage)) 
              {
                unlink($file);
              }
            }
          }
        }
        return $errors;     
     }
    }
     /**
     * Update map data 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mapDataUpdate(Request $reques)
    {
        
        $array = [];
        $line = [];
        for($n=1; $n<=5; $n++){
            for($m=1; $m<=20; $m++){
                $line[$m] = $m;
                $array[$n.'.'.$m] = array("x"=>$n,"y"=>$m,"sid"=>$n+$m,"sale"=>true,"vacant"=>true);
            }
        }

        $VenueAreaName = [];

        for($n=1; $n<=10; $n++){
            $VenueAreaName[] =  'n'.$n;
        }
        
        $datamap = array("x_min"=>1,"x_max"=>5,"y_min"=>1,"y_max"=>20,"seats"=>(object)$array,"lines"=>(object)$line);
        $data = array("bid"=>"77835b6c250df1fa4b65f3fa0d384c045808f45f96c117ec1a71c6d1a421812f","direction"=>1,"stock"=>50,"map"=>(object)$datamap);
        $map = array("info"=>(object)$data, "statusCode"=>"200", "venueAreaName"=>$VenueAreaName);
        $seatMap = json_encode($map);
        $seatMap = json_decode($seatMap);

        return response()->json($seatMap, 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    function getVideoId($videoUrl) {
      preg_match('/\?v=([^&]+)/',$videoUrl,$match);
      $id = null;
      if(isset($match[1]))$id = $match[1];  
      return $id;
    }
    function unescape($source, $iconv_to = 'UTF-8') {
      $decodedStr = '';
      $pos = 0;
      $len = strlen ($source);
      while ($pos < $len) {
        $charAt = substr ($source, $pos, 1);
        if ($charAt == '%') {
            $pos++;
            $charAt = substr ($source, $pos, 1);
            if ($charAt == 'u') {
                // we got a unicode character
                $pos++;
                $unicodeHexVal = substr ($source, $pos, 4);
                $unicode = hexdec ($unicodeHexVal);
                $decodedStr .= $this->code2utf($unicode);
                $pos += 4;
            }
            else { 
                // we have an escaped ascii character
                $hexVal = chr(hexdec(substr($source, $pos, 2)));
                if(!((bool)preg_match('//u', $hexVal)))
                {
                  //不正なバイト列が含まれてる場合、utf8_encode
                  //if the string is not valid in UTF-8,activation for UTF8 
                  $decodedStr .= utf8_encode($hexVal);
                }else{
                  $decodedStr .= $hexVal;
                }
                $pos += 2;
            }
        }
        else {
            $decodedStr .= $charAt;
            $pos++;
        }
    }

    if ($iconv_to != "UTF-8") {
        $decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
    }
    
    return $decodedStr;
  }

  function code2utf($num){
    if($num<128)return chr($num);
    if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
    if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
    if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
    return '';
  }
  function get_weekday($datetime)
  {
    if(empty($datetime)) return null;
    $weekday  = date('w', strtotime($datetime));
    $weeklist = array(
                      trans('events.days.sun'),
                      trans('events.days.mon'),
                      trans('events.days.tue'),
                      trans('events.days.wed'),
                      trans('events.days.thu'), 
                      trans('events.days.fri'),
                      trans('events.days.sat'),
                     );
    
    return  '('.$weeklist[$weekday].')';
  }      
}
