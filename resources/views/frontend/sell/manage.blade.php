@inject('SaleManagePresenter', 'App\Presenters\SaleManagePresenter')
{{ $SaleManagePresenter->constructPerfomationInf($events['performance_inf']) }}
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
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
<div id="app" class="content-navonly">
    <!-- 新增子選單 -->
    <ul id="" class="nav nav-tabs-sell">
        <li class="active">
            <a id="" onclick="loading.openLoading()" href="/sell"><span>{{ trans('sellManage.S_sellInfoTab_01') }}</span></a>
        </li>
        <li>
            <a id="" onclick="loading.openLoading()" href="/orders"><span>{{ trans('sellManage.S_sellInfoTab_02') }}</span></a>
        </li>
    </ul>
<div>
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
            <h3 class="box-title w-90 pr-x">
                @if (!$SaleManagePresenter->getPublished())
                <span class="badge status-badge bg-navy mr-15x">未公開</span>
                @endif
                {{ $events['performance_inf']["performance_name"] }}
            </h3>
            {{-- <span class="pull-right"> <button type="button" class="btn btn-danger btn-sm">{{trans('sellManage.S_EventStopBtn')}}</button></span> --}}
            <p class="margin-fix w-90">{{ $events['performance_inf']["performance_name_sub"] }}</p>
            @if($SaleManagePresenter->getPublished())
                <div class="box-btn pull-right">
                    <a href="/schedule/list/{{ $events['performance_inf']['performance_id'] }}/0" class="btn waves-effect waves-light btn-danger">中止管理</a>
                </div>
            @endif
        </div>
        <!---/.box-header--->
        <div class="box-body">
            <!--- table ----->
            <table id="example1" class="table table-striped">
            <thead>
                <th width="15">
                    @if($SaleManagePresenter->getPublished())
                    中 止
                    @endif
                </th>
                <th>{{ trans('sellManage.S_EventOpenDate') }} / {{ trans('sellManage.S_EventOpenTime') }}</th>
               <!-- <th>{{ trans('sellManage.S_EventOpenTime') }}</th>-->
                <th class="text-center">{{ trans('sellManage.S_EventTimeSlot') }}</th>
                <th class="text-right">{{trans('sellManage.S_EventDetailSeatName')}}</th> <!-- STS task 25 2020/06/24 -->
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
               
                <!-- STS task 25 2020/06/24 start -->
                <tbody>
                @foreach ($events["schedule_inf"] as $event) 
                 {{ $SaleManagePresenter->constructScheduleInf($event) }}{{$SaleManagePresenter->constructSeatData($event["seat_Data_First"])}}
                 <tr>
                        <td rowspan="{{ count($event['seat_Data'])+1 }}">
                            @if($SaleManagePresenter->getCancelBtn())
                               <a href="/schedule/list/{{ $events['performance_inf']['performance_id'] }}/{{ $event['schedule_id'] }}" class="btn btn-danger btn-stop"><i class="fas fa-calendar-times"></i></a>
                            @endif
                        </td>
                        <td rowspan="{{ count($event['seat_Data'])+1 }}">{{ $event['performance_date'] }} {{ $SaleManagePresenter->timeTransform($event['start_time'])}}
                            @if( $SaleManagePresenter->getCancelStatus())
                                <span class="badge bg-red">
                                    {{ trans('sellManage.S_Status_Stopped') }}
                                </span>
                            @endif
                        </td>
                        <td rowspan="{{ count($event['seat_Data'])+1 }}" class="ellipsis max-15"> 
                            {{ $SaleManagePresenter->getStageName() }}
                        </td>
                        <td class="text-right" style="border-top: 1px solid black;border-collapse: collapse;border-left: 1px solid #b7b7b7;border-collapse: collapse;"  >{{$event["seat_Data_First"]['seat_name']}}</td>
                        <td class="text-right" >{{$SaleManagePresenter->getSaleTotal()}}</td>
                        <td class="text-right" >{{$SaleManagePresenter->orderCol()}}</td>
                        <td class="text-right" >{{$SaleManagePresenter->isSellCol()}}</td>
                        <td class="text-right" >{{$SaleManagePresenter->resCol()}}</td>                    
                        <td class="text-right" >{{$SaleManagePresenter->noSellCol()}}</td>
                        <!-- STS 2021/08/02 Task 25-->
                        <td class="text-right __comma" style=" border-right: 1px solid #b7b7b7;border-collapse: collapse;" >{{$SaleManagePresenter->subtotalCol()}}</td>
                        <td rowspan="{{ count($event['seat_Data'])+1 }}">
                            @if($SaleManagePresenter->getPublished())
                                <a href="/sell/seat/{{ $event['schedule_id'] }}" class="btn btn-info-outline btn-mm" onclick="loading.openLoading()">
                                    {{ $SaleManagePresenter->getSeatSettingTitle() }}
                                </a>
                                @if($SaleManagePresenter->getDispStatus())
                                    <a href="/sell/detail/{{ $event['schedule_id'] }}" class="btn btn-info-outline btn-mm" onclick="loading.openLoading()">
                                        {{ trans('sellManage.S_EventDetail') }}
                                    </a>
                                @endif
                            @else 
                                @if($event['time_setting'] == 'normal')
                                    <a href="/sell/unpublished/seat/{{ $event['draft_id'] }}/{{ $event['date_value'] }}/{{ $event['rule_id'] }}" class="btn btn-info-outline btn-mm" onclick="loading.openLoading()">
                                        {{ $SaleManagePresenter->getSeatSettingTitle() }}
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @foreach ( $event["seat_Data"] as $event_defaut) 
                    {{$SaleManagePresenter->constructSeatData($event_defaut)}}
                    <tr>
                        <td class="text-right" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;  border-left: 1px solid #b7b7b7;border-collapse: collapse;" >{{ $event_defaut['seat_name']}}</td>
                        <td class="text-right" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;" >{{$SaleManagePresenter->getSaleTotal()}}</td>
                        <td class="text-right" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;" >{{$SaleManagePresenter->orderCol()}}</td>
                        <td class="text-right" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;" >{{$SaleManagePresenter->isSellCol()}}</td>
                        <td class="text-right" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;" >{{$SaleManagePresenter->resCol()}}</td>                    
                        <!--STS 2021/08/03 Task 25-->                 
                        <td class="text-right" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;">{{$SaleManagePresenter->noSellCol()}}</td>
                        <td class="text-right __comma" style="  border-top: 1px solid #b7b7b7;border-collapse: collapse;  border-right: 1px solid #b7b7b7;border-collapse: collapse;">{{$SaleManagePresenter->subtotalCol()}}</td>
                    </tr>
                    @endforeach
                    <tr style="  border-bottom: 2px solid #b7b7b7;border-collapse: collapse;"></tr>
                @endforeach
                </tbody> 
                <!-- STS task 25 2020/06/24 end -->

                <tfoot class="tfoot-light">
                <tr>
                    <!-- <th class="text-center" colspan="3">{{ trans('sellManage.S_TableTotal') }}</th> -->
                    <th class="text-center" colspan="4">{{ trans('sellManage.S_TableTotal') }}</th> <!-- STS task 25 -->

                    <th class="text-right __comma">{{ $SaleManagePresenter->AllseatTotal() }}</th>
                    <th class="text-right __comma">{{ $SaleManagePresenter->allInpayTotal() }}</th>
                    <th class="text-right __comma">{{ $SaleManagePresenter->allSellTotal() }}</th>
                    <th class="text-right __comma">{{ $SaleManagePresenter->resTotal() }}</th>
                    <th class="text-right __comma">{{ $SaleManagePresenter->allUnsellTotal() }}</th>
                    <th class="text-right __comma">{{ $SaleManagePresenter->allIncomeTotal() }}</th>
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
    let date = new Date();
    var fileName = String(date.getFullYear()) + String(date.getMonth() + 1) + String(date.getDate()) + "_ステージ一覧.csv";//匯出的檔名
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

if (!!window.performance && window.performance.navigation.type === 2) {
    //!! 用來檢查 window.performance 是否存在
    //window.performance.navigation.type ===2 表示使用 back or forward
    console.log('Reloading');
    window.location.reload();
}
</script>
@stop
