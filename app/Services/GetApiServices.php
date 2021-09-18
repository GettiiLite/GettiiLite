<?php

namespace App\Services;

use Log;
use Exception;
use App;

class GetApiServices
{

    /**
     * UserController constructor.
     * @param 
     * @return
     */
    public function __construct()
    {
    }  
    /**
     * get api data
     * @param $data
     * @return $result
     */
    public function get($data){
        try{
            $options = array(
                CURLOPT_URL            => $data['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_CONNECTTIMEOUT => 120,
                CURLOPT_TIMEOUT        => 120,
                CURLOPT_MAXREDIRS      => 10,
            );

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $apiData     = curl_exec($ch);
            $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
           switch($httpCode){
                case 200:
                    $resultStatus   =   true;
                    $memberData = json_decode($apiData,true);
                    break;
                case 400:
                case 404:
                default:
                    $resultStatus   =   false;
                    $memberData     =   Null;
                    break;   
            }
           
            $status = array(
                'result'    =>    $resultStatus,
                'httpCode'  =>    $httpCode,
            );

            $resultData  =   array(
                'memberData'  =>   $memberData,
            );

            $result = array(
                'status'   =>   $status,
                'data'     =>   $resultData,
            );
           
            if(!$resultStatus){
                throw new Exception('http status : '.$resultStatus.' | api error');
            }

        }catch(Exception $e){
            Log::error($e);
            
            $status = array(
                'result'    =>    false,
                'httpCode'  =>    $httpCode,
            );

            $resultData  =   array(
                'memberData'  =>   null,
            );

            $result = array(
                'status'   =>   $status,
                'data'     =>   $resultData,
            );
        }
       
        return $result;
    }

}