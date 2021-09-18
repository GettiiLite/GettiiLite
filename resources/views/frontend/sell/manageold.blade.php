@inject('SaleManagePresenter', 'App\Presenters\SaleManagePresenter')
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
<!-- Content Header (Page header) -->
<!-- /.btn-group  -->
<h1>
    {{trans('sellManage.S_SubTitle_2')}}
    {{-- <small>{{trans('sellManage.S_SubTitle_2')}}</small> --}}
</h1>
<ol class="breadcrumb">
    <li><a href="/sell" onclick="loading.openLoading()">{{trans('sellManage.S_SubTitle_1')}}</a></li>
    <li class="active">{{trans('sellManage.S_SubTitle_2')}}</li>
</ol>

@stop

@section('content')
<!-- 0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div class="funtion-btn-block">
    <button type="button" class="btn waves-effect waves-light btn-rounded btn-inverse" onclick="createCsvFile()"> {{trans('sellManage.S_CsvButton')}} <!--<i class="fas fa-file-export"></i>--> </button>
</div>
<!-- /.0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div>
    <!-- box -->
    <div class="box box-solid">
        <!---box-header--->
        <div class="box-header with-border box-s1">
            <h3 class="box-title">
                @if (!$events['published'])
                <span class="badge status-badge bg-navy ml-15x">未公開</span>
                @endif
                {{ $events["performanceName"] }}
            </h3>
            {{-- <span class="pull-right"> <button type="button" class="btn btn-danger btn-sm">{{trans('sellManage.S_EventStopBtn')}}</button></span> --}}
            <p class="margin-fix">{{ $events["performanceNameSub"] }}</p>
            @if($events['published'])
                <div class="box-btn pull-right">
                    <a href="/schedule/list/{{ $events['performanceId'] }}/0" class="btn waves-effect waves-light btn-danger">中止管理</a>
                </div>
            @endif
        </div>
        <!---/.box-header--->
        <div class="box-body">
        <!--- table ----->
        <table id="example1" class="table table-striped">
            <thead>
            <tr>
                <th width="15">
                    @if($events['published'])
                    中 止
                    @endif
                </th>
                <th>{{ trans('sellManage.S_EventOpenDate') }} / {{ trans('sellManage.S_EventOpenTime') }}</th>
               <!-- <th>{{ trans('sellManage.S_EventOpenTime') }}</th>-->
                <th>{{ trans('sellManage.S_EventTimeSlot') }}</th>
                <th class="text-right">{{ trans('sellManage.S_EventSeatTotal') }}</th>
                <th class="text-right">{{ trans('sellManage.S_EventOnPorcessTotal') }}</th>
                <th class="text-right">{{ trans('sellManage.S_EventSellTotal') }}</th>
                <th class="text-right">{{ trans('sellManage.S_EventNoSellTotal') }}</th>
                {{-- <th>{{ trans('sellManage.S_EventSeatTotalOther') }}</th> --}}
                <th class="text-right">{{ trans('sellManage.S_EventRestOfSeat') }}</th>
                <th class="text-right">{{ trans('sellManage.S_EventMaxTotal') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($events["data"] as $event) 
                <tr>
                    <td>
                        @if($event['cancel_flg'] == 0 && $events['published'])
                        
                            <a href="/schedule/list/{{ $events['performanceId'] }}/{{ $event['scheduleId'] }}" class="btn btn-danger btn-stop"><i class="fas fa-calendar-times"></i></a>
                        @endif
                    </td>
                    <td>{{ $event['openDate'] }}　{{ $event['openTime'] }}
                        @if($event['cancel_flg'] !== 0)
                            <span class="badge bg-red">
                                {{ trans('sellManage.S_Status_Stopped') }}
                            </span>
                        @endif
                    </td>
                   <!-- <td>
                        {{ $event['openTime'] }}
                        @if($event['cancel_flg'] !== 0)
                            <span class="badge bg-red">
                                {{ trans('sellManage.S_Status_Stopped') }}
                            </span>
                        @endif
                    </td>-->
                    <td>{{ $event['timeTitle'] }}</td>
                    <td class="text-right">{{ $event['seatTotal'] }}</td>
                    <td class="text-right">{{ $event['onProcess'] }}</td>
                    <td class="text-right">{{ $event['seatSell'] }} </td>
                    <td class="text-right">{{ $event['seatReserve'] }}</td>                    
                    <td class="text-right">{{ $event['unSell'] }}</td>
                    <td class="text-right">{{ $event['seatSellPrice'] }}</td>
                    <td>
                        @if($events['published'])
                            <a href="/sell/seat/{{ $event['scheduleId'] }}" class="btn btn-info-outline btn-mm" onclick="loading.openLoading()">
                                {{ $SaleManagePresenter->getSeatSettingTitle($events['seatmap_profile_cd']) }}
                            </a>
                            @if($event['status'] >= 3)
                                <a href="/sell/detail/{{ $event['scheduleId'] }}" class="btn btn-info-outline btn-mm" onclick="loading.openLoading()">
                                    {{ trans('sellManage.S_EventDetail') }}
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="tfoot-light">
            <tr>
                <th class="text-center" colspan="3">{{ trans('sellManage.S_TableTotal') }}</th>
                <th class="text-right">{{ $events["totalAllSeat"] }}</th>
                <th class="text-right">{{ $events["totalOnProcess"] }}</th>
                <th class="text-right">{{ $events["totalSell"] }}</th>
                <th class="text-right">{{ $events["totalSeatReserve"] }}</th>
                <th class="text-right">{{ $events["totalUnSell"] }}</th>
                <th class="text-right">{{ $events["totalSellPrice"] }}</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        <!--  /. table--->
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<script>
function createCsvFile(){
    let date = + new Date();
    var fileName = date + ".csv";//匯出的檔名
    var data = "{{ $events["csv"] }}";
    var blob = new Blob(["\ufeff",data], {
        type : "text/csv,charset=UTF-8"
    });
    var href = URL.createObjectURL(blob);
    var link = document.createElement("a");
    document.body.appendChild(link);
    link.href = href;
    link.download = fileName;
    link.click();
}
</script>
@stop
