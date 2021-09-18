<?php

namespace App\Services;

use Log;
use Exception;
use App;
use App\Repositories\MemberRepositories;
use App\Repositories\SellManageRepositories;

class PostApiServices
{
    /** @var MemberRepositories */
    protected $MemberRepositories;
    /** @var SellManageRepositories */
    protected $SellManageRepositories;


    /**
     * UserController constructor.
     * @param 
     * @return
     */
    public function __construct(MemberRepositories $MemberRepositories, SellManageRepositories $SellManageRepositories)
    {
        $this->MemberRepositories     = $MemberRepositories;
        $this->SellManageRepositories = $SellManageRepositories;
    }  

    public function amountChange($order_id, $patch_data) {
        if(!$this->SellManageRepositories->getOrderByOrderID($order_id)) {
            return false;
        }
            
        $site = $this->SellManageRepositories->getAPISitebyOrder();
        if($site == null || '' == $site) {
            return false;
        }
        $reserve_no = $this->SellManageRepositories->getReserveNo();
        $url = $site.'/sales/'.$reserve_no;
        return $this->_patch($url,$patch_data);
    }

    /**
     * 傳送patch資料
     * @param $url, $data
     * @return $result
     */
    private function _patch($url, $rowData){
        try{
            $data['data'] = $rowData;
            $fields['sale_json'] = json_encode($data);
            $timeout = 120;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FAILONERROR, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
            $api_data    = curl_exec($ch);
            $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result_data = json_decode($api_data);
            switch($httpCode){
                 case 201:
                     Log::info('patch http code : '.$httpCode);
                     return true;
                     break;
                 case 400:
                 case 404:
                 default:
                     Log::error('[ERROR]patch http code : '.$httpCode.'| URL ：'.$url);
                     break;     
             }
             return false;
            
         }catch(Exception $e){
             Log::error($e);
         }
     }

    /**
     * 傳送票卷資料
     * @param $data
     * @return $result
     */
    public function post($data){
       try{
            $api_site       = $this->MemberRepositories->getAPISite(session('GLID'));
            $reserve_inf    = 'performance_code : '.$data['performance_code'].' | reserve_no : '.$data['reserve_no'];
            $timeout = 120;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_site.'/sales?json='.$data['json']);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FAILONERROR, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $api_data    = curl_exec($ch);
            $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // dd($api_data); //"{"msn":"\u540c\u6b65\u6210\u529f","status":{"http_code":201,"sales_status":true},"data":[]}"
            // list($header_post, $header, $json) = explode("\r\n\r\n", $api_data, 3);
            $result_data = json_decode($api_data);
            
           switch($httpCode){
                case 201:
                    Log::info($reserve_inf.'http code : '.$httpCode.'| 結果 ：'.$result_data->msn);
                    return true;
                    break;
                case 400:
                case 404:
                default:
                    Log::warning($reserve_inf.'[ERROR] http code : '.$httpCode.'| URL ：'.$api_site.'/sales?json='.$data['json']);
                    break;     
            }
            return false;
           
        }catch(Exception $e){
            Log::warning($e);
        }
    }

}