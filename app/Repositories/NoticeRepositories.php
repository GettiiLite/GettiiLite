<?php

namespace App\Repositories;

use App\Models\UserAccountModel;
use Exception;
use Log;
use App;
use Carbon\Carbon;

class NoticeRepositories
{
    /** @var UserAccountModel */
    protected $UserAccountModel;

    /**
     * NoticeRepositories constructor
     * @param NoticeRepositories $NoticeRepositories
     */
    public function __construct(UserAccountModel $UserAccountModel)
    {
        $this->UserAccountModel = $UserAccountModel;
    }
    /**
     * 設定 UserAccountModel
     * @param  int $account_cd
     * @return bool $result
     */ 
    public function setUserAccount($account_cd){   
        try{
            $this->UserAccountModel = UserAccountModel::findOrFail($account_cd);
            return true;
        }catch (Exception $e){
            return false;
        }
    }
   /**
     * 取得 remind_code
     * @return int $remind_code
     */ 
    public function getUserAccountNoticeInfo(){   
       try {
            $pw_change = false;
            $remind_code = \Config::get('constant.remind_code.none');
            $user = $this->UserAccountModel->load('user')->user;

            if($user->user_status == 0){
                $remind_code = \Config::get('constant.remind_code.inf');
            }else if($this->UserAccountModel->pw_renew_date){
                $now_date = strtotime("now");
                $pw_change = Carbon::now()->gt(Carbon::parse($this->UserAccountModel->pw_renew_date));
               
                if($pw_change){
                    $remind_code = \Config::get('constant.remind_code.password');
                }
            }
            
            return $remind_code;
        }catch (Exception $e) {
            Log::info('S_EXC_MSN_0007 :'.$e->getMessage());
            throw new Exception ('S_EXC_MSN_0007 :'.$e->getMessage());
        }
    }
   
}
