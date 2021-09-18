<?php

namespace App\Repositories;

use App\Models\SeatSaleModel;
use Exception;
use Log;
use App;
use Carbon\Carbon;

class SeatSaleRepository
{
    /** @var SeatSaleModel */
    protected $seatSaleModel;


    public function __construct(SeatSaleModel $seatSaleModel)
    {
        $this->seatSaleModel = $seatSaleModel;
    }

    public function getExpiredTmpReserLst() {
        $list = SeatSaleModel::expireTmpResvSeat();
        return $list->pluck('seat_sale_id')->all();
    }

    public function delExpiredTmpReser($list) {
        $ret = SeatSaleModel::destroy($list);
        return $ret;
    }

    public function getbySeatsaleID($seatsaleID) {
        try {
            $this->seatSaleModel = SeatSaleModel::findOrFail($seatsaleID);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function getScheduleInfo() {
        $schedule = $this->seatSaleModel->schedule;
        $performance = $schedule->performance;
        $stagename = $schedule->stagename;
        $ret = [
            'performance_id'            => $schedule->performance_id,
            'schedule_id'               => $schedule->schedule_id,
            'performance_code'          => $performance->performance_code,
            'performance_name'          => $performance->performance_name,
            'performance_name_sub'      => $performance->performance_name_sub,
            'hall_disp_name'            => $performance->hall_disp_name,
            'performance_name_sej'      => $performance->performance_name_sej?$performance->performance_name_sej:'',
            'performance_date'          => $schedule->performance_date,
            'start_time'                => $schedule->start_time,
            'information_nm'            => $performance->information_nm,
            'information_tel'           => $performance->information_tel,
            'stagename'                 => $stagename->stage_disp_flg?$stagename->stage_name:'',
            'disp_performance_date'     => empty($schedule->disp_performance_date)?'':$schedule->disp_performance_date,
            'sch_kbn'                   => $schedule->sch_kbn,
        ];
        return $ret;
    }

    public function getSeatInfo() {

        $commissions = intval($this->seatSaleModel->commission_sv) 
        + intval($this->seatSaleModel->commission_payment) 
        + intval($this->seatSaleModel->commission_ticket)
        + intval($this->seatSaleModel->commission_delivery)
        + intval($this->seatSaleModel->commission_sub)
        + intval($this->seatSaleModel->commission_uc);

        if($this->seatSaleModel->alloc_seat_id)
        {
            // 指定席
            
            $seat = $this->seatSaleModel->seat;
            $hallseat  = $seat->hallseat;
            $profile = $hallseat->profile;
            $floor = $hallseat->floor;
            $block = $hallseat->block;
            $ret = [
                'seat_class_name'       => $this->seatSaleModel->seat_class_name,
                'ticket_class_name'     => $this->seatSaleModel->ticket_class_name,
                'gate'                  => $profile->gate_ctrl?$hallseat->gate:'',
                'floor_name'            => $profile->floor_ctrl?$floor->floor_name:'',
                'block_name'            => $profile->block_ctrl?$block->block_name:'',
                'seat_cols'             => $hallseat->seat_cols,
                'seat_number'           => $hallseat->seat_number,
                'sale_price'            => intval($this->seatSaleModel->sale_price),
                'reserve_seq'           => $this->seatSaleModel->reserve_seq,
                'commissions'           => $commissions,
            ];
            return $ret;
        }
        else {
            // 自由席
            $ret = [
                'seat_class_name'       => $this->seatSaleModel->seat_class_name,
                'ticket_class_name'     => $this->seatSaleModel->ticket_class_name,
                'gate'                  => '',
                'floor_name'            => '',
                'block_name'            => '',
                'seat_cols'             => '',
                'seat_number'           => ($this->seatSaleModel->seat_seq != 0)?$this->seatSaleModel->seat_seq:'',
                'sale_price'            => intval($this->seatSaleModel->sale_price),
                'reserve_seq'           => $this->seatSaleModel->reserve_seq,
                'commissions'           => $commissions,
            ];
            return $ret;
        }
            
        return null;
    }

    public function setSeathasPaid(SeatSaleModel $seat, String $paymentDate)
    {
        $seat->seat_status = 3;
        $seat->payment_flg = 1;
        $seat->payment_date = $paymentDate;
        $seat->update();
    }

    public function setSeathasIssued(SeatSaleModel $seat, String $issueDate)
    {
        if($seat->issue_flg == 0)
            $seat->issue_flg = 1;
        if("" == $seat->issue_date)
            $seat->issue_date = $issueDate;
        if("" == $seat->issue_account_cd)
            $seat->issue_account_cd = 1;
        $seat->seat_status = 3;
        $seat->update();
    }

    public function setSeathasCancel(SeatSaleModel $seat)
    {
        $seat->seat_status = 0 - abs($seat->seat_status);
        $seat->update();
    }

}