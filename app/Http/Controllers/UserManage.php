<?php

namespace App\Http\Controllers;

use App\Services\MailServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\UserManagerServices;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\File;

class UserManage extends Controller
{   
    /** @var UserManagerServices */
    protected $UserManagerServices;

    /**
     * UserController constructor.
     * @param UserManagerServices $UserManagerServices
     */
    public function __construct(UserManagerServices $UserManagerServices)
    {
        $this->UserManagerServices = $UserManagerServices;
       
    }
    /**
     * show user infmation
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {    
        $json = $this->UserManagerServices->show($request->all());
        return view('frontend.userManage.index', ['jsonUserData'=>json_encode($json), 'user_status'=>$json["data"]["userData"]["user_status"],'statusText'=>$json["statusText"], 'introduction'=>$json["data"]["introduction"]]);
    }
    /**
     * edit account information
     *
     * @param  Request  $request
     * @return Response
     */
    public function edit(Request $request)
    {
        $json = $this->UserManagerServices->showEdit($request->all());
        $user_kbn = $json['data']['userData']['status'];

        return view('frontend.userManage.editInf', ['user_kbn'=> $user_kbn,'jsonUserData'=>json_encode($json), 'eventOnSale'=>$json["eventOnSale"]]);
    }
    /**
     * edit account information
     *
     * @param  Request  $request
     * @return Response
     */
    public function accountInfChange(Request $request)
    {
        $json = $this->UserManagerServices->accountChangeInf($request->all(), $request);
      
        return view('frontend.home.consummation', ['json'=>$json]);
    }
    /**
     * change password
     *
     * @param  Request  $request
     * @return redirect userManage
     */
    public function changePassword(Request $request)
    {
        $json = $this->UserManagerServices->applyPassword($request->all());
        return view('frontend.userManage.index', ['jsonUserData'=>json_encode($json), 'user_status'=>$json["data"]["userData"]["user_status"],'statusText'=>$json["statusText"], 'introduction'=>$json["data"]["introduction"]]);
    }
    public function infApply()
    {
        echo 'success';
    }
    public function countryCheack()
    {
        $id = $_GET["infData"];
        $filename =  Storage::disk('csv')->path('KEN_ALL_ROME.CSV');
        $file = fopen($filename, "r");
        while (($line = fgetcsv($file)) !== FALSE) {
            $country[$line[0]] = $line[4].$line[5];
        }
        fclose($file);
        
        if(array_key_exists($id, $country)){
            $str = $country[$id];
        }else{
            $str = "error";
        }

        return $str;
    }
    public function addUserData(Request $request)
    {
        $json = $this->UserManagerServices->addUserData($request->all());
        if($json) {
            return view('frontend.userManage.index', ['jsonUserData'=>json_encode($json), 'statusText'=>$json["statusText"],'user_status'=>$json["data"]["userData"]["user_status"],'statusText'=>$json["statusText"], 'introduction'=>$json["data"]["introduction"]]);
        }
        else {
            $errors[] = trans('userManage.S_AccountDuplicated');

            // [TODO] James 08/12 : 返回畫面的錯誤處理
            return back()->withErrors($errors);
        }
    }
    /**
     * change sub-user data
     *
     * @param  Request  $request
     * @return redirect userManage
     */   
    public function changeSubUserData(Request $request)
    {   
        $json = $this->UserManagerServices->subuserDataChange($request->all());
        if($json) {
            return redirect('/userManage');
            //return view('frontend.userManage.index', ['jsonUserData'=>json_encode($json), 'statusText'=>$json["statusText"]]);
        }
        else {
            $errors[] = trans('userManage.S_AccountDuplicated');

            // [TODO] James 08/12 : 返回畫面的錯誤處理
            return back()->withErrors($errors);
        }
        
    }
    /**
     * apply delete acount
     *
     * @param  Request  $request
     * @return redirect userManage
     */
    public function accountDelete(Request $request)
    {  
        $json = $this->UserManagerServices->deleteId();

        return redirect('/consummation');
    }
    /**
     * apply acount data change
     *
     * @param  Request  $request
     * @return redirect userManage
     */
    public function accountChangeInf(Request $request)
    {  
        $json = $this->UserManagerServices->deleteId();

        return redirect('/userManage');
        //return view('frontend.userManage.index', ['jsonUserData'=>$json]);
    }

    /**
     * make csv files for GETTIIS
     *
     * @param  int  $guid
     * @return 
     */
    // public function transport(Request $request)
    // {
    //   if($request->guid)
    //     $this->UserManagerServices->transportUserInfo($request->guid);  
    //     dd('Finish the transport');
    //   return redirect() ->back();
    // }

}
