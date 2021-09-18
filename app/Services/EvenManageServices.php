<?php

namespace App\Services;

use DateTime;
use DateInterval;
use Validator;
use DatePeriod;
use Illuminate\Http\Request;
use App\Repositories\EvenManageRepositories;
use App\Repositories\MobapassOutputRepositories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use ZipArchive;
use finfo;
use Log;
use Mail;
use Carbon\Carbon;
use Exception;
use App\MailExceptionMail;
use DB;

class EvenManageServices
{
  /** @var  EvenManageRepositories */
  protected $EvenManageRepositories;
  /** @var  MobapassOutputRepositories */  
  protected $MobapassOutputRepositories;
  
  const PAGE_SIZE = 10;

  /**
   * EvenMananageRepositories constructor.
   * @param  EvenManageRepositories $EvenMananageRepositories
   * @return
   */
  public function __construct(EvenManageRepositories $EvenManageRepositories,MobapassOutputRepositories $MobapassOutputRepositories)
  {
    $this->EvenManageRepositories = $EvenManageRepositories;
    $this->MobapassOutputRepositories = $MobapassOutputRepositories;
  }
  /**
   * 8 bit number
   *
   * @return rand number
   */
  protected function randnum()
  {
    $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    str_shuffle($str);
    $star = rand(1, 26);
    $passWord = substr(str_shuffle($str), $star, 8);

    return $passWord;
  }
  /**
   * data tranformation
   *
   * @return rand number
   */
  protected function dataTranformation($dateStar, $dataEnd)
  {

    $dateData['date_d_star'] = date("Y/m/d", strtotime($dateStar));
    $dateData['date_t_star'] = date("H:i", strtotime($dateStar));
    $dateData['date_d_end'] = date("Y/m/d", strtotime($dataEnd));
    $dateData['date_t_end'] = date("H:i", strtotime($dataEnd));

    return $dateData;
  }
  /**
   * file upload
   *@parm $file -- image data
   * @return $path
   */
  protected function imageUpload($file, $namingType=1, $eventId=null)
  {
    if ($file) {
      if(!$eventId) 
        $namingType = 1;
      switch($namingType) {
        case 2:
          $name =  session('user_code') . '_' . $eventId . '.' . $file->getClientOriginalExtension();
        break;
        default :
          $name =  $this->randnum() . date("dYhmm") . '.' . $file->getClientOriginalExtension();
        break;
      }
      $destinationPath = '/public/event-data';
      $image = Storage::putFileAs($destinationPath, $file, $name);
      $path = Storage::url($image);
    } else {
      $path = null;
    }
    return $path;
  }
  /**
   * get basis setting data
   * @param $eventInf
   * @return $result
   */
  protected function getBasisSetting($eventInf)
  {
    
    $imageUrl = '';
    $vidioUrl = '';
    $eventData = $this->EvenManageRepositories->basisSettingGet($eventInf);
    $data = $eventData['performance'][0];

    if ($data['top_conten_type'] === 1) {
      $contentType = 'image';
      $imageUrl = $data['top_conten_url'];
    } else {
      $contentType = 'vidio';
      $vidioUrl =  $data['top_conten_url'];
    }

    $basisInf = array(
      'thumbnail' => $data['thumbnail'],
      'performance_code' => $eventInf['performance_code'],
      'tenporary_info' => $data['performance_name'],
      'performance_name_sub' => $data['performance_name_sub'],
      'genre_code' => $data['genre_code'],
      'information_nm' => $data['information_nm'],
      'mail_address' => $data['mail_address'],
      'information_tel' => $data['information_tel'],
      'offcial_url' => 'http://www.gettislight.test/events/info',
      'disp_start' => '2019/03/01 08:00',
      'earlyBirdDateChecked' => true,
      'earlyBirdDateStart' => '2019/03/01',
      'earlyBirdDateEnd' => '2019/04/01',
      'normalDateChecked' => true,
      'normalDateStart' => '2019/03/01',
      'normalDateEnd' => '2019/04/01',
      'hall_name' => $data['hall_name'],
      'hall_name_kana' => $data['hall_name_k'],
      'prefecture' => $data['prefecture'],
      'city' => $data['city'],
      'hall_url' => 'http://www.gettislight.test/events/info',
      'top_content_type' => $contentType,
      'top_image_url' => $imageUrl,
      'top_vidio_url' => $vidioUrl,
      'top_content_comment' => $data['top_content_comment'],
      'hall_nm' => '',
      'description' => '',
      'performance_st_dt' => $data['performance_st_dt'],
      'performance_end_dt' => $data['performance_end_dt']
    );

    return $basisInf;
  }
  /**
   * spec date information insert or update 
   *
   * @return $result
   */
  protected function _createSeatMapInfo($GLID, $performanceId, $account_cd, $settingData)
  {
    //GL_SEATMAP_PROFILE insert
    $seatmap = array(
      'hall_code' => $settingData->localId,             //会場コード
      'performance_id' => $performanceId,               //公演ID
      'floor_ctrl' => '1',                                //座席階管理
      'block_ctrl' => '1',                                //座席ブロック管理
      'gate_ctrl'  => '1',                               //座席ゲート管理
      'description' => $settingData->locationDescription, //備考
      'Owner_cd' => $GLID,                                //オーナー
      'public' => '0',                                    //公開 0:非公開 1:公開
      'version' => '0',                                   //version
      'update_account_cd' => $account_cd,                 //更新担当者コード                  
    );
    $seatMapProfileId = $this->EvenManageRepositories->seatMapProfileInsert($seatmap);

    //GL_PERFORMNCE update seatmap_profile_cd
    try {
      \App\Models\EvenManageModel::where('performance_id', $performanceId)
        ->update(['seatmap_profile_cd' => $seatMapProfileId]);
    }catch(Exception $e){
        Log::error('_createSeatMapInfo :'.$e->getMessage());
        throw new Exception ('EMS-EXP-CSMI');
    }
    return  $seatMapProfileId;
  }
  /**
   * 檢查發布狀態
   *
   * @return rand number
   */
  protected function getStarDate($temporary_info)
  {
    //販売期間
    $earlyBirdDateStart = isset($temporary_info->earlyBirdDateStart) ? $temporary_info->earlyBirdDateStart : ""; //先行from
    $normalDateStart    = isset($temporary_info->normalDateStart) ? $temporary_info->normalDateStart : "";    //一般from
    $starDate           = empty($earlyBirdDateStart) ? $normalDateStart : $earlyBirdDateStart; //活動開始時間

    return $starDate;
  }
  /**
   * 檢查發布狀態
   *
   * @return bool 
   */
  protected function checkAnoounceStatus($performance_data)
  {
    $result = false;
    $tt = json_decode($performance_data['temporary_info']);
    $temporary_info = isset($tt->basisData) ? $tt->basisData : "";
    $trans_performance_status = $performance_data->status;
    $announce_date = $performance_data['announce_date'];
    $star_date = $this->getStarDate($temporary_info);
    $now_status = $this->getPerformanceDispStatus($performance_data, $star_date, null);
  

    if(is_null($announce_date) || $now_status < \Config::get('constant.performance_disp_status.sale')){
      Log::debug('has');
    }else{
      $filter_data = array(
        'performance_id' => $performance_data['performance_id']
      );
      $draft = $this->EvenManageRepositories->getDraft($filter_data);
      if($draft){
        $trans_status = $this->getPerformanceDispStatus($performance_data, $star_date, $announce_date);
        $draft_updated_at = $draft['updated_at'];
        if($now_status != $trans_status && $draft_updated_at->lt(Carbon::parse($star_date))){
          Log::debug('edit status is old');
          $result = true;
        }
        if($now_status == \Config::get('constant.performance_disp_status.close') && $draft_updated_at->lt(Carbon::parse($performance_data->performance_end_dt)) ){
          Log::debug('edit status is old');
          $result = true;
        }
        if($now_status == \Config::get('constant.performance_disp_status.cancel')  ){
          Log::debug('edit status is old');
          $result = true;
        }
      }

    }
   
    return $result;
  }
  /**
   * 取得已設定席位
   *
   * @param string $map_data
   * @param array $ticket_data
   * @return array is_setting_tikcet
   */
  protected function getRespectiveSeat($map_data, $ticket_data)
  {
    $ticket_status = array('N', 'U', 'I');
    $is_setting_tikcet = array();
  
    if($ticket_data['settingType'] == 'selectSeat'){
      foreach($ticket_data['data'] as $key => $ticket){ 
        if(in_array($ticket['seatStatus'], $ticket_status)){
          $search_text = '"index":'.strval($key).'}';
          $search_result = strpos($map_data, $search_text);
          if($search_result){
            array_push($is_setting_tikcet, $key);
          }
        }else{
          continue;
        }
      }
    }
  
    return $is_setting_tikcet;
  }
  /**
   * Save ticket default message
   *
   * @return $result
   */
  private function _saveDefaultTicketMSG($performanceId, $ticketViewData, $accountCd)
  {
    $view         = $ticketViewData[0]->data[0]->mobapass[0];
    $qrpass       = isset($ticketViewData[0]->data[0]->qrpass[0])?$ticketViewData[0]->data[0]->qrpass[0] : null;
    $ibon         = isset($ticketViewData[0]->data[0]->ibon[0]) ? $ticketViewData[0]->data[0]->ibon[0] : null;
    $sevenEleven  = isset($ticketViewData[0]->data[0]->sevenEleven[0])? $ticketViewData[0]->data[0]->sevenEleven[0] : null;
    
    //set default message for all stages
    if (!isset($view->status) || $view->status != 'N') {

      $free_word = array(
        'title'    => '',
        'content'  => $view->content,
      );
  
      $json = json_encode($free_word);

      $layout = array(
        'performance_id'    => $performanceId,   //公演ID
        'schedule_id'       => null,           //default
        'ticket_kbn'        => \Config::get('constant.pickup_method.eticket'),  //チケット区分1:電子チケット
        'thumbnail'         => $view->logoPath,  //サムネイル todo logopathの設定
        'free_word'         => $json,   //自由表示欄
        'update_account_cd' => $accountCd,       //更新担当者コード
      );
      $layout = $this->EvenManageRepositories->updateOrCreateTicketLayout(!empty($view->id) ? $view->id : null, $layout);
      // todo : [07/08][James] Write the layout id back to json
      $ticketViewData[0]->data[0]->mobapass[0]->id = $layout->layout_id;
    }

    //set qrpass default message for all stages
    if ($qrpass && (!isset($qrpass->status) || $qrpass->status != 'N')) {
  
      $free_word = array(
        'title'    => $qrpass->title,
        'content'  => $qrpass->content,
      );
  
      $json = json_encode($free_word);

      $layout = array(
        'performance_id'    => $performanceId,   //公演ID
        'schedule_id'       => null,           //default
        'ticket_kbn'        => \Config::get('constant.pickup_method.qrpass_sms'), //チケット区分1:電子チケット
        'thumbnail'         => '',  //サムネイル todo logopathの設定
        'free_word'         => $json,   //自由表示欄
        'update_account_cd' => $accountCd,       //更新担当者コード
      );
      
      $layout = $this->EvenManageRepositories->updateOrCreateTicketLayout((!empty($qrpass->id)) ? $qrpass->id : null, $layout);
      $ticketViewData[0]->data[0]->qrpass[0]->id = $layout->layout_id;
    }
    
    //set ibom default message for all stages
    if ($ibon && (!isset($ibon->status) || $ibon->status != 'N')) {

      $free_word = array(
        'title'    => $ibon->title,
        'content'  => $ibon->content,
      );
  
      $json = json_encode($free_word);
     
      $layout = array(
        'performance_id'    => $performanceId,   //公演ID
        'schedule_id'       => null,           //default
        'ticket_kbn'        => \Config::get('constant.pickup_method.ibon'),//チケット区分1:電子チケット
        'thumbnail'         => '',  //サムネイル todo logopathの設定
        'free_word'         => $json,   //自由表示欄
        'update_account_cd' => $accountCd,       //更新担当者コード
      );
      $layout = $this->EvenManageRepositories->updateOrCreateTicketLayout((!empty($ibon->id)) ? $ibon->id : null, $layout);
      $ticketViewData[0]->data[0]->ibon[0]->id = $layout->layout_id;
    }
    
    //set sevenEleven default message for all stages
    if ($sevenEleven && (!isset($sevenEleven->status) || $sevenEleven->status != 'N')) {
      $free_word = array(
        'template'   => strval($sevenEleven->template),
        'title'      => $sevenEleven->title,
        'message_1'  => $sevenEleven->message1,
        'message_2'  => $sevenEleven->message2,
      );
  
      $json = json_encode($free_word);
      
      $layout = array(
        'performance_id'    => $performanceId,   //公演ID
        'schedule_id'       => null,           //default
        'ticket_kbn'        => \Config::get('constant.pickup_method.store'),//チケット区分3:SEJ
        'thumbnail'         => '',  //サムネイル todo logopathの設定
        'free_word'         => $json,   //自由表示欄
        'update_account_cd' => $accountCd,       //更新担当者コード
      );
      $layout = $this->EvenManageRepositories->updateOrCreateTicketLayout((!empty($sevenEleven->id)) ? $sevenEleven->id : null, $layout);
      $ticketViewData[0]->data[0]->sevenEleven[0]->id = $layout->layout_id;
    }

    $ticketViewData[0]->data[0]->mobapass[0]->status    = 'N';
    if($qrpass)
      $ticketViewData[0]->data[0]->qrpass[0]->status      = 'N';
    if($ibon)
      $ticketViewData[0]->data[0]->ibon[0]->status        = 'N';
    if($sevenEleven)
      $ticketViewData[0]->data[0]->sevenEleven[0]->status = 'N';
    
    return $ticketViewData;
  }


  /**
   * schedule information insert or update 
   *
   * @return $result
   */
  private function _saveTimeData($performanceId, $performance_st_dt, $performance_end_dt, $accountCd, $data, &$scheduleArr, $ticketViewData)
  {
    try{
      //if sch_kbn is changed, clear all schedule data.
      $change_sch_kbn = false;
      $current_PD = $this->EvenManageRepositories->getPerformance($performanceId);
      if ($current_PD->sch_kbn && ($current_PD->sch_kbn != ($data->status[0]->status === 'spec') ? 0 : 1)) {
        Log::debug('_saveTimeData : sch_kbn is changed, clear all schedule data.');
        //GL_NONRESERVED_STOCK delete
        $this->EvenManageRepositories->deleteNonreservedStockByPID($performanceId);
        //GL_TICKETLAYOUT - delete not default message
        $this->EvenManageRepositories->deleteDistinctTicketLayoutbyPID($performanceId);
        //GL_SCHEDULE delete
        $this->EvenManageRepositories->dateScheduleDeleteByPerformanceId($performanceId);
        //GL_STAGENAME delete
        $this->EvenManageRepositories->stageNameDelete($performanceId);
        $change_sch_kbn = true;
      }

      $view = $ticketViewData[0]->data[0]->mobapass[0];
      // $stagenm_cnt = $this->EvenManageRepositories->stagenameDataCount($performanceId);
      if ($data->status[0]->status == 'spec') {
        $ruleList = $data->specDate;
        //特定スケジュール不要
        $stageName = array(
          'performance_id' => $performanceId,
          'stage_num' => 0,
          'stage_name' => isset($ruleList[0]->specEvenTitle)?$ruleList[0]->specEvenTitle:'',
          'status' => (empty($ruleList[0]->specEvenStatus) ? 0 : $ruleList[0]->specEvenStatus),
          'account_cd' => $accountCd,
          'description' => '',
        );

        $stcd = $this->EvenManageRepositories->stageNameUpdateOrCreate(isset($ruleList[0]->stcd) ? $ruleList[0]->stcd : null, $stageName);
        // todo : [07/08][James] Write the stcd back to json
        $data->specDate[0]->stcd = $stcd;

        //GL_SCHEDULE insert 開催期間のformだけレコードを挿入する
        $start = new DateTime($performance_end_dt); // 開催期間to

        $schedule = array(
          'performance_id' => $performanceId,
          'performance_date' => $start->format('Y/m/d'),
          'start_time' => $ruleList[0]->specDate,
          'disp_performance_date' => (isset($ruleList[0]->specTitle) ? $ruleList[0]->specTitle : ''),
          'sch_kbn' => '0',
          'stcd' => $stcd,
          'update_account_cd' => $accountCd,
        );
        $sch_data = $this->EvenManageRepositories->specDateScheduleCreateorUpdate(isset($ruleList[0]->specDateId) ? $ruleList[0]->specDateId : null, $schedule);
        $scheduleArr[] = $sch_data;
        // todo : [07/08][James] Write the schedule id back to json -> 7/25 James : ok
        $data->specDate[0]->specDateId = $sch_data->schedule_id;
      } else if ($data->status[0]->status == 'normal') {
        $stgeCdArr = array();
        //    $stageNum = 1;
        //スケジュール設定の場合      
        foreach ($data->ruleList as $ruleListArr => $rule) {
          //GL_STAGENAME insert or update
          $stageName = array(
            'performance_id' => $performanceId,
            'stage_num' => $rule->id,
            'stage_name' => $rule->title,
            'status' => (empty($rule->status) ? 0 : $rule->status),
            'account_cd' => $accountCd,
            'description' => '',
          );
          $stcd = $this->EvenManageRepositories->stageNameUpdateOrCreate(isset($rule->stcd) ? $rule->stcd : null, $stageName);
          $stgeCdArr[$rule->id] = $stcd;
          // todo : [07/08][James] Write the stcd back to json -> 7/25 James : ok
          $data->ruleList[$ruleListArr]->stcd = $stcd;
        }

        //GL_SCHEDULE  設定されたレコードをUpdate
        foreach ($data->calenderDate as $cDate => $detail) {
          foreach ($detail->date->rule as $shcData => $sch) {
            if($sch->status == 'DD'){
              continue;
            }
            if ($sch->status != 'D') {
              if (empty($sch->time))
                continue;

              $schedule = array(
                'performance_id' => $performanceId,
                'performance_date' => $sch->date,
                'start_time' => $sch->time,
                'disp_performance_date' => $sch->title,
                'sch_kbn' => '1',
                'stcd' => $stgeCdArr[$sch->id],
                'update_account_cd' => $accountCd,
              );
              $sch_data = $this->EvenManageRepositories->specDateScheduleCreateorUpdate(isset($sch->dbId) ? $sch->dbId : null, $schedule);
              // todo : [07/08][James] Write the schedule id back to json
              $scheduleArr[] = $sch_data;
              $data->calenderDate[$cDate]->date->rule[$shcData]->dbId = $sch_data->schedule_id;
              $data->calenderDate[$cDate]->date->rule[$shcData]->status = 'N';


              //GL_TICKET_LAYOUT insert 個別日付 mobapass
              if (!isset($sch->ticketMsm->phone->status) || $sch->ticketMsm->phone->status == 'U' || $sch->ticketMsm->phone->status == 'I' || empty($sch->ticketMsm->phone->status) || $change_sch_kbn) {
                if (!empty($sch->ticketMsm->phone->msm)) {
                  $free_word = array(
                    'title'    => '',
                    'content'  => $sch->ticketMsm->phone->msm,
                  );
              
                  $json = json_encode($free_word);

                  $layout = array(
                    'performance_id'    => $performanceId,              //公演ID
                    'schedule_id'       => $sch_data->schedule_id,      //公演日程ID
                    'ticket_kbn'        => \Config::get('constant.pickup_method.eticket'), //チケット区分1:電子チケット
                    'thumbnail'         => $view->logoPath,             //サムネイル 
                    'free_word'         => $json, //自由表示欄
                    'update_account_cd' => $accountCd,                  //更新担当者コード
                  );
                  $ticketLayout = $this->EvenManageRepositories->updateOrCreateTicketLayout(isset($sch->ticketMsm->phone->layoutId) ? $sch->ticketMsm->phone->layoutId : null, $layout);
                  // todo : [07/08][James] Write the layout id back to json  
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->phone->layoutId = $ticketLayout->layout_id;
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->phone->status = 'N';
                } else if (!empty($sch->ticketMsm->phone->layoutId)) {
                  // delete this message
                  $this->EvenManageRepositories->deleteTicketLayoutbyID($sch->ticketMsm->phone->layoutId);
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->phone->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->phone->status = 'N';
                }
              }
              //GL_TICKET_LAYOUT insert 個別日付 qrpass
              if (!isset($sch->ticketMsm->qrpass->status) || $sch->ticketMsm->qrpass->status == 'U' || $sch->ticketMsm->qrpass->status == 'I' || empty($sch->ticketMsm->qrpass->status) || $change_sch_kbn) {
                if (!empty($sch->ticketMsm->qrpass->msm)) {
                  $free_word = array(
                    'title'    => $sch->ticketMsm->qrpass->title,
                    'content'  => $sch->ticketMsm->qrpass->msm,
                  );
              
                  $json = json_encode($free_word);

                  $layout = array(
                    'performance_id'    => $performanceId,              //公演ID
                    'schedule_id'       => $sch_data->schedule_id,      //公演日程ID
                    'ticket_kbn'        => \Config::get('constant.pickup_method.qrpass_sms'),//チケット区分1:電子チケット
                    'thumbnail'         => '',             //サムネイル 
                    'free_word'         => $json, //自由表示欄
                    'update_account_cd' => $accountCd,                  //更新担当者コード
                  ); 
                  $ticketLayout = $this->EvenManageRepositories->updateOrCreateTicketLayout(isset($sch->ticketMsm->qrpass->layoutId) ? $sch->ticketMsm->qrpass->layoutId : null, $layout);
                  // todo : [07/08][James] Write the layout id back to json  
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->qrpass->layoutId = $ticketLayout->layout_id;
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->qrpass->status = 'N';
                } else if (!empty($sch->ticketMsm->qrpass->layoutId)) {
                  // delete this message
                  $this->EvenManageRepositories->deleteTicketLayoutbyID($sch->ticketMsm->qrpass->layoutId);
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->qrpass->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->qrpass->status = 'N';
                }
              }

              //GL_TICKET_LAYOUT insert 個別日付 ibon
              if (!isset($sch->ticketMsm->ibon->status) || $sch->ticketMsm->ibon->status == 'U' || $sch->ticketMsm->ibon->status == 'I' || empty($sch->ticketMsm->ibon->status) || $change_sch_kbn) {
                if (!empty($sch->ticketMsm->ibon->msm)) {
                  $free_word = array(
                    'memo1'  => $sch->ticketMsm->ibon->title,
                    'memo2'  => $sch->ticketMsm->ibon->msm,
                  );
              
                  $json = json_encode($free_word);

                  $layout = array(
                    'performance_id'    => $performanceId,              //公演ID
                    'schedule_id'       => $sch_data->schedule_id,      //公演日程ID
                    'ticket_kbn'        => \Config::get('constant.pickup_method.ibon'),//チケット区分1:電子チケット
                    'thumbnail'         => '',             //サムネイル 
                    'free_word'         => $json, //自由表示欄
                    'update_account_cd' => $accountCd,                  //更新担当者コード
                  ); 
                  $ticketLayout = $this->EvenManageRepositories->updateOrCreateTicketLayout(isset($sch->ticketMsm->ibon->layoutId) ? $sch->ticketMsm->ibon->layoutId : null, $layout);
                  // todo : [07/08][James] Write the layout id back to json  
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->ibon->layoutId = $ticketLayout->layout_id;
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->ibon->status = 'N';
                } else if (!empty($sch->ticketMsm->ibon->layoutId)) {
                  // delete this message
                  $this->EvenManageRepositories->deleteTicketLayoutbyID($sch->ticketMsm->ibon->layoutId);
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->ibon->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->ibon->status = 'N';
                }
              }

              //GL_TICKET_LAYOUT insert 個別日付 日本 7-11
              if (!isset($sch->ticketMsm->sevenEleven->status) || $sch->ticketMsm->sevenEleven->status == 'U' || $sch->ticketMsm->sevenEleven->status == 'I' || empty($sch->ticketMsm->sevenEleven->status) || $change_sch_kbn) {
                if (!empty($sch->ticketMsm->sevenEleven->message1)) {
                  $free_word = array(
                    'template'     => strval($ticketViewData[0]->data[0]->sevenEleven[0]->template),
                    'title'        => $ticketViewData[0]->data[0]->sevenEleven[0]->title,
                    'message_1'    => $sch->ticketMsm->sevenEleven->message1,
                    'message_2'    => $sch->ticketMsm->sevenEleven->message2,
                  );
                
                  $json = json_encode($free_word);

                  $layout = array(
                    'performance_id'    => $performanceId,              //公演ID
                    'schedule_id'       => $sch_data->schedule_id,      //公演日程ID
                    'ticket_kbn'        => \Config::get('constant.pickup_method.store'),//チケット区分3:SEJ
                    'thumbnail'         => '',             //サムネイル 
                    'free_word'         => $json, //自由表示欄
                    'update_account_cd' => $accountCd,                  //更新担当者コード
                  ); 
                  $ticketLayout = $this->EvenManageRepositories->updateOrCreateTicketLayout(isset($sch->ticketMsm->sevenEleven->layoutId) ? $sch->ticketMsm->sevenEleven->layoutId : null, $layout);
                  // todo : [07/08][James] Write the layout id back to json  
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->sevenEleven->layoutId = $ticketLayout->layout_id;
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->sevenEleven->status = 'N';
                } else if (!empty($sch->ticketMsm->sevenEleven->layoutId)) {
                  // delete this message
                  $this->EvenManageRepositories->deleteTicketLayoutbyID($sch->ticketMsm->sevenEleven->layoutId);
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->sevenEleven->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->sevenEleven->status   = 'N';
                }
              }
            } else {
              if (isset($sch->dbId) && !empty($sch->dbId)) {
                $this->EvenManageRepositories->delStageSeatBySchedule($sch->dbId);
                $this->EvenManageRepositories->deleteNonreservedStockBySchedule($sch->dbId);
                $this->EvenManageRepositories->deleteDistinctTicketLayoutbySchedule($sch->dbId);

                if (isset($sch->ticketMsm->phone->layoutId) && !empty($sch->ticketMsm->phone->layoutId)) {
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->phone->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->phone->status   = 'N';
                }
                  
                if (isset($sch->ticketMsm->qrpass->layoutId) && !empty($sch->ticketMsm->qrpass->layoutId)) {
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->qrpass->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->qrpass->status   = 'N';
                }
                  
                if (isset($sch->ticketMsm->ibon->layoutId) && !empty($sch->ticketMsm->ibon->layoutId)) {
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->ibon->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->ibon->status   = 'N';
                }

                if (isset($sch->ticketMsm->sevenEleven->layoutId) && !empty($sch->ticketMsm->sevenEleven->layoutId)) {
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->sevenEleven->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->sevenEleven->status   = 'N';
                }
                  
                if (isset($sch->layoutCashId) && !empty($sch->layoutCashId)) {
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->layoutCashId->layoutId = '';
                  $data->calenderDate[$cDate]->date->rule[$shcData]->ticketMsm->layoutCashId->status   = 'N';
                }
                  
                $this->EvenManageRepositories->dateScheduleDelete($sch->dbId);
              }
              
            
              $data->calenderDate[$cDate]->date->rule[$shcData]->dbId = '';
              $data->calenderDate[$cDate]->date->rule[$shcData]->status = 'DD';
            }
          }
        }
      } 
      return $data;
    } catch(Exception $e){
      throw new Exception ($e);
    }
  }
  /**
   * 
   */
  public function eventUploadImage(Request $request)
  {
    $postData = $request->all();
    $location = $postData['location'];
    $type = $postData['type'];

    try {
      $file = $request->file('file');
      list($w, $h, $filetype) = getimagesize($file);
      $mime = image_type_to_mime_type($filetype);
      
      if(!($mime == 'image/jpeg' ||$mime == 'image/png'))
      {
        Log::debug('eventUploadImage - file type error.');
        throw new Exception('eventUploadImage - file type error');
      }
      $name =  $this->randnum() . date("dYhmm") . '.' . $file->getClientOriginalExtension();
      // $destinationPath = '/public/editor-image/'.$location.'/';
      $destinationPath = '/public/event-data/' . $location .'/'. $type;
      $path =  Storage::putFileAs($destinationPath, $file, $name);
      // $url = '/storage/block-image/'.$location.'/'.$name;
      $url = Storage::url($path);

      return response()->json([
        'uploaded' => true,
        'url' => $url
      ]);
    } catch (Exception $e) {
      return response()->json(
        [
          'uploaded' => false,
          'error' => [
            'message' => session('GLID') . $e->getMessage()
          ]
        ]
      );
    }
  }
  /**
   * 
   */
  public function blockImageUpload(Request $request)
  {
    $postData = $request->all();
    $location = $postData['location'];

    try {
      $file = $request->file('file');
      $name =  $this->randnum() . date("dYhmm") . '.' . $file->getClientOriginalExtension();
      // $destinationPath = '/public/editor-image/'.$location.'/';
      $destinationPath = '/public/block-image/' . $location;
      $path =  Storage::putFileAs($destinationPath, $file, $name);
      // $url = '/storage/block-image/'.$location.'/'.$name;
      $url = Storage::url($path);

      return response()->json([
        'uploaded' => true,
        'url' => $url
      ]);
    } catch (Exception $e) {
      return response()->json(
        [
          'uploaded' => false,
          'error' => [
            'message' => session('GLID') . $e->getMessage()
          ]
        ]
      );
    }
  }
  /**
   * upload editor image 
   * 
   */
  public function editorImangeUpload(Request $request)
  {
    $postData = $request->all();
    $location = $postData['location'];
  
    try {
      if(!$request->hasFile('upload') || !$request->file('upload')->isValid()){
        throw new Exception(trans('events.S_eventImageExceptionArticle'));
      }

      $file = $request->file('upload');
    
      if($file->getSize() > 2097152){
        throw new Exception(trans('events.S_eventImageNoticeArticle'));
      }
    
      $name =  $this->randnum() . date("dYhmm") . '.' . $file->getClientOriginalExtension();
      $destinationPath = '/public/event-data/' . $location;
      $path =  Storage::putFileAs($destinationPath, $file, $name);
      $url = config('app.url').Storage::url($path);

      $result = array(
        "result" => true,
        "url" => $url,
      );

      return $result;

    }catch (Exception $e) {
      Log::debug('editorImangeUpload'.$e->getMessage());

      $result = array(
        "result" => false,
        "message" => $e->getMessage(),
      );

      return $result;
    }
  }

  private function getPerformanceDispStatus($value, $starDate, $anoounce_date)
  {
    
    $event_publishable = session('event_publishable');

    switch($value["status"]) {
      //中止
      case \Config::get('constant.performance_status.cancel'):  
        $disp_status = \Config::get('constant.performance_disp_status.cancel');
      break;
 
      //削除
      case \Config::get('constant.performance_status.delete') : 
        $disp_status = \Config::get('constant.performance_disp_status.deleted');
      break;

      //登録中
      case \Config::get('constant.performance_status.going') : 
        $disp_status = \Config::get('constant.performance_disp_status.going');
      break;

      //登録済
      case \Config::get('constant.performance_status.complete'): 
        $disp_status = \Config::get('constant.performance_disp_status.complete');
      break;

      //表示可
      case \Config::get('constant.performance_status.browse'): 
        // $status = ($value["trans_flg"] == '0')? '表示可' : '表示中';     
        if($event_publishable)
          $disp_status = ($value["trans_flg"] == \Config::get('constant.GETTIIS_trans.yet')) ? \Config::get('constant.performance_disp_status.browse') : \Config::get('constant.performance_disp_status.public');
        else
          $disp_status = \Config::get('constant.performance_disp_status.complete'); 
      break;

      //販売可
      case \Config::get('constant.performance_status.sale') : 
        if($event_publishable) {
          if(empty($anoounce_date)){
            $now = Carbon::now();
          }else{
            $now = Carbon::parse($anoounce_date);
          }
         
          if($now->gt(Carbon::parse($starDate)) && $value["trans_flg"] > \Config::get('constant.GETTIIS_trans.yet')) {
          //処理日 <= 販売開始日 且つ 連携フラグON    
            $disp_status = \Config::get('constant.performance_disp_status.saling');
            // if ($now >= strtotime($value["performance_st_dt"])) {
            if($now->gt(Carbon::parse($value["performance_st_dt"]." 00:00:00"))) {
              //処理日 >= 公演開始日
              // $status = '期間中';     
              $disp_status = \Config::get('constant.performance_disp_status.ongoing');
            }
            // if ($now >= strtotime($value["performance_end_dt"])) {
            if($now->gt(Carbon::parse($value["performance_end_dt"]." 23:59:59"))) {
              //処理日 >= 公演終了日
              // $status = '終了';  
              $disp_status = \Config::get('constant.performance_disp_status.close');
            }
          }
          else {
            $disp_status = \Config::get('constant.performance_disp_status.sale');
          }  
        }
        else {
          $disp_status = \Config::get('constant.performance_disp_status.complete'); 
        }
      break;

      //unknow
      default :
        $disp_status = \Config::get('constant.performance_disp_status.unkonw');
      break;
    }

    return $disp_status;
  }
  /**
   * performance status trans to disp status
   * @param $json
   * @param $performance_status
   * @param $trans_flg
   * @return $display_status
   */
  private function getDisplayStatus($json, $performance_status, $trans_flg){
    $event_publishable = session('event_publishable');
    $performance_data = json_decode($json);
    $basis_data = $performance_data->basisData;
    $performance_start = $basis_data->performance_st_dt;
    $performance_end = $basis_data->performance_end_dt;
    $now_date = strtotime("now");
    $performance_sale_start = null;
    
    $early_date_start = isset($basis_data->earlyBirdDateStart) ? $basis_data->earlyBirdDateStart : ""; //先行from
    $normal_date_start    = isset($basis_data->normalDateStart) ? $basis_data->normalDateStart : "";    //一般from
    if (!empty($normal_date_start)) $performance_sale_start = $normal_date_start;
    if (!empty($early_date_start)) $performance_sale_start = $early_date_start;
   
    switch($performance_status) {
      //中止
      case \Config::get('constant.performance_status.cancel'):  
        $display_status = \Config::get('constant.performance_disp_status.cancel');
        break;
      //削除
      case \Config::get('constant.performance_status.delete') : 
        $display_status = \Config::get('constant.performance_disp_status.deleted');
        break;
      //登録中
      case \Config::get('constant.performance_status.going') : 
        $display_status = \Config::get('constant.performance_disp_status.going');
        break;
      //登録済
      case \Config::get('constant.performance_status.complete'): 
        $display_status = \Config::get('constant.performance_disp_status.complete');
        break;
      //表示可
      case \Config::get('constant.performance_status.browse'):     
        if($event_publishable){
          $display_status = ($trans_flg == \Config::get('constant.GETTIIS_trans.yet')) ? \Config::get('constant.performance_disp_status.browse') : \Config::get('constant.performance_disp_status.public');
        }else{
          $display_status = \Config::get('constant.performance_disp_status.complete'); 
        }
        break;
      //販売可
      case \Config::get('constant.performance_status.sale') : 
        if($event_publishable) {
          if ($now_date >= strtotime($performance_sale_start) &&  $trans_flg > \Config::get('constant.GETTIIS_trans.yet')) {
          //処理日 <= 販売開始日 且つ 連携フラグON    
            $display_status = \Config::get('constant.performance_disp_status.saling');
            if(Carbon::now()->gt(Carbon::parse($performance_start))){
              //処理日 >= 公演開始日
              // $status = '期間中';     
              $display_status = \Config::get('constant.performance_disp_status.ongoing');
            }
            if(Carbon::now()->gt(Carbon::parse($performance_end))){
              //処理日 >= 公演終了日
              // $status = '終了';  
              $display_status = \Config::get('constant.performance_disp_status.close');
            }
          }else{
            $display_status = \Config::get('constant.performance_disp_status.sale');
          }  
        }else{
          $display_status = \Config::get('constant.performance_disp_status.complete'); 
        }
        break;
      //unknow
      default :
        $display_status = \Config::get('constant.performance_disp_status.unkonw');
        break;
    }
    ;
    return $display_status;
  }
  protected function processPerformanceData($value)
  { 
    $starDate = "";
    $closeDate = "";
    $earlystartDate = "";
    $normalstartDate = "";
    $earlycloseDate = "";
    $normalcloseDate = "";
    $trans_flg = \Config::get('constant.GETTIIS_trans.yet');
    $disp_status = $value["status"];
    $sale_type = $value["sale_type"];

    $filter_data = array(
      'performance_id' => $value["performance_id"]
    );
   
    if($value['trans_flg'] == 0){
      // $temporary_info  = $value['draft']['draft_info'];
      $temporary_info  = $value['draft'] !== null? $value['draft']['draft_info'] : null;
    }else{
      $temporary_info = $value['temporary_info'];
    }
  
    // $draft_json = json_decode($value['draft']['draft_info']);
     $draft_json = isset($value['draft']['draft_info']) ? json_decode($value['draft']['draft_info']) : null; 
    $draft_sale_flg = isset($draft_json->basisData->sale_type)?$draft_json->basisData->sale_type:0;

    $salesTerm = collect($value['sales_term']);
   
    if ($value["status"] === \Config::get('constant.performance_status.going') || 
        $value["trans_flg"] !== \Config::get('constant.GETTIIS_trans.already') ||
        $salesTerm->isEmpty()){

      //対象の公演が登録中の場合
      $tt = json_decode($temporary_info);
      $temporary_info = isset($tt->basisData) ? $tt->basisData : "";

      $performance_name   = isset($temporary_info->eventTitle) ? $temporary_info->eventTitle : "";   //公演名  
      $performance_st_dt  = isset($temporary_info->performance_st_dt) ? $temporary_info->performance_st_dt : "";  //開催期間from
      $performance_end_dt = isset($temporary_info->performance_end_dt) ? $temporary_info->performance_end_dt : ""; //開催期間to
      $disp_start         = isset($temporary_info->infOpenDate) ? $temporary_info->infOpenDate : ""; //情報公開開始
      $hall_disp_name     = isset($temporary_info->locationName) ? $temporary_info->locationName : ""; //会場表示名
     
      //販売期間
      $earlyBirdDateStart = isset($temporary_info->earlyBirdDateStart) ? $temporary_info->earlyBirdDateStart : ""; //先行from
      $normalDateStart    = isset($temporary_info->normalDateStart) ? $temporary_info->normalDateStart : "";    //一般from
      if (!empty($normalDateStart)) $starDate = $normalDateStart;
      if (!empty($earlyBirdDateStart)) $starDate = $earlyBirdDateStart;

      $earlyBirdDateEnd = isset($temporary_info->earlyBirdDateEnd) ? $temporary_info->earlyBirdDateEnd : ""; //先行to
      $normalDateEnd    = isset($temporary_info->normalDateEnd) ? $temporary_info->normalDateEnd : "";    //一般to
      if (!empty($earlyBirdDateStart)) $closeDate = $earlyBirdDateEnd;
      if (!empty($normalDateStart)) $closeDate = $normalDateEnd;
   
    } else {
      //対象の公演が登録済以降の場合
      $performance_name   = $value["performance_name"];   //公演名
      $performance_st_dt  = $value["performance_st_dt"];  //開催期間from
      $performance_end_dt = $value["performance_end_dt"]; //開催期間to
      $disp_start         = $value["disp_start"];         //情報公開開始
      $hall_disp_name     = $value["hall_disp_name"];     //会場表示名

      foreach ($salesTerm as $salsInfo) {
     
        if ($salsInfo['sales_kbn'] ===  \Config::get('constant.ticket_sales_kbn.early')) {
          if($salsInfo['treat_flg']){
            //先行販売
            $earlystartDate = date('Y/m/d',  strtotime($salsInfo['reserve_st_date'])) . ' ' . $salsInfo['reserve_st_time'];
            $earlycloseDate = date('Y/m/d',  strtotime($salsInfo['reserve_cl_date'])) . ' ' . $salsInfo['reserve_cl_time'];
          }
        } else if ($salsInfo['sales_kbn'] ===  \Config::get('constant.ticket_sales_kbn.normal')) {
          if($salsInfo['treat_flg']){
            //一般販売
            $normalstartDate = date('Y/m/d',  strtotime($salsInfo['reserve_st_date'])) . ' ' . $salsInfo['reserve_st_time'];
            $normalcloseDate = date('Y/m/d',  strtotime($salsInfo['reserve_cl_date'])) . ' ' . $salsInfo['reserve_cl_time'];
          }
        }
      }   
      //販売期間 from
      $starDate = empty($earlystartDate) ? $normalstartDate : $earlystartDate;
      $closeDate = empty($normalcloseDate) ? $earlycloseDate : $normalcloseDate;
    }

    $stop_button_show = false;
    //是否呈現活動終止按鈕
    if($value["status"] > \Config::get('constant.performance_status.browse') && $value["trans_flg"] > \Config::get('constant.performance_status.yet') ){
      $stop_button_show = true;
    }
    
    //ステータスチェック
    $disp_status = $this->getPerformanceDispStatus($value, $starDate, null);
    
    $data = array(
      "user_code"        => $value["user_code"],
      "performance_id"        => $value["performance_id"],
      "performance_code"      => $value["performance_code"],
      "performance_status"    => $value["status"],
      "status"                => $disp_status,
      "performance_name"      => $performance_name,
      "performance_name_sub"  => $value["performance_name_sub"],
      "performance_st_dt"     => $performance_st_dt,
      "performance_end_dt"    => $performance_end_dt,
      "disp_start"            => $disp_start,
      "trans_flg"             => $value["trans_flg"],
      "hall_disp_name"        => $hall_disp_name,
      "performance_code"      => $value["performance_code"],
      "reserve_st_date"       => $starDate,
      "reserve_cl_date"       => $closeDate,
      "dataStar"              => 1,
      "dataEnd"               => 5,
      "edit_status"           => $value["edit_status"],
      "stop_button_show"      => $stop_button_show,
      'sale_type'             => $sale_type,
      'draft_sale_flg'        =>  $draft_sale_flg,
    );

    return $data;
  }

  /**
   * get performance data for index list show
   * 
   */
  public function performanceList($page)
  {
    $GLID = session('GLID');
    $admin_flg = session('admin_flg');
    $account_cd = session('account_cd');
    $listData = [];
    $statusHad = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0');

    $filterData = array(
      'GLID'      => $GLID,
      'admin_flg' => $admin_flg,
    );
    
    $preformanceData = $this->EvenManageRepositories->getPerformanceList($filterData);
    
    if(session()->exists('performance_delete_result')){
        $performance_delete_result  = true;
        $performance_delete_msn     = session('performance_delete_result');
        session()->forget('performance_delete_result');
    }else{
        $performance_delete_result = false;
        $performance_delete_msn    = '';
    }

    //再發佈信息
    if(session()->exists('republish')){
      $performance_republish_result  = true;
      $performance_republish_msn     = session('republish');
      session()->forget('republish');
    }else{
        $performance_republish_result = false;
        $performance_republish_msn    = '';
    }
  
    //新增信息
    if(session()->exists('performance_add_result')){
      $performance_add_result  = true;
      $performance_add_msn     = session('performance_add_result');
      session()->forget('performance_add_result');
    }else{
      $performance_add_result = false;
      $performance_add_msn    = '';
    }

    foreach ($preformanceData as $value) {
        $data = $this->processPerformanceData($value);
        array_push($listData, $data);
    }
    $totalEvent = count($listData);
    $nowPageStar = ($page - 1) * self::PAGE_SIZE;
    $nowPageEnd = self::PAGE_SIZE;
    $pageData = array_slice($listData, $nowPageStar, $nowPageEnd);
    $paginator = new LengthAwarePaginator($pageData, $totalEvent, self::PAGE_SIZE);
    $paginator->withPath("events");
  
    $result = array(
      'data'                          => $pageData,
      'keyword'                       => null,
      'filterStatus'                  => $statusHad,
      'paginator'                     => $paginator,
      'performance_delete_result'     => $performance_delete_result,
      'performance_delete_msn'        => $performance_delete_msn, 
      'performance_republish_result'  => $performance_republish_result,
      'performance_republish_msn'     => json_encode($performance_republish_msn), 
      'performance_add_result'        => $performance_add_result,
      'performance_add_msn'           => json_encode($performance_add_msn), 
      'data_json'                     => json_encode($pageData),
    );
   
    return $result;
  }
  /**
   * get performance data for index list show
   * 
   */
  public function performanceFilterList(array $request, $page)
  {
    $GLID = session('GLID');
    $admin_flg = session('admin_flg');
    $account_cd = session('account_cd');
    $listData = [];
    $statusHad = array('1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
    $url = 'filter?keyword=' . $request['keyword'];

    //if($request['keyword'] || isset($request["statusSelect"])) {
      if(isset($request['keyword'])){
        $keyword = $request['keyword'];
      }else{
        $keyword = null;
      }

      if(isset($request['statusSelect'])){
        $statusSelect = $request['statusSelect'];
      }else{
        $statusSelect = null;
      }

      $filterData = array(
        'GLID'            => $GLID,
        'admin_flg'       => $admin_flg,
        'keyword'         => $keyword,
        'status_select'   => $statusSelect,
      );

      $preformanceData = $this->EvenManageRepositories->getPerformanceFilter($filterData);
   // }else{
   //   return $result = array(
   //     'user_cd' => $user_cd,
   //     'data' => null,
   //     'keyword' =>  $request['keyword'],
   //     'filterStatus' => $statusHad,
   //     'paginator' => null,
   //  );
   // }

    foreach ($preformanceData as $value) {
      if ($value["status"] !=  \Config::get('constant.performance_status.delete') || !$admin_flg) {
        $data = $this->processPerformanceData($value);
        if(isset($statusSelect))
        {
          if($data["status"] == -1){
            if(!in_array(9, $statusSelect))continue; //add by Defect #819
          }else{
            if(!in_array($data["status"], $statusSelect))continue; //add by Defect #819
          }
        }
        array_push($listData, $data);
      }
    }

    if(isset($request["statusSelect"])){
      foreach ($request["statusSelect"] as $value) {
        $url .= '&statusSelect[]=' . $value;
      }
    }

    $totalEvent = count($listData);
    $nowPageStar = ($page - 1) * self::PAGE_SIZE;
    $nowPageEnd = self::PAGE_SIZE;
    $pageData = array_slice($listData, $nowPageStar, $nowPageEnd);
    $paginator = new LengthAwarePaginator($pageData, $totalEvent, self::PAGE_SIZE);
    $paginator->withPath($url);

    if(isset($request["statusSelect"])){
      foreach ($request["statusSelect"] as $value) {
        $statusHad[$value] = '0';
      }
    }

    $result = array(
      'data'                          => $pageData,
      'keyword'                       =>  $request['keyword'],
      'filterStatus'                  => $statusHad,
      'paginator'                     => $paginator,
      'performance_delete_result'     => false,
      'performance_delete_msn'        => '', 
      'performance_republish_result'  => false,
      'performance_republish_msn'     => '', 
      'data_json'                     => json_encode($pageData),
    );

    return $result;
  }
  /**
   * get performance data for index list show
   * 
   */
  public function performanceListForReport(array $request)
  {
    $admin_flg = 1; //1で固定
    $date = explode("-", $request['date']); 
    $filterData = array(
        'GLID'      => $request['GLID'],
        'admin_flg' => $admin_flg,
        'startdt'   =>  date("yy/m/d" ,strtotime(trim($date[0]))),
        'enddt'     =>  date("yy/m/d" ,strtotime(trim($date[1]))),
        'keyword'   =>  null,
    );

    $preformanceData = $this->EvenManageRepositories->getPerformanceFilter($filterData);

    return $preformanceData;
  } 
  /**
   * setting reserve ticket
   *
   * @return $result
   */
  private function _reserveTicketSetting($performanceId, $account_cd, $data, &$reserveArr, $data_old = null)
  {
    //[TODO] James 07/31 : 記號不分大小寫，前端需檢查
    foreach ($data as $key => $value) {
    //   if ($value->ticketStatus === "N") continue;
      if ($value->ticketStatus === "D") {
        //削除
        if(isset($value->ticketCode)){
          //GL_STAGE_SEAT delete  
          $this->EvenManageRepositories->deleteStageSeatByReserveCode($value->ticketCode);
          //GL_SEAT delete  
          $this->EvenManageRepositories->deleteSeatByReserveCode($value->ticketCode);
          //GL_RESERVE delete  
          $result = $this->EvenManageRepositories->reserveDelete($value->ticketCode);
          //unset($data[$key]);
          $data[$key]->ticketCode = NULL;
        }
        continue;
      }
      if (empty($value->ticketName) || empty($value->ticketText) || empty($value->ticketColor)) continue;
      $id = $this->EvenManageRepositories->reserveUpdateOrCreate($performanceId, $account_cd,  $value);
      $reserveArr[$value->ticketName] =  $id;
      $data[$key]->ticketCode = $id;
      $data[$key]->ticketStatus = 'N';
    }
    return $data;
  }
  /**
   * setting ticket
   *
   * @return $result
   */
  private function _ticketSetting($performanceId, $account_cd, $data, &$seatClassInfo, &$nonResevedSeat_id, $scheduleArr, $mapData, $ticketData_old,$time_data,$isRepublic)
  {
    //[TODO] James 07/30 : settingType 切換時的處理
    $ticketData = $data->data;
    $num = 0;
    $del = [];
    $seatDelNum = [];
    if ($data->settingType === 'freeSeat') {
      if (!isset($data->data->sdbid) || ($data->data->sdbid == 0)||($data->data->sdbid == "")) {
        //新規
        //全席自由の場合
        //GL_SEAT_CLASS insert
        $seatId = $this->EvenManageRepositories->seatClassUpdateOrCreate(
          \Config::get('constant.seat_class_kbn.unreserved'),
          \Config::get('constant.next_seat_flg.off'),
          $performanceId,
          $account_cd,
          $ticketData
        );
        $seatClassInfo[$ticketData->seatName] =  $seatId;
        $data->data->sdbid = $seatId;
        //GL_NONRESERVED_STOCK insert
        $this->_nonReservervedArrUpdateOrCreate(
          $scheduleArr,
          $seatId,
          $data->data->seatQty,
          $account_cd
        );
        //未公開單場設定
        $stageList = array();
        if(isset($time_data->calenderDate) && !empty($time_data->calenderDate)) {
          foreach($time_data->calenderDate as $stageDate) {
            foreach($stageDate->date->rule as $_stage) {
              $stageList[$stageDate->date->dateValue][$_stage->id] = $_stage->dbId;
            }
          }  
        }
        $_respectiveArr = array();
        if(isset($data->data->respectiveData) && !empty($data->data->respectiveData)) {
          foreach($data->data->respectiveData as $stageset) {
            if(isset($stageList[$stageset->dateValue][$stageset->ruleId])) {
              $_respectiveArr[$stageList[$stageset->dateValue][$stageset->ruleId]] = $stageset->total;
            }
          }
        }
        foreach($scheduleArr as $schedule) {
          if(array_key_exists($schedule->schedule_id,$_respectiveArr)){
            $this->_nonReservervedUpdateOrCreate(
              $schedule,
              $seatId,
              $_respectiveArr[$schedule->schedule_id],
              $account_cd
            );
          }
        }
      } else {
        //更新
        $seatClassInfo[$ticketData->seatName] = $data->data->sdbid;
        //変更有の場合
        //GL_SEAT_CLASS update
        $seatId = $this->EvenManageRepositories->seatClassUpdate(
          $ticketData,
          $account_cd
        );

        //上限枚数に変更がある場合
        //GL_NONRESERVED_STOCK update
        // $this->_nonReservervedArrUpdateOrCreate(
        //   $scheduleArr,
        //   $seatId,
        //   $data->data->seatQty,
        //   $account_cd
        // );

        // if now setting seatTotal != old setting seatTotal => update
        foreach($scheduleArr as $schedule) {
          $origNonReserv = $this->EvenManageRepositories->getNonreservedBySchedulenSeatClass($schedule->schedule_id, $seatId);
          $oldstock = null;
          $oldstock = $ticketData_old->seatQty;
          // if DB != old setting use db stock else use new setting
          if(isset($oldstock) && isset($origNonReserv->stock_limit) && $oldstock != $origNonReserv->stock_limit) { 
            $seatTotal = $origNonReserv->stock_limit;
          }
          else {
            $seatTotal = $data->seatQty;
          }
          $this->_nonReservervedUpdateOrCreate(
            $schedule,
            $seatId,
            $seatTotal,
            $account_cd
          );
        }
      }
      $seatId = $data->data->sdbid;
      $data->data->seatStatus = 'N';

      //券種、料金insert
      $ret = $this->_makeRecordTicketClass_Price($seatId, $account_cd, $ticketData->data, (!empty($ticketData_old) ? $ticketData_old->data : null));
      $data->data->data = $ret;
    } else if ($data->settingType === "selectSeat") {
      //自由/指定の場合
      foreach ($ticketData as $key => $SeatClassArr) {
        $seatId = null;
        $SeatClassArrOld = null;
       
        if ($SeatClassArr->seatStatus === 'D') {
          if(isset($SeatClassArr->sdbid)){
            //削除の場合
            //GL_STAGE_SEAT delete   
            $this->EvenManageRepositories->updateStageSeatBySeatClass($SeatClassArr->sdbid);

            foreach ($SeatClassArr->data as $TicketClass) {
              //delete GL_PRICE,GL_TICKET_CLASS 
              $this->_deletePriceTicketclassByTicketClassId($TicketClass);
            }
            //GL_SEAT delete  
            $this->EvenManageRepositories->deleteSeatBySeatClass($SeatClassArr->sdbid);
            //GL_GL_NONRESERVED_STOCK
            $this->EvenManageRepositories->deleteNonreservedStockBySeatClassId($SeatClassArr->sdbid);
            //GL_SEAT_CLASS delete
            $this->EvenManageRepositories->seatClassDelete($SeatClassArr->sdbid);

            $ticketData[$key]->sdbid = NULL;
          }
          //unset($ticketData[$key]);
         
          continue;
        }
        $isUpdate = false;
        if (!isset($SeatClassArr->sdbid) || ($SeatClassArr->sdbid == 0))
          $isUpdate = true;
        //GL_SEAT_CLASS update or insert
        $seatId = $this->EvenManageRepositories->seatClassUpdateOrCreate(
          (!empty($SeatClassArr->seatFree)) ? \Config::get('constant.seat_class_kbn.unreserved') : \Config::get('constant.seat_class_kbn.reserved'),
          (!empty($SeatClassArr->seatNextSeat)) ? \Config::get('constant.next_seat_flg.on') : \Config::get('constant.next_seat_flg.off'),
          $performanceId,
          $account_cd,
          $SeatClassArr
        );

        $seatClassInfo[$SeatClassArr->seatName] =  $seatId;
        $data->data[$key]->sdbid = $seatId;
        if ($SeatClassArr->seatFree) {
          //自由席の場合
          //GL_NONRESERVED_STOCK insert
          if($isRepublic){
            // if now setting seatTotal != old setting seatTotal => update
            foreach($scheduleArr as $schedule) {
              $origNonReserv = $this->EvenManageRepositories->getNonreservedBySchedulenSeatClass($schedule->schedule_id, $seatId);
              $oldstock = null;
              foreach($ticketData_old->data as $oldSetting) {
                if(isset($oldSetting->sdbid) && $oldSetting->sdbid == $SeatClassArr->sdbid) {
                  $oldstock = $oldSetting->seatTotal;
                  break;
                }
              }
              // if DB != old setting use db stock else use new setting
              if(isset($oldstock) && isset($origNonReserv->stock_limit) && $oldstock != $origNonReserv->stock_limit) { 
                $seatTotal = $origNonReserv->stock_limit;
              }
              else {
                $seatTotal = $SeatClassArr->seatTotal;
              }
              $this->_nonReservervedUpdateOrCreate(
                $schedule,
                $seatId,
                $seatTotal,
                $account_cd
              );
            }
          }
          else {
            $stageList = array();
            if(isset($time_data->calenderDate) && !empty($time_data->calenderDate)) {
              foreach($time_data->calenderDate as $stageDate) {
                foreach($stageDate->date->rule as $_stage) {
                  $stageList[$stageDate->date->dateValue][$_stage->id] = $_stage->dbId;
                }
              }  
            }
            $_respectiveArr = array();
            if(isset($SeatClassArr->respectiveData) && !empty($SeatClassArr->respectiveData)) {
              foreach($SeatClassArr->respectiveData as $stageset) {
                if(isset($stageList[$stageset->dateValue][$stageset->ruleId])) {
                  $_respectiveArr[$stageList[$stageset->dateValue][$stageset->ruleId]] = $stageset->total;
                }
              }
            }
            foreach($scheduleArr as $schedule) {
              if(array_key_exists($schedule->schedule_id,$_respectiveArr)){
                $this->_nonReservervedUpdateOrCreate(
                  $schedule,
                  $seatId,
                  $_respectiveArr[$schedule->schedule_id],
                  $account_cd
                );
              }
              else {
                $this->_nonReservervedUpdateOrCreate(
                  $schedule,
                  $seatId,
                  $SeatClassArr->seatTotal,
                  $account_cd
                );    
              }
            }
          }
          if (!empty($SeatClassArr->seatFree)) $nonResevedSeat_id = $seatId; //自由席の場合、seat_class_id保持
          if ($isUpdate)
            $this->EvenManageRepositories->updateStageSeatBySeatClass($SeatClassArr->seatid);
        } else {
          //指定
          if ($isUpdate)
            $this->EvenManageRepositories->deleteNonreservedStockBySeatClassId($seatId);
        }
        $data->data[$key]->seatStatus = 'N';
        //席種。券種、料金insert
        if (!empty($seatId))
          $this->_makeRecordTicketClass_Price($seatId, $account_cd, $SeatClassArr->data, $SeatClassArrOld);
      }
      $data->data = $ticketData;
    }

    foreach ($data->data as $content) {
      foreach ($del as $value) {
        array_splice($content->data, $value, 1);
      }
    }

    foreach ($seatDelNum as $value) {
      array_splice($data->data, $value, 1);
    }
    $data->seatStatus = 'N';
    return $data;
  }
  /**
   * inser GL_NONRESERVED_STOCK
   *
   * @return none
   */
  private function _nonReservervedArrUpdateOrCreate($scheduleArr, $seatId, $seatQty, $account_cd)
  {
    foreach ($scheduleArr as $schdule) {
      $nonreserve = array(
        'schedule_id'      => $schdule->schedule_id,  //スケジュールid
        'seat_class_id'    => $seatId,        //席種id
        'stock_limit'      => $seatQty, //在庫上限（会場・座席配置設定画面で設定した自由席分の席数）
        'current_num'      => 0,              //整理券採番
        'update_account_cd' => $account_cd     //更新担当者コード
      );
      $this->EvenManageRepositories->nonReservervedUpdateOrCreate($nonreserve);
    }
  }
    /**
   * inser GL_NONRESERVED_STOCK
   *
   * @return none
   */
  private function _nonReservervedUpdateOrCreate($schedule, $seatId, $seatQty, $account_cd)
  {
      $nonreserve = array(
        'schedule_id'      => $schedule->schedule_id,  //スケジュールid
        'seat_class_id'    => $seatId,        //席種id
        'stock_limit'      => $seatQty, //在庫上限（会場・座席配置設定画面で設定した自由席分の席数）
        'current_num'      => 0,              //整理券採番
        'update_account_cd' => $account_cd     //更新担当者コード
      );
      $this->EvenManageRepositories->nonReservervedUpdateOrCreate($nonreserve);
  }
  /**
   * inser/update/delete GL_SEAT_CLASS and GL_PRICE
   *
   * @return none
   */
  private function _makeRecordTicketClass_Price($seatId, $account_cd, $TicketClassArr, $TicketClassArr_old = null)
  {
    foreach ($TicketClassArr as $key => $TicketClass) {
      //券種情報  
      $ticketInfo = array(
        "seat_class_id"   => $seatId,
        "ticket_class_name" => $TicketClass->ticketName,
        "disp_order" => $key+1,
        "update_account_cd" => $account_cd,
      );
      
      if ($TicketClass->ticketStatus === 'I') {
        //新規
        if (!empty($TicketClass->ticketEarlyBird) && $TicketClass->ticketEarlyBird) {
          //先行
          //GL_TICKET_CLASS insert
          $ticketId = $this->EvenManageRepositories->ticketInsert((object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.early'));
          //GL_PRICE insert
          $this->EvenManageRepositories->ticketPriceupdateOrCreate($ticketId, $TicketClass->ticketPrice, $account_cd);
          $TicketClass->ticketEarlyBirdId = $ticketId;
        }
        if (!empty($TicketClass->ticketNormal) && $TicketClass->ticketNormal) {
          //一般
          //GL_TICKET_CLASS insert
          $ticketId = $this->EvenManageRepositories->ticketInsert((object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.normal'));
          //GL_PRICE insert
          $this->EvenManageRepositories->ticketPriceupdateOrCreate($ticketId, $TicketClass->ticketPrice, $account_cd);
          $TicketClass->ticketNormalId = $ticketId;
        }
        if (!empty($TicketClass->ticketOnSite) && $TicketClass->ticketOnSite) {
          //当日
          //GL_TICKET_CLASS insert
          $ticketId = $this->EvenManageRepositories->ticketInsert((object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.onsite'));
          //GL_PRICE insert
          $this->EvenManageRepositories->ticketPriceupdateOrCreate($ticketId, $TicketClass->ticketPrice, $account_cd);
          $TicketClass->ticketOnSiteId = $ticketId;
        }
        $TicketClass->ticketStatus = 'N';
      } else if ($TicketClass->ticketStatus === 'U') {
        //更新
        //先行
        if (!empty($TicketClass->ticketEarlyBird) && $TicketClass->ticketEarlyBird) {
          //先行 enable
          // Log::debug('update 先行 enable');
          if (($tid = $TicketClass->ticketEarlyBirdId) <= 0) {
            // Log::debug('OFF->ON');
            //OFF->ON
            //GL_TICKET_CLASS insert
            $tid = $this->EvenManageRepositories->ticketInsert((object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.early'));
            //GL_PRICE insert
            $this->EvenManageRepositories->ticketPriceupdateOrCreate($tid, $TicketClass->ticketPrice, $account_cd);
            $TicketClass->ticketEarlyBirdId = $tid;
          } else {
            // Log::debug('ON->ON');
            $tid = $TicketClass->ticketEarlyBirdId;
            $this->EvenManageRepositories->ticketUpdate($tid, (object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.early'));
            $this->EvenManageRepositories->ticketPriceupdateOrCreate($tid, $TicketClass->ticketPrice, $account_cd);
          }
        } else {
          //先行 disable
          Log::debug('update 先行 disable');
          if (($tid = $TicketClass->ticketEarlyBirdId) > 0) {
            // Log::debug('ON->OFF');
            //ON->OFF
            $this->EvenManageRepositories->ticketChangeDelFlg($tid);
            //GL_PRICE delete
            //24-12-2020 v6 欄位開發修改 刪除 -》 修改flg
            //$this->EvenManageRepositories->ticketPriceDelete($tid);
            //GL_TICKET_CLASS delete
            //$this->EvenManageRepositories->ticketDelete($tid);
            //$TicketClass->ticketEarlyBirdId = 0;
          }
        }
      
        //一般
        if (!empty($TicketClass->ticketNormal) && $TicketClass->ticketNormal) {
          //一般 enable
          // Log::debug('update 一般 enable');
          if (($tid = $TicketClass->ticketNormalId) <= 0) {
            // Log::debug('OFF->ON');
            //OFF->ON
            //GL_TICKET_CLASS insert
            $tid = $this->EvenManageRepositories->ticketInsert((object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.normal'));
            //GL_PRICE insert
            $this->EvenManageRepositories->ticketPriceupdateOrCreate($tid, $TicketClass->ticketPrice, $account_cd);
            $TicketClass->ticketNormalId = $tid;
          } else {
            // Log::debug('ON->ON');
            //ON->ON
            $this->EvenManageRepositories->ticketUpdate($tid, (object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.normal'));
            $this->EvenManageRepositories->ticketPriceupdateOrCreate($tid, $TicketClass->ticketPrice, $account_cd);
          }
        } else {
          //一般 disable
          // Log::debug('update 一般 disable');
          if (($tid = $TicketClass->ticketNormalId) > 0) {
            $this->EvenManageRepositories->ticketChangeDelFlg($tid);
            // Log::debug('ON->OFF');
            //ON->OFF
            //GL_PRICE delete
            // $this->EvenManageRepositories->ticketPriceDelete($tid);
            //GL_TICKET_CLASS delete
            // $this->EvenManageRepositories->ticketDelete($tid);
            // $TicketClass->ticketEarlyBirdId = 0;
          }
        }

        //当日
        if (!empty($TicketClass->ticketOnSite) && $TicketClass->ticketOnSite) {
          //当日 enable
          // Log::debug('update 当日 enable');
          if (($tid = $TicketClass->ticketOnSiteId) <= 0) {
            // Log::debug('OFF->ON');
            //OFF->ON
            //GL_TICKET_CLASS insert
            $tid = $this->EvenManageRepositories->ticketInsert((object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.onsite'));
            //GL_PRICE insert
            $this->EvenManageRepositories->ticketPriceupdateOrCreate($tid, $TicketClass->ticketPrice, $account_cd);
            $TicketClass->ticketOnSiteId = $tid;
          } else {
            // Log::debug('ON->ON');
            //ON->ON
            $this->EvenManageRepositories->ticketUpdate($tid, (object) $ticketInfo, \Config::get('constant.ticket_sales_kbn.onsite'));
            $this->EvenManageRepositories->ticketPriceupdateOrCreate($tid, $TicketClass->ticketPrice, $account_cd);
          }
        } else {
          //当日 disable
          // Log::debug('update 当日 disable');
          if (($tid = $TicketClass->ticketOnSiteId) > 0) {
            // Log::debug('ON->OFF');
            //ON->OFF
            $this->EvenManageRepositories->ticketChangeDelFlg($tid);
            //GL_PRICE delete
            //$this->EvenManageRepositories->ticketPriceDelete($tid);
            //GL_TICKET_CLASS delete
            // $this->EvenManageRepositories->ticketDelete($tid);
            // $TicketClass->ticketEarlyBirdId = 0;
          }
        }
        $TicketClass->ticketStatus = 'N';
      } else if ($TicketClass->ticketStatus === 'D') {
        //削除
        $this->_deletePriceTicketclassByTicketClassId($TicketClass);
        unset($TicketClassArr[$key]);
      }
    }
    return array_values($TicketClassArr);
  }
  /**
   * setting ticket view data
   *
   * @return $result
   */
  private function _deletePriceTicketclassByTicketClassId($TicketClass)
  {
    //先行
    //GL_PRICE delete 
    $this->EvenManageRepositories->ticketPriceDelete($TicketClass->ticketEarlyBirdId);
    //GL_TICKET_CLASS delete 
    $this->EvenManageRepositories->ticketDelete($TicketClass->ticketEarlyBirdId);

    //一般
    //GL_PRICE delete 
    $this->EvenManageRepositories->ticketPriceDelete($TicketClass->ticketNormalId);
    //GL_TICKET_CLASS delete 
    $this->EvenManageRepositories->ticketDelete($TicketClass->ticketNormalId);

    //当日
    //GL_PRICE delete 
    $this->EvenManageRepositories->ticketPriceDelete($TicketClass->ticketOnSiteId);
    //GL_TICKET_CLASS delete 
    $this->EvenManageRepositories->ticketDelete($TicketClass->ticketOnSiteId);
  }
  /**
   * setting ticket view data
   *
   * @return $result
   */
  public function ticketViewCreaditSetting($eventInf, $ticketViewData)
  {
    $data = $ticketViewData->data[0]->creditPay[0];

    if ($data->cheacked) {
      if ($data->status == "U") {
        $this->EvenManageRepositories->updateTicketLayout($eventInf, $data, NULL, 1, 0);

        $ticketViewData->data[0]->creditPay[0]->status = 'N';
      } else if ($data->status == "I") {
        $id = $this->EvenManageRepositories->insertTicketLayout($eventInf, $data, NULL, 1, 0);
        $ticketViewData->data[0]->creditPay[0]->id = $id;

        $ticketViewData->data[0]->creditPay[0]->status = 'N';
      }
    } else {
      if ($data->id) {
        $id = $this->EvenManageRepositories->deleteTicketLayout($data);
        $ticketViewData->data[0]->creditPay[0]->id = '0';

        $ticketViewData->data[0]->creditPay[0]->status = 'N';
      }
    }

    return $ticketViewData;
  }
  /**
   * setting ticket view data
   *
   * @return $result
   */
  public function  specTicketViewCreaditSetting($eventInf, $ticketViewData, $caleder)
  {
    // 07/25 James : Not used yet?
    $data = $ticketViewData->data[0]->cashPay[0];
    $calederData = $caleder->calenderDate;
    $numParent = 0;

    if ($data->cheacked) {
      foreach ($calederData as $value) {
        $numChild = 0;
        if ($value->date->hadEvens) {
          foreach ($value->date->rule as $inf) {
            if (!$inf->layoutCreditId && $value->date->layoutContent) {
              $id = $this->EvenManageRepositories->insertSpecTicketLayout($eventInf, $value->date->layoutContent, $inf->dbId, 1, 0);
              $calederData[$numParent]->date->rule[$numChild]->layoutCreditId = $id;
            } else {
              $this->EvenManageRepositories->updateSpecTicketLayout($eventInf, $value->date->layoutContent, $inf->dbId, 1, 0);
            }
            $numChild++;
          }
        }
        $numParent++;
      }
    } else {
      foreach ($calederData as $value) {
        $numChild = 0;
        if ($value->date->hadEvens) {
          foreach ($value->date->rule as $inf) {
            if ($inf->layoutCreditId) {
              $this->EvenManageRepositories->deleteTicketLayout($inf->layoutCreditId);
              $calederData[$numParent]->date->layoutContent = '';
              $calederData[$numParent]->date->rule[$numChild]->layoutCreditId = '';
            }
          }
        }
        $numParent++;
      }
    }

    return $calederData;
  }
  /**
   * setting ticket cash view data
   *
   * @return $result
   */
  public function  specTicketViewCashSetting($eventInf, $ticketViewData, $caleder)
  {
    $data = $ticketViewData->data[0]->creditPay[0];
    $calederData = $caleder->calenderDate;
    $numParent = 0;

    if ($data->cheacked) {
      foreach ($calederData as $value) {
        $numChild = 0;
        if ($value->date->hadEvens) {
          foreach ($value->date->rule as $inf) {
            if (!$inf->layoutCashId && $value->date->layoutCashContent) {
              $id = $this->EvenManageRepositories->insertSpecTicketLayout($eventInf, $value->date->layoutCashContent, $inf->dbId, 1, 0);
              $calederData[$numParent]->date->rule[$numChild]->layoutCashId = $id;
            } else {
              $this->EvenManageRepositories->updateSpecTicketLayout($eventInf, $value->date->layoutCashContent, $inf->dbId, 1, 0);
            }
            $numChild++;
          }
        }
        $numParent++;
      }
    } else {
      foreach ($calederData as $value) {
        $numChild = 0;
        if ($value->date->hadEvens) {
          foreach ($value->date->rule as $inf) {
            if ($inf->layoutCashId) {
              $this->EvenManageRepositories->deleteTicketLayout($inf->layoutCashId);
              $calederData[$numParent]->date->layoutCashContent = '';
              $calederData[$numParent]->date->rule[$numChild]->layoutCashId = '';
            }
          }
        }
        $numParent++;
      }
    }

    return $calederData;
  }
  /**
   * setting ticket view data
   *
   * @return $result
   */
  public function ticketViewCashSetting($eventInf, $ticketViewData, $pathTicketLogo)
  {

    $data = $ticketViewData->data[0]->cashPay[0];

    if ($pathTicketLogo) {
      $ticketViewData->data[0]->cashPay[0]->thumbnail = $pathTicketLogo;
    }

    if (!$pathTicketLogo && $ticketViewData->data[0]->cashPay[0]->thumbnail) {
      $pathTicketLogo = $ticketViewData->data[0]->cashPay[0]->thumbnail;
    }

    if (!$pathTicketLogo && !$ticketViewData->data[0]->cashPay[0]->thumbnail) {
      $pathTicketLogo = '0';
    }

    if ($data->cheacked) {
      if ($data->status == "U") {
        $this->EvenManageRepositories->updateTicketLayout($eventInf, $data, NULL, 1, $pathTicketLogo);

        $ticketViewData->data[0]->cashPay[0]->status = 'N';
      } else if ($data->status == "I") {
        $id = $this->EvenManageRepositories->insertTicketLayout($eventInf, $data, NULL, 1, $pathTicketLogo);
        $ticketViewData->data[0]->cashPay[0]->id = $id;

        $ticketViewData->data[0]->cashPay[0]->status = 'N';
      }
    } else {
      if ($data->id) {
        $id = $this->EvenManageRepositories->deleteTicketLayout($data);
        $ticketViewData->data[0]->cashPay[0]->id = '0';

        $ticketViewData->data[0]->cashPay[0]->status = 'N';
      }
    }

    return $ticketViewData;
  }
  /**
   * 未公開活動資料更新資料庫
   * 
   * @param  $transmit_data
   * @return 
   */
  // public function updateUndisclosedEvent($transmit_data)
  // { 
  //   //新規イベント作成
  //   $GLID             = session('GLID');
  //   $account_cd       = session('account_cd');
  //   $performance_id   = $transmit_data['performance_id'];
  //   $seatMapProfileId = null;
  //   $filter_data = array(
  //     'performance_id'  => $performance_id,
  //   );
   
  //   $get_temporary_info = $this->EvenManageRepositories->getTemporaryInfo($filter_data);
  //   $get_draft          = $this->EvenManageRepositories->getDraft($filter_data);
    
  //   $performance_status = $get_temporary_info['status'];
  //   $temporary_info     = $get_draft['draft_info'];
   
  //   $data           = json_decode($temporary_info);
  //   $settingData    = $data->basisData;        //基本情報
  //   $timeData       = $data->timeSetting;         //時間・回数情報
  //   $ticketData     = $data->ticketSeeting;       //席種・券種情報
  //   $sellData       = $data->sellSetting;         //販売条件       
  //   $ticketViewData = $data->ticketViewData;      //チケットレイアウト
  //   $mapData        = $data->mapData;          //座席配置図
  //   $floorData      = $mapData[0]->data[0]->mapData;
    
  //   if ($settingData->contentType === 'image') {
  //     $contentType = '1';
  //     $contentUrl = $settingData->contentImage;
  //   } else {
  //     $contentType = '2';
  //     $contentUrl = $settingData->contentVidioUrl;
  //   }

  //   if($performance_status > \Config::get('constant.performance_status.browse')) {
  //     //公演ステータス販売可以降に以下の処理を行う。
    
  //   //公演ステータス登録中以降に以下の処理を行う。
  //   $t_ret = $this->_saveDefaultTicketMSG($performance_id, $ticketViewData, $account_cd);
  //   $ticketViewData = $t_ret;

  //   //時間・階数情報、チケットレイアウト
  //   $scheduleArr = array(); //schedule_idのリスト        
  //   $date_ret = $this->_saveTimeData($performance_id, $settingData->performance_st_dt, $settingData->performance_end_dt, $account_cd, $timeData, $scheduleArr, $ticketViewData);
  //   $timeData = $date_ret;

  //   //席種・券種、料金
  //   $seatClassArr = array(); //席種名=>seat_class_idのリスト
  //   $nonResevedSeat_id = null; //自由席の席種id
  //   $ticketSetting = $this->_ticketSetting($performance_id, $account_cd, $ticketData->ticketSetting, $seatClassArr, $nonResevedSeat_id, $scheduleArr, $mapData);

  //   //押さえ
  //   $reserveArr = array(); //押さえ名=>reserve_codeのリスト
  //   if (isset($ticketData->specTicketSetting->data)) $this->_reserveTicketSetting($performance_id, $account_cd, $ticketData->specTicketSetting->data, $reserveArr);
    
  //   //販売条件
  //   $this->_createSalesTermInfo($performance_id, $sellData, $settingData, $account_cd);

  //   //座席図情報
  //   if (!empty($settingData->localId) && isset($seatMapProfileId))
  //     $this->_createHallMapInfo($GLID, $performance_id, $account_cd, $settingData, $seatMapProfileId, $nonResevedSeat_id, $mapData, $reserveArr, $seatClassArr);

  //     $t_ret = $this->_saveDefaultTicketMSG($performance_id, $ticketViewData, $account_cd);
  //     $ticketViewData = $t_ret;
  
  //     //時間・階数情報、チケットレイアウト
  //     $scheduleArr = array(); //schedule_idのリスト        
  //     $date_ret = $this->_saveTimeData($performance_id, $settingData->performance_st_dt, $settingData->performance_end_dt, $account_cd, $timeData, $scheduleArr, $ticketViewData);
  //     $timeData = $date_ret;
  
  //     //席種・券種、料金
  //     $seatClassArr = array(); //席種名=>seat_class_idのリスト
  //     $nonResevedSeat_id = null; //自由席の席種id
  //     $ticketSetting = $this->_ticketSetting($performance_id, $account_cd, $ticketData->ticketSetting, $seatClassArr, $nonResevedSeat_id, $scheduleArr, $mapData);
  
  //     //押さえ
  //     $reserveArr = array(); //押さえ名=>reserve_codeのリスト
  //     if (isset($ticketData->specTicketSetting->data)) $this->_reserveTicketSetting($performance_id, $account_cd, $ticketData->specTicketSetting->data, $reserveArr);
      
  //     //販売条件
  //     $this->_createSalesTermInfo($performance_id, $sellData, $settingData, $account_cd);
  
  //     if ($mapData[0]->status === 'I') {
  //       //GL_SEATMAP_PROFILE insert
  //       $seatMapProfileId = $this->_createSeatMapInfo($GLID, $performance_id, $account_cd, $settingData);
  //     }
  
  //     //座席図情報
  //     if (!empty($settingData->localId) && isset($seatMapProfileId))
  //       $this->_createHallMapInfo($GLID, $performance_id, $account_cd, $settingData, $seatMapProfileId, $nonResevedSeat_id, $mapData, $reserveArr, $seatClassArr);
  
  //     /* 基本資料*/
  //     $sellData[0]->sellSetting->buyLimit = (empty($sellData[0]->sellSetting->buyLimit) ? 0 : $sellData[0]->sellSetting->buyLimit); //購入可能累計枚数 補完
  //   }

  //   $basisInf = array(
  //     'eventId'               => $settingData->eventId,//公演コード
  //     'status'                => $performance_status,//公演ステータス
  //     'performance_name'      => $settingData->eventTitle,//公演名
  //     'performance_name_sub'  => $settingData->eventSubTitle,//副題
  //     'sch_kbn'               => $settingData->sch_kbn,//日程区分
  //     'performance_st_dt'     => $settingData->performance_st_dt,//公演開始日
  //     'performance_end_dt'    => $settingData->performance_end_dt,//公演終了日
  //     'hall_code'             => $settingData->localId,//会場コード
  //     'hall_disp_name'        => $settingData->hallName,//会場表示名
  //     'seatmap_profile_cd'    => $seatMapProfileId,//todo 座席配置プロフィール
  //     'disp_start'            => $settingData->infOpenDate,//表示開始日時
  //     'information_nm'        => $settingData->eventContact,//問い合わせ先名
  //     'information_tel'       => $settingData->eventContactTel,//問い合わせ先電話番号
  //     'mail_address'          => $settingData->eventContactMail,//問い合わせ先メールアドレス
  //     'genre_code'            => (empty($settingData->eventType) ? 900 : $settingData->eventType), //ジャンル
  //     'eventUrl'              => $settingData->eventUrl,//公式サイトURL
  //     'top_conten_type'       => $contentType,//TOPコンテンツタイプ
  //     'top_conten_url'        => $contentUrl,//TOPコンテンツURL
  //     'top_content_comment'   => $settingData->contentComment, //TOPコンテンツコメント
  //     'thumbnail'             => $settingData->eventLogo,//サムネイル
  //     'context'               => $settingData->editContent,//公演内容概要
  //     'selection_flg'         => $ticketData->ticketSetting->selection,//座席指定機能利用フラグ
  //     'purchasable_number'    => $sellData[0]->sellSetting->buyLimit,//購入可能累計枚数 
  //     'hall_name'             => $settingData->locationName,
  //     'prefecture'            => $settingData->country,
  //     'city'                  => $settingData->city,
  //     'locationDescription'   => $settingData->locationDescription,
  //     'article'               => $settingData->article,//記事情報
  //     'keywords'              => $settingData->keywords,//keywords
  //     'account_cd'            => $account_cd,
  //     'edit_status'           => \Config::get('constant.edit_status.not'),
  //   );

  //   // Save data to json
  //   $tempinfo = null;
   
  //   //PERFORMANCE LANG 寫入腫瘤科
  //   $en_information = json_decode($settingData->enInformation);
    
  //   $update_data = array(
  //     'lang_id'         => $en_information->data->langId,
  //     'performance_id'  => $performance_id,
  //     'lang_code'       => $en_information->status->lang,
  //     'lang_info'       => $settingData->enInformation,
  //   );
    
  //   $performance_lang = $this->EvenManageRepositories->updatePerformanceLang($update_data);
  //   $en_information->data->langId = $performance_lang['lang_id'];
  //   $settingData->enInformation = json_encode($en_information);

  //   //PERFORMANCE STATUS:登録中
  //   $tempinfo = array(
  //     'basisData' => $settingData,
  //     'timeSetting' => $timeData,
  //     'ticketSeeting' => $ticketData,
  //     'mapData' => $mapData,
  //     'ticketViewData' => $ticketViewData,
  //     'sellSetting' => $sellData,
  //     'status' => $performance_status,
  //   );
    
  //   $basisInf = array_merge($basisInf, array('temporary_info' => json_encode($tempinfo)));

  //   $this->EvenManageRepositories->performanceUpdate($performance_id, $basisInf);
  //   $update_data = array(
  //     'performance_id'    => $performance_id,
  //     'temporary_info'        => json_encode($tempinfo),
  //     'update_account_cd' => $account_cd,
  //     'performance_status' => $performance_status,
  //   );
    
  //   $this->EvenManageRepositories->updateDraft($update_data);
  // }

  /**
   * get all event setting data
   *
   * @return $result
   */
  public function addData($request, $settingData, $timeData, $ticketData, $sellData, $ticketViewData, $mapData, $performancestatus, $ps_ret)
  {
    Log::debug('EvenManageServices.addData()');
    //新規イベント作成
    $GLID = session('GLID');
    $account_cd = session('account_cd');
    
    $eventInf = array(
      'GLID' => $GLID,
      'account_cd' => $account_cd,
    );

    if ($timeData->status[0]->status === 'spec') {
      $settingData->sch_kbn = 0;
    } else {
      $settingData->sch_kbn = 1;
    }

    if ($settingData->contentType === 'image') {
      $contentType = '1';
      $contentUrl = $settingData->contentImage;
    } else {
      $contentType = '2';
      $contentUrl = $settingData->contentVidioUrl;
    }

    $status = array(
      'timeSetting' => $timeData->status
    );

    //GL_HALL insert
    if (empty($settingData->localId) && !empty($settingData->country) & !empty($settingData->city)) {
      //会場が新規入力の場合、会場データ新規作成
      $hall_result= $this->_createHallInfo($settingData, $GLID, $account_cd);
      if($hall_result){
        $settingData->localId = $hall_result['hall_id'];
        $settingData->localStatus = $hall_result['local_status'];
      }

    }

    if(!count($ps_ret['msg']) > 0) {  
      $edit_status = \Config::get('constant.edit_status.complete');
    }else{
      $edit_status = \Config::get('constant.edit_status.going');
    }
    
    /* 基本資料*/
    $langType = self::getLangType($settingData->autoTransZhtw,$settingData->autoTransEng);
    $sellData[0]->sellSetting->buyLimit = (empty($sellData[0]->sellSetting->buyLimit) ? 0 : $sellData[0]->sellSetting->buyLimit); //購入可能累計枚数 補完
    $basisInf = array(
      'eventId'               => $settingData->eventId,                          //公演コード
      'status'                => $performancestatus,                              //公演ステータス
      'paid_status'           => \Config::get('constant.paid_status.none'), //有料ステータス
      'performance_name'      => $settingData->eventTitle, //公演名
      'performance_name_sub'  => $settingData->eventSubTitle, //副題
      'sch_kbn'               => $settingData->sch_kbn,                    //日程区分
      'performance_st_dt'     => ($performancestatus == \Config::get('constant.performance_status.going')) ? '9999/12/31' : $settingData->performance_st_dt,   //公演開始日
      'performance_end_dt'    => ($performancestatus == \Config::get('constant.performance_status.going')) ? '9999/12/31' : $settingData->performance_end_dt, //公演終了日
      'hall_code'             => (empty($settingData->localId)) ? '9999' : $settingData->localId, //会場コード
      'hall_disp_name'        => ($performancestatus == \Config::get('constant.performance_status.going')) ? '*' : $settingData->hallName,         //会場表示名
      'seatmap_profile_cd'    => null,                                                                                                          // 座席配置プロフィール
      'disp_start'            => ($performancestatus == \Config::get('constant.performance_status.going')) ? '9999/12/31' : $settingData->infOpenDate, //表示開始日時
      'information_nm'        => ($performancestatus == \Config::get('constant.performance_status.going')) ? '*' : $settingData->eventContact,     //問い合わせ先名
      'information_tel'       => ($performancestatus == \Config::get('constant.performance_status.going')) ? '*' : $settingData->eventContactTel, //問い合わせ先電話番号
      'mail_address'          => ($performancestatus == \Config::get('constant.performance_status.going')) ? '*' : $settingData->eventContactMail,   //問い合わせ先メールアドレス
      'genre_code'            => (empty($settingData->eventType) ? 900 : $settingData->eventType), //ジャンル
      'eventUrl'              => $settingData->eventUrl,                  //公式サイトURL
      'top_conten_type'       => $contentType,                     //TOPコンテンツタイプ
      'top_conten_url'        => $contentUrl,                       //TOPコンテンツURL
      'top_content_comment'   => $settingData->contentComment, //TOPコンテンツコメント
      'thumbnail'             => $settingData->eventLogo,                              //サムネイル
      'context'               => $settingData->editContent,                //公演内容概要
      'selection_flg'         => $ticketData->ticketSetting->selection, //座席指定機能利用フラグ
      'purchasable_number'    => ($performancestatus == \Config::get('constant.performance_status.going')) ? '-1' : $sellData[0]->sellSetting->buyLimit, //購入可能累計枚数 
      // 'trans_flg'          => ($performancestatus >= \Config::get('constant.performance_status.browse')) ? \Config::get('constant.GETTIIS_trans.need') : \Config::get('constant.GETTIIS_trans.yet'), //GETTIIS連携フラグ
      'trans_flg'             => \Config::get('constant.GETTIIS_trans.yet') ,
      'hall_name'             => ($performancestatus == \Config::get('constant.performance_status.going')) ? '*' : $settingData->locationName,
      'prefecture'            => ($performancestatus == \Config::get('constant.performance_status.going')) ? '*' : $settingData->country,
      'city'                  => ($performancestatus == \Config::get('constant.performance_status.going')) ? '-1' : $settingData->city,
      'locationDescription'   => $settingData->locationDescription,
      'article'               => $settingData->article, //記事情報
      'keywords'              => $settingData->keywords, //keywords
      'edit_status'           => $edit_status,
      'sale_type'             => $settingData->sale_type,
      'autoTransChecked'      => $settingData->autoTransChecked,
      'portlanguage'          => $langType,
      'sell_anyone'           => $settingData->sellAnyone,
    );
    
    $tempinfo = null;
    // [James] 7/17 : Save tempinfo in every update
    // if($performancestatus == \Config::get('constant.performance_status.going'))
    {
      //PERFORMANCE STATUS:登録中
      $tempinfo = array(
        'basisData' => $settingData,
        'timeSetting' => $timeData,
        'ticketSeeting' => $ticketData,
        'mapData' => $mapData,
        'ticketViewData' => $ticketViewData,
        'sellSetting' => $sellData,
        'status' => $status,
      );
    }
    $basisInf = array_merge($basisInf, array('temporary_info' => json_encode($tempinfo)));
    $id = $this->EvenManageRepositories->performanceInsert($eventInf, $basisInf);

    // Note : 2020/05/21 James : No need in this version ?
    // $seatMapProfileId = null;
    // if ($mapData[0]->status === 'I') {
    //   //GL_SEATMAP_PROFILE insert
    //   $seatMapProfileId = $this->_createSeatMapInfo($GLID, $id, $account_cd, $settingData);
    // }

    //update_undisclosed_event 轉換
    if ($performancestatus != \Config::get('constant.performance_status.going')) {

      $date_end = null;
      if(isset($settingData->dateEnd->setFlg) && isset($settingData->dateEnd->date) && $settingData->dateEnd->setFlg == 'EndDate'){
        $date_end = $settingData->dateEnd->date;
      }

       $update_edit_status = array(
        'performance_id' => $id,
        'date_end' => $date_end,
        'status'         => $performancestatus,
        'edit_status'    => $edit_status,
        'autoTransChecked' => $settingData->autoTransChecked,
        'portlanguage' => $langType,
       );
  
       $this->EvenManageRepositories->updateEditStatus($update_edit_status);
     
    }

    $update_data = array(
      'performance_id'      => $id,
      'performance_status'  => $performancestatus, 
      'temporary_info'      => json_encode($tempinfo),
      'update_account_cd'   => $account_cd,
    );

   $this->EvenManageRepositories->updateDraft($update_data);
  
   $info[]   = array(
    'title' => trans('events.S_eventAdd'),
    'msn'   => trans('events.S_eventAddSucceeded'),
  );

  $status  = array(
    'update_status' => true,
    'title_custom'  => true,
    'msn_status'    => \Config::get('constant.message_status.information'),
  );

  $data  = array(
    'msn'   => $info,
    'title' => trans('events.S_eventAdd'),
  );

  $result  = array(
    'status' => $status,
    'data'   => $data,
  );

   return $result;
  }
  /**
   * 更新活動資料
   * 
   * @param $event_data 活動資料
   * @return $result 修改結果
   */
  public function updateJson($event_data)
  {
    Log::debug('[EventManageService]updateJson');
    $GLID = session('GLID');
    $account_cd = session('account_cd');

    $tempinfo = null;
    $performance_id      = $event_data['performanceId'];
    $performance_status  = $event_data['performancestatus'];//現在活動狀態
    //$transFlg            = $event_data['transFlg'];
    $basisData           = $event_data['basisData'];
    $timeSetting         = $event_data['timeSetting'];
    $ticketSeeting       = $event_data['ticketSeeting'];
    $mapData             = $event_data['mapData'];
    $ticketViewData      = $event_data['ticketViewData'];
    $sellSetting         = $event_data['sellSetting'];
    $ps_ret              = $event_data['ps_ret'];
    $forSell = (isset($basisData->sale_type)?$basisData->sale_type:0) == '1';
    $entry_time = Carbon::parse($event_data['entry_time']);
   
    $performance_data = $this->EvenManageRepositories->getPerformance($performance_id);
    
    if($performance_data->status == \Config::get('constant.performance_status.cancel'))
      $performance_status = \Config::get('constant.performance_status.cancel');

    $announce_date = $performance_data['announce_date'];
    $performance_tt = json_decode($performance_data['temporary_info']);
    $performance_temporary_info = isset($performance_tt->basisData) ? $performance_tt->basisData : "";
    $star_date = $this->getStarDate($performance_temporary_info);
    $now_status = $this->getPerformanceDispStatus($performance_data, $star_date, null);
    
    if(!is_null($announce_date) || $now_status >= \Config::get('constant.performance_status.sale')){
      $now_status = $this->getPerformanceDispStatus($performance_data, $star_date, null);
      $trans_status = $this->getPerformanceDispStatus($performance_data, $star_date, $announce_date);

      if($now_status != $trans_status && $entry_time->lt(Carbon::parse($star_date))){
        Log::debug('entry time is over announce date');
        return false;
      }
    }

    if ($timeSetting->status[0]->status === 'spec') {
      $basisData->sch_kbn = 0;
    } else {
      $basisData->sch_kbn = 1;
    }
    
    if ($basisData->contentType === 'image') {
      $contentType = '1';
      $contentUrl = $basisData->contentImage;
    } else {
      $contentType = '2';
      $contentUrl = $basisData->contentVidioUrl;
    }

    $status = array(
      'timeSetting' => $timeSetting->status
    );

    if (!empty($basisData->country) & !empty($basisData->city)) {
      //会場が新規入力の場合、会場データ新規作成
      $hall_result = $this->_createHallInfo($basisData, $GLID, $account_cd);
      if($hall_result){
        $basisData->localId = $hall_result['hall_id'];
        $basisData->localStatus = $hall_result['local_status'];
      }
      Log::debug("_createHallInfo : [" . $basisData->country . "][" . $basisData->city . "] -> ID => " . $basisData->localId);
    }

    /* 基本資料*/
    $sellSetting[0]->sellSetting->buyLimit = (empty($sellSetting[0]->sellSetting->buyLimit) ? 0 : $sellSetting[0]->sellSetting->buyLimit); //購入可能累計枚数 補完
    
    $tempinfo = array(
      'basisData'         => $basisData,
      'timeSetting'       => $timeSetting,
      'ticketSeeting'     => $ticketSeeting,
      'mapData'           => $mapData,
      'ticketViewData'    => $ticketViewData,
      'sellSetting'       => $sellSetting,
      'status'            => $status,
      'fbgCtrl'           => $event_data['fbgCtrl'],
    );

    $update_data = array(
      'performance_id'      => $performance_id,
      'performance_status'  => $performance_status, 
      'temporary_info'      => json_encode($tempinfo),
      'update_account_cd'   => $account_cd,
    );

   //言語区分
   $langType = self::getLangType($basisData->autoTransZhtw,$basisData->autoTransEng);
   
   $edit_status = \Config::get('constant.edit_status.complete');

  //取得 performance 資料庫狀態
  $transFlg         = $performance_data->trans_flg;
  $trans_performance_status = $performance_data->status;
  
  $tt = json_decode($performance_data['temporary_info']);
  $temporary_info = isset($tt->basisData) ? $tt->basisData : "";

  //販売期間
  $earlyBirdDateStart = isset($temporary_info->earlyBirdDateStart) ? $temporary_info->earlyBirdDateStart : ""; //先行from
  $normalDateStart    = isset($temporary_info->normalDateStart) ? $temporary_info->normalDateStart : "";    //一般from
  $starDate           = empty($earlyBirdDateStart) ? $normalDateStart : $earlyBirdDateStart; //活動開始時間

  if($now_status == \Config::get('constant.performance_disp_status.cancel')) {
    $performance_disp_status = \Config::get('constant.performance_disp_status.cancel');
  }
  else {
    $performance_disp_status = $this->getPerformanceDispStatus($performance_data, $starDate, null);
  }

  //活動公開判斷
  if(
    $transFlg > \Config::get('constant.GETTIIS_trans.yet') &&
    $performance_disp_status == \Config::get('constant.performance_disp_status.public') &&
    ($performance_status == \Config::get('constant.performance_status.browse') ||
    $performance_status == \Config::get('constant.performance_status.sale')) &&
    !$forSell && 
    $performance_status > \Config::get('constant.performance_status.browse')
  ){
    $update_data['performance_status'] = \Config::get('constant.performance_status.browse');
  }

  if(!count($ps_ret['msg']) > 0) {  
    $edit_status = \Config::get('constant.edit_status.complete');
  }else{
    $edit_status = \Config::get('constant.edit_status.going');
  }
  
  $date_end = null;
  if(isset($basisData->dateEnd->setFlg) && isset($basisData->dateEnd->date) && $basisData->dateEnd->setFlg == 'EndDate'){
    $date_end = $basisData->dateEnd->date;
  }

  $update_edit_status = array(
    'performance_id' => $performance_id,
    'date_end' => $date_end,
    'edit_status' => $edit_status,
    'autoTransChecked' => $basisData->autoTransChecked,
    'portlanguage' => $langType,
  );
  
  $performance_update_data = array(
    'performance_id'        => $performance_id,                   //公演コード
    'performance_name'      => $basisData->eventTitle,            //公演名
    'performance_name_sub'  => $basisData->eventSubTitle,         //副題
    'sch_kbn'               => $basisData->sch_kbn,               //日程区分
    'performance_st_dt'     => $basisData->performance_st_dt,     //公演開始日
    'performance_end_dt'    => $basisData->performance_end_dt,    //公演終了日
    'disp_start'            => $basisData->infOpenDate,           //表示開始日時
  );

  //如果活動未發布，更新活動狀態
  if($transFlg === \Config::get('constant.GETTIIS_trans.yet')){
    if(!$forSell && $performance_status > \Config::get('constant.performance_status.browse')){
      $update_data['performance_status'] = \Config::get('constant.performance_status.browse');
    }
    $this->EvenManageRepositories->updatePerformanceStatus($update_data);
  }

  if($transFlg !== \Config::get('constant.GETTIIS_trans.already')){
    $this->EvenManageRepositories->updatePerformanceListData($performance_update_data);
  }

  $this->EvenManageRepositories->updateEditStatus($update_edit_status);
  $this->EvenManageRepositories->updateDraft($update_data);
  
  return true;
}
  /**
   * republish 活動資料更新
   * 
   * @param $data
   * @return $result
   */
  public function republish($data){
    Log::debug('[EventManageService] republish');
    try{
      //イベント更新
      $GLID                 = session('GLID');
      $account_cd           = session('account_cd');
      $seat_map_profileId  = null;
      $performance_id       = $data['performance_id'];

      $filter_data  = array(
        'performance_id'  => $performance_id,
      );
      
      $performance           = $this->EvenManageRepositories->getPerformance($performance_id);
      $performance_status    = $performance->status;
      $performance_trans_flg = $performance->trans_flg;

      $check_anoounce_status = $this->checkAnoounceStatus($performance);
      
      if($check_anoounce_status){
        $this->EvenManageRepositories->deleteDraft($performance_id);
        throw new Exception(trans('error.S_EXC_MSN_0009'));
      }
     
      if($performance->edit_status ==  \Config::get('constant.edit_status.going')){
        Log::debug('S_EXC_MSN_0003 - sale_type is constant.edit_status.going');
        throw new Exception(trans('error.S_EXC_MSN_0003'));
      }
      
      $draft = $this->EvenManageRepositories->getDraft($filter_data);
      if(!$draft) {
        Log::debug('S_EXC_MSN_0010 - Draft not exist');
        throw new Exception(trans('error.S_EXC_MSN_0010'));
      }
      
      $draft_json = json_decode($draft['draft_info']);  //draft json 資料
      $draft_status = $draft['new_status'];

      if($performance_status == \Config::get('constant.performance_status.sale') && !$draft_json->basisData->sale_type){
        $performance_update_status = $performance_status;
      }else{
        $performance_update_status = $draft['new_status'];
      }
      
      $basis_data       = $draft_json->basisData;          //基本情報
      $time_data        = $draft_json->timeSetting;        //時間・回数情報
      $ticket_data      = $draft_json->ticketSeeting;      //席種・券種情報
      $sell_data        = $draft_json->sellSetting;        //販売条件    
      $map_data         = $draft_json->mapData; 
      $ticket_view_data = $draft_json->ticketViewData;
      $seat_map_profileId = isset($map_data[0]->seat_profile_cd)?$map_data[0]->seat_profile_cd:null;

      //日本 7-11 如票面活動名稱無資料,將帶入活動名稱(10字)
      if(strlen($ticket_view_data[0]->data[0]->sevenEleven[0]->title) == 0){
        if(strlen($basis_data->eventTitle) > 0){
          $event_title = mb_substr($basis_data->eventTitle, 0, 10, 'UTF-8');
          $ticket_view_data[0]->data[0]->sevenEleven[0]->title = $event_title;
        }
      }
      
      $temporary_info = json_decode($performance['temporary_info']); //performance temporary_info 資料
      $basis_data_old           = $temporary_info->basisData;         //old基本情報
      $ticket_data_old          = $temporary_info->ticketSeeting->ticketSetting;     //old席種・券種情報
      $spec_ticket_setting_old  = $temporary_info->ticketSeeting->specTicketSetting;              //old押さえ
      $sell_data_old            = $temporary_info->sellSetting;       //old販売条件
      $map_data_old             = $temporary_info->mapData;           //old mapdata
      
      if ($time_data->status[0]->status === 'spec') {
        $basis_data->sch_kbn = 0;
      } else {
        $basis_data->sch_kbn = 1;
      }

      if ($basis_data->contentType === 'image') {
        $content_type = '1';
        $content_url = $basis_data->contentImage;
      } else {
        $content_type = '2';
        $content_url = $basis_data->contentVidioUrl;
      }

      $status = array(
        'timeSetting' => $time_data->status
      );

      DB::beginTransaction();
      //GL_HALL insert
      //[TODO] James 07/19 : 這段code會造成hall_code變成1
      //[TODO] Jame 07/24 : This code is fixed?
      if (empty($basis_data->localId) && !empty($basis_data->country) & !empty($basis_data->city)) {
        //会場が新規入力の場合、会場データ新規作成
        $hall_result = $this->_createHallInfo($basis_data, $GLID, $account_cd);
        if($hall_result){
          $basis_data->localId = $hall_result['hall_id'];
          $basis_data->localStatus = $hall_result['local_status'];
        }
        Log::debug("_createHallInfo : [" . $basis_data->country . "][" . $basis_data->city . "] -> ID => " . $basis_data->localId);
      }

      Log::debug('[EventManageService] draft_status -> '.$draft_status);
      if($draft_status > \Config::get('constant.performance_status.browse')) {
        if ($map_data[0]->status === 'I' || ($map_data[0]->seat_profile_cd == null)) {
          $seat_map_profileId_old = $map_data_old[0]->seat_profile_cd;
          if($seat_map_profileId_old) {
            Log::debug('[EventManageService] This event has old map data, need to remove it. ('.$seat_map_profileId_old.')');
            // 10/09 James : This event has old map data, need to remove it.
            //GL_STAGE_SEAT delete  画面より連携されるseat_idで削除
            $this->EvenManageRepositories->deleteStageSeatByPID($performance_id);
            //GL_SEAT delete  画面より連携されるseat_idで削除
            $this->EvenManageRepositories->deleteSeatByPID($performance_id);
          }
          if ($map_data[0]->status === 'I' && count(get_object_vars($map_data[0]->data[0]->mapData)) > 0 ) {
            //新規入力：$mapDataが存在する場合、excel投入有
            //GL_SEATMAP_PROFILE insert
            $seat_map_profileId = $this->_createSeatMapInfo($GLID, $performance_id, $account_cd, $basis_data);
            $map_data[0]->seat_profile_cd = $seat_map_profileId;
            // $seat_map_profileId_old = $seat_map_profileId;
            //Insert new seat information to GL_HALL_SEAT
            $map_data_n = $this->_createHallSeatInfo($map_data, $seat_map_profileId, $account_cd);
            if ($map_data_n){
              $map_data = $map_data_n;
            }
            $map_data[0]->status = 'N';
          }
          
        } else {
          $seat_map_profileId = $map_data[0]->seat_profile_cd;
          $map_data[0]->status = 'N';
        }

        if (isset($ticket_data_old) &&  isset($ticket_data->ticketSetting->settingType) && $ticket_data_old->settingType != $ticket_data->ticketSetting->settingType) {
          Log::debug("Mode change:".$ticket_data_old->settingType ." -> ". $ticket_data->ticketSetting->settingType );
          if($ticket_data_old->settingType === "selectSeat")
          {
            //指定座位／自由座 => 無指定座位／無座位 
            //GL_STAGE_SEAT delete  画面より連携されるseat_idで削除
            $this->EvenManageRepositories->deleteStageSeatByPID($performance_id);
            //GL_SEAT delete  画面より連携されるseat_idで削除
            $this->EvenManageRepositories->deleteSeatByPID($performance_id);
            //GL_RESERVE delete  画面より連携されるreserve_codeで削除
            $this->EvenManageRepositories->deleteReserveByPID($performance_id);
            $seatMapProfileId = null;
          }
          //GL_NONRESERVED_STOCK delete all
          $this->EvenManageRepositories->deleteNonreservedStockByPID($performance_id);
          // clear price, clear ticketclass , clear seatclass 
          $this->EvenManageRepositories->deleteSeatClassDatabyPID($performance_id);
        }
        
        $t_ret = $this->_saveDefaultTicketMSG($performance_id, $ticket_view_data, $account_cd);
        $ticket_view_data = $t_ret;
        //時間・階数情報
        $schedule_arr = array(); //schedule_idのリスト  
        $spec_date = $this->_saveTimeData($performance_id, $basis_data->performance_st_dt, $basis_data->performance_end_dt, $account_cd, $time_data, $schedule_arr, $ticket_view_data);
        $time_data = $spec_date;

        //席種・券種、料金
        $seat_class_arr = array(); //席種名=>seat_class_idのリスト
        $nonResevedSeat_id = null; //自由席の席種id
        $ticket_setting = $this->_ticketSetting($performance_id, $account_cd, $ticket_data->ticketSetting, $seat_class_arr, $nonResevedSeat_id, $schedule_arr, $map_data, $ticket_data_old,$time_data,!is_null($performance->announce_date));
        $ticket_data->ticketSetting = $ticket_setting;

        // //押さえ
        if($ticket_data->ticketSetting->settingType === "selectSeat") {
          $reserve_arr = array(); //押さえ名=>reserve_codeのリスト
          if (isset($ticket_data->specTicketSetting->data)) {
            $ret = $this->_reserveTicketSetting($performance_id, $account_cd, $ticket_data->specTicketSetting->data, $reserve_arr, isset($spec_ticket_setting_old->data)?$spec_ticket_setting_old->data:null);
            $ticket_data->specTicketSetting->data = $ret;
          } 
        }

        //販売条件
        $this->_createSalesTermInfo($performance_id, $sell_data, $basis_data, $account_cd, $basis_data_old, $sell_data_old);

        //座席図情報
        if (!empty($basis_data->localId) && isset($seat_map_profileId) && $ticket_data->ticketSetting->settingType === "selectSeat")
        {
          $ret = $this->_updateSeatAllocInfo($performance_id, $account_cd, $map_data, $reserve_arr, $ticket_data, $time_data, !is_null($performance->announce_date));
          $map_data = $ret;
        }

        //フリーアンケート
        if (property_exists($basis_data, 'questionnaires')) {
          $questionnaires = $this->_createQuestionnaireInfo($performance_id, $account_cd, $basis_data->questionnaires);
          $basis_data->questionnaires = $questionnaires;
        }

        //変更され公演に予約済未キャンセルのデータが存在する場合、モバパス連携フラグoff
        //->定時バッチ再連携
        $order = $this->MobapassOutputRepositories->getMobapassOutputTargetByPerformanceId($performance_id);
        if(!empty($order))
        {
          foreach ($order as $order_info) {
            //GL_GENERAL_RESERVATION update - mobapass_trans_flg
            $this->MobapassOutputRepositories->generalReservationMbpsUpdate($order_info->order_id,\Config::get('constant.mobapass_trans_flg.off'));    
          }
        }

        /* 基本資料*/
        $sell_data[0]->sellSetting->buyLimit = (empty($sell_data[0]->sellSetting->buyLimit) ? 0 : $sell_data[0]->sellSetting->buyLimit); //購入可能累計枚数 補完
      }
      
      //公演ステータスが表示可以降の場合GETTIIS連携フラグ=要連携
      $upd_trans_flg = ($performance_status >= \Config::get('constant.performance_status.browse')) ? \Config::get('constant.GETTIIS_trans.need') : null;
      $basis_inf = array(
        'eventId'                   => $basis_data->eventId,                          //公演コード
        'status'                    => $performance_update_status,                              //公演ステータス
        'performance_name'          => $basis_data->eventTitle, //公演名
        'performance_name_sub'      => $basis_data->eventSubTitle, //副題
        'sch_kbn'                   => $basis_data->sch_kbn,                    //日程区分
        'performance_st_dt'         => ($draft_status == \Config::get('constant.performance_status.going')) ? '9999/12/31' : $basis_data->performance_st_dt,   //公演開始日
        'performance_end_dt'        => ($draft_status == \Config::get('constant.performance_status.going')) ? '9999/12/31' : $basis_data->performance_end_dt, //公演終了日
        'hall_code'                 => ($draft_status == \Config::get('constant.performance_status.going')) ? '9999' : $basis_data->localId,                      //会場コード
        'hall_disp_name'            => ($draft_status == \Config::get('constant.performance_status.going')) ? '*' : $basis_data->hallName,                    //会場表示名
        'seatmap_profile_cd'        => $seat_map_profileId,                                                                                                          //座席配置プロフィール
        'disp_start'                => ($draft_status == \Config::get('constant.performance_status.going')) ? '9999/12/31' : $basis_data->infOpenDate, //表示開始日時
        'information_nm'            => ($draft_status == \Config::get('constant.performance_status.going')) ? '*' : $basis_data->eventContact,     //問い合わせ先名
        'information_tel'           => ($draft_status == \Config::get('constant.performance_status.going')) ? '*' : $basis_data->eventContactTel, //問い合わせ先電話番号
        'mail_address'              => ($draft_status == \Config::get('constant.performance_status.going')) ? '*' : $basis_data->eventContactMail,   //問い合わせ先メールアドレス
        'genre_code'                => (empty($basis_data->eventType) ? 900 : $basis_data->eventType), //ジャンル
        'eventUrl'                  => $basis_data->eventUrl,                  //公式サイトURL
        'top_conten_type'           => $content_type,                     //TOPコンテンツタイプ
        'top_conten_url'            => $content_url,                       //TOPコンテンツURL
        'top_content_comment'       => $basis_data->contentComment, //TOPコンテンツコメント
        'thumbnail'                 => $basis_data->eventLogo,                              //サムネイル
        'context'                   => $basis_data->editContent,                //公演内容概要
        'article'                   => $basis_data->article,                    //記事情報
        'keywords'                  => $basis_data->keywords,                  //keywords
        'selection_flg'             => $ticket_data->ticketSetting->selection, //座席指定機能利用フラグ
        'purchasable_number'        => ($draft_status == \Config::get('constant.performance_status.going')) ? '-1' : $sell_data[0]->sellSetting->buyLimit, //購入可能累計枚数 
        'hall_name'                 => ($draft_status == \Config::get('constant.performance_status.going')) ? '*' : $basis_data->locationName,
        'trans_flg'                 => (!empty($upd_trans_flg)) ? $upd_trans_flg : $performance_trans_flg,//檢查
        'prefecture'                => ($draft_status == \Config::get('constant.performance_status.going')) ? '*' : $basis_data->country,
        'city'                      => ($draft_status == \Config::get('constant.performance_status.going')) ? '-1' : $basis_data->city,
        'locationDescription'       => $basis_data->locationDescription,
        'account_cd'                => $account_cd,
        'edit_status'               => \Config::get('constant.edit_status.not'),
        'sale_type'                 => $basis_data->sale_type,
        'sell_anyone'               => isset($basis_data->sellAnyone)?$basis_data->sellAnyone:false,
      );

      // Save data to json
      $tempinfo = null;
    
      //PERFORMANCE LANG 寫入腫瘤科
      $en_information = json_decode($basis_data->enInformation);
    
      $update_data = array(
        'lang_id'         => $en_information->data->langId,
        'performance_id'  => $performance_id,
        'lang_code'       => $en_information->status->lang,
        'lang_info'       => $basis_data->enInformation,
      );
      
      $performance_lang = $this->EvenManageRepositories->updatePerformanceLang($update_data);

      $tempinfo = array(
        'basisData' => $basis_data,
        'timeSetting' => $time_data,
        'ticketSeeting' => $ticket_data,
        'mapData' => $map_data,
        'ticketViewData' => $ticket_view_data,
        'sellSetting' => $sell_data,
        'status' => $draft_status,
      );

      $basis_inf = array_merge($basis_inf, array('temporary_info' => json_encode($tempinfo)));

      $this->EvenManageRepositories->performanceUpdate($performance_id, $basis_inf);
      $update_data = array(
        'performance_id'    => $performance_id,
        'temporary_info'        => json_encode($tempinfo),
        'update_account_cd' => $account_cd,
        'performance_status' => $draft_status
      );
    
      //$this->EvenManageRepositories->updateDraft($update_data);
      $this->EvenManageRepositories->deleteDraft($performance_id);

      if ($basis_inf['trans_flg'] >=  \Config::get('constant.GETTIIS_trans.need')) {
        //既に連携済の場合、GETTIIS連携CSV出力
        $this->transportPerfomanceInfo($performance_id, $basis_data->eventId, $ticket_view_data[0]->data[0]->mobapass[0]->logoPath);
      }
      
      $info[]   = array(
        'title' => trans('events.S_Send'),
        'msn'   => trans('events.S_SendMsn'),
      );

      $status  = array(
        'update_status' => true,
        'title_custom'  => true,
        'msn_status'    => \Config::get('constant.message_status.information'),
      );

      $data  = array(
        'msn'   => $info,
        'title' => trans('events.S_SendResult'),
      );

      $result  = array(
        'status' => $status,
        'data'   => $data,
      );

      DB::commit();
      return $result;
    }catch (Exception $e) {
      DB::rollback();
      Log::error('[republish]:Exception occurred - '.$e->getMessage());

      $info[]   = array(
        'title' => trans('events.S_SendErrDetailMsn'),
        'msn'   => 'Error Code : '.$e->getMessage(),
      );

      $status  = array(
        'update_status' => false,
        'title_custom'  => true,
        'note_custom'  => true,
        'msn_status'    => \Config::get('constant.message_status.information'),
      );

      $data  = array(
        'msn'   => $info,
        'title' => trans('events.S_SendResult'),
        'note'  => trans('events.S_SendErrMsn'),
      );

      $result  = array(
        'status' => $status,
        'data'   => $data,
      );

      return $result;
    }
  }
  
  public function parseOldJson($performanceId, $json, $readDraftData)
  {
    $GLID     = session('GLID');
    $hallData = $this->EvenManageRepositories->hallGet($GLID);
    $pcstArr  = \Config::get('constant.post');
    $map_had_stage_seat =  $this->EvenManageRepositories->hasStageSeat($performanceId);
    
    foreach ($hallData as $hall_row) {
      $hall_location        = $pcstArr[$hall_row->post_code];
      $hall_row->prefecture = $hall_location[0];
      $hall_row->city       = $hall_location[1];
    }

    $data           = json_decode($json);
    $settingData    = json_decode($data[0]->basisData, true)[0];
    $timeData       = json_decode($data[0]->timeData, true)[0];
    $ticketData     = json_decode($data[0]->ticketData, true)[0];
    $sellData       = json_decode($data[0]->sellData, true);
    $mapData        = json_decode($data[0]->mapData, true);
    $ticketViewData = json_decode($data[0]->ticketView, true);
    $fbgCtrl        = isset($data[0]->fbgCtrl)?$data[0]->fbgCtrl:'fbg';
    
    if ($timeData["status"] === 'spec') {
      $settingData["sch_kbn"] = 0;
    } else {
      $settingData["sch_kbn"] = 1;
    }

    $status = array(
      'timeSetting' => $timeData["status"]
    );


    $eventData = array(
      'basisData'       => $settingData,
      'timeSetting'     => $timeData,
      'ticketSeeting'   => $ticketData,
      'mapData'         => $mapData,
      'ticketViewData'  => $ticketViewData,
      'sellSetting'     => $sellData,
      'status'          => $status,
    );
    
    $location = "";
    $eventJson = [];

    if (isset($eventData['basisData'])) {
      $eventJson = array(
        'status' => time(),
        'data'   => $eventData['basisData'],
      );

      if (isset($eventData["basisData"]["imageLocation"])) {
        $location =  $eventData["basisData"]["imageLocation"];
      } else {
        $location = $eventData["basisData"]["eventId"];
      }
    }

    $pStatus    = null;
    $transFlg   = null;
    $dispStatus = null;
    $edit_status = 0;
    $sale_info = array(
      'has_sale' => false,
      'schedule_info' => [],
      'first_stage_date' => '',
      'last_stage_date' => '',
      'earliest_sale_date' => '',
      'latest_sale_date' => '',
      'seats_info' => false,
      'seatTypeArr' => false
    );


    if ($performanceId) {
      if($value = $this->EvenManageRepositories->getPerformance($performanceId)) {
        $pStatus = $value->status;
        $transFlg = $value->trans_flg;
        $starDate = '';
    
        $filter_data = array(
          'performance_id' => $performanceId
        );
        
        $sale_info = $this->EvenManageRepositories->getSaleInfo();

        // performation 資料
        $performance_data = $this->EvenManageRepositories->getPerformance($performanceId);
        $performance_status = $performance_data['status'];
        $edit_status      = $performance_data['edit_status']; 
        $temporary_info = $performance_data['temporary_info'];
        $transFlg = $performance_data['trans_flg'];
        $performance_dispaly_status = $this->getDisplayStatus($temporary_info, $performance_status, $transFlg);
        
        // draft 資料
        $draft = $this->EvenManageRepositories->getDraft($filter_data);
        $draft_temporary_info  = $draft['draft_info'];
        $draft_status  = $draft['new_status'];
        $draft_dispaly_status = $performance_dispaly_status;
        if($readDraftData) {
          $article = json_decode($temporary_info, true)['basisData']['article'];
          $eventData = json_decode($draft_temporary_info, true);
          $article   = json_decode( $article , true);  
        }
        if($draft){
          $draft_dispaly_status = $this->getDisplayStatus($draft_temporary_info, $draft_status, $transFlg);
        }
        
      }
    }

    if(isset($eventData['mapData'])){
      $map_data = json_encode($eventData['mapData']);
      $is_setting_tikcet = $this->getRespectiveSeat($map_data, $eventData['ticketSeeting']['ticketSetting']);
    }else{
      $map_data = "";
    }

    $retData = array(
      'evenId'                => $location,
      'status'                => ($performanceId) ? 'edit' : 'new2',
      'performanceStatus'     => isset($performance_status) ? $performance_status : 0,
      'performanceDispStatus' => isset($performance_dispaly_status) ? $performance_dispaly_status : 0,
      'draft_status'          => isset($draft_status) ? $draft_status : 0,
      'draft_dispaly_status'  => isset($draft_dispaly_status) ? $draft_dispaly_status : 0,
      'transFlg'              => $transFlg,
      'performanceId'         => $performanceId,
      'hallData'              => $hallData,
      'basisData'             => json_encode($eventJson),
      'timeStatus'            => isset($eventData['timeSetting']['status']) ? $eventData['timeSetting']['status'] : "",
      'timeSetting'           => isset($eventData['timeSetting']) ? json_encode($eventData['timeSetting']) : "",
      'timeTitle'             => isset($eventData['timeSetting']['ruleList']) ? json_encode($eventData['timeSetting']['ruleList']) : "",
      'specDate'              => isset($eventData['timeSetting']['specDate']) ? json_encode($eventData['timeSetting']['specDate']) : "",
      'calenderData'          => isset($eventData['timeSetting']['calenderDate']) ? json_encode($eventData['timeSetting']['calenderDate']) : "",
      'ticketSetting'         => isset($eventData['ticketSeeting']['ticketSetting']) ? json_encode($eventData['ticketSeeting']['ticketSetting']) : "",
      'specTicketSetting'     => isset($eventData['ticketSeeting']['specTicketSetting']) ? json_encode($eventData['ticketSeeting']['specTicketSetting']) : "",
      'ticketOption'          => isset($eventData['ticketSeeting']['option']) ? json_encode($eventData['ticketSeeting']['option']) : "",
      'mapData'               => $map_data,
      'ticketViewContent'     => isset($eventData['ticketViewData']) ? json_encode($eventData['ticketViewData']) : "",
      'sellSetting'           => isset($eventData['sellSetting'][0]['sellSetting']) ? json_encode($eventData['sellSetting'][0]['sellSetting']) : "",
      'sale_type'             => isset($eventData['basisData']['sale_type']) ? $eventData['basisData']['sale_type'] : 0,
      'edit_status'           => $edit_status,
      'fbgCtrl'               => $fbgCtrl,
      'sale_info'             => isset($sale_info)?$sale_info : false,
      'map_had_stage_seat'    => $map_had_stage_seat,
      'remind_code'           => \Config::get('constant.remind_code.none'),
      'entry_time'            => Carbon::now(),
      'is_setting_tikcet'     => $is_setting_tikcet,
    );
    
    return $retData;
  }

  /**
   * get edit data
   *
   * @return $result
   */
  public function show($performanceId)
  {
    $GLID = session('GLID');
    $account_cd = session('account_cd');
    $hallData = $this->EvenManageRepositories->hallGet($GLID);
    $pcstArr = \Config::get('constant.post');
    $map_had_stage_seat =  $this->EvenManageRepositories->hasStageSeat($performanceId);
    $is_setting_tikcet = array();

    if(session()->exists('event_update')){
      $has_msn  = true;
      $info[]   = array(
        'title' => trans('events.S_Add'),
        'msn'   => trans('events.S_AddMsn'),
      );
      
      $status  = array(
        'update_status' => true,
        'msn_status'    => \Config::get('constant.message_status.information'),
      );
  
      session()->forget('event_update');
    }else{
      $info     = [];
      $has_msn  = false;

      $status  = array(
        'update_status' => '',
        'msn_status'    => '',
      );
    }

    $data  = array(
      'msn'    => $info,
    );

    $result  = array(
      'status' => $status,
      'data'    => $data,
    );

    $msn_json = json_encode($result);

    foreach ($hallData as $hall_row) {
      if(isset($pcstArr[$hall_row->post_code])){
        $hall_location = $pcstArr[$hall_row->post_code];
        $hall_row->prefecture = $hall_location[0];
        $hall_row->city = $hall_location[1];
      }
    }

    $filter_data = array(
      'performance_id' => $performanceId
    );
   
    // performation 資料
    $performance_data = $this->EvenManageRepositories->getPerformance($performanceId);
    $performance_status = $performance_data['status'];
    $edit_status      = $performance_data['edit_status']; 
    $temporary_info = $performance_data['temporary_info'];
    $transFlg = $performance_data['trans_flg'];
    $performance_dispaly_status = $this->getDisplayStatus($temporary_info, $performance_status, $transFlg);
    $sale_info = $this->EvenManageRepositories->getSaleInfo();
    $article = json_decode($temporary_info, true)['basisData']['article'];
    $article   = json_decode( $article , true);

    $check_anoounce_status = $this->checkAnoounceStatus($performance_data);

    if($check_anoounce_status){
      $this->EvenManageRepositories->deleteDraft($performanceId);
      $remind_code = \Config::get('constant.remind_code.draft');
    
    }else{ 
      if(session()->exists('entryTimeOver')){
        $remind_code = \Config::get('constant.remind_code.draft');
      }else{
        $remind_code = \Config::get('constant.remind_code.none');
      }
    }
    
    // draft 資料
    $draft = $this->EvenManageRepositories->getDraft($filter_data);

    if($draft){
      $draft_temporary_info  = $draft['draft_info'];
      $draft_status  = $draft['new_status'];
      $eventData = json_decode($draft_temporary_info, true);
      $draft_dispaly_status = $this->getDisplayStatus($draft_temporary_info, $draft_status, $transFlg);
    }else{
      $eventData = json_decode($temporary_info, true);
      $draft_status = $performance_status;
      $draft_dispaly_status = $performance_dispaly_status;
    }

    $location = "";
    $eventJson = [];
    if (isset($eventData['basisData'])) {
      if (!isset($eventData['basisData']['questionnaires'])) {
        $eventData['basisData']['questionnaires'] = $this->_newQuestionnaires();
      }
      $eventJson = array(
        'timestamp' => time(),
        'data' => $eventData['basisData'],
      );
      if (isset($eventData["basisData"]["imageLocation"])) {
        $location =  $eventData["basisData"]["imageLocation"];
      } else {
        $location = $eventData["basisData"]["eventId"];
      }
    }
    
    $fbgCtrl = 'fbg';
    if($transFlg > 0) {
      $fbgCtrl = $this->EvenManageRepositories->getFBGCtrl($performanceId);
    }

    if(isset($eventData['mapData'])){
      $map_data = json_encode($eventData['mapData']);
      $is_setting_tikcet = $this->getRespectiveSeat($map_data, $eventData['ticketSeeting']['ticketSetting']);
    }else{
      $map_data = "";
    }
    
    $event = array(
      'evenId'                => $location,
      'status'                => 'edit',
      'performanceStatus'     => $performance_status,
      'performanceDispStatus' => $performance_dispaly_status,
      'draft_status'          => $draft_status,
      'draft_dispaly_status'  => $draft_dispaly_status,
      'transFlg'              => $transFlg,
      'performanceId'         => $performanceId,
      'hallData'              => json_encode($hallData->toArray()),
      'basisData'             => json_encode($eventJson),
      'article'               => ( empty($article) ) ? "[{text: '', type: 1, 'subContent':true, 'image_url': null, 'video_url':null}]" : json_encode($article),
      'timeStatus'            => isset($eventData['timeSetting']['status']) ? $eventData['timeSetting']['status'] : "",
      'timeSetting'           => isset($eventData['timeSetting']) ? json_encode($eventData['timeSetting']) : "",
      'timeTitle'             => isset($eventData['timeSetting']['ruleList']) ? json_encode($eventData['timeSetting']['ruleList']) : "",
      'specDate'              => isset($eventData['timeSetting']['specDate']) ? json_encode($eventData['timeSetting']['specDate']) : "",
      'calenderData'          => isset($eventData['timeSetting']['calenderDate']) ? json_encode($eventData['timeSetting']['calenderDate']) : "",
      'ticketSetting'         => isset($eventData['ticketSeeting']['ticketSetting']) ? json_encode($eventData['ticketSeeting']['ticketSetting']) : "",
      'specTicketSetting'     => isset($eventData['ticketSeeting']['specTicketSetting']) ? json_encode($eventData['ticketSeeting']['specTicketSetting']) : "",
      'ticketOption'          => isset($eventData['ticketSeeting']['option']) ? json_encode($eventData['ticketSeeting']['option']) : "",
      'mapData'               => $map_data,
      'ticketViewContent'     => isset($eventData['ticketViewData']) ? json_encode($eventData['ticketViewData']) : "",
      'sellSetting'           => isset($eventData['sellSetting'][0]['sellSetting']) ? json_encode($eventData['sellSetting'][0]['sellSetting']) : "",
      'has_msn'               => $has_msn,
      'msn_json'              => $msn_json,
      'sale_type'             => isset($eventData['basisData']['sale_type']) ? $eventData['basisData']['sale_type'] : 0,
      'edit_status'           => $edit_status,
      'fbgCtrl'               => $fbgCtrl,
      'sale_info'             => $sale_info,
      'map_had_stage_seat'    => $map_had_stage_seat,
      'remind_code'           => $remind_code,
      'entry_time'            => Carbon::now(),
      'is_setting_tikcet'     => $is_setting_tikcet,
    );
    return $event;
  }
  /**
   * _createHallInfo
   *
   * @return $result
   */
  private function _createHallInfo($settingData, $GLID, $account_cd)
  {
    $result = array(
      'hall_id' => $settingData->localId,
      'local_status' => 'N',
    );

    $local_status = 'I';

    if(isset($settingData->localStatus) && !empty($settingData->localId)){
      $local_status = $settingData->localStatus;

      if($local_status == 'N'){
        return $result;
      }
    }
      
    $postcd = $settingData->post_code;

    //set prefecture
    $prefectureArr = \Config::get('constant.prefectures');
    $prefecture = '';
    foreach ($prefectureArr as $prefectureCode => $prefectureName) {
      $ziplist = $prefectureName[1];
      //   if($prefectureName[0] === $settingData->country)
      if (in_array($postcd, $ziplist)) {
        $prefecture = $prefectureCode;
      }
    }



    $hallInf = array(
      'hall_id' => $settingData->localId,
      'hall_name' => $settingData->locationName, //会場名
      'hall_name_short' => '',                    //会場名略称
      'hall_name_kana' => '',                     //会場名カナ
      'post_code' => $postcd,                     //郵便番号
      'prefecture' => $prefecture,                //都道府県
      'address1' => '',                           //住所１
      'address2' => '',                           //住所２
      'tel_num' => '',                            //電話番号
      'fax_num' => '',                            //ＦＡＸ番号
      'home_page' => $settingData->localUrl,    //ホームページアドレス
      'mail_address' => '',                       //メールアドレス
      'description' => $settingData->locationDescription, //備考
      'Owner_cd' => $GLID,                        //オーナー
      'public' => '0',                            //公開 0:非公開 1:公開
      'update_account_cd' => $account_cd,         //更新担当者コード         
    );
  
    if($local_status == 'I' || empty($settingData->localId)){
      $result['hall_id'] = $this->EvenManageRepositories->hallInsert($hallInf);
    }else if($local_status == 'U'){
      $this->EvenManageRepositories->hallUpadte($hallInf);
    }

    return $result;
  }
  /**
   * createSalesTermInfo
   *
   * @return $result
   */
  private function _createSalesTermInfo($performanceId, $sellData, $settingData, $account_cd, $settingData_old = null, $sellData_old = null)
  {
    if ($sellData && !empty($sellData)) {
      $reserve_period = 0;
      //販売条件-先行
      $terminf = array(
        'performance_id' => $performanceId,
        'treat_kbn' => \Config::get('constant.treat_kbn.net'),
        'treat_flg' => (empty($settingData->earlyBirdDateChecked) ? false : $settingData->earlyBirdDateChecked), //基本情報画面 先行販売チェック
        'sales_kbn' => \Config::get('constant.ticket_sales_kbn.early'),
        'reserve_st_date' => $settingData->earlyBirdDateStart,  //基本情報画面 先行販売開始日　
        'reserve_st_time' => substr($settingData->earlyBirdDateStart, 11, 8),  //基本情報画面 先行販売開始時間
        'reserve_cl_date' => $settingData->earlyBirdDateEnd,    //基本情報画面 先行販売終了日　
        'reserve_cl_time' => substr($settingData->earlyBirdDateEnd, 11, 8),    //基本情報画面 先行販売終了時間　
        'reserve_period' => $reserve_period,
        'sales_kbn_nm' => '先行',
        'account_cd' => $account_cd,
      );
      $ret = $this->EvenManageRepositories->salesTremUpdateOrCreate($terminf);
      $termid = $ret->term_id;
      $this->_createPayPickInfo($sellData, $ret, $termid, $account_cd);

      //販売条件-一般
      $terminf = array(
        'performance_id' => $performanceId,
        'treat_kbn' => \Config::get('constant.treat_kbn.net'),
        'treat_flg' => (empty($settingData->normalDateChecked) ? false : $settingData->normalDateChecked), //基本情報画面 一般販売チェック
        'sales_kbn' => \Config::get('constant.ticket_sales_kbn.normal'),
        'reserve_st_date' => $settingData->normalDateStart,  //基本情報画面 一般販売開始日　
        'reserve_st_time' => substr($settingData->normalDateStart, 11, 8),  //基本情報画面 一般販売開始時間
        'reserve_cl_date' => $settingData->normalDateEnd,    //基本情報画面 一般販売終了日　
        'reserve_cl_time' => substr($settingData->normalDateEnd, 11, 8),    //基本情報画面 一般販売終了時間　
        'reserve_period' => $reserve_period,
        'sales_kbn_nm' => '一般',
        'account_cd' => $account_cd,
      );
      $ret = $this->EvenManageRepositories->salesTremUpdateOrCreate($terminf);
      $termid = $ret->term_id;
      $this->_createPayPickInfo($sellData, $ret, $termid, $account_cd);

      //販売条件-当日
      $terminf = array(
        'performance_id' => $performanceId,
        'treat_kbn' => \Config::get('constant.treat_kbn.net'),
        // 'treat_flg' => (empty($settingData->normalDateChecked) ? false : $settingData->normalDateChecked), //基本情報画面 一般販売チェック
        'treat_flg' => 1,
        'sales_kbn' => \Config::get('constant.ticket_sales_kbn.onsite'),
        'reserve_st_date' => $settingData->performance_st_dt,  //基本情報画面 一般販売開始日　
        'reserve_st_time' => substr($settingData->performance_st_dt, 11, 8),  //基本情報画面 一般販売開始時間
        'reserve_cl_date' => $settingData->performance_end_dt,    //基本情報画面 一般販売終了日　
        'reserve_cl_time' => substr($settingData->performance_end_dt, 11, 8),    //基本情報画面 一般販売終了時間　
        'reserve_period' => $reserve_period,
        'sales_kbn_nm' => '当日',
        'account_cd' => $account_cd,
      );
      $ret = $this->EvenManageRepositories->salesTremUpdateOrCreate($terminf);
      $termid = $ret->term_id;
      $this->_createPayPickInfo($sellData, $ret, $termid, $account_cd);
      
    }
  }

  /**
   * _createHallSeatInfo
   *
   * @return mapData[]
   */
  private function _createHallSeatInfo($mapData, $seatMapProfileId, $account_cd)
  {
    if (!$seatMapProfileId || !$mapData || !$account_cd)
      return null; //error

    //GL_FLOOR insert 
    $floorData = $mapData[0]->data[0]->mapData;
    $floorSeq = 0;
    $hallSeatSeq = 0;
    foreach ($floorData as $floorName  => $floor) {
      //GL_SEATMAP_PROFILEを作成している場合、GL_FLOOR insert
      $floorSeq++;
      $floorinfo = array(
        'profile_id'        => $seatMapProfileId,   //profile_id
        'floor_name'        => $floor->floorTittle, //フロア名
        'sequence'          => $floorSeq,           //順序
        'image_file_name'   => $floor->imageUrl,    //画像ファイル名
        'update_account_cd' => $account_cd,         //更新担当者コード 
      );
      $floorId = $this->EvenManageRepositories->floorInsert($floorinfo);
      $mapData[0]->data[0]->mapData->$floorName->id = $floorId;

      //GL_BLOCK insert
      foreach ($floor->blockData as $blockName  => $block) {
        //GL_SEATMAP_PROFILEを作成している場合、GL_BLOCK insert
        $blockinfo = array(
          'profile_id' => $seatMapProfileId,         //profile id 
          'block_name_short' => $block->blockTittle, //ブロック名略称
          'block_name' => $block->blockTittle,       //ブロック名
          'app_block' => '',                         //アプリブロック
          'app_coordinate' => '',                    //アプリ座標
          'net_coordinate' => '',                    //ネット座標
          'image_file_name' => '',                   //画像ファイル名
          'seat_direction' => $block->direction,     //座席方向
          'external_image' => '',                    //展望画像
          'update_account_cd' => $account_cd,        //更新担当者コード 
        );
        $blockId = $this->EvenManageRepositories->blockInsert($blockinfo);
        $mapData[0]->data[0]->mapData->$floorName->blockData->$blockName->id = $blockId;

        //GL_HALL_SEAT insert
        foreach ($block->seatData as $seat_key => $hallSeat) {
          //GL_SEATMAP_PROFILEを作成している場合、GL_HALL_SEAT insert
          $hallSeatSeq++;
          $hallSeatInfo = array(
            'profile_id' => $seatMapProfileId, // profile id
            'floor_id' => $floorId,             //フロアid
            'block_id' => $blockId,             //ブロックid
            'seat_seq' => $hallSeatSeq,         //座席連番
            'x_coordinate' => $hallSeat->x,     //座標Ｘ
            'y_coordinate' => $hallSeat->y,     //座標Ｙ
            'x_position' => '0',                //位置Ｘ
            'y_position' => '0',                //位置Ｙ
            'seat_angle' => '0',                //角度
            'seat_cols' => $hallSeat->rowname,  //列 
            'seat_number' => $hallSeat->number, //番号
            'gate' => $block->gate,             //ゲート
            'prio_floor' => $floorSeq,          //優先順位階  = GL_FLOOR.sequence
            'prio_seat' => $hallSeat->seatPriority, //優先順位座席
            'update_account_cd' => $account_cd,     //更新担当者コード
          );
          // $hallSeatId = $this->EvenManageRepositories->hallSeatInsert($hallSeatInfo);
          $hallSeatId = $this->EvenManageRepositories->hallSeatCreatorUpdate($hallSeatInfo);

          $mapData[0]->data[0]->mapData->$floorName->blockData->$blockName->seatData->$seat_key->hsid = $hallSeatId;
        }
      }
    }
    return $mapData;
  }


  /**
   * _createHallMapInfo
   *
   * @return 
   */
  private function _createHallMapInfo($GLID, $performanceId, $account_cd, $settingData, $seatMapProfileId, $nonResevedSeat_id, $mapData, $reserveArr, $seatClassArr)
  {

    //GL_FLOOR insert 
    $floorData = $mapData[0]->data[0]->mapData;
    $floorSeq = 0;
    $hallSeatSeq = 0;

    foreach ($floorData as $floorNmae  => $floor) {
      if (!empty($seatMapProfileId)) {
        //GL_SEATMAP_PROFILEを作成している場合、GL_FLOOR insert
        $floorSeq++;
        $floorinfo = array(
          'profile_id'        => $seatMapProfileId,   //profile_id
          'floor_name'        => $floor->floorTittle, //フロア名
          'sequence'          => $floorSeq,           //順序
          'image_file_name'   => $floor->imageUrl,    //画像ファイル名
          'update_account_cd' => $account_cd,         //更新担当者コード 
        );
        $floorId = $this->EvenManageRepositories->floorInsert($floorinfo);
      }
      //GL_BLOCK insert
      foreach ($floor->blockData as $blockNmae  => $block) {
        if (!empty($seatMapProfileId)) {
          //GL_SEATMAP_PROFILEを作成している場合、GL_BLOCK insert
          $blockinfo = array(
            'profile_id' => $seatMapProfileId,         //profile id 
            'block_name_short' => $block->blockTittle, //ブロック名略称
            'block_name' => $block->blockTittle,       //ブロック名
            'app_block' => '',                         //アプリブロック
            'app_coordinate' => '',                    //アプリ座標
            'net_coordinate' => '',                    //ネット座標
            'image_file_name' => '',                   //画像ファイル名
            'seat_direction' => $block->direction,     //座席方向
            'external_image' => '',                    //展望画像
            'update_account_cd' => $account_cd,        //更新担当者コード 
          );
          $blockId = $this->EvenManageRepositories->blockInsert($blockinfo);
        }
        //GL_HALL_SEAT insert
        foreach ($block->seatData as $hallSeat) {
          if (!empty($seatMapProfileId)) {
            //GL_SEATMAP_PROFILEを作成している場合、GL_HALL_SEAT insert
            $hallSeatSeq++;
            $hallSeatInfo = array(
              'profile_id' => $seatMapProfileId, // profile id
              'floor_id' => $floorId,             //フロアid
              'block_id' => $blockId,             //ブロックid
              'seat_seq' => $hallSeatSeq,         //座席連番
              'x_coordinate' => $hallSeat->x,     //座標Ｘ
              'y_coordinate' => $hallSeat->y,     //座標Ｙ
              'x_position' => '0',                //位置Ｘ
              'y_position' => '0',                //位置Ｙ
              'seat_angle' => '0',                //角度
              'seat_cols' => $hallSeat->rowname,  //列 
              'seat_number' => $hallSeat->number, //番号
              'gate' => $block->gate,             //ゲート
              'prio_floor' => $floorSeq,          //優先順位階  = GL_FLOOR.sequence
              'prio_seat' => $hallSeat->seatPriority, //優先順位座席
              'update_account_cd' => $account_cd,     //更新担当者コード
            );
            $hallSeatId = $this->EvenManageRepositories->hallSeatInsert($hallSeatInfo);
          }
          
          $reserveCd = null;
          $seat_class_id = null;

          if (isset($hallSeat->typeData->type) && $hallSeat->typeData->type === 'specSeat') {
            //押さえ席の場合、押えコード取得
            $reserveCd = $reserveArr[$hallSeat->typeData->title];
          } else {
            //押さえ以外
            if (isset($hallSeat->typeData->type)) {
              //指定席
              $seat_class_id = $seatClassArr[$hallSeat->typeData->title];
            } else {
              //自由席
              // $seat_class_id = $nonResevedSeat_id;
              $seat_class_id = null;
            }
          }
          $seatInfo = array(
            'performance_id' => $performanceId,  //公演ID
            'seat_id' => $hallSeatId,            //会場座席id
            'seat_class_id' => $seat_class_id,  //席種id
            'reserve_code' => $reserveCd,       //押えコード
            'update_account_cd' => $account_cd   //更新担当者コード
          );
          if ($hallSeat->status === 'I') {
            //GL_INSERT insert
            $seat = $this->EvenManageRepositories->seatInsert($seatInfo);
          } else if ($hallSeat->status === 'U') {
            //GL_INSERT update
            $seat = $this->EvenManageRepositories->seatInsert($hallSeat->seatid, $seatInfo); //seatid-objectname確認                    
          }
        }
      }
    }
  }

  /**
   * _updateSeatAllocInfo
   *
   * @return 
   */
  private function _updateSeatAllocInfo($performanceId, $account_cd, $mapData, $reserveArr, $allTicketData, $time_data, $isRepulic)
  {
    $ticketData = $allTicketData->ticketSetting;
    $specTicket = isset($allTicketData->specTicketSetting->data)?$allTicketData->specTicketSetting->data:null;
    $floorData = &$mapData[0]->data[0]->mapData;
    $stageList = array();
    if(isset($time_data->calenderDate) && !empty($time_data->calenderDate)) {
      foreach($time_data->calenderDate as $stageDate) {
        foreach($stageDate->date->rule as $_stage) {
          $stageList[$stageDate->date->dateValue][$_stage->id] = $_stage->dbId;
        }
      }  
    }
    foreach ($floorData as $floorNmae  => &$floor) {
      foreach ($floor->blockData as $blockNmae  => &$block) {
        foreach ($block->seatData as $key => &$hallSeat) {
  
          $reserveCd = null;
          $seat_class_id = null;
      
          if (isset($hallSeat->typeData->type) && $hallSeat->typeData->type === 'specSeat') {
            //押さえ席の場合、押えコード取得
            $reserveCd = $reserveArr[$hallSeat->typeData->title];
          } else {
            //押さえ以外
            if (isset($hallSeat->typeData->type) && $hallSeat->typeData->type === 'ticketSetting') {
              if(isset($ticketData->data[$hallSeat->typeData->id]) && isset($ticketData->data[$hallSeat->typeData->id]->sdbid)) {
                //指定席
                $seat_class_id = $ticketData->data[$hallSeat->typeData->id]->sdbid;
              }else{
                $seat_class_id = null;
              }
            } else {
              //清空席位設定
              // $seat_class_id = $nonResevedSeat_id;
              $seat_class_id = null;
            }
          }
    
          //[TODO] James 08/02 : 前端模式切換後不會改變狀態，所以狀態判斷先移除
          // if ($hallSeat->status !== 'N' ) {
            $seatInfo = array(
              'alloc_seat_id' => (isset($hallSeat->asid)? $hallSeat->asid : 0),
              'performance_id' => $performanceId,  //公演ID
              'seat_id' => $hallSeat->hsid,            //会場座席id
              'seat_class_id' => $seat_class_id,  //席種id
              'reserve_code' => $reserveCd,       //押えコード
              'update_account_cd' => $account_cd   //更新担当者コード
            );
           
            $seat = $this->EvenManageRepositories->seatUpdateOrCreate($seatInfo);
            $hallSeat->asid = $seat->alloc_seat_id;
            $hallSeat->status = 'N';
          // }

          //如果該座位有場次變更的話，將變更記入GL_STAGE_SEAT
          if(isset($hallSeat->respectiveData) && !empty($hallSeat->respectiveData) && !$isRepulic) {
            foreach($hallSeat->respectiveData as $id => $stageseat) {
              Log::debug('tt'.json_encode($stageseat));
              $seat_class_id_m = NULL;
              $reserveCd_m = NULL;

              if(isset($stageseat->type)) {
                switch($stageseat->type) {
                  case 0: //清空席位設定
                  case '0':
                    $seat_class_id_m = NULL;
                    $reserveCd_m = NULL;
                  break;
                  case 1: //押さえ席の場合
                  case '1':
                    // 押えコード取得
                    if(isset($specTicket[$stageseat->index]) && $specTicket[$stageseat->index]->ticketStatus != 'D'){
                        $reserveCd_m = $specTicket[$stageseat->index]->ticketCode;
                    }
                    else if(isset($specTicket[$stageseat->index]) && $specTicket[$stageseat->index]->ticketStatus == 'D') {
                        $reserveCd_m = NULL;
                    }
                    else {
                        throw new Exception("[_updateSeatAllicInfo]Unknow/Error respectiveData(specTicket)");
                    }
                  break;
                  case 2: //指定席
                  case '2':
                    if(isset($ticketData->data[$stageseat->index]) && isset($ticketData->data[$stageseat->index]->sdbid)) {
                      $seat_class_id_m = $ticketData->data[$stageseat->index]->sdbid;
                    }else{
                        throw new Exception("[_updateSeatAllicInfo]Unknow/Error respectiveData(seat_class)");
                    }
                  break;
                  default: //以外
                    //清空席位設定
                    throw new Exception("[_updateSeatAllicInfo]Unknow/Error respectiveData(stageseat->type)");
                    break;
                }
              }
              $stageSeatInfo = array(
                'stage_seat_id'   =>    (isset($stageseat->stsid)? $stageseat->stsid : 0),
                'alloc_seat_id'   =>    $hallSeat->asid,
                'schedule_id'     =>    $stageList[$stageseat->dateValue][$stageseat->ruleId],
                'seat_class_id'   =>    $seat_class_id_m,
                'reserve_code'    =>    $reserveCd_m,
                'update_account_cd' => $account_cd   //更新担当者コード
              );
              $_stageseat = $this->EvenManageRepositories->stageSeatUpdateOrCreate($stageSeatInfo);
              $stageseat->stsid = $_stageseat->stage_seat_id;
            }
          }
        }
      }
    }
    return $mapData;
  }

  /**
   * createPayPickInfo
   *
   * @return $result
   */
  private function _createPayPickInfo($sellData, $salesTrem, $termid, $account_cd)
  { 
    if (empty($termid)) return;
    //支払引取
    $payMethodArr = \Config::get('constant.pay_method');    //支払方法
    $pickUpArr    = \Config::get('constant.pickup_method'); //引取方法
    $payPick      = $sellData[0]->sellSetting; //支払い引取情報
    
    //先行ネット 
    foreach ($payMethodArr as $pay) {
      foreach ($pickUpArr as $pick) {
        $payData = array(
          'term_id'        => $termid,
          'pay_method'     => $pay,
          'pickup_method'  => $pick,
          'treat_flg'      => 0,
          'treat_end_date' => null,
          'receive_limit'  => 0,
          'update_account_cd' => $account_cd,
        );
       
        if ($pay === \Config::get('constant.pay_method.card')) {
          //支払方法:card
          //信用卡收款check on   
          if ($pick === \Config::get('constant.pickup_method.eticket')) {
            //引取方法:電子票券（Mobapass）
            if ($payPick->payCredit->creditCard && $payPick->payCredit->onlineGetTicket)
              $payData['treat_flg'] = 1; //check on
          } else if ($pick === \Config::get('constant.pickup_method.qrpass_sms')) {
            //引取方法:電子票券（qrpass sms）
            if ($payPick->payCredit->creditCard && $payPick->payCredit->qrPassSms)
              $payData['treat_flg'] = 1; //check on
          } else if ($pick === \Config::get('constant.pickup_method.qrpass_sms')) {
            //引取方法:電子票券（qrpass email）=
            if ($payPick->payCredit->creditCard && $payPick->payCredit->qrPassEmail)
              $payData['treat_flg'] = 1; //check on
          } else if($pick === \Config::get('constant.pickup_method.ibon')){
            //引取方法:電子票券（ibon)
            if ($payPick->payCredit->creditCard && $payPick->payCredit->ibon)
              $payData['treat_flg'] = 1; //check on
          } else if ($pick === \Config::get('constant.pickup_method.store')) {
            //引取方法:超商取票 日本 7-11
            if ($payPick->payCredit->creditCard && $payPick->payCredit->sevenEleven)
              $payData['treat_flg'] = 1; //check on
          } else if ($pick === \Config::get('constant.pickup_method.resuq')) {
            //引取方法:超商取票 日本 q
            if ($payPick->payCredit->creditCard && $payPick->payCredit->resuq)
              $payData['treat_flg'] = 1; //check on
          }else if($pick === \Config::get('constant.pickup_method.no_ticketing')){
            //引取方法:無票
            if(isset($payPick->payCredit->noTicketing)){
              if($payPick->payCredit->creditCard && $payPick->payCredit->noTicketing)
                $payData['treat_flg'] = 1; //check on
            }
          }

          $payData['receive_limit'] = $payPick->payCredit->creditCardLimit;
        }

        if ($pay === \Config::get('constant.pay_method.ibon')) {
          //支払方法:ibon 
          if ($pick === \Config::get('constant.pickup_method.ibon')) {
            //引取方法:ibon
            if ($payPick->payIbon->status){
              $payData['treat_flg'] = 1; //check on
              $payData['receive_limit'] = $payPick->payIbon->ibonTicketLimit;
              $payData['pay_due_days'] = $payPick->payIbon->ibonDateLimit;
            }
          }

        }
       
        if ($pay === \Config::get('constant.pay_method.store') &&  $payPick->paySEJ->status) {
          //支払方法:超商收款取票
          if(isset($payPick->paySEJ->sevenElevenSEJ)){
            if($payPick->paySEJ->sevenElevenSEJ && $pick === \Config::get('constant.pickup_method.store')){
              //超商收款取票 check on
              $payData['treat_flg'] = 1; 
              $payData['receive_limit'] = $payPick->paySEJ->SEJTicketLimit;
              $payData['treat_end_kbn'] = 2;
              $payData['pay_due_days'] = $payPick->paySEJ->SEJDateLimit-1; 
              $payData['treat_end_days'] = $payPick->paySEJ->SEJDateLimit-1;
            }
          }
          if(isset($payPick->paySEJ->noTicketingSEJ)){
            if($payPick->paySEJ->noTicketingSEJ && $pick === \Config::get('constant.pickup_method.no_ticketing')){
              //超商收款取票 check on
              $payData['treat_flg'] = 1; 
              $payData['receive_limit'] = $payPick->paySEJ->SEJTicketLimit;
              $payData['treat_end_kbn'] = 2;
              $payData['pay_due_days'] = $payPick->paySEJ->SEJDateLimit-1; 
              $payData['treat_end_days'] = $payPick->paySEJ->SEJDateLimit-1;
            }
          }
          // 未完成前端未連接
          if(isset($payPick->paySEJ->resuqSEJ)){
            if($payPick->paySEJ->resuqSEJ && $pick === \Config::get('constant.pickup_method.resuq')){
              //超商收款 && resuq取票
              $payData['treat_flg'] = 1; 
              $payData['receive_limit'] = $payPick->paySEJ->SEJTicketLimit;
              $payData['treat_end_kbn'] = 2;
              $payData['pay_due_days'] = $payPick->paySEJ->SEJDateLimit-1; 
              $payData['treat_end_days'] = $payPick->paySEJ->SEJDateLimit-1;
          
            }
          }
        }

        if(isset($salesTrem->reserve_st_date)) {
          $payData['pickup_st_kbn'] = 1;
          $payData['pickup_st_date'] = $salesTrem->reserve_st_date;
        }
        
        $this->EvenManageRepositories->paynPickUpdateOrCreate($payData);
      }
    }
  }
  
  /**
   * createQuestionnaireInfo
   *
   * @return $result
   */
  private function _createQuestionnaireInfo($performanceId, $accountCd, $questionnaires) {
    $result = [];
    try {
      foreach($questionnaires as $questionnaire) {
        //GL_QUESTION update or insert
        $result[] = $this->EvenManageRepositories->questionUpdateOrCreate($performanceId, $accountCd, $questionnaire);
      }
    } catch(Exception $e) {
        dd($data);
        Log::error('_createQuestionnaireInfo :'.$e->getMessage());
        throw new Exception ('_createQuestionnaireInfo :'.$e->getMessage());
    }
    return $result;
  }

  /**
   * _callMbpsImageApi
   *
   * @return 
   */
  private function _callMbpsImageApi($user_code, $performance_code,$logoPath)
  {
      if(strlen($logoPath) > 0)
      { 
        \Log::debug('callMbpsImageApi satart');
        \Log::debug('user:'. $user_code);
        $ifile      = file_get_contents(public_path() . $logoPath);
        $image      =  base64_encode($ifile);  
        $exetension = pathinfo($logoPath, PATHINFO_EXTENSION);
        $imagelist[] = array(
                              ((\App::getLocale() == "ja" )? '988':'999') . $user_code //ユーザーコード
                             ,$performance_code    //公演コード
                             ,$exetension          //拡張子
                             ,$image               //画像データ
                            );

        $outputTime = str_replace('/', '', date("Y/m/d")) . str_replace(':', '', date("H:i:s"));
        //画像csv作成->zip出力
        $zip = $this->makeZip("imageinfo_", $imagelist, 'mbps_imagedata_', $outputTime);
        //作成したzipをencode
        $req = $this->zipEncode($zip);       
        // api call
        $result = \MbpsApi::callApi('image.api', $req);  
    
        if(isset($result->result) && $result->result)
        {
          //api連携成功の場合
          //GL_MOBAPASS_OUTPUT insert        
          $output = array(
                            'data_kbn'  => '5',  //データ区分 5:画像
                            'data_id'   => $performance_code . $outputTime , //データID   performance_code + outputtime
                            'file_name' => $zip, //ファイル名             
                         );
          //GL_MBPS_OUTPUT insert
          $this->MobapassOutputRepositories->mbpsOutputInsert($output);
        }else{
          Log::debug('****画像連携失敗****:' . $zip);      
          Log::debug('****画像連携失敗****:' . $zip);      
          //メール通知
          Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
        }    
        Log::debug('公演画像:' .  $zip);     
        //圧縮済のcsv削除
        // 拡張子が.csvのファイルをglobで取得しループ処理
        $dir = glob(storage_path(config('app.mbps_temp_path')) . '/*.csv');
        foreach ($dir as $file) 
        {
          // globで取得したファイルをunlinkで1つずつ削除していく
          unlink($file);
        }
        \Log::debug('callMbpsImageApi end');
      }
  }
  function makeZip($csvname, $data, $zipname, $outputTime)
  {
    //csv出力
    $f = fopen(storage_path(config('app.mbps_temp_path')) . "/" . $csvname .$outputTime . ".csv", "w"); //gettiilite\public
    if($f)
    {
      $this->makeCsvLine_forMobapass($data, $f);
    }else{
      \Log::debug('callMbpsImageApi creating zip failed');
      //メール通知
      Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
    }
    fclose($f);            

    //圧縮処理
    $zipFileName = $zipname . $outputTime . '.zip';
    $zip = new ZipArchive;
    $zip->open(storage_path(config('app.mbps_save_path')) . '/' . $zipFileName, ZipArchive::CREATE);
    $zip->addFile(storage_path(config('app.mbps_temp_path')) .  "/" . $csvname  . $outputTime . '.csv',  "/" . $csvname  . $outputTime . '.csv');
    $zip->close();    
    return $zipFileName;
  }  
  function zipEncode($zip)
  {
    $enc = file_get_contents(storage_path(config('app.mbps_save_path')) .  "/" . $zip);
    $enc =  base64_encode($enc);    
    $enc = ['csv'=>$enc];
    $enc =  json_encode($enc);
    return $enc;
  }    
  function makeCsvLine_forMobapass($data, $f)
  {
    foreach ($data as $line) 
    { 
      $line = str_replace('"', '""', $line); // double quote escape
      $out = '';
      $row_tmp = '"';
      $row_tmp .= implode('","', $line);
      $row_tmp .= '"' . "\n";
      $out .= $row_tmp;
      fwrite($f, $out);
    }
  }      
  /**
   * make performance csv files for GETTIIS
   *
   * @param    $performanceId 
   * @return 
   */
  public function transportPerfomanceInfo($performance_id,$performance_code=null,$logoPath=null,$userStatus=null)
  { 
    //GETTIIS連携用公演CSV作成
    \Log::debug('export event mst_data satart');
    // 公演基本情報 gep_kouen
    $eventInfo = $this->EvenManageRepositories->getPerfromanceInfoForCsv($performance_id);
    // 公演基本情報 英語名
    $eventInfoEng = $this->EvenManageRepositories->getPerfromanceInfoForCsvEng($performance_id);
    $eventInfoEng       = json_decode($eventInfoEng->lang_info);

    if (!$eventInfo) {
      throw new Exception("[transportPerfomanceInfo]Not found the event data", 1);
      return;
    }
    // 席種区分が指定席の席種数 -> 0で全席自由
    if($eventInfo->selection_flg != 0) {
      $seatClassReserved = $this->EvenManageRepositories->getCountSeatClassReserved($performance_id)?1:0;
    }
    else {
      $seatClassReserved = 0;
    }
    
    $outputTime = str_replace('/', '', date("Y/m/d")) . str_replace(':', '', date("H:i:s"));
    $gep_kouen = array(
      //ヘッダー
      array(
        "usercd",
        "kouencd",
        "kouennm",
        "kouenknm",
        "kouensubnm",
        "kikankbn",
        "kouenfm",
        "kouento",
        "dispkikan",
        "hallnm",
        "hallknm",
        "portaldispkbn",
        "portalsalekbn",
        "netstartdt",
        "portalst",
        "portaled",
        "genrecd",
        "prefcd",
        "sikucode",
        "place_url",
        "official_url",
        "informationnm",
        "informationtel",
        "syusai",
        "gazou",
        "gazou_exte",
        "gazou_url",
        "any1_title",
        "any1_naiyou",
        "staff",
        "syutuen",
        "syutuen2",
        "kyokumoku",
        "gaiyo",
        "salesinfo",
        "any2_title",
        "any2_naiyou",
        "keyword",
        "stopflg",
        "kousinkbn",
        "collectdt",
        "kouen_update",
        "gazou_big",
        "gazou_big_exte",
        "gazou_updatedt",
        "gazou_big_updatedt",
        "gazou_flag",
        "gazou_big_flag",
        "selseatkbn",
        "selseatdt",
        "selblockkbn",
        "selblockdt",
        "ryokin_selseatkbn",
        "ryokin_selseatdt",
        "ryokin_selblockkbn",
        "ryokin_selblockdt",
        "point_flag",
        "point_tag",
        "selseateddt",
        "selblockeddt",
        "ryokin_selseateddt",
        "ryokin_selblockeddt",
        "point_kbn",
        "kouennm_en",
        "kouensubnm_en",
        "hallnm_en",
        "informationnm_en",
        "dispkikan_en",
        "portlanguage",
        'autotranslation',
        'hallcd',
        "top_content_type",    //new column for gettii lite. 
        "top_content_comment", //new column for gettii lite. 
        "article",             //new column for gettii lite ph2. 
        "keywords",            //new column for gettii lite ph2. for article table
      ),
    );

    //サムネイル画像をバイナリ文字列化　Binary stringization of thumbnail images 
    // base64エンコード
    $thumbnail = '';
    if ($eventInfo->thumbnail && !empty($eventInfo->thumbnail) && file_exists(public_path() . $eventInfo->thumbnail)) {
      $ifile = file_get_contents(public_path() . $eventInfo->thumbnail);
      $thumbnail = base64_encode($ifile);
    }
    $top_conten_url = '';
    if ($eventInfo->top_conten_type === \Config::get('constant.top_content_type.img')) {
      if ($eventInfo->top_conten_url && !empty($eventInfo->top_conten_url) && file_exists(public_path() . $eventInfo->top_conten_url)) {
        $ifile = file_get_contents(public_path() . $eventInfo->top_conten_url);
        $top_conten_url =  base64_encode($ifile);
      }
    } else {
      $top_conten_url = $eventInfo->top_conten_url;
    }
    
    //記事タイトル
    //$article = json_decode($eventInfo->article);
    //$articleTitle = null;
    //foreach ($article as $articleInfo) 
    //{
    //  if(isset($articleInfo->title))
    //  {
    //    $articleTitle = $articleInfo->title;
    //  }
    //}
    
    //keyword(gep_kouen)設定
    //公演名 + 会場 + 市 + 区
    $postCode       = \Config::get('constant.post');
    $keyword = $eventInfo->performance_name;
    $keyword = $keyword . '　';
    $keyword = $keyword . $eventInfo->hall_disp_name;
    $keyword = $keyword . '　';
    $keyword = $keyword . $postCode[$eventInfo->post_code][0];
    $keyword = $keyword . '　';
    $keyword = $keyword . $postCode[$eventInfo->post_code][1];
    
    $gep_kouen[] = array(
      $eventInfo->user_code,
      $eventInfo->performance_code,
      $this->escapeDoubleQuotes($eventInfo->performance_name),
      '',
      $this->escapeDoubleQuotes($eventInfo->performance_name_sub),
      ($eventInfo->sch_kbn == \Config::get('constant.schedule_type.non'))?'1':'0',  //期間区分 0:通常公演 1:期間公演
      date("Y/m/d", strtotime($eventInfo->performance_st_dt)),
      date("Y/m/d", strtotime($eventInfo->performance_end_dt)),
      '',   //期間区分が1:期間公演 の場合に販売期間に表示する文字
      $this->escapeDoubleQuotes($eventInfo->hall_disp_name),
      '',   //会場名カナ
      '1',  //ポータル公開区分 1:公開する 
      $eventInfo->sale_type,  //ポータル販売区分 0:販売なし 1:ポータル販売のみ 2:主催者販売のみ 3:両方で販売
      date("Y/m/d H:i", strtotime($eventInfo->disp_start)), //掲載元リンク表示開始日
      ($userStatus == -2)?'9999/12/31 23:59:59':$eventInfo->disp_start, //ポータル表示開始日
      $eventInfo->disp_end?:date("Y/m/d 23:59:59", strtotime($eventInfo->performance_end_dt)), //ポータル表示終了日
      ($eventInfo->genre_code < 100)? 900 : $eventInfo->genre_code,
      $eventInfo->prefecture,
      $eventInfo->post_code,   //city(=post code)
      $eventInfo->home_page,
      $eventInfo->official_url,
      $this->escapeDoubleQuotes($eventInfo->information_nm),
      $eventInfo->information_tel,
      '1',   //主催者区分(1:主催者 2:プレイガイド他)
      $thumbnail,  //表示画像 blob base64  
      ($eventInfo->thumbnail) ? $this->getImageExtension($eventInfo->thumbnail) : '',   //画像拡張子
      '',   //画像URL
      '',   //任意項目１タイトル
      '',   //任意項目１内容
      '',   //スタッフ
      '',   //出演曲目カード
      '',   //出演
      '',   //曲目
      'URLENCODE' . $eventInfo->context,    //概要（GETTIIS紹介文）
      '',    //発売情報
      '',    //任意項目２タイトル
      '',    //任意項目２内容
      $this->escapeDoubleQuotes($keyword), //キーワード 公演名 + 会場 + 市 + 区
      ($eventInfo->status == \Config::get('constant.performance_status.cancel')) ? '1':'0',    //公演中止フラグ
      ($eventInfo->trans_flg === \Config::get('constant.GETTIIS_trans.yet')) ? '1' : '2', //更新区分 1:追加 2:変更 3:削除 未連携の場合1、再連携の場合、2
      date("Y/m/d H:i:s"),    //データ収集日時（CSV出力日）
      date("Y/m/d H:i:s ", strtotime($eventInfo->updated_at)), //公演更新日時
      $top_conten_url, //表示画像大
      ($eventInfo->top_conten_type === \Config::get('constant.top_content_type.img')) ? $this->getImageExtension($eventInfo->top_conten_url) : '',    //表示画像大拡張子
      '',    //表示画像更新日時
      '',    //表示画像大更新日時
      (!empty($eventInfo->thumbnail)) ? '1' : '0',  //表示画像処理フラグ 0:何もしない 1:作成・更新する 3:削除する
      ($eventInfo->top_conten_type === \Config::get('constant.top_content_type.img')) ? '1' : '0',    //表示画像大処理フラグ 0:何もしない 1:作成・更新する 3:削除する
      '0',    //座席指定区分 0:指定なし 1:座席指定あり
      '',    //座席指定開始日時
      '0',    //ブロック指定区分 0:指定なし 1:指定あり
      '',    //ブロック指定開始日時
      $seatClassReserved,   //料金の座席指定区分 0:指定なし
      $seatClassReserved?date("Y/m/d H:i", strtotime($eventInfo->disp_start)):'',    //料金の座席指定開始日時
      $seatClassReserved,    //料金のブロック指定区分 0:指定なし
      $seatClassReserved?date("Y/m/d H:i", strtotime($eventInfo->disp_start)):'',    //料金のブロック指定開始日時
      '0',    //ポイントの実施有無 0:無し
      '',    //ポイントタグ
      '',    //座席指定終了日時
      '',    //ブロック指定終了日時
      $seatClassReserved?date("Y/m/d H:i", strtotime($eventInfo->performance_end_dt)):'',    //料金の座席指定終了日時
      $seatClassReserved?date("Y/m/d H:i", strtotime($eventInfo->performance_end_dt)):'',    //料金のブロック指定終了日時
      '1',    //ポイント区分 1:ポイント有り
      $eventInfoEng->data->performanceName,    //公演名（英）
      $eventInfoEng->data->performanceNameSub, //公演名副題（英）
      $eventInfoEng->data->hallDispName,       //会場名（英）
      $eventInfoEng->data->informationNm,      //問合せ先名称（英）
      '',    //期間表示名（英）
      $eventInfo->portlanguage,    //言語区分  
      $eventInfo->autotranslation, //自動翻訳
      $eventInfo->hall_code, //会場コード
      //add for GL s
      $eventInfo->top_conten_type,    //top content type     
      $this->escapeDoubleQuotes($eventInfo->top_content_comment), //top content comment  
      $this->escapeDoubleQuotes($eventInfo->article), //記事情報
      $eventInfo->keywords, //keywords
    //  $articleTitle //記事タイトル
      //add for GL e
    );
    $f = fopen(storage_path(config('app.mst_temp_path')) . "/gep_kouen_" . $outputTime .".csv", "w"); //gettiilite\public
    if ($f) {
      $this->makeCsvLine($gep_kouen, $f);
    } else {
      throw new Exception("transport error : " . (string) $f);
    }
    fclose($f);

    if($eventInfo->sale_type && $userStatus != -2) {
      // 販売条件 gep_sales_info
      $saleterm = $this->EvenManageRepositories->getSalesTermInfoForCsv($performance_id);

      $gep_sales_info = array(
          //ヘッダー
          array(
            "usercd",       //ユーザーコード
            "kouencd",      //公演コード
            "yoyakukikan",  //予約期間区分
            "hanbaisbt",    //販売種別
            "orderstdt",    //予約開始日時
            "ordereddt",    //予約締切日時
            "yoyakuhouhou", //予約方法
            "collectdt",    //データ収集日時
            "salesmethod1", //販売方法１
            "salesmethod2", //販売方法２
            "salesmethod3", //販売方法３
            "salesmethod4",  //販売方法４
            "salesmethod5", //販売方法5
            "salesmethod6",  //販売方法6
            "salesmethod7"  //販売方法7
          )
      );
     
      $salesmethod1 = 0; //コンビニ-コンビニ
      $salesmethod2 = 0; //カード-コンビニ
      $salesmethod3 = 0; //カード-れすQ(台湾未使用)
      $salesmethod4 = 0; //カード-モバパス
      $salesmethod5 = 0; //credit card && no ticket(台湾未使用)
      $salesmethod6 = 0; //7-11 && no ticket(台湾未使用)
      $salesmethod7 = 0; //7-11 && れすq (台湾未使用)

      foreach ($saleterm as $saletermInfo) {

        if($saletermInfo->sales_kbn > 2 || $saletermInfo->sales_kbn < 1)
          continue;
        $reserve_st_date = strtotime($saletermInfo->reserve_st_date);
        $reserve_cl_date = strtotime($saletermInfo->reserve_cl_date);
        $gep_sales_info = array_add($gep_sales_info, $saletermInfo->sales_kbn, array(
                                                          $eventInfo->user_code,          //ユーザーコード
                                                          $eventInfo->performance_code,   //公演コード
                                                          $saletermInfo->sales_kbn,       //予約期間区分
                                                          "2",                            //販売種別 1:掲載元 2:ポータル販売
                                                          date('Y/m/d H:i', $reserve_st_date), //予約開始日時
                                                          date('Y/m/d H:i', $reserve_cl_date), //予約締切日時
                                                          "1",                            //予約方法 1:先着 2:抽選
                                                          date("Y/m/d H:i:s"),            //データ収集日時（CSV出力日）
                                                          $salesmethod1, //販売方法１
                                                          $salesmethod2, //販売方法２
                                                          $salesmethod3, //販売方法３
                                                          $salesmethod4,  //販売方法４
                                                          $salesmethod5, //販売方法5
                                                          $salesmethod6,  //販売方法6
                                                          $salesmethod7  //販売方法7
                                                          ) );

        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.store') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.store')) {
          //コンビニ-コンビニ
          $gep_sales_info[$saletermInfo->sales_kbn][8] = 1;
        }
        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.card') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.store')) {
          //カード-コンビニ
          $gep_sales_info[$saletermInfo->sales_kbn][9] = 1;
        }
        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.card') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.resuq')) {
          //カード-れすq
          $gep_sales_info[$saletermInfo->sales_kbn][10] = 1;
        }
        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.card') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.eticket')) {
          //カード-モバパス
          $gep_sales_info[$saletermInfo->sales_kbn][11] = 1;
        }
        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.card') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.no_ticketing')) {
          //credit card && no ticket
          $gep_sales_info[$saletermInfo->sales_kbn][12] = 1;
        }
        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.store') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.no_ticketing')){
          //7-11 && no ticket
          $gep_sales_info[$saletermInfo->sales_kbn][13] = 1;
        }
        if ($saletermInfo->pay_method === \Config::get('constant.pay_method.store') && $saletermInfo->pickup_method === \Config::get('constant.pickup_method.resuq')){
          //7-11 && れすq
          $gep_sales_info[$saletermInfo->sales_kbn][14] = 1;
        }
      }
      
      $f = fopen(storage_path(config('app.mst_temp_path')) . "/gep_salesinfo_" . $outputTime . ".csv", "w"); //gettiilite\public
      if ($f) {
        $this->makeCsvLine($gep_sales_info, $f);
      } else {
        throw new Exception("transport error : " . (string) $f);
      }
      fclose($f);
      
      if($eventInfo->status == \Config::get('constant.performance_status.sale') || $eventInfo->status == \Config::get('constant.performance_status.cancel')) {
        // 時間・回数 gep_kouensch
        $kouensch = $this->EvenManageRepositories->getScheduleInfoForCsv($performance_id);
        $gep_kouen_sch = array(
          //ヘッダー
          array(
            "usercd",   //ユーザーコード
            "kouencd",  //公演コード
            "kouenbi",  //公演日
            "stagecd",  //ステージコード
            "kouenflg", //公演フラグ
            "kaijyotm", //開場時間
            "kaientm",  //開演時間
            "stopflg",  //公演中止フラグ
            "bikou",    //備考
            "senkou_orderstdt", //先行予約開始日時
            "senkou_ordereddt", //先行予約締切日時
            "ippan_orderstdt",  //一般予約開始日時
            "ippan_ordereddt",  //一般予約締切日時
            "collectdt",        //デー��収集日時
            "dispkouendt",      //表示公演日時
            "stagenm",          //ステージ名
            "bikou_en",         //備考（英）
            "stagenm_en",       //ステージ名（英）
            "dispkouendt_en",   //表示公演日時（英）
            //add for GL s
            "refund_st_date",   //払戻開始
            "refund_end_date",  //払戻終了
            "cancel_messgae",   //公演中止文言
            //add for GL e
          )
        );

        $s_senkou_orderstdt = null;
        $s_senkou_ordereddt = null;
        $s_ippan_orderstdt = null;
        $s_ippan_ordereddt = null;
        foreach ($saleterm as $saletermInfo) {
          if ($saletermInfo->sales_kbn == 1) {
            if ($s_senkou_orderstdt)
              $s_senkou_orderstdt = ($s_senkou_orderstdt < $saletermInfo->reserve_st_date) ? $s_senkou_orderstdt : $saletermInfo->reserve_st_date;
            else
              $s_senkou_orderstdt = $saletermInfo->reserve_st_date;

            if ($s_senkou_ordereddt)
              $s_senkou_ordereddt = ($s_senkou_ordereddt > $saletermInfo->reserve_cl_date) ? $s_senkou_ordereddt : $saletermInfo->reserve_cl_date;
            else
              $s_senkou_ordereddt = $saletermInfo->reserve_cl_date;
          }
          if ($saletermInfo->sales_kbn == 2) {
            if ($s_ippan_orderstdt)
              $s_ippan_orderstdt = ($s_ippan_orderstdt < $saletermInfo->reserve_st_date) ? $s_ippan_orderstdt : $saletermInfo->reserve_st_date;
            else
              $s_ippan_orderstdt = $saletermInfo->reserve_st_date;

            if ($s_ippan_ordereddt)
              $s_ippan_ordereddt = ($s_ippan_ordereddt > $saletermInfo->reserve_cl_date) ? $s_ippan_ordereddt : $saletermInfo->reserve_cl_date;
            else
              $s_ippan_ordereddt = $saletermInfo->reserve_cl_date;
          }
        }

        $senkou_orderstdt = ($s_senkou_orderstdt) ? strtotime($s_senkou_orderstdt) : null;
        $senkou_ordereddt = ($s_senkou_ordereddt) ? strtotime($s_senkou_ordereddt) : null;
        $ippan_orderstdt = ($s_ippan_orderstdt) ? strtotime($s_ippan_orderstdt) : null;
        $ippan_ordereddt = ($s_ippan_ordereddt) ? strtotime($s_ippan_ordereddt) : null;

        foreach ($kouensch as $kouenschInfo) {
          $gep_kouen_sch[] = array(
            $eventInfo->user_code,           //ユーザーコード
            $eventInfo->performance_code,    //公演コード
            date("Y/m/d", strtotime($kouenschInfo->performance_date)), //公演日
            $kouenschInfo->stage_num,        //ステージコード
            $kouenschInfo->performance_flg,  //公演フラグ
            $kouenschInfo->open_date,        //開場時間(常にNULLなのでフォーマット不要)
            date('H:i',  strtotime($kouenschInfo->start_time)), //開演時間 
            $kouenschInfo->cancel_flg,       //公演中止フラグ
            $this->escapeDoubleQuotes($kouenschInfo->description), //備考
            ($senkou_orderstdt) ? date('Y/m/d H:i', $senkou_orderstdt) : "",  //先行予約開始日時
            ($senkou_ordereddt) ? date('Y/m/d H:i', $senkou_ordereddt) : "",  //先行予約締切日時
            ($ippan_orderstdt) ? date('Y/m/d H:i', $ippan_orderstdt) : "",    //一般予約開始日時
            ($ippan_ordereddt) ? date('Y/m/d H:i', $ippan_ordereddt) : "",    //一般予約締切日時
            date("Y/m/d H:i:s"),                                             //データ収集日時（CSV出力日）
            $this->escapeDoubleQuotes($kouenschInfo->disp_performance_date),  //表示公演日時
            ($kouenschInfo->stage_disp_flg == 1) ? $this->escapeDoubleQuotes($kouenschInfo->stage_name) : '', //ステージ名
            $this->escapeDoubleQuotes($kouenschInfo->description),                                      //備考（英）
            ($kouenschInfo->stage_disp_flg == 1) ? $this->escapeDoubleQuotes($kouenschInfo->stage_name) : '', //ステージ名（英）
            $this->escapeDoubleQuotes($kouenschInfo->disp_performance_date),                             //表示公演日時（英）
            //add for GL s
            ($kouenschInfo->refund_st_date)?date('Y/m/d', strtotime($kouenschInfo->refund_st_date)) : "",   //払戻開始
            ($kouenschInfo->refund_end_date)?date('Y/m/d', strtotime($kouenschInfo->refund_end_date)) : "",  //払戻終了
            $this->escapeDoubleQuotes($kouenschInfo->cancel_messgae),   //公演中止文言
            //add for GL e      
          );
        }
        $f = fopen(storage_path(config('app.mst_temp_path')) . "/gep_kouensch_" . $outputTime . ".csv", "w"); //gettiilite\public
        if ($f) {
          $this->makeCsvLine($gep_kouen_sch, $f);
        } else {
          throw new Exception("transport error : " . (string) $f);
        }
        fclose($f);

        // 料金 gep_ryokin
        $ryokin = $this->EvenManageRepositories->getRyokinForCsv($performance_id);
        $gep_ryokin = array(
          //ヘッダー
          array(
            "usercd",       //ユーザーコード
            "kouencd",      //公演コード
            "hanbaisbt",    //販売種別
            "kouenbi",      //公演日
            "stagecd",      //ステージコード
            "sekisyu",      //席種コード
            "kensyu",       //券種コード
            "sekisyunm",    //席種名
            "price",        //料金
            "stdt",         //適用開始日時
            "eddt",         //適用終了日時
            "sekisyusort",  //席種表示順
            "collectdt",    //データ収集日時
            "selseatkbn",   //座席指定区分
            "selseatdt",    //座席指定開始日時
            "selblockkbn",  //ブロック指定区分
            "selblockdt",   //ブロック指定開始日時
            "selseateddt",  //座席指定終了日時
            "selblockeddt", //ブロック指定終了日時
            "sekisyunm_en"  //席種名（英）
          )
        );
        foreach ($ryokin as $ryokinInfo) {
          $ryokinSelSeat = ($seatClassReserved && ($ryokinInfo->seat_class_kbn == \Config::get('constant.seat_class_kbn.reserved')))? \Config::get('constant.seat_selection.on'):\Config::get('constant.seat_selection.off');
          $gep_ryokin[] = array(
            $eventInfo->user_code,           //ユーザーコード
            $eventInfo->performance_code,    //公演コード
            "2",                             //販売種別 1:掲載元 2:ポータル販売 Gettiiでは会員区分に相当
            date("Y/m/d", strtotime($ryokinInfo->performance_date)),   //公演日
            $ryokinInfo->stage_num,          //ステージコード
            $ryokinInfo->seat_class_id,      //席種コード
            $ryokinInfo->ticket_class_id,    //券種コード
            $this->escapeDoubleQuotes($ryokinInfo->seat_class_name),    //席種名
            $ryokinInfo->price,              //料金
            date("Y/m/d", strtotime($ryokinInfo->reserve_st_date)) . ' '  . date("H:i", strtotime($ryokinInfo->reserve_st_time)), //適用開始日時 予約開始日時(YYYY/MM/DD HH24:MI) Gettiiでは券種の適用期間
            date("Y/m/d", strtotime($ryokinInfo->reserve_cl_date)) . ' ' . date("H:i", strtotime($ryokinInfo->reserve_cl_time)), //適用終了日時 予約締切日時(YYYY/MM/DD HH24:MI) Gettiiでは券種の適用期間
            $ryokinInfo->disp_order,         //席種表示順
            date("Y/m/d H:i:s"),             //データ収集日時（CSV出力日）//データ収集日時
            $ryokinSelSeat,  //座席指定区分 0:指定なし 1:座席指定あり 自由席の場合,0
            $ryokinSelSeat?date("Y/m/d H:i", strtotime($ryokinInfo->reserve_st_date)):'',    //座席指定開始日時
            $ryokinSelSeat, //ブロック指定区分 0:指定なし 1:座席指定あり 座席指定あり 自由席の場合,0
            $ryokinSelSeat?date("Y/m/d H:i", strtotime($ryokinInfo->reserve_st_date)):'',    //ブロック指定開始日時
            $ryokinSelSeat?date("Y/m/d H:i", strtotime($ryokinInfo->reserve_cl_date)):'',    //座席指定終了日時
            $ryokinSelSeat?date("Y/m/d H:i", strtotime($ryokinInfo->reserve_cl_date)):'',    //ブロック指定終了日時
            "" //席種名（英）
          );
        }
        $f = fopen(storage_path(config('app.mst_temp_path')) . "/gep_ryokin_" . $outputTime . ".csv", "w"); //gettiilite\public
        if ($f) {
          $this->makeCsvLine($gep_ryokin, $f);
        } else {
          throw new Exception("transport error : " . (string) $f);
        }
        fclose($f);
      }
    }

    //圧縮処理
    $zipFileName = 'gettiis_mstdata_' . $outputTime . substr(explode(".", microtime(true))[1], 0, 3) . '.zip';
    $zip = new ZipArchive;
    $zip->open(storage_path(config('app.mst_save_path')) . '/' . $zipFileName, ZipArchive::CREATE);
    // $zip->addFile(storage_path(config('app.mst_temp_path')) . '/gep_kouen_' . $outputTime . '.csv', 'gep_kouen_' . $outputTime . '.csv');
    // $zip->addFile(storage_path(config('app.mst_temp_path')) . '/gep_salesinfo_' . $outputTime . '.csv', 'gep_salesinfo_' . $outputTime . '.csv');
    // if($eventInfo->status == \Config::get('constant.performance_status.sale') || $eventInfo->status == \Config::get('constant.performance_status.cancel')) {
    //   $zip->addFile(storage_path(config('app.mst_temp_path')) . '/gep_kouensch_' . $outputTime . '.csv', 'gep_kouensch_' . $outputTime . '.csv');
    //   $zip->addFile(storage_path(config('app.mst_temp_path')) . '/gep_ryokin_' . $outputTime . '.csv', 'gep_ryokin_' . $outputTime . '.csv');  
    // }
    $gepkouenName = storage_path(config('app.mst_temp_path')) . '/gep_kouen_' . $outputTime . '.csv';
    $gepSalesinfoName = storage_path(config('app.mst_temp_path')) . '/gep_salesinfo_' . $outputTime . '.csv';
    if(file_exists($gepkouenName)) $zip->addFile($gepkouenName, 'gep_kouen_' . $outputTime . '.csv');
    if(file_exists($gepSalesinfoName))$zip->addFile($gepSalesinfoName, 'gep_salesinfo_' . $outputTime . '.csv');
    
    if($eventInfo->status == \Config::get('constant.performance_status.sale') || $eventInfo->status == \Config::get('constant.performance_status.cancel')) {
      $gepkouenschPath = storage_path(config('app.mst_temp_path')) . '/gep_kouensch_' . $outputTime . '.csv';
      $gepRyokinPath = storage_path(config('app.mst_temp_path')) . '/gep_ryokin_' . $outputTime . '.csv';
      if(file_exists($gepkouenschPath))$zip->addFile($gepkouenschPath, 'gep_kouensch_' . $outputTime . '.csv');
      if(file_exists($gepRyokinPath))$zip->addFile($gepRyokinPath, 'gep_ryokin_' . $outputTime . '.csv');
        
    }
    $zip->close();

    //圧縮済のcsv削除
    // 拡張子が.csvのファイルをglobで取得しループ処理
    $dir = glob(storage_path(config('app.mst_temp_path')) . '/*.csv');
    foreach ($dir as $file) {
      // globで取得したファイルをunlinkで1つずつ削除していく
      unlink($file);
    }

    $UpdateInf = array(
      'trans_flg' => \Config::get('constant.GETTIIS_trans.already'),
      'account_cd' => session('account_cd'),
    );
    //GETTIIS連携フラグ更新
    $this->EvenManageRepositories->UpdateGETTIIStransFlg($performance_id, $UpdateInf);

    //GL_PORTAL_MST_OUTPUTにデータ作成
    $mstOutPut = array(
      'sight_id'    => $eventInfo->user_id,
      'data_id'     => $eventInfo->performance_code . $outputTime,
      'data_kbn'    => '1', //1:パッチデータ 2:スナップショットデータ
      'output_date' => $outputTime,
      'corp_target' => '1', //0:対象外 1:対象
      'file_name'   => $zipFileName
    );
    $this->EvenManageRepositories->portalMstOutputInsert($mstOutPut);
    \Log::debug('export event mst_data finished');

    if(is_null($performance_code))
    {
      //公演コード
      $performance_code = \DB::table('GL_PERFORMANCE')->select('performance_code')
                                                    ->where('performance_id', $performance_id)
                                                    ->first();    
      $performance_code = $performance_code->performance_code;
    }    
            
    if($userStatus != -2)
    {
      if(is_null($logoPath))
      {
        //チケット画像
        $logoPath = \DB::table('GL_TICKET_LAYOUT')->select('thumbnail')
                                                  ->where('performance_id', $performance_id)
                                                  ->where('ticket_kbn', '9')
                                                  ->orderBy('schedule_id','desc')
                                                  ->first();    
        $logoPath = isset($logoPath->thumbnail)?$logoPath->thumbnail:null;
      }        
      //mobapass 画像api連携
      $this->_callMbpsImageApi($eventInfo->user_code,$performance_code,$logoPath);
    }
  }

  /**
   * 檢查 performation_code 是否重複
   *
   * @return bool
   */
  public function eventIdcheck($GLID, $event_id)
  {
    $event_id = trim($event_id);
    $result = $this->EvenManageRepositories->checkEventId($GLID,$event_id);
    
    return $result;
  }

  /**
   * escape double quotes
   *
   * @return $str
   */
  public function escapeDoubleQuotes($str)
  {
    return $str = mb_ereg_replace('"', '""', $str);
  }

  /**
   * get edit data
   *
   * @return $result
   */
  public function newEvent()
  {
    $GLID = session('GLID');
    $account_cd = session('account_cd');
    $hallData = $this->EvenManageRepositories->hallGet($GLID);
    $pcstArr = \Config::get('constant.post');

    $contact_inf = array(
      'contact_person' => '',
      'tel_num'        => '',
      'mail_address'   => '',
    );

    foreach ($hallData as $hall_row) {
      $hall_location = $pcstArr[$hall_row->post_code];
      $hall_row->prefecture = $hall_location[0];
      $hall_row->city = $hall_location[1];
    }
    $article = "[{text: '', type: 1, 'subContent':true, 'image_url': null, 'video_url':null}]";

    if($this->EvenManageRepositories->getUser($GLID)){
      $get_contact_inf = $this->EvenManageRepositories->getContactInf();
      if($get_contact_inf){
        $contact_inf['contact_person']  = $get_contact_inf['contact_person'];
        $contact_inf['tel_num']  = $get_contact_inf['tel_num'];
        $contact_inf['mail_address']  = $get_contact_inf['mail_address'];
      }
    }

    $event = array(
      'performanceStatus' =>  config('constant.performance_status.going'),
      'performanceDispStatus' =>  config('constant.performance_disp_status.going'),
      'status' => 'add',
      'evenId' => $this->randnum(),
      'hallData' => $hallData,
      'article' => $article,
      'contact_inf' => $contact_inf,
      'sale_type' => 0,
      'transFlg' => 0,
      'sale_info' => '',
      'remind_code' => \Config::get('constant.remind_code.none'),
      'entry_time' => Carbon::now(),
      'is_setting_tikcet' => array(),
      'questionnaires' => [], //$this->_newQuestionnaires(), STS 2021/06/09 Task 15
    );

    return $event;
  }

  function makeCsvLine($data, $f)
  {
    foreach ($data as $line) {
      $out = '';
      $row_tmp = '"';
      $row_tmp .= implode('","', $line);
      $row_tmp .= '"' . "\n";
      $out .= $row_tmp;
      fwrite($f, $out);
    }
  }


  function getImageExtension($file)
  {
    $img_file = public_path() . $file;
    //MIMEタイプの取得
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($img_file);
    $extension = null;
    switch ($mime_type) {
      case 'image/jpeg':
        $extension = 'jpg';
        break;
      case 'image/png':
        $extension = 'png';
        break;
    }
    return $extension;
  }
  /**
   * event delete
   * 
   * @param array $request
   * @return result
   */
  public function eventDelete(array $request){
    $json       = json_decode($request['json'])[0];
    $inf        = $json->data[0];
    $GLID       = session('GLID');
    $account_cd = session('account_cd');
    $admin_flg  = session('admin_flg');
   
    $data = array(
      'GLID'                =>  $GLID,
      'admin_flg'           =>  $admin_flg,
      'sp_GLID'             =>  \Config::get('constant.super_user_data.GLID'),
      'account_cd'          =>  $account_cd,
      'performation_id'     =>  $inf->performationId,
      'performance_status'  =>  \Config::get('constant.performance_status.delete'),
    );
  
    $result = $this->EvenManageRepositories->deleteEvent($data);
   
    if($result){
      return true;
    }else{
      return false;
    }
  }
  
  private function getLangType($autoTransZhtw,$autoTransEng)
  {
     $langType = \Config::get('constant.portal_lang.none');
    if($autoTransZhtw && $autoTransEng) $langType =  \Config::get('constant.portal_lang.engzh'); //英&中
    if(!$autoTransZhtw && $autoTransEng) $langType = \Config::get('constant.portal_lang.eng');   //英
    if($autoTransZhtw && !$autoTransEng) $langType = \Config::get('constant.portal_lang.zh');   //中
    return $langType;
  }
  
  private function _newQuestionnaires() {
    $questionnaires = [[
        'id' => -1,
        'use' => false,
        'langs' => [
          'ja' => ['id' => -1, 'title' => '', 'text'  => '', 'placeholder' => '', 'selected' => true,],
          'en' => ['id' => -1, 'title' => '', 'text'  => '', 'placeholder' => '', 'selected' => false,],
          'zh_tw' => ['id' => -1, 'title' => '', 'text'  => '', 'placeholder' => '', 'selected' => false,],
        ],
        'required' => false,
        'sort' => 1,
      ],
    ];
    return $questionnaires;
  }
}
