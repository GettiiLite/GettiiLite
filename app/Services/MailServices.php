<?php

namespace App\Services;

use Mail;
use Exception;
use Log;
use App;
use App\Repositories\UserManagerRepositories;

class MailServices
{
    /** @var UserManagerServices */
    protected $UserManagerRepositories;
    protected $mail_address;

    /**
     * UserController constructor.
     * @param UserManagerServices $UserManagerServices
     * @return
     */
    public function __construct(UserManagerRepositories $UserManagerRepositories)
    {
        $this->UserManagerRepositories = $UserManagerRepositories;
    }   
    /**
     * passwordRemind
     *
     * @return rand number
     */
    public function passwordRemind($userInf, $password, $addFlg=false){
       
        $subUserData = $this->UserManagerRepositories->getSubUserData($userInf);
        $this->mail_address = $subUserData[0]['mail_address'];
        $mailContent = array(
            'contract_name' =>  'ご利用者',
            'mail_address'=>$subUserData[0]['mail_address'],
            'account_code'=>$subUserData[0]['account_code'],
            'password'=>$password,
            'date'=>date("Y/m/d"),
            'addFlg'=>$addFlg,
        );       
       
        try {
            Mail::send('emails.passwordReminder', ['data' => $mailContent], function($message)use($mailContent,$addFlg) {
                $subject = ($addFlg)?trans('mail.S_PWDCreate_title'):trans('mail.S_PWDRemind_title');
                $message->to($this->mail_address, '')->subject($subject);
            });
        }catch (Exception $e) {
            Log::notice($e->getMessage());
            App::abort(500);
        }

        return true;
    }
    /**
     *root send account password 
     *
     * @return rand number
     */
    public function adminPasswordRemind($data){
       
        $subUserData = $this->UserManagerRepositories->getSubAccountData($data);
        $this->mail_address = $subUserData[0]['mail_address'];
        $mailContent = array(
            'contract_name' =>  $subUserData[0]['contract_name'],
            'mail_address'  =>  $subUserData[0]['mail_address'],
            'account_code'  =>  $subUserData[0]['account_code'],
            'password'      =>  $data['password'],
            'date'          =>  date("Y/m/d"),
            'addFlg'        =>  false,
        );       
       
        try {
            Mail::send('emails.passwordReminder', ['data' => $mailContent], function($message)use($mailContent) {

                $message->to($this->mail_address, '')->subject(trans('mail.S_PWDRemind_title'));
            });

            Log::notice('mail success');
        }catch (Exception $e) {
            Log::notice('mail fail'.$e->getMessage());
            return false;
        }

        return true;
    }
    /**
     * register send password
     *
     * @return bool
     */
    public function sendPassword($userInf, $password){
  
        $subUserData = $this->UserManagerRepositories->getSubUserData($userInf);
        $this->mail_address = $subUserData[0]['mail_address'];
        $mailContent = array(
            'contract_name' => empty($subUserData[0]['contract_name'])?'ご利用者':$subUserData[0]['contract_name'],
            'account_code' => $subUserData[0]['account_code'],
            'password' => $password,
            'date'=>date("Y/m/d"),
        );       
        
        try {
            Mail::send('emails.sendPassword', ['data' => $mailContent], function($message)use($mailContent) {

                $message->to($this->mail_address, '')->subject(trans('mail.S_PWDRemind_newUser'));
            });
        } catch (Exception $e) {
            Log::notice('rsgister send password'.$e->getMessage());
            return false;
        }

        return true;
    }
    /**
     * account password
     *
     * @return bool
     */
    public function sendAccountPassword($userInf, $password){
        
        $subUserData = $this->UserManagerRepositories->getSubUserData($userInf);
        $this->mail_address = $subUserData[0]['mail_address'];
        $mailContent = array(
            'mail_address'=>$subUserData[0]['mail_address'],
            'account_code'=>$subUserData[0]['account_code'],
            'password'=>$password,
            'contract_name' => empty($subUserData[0]['contract_name'])?'ご利用者':$subUserData[0]['contract_name'],
            'date'=>date("Y/m/d"),
            'addFlg' => false,
        );       

        try {
            Mail::send('emails.passwordReminder', ['data' => $mailContent], function($message)use($mailContent) {

                $message->to($this->mail_address, '')->subject(trans('mail.S_PWDRemind_title'));
            });
        } catch (Exception $e) {
            Log::notice($e->getMessage());
            return false;
        }

        return true;
    }
    /**
     * draw mail
     * @param
     * @return 
     */
    public function sendDrawMail($json){
       
        $this->mail_address =  $json['inf'][0]['mail'];

        try {
            Mail::send('emails.resendDrawNotice', ['data' => $json], function($message)use($json) {

                $message->to($this->mail_address, '')->subject(trans('mail.S_NOticeResend_title'));
            });
        } catch (Exception $e) {
            Log::notice($e->getMessage());
            return false;
            //App::abort(500);
        }

        return true;
    }
    
    /**
     *userApplyCompleteRemind
     *
     */
    public function userApplyCompleteRemind($contract_name,$mail_address,$reviewStatus){
       
        $this->mail_address = $mail_address;
        $mailContent = array(
            'contract_name' => $contract_name,
            'reviewStatus'  => $reviewStatus
        );       
       
        try {
            Mail::send('emails.userApplyComplete', ['data' => $mailContent], function($message)use($mailContent) {
                $subject = ($mailContent['reviewStatus'] == 9)?trans('mail.S_userApply'):trans('mail.S_userStop');
                $message->to($this->mail_address, '')->subject($subject);
            });

            Log::notice('mail success');
        }catch (Exception $e) {
            Log::notice('mail fail'.$e->getMessage());
            return false;
        }

        return true;
    }    

    /**
     *userWithdrawalCompleteRemind
     *
     */
    public function userWithdrawalCompleteRemind($user_id,$contract_name,$mail_address){
       
        $this->mail_address = $mail_address;
        $mailContent = array(
            'user_id' => $user_id,
            'contract_name' => $contract_name,
        );       
       
        try {
            Mail::send('emails.userWithdrawalComplete', ['data' => $mailContent], function($message)use($mailContent) {
                $subject = trans('mail.S_userWithdrawral');
                $message->to($this->mail_address, '')->subject($subject);
            });

            Log::notice('mail success');
        }catch (Exception $e) {
            Log::notice('mail fail'.$e->getMessage());
            return false;
        }
        return true;
    }    
}