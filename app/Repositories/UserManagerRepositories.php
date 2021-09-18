<?php

namespace App\Repositories;

use App\Models\UserManageModel;
use App\Models\UserAccountModel;
use App\Models\UserExModel;
use App\Models\PortalMstOutputModel;
use Carbon\Carbon;
use Exception;
use App;
use Log;

class UserManagerRepositories
{
    /** @var UserManageModel */
    protected $UserManageModel;
    protected $UserAccountModel;
    /** @var UserExModel*/
    protected $UserExModel;
    /** @var PortalMstOutputModel*/
    protected $PortalMstOutputModel;

    /**
     * UserManageModel constructor.
     * @param UserManageModel $UserManageModel
     * @param UserAccountModel $UserAccountModel;
     */
    public function __construct(UserManageModel $UserManageModel, UserAccountModel $UserAccountModel, PortalMstOutputModel $PortalMstOutputModel, UserExModel $UserExModel)
    {
        $this->UserManageModel = $UserManageModel;
        $this->UserAccountModel = $UserAccountModel;
        $this->UserExModel = $UserExModel;
        $this->PortalMstOutputModel = $PortalMstOutputModel;
    }
    /**
     * change user status to 8
     * @param integer $subUserData
     * @return result 
     */ 
    public function applyDelAccount($GLID)
    {               
        try{                   
            $result = $this->UserManageModel->where('GLID', $GLID)
                                            ->update([
                                                'user_status'=>8,
                                            ]);
        
            return $result; 
        }catch(Exception $e){
            Log::info('function applyDelAccount| error code : 301 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }  
    /**
     * cheak account had sama
     * @param integer $subUserData
     * @return result 
     */ 
    public function cheakAccount($subUserData)
    {
        try{
            $ret = $this->UserAccountModel ->where('GLID', $subUserData['GLID'])
                                                ->where('account_code', $subUserData['account_code'])
                                                ->get();

            if( $ret->isEmpty() ) {
                $result = null;
            }else{
                $result = null;
                foreach( $ret as $acc ) {
                    if ($acc->status >= 0 ) 
                        $result = $acc->account_number;
                }
            }
        
            return $result; 
        }catch(Exception $e){
            Log::info('function cheakAccount| error code : 302 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }  
    /**
     * get user information data
     * @param integer GLID
     * @return array result
     */ 
    public function getInformation($GLID)
    {
        try{
            $userData =  $this->UserManageModel::where('GLID', $GLID)->get();
            $accountData = $this->UserAccountModel::where('GLID', $GLID)->get();
            $introduction = $this->UserExModel::select('value')->where('GLID', $GLID)->where('parameter', 'SHOUKAI')->get()->toArray();

            $result =array(
                "userData"=> $userData,
                "accountInf"=> $accountData,  
                "introduction"=> $introduction,  
            );

            return $result; 
        }catch(Exception $e){
            Log::info('function getInformation| error code : 303 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * add sub user data
     * @param integer $subUserData
     * @return result
     */ 
    public function addSubUser($subUserData)
    {    
        try{
            $tt = $this->UserAccountModel->where('GLID',$subUserData['GLID'])
                                    ->where('account_number',$subUserData['account_number'])
                                    ->get();
            if($tt->isEmpty()) {
                $result = $this->UserAccountModel->create(
                    [
                        'GLID'              =>  $subUserData['GLID'],
                        'account_number'    =>  $subUserData['account_number'],
                        'account_code'      =>  $subUserData['account_code'], 
                        'mail_address'      =>  $subUserData['mail_address'],
                        'password'          =>  $subUserData['password'],
                        'profile_info_flg'  =>  $subUserData['profile_info_flg'],
                        'event_info_flg'    =>  $subUserData['event_info_flg'],
                        'sales_info_flg'    =>  $subUserData['sales_info_flg'],
                        'member_info_flg'   =>  $subUserData['member_info_flg'],
                        'personal_info_flg' =>  $subUserData['personal_info_flg'],
                        'status'            =>  $subUserData['status'],
                        'remarks'           =>  $subUserData['remarks'],
                        'expire_date'       =>  $subUserData['expire_date'],
                        'update_account_cd' =>  $subUserData['update_account_cd'],
                    ]
                );            
                return $result;     
            }
            else {
                throw new Exception('account_number has duplicated');
            }
        }catch(Exception $e){
            Log::info('function addSubUser| error code : 304 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get sub user data
     * @param  $subUserData
     * @return result
     */ 
    public function getSubUserData($subUserData)
    {   
        try{
            $userData =  $this->UserManageModel::select('GLID')->where('user_id', $subUserData->account)->get();
        
            $result = $this->UserAccountModel->select('GL_ACCOUNT.*','GL_USER.contract_name')
                                             ->join('GL_USER','GL_ACCOUNT.GLID','=','GL_USER.GLID')
                                             ->where('GL_ACCOUNT.GLID', $userData[0]['GLID'])
                                             ->where('GL_ACCOUNT.account_number', $subUserData->id)
                                             ->get();
            
            return $result; 
        }catch(Exception $e){
            Log::info('function getSubUserData| error code : 302 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get Sub Account Data
     * @param  $subUserData
     * @return result
     */ 
    public function getSubAccountData($data)
    {   
        try{
            $result = $this->UserAccountModel->select('GL_ACCOUNT.*','GL_USER.contract_name')
                                             ->join('GL_USER','GL_ACCOUNT.GLID','=','GL_USER.GLID')
                                             ->where('GL_ACCOUNT.GLID', $data['GLID'])
                                             ->where('GL_ACCOUNT.account_number', $data['account_number'])
                                             ->get();
          
            return $result; 
        }catch(Exception $e){
            Log::info('function getSubUserData| error code : 302 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * sub user change data
     * @param integer $subUserData
     * @return result
     */ 
    public function changeSubUserData($subUserData)
    {
        try{
            $result = $this->UserAccountModel->where('GLID', $subUserData['GLID'])
                                            ->where('account_number', $subUserData['account_number'])
                                            ->update([
                                                'account_code'      =>  $subUserData['account_code'],
                                                'mail_address'      =>  $subUserData['mail_address'],
                                                'profile_info_flg'  =>  $subUserData['profile_info_flg'],
                                                'event_info_flg'    =>  $subUserData['event_info_flg'],
                                                'sales_info_flg'    =>  $subUserData['sales_info_flg'],
                                                'member_info_flg'   =>  $subUserData['member_info_flg'],
                                                'personal_info_flg' =>  $subUserData['personal_info_flg'],
                                                'status'            =>  $subUserData['status'],
                                                'remarks'           =>  $subUserData['remarks'],
                                                'expire_date'       =>  $subUserData['expire_date'],
                                                'update_account_cd' =>  $subUserData['update_account_cd'],
                                            ]);
                                
            return $result; 
        }catch(Exception $e){
            Log::info('function getSubUserData| error code : 305 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * sub user change passwork
     * @param integer $subUserData
     * @return result
     */ 
    public function changePasswork($subUserData)
    {
        try{
            $result = $this->UserAccountModel->where('GLID', $subUserData['GLID'])
                                            ->where('account_number', $subUserData['id'])
                                            ->update([
                                                'password'=>$subUserData['password'],
                                            ]);
        
            return $result; 
        }catch(Exception $e){
            Log::info('function changePasswork| error code : 305 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * user apply change inf
     * @param json $userInf
     * @param integer $userId
     * @return result
     */ 
    public function applyChangeInf($userInf, $GLID)
    {      
        try{
            $result = $this->UserManageModel->where('GLID', $GLID)
                                            ->update([
                                                'user_status'=>1,
                                                'temporary_info'=>$userInf,
                                                'app_date' => Carbon::now()->toDateTimeString(),
                                            ]);
                                            
            return $result; 
        }catch(Exception $e){
            Log::info('function applyChangeInf| error code : 305 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * user apply userWithdrawalApply inf
     * @param integer $GLID
     * @return result
     */ 
    public function userWithdrawalApply($GLID)
    {      
        try{
            //userData
            $result = $this->UserManageModel->where('GLID', $GLID)
                                            ->update([
                                                      'user_status'=> 8,  //退会申請
                                                      'event_publishable'=> 0,
                                                      'app_date' => Carbon::now()->toDateTimeString(),
                                                    ]);
            return $result; 
        }catch(Exception $e){
            Log::info('fuserWithdrawalApply | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
}
