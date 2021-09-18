<?php

namespace App\Http\Controllers;

use Validator;
use Exception;
use Illuminate\Http\Request;
use App\Services\AdminManageServices;
use App\Services\EvenManageServices;
use App\Services\SellManageServices;
use App\Services\MiddlewareServices; // STS 2021/09/09 Task 48 No.2
use App;// STS 2021/09/09 Task 48 No.2
use Log;// STS 2021/09/09 Task 48 No.2
class ReportController extends Controller
{
    /**
     * ReportController constructor.
     * @param AdminManageServices $AdminManageServices
	 * @param MiddlewareServices $MiddlewareServices STS 2021/09/09 Task 48 No.2
     */
    public function __construct(AdminManageServices $AdminManageServices,EvenManageServices $EvenManageServices,SellManageServices $SellManageServices,MiddlewareServices $MiddlewareServices)
    {
      $this->AdminManageServices = $AdminManageServices;
      $this->EvenManageServices = $EvenManageServices;
      $this->SellManageServices = $SellManageServices;
	  $this->MiddlewareServices = $MiddlewareServices;
    }
    /**
     * index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      return view('frontend.report.index');
    }
    /**
     * systemReport
     *
     * @return \Illuminate\Http\Response
     */
    public function systemReport(Request $request)
    {
      //user info 
      $GLID = $request->session()->get('GLID');
      
      //default term
      $startDate = date("Y-m-01");
      $endDate  = date("Y-m-t");
      
      $date = array(
                     'startDate' => $startDate,
                     'endDate'   => $endDate,
                   );

      $clients = null;
      $user=null;
      $eventData=array();
      if($GLID == '1')
      {
        //LS userの場合、クライアント情報取得
        $clients = $this->getClient();
      }else{    
        //初期表示用      
        $user = $this->AdminManageServices->getValidationData($GLID);
        //期間内に入金、キャンセルの有った期間を取得  
        $req = array(
                     'GLID' => $GLID,
                     'date' => str_replace('-','',$startDate) .'-' .str_replace('-','',$endDate),
                     );
        $eventData = $this->SellManageServices->performanceListForReport($req);
      }
      return view('frontend.report.sysreport', ['glid' => $GLID, 'date' => $date,'clients' => $clients,'user' => $user, 'eventData' => $eventData]);
    }
    /**
     * selectEvent
     *
     * @return \Illuminate\Http\Response
     */
    public function selectEvent(Request $request)
    { 
      $data = json_decode($request->all()['jsonEvent'])[0];

      //if(session('GLID') != 1 && session('GLID') != $request->GLID)
      //{
      //   \App::abort(404); 
      // }
      $user = $this->AdminManageServices->getValidationData($data->glid);
      if(is_null($user))
      {
        //存在しないuser
        \App::abort(404); 
      }
      //期間内に入金、キャンセルの有った期間を取得  
      $req = array(
                   'GLID' => $data->glid,
                   'date' => $data->date,
                   );
      $eventData = $this->SellManageServices->performanceListForReport($req);
      
      $date = explode("-", $data->date); 
      $date = array(
                     'startDate' => date("Y-m-d" ,strtotime(trim($date[0]))),
                     'endDate'   => date("Y-m-d" ,strtotime(trim($date[1]))),
                   );

       //クライアント情報取得
       $clients = null;
       if(session('GLID') == 1)$clients = $this->getClient();
      
      return view('frontend.report.sysreport', ['glid' => $data->glid, 'date' => $date,'clients' => $clients,'user' => $user, 'eventData' => $eventData]);
      //return view('frontend.report.sysreportEvent', ['glid' => $data->glid,'date' => $data->date,'user' => $user, 'eventData' => $eventData]);
    }
    /**
     * systemReport
     *
     * @return \Illuminate\Http\Response
     */
    public function systemReportOutput(Request $request)
    {
     //id ... accont_cd + 日時 + ミリ秒   
     $id = session('account_cd') . date("YmdHis").substr(explode(".", microtime(true))[1], 0, 3);
    
     $postData = json_decode($request->all()['jsonRepo'],true)[0];
		//STS 2021/09/09 Task 48 No.2 start
	$performancesId = $postData['performance'];  
	if($request->session()->exists('admin_flg')){
		$flag = true;
		if(session('admin_flg')){
			//Check GLID
			if($postData['glid'] != strval(session('GLID'))){
				Log::info('ReportController:systemReportOutput');
				App::abort(404);
			}
			//Check performance id 
			foreach($performancesId as $id){
				$result = $this->MiddlewareServices->checkPerformanceId($id);
				if(!$result) {
					$flag = false;
					break;
				}
			}
		}else{
			//Check performance id super user
			foreach($performancesId as $id){
				$result = $this->MiddlewareServices->checkSuperUserPerformanceId($id);
				if(!$result) {
					$flag = false;
					break;
				}
			}
		}
		if (!$flag){
			Log::info('ReportController:systemReportOutput');
			App::abort(404);
		}
	}else{
		Log::info('ReportController:systemReportOutput');
		App::abort(404);
	}
	//STS 2021/09/09 Task 48 No.2 end

     $Rules = [
        'glid' => 'required|numeric',
        'date' => 'required|string',
        'performance' => 'required|array',
        'performance.*' => 'required|numeric',
      ];
      $validator = Validator::make($postData, $Rules);

      if($validator->fails()){
          throw new Exception('REQ-ERR-01 Request format error!');
          return null;
      }

     $date = $postData['date'];
     $performance = $postData['performance'];   

      //検索条件
      $week = [
               '日', //0
               '月', //1
               '火', //2
               '水', //3
               '木', //4
               '金', //5
               '土', //6
              ];

      $applyDate = explode("-", $date);
      $from = date("Y/m/d" ,strtotime(trim($applyDate[0])));
      $startDate = $from ."(".$week[date("w", strtotime($from))].")" ;

      $to = date("Y/m/d" ,strtotime(trim($applyDate[1])));
      $endDate = $to ."(".$week[date("w", strtotime($to))].")" ;

      //作成日時　曜日
      $date = date("Y/m/d" ,time());
      $time = date("H:i" ,time());

      $day = date('w');
      $day = $week[$day]; 
      $createDate = $date."(".$day.") ".$time;

      //レポート情報
      $summary = $this->SellManageServices->getSummaryDataForSystemReport($postData['glid'], $id, $performance,$from,$to);
      $bank_inf = $this->SellManageServices->getBankInf($postData['glid']);
      $trans_fee = $this->SellManageServices->getTransFee($postData['glid']);
      
      $param = array(
                     'startDate'  => $startDate,
                     'endDate'    => $endDate,
                     'createDate' => $createDate,
                     'from'       => $from,
                     'to'         => $to,
                     'repoInfo'   => $summary,
                     'bank_inf'   => $bank_inf,
                     'trans_fee'  => $trans_fee,
                    );

      $pdf = \PDF::loadView('frontend.report.sysreportoutput', ['param' => $param]);
      $pdf->getDomPDF()->set_option('enable_font_subsetting', true); //2021/06/24 STS - Task 33 - Adding font subsetting to reduce size of pdf file
      return $pdf->stream('SystemReport_GettiiLite.pdf');
      
      //return view('frontend.report.sysreportoutput');
    }
    
    private function getClient()
    {
      $req = array(
                    "keyword" => null,
                    "dateFilter" => null,
                    "applyDate" =>  "2020/04/01 - 2020/04/01", //dummy日付
                    "userKbn" => array(
                                        0 => "0",
                                        1 => "1"
                                       ),
                     "adminStatus" => array(
                                             0 => "9",//審査OK
                                             1 => "8",//中止
                                             2 => "-2",//退会
                                             3 => "-1",//中止
                                           )      
                  );
      $clients = $this->AdminManageServices->index($req, 0); 
      return $clients;
    }
}
