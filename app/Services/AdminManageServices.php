<?php

namespace App\Services;

use Log;
use Exception;
use App;
use Illuminate\Http\Request;
use App\Repositories\AdminManageRepositories;
use App\Repositories\EvenManageRepositories;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\EvenManageServices;
use App\Services\MailServices;
use Illuminate\Support\Facades\Hash;
use ZipArchive;
use finfo;

class AdminManageServices
{
    /** @var AdminManagerRepositories */
    protected $AdminManagerRepositories;

    protected $SendMail;

    const PAGE_SIZE = 20;

    /**
     * AdminManageServices constructor.
     */
    public function __construct(AdminManageRepositories $AdminManageRepositories, EvenManageRepositories $EvenManageRepositories, EvenManageServices $EvenManageServices, MailServices $SendMail)
    {
        $this->AdminManageRepositories = $AdminManageRepositories;
        $this->EvenManageRepositories = $EvenManageRepositories;
        $this->EvenManageServices     = $EvenManageServices;
        $this->SendMail = $SendMail;
    }   
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

    private function makeCsvLine($data, $f) {   
        foreach($data as $line)
        {
            $out = '';
            $row_tmp = '"';
            $row_tmp .= implode('","', $line);
            $row_tmp .= '"' . "\n";
            $out .= $row_tmp;
            fwrite($f, $out);
        }
    }
    /**
     * make User csv files for GETTIIS
     *
     * @param    $user_id
     * @return 
     */
    private function transportUserInfo($GLID){
        //GETTIIS連携用ユーザーCSV作成
         $outputTime = str_replace('/','',date("Y/m/d")).str_replace(':','',date("H:i:s")); 
         Log::debug("UserManagerService.transportUserInfo@".$outputTime);
         // ユーザー情報 gep_user
        $userInfo = $this->AdminManageRepositories->getUserInfoForCsv($GLID);  
  
        $gep_user = array(
                           //ヘッダー
                           array(
                                 "usercd",          //ユーザーコード
                                 "netusercd",       //クライアントコード
                                 "usernm",          //ユーザー名
                                 "portalnm",        //ポータル表示名
                                 "home_url",        //ホームページアドレス
                                 "gettii_url",      //GettiiＵＲＬ
                                 "collectdt",       //データ収集日時
                                 "kousinkbn",       //更新区分
                                 "logogazou",       //ロゴ画像
                                 "logogazou_exte",  //ロゴ画像拡張子
                                 "logogazou_flag",  //ロゴ画像処理フラグ
                                 "usernm_en",       //ユーザー名（英）
                                 ),
                          );
       
        //ロゴ画像をバイナリ文字列化　Binary stringization of thumbnail images 
        $logo = null;
        
        if(!empty($userInfo->logo_image) && file_exists(public_path() . $userInfo->logo_image))
        { 
            $ifile = file_get_contents(public_path() . $userInfo->logo_image);
          // base64エンコード
          $logo = base64_encode($ifile);     
        }
        // STS 2021/07/17 Task 26 Start
        $temporary_info = json_decode($userInfo->temporary_info);
        $GETTIIS_logo_disp_flg = isset($temporary_info->GETTIIS_logo_disp_flg) ? $temporary_info->GETTIIS_logo_disp_flg : '0';
		$GETTIIS_disp_flg = isset($temporary_info->dispFlg) ? $temporary_info->dispFlg ? '1' : '0' : '0'; //STS 2021/08/09  FIX
        // STS 2021/07/17 Task 26 End
        $gep_user[] = array(
                             $userInfo->user_code,     //ユーザーコード
                             $userInfo->user_id,       //クライアントコード
                             $userInfo->disp_name,     //ユーザー名
                             $userInfo->disp_name,     //ポータル表示名
                             $userInfo->home_page,     //ホームページアドレス
                             "",                       //GettiiＵＲＬ
                             date("Y/m/d H:i:s"),      //データ収集日時（CSV出力日）
                             $GETTIIS_disp_flg,                      //更新区分 1:追加 2:変更 3:削除 //[James] 7/17 : Need to modify when updated the user info  STS 2021/07/28
                             $logo?$logo:"",                    //ロゴ画像
                             $logo?$this->getImageExtension($userInfo->logo_image):"",  //ロゴ画像拡張子
                             $GETTIIS_logo_disp_flg,                     //ロゴ画像処理フラグ (0:何もしない 1:作成・更新する 3:削除する
                             "",                      //ユーザー名（英）
                            );  
        
        $f = fopen(storage_path(config('app.mst_temp_path'))."/gep_user_" . $outputTime . ".csv", "w"); //gettiilite\public
        if ( $f ) 
        {
          $this->makeCsvLine($gep_user, $f);
        }else{
           throw new Exception("transport error : ".(string)$f);    
        }
        fclose($f);      
   
        //圧縮処理
        $zipFileName = 'gettiis_mstdata_' . $outputTime . '.zip';
        $zip = new ZipArchive;
        $zip->open(storage_path(config('app.mst_save_path')).'/'.$zipFileName , ZipArchive::CREATE);
        $zip->addFile(storage_path(config('app.mst_temp_path')).'/gep_user_' . $outputTime . '.csv','gep_user_' . $outputTime . '.csv');
        $zip->close();
  
        //圧縮済のcsv削除
        // 拡張子が.csvのファイルをglobで取得しループ処理
        $dir = glob(storage_path(config('app.mst_temp_path')).'/*.csv');
        foreach ($dir as $file) 
        {
          // globで取得したファイルをunlinkで1つずつ削除していく
          unlink($file);
        }
              
        //GL_PORTAL_MST_OUTPUTにデータ作成
        $mstOutPut = array(
                          'sight_id'    => $userInfo->user_id,
                          'data_id'     => $userInfo->user_code . $outputTime,
                          'data_kbn'    => '1', //1:パッチデータ 2:スナップショットデータ
                          'output_date' => $outputTime,
                          'corp_target' => '1',//0:対象外 1:対象
                          'file_name'   => $zipFileName
                           );  
        
        $this->AdminManageRepositories->portalMstOutputInsert($mstOutPut);         
     }   

    /**
     * assign user acount
     * @parm array $userInf
     * @return array result
     */
    protected function assignSubUser($data){
        $result = array();
        
        foreach ($data as $value) {
           
            if( strtotime('9999-12-31') === strtotime($value['expire_date'])){
                $dateRadio = 'had';
                $date = '';
            }else{
                $dateRadio = 'date';
                $date = $value['expire_date'];
            }
         
            $subAcount = array( 
                "id"=>$value['account_number'],
                "name"=>$value['account_code'],
                "mail"=>$value['mail_address'],
                "profile_info_flg"=>$value['profile_info_flg'],
                "event_info_flg"=>$value['event_info_flg'],
                "sales_info_flg"=>$value['sales_info_flg'],
                "personal_info_flg"=>$value['personal_info_flg'],
                "member_info_flg"=>$value['member_info_flg'],
                "permissionDeadline"=>$dateRadio,
                "deadlineDate"=>$date,
                "userStatus"=>$value['status'],
                "note"=>$value['remarks']
            );

            array_push($result, $subAcount); 
        }

        return $result;
    }
    /**
     * 更新 user detail 資料
     * @param   $json
     * @return  $result
     */
    public function detailUpdate($json){
        $detail_data = $json[0]->data[0];
        
        $update_data = array(
            'GETTIIS_disp_flg'  => $detail_data->GETTIIS_disp_flg,
            'GLID'              => $detail_data->GLID,
            'SID'               => $detail_data->SID,
            'aid'               => $detail_data->aid,
            'xcdkey'            => $detail_data->xcdkey,
            'url_gs'            => $detail_data->url_gs,
            'url_api'           => $detail_data->url_api,
        );

        //commission client 更新
        $commission_data = array(
            'GLID'       => $detail_data->GLID,
            'account_cd' => session('account_cd'),
            'commission' => $detail_data->commission_info
        );
        $this->AdminManageRepositories->updateCommissionClient($detail_data->GLID,$commission_data);

        //無料許可フラグ更新
        $this->AdminManageRepositories->setFreeTixInfo($detail_data->GLID, $detail_data->freetix);
                
        $gssite_update['status']['update_status'] = true;
        if($detail_data->GSSITE) {
            if ($detail_data->SID > 1){
                $gssite_update = $this->AdminManageRepositories->updateGssite($update_data);
            }
            else {
                $gssite_update = $this->AdminManageRepositories->createGssite($update_data);
            }
    
        }
        if($gssite_update['status']['update_status'])
            $gssite_update = $this->AdminManageRepositories->updateUserGETTIIS($update_data);
       
        $result = json_encode($gssite_update);

        return $result;
    }
    /**
     *  取得 detail 資料
     * @param $GLID
     * @return  $result
     */
    public function detail($GLID){
        $gssite_update = session('gssite_update');

        $filter_data = array(
            'GLID' => $GLID,
        );
       
        $gssite_data = $this->AdminManageRepositories->getGssiteData($filter_data);

        if($gssite_update){
            $status = 'update';
        }else{
            $status = 'show';
        }

        $status = array(
            'status' => $status,
        );

        //取得時点で有効な料率/手数料の取得
        $validCommission = $this->AdminManageRepositories->getValidCommissionData($GLID);
        //料率/手数料の設定履歴取得
        $commissionHistory = $this->AdminManageRepositories->getCommissionHistory($GLID);
        //無料チケット許可フラグ
        $freeTix = $this->AdminManageRepositories->getFreeTixInfo($GLID);
        
        $data = array(
            'GLID'                   => $GLID,
            'gssite_data'            => json_encode($gssite_data),
            'freeTix'                => $freeTix,
            'gssite_update'          => $gssite_update,
            'validCommission'        => $validCommission,
            'commissionHistory'      => $commissionHistory,
        );

        $result = array(
            'status'  => $status,
            'data'    => $data, 
        );

        return $result;
    }
    /**
     * change account password 
     * @parm array $request
     * @return array $result
     */
    public function accountPasswordChange(array $request){

        $password = $this->randnum();
        $account_cd = session('account_cd');
        
        $data = array(
            'GLID'           =>  $request['GLID'],
            'account_number' =>  $request['accountNumber'],
            'password'       =>  $password,
            'update_account_cd'     =>  $account_cd,
        );
        
        $this->AdminManageRepositories->changeAccPwd($data);

        $mailResult = $this->SendMail->adminPasswordRemind($data);

        if($mailResult){
            $msg = 'success';
        }else{
            $msg = 'failure';
        }

        return $msg;
    }
    /**
     * get all user data
     * @param $request
     * @return result
     */
    public function index(array $request, $page){
        $url = 'adminManage?filter=n';
        $adminApplyStatus = array('0', '0', '0', '0', '0', '0', '0');
        $userKbnStatus = array('0', '0', '0');
        $applyStatusTrans = array(
            '0' => '0',
            '1' => '1',
            '9' => '2',
            '2' => '3',
            '8' => '4',
            '-2' => '5',
            '-1' => '6'
        );
      
        //search admin status
        if(isset($request["adminStatus"])){
            $adminStatus = $request["adminStatus"];
            foreach ($adminStatus as $value) {
                $statusTrans = $applyStatusTrans[$value];
                $adminApplyStatus[$statusTrans] = '1';
                $url .= '&adminStatus[]=' . $value;
            }
        }else{
            $adminStatus =  array('0', '1', '9', '2', '8', '-2', '-1');
            $adminApplyStatus = array('1', '1', '1', '1', '1', '1', '1');
        }
       
        //search date range
        if(isset($request["applyDate"])){
            $applyDate = explode("-", $request["applyDate"]);
            $starDate = trim($applyDate[0]);
            $endDate = trim($applyDate[1]);

            if(isset($request["dateFilter"])){
                $dateFilter = true;
                $url .= '&dateFilter=' . $request["dateFilter"];
            }else{
                $dateFilter = false;
            }

            $url .= '&applyDate=' . $request["applyDate"];
        }else{
            $starDate = date("Y-m-d", strtotime("-7 Day"))." 00:00";
            $endDate = date("Y-m-d")." 23:59";
            $dateFilter = true;
        }
       
        //search user kbn
        if(isset($request["userKbn"])){
            $userKbn = $request["userKbn"];
            foreach ($userKbn as $value) {
                $userKbnStatus[$value] = '1';
                $url .= '&userKbn[]=' . $value;
            }
        }else{
            $userKbn = null;
            $userKbnStatus = array('1', '1', '1');
        }

        //search keyword
        if(isset($request["keyword"])){
            $keyword = $request["keyword"];
            $url .= '&keyword=' . $request["keyword"];
        }else{
            $keyword = null;
        }

        $filterData = array(
            'admin-status' => $adminStatus,
            'date-filter'  => $dateFilter,
            'star-date'    => $starDate,
            'end-date'     => $endDate,
            'user-kbn'     => $userKbn,
            'keyword'      => $keyword,
        );

        $userData = $this->AdminManageRepositories->getAdminData($filterData);
        $paginator = null;
        if($page >= 1)
        {
          $totalEvent = count($userData);
          $nowPageStar = ($page - 1) * self::PAGE_SIZE;
          $nowPageEnd = self::PAGE_SIZE;
          $pageData = array_slice($userData, $nowPageStar, $nowPageEnd);
          $paginator = new LengthAwarePaginator($pageData, $totalEvent, self::PAGE_SIZE);
          $paginator->withPath($url);
          $dateFilter = ($dateFilter)?'date':'all';
        }else{
          //page機能使用無し
          $pageData = $userData;            
        }
        $status = array(
            'user-kbn-status'       => $userKbnStatus,
            'admin-apply-status'    => $adminApplyStatus,
            'keyword'               => $keyword,
            'star-date'             => $starDate,
            'end-date'              => $endDate,
            'date-filter'           => $dateFilter,
        );

        $data = array(
            'user-data' => $pageData,
        );

        $result = array(
            'status' => $status,
            'data' => $data, 
            'paginator' => $paginator,
        );

        return $result;
    }

    /**
     * get user information data
     * @parm $userId
     * @return result
     */
    public function getValidationData($GLID){
       $userData       = $this->AdminManageRepositories->getUserData($GLID);
       $introductionData = $this->AdminManageRepositories->getIntorodutionData($GLID);
       $accountData    = $this->AdminManageRepositories->getSubUserData($GLID);
       $accountInf     = $this->assignSubUser($accountData);
       $commissionData = $this->AdminManageRepositories->getCommissionData($GLID);

       if(empty($commissionData)){
            $comissionPercent  = 0;
            $comissionFee      = 0;
       }else{
            $comissionPercent  = $commissionData[0]['comission_percent'];
            $comissionFee      = $commissionData[0]['comission_fee'];
       }

       if( isset($userData[0]) ){

            if( is_null($userData[0]['temporary_info']) ){

            }else{
                $temporaryInfo = json_decode($userData[0]['temporary_info'], true);
               
                if( !empty($temporaryInfo['pathLogo']) ){
                    $pathLogo = $temporaryInfo['pathLogo'];
                }else{
                    $pathLogo = " ";
                }

                if( !empty($userData[0]['id_image']) ){
                    $idImage = json_decode($userData[0]['id_image'], true);
                }

                if( !empty($temporaryInfo['pathImage01']) ){
                    $pathImage01 = $temporaryInfo['pathImage01'];
                }else{
                    $pathImage01 = null;
                }

                if( !empty($temporaryInfo['pathImage02']) ){
                    $pathImage02 = $temporaryInfo['pathImage02'];
                }else{
                    $pathImage02 = null;
                }

                if( !empty($temporaryInfo['pathImage03']) ){
                    $pathImage03 = $temporaryInfo['pathImage03'];
                }else{
                    $pathImage03 = null;
                }
               
                $postCode       = \Config::get('constant.post');
                $postCodeTran   = (isset($userData[0]['post_code']) && !empty($userData[0]['post_code'])) ? $postCode[$userData[0]['post_code']][0] . $postCode[$userData[0]['post_code']][1]  : "";
                $userAddress    = isset($userData[0]['address']) ? $userData[0]['address'] : "";
                $address        = $userAddress;

                $userInf = array(
                    'user_code'          => isset($userData[0]['user_code']) ? $userData[0]['user_code'] : "",
                    'user_id'            => isset($userData[0]['user_id']) ? $userData[0]['user_id'] : "",
                    'user_kbn'           => isset($userData[0]['user_kbn']) ? $userData[0]['user_kbn'] : "",
                    'user_status'        => isset($userData[0]['user_status']) ? $userData[0]['user_status'] : "",
                    'comission_percent'  => $comissionPercent,
                    'comission_fee'      => $comissionFee,  
                    'disp_flg'           => isset($userData[0]['GETTIIS_disp_flg']) ? $userData[0]['GETTIIS_disp_flg'] : "",
                    'disp_name'          => isset($userData[0]['disp_name']) ? $userData[0]['disp_name'] : "",
                    'home_page'          => isset($userData[0]['home_page']) ? $userData[0]['home_page'] : "",
                    'contract_name'      => isset($userData[0]['contract_name']) ? $userData[0]['contract_name'] : "",
                    'contract_name_kana' => isset($userData[0]['contract_name_kana']) ? $userData[0]['contract_name_kana'] : "",
                    'post_code'          => isset($userData[0]['post_code']) ? $userData[0]['post_code'] : "",
                    'post_display'       => isset($userData[0]['post_display']) ? $userData[0]['post_display'] : "",
                    'location'           => $postCodeTran,
                    'address'            => $address,
                    'department'         => isset($userData[0]['department']) ? $userData[0]['department'] : "",
                    'contact_person'     => isset($userData[0]['contact_person']) ? $userData[0]['contact_person'] : "",
                    'tel_num'            => isset($userData[0]['tel_num']) ? $userData[0]['tel_num'] : "",
                    'mail_address'       => isset($userData[0]['mail_address']) ? $userData[0]['mail_address'] : "",
                    'bank_name'          => isset($userData[0]['bank_name']) ? $userData[0]['bank_name'] : "",
                    'branch_name'        => isset($userData[0]['branch_name']) ? $userData[0]['branch_name'] : "",
                    'account_kbn'        => isset($userData[0]['account_kbn']) ? $userData[0]['account_kbn'] : "",
                    'account_num'        => isset($userData[0]['account_num']) ? $userData[0]['account_num'] : "",
                    'account_name'       => isset($userData[0]['account_name']) ? $userData[0]['account_name'] : "",
                    'introduction_text'  => isset($userData[0]['introduction_text']) ? $userData[0]['introduction_text'] : "",
                    'pathLogo'           => $pathLogo,
                    'pathImage01'        => $pathImage01,
                    'pathImage02'        => $pathImage02,
                    'pathImage03'        => $pathImage03,
                    'introductionCode'   => $introductionData
                );
            }

         
       }else{
            return null;
       }
        
        $status = array(
            'GLID' => $GLID,
        );

        $data = array(
            'user_data'          => $userInf,
            'user_temporaryInfo' => $userData[0]['temporary_info'],
            'account_data'       => json_encode($accountInf),
        );

        $result = array(
            'status'  => $status,
            'data'    => $data, 
        );
        return $result;
    }

    /**
     * data validation upload
     * @parm array $request
     * @return result
     */
    public function dataValidationUpload(array $request){
        
        $GLID = $request['GLID'];
        $user_id = $request['user_id'];
        $reviewStatus = $request['reviewStatus'];
        $reviewStatusOld = $request['reviewStatusOld'];
        $json = json_decode($request['json'], true);
        $account_cd = session('account_cd');
        //update user data
        $data = array(
            'GLID'          => $GLID,
            'status'        => $reviewStatus,
            'json'          => json_encode($json[0]['userData'][0]),
            'account_cd'    => $account_cd,
        );
        switch($reviewStatus) {
            case 0: //新申請
                $data['event_publishable'] = 0;
            break;
            case 1: //審核中 -> event_publishable no need to change
            case 2: //審核未通過 -> event_publishable no need to change
            case 8: //註銷申請中 -> event_publishable no need to change
            break;
            case 9: //審核已通過
                $data['event_publishable'] = 1;
                // [TODO] 08/30 James : 如原狀態為中止的話需更新該帳戶下所有的event的狀態並同步至前端。
            break;
            case -1: //已中止
            case -2: //已註銷
                $data['event_publishable'] = 0;
            break;
            default : //error status
                throw new Exception('AdminManageSevices : Unknow status.');
            return;
        }
        
        $userData = $json[0]['userData'][0];
        $contract_name = ($json[0]['userKbn'] == 1) ? (isset($userData["companyName"]) ? $userData["companyName"] : ""):(isset($userData["personalName"]) ? $userData["personalName"] : "");
        $mailAddress = ($json[0]['userKbn'] == 1) ? (isset($userData["contactMail"]) ? $userData["contactMail"] : "") : (isset($userData["personalMail"]) ? $userData["personalMail"] : "");

        $mailResult = true;
        if(($reviewStatusOld != 9 && $reviewStatus  == 9) || ($reviewStatusOld != -1 && $reviewStatus  == -1))
        {
          //審査OK/NG時 mail送信
          $mailResult = $this->SendMail->userApplyCompleteRemind($contract_name,$mailAddress,$reviewStatus);            
        }        
        if($reviewStatusOld == 8 && $reviewStatus  == -2)
        {
          //退会申請→退会時 mail送信
          $mailResult = $this->SendMail->userWithdrawalCompleteRemind($user_id,$contract_name,$mailAddress);    
        }        
        if($mailResult)
        {    
          $this->AdminManageRepositories->updateTemporaryInfo($data);

          if(in_array($reviewStatus, array(9))){
            

              if($userData["bankType"] == "spec"){
                  $bankType = 2;
              }else{
                  $bankType = 1;
              }
           
              $id_image = array(
                'pathImage01' => $userData['pathImage01'],
                'pathImage02' => $userData['pathImage02'],
                'pathImage03' => $userData['pathImage03'],
              );
            
              // $postCode = \Config::get('constant.post');
              // if($userData["postCode"]){dd('csacas');
              //     $address = $postCode[$userData["postCode"]][0] . $postCode[$userData["postCode"]][1] . $userData['placeDetailed'];
              // }else{
              //     $address = '';
              // }
              $data = array(
                'logo_image'        =>  isset($userData["pathLogo"]) ? $userData["pathLogo"] : "",
                'id_image'          =>  json_encode($id_image),
                'disp_name'         =>  isset($userData["sellTittle"]) ? $userData["sellTittle"] : "",
                'disp_flg'          =>  isset($userData["dispFlg"]) ? (int)$userData["dispFlg"] : 0,
                'home_page'         =>  isset($userData["sellUrl"]) ? $userData["sellUrl"] : "",
                'contract_name'     =>  ($json[0]['userKbn'] == 1) ? (isset($userData["companyName"]) ? $userData["companyName"] : ""):(isset($userData["personalName"]) ? $userData["personalName"] : ""),
                'contract_name_kana'=>  isset($userData["companyNameKana"]) ? $userData["companyNameKana"] : "",
                'post_code'         =>  isset($userData["postCode"]) ? $userData["postCode"] : "",
                'post_display'      =>  isset($userData["postDisplay"]) ? $userData["postDisplay"] : "",
                'address'           =>  isset($userData["placeDetailed"]) ? $userData["placeDetailed"] : "",
                'department'        =>  isset($userData["contactDeparment"]) ? $userData["contactDeparment"] : "",
                'contact_person'    =>  isset($userData["contactName"]) ? $userData["contactName"] : "",
                'tel_num'           =>  ($json[0]['userKbn'] == 1) ? (isset($userData["tel"]) ? $userData["tel"] : ""):(isset($userData["personalTel"]) ? $userData["personalTel"] : ""),
                'mail_address'      =>  $mailAddress,
                'bank_name'         =>  isset($userData["bankName"]) ? $userData["bankName"] : "",
                'branch_name'       =>  isset($userData["branch"]) ? $userData["branch"] : "",
                'account_kbn'       =>  $bankType,
                // 'temporary_info'    =>  json_encode($json[0]['userData'][0]),
                'event_publishable' =>  1,
                'account_num'       =>  isset($userData["bankAccount"]) ? $userData["bankAccount"] : "",
                'account_name'      =>  isset($userData["bankAccountKana"]) ? $userData["bankAccountKana"] : "",
                'introduction_text' =>  isset($userData["openInf"]) ? $userData["openInf"] : "",
                'temporary_info'    =>  json_encode($json[0]['userData'][0]),
                'GLID'              =>  $GLID,
                'status'            =>  $reviewStatus,
                'account_cd'        =>  $account_cd,
                'GETTIIS_logo_disp_flg'     =>  isset($userData['GETTIIS_logo_disp_flg']) ? $userData['GETTIIS_logo_disp_flg'] : '0' // STS 2021/07/17 Task 26
            );
              $this->AdminManageRepositories->updateUserInfo($data);
              $this->transportUserInfo($GLID);
          }else{

          }

          if($reviewStatus == -2)
          {
           //退会時、GETTIISに該当ユーザーの公演を表示させない。
            //GETTIIS連携済公演を取得
            $event = $this->EvenManageRepositories->getPerformancePublished($GLID);
            foreach ($event as $eventData) 
            {
              //GETTIIS連携済公演を再連携して表示させないようにする。
              $this->EvenManageServices->transportPerfomanceInfo($eventData['performance_id'],null,null,$reviewStatus);
            }
          }
        }       
        $result = array(
          'GLID' => $GLID,
          'mailResult' => $mailResult,
        );
        
        return $result;
    }

    /**
     * master account data upload 
     * @parm array $request
     * @return $GLID
     */
    public function accountDataUpload(array $request){
        $GLID = $request['GLID'];
        $json = json_decode($request['json'], true);
        $account_cd = session('account_cd');
        $accoundData = $json[0]['accountData'][0];
       
        //update sub account data
        if($accoundData){

            if($accoundData['permissionDeadline'] == 'had'){
                $expireDate = '9999-12-31 00:00:00';
            }else{
                $expireDate = $accoundData['permissionDeadline'];
            }
            
            if(!isset($accoundData['note'])){
                $remarks = '';
            }else{
                $remarks = $accoundData['note'];
            }
           
            $subAcount = array( 
                'GLID'               =>  $GLID,
                'account_code'       =>  $accoundData['name'],
                'user_status'        =>  $accoundData['userStatus'],
                'mail_address'       =>  $accoundData['mail'],
                'expire_date'        =>  $expireDate,
                'profile_info_flg'   =>  $accoundData['profile_info_flg'],
                'event_info_flg'     =>  $accoundData['event_info_flg'],
                'sales_info_flg'     =>  $accoundData['sales_info_flg'],
                'member_info_flg'    =>  $accoundData['member_info_flg'],
                'personal_info_flg'  =>  $accoundData['personal_info_flg'],
                'remarks'            =>  $remarks,
                'update_account_cd'  =>  $account_cd,
            );

            $this->AdminManageRepositories->updateAccountInfo($subAcount);            
        }

        return $GLID;
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