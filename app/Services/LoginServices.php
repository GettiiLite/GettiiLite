<?php

namespace App\Services;

use Validator;
use App\Repositories\LoginRepositories;
use App\Services\MailServices;
use App\Repositories\AdminManageRepositories; //STS 2021/08/27 Task 48 No4. 
use Illuminate\Support\Facades\Hash;
use Exception;
use Lang;
use Log;
use App;

class LoginServices
{
    /** @var  LoginRepositories */
    protected $LoginRepositories;
    /** @var  MailServices */
    protected $SendMail;
    /** @var  AdminManageRepositories */
    protected $AdminManageRepositories;  //STS 2021/08/27 Task 48 No4.

    /**
     * UserController constructor.
     * @param  LoginRepositories $LoginRepositories
     * @return
     */
    //STS 2021/08/27 Task 48 No4. --START
    // public function __construct(LoginRepositories $LoginRepositories, MailServices $SendMail)
    // {
    //     $this->LoginRepositories = $LoginRepositories;
    //     $this->SendMail = $SendMail;
    // }  

     public function __construct(LoginRepositories $LoginRepositories, MailServices $SendMail, AdminManageRepositories $AdminManageRepositories)
    {
        $this->LoginRepositories = $LoginRepositories;
        $this->SendMail = $SendMail;
        $this->AdminManageRepositories = $AdminManageRepositories;
    } 
     //STS 2021/08/27 Task 48 No4. -- END  
    /**
     * 8 bit number
     *
     * @return rand number
     */
    protected function randnum(){
        $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        $star = rand(1,26);
        $passWord = substr(str_shuffle($str),$star,8);

        return $passWord;
    }
    /**
     * siye verify
     * 
     * @parm recaptcha code
     * @return recaptcha result
     */
    protected function _recaptchaVerify($recaptchaCode){
        $recaptcha_data = \Config::get('constant.googlr_recaptcha_data');
        
        $url = $recaptcha_data['url'];
        $data = [
            'secret' => $recaptcha_data['secret_key'],
            'response' => $recaptchaCode
        ];
        $options = [
            'http' => [
                'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $content = stream_context_create($options);
        $result = file_get_contents($url, false, $content);
        $resultJson = json_decode($result);

        return $resultJson;
    }
    /**
     * login
     *
     * @return rand number
     */
    public function login(array $request)
    {  
        $data = json_decode($request['json']);
        $dataArray =  (array)$data[0];
        
        $resultJson = $this->_recaptchaVerify($request['recaptcha']);
      
        if($resultJson->success){
            if($resultJson->score < 0.5){
                Log::info('error msg : recaptchaVerify |companyId :'.$dataArray['companyId'].'|userId :'.$dataArray['userId'].'|date :'.$resultJson->challenge_ts);
                App::abort(500);
            }
        }

        $personalRules = [
            'companyId' => 'required',
            'userId' => 'required',
            'userPassword' => 'required',
        ];
      
        $validator = Validator::make($dataArray, $personalRules);
        if($validator->fails()){
          return back()->withErrors('error');
        }
        
        $result = $this->LoginRepositories->login($data[0]);
       
        if($result){
            $userCode = $this->LoginRepositories->userData($data[0]);
            $accountData = $this->LoginRepositories->accountData($userCode[0]->GLID, $data[0]->userId);
           
            $userex_full_refund = $userCode[0]['UserEX']->first(function ($value, $key) {
                return $value['parameter'] == 'FULLREFULL';
            });
           
            if($userex_full_refund){
                $full_refund = $userex_full_refund['value'];
            }else{
                $full_refund = false;
            }

            if($userCode[0]['logo_image']){
                $logo = $userCode[0]['logo_image'];
            }else{
                $logo = \URL::to('/assets/images/logo/default-logo.png');
            }
          
            if($userCode[0]->GLID == 1){
                $adminCd = \Config::get('app.admin');
                if (in_array($accountData['account_cd'], $adminCd)) {
                    $adminStatus = 0;
                }else{
                    $adminStatus = 1;
                }
            }else{
                $adminStatus = 1;
            }

            //是否是最高權限賬號
            $root_account = false;

            if($userCode[0]['GLID'] == 1){
                $root_account  = true;
                $full_refund = true;
                if($accountData['account_number'] == 0){
                    $profile_info_flg    = 2;
                    $event_info_flg      = 2;
                    $sales_info_flg      = 2;
                    $member_info_flg     = 2;
                    $personal_info_flg   = 0;
                }else{
                    $profile_info_flg    = $accountData['profile_info_flg'];
                    $event_info_flg      = $accountData['event_info_flg'];
                    $sales_info_flg      = $accountData['sales_info_flg'];
                    $member_info_flg     = $accountData['member_info_flg'];
                    $personal_info_flg   = $accountData['personal_info_flg'];
                }
            }else{
                if($accountData['account_number'] == 0 && $userCode[0]['user_status'] != 8){
                    $profile_info_flg    = 2;
                    $event_info_flg      = 2;
                    $sales_info_flg      = 2;
                    $member_info_flg     = ($userCode[0]['SID'] > 1)?2:0;
                    $personal_info_flg   = 0;
                }else{
                    $profile_info_flg    = $accountData['profile_info_flg'];
                    $event_info_flg      = $accountData['event_info_flg'];
                    $sales_info_flg      = $accountData['sales_info_flg'];
                    $member_info_flg     = ($userCode[0]['SID'] > 1)?$accountData['member_info_flg']:0;
                    $personal_info_flg   = $accountData['personal_info_flg'];
                }
            } 
           
            if(\App::getLocale() == "ja") {
                $member_info_flg = 0;
            }
            
            session(
                [
                 'status'               => 'login',
                 'GLID'                 => $userCode[0]->GLID,
                 'user_code'            => $userCode[0]->user_code,
                 'account_number'       => $accountData['account_number'],
                 'account_cd'           => $accountData['account_cd'],
                 'account_code'         => $accountData['account_code'],
                 'account_title'        => $userCode[0]['disp_name']?$userCode[0]['disp_name']:trans('basisInf.S_DefaultMemberTitle'),
                 'logo'                 => $logo,
                 'profile_info_flg'     => $profile_info_flg,
                 'event_info_flg'       => $event_info_flg,
                 'sales_info_flg'       => $sales_info_flg,
                 'member_info_flg'      => $member_info_flg,
                 'personal_info_flg'    => $personal_info_flg,
                 'admin_flg'            => $adminStatus,
                 'root_account'         => $root_account,
                 'event_publishable'    => $userCode[0]->event_publishable,
                 'full_refund' => $full_refund,
                ]
            );
            
            if($userCode[0]['user_status'] == 8)
            {
              //退会申請user
              //sessionの各権限を閲覧のみに変更
              (session('profile_info_flg') == 2) ? session(['profile_info_flg' => 1 ]) : '';
              (session('event_info_flg') == 2) ? session(['event_info_flg' => 1 ]) : '';
              (session('sales_info_flg') == 2) ? session(['sales_info_flg' => 1 ]) : '';
               session(['event_publishable' => 0 ]);            
            }
        }else{
            return false;
        }
      
        return $result;
    }
    /**
     * login
     *
     * @return rand number
     */
    public function logout(array $request)
    {
        session()->flush();

        return true;
    }
    /**
     * account apply passwordRemind
     *
     * @return rand bool
     */
    public function accountPasswork(array $request)
    {   
        $inf = json_decode($request["json"]);
        $dataArray =  (array)$inf[0];
        $Rules = [
            'companyId' => 'required',
            'mail' => 'required',
            'tel' => 'required',
        ];
        $status = [
            'result' => true,
        ];
        $data = [
            'apply_title' => Lang::get('basisInf.S_ApplyNewPassword'),
            'title' =>  Lang::get('registered.S_ApplyComplete'),
            'msn'   =>  Lang::get('registered.S_CompleteNotice1'),
        ];
        $result = [
            'status' => $status,
            'data'   => $data,
        ];

        try {
            $validator_result = Validator::make($dataArray, $Rules);

            if($validator_result->fails()){
                $error = Lang::get('registered.S_ErrorNotice1');
                throw new Exception($error);
            }
        
            $password = $this->randnum();
            $hashed =  password_hash($password, PASSWORD_BCRYPT);
        
            $accountInf = array(
                'companyId' => $inf[0]->companyId,
                'mail' => $inf[0]->mail,
                'tel' => $inf[0]->tel,
                'password' => $hashed,
            );
        
            $change_result = $this->LoginRepositories->accountPasswork($accountInf);

            if(!$change_result){
                $error = Lang::get('registered.S_ErrorNotice2');
                throw new Exception($error);
            }
            
            $mailData = array(
                'account'=>$inf[0]->companyId,
                'id'=>0,
            );
            
            $mail_result = $this->SendMail->sendAccountPassword((object)$mailData, $password);
        
            if(!$mail_result){
                $error = Lang::get('registered.S_ErrorNotice3');
                throw new Exception($error);
            }

            return $result;
        }catch (Exception $e){
            Log::info('error code : 211 | error account id : '.$inf[0]->companyId.'|'.$e->getMessage());
            $result['status']['result'] = false;
            $result['data']['title'] = Lang::get('registered.S_SearchError');
            $result['data']['msn'] = $e->getMessage();

            return $result;
        }
    }

    //STS 2021/28/08 Task 48 No4. --START
    // /**
    //  * account apply change password
    //  *
    //  * @return rand bool
    //  */
    // public function accountChangePasswork(array $request)
    // {
    //     $data = json_decode($request["json"]);
    //     $dataArray =  (array)$data[0];
    //     $Rules = [
    //         'oldPassword' => 'required',
    //         'newPassword' => 'required',
    //         'newSePassword' => 'required',
    //     ];
      
    //     $validator = Validator::make($dataArray, $Rules);
    //     if($validator->fails()){
    //         return back()->withErrors('error');
    //     }
           
    //     $GLID = session('GLID');
    //     $account_cd = session('account_cd');
    //     $hashed =  password_hash($data[0]->newPassword, PASSWORD_BCRYPT);

    //     $accountInf = array(
    //         'GLID' => $GLID,
    //         'account_cd' => $account_cd,
    //         'userPassword' => $data[0]->oldPassword,
    //         'password' => $hashed,
    //     );
    //     $result = $this->LoginRepositories->accountChangePasswork($accountInf);
     
    //     return $result;
    // }

    /**
     * account apply change password
     *
     * @return rand bool
     */
    public function accountChangePasswork(array $request)
    {
        $data = json_decode($request["json"]);
        $dataArray =  (array)$data[0];
        $invalidPasswords = config('passwordlist_full.list');
        $Rules = [
            'oldPassword' => 'required',
            'newPassword' => 'required|not_in:'.implode(',', $invalidPasswords),
            'newSePassword' => 'required',
        ];
        $GLID = session('GLID');
        $account_cd = session('account_cd');
        $hashed =  password_hash($data[0]->newPassword, PASSWORD_BCRYPT);
        $account_code = session('account_code'); 
        $userData = $this->AdminManageRepositories->getUserData($GLID); 
        $user_id = isset($userData[0]['user_id']) ? $userData[0]['user_id'] : "";
        $accountInf = array(
            'GLID' => $GLID,
            'account_cd' => $account_cd,
            'userPassword' => $data[0]->oldPassword,
            'password' => $hashed,
        );

        $verifyPassWord = $this->LoginRepositories->checkPassWord($accountInf); 
        if(!$verifyPassWord) 
        return[
            'mess'      => trans('userManage.S_ChangePwdError'),
            'status'    => false
        ];
        
        $validator = Validator::make($dataArray, $Rules);
        if(($data[0]->newPassword === "{$account_code}" || $data[0]->newPassword === "{$user_id}")) {
            return $result = [
                'mess'      => trans('userManage.S_duplicateID'),
                'status'    => false
            ];
        }

        $jsonRecentPassWord = $this->LoginRepositories->getRecentPassword($account_cd);
        $maxRecentPw = 4;
        if($jsonRecentPassWord){
            $listRecentPassWord = json_decode($jsonRecentPassWord);
            foreach($listRecentPassWord as $recentPW){
                if(password_verify($data[0]->newPassword, $recentPW )) 
                return $result = [
                    'mess'      => trans('userManage.S_ChangePwdErrorRecent'),
                    'status'    => false
                ];
            };
            array_push($listRecentPassWord, $hashed);
            $count = count($listRecentPassWord);
            if($count > $maxRecentPw) $listRecentPassWord = array_slice($listRecentPassWord, -$maxRecentPw);         
        }else{
            $listRecentPassWord[0] = $hashed;
        }
        $jsonRecentPassWord = json_encode($listRecentPassWord);
        $accountInf['recent_password'] = $jsonRecentPassWord;

        if($validator->fails()){
            if($validator->errors()->has('newPassword')) {
                return $result = [
                    'mess'      => trans('userManage.S_ChangePwdErrorNewPass'),
                    'status'    => false
                ];
            } 
        }

        $result = $this->LoginRepositories->accountChangePasswork($accountInf);     
        return [
            'mess'      => $result ? trans('userManage.S_CompletedChangePwd') : trans('userManage.S_ChangePwdError'),
            'status'    => $result ? true : false 
        ];
    }
    //STS 2021/28/08 Task 48 No4. --END

    /**
     * register
     *
     * @return rand number
     */
    public function register(array $request)
    {
        //STS 2021/08/30 Task 48 No.5 start
        try{
            $inf = json_decode($request['json']);
            $dataArray =  (array)$inf[0];
            $identity = $inf[0]->identity;
            $status = [
                'result' => true,
            ];
            $data = [
                'apply_title' => Lang::get('registered.S_Apply'),
                'title' =>  Lang::get('registered.S_ApplyComplete'),
                'msn'   =>  Lang::get('registered.S_Notice'),
            ];
            $result = [
                'status' => $status,
                'data'   => $data,
            ];
           
            if($identity == "personal"){
                $userData = array(
                    "status"=>'0',
                    "sellTittle"=>'',
                    "sellImg"=>'',
                    "sellUrl"=>'',
                    "companyName"=>'',
                    "companyNameKana"=>'',
                    "personalName"=>$inf[0]->userName,
                    "personalNameKana"=>$inf[0]->userNameKana,
                    "personalTel"=>$inf[0]->userTel,
                    "personalMail"=>$inf[0]->userMail,
                    "prefecture"=>'',
                    "city"=>'',
                    "postCode"=>'',
                    "postDisplay" => '',
                    "contactDeparment"=>'',
                    "contactName"=>'',
                    "tel"=>'',
                    "contactMail"=>'',
                    "bankName"=>'',
                    "branch"=>'',
                    "bankType"=>'',
                    "bankAccount"=>'',
                    "bankAccountKana"=>'',
                    "openInf"=>'',
                    'pathLogo'=>'',
                    'dispFlg'=>'',
                    'pathImage01'=>'',
                    'pathImage02'=>'',
                    'pathImage03'=>'',
                ); 
            }else{
                $userData = array(
                    "status"=>'0',
                    "sellTittle"=>'',
                    "sellImg"=>'',
                    "sellUrl"=>'',
                    "companyName"=>$inf[0]->companyName,
                    "companyNameKana"=>$inf[0]->companyNameKana,
                    "personalName"=>'',
                    "personalNameKana"=>'',
                    "personalTel"=>'',
                    "personalMail"=>'',
                    "prefecture"=>$inf[0]->prefecture,
                    "city"=>$inf[0]->city,
                    "postCode"=>$inf[0]->postCode,
                    "postDisplay"=>$inf[0]->postDisplay,
                    "contactDeparment"=>$inf[0]->contactDepartment,
                    "contactName"=>$inf[0]->contactPerson,
                    "tel"=>$inf[0]->contactTel,
                    "contactMail"=>$inf[0]->contactMail,
                    "placeDetailed"=>$inf[0]->placeDetailed,
                    "bankName"=>'',
                    "branch"=>'',
                    "bankType"=>'',
                    "bankAccount"=>'',
                    "bankAccountKana"=>'',
                    "openInf"=>'',
                    'pathLogo'=>'',
                    'dispFlg'=>'',
                    'pathImage01'=>'',
                    'pathImage02'=>'',
                    'pathImage03'=>'',
                ); 
            }
        }catch(Exception $e){
            return null;
        }
        //STS 2021/08/30 Task 48 No.5 end.
        $personalRules = [
            'applyId' => 'required',
            'adminName' => 'required',
            'userName' => 'required',
            'userTel' => 'required',
            'userMail' => 'required',
        ];

        $companyRules = [
            'applyId' => 'required',
            'adminName' => 'required',
            'companyName' => 'required', 
            'contactPerson' => 'required', 
            'contactTel' => 'required', 
            'contactMail' => 'required',
        ];
        try {
            $dataArray =  (array)$inf[0];
            
            if($identity === 'personal'){ 
                $validator = Validator::make($dataArray, $personalRules);
                if($validator->fails()){
                    $error = Lang::get('registered.S_ErrorNotice1');
                    throw new Exception($error);
                }
            }elseif($identity === 'company'){
                $validator = Validator::make($dataArray, $companyRules);
                if($validator->fails()){
                    $error = Lang::get('registered.S_ErrorNotice1');
                    throw new Exception($error);
                }
            }

            $identityNum = ($inf[0]->identity === 'personal')?'0':'1';

            if($identity === 'personal')
            {
                $contractName = $inf[0]->userName;
                $contractNameKana = $inf[0]->userNameKana;
                $telnum = $inf[0]->userTel;
                $mailAddress = $inf[0]->userMail;
                $postCode = null;
                $postDisplay = null;
                $address = null;
                $department = null;
                $contactPerson = null;
            
            }else{
                $contractName = $inf[0]->companyName;
                $contractNameKana = $inf[0]->companyNameKana;
                $postCode = $inf[0]->postCode;
                $postDisplay = $inf[0]->postDisplay;
                $address = $inf[0]->placeDetailed;
                $department = $inf[0]->contactDepartment;
                $contactPerson = $inf[0]->contactPerson;
                $telnum = $inf[0]->contactTel;
                $mailAddress = $inf[0]->contactMail;
            }

            $password = $this->randnum();
            $hashed =  password_hash($password, PASSWORD_BCRYPT);
            $userInf = array(
                'user_code'=> 99000000 + intval(date("dhs")),
                'user_id'=>$inf[0]->applyId,
                'account_code'=>$inf[0]->adminName,
                'user_kbn'=>$identityNum,
                'contract_name'=> $contractName,
                'contract_name_kana'=>$contractNameKana,
                'tel_num'=>$telnum,
                'mail_address'=>$mailAddress,
                'post_code'=>$postCode,
                'post_display'=>$postDisplay,
                'address'=> $address,
                'department'=>$department,
                'contact_person'=>$contactPerson,
                'mail_addres'=>$mailAddress,
                'password'=>$hashed,
                'temporary_info' => json_encode($userData),
                'introduction' =>$inf[0]->introduction
            );

            $register_result = $this->LoginRepositories->register($userInf);
        
            if(!$register_result){
                $error = Lang::get('registered.S_ErrorNotice4');
                throw new Exception($error);
            }

            $mailData = array(
                'account'=>$inf[0]->applyId,
                'id'=>0,
            );

            $SendMail_result = $this->SendMail->sendPassword((object)$mailData, $password);

            if(!$SendMail_result){
                $error = Lang::get('registered.S_ErrorNotice1');
                throw new Exception($error);
            }

            return $result;
        }catch (Exception $e){
            Log::info('error code : 211 | register error : '.$e->getMessage());
            throw new Exception ($e->getMessage());
        }
    }
}