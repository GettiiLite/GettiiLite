<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Mail;
use Storage;
use ZipArchive;
use App\Repositories\SellManageRepositories;
use App\Mail\ExceptionMail;

// php artisan MobapassOutput
class PointBooking extends Command
{
    protected $SellManageRepositories;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PointBooking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GETTIISにポイント連携するcsvを出力する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
  public function __construct(SellManageRepositories $SellManageRepositories)
  {
    $this->SellManageRepositories = $SellManageRepositories;
    parent::__construct();
  }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('PointBooking start');
        $imagelist = array();
        $imgOut = array();
        $outputTime = str_replace('/', '', date("Y/m/d")) . str_replace(':', '', date("H:i:s"));
        
        $point = array(
                        //ヘッダー
                        array(
                          "状態区分",
                          "購入ID",
                        ),
                      );
        //point csv出力
        //入金
        $targetSale = $this->SellManageRepositories->getSalePointOutputTarget();     
        if (count($targetSale) == 0) {
          Log::debug('point sale 連携対象 none');
        }else{
          Log::debug('point sale 連携対象:' . count($targetSale) . '件');
          
          foreach ($targetSale as $sale)
          { 
            //purchaseid生成  
            $purchaseId = $this->makePurchaseId($sale->user_code,$sale->reserve_no);  
              
            $point[] = array(
                              1           //状態区分
                             ,$purchaseId //購入ID
                            );
          }  
        }
          
        //seven未支払い ポイント使用 キャンセル
        $targetCancel_1 = $this->SellManageRepositories->getCancelSevenUnpaidPointOutputTarget();     
        if (count($targetCancel_1) == 0) {
          Log::debug('point cancel seven 未支払ポイント使用 連携対象 none');
        }else{
          Log::debug('point cancel 連携対象:' . count($targetCancel_1) . '件');
          
          foreach ($targetCancel_1 as $cancel)
          { 
            //purchaseid生成  
            $purchaseId = $this->makePurchaseId($cancel->user_code,$cancel->reserve_no);  

            $point[] = array(
                              9           //状態区分
                             ,$purchaseId //購入ID
                            );
          }               
        }  
        
        //支払済キャンセル
        $targetCancel_2 = $this->SellManageRepositories->getCancelPaidPointOutputTarget();     
        if (count($targetCancel_2) == 0) {
          Log::debug('point cancel 支払い済連携対象 none');
        }else{
          Log::debug('point cancel 連携対象:' . count($targetCancel_2) . '件');
          
          foreach ($targetCancel_2 as $cancel)
          { 
            //purchaseid生成  
            $purchaseId = $this->makePurchaseId($cancel->user_code,$cancel->reserve_no);  

            $point[] = array(
                              9           //状態区分
                             ,$purchaseId //購入ID
                            );
          }               
        }  
                
        $f = fopen(storage_path(config('app.mst_temp_path')) . "/gettiis_rstatus_" . $outputTime . ".csv", "w"); 
        if ($f) {
          $this->makeCsvLine($point, $f);
        } else {
          throw new Exception("create point csv error : " . (string) $f);
        }
        fclose($f);          
    
        //圧縮処理
        $zipFileName = 'gettiis_rstatus_' . $outputTime . substr(explode(".", microtime(true))[1], 0, 3) . '.zip';
        $zip = new ZipArchive;
        $zip->open(storage_path(config('app.mst_save_path')) . '/' . $zipFileName, ZipArchive::CREATE);
        $zip->addFile(storage_path(config('app.mst_temp_path')) . '/gettiis_rstatus_' . $outputTime . '.csv', 'gettiis_rstatus_' . $outputTime . '.csv');
        $zip->close();

        //圧縮済のcsv削除
        // 拡張子が.csvのファイルをglobで取得しループ処理
       $dir = glob(storage_path(config('app.mst_temp_path')) . '/*.csv');
       foreach ($dir as $file) {
          // globで取得したファイルをunlinkで1つずつ削除していく
          unlink($file);
       }
       //wirte to DB
        //GL_PORTAL_MST_OUTPUTにデータ作成
        $mstOutPut = array(
          'sight_id'    => 'rstatus',
          'data_id'     => 'rstatus_' . $outputTime ,
          'data_kbn'    => '3', //1:パッチデータ 2:スナップショットデータ 3:rstatus
          'output_date' => $outputTime,
          'corp_target' => '1',//0:対象外 1:対象
          'file_name'   => $zipFileName
        );  

        $this->SellManageRepositories->portalMstOutputInsert($mstOutPut);         

        Log::debug('PointBooking end');
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

      //圧縮処理
      $zipFileName = $zipname . $outputTime . '.zip';
      $zip = new ZipArchive;
      $zip->open(storage_path(config('app.mbps_save_path')) . '/' . $zipFileName, ZipArchive::CREATE);
      $zip->addFile(storage_path(config('app.mbps_temp_path')) .  "/" . $csvname  . $outputTime . '.csv',  "/" . $csvname  . $outputTime . '.csv');
      $zip->close();    
      return $zipFileName;
    }
    function makePurchaseId($user_code,$reserve_no)
    {
      //purchaseid生成  
      //format -> 2:[GL_USER.user_code]:[GL_GENERAL_RESERVATION.reserve_no]:[HASH-CODE]
      //[Has-code]
      //hmac-sha1(2:[GL_USER.user_code]:[GL_GENERAL_RESERVATION.reserve_no])
      //[key] = API apis.properties => gettiis.admin.key
      //hmac-sha1 key = RESERVE2@[key]
      $key = 'RESERVE2@'. \Config::get('app.GSadminKey');
      $hashCode = hash_hmac('SHA1', '2:'. $user_code . ':' . $reserve_no   , $key);
      $purchaseId = '2:'. $user_code . ':' . $reserve_no . ':' . $hashCode; 
      return $purchaseId;
    }   
}
