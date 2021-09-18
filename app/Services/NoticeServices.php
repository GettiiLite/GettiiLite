<?php

namespace App\Services;

use Log;
use Exception;
use App;
use Storage;
use App\Repositories\NoticeRepositories;

class NoticeServices
{
    /** @var  NoticeRepositories */
    protected $NoticeRepositories;

    /**
    * UserController constructor.
    */
    public function __construct(NoticeRepositories $NoticeRepositories)
    {
        $this->NoticeRepositories = $NoticeRepositories;
    } 
    /**
     * 資訊頁首頁
     * @return array $result
     */
    public function index(){
        $result = [
            'statuc' => [
                'HTML_exist'  =>  false,
                'CSS_exist'   =>  false,
            ],
            'data' => [
                'HTML_content'  =>  '',
                'CSS_content'   =>  '',
                'remind_code' => false,
            ],  
        ];

        $HTML_exist = Storage::has('public/notice/index.html');
        $CSS_exist  = Storage::has('public/notice/notice.css');

        if($HTML_exist){
            $result['statuc']['HTML_exist'] = $HTML_exist;
            $result['data']['HTML_content'] = Storage::get('public/notice/index.html');
        }
       
        if($CSS_exist){
            $result['statuc']['CSS_exist']  = $CSS_exist;
            $result['data']['CSS_content']  = Storage::get('public/notice/notice.css');
        }
        try {
           if(session()->exists('status')){
                $account_cd = session('account_cd');
                $this->NoticeRepositories->setUserAccount($account_cd);
                $result['data']['remind_code'] = $this->NoticeRepositories->getUserAccountNoticeInfo($account_cd);
           }
        }catch (Exception $e) {
            //錯誤處理
        }
       
        return $result;
    }

}