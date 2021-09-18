<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Services\LoginServices;
use Log;
use App;
use Exception;

class AuthController extends Controller
{
    /** @var LoginServices */
    protected $LoginServices;

    /**
     * UserController constructor.
     * @param LoginServices $UserManagerServices
     */
    public function __construct(LoginServices $LoginServices)
    {
        $this->LoginServices = $LoginServices;
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
    public function show()
    {
        return view('frontend/login/index');
    }
    public function login(Request $request)
    {   
        $result = $this->LoginServices->login($request->all());
      
        if($result){
            return redirect('/notice')->with('status', 'login');;
        }else{
            Log::info('error code : 21 ');
            return back()->withErrors(trans('basisInf.S_LoginError'));
        }
    }
    public function logout(Request $request)
    {   
        $result = $this->LoginServices->logout($request->all());
       
        return redirect('/');
    }
    public function passwordReminder()
    {
        return view('frontend/login/accountReminder');
    }

    public function passwordReminderApply(Request $request)
    {       
        try{
            if(!\Config::get('app.debug')){
                $result_json = $this->_recaptchaVerify($request['recaptcha']);
    
                if($result_json->success){
                    if($result_json->score < 0.5){
                        throw new Exception("error msg : passwordReminderApply |".$result_json->challenge_ts);
                    }
                }else{
                    throw new Exception("google recaptcha verify return fasle");
                }
            }

            $result = $this->LoginServices->accountPasswork($request->all());
    
            $url = [
                'back_url'  => '/accountReminder',
            ];

            return view('frontend.login.result', ['result' => $result, 'url' => $url]);      
                
            
        }catch(Exception $e){
            Log::info('error code : 211 | error messeger : '.$e->getMessage());
            return   App::abort(500);
        }
      
    }

   //STS 2021/28/08 Task 48 No4 --START

    // public function passwordChangeApply(Request $request)
    // {
    //     $result = $this->LoginServices->accountChangePasswork($request->all());
       
    //     if($result){
    //         return back()->with('message', trans('userManage.S_CompletedChangePwd'))
    //                      ->with('change_status', true);
    //     }else{
    //         return back()->with('message', trans('userManage.S_ChangePwdError'))
    //                      ->with('change_status', false);
    //     }

    // }
    public function passwordChangeApply(Request $request)
    {
        $result = $this->LoginServices->accountChangePasswork($request->all());
        return back()->with('message',$result['mess'])
                    ->with('change_status', $result['status']);
    }
    //STS 2021/28/08 Task 48 No4 --END
}
