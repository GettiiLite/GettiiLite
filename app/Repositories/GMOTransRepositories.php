<?php

namespace App\Repositories;

use App\Models\GMOTransModel;
use App\Models\GSSiteModel;
use Illuminate\Support\Facades\Hash;
use Exception;
use Log;
use App;

class GMOTransRepositories
{
    /** @var GMOTransModel */
    protected $GMOTransModel;

    /** @var GSSiteModel */
    protected $GSSiteModel;

    /**
     * @param GMOTransModel $GMOTransModel
     */
    public function __construct(GMOTransModel $GMOTransModel, GSSiteModel $GSSiteModel)
    {
        $this->GMOTransModel = $GMOTransModel;
        $this->GSSiteModel = $GSSiteModel;
    }

    public function getByOrderNum(string $orderNum) {
        $this->GMOTransModel = GMOTransModel::where('order_number',$orderNum)->firstOrFail();
    }

    public function updateData(Array $data) {
        $this->GMOTransModel->fill($data)->save();
    }
    public function getRetURL() {
        $this->GSSiteModel = GSSiteModel::findOrFail($this->GMOTransModel->SID);
        return $this->GSSiteModel->url_gs;
    }

    public function getByTransCode(string $transCode) {
        $this->GMOTransModel = GMOTransModel::where('trans_code',$transCode)->firstOrFail();
    }
    
    public function verifyTransCode(string $transCode) {
        if ($transCode == $this->GMOTransModel->trans_code)
            return true;
        return false;
    }
}
