@inject('SaleManagePresenter', 'App\Presenters\SaleManagePresenter')
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
    <h1>
        {{trans('sellManage.S_SubTitle_3')}}
        {{-- <small>明細</small> --}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="/sell" onclick="loading.openLoading()">{{trans('sellManage.S_SubTitle_1')}}</a></li>
        <li><a href="/sell/manage/{{ $event['performanceId'] }}" onclick="loading.openLoading()">{{trans('sellManage.S_SubTitle_2')}}</a></li>
        <li class="active">{{trans('sellManage.S_SubTitle_3')}}</li>
    </ol>
@stop

@section('content')
<div id="app" class="content-navonly">
    <!-- 新增子選單 -->
    <ul id="" class="nav nav-tabs-sell">
        <li class="active">
            <a id="" href="/sell" onclick="loading.openLoading()"><span>{{ trans('sellManage.S_sellInfoTab_01') }}</span></a>
        </li>
        <li>
            <a id="" href="/orders" onclick="loading.openLoading()"><span>{{ trans('sellManage.S_sellInfoTab_02') }}</span></a>
        </li>
    </ul>
    <!-- /.新增子選單 -->
    <div>
<div id="orderDetail">
    <form id="getCsv" method="GET" action="csv/{{ $event['scheduleId'] }}">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="filterJson" v-model="filterJson">
    </form>
    <form id="sendSeatInf" method="POST" action="/sell/resSeatSetting">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="page" value="{{ $event['page'] }}">
        <input type="hidden" name="filterJson" v-model="filterJson">
    </form>
    <form id="resendNotice" method="POST" action="/sell/resendNotice">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="page" value="{{ $event['page'] }}">
        <input type="hidden" name="filterJson" v-model="filterJson">
    </form>
    <form id="orderCancel" method="POST" action="/sell/orderCancel/{{ $event['scheduleId'] }}">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="page" value="{{ $event['page'] }}">
        <input type="hidden" name="filterJson" v-model="filterJson">
    </form>
    <form id="reviseAmount" method="POST" action="/sell/reviseAmount/{{ $event['scheduleId'] }}">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="page" value="{{ $event['page'] }}">
        <input type="hidden" name="filterJson" v-model="filterJson">
    </form>
    <form id="filterOrder" method="GET" action="/sell/detail/{{ $event['scheduleId'] }}">
        {{ csrf_field() }}
        <input type="hidden" name="filterJson" v-model="filterJson">
    </form>
    <div class="box box-solid">
        <div class="box-body">
            <!-- FORMGROUP 2  Grid + BTN  -->
            <h3></h3>
            <div class="row-group-grid">
                <!-- Row1 -->
                <div class="row ">
                    <div class="col-xs-2 col-sm-2 grid-title">{{ trans('sellManage.S_EventTitle') }}</div>
                    <div class="col-xs-6 col-sm-10 grid-text">{{ $event['perfomanceTitle'] }}</div>
                </div>
                <!-- /.Row1 -->
                <!-- Row2 -->
                <div class="row">
                    <div class="col-xs-2 col-sm-2 grid-title">{{ trans('sellManage.S_EventOpenDate') }}</div>
                    <div class="col-xs-4 col-sm-4 grid-text">{{ $event['openDate'] }}</div>
                    <div class="col-xs-2 col-sm-2 {{ ($event['performance_status'] == '7')?'bg-yellow':''}} grid-title">{{ trans('sellManage.S_EventOpenTime') }}</div>
                    <div class="col-xs-4 col-sm-4 {{ ($event['performance_status'] == '7')?'bg-yellow':''}} grid-text">{{ $SaleManagePresenter->timeTransform($event['openTime']) }}</div>
                </div>
                <!-- /.Row2 -->
            </div>
        </div>
        <div class="box-footer text-right">
            @if(!is_null($event['seatmap_profile_cd']))
                <a href="/sell/seat/{{ $event['scheduleId'] }}" class="btn waves-effect waves-light btn-angle btn-inverse-outline">{{ trans('sellManage.S_EventSeatImage') }}</a>
            @endif
            <button type="button" v-on:click="csvEx()"  class="btn waves-effect waves-light btn-angle btn-inverse">{{ trans('sellManage.S_CsvButton') }}</button>
        </div>       
    </div>
    <!-- /.FORMGROUP 2  Grid + BTN  -->

    <!-- box - 檢索 -->
    <!--0511 調整樣式-->
    <div class="box no-border collapsed-box">
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
                            <input name="keyword" type="text" placeholder="{{ trans('sellManage.S_PleaseInputKeyword') }}" class="form-control" v-model="keyword">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label  col-md-2">{{ trans('sellManage.S_EventDetailTableDate') }}</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="date" type="text" class="form-control pull-right" id="daterange" v-model="orderRange">
                                <div class="input-group-btn">
                                    <button type="button" v-on:click="dateClear()"  class="btn waves-effect waves-light btn-inverse">{{ trans('sellManage.S_BtnClear') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{ trans('sellManage.S_EventDetailSeatName') }}</label>
                        <div class="col-md-4">
                            <select id="n" name="seat" class="form-control" style="width: 100%;" v-model="seatType">
                                <option value="all">{{ trans('sellManage.S_StatusAll') }}</option>
                                <template v-for="data in seatOption">
                                    <option :value="data">@{{ data }}</option>
                                </template>
                            </select>
                        </div>
                        <label class="control-label col-md-2">{{ trans('sellManage.S_EventDetailTicketName') }}</label>
                        <div class="col-md-4">
                            <select name="ticket" class="form-control" style="width: 100%;" v-model="fTicketType">
                                <option selected="selected" value="all">{{ trans('sellManage.S_StatusAll') }}</option>
                                <template v-for="data in ticketOption">
                                    <option :value="data">@{{ data }}</option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{ trans('sellManage.S_Paytype') }}</label>
                        <div class="col-md-4 control-label-checkbox">
                            {{-- <label class="control control--checkbox">
                                <input name="cash" type="checkbox" v-model="payCash">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailCash') }}
                            </label> --}}
                            <label class="control control--checkbox">
                                <input name="credit" type="checkbox"  v-model="notPaymentMethod">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailNone') }}
                            </label>
                            <label class="control control--checkbox">
                                <input name="credit" type="checkbox"  v-model="payCredit">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailCreditCard') }}
                            </label>
                            @if((\App::getLocale() == "zh-tw" ))
                            <label class="control control--checkbox">
                                <input name="credit" type="checkbox"  v-model="payIbon">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailIbon') }}
                            </label>
                            @endif
                            @if((\App::getLocale() == "ja" ))
                            <label class="control control--checkbox">
                                <input name="credit" type="checkbox"  v-model="paySevenEleven">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailConvenience') }}
                            </label>
                            @endif
                            <label class="control control--checkbox">
                                <input name="credit" type="checkbox"  v-model="payFree">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailFree') }}
                            </label>
                        </div>
                        <label class="control-label col-md-2">{{ trans('sellManage.S_Pickuptype') }}</label>
                        <div class="col-md-4 control-label-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="noTPickup">
                                <div class="control__indicator"></div>
                                無
                            </label>
                            <!-- STS 2021/05/28 モバパスの選択肢を非表示にしてください。  -->
                            <!-- <label class="control control--checkbox">
                                <input type="checkbox" v-model="pickup">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_Pickup_Mobapass') }}
                            </label> -->
                            @if((\App::getLocale() == "zh-tw" ))
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="qrpass">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_Pickup_QrPass') }}
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="ibon">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_Pickup_Ibon') }}
                            </label>
                            @endif
                            @if((\App::getLocale() == "ja" ))
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="sevenEleven">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_Pickup_SevenEleven') }}
                            </label>
                            @endif
                            {{-- <label class="control control--checkbox">
                                <input type="checkbox" v-model="noPickup">
                                <div class="control__indicator"></div>
                                信用現金現
                            </label> --}}
                            <!-- 0826新增取票方式 -->
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="resuq">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_Pickup_Resuq') }}
                            </label>
                            <!-- 0304新增無票券 -->
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="noTicketing">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_Pickup_NoTicketing') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{ trans('sellManage.S_EventDetailTableBill') }}</label>
                        <div class="col-md-4 control-label-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="notReceipt">
                                <div class="control__indicator"></div>
                                無
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="receipt">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailHad') }}
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="noReceipt">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailNoHad') }}
                            </label>
                        </div>
                        <label class="control-label col-md-2">{{ trans('sellManage.S_EventDetailTableTicketing') }}</label>
                        <div class="col-md-4 control-label-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="noTissue">
                                <div class="control__indicator"></div>
                                無
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="issue">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailGot') }}
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="noIssue">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailNotGet') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{ trans('sellManage.S_Seattype') }}</label>
                        <div class="col-md-4 control-label-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="seatFree">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailFreeSeat') }}
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="seatOrder">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailSelectSeat') }}
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="seatReserve">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_EventDetailRetainSeat') }}
                            </label>
                        </div>
                        <label class="control-label col-md-2">{{ trans('sellManage.S_OrderStatus') }}</label>
                        <div class="col-md-4 control-label-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="orderStatus['normal']">
                                <div class="control__indicator"></div>
                                正常
                            </label>
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="orderStatus['cancel']">
                                <div class="control__indicator"></div>
                                {{ trans('sellManage.S_CancelNotice09') }}
                            </label>
                            @if((\App::getLocale() != "ja" ))
                              <label class="control control--checkbox">
                                  <input type="checkbox" v-model="orderStatus['systemCancel']">
                                  <div class="control__indicator"></div>
                                  {{ trans('sellManage.S_CancelNotice08') }}
                              </label>
                            @endif
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="orderStatus['timeoutCancel']">
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
            <button v-on:click="sreachData()" class="btn waves-effect waves-light btn-angle btn-info">{{ trans('sellManage.S_InquireButton') }}</button>
        </div>
    </div>
    <!-- /.box - 檢索 -->

    <!-- 查詢結果表格 -->
    <div class="box box-solid" >
        <!-- 1219  -->
        @if((\App::getLocale() != "ja" ))
            <div class="box-header with-border flex-end lh-normal"  v-if="this.freeSeatInt.length > 0 && !performance_disp_status">
            <div class="tip"><span data-tooltip="{{ trans('sellManage.S_TickettingTooltip') }}"><i class="fas fa-info fa-1x"></i></span></div>
            <div class="w-20">
                <div class="mmlr-1">
                    <select id="evenType" name="evenType" class="form-control" aria-required="true" aria-invalid="false" v-model="freeSeatType">
                        <option  class="not-select" value="-1" disabled hidden>{{ trans('sellManage.S_TickettingNote') }}</option> 
                        <option v-for="(event,index) in freeSeatInt" v-bind:value="index">@{{event.inf.seatTitle}}</option>  
                        </select> 
                    </div>
                </div>
                <button :disabled="freeSeatType === -1" class="btn btn-info btn-ml" v-on:click="openTicketSetting( 0, 0, 1, 'freeSeat')">{{ trans('sellManage.S_Ticketting') }}</button>
            </div>
        @endif
        <!--   -->
        <div class="box-body" >
        <div class="row">
            <div class="col-md-12">
            <!-- TABLE 5 / Toggle Footable -->
                    <div class="panel-body">
                    <table class="table table-condensed tablesaw no-wrap tablesaw-sortable tablesaw-swipe" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>{{ trans('sellManage.S_EventDetailTableAll') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTable') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableDate')}}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableId') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableName') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableTel') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableMail') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTablePay') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableGet') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableBill') }}</th>
                            <th>{{ trans('sellManage.S_EventDetailTableTicketing') }}</th>
                            <th>入場</th>
                            <th>{{ trans('sellManage.S_EventDetailTableComplete') }}</th>
                            <!-- 1219 新增 class="text-right"-->
                            <th class="text-right">{{ trans('sellManage.S_EventDetailTableNum') }}</th>
                            <th class="text-right">{{ trans('sellManage.S_EventDetailTableTotal') }}</th>
                            <th class="text-right">{{ trans('sellManage.S_EventDetailTableReceivedTotal') }}</th>
                        </tr>
                        </thead>
                        <template v-for="(inf,index) in filtertData">
                        <tbody>
                          <tr class="editblocks">
                            <td>
                                <button class="showpageblock btn btn-default btn-xs">
                                  <!--<span class="glyphicon glyphicon-eye-open"></span>-->
                                  <i class="fas fa-th-list"></i>
                                </button>
                            </td>
                            <td>
                                {{-- for dev only --}}
                                {{-- (@{{ inf['order_id'] }} )  --}}
                                {{-- for dev only --}}
                                @{{ (inf['reserve_no'])?inf['reserve_no']:'-' }}
                                <small class="subtitle" v-if='inf["order_status_cancel"]'>
                                    @{{ `(${inf['order_cancel_reason']})` }}
                                </small>   
                            </td>
                            <td>@{{ (inf['reserve_date'])?inf['reserve_date']:'-' }}</td>
                            <!--//STS 2021/06/17 Task 21: 非会員のダミーのID（gettiis$[N_M]）が表示される箇所を"非会員"に変更してください。 START-->
                            <!-- <td>@{{ (inf['member_id'])?inf['member_id']:"-" }}</td> -->
                            <td v-if="(inf['member_id']) === 'gettiis$[N_M]'">{{ trans('sellManage.S_EventDetailNoneMember') }}</td>
                            <td v-else>@{{ (inf['member_id'])?inf['member_id']:"-" }}</td>
                            <!--END-->
                            <td>
                              @{{ (inf['consumer_name'])?inf['consumer_name']:'-' }}<br>
                              @{{ inf['consimer_kana'] }}
                            </td>
                            <td>@{{ inf['tel_num'] }}</td>
                            <td>@{{ inf['mail_address'] }}</td>
                            <td v-if="inf['pay_method'] === -1">X</td>
                            <td v-else-if="inf['pay_method'] === 1">{{ trans('sellManage.S_EventDetailCash') }}</td>
                            <td v-else-if="inf['pay_method'] === 2">{{ trans('sellManage.S_EventDetailCreditCard') }}</td>
                            <td v-else-if="inf['pay_method'] === 31">ibon</td>
                            <td v-else-if="inf['pay_method'] === 3">{{ trans('sellManage.S_EventDetailConvenience') }}</td>
                            <td v-else-if="inf['pay_method'] === 20">{{ trans('sellManage.S_EventDetailFree') }}</td>
                            <td v-else-if="inf['pay_method'] === 4">-</td>
                            <td v-else>-</td>
                            <td v-if="inf['pickup_method'] === -1">X</td>
                            <td v-else-if="inf['pickup_method'] === 9">{{ trans('sellManage.S_EventDetailPickup_ET') }}</td>
                            <td v-else-if="inf['pickup_method'] === 91">QR PASS</td>
                            <td v-else-if="inf['pickup_method'] === 31">{{ trans('sellManage.S_Pickup_Ibon') }}</td>
                            <td v-else-if="inf['pickup_method'] === 3">{{ trans('sellManage.S_EventDetailConvenience') }}</td>
                            <td v-else-if="inf['pickup_method'] === 8">{{ trans('sellManage.S_Pickup_Resuq') }}</td>
                            <td v-else-if="inf['pickup_method'] === 99">{{ trans('sellManage.S_Pickup_NoTicketing') }}</td>
                            <td v-else-if="inf['pickup_method'] === 4">-</td>
                            <td v-else>-</td>
                            <td v-if="inf['payment_flg'] === 0">{{ trans('sellManage.S_EventDetailNoHad') }}</td>
                            <td v-else-if="inf['payment_flg'] === 1">{{ trans('sellManage.S_EventDetailHad') }}</td>
                            <td v-else-if="inf['payment_flg'] === 2">{{ trans('sellManage.S_EventDetailHad') }}※</td>
                            <td v-else>-</td>
                            <td v-if="inf['issue_flg'] === 0">{{ trans('sellManage.S_EventDetailNotGet') }}</td>
                            <td v-else-if="inf['issue_flg'] === 1">{{ trans('sellManage.S_EventDetailGot') }}</td>
                            <td v-else-if="inf['issue_flg'] === 2">{{ trans('sellManage.S_EventDetailGot') }}※</td>
                            <td v-else>-</td>
                            <td>@{{ inf['visit_flg'] }}</td>
                            <td>-</td>
                            <!-- 1219 新增 class="text-right"-->
                            <td class="text-right">@{{ (inf['total_pie'])?inf['total_pie']:0 }}</td>
                            <td class="text-right __comma">@{{ (inf['total_price'])?inf['total_price']:0 }}</td>  
                            <td class="text-right __comma">@{{ inf['received_amount'] }}</td>                         
                          </tr>
                          <tr>
                            <td colspan="16" class="hiddenRow">
                              <div class="togglesettings">
                                <!-- 1219 更新調整 -->
                                <button v-if="inf['cancel_able'] && inf['order_type'] == 1 && inf['cancel_flg'] == 0 && !inf['order_status_cancel'] && (inf['issue_flg'] === 0 || (inf['pickup_method'] === 9 && inf['issue_flg'] !== 0 && inf['visit_flg'] === 0))  && (( inf['pay_method'] === 2 && inf['payment_flg'] === 1 ) || ( inf['pay_method'] === 3 && inf['payment_flg'] === 0 ) || (inf['pay_method'] === 31) || (inf['pay_method'] === 20))" class="btn btn-danger pull-right margin-10"  v-on:click="openOrderCancel(index, inf['order_id'], 1)">
                                    {{ trans('sellManage.S_ReserveCancel') }}
                                </button>
                                <button v-if="inf['order_type'] == 1 && inf['cancel_flg'] == 1" class="btn btn-danger pull-right margin-10"  v-on:click="openOrderCancel(index, inf['order_id'], 2)">
                                    {{ trans('sellManage.S_ReserveCanceled') }}
                                </button>
                                <!-- 訂單金額修改按鈕 -->
                                    <button v-if="inf['revise_amount']['data']['status']" class="btn btn-danger pull-right margin-10"  v-on:click="openReviseAmount(index, inf['order_id'], 2, 'reviseAmount', 'Inf')">
                                        {{ trans('sellManage.S_ReserveChangeDetail') }}
                                    </button>
                                    {{-- <button v-if="inf['revise_amount']['reviseStatus'] && (inf['issue_flg'] === 0 || (inf['pickup_method'] === 9 && inf['issue_flg'] !== 0)) && (( inf['pay_method'] === 2 && inf['payment_flg'] === 1 ) || ( inf['pay_method'] === 3 && inf['payment_flg'] === 0 ) || (inf['pay_method'] === 31))" class="btn btn-danger pull-right margin-10"  v-on:click="openReviseAmount(index, inf['order_id'], 1, 'reviseAmount', 'revise')"> --}}
                                    <button v-if="inf['revise_amount']['reviseStatus'] && (inf['issue_flg'] === 0 || (inf['pickup_method'] === 9 && inf['issue_flg'] !== 0)) && ( ( inf['pay_method'] === 3 && inf['payment_flg'] === 0 ) || (inf['pay_method'] === 31))" class="btn btn-danger pull-right margin-10"  v-on:click="openReviseAmount(index, inf['order_id'], 1, 'reviseAmount', 'revise')">
                                        {{ trans('sellManage.S_ReserveChange') }}
                                    </button>
                                 <!-- 訂單金額修改按鈕 -->
                                <!-- /.1219 更新調整 -->
                                <table style="width: 93%; margin: 10px 10px 20px auto;">
                                <tr><td>
                                <table class="table color-bordered-table success-bordered-table pull-right">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>入場</th>
                                        <th v-if="inf['order_type'] == 2">{{ trans('sellManage.S_EventDetailTableFllorBlock') }}</th>
                                        <th v-else>{{ trans('sellManage.S_EventDetailSeatName') }}</th>
                                        <th v-if="inf['order_type'] == 2">{{ trans('sellManage.S_EventDetailSeatPosition') }}</th>
                                        <th v-else>{{ trans('sellManage.S_EventDetailTicketName') }}</th>
                                        <th v-if="inf['order_type'] == 2">{{ trans('member.S_MemberName') }}</th>
                                        <th v-else>{{ trans('sellManage.S_EventDetailSeatType') }}</th>
                                        <th v-if="inf['order_type'] == 2">{{ trans('sellManage.S_Pickuptype') }}</th>
                                        <th v-else>{{ trans('sellManage.S_EventDetailSeatPosition') }}  / {{ trans('sellManage.S_EventDetailReferencenNumber') }}</th>
                                        <!-- 1219 新增 class="text-right"-->
                                        <th v-if="inf['order_type'] == 2">{{ trans('sellManage.S_EventDetailTableTicketingDate') }}</th>
                                        <th v-else class="text-right">{{ trans('sellManage.S_EventDetailTableTP') }}</th>
                                        <th v-if="inf['order_type'] == 2">　
                                        <th style="width: 150px;">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody v-for="(data, no) in inf['seatData']">
                                    <tr>
                                        <td>@{{ no+1 }}.</td>
                                        <td>@{{ data['visit_flg'] }}</td> 
                                        <td v-if="inf['order_type'] == 2">@{{ data['floor_name'] + '-' + data['block_name'] }}</td>
                                        <td v-else>@{{ data['seatTitle'] }}</td>
                                        <td v-if="inf['order_type'] == 2">@{{ data['seat_cols'] + '-' + data['seat_number'] }}</td>
                                        <td v-else>@{{ data['ticketTitle'] }}</td>
                                        <template v-if="inf['order_type'] == 2">
                                            <td>@{{ (data['consumerName'])?data['consumerName']:'-' }}</td>
                                        </template>
                                        <template v-else>
                                            <td v-if="data['seatType'] === 1">{{trans('sellManage.S_EventDetailSelectSeat') }}</td>
                                            <td v-else-if="data['seatType'] === 'R'">{{trans('sellManage.S_EventDetailFreeSeat') }}</td>
                                            <td v-else-if="data['seatType'] === 3">{{trans('sellManage.S_EventDetailRetainSeat') }}</td>
                                            <td v-else></td>
                                        </template>
                                        <template v-if="inf['order_type'] == 2">@{{ data['inf']['pickup_method'] }}
                                            <td v-if="data['orderInf']['pickup_method'] === -1">X</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 9">{{ trans('sellManage.S_EventDetailPickup_ET') }}</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 91">QR PASS</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 8">{{ trans('sellManage.S_Pickup_Resuq') }}</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 99">{{ trans('sellManage.S_Pickup_NoTicketing') }}</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 31">{{ trans('sellManage.S_Pickup_Ibon') }}</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 3">{{ trans('sellManage.S_EventDetailConvenience') }}</td>
                                            <td v-else-if="data['orderInf']['pickup_method'] === 4">-</td>
                                            <td v-else>-</td>
                                        </template>
                                        <template v-else>
                                            <td>@{{ getTicketPosition(data) }}</td>
                                        </template>
                                        <!-- 1219 新增 class="text-right"-->
                                        <td v-if="inf['order_type'] == 2">@{{ data['reserve_date'] }}</td>
                                        <td v-else class="text-right __comma">@{{ data['price'] }}</td>
                                        <td class="text-right" v-if="data['seatType'] === 3 && data['orderInf']['status'] === 1 && !performance_disp_status">
                                          @if((\App::getLocale() == "zh-tw" ))
                                            <!-- 1219 更新 btn-danger-outline 樣式-->
                                            <button class="btn btn-info-outline btn-m btn-w90" v-on:click="openTicketSetting(index, no, data['orderInf']['status'])">
                                                {{ trans('sellManage.S_Ticketting') }}
                                            </button>
                                          @endif
                                        </td>
                                        <td class="text-right" v-else-if="data['seatType'] === 3 && data['orderInf']['status'] === 2">
                                            <!-- 1219 更新 btn-inverse-outline 樣式-->
                                            <button class="btn btn-inverse-outline btn-m btn-w90" v-on:click="openTicketSetting(index, no, 3)">
                                                {{ trans('sellManage.S_TickettingDetail') }}
                                            </button>
                                        </td>
                                        <td v-if="getVisitButtonFlg(inf)"><!-- 0412新增 入場/取消入場按鈕 -->
                                            <template v-if="getVisitFlg(data['visit_flg']) == 2">
                                                <button class="btn btn-info-outline btn-mm pull-right" v-on:click="openPopResult(data['seat_sale_id'], true)">{{ trans('sellManage.S_Admission') }}</button>
                                            </template>
                                            <template v-if="getVisitFlg(data['visit_flg']) == 1">
                                                <button class="btn btn-inverse-outline btn-mm pull-right" v-on:click="openPopResult(data['seat_sale_id'], false)">{{ trans('sellManage.S_AdmissionCancel') }}</button>
                                            </template>
                                        </td>
                                        <td v-else>
                                        </td>
                                    </tr>    
                                </tbody>
                                </table>
                                </td></tr>
                                <tr v-show="inf['order_type'] != 2 && 0 < questionnaires.length">
                                    <td>
                                        <table class="table color-bordered-table success-bordered-table pull-right">
                                            <thead>
                                                <tr>
                                                    <th width="20%">{{ trans('sellManage.S_FreeQuestionTitle') }}</th>
                                                    <th width="30%">{{ trans('sellManage.S_FreeQuestionDesc') }}</th>
                                                    <th width="50%">{{ trans('sellManage.S_FreeQuestionAns') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody v-for="questionnaire in questionnaires">
                                                <tr>
                                                    <td>
                                                        <span v-if="20 < questionnaire.question_lang_ja[0].question_title.length" data-toggle="tooltip" :data-original-title="questionnaire.question_lang_ja[0].question_title">@{{ omittedText(questionnaire.question_lang_ja[0].question_title, 20) }}</span>
                                                        <span v-else>@{{ questionnaire.question_lang_ja[0].question_title }}</span>
                                                    </td>
                                                    <td>
                                                        <span v-if="40 < questionnaire.question_lang_ja[0].question_text.length" data-toggle="tooltip" :data-original-title="questionnaire.question_lang_ja[0].question_text">@{{ omittedText(questionnaire.question_lang_ja[0].question_text, 40) }}</span>
                                                        <span v-else>@{{ questionnaire.question_lang_ja[0].question_text }}</span>
                                                    </td>
                                                    <td>
                                                        <!-- 2021/06/26 - STS - Task 34 - Check when answer is null - START -->
                                                        <!--<span v-if="0 == inf.questionAnswers.length"></span> -->
                                                        <span v-if="null == inf.questionAnswers[questionnaire.question_id]"></span>
                                                        <!-- 2021/06/26 - STS - Task 34 -  END-->
                                                        <span v-else-if="100 < inf.questionAnswers[questionnaire.question_id].length" data-toggle="tooltip" :data-original-title="inf.questionAnswers[questionnaire.question_id]">@{{ omittedText(inf.questionAnswers[questionnaire.question_id], 100) }}</span>
                                                        <span v-else>@{{ inf.questionAnswers[questionnaire.question_id] }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                        </template>
                        <tfoot class="tfoot-light">
                            <tr>
                            <th colspan="13" class="text-center">
                                @if(!is_null($event['paginator']))
                                    小計
                                @else
                                    {{ trans('sellManage.S_TableTotal') }}
                                @endif
                            </th>
                            <!-- 1219 新增 class="text-right"-->
                            <th class="text-right __comma">@{{ totalPie }}</th>
                            <th class="text-right __comma">@{{ totalPrice }}</th>
                            <th class="text-right __comma">@{{ receivedAmount }}</th>
                            <!-- 1219 移除 <th></th>-->
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    <!-- /.查詢結果表格 -->
                    @if(!is_null($event['paginator']))
                        <!-- Page navigation -->
                        <div class="col-sm-12">
                            <nav aria-label="Page navigation" class="pull-right">
                                {{ $event['paginator']->links() }}
                            </nav>
                        </div>
                        <!-- /.Page navigation -->
                    @endif
            <!-- /.TABLE 5 / Toggle Footable -->
            </div>
        </div>
        </div>
    </div>
    <!-- modal-dialog -->
    <!-- 0412新增 入場按鈕跳出pop確認是否執行 -->
    <div id="pop-up-admission">
        <div class="modal-mask" v-show="popResult['show']">
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
    <!-- /.0412 -->
    <div class="modal-mask" v-show="showModal">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <!--出票-->
                        <template v-if="dialogType == 'draw'">        
                            <!-- form  step 1-->
                            <h4 class="modal-title" v-show="step == '1'">
                                {{ trans('sellManage.S_TicketingNotice_1') }}
                            </h4>
                            <!-- form  step 1-->
                            <!-- form  step 2-->
                            <h4 class="modal-title" v-show="step == '2' && !isInsert">
                                {{ trans('sellManage.S_TicketingNotice_2') }}
                            </h4>
                            <!-- form  step 2-->
                            <!-- form  step 3-->
                            <h4 class="modal-title" v-show="step == '3'">
                                {{ trans('sellManage.S_TicketingNotice_3') }}
                            </h4>
                            <!-- form  step 3-->
                        </template>
                    <!-- 出票 -->
                    <!--訂單取消-->
                        <template v-if="dialogType == 'orderCancel'">        
                            <h4 class="modal-title" v-show="step == '1'">
                                 {{ trans('sellManage.S_ReserveCancel') }}
                            </h4>
                            <h4 class="modal-title" v-show="step == '2'" v-if="dialogType == 'orderCancel'">
                                 {{ trans('sellManage.S_ReserveCancelDetail') }}
                            </h4>
                        </template>
                    <!--訂單取消-->
                    <!--訂單金額修改-->
                        <template v-if="dialogType == 'reviseAmount'">        
                            <h4 class="modal-title" v-show="step == '1'">
                                 {{ trans('sellManage.S_ReserveChange') }}
                            </h4>
                            <h4 class="modal-title" v-show="step == '2' && process == 'revise'">
                                 {{ trans('sellManage.S_ReserveChangeConfirm') }}
                            </h4>
                            <h4 class="modal-title" v-show="step == '2' && process == 'Inf'">
                                 {{ trans('sellManage.S_ReserveChangeDetail') }}
                            </h4>
                        </template>
                    <!--訂單金額修改-->
                    <!--統一頁-->
                        <!-- form  step 4-->
                        <h4 class="modal-title" v-show="step == '-2' || step == '-3'">
                            <template v-if="dialogType == 'reviseAmount'">
                                {{ trans('sellManage.S_ReserveChangeResult') }}
                            </template>
                            <template v-else-if="dialogType == 'orderCancel'"> 
                                {{ trans('sellManage.S_ReserveCancel') }}
                            </template>                            
                            <template v-else>
                                {{ trans('sellManage.S_TicketingNotice_4') }}
                            </template>
                        </h4>
                        <!-- form  step 4-->
                        <!-- form  step -1 loading頁-->
                        <h4 class="modal-title" v-show="step == '-1'">
                           {{ trans('sellManage.S_TicketingNotice_7') }}
                        </h4>
                        <!-- form  step -1-->
                    <!--統一頁-->
                </div>
                <div class="modal-body">
                    <!--出票-->
                        <template v-if="dialogType == 'draw'">
                            <!-- form  step 1-->
                            <div class="row form-horizontal" v-show="step == '1'">
                                <div class="col-md-12">
                                <!-- 會員查詢 -->
                                    <div class="form-group mt-4">
                                        <!--<div class="col-md-4 control-label">會員資料</div>-->
                                        <div class="col-md-12">  
                                            <div class="input-group">
                                                <input type="text" class="form-control pull-right" id="memberSearch"  placeholder="{{ trans('sellManage.S_TicketingPlacehokder') }}" v-model="memberKeyword" v-on:keyup.enter="memberSearch()">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn waves-effect waves-light btn-inverse" v-on:click="memberSearch()">{{ trans('sellManage.S_SearchBtn') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.會員查詢 -->
                                    <div class="divider"></div>
                                    <div class="loading-box" v-show="search_loading">
                                        <div class="loader"></div>
                                    </div>
                                    <!-- 查詢結果說明 -->
                                    <div class="modal-result" v-show="showMembersInf && !search_loading">
                                        <h5>{!! trans('sellManage.S_TicketingSearch', ['keyword' => '@{{memberKeyword}}','total' => '@{{membersTotal}}','showtotal' => '@{{membersShowTotal}}']) !!}</h5>
                                        <!-- 被選取的tag樣式請使用 active -->
                                        <p>
                                            <span v-for="(item, index) in members" :class="{ active: memberId === index }" class="label label-normal-outline cursor lh-3" v-on:click="memberOnSelect(index)"> 
                                                @{{item.user_id}}(@{{item.name}})
                                            </span> 
                                        </p>
                                    </div>
                                    <!-- /.查詢結果說明 -->
                                </div>
                            </div>
                            <!-- form  step 1-->
                            <!-- form  step 2-->
                            <div class="row form-horizontal" v-show="step == '2'">
                                <div class="col-md-12">
                                    <div class="modal-overflow">
                                        <ul class="modal-list-non mmlr-1 box-light">
                                            <li>
                                                <div class="col-md-3">
                                                <b>{{ trans('sellManage.S_EventDetailTableId') }} / {{ trans('member.S_MemberName') }}</b>
                                                </div> @{{memberName}} / @{{memberUserId}} 
                                            </li> 
                                        </ul>
                                        <!--分隔線-->
                                        <div class="divider"></div>
                                        <!--/.分隔線-->
                                        <!-- form-group -->
                                        <div class="modal-result">
                                            <h5 class="mlb-1">
                                                <i class="fas fa-check-double"></i>  
                                                {{ trans('sellManage.S_TicketingNotice_5') }}
                                            </h5>
                                            <div class="form-group" >
                                                <label class="col-md-2 control-label">{{ trans('sellManage.S_TicketingType') }}</label>
                                                <div class="col-md-10 form-group-flex mtb-1">
                                                    <div class="form-checkbox">
                                                        <label class="control control--radio">
                                                            <input type="radio" name="pickupMethod" value="mobapass" v-model="pickupMethod">
                                                            {{ trans('sellManage.S_EventDetailPickup_ET') }}
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    @if((\App::getLocale() == "zh-tw" ))
                                                      <div class="form-checkbox">
                                                          <label class="control control--radio">
                                                              <input type="radio" name="pickupMethod" value="qrpass" v-model="pickupMethod">
                                                              {{ trans('sellManage.S_EventDetailQrpass') }}
                                                              <div class="control__indicator"></div>
                                                          </label>
                                                      </div>
                                                      <div class="form-checkbox">
                                                          <label class="control control--radio">
                                                              <input type="radio" name="pickupMethod" value="ibon" v-model="pickupMethod">
                                                              {{ trans('sellManage.S_EventDetailIbon') }}
                                                              <div class="control__indicator"></div>
                                                          </label>
                                                      </div>
                                                    @endif
                                                    @if((\App::getLocale() == "ja" ))
                                                      <div class="form-checkbox">
                                                          <label class="control control--radio">
                                                              <input type="radio" name="pickupMethod" value="sevenEleven" v-model="pickupMethod">
                                                              {{ trans('sellManage.S_EventDetailConvenience') }}
                                                              <div class="control__indicator"></div>
                                                          </label>
                                                      </div>
                                                      <div class="form-checkbox">
                                                          <label class="control control--radio">
                                                              <input type="radio" name="pickupMethod" value="resuq" v-model="pickupMethod">
                                                              {{ trans('sellManage.S_EventDetailResuq') }}
                                                              <div class="control__indicator"></div>
                                                          </label>
                                                      </div>
                                                    @endif
                                                    <!-- 0304新增無票券 -->
                                                    <div class="form-checkbox">
                                                          <label class="control control--radio">
                                                              <input type="radio" name="pickupMethod" value="noTicketing" v-model="pickupMethod">
                                                              {{ trans('sellManage.S_EventDetailNoTicketing') }}
                                                              <div class="control__indicator"></div>
                                                          </label>
                                                      </div>
                                                </div>
                                            </div>
                                            <!-- form-group -->
                                            <!-- 資訊顯示 -->
                                            <ul class="modal-list-non mmlr-1 box-light">
                                                <li>
                                                    <b class="col-md-2">{{ trans('sellManage.S_EventDetailTableTel') }}</b>@{{phoneNum}}
                                                </li>
                                                <li>
                                                    <b class="col-md-2">EMail</b>@{{mail}}
                                                </li>
                                                <li v-show="pickupMethod == 'mobapass'">
                                                    <b class="col-md-2">{{ trans('sellManage.S_EventDetailPickup_ET') }} ID</b>@{{mobapassId}}
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- /.資訊顯示 -->
                                    </div>
                                </div>
                            </div>
                            <!-- form  step 2-->
                            <!-- form  step 3 確認資料 -->
                            <div class="row form-horizontal" v-show="step == 3">
                                <div class="col-md-12">
                                    <h4 class="text-red"><i class="fas fa-exclamation-triangle"></i>  {{ trans('sellManage.S_TicketingNotice_6') }}</h4>
                                        <div class="modal-overflow">
                                            <!-- 會員資訊呈現 -->
                                            <ul class="modal-list">
                                                <li>
                                                    <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailSeatName') }}</span>@{{seatTitle}} 
                                                </li> 
                                                <li>
                                                    <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailSeatPosition') }}</span>@{{seatPosition}}
                                                </li> 
                                                <li>
                                                    <span class="badge bg-gray-light">{{ trans('sellManage.S_Pickuptype') }}</span>@{{pickupMethodTitle}}
                                                </li>   
                                                <li>
                                                    <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailTableTel') }}</span>@{{phoneNum}} 
                                                </li>
                                                <li>
                                                    <span class="badge bg-gray-light">Email</span>@{{mail}}
                                                </li>
                                                <li v-show="pickupMethod == 'mobapass'" >
                                                    <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailPickup_ET') }} id</span>@{{mobapassId}}
                                                </li>
                                            </ul> 
                                            <!-- /.會員資訊呈現 --> 
                                        </div>
                                    </div>
                                </div>
                            <!-- form  step 3 確認資料 -->
                        </template>
                    <!--出票-->
                    <!--訂單取消-->
                        <template v-if="dialogType == 'orderCancel'">
                            <div class="row form-horizontal" v-show="step == '1'">
                                <div class="col-md-12">
                                    <h4 class="">
                                        <i class="fas fa-check-double"></i>
                                        <!-- 信用卡請使用  您選擇取消訂單 1911-100031-70，請確認欲退款的信用卡資訊：-->
                                        {!! trans('sellManage.S_ReserveCancelNotice1', ['reserve_no' => '@{{reserve_no}}']) !!}
                                    </h4>
                                    <h4 class="text-red" v-show="refundCheck">
                                        <i class="fas fa-exclamation-triangle"></i>  
                                        @{{ this.errorWarm }}
                                    </h4>
                                    <div class="flex-start i-memo-blue">{{ trans('sellManage.S_ReserveCancelNotice2') }} @{{ reserve_date }}</div>
                                        <div class="modal-overflow overflow-x-hidden">
                                            <template v-if="refund_kbn == '{{ \Config::get('constant.pay_method.card') }}'">
                                                <!-- 信用卡請使用以下 -->
                                                <ul class="modal-list">
                                                    <li>
                                                        <span class="badge bg-gray-light">{{ trans('sellManage.S_RefundCard') }}</span>  
                                                    </li> 
                                                </ul>
                                            </template>
                                            <template v-else-if="refund_kbn == '{{ \Config::get('constant.pay_method.store') }}'">
                                                <!-- SEJ請使用以下 -->
                                                <ul class="modal-list">
                                                    <li>
                                                        <span class="badge bg-gray-light">{{ trans('sellManage.S_RefundSEJ') }}</span>  
                                                    </li> 
                                                </ul>
                                            </template>
                                            <template v-else-if="refund_kbn == '{{ \Config::get('constant.pay_method.free') }}'">
                                                <!-- SEJ請使用以下 -->
                                                <ul class="modal-list">
                                                    <li>
                                                        <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailFree') }}</span>  
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
                                                                <input dusk="bankName1" type="text" class="form-control" maxlength="4" placeholder="{{ trans('sellManage.S_RefundBankCode') }}" @change="bankCodeChange($event)" v-model="bankCode">
                                                            @else
                                                                <input dusk="bankName1" type="text" class="form-control" maxlength="3" placeholder="{{ trans('sellManage.S_RefundBankCode') }}" @change="bankCodeChange($event)" v-model="bankCode">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select id="evenType" name="evenType" class="form-control" aria-required="true" aria-invalid="false" @change="bankSelectChange($event)" v-model="bankId">
                                                                <option value="" class="not-select" disabled hidden>{{ trans('sellManage.S_RefundBankSelect') }}</option> 
                                                                <option v-for="(data, index) in bankinf" v-bind:value="data[0]">@{{'('+data[0]+') ' + data[1]}}</option> 
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
                                                            <input dusk="bankName2" type="text" class="form-control"  placeholder="{{ trans('sellManage.S_RefundBankBranch') }}" v-model="branchName">
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
                                                            <input dusk="bankNum" type="text" class="form-control"  placeholder="ex:00987437392" v-model="bankAccount">
                                                        </div>
                                                    </div>
                                                <!-- /.3 -->   
                                            </template>       
                                            <!-- 4 -->
                                            <template v-if="refundDisplay()">
                                                <div class="form-group">
                                                    <div class="ml-70x">
                                                        @if(session()->get('full_refund'))
                                                            <div class="form-checkbox">
                                                                <label class="control control--radio">
                                                                <input type="radio" name="all" value="fullRefund" v-on:change="setFullRefund" v-model="fullRefund">{{ trans('sellManage.S_FullRefund') }}
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="form-checkbox">
                                                                <label class="control control--radio">
                                                                <input type="radio" name="all" value="basisRefund" v-on:change="setFullRefund" v-model="fullRefund">{{ trans('sellManage.S_PartialRefund') }}
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if(\App::getLocale() == "ja" )
                                                          <div class="mtb-2">
                                                        <label class="col-md-4 control-label">
                                                        <!--<b>＊</b>-->
                                                        {{ trans('sellManage.S_RefundAmount') }}
                                                        </label>
                                                        <div class="col-md-8">
                                                        <!-- **20201106 /新增調整 /daphne -->
                                                            <input dusk="refund" type="text" class="from-style __comma"  placeholder=""  v-model="refundPaymentDisplay"  disabled="disabled" >
                                                        @else
                                                            <!-- **20201106 /新增調整 /daphne-->
                                                            <div class="from-style __comma">@{{ refundPayment }}</div>
                                                            <!--<input dusk="refund" type="text" class="form-control __comma"  placeholder=""  v-model="refundPayment" >-->
                                                        @endif
                                                        <span v-if="refundTextDisplay" class="help-block text-blue text-right">{{ trans('sellManage.S_ReserveCancelNotice4') }}</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <!-- /.4 --> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-horizontal" v-show="step == '2'">
                                <div class="col-md-12">
                                    <h4 class="text-red">
                                        <i class="fas fa-exclamation-triangle"></i>  {{ trans('sellManage.S_ReserveCancelNotice5') }}
                                    </h4>
                                    <div class="modal-overflow">
                                        <ul class="modal-list">
                                            <li>
                                                <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailTable') }}</span> @{{ reserve_no }}
                                            </li> 
                                            <li v-if="refund_kbn != '{{ \Config::get('constant.pay_method.card') }}' && refund_kbn != '{{ \Config::get('constant.pay_method.store') }}' && refund_kbn != '{{ \Config::get('constant.pay_method.free') }}'">
                                                <span class="badge bg-gray-light">{{ trans('sellManage.S_RefundAccount') }}</span> @{{ '('+bankCode+')'+bankName + '　　 {!! trans('sellManage.S_RefundBankBranch') !!}: ' + branchName + '　　 {!! trans('sellManage.S_RefundAccountNumber') !!}: ' + bankAccount }}  
                                            </li> 
                                            <li v-if="refund_kbn != '{{ \Config::get('constant.pay_method.store') }}'">
                                                <span class="badge bg-gray-light">{{ trans('sellManage.S_RefundAmount') }}</span> <span class="__comma">@{{ refundPaymentDisplay }}</span>
                                            </li>   
                                            <li>
                                                <span class="badge bg-gray-light">{{ trans('sellManage.S_EventTitle') }}</span> @{{ perfomanceTitle }}
                                            </li>
                                            <li>
                                                <span class="badge bg-gray-light">{{ trans('sellManage.S_EventOpenDate') }}</span> @{{ openDate }} - {{ $event['openTime'] }}
                                            </li>
                                            <li v-for="seatData in orderSeatData">
                                                <span class="badge bg-gray-light">{{ trans('sellManage.S_EventDetailSeatName') }}/{{ trans('sellManage.S_EventDetailTicketName') }}</span> @{{ seatData['seatTitle'] + '  ' + seatData['ticketTitle'] + '  ' +seatData['seatPosition'] }} 
                                            </li>
                                        </ul>      
                                    </div>
                                </div>
                            </div>
                        </template>
                    <!--訂單取消-->
                    <!--訂單金額修改-->  
                        <template  v-if="dialogType == 'reviseAmount'  && step != '-2'">   
                            <!-- form  step 1 資料編輯-->
                                <div class="row form-horizontal" v-show="step == '1'">
                                    <div class="col-md-12">
                                        <h4 class="">
                                            <i class="fas fa-check-double"></i>
                                            <!-- 信用卡請使用  您選擇取消訂單 1911-100031-70，請確認欲退款的信用卡資訊：-->
                                            {!! trans('sellManage.S_ReviseAmountNotice11', ['reserve_no' => '@{{ orderData["reserve_no"] }}']) !!}
                                        </h4>
                                        <!-- 0713 新增 scroll -->
                                        <div class="modal-overflow">
                                        <!--  TABLE3 直式樣式  -->
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="" class="table table-striped table-white">
                                                        <thead>
                                                            <tr>
                                                                <th width="10%">{{ trans('sellManage.S_EventDetailSeatName') }}</th>
                                                                <th width="15%">{{ trans('sellManage.S_EventDetailTicketName') }}</th>
                                                                <th width="15%">{{ trans('sellManage.S_EventDetailSeatPosition') }}</th>
                                                                <!-- 金額＆數字置右 -->
                                                                <th class="text-right">{{ trans('sellManage.S_ReviseAmountNotice5') }}</th>
                                                                <th class="text-right">{{ trans('sellManage.S_ReviseAmountNotice15') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for='seat in reviseSeatPrice'>
                                                                <td>@{{ seat.seatTitle }}</td>
                                                                <td>@{{ seat.ticketTitle }}</td>
                                                                <td>@{{ seat.seatPosition }}</td>
                                                                <td width="300" class="text-right __comma">@{{ seat['oriPrice'] }} + @{{ seat['commissionSum'] }}</td>
                                                                <td width="300" class="text-right __comma">
                                                                    <div class="">
                                                                        <h6 class="text-red" v-show="seat['warnStatuc']">
                                                                            <i class="fas fa-exclamation-triangle"></i>  
                                                                            @{{ seat['warnNotice'] }}
                                                                        </h6>
                                                                        <input type="text" class="form-control text-right text-black font-600"  placeholder="" v-model="seat['price']">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot class="">
                                                            <tr>
                                                            <th colspan="5">
                                                                <div class="total-box-non w-75">
                                                                    <table class="table table-gray">
                                                                        <tr>
                                                                            <th>{{ trans('sellManage.S_ReviseAmountNotice16') }}</th>
                                                                            <td width="220" class="text-right text-gray ">@{{ orderData['reservation_commission'] }}</td>
                                                                            <td width="220"  class="text-right">@{{ orderData['reservation_commission'] }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="total-foot">
                                                                            <th>{{ trans('sellManage.S_ReviseAmountNotice17') }}</th>
                                                                            <td class="text-right text-gray">@{{ oriPriceSum }}</td>
                                                                            <td class="text-right">@{{ reviseAmount }}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--  /. TABLE3 直式樣式  -->
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">
                                                        <b>［ 必須 ］</b>
                                                        {{ trans('sellManage.S_ReviseAmountNotice7') }}
                                                    </label>
                                                    <div class="col-md-10">
                                                        <h6 class="text-red" v-show="process == 'revise' && refundCheck">
                                                            <i class="fas fa-exclamation-triangle"></i>  
                                                            @{{ this.errorWarm }}
                                                        </h6>
                                                        <textarea class="form-control" rows="5" placeholder="" v-model="reviseMemo"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- 0713 新增 /.scroll-->
                                    </div>
                                </div>
                            <!-- form  step 1 資料編輯-->
                            <!-- form  step 2 資料詳細-->
                                <div class="row form-horizontal" v-show="step == 2">
                                    <div class="col-md-12">
                                        <h4 class="text-red" v-show="process == 'revise'" ><i class="fas fa-exclamation-triangle"></i>  {{ trans('sellManage.S_ReviseAmountNotice13') }}</h4>
                                        <h4 class="" v-show="process == 'Inf'">
                                            <i class="fas fa-check-double"></i>
                                            {!! trans('sellManage.S_ReviseAmountNotice14', ['reserve_no' => '@{{ orderData["revise_amount"]["data"]["created_at"] }}', 'update_account' => '@{{ orderData["revise_amount"]["data"]["update_account"] }}']) !!}
                                        </h4>
                                        <div class="modal-overflow">
                                            <!-- 0713 新增 修改資訊呈現  -->
                                                <div class="col-xs-12">
                                                    <div class="row">
                                                        <div class="min-25 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="rate-box">
                                                                <div class="rate-box-content"><span class="rate-box-text">{{ trans('sellManage.S_ReviseAmountNotice1') }}</span>
                                                                    <div class="flex-end"><span class="rate-box-number">@{{ orderData['reserve_no'] }} </span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="min-25 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="rate-box">
                                                                <div class="rate-box-content"><span class="rate-box-text">{{ trans('sellManage.S_ReviseAmountNotice2') }}</span>
                                                                    <div class="flex-end"><span class="rate-box-number">@{{ orderData['consumer_name'] }} </span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="min-25 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="rate-box">
                                                                <div class="rate-box-content"><span class="rate-box-text">{{ trans('sellManage.S_ReviseAmountNotice3') }}</span>
                                                                    <div class="flex-end"><span class="rate-box-number">@{{ orderData['tel_num'] }} </span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="min-25 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="rate-box">
                                                                <div class="rate-box-content"><span class="rate-box-text">{{ trans('sellManage.S_ReviseAmountNotice4') }}</span>
                                                                    <div class="flex-end"><span class="rate-box-number">@{{ orderData['mail_address'] }} </span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- TABLE -->
                                                        <div class="table-responsive">
                                                            <table id="" class="table table-striped table-white">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="10%">{{ trans('sellManage.S_EventDetailSeatName') }}</th>
                                                                        <th width="15%">{{ trans('sellManage.S_EventDetailTicketName') }}</th>
                                                                        <th width="15%">{{ trans('sellManage.S_EventDetailSeatPosition') }}</th>
                                                                        <!-- 金額＆數字置右 -->
                                                                        <th class="text-right">{{ trans('sellManage.S_ReviseAmountNotice5') }}</th>
                                                                        <th class="text-right pr-20 ">{{ trans('sellManage.S_ReviseAmountNotice15') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for='seat in reviseSeatPrice'>
                                                                        <td>@{{ seat.seatTitle }}</td>
                                                                        <td>@{{ seat.ticketTitle }}</td>
                                                                        <td>@{{ seat.seatPosition }}</td>
                                                                        <td width="300" class="text-right __comma">@{{ seat['oriPrice'] }} + @{{ seat['commissionSum'] }}</td>
                                                                        <td width="300" class="text-right __comma pr-20 font-700">@{{ seat['price'] }} + @{{ seat['commissionSum'] }}</td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot class="">
                                                                    <tr>
                                                                        <th colspan="5">
                                                                            <div class="total-box-non w-75">
                                                                                <table class="table table-gray">
                                                                                    <tr>
                                                                                        <th>{{ trans('sellManage.S_ReviseAmountNotice16') }}</th>
                                                                                        <td width="220" class="text-right text-gray ">@{{ orderData['reservation_commission'] }}</td>
                                                                                        <td width="220" class="text-right">@{{ orderData['reservation_commission'] }}</td>
                                                                                    </tr>
                                                                                    <tr class="total-foot">
                                                                                        <th>{{ trans('sellManage.S_ReviseAmountNotice17') }}</th>
                                                                                        <td class="text-right __comma text-gray">@{{ oriPriceSum }}</td>
                                                                                        <td class="text-right __comma">@{{ reviseAmount }}
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    <!--  /.TABLE-->
                                                    <!-- 修改原因 -->
                                                        <div class="form-group">
                                                            <label class="col-md-2 text-black font-700">
                                                                {{ trans('sellManage.S_ReviseAmountNotice7') }}
                                                            </label>
                                                            <div class="col-md-10"><p>@{{ reviseMemo }}</p> </div>
                                                        </div>
                                                    <!--/.修改原因-->
                                                </div>
                                            <!-- 0713 新增 /.修改資訊呈現 -->
                                            </div>
                                        </div>
                                    </div>
                            <!-- form  step 2 資料詳細 -->
                        </template>
                    <!--訂單金額修改-->
                    <!--統一頁-->
                        <!-- form  step 4 結果畫面 返回結果-->
                        <div class="row form-horizontal" v-show="step == '-2'">
                            <div class="col-md-12">
                                <!-- form-group -->
                                <h5 class="text-center">@{{ resultMsn }}</h5>
                                <h4 class="text-center text-red"></h4>
                                <div class="text-center">
                                    
                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>
                        <!-- form  step 4 結果畫面 返回結果-->
                        <!-- form  step 4 結果畫面 返回錯誤結果-->
                        <div class="row form-horizontal" v-show="step == '-3'">
                            <div class="col-md-12">
                                <!-- form-group -->
                                <h3>
                                    <i class="fas fa-times-circle text-red"></i> 
                                    @{{ resultMsn }}
                                </h3>
                                <!-- /.form-group -->
                            </div>
                        </div>
                        <!-- form  step 4 結果畫面 返回錯誤結果-->
                        <!-- form  step -1 loading 畫面-->
                        <div class="row form-horizontal" v-show="step == '-1'">
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
                        <!-- form  step -1 loading 畫面-->
                    <!--統一頁-->
                </div>
                <!-- /.form  -->
                <div class="modal-footer" >
                    <!-- form  step 1-->
                    <button class="btn btn-default pull-left" v-show="step == '1'" v-on:click="resultClose()">
                        {{ trans('userManage.S_DialogCancel') }}
                    </button>
                    <!--出票-->
                        <template v-if="dialogType == 'draw'">
                            <button class="btn btn-primary pull-right" v-show="step == '1' && memberIsSelect" v-on:click="nextStep()">
                                {{ trans('sellManage.S_Next') }}
                            </button>
                            <!-- /.form  step 1-->
                            <!-- form  step 2 -->
                            <button class="btn btn-default pull-left" v-show="step == '2' && !isInsert" v-on:click="prevStep1()">
                                {{ trans('sellManage.S_Back') }}
                            </button>
                            <button class="btn btn-primary pull-right" v-show="step == '2' && !isInsert"  v-on:click="ticketDataCheack()">
                                {{ trans('sellManage.S_Next') }}
                            </button>
                            <!--
                            <button class="btn btn-primary pull-right" v-show="step == '2' && !isInsert"  v-on:click="sendSeatInf('insert')">
                                下一步
                            </button>-->
                            <!-- /.form  step 2-->
                            <!-- form  step 3 確認資料-->
                            <button class="btn btn-default pull-left" v-show="step == '3' && !isInsert" v-on:click="prevStep2()">
                                {{ trans('sellManage.S_Back') }}
                            </button>
                            <button class="btn btn-danger pull-right" v-show="step == '3' && !isInsert" v-on:click="sendSeatInf('insert')">
                                {{ trans('sellManage.S_TicketingConfirm') }}
                            </button>
                            <!-- /.form  step 3 確認資料-->
                            <!-- form  重新寄發-->
                            <button class="btn btn-default pull-left" v-show="step == '3' && isInsert" v-on:click="resultClose()">
                                取消
                            </button>
                            <button class="btn btn-danger pull-right" v-show="step == '3' && isInsert  && !performance_disp_status" v-on:click="sendSeatInf('resend')">
                                {{ trans('sellManage.S_TicketingResend') }}
                            </button>
                            <!-- /.form  重新寄發-->
                        </template>
                    <!--出票-->
                    <!--訂單取消-->
                        <template v-if="dialogType == 'orderCancel'">
                            <button class="btn btn-primary pull-right" v-show="step == '1'" v-on:click="openOrderCancelStep2()">
                                {{ trans('sellManage.S_Next') }}
                            </button>
                            <button class="btn btn-default pull-left" v-show="step == '2' &&  !isCancel"  v-on:click="openOrderCancelprevStep1()">
                                {{ trans('sellManage.S_Back') }}
                            </button>
                            <button class="btn btn-danger pull-right" v-show="step == '2' &&  !isCancel"  v-on:click="sendOrderCancelInf()">
                                {{ trans('sellManage.S_ReserveCancel') }}
                            </button>
                        </template>
                    <!--訂單取消-->
                    <!--訂單金額修改-->
                        <template v-if="dialogType == 'reviseAmount'">   
                            <button class="btn btn-primary pull-right" v-show="step == '1'"  v-on:click="openReviseAmountStep2()">
                                {{ trans('sellManage.S_Next') }}
                            </button>
                            <button class="btn btn-default pull-left" v-show="step == '2' && process == 'revise'"  v-on:click="openReviseAmountStep1()">
                                {{ trans('sellManage.S_Back') }}
                            </button>
                            <button class="btn btn-danger pull-right" v-show="step == '2' && process == 'revise'"  v-on:click="sendReviseAmountInf()">
                                {{ trans('sellManage.S_ReviseAmountNotice18') }}
                            </button>
                        </template>
                    <!--訂單金額修改-->
                    <!-- form  step 4-->
                        <button class="btn btn-inverse pull-left" v-show="step == '-3' || step == '-2' || (step == '2' && isCancel && dialogType == 'orderCancel') || (step == '2' && process == 'Inf' && dialogType == 'reviseAmount')" v-on:click="resultClose()">
                            確認
                        </button>
                    <!-- /.form  step 4-->
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-dialog -->
    <!-- 出票 -->
</div>

<script>
    const SchKbn = "{{ $event['sch_kbn'] }}"
    $(document).ready(function () {
        $('.togglesettings').hide();
        $('.showpageblock').on('click', function () {
            var $t = $(this).closest('.editblocks').next().find('.togglesettings').stop(true).slideToggle();
            //$('.togglesettings').not($t).stop(true).slideUp();
            return false;
        });
    });
    //銀行code資料
    const bankCode = {!! (\App::getLocale() == "ja" )?json_encode(\Config::get('bankcode.jp')):json_encode(\Config::get('bankcode.tw')) !!}
    /**
     * ajax function
     * 
     * @parm url
     * @return promise
     */
    const getJson = function(url){
        const promise = new Promise(function(resolve, reject){
            const handler = function (){
                if(this.readyState !== 4){
                    return;
                }
                if(this.status === 200){
                    resolve(this.response)
                }else{
                    reject(new Error(this.statusText))
                }
            }
            const client = new XMLHttpRequest()
            client.open("GET", url)
            client.onreadystatechange   = handler
            client.responsetype         = "json"
            client.setRequestHeader("Accept", "application/json")
            client.send()
        })

        return promise
    }
    Vue.config.devtools = true;
    var orderDetail = new Vue({
        el: '#orderDetail',
        data:{
           bankinf : bankCode,
           perfomanceTitle : "{{ $event['perfomanceTitle'] }}",
           performance_disp_status : false,
           openDate : "{{ $event['openDate'] }}",
           orderData : {!! json_encode($event['reservationData']) !!},
           filtertData :  {!! json_encode($event['reservationData']) !!},
           freeSeatInt: {!! json_encode($event['freeSeatInt']) !!},
           keyword : '',
           orderRange : '',
           seatType : 'all',
           fTicketType : 'all',
           dateRangeStar : '',
           dateRangeEnd : '',
           seatName : '',
           ticketName : '',
           filterJson : [],
           notPaymentMethod: true,
           payCash : false,
           payCredit : true,
           payIbon : false,
           paySevenEleven : true,
           payFree : true,
           noTPickup : true,
           pickup : true,
           qrpass : false,
           ibon : false,
           sevenEleven : true,
           resuq : true,
           noTicketing : true,
           noPickup : true,
           noTissue : true,
           issue : true,
           noIssue : true,
           notReceipt : true,
           receipt : true,
           noReceipt : true,
           seatOrder : true,
           seatFree : true,
           seatReserve : true,
           orderStatus : [],
           seatOption : {!! json_encode( $event['seatOption'] ) !!},
           ticketOption : {!! json_encode( $event['ticketOption'] ) !!},
           totalPie : {{ $event['allPie'] }},
           totalPrice : {{ $event['allTicketPrice'] }},
           totalCost : {{ $event['allPriceCost'] }},
           receivedAmount : {{ $event['received_amount_sum'] }},
           csv : '',
           showModal : false, 
           step: 3,
           isInsert: false,
           nowTicketIndex:4,
           nowTicketNo:1,
           pickupMethod : 'MOBAPASS',
           pickupMethodTitle : '',
           phoneNum : '',
           mail : '',
           mobapassId : '',
           seatTitle : '',
           seatPosition : '',
           json : '',
           resultMsn: '',
           showMembersInf:false,
           memberKeyword: '',
           members: [],
           membersTotal: 0,
           membersShowTotal: 8,
           memberId: '',
           memberName: '',
           memberUserId: '',
           memberIsSelect: false,
           ticketType: '',
           freeSeatType: -1,
           dialogType:'',
           orderId:'',
           reserve_no : '',
           reserve_date : '',
           orderSeatData : [],
           refund_kbn : '',
           pickup_method : '',
           bankId : '',
           bankCode : '',
           bankName : '',
           branchName : '',
           bankAccount : '',
           refundPayment : '',
           use_point : '',
           refundPaymentLimit : '',
           refundCheck : true,
           isCancel : '',
           search_loading : false,
           process:'',
           reviseAmount: '',
           reviseMemo:'',
           orderData: '',
           errorWarm: '',
           reviseSeatPrice:[],
           oriPriceSum:0,
           questionnaires : {!! json_encode($event['questionnaires']) !!},
           popResult:[],
           seatSaleId: '',
           visitStatus: '',
           fullRefund: 'basisRefund',
           refundPaymentDisplay: 0,
           refundTextDisplay: true,
        }, 
        watch: {
            reviseSeatPrice: {
                immediate: true,
                deep: true,
                handler(newValue, oldValue) {
                    let reviseSum   = 0

                    this.reviseSeatPrice.forEach(function(seat){
                        reviseSum += parseInt(seat.price) +  parseInt(seat.commissionSum)
                    });
                   
                    this.reviseAmount = reviseSum + this.orderData['reservation_commission']
                }
            },
            refundPayment: {
                immediate: true,
                deep: true,
                handler(newValue, oldValue) {
                    this.refundPaymentDisplay = newValue.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
                }
            },
        },
        methods: {
            refundDisplay : function(){
                let result = true
                
                if(this.refund_kbn == '{{ \Config::get('constant.pay_method.free') }}'){
                    result = false
                }

                if(this.refund_kbn == '{{ \Config::get('constant.pay_method.store') }}'){
                    result = false
                }
                return result
            },
            /**
            入場按鈕是否顯示
            @param object inf
            @return bool result
             */
            getVisitButtonFlg:function(inf)
            {
                let result = false;

                if(inf.payment_flg && inf.order_type != 2){
                    switch(inf.pickup_method){
                        case 3:
                            if(inf.issue_flg){
                                result = true
                            }
                            break
                        default:
                            result = true
                    }
                }

                return result;
            },
            /**
            設定入場狀態修改 pop 開啟
            @param string visitText 
            @return int visitNo
             */
            getVisitFlg:function(visitText){
                let visitNo = 2
                switch(visitText) {
                    case "{{ trans('sellManage.S_EventDetailGot') }}":
                        visitNo = 1
                        break;
                    case "{{ trans('sellManage.S_EventDetailNotGet') }}":
                        visitNo = 2
                        break;
                }
                return visitNo
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
            修改訂單入場資料
            @param object data
             */
            setOrderVisit:function(data){
                let orderId = data.order_id
                let seatSaleId = data.seat_sale_id
                let visitText = ""
              
                let orderIndex = this.filtertData.findIndex(function(item, index, array){
                    return item.order_id == orderId;      
                });

                let ticketIndex = this.filtertData[orderIndex].seatData.findIndex(function(item, index, array){
                    return item.seat_sale_id == seatSaleId;      
                });
               
                this.filtertData[orderIndex].seatData[ticketIndex].visit_flg = visitText

                if(data.visit_flg){
                    visitText = "{{ trans('sellManage.S_EventDetailGot') }}"
                }else{
                    visitText ="{{ trans('sellManage.S_EventDetailNotGet') }}"
                }

                this.filtertData[orderIndex].seatData[ticketIndex].visit_flg = visitText

                let odersVisit = this.filtertData[orderIndex].seatData.some(function(item, index, array){
                    return item.visit_flg != visitText;      
                });

                if(odersVisit){
                    this.filtertData[orderIndex].visit_flg = "{{ trans('sellManage.S_EventDetailIncomplete') }}"
                }else{
                    this.filtertData[orderIndex].visit_flg = visitText
                }
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
                    orderDetail.setOrderVisit(result.data)
                    orderDetail.setPopResult(result)
                }).catch((err) => {
                    this.popResult['step'] = 3 //STS 2021/09/10 Task 48 No.2
                    this.popResult['message'] = '{{ trans('sellManage.S_AdmissionMessageFail') }}'//STS 2021/09/10 Task 48 No.2
                    console.log('錯誤:', err);
                });
            },
            /**
            取得票位置或區號
            @param object ticket
            @return string seatPosition
             */
            getTicketPosition:function(ticket){
                let seatPosition = ''
                
                if(SchKbn == 1){
                    switch(ticket['seatType']) {
                        case 1:
                            seatPosition = ticket['seatPosition']
                            break;
                        case 'R':
                            seatPosition = ticket['seat_number']
                            break;
                        default:
                            seatPosition = '-'
                    }
                }

               return seatPosition
            },
            /**
             * 訂單修改金額資料送出
             */
            sendReviseAmountInf:function(){
                let data        = []
                let inf         = []
                let status      = []
                let revise_info = [] 
            
                this.reviseSeatPrice.forEach(function(seat){
                    revise_info.push({
                        'seat_sale_id'  : seat.seat_sale_id, 
                        'ori_price'     : seat.oriPrice,
                        'seat_price'    : seat.price, 
                    })
                });
                
                inf.push({
                    'order_id'      : this.orderId, 
                    'amount_total'  : this.reviseAmount,
                    'amount_memo'   : this.reviseMemo,
                    'revise_info'   : JSON.stringify(revise_info),
                })

                data.push({
                    'status'    : status,
                    'inf'       : inf,
                })

                this.json = JSON.stringify(data)
               
                this.$nextTick(() => {
                    document.getElementById("reviseAmount").submit();
                    loading.openLoading()
                })
            },
            /**
             * 訂單修改金額檢查
             *
             * 修改金額 - 不能大於原金額 & 不能小於零 & 只能是數字
             * 修改原因 - 不能為空
             */
            openReviseAmountStep2:function(){
                let checkResult = true

                this.errorWarm   = ''
                this.refundCheck = false

                this.reviseSeatPrice.forEach(function(seat){
                    seat.warnStatuc = false 
                    seat.warnNotice = ''

                    let reNum      = /^[0-9]*$/
                    let seatPrice  = parseInt(seat.price)
                    let oriPrice   = parseInt(seat.oriPrice)

                    if(!reNum.test(seatPrice)){
                        checkResult     = false
                        seat.warnStatuc = true 
                        seat.warnNotice += '{{ trans('sellManage.S_ReviseAmountNotice19') }}'
                    }

                    if(seatPrice <= 0){
                        checkResult     = false
                        seat.warnStatuc = true 
                        seat.warnNotice += ' {{ trans('sellManage.S_ReviseAmountNotice20') }}'
                    }

                    if(seatPrice > oriPrice){
                        checkResult     = false
                        seat.warnStatuc = true 
                        seat.warnNotice += ' {{ trans('sellManage.S_ReviseAmountNotice21') }}'
                    }
                });

                if(this.reviseMemo.length <= 0){
                    this.errorWarm   = '{{ trans('sellManage.S_ReviseAmountNotice22') }}'
                    this.refundCheck = true
                    checkResult      = false
                }

                this.$forceUpdate()
                
                if(checkResult){
                    this.step = 2
                }
            },
            openReviseAmountStep1:function(){
                this.step = 1
            },
            /**
             * 訂單金額修改 Dialog 開啓
             */
            openReviseAmount:function(index, orderId, step, type, process){
                this.orderData          = this.filtertData[index]
                this.nowTicketIndex     = index
                this.orderId            = orderId
                this.dialogType         = type
                this.step               = step
                this.process            = process
                let oriPriceSum         = 0
                let seatData            = []
                
                if(process == 'revise'){
                    this.reviseAmount = this.orderData['allCost']
                    this.reviseMemo   = ''
                  
                    oriPriceSum  = this.orderData['reservation_commission']

                    this.orderData['seatData'].forEach(function(seat){
                        let price = seat.price - seat.seat_commission_sum

                        oriPriceSum += price

                        seatData.push({
                            'seat_sale_id'  : seat.seat_sale_id, 
                            'seatPosition'  : seat.seatPosition,
                            'seatTitle'     : seat.seatTitle,
                            'ticketTitle'   : seat.ticketTitle,
                            'commissionSum' : seat.seat_commission_sum, 
                            'oriPrice'      : price,
                            'price'         : price, 
                            'warnStatuc'    : false,
                            'warnNotice'    : '',
                        })
                    });

                }else{
                    this.reviseAmount = this.orderData['revise_amount']['data']['amount_total']
                    this.reviseMemo   = this.orderData['revise_amount']['data']['amount_memo']
                    let reviseData    = this.orderData['revise_amount']['data']

                    oriPriceSum  = this.orderData['reservation_commission']

                    this.orderData['seatData'].forEach(function(seat){
                        let price = seat.price - seat.seat_commission_sum

                        let reviseInf = reviseData.revise_info.find(function(item, index, array){
                            return item.seat_sale_id === seat.seat_sale_id           
                        });
                    
                        oriPriceSum += parseInt(reviseInf.ori_price) + parseInt(seat.seat_commission_sum)

                        seatData.push({
                            'seat_sale_id'  : seat.seat_sale_id, 
                            'seatPosition'  : seat.seatPosition,
                            'seatTitle'     : seat.seatTitle,
                            'ticketTitle'   : seat.ticketTitle,
                            'commissionSum' : seat.seat_commission_sum, 
                            'oriPrice'      : reviseInf.ori_price,
                            'price'         : reviseInf.seat_price, 
                            'warnStatuc'    : false,
                            'warnNotice'    : '',
                        })
                    });
                }

                this.oriPriceSum        = oriPriceSum
                this.reviseSeatPrice    = seatData
                this.showModal          = true
                this.isCancel           = true
                this.refundCheck        = false
            },
            refundDataCheck: function(){ 
                var re          = /^\d+(\.\d{1,2})?$/ 
                let reNum       =/^[0-9]*$/
                let refundCheck = false
                
                try {
                    if(this.refundPayment  == "" && this.refund_kbn != '{{ \Config::get('constant.pay_method.free') }}'){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice01') }}'));
                    }

                    if(!reNum.test(this.refundPayment)){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice02') }}'));
                    }

                    if(this.refundPayment < 0){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice03') }}'));
                    }

                    if(parseInt(this.refundPayment) > this.refundPaymentLimit){
                        throw (new Error('{{ trans('sellManage.S_CancelNotice04') }}'));
                    }

                    if(this.refund_kbn != '{{ \Config::get('constant.pay_method.card') }}' && this.refund_kbn != '{{ \Config::get('constant.pay_method.store') }}' && this.refund_kbn != '{{ \Config::get('constant.pay_method.free') }}'){
                        if(this.bankCode == ""){
                            throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                        }else{
                            if(!reNum.test(this.bankCode)){
                                throw (new Error('{{ trans('sellManage.S_CancelNotice06') }}'));
                            }
                        }
                        if(this.bankName == ""){
                            throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                        }
                        @if(\App::getLocale() == "ja" )
                            if(this.branchName == ""){
                                throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                            }
                        @endif

                        if(this.bankAccount == ""){
                            throw (new Error('{{ trans('sellManage.S_CancelNotice05') }}'));
                        }else{
                            if(!reNum.test(this.bankAccount)){
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
            bankCodeChange:function(){
                let id =  parseInt(this.bankCode)

                if(typeof this.bankinf[id] !== 'undefined'){
                    let bankinf = this.bankinf[id]
                    this.bankId   = this.bankCode
                    this.bankCode = bankinf[0]
                    this.bankName = bankinf[1]
                }else{
                    let bankinf = null
                    this.bankId = null
                    this.bankName = ""
                }
            },
            bankSelectChange:function(event){
                let id        = parseInt(event.target.value)
                let bankinf   = this.bankinf[id]
                this.bankCode = bankinf[0]
                this.bankName = bankinf[1]
            },
            /**
             * 訂單取消 Dialog 開起
             */
            openOrderCancel:function(index, orderId, step){
                let filtertData         = this.filtertData[index]
                this.orderData          = filtertData
                this.nowTicketIndex     = index
                this.orderId            = orderId
                this.dialogType         = 'orderCancel'
                this.step               = step
                this.reserve_no         = filtertData['reserve_no']
                this.refund_kbn         = filtertData['pay_method']
                this.pickup_method      = filtertData['pickup_method']
                this.reserve_date       = filtertData['reserve_date']
                this.orderSeatData      = filtertData['seatData']
                this.showModal          = true
                this.isCancel           = true
                this.refundCheck        = false
                this.fullRefund = 'basisRefund'
                
                if(filtertData['cancel_flg']){
                    let data                = filtertData['refund_inf']
                    let refund_inf          = JSON.parse(data['refund_inf'])

                    this.bankCode           = refund_inf['bankCode']
                    this.bankName           = refund_inf['bankName']
                    this.branchName         = refund_inf['branchName']
                    this.bankAccount        = refund_inf['bankAccount']
                    this.refundPayment = Math.round(data['refund_payment'])
                }else{
                    //未取消
                    this.bankCode           = ''
                    this.bankName           = ''
                    this.branchName         = ''
                    this.bankAccount        = ''

                  
                    this.setPaymentTotal()
                       
                    this.use_point = filtertData['use_point'];
                }
              
                this.$nextTick(() => {
                    allThousandsTransform()
                })
            },
            setPaymentTotal : function(){
                let filtertData = this.orderData

                if(filtertData['revise_amount']['reviseStatus']){
                        this.refundPayment      = filtertData['revise_amount']['data']['amount_total']
                        this.refundPaymentLimit = filtertData['revise_amount']['data']['amount_total']
                }else{
                    if(filtertData['pay_method'] === 2 && filtertData['pickup_method'] === 3)
                            {
                        //card/seven
                        this.refundPayment  = parseInt(filtertData['total_price']) + parseInt(filtertData['commission_ticket']);// - parseInt(filtertData['use_point'])//予約取消金額
                        this.refundTextDisplay = true
                    }else if(filtertData['pay_method'] === 20){
                        // 無料
                        this.refundPayment  = parseInt(filtertData['allCost'])//予約取消金額
                        this.refundTextDisplay = false
                    }else if(
                        (filtertData['pay_method'] == 3 && filtertData['pickup_method'] == '{{ \Config::get('constant.pickup_method.store') }}') ||
                        (filtertData['pay_method'] == 3 && filtertData['pickup_method'] == '{{ \Config::get('constant.pickup_method.resuq') }}') ||
                        (filtertData['pay_method'] == 3 && filtertData['pickup_method'] == '{{ \Config::get('constant.pickup_method.no_ticketing') }}') 
                    ){
                        //seven/seven
                        this.refundPayment  = parseInt(filtertData['allCost'])//予約取消金額
                        this.refundTextDisplay = false
                       
                    }else{
                        //card/resQ card/mbps
                        this.refundPayment  = parseInt(filtertData['total_price'])//予約取消金額
                        this.refundTextDisplay = false             
                    }
                    this.refundPaymentLimit = filtertData['allCost']
                }
            },
            openOrderCancelStep2:function(){
                let checkResult = this.refundDataCheck()
                
                if(checkResult){
                    this.step           = 2
                    this.isCancel       = false
                }
            },
            openOrderCancelprevStep1:function(){
                this.step           = 1
            },
            sendOrderCancelInf:function(){
                let data    = []
                let inf     = []
                let bankinf = []
                let status  = []
                let seatInf = ''
 
                bankinf.push({
                    'bankCode'     : this.bankCode,
                    'bankName'     : this.bankName,
                    'branchName'   : this.branchName,
                    'bankAccount'  : this.bankAccount,
                })

                inf.push({
                    'orderId'      : this.orderId, 
                    'refund_kbn'   : this.refund_kbn,
                    'refundPayment': this.refundPayment,
                    'use_point'    : this.use_point,
                    'bankinf'      : bankinf,
                })

                data.push({
                    'status'    : status,
                    'inf'       : inf,
                })

                this.json = JSON.stringify(data)
               
                this.$nextTick(() => {
                    document.getElementById("orderCancel").submit();
                    loading.openLoading()
                })
            },
            memberOnSelect:function(index){
                this.memberId       = index
                this.memberIsSelect = true
            },
            memberSearch:function(){
                this.search_loading = true
                this.memberId       = ''
                this.members        = []
                this.memberIsSelect = false
                let json = [];
                getJson('/sell/members/'+this.memberKeyword).then(function(data){
                    json = JSON.parse(data)
                    orderDetail.showMembersInf      =  true
                    orderDetail.membersTotal        =  json.status.searchTotal
                    orderDetail.members             =  json.data.userInf
                    orderDetail.membersShowTotal    =  (json.status.searchTotal > 8)?8:json.status.searchTotal
                    orderDetail.search_loading      =  false
                },function(error){
                   
                })

                
            },
            resultClose:function(){
                this.showModal = false
            },
            openTicketSetting:function(index, no, step, type = 'seat'){
                this.nowTicketIndex = index
                this.nowTicketNo    = no
                this.step           = step
                this.showModal      = true
                this.isInsert       = false
                this.pickupMethod   = 'MOBAPASS'
                this.phoneNum       = null
                this.mail           = null
                this.ticketType     = type
                this.dialogType     = 'draw'

                if(step == 3){
                    this.isInsert       = true
                    let data            = this.filtertData[this.nowTicketIndex].seatData[this.nowTicketNo]
                    this.memberUserId   = data.memberId
                    this.memberName     = data.consumerName
                    this.seatTitle      = data.seatTitle
                    this.seatPosition   = data.seatPosition
                    this.phoneNum       = data.orderInf.tel_num
                    this.mail           = data.orderInf.mail_address
                    //this.mobapassId     = data.orderInf.tel_num
                    switch(data.orderInf.pickup_method){
                        case 3:
                            this.pickupMethod       = 'sevenEleven'
                            this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailConvenience') }}'
                            break
                        case 9:
                            this.pickupMethod       = 'MOBAPASS'
                            this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailPickup_ET') }}'
                            break
                        case 31:
                            this.pickupMethod       = 'ibon'
                            this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailIbon') }}'
                            break
                        case 91:
                            this.pickupMethod       = 'QRPASS'
                            this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailQrpass') }}'
                            break
                        case 8:
                            this.pickupMethod       = 'resuq'
                            this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailResuQ') }}'
                            break
                        default:
                            this.pickupMethod       = '-'
                            this.pickupMethodTitle  = '-'

                    }
                }
            },
            /**
             * 回到步驟一
             */
            prevStep1:function(){
                this.step                =  1
                this.showMembersInf      =  false
                this.membersTotal        =  0
                this.members             =  []
                this.membersShowTotal    =  0
                this.memberId            =  ''
                this.memberIsSelect      =  false
                this.memberKeyword       =  null
            },
            prevStep2:function(){
                this.step = 2
            },
            /**
             * 出票第二步驟，選擇出票方式
             */
            nextStep:function(){
                let member = this.members[this.memberId]

                this.step           =   2
                this.pickupMethod   =   'mobapass'
                this.memberName     =   member.name
                this.memberUserId   =   member.user_id 
                this.phoneNum       =   member.tel
                this.mail           =   member.email
                this.mobapassId     =   member.moba_id
            },
            /**
             * 出票第三步驟，選擇出票方式
             */
            ticketDataCheack:function(){
                this.step           =   3
                if(this.ticketType === 'seat'){
                    let data            = this.filtertData[this.nowTicketIndex].seatData[this.nowTicketNo]
                    this.seatTitle      = data.seatTitle
                    this.seatPosition   = data.seatPosition
                }else{
                    let data            = this.freeSeatInt[this.freeSeatType]
                    this.seatTitle      = data.inf.seatTitle
                    this.seatPosition   = ''
                }
                switch(this.pickupMethod){
                    case 'sevenEleven':
                        this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailConvenience') }}'
                        break
                    case 'resuq':
                        this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailResuq') }}'
                        break
                    case 'mobapass':
                        this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailPickup_ET') }}'
                        break
                    case 'ibon':
                        this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailIbon') }}'
                        break
                    case 'qrpass':
                        this.pickupMethodTitle  = '{{ trans('sellManage.S_EventDetailQrpass') }}'
                        break
                    default:
                        this.pickupMethodTitle  = '-'

                }
            },
            sendSeatInf:function(type){
                this.step = -1

                let data    = []
                let inf     = []
                let status  = []
                let seatInf = ''

                if(this.ticketType === 'seat'){
                    seatInf = this.filtertData[this.nowTicketIndex].seatData[this.nowTicketNo]
                }else{
                    seatInf = this.freeSeatInt[this.freeSeatType]
                }

                status.push({
                    'ticketType'    : this.ticketType,
                })

                inf.push({
                    'memberId'      : this.memberUserId,
                    'memberName'    : this.memberName,
                    'pickupMethod'  : this.pickupMethod,
                    'phoneNum'      : this.phoneNum,
                    'mail'          : this.mail,
                    'mobapassId'    : this.mobapassId,
                })

                data.push({
                    'status'    : status,
                    'inf'       : inf,
                    'seatInf'   : seatInf,
                })

                this.json = JSON.stringify(data)

                this.$nextTick(() => {
                    if(type === 'insert'){
                        document.getElementById("sendSeatInf").submit();
                    }else if(type === 'resend'){
                        document.getElementById("resendNotice").submit();
                    }
                })
            },
            closeTicketSetting:function(){
                this.showModal = false
            },
            init:function(){
                $('.togglesettings').hide();
                $('.showpageblock').unbind()
                $('.showpageblock').on('click', function () {
                    var $t = $(this).closest('.editblocks').next().find('.togglesettings').stop(true).slideToggle();

                    return false;
                });
            },
            dateClear:function(){
                this.dateRangeStar = ''
                this.dateRangeEnd = ''
                this.orderRange = ''
            },
            csvEx:function(){
                document.getElementById("getCsv").submit();
            },
            sreachData:function(){
               status = {
                    'filter'    :   true,
               }

               inf = {
                    'keyword'           : this.keyword,
                    'orderRange'        : this.orderRange,
                    'dateRangeStar'     : this.dateRangeStar,
                    'dateRangeEnd'      : this.dateRangeEnd,
                    'seatType'          : this.seatType,     
                    'ticketType'        : this.fTicketType,
                    'notPaymentMethod'  : this.notPaymentMethod,
                    'payCash'           : this.payCash,
                    'payCredit'         : this.payCredit,
                    'payIbon'           : this.payIbon,
                    'paySevenEleven'    : this.paySevenEleven,
                    'payFree'           : this.payFree,
                    'noTPickup'         : this.noTPickup,
                    'pickup'            : this.pickup,
                    'qrpass'            : this.qrpass,
                    'ibon'              : this.ibon,
                    'sevenEleven'       : this.sevenEleven,
                    'resuq'             : this.resuq,
                    'noTicketing'       : this.noTicketing,
                    'noTissue'          : this.noTissue,
                    'issue'             : this.issue,
                    'noIssue'           : this.noIssue,
                    'notReceipt'        : this.notReceipt,
                    'receipt'           : this.receipt,
                    'noReceipt'         : this.noReceipt,
                    'seatFree'          : this.seatFree,
                    'seatOrder'         : this.seatOrder,
                    'seatReserve'       : this.seatReserve,
                    'orderStatus'       : this.orderStatus,
                }

                json = {
                    'status'    : status,
                    'inf'       : inf,
                }
                
                this.filterJson = JSON.stringify(json)
                
                this.$nextTick(() => {
                    document.getElementById("filterOrder").submit()
                })
            },
            dataSort:function(){
                let data = this.filtertData
                let totalPie = 0
                let totalPrice = 0
                let allCost = 0
                let receivedAmountSum = 0
               

                data.forEach(function(element) {
                    totalPie += element.total_pie
                    let num = 0
                    let payMethod = ''
                    let pickupMethod  = ''
                    let issueFlg = ''
                    let paymentFlg = ''
                  
                    receivedAmountSum += parseFloat(element.received_amount)

                    if(element.total_price !== '-'){
                        totalPrice += parseFloat(element.total_price)
                        if(element.revise_amount.data.status){
                            allCost += parseFloat(element.revise_amount.data.amount_total)
                        }else{
                            allCost += parseFloat(element.allCost)
                        }
                    }

                    element.seatData.forEach(function(ele){
                        num++
                      
                      
                        if(ele.seatType == 3){

                        }else{
                            let total_pie   = ''
                            let total_price = ''
                            let allCost     = ''

                            if(num == 1){
                              total_pie   = element.total_pie
                              total_price = element.total_price
                              allCost     = element.allCost
                            }
                        }
                    })
                });

                this.totalPie = totalPie
                this.totalPrice = totalPrice
                this.totalCost = allCost 
                this.receivedAmount = receivedAmountSum
            },
            omittedText(text, length) {
                return length < text.length ? text.slice(0, length) + "..." : text;
            },
            setFullRefund : function(){
                if(this.fullRefund == 'fullRefund'){ 
                    this.refundPayment = this.orderData['allCost']
                    this.refundTextDisplay = true
                }else{
                    this.setPaymentTotal()
                }
            }
        },
        mounted(){
            this.performance_disp_status =  ["8", "7", "-1", "-99"].includes("{{ $event['performance_disp_status'] }}")
           
            $('#daterange').daterangepicker({ 
                autoUpdateInput: false,
            })
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                orderDetail.orderRange = picker.startDate.format('YYYY/MM/DD') + ' ~ ' + picker.endDate.format('YYYY/MM/DD')
                orderDetail.dateRangeStar = picker.startDate.format('YYYY/MM/DD')
                orderDetail.dateRangeEnd = picker.endDate.format('YYYY/MM/DD')
            });

            this.dataSort()

            this.orderStatus = {
                'normal'    : true,
                'cancel'    : true,
                'systemCancel'    : true,
                'timeoutCancel'    : false,
            }

            this.popResult = {
                'show' : false,
                'step' : 0,
                'message' : '',
            }

            @if (count($errors) > 0) 
                this.showModal  = true
                this.step       = -2
                this.resultMsn  = '{{ $errors->first() }}'
            @endif

            @if ($event['insert_draw_result'])
                this.showModal  = true
                this.step       = -2
                this.resultMsn  = "{{ $event['insert_draw_msn'] }}"
            @endif

            @if ($event['resend_draw_result'])
                this.showModal  = true
                this.step       = -2
               // this.resultMsn  = '重新傳送取票資訊成功'
                this.resultMsn  = '再送信に成功しました。'
            @endif

            @if ($event['cancel_order_result'])
                this.showModal  = true
                this.resultMsn  = "{{ $event['cancel_order_msn'] }}"
                this.dialogType = 'orderCancel'
                
                let cancelStatus = "{{ $event['cancel_order_status'] }}"

                if(cancelStatus){
                    this.step       = -2
                }else{
                    this.step       = -3
                }
            @endif
           
            @if ($event['revise_amount_result'])
                this.showModal  = true
                this.step       = -2
                this.resultMsn  = "{{ $event['revise_amount_msn'] }}"
                this.dialogType = 'reviseAmount'
            @endif
           
            @if($event['filterJson'])
                this.filterJson = '{!! addslashes($event['filterJson']) !!}'
                let filterJson  = JSON.parse(this.filterJson)

                this.keyword            = filterJson.inf.keyword
                this.orderRange         = filterJson.inf.orderRange
                this.dateRangeStar      = filterJson.inf.dateRangeStar
                this.dateRangeEnd       = filterJson.inf.dateRangeEnd
                this.seatType           = filterJson.inf.seatType
                this.fTicketType        = filterJson.inf.ticketType
                this.notPaymentMethod   = filterJson.inf.notPaymentMethod
                this.payCash            = filterJson.inf.payCash
                this.payCredit          = filterJson.inf.payCredit
                this.payIbon            = filterJson.inf.payIbon
                this.paySevenEleven     = filterJson.inf.paySevenEleven
                this.payFree            = filterJson.inf.payFree
                this.noTPickup          = filterJson.inf.noTPickup
                this.pickup             = filterJson.inf.pickup
                this.qrpass             = filterJson.inf.qrpass
                this.ibon               = filterJson.inf.ibon
                this.sevenEleven        = filterJson.inf.sevenEleven
                this.resuq              = filterJson.inf.resuq
                this.noTicketing        = filterJson.inf.noTicketing
                this.noTissue           = filterJson.inf.noTissue
                this.issue              = filterJson.inf.issue
                this.noIssue            = filterJson.inf.noIssue
                this.notReceipt         = filterJson.inf.notReceipt
                this.receipt            = filterJson.inf.receipt
                this.noReceipt          = filterJson.inf.noReceipt
                this.seatFree           = filterJson.inf.seatFree
                this.seatOrder          = filterJson.inf.seatOrder
                this.seatReserve        = filterJson.inf.seatReserve
                this.orderStatus        = filterJson.inf.orderStatus
            @endif

            $('[data-toggle="tooltip"]').tooltip();
        },
    });

    // sidebar 
    $(document).ready(function () {
      $('.sidebar-menu').tree()
    })
    $(function () {
      //Initialize Select2 Elements - multiple & tag
      $('.select2').select2()
    })
</script>
@stop


    
