<?php

namespace App\Repositories;

use App\Models\MemberModel;
use App\Models\BatchModel;
use App\Models\GettiisUserModel;
use Log;
use DB;


/**
 * Class GettiisRepositories.
 * Create by STS 2021/08/16 Task 45
 */
class GettiisRepositories 
{
     /** @var MemberModel */
     protected $MemberModel;
     /** @var BatchModel */
     protected $BatchModel ;
     /** @var GettiisUserModel */
     protected $GettiisUserModel ;
    

    public function __construct(GettiisUserModel $GettiisUserModel,MemberModel $MemberModel, BatchModel $BatchModel )
    {
        $this->GettiisUserModel         = $GettiisUserModel;
        $this->MemberModel              = $MemberModel;
        $this->BatchModel               = $BatchModel;
       
    }



    /**
     * Get data from Gettiis db table Users
     * @param $search
     * @return $result->toArray()
     */
    
    public function getUserGettiis($search){
       
        try{
            $result = $this->GettiisUserModel 
                                            ->select(
                                                'id as ID',                   
                                                'user_id as member_id' ,
                                                'tel as tel_num',
                                                'email as mail_address',
                                                'email_status as allow_email',
                                                'status'
                                            )
                                            ->where('updated_at','>=',$search['start'])
                                            ->where('updated_at','<=',$search['target'])
                                            ->where(function($query) {
                                                    $query->Where('status', 3)
                                                    ->orWhere('status', 2);
                                            })->get();
            return $result->toArray();
        }catch(Exception $e){
            Log::info('getUserGettiis: '.$e->getMessage());
           
        } 
    }

    /**
     * Update or insert member to GL_Members
     * @param $memberInf
     * @return $result
     * //STS 2021/09/09 updated
     */
    public function updateOrInsertGLMembers($memberInf){
        try{
            $this->MemberModel->unguard();
            $result =  $this->MemberModel->updateOrCreate(
                [
                    // 'ID'                   => $memberInf['ID'], 
                    'member_id'            => $memberInf['member_id'],  

                ],
                [   
                    // 'member_id'            => $memberInf['member_id'],  
                    'tel_num'              => $memberInf['tel_num'],
                    'mail_address'         => $memberInf['mail_address'],
                    'allow_email'          => $memberInf['allow_email'],
                    'status'               => $memberInf['status'],
                    'update_account_cd'    => $memberInf['update_account_cd'],
                    'system_kbn'           => $memberInf['system_kbn'],
                ]);    
            return $result;
        }catch(Exception $e){
            Log::info('updateOrInsertGLMembers: '.$e->getMessage());
           
        } 
    }

     /**
     * Get data from GL_Batch
     * @param $system_kbn, $process_kbn
     * @return $result
     */

    public function getBatch($system_kbn, $process_kbn){
        try{
            $result = $this->BatchModel ->where('system_kbn', $system_kbn)
                                        ->where('process_kbn', $process_kbn)
                                        ->get();
            return isset($result->toArray()[0]) ? $result->toArray()[0] : [];
        }catch(Exception $e){
            Log::info('getBatch: '.$e->getMessage());
           
        } 
    }
    
    /**
     * Update to GL_Batch
     * @param $batchInf
     * @return $result
     * //STS 2021/09/09 updated
     */
    public function updateBatch($batchInf){
        try{
            $this->BatchModel->unguard();
            $result = $this->BatchModel->updateOrCreate(
                [
                    'system_kbn'    => $batchInf['system_kbn'],
                    'process_kbn'   => $batchInf['process_kbn'],
                    
                ],
                [
                    // 'exec_time'     => isset($batchInf['noChange_exec_time'])? $batchInf['exec_time'] : date("y/m/d H:i:s"),

                    'exec_time'     => $batchInf['exec_time'],
                    'status'        => $batchInf['status'],
                ]
            );
            
            return $result;
        }catch(Exception $e){
            Log::info('updateBatch: '.$e->getMessage());
        } 
    }
    /**
     * STS 2021/09/09 created
     * Get max updated date
     * @param startDate
     * @return $maxDate
     */
    public function getMaxUpdatedAt(){
        try{
            $result = $this->GettiisUserModel->Where('status', 3)
                                            ->orWhere('status', 2)
                                            ->max('updated_at');            
            return $result;
        }catch(Exception $e){
            Log::info('updateBatch: '.$e->getMessage());
        } 
    }
}
