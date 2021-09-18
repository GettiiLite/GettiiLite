<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class qrpassExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrpassExcel 
                            {--pc= : performance_code}
                            {--s=  : 場次代碼}
                            {--p=  : 存放位置}
                            {--f=  : 檔案名稱}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '--pc= : performance_code | --s=  : 場次代碼 |--p=  : 存放位置 | --f=  : 檔案名稱';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   

        $arguments = $this->option();
        $data = array('報名序號,智林序號,驗證序號,活動名稱,場次,姓名,Email,手機,場地,活動日期,座位,票種,票券付款狀態,售出日期,票面價格,主辦單位,購買人,聯絡人,郵遞區號,地址,備註');
        $schedule_datatime = '';
        $seat_title    = '';
        $ticket_type   = '';
        $ticket_status = '';
        
        if(!is_null($arguments['pc'])){
            $performance_code = $arguments['pc'];
        }
        
        if(!is_null($arguments['s'])){
            $schedule_code = explode(",", $arguments['s']);
        }else{
            $schedule_code = null;
        }
       
        if(!is_null($arguments['p'])){
            $path = $arguments['p'];
        }else{
            $path = base_path();
        }
       
        if(!is_null($arguments['f'])){
            $file_name = $arguments['f'];
        }else{
            $file_name = $arguments['pc'];
        }

        $file = fopen($path . "/" . $file_name . ".csv", "wb"); 

        //會場資料
        $performance_data = \DB::table('GL_PERFORMANCE')
                            ->leftJoin('GL_USER', 'GL_PERFORMANCE.GLID', '=', 'GL_USER.gl_code')
                            ->select(
                                'GL_PERFORMANCE.performance_id',
                                'GL_PERFORMANCE.performance_name',
                                'GL_PERFORMANCE.hall_disp_name',
                                'GL_USER.disp_name'
                            )
                            ->where('performance_code', $performance_code)
                            ->get()
                            ->toArray();
        
        if(isset($performance_data[0])){
            //  開啟檔案
            fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, explode(',', $data[0]));
            $performance_id = $performance_data[0]->performance_id;
           
            if(is_null($schedule_code)){
                //場次資料
                $schedule_data  = \DB::table('GL_SCHEDULE')
                                    ->leftJoin('GL_TICKET_LAYOUT', 'GL_SCHEDULE.schedule_id', '=', 'GL_TICKET_LAYOUT.schedule_id')
                                    ->join('GL_STAGENAME', function ($join) {
                                        $join->on('GL_STAGENAME.performance_id', '=', 'GL_SCHEDULE.performance_id')
                                             ->on('GL_STAGENAME.stcd', '=', 'GL_SCHEDULE.stcd');
                                    })
                                    ->select(
                                        'GL_SCHEDULE.schedule_id',
                                        'GL_SCHEDULE.performance_date',
                                        'GL_SCHEDULE.start_time',
                                        'GL_SCHEDULE.disp_performance_date',
                                        'GL_TICKET_LAYOUT.free_word',
                                        'GL_STAGENAME.stage_disp_flg',
                                        'GL_STAGENAME.stage_name'
                                    )
                                    ->where('GL_SCHEDULE.performance_id', $performance_id)
                                    ->get()
                                    ->toArray();
            }else{
                //場次資料
                $schedule_data  = \DB::table('GL_SCHEDULE')
                                    ->leftJoin('GL_TICKET_LAYOUT', 'GL_SCHEDULE.schedule_id', '=', 'GL_TICKET_LAYOUT.schedule_id')
                                    ->join('GL_STAGENAME', function ($join) {
                                        $join->on('GL_STAGENAME.performance_id', '=', 'GL_SCHEDULE.performance_id')
                                             ->on('GL_STAGENAME.stcd', '=', 'GL_SCHEDULE.stcd');
                                    })
                                    ->select(
                                        'GL_SCHEDULE.schedule_id',
                                        'GL_SCHEDULE.performance_date',
                                        'GL_SCHEDULE.start_time',
                                        'GL_SCHEDULE.disp_performance_date',
                                        'GL_TICKET_LAYOUT.free_word',
                                        'GL_STAGENAME.stage_disp_flg',
                                        'GL_STAGENAME.stage_name'
                                    )
                                    ->where('GL_SCHEDULE.performance_id', $performance_id)
                                    ->whereIn('GL_SCHEDULE.schedule_id', $schedule_code)
                                    ->get()
                                    ->toArray();
            }
            
            $bar = $this->output->createProgressBar(count($schedule_data));

            // dd($schedule_data);
            foreach($schedule_data as $value){
                $bar->advance();
                // dd($value);

                //場次判斷
                if($value->stage_disp_flg && !is_null($value->stage_name)){
                    $schedule_datatime = $value->stage_name;
                }else if(!is_null($value->disp_performance_date)){
                    $schedule_datatime = sprintf('%s %s %s', $value->performance_date, $value->disp_performance_date, $value->start_time);
                }else{
                    $schedule_datatime = sprintf('%s-%s', $value->performance_date . $value->start_time);
                }
                // dd($schedule_datatime);

                //訂單資料
                $reservation_data = \DB::table('GL_GENERAL_RESERVATION')
                                        ->leftJoin('GL_SEAT_SALE', 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_SEAT_SALE.order_id')
                                        // ->leftJoin('GL_V_Seat_of_Stage', 'GL_SEAT_SALE.alloc_seat_id', '=', 'GL_V_Seat_of_Stage.alloc_seat_id')
                                        ->select(
                                            'GL_GENERAL_RESERVATION.order_id',
                                            'GL_GENERAL_RESERVATION.consumer_name',
                                            'GL_GENERAL_RESERVATION.mail_address',
                                            'GL_GENERAL_RESERVATION.tel_num',
                                            'GL_SEAT_SALE.reserve_seq',
                                            'GL_SEAT_SALE.seat_sale_id',
                                            'GL_SEAT_SALE.seat_class_name',
                                            'GL_SEAT_SALE.ticket_class_name',
                                            'GL_SEAT_SALE.seat_status',
                                            'GL_SEAT_SALE.issue_date',
                                            'GL_SEAT_SALE.sale_price',
                                            'GL_SEAT_SALE.alloc_seat_id',
                                            'GL_SEAT_SALE.seat_seq'
                                            // 'GL_V_Seat_of_Stage.seat_id',
                                            // 'GL_V_Seat_of_Stage.reserve_code'
                                        )
                                        ->where('GL_GENERAL_RESERVATION.pickup_method', '=', 91)
                                        // ->where('GL_V_Seat_of_Stage.schedule_id', '=', $value->schedule_id)
                                        ->where('GL_SEAT_SALE.schedule_id', '=', $value->schedule_id)
                                        ->get()
                                        ->toArray();

                foreach($reservation_data as $reservation_value){
                    $ticket_type = $reservation_value->seat_class_name . ' ' .$reservation_value->ticket_class_name;
                    if(is_null($reservation_value->alloc_seat_id)) { //自由席
                        $seat_title = $reservation_value->seat_seq;
                    }
                    else { //指定席
                        $seat_data = \DB::table('GL_SEAT')
                                        ->leftJoin('GL_HALL_SEAT', 'GL_HALL_SEAT.seat_id', '=', 'GL_SEAT.seat_id')
                                        ->leftJoin('GL_FLOOR', 'GL_HALL_SEAT.floor_id', '=', 'GL_FLOOR.floor_id')
                                        ->leftJoin('GL_BLOCK', 'GL_HALL_SEAT.block_id', '=', 'GL_BLOCK.block_id')
                                        ->select(
                                            'GL_FLOOR.floor_name',
                                            'GL_BLOCK.block_name',
                                            'GL_HALL_SEAT.seat_cols',
                                            'GL_HALL_SEAT.seat_number'
                                        )
                                        ->where('GL_SEAT.alloc_seat_id', '=', $reservation_value->alloc_seat_id)
                                        ->get()
                                        ->toArray();

                        if(isset($seat_data[0])){
                            $seat_title = $seat_data[0]->floor_name . ' ' .$seat_data[0]->block_name . ' ' .$seat_data[0]->seat_cols . ' ' .$seat_data[0]->seat_number;
                        }

                        $seat_data = \DB::table('GL_V_Seat_of_Stage')
                                    ->leftJoin('GL_RESERVE', 'GL_RESERVE.reserve_code', '=', 'GL_V_Seat_of_Stage.reserve_code')
                                    ->select(
                                        'GL_RESERVE.reserve_name'
                                    )
                                    ->where('GL_V_Seat_of_Stage.alloc_seat_id', '=', $reservation_value->alloc_seat_id)
                                    ->where('GL_V_Seat_of_Stage.schedule_id', '=', $value->schedule_id)
                                    ->get()
                                    ->toArray();
                                
                        if(isset($seat_data[0]) && !is_null($seat_data[0]->reserve_name) ){
                            $ticket_type = $seat_data[0]->reserve_name;
                        }
                    }
                   
                    switch($reservation_value->seat_status){
                        case 1:
                            $ticket_status = '仮預約';
                            break;
                        case 2:
                            $ticket_status = '處理中';
                            break;
                        case 3:
                            $ticket_status = '售出';
                            break;
                        default:
                            $ticket_status = '-';
                    }
                    
                    $lst_sn = sprintf("GL-%s-%s",$reservation_value->order_id, $reservation_value->reserve_seq);
                    $hmac = hash_hmac(hash_algos()[28],$lst_sn,$reservation_value->seat_sale_id);
            
                    $inf = ',';                                        //報名序號
                    $inf .= $lst_sn .',';                              //智林序號
                    $inf .= $hmac .',';                               //驗證序號
                    $inf .= $performance_data[0]->performance_name.',';//活動名稱
                    $inf .= $schedule_datatime.',';                    //場次
                    $inf .= $reservation_value->consumer_name.',';     //姓名
                    $inf .= $reservation_value->mail_address.',';      //Email
                    $inf .= $reservation_value->tel_num.',';           //手機
                    $inf .= $performance_data[0]->hall_disp_name.',';  //場地
                    $inf .= $value->performance_date.',';              //活動日期
                    $inf .= $seat_title.',';                           //座位
                    $inf .= $ticket_type.',';                          //票種
                    $inf .= $ticket_status.',';                        //票券付款狀態
                    $inf .= $reservation_value->issue_date.',';        //售出日期
                    $inf .= $reservation_value->sale_price.',';        //票面價格
                    $inf .= $performance_data[0]->disp_name.',';       //主辦單位
                    $inf .= ',';                                       //購買人
                    $inf .= ',';                                       //聯絡人
                    $inf .= ',';                                       //郵遞區號
                    $inf .= ',';                                       //地址
                    $inf .= $value->free_word.',';                     //備註

                    fputcsv($file, explode(',', $inf));
                }
            }

            $bar->finish();
        }

        // 關閉檔案
        fclose($file);
        $this->info(' complete' . "\n");
    }
}
