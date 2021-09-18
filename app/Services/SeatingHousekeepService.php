<?php

namespace App\Services;

use Log;
use Exception;
use Carbon\Carbon;
use App\Repositories\SeatSaleRepository;
// use App\Repositories\GeneralReservationRepository;


class SeatingHousekeepService
{
    protected $seatSaleRepo;

    public function __construct(SeatSaleRepository $seatSaleRepo)
    {
        $this->seatSaleRepo = $seatSaleRepo;
    }

    public function cleanExpiredTmpResv() {
        $list = $this->seatSaleRepo->getExpiredTmpReserLst();
        if(!empty($list)) {
            Log::info('[cleanExpiredTm
            pResv] Seating sale list:'.json_encode($list));
            $num = $this->seatSaleRepo->delExpiredTmpReser($list);
            Log::info('[cleanExpiredTmpResv] Seating sale deleted :'.$num);
        }
        
        return $list;
    }

}