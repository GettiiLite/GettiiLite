<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SellManageServices;
use App\Exports\csvExport; //Laravel 8, upgrade Maatwebsite/Laravel-Excel 3.1
use Faker\Factory as Faker;
use Carbon\Carbon;
use Excel;
use Log;
use Validator;

class SellManage extends Controller
{
    /** @var SellManageServices */
    protected $SellManageServices;

    /**
     * sellManageController constructor.
     * @param SellManageServices $SellManageServices
     */
    public function __construct(SellManageServices $SellManageServices)
    {
        $this->SellManageServices = $SellManageServices;
    }
    /**
     * 販賣管理 8/9/2020
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $events = $this->SellManageServices->getPerformanceData();

    //     return view('frontend.sell.index', ['events' => $events]);
    // }
    public function index(Request $request)
    {
        $events = $this->SellManageServices->getPerformanceData();

        return view('frontend.sell.performationSell', ['events' => $events]);
    }
    public function indexSearch(Request $request)
    {
        $events = $this->SellManageServices->getPerformanceData($request->all());

        return view('frontend.sell.performationSell', ['events' => $events]);
    }
    /**
     * saerch performation status
     *販賣管理 8/9/2020
     * @return \Illuminate\Http\Response
     */
    // public function indexSearch(Request $request)
    // {
    //     $events = $this->SellManageServices->getPerformanceFilterData($request->all());

    //     return view('frontend.sell.index', ['events' => $events]);
    // }
    /**
     * 販賣管理 9/9/2020
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function manage($performanceId)
    // {
    //     $events = $this->SellManageServices->getStageData($performanceId);
       
    //     return view('frontend.sell.manage', ['events' => $events]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage($performanceId)
    {   
        $events = $this->SellManageServices->getStageData($performanceId);
       
        return view('frontend.sell.manage', ['events' => $events]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stop()
    {
        $faker = Faker::create('zh_TW');

        $timeSlot = array("朝あ部","晝夜部");
        $dateStar = $faker->dateTimeInInterval($startDate = '- 10 days', $interval = '+ 1 days', $timezone = null);
        $dateEnd = $faker->dateTimeInInterval($startDate = '+ 5 days', $interval = '+ 15 days', $timezone = null);

        $eventInf['title'] = $faker->realText($maxNbChars = 10, $indexSize = 2).'企劃';
        $eventInf['placeTitle'] = $faker->realText($maxNbChars = 10, $indexSize = 2).'公會堂';
        $eventInf['date'] = $dateStar->format('Y-m-d').' ~ '.$dateEnd->format('Y-m-d');
       
        for ($i=0; $i < 2; $i++) {
            $eventDateStar = $faker->dateTimeInInterval($startDate = '- 5 days', $interval = '+ 5 days', $timezone = null);
            $eventDateEnd = $faker->dateTimeInInterval($startDate = '- 5 days', $interval = '+ 5 days', $timezone = null);

            $events[$i]['openDay'] = $eventDateStar->format('Y-m-d');
            $events[$i]['openTime'] = $eventDateStar->format('H:i');
            $events[$i]['status'] = rand(0,1);
            $events[$i]['timeSlot'] = $timeSlot[rand(0,1)];
            $events[$i]['status'] = rand(0,1);
            $events[$i]['timeSlot'] = $timeSlot[rand(0,1)];

        }
        
        return view('frontend.sell.eventStop', ['eventInf' => $eventInf, 'events' => $events]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, $scheduleId)
    {
        $page       = (int)$request->input("page",1);
        $filterJson = $request->input("filterJson", null);
       
        $events = $this->SellManageServices->getDetailData($scheduleId, $page, $filterJson);
        
        return view('frontend.sell.detail', ['event' => $events]);
    }
    /**
     * 取得訂單 csv 檔
     *
     * @param Request $request
     * @param int $scheduleId
     * @return Excel
     */
   public function csvExport(Request $request, $scheduleId){
        Log::info('get scheduleId '.$scheduleId.' detail csv.');
        $filterJson = $request->input("filterJson", null);
        $events = $this->SellManageServices->getDetailData($scheduleId, 1, $filterJson);

        //STS 2021/06/17 Task 21: 非会員のダミーのID（gettiis$[N_M]）が表示される箇所を"非会員"に変更してください。--START
        $checkExport = $events['all_reservation_data'];
        $member_id = trans('sellManage.S_EventDetailNoneMember');
            for ($i=0; $i < count($checkExport); $i++) {
             if ($checkExport[$i]['member_id'] == 'gettiis$[N_M]') {
                $checkExport[$i]['member_id'] = $member_id;
             }
          }
        $sch_kbn = $events['sch_kbn'];
        $questionnaires = $events['questionnaires'];
        $csv_data = $this->SellManageServices->detailCsv($checkExport, $sch_kbn, $questionnaires);
        //STS 2021/06/17 Task 21: 非会員のダミーのID（gettiis$[N_M]）が表示される箇所を"非会員"に変更してください。--END

        $csv_file_name = date("YmdHi").'_販売明細';

         //Laravel 8 upgrade function changing
        return Excel::download(new csvExport($csv_data), $csv_file_name.'.csv');
        // Excel::create($csv_file_name,function($excel) use ($csv_data){
        //     $excel->sheet('score', function($sheet) use ($csv_data){
        //         $sheet->rows($csv_data);
        //     });
        // })->export('csv');
    }
    /**
     * 取得訂單查詢 csv 檔
     *
     * @param Request $request
     * @return Excel
     */
    public function orderCsvExport(Request $request)
    {
        Log::info('get order CsvExport  detail csv.');
       
        $csv_file_name = date("YmdHi").'_販売明細';
        $filter_json = $request->input("filterJson");
        $orders = $this->SellManageServices->getAllOrders($filter_json);
        $csv_data = $this->SellManageServices->getOrdersCsv($orders);
        
         //Laravel 8 upgrade function changing
        return Excel::download(new csvExport($csv_data), $csv_file_name.'.csv');
        // Excel::create($csv_file_name,function($excel) use ($csv_data){
        //     $excel->sheet('score', function($sheet) use ($csv_data){
        //         $sheet->rows($csv_data);
        //     });
        // })->export('csv');
    }
    /**
     * insert draw data
     *
     * @return \Illuminate\Http\Response
     */
    public function insertDraw(Request $request)
    {  
        $filterJson = $request->input("filterJson", null);
        $page       = $request->input("page", null);
        $result = $this->SellManageServices->insertDraw($request->all());
       
        if($result['status']){
            session([
                        'insert_draw_result' => true,
                        'insert_draw_msn'    => $result['msn']
                    ]);
            
            return redirect('/sell/detail/'. $result['schedule_id'] . '?filterJson=' . $filterJson .'&page=' . $page);
        }else{
            $errors = $result['msn'];
            return redirect()->back()->withErrors($errors); 
        }
    }
    /**
     * resend notice mail
     * 
     * retrun mail result
     */
    public function resendNotice(Request $request)
    {
        $filterJson = $request->input("filterJson", null);
        $page       = $request->input("page", null);
        $result     = $this->SellManageServices->sendDrawNoticeMail($request->all());

        if($result['status']){
            session(['resend_draw_result' => true]);

            return redirect('/sell/detail/'. $result['schedule_id'] . '?filterJson=' . $filterJson .'&page=' . $page);
        }else{
            $errors = '發送失敗';

            return redirect()->back()->withErrors($errors); 
        }
    }
    public function detailSelect(Request $request, $scheduleId)
    {
        // dd($request->all());
        $events = $this->SellManageServices->getSeachDetailData($scheduleId);

        $dateRange = $request->input('dateRange');
        $seatName = $request->input('seatName');
        $ticketName = $request->input('ticketName');
        $cash = $request->input('cash');

        $creditCard = $request->input('creditCard');
        $seatName = $request->input('seatName');
        $ticketName = $request->input('ticketName');
        $cash = $request->input('cash');
        // dd($scheduleId);
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seatMap($scheduleId)
    { 
        $eventsInf = $this->SellManageServices->getSeatMapData($scheduleId);
        //2021-06-23 STS - TASK 24: Get all schedules of published performance --START
        $performanceId = $eventsInf['data']['performanceId'];
        $events = $this->SellManageServices->getPerfomanceSchedules($performanceId);
        // return view('frontend.sell.seat', ['eventsInf' => $eventsInf]);
        //dd($eventsInf);
        return view('frontend.sell.seat', ['eventsInf' => $eventsInf, 'events' => $events]);
        //2021-06-23 STS - TASK 24 -- END
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unpublished($draft_id, $performance_date, $rule_id){  
        $session_data = array(
            'draft_id' => $draft_id,
            'performance_date_timestamp' => $performance_date,
            'rule_id' => $rule_id,
        );
      
        $eventsInf = $this->SellManageServices->getDraftSeatMapData($session_data);
        //2021-06-24 STS - TASK 24: Get all schedules of unpublished performance --START
        $performanceId = $eventsInf['data']['performanceId'];
        $events = $this->SellManageServices->getPerfomanceUnpublish($performanceId);
        //return view('frontend.sell.seat', ['eventsInf' => $eventsInf]);
        return view('frontend.sell.seat', ['eventsInf' => $eventsInf, 'events' => $events]);
        //2021-06-24 STS - TASK 24: Get all schedules of unpublished performance --END
    }
    /**
     * 
     * 
     * 
     */
    public function uploadSeatMap(Request $request){
        $json_data = json_decode($request['json']);
        if($json_data[0]->publish){
            $upload_result = $this->SellManageServices->uploadSellSeatData($request->all());

            return redirect('/sell/seat/'.$upload_result['scheduleId'])->with(['upload_result' => $upload_result]);
        }else{
            $draft_id = $json_data[0]->draftId;
            $date_value = $json_data[0]->dateValue;
            $rule_id = $json_data[0]->ruleId;
            $url = '/sell/unpublished/seat/'.$json_data[0]->draftId.'/'.$date_value.'/'.$rule_id;

            $upload_result = $this->SellManageServices->uploadUnpublishedData($json_data[0]);

            return redirect($url)
                    ->with([
                        'update_result' => $upload_result,
                    ]);
        }
        
    }
    /**
     * 從 gettis 取得會員資料
     *
     * @return \Illuminate\Http\Response
     */
    public function getMembers(Request $request, $keyword = null)
    {
        $events = $this->SellManageServices->getMembers($keyword);
        
        return $events;
    }
    /**
     * 取消訂單
     *
     * @return \Illuminate\Http\Response
     */
    public function orderCancel(Request $request, $scheduleId)
    {
        $filterJson = $request->input("filterJson", null);
        $page       = $request->input("page", null);
        $result     = $this->SellManageServices->orderCancel($request->all());
        
        return redirect('/sell/detail/' . $scheduleId . '?filterJson=' . $filterJson .'&page=' . $page)
                ->with([
                    'cancel_order_result' => $result['status'],
                    'cancel_order_msn'    => $result['msn']
                ]);
       
    }
    /**
     * 訂單金額修改
     *
     * @return \Illuminate\Http\Response
     */
    public function reviseAmount(Request $request, $scheduleId)
    {
        $filterJson = $request->input("filterJson", null);
        $page       = $request->input("page", null);
        $result     = $this->SellManageServices->reviseAmount($request->all());
        
        if($result['status']){
            
            return redirect('/sell/detail/' . $scheduleId . '?filterJson=' . $filterJson .'&page=' . $page)
                        ->with([
                                'revise_amount_result' => true,
                                'revise_amount_msn'    => $result['msn']
                               ]);
        }else{
            $errors = $result['msn'];
            return redirect()->back()->withErrors($errors); 
        }
    }
    /**
     * 取得訂單資訊
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getOrders(Request $request){
        $result = array(
            'status' => true,
            'filter_json' => null,
            'orders' => null,
            'performances' => [],
        );

        $performance_list = $this->SellManageServices->getPerformanceList();
            
        if($performance_list){
            $result['performances'] = $performance_list;
        }
        
        if($request->exists('filterJson')){
            $filter_json = $request->input("filterJson");
            $result['filter_json'] = json_decode($filter_json);
            $orders = $this->SellManageServices->getOrders($filter_json);
        
            if($orders){
                $result['orders'] = $orders;
            }else{
                $result['status'] = false;
            }
        }
        
        return view('frontend.sell.orderSearch', ['events' => $result]);
    }
    /**
     * 取消訂單
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(Request $request){
        $http_code = 200;
      
        $result = $this->SellManageServices->orderCancel($request->all());
       
        if(!$result['status']){
            $http_code = 400;
        }

        $json = json_encode($result);

        return response($json, $http_code)
               ->header('Content-Type', 'text/plain');
    }

    /**
     * 入場狀態修改
     *
     * @return \Illuminate\Http\Response
     */
    public function changeVisitStatus(Request $request, $seat_sale_id)
    {
        Log::info('change Visit Status : '. PHP_EOL, ['request' => $request]);

        $http_code = 201;
        $result =  array(
            "successus" => false,
            "message" => "",
            "data" => ""
        );
        
        $validator  = Validator::make(['seat_sale_id'=> $seat_sale_id], ['seat_sale_id' => 'exists:GL_SEAT_SALE,seat_sale_id']);
      
        if($validator->fails()){
            Log::error('change Visit Status 2020');
            $http_code = 400;
            $result['message'] = '不存在';
        }else{
            $result = $this->SellManageServices->updateVisitStatus($request->all(), $seat_sale_id);
        }

        Log::notice(['result' => $result]);

        return response($result, $http_code);
    }
}
