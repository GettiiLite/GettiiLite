<?php

namespace App\Services;

use Log;
use Exception;
use App;
use Storage;

class HelpServices
{
   
    /**
     * 資訊頁首頁
     * @return $result
     */
    public function index(){
        $result = [
            'statuc'      => [
                                    'HTML_exist'  =>  false,
                                    'CSS_exist'   =>  false,
            ],
            'data'        =>  [
                                    'HTML_content'  =>  '',
                                    'CSS_content'   =>  '',
            ],  
        ];

        $HTML_exist = Storage::has('public/help/index.html');
        $CSS_exist  = Storage::has('public/help/notice.css');

        if($HTML_exist){
            $result['statuc']['HTML_exist'] = $HTML_exist;
            $result['data']['HTML_content'] = Storage::get('public/help/index.html');
        }
       
        if($CSS_exist){
            $result['statuc']['CSS_exist']  = $CSS_exist;
            $result['data']['CSS_content']  = Storage::get('public/help/notice.css');
        }

        return $result;
    }

}