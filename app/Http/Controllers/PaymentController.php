<?php

namespace App\Http\Controllers;
use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\File;
use Mail;
use App\Mail\ContactCSSent;
use App\Repositories\GMOTransRepositories;


class paymentController extends Controller
{   
    /** @var GMOTransRepositories */
    protected $GMOTransRepositories;

    const GMO_RESULT_CODE_SUCCESS = 1;
    const GMO_RESULT_CODE_EXIT = 2;
    
    public function __construct(GMOTransRepositories $GMOTransRepositories)
    {
        $this->GMOTransRepositories = $GMOTransRepositories;
    }   


    public function executeReservation(Request $request)
    {
        $url = '';
        try 
        {
          \DB::beginTransaction();       
          //GET
          $fields['contract_code'] = $request->contract_code; //契約番号
          $fields['order_number']  = $request->order_number;  //注文番号
          $fields['payment_code']  = $request->payment_code;  //決済方法
          $fields['state']         = $request->state;         //ステータス
          $fields['trans_code']    = $request->trans_code;    //トランザクションコード
          $fields['user_id']       = $request->user_id;       //ユーザーID

          //GMOTRANテーブル更新
          // DB::table('GL_GMO_TRANS')
          //   ->where('order_number','=', $fields['order_number']) //order_number
          //   ->update(['trans_code'   => $fields['trans_code'],   //トランザクションコード 
          //             'user_id'      => $fields['user_id'],      //ユーザーID
          //             'payment_code' => $fields['payment_code'], //決済方法
          //             'state'        => $fields['state'] ,       //ステータス  
          //           // 'charge_date'  => str_replace('+08','',urldecode($payment['charge_date'])), //課金日時 
          //             'updated_at'   => Carbon::now()            //更新日
          //            ]);
          
          $this->GMOTransRepositories->getByOrderNum($fields['order_number']);
          $saveData = [
            'trans_code'    => $fields['trans_code'],   //トランザクションコード 
            'user_id'       => $fields['user_id'],      //ユーザーID
            'payment_code'  => $fields['payment_code'], //決済方法
            'state'         => $fields['state'] ,       //ステータス  
          ];
          $this->GMOTransRepositories->updateData($saveData);
          \DB::commit();

          $url = $this->GMOTransRepositories->getRetURL();
          
        } catch (\Exception $e) {
          //更新処理失敗  
          \DB::rollback();
          if ($_GET)
          {    
            Log::info('executeReservation:GL_GMO_TRANS update failed:' . print_r($fields, true));
          }else{
            Log::info('executeReservation:GL_GMO_TRANS update failed:GETパラメータ無し');
          }
          //メール通知
          Mail::to(\Config::get('app.cs_mail_to'))->send(new ContactCSSent());
          if(empty($url))
            return response(csrf_token(),200);
          else
            return view('frontend/payment/executeReservationfailed', ['retUrl' => $url]);
        }        
        return view('frontend/payment/executeReservation' ,['gkecd' => $fields['order_number'],'result' => self::GMO_RESULT_CODE_SUCCESS, 'retUrl' => $url]);
    }
    
    public function cancelReservation(Request $request)
    {
        try 
        {
          DB::beginTransaction();       
          //GET
          $fields['contract_code'] = $request->contract_code; //契約番号
          $fields['order_number']  = $request->order_number;  //注文番号
          $fields['trans_code']    = $request->trans_code;    //トランザクションコード
          $fields['user_id']       = $request->user_id;       //ユーザーID

          //GMOTRANテーブル更新
          DB::table('GL_GMO_TRANS')
          ->where('order_number','=', $fields['order_number']) //order_number
          ->update(['state'        => '9' ,                    //ステータス  
                    'updated_at'   => Carbon::now()            //更新日
                  ]);
          DB::commit();
        } catch (\Exception $e) {
          //更新処理失敗  
          DB::rollback();
          if ($_GET)
          {    
            Log::info('cancelReservation:GL_GMO_TRANS update failed:' . print_r($fields, true));
          }else{
            Log::info('cancelReservation:GL_GMO_TRANS update failed:GETパラメータ無し');
          }
          //メール通知
          Mail::to(\Config::get('app.cs_mail_to'))->send(new ContactCSSent());
          return view('frontend/payment/executeReservationfailed');
        }           
        return view('frontend/payment/cancelReservation');
    }    

    public function paymentNotify(Request $request) {
      // Log::info('paymentNotify:request=' . print_r($_GET, true));
      Log::info('paymentNotify:request = [' . $request->method() .'] '. print_r($request->all(), true));
      // retrun data sample:
    //   [POST] Array
    //   (
    //     [order_number] => 3bf2dc5b4cfd4d0dab7dcc2de36f1b4d
    //     [trans_code] => 181249
    //     [payment_code] => 11
    //     [charge_date] => 2020-02-07 15:40:40+08
    //     [user_id] => jameslai
    //     [contract_code] => 10085100
    //     [state] => 1
    //     [_url] => /api/gmotwnotify
    // )
      
      $inputData = $request->all();
      $inputData['charge_date'] = Carbon::parse($inputData['charge_date'])->toDateTimeString();
      if($request->has('order_number')) {
        try {
          $this->GMOTransRepositories->getByOrderNum($request->order_number);
        }
        catch(\Exception $e) {
          Log::error('[paymentNotify] order_number not found. data = '. print_r($request->all(), true) ."\nException - ".$e->getMessage() );
          return response('0 1001 order_number_not_found');
        }
        if(!$this->GMOTransRepositories->verifyTransCode($request->trans_code)) {
          Log::error('[paymentNotify] trans_code error. data = '. print_r($request->all(), true) );
          return response('0 1002 trans_code_errer');
        }
        //[TODO] James 2020.02.05 - 使用order_id查詢訂單狀態，必要時要提出訂單錯誤處理
        try {
          $this->GMOTransRepositories->updateData($inputData);
        }
        catch(\Exception $e) {
          Log::error('[paymentNotify] update data has error. data = '. print_r($inputData, true) ."\nException - ".$e->getMessage() );
          return response('0 9001 DB_error');
        }
        return response('1');
      }
      Log::error('[paymentNotify] input data has error. data = '. print_r($request->all(), true) );
      return response('0 9999 ERROR');
    }


}
