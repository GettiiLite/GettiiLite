<?php

namespace App\Presenters;

class CommonPresenter
{
    /**
    * 判斷活動狀態
    * @param string $status
    * @param string $sale_type
    * @return string
    */
    public function getStatusString($status, $sale_type = 0)
    {
        $status_string = '';

        switch ($status) {
            case config('constant.performance_disp_status.going'):
                $status_string = trans('common.S_StatusCode_0');
                break;
            case config('constant.performance_disp_status.complete'):
                $status_string = trans('common.S_StatusCode_1');
                break;
            case config('constant.performance_disp_status.browse'):
                $status_string = trans('common.S_StatusCode_2');
                break;
            case config('constant.performance_disp_status.public'):
                $status_string = trans('common.S_StatusCode_2_1');
                break;
            case config('constant.performance_disp_status.sale'):
                $status_string = trans('common.S_StatusCode_3');
                break;
            case config('constant.performance_disp_status.saling'):
                $status_string = trans('common.S_StatusCode_4');
                break;
            case config('constant.performance_disp_status.ongoing'):
                $status_string = trans('common.S_StatusCode_5');
                break;
            case config('constant.performance_disp_status.close'):
                $status_string = trans('common.S_StatusCode_6');
                break;
            case config('constant.performance_disp_status.cancel'):
                $status_string = trans('common.S_StatusCode_7');
                break;
            case config('constant.performance_disp_status.deleted'):
                $status_string = trans('common.S_StatusCode_8');
                break;
            default:
                $status_string = '-';
        }

        return $status_string;
    }
}