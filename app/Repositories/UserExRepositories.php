<?php

namespace App\Repositories;

use App\Models\UserExModel;
use Exception;
use Log;

class UserExRepositories
{
    protected $UserExModel;

    public function __construct(UserExModel $UserExModel)
    {
        $this->UserExModel = $UserExModel;
    }

    /*
     * 取得使用手續費資料
     * @param string $glid
     * @return collections UserExModel
     */
    public function getTransFee($glid){
        try{
            return $this->UserExModel->Parameter('TRANSFEE')
                        ->find($glid);

        }catch(Exception $e){
            Log::info('paynPickUpdateOrCreate :'.$e->getMessage());
            throw new RepositioriesException('1_001_0001');
        }
    }
 

}