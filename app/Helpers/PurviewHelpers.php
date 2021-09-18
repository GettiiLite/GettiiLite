<?php

namespace App\Helpers;

class PurviewHelpers
{
    /**
     * 將部分資料轉換成隱碼
     * 0:閲覧可 1:閲覧不可
     * @param string $data
     * @return string
     */
    public function hideInformation($data, $type = null)
    {
        $personal_info_flg = session('personal_info_flg');

        if($personal_info_flg == 1 && strlen($data) > 0){
            switch($type){
                case 'email':
                    $email    = explode("@", $data);
                    $mailHide = preg_replace('/(.)./', '$1*', $email[0]);
                    $result   = $mailHide.'@'.$email[1];
                    break;
                default:
                    $result =  preg_replace('/(.)./', '$1*', $data);
            }
            
        }else{
            $result = $data;
        }

        return $result;
    }
}
