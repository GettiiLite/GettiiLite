<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Mail;
use Storage;
use ZipArchive;
use App\Repositories\MobapassOutputRepositories;
use App\Mail\ExceptionMail;

// php artisan MobapassOutput
class MobapassOutput extends Command
{
    protected $MobapassOutputRepositories;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MobapassOutput';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gettii Liteモバパス連携（予約・取消・アプリ番号-購入・アプリ番号-譲渡）';

    /**
     * Create a new command instance.
     *
     * @return void
     */
  public function __construct(MobapassOutputRepositories $MobapassOutputRepositories)
  {
    $this->MobapassOutputRepositories = $MobapassOutputRepositories;
    parent::__construct();
  }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('MobapassOutput start');
        $imagelist = array();
        $imgOut = array();
        $outputTime = str_replace('/', '', date("Y/m/d")) . str_replace(':', '', date("H:i:s"));
        
        //予約連携出力
        Log::debug('予約情報/アプリ番号（購入） start');
        $target = $this->MobapassOutputRepositories->getMobapassOutputTarget();     
        if (count($target) == 0) {
          Log::debug('予約情報/アプリ番号（購入） 連携対象 none');
        }else{
          Log::debug('予約情報/アプリ番号（購入） 連携対象:' . count($target) . '件');
          
          foreach ($target as $order)
          { 
            //チケットレイアウト 公演名+副題+v会場表示名称+開演時間+席種・券種+料金+座席情報
            //オーダー番号+問い合わせ表示名称+電話番号
            $currency = (\App::getLocale() == "ja" )? ' \\':' $';
            $contact = (\App::getLocale() == "ja" )? 'お問合せ先：':'活動聯絡資訊：';
            //全席自由ではない公演且つ、自由席の場合、連番表示
            $reserve_seq = (!is_null($order->seatmap_profile_cd) && $order->seat_class_kbn == \Config::get('constant.seat_class_kbn.unreserved'))?$order->reserve_seq . "\n": null;
            $layout = $order->performance_name_sub ."\n". 
                      $order->performance_name ."\n". 
                      $order->hall_disp_name . "\n". 
                      date('Y年m月d日',  strtotime($order->performance_date)) . $order->day_week ."\n". 
                      "  開演 ".$order->start_time ."\n". 
                      $order->seat_class_name . ' ' . 
                      $order->ticket_class_name .
                      $currency .  $order->sale_price . "（税込）\n" .
                      $reserve_seq .  
                      $order->gate ." ". $order->floor_name ." ". $order->block_name ." " . $order->seat_cols ." ". $order->seat_number .
                      "\n\n" . 
                      $order->reserve_no ."\n".
                      $contact . $order->information_nm  ."\n" . 
                      '電話番号：'   . $order->information_tel;

            //チケットレイアウト
            $ticketlayout = \DB::table('GL_TICKET_LAYOUT')->select('*')
                                                          ->where('performance_id', $order->performance_id)
                                                          ->where('ticket_kbn', '9')
                                                          ->orderBy('schedule_id','desc')
                                                          ->get(); 
            $freeword = null;
            $thumbnail = null;
            foreach ($ticketlayout as $layoutInfo)
            {
              //2019/10/18 free_word string change to json
              if($layoutInfo->free_word){
                $json = json_decode($layoutInfo->free_word, true);
                if(is_null($json)){
                  $freeword = $layoutInfo->free_word;
                }else{
                  $freeword = $json["content"];
                }
              }

              $thumbnail = $layoutInfo->thumbnail;
              if(is_null($layoutInfo->schedule_id))
              {
                //共通レイアウト
                break;
              }
              if($order->schedule_id != $layoutInfo->schedule_id) continue;
              break;
            }
            
            //チケットレイアウト +自由文言
            if(!is_null($freeword))$layout = $layout ."\n". $freeword;
            $layout = urlencode($layout);
            //画像リスト    
            //if(strlen($thumbnail) > 0)
            //{ 
              //$ifile      = file_get_contents(public_path() . $thumbnail);
              //$image      =  base64_encode($ifile);  
              //$exetension = pathinfo($thumbnail, PATHINFO_EXTENSION);
              //$imagelist[] = array(
              //                       $order->user_code        //ユーザーコード
              //                      ,$order->performance_code //公演コード
              //                      ,$exetension              //拡張子
              //                      ,$image                   //画像データ
              //                     );
              //$imgOut[] = array(
              //                   'performance_code' => $order->performance_code
              //                  );
            //}  
            //予約連携リスト  
            switch ($order->date_name)
            {
              case 0:
                $date_name = "日";
                break;
              case 1:
                $date_name = (\App::getLocale() == "zh-tw" ) ? "一" : "月";
                break;
              case 2:
                $date_name = (\App::getLocale() == "zh-tw" ) ? "二" : "火";
                break;
              case 3:
                $date_name = (\App::getLocale() == "zh-tw" ) ? "三" : "水";
                break;
              case 4:
                $date_name = (\App::getLocale() == "zh-tw" ) ? "四" : "木";
                break;
              case 5:
                $date_name = (\App::getLocale() == "zh-tw" ) ? "五" : "金";
                break;
              case 6:
                $date_name = (\App::getLocale() == "zh-tw" ) ? "六" : "土";
                break;
              default:
                $date_name = "x";
            }
            $orderlist[] = array(
                              $this->editUserCodeForMbps($order->user_code) //ユーザーコード
                             ,$order->performance_code      //公演コード
                             ,$order->performance_date      //公演日
                             ,$order->stage_num             //ステージコード
                             ,$order->reserve_no            //予約番号
                             ,$order->reserve_seq           //連番
                             ,$order->performance_name      //公演名
                             ,$order->performance_name_sub  //公演名副題
                             ,$order->hall_disp_name        //会場名
                             ,$date_name                    //曜日
                             ,$order->stage_name            //ステージ名
                             ,$order->start_time            //開場時間
                             ,$order->start_time            //開演時間
                             ,$order->expiration_date       //有効期限日時
                             ,$order->seat_class_name       //席種名
                             ,$order->ticket_class_name     //券種名
                             ,$order->sale_price            //料金
                             ,$order->gate                  //ゲート
                             ,$order->floor_name            //階
                             ,$order->block_name            //ブロック
                             ,$order->seat_cols             //列
                             ,$order->seat_number           //座席番号
                             ,$order->information_nm        //問合せ先名
                             ,$order->information_tel       //問合せ先電話番号
                             ,$order->member_id             //会員ＩＤ
                             ,$order->performance_st_dt     //公演開始日
                             ,$order->disp_performance_date //表示公演日時
                             ,($order->sch_kbn == \Config::get('constant.schedule_type.non'))?'1':'0'//期間券区分
                             ,$order->cancel_flg            //取消区分
                             ,'0'                           //電子チケット譲渡区分
                             ,'1'                           //入場操作区分
                             ,'0'                           //状態
                             ,$order->app_id                //購入時アプリ番号
                             ,''                            //譲渡先アプリ番号                    
                             ,$layout                       //チケットレイアウト 公演名+副題+会場+公演日+会場時間+開演時間+料金+座席情報+文言
                             ,$order->updated_at->format('Y/m/d H:i:s') //更新日時
                             ,$order->update_account_cd     //更新者
                             ,'1'                           //連携システム種別
                             ,''                            //連携システム登録日時
                             ,''                            //連携システム登録者
                             ,''                            //連携システム更新日時
                             ,''                            //連携システム更新者
                             //,$order->receipt_no            //受付NO
                             ,''                            //受付NO
                             ,'0'                           //削除フラグ
                             ,$order->pickup_st_date        //引取開始日時
                             ,$order->seat_class_id         //席種コード
                             ,'2'                           //入場方法 2;入場時にカメラ起動無し
                             ,''                            //入場後キーワード
                             ,''                           //入場前背景色
                             ,''                           //入場前文字色
                             ,''                           //入場後背景色
                             ,''                           //入場後文字色               
                            );

            //アプリ番号（購入）リスト         
            $purchaselist[] = array(
                                      $order->app_id            //購入時アプリ番号
                                     ,$order->updated_at->format('Y/m/d H:i:s') //更新日時
                                     ,$order->update_account_cd //更新者
                                     ,''                        //連携システム更新日時
                                     ,''                        //連携システム更新者
                                     ,$this->editUserCodeForMbps($order->user_code) //ユーザーコード
                                     ,$order->member_id         //会員ＩＤ
                                   );

          }    
          //画像情報重複削除
          //$imagelist = array_map("unserialize", array_unique(array_map("serialize", $imagelist)));
          //$imgOut = array_map("unserialize", array_unique(array_map("serialize", $imgOut)));

          //アプリ番号（購入）csv作成->zip出力
          $zip = $this->makeZip("purchaseinfo_", $purchaselist, 'mbps_purchasedata_', $outputTime);
          //作成したzipをencode
          $req = $this->zipEncode($zip);

          // api call
          $result = \MbpsApi::callApi('buyapp.api', $req);  
          if(isset($result->result) && $result->result)
          {
            //api連携成功の場合
            foreach ($target as $order)
            { 
              //GL_MOBAPASS_OUTPUT insert        
              $output = array(
                               'data_kbn'  => '3',                                   //データ区分 3:購入アプリ番号連携
                               'data_id'   => $order->seat_sale_id . $outputTime ,   //データID   GL_SEAT_SALE.seat_sale_id + outputtime
                               'file_name' => $zip,                                  //ファイル名             
                             );
              //GL_MBPS_OUTPUT insert
              $this->MobapassOutputRepositories->mbpsOutputInsert($output);
            }  
          }else{
            Log::debug('****アプリ番号（購入）連携失敗****:' . $zip);      
            $exception = new \Exception('****アプリ番号（購入）連携失敗****:' . $zip);
            //メール通知
            Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
            throw $exception;
          }          
          Log::debug('アプリ番号（購入）:' .  $zip);  

          //画像csv作成->zip出力
          //$zip = $this->makeZip("imageinfo_", $imagelist, 'mbps_imagedata_', $outputTime);
          //作成したzipをencode
          //$req = $this->zipEncode($zip);       
          // api call
          //$result = \MbpsApi::callApi('image.api', $req);  
          //if(isset($result->result) && $result->result)
          //{
            ////api連携成功の場合
            //foreach ($imgOut as $img)
            //{ 
              //GL_MOBAPASS_OUTPUT insert        
              //$output = array(
              //                 'data_kbn'  => '5',                                     //データ区分 5:画像
              //                 'data_id'   => $img['performance_code'] . $outputTime , //データID   performance_code + outputtime
              //                 'file_name' => $zip,                                    //ファイル名             
              //               );
              //GL_MBPS_OUTPUT insert
              //$this->MobapassOutputRepositories->mbpsOutputInsert($output);
            //}  
          //}else{
            //Log::debug('****画像連携失敗****:' . $zip);      
            //$exception = new \Exception('****画像連携失敗****:' . $zip);
            //メール通知
            //Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
            //throw $exception;
          //}    
           //Log::debug('公演画像:' .  $zip);

          //予約情報csv作成->zip出力
          $zip = $this->makeZip("orderinfo_", $orderlist, 'mbps_orderdata_', $outputTime);
          //作成したzipをencodeし、json形式に変換
          $req = $this->zipEncode($zip);  
                    
          // api call
          $result = \MbpsApi::callApi('reserve.api', $req);  
          if(isset($result->result) && $result->result)
          {
            //api連携成功の場合
            foreach ($target as $order)
            { 
              //GL_MOBAPASS_OUTPUT insert        
              $output = array(
                               'data_kbn'  => '1',                                 //データ区分 1:予約連携
                               'data_id'   => $order->seat_sale_id . $outputTime , //データID   GL_SEAT_SALE.seat_sale_id + outputtime
                               'file_name' => $zip,                                //ファイル名             
                             );
              //GL_MBPS_OUTPUT insert
              $this->MobapassOutputRepositories->mbpsOutputInsert($output);
              //GL_GENERAL_RESERVATION update - mobapass_trans_flg
              $this->MobapassOutputRepositories->generalReservationMbpsUpdate($order->order_id,\Config::get('constant.mobapass_trans_flg.on'));
              //GL_SEAT_SALE update - issue_flg
              $this->MobapassOutputRepositories->seatSaleIssueFlgUpdate($order->order_id,$order->reserve_seq);             
            }  
          }else{
            //api連携失敗の場合    
            Log::debug('****予約情報連携失敗****:' . $zip);      
            $exception = new \Exception('****予約情報連携失敗****:' . $zip);
            //メール通知
            Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
            throw $exception;
          }     
          Log::debug('予約情報出力:' . $zip);          
        }
        Log::debug('予約情報/アプリ番号（購入）');

        //取消情報出力
        Log::debug('取消情報出力 start');
        $cancel = $this->MobapassOutputRepositories->getMobapassCancelTarget();
        if (count($cancel) == 0) 
        {
          Log::debug('取消情報出力 target none');
        }else{
          Log::debug('取消情報出力:' . count($cancel) . '件');
          foreach ($cancel as $cancelInfo)
          {
            //予約取消リスト 
            $cancellist[] = array(
                                   $this->editUserCodeForMbps($cancelInfo->user_code), //ユーザーコード'
                                   $cancelInfo->performance_code, //公演コード'
                                   $cancelInfo->performance_date, //公演日
                                   $cancelInfo->stage_num,        //ステージコード'
                                   $cancelInfo->reserve_no,       //予約番号
                                   $cancelInfo->reserve_seq,      //連番
                                   $cancelInfo->cancel_flg,       //取消区分
                                 );
          }   
          //取消情報 csv作成->zip出力
          $zip = $this->makeZip("cancelinfo_", $cancellist, 'mbps_canceldata_', $outputTime);
          //作成したzipをencode
          $req = $this->zipEncode($zip);  

          // api call
          $result = \MbpsApi::callApi('cancel.api', $req);  
          if(isset($result->result) && $result->result)
          {
            //api連携成功の場合
            foreach ($cancel as $cancelInfo)
            { 
              //GL_MOBAPASS_OUTPUT insert        
              $output = array(
                               'data_kbn'  => '2',                                      //データ区分 2:予約取消
                               'data_id'   => $cancelInfo->seat_sale_id . $outputTime , //データID   GL_SEAT_SALE.seat_sale_id + outputtime
                               'file_name' => $zip,                                     //ファイル名             
                             );
              //GL_MBPS_OUTPUT insert
              $this->MobapassOutputRepositories->mbpsOutputInsert($output);
              //GL_GENERAL_RESERVATION update - mobapass_cancel_flg
              $this->MobapassOutputRepositories->generalReservationMbpsCancelUpdate($cancelInfo->order_id);
            }  
          }else{
            Log::debug('****取消情報連携失敗****:' . $zip);      
            $exception = new \Exception('****取消情報連携失敗****:' . $zip);
            //メール通知
            Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
            throw $exception;
          }     
          Log::debug('取消情報出力:' .  $zip);
        }        
        Log::debug('取消情報出力 end');

        Log::debug('アプリ番号（譲渡） start');
        $transfer = $this->MobapassOutputRepositories->getMobapassTransferTarget();             
        if (count($transfer) == 0) {
          Log::debug('アプリ番号（譲渡） target none');
        }else{      
          //Log::debug('アプリ番号（譲渡）:' . count($transfer) . '件');
          foreach ($transfer as $transInfo)
          {
            //未入場リスト         
            $transferlist[] = array(
                                      $transInfo->app_id            //購入時アプリ番号
                                     ,$transInfo->updated_at        //更新日時
                                     ,$transInfo->update_account_cd //更新者
                                     ,''                            //連携システム更新日時
                                     ,''                            //連携システム更新者
                                     ,$this->editUserCodeForMbps($transInfo->user_code) //ユーザーコード
                                     ,$transInfo->member_id         //会員ＩＤ
                                   );
          }
          //譲渡情報重複削除
          $transferlist = array_map("unserialize", array_unique(array_map("serialize", $transferlist)));
          Log::debug('アプリ番号（譲渡）:' . count($transferlist) . '件');
           //譲渡情報 csv作成->zip出力
          $zip = $this->makeZip("transferinfo_", $transferlist, 'mbps_transferdata_', $outputTime);         
          //作成したzipをencode
          $req = $this->zipEncode($zip);  

          // api call
          $result = \MbpsApi::callApi('transferapp.api', $req);       
          if(isset($result->result) && $result->result)
          {
            //api連携成功の場合
            foreach ($transfer as $transInfo)
            { 
              //GL_MOBAPASS_OUTPUT insert        
              $output = array(
                               'data_kbn'  => '4',                                     //データ区分 4:譲渡アプリ番号連携
                               'data_id'   => $transInfo->seat_sale_id . $outputTime , //データID   GL_SEAT_SALE.seat_sale_id + outputtime
                               'file_name' => $zip,                                    //ファイル名             
                             );
              //GL_MBPS_OUTPUT insert
              $this->MobapassOutputRepositories->mbpsOutputInsert($output);
            }  
          }else{
            Log::debug('****アプリ番号（譲渡）情報連携失敗****:' . $zip);      
            $exception = new \Exception('****アプリ番号（譲渡）情報連携失敗****:' . $zip);
            //メール通知
            Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
            throw $exception;
          }           
          Log::debug('アプリ番号（譲渡）:' . $zip);          
        }
        Log::debug('アプリ番号（譲渡） end');
                   
        //圧縮済のcsv削除
        // 拡張子が.csvのファイルをglobで取得しループ処理
        $dir = glob(storage_path(config('app.mbps_temp_path')) . '/*.csv');
        foreach ($dir as $file) 
        {
          // globで取得したファイルをunlinkで1つずつ削除していく
          unlink($file);
        }
        Log::debug('MobapassOutput end');
    }
    function makeCsvLine($data, $f)
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
    function makeZip($csvname, $data, $zipname, $outputTime)
    {
      //csv出力
      $f = fopen(storage_path(config('app.mbps_temp_path')) . "/" . $csvname .$outputTime . ".csv", "w"); //gettiilite\public
      if($f)
      {
        $this->makeCsvLine($data, $f);
      }else{
        throw new \Exception("mobapass transport error : " . (string) $f);
      }
      fclose($f);            
      /* convert to sjis
      $f = fopen(storage_path(config('app.mbps_temp_path')) . "/" . $csvname .$outputTime . ".csv", "w");
      fwrite($f, $this->arr2csv($data)); 
      fclose($f);  
      */  
      //圧縮処理
      $zipFileName = $zipname . $outputTime . '.zip';
      $zip = new ZipArchive;
      $zip->open(storage_path(config('app.mbps_save_path')) . '/' . $zipFileName, ZipArchive::CREATE);
      $zip->addFile(storage_path(config('app.mbps_temp_path')) .  "/" . $csvname  . $outputTime . '.csv',  "/" . $csvname  . $outputTime . '.csv');
      $zip->close();    
      return $zipFileName;
    }
    function arr2csv($fields) 
    {
      $fp = fopen('php://temp', 'r+b');
      foreach($fields as $field) 
      {
        fputcsv($fp, $field);
      }
      rewind($fp);
      $tmp = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
      $tmp = str_replace("\"", "", $tmp); //remove double quote
      return mb_convert_encoding($tmp, 'SJIS', 'UTF-8');
    }   
    function zipEncode($zip)
    {
      $enc = file_get_contents(storage_path(config('app.mbps_save_path')) .  "/" . $zip);
      $enc =  base64_encode($enc);    
      $enc = ['csv'=>$enc];
      $enc =  json_encode($enc);
      return $enc;
    }   
    function editUserCodeForMbps($usercode)
    {
      $usercode = ((\App::getLocale() == "ja" )? '988':'999') . $usercode;
      return $usercode;
    }       
}
