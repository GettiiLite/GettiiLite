<?php

namespace App\Repositories;

use App;
use Log;
use Exception;
use App\Models\UserManageModel;
use App\Models\UserAccountModel;
use App\Models\UserExModel;
use App\Models\PortalMstOutputModel;
use App\Models\CommissionModel;
use App\Models\GSSiteModel;
use App\Models\CommissionClientModel;

class AdminManageRepositories
{
    /** @var UserManageModel */
    protected $UserManageModel;
    /** @var UserAccountModel */
    protected $UserAccountModel;
    /** @var UserExModel*/
    protected $UserExModel;    
    /** @var CommissionModel */
    protected $CommissionModel;
    /** @var PortalMstOutputModel */
    protected $PortalMstOutputModel;
    /** @var GSSiteModel */
    protected $GSSiteModel;
    /** @var CommissionClientModel */
    protected $CommissionClientModel;

    /**
     * AdminManagerRepositories constructor.
     * @param UserManageModel $UserManageModel
     * @param UserAccountModel $UserAccountModel
     */
    public function __construct(UserManageModel $UserManageModel, UserAccountModel $UserAccountModel, UserExModel $UserExModel, CommissionModel $CommissionModel, PortalMstOutputModel $PortalMstOutputModel, GSSiteModel $GSSiteModel, CommissionClientModel $CommissionClientModel)
    {
        $this->UserManageModel          = $UserManageModel;
        $this->UserAccountModel         = $UserAccountModel;
        $this->UserExModel              = $UserExModel;
        $this->PortalMstOutputModel     = $PortalMstOutputModel;
        $this->CommissionModel          = $CommissionModel;
        $this->GSSiteModel              = $GSSiteModel;
        $this->CommissionClientModel    = $CommissionClientModel;
    }
    /**
     * 更新 User GETTIIS_disp_flg
     * @param $update_data
     * @return $result 
     */
    public function updateUserGETTIIS($update_data){
        $status = array(
            'update_status' => '',
            'msn_status'    => '',
        );

        $data = array(
            'msn'  => [],
        );

        try {    
            $user = $this->UserManageModel::find($update_data['GLID']);

            // $user->GETTIIS_disp_flg = $update_data['GETTIIS_disp_flg'];
            $user->public2portal = $update_data['GETTIIS_disp_flg'];
            $user->save();
        
            $status['update_status'] = true;
            $status['msn_status']    = \Config::get('constant.message_status.information');
            
            $data['message'] = array(
                                    'title' =>  $update_data['aid'],
                                    'msn'   =>  trans('common.S_SuccessUpdate'),
                                ); 

        } catch (Exception $e) {
            $status['update_status'] = false;
            $status['msn_status']    = \Config::get('constant.message_status.error');
            $data['msn'][] = array(
                                    'title' =>  trans('common.S_FailedUpdate'),
                                    'msn'   =>  $e->getMessage(),
                                ); 
        }

        $result = array(
            'status'  => $status,
            'data'    => $data, 
        );
        
        return $result;
    }
    /**
     * 更新 Gssite 資料
     * @param $update_data
     * @return $result 
     */
    public function updateGssite($update_data){
        $status = array(
            'update_status' => '',
            'msn_status'    => '',
        );

        $data = array(
            'msn'  => [],
        );

        try {

            $aid_exists = $this->GSSiteModel::where('aid', '=', $update_data['aid'])
                                            ->where('SID', '!=', $update_data['SID'])
                                            ->exists();
            
            if($aid_exists){
                throw new Exception('aid :'.$update_data['aid'].trans('common.S_Duplicated'));
            }

            $GSSITE = $this->GSSiteModel::find($update_data['aid']);
        
            $GSSITE_update = $this->GSSiteModel::Where('SID', '=', $update_data['SID'])
                                                ->update([
                                                        'aid'       => $update_data['aid'],
                                                        'xcdkey'    => $update_data['xcdkey'],
                                                        'url_gs'    => $update_data['url_gs'],
                                                        'url_api'   => $update_data['url_api'],
                                                ]);
                    
            $status['update_status'] = true;
            $status['msn_status']    = \Config::get('constant.message_status.information');

            $data['message'] = array(
                                    'title' =>  $update_data['aid'],
                                    'msn'   =>  trans('common.S_SuccessUpdate'),
                                ); 

        } catch (Exception $e) {
            $status['update_status'] = false;
            $status['msn_status']    = \Config::get('constant.message_status.error');
            $data['msn'][] = array(
                                    'title' =>  trans('common.S_FailedUpdate'),
                                    'msn'   =>  $e->getMessage(),
                                ); 
        }

        $result = array(
            'status'  => $status,
            'data'    => $data, 
        );
        
        return $result;
    }
    /**
     * 更新 GL_COMMISSION_CLIENT
     * @param $create_data
     * @return  
     */
    public function updateCommissionClient($GLID,$commission_data){

       try {
         foreach($commission_data['commission'] as $commission){
            
            if($commission->delete_flg == 1)
            {  
              //delete_flg ON
              $this->CommissionClientModel::Where('id', '=', $commission->id)
                                           ->update([
                                                     'delete_flg'       => 1,
                                                     'update_account_cd'=>$commission_data['account_cd'],
                                                   ]);  
              Log::info('commission delete :'. $commission->id);
            }
            if(empty($commission->create_date))
            {
              //新規登録
              $this->CommissionClientModel::updateOrCreate(
                                                  [
                                                    'GLID'             => $GLID,  
                                                    'commission_type'  => $commission->commission_type,
                                                    'apply_date'     => $commission->apply_date,
                                                  ]
                                                 ,[
                                                    'GLID'             => $GLID,  
                                                    'commission_type'  => $commission->commission_type,
                                                    'apply_date'       => $commission->apply_date,
                                                    'rate'             => (empty($commission->rate)) ? 0 : $commission->rate,
                                                    'amount'           => (empty($commission->amount)) ? 0 : $commission->amount,
                                                    'delete_flg'       => 0,
                                                    'update_account_cd'=>$commission_data['account_cd'],
                                                  ]);               
              Log::info('commission add/update:'. $commission->commission_type . '/' . $commission->commission_type . '/' . $commission->rate . '/' . $commission->amount);
            }
         }
        }catch(Exception $e){
            Log::info('updateCommissionClient :'.$e->getMessage());
            throw new Exception ('updateCommissionClient :'.$e->getMessage());
        }
        return;
    }    
    /**
     * 新增 Gssite 資料
     * @param $create_data
     * @return $result 
     */
    public function createGssite($create_data){
        $status = array(
            'update_status' => '',
            'msn_status'    => '',
        );

        $data = array(
            'msn'  => [],
        );

        try {
            $aid_exists = $this->GSSiteModel::where('aid', '=', $create_data['aid'])
                                            ->exists();

            if($aid_exists){
                throw new Exception('aid :'.$create_data['aid'].trans('common.S_Duplicated'));
            }
        
            $GSSITE =  $this->GSSiteModel::create([
                                                    'aid'       => $create_data['aid'],
                                                    'xcdkey'    => $create_data['xcdkey'],
                                                    'url_gs'    => $create_data['url_gs'],
                                                    'url_api'   => $create_data['url_api'],
                                                 ]);
        
            $user = $this->UserManageModel::find($create_data['GLID']);

            $user->SID              = $GSSITE['SID'];

            $user->save();
        
            $status['update_status'] = true;
            $status['msn_status']    = \Config::get('constant.message_status.information');
            $data['message'] = array(
                                    'title' =>  $create_data['aid'],
                                    'msn'   =>  trans('common.S_SuccessUpdate'),
                                ); 

        } catch (Exception $e) {
            $status['update_status'] = false;
            $status['msn_status']    = \Config::get('constant.message_status.error');
            $data['msn'][] = array(
                                    'title' =>  trans('common.S_FailedUpdate'),
                                    'msn'   =>  $e->getMessage(),
                                ); 
        }

        $result = array(
            'status'  => $status,
            'data'    => $data, 
        );
        
        return $result;
    }
    /**
     * get Gssit eData
     * @param $filter_data
     * @return $result
     */
    public function getGssiteData($filter_data){
        
        $user   = $this->UserManageModel::find($filter_data['GLID']);
        $result = array(
                    // 'GETTIIS_disp_flg'  => $user['GETTIIS_disp_flg'],
                    'GETTIIS_disp_flg'  => $user['public2portal'],
                    'SID'               => '',
                    'aid'               => '',
                    'xcdkey'            => '',
                    'url_gs'            => '',
                    'url_api'           => '',
                );

        if($user['SID'] != 1 ) {
            $gs_site = $user->GsSite()
                        ->get();
            $result['SID'] = $user['SID'];
            $result['aid'] = $gs_site[0]['aid'];
            $result['xcdkey'] = $gs_site[0]['xcdkey'];
            $result['url_gs'] = $gs_site[0]['url_gs'];
            $result['url_api'] = $gs_site[0]['url_api'];
        }

        return $result;
    }
    /**
     * get getValidCommissionData
     * @param $GLID
     * @return $result
     */
    public function getValidCommissionData($GLID){

        try{
             $subQuery = $this->CommissionClientModel->select(
                                                              'GLID'
                                                             ,'commission_type'
                                                             ,\DB::raw('max(apply_date)as apply_date')
                                                             )
                                                     ->where('GLID', $GLID)
                                                     ->where('apply_date','<=',date("Y/m/d"))
                                                     ->where('delete_flg',0)
                                                     ->groupby('GLID','commission_type');
             
             $result = \DB::table(\DB::raw("({$subQuery->toSql()}) as sub") )
                       ->mergeBindings($subQuery->getQuery()) 
                       ->Join('GL_COMMISSION_CLIENT', function($join)
                                                     {
                                                       $join->on('GL_COMMISSION_CLIENT.GLID', '=', 'sub.GLID')
                                                            ->on('GL_COMMISSION_CLIENT.commission_type','=','sub.commission_type')
                                                            ->on('GL_COMMISSION_CLIENT.apply_date','=','sub.apply_date');
                                                     })                
                       ->select(
                                'GL_COMMISSION_CLIENT.commission_type'
                               ,\DB::raw('DATE_FORMAT(GL_COMMISSION_CLIENT.apply_date, "%Y/%m/%d") as apply_date')
                               ,'GL_COMMISSION_CLIENT.rate'
                               ,\DB::raw('TRUNCATE(GL_COMMISSION_CLIENT.amount,0) as amount')
                               )
                         ->orderby('commission_type')
                         ->get();

        }catch(Exception $e){
            Log::info('getValidCommissionData| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
        return $result;           
    }    
    /**
     * get getCommissionHistory
     * @param $GLID
     * @return $result
     */
    public function getCommissionHistory($GLID){

        try{
             $result = $this->CommissionClientModel->select(
                                                            'id'
                                                           ,'commission_type'
                                                           ,\DB::raw('DATE_FORMAT(apply_date, "%Y/%m/%d") as apply_date')
                                                           ,'rate'
                                                           ,\DB::raw('TRUNCATE(amount,0) as amount')
                                                           ,\DB::raw('DATE_FORMAT(created_at, "%Y/%m/%d") as create_date')
                                                           ,'delete_flg'
                                                           )
                                                   ->where('GLID', $GLID)
                                                   ->where('delete_flg',0)
                                                   ->orderby('commission_type')
                                                   ->orderby('apply_date' ,'desc')
                                                   ->get();
        }catch(Exception $e){
            Log::info('getCommissionHistory| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
        return $result;           
    }    
    /**
     * get commission data
     * @param $userData
     * @return $result
     */
    public function getCommissionData($GLID){
        try{
            $result = $this->CommissionModel->select('*')
                                            ->where('GLID', $GLID);

            $result = $result->get()->toArray();
            
            return $result;           
        }catch(Exception $e){
            Log::info('function getCommissionData| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get user data
     * @param $filterData
     * @return result 
     */ 
    public function getAdminData($filterData)
    {          
        try{    
            $result = $this->UserManageModel->select('*');

            $result = $result->whereNotIn('GLID', [1]);
       
            if(!empty($filterData['admin-status'])){
                $result = $result->whereIn('user_status', $filterData['admin-status']);
            }
    
            if(!empty($filterData['star-date']) && !empty($filterData['end-date']) && $filterData['date-filter']){
                $from = date($filterData['star-date'].' 00:00');
                $to = date($filterData['end-date'].' 23:59');
                $result = $result->whereBetween('app_date', [$from, $to]);
            }
           
            if(!empty($filterData['user-kbn'])){
                $result = $result->whereIn('user_kbn', $filterData['user-kbn']);
            }
    
            if(!empty($filterData['keyword'])){
                $keyword = $filterData['keyword'];
                $result = $result->where(function($query) use ($keyword)
                                    {
                                        $query->where('user_code', 'like', "%{$keyword}%")
                                                ->orWhere('user_id', 'like', "%{$keyword}%")
                                                ->orWhere('contract_name', 'like', "%{$keyword}%")
                                                ->orWhere('contract_name_kana', 'like', "%{$keyword}%")
                                                ->orWhere('disp_name', 'like', "%{$keyword}%");
                                    });
            }
    
            $result = $result->get()->toArray();

            return $result;
        }catch(Exception $e){
            Log::info('function getAdminData| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }  
    /*
     * get user data
     * @param $GLID
     * @return $result
     */
    public function getUserData($GLID){
        try{ 
            $result = $this->UserManageModel->select('*')
                        ->where('GLID', $GLID)
                        ->get()
                        ->toArray();

            return $result;
        }catch(Exception $e){
            Log::info('function getUserData| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /*
     * getIntorodutionData
     * @param $GLID
     * @return $result
     */
    public function getIntorodutionData($GLID){
        try{
            $result = $this->UserExModel::select('value')->where('GLID', $GLID)->where('parameter', 'SHOUKAI')->get()->toArray();
            $IntCd =null;
            if(count($result) > 0)
            {
              $IntCd = $result[0]['value'];    
            }
            return $IntCd;
        }catch(Exception $e){
            Log::info('function getIntorodutionData| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /*
     * setFreeTixInfo
     * @param $GLID
     * @return $result
     */
    public function setFreeTixInfo($GLID,$freeTix){
        try{
              //無料チケットを許可フラグ更新
              $this->UserExModel::updateOrCreate(
                                                  [
                                                    'GLID'     =>$GLID,
                                                    'parameter'=>'ALLOWFREE',                                                  ]
                                                 ,[
                                                    'GLID'     =>$GLID,
                                                    'parameter'=>'ALLOWFREE',
                                                    'value'    =>$freeTix,
                                                    "created_at" =>  \Carbon\Carbon::now(),
                                                    "updated_at" => \Carbon\Carbon::now(),
                                                  ]);                  
            
        }catch(Exception $e){
            Log::info('function setFreeTixInfo| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /* getFreeTixInfo
     * @param $GLID
     * @return $result
     */
    public function getFreeTixInfo($GLID){
        try{
            $result = $this->UserExModel::select('value')->where('GLID', $GLID)->where('parameter', 'ALLOWFREE')->get()->toArray();
            $freeTix = null;
            if(count($result) > 0)
            {
              $freeTix = $result[0]['value'];    
            }
            return $freeTix;
        }catch(Exception $e){
            Log::info('function getFreeTixInfo| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /*
     * get account data
     * @param $GLID
     * @return $result
     */
    public function getSubUserData($GLID){
        try{
            $result = $this->UserAccountModel->select('*')
                        ->where('GLID', $GLID)
                        ->get()
                        ->toArray();
            
            return $result;
        }catch(Exception $e){
            Log::info('function getSubUserData| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * update user TemporaryInfo data
     * @param $data
     * @return $result
     */
    public function updateTemporaryInfo($data)
    {   
        try{
            $db_data = [
                'user_status'       => $data['status'],
                'temporary_info'    => $data['json'],  
                'update_account_cd' => $data['account_cd'],                                         
            ];

            if(array_key_exists('event_publishable',$data))
                $db_data['event_publishable'] = $data['event_publishable'];

            $result = $this->UserManageModel->where('GLID', $data["GLID"])
                                            ->update($db_data);
            return $result;
        }catch(Exception $e){
            Log::info('function updateTemporaryInfo| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * update user infomation
     * @param array $data
     * @return $result
     */
    public function updateUserInfo($data)
    {   
        try{
            $result = $this->UserManageModel->where('GLID', $data["GLID"])
                                            ->update([
                                                'logo_image'        =>  $data['logo_image'],
                                                'user_status'       =>  $data['status'],
                                                'disp_name'         =>  $data["disp_name"],
                                                'GETTIIS_disp_flg'  =>  $data["disp_flg"],
                                                'home_page'         =>  $data["home_page"],
                                                'contract_name'     =>  $data["contract_name"],
                                                'contract_name_kana'=>  $data["contract_name_kana"],
                                                'post_code'         =>  $data["post_code"],
                                                'post_display'      =>  $data["post_display"],
                                                'address'           =>  $data["address"],
                                                'department'        =>  $data["department"],
                                                'contact_person'    =>  $data["contact_person"],
                                                'tel_num'           =>  $data["tel_num"],
                                                'mail_address'      =>  $data["mail_address"],
                                                'id_image'          =>  $data["id_image"],
                                                'bank_name'         =>  $data["bank_name"],
                                                'branch_name'       =>  $data["branch_name"],
                                                'account_kbn'       =>  $data["account_kbn"],
                                                'account_num'       =>  $data["account_num"],
                                                'account_name'      =>  $data["account_name"],
                                                'introduction_text' =>  $data["introduction_text"],
                                                // 'temporary_info'    =>  $data["temporary_info"],
                                                'event_publishable'    =>  $data["event_publishable"],
                                                'judge_account_cd' =>  $data['account_cd'], 
                                                'judgement_date' =>  \Carbon\Carbon::now(),
                                                'update_account_cd' =>  $data['account_cd'],                                                 
                                                'GETTIIS_logo_disp_flg'     =>  $data['GETTIIS_logo_disp_flg'] // STS 2021/07/17 Task 26
                                            ]);
            return $result;
        }catch(Exception $e){
            Log::info('function updateUserInfo| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * 
     */
    public function updateAccountInfo($data){
        
        try{
            $result = $this->UserAccountModel->where('GLID', $data["GLID"])
                                            ->where('account_number', 0)
                                            ->update([
                                                    "account_code"      =>  $data['account_code'],
                                                    "status"            =>  $data['user_status'],
                                                    "mail_address"      =>  $data['mail_address'],
                                                    "expire_date"       =>  $data['expire_date'],
                                                    "profile_info_flg"  =>  $data['profile_info_flg'],
                                                    "event_info_flg"    =>  $data['event_info_flg'],
                                                    "sales_info_flg"    =>  $data['sales_info_flg'],
                                                    "member_info_flg"   =>  $data['member_info_flg'],
                                                    "personal_info_flg" =>  $data['personal_info_flg'],
                                                    "remarks"           =>  $data['remarks'],
                                                    'update_account_cd' =>  $data['update_account_cd'],                                      
                                                ]);
            return $result;
        }catch(Exception $e){
            Log::info('function updateAccountInfo| error code :  | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }

        /**
     * getUserInfoForCsv
     * @param array $performance_id
     * @return result 
     */ 
    public function getUserInfoForCsv($glid)
    {
        try{
           
            $result = $this->UserManageModel::select('user_code',
                                                      'user_id',
                                                      'contract_name',
                                                      'disp_name',
                                                      'home_page',
                                                      'logo_image',
                                                      'temporary_info' // STS 2021/07/17 Task 26
                                                     )
                                            ->where('GLID', $glid)
                                            ->first();
          
            return $result;
       }catch(Exception $e){
           Log::info('getUserInfoForCsv :'.$e->getMessage());
       }
    }  

    /**
     * insert portalMstOutput date
     * @param array $data
     * @return result 
     */ 
    public function portalMstOutputInsert($data)
    {     
        Log::debug('UserManagerRepositories.portalMstOutputInsert');
        try{
            $result = $this->PortalMstOutputModel->insert(
                [
                    'sight_id'=>$data['sight_id'],
                    'data_id'=>$data['data_id'],
                    'data_kbn'=>$data['data_kbn'],
                    'output_date'=>$data['output_date'],
                    'corp_target'=>$data['corp_target'],
                    'file_name'=>$data['file_name'],
                ]
            );

            return $result;
        }catch(Exception $e){
            Log::info('portalMstOutputInsert :'.date("y/m/d h:m:s").$e->getMessage());
        }
    }

    /**
     * Chaneg sub user password
     * @param integer $subUserData
     * @return result
     */ 
    public function changeAccPwd($subUserData)
    {
        try{
            $hashed = password_hash($subUserData['password'], PASSWORD_BCRYPT);
            $result = $this->UserAccountModel->where('GLID', $subUserData['GLID'])
                                            ->where('account_number', $subUserData['account_number'])
                                            ->update([
                                                'password'=>$hashed,
                                                'update_account_cd'=>$subUserData['update_account_cd']
                                            ]);
        
            return $result; 
        }catch(Exception $e){
            Log::info('function changePasswork| error code : 305 | error messeger : '.$e->getMessage());
            App::abort(500);
        }
    }
}
