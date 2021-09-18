<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Services\LoginServices;
use Exception;
use Lang;
use Log;

class registerController extends Controller
{
    /** @var LoginServices */
    protected $LoginServices;
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
     * UserController constructor.
     * @param LoginServices $UserManagerServices
     */
    public function __construct(LoginServices $LoginServices)
    {
        $this->LoginServices = $LoginServices;
       
    }   
    public function index()
    {
        return view('frontend/login/register');
    }
    public function show()
    {
        return view('frontend/login/registerComplete');
    }
    public function update(Request $request){
        try {
            $recaptcha_verify = $this->_recaptchaVerify($request['recaptcha']);

            if($recaptcha_verify->success){
                if($recaptcha_verify->score < 0.5){
                    throw new Exception (trans('error.S_EXC_MSN_0006'));
                }
            }

            $result = $this->LoginServices->register($request->all());
            
            if(!$result) return back(); //STS 2021/08/30 SQL injection No.5.

            $url = [
                'back_url'  => '/register',
            ];
           
            return view('frontend.login.result', ['result' => $result, 'url' => $url]);   

        }catch (Exception $e){
            $errors  = [
                'success' => false,
                'message' => json_encode([
                    'status' => \Config::get('constant.message_status.error'),
                    'title' => Lang::get('registered.S_ApplyError'),
                    'content' => $e->getMessage(),
                ]),
                'data'  => $request['json'],
            ];
            
            return back()->withInput()->withErrors([json_encode($errors)]);
        }   
    }
}
