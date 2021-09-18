<?php

namespace App\Services;

use Log;
use Exception;
use App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\MemberRepositories;
use App\Repositories\CancelOrderRepositories;
use App\Services\GetApiServices;
use Illuminate\Pagination\LengthAwarePaginator;


class MemberServices
{
    /** @var MemberRepositories */
    protected $MemberRepositories;
    /** @var GetApiServices */
    protected $GetApiServices;
    /** @var CancelOrderRepositories */
    protected $CancelOrderRepositories;

    const PAGE_SIZE = 10;

    /**
     * UserController constructor.
     * @param MemberRepositories $MemberRepositories
     * @param GetApiServices $GetApiServices
     * @return
     */
    public function __construct(MemberRepositories $MemberRepositories, GetApiServices $GetApiServices, CancelOrderRepositories $CancelOrderRepositories)
    {
        $this->MemberRepositories   = $MemberRepositories;
        $this->GetApiServices       = $GetApiServices;
        $this->CancelOrderRepositories = $CancelOrderRepositories;
    }  
    /**
     * 判斷 http code 回傳狀態
     * 
     * @param int $httpCode
     * @return array $result
     */
    private function apiStatus($httpCode){
        $status     = true;
        $msn        = '';
        
        switch($httpCode){
            case 0:
                $status     = false;
                $msn        = trans('member.S_Error') . ' E0';
                break; 
            case 400:
                $status     = false;
                $msn        = trans('member.S_Error') . ' E400';
                break;   
            case 404:
                $status     = false;
                $msn        = trans('member.S_Error') . ' E404';
                break;      
            default:
        }

        $result = array(
            'status'    =>  $status,    
            'msn'       =>  $msn       
        );

        return $result;
    }
    /**
     * 取得 gettiis 上會員資料
     * 
     * @param int $page
     * @param string $keyWord
     * @param string $orderId
     * 
     * @return $result
     */
    public function getMembersData($page, $keyWord, $orderId){
        $userInf    =  array();
        $perPage    =  self::PAGE_SIZE;
        $url        = '/member?keyWord='.$keyWord.'&orderId='.$orderId;
        $hadOrderId = false;
        $search     = true;
        $errorData  = '';
        $msn        = '';
       
        if($orderId){
            $reserveData = array(
                'reserve_no'   =>  $orderId,
            );
            
            $reserveResult = $this->MemberRepositories->getOrderUserId($reserveData);
          
            //無相關訂單編號
            if(empty($reserveResult)){

                $errorData  = array(
                    'msn'   => trans('member.S_OrderNoErr'),
                );

                $status = array(
                    'status'     =>  true,
                    'paginator'  =>  null,
                    'keyWord'    =>  $keyWord,
                    'orderId'    =>  $orderId,
                    'search'     =>  false,
                 );
         
                 $resultData  =   array(
                    'errorData' =>   $errorData,
                    'userInf'   =>   null,
                    'url'       =>   null,
                 );
         
                 $result = array(
                    'status'   =>   $status,
                    'data'     =>   $resultData,
                 );
              
                 return  $result;
            }else{
                $explodeArray = explode('gettiis$', $reserveResult[0]['member_id']);
                
                if(count($explodeArray) == 2){
                    $userid = $explodeArray[1];
                } 

                $hadOrderId = true;
            }
        }else{
            $userid = NULL;
        }
        
        $apiSite = $this->MemberRepositories->getAPISite(session('GLID'));
        $data = array(
            'url'   =>  $apiSite.'/members?page='.$page.'&perPage='.$perPage.'&keyWord='.$keyWord.'&userid='.$userid,
        );
        
        $apiData = $this->GetApiServices->get($data);
        $memberData = $apiData['data']['memberData'];
        $userData   = $memberData[0]['data'][0]['userInf'];
        $totalData  = $memberData[0]['status']['total'];
        
        if($userData){
            foreach($userData as $value){
                $userInf[]  =   array(
                    'id'            =>  $value['id'],
                    'user_id'       =>  $value['user_id'],
                    'name'          =>  $value['name'],
                    'status'        =>  $this->getStatusStr($value['status_code']),
                    'tel'           =>  $value['tel'],
                    'email'         =>  $value['email'],
                );
            }
        }else{
            $apiStatus  = $this->apiStatus($apiData['status']['httpCode']);
            $search     = $apiStatus['status'];
            $errorData  = array(
                'msn'   => $apiStatus['msn'],
            );
        }
      
        $totalEvent = $totalData;
        $paginator  = new LengthAwarePaginator($userInf, $totalEvent, $perPage);
        $paginator->withPath($url);
       
        if($hadOrderId){
            $status     =   false;
            $orderUrl   =   '/member/orders/'.$userInf[0]['id'].'/'. $orderId;
        }else{
            $status     =   true;
            $orderUrl   =   null;
        }
       
        $status = array(
           'status'     =>  $status ,
           'paginator'  =>  $paginator,
           'keyWord'    =>  $keyWord,
           'orderId'    =>  $orderId,
           'search'     =>  $search,
        );

        $resultData  =   array(
            'errorData' =>   $errorData,
            'userInf'   =>   $userInf,
            'url'       =>   $orderUrl,
        );

        $result = array(
            'status'   =>   $status,
            'data'     =>   $resultData,
        );
        
        return  $result;
    }
    /**
     * 取得 gettiis 會員詳細資料與訂單記錄
     * 
     * @param int $page
     * @param string $search
     * @param string $orderStatus
     * @param string $keyWord
     * @param string $userId
     * 
     * @return array $result
     */
    public function getMemberInf($page, $search, $orderStatus, $keyWord, $userId){
        $userInf        =  array();
        $orderInf       =  array();
        $had_reserve_no = false;
        $errorData      = null;
        $url            = '/member/information/'.$userId.'?search='.$search.'&orderStatus='.$orderStatus.'&keyWord='.$keyWord;

        $apiSite = $this->MemberRepositories->getAPISite(session('GLID'));
        $data = array(
            'url'   =>  $apiSite.'/member/'.$userId,
        );

        $apiData        = $this->GetApiServices->get($data);
        $memberData     = $apiData['data']['memberData'];
        $userData       = $memberData[0]['data'][0]['userInf'];
        $apiStatus      = $this->apiStatus($apiData['status']['httpCode']);
        $httpStatus    = $apiStatus['status'];
        $msn            = $apiStatus['msn'];
       
        if($httpStatus){
            $userInf  =   array(
                'id'                    =>  $userData[0]['id'],
                'user_id'               =>  $userData[0]['user_id'],
                'name'                  =>  $userData[0]['name'],
                'status'                =>  $this->getStatusStr($userData[0]['status_code']),
                'mobapass_app_id'       =>  $userData[0]['mobapass_app_id'],
                'purchase_ticket_count' =>  $userData[0]['purchase_ticket_count'],
                // 'birthdate'             =>  $userData[0]['birthdate'],
                'birthdate'             =>  empty($userData[0]['gender_code'])?'-':Carbon::parse($userData[0]['birthdate'])->toDateString(),
                'logined_at'            =>  $userData[0]['logined_at'],
                'email_status'          =>  $userData[0]['email_status'],
                'tel'                   =>  $userData[0]['tel'],
                'email'                 =>  $userData[0]['email'], 
                'gender'                =>  $this->getGenderStr($userData[0]['gender_code']),
                'created_at'            =>  $userData[0]['created_at'],
                'updated_at'            =>  $userData[0]['updated_at'],
                'country'               =>  $userData[0]['country'],
                'favoriteCount'         =>  $userData[0]['favoriteCount'],
            );
        
            $memberId = 'gettiis$'.$userData[0]['user_id'];

            $filterData = array(
                'member_id'     => $memberId,
                'keyWord'       => $keyWord,
                'glid'          => session('GLID'),
            );
            
            if($search == 'search'){
                $OrderData = $this->MemberRepositories->getOrderDataById($filterData);
            }else{
                $OrderData = null;
            }

            if(!$OrderData || $search == 'all'){
                $OrderData = $this->MemberRepositories->getOrderData($filterData);
            }elseif($OrderData && $search == 'search'){
                $had_reserve_no = true;
            }
            
            foreach($OrderData as $key => $value){
                $performance_name   =   '';
                $open_date          =   '';
                $ticket_class_name  =   '';
                $payment_fly        =   '';
                $issue_flg          =   '';
                $payment            =   0;

                $filterData = array(
                    'order_id'          =>  $value['order_id'],
                    'search'            =>  $search,
                    'orderStatus'       =>  $orderStatus, 
                    'keyWord'           =>  $keyWord,
                    'had_reserve_no'    =>  $had_reserve_no,
                );
            
                $SeatSaleData = $this->MemberRepositories->getSeatSaleData($filterData);
                $totalTicket = count($SeatSaleData);
                
                foreach($SeatSaleData as $key1 => $value1){
                    if($key1 == 0){
                        $performance_status =   $value1['status'];
                        $seat_status        =   $value1['seat_status'];
                        $performance_name   =   $value1['performance_name'];
                        $open_date          =   $value1['open_date'];
                        $eventStartDate     =   Carbon::parse($value1['performance_date'] . ' '. $value1['start_time']);
                        $seat_class_name    =   $value1['seat_class_name'];
                        $payment_fly        =   $value1['payment_flg'];
                        $issue_flg          =   $value1['issue_flg'];
                    }

                    $payment += $value1['sale_price'] + $value1['commission_payment'] + $value1['commission_ticket'] + $value1['commission_delivery'] + $value1['commission_sub'] + $value1['commission_uc'];
                }
                if($SeatSaleData){
                    $orderInf[] = array(
                        'order_id'          => $value['order_id'],
                        'reserve_no'        => $value['reserve_no'],
                        'reserve_date'      => Carbon::parse($value['reserve_date'])->format('y-m-d H:i'),
                        'seat_status'       => $seat_status,
                        'performance_name'  => $performance_name,
                        'performance_status'=> $performance_status,
                        'open_date'         => $open_date,
                        'eventStartDate'    => $eventStartDate->format('Y-m-d H:i'),
                        'seat_class_name'   => $seat_class_name,
                        'payment_fly'       => ($value['pay_method'] == 0)?-1:$payment_fly,
                        'issue_flg'         => $issue_flg,
                        'total_ticket'      => $totalTicket,
                        'payment'           => number_format($payment),
                        'reserve_expire'    => $value['reserve_expire'],
                        'pay_method'        => $value['pay_method'],
                        'pickup_method'     => $value['pickup_method'],
                        'cancel_flg'        => $value['cancel_flg'],
                    );
                }
            }

            $totalEvent = count($orderInf);
            $nowPageStar = ($page - 1) * self::PAGE_SIZE;
            $nowPageEnd = self::PAGE_SIZE;
            $pageData = array_slice($orderInf, $nowPageStar, $nowPageEnd);
            $paginator = new LengthAwarePaginator($pageData, $totalEvent, self::PAGE_SIZE);
            $paginator->withPath($url);
        }else{
            $paginator  =   null;
            $pageData   =   null;

            $errorData = array(
                'msn'       =>   $msn,
            );
        }

        $status = array(
            'status'        => $httpStatus,
            'userId'        => $userId,
            'search'        => $search,
            'orderStatus'   => $orderStatus, 
            'keyWord'       => $keyWord,
            'paginator'     => $paginator,
        );
        
        $resultData  =   array(
            'errorData' =>   $errorData,
            'userInf'   =>   $userInf,
            'orderInf'  =>   $this->transOrderDatatoStr($pageData),
        );

        $result = array(
            'status'   =>   $status,
            'data'     =>   $resultData,
        );
        return  $result;
    }
    /**
     * 訂單資料
     * 
     * @param int $page
     * @param int $userId
     * @param int $orderId
     * 
     * @return array $result
     */
    public function getMemberOrders($page, $userId, $orderId){
        $userInf            =  array();
        $ticketInf          =  array();
        $orderInf           =  array();
        $commission_sv      =   array(
            'status'    =>  false,
            'num'       =>  0,
            'total'     =>  0,
        );
        $commission_payment =   array(
            'status'    =>  false,
            'num'       =>  0,
            'total'     =>  0,
        );
        $commission_ticket  =   array(
            'status'    =>  false,
            'num'       =>  0,
            'total'     =>  0,
        );
        $commission_delivery =   array(
            'status'    =>  false,
            'num'       =>  0,
            'total'     =>  0,
        );
        $commission_sub     =   array(
            'status'    =>  false,
            'num'       =>  0,
            'total'     =>  0,
        );
        $commission_uc      =   array(
            'status'    =>  false,
            'num'       =>  0,
            'total'     =>  0,
        );

        $apiSite = $this->MemberRepositories->getAPISite(session('GLID'));
        $data = array(
            'url'   =>  $apiSite.'/member/'.$userId,
        );

        $apiData    = $this->GetApiServices->get($data);
        $memberData = $apiData['data']['memberData'];
        $userData   = $memberData[0]['data'][0]['userInf'];
        
        $userInf  =   array(
            'id'                    =>  $userData[0]['id'],
            'user_id'               =>  $userData[0]['user_id'],
            'name'                  =>  $userData[0]['name'],
            'status'                =>  $this->getStatusStr($userData[0]['status_code']),
            'mobapass_app_id'       =>  $userData[0]['mobapass_app_id'],
            'purchase_ticket_count' =>  $userData[0]['purchase_ticket_count'],
            'birthdate'             =>  empty($userData[0]['gender_code'])?'-':Carbon::parse($userData[0]['birthdate'])->toDateString(),
            'logined_at'            =>  $userData[0]['logined_at'],
            'email_status'          =>  $userData[0]['email_status'],
            'tel'                   =>  $userData[0]['tel'],
            'email'                 =>  $userData[0]['email'],
            'gender'                =>  $this->getGenderStr($userData[0]['gender_code']),
            'created_at'            =>  $userData[0]['created_at'],
            'updated_at'            =>  $userData[0]['updated_at'],
            'country'               =>  $userData[0]['country'],
            'favoriteCount'         =>  $userData[0]['favoriteCount'],
        );
       
        $filterData = array(
            'reserve_no' =>  $orderId,
        );

        $OrderData    = $this->MemberRepositories->getOrdersDetails($filterData);
        $sumPayment   = 0;
        $totalPayment = 0;
        
        if(!empty($OrderData)){
            if((float)$OrderData[0]['commission_sv']){
                $commission_sv['status'] = true;
                $commission_sv['num']   += 1;
                $commission_sv['total'] += (float)$OrderData[0]['commission_sv'];
                $totalPayment += (float)$OrderData[0]['commission_sv'];
            }

            if((float)$OrderData[0]['commission_payment']){
                $commission_payment['status'] = true;
                $commission_payment['num']   += 1;
                $commission_payment['total'] += (float)$OrderData[0]['commission_payment'];
                $totalPayment += (float)$OrderData[0]['commission_payment'];
            }
         
            if((float)$OrderData[0]['commission_ticket']){
                $commission_ticket['status'] = true;
                $commission_ticket['num']   += 1;
                $commission_ticket['total'] += (float)$OrderData[0]['commission_ticket'];
                $totalPayment += (float)$OrderData[0]['commission_ticket'];
            }

            if((float)$OrderData[0]['commission_delivery']){
                $commission_delivery['status'] = true;
                $commission_delivery['num']   += 1;
                $commission_delivery['total'] += (float)$OrderData[0]['commission_delivery'];
                $totalPayment += (float)$OrderData[0]['commission_delivery'];
            }
            if((float)$OrderData[0]['commission_sub']){
                $commission_sub['status'] = true;
                $commission_sub['num']   += 1;
                $commission_sub['total'] += (float)$OrderData[0]['commission_sub'];
                $totalPayment += (float)$OrderData[0]['commission_sub'];
            }
            if((float)$OrderData[0]['commission_uc']){
                $commission_uc['status'] = true;
                $commission_uc['num']   += 1;
                $commission_uc['total'] += (float)$OrderData[0]['commission_uc'];
                $totalPayment += (float)$OrderData[0]['commission_uc'];
            }
        }

        foreach($OrderData as $key => $value){
            $seatType       =   ($value['alloc_seat_id'])?trans('member.S_SeatTypeReserve'):trans('member.S_SeatTypeFree');
            $saatPosition   =   $value['floor_name'].'-'.$value['block_name'].'-'.$value['seat_cols'].'-'.$value['seat_number'];
            $payment        =   $value['sale_price'];

            switch($value['pay_method']){
                case \Config::get('constant.pay_method.cash'):
                    $pay_inf = trans('member.S_PayMethodCash');
                    break;
                case \Config::get('constant.pay_method.card'):
                    $pay_inf = trans('member.S_PayMethodCard');
                    break;
                case \Config::get('constant.pay_method.store'):
                    $pay_inf = trans('member.S_PayMethodSEJ');;
                    break;
                case \Config::get('constant.pay_method.ibon'):
                    $pay_inf = 'ibon';
                    break;
                default:
                    $pay_inf = '-';
            }
           
            switch($value['pickup_method']){
                case \Config::get('constant.pickup_method.eticket');
                    $pickup_inf = trans('member.S_PickUpMethodMbps');
                    break;
                case \Config::get('constant.pickup_method.qrpass_sms'):
                case \Config::get('constant.pickup_method.qrpass_email'):
                    $pickup_inf = 'qrpass';
                    break;
                case \Config::get('constant.pickup_method.store'):
                    $pickup_inf = trans('member.S_PickUpMethodSEJ');
                    break;
                case \Config::get('constant.pickup_method.onsite'):
                    $pickup_inf = '';
                    break;
                case \Config::get('constant.pickup_method.ibon'):
                    $pickup_inf = 'ibon';
                    break;
                default:
                    $pickup_inf = '-';
            }

            $ticketInf[] = array(
                'pay_inf'           =>   $pay_inf,
                'pickup_inf'        =>   $pickup_inf,
                'seat_type'         =>   $seatType,
                'ticket_class_name' =>   $value['ticket_class_name'],
                'seat_class_name'   =>   $value['seat_class_name'],
                'saat_position'     =>   $saatPosition,
                'payment'           =>   number_format($payment),
            );
            
            
            if($key == 0){
                $order_id           = $value['order_id'];
                $performanceName    = $value['performance_name'];
                $openDate           = $value['open_date'];
                $eventDatetime      = Carbon::parse($value['performance_date'] . ' '. $value['start_time']);
                $reserveNo          = $value['reserve_no'];
                $reserveDate        = $value['reserve_date'];
                // $payStatus          = ($value['payment_flg'])?trans('member.S_PayStatusAlready'):trans('member.S_PayStatusYet');
                $payMethod          = $pay_inf;
                $payInfo            = '';
                $pickupStatus       = ($value['issue_flg'])?trans('member.S_PickUpStatusAlready'):trans('member.S_PickUpStatusYet');
                $pickupMethod       = $pickup_inf;
                $pickupInfo         =  trans('member.S_DeadLine') .  Carbon::parse($value['pickup_st_date'])->format('Y-m-d H:i') . trans('member.S_To') .  Carbon::parse($value['pickup_due_date'])->format('Y-m-d H:i');
                // $pickupStDate       = Carbon::parse($value['pickup_st_date'])->format('Y-m-d H:i');
                // $pickupDueDate      = Carbon::parse($value['pickup_due_date'])->format('Y-m-d H:i');
                $cancelFlg          = $value['cancel_flg'];
                // $cancelcomment      = $value['comment'];
                $seat_status        = $value['seat_status'];
                $issue_flg          = $value['issue_flg'];
                $pay_method         = $value['pay_method'];
                $reserve_expire     = $value['reserve_expire'];
                $payment_fly          = $value['payment_flg'];
                $payInfo            = '';
                if($payment_fly) {
                    $payStatus          = trans('member.S_PayStatusAlready');
                    if(isset($value['payment_date']))
                        $payInfo            = trans('member.S_PaymentDate') . Carbon::parse($value['payment_date'])->format('Y-m-d H:i');
                }
                else {
                    if($value['pay_method'] != 0) {
                        $payStatus          = trans('member.S_PayStatusYet');
                        $payInfo            = trans('member.S_PaymentDeadline') . Carbon::parse($reserve_expire)->format('Y-m-d H:i');    
                    }
                    else {
                        $payStatus          = trans('member.S_NoPayment');
                        $payMethod          = trans('member.S_SystemTicketing');
                    }
                }
            }

            $sumPayment   += $payment;
            $totalPayment += $payment;
           
        }

        $commissionInf = array(
            'commission_sv'         =>  $commission_sv,
            'commission_payment'    =>  $commission_payment,
            'commission_ticket'     =>  $commission_ticket,
            'commission_delivery'   =>  $commission_delivery,
            'commission_sub'        =>  $commission_sub,
            'commission_uc'         =>  $commission_uc,
        );
       
        $cancelStatusStr = '';
        $cancelInfo1 = '';
        $cancelInfo2 = '';
        $memo = '';
        if($cancelFlg) {
            try {
                $this->CancelOrderRepositories = $this->CancelOrderRepositories->getByOrderId($order_id);
                $cancelStatusStr = $this->CancelOrderRepositories->getCancelStatusStr() . '｜'. 
                                   $this->CancelOrderRepositories->getRefundToolStr() . trans('member.S_Refund') .
                                   $this->CancelOrderRepositories->getRefundAmtStr();
                $cancelInfo1 = trans('member.S_ApplyDate').$this->CancelOrderRepositories->getCreatedTimeStr();
                if(!empty($this->CancelOrderRepositories->getReundTimeStr()))
                    $cancelInfo2 = trans('member.S_RefundDate').$this->CancelOrderRepositories->getReundTimeStr();
                if(!empty($this->CancelOrderRepositories->getReundInfStr())) {
                    $memoStr = trans('member.S_RefundDetail') . ' ：' . $this->CancelOrderRepositories->getReundInfStr();
                    $memo .= $memoStr;
                }
            }
            catch(Exception $e){
                Log::error('Can not found cancel log data. orderid = '.$order_id);
            }
            
        }

        $orderInf = array(
            'performanceName'   =>   $performanceName,
            'reserveNo'         =>   $reserveNo,
            'reserveDate'       =>   $reserveDate,
            'openDate'          =>   $openDate,
            'eventDate'         =>   $eventDatetime->toDateString(),
            'startTime'         =>   $eventDatetime->format('H:i'),
            'payStatus'         =>   $payStatus,
            'payMethod'         =>   $payMethod,
            'payInfo'           =>   $payInfo,
            'pay_method'        =>   $pay_method,
            'payInfo'           =>   $payInfo,
            'pickupStatus'      =>   $pickupStatus,
            'pickupMethod'      =>   $pickupMethod,
            'pickupInfo'        =>   $pickupInfo,
            // 'pickupStDate'      =>   $pickupStDate,
            // 'pickupDueDate'     =>   $pickupDueDate,
            'cancel_flg'        =>   $cancelFlg,
            'cancelStatus'      =>   $cancelStatusStr,
            'cancelInfo1'       =>   $cancelInfo1,
            'cancelInfo2'       =>   $cancelInfo2,
            'sumPayment'        =>   number_format($sumPayment),
            'totalPayment'      =>   number_format($totalPayment),
            'seat_status'       =>   $seat_status,
            'issue_flg'         =>   $issue_flg,
            'payment_fly'       =>   $payment_fly,
            'reserve_expire'    =>   $reserve_expire,
            'memo'              =>   $memo,
        );

        $status = $this->getOrderStatus($orderInf);

        $resultData  =   array(
            'userInf'       =>   $userInf,
            'ticketInf'     =>   $ticketInf,
            'orderInf'      =>   $orderInf,
            'commissionInf' =>  $commissionInf,
        );

        $result = array(
            'status'   =>   $status,
            'data'     =>   $resultData,
        );
       return $result;

    }

    private function getGenderStr($gender_code) {
        switch($gender_code) {
            case 1:
                // return trans('member.S_Gender');
                return '男性';
            break;
            case 2:
                return '女性';
            break;
            case 3:
                return '不指定';
            break;
            default:
                return '未設定';
            break;
        }
    }

    private function getStatusStr($statusCode) {
        switch($statusCode) {
            case 1:
                return trans('member.S_ApplyDraft');
            break;
            case 2:
                return trans('member.S_ApplyComplete');
            break;
            default:
                return '--';
            break;
        }
    }

    private function transOrderDatatoStr($orderArr) {
        $resultArr = [];
        foreach($orderArr as $order) {
            $order['seat_status'] = $this->getOrderStatus($order);
            $resultArr[] = $order;
        }
        return $resultArr;
    }

    private function getOrderStatus($order) {
        if($order['cancel_flg'] == 0) {
            switch($order['seat_status']) {
                case 1:
                    return trans('member.S_TempReserved');
                case 2:
                    if(Carbon::now()->gt(Carbon::parse($order['reserve_expire']))) {
                        // 非線上付款
                        return trans('member.S_PaymentOverdue');
                    }
                    else {
                        if($order['pay_method'] != 2) {
                            return trans('member.S_NotPaymnet');
                        }
                        else {
                            return trans('member.S_OnProcessing');
                        }
                    }
                break;
                    
                case 3:
                    if($order['issue_flg'] != 0 ) {
                        return trans('member.S_OrderComplete');
                    }
                    else {
                        if($order['pay_method'] == 0) {
                            return trans('member.S_NotPickUp');
                        }
                        else {
                            if($order['payment_fly'] == 0 ) {
                                //未付款
                                if($order['pay_method'] != 2 && Carbon::now()->gt(Carbon::parse($order['reserve_expire']))) {
                                    // 非線上付款
                                    return trans('member.S_PaymentOverdue');
                                }
                                else {
                                    return trans('member.S_NotPaymnet');
                                }
                                // return '待付款';
                            }
                            else {
                                return trans('member.S_NotPickUp');
                            }
                        }
                    }
                default:
                    return 'Err('.$order['seat_status'].')';
            }    
        }
        return trans('member.S_Cancel');
    }
}