<?php

namespace App\Repositories;

use App\Models\BlockModel;
use App\Models\EvenManageModel;
use App\Models\FloorModel;
use App\Models\UserManageModel;
use App\Models\HallModel;
use App\Models\HallSeatModel;
use App\Models\SalesTermModel;
use App\Models\SeatModel;
use App\Models\StagenameModel;
use App\Models\StageSeatModal;
use App\Models\ScheduleModel;
use App\Models\UserAccountModel;
use App\Models\ReserveModel;
use App\Models\SeatClassModel;
use App\Models\SeatMapProfileModel;
use App\Models\NonreservedStockModal;
use App\Models\TicketClassModal;
use App\Models\PriceModal;
use App\Models\PayPickModel;
use App\Models\PortalMstOutputModel;
use App\Models\TicketLayoutModel;
use App\Models\DraftModel;
use App\Models\PerformanceLangModel;
use App\Models\QuestionModel;
use App\Models\QuestionLangModel;
use Exception;
use Log;
use App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvenManageRepositories
{
    /** @var BlockModel */
    protected $BlockModel;
    /** @var EvenManageModel */
    protected $EvenManageModel;
    /** @var FloorModel */
    protected $FloorModel;
    /** @var UserManageModel */
    protected $UserManageModel;
    /** @var HallModel */
    protected $HallModel;
    /** @var HallSeatModel */
    protected $HallSeatModel;
    /** @var SalesTermModel */
    protected $SalesTermModel;
    /** @var SeatModel */
    protected $SeatModel;
    /** @var StagenameModel */
    protected $StagenameModel;
    /** @var StageSeatModal */
    protected $StageSeatModal;
    /** @var ScheduleModel */
    protected $ScheduleModel;
    /** @var UserAccountModel */
    protected $UserAccountModel;
    /** @var ReserveModel */
    protected $ReserveModel;
    /** @var SeatClassModel*/
    protected $SeatClassModel;
    /** @var SeatMapProfileModel*/
    protected $SeatMapProfileModel;
    /** @var NonreservedStockModal*/
    protected $NonreservedStockModal;
    /** @var TicketClassModal*/
    protected $TicketClassModal;
    /** @var PriceModal*/
    protected $PriceModal;
    /** @var PayPickModel*/
    protected $PayPickModel;
    /** @var PortalMstOutputModel*/
    protected $PortalMstOutputModel;
    /** @var TicketLayoutModel*/
    protected $TicketLayoutModel;
    /** @var DraftModel*/
    protected $DraftModel;
    /** @var PerformanceLangModel*/
    protected $PerformanceLangModel;
    /** @var QuestionModel*/
    protected $QuestionModel;
    /** @var QuestionLangModel*/
    protected $QuestionLangModel;
    /**
     * UserManageModel constructor.
     * @param UserManageModel $UserManageModel
     */
    public function __construct(BlockModel $BlockModel, EvenManageModel $EvenManageModel, FloorModel $FloorModel, UserManageModel $UserManageModel, HallModel $HallModel, HallSeatModel $HallSeatModel, SalesTermModel $SalesTermModel, SeatModel $SeatModel, StagenameModel $StagenameModel, StageSeatModal $StageSeatModal, ScheduleModel $ScheduleModel, UserAccountModel $UserAccountModel, ReserveModel $ReserveModel, SeatClassModel $SeatClassModel, SeatMapProfileModel $SeatMapProfileModel, NonreservedStockModal $NonreservedStockModal, TicketClassModal $TicketClassModal, PriceModal $PriceModal, PayPickModel $PayPickModel, PortalMstOutputModel $PortalMstOutputModel, TicketLayoutModel $TicketLayoutModel, DraftModel $DraftModel, PerformanceLangModel $PerformanceLangModel, QuestionModel $QuestionModel, QuestionLangModel $QuestionLangModel)
    {
        $this->BlockModel               = $BlockModel;
        $this->EvenManageModel          = $EvenManageModel;
        $this->FloorModel               = $FloorModel;
        $this->UserManageModel          = $UserManageModel;
        $this->HallModel                = $HallModel;
        $this->HallSeatModel            = $HallSeatModel;
        $this->SalesTermModel           = $SalesTermModel;
        $this->SeatModel                = $SeatModel;
        $this->StagenameModel           = $StagenameModel;
        $this->StageSeatModal           = $StageSeatModal;
        $this->ScheduleModel            = $ScheduleModel;
        $this->UserAccountModel         = $UserAccountModel;
        $this->ReserveModel             = $ReserveModel;
        $this->SeatMapProfileModel      = $SeatMapProfileModel;
        $this->SeatClassModel           = $SeatClassModel;
        $this->NonreservedStockModal    = $NonreservedStockModal;
        $this->PriceModal               = $PriceModal;
        $this->TicketClassModal         = $TicketClassModal;
        $this->PayPickModel             = $PayPickModel;
        $this->PortalMstOutputModel     = $PortalMstOutputModel;
        $this->TicketLayoutModel        = $TicketLayoutModel;
        $this->DraftModel               = $DraftModel;
        $this->PerformanceLangModel     = $PerformanceLangModel;
        $this->QuestionModel            = $QuestionModel;
        $this->QuestionLangModel        = $QuestionLangModel;
    }
    /*
     * 刪除 draft 
     * 
     * @param $performance_id
     * @return bool $draft
     */
    public function deleteDraft($performance_id){
        try {
            $draft =  DraftModel::where('performance_id', $performance_id)
                                ->delete();

            $performance = EvenManageModel::find($performance_id);
            $performance->edit_status = \Config::get('constant.edit_status.not');
            $performance->save();

            return $draft;
        }catch (Exception $e) {
            return false;
        }
    } 
    /**
     * 檢查 performation_code
     * 
     * @param $event_id
     * @return $result
     */
    public function checkEventId($GLID,$event_id){
        try {
            $result =  EvenManageModel::where('performance_code', $event_id)->where('GLID',$GLID)->exists();
            
            return $result;
        }
        catch (Exception $e) {
            return false;
        }
    } 
    /**
     * 取得使用者聯絡資料
     * 
     * @param $user_id
     * @return $result
     */
    public function getContactInf(){
        try {
           return $result = array(
                                'contact_person' => $this->UserManageModel->contact_person,
                                'tel_num'        => $this->UserManageModel->tel_num,
                                'mail_address'   => $this->UserManageModel->mail_address,
                            );
        }
        catch (Exception $e) {
            return false;
        }
    }
    /**
     * 取得使用者資料
     * 
     * @param $user_id
     * @return bool
     */
    public function getUser($user_id){
        try {
            $this->UserManageModel = UserManageModel::findOrFail($user_id);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
    /**
     * 更新 PERFORMANCE LANG
     * 
     * @param $update_data
     * @return $result
     */
    public function updatePerformanceLang($update_data){
        try{
            $update_result = $this->PerformanceLangModel::updateOrCreate(
                                                                            [
                                                                                'lang_id'        => $update_data['lang_id'],
                                                                                'performance_id' => $update_data['performance_id'],
                                                                                'lang_code'      => $update_data['lang_code'],
                                                                                
                                                                            ], 
                                                                            [
                                                                            
                                                                                'lang_info'        => $update_data['lang_info'],
                                                                            ]
                                                                        );
        
            $result = array(
                'lang_id'  => $update_result->lang_id,
            );

            return $result;
        }catch(Exception $e){
            Log::info('updatePerformanceLang error :'.$e->getMessage());
            App::abort(500);
        } 
    }
    /**
     * 更新活動部分資料
     * 
     * @param $update_data
     */
    public function updatePerformanceListData($update_data){
        try{
            // dd($update_data);
            $performance = $this->EvenManageModel::find($update_data['performance_id']);

            $performance->performance_name      = $update_data['performance_name']?:'';
            $performance->performance_name_sub  = $update_data['performance_name_sub'];
            $performance->sch_kbn               = $update_data['sch_kbn'];
            $performance->performance_st_dt     = $update_data['performance_st_dt'];
            $performance->performance_end_dt    = $update_data['performance_end_dt'];
            $performance->disp_start            = $update_data['disp_start']?:'9999-12-31';

            $performance->save();
        }catch(Exception $e){
            Log::info('updatePerformanceListData :'.$e->getMessage());
            App::abort(500);
        } 
    }
    /**
     * 更新活動狀態
     * 
     * @param $update_data
     */
    public function updateEditStatus($update_data){
        try{
            $performance = $this->EvenManageModel::find($update_data['performance_id']);

            // $performance->status      = $update_data['status'];
            $performance->disp_end = $update_data['date_end'];
            $performance->edit_status = $update_data['edit_status'];
            $performance->autotranslation = $update_data['autoTransChecked']?:0;
            $performance->portlanguage = $update_data['portlanguage']?:0;
       
            $performance->save();
        }catch(Exception $e){
            Log::info('updateEditStatus :'.$e->getMessage());
            App::abort(500);
        }   
    }
    /**
     * 取得 Temporary Info
     * 
     * @param $update_data
     * @return $result
     */
    public function getTemporaryInfo($filter_data){
        try{
            $performance = $this->EvenManageModel::find($filter_data['performance_id']);
            
            $result = array(
                'status'         => $performance->status,
                'trans_flg'      => $performance->trans_flg,
                'temporary_info' => $performance->temporary_info,
            );

            return $result;
        }catch(Exception $e){
            Log::info('getDraft :'.$e->getMessage());
            App::abort(404);
        } 
    }
    /**
     * 取得 Draft
     * 
     * @param $update_data
     * @return $result
     */
    public function getDraft($filter_data){
        try{
            $draft = $this->DraftModel::where('performance_id', '=', $filter_data['performance_id'])
                                        ->first();
            if($draft){
                $result = array(
                    'draft_info'  => $draft->draft_info,
                    'new_status'  => $draft->new_status,
                    'updated_at'  => $draft->updated_at,
                );

                return $result;
            }else{
                return false;
            }

        }catch(Exception $e){
            Log::info('getDraft :'.$e->getMessage());
            App::abort(404);
        } 
    }
    /**
     * 更新 performance status
     * 
     * @param $update_data
     */
    public function updatePerformanceStatus($update_data){
        try{
            $performance = $this->EvenManageModel::find($update_data['performance_id']);

            $performance->status = $update_data['performance_status'];
        
            $performance->save();
        }catch(Exception $e){
            Log::info('updatePerformanceStatus :'.$e->getMessage());
            App::abort(500);
        } 
       
    }
    /**
     * 更新 Draft
     * 
     * @param $update_data
     * @return $result
     */
    public function updateDraft($update_data){
        try{
            $this->DraftModel::updateOrCreate(
                                                [
                                                    'performance_id' => $update_data['performance_id'],
                                                ], 
                                                [
                                                    'draft_info'        => $update_data['temporary_info'],
                                                    'update_account_cd' => $update_data['update_account_cd'],
                                                    'new_status'        => $update_data['performance_status'],
                                                ]
                                            );
        }catch(Exception $e){
            Log::info('updateDraft :'.$e->getMessage());
            App::abort(500);
        } 
       
    }
    /**
     * 更新 Temporary Info
     * 
     * @param $update_data
     * @return $result
     */
    public function updateTemporaryInfo($update_data){
        try{
            $performance = $this->EvenManageModel::find($update_data['performance_id']);

            $performance->status = $update_data['performance_status'];
            $performance->temporary_info = $update_data['temporary_info'];
        
            $performance->save();
        }catch(Exception $e){
            Log::info('updateTemporaryInfo :'.$e->getMessage());
            App::abort(500);
        } 
    }
    /**
     * delete event
     * 
     * @param $data
     * @return $result
     */
    public function deleteEvent($data){
        try{

            $result = $this->EvenManageModel->where('performance_id', $data['performation_id']);

            if($data['admin_flg']){
                $result =  $result->where('GLID', $data['GLID']);
            }
                          
            $result = $result->update([
                                        'status'            => $data['performance_status'],
                                        'update_account_cd' => $data['account_cd'],
                                    ]);
        
            return $result;
        }catch(Exception $e){
            Log::info('deleteEvent :'.$e->getMessage());
            App::abort(500);
        } 

        
    }
    /**
     * 
     * insert pay pick data
     */
    public function paynPickUpdateOrCreate($data){
       
        try{
          $result = $this->PayPickModel->updateOrCreate(
                                        [
                                            'term_id' => $data['term_id'],
                                            'pay_method' => $data['pay_method'],
                                            'pickup_method' => $data['pickup_method'],    
                                        ],
                                        [
                                            'treat_flg' => array_key_exists('treat_flg',$data)?$data['treat_flg']:0,
                                            'treat_end_kbn' => array_key_exists('treat_end_kbn',$data)?$data['treat_end_kbn']:'4',
                                            'treat_end_date' => array_key_exists('treat_end_date',$data)?$data['treat_end_date']:null,
                                            'treat_end_days' => array_key_exists('treat_end_days',$data)?$data['treat_end_days']:'0',
                                            'treat_end_time' => array_key_exists('treat_end_time',$data)?$data['treat_end_time']:'23:59:59',
                                            'pay_due_days' => array_key_exists('pay_due_days',$data)?$data['pay_due_days']:'0',
                                            'pickup_st_kbn' => array_key_exists('pickup_st_kbn',$data)?$data['pickup_st_kbn']:2,
                                            'pickup_st_date' => array_key_exists('pickup_st_date',$data)?$data['pickup_st_date']:null,
                                            'pickup_st_days' => array_key_exists('pickup_st_days',$data)?$data['pickup_st_days']:10,
                                            'pickup_st_time' => array_key_exists('pickup_st_time',$data)?$data['pickup_st_time']:'00:00',
                                            'pickup_st_count' => array_key_exists('pickup_st_count',$data)?$data['pickup_st_count']:1,
                                            'pickup_due_kbn' => array_key_exists('pickup_due_kbn',$data)?$data['pickup_due_kbn']:2,
                                            'pickup_due_date' => array_key_exists('pickup_due_date',$data)?$data['pickup_due_date']:null,
                                            'pickup_due_days' => array_key_exists('pickup_due_days',$data)?$data['pickup_due_days']:0,
                                            'pickup_due_time' => array_key_exists('pickup_due_time',$data)?$data['pickup_due_time']:'23:59:59',
                                            'pickup_due_count'=> array_key_exists('pickup_due_count',$data)?$data['pickup_due_count']:0,
                                            'receive_limit' => array_key_exists('receive_limit',$data)?$data['receive_limit']:4,
                                            'update_account_cd' => $data['update_account_cd'],
                                        ]);
            return $result->paynpick_id;
        }catch(Exception $e){
          Log::info('paynPickUpdateOrCreate :'.$e->getMessage());
        //   throw new Exception ('paynPickUpdateOrCreate :'.$e->getMessage());
          throw new Exception ('EMR-EXP-PNPUOC');
        }        
        
    }
    /**
     * 
     * iupdatePayPick
     */
    public function updatePayPick($paypick_id,$data){
       
        try{
             $this->PayPickModel->where('paynpick_id', $paypick_id)
                                ->update(
                                         [
                                          'treat_flg' => $data['treat_flg'],
                                          'treat_end_date' => $data['treat_end_date'],
                                          'receive_limit' => $data['receive_limit'],
                                          'update_account_cd' => $data['update_account_cd'],
                                          'updated_at' =>date("y/m/d H:i:s"),                                                    
                                         ]
                                        );
        
             return;
        }catch(Exception $e){
          Log::info('updatePayPick :'.$e->getMessage());
        //   throw new Exception ('updatePayPick :'.$e->getMessage());
          throw new Exception ('EMR-EXP-UPP');
        }
    }    
    /**
     * get performance list
     * 
     */
    public function getPerformanceList($filterData){
        try{
            $result = $this->EvenManageModel->select(
                                                'GL_USER.user_code',
                                                'GL_PERFORMANCE.performance_id',
                                                'GL_PERFORMANCE.status',
                                                'GL_PERFORMANCE.performance_st_dt',
                                                'GL_PERFORMANCE.performance_end_dt',
                                                'GL_PERFORMANCE.performance_name',
                                                'GL_PERFORMANCE.performance_name_sub',
                                                'GL_PERFORMANCE.performance_st_dt',
                                                'GL_PERFORMANCE.performance_end_dt',
                                                'GL_PERFORMANCE.disp_start',
                                                'GL_PERFORMANCE.trans_flg',   
                                                'GL_PERFORMANCE.hall_disp_name',
                                                'GL_PERFORMANCE.performance_code',
                                                'GL_PERFORMANCE.temporary_info',
                                                'GL_PERFORMANCE.edit_status',
                                                'GL_PERFORMANCE.sale_type'
                                                )
                                            ->join('GL_USER','GL_PERFORMANCE.GLID','=','GL_USER.GLID')
                                            ->orderBy('performance_id', 'desc');

            if($filterData['admin_flg']){
                $result = $result->where('GL_PERFORMANCE.GLID', $filterData['GLID']);
                $result = $result->where('GL_PERFORMANCE.status', '>=', 0);
            }

            $result = $result->get();
            
            $performance_list = $result->load('draft', 'salesTerm')
                                        ->toArray();
            
            return $performance_list;
        }catch(Exception $e){
            Log::info('performance list :'.$e->getMessage());
        }
    }
    /**
     * get performance filter data
     * @param
     * @return 
     */
    public function getPerformanceFilter($filterData){
        try{
            $result = $this->EvenManageModel->select(
                                'GL_USER.user_code',
                                'GL_PERFORMANCE.performance_id',
                                'GL_PERFORMANCE.status',
                                'GL_PERFORMANCE.performance_st_dt',
                                'GL_PERFORMANCE.performance_end_dt',
                                'GL_PERFORMANCE.performance_name',
                                'GL_PERFORMANCE.performance_name_sub',
                                'GL_PERFORMANCE.performance_st_dt',
                                'GL_PERFORMANCE.performance_end_dt',
                                'GL_PERFORMANCE.disp_start',
                                'GL_PERFORMANCE.trans_flg',   
                                'GL_PERFORMANCE.hall_disp_name',
                                'GL_PERFORMANCE.performance_code',
                                'GL_PERFORMANCE.temporary_info',
                                'GL_PERFORMANCE.edit_status',
                                'GL_PERFORMANCE.sale_type'
                            )
                           ->join('GL_USER','GL_PERFORMANCE.GLID','=','GL_USER.GLID')
                           ->orderBy('performance_id', 'desc');

                           if($filterData['admin_flg']){
                                $result = $result->where('GL_PERFORMANCE.GLID', $filterData['GLID']);
                                $result = $result->where('GL_PERFORMANCE.status', '>=', 0)  ;
                            }
                           
                            if(array_key_exists('startdt',$filterData) && $filterData['startdt']){
                                $result = $result->where('GL_PERFORMANCE.performance_st_dt', '>=', $filterData['startdt'])  ;    
                            }
                            
                            if(array_key_exists('enddt',$filterData) &&  $filterData['enddt']){
                                $result = $result->where('GL_PERFORMANCE.performance_end_dt', '<=', $filterData['enddt'])  ;    
                            }

                            if($filterData['keyword']){
                                $keyword = $filterData['keyword'];
                                $result = $result->Where(function($q) use ($keyword){
                                                $q->where('GL_PERFORMANCE.performance_code', 'like', '%'.$keyword.'%')
                                                ->orWhere('GL_PERFORMANCE.performance_name', 'like', '%'.$keyword.'%')
                                                ->orWhere('GL_PERFORMANCE.performance_name_k', 'like', '%'.$keyword.'%')
                                                ->orWhere('GL_PERFORMANCE.performance_name_sub', 'like', '%'.$keyword.'%')
                                                ->orWhere('GL_PERFORMANCE.performance_name_seven', 'like', '%'.$keyword.'%')
                                                ->orWhere('GL_PERFORMANCE.hall_disp_name', 'like', '%'.$keyword.'%');
                                            });
                            }

                            //modified by Defect #819
                            //if($filterData['status_select']){
                            //    $result = $result->whereIn('status', $filterData['status_select']);       
                            //}

                            $result = $result->get();
            
                            $performance_list = $result->load('draft', 'salesTerm')
                                                        ->toArray();
            
            return $performance_list;
        }catch(Exception $e){
            Log::info('get schedule data error :'.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * get sales term date
     * 
     */
    public function getSalesTermDate($performance_id){
        try{
            $result = $this->SalesTermModel->select('GL_SALES_TERM.sales_kbn',
                                                    'GL_SALES_TERM.reserve_st_date',
                                                    'GL_SALES_TERM.reserve_st_time',
                                                    'GL_SALES_TERM.reserve_cl_date',
                                                    'GL_SALES_TERM.reserve_cl_time')
                                           ->where('performance_id', $performance_id)
                                           ->where('treat_flg', '1')
                                           ->orderBy('sales_kbn', 'asc')
                                           ->get() ;
            
            return $result;
        }catch(Exception $e){
            Log::info('getSalesTermDate list :'.$e->getMessage());
        }
    }    /**
     * get sales term date
     * 
     */
    public function getSalesTermStarDate($performance_id){
        try{
            $result = $this->SalesTermModel->where('performance_id', $performance_id)   
                                           ->min('reserve_st_date');
            
            return $result;
        }catch(Exception $e){
            Log::info('performance list :'.$e->getMessage());
        }
    }
    /**
     * get sales term date
     * 
     */
    public function getSalesTermCloseDate($performance_id){
        try{
            $result = $this->SalesTermModel->where('performance_id', $performance_id)   
                                           ->max('reserve_cl_date');
          
            return $result;
        }catch(Exception $e){
            Log::info('performance list :'.$e->getMessage());
        }
    }
    /**
     * 
     * insert pay pick data
     */
    public function deletePayPick($paynpick_id){
        
        $result = $this->PayPickModel->where('paynpick_id', $paynpick_id)
                                     ->delete();

        return $result;
    }
    /**
     * insert Ticket Layout
     * @param $performanceId
     * @return result 
     */ 
    public function insertTicketLayout($ticketViewData)
    {  
        try{
              $result = $this->TicketLayoutModel->insertGetId(   
              [
                'performance_id' => $ticketViewData['performance_id'],
                'schedule_id' => $ticketViewData['schedule_id'],
                'ticket_kbn' => $ticketViewData['ticket_kbn'],
                'thumbnail' => $ticketViewData['thumbnail'],
                'free_word' => $ticketViewData['free_word'],
                'update_account_cd' => $ticketViewData['update_account_cd'],
                'created_at' =>date("y/m/d H:i:s"),
                'updated_at'=>date("y/m/d H:i:s"),
              ]
              );
              return $result;
        }catch(Exception $e){
            Log::info('insertTicketLayout :'.$e->getMessage());
        }        
    }  
    /**
     * update Ticket Layout
     * @param $performanceId
     * @return result 
     */ 
    public function updateTicketLayout($eventInf, $ticketViewData, $layout_id, $type, $thumbnail)
    {  
        $result = $this->TicketLayoutModel->where('layout_id', $ticketViewData->id)
                                          ->update(
                                                [
                                                    'ticket_kbn' => $type,
                                                    'thumbnail' => $thumbnail,
                                                    'free_word' => $ticketViewData->content,
                                                    'update_account_cd' => $eventInf['account_cd'],
                                                ]
                                            );
        return $result;
    }
    /**
     * Create or update  Ticket Layout
     * @param $performanceId
     * @return result 
     */ 
    public function updateOrCreateTicketLayout($layout_id,$ticketViewData)
    {  
        
        try{
            if(!empty($layout_id)) {
                $result = $this->TicketLayoutModel->updateOrCreate(
                    [
                        'layout_id' => $layout_id,
                    ],
                    [
                        'performance_id' => $ticketViewData['performance_id'],
                        'schedule_id' => $ticketViewData['schedule_id'],
                        'ticket_kbn' => $ticketViewData['ticket_kbn'],
                        'thumbnail' => $ticketViewData['thumbnail'],
                        'free_word' => $ticketViewData['free_word'],
                        'update_account_cd' => $ticketViewData['update_account_cd'],
                ]);
            }
            else  {
                $result = $this->TicketLayoutModel->updateOrCreate(
                    [
                        'performance_id' => $ticketViewData['performance_id'],
                        'schedule_id' => $ticketViewData['schedule_id'],
                        'ticket_kbn' => $ticketViewData['ticket_kbn'],
                    ],
                    [
                        'thumbnail' => $ticketViewData['thumbnail'],
                        'free_word' => $ticketViewData['free_word'],
                        'update_account_cd' => $ticketViewData['update_account_cd'],
                ]);
            }
            return $result;
        }catch(Exception $e){
            Log::info('insertTicketLayout :'.$e->getMessage());
            throw new Exception ('EMR-EXP-UOCTL');
        }        
    }  

    /**
     * insert Ticket Layout
     * @param $performanceId
     * @return result 
     */ 
    public function insertSpecTicketLayout($eventInf, $ticketViewData, $scheduleId, $type, $thumbnail)
    {  
       
        $result = $this->TicketLayoutModel->insertGetId(   
            [
                'performance_id' => $eventInf['performance_id'],
                'schedule_id' => $scheduleId,
                'ticket_kbn' => $type,
                'thumbnail' => $thumbnail,
                'free_word' => $ticketViewData,
                'update_account_cd' => $eventInf['account_cd'],
                'created_at' => date("y/m/d"),
            ]
        );
        
        return $result;

    }  
    /**
     * update Ticket Layout
     * @param $performanceId
     * @return result 
     */ 
    public function updateSpecTicketLayout($eventInf, $ticketViewData, $layout_id, $type, $thumbnail)
    {  
       
        $result = $this->TicketLayoutModel->where('layout_id', $layout_id)
                                          ->update(
                                                [
                                                    'ticket_kbn' => $type,
                                                    'thumbnail' => $thumbnail,
                                                    'free_word' => $ticketViewData,
                                                    'update_account_cd' => $eventInf['account_cd'],
                                                ]
                                            );
        return $result;

    }  
    /**
     * delete Ticket Layout
     * @param $performanceId
     * @return result 
     */ 
    public function deleteTicketLayout($ticketViewData)
    {  
        try {
            $result = $this->TicketLayoutModel
                            ->where('layout_id', $ticketViewData->id)
                            ->delete();

            return $result;
        } catch(Exception $e){
            Log::info('deleteTicketLayout :'.$e->getMessage());
        }
    } 
    /**
     * delete Ticket Layout by layout id 
     * @param $performanceId
     * @return result 
     */ 
    public function deleteTicketLayoutbyID($layout_id)
    {  
        try {
            $result = $this->TicketLayoutModel
                            ->where('layout_id', $layout_id)
                            ->delete();
            
            return $result;

        }catch(Exception $e){
            Log::info('S_EXC_MSN_0002 :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0002').'(EMR-EXP-DTLBID)');
        }        
    } 

    /**
     * delete distinct Ticket Layout by performance id
     * @param $performanceId
     * @return result 
     */ 
    public function deleteDistinctTicketLayoutbyPID($performance_id)
    {  
        try {
            $result = $this->TicketLayoutModel
                            ->where('performance_id', $performance_id)
                            ->whereNotNull('schedule_id')
                            ->delete();
            
            return $result;

        }catch(Exception $e){
            Log::error('deleteDistinctTicketLayoutbyPID :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DDTLBP');
        }        
    } 

    /**
     * delete distinct Ticket Layout by schedule id
     * @param $schedule_id
     * @return result 
     */ 
    public function deleteDistinctTicketLayoutbySchedule($schedule_id)
    {  
        try {
            $result = $this->TicketLayoutModel
                            ->where('schedule_id', $schedule_id)
                            ->delete();
            
            return $result;

        }catch(Exception $e){
            Log::error('deleteDistinctTicketLayoutbySchedule :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0002').'(EMR-EXP-DDTLBS)');
        }        
    } 

    /**
     * get seat id
     * @param $performanceId
     * @return result 
     */ 
    public function seatId($performanceId)
    {  
        try{
            $result = $this->SeatClassModel::select('seat_class_id')
                                            ->where('performance_id', $performanceId)
                                            ->get();
                                            
           return $result;

        }catch(Exception $e){
            Log::error('seatId :'.$e->getMessage());
        }
        
    }
     /**
     * get seat id
     * @param array $seatId
     * @return result 
     */ 
    public function ticketId($seatId)
    {  
        try{
            $result = $this->TicketClassModal::select('ticket_class_id')
                                              ->where('seat_class_id', $seatId)
                                              ->get();
                                            
           return $result;

        }catch(Exception $e){
            Log::info('ticketId :'.$e->getMessage());
        }
        
    }
     /**
     * insert seatClassInsert data
     * @param array $stageName
     * @return result 
     */ 
    public function seatClassUpdateOrCreate($type, $nextSeat, $performanceId, $userCd, $data)
    {   
        try{
            if (isset($data->sdbid) && !empty($data->sdbid))
            {
                $result =  $this->SeatClassModel->updateOrCreate(   
                    [
                        'seat_class_id' => $data->sdbid,
                    ],
                    [
                        'performance_id' => $performanceId,
                        'seat_class_name' => $data->seatName,
                        //'seat_class_name_short' => $data->seatName,
                        'seat_class_kbn' => $type,
                        'next_seat_flg' => $nextSeat,
                        'gate' => '',
                        'disp_order' => empty($data->seatid)?0:$data->seatid,
                        'update_account_cd' => $userCd,
                        'seat_class_color' => (isset($data->seatColor)) ? $data->seatColor : '#FFFFFF',
                    ]
                );    
            }
            else {
                $result =  $this->SeatClassModel->updateOrCreate(   
                    [
                        'performance_id' => $performanceId,
                        'seat_class_name' => $data->seatName,
                    ],
                    [
                        //'seat_class_name_short' => $data->seatName,
                        'seat_class_kbn' => $type,
                        'next_seat_flg' => $nextSeat,
                        'gate' => '',
                        'disp_order' => empty($data->seatid)?0:$data->seatid,
                        'update_account_cd' => $userCd,
                        'seat_class_color' => (isset($data->seatColor)) ? $data->seatColor : '#FFFFFF',
                    ]
                );
            }
            
          return $result->seat_class_id;

        }catch(Exception $e){
           Log::info('seatClassInsert :'.$e->getMessage());
           throw new Exception ('EMR-EXP-SCUOC');
        }
    }  
     /**
     * update saetclass data
     * @param array $stageName
     * @return result 
     */ 
    public function seatClassUpdate($data, $userCd)
    {  
        try{
            $result = $this->SeatClassModel->where('seat_class_id', $data->sdbid)
                                           ->update(
                                                [
                                                    'seat_class_name' => $data->seatName,
                                                    //'seat_class_name_short' => $data->seatName,
                                                    'update_account_cd' => $userCd,
                                                ]
                                            );
                                            
           return $data->sdbid;

        }catch(Exception $e){
            Log::info('seatClassUpdate :'.$e->getMessage());
            // throw new Exception ('seatClassUpdate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SCU');
        }
    }  
    /**
     * delete saet data 刪除
     * @param array $stageName
     * @return result 
     */ 
    public function seatClassDelete($seatId)
    {  
        try{
            $result = $this->SeatClassModel->where('seat_class_id', $seatId)
                                             ->delete();
           return $result;

        }catch(Exception $e){
            Log::info('seatClassDelete :'.$e->getMessage());
            // throw new Exception ('seatClassDelete :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SCD');
        }
    }  
    /**
     * delete all seat data by PID
     * @param array $PID
     * @return result 
     */ 
    public function deleteSeatClassDatabyPID($PID)
    {  
        try{
            $seatClass_list = $this->SeatClassModel
                                    ->where('performance_id', $PID)
                                    ->pluck('seat_class_id');

            $ticketClass_list = $this->TicketClassModal
                                        ->wherein('seat_class_id', $seatClass_list)
                                        ->pluck('ticket_class_id');

            $this->PriceModal->wherein('ticket_class_id', $ticketClass_list)
                                        ->delete();

            $this->TicketClassModal->wherein('seat_class_id', $seatClass_list)
                                        ->delete();

            $this->SeatClassModel->wherein('seat_class_id', $seatClass_list)
                                        ->delete();

        }catch(Exception $e){
            Log::info('deleteSeatClassDatabyPID :'.$e->getMessage());
            // throw new Exception ('deleteSeatClassDatabyPID :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSCDBP');
        }
    }  

    /**
     * GET saet data
     * @param array $stageName
     * @return result 
     */ 
    public function getSeatData($performanceId, $data)
    {  
        try{
            $result = $this->SeatClassModel->where('performance_id', $performanceId)
                                           ->where('seat_class_id', $data->seatId)
                                           ->get();
                                            
           return $result;

        }catch(Exception $e){
            Log::info('getSeatData :'.$e->getMessage());
        }
    }  
     /**
     * getCountSeatClassReserved
     * @param  $stageName
     * @return result 
     */ 
    public function getCountSeatClassReserved($performanceId)
    {  
        try{
            $result = $this->SeatClassModel->where('performance_id', $performanceId)
                                           ->where('seat_class_kbn', \Config::get('constant.seat_class_kbn.reserved'))
                                           ->count();
                                            
           return $result;

        }catch(Exception $e){
            Log::info('getCountSeatClassReserved :'.$e->getMessage());
        }
    }     
    /**
     * insert ticket data
     * @param array $stageName
     * @return result 
     */ 
    public function ticketInsert($data, $salesKbn)
    {  
       try{
            $result =  $this->TicketClassModal->updateOrCreate(   
                [
                    'seat_class_id' => $data->seat_class_id,
                    'ticket_sales_kbn' => $salesKbn,
                    'ticket_class_name' => $data->ticket_class_name,
                ],
                [
                    //'ticket_class_name_short' => $data->ticket_class_name,
                    'disp_order' => $data->disp_order,                    
                    'update_account_cd' => $data->update_account_cd,
                ]
            );
           return $result->ticket_class_id;

        }catch(Exception $e){
            Log::info('stageNameInsert :'.$e->getMessage());
            // throw new Exception ('stageNameInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-TI');
            
        }
    }  
    /**
     * insert ticket data
     * @param array $stageName
     * @return result 
     */ 
    public function ticketUpdate($id, $data, $ticket_sales_kbn)
    {  
       try{
           
            $result = $this->TicketClassModal->where('ticket_class_id', $id)
                                             ->update(
                                                        [
                                                        'ticket_class_name' => $data->ticket_class_name,
                                                        //'ticket_class_name_short' => $data->ticket_class_name,
                                                        'ticket_sales_kbn' => $ticket_sales_kbn,
                                                        'disp_order' => $data->disp_order, 
                                                        'update_account_cd' => $data->update_account_cd,
                                                        'updated_at' => date("y/m/d H:i:s"),
                                                        ]
                                                    );
           return;

        }catch(Exception $e){
            Log::info('ticketUpdate :'.$e->getMessage());
            // throw new Exception ('ticketUpdate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-TU');
        }
    }  
    /**
     * ticketDelete
     * @param array $stageName
     * @return result 
     */ 
    public function ticketChangeDelFlg($ticket_class_id)
    {  
       try{
            $ticket_class = TicketClassModal::findOrFail($ticket_class_id);
            $ticket_class->ticket_sales_kbn = -1 * abs($ticket_class->ticket_sales_kbn);
            $ticket_class->save();
            
            return true;
        }catch(Exception $e){
            Log::error('S_EXC_MSN_0004 :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0004').'(EMR-EXP-TCDF)');
        }
    }  
    /**
     * ticketDelete
     * @param array $stageName
     * @return result 
     */ 
    public function ticketDelete($ticketClassId)
    {  
        try{
            $result = $this->TicketClassModal->where('ticket_class_id', $ticketClassId)
                                             ->delete();

            return $result;

        }catch(Exception $e){
            Log::info('ticketDelete :'.$e->getMessage());
            // throw new Exception ('ticketDelete :'.$e->getMessage());
            throw new Exception ('EMR-EXP-TD');
        }
    }  
    /**
     * insert or update preice data
     * @param 
     * @return PriceModal
     */ 
    public function ticketPriceupdateOrCreate($ticketId, $price, $update_account_cd)
    {  
        try{
            $this->PriceModal = PriceModal::updateOrCreate(
                [
                    'ticket_class_id' => $ticketId,
                    'member_kbn' => 0,
                    'treat_kbn' => 3,
                ],
                [
                    'price' => $price,
                    'update_account_cd' => $update_account_cd,
                ]
            );

           if(!$this->PriceModal){
            //    throw new Exception ('ticketPriceupdateOrCreate');
               throw new Exception ('EMR-EXP-TPUOC');
            }

           return $this->PriceModal;

        }catch(Exception $e){
            Log::error('ticketPriceupdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-TPUOC');
        }
    }  
    /**
     * ticketPriceDelete
     * @param array $stageName
     * @return result 
     */ 
    public function ticketPriceDelete($data)
    {  
        try{
           
            $this->PriceModal->where('ticket_class_id', $data)
                             ->delete();
           return;

        }catch(Exception $e){
            Log::info('ticketPriceDelete :'.$e->getMessage());
            // throw new Exception ('ticketPriceDelete :'.$e->getMessage());
            throw new Exception ('EMR-EXP-TPD');
        }
    }  
    /**
     * ticketPriceDelete
     * @param array $stageName
     * @return result 
     */ 
    // public function ticketPriceDeletebyPID($PID)
    // {  
    //     try{
           
    //         $this->PriceModal->where('ticket_class_id', $data)
    //                          ->delete();
    //        return;

    //     }catch(Exception $e){
    //         Log::info('ticketPriceDelete :'.$e->getMessage());
    //         throw new Exception ('ticketPriceDelete :'.$e->getMessage());
    //     }
    // }  


    /**
     * insert preice data
     * @param array $stageName
     * @return result 
     */ 
    public function nonReservervedUpdateOrCreate($nonreserve)
    {   
        try{
            $result = $this->NonreservedStockModal->updateOrCreate(
                [
                    'schedule_id'      => $nonreserve['schedule_id'],   //スケジュールid
                    'seat_class_id'    => $nonreserve['seat_class_id'], //席種id   
                ],
                [                 
                 'stock_limit'      => $nonreserve['stock_limit'],   //在庫上限
                 'current_num'      => $nonreserve['current_num'],   //整理券採番
                 'update_account_cd'=> $nonreserve['update_account_cd'], //更新担当者コード
               ]
            );
           return $result->stock_id;

        }catch(Exception $e){
            Log::info('nonReservervedUpdateOrCreate :'.$e->getMessage());
            // throw new Exception ('nonReservervedUpdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-NRUOC');
        }
    }  

    /**
     * delete non reserved stock by performance 
     * @param performance_id
     * @return  
     */ 
    public function deleteNonreservedStockByPID($performance_id)
    {  
        try{
            $SIDs = $this->getScheduleData($performance_id);
            
            foreach($SIDs as $schedule){
                $this->NonreservedStockModal->where('schedule_id', $schedule->schedule_id)
                    ->delete();
            }
            return;
        }catch(Exception $e){
            Log::info('deleteNonreservedStockBySeatClassId :'.$e->getMessage());
            // throw new Exception ('deleteNonreservedStockBySeatClassId :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DNRSBP');
        }
    }      


    /**
     * delete non reserved stocl  刪除
     * @param array $stageName
     * @return result 
     */ 
    public function deleteNonreservedStockBySeatClassId($seatClassId)
    {  
        try{
            $this->NonreservedStockModal->where('seat_class_id', $seatClassId)
                                        ->delete();
            return;
        }catch(Exception $e){
            Log::info('deleteNonreservedStockBySeatClassId :'.$e->getMessage());
            // throw new Exception ('deleteNonreservedStockBySeatClassId :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DNRSBSCID');
        }
    }  
    
    /**
     * delete non reserved stock by schedule 
     * @param schedule_id
     * @return  
     */ 
    public function deleteNonreservedStockBySchedule($schedule_id)
    {  
        try{
            $this->NonreservedStockModal->where('schedule_id', $schedule_id)
                ->delete();
            return;
        }catch(Exception $e){
            Log::info('deleteNonreservedStockBySchedule :'.$e->getMessage());
            // throw new Exception ('deleteNonreservedStockBySchedule :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DNRSBS');
        }
    }

    /**
     * update non reserved stoclk
     * @param array $stageName
     * @return result 
     */ 
    public function updateNonreservedStockBySeatClassId($seatClassId,$stock_limit,$account_cd)
    {  
        try{
            $this->NonreservedStockModal->where('seat_class_id', $seatClassId)
                                        ->update(
                                                  [
                                                   'stock_limit' => $stock_limit,
                                                   'update_account_cd' => $account_cd,
                                                   'updated_at'=>date("y/m/d H:i:s"),
                                                   ]
                                                 );
            return;
        }catch(Exception $e){
            Log::error('updateNonreservedStockBySeatClassId :'.$e->getMessage());
            // throw new Exception ('updateNonreservedStockBySeatClassId :'.$e->getMessage());
            throw new Exception ('EMR-EXP-UNRSBSCID');
        }
    }
    public function getNonreservedBySchedulenSeatClass($scheduleId, $seatClassId) {
        try{
            $ret = NonreservedStockModal::where('seat_class_id', $seatClassId)
                                                                    ->where('schedule_id',$scheduleId)
                                                                    ->first();

            return $ret;
        }catch(Exception $e){
            Log::error('getNonreservedBySchedulenSeatClass :'.$e->getMessage());
            // throw new Exception ('getNonreservedBySchedulenSeatClass :'.$e->getMessage());
            throw new Exception ('EMR-EXP-GNRBSSC');
        }
    }
    /**
     * get Schedule data
     * @param array $performance id
     * @return result
     * 
     */
    public function getScheduleData($performanceId){

        try{
            $result = $this->ScheduleModel->where('performance_id', $performanceId)
                                            ->get();
            
           return $result;

        }catch(Exception $e){
            Log::info('getScheduleData :'.$e->getMessage());
        }
    }
    /**
     * insert preice data
     * @param array $stageName
     * @return result 
     */ 
    public function nonReservervedUpdate($schedule_id, $seatId,  $account_cd, $seatQty)
    { 
        try{
         
            $result = $this->NonreservedStockModal::updateOrCreate(
                [
                    'schedule_id' => $schedule_id,
                    'seat_class_id' => $seatId, 
                ],
                [
                    'stock_limit'=> $seatQty,
                    'update_account_cd'=> $account_cd,
                ]
            );
                        
           if(!$result){
            //    throw new Exception ('nonReservervedUpdate');
               throw new Exception ('EMR-EXP-NRU');
            }

           return $result;

        }catch(Exception $e){
            Log::info('nonReservervedUpdate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-NRU');
        }
    }  
     /**
     * Update Or Create reserve data
     * @param array 
     * @return result 
     */ 
    public function reserveUpdateOrCreate($performanceId, $userCd, $data)
    {   
        try{
            if(isset($data->ticketCode) && !empty($data->ticketCode)) {
                $result =  $this->ReserveModel->updateOrCreate(   
                    [
                        'reserve_code' => $data->ticketCode,
                    ],
                    [
                        'performance_id' => $performanceId,
                        'reserve_symbol' => $data->ticketText,
                        'reserve_name' => $data->ticketName,
                        'reserve_color' => '#FFFFFF',
                        'reserve_word_color' =>  $data->ticketColor,
                        'sys_reserve_flg' => '0',
                        'update_account_cd' => $userCd,
                    ]
                );    
            }
            else {
                $result =  $this->ReserveModel->updateOrCreate(   
                    [
                        'performance_id' => $performanceId,
                        'reserve_symbol' => $data->ticketText,
                    ],
                    [
                        'reserve_name' => $data->ticketName,
                        'reserve_color' => '#FFFFFF',
                        'reserve_word_color' =>  $data->ticketColor,
                        'sys_reserve_flg' => '0',
                        'update_account_cd' => $userCd,
                    ]
                );    
            }

           if(!$result){
            //    throw new Exception ('reserveUpdateOrCreate');
               throw new Exception ('EMR-EXP-RUOC');
            }
         
            return $result->reserve_code;

        }catch(Exception $e){
            Log::info('reserveUpdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-RUOC');
        }
    }  

    /**
     * delete reserve data
     * @param array $stageName
     * @return result 
     */ 
    public function reserveDelete($reserveCode)
    {   
        try{
      
            $this->ReserveModel->where('reserve_code',  $reserveCode)
                               ->delete();
            return;

        }catch(Exception $e){
            Log::info('reserveDelete :'.$e->getMessage());
            // throw new Exception ('reserveDelete :'.$e->getMessage());
            throw new Exception ('EMR-EXP-RD');
        }
    }  
    /**
     * Delete satagename
     * @param string $Id
     * @return result 
     */ 
    public function stageNameDelete($Id)
    {   
        try{
            // to-do : [James] Before delete stage, need to delete schedule
            $result = $this->StagenameModel->where('performance_id', $Id)
                                          ->delete();

        }catch(Exception $e){
            Log::info('stageNameDelete :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SND');
        }
    }
    /**
     * insert time rule data
     * @param array $stageName
     * @return result 
     */ 
    public function stageNameInsert($performanceId, $stageName)
    {   
        try{
            $result = $this->StagenameModel->insertGetId(
                [
                    'performance_id' => $stageName->performance_id,
                    'stage_num' => $stageName->stage_num,
                    'stage_name' => $stageName->stage_name,
                    'stage_disp_flg' => $stageName->status,
                    'description' => $stageName->description,
                    'update_account_cd' => $stageName->account_cd,
                    'created_at' =>date("y/m/d H:i:s"),
                    'updated_at'=>date("y/m/d H:i:s"),
                ],'stcd'
            );
            
            if(!$result){
                // throw new Exception ('stageNameInsert');
                throw new Exception ('EMR-EXP-SNI');
            }
            return $result;
        }catch(Exception $e){
            Log::info('stageNameInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SNI');
        }
    }
      /**
     * update stagename data by performanceid
     * @param array $stageName
     * @return result 
     */ 
    public function stageNameUpdateByPerformanceId($performanceId, $stageName)
    {   
        try{
            $this->StagenameModel->where('performance_id', $performanceId)
                                 ->update(
                                          [
                                           'performance_id' => $stageName->performance_id,
                                           'stage_num' => $stageName->stage_num,
                                           'stage_name' => $stageName->stage_name,
                                           'stage_disp_flg' => $stageName->status,
                                           'description' => $stageName->description,
                                           'update_account_cd' => $stageName->account_cd,
                                           'created_at' =>date("y/m/d H:i:s"),
                                           'updated_at'=>date("y/m/d H:i:s"),
                                          ]);
            
        $result = $this->StagenameModel->where('performance_id', $performanceId)
                                       ->get();
       
        return $result[0]['stcd'];

            if(!$result){
                // throw new Exception ('stageNameUpdateByPerformanceId');
                throw new Exception ('EMR-EXP-SNUBPID');
            }
            return $result;
        }catch(Exception $e){
            Log::info('stageNameUpdateByPerformanceId :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SNUBPID');
        }
    }
      /**
     * update or create stagename data by performanceid and stage_num
     * @param array $stageName
     * @return result 
     */ 
    public function stageNameUpdateOrCreate($stcd, $stageName)
    {   
        
        try{
            if($stcd) {
                $stage_data = $this->StagenameModel->updateOrCreate(
                    [
                        'stcd' => $stcd,
                    ],
                    [
                        'performance_id' => $stageName['performance_id'],
                        'stage_num' => $stageName['stage_num'],
                        'stage_name' => $stageName['stage_name'],
                        'stage_disp_flg' => $stageName['status'],
                        'description' => $stageName['description'],
                        'update_account_cd' => $stageName['account_cd'],
                ]);
            }
            else {
                $stage_data = $this->StagenameModel->updateOrCreate(
                    [
                        'performance_id' => $stageName['performance_id'],
                        'stage_num' => $stageName['stage_num'],
                    ],
                    [
                        'stage_name' => $stageName['stage_name'],
                        'stage_disp_flg' => $stageName['status'],
                        'description' => $stageName['description'],
                        'update_account_cd' => $stageName['account_cd'],
                ]);
            }
            return $stage_data->stcd;
        }catch(Exception $e){
            Log::info('stageNameUpdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SNUOC');
        }
        
    }
    /**
     * get stagename data count
     * @param $performanceId
     * @return result 
     */ 
    public function stagenameDataCount($performanceId)
    {    
        $result = $this->StagenameModel->where('performance_id', $performanceId)
                                       ->count();
       
        return $result;
    }
     /**
     * get stagename data
     * @param array $data
     * @return result 
     */ 
    public function stagenameData($performanceId, $stage_num)
    {    
        $result = $this->StagenameModel->where('performance_id', $performanceId)
                                       ->where('stage_num', $stage_num)
                                       ->get();
       
        return $result[0]['stcd'];
    }
    /**
     * updateStageSeatBySeatClass
     * @param string $Id
     * @return result 
     */ 
    public function updateStageSeatBySeatClass($SeatClassId)
    {   
        try{
     
            $this->StageSeatModal->where('seat_class_id', $SeatClassId)
                                 ->whereNull('reserve_code')
                                 ->delete();

            $this->StageSeatModal->where('seat_class_id', $SeatClassId)
                                 ->whereNotNull('reserve_code')
                                 ->update(['seat_class_id' => null]);

        }catch(Exception $e){
            Log::info('updateStageSeatBySeatClass :'.$e->getMessage());
            // throw new Exception ('updateStageSeatBySeatClass :'.$e->getMessage());
            throw new Exception ('EMR-EXP-ESSBSC');
        }
    }    

    /**
     * deleteStageSeatByPID
     * @param string $performance_id
     * @return result 
     */ 
    public function deleteStageSeatByPID($PID)
    {   
        try{
            $schedule_arr = $this->ScheduleModel ->where('performance_id', $PID)
                                                 ->pluck('schedule_id');

            $this->StageSeatModal->wherein('schedule_id', $schedule_arr)
                                 ->delete();
        }catch(Exception $e){
            Log::info('deleteStageSeatByPID :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSSBP');
        }
    }    

    /**
     * deleteReserveByPID
     * @param string $performance_id
     * @return result 
     */ 
    public function deleteReserveByPID($PID)
    {   
        try{
            $this->ReserveModel->where('performance_id', $PID)
                                ->delete();
        }catch(Exception $e){
            Log::info('deleteReserveByPID :'.$e->getMessage());
            //throw new Exception ('deleteReserveByPID :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DRBP');
        }
    }    

    /**
     * deleteStageSeatByReserveCode
     * @param string $Id
     * @return result 
     */ 
    public function deleteStageSeatByReserveCode($ReserveCode)
    {   
        try{
     
            $this->StageSeatModal->where('reserve_code', $ReserveCode)
                                 ->delete();

        }catch(Exception $e){
            Log::info('deleteStageSeatByReserveCode :'.$e->getMessage());
            // throw new Exception ('deleteStageSeatByReserveCode :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSSBRC');
        }
    }   
    /**
     * insert or update schedule
     * @param array $data
     * @return result 
     */ 
    public function dateScheduleInsert($data)
    {   
        try{
            $glData = $this->EvenManageModel::where('performance_id', $data['performanceId'])->get();

            if($glData->isEmpty()){
                // throw new Exception ('GLID fail');
                throw new Exception ('EMR-EXP-DSI-01');
            }

            $result = $this->ScheduleModel->insertGetId( 
                [
                    'performance_id'=>$data['performanceId'],
                    'performance_date'=>$data['performance_date'],
                    'performance_flg'=>1,
                    'open_date'=>$data['open_date'],
                    'start_time'=>$data['start_time'],
                    'disp_performance_date'=>$data['disp_performance_date'],
                    'sch_kbn'=>$data['sch_kbn'],
                    'stcd'=>$data['stcd'],
                    'cancel_flg'=>0,
                    'update_account_cd'=>$data['accountCd'],
                ]
            );

           if(!$result){
            //    throw new Exception ('stageNameInsert');
               throw new Exception ('EMR-EXP-DSI-02');
            }
            
            return $result;

        }catch(Exception $e){
            Log::info('dateScheduleInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSI');
        }
    }  
    /**
     * insert or update schedule
     * @param array $data
     * @return result 
     */ 
    public function dateScheduleUpdate($dbId, $data)
    {   
        try{
            $result = $this->ScheduleModel->where('schedule_id', $dbId)
                                          ->update(
                                                [
                                                    'open_date'=>$data['open_date'],
                                                    'start_time'=>$data['start_time'],
                                                    'disp_performance_date'=>$data['disp_performance_date'],
                                                    'sch_kbn'=>$data['sch_kbn'],
                                                    'stcd'=>$data['stcd'],
                                                    'cancel_flg'=>0,
                                                    'update_account_cd'=>$data['accountCd'],
                                                ]
                                            );

           if(!$result){
            //    throw new Exception ('stageNameInsert');
               throw new Exception ('EMR-EXP-DSU-01');
            }
            
            return $result;

        }catch(Exception $e){
            Log::info('dateScheduleUpdate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSU');
        }
    }  
    /**
     * Delete schedule
     * @param array $data
     * @return result 
     */ 
    public function dateScheduleDelete($dbId)
    {   
        try{ 
            $result = $this->ScheduleModel->where('schedule_id', $dbId)
                                          ->delete();

        }catch(Exception $e){
            Log::info('dateScheduleDelete :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0001').'(EMR-EXP-DSD)');
        }
    } 
    /**
     * Delete schedule
     * @param array $data
     * @return result 
     */ 
    public function dateScheduleDeleteByPerformanceId($performanceId)
    {   
        try{
            $this->ScheduleModel->where('performance_id', $performanceId)
                                ->delete();

        }catch(Exception $e){
            Log::info('dateScheduleDeleteByPerformanceId :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSDBPID');
        }
    } 
   /**
     * update schedule (spec date)
     * @param array $data
     * @return result 
     */ 
    public function specDateScheduleInsert($data)
    {   
        try{  
            $result = $this->ScheduleModel->insertGetId( 
                        [
                            'performance_id'=>$data['performance_id'],
                            'performance_date'=>$data['performance_date'],
                            'start_time'=>$data['start_time'],
                            'disp_performance_date'=>$data['disp_performance_date'],
                            'sch_kbn'=>$data['sch_kbn'],
                            'stcd'=>$data['stcd'],
                            'update_account_cd'=>$data['update_account_cd'],
                            'created_at' =>date("y/m/d H:i:s"),
                            'updated_at'=>date("y/m/d H:i:s"),
                        ]
                    );
            
            if(!$result){
                // throw new Exception ('specDateScheduleInsert');
                throw new Exception ('EMR-EXP-SDSI-01');
            }
            return $result;

        }catch(Exception $e){
          Log::info('specDateScheduleInsert :'.$e->getMessage());
          throw new Exception ('EMR-EXP-SDSI');
        }
    }
    /**
     * update schedule (spec date)
     * @param array $data
     * @return result 
     */ 
    public function specDateScheduleUpdate($specDateId, $data)
    {   
        try{  
          
            $glData =  $this->EvenManageModel::where('performance_id', $data['performanceId'])->get();

            if($glData->isEmpty()){
                // throw new Exception ('GLID fail');
                throw new Exception ('EMR-EXP-SDSU-01');
            }

            $userData =  $this->UserAccountModel->where('GLID', $glData[0]['GLID'])
                                                ->where('account_number', $data['accountNum'])
                                                ->get();
          
            $result = $this->ScheduleModel->where('schedule_id', $specDateId)
                                          ->update(
                                              [
                                                'open_date' => $data['open_date'],
                                                'start_time' => $data['start_time'],
                                                'update_account_cd' => $userData[0]['account_cd'],
                                                'updated_at' => $data['update_date'],
                                                'stcd' => $data['stcd'],
                                                'performance_datedisp' => $data['performance_datedisp'],
                                                'sch_kbn' => $data['sch_kbn'],
                                              ]
                                            );

           if(!$result){
            //    throw new Exception ('stageNameInsert');
               throw new Exception ('EMR-EXP-SDSU-02');
            }
          
            return $result;

        }catch(Exception $e){
          Log::info('specDateScheduleUpdate :'.$e->getMessage());
          throw new Exception ('EMR-EXP-SDSU');
        }
    }
    /**
     * Create or update schedule (spec date)
     * @param array $data
     * @return result 
     */ 
    public function specDateScheduleCreateorUpdate($specDateId, $data)
    {
        Log::debug('specDateScheduleCreateorUpdate'.json_encode($data));
        try{  
            Log::debug('specDateScheduleCreateorUpdate:'.$data['performance_id'].'-'.$data['performance_date'].'-'.$data['stcd'].'-'.$data['start_time']);
            if($specDateId && !empty($specDateId) ){
                $result = $this->ScheduleModel->updateOrCreate(
                    [
                        'schedule_id' => $specDateId,
                    ],
                    [
                        'performance_id' => $data['performance_id'],
                        'stcd' => $data['stcd'],
                        'performance_date'=>$data['performance_date'],
                        'start_time'=>$data['start_time'],
                        'disp_performance_date'=>$data['disp_performance_date'],
                        'sch_kbn'=>$data['sch_kbn'],
                        'update_account_cd'=>$data['update_account_cd'],
                    ]
                  );
            }
            else {
                $result = $this->ScheduleModel->updateOrCreate(
                    [
                        'performance_id' => $data['performance_id'],
                        'performance_date'=>$data['performance_date'],
                        'stcd' => $data['stcd'],
                    ],
                    [
                        'start_time'=>$data['start_time'],
                        'disp_performance_date'=>$data['disp_performance_date'],
                        'sch_kbn'=>$data['sch_kbn'],
                        'update_account_cd'=>$data['update_account_cd'],
                    ]
                  );            

            }
            Log::debug('specDateScheduleCreateorUpdate: id ='.$result->schedule_id);
            return $result;

        }catch(Exception $e){
          Log::info('[Exception] specDateScheduleCreateorUpdate:'.$e->getMessage());
          throw new Exception ('EMR-EXP-SDSCOU');
        }
    }
    /**
     * insert performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function performanceInsert($eventInf, $basisInf)
    {  
        Log::debug('EvenManageRepositories.performanceInsert()');
        try{
            $result = $this->EvenManageModel->updateOrCreate(
                [
                    'GLID'              => $eventInf['GLID'],
                    'performance_code'  => $basisInf['eventId'],
                ],
                [
                    'status'=>$basisInf['status'],
                    'paid_status'=>$basisInf['paid_status'],
                    'performance_name'=>$basisInf['performance_name'],
                    'performance_name_k'=>'',
                    'performance_name_sub'=>$basisInf['performance_name_sub'],
                    'performance_name_seven'=>'',
                    'sch_kbn'=>$basisInf['sch_kbn'],
                    'performance_st_dt'=>$basisInf['performance_st_dt'],
                    'performance_end_dt'=>$basisInf['performance_end_dt'],
                    'hall_code'=>$basisInf['hall_code'],
                    'hall_disp_name'=>$basisInf['hall_disp_name'],
                    'official_url'=>$basisInf['eventUrl'],
                    'disp_start'=>$basisInf['disp_start'],
                    'information_nm'=>$basisInf['information_nm'],
                    'information_tel'=>$basisInf['information_tel'],
                    'mail_address'=>$basisInf['mail_address'],
                    'genre_code'=>$basisInf['genre_code'],
                    'top_conten_type'=>$basisInf['top_conten_type'],
                    'top_conten_url'=>$basisInf['top_conten_url'],
                    'top_content_comment'=>$basisInf['top_content_comment'],
                    'thumbnail'=>$basisInf['thumbnail'],
                    'context'=>$basisInf['context'],
                    'article'=>$basisInf['article'],
                    'keywords'=>$basisInf['keywords'],
                    'selection_flg'=>$basisInf['selection_flg'],
                    'purchasable_number'=>$basisInf['purchasable_number'],
                    'sale_type'=>$basisInf['sale_type'],
                    'trans_flg'=>$basisInf['trans_flg'],
                    'temporary_info'=>$basisInf['temporary_info'],
                    'insert_account_cd'=>$eventInf['account_cd'],
                    'update_account_cd'=>$eventInf['account_cd'],
                    'edit_status'=>$basisInf['edit_status'],
                    'autotranslation'=>$basisInf['autoTransChecked']?:0,
                    'portlanguage'=>$basisInf['portlanguage']?:0,
                    'sell_anyone'=>$basisInf['sell_anyone'],
                ]);
        return $result->performance_id;

        }catch(Exception $e){
          \Log::debug('*** insert failed ***'); 
          \Log::debug($e->getMessage()); 
        //   throw new Exception ('performanceInsert:'.$e->getMessage());
          throw new Exception ('EMR-EXP-PI');
        
        }
    } 
    /**
     * get performance json
     * @param array $basisInf
     * @return result 
     */ 
    public function jsonGet($performance_id)
    {
        try{
           
            $result = $this->EvenManageModel::select('temporary_info','article')
                                            ->where('performance_id', $performance_id)
                                            ->get();
          
            return $result;
       }catch(Exception $e){
           Log::info('table performance insert data :'.$e->getMessage());
           throw new Exception ('EMR-EXP-JG');
       }
    }

    /**
     * getPerfromanceInfoForCsv
     * @param  $performance_id
     * @return result 
     */ 
    public function getPerfromanceInfoForCsv($performance_id)
    {
        try{
           
            $result = $this->EvenManageModel::select('GL_USER.user_code',
                                                     'GL_USER.user_id',
                                                     'GL_USER.user_status',
                                                     'GL_PERFORMANCE.performance_code',
                                                     'GL_PERFORMANCE.status',
                                                     'GL_PERFORMANCE.performance_name',
                                                     'GL_PERFORMANCE.performance_name_sub',
                                                     'GL_PERFORMANCE.sch_kbn',
                                                     'GL_PERFORMANCE.performance_st_dt',
                                                     'GL_PERFORMANCE.performance_end_dt',
                                                     'GL_PERFORMANCE.hall_code',
                                                     'GL_PERFORMANCE.hall_disp_name',
                                                     'GL_PERFORMANCE.disp_start',
                                                     'GL_PERFORMANCE.disp_end',
                                                     'GL_PERFORMANCE.genre_code',
                                                     'GL_HALL.prefecture',
                                                     'GL_HALL.home_page',
                                                     'GL_HALL.post_code',
                                                     'GL_PERFORMANCE.official_url',                   
                                                     'GL_PERFORMANCE.information_nm',
                                                     'GL_PERFORMANCE.information_tel',
                                                     'GL_PERFORMANCE.thumbnail',
                                                     'GL_PERFORMANCE.context',
                                                     'GL_PERFORMANCE.article',
                                                     'GL_PERFORMANCE.keywords',
                                                     'GL_PERFORMANCE.top_conten_type',
                                                     'GL_PERFORMANCE.top_conten_url',
                                                     'GL_PERFORMANCE.top_content_comment',
                                                     'GL_PERFORMANCE.context',
                                                     'GL_PERFORMANCE.selection_flg',
                                                     'GL_PERFORMANCE.trans_flg',
                                                     'GL_PERFORMANCE.sale_type',
                                                     'GL_PERFORMANCE.updated_at',
                                                     'GL_PERFORMANCE.autotranslation',
                                                     'GL_PERFORMANCE.portlanguage'
                                                     )
                                            ->join('GL_USER','GL_PERFORMANCE.GLID','=','GL_USER.GLID')
                                            ->join('GL_HALL','GL_PERFORMANCE.hall_code','=','GL_HALL.hall_code')
                                            ->where('performance_id', $performance_id)
                                            ->first();
         
            return $result;
       }catch(Exception $e){
           Log::info('getPerfromanceInfoForCsv :'.$e->getMessage());
           throw new Exception ('EMR-EXP-GPIFC');
       }
    }
    
    /**
     * getPerfromanceInfoForCsvEng
     * @param  $performance_id
     * @return result 
     */ 
    public function getPerfromanceInfoForCsvEng($performance_id)
    {
        try{
           
            $result = $this->PerformanceLangModel::select('lang_info')
                                                  ->where('performance_id', $performance_id)
                                                  ->orderBy('performance_id', 'desc')
                                                  ->first();
         
            return $result;
       }catch(Exception $e){
           Log::info('getPerfromanceInfoForCsvEng :'.$e->getMessage());
           throw new Exception ('EMR-EXP-GPIFCE');
       }        
    }   
        
    /**
     * getPerfromanceInfoByPerformanceid
     * @param array $performance_id
     * @return result 
     */ 
    public function getSalesTermInfoForCsv($performance_id)
    {
        try{
           
            $result = $this->SalesTermModel::select(  'GL_SALES_TERM.sales_kbn'
                                                     ,'GL_SALES_TERM.reserve_st_date'
                                                     ,'GL_SALES_TERM.reserve_cl_date'
                                                     ,'GL_PAY_PICK.pay_method'
                                                     ,'GL_PAY_PICK.pickup_method'
                                                     )
                                            ->join('GL_PAY_PICK','GL_SALES_TERM.term_id','=','GL_PAY_PICK.term_id')
                                            ->where('GL_SALES_TERM.performance_id', $performance_id)
                                            ->where('GL_SALES_TERM.treat_flg', '1')
                                            ->whereIn('GL_PAY_PICK.pay_method', array(PayPickModel::PAY_METHOD_CARD, PayPickModel::PAY_METHOD_STORE)) //pay_method-現金以外
                                            ->whereIn('GL_PAY_PICK.pickup_method', array(PayPickModel::PICKUP_METHOD_STORE, PayPickModel::PICKUP_METHOD_ETICOKET, PayPickModel::PICKUP_METHOD_RESUQ, PayPickModel::PICKUP_METHOD_NO_TICKETING)) //pickup_method-窓口以外
                                            ->where('GL_PAY_PICK.treat_flg', '1')
                                            ->get();
          
            return $result;
       }catch(Exception $e){
           Log::info('getSalesTermInfoForCsv :'.$e->getMessage());
       }
    }    
    /**
     * getScheduleInfoForCsv
     * @param array $performance_id
     * @return result 
     */ 
    public function getScheduleInfoForCsv($performance_id)
    {
        try{
           
            $result = $this->ScheduleModel::select(  'GL_SCHEDULE.performance_date'
                                                    ,'GL_STAGENAME.stage_num'
                                                    ,'GL_SCHEDULE.performance_flg'
                                                    ,'GL_SCHEDULE.open_date'
                                                    ,'GL_SCHEDULE.start_time'
                                                    ,'GL_SCHEDULE.cancel_flg'
                                                    ,'GL_SCHEDULE.refund_st_date'
                                                    ,'GL_SCHEDULE.refund_end_date'
                                                    ,'GL_SCHEDULE.cancel_messgae'
                                                    ,'GL_STAGENAME.description'
                                                    ,'GL_SCHEDULE.disp_performance_date'
                                                    ,'GL_STAGENAME.stage_name'
                                                    ,'GL_STAGENAME.stage_disp_flg'
                                                     )
                                            ->join('GL_STAGENAME','GL_SCHEDULE.stcd','=','GL_STAGENAME.stcd')
                                            ->where('GL_SCHEDULE.performance_id', $performance_id)
                                            ->get();
          
            return $result;
       }catch(Exception $e){
           Log::info('getScheduleInfoForCsv :'.$e->getMessage());
       }
    }
     /**
     * getRyokinForCsv
     * @param array $performance_id
     * @return result 
     */ 
    public function getRyokinForCsv($performance_id)
    {
        try{
           
            $result = $this->ScheduleModel::select('GL_SCHEDULE.performance_date'
                                                  ,'GL_STAGENAME.stage_num'
                                                  ,'GL_SEAT_CLASS.seat_class_id'
                                                  ,'GL_SEAT_CLASS.seat_class_kbn'
                                                  ,'GL_TICKET_CLASS.ticket_class_id'
                                                  ,'GL_SEAT_CLASS.seat_class_name'
                                                  ,'GL_PRICE.price'
                                                  ,'GL_TICKET_CLASS.ticket_sales_kbn'
                                                  ,'GL_SEAT_CLASS.disp_order'
                                                  ,'GL_SALES_TERM.reserve_st_date'
                                                  ,'GL_SALES_TERM.reserve_st_time'
                                                  ,'GL_SALES_TERM.reserve_cl_date'
                                                  ,'GL_SALES_TERM.reserve_cl_time'
                                                  )
                                            ->join('GL_STAGENAME','GL_SCHEDULE.stcd','=','GL_STAGENAME.stcd')
                                            ->join('GL_SEAT_CLASS','GL_SCHEDULE.performance_id','=','GL_SEAT_CLASS.performance_id')
                                            ->join('GL_TICKET_CLASS','GL_SEAT_CLASS.seat_class_id','=','GL_TICKET_CLASS.seat_class_id')
                                            ->join('GL_PRICE','GL_TICKET_CLASS.ticket_class_id','=','GL_PRICE.ticket_class_id')
                                            ->join('GL_SALES_TERM', function ($join) {
                                                    $join->on('GL_SCHEDULE.performance_id', '=', 'GL_SALES_TERM.performance_id')
                                                         ->on('GL_TICKET_CLASS.ticket_sales_kbn', '=', 'GL_SALES_TERM.sales_kbn');
                                              })
                                            ->where('GL_SCHEDULE.performance_id', $performance_id)
                                            // ->where('GL_TICKET_CLASS.ticket_sales_kbn','<>', \Config::get('constant.ticket_sales_kbn.onsite')) //販売区分：当日以外
                                            ->where('GL_SALES_TERM.treat_flg', '1')
                                            ->get();
          
            return $result;
       }catch(Exception $e){
           Log::info('getRyokinForCsv :'.$e->getMessage());
       }
    }   /**
     * get location json
     * @param array $basisInf
     * @return result 
     */ 
    public function hallGet($GLID)
    {  
        try{
            $result = $this->HallModel->whereIn('Owner_cd', array(0, $GLID))
                                        ->orWhere('public','1')
                                       ->get();
           
            return ($result);
       }catch(Exception $e){
           Log::info('table performance insert data :'.$e->getMessage());
       }
    }
    /**
     * get basis setting
     * @param array $eventInf
     * @return result 
     */ 
    public function basisSettingGet($eventInf)
    {  
        try{
            $glCode =  $this->UserManageModel::select('GLID')->where('user_code', $eventInf['companyId'])->get();
            
            if($glCode->isEmpty()){
                // throw new Exception ('GLID fail');
                throw new Exception ('EMR-EXP-BSG-01');
            }
           
            $performance = $this->EvenManageModel->where('GLID', $glCode[0]['GLID'])
                                            ->where('performance_code', $eventInf['performance_code'])
                                            ->get();

            if(!$performance){
                // throw new Exception ('select error');
                throw new Exception ('EMR-EXP-BSG-02');
            }           
          
            $result = array(
                'performance' => $performance,
                'hall' => '',
                'salesTerm' => '',
            );

            return $result;
                                                         
        }catch(Exception $e){
            Log::info('basisSettingGet :'.$e->getMessage());
            throw new Exception ('EMR-EXP-BSG');
        }
    }   
    /**
     * update performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function performanceJsonUpdate($basisInf)
    { 
        //try{

            $result = $this->EvenManageModel->where('GLID', $basisInf['GLID'])
                                            ->where('performance_id', $basisInf['performance_id'])
                                            ->update(
                                                    [
                                                        'temporary_info'=>$basisInf['temporary_info'],
                                                        'update_account_cd'=>$basisInf['account_cd'],
                                                    ]
                                                );
           // if(!$result){
          //      throw new Exception ('update error');
         //   }           
                                                         
       // }catch(Exception $e){
        //    Log::info('table performance insert data :'.$e->getMessage());
       // }
    }  
    /**
     * get performance  data
     * @param array $eventInf
     * @return result 
     */ 
    public function performanceGet($eventInf)
    {  
        try{
            $glCode =  $this->UserManageModel::select('GLID')->where('user_code', $eventInf['companyId'])->get();
            
            if($glCode->isEmpty()){
                throw new Exception ('GLID fail');
            }
           
            $result = $this->EvenManageModel->where('GLID',$glCode[0]['GLID'])
                                            ->where('performance_code', $eventInf['performance_code'])
                                            ->get();

            if(!$result){
                throw new Exception ('select error');
            }           

            return $result;
                                                         
        }catch(Exception $e){
            Log::info('performation seeting get data :'.$e->getMessage());
        }
    }  
    /**
     * update performance data
     * @param $performanceId,array $basisInf
     * @return result 
     */ 
    public function performanceUpdate($performanceId,$basisInf)
    { 
       try{
            $result = $this->EvenManageModel->where('performance_id', $performanceId)
                                            ->update(
                                              [
                                                'status'=>$basisInf['status'],
                                                'performance_name'=>$basisInf['performance_name'],
                                                'performance_name_k'=>'',
                                                'performance_name_sub'=>$basisInf['performance_name_sub'],
                                                'performance_name_seven'=>'',
                                                'sch_kbn'=>$basisInf['sch_kbn'],
                                                'performance_st_dt'=>$basisInf['performance_st_dt'],
                                                'performance_end_dt'=>$basisInf['performance_end_dt'],
                                                'hall_code'=>$basisInf['hall_code'],
                                                'hall_disp_name'=>$basisInf['hall_disp_name'],
                                                'seatmap_profile_cd'=>$basisInf['seatmap_profile_cd'],
                                                'official_url'=>$basisInf['eventUrl'],
                                                'disp_start'=>$basisInf['disp_start'],
                                                'information_nm'=>$basisInf['information_nm'],
                                                'information_tel'=>$basisInf['information_tel'],
                                                'mail_address'=>$basisInf['mail_address'],
                                                'genre_code'=>$basisInf['genre_code'],
                                                'top_conten_type'=>$basisInf['top_conten_type'],
                                                'top_conten_url'=>$basisInf['top_conten_url'],
                                                'top_content_comment'=>$basisInf['top_content_comment'],
                                                'thumbnail'=>$basisInf['thumbnail'],
                                                'context'=>$basisInf['context'],
                                                'article'=>$basisInf['article'],
                                                'keywords'=>$basisInf['keywords'],
                                                'selection_flg'=>$basisInf['selection_flg'],
                                                'purchasable_number'=>$basisInf['purchasable_number'],
                                                'temporary_info'=>$basisInf['temporary_info'],
                                                'update_account_cd'=>$basisInf['account_cd'],
                                                'edit_status'=>$basisInf['edit_status'],   
                                                'sale_type' => $basisInf['sale_type'],
                                                'sell_anyone' => $basisInf['sell_anyone'],
                                              ]
                                            );
                  
            if(!$result){
                throw new Exception ('performanceUpdate error');
            }           
                                                         
        }catch(Exception $e){
            Log::info('table performance update data :'.$e->getMessage());
            throw new Exception ('EMR-EXP-PU');
        }
    }  
     /**
     * UpdateGETTIIStransFlg
     * @param $performanceId,array $basisInf
     * @return result 
     */ 
    public function UpdateGETTIIStransFlg($performanceId,$basisInf)
    { 
        try{
           
            $result = $this->EvenManageModel->where('performance_id', $performanceId)
                                            ->update(
                                              [
                                                'trans_flg'=>$basisInf['trans_flg'],
                                                'announce_date' => date("y/m/d H:i:s"),
                                                'update_account_cd'=>$basisInf['account_cd']?$basisInf['account_cd']:1,
                                                'updated_at' => date("y/m/d H:i:s"),
                                              ]
                                            );
            if(!$result){
                throw new Exception ('performanceUpdate error');
            }           
                                                         
        }catch(Exception $e){
            Log::info('UpdateGETTIIStransFlg :'.$e->getMessage());
            throw new Exception ('EMR-EXP-UGSTF');
        }
    }  
    /**
     * update performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function selectionSetting($performanceId, $selection)
    { 
        try{
            $result = $this->EvenManageModel->where('performance_id', $performanceId)
                                            ->update(
                                                    [
                                                        'selection_flg'=>$selection,
                                                        'updated_at'=>date("y/m/d"),
                                                    ]
                                                );
            if(!$result){
                throw new Exception ('update error');
            }           
                                                         
        }catch(Exception $e){
            Log::info('table performance insert data :'.$e->getMessage());
        }
    }  
    /**
     * update hall data
     * @param array $hallData
     * @return result 
     */ 
    public function hallUpadte($hall_data)
    {
        try{
            $hall = HallModel::find($hall_data['hall_id']);

            $hall->hall_name = $hall_data['hall_name'];
            $hall->post_code = $hall_data['post_code'];
            $hall->prefecture = $hall_data['prefecture'];
            $hall->home_page = $hall_data['home_page'];
            $hall->description = $hall_data['description'];
            $hall->update_account_cd = $hall_data['update_account_cd'];
            $hall->save();
           
            return true;
        }catch(Exception $e){
            Log::info('hallUpadte :'.$e->getMessage());
            throw new Exception ('EMR-EXP-HU');
            return false;
        }
    }
    /**
     * insert hall data
     * @param array $hallData
     * @return result 
     */ 
    public function hallInsert($hallData)
    {
        try{
            
            $hall_code = $this->HallModel->max('hall_code');
            if(is_null($hall_code))
            {
              //採番3000000～
              $result = $this->HallModel->insertGetId(
                [
                    'hall_code'=>HallModel::HALL_CD_INITIAL,
                    'hall_name'=>$hallData['hall_name'],
                    'hall_name_kana'=>$hallData['hall_name_kana'],
                    'post_code'=>$hallData['post_code'],
                    'prefecture'=>$hallData['prefecture'],
                    'home_page'=>$hallData['home_page'],
                    'description'=>$hallData['description'],
                    'Owner_cd'=>$hallData['Owner_cd'],
                    'update_account_cd'=>$hallData['update_account_cd'],
                    'created_at' => date("y/m/d H:i:s"),
                    'updated_at' => date("y/m/d H:i:s"),
                ]
              );
            }else{
               $result = $this->HallModel->insertGetId(
                [
                    'hall_name'=>$hallData['hall_name'],
                    'hall_name_kana'=>$hallData['hall_name_kana'],
                    'post_code'=>$hallData['post_code'],
                    'prefecture'=>$hallData['prefecture'],
                    'home_page'=>$hallData['home_page'],
                    'description'=>$hallData['description'],
                    'Owner_cd'=>$hallData['Owner_cd'],
                    'update_account_cd'=>$hallData['update_account_cd'],
                    'created_at' => date("y/m/d H:i:s"),
                    'updated_at' => date("y/m/d H:i:s"),
                ]
              );
           }            
            return $result;
       }catch(Exception $e){
            Log::info('hallInsert :'.$e->getMessage());
            // throw new Exception ('hallInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-HI');
       }
    }
    /**
     * insert seat map profile data
     * @param array $hallData
     * @return result 
     */ 
    public function seatMapProfileInsert($seatMapData)
    {
        try{
            $result = $this->SeatMapProfileModel->insertGetId(
                [
                    'hall_code'=>$seatMapData['hall_code'],
                    'performance_id'=>$seatMapData['performance_id'],
                    'floor_ctrl'=>$seatMapData['floor_ctrl'],
                    'block_ctrl'=>$seatMapData['block_ctrl'],
                    'gate_ctrl'=>$seatMapData['gate_ctrl'],
                    'description'=>$seatMapData['description'],
                    'Owner_cd'=>$seatMapData['Owner_cd'],
                    'public'=>$seatMapData['public'],
                    'version'=>$seatMapData['version'],
                    'update_account_cd'=>$seatMapData['update_account_cd'],
                    'created_at' => date("y/m/d H:i:s"),
                    'updated_at' => date("y/m/d H:i:s"),             
                ]
            );
            
            return $result;
       }catch(Exception $e){
            Log::info('seatMapProfileInsert :'.$e->getMessage());
            // throw new Exception ('seatMapProfileInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SMPI');
       }
    }
     /**
     * insert floor data
     * @param array $hallData
     * @return result 
     */ 
    public function floorInsert($floorData)
    {
        try{
            $result = $this->FloorModel->insertGetId(
                [
                    'profile_id'=>$floorData['profile_id'],
                    'floor_name'=>$floorData['floor_name'],
                    'sequence'=>$floorData['sequence'],
                    'image_file_name'=>$floorData['image_file_name'],
                    'update_account_cd'=>$floorData['update_account_cd'],
                    'created_at' => date("y/m/d H:i:s"),
                    'updated_at' => date("y/m/d H:i:s"),             
                ]
            );
            
            return $result;
       }catch(Exception $e){
            Log::info('floorInsert :'.$e->getMessage());
            // throw new Exception ('floorInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-FI');
       }
    }
     /**
     * insert block data
     * @param array $hallData
     * @return result 
     */ 
    public function blockInsert($blockData)
    {
        try{
            $result = $this->BlockModel->insertGetId(
                [
                    'profile_id'=>$blockData['profile_id'],
                    'block_name_short'=>$blockData['block_name_short'],
                    'block_name'=>$blockData['block_name'],
                    'app_block'=>$blockData['app_block'],                                       
                    'app_coordinate'=>$blockData['app_coordinate'],          
                    'net_coordinate'=>$blockData['net_coordinate'],
                    'image_file_name'=>$blockData['image_file_name'],
                    'seat_direction'=>$blockData['seat_direction'],
                    'external_image'=>$blockData['external_image'],
                    'update_account_cd'=>$blockData['update_account_cd'],
                    'created_at' => date("y/m/d H:i:s"),
                    'updated_at' => date("y/m/d H:i:s"),             
                ]
            );
            
            return $result;
       }catch(Exception $e){
            Log::info('blockInsert :'.$e->getMessage());
            //throw new Exception ('blockInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-BI');
       }
    }
     /**
     * insert hallSeatInsert data
     * @param array $hallSeatData
     * @return result 
     */ 
    public function hallSeatInsert($hallSeatData)
    {
        try{
            $prio_seat = $hallSeatData['prio_seat'];
            
            if(!$prio_seat || empty($prio_seat))
            {
                $prio_seat = $hallSeatData['seat_seq'];
            }
            $result = $this->HallSeatModel->insertGetId(
                [
                 'profile_id'=>$hallSeatData['profile_id'],     //profile id
                 'floor_id'=>$hallSeatData['floor_id'],         //フロアid
                 'block_id'=>$hallSeatData['block_id'],         //ブロックid
                 'seat_seq'=>$hallSeatData['seat_seq'],         //座席連番
                 'x_coordinate'=>$hallSeatData['x_coordinate'], //座標Ｘ
                 'y_coordinate'=>$hallSeatData['y_coordinate'], //座標Ｙ
                 'x_position'=>$hallSeatData['x_position'],     //座標Ｘ
                 'y_position'=>$hallSeatData['y_position'],     //座標Ｙ
                 'seat_angle'=>$hallSeatData['seat_angle'],     //角度
                 'seat_cols'=>$hallSeatData['seat_cols'],       //列
                 'seat_number'=>$hallSeatData['seat_number'],   //番号
                 'gate'=>$hallSeatData['gate'],                 //ゲート
                 'prio_floor'=>$hallSeatData['prio_floor'],     //優先順位階
                 'prio_seat'=>$prio_seat,       //優先順位座席
                 'update_account_cd'=>$hallSeatData['update_account_cd'], //更新担当者コード
                 'created_at' => \Carbon\Carbon::now(),
                 'updated_at' => \Carbon\Carbon::now(),
                ]
            );
            return $result;
       }catch(Exception $e){
            Log::info('hallSeatInsert :'.$e->getMessage());
            // throw new Exception ('hallSeatInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-HI    ');
       }
    }
    /**
     * Creat or update hallSeat data
     * @param array $hallSeatData
     * @return result 
     */ 
    public function hallSeatCreatorUpdate($hallSeatData)
    {
        try{
            $prio_seat = $hallSeatData['prio_seat'];
            
            if(!$prio_seat || empty($prio_seat))
            {
                $prio_seat = $hallSeatData['seat_seq'];
            }
            $result = $this->HallSeatModel->updateOrCreate(
                [
                 'profile_id'=>$hallSeatData['profile_id'],     //profile id
                 'floor_id'=>$hallSeatData['floor_id'],         //フロアid
                 'block_id'=>$hallSeatData['block_id'],         //ブロックid
                 'seat_cols'=>$hallSeatData['seat_cols'],       //列
                 'seat_number'=>$hallSeatData['seat_number'],   //番号
                ],
                [
                 'seat_seq'=>$hallSeatData['seat_seq'],         //座席連番
                 'x_coordinate'=>$hallSeatData['x_coordinate'], //座標Ｘ
                 'y_coordinate'=>$hallSeatData['y_coordinate'], //座標Ｙ
                 'x_position'=>$hallSeatData['x_position'],     //座標Ｘ
                 'y_position'=>$hallSeatData['y_position'],     //座標Ｙ
                 'seat_angle'=>$hallSeatData['seat_angle'],     //角度
                 'gate'=>$hallSeatData['gate'],                 //ゲート
                 'prio_floor'=>$hallSeatData['prio_floor'],     //優先順位階
                 'prio_seat'=>$prio_seat,       //優先順位座席
                 'update_account_cd'=>$hallSeatData['update_account_cd'], //更新担当者コード
                 'created_at' => \Carbon\Carbon::now(),
                 'updated_at' => \Carbon\Carbon::now(),
                ]
            );
            return $result->seat_id;
       }catch(Exception $e){
            Log::info('hallSeatCreatorUpdate :'.$e->getMessage());
            // throw new Exception ('hallSeatCreatorUpdate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-HSCU');
       }
    }
    /**
    * insert seatInsert data
    * @param array $seatData
    * @return result 
    */ 
    public function seatInsert($seatData)
    {
        try{
            $result = $this->SeatModel->insertGetId(
                [
                 'performance_id'=>$seatData['performance_id'], //公演ID
                 'seat_id'=>$seatData['seat_id'],               //会場座席id
                 'seat_class_id'=>$seatData['seat_class_id'],   //席種id
                 'reserve_code'=>$seatData['reserve_code'],     //押えコード
                 'update_account_cd'=>$seatData['update_account_cd'], //更新担当者コード
                 'created_at' => date("y/m/d H:i:s"),
                 'updated_at' => date("y/m/d H:i:s"),      
                ]
            );
            return $result;
       }catch(Exception $e){
            Log::info('seatInsert :'.$e->getMessage());
            throw new Exception ('seatInsert :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SI');
       }
    }  
    
    /**
    * insert seatUpdate data
    * @param array $seatData
    * @return result 
    */ 
    public function seatUpdateOrCreate($seatData)
    {
        try{
            if(isset($seatData['alloc_seat_id']) && $seatData['alloc_seat_id'] != 0)
            {
                $data = array(
                    'performance_id' => $seatData['performance_id'],
                    'seat_id' => $seatData['seat_id'],
                    'seat_class_id' => $seatData['seat_class_id'],
                    'reserve_code' => $seatData['reserve_code'],
                    'update_account_cd' => $seatData['update_account_cd']
                );

                // if(!$seatData['seat_class_id']) {
                //     unset($data['seat_class_id']);
                // }
                    
                // if(!$seatData['reserve_code']) {
                //     unset($data['reserve_code']);
                // }
                $ret = $this->SeatModel->updateOrCreate(
                                            [
                                                'alloc_seat_id' => $seatData['alloc_seat_id']
                                            ],
                                            $data
                                        );
            }
            else {
                $data = [
                        'reserve_code'=>$seatData['reserve_code'],
                        'seat_class_id'=> $seatData['seat_class_id']?$seatData['seat_class_id']:null,
                        'reserve_code'=>$seatData['reserve_code']?$seatData['reserve_code']:null,
                        'update_account_cd'=>$seatData['update_account_cd'],   
                    ];
                
                // if(!$seatData['seat_class_id']) {
                //     unset($data['seat_class_id']);
                // }

                // if(!$seatData['reserve_code']) {
                //     unset($data['reserve_code']);
                // }
                $ret = $this->SeatModel->updateOrCreate(
                                            [
                                                'performance_id' => $seatData['performance_id'],
                                                'seat_id' => $seatData['seat_id'],
                                            ],$data
                                        );
            }
            return $ret;
       }catch(Exception $e){
            Log::info('seatUpdate :'.$e->getMessage());
            // throw new Exception ('seatUpdate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SUOC');
       }
    }
    
    /**
    * update or creat GL_STAGE_SEAT data
    * @param array $stageSeatInfo
    * @return result 
    */ 
    public function stageSeatUpdateOrCreate($stageSeatInfo)
    {
        try{
            if(isset($stageSeatInfo['stage_seat_id']) && $stageSeatInfo['stage_seat_id'] != 0)
            {
                $data = array(
                    'alloc_seat_id'   =>    $stageSeatInfo['alloc_seat_id'],
                    'schedule_id'     =>    $stageSeatInfo['schedule_id'],
                    'seat_class_id'   =>    $stageSeatInfo['seat_class_id'],
                    'reserve_code'    =>    $stageSeatInfo['reserve_code'],
                    'update_account_cd' =>  $stageSeatInfo['update_account_cd'],
                );

                $ret = $this->StageSeatModal->updateOrCreate(
                                            [
                                                'stage_seat_id' => $stageSeatInfo['stage_seat_id']
                                            ],
                                            $data
                                        );
            }
            else {
                $data = [
                        'seat_class_id'   =>    $stageSeatInfo['seat_class_id'],
                        'reserve_code'    =>    $stageSeatInfo['reserve_code'],
                        'update_account_cd' =>  $stageSeatInfo['update_account_cd'],
                    ];
                
                $ret = $this->StageSeatModal->updateOrCreate(
                                            [
                                                'alloc_seat_id'   =>    $stageSeatInfo['alloc_seat_id'],
                                                'schedule_id'     =>    $stageSeatInfo['schedule_id'],
                                            ],$data
                                        );
            }
            return $ret;
       }catch(Exception $e){
            Log::error('stageSeatUpdateOrCreate :'.$e->getMessage());
            // throw new Exception ('stageSeatUpdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-SSUOC');
       }
    }

    /**
    * Clear GL_STAGE_SEAT data by schedule
    * @param int schedule_id
    * @return result 
    */ 
    public function delStageSeatBySchedule($schedule_id) {
        try{ 
            $result = $this->StageSeatModal->where('schedule_id', $schedule_id)
                                          ->delete();

        }catch(Exception $e){
            Log::info('delStageSeatBySchedule :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0001').'(EMR-EXP-DSSBS)');
        }
    }


    /**
    * dleteSeatBySeatClass
    * @param array $seatClassId
    * @return result 
    */ 
    public function deleteSeatBySeatClass($seatClassId)
    {
        //james 07/31 : 應該是update
        try{
            $this->SeatModel->where('seat_class_id', $seatClassId)
                            ->update(
                                        [
                                            'seat_class_id'=> null,
                                        ]
                                    );
            return;
       }catch(Exception $e){
            Log::info('deleteSeatBySeatClass :'.$e->getMessage());
            // throw new Exception ('deleteSeatBySeatClass :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSBSC');
       }
    }    
    /**
    * deleteSeatByPID
    * @param array $seatClassId
    * @return result 
    */ 
    public function deleteSeatByPID($PID)
    {
        try{
            $this->SeatModel->where('performance_id', $PID)
                            ->delete();
            return;
       }catch(Exception $e){
            Log::info('deleteSeatByPID :'.$e->getMessage());
            // throw new Exception ('deleteSeatByPID :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSBP');
       }
    }    

    /**
    * deleteSeatByReserveCode
    * @param array $seatClassId
    * @return result 
    */ 
    public function deleteSeatByReserveCode($ReserveCode)
    {
        //[TODO] james 07/31 : 應該是update
        try{
            $this->SeatModel->where('reserve_code', $ReserveCode)
                             ->update(
                                        [
                                            'reserve_code'=> null,
                                        ]
                                    );
            return;
       }catch(Exception $e){
            Log::info('deleteSeatByReserveCode :'.$e->getMessage());
            // throw new Exception ('deleteSeatByReserveCode :'.$e->getMessage());
            throw new Exception ('EMR-EXP-DSBRC');
       }
    }        
    /**
     * get performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function salesTermGet($performance_id)
    {
        try{
            $result = $this->SalesTermModel->where('performance_id', $performance_id)
                                           ->count();
           
            return $result;
        }catch(Exception $e){
            Log::info('table performance insert data :'.$e->getMessage());
        }
    }
    /**
     * get performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function earlyDateGet($performance_id)
    {
        try{
            $result = $this->SalesTermModel->where('performance_id', $performance_id)
                                           ->where('sales_kbn', '0')
                                           ->get();
           
            return $result;
        }catch(Exception $e){
            Log::info('table performance insert data :'.$e->getMessage());
        }
    }
    /**
     * get performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function normalDateGet($performance_id)
    {
        try{
            $result = $this->SalesTermModel->where('performance_id', $performance_id)
                                           ->where('sales_kbn', '1')
                                           ->get();
           
            return $result;
        }catch(Exception $e){
            Log::info('table performance insert data :'.$e->getMessage());
        }
    }

    /**
     * delete early date
     * @param array $basisInf
     * @return result 
     */ 
    public function dateDelete($earlyBirdId)
    {     
        try{
            $result = $this->SalesTermModel->where('term_id', $earlyBirdId)
                                           ->delete();

            return $result;
        }catch(Exception $e){
            Log::info('even early delete :'.date("y/m/d hh:mm:ss").$e->getMessage());
        }
    }

    /**
     * update or create sales trems
     * @param array $basisInf
     * @return result 
     */ 
    public function salesTremUpdateOrCreate($data)
    {     
        try{
            $ret = $this->SalesTermModel->updateOrCreate(
                                    [
                                        'performance_id' => $data['performance_id'],
                                        'member_kbn' => 0,
                                        'treat_kbn' => $data['treat_kbn'],
                                        'sales_kbn' => $data['sales_kbn']
                                    ],
                                    [
                                        'treat_flg'=>$data['treat_flg'],
                                        'reserve_st_kbn' => 1,
                                        'reserve_st_date'=>(empty($data['reserve_st_date'])?null:$data['reserve_st_date']),
                                        // 'reserve_st_time'=>$data['reserve_st_time'],
                                        'reserve_st_time'=>empty($data['reserve_st_time'])?false : $data['reserve_st_time'],
                                        'reserve_cl_kbn' => 1,
                                        'reserve_cl_date'=>(empty($data['reserve_cl_date'])?null:$data['reserve_cl_date']),
                                        // 'reserve_cl_time'=>$data['reserve_cl_time'],
                                        'reserve_cl_time'=>empty($data['reserve_cl_time'])?false : $data['reserve_cl_time'],
                                        'reserve_period'=>$data['reserve_period'],
                                        'sales_kbn_nm'=>$data['sales_kbn_nm'],
                                        'update_account_cd'=>$data['account_cd'],
                                    ]
                                    );
            return $ret;
        }catch(Exception $e){
            Log::info('salesTremUpdateOrCreate :'.$e->getMessage());
            // throw new Exception ('salesTremUpdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-STUOC');
        }
    }
    /**
     * insert portalMstOutput date
     * @param array $data
     * @return result 
     */ 
    public function portalMstOutputInsert($data)
    {     
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
            Log::info('portalMstOutputInsert :'.date("y/m/d hh:mm:ss").$e->getMessage());
        }
    }

    public function getPerformanceStatus($performceID){
        try {
            $result = $this->EvenManageModel->find($performceID);
            if($result)
                return $result->status;
            return null;
        } catch(Exception $e){
            Log::info('getPerformanceStatus :'.$e->getMessage());
        }
    }

    public function getSaleInfo(){
       try {
            $sale_info = array(
                'has_sale' => false,
                'schedule_info' => [],
                'first_stage_date' => '',
                'last_stage_date' => '',
                'earliest_sale_date' => '',
                'latest_sale_date' => '',
                'seats_info' => false,
                'seatTypeArr' => false
            );
            $earliest_sale_date = '';
            $latest_sale_date = '';
            $seats_info = array();

            $schedule = $this->EvenManageModel->schedule()
                                    ->with('Stagename', 'SeatSale', 'GLVSeatStaticOfStage', 'SeatClass', 'SeatClass.ticketClass')
                                    ->get();
        
            if($schedule->isNotEmpty()){
                $sale_info['first_stage_date'] = $schedule->min('performance_date');
                $sale_info['last_stage_date'] = $schedule->max('performance_date');
            }
            $seatMap = $this->EvenManageModel->GLVSeatOfStage()->get();
            $seatTypeArr = ($seatMap->where('seat_class_id','<>','')->groupBy('seat_class_id')->keys()->toArray());

            foreach($schedule as $key => $item){
                $stage_sale = false;
                $res_total = ($item->GLVSeatStaticOfStage)?$item->GLVSeatStaticOfStage->RES:0;
                if($item->SeatSale->count() > 0 || $res_total > 0){
                    $sale_info['has_sale'] = true;
                    $stage_sale = true;
                    $now_date = strtotime($item->performance_date);
                    if($now_date < $sale_info['earliest_sale_date'] || $sale_info['earliest_sale_date'] == null){
                        $sale_info['earliest_sale_date'] = $item->performance_date;
                        $earliest_sale_date = $now_date;
                    }
                    if($now_date > $sale_info['latest_sale_date'] || $sale_info['latest_sale_date'] == null){
                        $sale_info['latest_sale_date'] = $item->performance_date;
                        $latest_sale_date = $now_date;
                    }
                }

                if($item->SeatSale->isNotEmpty()){
                    foreach($item->SeatSale as $SeatSale){
                        $seats_info[$SeatSale->seat_class_id][] = $SeatSale->ticket_class_id;
                        $seats_info[$SeatSale->seat_class_id] = array_unique($seats_info[$SeatSale->seat_class_id]);
                    }
                    $sale_info['seats_info'] = $seats_info;
                }

                $schedule_info = array(
                    'schedule_id' => $item->schedule_id,
                    'performance_date' => $item->performance_date,
                    'start_time' => $item->start_time,
                    'stage_sale' => $stage_sale,
                    'sale_seat_sum' => $item->SeatSale->count(),
                    'res' => isset($item->GLVSeatStaticOfStage->RES)?$item->GLVSeatStaticOfStage->RES:0,
                    'stage_index' => $item->Stagename->stage_num,
                    
                );
            
                array_push($sale_info['schedule_info'], $schedule_info);
                $sale_info['seatTypeArr'] = empty($seatTypeArr)?false:$seatTypeArr;
            }
            return $sale_info;
        } catch(Exception $e){
            Log::info('S_EXC_MSN_0005 :'.$e->getMessage());
            return false;
        }
    }

    public function getPerformance($performceID){
        try {
            $this->EvenManageModel = $this->EvenManageModel->find($performceID);
            if($this->EvenManageModel)
                return $this->EvenManageModel;
            return null;
        } catch(Exception $e){
            Log::info('getPerformance :'.$e->getMessage());
        }
    }
 
    public function getPerformancePublished($GLID){
        try {
            $treturn = $this->EvenManageModel->where('GLID',$GLID)
                                             ->where('trans_flg',\Config::get('constant.GETTIIS_trans.already'))
                                             ->get();
            return $treturn;
        } catch(Exception $e){
            Log::info('getPerformancePublished :'.$e->getMessage());
        }
    }


    public function getTransFlg($performceID){
        try {
            $result = $this->EvenManageModel->find($performceID);
            if($result)
                return $result->trans_flg;
            return null;
        } catch(Exception $e){
            Log::info('getTransFlg :'.$e->getMessage());
        //    throw new Exception ('getTransFlg :'.$e->getMessage());
           throw new Exception ('EMR-EXP-GTF');
        }
    }

    public function getEditStatus() {
        $ret = $this->EvenManageModel->edit_status;
        $ret = $ret?:0;
        return $ret;
    }
    public function getEventOnSale($GLID) {
        //販売中、公演期間中の公演取得
        try {
          $ret = $this->EvenManageModel->where('GLID',$GLID)
                                       ->where('status',  \Config::get('constant.performance_status.sale'))
                                       ->where('sale_type', '1')
                                       ->where('performance_end_dt', '>=' ,date("y/m/d"))
                                       ->get()->toArray();

        } catch(Exception $e){
            Log::info('getEventOnSale :'.$e->getMessage());
        //    throw new Exception ('getEventOnSale :'.$e->getMessage());
           throw new Exception ('EMR-EXP-GEOS');
        }
        return $ret;
    }

    public function getFBGCtrl($performceID){
        try {
            $this->EvenManageModel = EvenManageModel::findOrFail($performceID);
            $fbgCtrl = 'fbg';
            
            if($this->EvenManageModel->seatmapProfile) {
                $fbgCtrl = '';
                $fbgCtrl .= $this->EvenManageModel->seatmapProfile->floor_ctrl>0?'f':'';
                $fbgCtrl .= $this->EvenManageModel->seatmapProfile->block_ctrl>0?'b':'';
                $fbgCtrl .= $this->EvenManageModel->seatmapProfile->gate_ctrl>0?'g':'';
                
            }
            return $fbgCtrl;

        } catch(Exception $e){
            Log::info('getFBGCtrl :'.$e->getMessage());
        //    throw new Exception ('getFBGCtrl :'.$e->getMessage());
            $fbgCtrl = 'fbg';
        }
    }
    public function hasStageSeat($preformanceId) {
        try {
            $list = $this->ScheduleModel->where('performance_id',$preformanceId)
                                        ->has('StageSeat')
                                        ->get();
            if($list->count() > 0 )
                return true;
            else
                return false;

        }catch(Exception $e){
            Log::error('hasStageSeat :'.$e->getMessage());
            // throw new Exception ('hasStageSeat :'.$e->getMessage());
            throw new Exception ('EMR-EXP-HSS');
        }
    }

    /**
     * update or create questions
     * @param array $data
     */ 
    public function questionUpdateOrCreate($performanceId, $accountCd, $data) {     
        // $result = $data;
        try {
            //GL_QUESTION update or insert
            $questionModel = $this->QuestionModel->updateOrCreate(
                [
                    'question_id' => $data->id
                ],
                [
                    'performance_id' => $performanceId,
                    'use_flg' => $data->use ? 1 : 0,
                    'require_flg' => $data->required ? 1 : 0,
                    'disp_order' => $data->sort,
                    'update_account_cd' => $accountCd,
                ]
            );
            $data->id = $questionModel->question_id;

            //GL_QUESTION_LANG update or insert
            if (property_exists($data, 'langs')) {
                $langs = get_object_vars($data->langs);
                foreach($langs as $key => $val) {
                    $lang = $key === 'zh_tw' ? 'zh-tw' : $key;
                    $questionLangModel = $this->QuestionLangModel->updateOrCreate(
                        [
                            'lang_id' => $val->id
                        ],
                        [
                            'question_id' => $questionModel->question_id,
                            'lang_code' => $lang,
                            'question_title' => $val->title,
                            'question_text' => $val->text,
                            'answer_placeholder' => $val->placeholder,
                            'update_account_cd' => $accountCd,
                        ]
                    );
                    $val->id = $questionLangModel->lang_id;
                }
            }

        } catch(Exception $e) {
            Log::info('questionUpdateOrCreate :'.$e->getMessage());
            // throw new Exception ('questionUpdateOrCreate :'.$e->getMessage());
            throw new Exception ('EMR-EXP-QUOC');
        }

        return $data;
    }
}