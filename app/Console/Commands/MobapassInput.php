<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use Log;
use Mail;
use FilesystemIterator;
use Storage;
use ZipArchive;
use App\Repositories\MobapassInputRepositories;
use App\Mail\ExceptionMail;

// php artisan MobapassOutput
class MobapassInput extends Command
{
    protected $MobapassOutputRepositories;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MobapassInput';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gettii Liteモバパス連携（入場）';

    /**
     * Create a new command instance.
     *
     * @return void
     */
  public function __construct(MobapassInputRepositories $MobapassInputRepositories)
  {
    $this->MobapassInputRepositories = $MobapassInputRepositories;
    parent::__construct();
  }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('MobapassInput start');
        $InputTime = str_replace('/', '', date("Y/m/d")) . str_replace(':', '', date("H:i:s"));
        
        //入場情報取込
        Log::debug('入場情報取込 start');  
        // api call
        $req = ['csv'=>date("Y/m/d")];
        $req =  json_encode($req);
        $result = \MbpsApi::callApi('entering.api',$req);  

        //todo 確認 ファイル無しとapiエラーのstatusコード
        if(!isset($result->csv))
        {
          Log::debug('入場情報取込 該当ファイル無し');  
          Log::debug('入場情報取込 end');  
          exit;
        }

        $zip_file = storage_path(config('app.mbps_save_path')) . '/tempdir/' . time() . '.zip';
        $dest_dir = storage_path(config('app.mbps_save_path')) . '/enteringCsv';
        
        file_put_contents($zip_file, base64_decode($result->csv));
        Log::info('zip: '.$zip_file);
        $zip = new \ZipArchive();
        $res = $zip->open($zip_file);
        if ($res === true) 
        {
          $zip->extractTo($dest_dir);
          $zip->close();
        } else {
          throw new \Exception('zipファイルの展開に失敗しました。');
        }
     
        // csvを一つずつ読み込み、DBに取り組む
        try 
        {
          DB::beginTransaction();
          $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dest_dir,
                                                                                      FilesystemIterator::CURRENT_AS_FILEINFO |
                                                                                      FilesystemIterator::KEY_AS_PATHNAME |
                                                                                      FilesystemIterator::SKIP_DOTS));
          foreach ($iterator as $file_info) 
          { 
            if ($file_info->isFile()) 
            {
              // 入場情報の保存
              Log::debug('file name:' . $file_info->getPathname());
              $this->updateEnteringInfo($this->getCsvData($file_info->getPathname()));
            }
          }
          DB::commit();
        } catch (\Exception $e) {
          DB::rollback();
          Log::error($e);
          \Mail::to(config('app.exception_notification_address'))->send(new ExceptionMail($e));
        }      
        Log::debug('入場情報取込  end');
        Log::debug('MobapassInput end');

        // csvとzipファイルを削除する
        //unlink($zip_file); 更新失敗時に手動で更新する必要がある為、zipは残す。
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dest_dir, \FilesystemIterator::SKIP_DOTS)
                                                                              , \RecursiveIteratorIterator::CHILD_FIRST
                                                );

        foreach ($files as $file) 
        {
          if ($file->isDir() === true) 
          {
            rmdir($file->getPathname());
          }else{
            unlink($file->getPathname());
          }
        }
        rmdir($dest_dir);
    }
    /**
     * CSVを配列に展開する
     */
    private function getCsvData($file_path)
    {
        $result = [];
        $header = [];

        // CSV読み込み
        $file = new \SplFileObject($file_path);
        $file->setFlags(\SplFileObject::READ_CSV);
        foreach ($file as $key => $line) {
            // header
            //if ($key === 0) {
            //    foreach ($line as $header_value) {
            //      $header[] = strtolower($header_value);
            //    }
            //    continue;
            //}
            // 終端の空行は除く
            // ヘッダー行とカラム数が一致しないデータは登録しない
            if (!is_null($line) /*&& count($header) === count($line)*/) {
                //$row = [];
                //foreach ($line as $line_key => $col_value) {
                //    $row[$header[$line_key]] = $col_value;
                //}
                $result[] = $line;
            }
        }
        return $result;
    }    
    /**
     * 来場フラグ更新
     */
    private function updateEnteringInfo($data)
    {
      //order_id取得  
      //$cnt = count($data) - 1;
      $cnt = 0;
      //Log::debug('入場情報:' . $cnt . '件');
      foreach ($data as $row) 
      { 
        if(is_null($row[0]))continue; //csv最終行
        try 
        {
          $orderId = $this->MobapassInputRepositories->getOrderIdByReserveNumber($row[4]); //予約ID
          
          //GL_SEAT_SALES search
          $visit_flg = $this->MobapassInputRepositories->searchVisitFlg($orderId,$row[5]);
          if($visit_flg == '1')continue; //入場済は更新処理skip
          $cnt++;
          
          //GL_SEAT_SALES update
          Log::debug('$orderId and seq:' . $orderId . '_' . $row[5]);
          $visitInfo = array(
                              'order_id'    => $orderId,  //予約ID
                              'reserve_seq' => $row[5] ,  //予約連番
                              'visit_flg'  => $row[7] ,   //入場フラグ
                              'visit_date'  => $row[8] ,  //入場日時
                             );
          $this->MobapassInputRepositories->visitFlgUpdate($visitInfo);
          //GL_MOBAPASS_INPUT inserts
          $input = array(
                         'data_kbn'  => '1',                                    //データ区分 1:入場連携
                         'data_id'   => $orderId . '_' . $row[5] ,              //データID  予約番号_連番
                         );       
          $this->MobapassInputRepositories->mbpsInputInsert($input);         
        }catch(\Exception $e) {
          Log::debug('入場情報の設定でエラーが発生しました。('.$orderId.')\n'.$e);
          $exception = new \Exception('入場情報の設定でエラーが発生しました。('.$orderId .')');
          //メール通知
          Mail::to(\Config::get('app.exception_notification_address'))->send(new ExceptionMail());
          throw $exception;
        }      
      }
      Log::debug('入場情報:' . $cnt . '件');
    }
}
