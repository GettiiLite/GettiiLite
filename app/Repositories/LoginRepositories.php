<?php

namespace App\Repositories;

use App\Models\UserManageModel;
use App\Models\UserAccountModel;
use App\Models\UserExModel;
use App\Models\CommissionClientModel;
use Illuminate\Support\Facades\Hash;
use Exception;
use Log;
use App;
use Carbon\Carbon;

class LoginRepositories
{
    /** @var UserManageModel */
    protected $UserManageModel;
    /** @var UserAccountModel */
    protected $UserAccountModel;
    /** @var UserExModel */
    protected $UserExModel;
    /** @var CommissionClientModel */
    protected $CommissionClientModel;

    /**
     * UserManageModel constructor.
     * @param UserManageModel $UserManageModel
     * @param UserAccountModel $UserAccountModel;
     */
    public function __construct(UserManageModel $UserManageModel, UserAccountModel $UserAccountModel, UserExModel $UserExModel, CommissionClientModel $CommissionClientModel)
    {
        $this->UserManageModel = $UserManageModel;
        $this->UserAccountModel = $UserAccountModel;
        $this->UserExModel = $UserExModel;
        $this->CommissionClientModel = $CommissionClientModel;
    }
    /**
     * cheak account had sama
     * @param array $account
     * @return result 
     */ 
    public function login($account)
    {   try{
                $glCode =  $this->UserManageModel::select('GLID')->where('user_id', $account->companyId)
                                                                 ->where('user_status', '<>' , -2)
                                                                 ->get();
                
                if ($glCode->isEmpty()){
                    return false;
                }
                
                $result = $this->UserAccountModel->where('GLID', $glCode[0]['GLID'])
                                                ->where('account_code', $account->userId)
                                                ->where('status',  1)
                                                ->where('expire_date', '>' , now())
                                                ->get();
                if ($result->isEmpty()){
                   return false;
                }
                
                $result = password_verify($account->userPassword , $result[0]['password']);
                
                return $result; 
            }catch(Exception $e){
                Log::info('login error :'.$e->getMessage());
                App::abort(500);
            }
    }
    /**
     * get user_code
     * @param array $account
     * @return result 
     */ 
    public function userData($account)
    {    
        try{
            $result =  $this->UserManageModel::where('user_id', $account->companyId)
                                                ->with('UserEX')
                                                ->get();
          
            return $result; 
        }catch(Exception $e){
            Log::info('register :'.$e->getMessage());
            App::abort(500);
        }
    }

    /**
     * get user_code
     * @param array $account
     * @return result 
     */ 
    public function accountData($GLID, $userId)
    {   try{
            $result = $this->UserAccountModel->where('GLID', $GLID)
                                            ->where('account_code', $userId)
                                            ->get();

        
            return $result[0]; 
        }catch(Exception $e){
            Log::info('register :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * register account
     * @param array $accountData
     * @return result 
     */ 
    public function register($accountData)
    {    
        try{
            $GLID = $this->UserManageModel ->insertGetId(
                [
                    'user_code'=>$accountData["user_code"],
                    'user_id'=>$accountData["user_id"],
                    'user_status'=>0,
                    'contract_name'=>$accountData["contract_name"],
                    'contract_name_kana'=>$accountData["contract_name_kana"],
                    'tel_num'=>$accountData["tel_num"],
                    'mail_address'=>$accountData["mail_address"],
                    'post_code'=>$accountData["post_code"],
                    'post_display'=>$accountData["post_display"],
                    'address'=>$accountData["address"],
                    'department'=>$accountData["department"],
                    'contact_person'=>$accountData["contact_person"],
                    'mail_address'=>$accountData["mail_addres"],
                    'temporary_info'=>$accountData["temporary_info"],
                    'user_kbn' => $accountData["user_kbn"],
                    "app_date" =>  \Carbon\Carbon::now(),
                    "request_date" => \Carbon\Carbon::now(),
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                    'SID' => 1,
                ]
            );
            
            $user = $this->UserManageModel->find($GLID);
            $user->user_code = $GLID+100000;
            $user->save();

            // $glCode =  $this->UserManageModel::select('GLID')->where('user_code', $accountData["user_code"])->get();
          
            $result = $this->UserAccountModel->insert(
                [
                    'GLID'=>$GLID,
                    'account_number'=>0,
                    'account_code'=>$accountData["account_code"],
                    'mail_address'=>$accountData["mail_address"],
                    'password'=>$accountData["password"],
                    'pw_renew_date'=> Carbon::now()->addDays(180)->format('Y-m-d H:i:s'),
                    'profile_info_flg'=>2,
                    'event_info_flg'=>2,
                    'sales_info_flg'=>2,
                    'personal_info_flg'=>0,
                    'status'=>1,
                    'remarks'=>'Default administartor',
                    'expire_date'=>"9999-12-31",
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ]
            );
            
            if(!empty($accountData["introduction"]))
            {
                //紹介コード
                $result = $this->UserExModel->insert(
                                                     [
                                                       'GLID'     =>$GLID,
                                                       'parameter'=>'SHOUKAI',
                                                       'value'    =>$accountData["introduction"],
                                                       "created_at" =>  \Carbon\Carbon::now(),
                                                       "updated_at" => \Carbon\Carbon::now(),
                                                     ]
                                                    );
            }
            
            $result = $this->UserExModel::updateOrCreate(
                            [
                                'GLID' => $GLID,
                                'parameter' => 'ALLOWFREE',
                            ], 
                            [
                                'value' => 1,
                                "created_at" =>  \Carbon\Carbon::now(),
                                "updated_at" => \Carbon\Carbon::now(),
                            ]
                        );

            $result = $this->CommissionClientModel::updateOrCreate(
                            [
                                'GLID' => $GLID,
                                'commission_type' => '0',
                            ], 
                            [
                                'apply_date' => \Carbon\Carbon::now()->toDateString(),
                                "rate" =>  '4.00',
                                "amount" => '0.00',
                                'update_account_cd' => 1,
                                "created_at" =>  \Carbon\Carbon::now(),
                                "updated_at" => \Carbon\Carbon::now(),
                            ]
                        );
            return $result; 

        }catch(Exception $e){
            Log::info('error code : 211 | error messeger : '.$e->getMessage());
            return false;
        }
    }    
    /**
     * change account password
     * @param array $account
     * @return result 
     */ 
    public function accountPasswork($account)
    {   
        $glCode =  $this->UserManageModel::select('GLID')
                                            ->where('user_id', $account['companyId'])
                                            ->where('tel_num', $account['tel'])
                                            ->where('mail_address', $account['mail'])
                                            ->get();
        
        if ($glCode->isEmpty()){
           return false;
        }
       
        $result = $this->UserAccountModel->where('GLID', $glCode[0]['GLID'])
                                         ->where('account_number', 0)
                                         ->update([
                                             'password'=>$account['password'],
                                          ]);
    
        return $result; 
    }
    /**
     * account change password
     * @param array $account
     * @return result 
     */ 
    public function accountChangePasswork($account)
    {   
        try{
            $userData = $this->UserAccountModel->where('GLID', $account['GLID'])
                                            ->where('account_cd', $account['account_cd'])
                                            ->get();

            $result = password_verify($account['userPassword'] , $userData[0]['password']);
            if($result){
                $user_account = UserAccountModel::findOrFail($account['account_cd']);
                $user_account->password = $account['password'];
                $user_account->recent_password    = $account['recent_password']; //STS 2021/08/30 Task 48 No.4
                $user_account->pw_renew_date = Carbon::now()->addDays(180)->format('Y-m-d H:i:s');
                $user_account->save();       
            }
        
            return $result; 
        }catch(Exception $e){
         
            Log::info('register :'.$e->getMessage());
            App::abort(500);
        }
    }

    //STS 2021/08/30 Task 48 No.4 start
    /**
     * Check password
     * @param array $account
     * @return result 
     */ 
    public function checkPassWord($account)
    {   
        try{
            $userData = $this->UserAccountModel->where('GLID', $account['GLID'])
                                            ->where('account_cd', $account['account_cd'])
                                            ->get();

            $result = password_verify($account['userPassword'] , $userData[0]['password']);
            return $result; 
        }catch(Exception $e){
            Log::info('checkPassWord:'.$e->getMessage());
            App::abort(500);
        }
    }
     /**
     * Get recent passwords
     * @param array $account_cd
     * @return $result[0]->recent_password
     */ 
    public function getRecentPassword($account_cd)
    {   
        try{
            $result = $this->UserAccountModel   ->select('recent_password')
                                                ->where('account_cd', $account_cd)
                                                ->get();
            return $result[0]->recent_password; 
        }catch(Exception $e){
            Log::info('getRecentPassword :'.$e->getMessage());
            App::abort(500);
        }
    }
    //STS 2021/08/30 Task 48 No.4 end
}
