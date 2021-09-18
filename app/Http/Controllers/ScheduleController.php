<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScheduleManageServices;

class ScheduleController extends Controller
{
    /**@var ScheduleManageServices*/
    protected $ScheduleManageServices;

    /**
     * UserController constructor.
     * @param ScheduleManageServices $ScheduleManageServices
     */
    public function __construct(ScheduleManageServices $ScheduleManageServices)
    {
        $this->ScheduleManageServices = $ScheduleManageServices;
    }  
    /**
     * show schedule 
     * @param $performanceId
     */
    public function scheduleList($performanceId, $scheduleId){
        $data = $this->ScheduleManageServices->scheduleList($performanceId, $scheduleId);
        
        return view('frontend.sell.scheduleList', ['data' => $data]);
    }
    /**
     * show schedule 
     * @param $performanceId
     */
    public function scheduleCancel(Request $request){
        $result = $this->ScheduleManageServices->cancelSchedule($request->all());
        
        return redirect($result);
    }
}
