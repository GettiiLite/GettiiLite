<?php

namespace App\Console\Commands;

use Log;
use Illuminate\Console\Command;
use App\Services\SeatingHousekeepService;

class SeatingHousekeep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SeatingHK';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '仮予約削除バッチ';

    /** @var SeatingHousekeepService */
    protected $SeatingHousekeepService;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SeatingHousekeepService $SeatingHousekeepService)
    {
        parent::__construct();
        $this->SeatingHousekeepService = $SeatingHousekeepService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $this->info('SeatingHK start' . "\n");
        \Log::info('SeatingHK start');

        $arguments = $this->option();
        $ret = $this->SeatingHousekeepService->cleanExpiredTmpResv();

        $this->info('SeatingHK complete' . "\n");
        \Log::info('SeatingHK complete');
    }
}
