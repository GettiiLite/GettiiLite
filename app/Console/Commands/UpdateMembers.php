<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\GettiisRepositories;
use App\Models\BatchModel;
use App\Models\MemberModel;
use Carbon\Carbon;
use Log;

class UpdateMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     *  Create by STS 2021/08/16 Task 45
     * 1. Get all user information from Gettiis.
     *     php artisan updateMembers 1 1
     * 2. Get recent user information.
     *     php artisan updateMembers 2 1
     * 3. Get user information by date. 
     *     php artisan updateMembers 3 1 2021-08-15
     */
    protected $signature = 'updateMembers {type} {sysKbn} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update member from Gettiis.Users to GettiiLite.GL_Members';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    /** @var  GettiisRepositories */
    protected $GettiisRepositories;

    /** @var MemberModel*/
    protected $MemberModel;
    /** @var BacthModel*/
    protected $BatchModel;

    public function __construct(GettiisRepositories $GettiisRepositories, MemberModel $MemberModel, BatchModel $BatchModel)
    {
        $this->GettiisRepositories      = $GettiisRepositories;
        $this->MemberModel              = $MemberModel;
        $this->BatchModel               = $BatchModel;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $type       = $this->argument('type');
        $date       = $this->argument('date');
        $sysKbn     = 1; //$this->argument('sysKbn');
        $processKbn = 1;
        $batch      = $this->GettiisRepositories->getBatch($sysKbn, $processKbn); 
        $nowDate    = Carbon::now(); 
        $targetDate = $this->GettiisRepositories->getMaxUpdatedAt(); //STS 2021/09/09
        Log::debug('Start the process of getting data from Gettiis, type: '.$type.' ; system kbn: '.$sysKbn);
        if(!$batch){
            $batch['system_kbn']    = $sysKbn;
            $batch['process_kbn']   = 1;
            $batch['status']        = 1;
            $batch['exec_time']     = '1990-01-01 00:00:00';
        }
        switch($type){
            case 1: 
                $search['start'] = '1990-01-01 00:00:00'; 
                $search['target'] = $targetDate; 
                $batch['exec_time'] = $targetDate; //STS 2021/09/09
                break;
            case 2: 
                $search['start'] = $batch['exec_time']; 
                $search['target'] = $targetDate; 
                $batch['exec_time'] = $targetDate; //STS 2021/09/09
                break;
            case 3: 
                $arg_time = date_parse_from_format("Y-m-d", $this->argument('date'));
                if ($arg_time['error_count'] !== 0) {
                    throw new \Exception('Please enter in the [YYYY-mm-dd] format.');
                };
                $batch['noChange_exec_time'] = true;
                $search['start'] = date("Y-m-d 00:00:00", strtotime($date)); 
                $search['target'] = date("Y-m-d 23:59:59", strtotime($date)); 
                break;
            default:
            throw new \Exception('Please enter style: 1 update all user, 2 recent updates, 3 updates by date');
            
        }
        $gsDataMembers = $this->GettiisRepositories->getUserGettiis($search);
        $updateMembers = $this->updateMemberInf($gsDataMembers);

        if(count($updateMembers['err']) > 0) Log::debug('An error occurred during processing members from gettiis');
        if(count($updateMembers['err']) > 0 && count($updateMembers['data']) <= 0) $batch['status'] = 0; 
        // $batch->exec_time = date("Y-m-d H:i:s", strtotime(Carbon::now()));
        $this->GettiisRepositories->updateBatch($batch);
        Log::debug('complete update GL_Members');
        
    }

    /**
     * Update member info
     * @param $dataMembers
     */
    public function updateMemberInf($dataMembers){
        $dataUpdated = [];
        $dataErr = [];
        foreach($dataMembers as $memberInf){   
            $memberInf['system_kbn']        = 1;
            $memberInf['update_account_cd'] = 1;
            $updateOrInser = $this->GettiisRepositories->updateOrInsertGLMembers($memberInf); 
            if($updateOrInser){
                $dataUpdated[$updateOrInser->ID] = $updateOrInser;
            }else{
                $dataErr[$dataMembers['ID']] = $dataMembers;
            }
        }
        return [
            'data'  => $dataUpdated,
            'err'   => $dataErr
        ];
    }
    
}
