<?php

namespace App\Presenters;

class EditListPresenter
{
    /**
    * 判斷活動狀態 class
    * @param string $status
    * @param string $sale_type
    * @return string
    */
    public function getStatusClass($status, $sale_type = 0)
    {
        $status_class = '';

        if($sale_type == 0 && $status > config('constant.performance_disp_status.sale')){
            $status =  config('constant.performance_disp_status.sale');
        }

        switch ($status) {
            case config('constant.performance_disp_status.going'):
                $status_class = 'status-off';
                break;
            case config('constant.performance_disp_status.complete'):
                $status_class = 'status-off';
                break;
            case config('constant.performance_disp_status.browse'):
                $status_class = 'status-off';
                break;
            case config('constant.performance_disp_status.public'):
                $status_class = 'status-on';
                break;
            case config('constant.performance_disp_status.sale'):
                $status_class = 'status-off';
                break;
            case config('constant.performance_disp_status.saling'):
                $status_class = 'status-on';
                break;
            case config('constant.performance_disp_status.ongoing'):
                $status_class = 'status-on';
                break;
            case config('constant.performance_disp_status.close'):
                $status_class = 'status-block';
                break;
            case config('constant.performance_disp_status.cancel'):
                $status_class = 'status-block';
                break;
            case config('constant.performance_disp_status.deleted'):
                $status_class = 'status-block';
                break;
            default:
                $status_class = '';
        }

        return $status_class;
    }
}