@inject('OrdersPresenter', 'App\Presenters\OrdersPresenter')
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
<h1>
    {{trans('sellManage.S_MainTitle')}}
</h1>
<ol class="breadcrumb">
    <li class="active">{{trans('sellManage.S_SubTitle_B')}}</li>
</ol>
@stop

@section('content')
<div id="app" class="content-navonly">
    <!-- 新增子選單 -->
    <ul id="" class="nav nav-tabs-sell">
        <li>
            <a id="" onclick="loading.openLoading()" href="sell"><span>{{ trans('sellManage.S_sellInfoTab_01') }}</span></a>
        </li>
        <li class="active">
            <a><span>{{ trans('sellManage.S_sellInfoTab_02') }}</span></a>
        </li>
    </ul>
    <!-- /.新增子選單 -->
    <div>
        <form id="search" method="POST" action="/orders">
            {{ csrf_field() }}
            <input type="hidden" name="filterJson" v-model="filterJson">
        </form>
        <form id="getCsv" method="GET" action="csv/orders">
            {{ csrf_field() }}
            <input type="hidden" name="filterJson" v-model="filterJson">
        </form>
        <div class="funtion-btn-block">
            <button type="button" v-on:click="csvEx()"  class="btn waves-effect waves-light btn-rounded btn-inverse">{{ trans('sellManage.S_CsvButton') }}</button>
        </div>   
        <!-- box - 檢索 -->
        <!--0511 調整樣式-->
        <div class="box no-border">
            <!---box-header--->
            <div class="box-header with-border-non" data-widget="collapse">
                <h3 class="box-title">{{ trans('sellManage.S_SearchTitle2') }}</h3>
                <!-- 收合 開關 -->
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.收合 開關 -->
            </div>
            <!---/.box-header  --->
            <div class="box-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('sellManage.S_Keyword') }}</label>
                            <div class="col-md-10">
                                <input name="keyword" type="text" placeholder="{{ trans('sellManage.S_PleaseInputKeyword') }}" class="form-control" v-model="filterItem.keyword">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ trans('sellManage.S_EventTitle') }}</label>
                                <input id="" class="" v-show="false" > 
                                <div class="col-md-10">
                                    <select id="performance-list"  :value="filterItem.performanceId" class="form-control select2 event" style="width: 100%;"> 
                                        <option value='-1'>{{ trans('sellManage.S_Performances') }}</option>
                                        @foreach ($events['performances'] as $performance)
                                            <option value='{{ $performance->performance_id }}'>{{ $performance->performance_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <!--與活動連動，選擇活動後才會出現-->
                            <div class="form-group">
                            <label class="col-md-2 control-label">{{ trans('sellManage.S_EventOpenTime') }}</label>
                                <input id="" class=""  v-show="false">
                                <div class="col-md-10">
                                    <select class="form-control select2 eventopentime" style="width: 100%;" v-model="filterItem.schedulesId">
                                        <option value='-1'>{{ trans('sellManage.S_Performances') }}</option>
                                        <option v-for="schedule in schedules" :value='schedule.schedule_id' :key="schedule.schedule_id">
                                            @{{ getScheduleText(schedule) }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!--//-->
                        <div class="form-group">
                            <label class="control-label  col-md-2">{{ trans('sellManage.S_EventDetailTableDate') }}</label>
                            <div class="col-md-10">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input name="date" type="text" class="form-control" id="daterange" v-model="orderRange" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" v-on:click="setDateClear()" class="btn waves-effect waves-light btn-inverse btn-flat">{{ trans('sellManage.S_BtnClear') }}</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('sellManage.S_Paytype') }}</label>
                            <div class="col-md-4 control-label-checkbox">
                                {{-- <label class="control control--checkbox">
                                    <input name="cash" type="checkbox" v-model="filterItem.payCash">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailCash') }}
                                </label> --}}
                                <label class="control control--checkbox">
                                    <input name="credit" type="checkbox"  v-model="filterItem.notPaymentMethod">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailNone') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input name="credit" type="checkbox"  v-model="filterItem.payCredit">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailCreditCard') }}
                                </label>
                                @if((\App::getLocale() == "zh-tw" ))
                                <label class="control control--checkbox">
                                    <input name="credit" type="checkbox"  v-model="filterItem.payIbon">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailIbon') }}
                                </label>
                                @endif
                                @if((\App::getLocale() == "ja" ))
                                <label class="control control--checkbox">
                                    <input name="credit" type="checkbox"  v-model="filterItem.paySevenEleven">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailConvenience') }}
                                </label>
                                @endif
                                <label class="control control--checkbox">
                                    <input name="credit" type="checkbox"  v-model="filterItem.payFree">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailFree') }}
                                </label>
                            </div>
                            <label class="control-label col-md-2">{{ trans('sellManage.S_Pickuptype') }}</label>
                            <div class="col-md-4 control-label-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.noTPickup">
                                    <div class="control__indicator"></div>
                                    無
                                </label>
                                <!--
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.pickup">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_Pickup_Mobapass') }}
                                </label>
                                -->
                                @if((\App::getLocale() == "zh-tw" ))
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.qrpass">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_Pickup_QrPass') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.ibon">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_Pickup_Ibon') }}
                                </label>
                                @endif
                                @if((\App::getLocale() == "ja" ))
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.sevenEleven">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_Pickup_SevenEleven') }}
                                </label>
                                @endif
                                {{-- <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.noPickup">
                                    <div class="control__indicator"></div>
                                    信用現金現
                                </label> --}}
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.resuq">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_Pickup_Resuq') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.noTicketing">
                                    <div class="control__indicator"></div>
                                        {{ trans('sellManage.S_EventDetailNoTicketing') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('sellManage.S_EventDetailTableBill') }}</label>
                            <div class="col-md-4 control-label-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.notReceipt">
                                    <div class="control__indicator"></div>
                                    無
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.receipt">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailHad') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.noReceipt">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailNoHad') }}
                                </label>
                            </div>
                            <label class="control-label col-md-2">{{ trans('sellManage.S_EventDetailTableTicketing') }}</label>
                            <div class="col-md-4 control-label-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.noTissue">
                                    <div class="control__indicator"></div>
                                    無
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.issue">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailGot') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.noIssue">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailNotGet') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('sellManage.S_Seattype') }}</label>
                            <div class="col-md-4 control-label-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.seatFree">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailFreeSeat') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.seatOrder">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailSelectSeat') }}
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.seatReserve">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_EventDetailRetainSeat') }}
                                </label>
                            </div>
                            <label class="control-label col-md-2">{{ trans('sellManage.S_OrderStatus') }}</label>
                            <div class="col-md-4 control-label-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.orderStatus.normal">
                                    <div class="control__indicator"></div>
                                    正常
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.orderStatus.cancel">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_CancelNotice09') }}
                                </label>
                                @if((\App::getLocale() != "ja" ))
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.orderStatus.systemCancel">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_CancelNotice08') }}
                                </label>
                                @endif
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="filterItem.orderStatus.timeoutCancel">
                                    <div class="control__indicator"></div>
                                    {{ trans('sellManage.S_CancelNotice07') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button v-on:click="search()" class="btn waves-effect waves-light btn-angle btn-info">{{ trans('sellManage.S_InquireButton') }}</button>
            </div>
        </div>
        <!-- modal-dialog -->
        <div class="modal-mask" v-show="dialog"  style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content" >
                    <div class="modal-header">
                        <h4 class="modal-title">
                            @{{ dialogTitle }}
                        </h4>
                    </div>
                    <div class="modal-body">
                        <!--訂單取消-->
                        <template v-if="dialogType == 1">
                            <div class="row form-horizontal" v-show="step == '1'">
                                <div class="col-md-12">
                                    <h4 class="">
                                        <i class="fas fa-check-double"></i>
                                        <!-- 信用卡請使用  您選擇取消訂單 1911-100031-70，請確認欲退款的信用卡資訊：-->
                                        {!! trans('sellManage.S_ReserveCancelNotice1', ['reserve_no' => '@{{ order["reserve_no"] }}']) !!}
                                    </h4>
                                    <h4 class="text-red" v-show="refundCheck">
                                        <i class="fas fa-exclamation-triangle"></i>  
                                        @{{ this.errorWarm }}
                                    </h4>
                                    <div class="flex-start i-memo-blue">
                                        {{ trans('sellManage.S_ReserveCancelNotice2') }}
                                        @{{ order['reserve_date'] }}
                                    </div>
                                    <div class="modal-overflow overflow-x-hidden">
                                        <template v-if="order['pay_method_trans']">
                                            <ul class="modal-list">
                                                <li>
                                                    <span class="badge bg-gray-light">
                                                        @{{ order['pay_method_trans'] }}
                                                    </span>  
                                                </li> 
                                            </ul>
                                        </template>
                                        <template v-else>
                                            <!-- 1 -->
                                            <div class="form-group form-group-flex">
                                                <label class="col-md-3 control-label">
                                                    <b>［ 必須 ］</b>
                                                    {{ trans('sellManage.S_RefundBankName') }}
                                                </label>
                                                <div class="col-md-3">
                                                    @if(\App::getLocale() == "ja" )
                                                        <input type="text" class="form-control" maxlength="4" placeholder="{{ trans('sellManage.S_RefundBankCode') }}" v-model="order['bank_inf'][0]['bankCode']">
                                                    @else
                                                        <input type="text" class="form-control" maxlength="3" placeholder="{{ trans('sellManage.S_RefundBankCode') }}" v-model="order['bank_inf'][0]['bankCode']">
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select id="evenType" name="evenType" class="form-control" aria-required="true" aria-invalid="false" @change="setBankName($event)" v-model="order['bank_inf'][0]['bankCode']">
                                                        <option value="" class="not-select" disabled hidden>{{ trans('sellManage.S_RefundBankSelect') }}</option> 
                                                        <option v-for="(data, index) in banksCode" v-bind:value="data[0]">@{{'('+data[0]+') ' + data[1]}}</option> 
                                                    </select> 
                                                </div>
                                            </div>
                                            <!-- /.1 -->  
                                            <!-- 2 -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">
                                                    @if(\App::getLocale() == "ja" )
                                                        <b>［ 必須 ］</b>
                                                    @endif
                                                    {{ trans('sellManage.S_RefundBankBranch') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"  placeholder="{{ trans('sellManage.S_RefundBankBranch') }}" v-model="order['bank_inf'][0]['branchName']">
                                                </div>
                                            </div>
                                            <!-- /.2 --> 
                                            <!-- 3 -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">
                                                    <b>［ 必須 ］</b>
                                                    {{ trans('sellManage.S_RefundAccountNumber') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"  placeholder="ex:00987437392" v-model="order['bank_inf'][0]['bankAccount']">
                                                </div>
                                            </div>
                                            <!-- /.3 -->   
                                        </template>
                                        <template v-if="order['refund_kbn']">
                                            <div class="form-group ml-60x">
                                                    @if(session()->get('full_refund'))
                                                        <div class="form-checkbox">
                                                            <label class="control control--radio">
                                                            <input type="radio" name="all" value="fullRefund" v-on:change="setRefundPayment" v-model="order['refund_type']">{{ trans('sellManage.S_FullRefund') }}
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="form-checkbox">
                                                            <label class="control control--radio">
                                                            <input type="radio" name="all" value="basisRefund" v-on:change="setRefundPayment" v-model="order['refund_type']">{{ trans('sellManage.S_PartialRefund') }}
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    @endif
                                                <label class="col-md-4 control-label">
                                                    <!--<b>［ 必須 ］</b>-->
                                                    {{ trans('sellManage.S_RefundAmount') }}
                                                </label>
                                                <div class="col-md-8">
                                                    <div class="from-style __comma">
                                                        @{{ order['refund_payment']['refund_payment_display'] }}
                                                    </div>
                                                    <span v-if="order['refund_text_display']" class="help-block text-blue text-right">
                                                        {{ trans('sellManage.S_ReserveCancelNotice4') }}
                                                    </span>
                                                    <span  v-if="order['fee_kbn']" class="help-block text-blue text-right">{{ trans('sellManage.S_ReserveCancelNotice4') }}</span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-horizontal" v-show="step == '2'">
                                <div class="col-md-12">
                                    <h4 class="text-red">
                                        <i class="fas fa-exclamation-triangle"></i>  
                                        {{ trans('sellManage.S_ReserveCancelNotice5') }}
                                    </h4>
                                    <div class="modal-overflow">
                                        <ul class="modal-list">
                                            <li>
                                                <span class="badge bg-gray-light">
                                                    {{ trans('sellManage.S_EventDetailTable') }}
                                                </span> 
                                                @{{ order['reserve_no'] }}
                                            </li> 
                                            <li v-if="order['bank_inf_kbn']">
                                                <span class="badge bg-gray-light">
                                                    {{ trans('sellManage.S_RefundAccount') }}
                                                </span> 
                                             
                                            </li> 
                                            <li v-if="order['refund_amount_kbn']">
                                                <span class="badge bg-gray-light">
                                                    {{ trans('sellManage.S_RefundAmount') }}
                                                </span> 
                                                <span class="__comma"> 
                                                    @{{ order['refund_payment']['refund_payment'] }} 
                                                </span>
                                            </li>   
                                            <li>
                                                <span class="badge bg-gray-light">
                                                    {{ trans('sellManage.S_EventTitle') }}
                                                </span> 
                                                @{{ order['perfomance_name'] }}
                                            </li>
                                            <li>
                                                <span class="badge bg-gray-light">
                                                    {{ trans('sellManage.S_EventOpenDate') }}
                                                </span> 
                                                @{{ order['schedule_date'] }}
                                            </li>
                                            <li v-for="seat in order['seat_inf']">
                                                <span class="badge bg-gray-light">
                                                    {{ trans('sellManage.S_EventDetailSeatName') }}/{{ trans('sellManage.S_EventDetailTicketName') }}
                                                </span> 
                                                @{{ seat['seat_class_name'] }} / @{{ seat['ticket_class_name'] }} 
                                            </li>
                                        </ul>      
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!--訂單取消-->
                        <!-- form  step 4 結果畫面 返回結果-->
                        <template v-if="step == 'e'">
                            <div class="row form-horizontal" v-show="resultStatus == '1'">
                                <div class="col-md-12">
                                    <h5 class="text-center">@{{ resultText }}</h5>
                                    <h4 class="text-center text-red"></h4>
                                </div>
                            </div>
                            <!-- form  step 4 結果畫面 返回結果-->
                            <!-- form  step 4 結果畫面 返回錯誤結果-->
                            <div class="row form-horizontal" v-show="resultStatus == '-1'">
                                <div class="col-md-12">
                                    <h3>
                                        <i class="fas fa-times-circle text-red"></i> 
                                        @{{ resultText }}
                                    </h3>
                                </div>
                            </div>
                        </template>
                        <!-- form  step 4 結果畫面 返回錯誤結果-->
                        <!-- form  step -1 loading 畫面-->
                        <template v-if="step == 'l'">
                            <div class="row form-horizontal">
                                <div class="col-md-12">
                                    <!-- form-group -->
                                    <div class="loader-gl">G
                                        <div class="inner c1"></div>
                                        <div class="inner c2"></div>
                                        <div class="inner c3"></div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                            </div>
                        </template>
                        <!-- form  step -1 loading 畫面-->
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default pull-left" v-show="step == '1' || order['hasCancelled']" v-on:click="resultClose()">
                            {{ trans('userManage.S_DialogCancel') }}
                        </button>
                        <!--訂單金額修改-->
                        <template v-if="dialogType == 1 && !order['hasCancelled']">   
                            <button class="btn btn-primary pull-right" v-show="step == '1'"  v-on:click="nextstep(2)">
                                {{ trans('sellManage.S_Next') }}
                            </button>
                            <button class="btn btn-default pull-left" v-show="step == '2' "  v-on:click="nextstep(1)">
                                {{ trans('sellManage.S_Back') }}
                            </button>
                            <button class="btn btn-danger pull-right" v-show="step == '2'"  v-on:click="postOrderCancelInf()">
                                {{ trans('sellManage.S_ReserveCancel') }}
                            </button>
                        </template>
                        <!--訂單金額修改-->
                        <button class="btn btn-inverse pull-left" v-show="step == 'e'" v-on:click="confirmClose()">
                            確認
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal-dialog -->
    </div>
    <!-- /.box - 檢索 -->
    @if($events['orders'])
        <!-- 查詢結果表格 -->
        <div class="box box-solid" >
            <div class="box-body" >
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-body">
                            <table class="table table-condensed tablesaw table-hover no-wrap tablesaw-sortable tablesaw-swipe" style="border-collapse:collapse;">
                                <thead>
                                    <tr>
                                        <th>{{ trans('sellManage.S_EventDetailTableAll') }}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTable') }}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTableDate')}}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTableId') }}</th>
                                        <th>{{ trans('sellManage.S_EventTitle') }}</th>
                                        <th>{{ trans('sellManage.S_EventOpenDate') }} / {{ trans('sellManage.S_EventOpenTime') }}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTableBill') }}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTableTicketing') }}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTableVisit') }}</th>
                                        <th>{{ trans('sellManage.S_EventDetailTableComplete') }}</th>
                                        <th class="text-right">{{ trans('sellManage.S_EventDetailTableNum') }}</th>
                                        <th class="text-right">{{ trans('sellManage.S_EventDetailTableReceivedTotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events['orders'] as $order)
                                        {{ $OrdersPresenter->constructOder($order) }}
                                        <tr class="editblocks">
                                            <td>
                                                <button class="showpageblock btn btn-default btn-xs">
                                                    <i class="fas fa-th-list"></i>
                                                </button>
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->basisFormat($order->reserve_no) }}
                                                <small class="subtitle">
                                                    {{ $OrdersPresenter->getOrderCancel() }}
                                                </small> 
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->basisFormat($order->reserve_date) }}
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->getMemberName($order->member_id) }}
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->getPerfomanceName() }}
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->getPerfomanceData() }}
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->paymentFlgFormat() }}
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->issueFlgFormat() }}
                                            </td>
                                            <td>
                                                {{ $OrdersPresenter->visitFlgFormat() }}
                                            </td>
                                            <td>
                                                -
                                            </td>
                                            <td class="text-right">
                                                {{ $OrdersPresenter->ticketTotal() }}
                                            </td>
                                            <td class="text-right __comma">
                                                {{ $OrdersPresenter->receivedAmount() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="16" class="hiddenRow">
                                                <div class="togglesettings">
                                                    <!-- 新增會員資料 -->
                                                <div class="flex-around--start">
                                                    <div class="col-sm-offset-1 col-sm-9 flex-around order-member-box">
                                                        <div class="col col-auto order-member-list">
                                                            <p class="font-5">
                                                                {{ trans('sellManage.S_EventDetailTableName') }}
                                                            </p>
                                                            <p class="ellipsis max-20">
                                                                <b>
                                                                    {{ $OrdersPresenter->getConsumerName($order->consumer_name) }}
                                                                </b>
                                                            </p>
                                                        </div>
                                                        <div class="order__line"></div>
                                                        <div class="col col-auto order-member-list">
                                                            <p class="font-5">
                                                                {{ trans('sellManage.S_EventDetailTableNameKatakana') }}
                                                            </p>
                                                            <p class="ellipsis max-20">
                                                                <b>
                                                                    {{ $OrdersPresenter->basisFormat($order->consumer_kana) }}
                                                                </b>
                                                            </p>
                                                        </div>
                                                        <div class="order__line"></div>
                                                        <div class="col col-auto order-member-list">
                                                            <p class="font-5">{{ trans('sellManage.S_EventDetailTableTel') }}</p>
                                                            <p>
                                                                <b>
                                                                    {{ $OrdersPresenter->getTel($order->tel_num) }}
                                                                </b>
                                                            </p>
                                                        </div>
                                                        <div class="order__line"></div>
                                                        <div class="col col-auto order-member-list">
                                                            <p class="font-5">{{ trans('sellManage.S_EventDetailTableMail') }}</p>
                                                            <p>
                                                                <b>
                                                                    {{ $OrdersPresenter->getMail($order->mail_address) }}
                                                                </b>
                                                            </p>
                                                        </div>
                                                        <div class="order__line"></div>
                                                        <div class="col col-auto order-member-list">
                                                            <p class="font-5">{{ trans('sellManage.S_EventDetailTablePay') }}</p>
                                                            <p>
                                                                <b>
                                                                    {{ $OrdersPresenter->payMethodFormat() }}
                                                                </b>
                                                            </p>
                                                        </div>
                                                        <div class="order__line"></div>
                                                        <div class="col col-auto order-member-list">
                                                            <p class="font-5">{{ trans('sellManage.S_EventDetailTableGet') }}</p>
                                                            <p>
                                                                <b>
                                                                {{ $OrdersPresenter->pickupMethodFormat() }}
                                                                </b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!-- 按鈕區 --> 
                                                    @if($OrdersPresenter->getCancelFlg())
                                                        <button class="btn btn-danger pull-right margin-10"  v-on:click="openOrderCancel('{{ $order['reserve_no'] }}', 1)">
                                                            {{ trans('sellManage.S_ReserveCancel') }}
                                                        </button>
                                                    @endif
                                                    @if($OrdersPresenter->getCancelInfFlg())
                                                        <button class="btn btn-danger pull-right margin-10"  v-on:click="openOrderCancel('{{ $order['reserve_no'] }}', 2)">
                                                            {{ trans('sellManage.S_ReserveCanceled') }}
                                                        </button>
                                                    @endif
                                                    <!-- /.按鈕區 -->
                                                </div>
                                                    <!-- /.新增會員資料 -->
                                                    
                                                    <table class="table color-bordered-table success-bordered-table col-sm-offset-1 col-sm-9" style="width: 91%;">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>入場</th>
                                                                <th>{{ trans('sellManage.S_EventDetailSeatName') }}</th>
                                                                <th>{{ trans('sellManage.S_EventDetailTicketName') }}</th>
                                                                <th>{{ trans('sellManage.S_EventDetailSeatType') }}</th>
                                                                <th>{{ trans('sellManage.S_EventDetailSeatPosition') }} / {{ trans('sellManage.S_EventDetailReferencenNumber') }}</th>
                                                                <th class="text-right" style="width: 160px;">{{ trans('sellManage.S_EventDetailTableTP') }}</th>
                                                                <th style="width: 150px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($OrdersPresenter->SEAT_SALE as $ticket)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $OrdersPresenter->ticketVisitFlgFormat($ticket) }}</td>
                                                                    <td>{{ $ticket->seat_class_name }}</td>
                                                                    <td>{{ $ticket->ticket_class_name }}</td>
                                                                    <td>{{ $OrdersPresenter->seatClassFormat($ticket) }}</td>
                                                                    <td>{{ $OrdersPresenter->seatPositionFormat($ticket) }}</td>
                                                                    <td class="text-right" style="width: 160px;">{{ $OrdersPresenter->ticketprice($ticket) }}</td>
                                                                    <td>{!! $OrdersPresenter->getVisit($ticket) !!}</td>
                                                                </tr>   
                                                            @endforeach 
                                                        </tbody>
                                                        <!-- 新增 合計金額 -->
                                                        <tfoot class="tfoot-light">
                                                            <tr>
                                                                <th colspan="6" class="text-center">{{ trans('sellManage.S_EventDetailTableTotal') }}</th>  
                                                                <th class="text-right __comma">{{ $OrdersPresenter->salePriceTotal() }}</th>
                                                                <th></th> 
                                                            </tr>
                                                        </tfoot>
                                                        <!-- /.新增 合計金額 -->
                                                    </table>
                                                    <!-- 0729免費問券 新增-->
                                                    @if($OrdersPresenter->hadQuestion())
                                                        <table class="table color-bordered-table success-bordered-table col-sm-offset-1 col-sm-9" style="width: 91%;">
                                                            <thead>
                                                                <tr>
                                                                    <th width="20%">{{ trans('sellManage.S_EventDetailQuestionTitle') }}</th> 
                                                                    <th width="30%">{{ trans('sellManage.S_EventDetailQuestionText') }}</th>
                                                                    <th width="50%">{{ trans('sellManage.S_EventDetailAnswerText') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {!! $OrdersPresenter->getQuestion() !!}
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                    <!--//-->
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- 查詢結果表格 -->
        {{ $events['orders']->appends(['filterJson' => json_encode($events['filter_json'])])->links() }}
    @endif
    <!-- 0412新增 入場按鈕跳出pop確認是否執行 -->
    <div id="pop-up-admission">
        <div class="modal-mask" v-show="popResult['show']" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content" >
                    <div class="modal-header">    
                        <h4 class="modal-title">
                            <i class="fas fa-exclamation-circle"></i>
                            入場状態変更
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-horizontal">
                            <div class="col-md-12">                         
                                <template  v-if="popResult['step'] == 1">
                                    <h4 >
                                        {{ trans('sellManage.S_AdmissionMessage') }}
                                    </h4>
                                </template>
                                <template  v-if="popResult['step'] == 2">
                                    <div class="text-center">
                                        <svg version="1.1" id="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;"
                                            xml:space="preserve">
                                            <path fill="#33bbeb"
                                            d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                                            <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                                dur="0.6s" repeatCount="indefinite" />
                                            </path>
                                        </svg>
                                    </div>
                                </template>
                                <template  v-if="popResult['step'] == 3">
                                    <div class="messages-content messages-save-content-pop">
                                        <p class="lead text-left">
                                            @{{ popResult['message'] }}
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" > 
                        <template  v-if="popResult['step'] == 1">
                            <button class="btn btn-default pull-left"  v-on:click="closePopResult()">
                                {{ trans('sellManage.S_AdmissionCloseBtn') }}
                            </button>
                            <button class="btn btn-danger pull-right"  v-on:click="patchOrderCancelInf()">
                                    {{ trans('sellManage.S_AdmissionBtn') }}
                            </button>
                        </template>
                        <template  v-if="popResult['step'] == 3">
                            <button class="btn btn-default pull-left"  v-on:click="closePopResult(3)">
                                {{ trans('sellManage.S_AdmissionCancelCloseBtn') }}
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script> 
    const accountCd = '{{ session('account_cd') }}'
    const banksCode = {!! (\App::getLocale() == "ja" )?json_encode(\Config::get('bankcode.jp')):json_encode(\Config::get('bankcode.tw')) !!}
 
    var app = new Vue({
        el: '#app',
        data:{
            filterJson: [],
            filterItem: [],
            filterStatus: [],
            ordersInf: [],
            order: [],
            banksCode: banksCode,
            orderRange: '',
            step: 0,
            dialog: false,
            dialogType: '',
            dialogTitle: '',
            resultStatus: 0, // 0 : 無 | 1 ： 成功 | -1 ： 失敗
            resultText: '',
            bankinf: [],
            refundCheck: false,
            errorWarm: '',
            popResult:[],
            seatSaleId: '',
            visitStatus: '',
            perfomances: @json($events['performances']),
            schedules: []
        },
        mounted(){
            let filterJson = @json($events['filter_json']);

            @if($events['orders'])
                this.ordersInf = @json($events['orders']->toArray())
            @endif

            this.popResult = {
                'show' : false,
                'step' : 0,
                'message' : '',
            }

            if(filterJson){
                this.filterJson = filterJson
                this.filterStatus = filterJson.status
                this.filterItem = filterJson.inf

                this.setSchedules(this.filterItem.performanceId, this.filterItem.schedulesId)
            }else{
                this.filterStatus = {
                    'filter': true,
                }
                
                this.filterItem = {
                    'performanceId': -1,
                    'schedulesId': -1,
                    'keyword': '',
                    'dateRangeStar': null,
                    'dateRangeEnd': null,
                    'notPaymentMethod': true,
                    'payCash': true,
                    'payCredit':true,
                    'payIbon': true,
                    'paySevenEleven': true,
                    'payFree': true,
                    'noTPickup': true,
                    'pickup': true,
                    'qrpass': true,
                    'ibon':true,
                    'sevenEleven': true,
                    'resuq': true,
                    'noTicketing': true,
                    'noTissue':true,
                    'issue': true,
                    'noIssue': true,
                    'notReceipt': true,
                    'receipt': true,
                    'noReceipt': true,
                    'seatFree': true,
                    'seatOrder': true,
                    'seatReserve': true,
                    'orderStatus': {
                        'normal': true,
                        'cancel': true,
                        'timeoutCancel': true,
                        'systemCancel': true,
                    },
                }
            }

            this.filterJson = JSON.stringify({
                'status': this.filterStatus,
                'inf': this.filterItem,
            })
        },
        watch:{

        },
        methods:{
            getScheduleText: function(schedule){
                let result = '-'

                if(schedule){
                    result = `${schedule.disp_performance_date} ${schedule.performance_date}  ${schedule.start_time}`
                }

                return result
            },
            setSchedules: function(performanceId = this.filterItem.performanceId, scheduleId = -1){
                this.schedules = []

                if(performanceId > 0){
                    let perfomance = this.perfomances.find(function(item, index, array){
                        return item['performance_id'] == performanceId 
                    })

                    this.schedules = perfomance.schedule
                }
                
                this.filterItem.schedulesId = scheduleId
            },
            csvEx:function(){
                document.getElementById("getCsv").submit();
            },
            /**
            設定入場狀態修改 pop 開啟
            @param int seatSaleId
            @param bool visitStatus
             */
            openPopResult:function(seatSaleId, visitStatus){
                this.seatSaleId = seatSaleId
                this.visitStatus = visitStatus
                this.popResult['step'] = 1
                this.popResult['show'] = true
            },
            /**
            關閉入場狀態修改 pop
             */
            closePopResult:function(step = 0){
                if(step == 3){
                    location.reload()
                }else{
                    this.popResult['show'] = false
                }
            },
           /**
            設定入場狀態修改回傳信息
            @param object data
             */
            setPopResult:function(data){
                this.popResult['step'] = 3
                this.popResult['message'] = data.message
            },
            /**
            修改入場狀態
            @param int seat_sale_id
             */
            patchOrderCancelInf:function(){
                this.popResult['step'] = 2
                
                let url = `/api/seatSale/${this.seatSaleId}`

                let refund = {
                    'visit_flg': this.visitStatus
                }
               
                let params = JSON.stringify(refund)
              
                fetch(url, {
                    method:'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    cache: 'no-cache',
                    body: params
                }).then(res => {
                    
                    if (res.status >= 200 && res.status < 300) {
                        return res.json()
                    } else {
                        throw new Error(res.statusText);
                    }
                }).then(result => {
                    app.setPopResult(result)
                }).catch((err) => {
                    this.popResult['step'] = 3 //STS 2021/09/10 Task 48 No.2
                    this.popResult['message'] = '{{ trans('sellManage.S_AdmissionMessageFail') }}'//STS 2021/09/10 Task 48 No.2
                    console.log('錯誤:', err);
                });
               
            },
            /**
            * 訂單取消資料檢查
            */
            checkRefundData: function(){ 
                var re          = /^\d+(\.\d{1,2})?$/ 
                let reNum       =/^[0-9]*$/
                let refundCheck = false
                let order = this.order
                let refundPayment = order['refund_payment']

                try {
                    if(
                        refundPayment['refund_payment'] == "" && 
                        order['pay_method'] != '{{ \Config::get('constant.pay_method.free') }}' &&
                        order.seats[0].payment_flg
                    ){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice01') }}'));
                    }
                    
                    if(!reNum.test(refundPayment['refund_payment'])){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice02') }}'));
                    }

                    if(
                        refundPayment['refund_payment'] < 0 &&
                        order['pay_method'] != '{{ \Config::get('constant.pay_method.free') }}' &&
                        order.seats[0].payment_flg
                    ){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice03') }}'));
                    }

                    if(parseInt(refundPayment['refund_payment']) > refundPayment['refund_payment_limit']){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice04') }}'));
                    }

                    if(
                        order['pay_method'] != '{{ \Config::get('constant.pay_method.card') }}' && 
                        order['pay_method'] != '{{ \Config::get('constant.pay_method.store') }}' && 
                        order['pay_method'] != '{{ \Config::get('constant.pay_method.free') }}'
                      ){
                        if(order['bank_inf'][0]['bankCode'] == ""){
                            throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                        }else{
                            if(!reNum.test(order['bank_inf'][0]['bankCode'])){
                                throw (new Error('{{ trans('sellManage.S_CancelNotice06') }}'));
                            }
                        }
                        if(order['bank_inf'][0]['bankName'] == ""){
                            throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                        }
                        @if(\App::getLocale() == "ja" )
                            if(order['bank_inf'][0]['branchName'] == ""){
                                throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                            }
                        @endif

                        if(order['bank_inf'][0]['bankAccount'] == ""){
                            throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                        }else{
                            if(!reNum.test(order['bank_inf'][0]['bankAccount'])){
                                throw (new Error('{{ trans('sellManage.S_CancelNotice06') }}'));
                            }
                        }
                    }

                    this.refundCheck = refundCheck

                    return true
                }catch(e){
                    this.errorWarm   = e.message;
                    this.refundCheck = true

                    return false
                }
            },
            /**
            * 設定銀行名稱
            * @param ele event
            */
            setBankName:function(event){
                let id        = parseInt(event.target.value)
                let bankinf   = this.banksCode[id]

                this.order['bank_inf'][0]['bankName'] = bankinf[1]
            },
            /**
            * 整理查詢條件並送出
            */
            search: function(){
                let n = {
                    'status': this.filterStatus,
                    'inf': this.filterItem,
                }
                this.filterJson = JSON.stringify(n)
                this.$nextTick(() => {
                    document.getElementById("search").submit()
                })
            },
            /**
            * 時間欄位清空
            */
            setDateClear:function(){
                this.filterItem['dateRangeStar'] = ''
                this.filterItem['dateRangeEnd'] = ''
                this.orderRange = ''
            },
            /**
            * 取得席位金額
            *@param array seat
            *@return int 
            */
            getSeatPrice:function(seat){
                return parseInt(seat.sale_price) +  parseInt(seat.commission_sv) +  parseInt(seat.commission_payment) +  parseInt(seat.commission_ticket) +  parseInt(seat.commission_delivery) +  parseInt(seat.commission_sub) +  parseInt(seat.commission_uc) 
            },
            /**
            * 取得訂單可以取消總金額
            *@param array order
            *@return array refund
            */
            getTotalPrice:function(seatSale){
                let totalPrice = 0

                seatSale.forEach(item => {
                    let seatPrice =  
                    totalPrice += this.getSeatPrice(item)
                });
                
                return totalPrice
            },
            /**
            * 取得訂單可以取消金額
            *@param array order
            *@return array refund
            */
            getRefundPayment:function(order){
                let refund = new Array()
                let amountRevise = order['amount_revise']
                let seatSale = order['seat_sale']
                let payMethod = order['pay_method']
                let pickupMethod = order['pickup_method']
                let totalPrice = this.getTotalPrice(seatSale)
                let orderCommission = parseInt(order.commission_sv) +  parseInt(order.commission_payment) +  parseInt(order.commission_ticket) +  parseInt(order.commission_delivery) +  parseInt(order.commission_sub) +  parseInt(order.commission_uc)
                let totalOrderPrice = totalPrice + orderCommission
                let refundPayment = 0
                let refundPaymentLimit = 0

                if(seatSale[0].payment_flg){
                    if(amountRevise){
                            refundPayment =  parseInt(amountRevise['amount_total'])
                            refundPaymentLimit =  parseInt(amountRevise['amount_total'])
                    }else{
                        if(payMethod === 2 && pickupMethod === 3){ //card/seven
                            refundPayment  = totalPrice + parseInt(order['commission_ticket']);
                        }else if(
                            (payMethod === 3 && pickupMethod == '{{ \Config::get('constant.pickup_method.store') }}') ||
                            (payMethod === 3 && pickupMethod == '{{ \Config::get('constant.pickup_method.resuq') }}') ||
                            (payMethod === 3 && pickupMethod == '{{ \Config::get('constant.pickup_method.no_ticketing') }}') 
                        ){//seven/seven
                            refundPayment  = totalOrderPrice
                        }else{//card/resQ card/mbps
                            refundPayment  = totalPrice              
                        }
                        refundPaymentLimit = totalOrderPrice
                    }
                }

                refund = {
                    'full_payment' : totalOrderPrice,
                    'part_payment' : refundPayment,
                    'refund_payment': refundPayment,
                    'refund_payment_display': this.getThousands(refundPayment),
                    'refund_payment_limit': refundPaymentLimit
                }

                return refund
            },
            getRefundTextDisplay: function (payMethod, pickupMethod) {
                let result = false

                if(payMethod === 2 && pickupMethod === 3){
                    result = true
                }else if(payMethod === 20){
                    result = false
                }else if(
                    (payMethod === 3 && pickupMethod == '{{ \Config::get('constant.pickup_method.store') }}') ||
                    (payMethod === 3 && pickupMethod == '{{ \Config::get('constant.pickup_method.resuq') }}') ||
                    (payMethod === 3 && pickupMethod == '{{ \Config::get('constant.pickup_method.no_ticketing') }}') 
                ){
                    result = false
                }else{
                    result = false               
                }

                return result
            },
            /**
            * 取席位位子
            *@param array seats
            *@return array seatInf
            */
            getSeatInf:function(seats){
                let seatInf = new Array()
                
                seats.forEach(item => {
                    let seat = item['seat']
                    let seatPosition  = ''
                    let seatPrice = this.getSeatPrice(item)

                    if(seat){
                        let hallSeat = seat['hall_seat']
                        let block = hallSeat['block']
                        let floor = hallSeat['floor']
                        seatPosition = `${floor['floor_name']}-${block['block_name']}-${hallSeat['seat_cols']}-${hallSeat['seat_number']}`
                    }

                    seatInf.push({
                        'seat_position': seatPosition,
                        'seat_sale_id': item['seat_sale_id'],
                        'ori_price': seatPrice,
                        'seat_price': seatPrice,
                        'seat_class_name': item['seat_class_name'],
                        'ticket_class_name':  item['ticket_class_name']
                    })
                });

                return seatInf
            },
            /**
            * 取得活動日期時間
            *@param array schedule
            *@return string scheduleDate
            */
            getScheduleDate:function(schedule){
                let scheduleDate = ''
                let dateTime = `${schedule['performance_date']} ${schedule['start_time']}`
                let dateTrans = new Date(dateTime)
                let year = dateTrans.getFullYear()
                let month = ('0' + (dateTrans.getMonth() + 1)).substr(-2)
                let date = ('0' + dateTrans.getDate()).substr(-2)
                let hours = ('0' + dateTrans.getHours()).substr(-2)
                let minutes = ('0' + dateTrans.getMinutes()).substr(-2)
                
                scheduleDate = `${year}-${month}-${date} ${hours}:${minutes}`

                return scheduleDate
            },
            /**
            * 取得銀行賬戶資料
            *@return array bankinf
            */
            getBankInf:function(){
                let bankInf = new Array()

                bankInf.push({
                    'bankCode': '',
                    'bankName': '',
                    'branchName': '',
                    'bankAccount': '',
                }) 
                
                return bankInf
            },
            /**
            * 訂單取消 Dialog 開起
            */
            openOrderCancel:function(reserveNo, step){
                this.dialogTitle = '{{ trans('sellManage.S_ReserveCancel') }}'
                this.refundCheck = false
                this.errorWarm = ''

                let orders = this.ordersInf.data
                let payMethod = ''
                let pickupMethod = ''
                let bankInfKbn = true
                let refundAmountKbn = true
                let refundKbn = true
                let feeKbn = false
                let hasCancelled = false

                let order = orders.find(function(item, index, array){
                    return item['reserve_no'] == reserveNo 
                })
                
                let schedule = order['seat_sale'][0]['schedule']
                let performance = schedule['performance']
                let refundPayment = this.getRefundPayment(order)
                let seatInf = this.getSeatInf(order['seat_sale'])
                let scheduleDate = this.getScheduleDate(schedule)
                let bankInf = this.getBankInf()
                payMethod = order['pay_method']
                pickupMethod = order['pickup_method']
                let refundTextDisplay = this.getRefundTextDisplay(payMethod, pickupMethod)

                switch(payMethod){
                    case {{ \Config::get("constant.pay_method.free") }}:
                        payMethodTrans = "{{ trans('sellManage.S_EventDetailFree') }}"
                        refundKbn = false
                        bankInfKbn = false
                        refundAmountKbn = false
                        feeKbn = false
                        break;
                    case {{ \Config::get("constant.pay_method.card") }}:
                        payMethodTrans = "{{ trans('sellManage.S_RefundCard') }}"
                        bankInfKbn = false
                        break;
                    case {{ \Config::get("constant.pay_method.store") }}:
                        payMethodTrans = "{{ trans('sellManage.S_RefundSEJ') }}"
                        refundKbn = false
                        bankInfKbn = false
                        refundAmountKbn = false
                        feeKbn = true
                        break;
                    case {{ \Config::get("constant.pay_method.free") }}:
                        payMethodTrans = '{{ trans("sellManage.S_EventDetailFree") }}'
                        bankInfKbn = false
                        break;
                    default:
                        payMethodTrans = false
                    
                }
               
                if(step == 2){
                    hasCancelled = true
                }

                if(order){
                    this.order = {
                        'hasCancelled': hasCancelled,
                        'order_id': order['order_id'],
                        'reserve_no': order['reserve_no'],
                        'perfomance_name': performance['performance_name'],
                        'schedule_date': scheduleDate,
                        'reserve_date': order['reserve_date'],
                        'pay_method': payMethod,
                        'pay_method_trans': payMethodTrans,
                        'pickup_method': pickupMethod,
                        'bank_inf_kbn': bankInfKbn,
                        'refund_kbn': refundKbn,
                        'fee_kbn': feeKbn,
                        'refund_amount_kbn': refundAmountKbn,
                        'seats': order['seat_sale'],
                        'seat_inf': seatInf, 
                        'refund_type' : 'basisRefund',
                        'refund_text_display' : refundTextDisplay,
                        'refund_payment': refundPayment,
                        'bank_inf': bankInf,
                        'use_point': order['use_point']
                    }
                }  
               
                this.step = step
                this.dialogType = 1
                this.dialog = true
            },
            /**
            * 千分位 
            */
            getThousands : function (input) {
                return input.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
            },
            /**
            * 設定退款金額
            */
            setRefundPayment : function () {
                let payMethod = this.order['pay_method']
                let pickupMethod = this.order['pickup_method']

                if(this.order['refund_type'] == 'fullRefund'){
                    this.order['refund_payment']['refund_payment'] = this.order['refund_payment']['full_payment']
                    this.order['refund_payment']['refund_payment_display'] = this.getThousands(this.order['refund_payment']['full_payment'])
                    this.order['refund_text_display'] = true
                }else{
                    this.order['refund_payment']['refund_payment'] = this.order['refund_payment']['part_payment']
                    this.order['refund_payment']['refund_payment_display'] = this.getThousands(this.order['refund_payment']['part_payment'])
                    this.order['refund_text_display'] = this.getRefundTextDisplay(payMethod, pickupMethod)
                }

            },
            /**
            * 下一步
            */
            nextstep:function(step){
                if(this.checkRefundData()){
                    this.step = step
                }
            },
            /**
            * 關閉 Dialog 
            */
            resultClose:function(){
                this.dialog = false
            },
            /**
            * 關閉 Dialog 
            */
            confirmClose:function(){
                location.reload();
            },
            /**
             * 訂單修改金額資料送出
             */
            postOrderCancelInf:function(){
                this.nextstep('l')

                let formData = new FormData()
                let params = ''
                let url = 'api/cancelOrder'
                let data = new Array()
                let inf = new Array()
                let status = new Array()
                let orderId =  this.order['order_id']
                let seatInf = this.order['seat_inf']
                let bankInf = this.order['bank_inf']
                let refundPayment = this.order['refund_payment']
                let usePoint = this.order['use_point']
                let payMethod = this.order['pay_method']
              
                inf.push({
                    'orderId'      : orderId, 
                    'refund_kbn'   : payMethod,
                    'refundPayment': refundPayment['refund_payment'],
                    'use_point'    : usePoint,
                    'bankinf'      : bankInf,
                })

                data.push({
                    'status'    : status,
                    'accountCd' : accountCd,
                    'inf'       : inf,
                })

                params = JSON.stringify(data)
                formData.append("json", params);
              
                fetch(url, {
                    method:'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: formData
                }).then(res => {
                    console.log(res.status)
                    if (res.status >= 200 && res.status < 300) {
                        return res.json()
                    } else {
                        console.log(res.statusText)
                        throw new Error(res.statusText);
                    }
                }).then(result => {
                    app.nextstep('e')

                    app.resultText = result.msn

                    if(result.status){
                        app.resultStatus =  1
                    }else{
                        app.resultStatus =  -1
                    }
                }).catch((err) => {
                    m = 12
                    app.nextstep('e')
                    app.resultStatus =  -1
                    app.resultText = err.message
                    console.log('錯誤:', err);
                });
            },
        }
    })
    
    //訂單詳細展開&收起功能
    $(document).ready(function () {
        $('.togglesettings').hide();
        $('.showpageblock').on('click', function () {
            var $t = $(this).closest('.editblocks').next().find('.togglesettings').stop(true).slideToggle();
            return false;
        });

        $("#performance-list").select2({

        }).on('select2:closing', function( event ) {
            if(typeof event.params.args.originalSelect2Event !== 'undefined') {
                let data = event.params.args.originalSelect2Event.data
                app.filterItem.performanceId = data.id
                app.setSchedules()
            }
        })
    });
    
    $(function () {
        /**
        * 時間區間欄位初始化
        */
        if(app.filterItem['dateRangeStar'] && app.filterItem['dateRangeEnd']){
            app.orderRange = app.filterItem['dateRangeStar'] + '~' + app.filterItem['dateRangeEnd']
            let dateRangeStar = new Date(app.filterItem['dateRangeStar'])
            let dateRangeEnd = new Date(app.filterItem['dateRangeEnd'])
        
            $('#daterange').daterangepicker({ 
                autoUpdateInput: false,
                startDate: dateRangeStar,
                endDate: dateRangeEnd
            })
        }else{
            $('#daterange').daterangepicker({ 
                autoUpdateInput: false,
            })
        }
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            app.orderRange = picker.startDate.format('YYYY/MM/DD') + ' ~ ' + picker.endDate.format('YYYY/MM/DD')
            app.filterItem['dateRangeStar'] = picker.startDate.format('YYYY/MM/DD')
            app.filterItem['dateRangeEnd'] = picker.endDate.format('YYYY/MM/DD')
        });
    })
</script>
@stop
