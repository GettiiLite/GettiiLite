<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Services\MemberServices;

class MemberController extends Controller
{
    /** @var MemberServices */
    protected $MemberServices;

    /**
     * sellManageController constructor.
     * @param MemberServices $MemberServices
     */
    public function __construct(MemberServices $MemberServices)
    {
        $this->MemberServices = $MemberServices;
    }
    /**
     * get member data
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(session('member_info_flg') > 0) {
            $page       = (int)($request->page == 0)?1:$request->page;
            $keyWord    = $request->keyWord;
            $orderId    = $request->orderId;
            $result     = $this->MemberServices->getMembersData($page, $keyWord, $orderId);
    
            if($result['status']['status']){
                return view('frontend.member.index', ['events' => $result]);
            }else{
                return redirect($result['data']['url']);
            }    
        }
        else {
            App::abort(404);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function information(Request $request)
    {
        if(session('member_info_flg') > 0) {
            $page           = (int)($request->page == 0)?1:$request->page;
            $search         = $request->search;
            $orderStatus    = $request->orderStatus;
            $keyWord        = $request->keyWord;
            $userId         = $request->userId;
            $result         = $this->MemberServices->getMemberInf($page, $search, $orderStatus, $keyWord, $userId);
            
            return view('frontend.member.information', ['events' => $result]);
        }
        else {
            App::abort(404);
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request)
    {
        if(session('member_info_flg') > 0) {
            $page       = (int)($request->page == 0)?1:$request->page;
            $userId     = $request->userId;
            $ordersId   = $request->ordersId;
            $result     = $this->MemberServices->getMemberOrders($page, $userId, $ordersId);
    
            return view('frontend.member.orders', ['events' => $result]);    
        }
        else {
            App::abort(404);
        }
    }
}
