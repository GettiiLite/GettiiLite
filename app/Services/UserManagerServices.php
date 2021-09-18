<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Repositories\EvenManageRepositories;
use App\Repositories\UserManagerRepositories;
use Illuminate\Support\Facades\Storage;
use App\Services\MailServices;
use Exception;
use finfo;
use Log;

class UserManagerServices
{
    /** @var UserManagerServices */
    protected $UserManagerRepositories;

    protected $SendMail;

    /**
     * UserController constructor.
     * @param UserManagerServices $UserManagerServices
     */
    public function __construct(UserManagerRepositories $UserManagerRepositories, EvenManageRepositories $EvenManageRepositories, MailServices $SendMail)
    {
        $this->UserManagerRepositories = $UserManagerRepositories;
        $this->EvenManageRepositories = $EvenManageRepositories;
        $this->SendMail = $SendMail;
    }    
    /**
     * get user status text
     * 
     * @parm int $userStatus
     * @return string $statusText
     */
    protected function statusText($userStatus){
        switch ($userStatus) {
            case "0":
                $statusText = trans('userManage.S_Status_00');
                break;
            case "1":
                $statusText = trans('userManage.S_Status_01');
                break;
            case "2":
                $statusText = trans('userManage.S_Status_02');
                break;
            case "6":
                $statusText = trans('userManage.S_Status_02');
                break;
            case "8":
                $statusText = trans('userManage.S_Status_08');
                break;
            case "9":
                $statusText = trans('userManage.S_Status_09');
                break;
            case "-1":
                $statusText = trans('userManage.S_Status_-1');
                break;
            case "-2":
                $statusText = trans('userManage.S_Status_-2');
                break;
            default:
                $statusText = 'no this status.......';
        }
        return $statusText;
    }
     /**
     * 8 bit number
     *
     * @return rand number
     */
    protected function randnum(){
        $str = "QWERTYUPASDFGHJKLZXCVBNM23456789qwertyuipasdfghjkzxcvbnm";
        str_shuffle($str);
        $star = rand(1,26);
        $passWord = substr(str_shuffle($str),$star,8);

        return $passWord;
    }
     /**
     * file upload
     *@parm $file -- image data
     * @return $path
     */
    protected function imageUpload($file){
        if($file){
            $name =  $this->randnum().date("dYhmm").'.'.$file->getClientOriginalExtension();
            $destinationPath = '/public/user-data';
            $path =  Storage::putFileAs($destinationPath , $file, $name);
        }else{
            $path = null;
        }
        return $path;
    }
    /**
     * assign user acount
     * @parm array $userInf
     * @return array result
     */
    protected function assignUserInf($userInf){
         $pcstArr = ['',''];
         if($userInf['post_code'] && isset(\Config::get('constant.post')[$userInf['post_code']])){
            $pcstArr = \Config::get('constant.post')[$userInf['post_code']];
         }
        $result =array(
            "account"=>$userInf['user_id'],
            "user_status"=>$userInf['user_status'],
            "status"=>$userInf['user_kbn'],
            "sellTittle"=>$userInf['disp_name'],
            "sellImg"=>$userInf['logo_image'],
            "sellUrl"=>$userInf['home_page'],
            "companyName"=>$userInf['contract_name'],
            "companyNameKana"=>$userInf['contract_name_kana'],
            "placeNum"=>$userInf['post_code'],
            "postDisplay"=>$userInf['post_display'],
            "place"=>$pcstArr[0].$pcstArr[1].' '.$userInf['address'],
            "country"=>"",
            "contactDeparment"=>$userInf['department'],
            "contactName"=>$userInf['contact_person'],
            "tel"=>$userInf['tel_num'],
            "contactMail"=>$userInf['mail_address'],
            "sellChecked"=>$userInf['sellChecked'],
            "bankName"=>$userInf['bank_name'],
            "branch"=>$userInf['branch_name'],
            "bankType"=>$userInf['account_kbn'],
            "bankAccount"=>$userInf['account_num'],
            "bankAccountKana"=>$userInf['account_name'],
            "GETTIIS_disp_flg"=>$userInf['GETTIIS_disp_flg'],
            "GETTIIS_logo_disp_flg"=>$userInf['GETTIIS_logo_disp_flg'] // STS 2021/07/17 Task 26
        ); 

        return $result;
    }
    /**
     * assign user acount
     * @parm array $userInf
     * @return array result
     */
    protected function assignSubUser($data){
        $result = array();
        
        foreach ($data as $value) {
            if ($value['account_number'] == 0 )
                continue;
           
            if( strtotime('9999-12-31') === strtotime($value['expire_date'])){
                $dateRadio = 'had';
                $date = '';
            }else{
                $dateRadio = 'date';
                $date = date("Y/m/d",strtotime($value['expire_date']));
            }
            
            $subAcount = array( 
                "id"                 =>  $value['account_number'],
                "name"               =>  $value['account_code'],
                "mail"               =>  $value['mail_address'],
                "permission"         =>  $value['permission'],
                "profile_info_flg"   =>  $value['profile_info_flg'],
                "event_info_flg"     =>  $value['event_info_flg'],
                "sales_info_flg"     =>  $value['sales_info_flg'],
                "member_info_flg"    =>  $value['member_info_flg'],
                "personal_info_flg"  =>  $value['personal_info_flg'],
                "permissionDeadline" =>  $dateRadio,
                "deadlineDate"       =>  $date,
                "infPermission"      =>  $value['permission'],
                "userStatus"         =>  $value['status'],
                "note"               =>  $value['remarks']
            );

            array_push($result, $subAcount); 
        }

        return $result;
    }
     /**
     * 呈現頁面
     *
     * @param array $request
     */
    public function show(array $request){
        $GLID = session('GLID');
        $account_cd = session('account_cd');
        $infData = $this->UserManagerRepositories->getInformation($GLID);
        $userInf = $infData['userData'][0];
        $subAccount = $infData['accountInf'];
        $introduction = $infData['introduction'];
        $userData = $this->assignUserInf($userInf);
        $accountInf = $this->assignSubUser($subAccount);
        $statusText = $this->statusText($userInf['user_status']);
          
        $staticSetting = array(
       
        );
        
        $IntCd =null;
        if(count($introduction) > 0)
        {
          $IntCd = $introduction[0]['value'];    
        }
        $inf =array(
            "userData"=> $userData,
            "accountInf"=> $accountInf, 
            "introduction"=> $IntCd
        );
        $json = array(
            "attributes" => $staticSetting,
            "data" => $inf,
            "statusText" => $statusText
        ); 
        return $json;
    }
     /**
     * 呈現編輯頁面
     *
     * @param array $request
     */
    public function showEdit(array $request){
      
        $GLID = session('GLID');
        $account_cd = session('account_cd');
        $infData = $this->UserManagerRepositories->getInformation($GLID);
        $userStatus = $infData['userData'][0]["user_status"];
        if($userStatus == 8)
        { 
          //退会申請中
          abort(404);  
        }
        $userInf = json_decode($infData['userData'][0]["temporary_info"], true);
        $pathLogo = ($userInf['pathLogo'])?$userInf['pathLogo']:null;
        $pathImage01 = ($userInf['pathImage01'])?$userInf['pathImage01']:null;
        $pathImage02 = ($userInf['pathImage02'])?$userInf['pathImage02']:null;
        $pathImage03 = ($userInf['pathImage03'])?$userInf['pathImage03']:null;
        if($userInf){
            if(isset($userInf['postCode'])&&!empty($userInf['postCode'])){
                $postCode = $userInf['postCode'];
                $post_display = isset($userInf['postDisplay'])?$userInf['postDisplay']:'';
                $pcstArr = \Config::get('constant.post');
                $location = $pcstArr[$postCode];
                $prefecture = $location[0];
                $city = $location[1];
            }else{
                $postCode = null;
                $post_display = null;
                $prefecture = null;
                $city = null;
            }
           
            $userData = array(
                "status"=>($userStatus == 8)? $userStatus : $infData['userData'][0]["user_kbn"], //退会申請はDBの値
                "sellTittle"=>$userInf['sellTittle'],
                "sellImg"=>$userInf['pathLogo'],
                "sellUrl"=>$userInf['sellUrl'],
                "companyName"=>$userInf['companyName'],
                "companyNameKana"=>$userInf['companyNameKana'],
                "personalName"=>$userInf['personalName'],
                "personalNameKana"=>$userInf['personalNameKana'],
                "personalTel"=>$userInf['personalTel'],
                "personalMail"=>$userInf['personalMail'],
                "postCode"=>$postCode,
                "postDisplay"=>$post_display,
                "prefecture"=>$prefecture,
                "city"=>$city,
                "placeDetailed"=>isset($userInf['placeDetailed'])?$userInf['placeDetailed']:$infData['userData'][0]["address"],
                "contactDeparment"=>$userInf['contactDeparment'],
                "contactName"=>$userInf['contactName'],
                "tel"=>$userInf['tel'],
                "contactMail"=>$userInf['contactMail'],
                "bankName"=>$userInf['bankName'],
                "branch"=>$userInf['branch'],
                "bankType"=>$userInf['bankType'],
                "bankAccount"=>$userInf['bankAccount'],
                "bankAccountKana"=>$userInf['bankAccountKana'],
                "openInf"=>$userInf['openInf'],
                'pathLogo'=>$pathLogo,
                'dispFlg'=>isset($userInf['dispFlg'])?$userInf['dispFlg']:true,
                'pathImage01'=>$pathImage01,
                'pathImage02'=>$pathImage02,
                'pathImage03'=>$pathImage03,
                'GETTIIS_logo_disp_flg' => isset($userInf['GETTIIS_logo_disp_flg']) ? $userInf['GETTIIS_logo_disp_flg'] : '0' // STS 2021/07/17 Task 26
            ); 
        }else{
            $userData = array(
                "status"=>0, //0612 James : need to check
                "sellTittle"=>$infData['userData'][0]["user_id"],
                "sellImg"=>$infData['userData'][0]["temporary_info"],
                "sellUrl"=>$infData['userData'][0]["temporary_info"],
                "companyName"=>$infData['userData'][0]["contract_name"],
                "companyNameKana"=>$infData['userData'][0]["contract_name_kana"],
                "personalName"=>$infData['userData'][0]["personalName"],
                "personalNameKana"=>$infData['userData'][0]["personalNameKana"],
                "personalTel"=>$infData['userData'][0]["personalTel"],
                "personalMail"=>$infData['userData'][0]["personalMail"],
                "placeNum"=>$infData['userData'][0]["post_code"],
                "postDisplay"=>$infData['userData'][0]["post_display"],
                "place"=>$infData['userData'][0]["address"],
                "country"=>"",
                "contactDeparment"=>$infData['userData'][0]["department"],
                "contactName"=>$infData['userData'][0]["contact_person"],
                "tel"=>$infData['userData'][0]["tel_num"],
                "contactMail"=>$infData['userData'][0]["mail_address"],
                "bankName"=>$infData['userData'][0]["bank_name"],
                "branch"=>$infData['userData'][0]["branch_name"],
                "bankType"=>$infData['userData'][0]["account_kbn"],
                "bankAccount"=>$infData['userData'][0]["account_num"],
                "bankAccountKana"=>$infData['userData'][0]["account_name"],
                "openInf"=>$userInf['openInf'],
                "pathLogo"=>$pathLogo,
                "dispFlg"=>isset($infData['userData'][0]["dispFlg"])?$infData['userData'][0]["dispFlg"]:true,
                "pathImage01"=>$pathImage01,
                "pathImage02"=>$pathImage02,
                "pathImage03"=>$pathImage03,
                'GETTIIS_logo_disp_flg' => isset($userInf['GETTIIS_logo_disp_flg']) ? $userInf['GETTIIS_logo_disp_flg'] : '0' // STS 2021/07/17 Task 26
            ); 
        }

        //販売中の公演
        $eventOnSale = 0;
        if(session('account_number') == 0)
        {
          //退会申請はadmin addountのみ
          $eventOnSale = $this->EvenManageRepositories->getEventOnSale($GLID);        
        }
        $staticSetting = array(
       
        );

        $inf =array(
            "userData"=> $userData,
        );
        $json = array(
            "attributes" => $staticSetting,
            "data" => $inf,
            "eventOnSale" => $eventOnSale
        );
       
        return $json;
    }
    /**
     * sub user add member
     *
     */   
    public function addUserData(array $request){
        $data = json_decode($request['json']);
        $dataArray =  (array)$data[0];
        
        $GLID = session('GLID');
        $account_cd = session('account_cd');

        $Rules = [
            'arrayId' => 'nullable|integer',
            'account' => 'required|alpha_dash',
            'id' => 'nullable|integer',
            'name' => 'required|alpha_dash',
            'mail' => 'nullable|email',
            'contact' => 'nullable|alpha_dash',
            'permission' => 'nullable|alpha_dash',
            'permissionDeadline' => 'required|alpha_dash',
            // 'deadlineDate' => 'nullable|date',
            'profileInfo'  => 'required|numeric',
            'eventInfo' => 'required|numeric',
            'salesInfo' => 'required|numeric',
            'memberManage' => 'required|numeric',
            'personalInfo' => 'required|numeric',
            'infPermission' => 'nullable|alpha_dash',
            'userStatus' => 'required|numeric',
            'note'=> 'nullable|string',
            'status' => 'required|alpha_dash',
        ];
        $validator = Validator::make($dataArray, $Rules);
        $validator->sometimes('deadlineDate','required|date',function($input){
            return $input->permissionDeadline === 'date';
        });

        if($validator->fails()){
            throw new Exception('REQ-ERR-01 Request format error!');
            return null;
        }

        $contact = $data[0]->contact;
       
        //deadlineDate 日期   permissionDeadline radio
        if($data[0]->permissionDeadline !== 'date'){
            $date = '9999/12/31';
        }else{
            $date = $data[0]->deadlineDate.' 23:59:59';
        }

        $password = $this->randnum();
        $hashed =  password_hash($password, PASSWORD_BCRYPT);

        $addInf = array(
            'GLID'              =>  $GLID,
            'account_number'    =>  $data[0]->id+1,
            'account_code'      =>  $data[0]->name, 
            'mail_address'      =>  $data[0]->mail,
            'password'          =>  $hashed,
            'permission'        =>  $data[0]->permission,
            'profile_info_flg'  =>  $data[0]->profileInfo,
            'event_info_flg'    =>  $data[0]->eventInfo,
            'sales_info_flg'    =>  $data[0]->salesInfo,
            'member_info_flg'   =>  $data[0]->memberManage,
            'personal_info_flg' =>  $data[0]->personalInfo,
            'status'            =>  $data[0]->userStatus,
            'remarks'           =>  $data[0]->note,
            'expire_date'       =>  $date,
            'update_account_cd' =>  $account_cd,
        );
       
        $cheakResult = $this->UserManagerRepositories->cheakAccount($addInf);
        
        if( is_null($cheakResult) ){
            $insertResult = $this->UserManagerRepositories->addSubUser($addInf);
        }
        else {
            return null;
        }

        if($data[0]->contact === "mail"){
            $data[0]->id += 1;
            $this->SendMail->passwordRemind($data[0], $password,true); 
        }

        if($contact === "web"){
            $showPassword = "web";
        }else{
            $showPassword = "mail";
        }

        $infData = $this->UserManagerRepositories->getInformation($GLID);
        $userInf = $infData['userData'][0];
        $subAccount = $infData['accountInf'];
        $introduction = $infData['introduction'];    
        $userData = $this->assignUserInf($userInf);
        $accountInf = $this->assignSubUser($subAccount);
        $statusText = $this->statusText($userInf['user_status']);

        $staticSetting = array(
            "showPasswordModal"=> true,
            "passwordChangeStatus"=> $showPassword,
            "passwordSelect"=> $showPassword,
            "password" => $password,
            "mode"=>"new"
        ); 
        
        $IntCd =null;
        if(count($introduction) > 0)
        {
          $IntCd = $introduction[0]['value'];    
        }     
        $inf =array(
            "userData"=> $userData,
            "accountInf"=> $accountInf,
            "introduction"=> $IntCd
        );
        $json = array(
            "attributes" => $staticSetting,
            "data" => $inf,
            "statusText" => $statusText,
        );

        return $json;
    }
    /**
     * sub user data change
     *
     */   
    public function subuserDataChange(array $request){
        $GLID = session('GLID');
        $account_cd = session('account_cd');
        $data = json_decode($request['json']);
        $dataArray =  (array)$data[0];
        
        $Rules = [
            'arrayId' => 'nullable|integer',
            'account' => 'required|alpha_dash',
            'id' => 'nullable|integer',
            'name' => 'required|alpha_dash',
            'mail' => 'nullable|email',
            'contact' => 'nullable|alpha_dash',
            'permission' => 'nullable|alpha_dash',
            'permissionDeadline' => 'required|alpha_dash',
            // 'deadlineDate' => 'nullable|date',
            'profileInfo'  => 'required|numeric',
            'eventInfo' => 'required|numeric',
            'salesInfo' => 'required|numeric',
            'memberManage' => 'required|numeric',
            'personalInfo' => 'required|numeric',
            'infPermission' => 'required|alpha_dash',
            'userStatus' => 'required|numeric',
            'note'=> 'nullable|string',
            'status' => 'required|alpha_dash',
        ];
        $validator = Validator::make($dataArray, $Rules);
        $validator->sometimes('deadlineDate','required|date',function($input){
            return $input->permissionDeadline === 'date';
        });

        if($validator->fails()){
            throw new Exception('REQ-ERR-01 Request format error!');
            return null;
        }
        //deadlineDate 日期   permissionDeadline radio
        if($data[0]->permissionDeadline !== 'date'){
            $date = '9999-12-31 00:00:00';
        }else{
            $date = $data[0]->deadlineDate.' 23:59:59';
        }

        $password = $this->randnum();
        $hashed = password_hash($password, PASSWORD_BCRYPT);
      
        $userInf = array(
            'GLID'              =>  $GLID,
            'account_number'    =>  $data[0]->id,
            'account_code'      =>  $data[0]->name, 
            'mail_address'      =>  $data[0]->mail,
            'password'          =>  $hashed,
            'profile_info_flg'  =>  $data[0]->profileInfo,
            'event_info_flg'    =>  $data[0]->eventInfo,
            'sales_info_flg'    =>  $data[0]->salesInfo,
            'member_info_flg'   =>  $data[0]->memberManage,
            'personal_info_flg' =>  $data[0]->personalInfo,
            'status'            =>  $data[0]->userStatus,
            'remarks'           =>  $data[0]->note,
            'expire_date'       =>  $date,
            'update_account_cd' =>  $account_cd,
        );
        
        $cheakResult = $this->UserManagerRepositories->cheakAccount($userInf);
        
        if( (!is_null($cheakResult) && $cheakResult == $data[0]->id) || is_null($cheakResult) ){
            $insertResult = $this->UserManagerRepositories->changeSubUserData($userInf);
        }else{
            return null;
        }
        $infData = $this->UserManagerRepositories->getInformation($GLID);
        $userInf = $infData['userData'][0];
        $subAccount = $infData['accountInf'];
       
        $userData = $this->assignUserInf($userInf);
        $accountInf = $this->assignSubUser($subAccount);
        $statusText = $this->statusText($userInf['user_status']);

        $staticSetting = array(

        ); 
        $inf =array(
            "userData"=> $userData,
            "accountInf"=> $accountInf,  
        );
        $json = array(
            "attributes" => $staticSetting,
            "data" => $inf,
            "statusText" => $statusText,
        );

        return $json;

    }
    /**
     * 新密碼申請
     *
     */   
    public function applyPassword(array $request){
       
        $GLID = session('GLID');
        $account_cd = session('account_cd');
        $data = json_decode($request['json']);
        $password = $this->randnum();
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        
        if($data[0]->contact === "mail"){
            $this->SendMail->passwordRemind($data[0], $password);
        }

        $dataArray =  (array)$data[0];
        
        $Rules = [
            'account' => 'required|alpha_dash',
            'name' => 'required|alpha_dash',
            'id' => 'nullable|integer',
            'mail' => 'nullable|email',
            'contact' => 'required|alpha_dash',
        ];
        
        $validator = Validator::make($dataArray, $Rules);
        if($validator->fails()){
            throw new Exception('REQ-ERR-01 Request format error!');
            return back()->withErrors('error');
        }
      
        $subUserData = array(
            'id'=>$data[0]->id,
            'GLID'=>$GLID,
            'password'=>$hashed,
        );       
        
        $changePassword = $this->UserManagerRepositories->changePasswork($subUserData);
       
        //查詢需要
        $account = $data[0]->account;
        $name = $data[0]->name; 
        $mail = $data[0]->mail;
        $contact = $data[0]->contact;

        if($contact === "web"){
            $showPassword = "web";
        }else{
            $showPassword = "mail";
        }
        
        $infData = $this->UserManagerRepositories->getInformation($GLID);
        $userInf = $infData['userData'][0];
        $subAccount = $infData['accountInf'];
        $introduction = $infData['introduction'];
        $userData = $this->assignUserInf($userInf);
        $accountInf = $this->assignSubUser($subAccount);
        $statusText = $this->statusText($userInf['user_status']);

        $staticSetting = array(
            "showPasswordModal"=>true,
            "passwordChangeStatus"=>$showPassword,
            "passwordSelect"=>$showPassword,
            "password"=>$password,
            "mode"=>"change"
        );
        $IntCd =null;
        if(count($introduction) > 0)
        {
          $IntCd = $introduction[0]['value'];    
        }      
        $inf =array(
            "userData"=> $userData,
            "accountInf"=> $accountInf,  
            "introduction"=> $IntCd
        );

        $json = array(
            "attributes" => $staticSetting,
            "data" => $inf,
            "statusText" => $statusText,
        );

        return $json;
    }
    /**
     * 刪除賬號
     *
     * @param array $request
     */   
    public function deleteId(){
      
        $GLID = session('GLID');
        $result = $this->UserManagerRepositories->applyDelAccount($GLID);

        return  $result;
    }
    /**
     * delete account
     *
     * @param array $request
     */
    public function accountDelete(array $request){
        var_dump($request);
        return true;
    }
    /**
     *apply acount data change
     *
     * @param array $request
     */
    public function accountChangeInf(array $inf, Request $request){
      
        $GLID = session('GLID');
        $data = json_decode($inf['json']);
        $withdrawal = $data[0]->withdrawal;
        if($withdrawal)
        {
          //退会申請
          //販売中の公演
          $eventOnSale = $this->EvenManageRepositories->getEventOnSale($GLID);    
          if(count($eventOnSale) > 0)
          {
            $result = array(
                'status' => '2',
                'title' => trans('userManage.S_ResultTitle_Fail2'),
                'messeger' => trans('userManage.S_ResultMSG_Fail'),
                'messeger_detail' => "",
                'url' => $request->path(),
            );
            return $result;              
          }
          
          $infData = $this->UserManagerRepositories->userWithdrawalApply($GLID);
          if($infData)
          {
            $result = array(
                'status' => '1',
                'title' => trans('userManage.S_ResultTitle_Withdrawal'),
                'messeger' => trans('userManage.S_ResultMSG_Withdrawal'),
                'messeger_detail' => "",
                'url' => $request->path(),
            );
          }else{
            $result = array(
                'status' => '2',
                'title' => trans('userManage.S_ResultTitle_Fail'),
                'messeger' => trans('userManage.S_ResultMSG_Fail'),
                'messeger_detail' => "",
                'url' => $request->path(),
            );
          }
          
          //sessionの各権限を閲覧のみに変更
          (session('profile_info_flg') == 2) ? session(['profile_info_flg' => 1 ]) : '';
          (session('event_info_flg') == 2) ? session(['event_info_flg' => 1 ]) : '';
          (session('sales_info_flg') == 2) ? session(['sales_info_flg' => 1 ]) : '';
           session(['event_publishable' => 0 ]);

          return $result;
        }
        $account_cd = session('account_cd');
        $infData = $this->UserManagerRepositories->getInformation($GLID);
        $userInf = json_decode($infData['userData'][0]["temporary_info"], true);
        $password = $this->randnum();
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $status = (isset($data[0]->status))?$data[0]->status:'' ;
        $sellTittle = (isset( $data[0]->sellTittle))?$data[0]->sellTittle:'' ;
        $sellChecked = (isset($data[0]->sellChecked ))?$data[0]->sellChecked:'' ;
        $sellUrl = (isset($data[0]->sellUrl))?$data[0]->sellUrl:'' ;
        $companyName = (isset($data[0]->companyName))?$data[0]->companyName:'' ;
        $companyNameKana = (isset($data[0]->companyNameKana))?$data[0]->companyNameKana:'' ;
        $postCode = (isset($data[0]->postCode))?$data[0]->postCode:'' ;
        $postDisplay = (isset($data[0]->postDisplay))?$data[0]->postDisplay:'' ;
        $prefecture = (isset($data[0]->prefecture))?$data[0]->prefecture:'' ;
        $city = (isset($data[0]->city))?$data[0]->city:'' ;
        $placeDetailed = (isset($data[0]->placeDetailed))?$data[0]->placeDetailed:'' ;
        $contactDeparment = (isset($data[0]->contactDeparment))?$data[0]->contactDeparment:'' ;
        $contactName = (isset($data[0]->contactName))?$data[0]->contactName:'' ;
        $tel = (isset($data[0]->tel))?$data[0]->tel:'' ;
        $contactMail = (isset($data[0]->contactMail))?$data[0]->contactMail:'' ;
        $bankName = (isset($data[0]->bankName))?$data[0]->bankName:'' ;
        $branch = (isset($data[0]->branch))?$data[0]->branch:'' ;
        $bankType = (isset($data[0]->bankType))?$data[0]->bankType:'' ;
        $bankAccount = (isset($data[0]->bankAccount))?$data[0]->bankAccount:'' ;
        $bankAccountKana = (isset($data[0]->bankAccountKana))?$data[0]->bankAccountKana:'' ;
        $personalName = (isset( $data[0]->personalName))?$data[0]->personalName:'' ;
        $personalNameKana = (isset($data[0]->personalNameKana))?$data[0]->personalNameKana:'' ;
        $personalTel = (isset($data[0]->personalTel))?$data[0]->personalTel:'' ;
        $personalMail = (isset($data[0]->personalMail))?$data[0]->personalMail:'' ;
        $openInf = (isset($data[0]->openInf))?$data[0]->openInf:'' ;
        $dispFlg = (isset($data[0]->dispFlg))?$data[0]->dispFlg:'' ;
        $GETTIIS_logo_disp_flg = (isset($data[0]->GETTIIS_logo_disp_flg))?$data[0]->GETTIIS_logo_disp_flg:'0' ; // STS 2021/07/17 Task 26
        $dataArray =  (array)$data[0];
      
        if($status === "company"){
            $Rules = [
                'sellTittle' => 'required',
                'sellUrl' => 'required',
                'companyName' => 'required',
                'companyNameKana' => 'required',
                'contactName' => 'required',
                'tel' => 'required',
                'contactMail' => 'required',
                'bankName' => 'required',
                'branch' => 'required',
                'bankType' => 'required',
                'bankAccount' => 'required',
                'bankAccountKana' => 'required',
            ];
        }else{
            $Rules = [
                'sellTittle' => 'required',
                'sellUrl' => 'required',
                'personalName' => 'required',
                'personalNameKana' => 'required',
                'personalTel' => 'required',
                'personalMail' => 'required',
                'file_01' => 'required',
                'bankName' => 'required',
                'branch' => 'required',
                'bankType' => 'required',
                'bankAccount' => 'required',
                'bankAccountKana' => 'required',
            ];
        }
      
        $validator = Validator::make($dataArray, $Rules); 
        if($validator->fails()){
            //return back()->withErrors('error');
        }
        
        $pathLogo = $this->imageUpload($request->file('logo'));
        $pathImage01 = $this->imageUpload($request->file('file_01'));
        $pathImage02 = $this->imageUpload($request->file('file_02'));
        $pathImage03 = $this->imageUpload($request->file('file_03'));
        
        // STS 2021/07/20 Task 26 start
        if(!$data[0]->logo_dell){
            $pathLogo = ($pathLogo)? Storage::url($pathLogo):$userInf['pathLogo'];
        }else{
            $pathLogo = "";
        }
        // STS 2021/07/20 Task 26 end
        
        $pathImage01 = ($pathImage01)? Storage::url($pathImage01):$userInf['pathImage01'];
        $pathImage02 = ($pathImage02)? Storage::url($pathImage02):$userInf['pathImage02'];
        $pathImage03 = ($pathImage03)? Storage::url($pathImage03):$userInf['pathImage03'];
        
        $userInf = array(
            'status'=>$status,
            'sellTittle'=>$sellTittle,
            'sellChecked'=>$sellChecked,
            'sellUrl'=>$sellUrl,
            'companyName'=>$companyName,
            'companyNameKana'=>$companyNameKana,
            'postCode'=>$postCode,
            'postDisplay'=>$postDisplay,
            'prefecture'=>$prefecture,
            'city'=>$city,
            'placeDetailed'=>$placeDetailed,
            'contactDeparment'=>$contactDeparment,
            'contactName'=>$contactName,
            'tel'=>$tel,
            'contactMail'=>$contactMail,
            'bankName'=>$bankName,
            'branch'=>$branch,
            'bankType'=>$bankType,
            'bankAccount'=>$bankAccount,
            'bankAccountKana'=>$bankAccountKana,
            'personalName'=>$personalName,
            'personalNameKana'=>$personalNameKana,
            'personalTel'=>$personalTel,
            'personalMail'=>$personalMail,
            'openInf'=>$openInf,
            'pathLogo'=>$pathLogo,
            'dispFlg'=>$dispFlg,
            'pathImage01'=>$pathImage01,
            'pathImage02'=>$pathImage02,
            'pathImage03'=>$pathImage03,
            'GETTIIS_logo_disp_flg' => $GETTIIS_logo_disp_flg // STS 2021/07/17 Task 26
        );
       
        $infData = $this->UserManagerRepositories->applyChangeInf(json_encode($userInf), $GLID);
        
        if($infData){
            $result = array(
                'status' => '1',
                'title' => trans('userManage.S_ResultTitle_Success'),
                'messeger' => trans('userManage.S_ResultMSG_Success'),
                'messeger_detail' => "",
                'url' => $request->path(),
            );
        }else{
            $result = array(
                'status' => '2',
                'title' => trans('userManage.S_ResultTitle_Fail'),
                'messeger' => trans('userManage.S_ResultMSG_Fail'),
                'messeger_detail' => "",
                'url' => $request->path(),
            );
        }
        
        return $result;
    }

     function getImageExtension($file){
        $img_file = public_path() . $file; 
        //MIMEタイプの取得
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($img_file);
        $extension = null;
        switch ($mime_type) 
        {
          case 'image/jpeg':
            $extension = 'jpg';
            break;
          case 'image/png':
            $extension = 'png';
            break;
        }   
        return $extension; 
      }    
}