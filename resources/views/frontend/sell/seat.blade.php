@inject('SaleManagePresenter', 'App\Presenters\SaleManagePresenter')
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
<h1>
    {{trans('sellManage.S_MainTitle')}}<!--<small>明細一覽｜座席圖</small>-->
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    <li><a href="/sell" onclick="loading.openLoading()">{{trans('sellManage.S_SubTitle_1')}}</a></li>
    <li><a href="/sell/manage/{{ $eventsInf['data']['performanceId'] }}" onclick="loading.openLoading()">{{trans('sellManage.S_SubTitle_2')}}</a></li>
    <li class="active">{{ $SaleManagePresenter->getSeatSettingTitle($eventsInf['data']['seatmap_profile_cd']) }}</li>
</ol>
<!-- /.網站導覽 -->
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
    <!-- /.新增子選單 -->
<div>
<!-- 0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
@if(session('event_info_flg') == 2)
    <div class="funtion-btn-block">
        <button id="updateBtn" type="button" class="btn waves-effect waves-light btn-rounded btn-normal"> 
            {{trans('events.S_SaveBtn')}}
        </button>
    </div>
@endif
<!-- /.0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<form id="settingSend" method="POST" style="visibility: collapse;" action="/sell/seat/" enctype="multipart/form-data">
    <input id="settingContent" type="hidden" name="json" >
    {{ csrf_field() }} 
</form>
<div class="box box-solid">
    <div class="box-body">
        <!-- FORMGROUP 2  Grid + BTN  -->
        <div class="row-group-grid">
            <!-- Row1 -->
            <div class="row ">
                <div class="col-xs-2 col-sm-2 grid-title">{{ trans('sellManage.S_EventTitle') }}</div>
                <div class="col-xs-10 col-sm-10 grid-text">{{ $eventsInf['data']['performanceName']  }}</div>
            </div>
            <!-- /.Row1 -->
            <!-- 2021/06/23 STS - Task 24 - Show current performance date and time -- START -->
           <!-- Row2 -->
             <div class="row ">
                <div class="col-xs-2 col-sm-2 grid-title">{{ trans('sellManage.S_EventOpenDate') }}</div>
                <div class="col-xs-10 col-sm-10 grid-text">{{ $eventsInf['data']['date'] }} {{ $SaleManagePresenter->timeTransform($eventsInf['data']['time']) }}</div>
            </div>
             <!-- /.Row2 -->
            <!-- 2021/06/23 STS - Task 24  -- END -->
            <!-- Row3 -->
            <div class="row {{ ($eventsInf['data']['performanceStatuc'] == 7)? 'bg-yellow':'' }}">
                <div class="col-xs-2 col-sm-2 grid-title">{{ trans('sellManage.S_EventChangedate') }}</div>
                <div class="col-xs-10 col-sm-10 grid-text form-group form-group-fix">
                    <!-- Date Picker -->
                    <div class="input-group date col-lg-3 col-sm-4 col-xs-12">
                        <!-- STS 2021/06/21: Task 24: create daterangepicker to changing performance schedule  -->
                        <input id="searchDate" type="text" class="form-control pull-right">
                        <!-- <input type="text" class="form-control pull-right" value="{{ $eventsInf['data']['date'] }}" readonly> -->
                        <div class="input-group-addon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <!-- Time Picker -->
                    <div class="bootstrap-timepicker col-lg-2 col-sm-3">
                        <div class="input-group">
                            <!-- STS 2021/06/21: Task 24: change the date and time by switching to another date and time -->
                            <!-- <input type="text" class="form-control timepicker" value="{{ $SaleManagePresenter->timeTransform($eventsInf['data']['time']) }}" readonly> -->
                           <select name="start-time" class="form-control timepicker" id="start-time"></select>
                            <div class="input-group-addon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Search Button -->
                    <div class="mr-auto">
                        <button id="searchBtn" type="button" class="btn btn-info-outline btn-mm"> 
                            {{ trans('sellManage.S_EventMove') }}
                        </button>
                        @if ($eventsInf['data']['performanceStatuc'] == 7)
                            <span class="badge bg-red">中止</span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.Row3 --><!--  2021/06/23 - STS - Task 24 -->
        </div>
    </div>
</div>
<!-- /.FORMGROUP 2  Grid + BTN  -->
<!-- 席種設定 -->
<div class="tab-pane" id="seat">
    <div id="seatSetting">
    <div class="row form-horizontal">
        <!-- col -->
        <div class="col-md-12">

        <!-- BOX 2 - box-title + form group -->
        <div class="box no-border">
            <div class="box-header with-border-non" data-widget="collapse">
            <div class="row form-horizontal box-title-block">
                <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{ trans('events.S_SeatTotal') }}
                    </label>
                    <div class="col-sm-8">
                    <input type="" class="form-control" :value="seatTotal" readonly>
                    </div>
                </div>
                <!-- /.form-group -->
               </div>
                <!-- /.col -->
                <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-5 control-label">
                        {{ trans('events.S_SeatSell') }}
                    </label>
                    <div class="col-sm-7">
                        <input type="" class="form-control" :value="seatSetting" readonly>
                    </div>
                </div>
                <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <!-- 20200903 新增販售管理-自由席數 -->
                <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        自由席数
                    </label>
                    <div class="col-sm-8">
                    <input type="" class="form-control"  v-model='freeSeatTotal' readonly>
                    </div>
                </div>
                </div>
                <!-- /.20200903 新增販售管理-自由席數 -->
            </div>
            <!-- /.col -->
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool">
                <i class="fa fa-minus"></i>
                </button>
            </div>
            </div>
            <!---/.box-header--->

            <div class="box-body">
                <!-- Blocks -->
                <div class="row">
                    <!-- 指定席 ＋ 自由席  -->
                    <div class=" col-md-12 card-wrap">
                        <template v-for="(data, index) in typeSeat">
                            <div class="col-lg col-md-3 col-sm-4 mb-4" v-if="data.seat_class_kbn == {!! \Config::get('constant.seat_class_kbn.reserved') !!} && data.status !== 'D'">
                                <div class="stats-small card card-small" :style="{backgroundColor: data.seat_class_color}">
                                    <div class="card-body ">
                                        <div class="flex-column m-auto">
                                        <div class="stats-small__title text-left">
                                            {{-- <div class="stats-small__label text-uppercase"> --}}
                                            <div class="stats-small__label text-uppercase" :style="{color :seatCardStyle(data.seat_class_color)}">
                                                @{{ data.seat_class_name }}
                                            </div>
                                            <div class="stats-small__value count my-3"></div>
                                        </div>
                                        <div class="stats-small__form-group">
                                            <div class="stats-small__forms stats-small_forms__1">
                                            <!--20200903 新增與調整-->
                                            <div class="form-flex stats-small__one w-30" :style="{color :seatCardStyle(data.seat_class_color)}">🈯︎</div>
                                            <div class="num-block w-70" :style="{color :seatCardStyle(data.seat_class_color)}" >@{{ data.typeSell }}/@{{ data.typeTotal }} {{ trans('events.S_SeatUnit') }}</div>
                                            <!--/.20200903 新增與調整-->
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg col-md-3 col-sm-4 mb-4" v-if="data.seat_class_kbn == {!! \Config::get('constant.seat_class_kbn.unreserved') !!} && data.status !== 'D'">
                                <div class="stats-small stats-small card card-wite card-small">
                                    <div class="card-body">
                                        <div class="flex-column m-auto">
                                            <div class="stats-small__title text-left">
                                                <div class="stats-small__label text-uppercase">@{{ data.seat_class_name }} </div>
                                                <div class="stats-small__value count my-3">
                                                    <span class="help is-danger" v-show='data.errorStatus'>
                                                        <i class="fas fa-exclamation-circle"></i> @{{ data.errorMsn }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="stats-small__form-group">
                                                <div class="stats-small__forms stats-small_forms__1">
                                                <div class="form-flex stats-small__one w-10">🈚︎</div> 
                                                <!--<div class="form-flex">
                                                    <input type="" class="form-control in-small border__blue" id="" placeholder="{{ trans('events.S_feeSeatType') }}" disabled="disabled">
                                                </div>-->
                                                <div class="form-flex w-40">
                                                <input type="number" min=0 class="form-control in-small border__blue"  @change="typeTotalChange(index)" a v-model='data.typeTotal'>
                                                </div>
                                                <div class="form-flex num-block w-50">@{{ data.typeSell }}/@{{ data.typeTotal }} {{ trans('events.S_SeatUnit') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 20200903 新增販售管理-自由席設定 -->
                            <!--
                                <div class="col-lg col-md-3 col-sm-4 mb-4">
                                    <div class="stats-small stats-small card card-wite card-small">
                                        <div class="card-body">
                                            <div class="flex-column m-auto">
                                                <div class="stats-small__title text-left">
                                                    <div class="stats-small__label text-uppercase">自由</div> 
                                                    <div class="stats-small__value count my-3"></div>
                                                </div> 
                                                <div class="stats-small__form-group">
                                                    <div class="stats-small__forms stats-small_forms__1">
                                                    <div class="form-flex w-10">🈚︎</div> 
                                                    <div class="form-flex w-50">
                                                        <input type="number" min="0" id="" class="form-control in-small">
                                                        位
                                                    </div>
                                                    <div class="form-flex num-block w-50 ">0/0 席</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                            <!------------ /.20200903 新增販售管理-自由席設定 ------------------>
                        </template>
                    </div>
                    <!-- /.指定席 ＋ 自由席  -->
                    <!-- 保留席  -->
                    <div class=" col-md-12 card-wrap">
                        <template v-for="(data,index) in reserveSeat">
                            <div class="col-lg col-md-3 col-sm-4 mb-4" v-if='data.status !== "D"'>
                                <div class="stats-small stats-small card card-wite card-small">
                                    <div class="card-body">
                                        <div class="flex-column m-auto">
                                            <div class="stats-small__title text-left">
                                                <div class="stats-small__label text-uppercase">@{{ data.reserve_name }}</div>
                                                <div class="stats-small__value stats-roundbox count":style="{backgroundColor: data.text_color}">@{{ data.text }}</div>
                                            </div>
                                            <div class="stats-small__form-group">
                                                <div class="stats-small__forms stats-small_forms__1">
                                                    <!--<div class="form-flex w-10"></div>-->
                                                    <div class="form-flex stats-small__one w-30">
                                                        <span class="stats-small__box">押え</span>
                                                        <!--<input type="" class="form-control in-small border__blue" id="" value="保留" readonly>-->
                                                    </div>
                                                    <div class="form-flex w-70">
                                                        <div class="stats-small__form-group">
                                                            <div class="stats-small__forms stats-small_forms__1">
                                                                <div class="">@{{ data.order_total }}/@{{ data.total }} {{ trans('events.S_SeatUnit') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- /.保留席  -->
                </div>
            </div>
            <!-- /.box-body-->
        </div>
        <!-- /.BOX 2 -->
        </div>
        <!-- /.col -->

        <!-- 樓層場地 -->
        <div class="col-md-12" v-show='Object.keys(this.mapData).length > 0'>
        <!--  樓層tab -->
        <!-- 20201023 錯誤訊息提示 -->
        <!--<div class="callout callout-txt-warning "><div class="icon"><i class="fas fa-exclamation-triangle"></i></div> <p>錯誤訊息提示</p></div>-->
            <ul id="tagControl" class="nav nav-tabs nav-tabs-floor">
                <template v-for="(floor, key, index) in mapData">
                    <!-- 錯誤提示出現時li 需新增class---- error-line 或是 Style---- border: 2px solid #e44f1e; -->
                        <li v-bind:class="{active: index == 0 }" >
                            <a id="basisInfPage" :href="'#'+key" class="tabs-basic" data-toggle="tab" aria-expanded="true" @click="settingFunction(key)">
                                @{{ key }}
                            </a>
                        </li>
                </template>
            </ul>
            <div class="tab-content tab-floor-content">
                <!-- BLOCK --><!--<button v-on:click="getMapData()">lalala</button>-->
                <template v-for="(floor, key, index) in mapData">
                    <div class="tab-pane content-width" v-bind:class="{active: index == 0 }" :id="key">
                        <div id="floorSetting-1f">
                            <div class="row form-horizontal">
                                <div class="col-md-12 floor-content">
                                    <div class="col-md-4 floor-map">
                                        <h5 class="text-center mb-4">{{ trans('events.S_venuePicTitle') }}</h5>
                                        <div class="drop-image">
                                            <input name="image" type="file" id="" class="dropify floor-img" :data-default-file="floor.imageUrl" @change="imageUpload" disabled="disabled"/>
                                        </div>
                                    </div>
                                    <div class="col-md-8 floor-direction">
                                        <h5 class="text-center mb-4">{{ trans('events.S_blockListTitle') }}
                                        {{-- <button type="button"
                                            class="btn btn-xs waves-effect waves-light btn-rounded btn-success pull-right">全座席表示</button>--}}
                                        </h5> 
                                        <div class="col-sm-12">
                                            <template v-for="data in floor.blockData">
                                                <div :id="key+data.blockTittle" class="col-sm-2 d-block" v-on:click="seatDataChange(key, data.blockTittle)">
                                                    <div class="d-block-name">@{{data.blockTittle}}</div>
                                                    <div v-if="data.direction == 4" class="d-block-arrow"><i class="far fa-arrow-alt-circle-right"></i></div>
                                                    <div v-else-if="data.direction == 2" class="d-block-arrow"><i class="far fa-arrow-alt-circle-down"></i></div>
                                                    <div v-else-if="data.direction == 3" class="d-block-arrow"><i class="far fa-arrow-alt-circle-left"></i></div>
                                                    <div v-else class="d-block-arrow"><i class="far fa-arrow-alt-circle-up"></i></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <!-- 座席設定-->
                                <div class="col-sm-12">
                                <h5 class="text-center floor-settings-title">
                                    {{ trans('events.S_seatSettingTilte') }} - @{{ nowFloor }} @{{ nowBlock }}
                                    <!---圖例--->
                          <div class="toolkey-box">
                            <a class="toolkey btn-key">
                              <div class="toolkey-text">{{ trans('events.S_seatSymbol') }}</div>
                              <div class="toolkey-left">
                                <div class="row" >
                                  <div class="col-sm-3"><img src="/assets/images/seat/i-seat-order.svg">{{ trans('events.S_seatReserved') }}</div>
                                  <div class="col-sm-3"><img src="/assets/images/seat/i-seat-pay.svg">{{ trans('events.S_seatPayment') }}</div>
                                  <div class="col-sm-3"><img src="/assets/images/seat/i-seat-sold.svg">{{ trans('events.S_seatSold') }}</div>
                                 <!-- <div class="col-sm-3"><img src="/assets/images/seat/i-seat-situation.svg">{{ trans('events.S_seaPrblemt') }}</div> -->
                                </div>
                              </div>
                            </a>
                          </div>
                          <!---圖例--->
                                </h5>
                                <div class="row floor-settings">
                                    <div class="col-md-12 d-flex-bt">
                                        <div class="form-group form-d-flex">
                                            <div class="input-group my-colorpicker2 colorpicker-element form-group-flex col-sm-5">
                                                <select id="SeatSettingOption" class="form-control m-r-10" @change="seatUnitChange()" v-model="seatUnit">
                                                    <template v-for="(data, index) in dataSet">
                                                        <option :value="index" v-if="data.setting">
                                                            @{{ data.title }}
                                                        </option> 
                                                    </template>    
                                                </select>
                                                <div class="input-group-addon input-group-h35"  v-bind:style="{backgroundColor: colorNow}">
                                                    
                                                        @{{ nowText }}
                                                    
                                                </div>
                                            </div>
                                            <label class="d-h-spec">@{{ nowUnitTotal }}{{ trans('events.S_SeatUnit') }}</label>
                                            <!-- /.input group -->
                                            <div class="floor-btn-group" v-bind:style="{ visibility: (statuc == 7)?'hidden':'' }">
                                                <button type="button" class="btn btn-info" v-on:click="saveSeatIsSelect()" :disabled="settingBtn">{{ trans('events.S_btnSeatSetup') }}</button>
                                                <button type="button" class="btn btn-inverse" v-on:click="clearSeatIsSelect()">{{ trans('events.S_btnSeatRelease') }}</button>
                                            </div>
                                        </div>
                                        <!-- 方向表示 -->
                                        <div class="col-sm-2 d-block">
                                    <div class="d-block-name">@{{ nowBlock }}</div>
                                    <div v-if="mapDirection == 4" class="d-block-arrow"><i class="far fa-arrow-alt-circle-right"></i></div>
                                    <div v-else-if="mapDirection == 2" class="d-block-arrow"><i class="far fa-arrow-alt-circle-down"></i></div>
                                    <div v-else-if="mapDirection == 3" class="d-block-arrow"><i class="far fa-arrow-alt-circle-left"></i></div>
                                    <div v-else class="d-block-arrow"><i class="far fa-arrow-alt-circle-up"></i></div>
                                    </div>
                                        <!-- /．方向表示 -->
                                        <div class="form-group form-d-flex ">
                                            <div class="floor-btn-group">
                                                <button type="button" class="btn btn-default" :disabled="zoomOutBtn" @click="zoomOutTable">{{ trans('events.S_btnZoomin') }}</button>
                                                <button type="button" class="btn btn-default" :disabled="zoomInBtn"  @click="zoomInTable">{{ trans('events.S_btnZoomout') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <!-- 座席位置表 -->
                                <!--<div class="col-sm-2 d-block mb-6">
                                    <div class="d-block-name">@{{ nowBlock }}</div>
                                    <div v-if="mapDirection == 0" class="d-block-arrow"><i class="far fa-arrow-alt-circle-up"></i></div>
                                    <div v-else-if="mapDirection == 2" class="d-block-arrow"><i class="far fa-arrow-alt-circle-down"></i></div>
                                    <div v-else-if="mapDirection == 3" class="d-block-arrow"><i class="far fa-arrow-alt-circle-left"></i></div>
                                    <div v-else class="d-block-arrow"><i class="far fa-arrow-alt-circle-right"></i></div>
                                </div>-->
                                <!---->
                                <div id="seatMap" class="floor-settings-table">
                                    <table class="table table-non-bordered" v-bind:style="[mapStyle]"> 
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th v-for="num in rowLenght">@{{  num  }}</th>
                                            </tr>
                                        </thead>
                                        <tbody v-html="mapCreate" :key="mapKey"></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </template>
                <!-- /.BLOCK -->        
            </div>
        <!--  /.樓層tab -->
        </div>
        <!-- /.樓層場地-->
    </div>
    <div id="pop-up-result">
        <div class="modal-mask" v-show="uploadResultShow">
            <div class="modal-dialog">
                <div class="modal-content" >
                    <div class="modal-header">    
                        <h4 class="modal-title">
                            更新結果
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-horizontal">
                            <div class="col-md-12">
                                <div>
                                    <!-- 0302 調整新增 樣式 -->
                                    <div class="messages-content messages-save-content-pop">
                                        <h3>
                                            <template v-if="upload_result['book_error']">
                                                <i class="fas fa-times-circle text-red"></i>
                                                <template v-if="upload_result['message'] == ''">
                                                    以下の座席は仮予約/予約済の為、更新できませんでした。
                                                </template>
                                                <template v-else>
                                                    @{{ upload_result['message'] }}
                                                </template>
                                            </template>
                                            <template v-else>
                                                <i class="fas fa-check-circle text-aqua"></i> 
                                                成功
                                            </template>
                                        </h3>
                                        <ol class="result-tab-title" v-if="upload_result['book_error']">
                                            <div class="modal-overflow">
                                                <ul class="result-tab-subtitle modal-list">
                                                    <li v-for="data in upload_result['seat_book']">
                                                        @{{ data.floor }} - @{{ data.block }} - @{{ data.rowname }} - @{{ data.number }} 
                                                    </li>
                                                </ul>
                                            </div>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" >
                        <button class="btn btn-inverse pull-left" v-on:click="closeUploadResultShow()">
                            {{ trans('events.S_Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <transition name="slide-fade">  
        <div class="modal-mask" v-show='showModal'>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('events.S_setReserved') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-horizontal">
                                <div class="col-md-12">
                            
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">{{ trans('events.S_reserveNameTitle') }}</label>
                                        <div class="col-md-10">
                                            <input  name="text" type="text" class="form-control" v-model="specSeatTitle">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">{{ trans('events.S_reserveSymbol') }}</label>
                                        <div class="col-md-10 form-group-flex">
                                            <div class="w-5">
                                                <input name="text" type="text" maxlength="1" class="form-control" placeholder="" v-model="specSeatText">
                                            </div>
                                            <div class="has-feedback mml-2 w-20">
                                                <!-- 0511 調整 -->
                                                <div class="colorpick-box">
                                                    <div id="saetSettingColorPick" data-color="#2ECC71" class="colorPickSelector form-control" style="background-color: rgb(46, 204, 113); color: rgb(46, 204, 113);">
                                                    </div>
                                                    <div class="color-arrow"><i class="fas fa-caret-down"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" @click="closeDialog()">取 消</button>
                        <button id="" class="btn btn-inverse" @click="addSpecTicket()">{{ trans('basisInf.S_Apply') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
    <!--loading-->
    <transition name="slide-fade">
        <div class="modal-overlay" style="display: block" v-show="loading">
            <div class="modal-loading">
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
        </div>
    </transition>
    <!-- /.loading -->
    </div>
</div>
<!-- /.
 -->

<script>
window.is_confirm = true
window.addEventListener('beforeunload', (event) => {
    if (window.is_confirm){
        event.preventDefault();
        event.returnValue = '';
        loading.closeLoading()
    }
});

//2021/06/24 - STS - Task 24: href to selected performance schedule - START
$(document).ready(function(){ 
    $('#searchBtn').click(function(){ 
        var redir = $('#start-time :selected').attr("value")
        window.location.href = '/'+ redir;
    });
});
//2021/06/24 - STS - Task 24 - END

//2021-06-23 - STS - Task 24: filling datarangepicker and combobox --- START---
var dates = []
var scheduleList = JSON.parse('{!! json_encode($events["schedule_inf"]) !!}');
scheduleList.forEach(item => {
    if(!dates.includes(item.performance_date))
    {
        dates.push(item.performance_date)
    }
})

$('#searchDate').daterangepicker({
    "locale": {
        "format": "YYYY-MM-DD"
    },
    timePicker: false,
    singleDatePicker: true,
    autoUpdateInput: true,
    startDate: "{{$eventsInf['data']['date']}}",
    isInvalidDate: function(date) {
        let currDate = date.format('YYYY-MM-DD');
        return (dates.indexOf(currDate)==-1);
    },

});

$('#searchDate').on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY-MM-DD');
            onDateChange(date);
         });

function onDateChange(date) {
    let scheduleTime = "{{$eventsInf['data']['time']}}"
    let scheduleDate = "{{$eventsInf['data']['date']}}"
    let draftId = "{{$eventsInf['data']['draftId']}}"
    let options = ''
    let schedules = scheduleList.filter(x => x.performance_date === date)
    schedules.forEach(item => {
        if(draftId!=-1)
        {
            let dateValue = item.date_value;
            let ruleId = item.rule_id;
            if(scheduleDate == date && item.start_time == scheduleTime) {
                options += `<option id="-1" value="sell/unpublished/seat/${draftId}/${dateValue}/${ruleId}" selected> ${item.start_time.slice(0,5)}</option>`
            } 
            else options += `<option  id="-1" value="sell/unpublished/seat/${draftId}/${dateValue}/${ruleId}">${item.start_time.slice(0,5)}</option>`
        }
        else
        {
            if(scheduleDate == date && item.start_time == scheduleTime) {
                options += `<option id="${item.schedule_id}" value="sell/seat/${item.schedule_id}" selected> ${item.start_time.slice(0,5)}</option>`
            } 
            else options += `<option  id="${item.schedule_id}" value="sell/seat/${item.schedule_id}">${item.start_time.slice(0,5)}</option>`
        }
    })
    $('#start-time').html(options)
}
//2021-06-23 - STS - Task 24 - END

const seatMapSetting = new Vue({
    el: '#seatSetting',
    data: {
        dateValue: '',
        ruleId:'',
        publish: true,
        loading: true,
        statuc: '',
        typeSeat:'',
        reserveSeat:'',
        seatSelect:'',
        seatReserve:'',
        seatSetting:'',
        seatTotal:'',
        mapData:'',
        nowFloor:'',
        nowBlock:'',
        mapDirection:'',
        mapCreate:'',
        rowLenght:'',
        seatUnit:'',
        nowText:'',
        nowUnitTotal:'',
        settingBtn:true,
        dataSet:[],
        zoomInBtn:'',
        mapStyle: {
            zoom: '1'
        },
        scale: 1,
        zoomOutBtn: false,
        zoomInBtn: false,
        settingBtnOption:true,
        settingBtnSel:true,
        showModal:false,
        specSeatTitle:'',
        specSeatText:'',
        specSeatColor:'',
        colorNow:'',
        mapKey: 0,
        freeSeatTotal:0,
        uploadResultShow: false,
        upload_result:[],
    },
    watch: {
        settingBtnSel: function (val) {
            if(this.settingBtnOption || val){
                this.settingBtn = true
            }
            if(!val && !this.settingBtnOption){
                this.settingBtn = false
            }
        },
        settingBtnOption: function (val) {
            if(!val && !this.settingBtnSel){
                this.settingBtn = false
            }
        },
        nowFloor: function(val){
            if(typeof(this.mapData[val]) !== 'undefined'){
                let blcok = Object.keys(this.mapData[val]['blockData'])

                if(typeof(blcok[0]) !== 'undefined'){
                    this.nowBlock = blcok[0]
                }
            }
            this.seatDataChange(this.nowFloor, this.nowBlock)
        },
    },
    created: function() {

    },
    mounted:function(){
        sessionStorage.setItem('sellMapData','{!! addslashes(json_encode($eventsInf)) !!}')
        let sellMapData = JSON.parse(sessionStorage.getItem("sellMapData"))
        this.dateValue = "{{ $eventsInf['data']['date_value'] }}"
        this.publish = "{{ $eventsInf['data']['publish_status'] }}"
        this.ruleId = "{{ $eventsInf['data']['rule_id'] }}"
        this.statuc =  sellMapData['data']['performanceStatuc']
        this.typeSeat = sellMapData['data']['typeSeat']
        this.reserveSeat = sellMapData['data']['reserveData']
        this.seatSelect = sellMapData['data']['seatSelect']
        this.seatReserve = sellMapData['data']['seatReserve']
        this.seatSetting = sellMapData['data']['seat_receive']
        this.seatTotal = sellMapData['data']['seatTotal']
        this.mapData = sellMapData['data']['seatMap']
        this.upload_result = sellMapData['data']['upload_result']
        onDateChange('{{ $eventsInf['data']['date'] }}') //2021-06-23 STS - TASK 24

        if(this.upload_result['upload']){
            this.uploadResultShow = true
        }

        if(typeof(this.mapData) !== 'undefined' && Object.keys(this.mapData).length > 0){
            let floor = Object.keys(this.mapData)

            if(typeof(floor[0]) !== 'undefined'){
                this.nowFloor = floor[0]
                let blcok = Object.keys(this.mapData[floor[0]]['blockData'])

                if(typeof(blcok[0]) !== 'undefined'){
                    this.nowBlock = blcok[0]
                }
            }
            this.settingFunction(this.nowFloor)
            this.seatDataChange(this.nowFloor, this.nowBlock)
            this.seatUnit = ''
            this.$nextTick(() => {
                this.seatUnitChange()
                this.countSeatSelect()
            })
        }
        this.countFreeSeatTotal()
        this.loading = false
    },
    methods: {
        closeUploadResultShow: function(){
          this.uploadResultShow = false  
        },
        typeTotalChange: function(index){
            this.typeSeat[index].status = 'U'
            this.checkTypeSeat()
            this.countFreeSeatTotal()
            this.countTotal()
        },
        countFreeSeatTotal: function(){
            let total = 0

            this.typeSeat.forEach(function(item) {
                if(!item.errorStatus){
                    total += parseInt(item.typeTotal)
                }
            })

            this.freeSeatTotal = total
        },
        /**
         * 檢查自由席是否符合規範
         */
        checkTypeSeat: function(){
            let errorStatus = false
            let reNum       =/^[0-9]*$/
            let num         =  /^\d+$/

            this.typeSeat.forEach(function(item) {
                item.errorStatus = false
                item.errorMsn = ''
                try{
                    if(!num.test(item.typeTotal)){
                        throw (new Error('数字で入力ください'))
                    }
                    if(parseInt(item.typeTotal) < 0){
                        throw (new Error('正の整数を入力してください'))
                    }
                }catch (e){
                    item.errorStatus = true
                    item.errorMsn = e.message
                    errorStatus = true
                }
            })
            
            return errorStatus
        },
        /**
         * 所有資料是否符合規範
         */
        checkInf: function(){
            let typeSeatResult = this.checkTypeSeat()

            if(typeSeatResult){
                return false
            }else{
                return true
            }
        },
        seatCardStyle: function(backgroundColor){
                let color = ['#AE8445','#44C1B4','#67A934','#D96C7D','#8F4AC5','#385AB0','#7A5C2F','#1A747B','#22730A','#B1253B','#60238F','#013573']
                let style = '#3A3A3A'

                if(color.includes(backgroundColor)){
                    style = '#FFFFFF'
                }
                
                return style
            },
        closeDialog:function(){
            this.showModal = false
            this.specSeatTitle = ''
            this.specSeatText = ''
            this.specSeatColor = ''
            document.body.style.overflowY = "scroll";
        },
        addSpecTicket:function(){
        
            this.reserveSeat.push({
                color: this.specSeatColor,
                reserve_code: '',
                reserve_name:  this.specSeatTitle,
                status: "I",
                text: this.specSeatText,
                text_color: this.specSeatColor,
                total: 0,
            })

            this.closeDialog()
            this.seatSettingInf()
        },
        imageUpload:function($event){
            let img = $event.target.files[0]
            const form = new FormData();
            form.append('file', img);
            form.append('location', basisSetting.imageLocation)
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/blockImage/import',
                type: 'POST',
                data: form,
                cache: false,
                processData: false,
                contentType: false,
                success: function(data, textStatus, jqXHR)
                {
                    console.log(data)
                    seatSetting.mapData[seatSetting.nowFloor]['imageUrl'] = data.url
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log('ERRORS: ' + textStatus)
                }
            });

        },
        seatSettingInf:function(){
            let seatSettingData = []
            let num = 1

            if(this.typeSeat){
                this.typeSeat.forEach(function(element) {
                    if(element.seat_class_kbn == {!! \Config::get('constant.seat_class_kbn.reserved') !!}){
                        let setting =  (element.status == 'D')?false:true
                        seatSettingData.push({
                            type: 2,
                            typeId:　element.class_id,
                            setting: setting,
                            id: num,
                            title: element.seat_class_name,
                            color: element.seat_class_color,
                            text: '',
                            total: element.typeTotal,
                            index: element.index,
                        })
                        num++
                    }
                })
            }

            num = 1
            if(this.reserveSeat){
                this.reserveSeat.forEach(function(element) {
                    let setting =  (element.status == 'D')?false:true
                    seatSettingData.push({
                        type: 1,
                        typeId:　element.reserve_code,
                        setting: setting,
                        id: num,
                        title: element.reserve_name,
                        color: element.text_color,
                        text: element.text,
                        total: element.total,
                        index: element.index,
                    })
                    num++
                })
            }
            this.$nextTick(() => {
                this.dataSet = seatSettingData
                this.nowUnitTotal = this.dataSet[this.seatUnit].total
            })
        },
        settingFunction:function(floor){
            this.nowFloor = floor
            this.seatSettingInf()
            $.getScript("{{ asset('js/dropify.min.js') }}", function(){
                $('.dropify').dropify({
                   tpl: {
                          wrap: '<div class="dropify-wrapper dropify-wrapper-h420"></div>',
                          loader: '<div class="dropify-loader"></div>',
                          message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p>{{trans("common.S_DropifyMsg")}}</p></div>',
                          preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{trans("common.S_DropifyEdit")}}</p></div></div></div>',
                          filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
                          clearButton: '<button type="button" class="btn"></button>',
                          errorLine: '<p class="dropify-error">{{trans("common.S_DropifyErr")}}</p>',
                          errorsContainer: '<div class="dropify-errors-container"><ul>{{trans("common.S_DropifyErr")}}</ul></div>'
                    }
                });
            });
        },
        seatDataChange:function(floor, block){
            let mapData = this.mapData[floor]['blockData'][block]
            let xLine = this.mapData[floor]['blockData'][block].x
            let xLineSort = []
            let yLineSort = []
            let yLine  = this.mapData[floor]['blockData'][block].y
            let mapTilteOri = this.mapData[floor]['blockData'][block].line
            let mapTilte = []
            let mapSubTilteOri = this.mapData[floor]['blockData'][block].lineNum
            let mapSubTilte = []
            this.mapDirection = this.mapData[floor].blockData[block].direction
            this.nowFloor = floor
            this.nowBlock = block
            let nowSeatData = this.mapData[floor].blockData[block].seatData
            let x_max = mapData['x_max']
            let x_min = mapData['x_min']
            let y_min = mapData['y_min']
            let y_max = mapData['y_max']
            let mapDraw = ''
            let reservedSid = []
            let seatDirCN = ''

            this.selectSeatClear()
            
            this.tableHidden = true
            switch(this.mapDirection) {
                case 1: //↑
                    mapTilte = []
                    $.each(mapTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(yLine)
                    var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    var lineNum = 0
                    $.each(yLine, function(index, seat){
                        titleSet[index-starNum] = mapTilte[lineNum]
                        lineNum++
                    });
                    
                    mapSubTilte = titleSet
                    break;
                case 2: //↓
                    mapTilte = []
                    $.each(mapTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(yLine)
                    var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    var lineNum = 0
                    $.each(yLine, function(index, seat){
                        titleSet[index-starNum] = mapTilte[lineNum]
                        lineNum++
                    });
                    
                    mapSubTilte = titleSet
                    break;
                case 3: //←
                    mapTilte = []
                    $.each(mapSubTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(yLine)
                    // var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var arrayLenght = y_max;
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    // var lineNum = 0
                    // $.each(yLine, function(index, seat){
                    //     // titleSet[index-starNum] = mapTilte[lineNum]
                    //     titleSet[index-starNum] = ""
                    //     lineNum++
                    // });
                    
                    mapSubTilte = titleSet
                    break;
                case 4: //→
                    mapTilte = []
                    $.each(mapSubTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(yLine)
                    // var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var arrayLenght = y_max;
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    // var lineNum = 0
                    // $.each(yLine, function(index, seat){
                    //     // titleSet[index-starNum] = mapTilte[lineNum]
                    //     titleSet[index-starNum] = ""
                    //     lineNum++
                    // });
                    
                    mapSubTilte = titleSet
                    break;
                default:
                    this.rowLenght = (x_max - x_min)+1
            } 

            switch(this.mapDirection) {
                case 1://↑
                    mapTilte = []
                    $.each(mapSubTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(xLine)
                    var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    var lineNum = 0
                    $.each(xLine, function(index, seat){
                        // titleSet[index-starNum] = mapTilte[lineNum]
                        titleSet[index-starNum] = ""
                        lineNum++
                    });
                    
                    this.rowLenght = titleSet
                    break;
                case 2: //↓
                    mapTilte = []
                    $.each(mapSubTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                
                    $.each(xLine, function(index, data){
                        let num = parseInt(index, 10) + 1
                        if(typeof(xLine[num]) == 'undefined' && Object.keys(xLineSort).length < Object.keys(xLine).length-1){
                                    xLineSort.push(data)
                                    xLineSort.push('')
                        }else{
                                xLineSort.push(data)
                        }
                    }); 
                    
                    var xLineNum = 0
                    $.each(xLineSort, function(index, data){
                        if(data){   
                            // xLineSort[index] = mapTilte[xLineNum]
                            xLineSort[index] = ""
                            xLineNum++
                        }
                    });                      
                    mapTilte = xLineSort                   
                    this.rowLenght = mapTilte   
                    break;
                case 3: //←
                    mapTilte = []
                    $.each(mapTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(xLine)
                    var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    var lineNum = 0
                    $.each(xLine, function(index, seat){
                        titleSet[index-starNum] = mapTilte[lineNum]
                        lineNum++
                    });
                    
                    this.rowLenght = titleSet
                    break;
                case 4://→
                    mapTilte = []
                    $.each(mapTilteOri, function(index, seat){
                        mapTilte.push(seat)
                    }); 
                    
                    var keys = Object.keys(xLine)
                    var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                    var titleSet = new Array(arrayLenght)
                    var starNum = keys[0]
                    
                    
                    $.each(titleSet, function(index, seat){
                        titleSet[index] = ""
                    });

                    var lineNum = 0
                    $.each(xLine, function(index, seat){
                        titleSet[index-starNum] = mapTilte[lineNum]
                        lineNum++
                    });
                    
                    this.rowLenght = titleSet
                    break;
                default:
                    this.rowLenght = (x_max - x_min)+1
            } 
            let num = 0
            let seatDirTextCN = 0
            for(let i = y_min; i <=  y_max; i++){
                switch(this.mapDirection) {
                    case 1:
                        leftTittle = (typeof(mapSubTilte) === 'undefined')?'':mapSubTilte[num]
                        seatDirCN = 'rotate-0'
                        seatDirTextCN = 180
                        break;
                    case 2:
                        leftTittle = (typeof(mapSubTilte) === 'undefined')?'':mapSubTilte[num]
                        seatDirCN = 'rotate-180'
                        seatDirTextCN = -180
                        break;
                    case 3:
                        leftTittle = ''
                        seatDirCN = 'rotate-270'
                        seatDirTextCN = 180
                        break;
                    case 4:
                        leftTittle = ''
                        seatDirCN = 'rotate-90'
                        seatDirTextCN = -180
                        break;
                    default:
                        this.rowLenght = (x_max - x_min)+1
                } 
               
                mapDraw += '<tr><td>'+leftTittle+'</td>'

                for(let j = x_min; j <= x_max; j++){
                    let xy =  i+'.'+j
                    let nonseat = xy in mapData.seatData
                    let selectData = nowSeatData[xy]
                    
                    if(!nonseat){
                        mapDraw += '<td><div class="s-seat-line">　</div></td>'
                    }else{
                        let color = '#ffffff'
                        
                        if(typeof(selectData['typeData']) !== 'undefined' && typeof(selectData['typeData']['color']) !== 'undefined'){
                            color = selectData['typeData'].color || '#ffffff'
                        }

                        let typeStatus = true
                        let sellStatus = false
                        let seatType = 1
                        let typeText = ''
                        //初使資料
                        if(typeof(typeData) !== 'undefined'){
                            typeStatus = false
                        }else{
                            sellStatus = selectData['sellStatus']
                            seatType = selectData['typeData']['type']
                            typeText = selectData['typeData']['text']
                        }
                       
                        //未發布
                        if(!this.publish){
                            if(typeof selectData.respectiveData == 'undefined'){
                                selectData.respectiveData = []
                            }
                            let filterData = ({
                                'dateValue' :  this.dateValue,
                                'ruleId' : this.ruleId,
                            })

                            let respectiveId = selectData.respectiveData.findIndex(function(ele, index, arr){
                                return ele.dateValue == filterData.dateValue && ele.ruleId == filterData.ruleId
                            }, filterData)
                                    
                            if(respectiveId >= 0){  
                                let respectiveData = selectData.respectiveData[respectiveId]
                                switch (respectiveData.type) {
                                    case 0:
                                    case 3:
                                        typeStatus = false
                                        color = '#ffffff'
                                        break;                  
                                    case 1:
                                        let reserveSeat = this.reserveSeat[respectiveData.index]

                                        seatType = reserveSeat.type
                                        typeText = reserveSeat.text
                                        color = reserveSeat.text_color
                                        break;
                                    case 2:
                                        let typeSeat = this.typeSeat[respectiveData.index]

                                        seatType = typeSeat.type
                                        typeText = ''
                                        color = typeSeat.seat_class_color
                                        break;

                                }
                            }
                        }
                        if(typeStatus){

                            if(sellStatus){
                                let statusStr = ''
                                switch(selectData['sellStatusId']) {
                                    case 1: //仮
                                        statusStr =　'✽'
                                    break;
                                    case 2:　//處理中（未付款、未取票）
                                        statusStr = '○'
                                    break;
                                    case 3:　//済む
                                        statusStr = '◉'
                                    break;
                                    default: //Unknow
                                        statusStr = '？'
                                    break;
                                }
                                mapDraw += '<td class="" id="seat_'+j+'_'+i+'" data-position="'+selectData.rowname+'行'+selectData.number+'列" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="sell" data-status="sell">\
                                                <div class="s-seat-line">\
                                                <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                    <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                        <g id="Gettii-Lite" stroke="none" stroke-width="1" fill="none"fill-rule="evenodd">\
                                                            <g id="Gettii-Lite_Seating-Plan_Template" transform="translate(-302.000000, -334.000000)" fill-rule="nonzero" fill="#d2d2d2" stroke="#181818" stroke-width="2">\
                                                                <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                            <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                            <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                              </g>\
                                                              </g>\
                                                            </g>\
                                                        <text id="" y="45%" x="50%" fill="#BA0050" text-anchor="middle" dominant-baseline="middle" font-weight="600" font-size="25" font-family="Microsoft JhengHei">\
                                                                    <tspan>'+statusStr+'</tspan>\
                                                                </text>\
                                                    </svg>\
                                                </div>\
                                            </td>'
                            }else{
                                if(seatType == 1 || seatType == 'specSeat'){
                                    mapDraw += '<td class="cliseat" id="seat_'+j+'_'+i+'" data-position="'+selectData.rowname+''+selectData.number+'" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="unSelect" data-status="unSelect">\
                                                    <div class="s-seat-line">\
                                                    <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                        <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                         <g id="Gettii-Lite" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                          <g id="Gettii-Lite_Seating-Plan_Template" transform="translate(-302.000000, -334.000000)" fill-rule="nonzero" fill="#FFFFFF" stroke="#FFA800" stroke-width="2">\
                                                           <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                            <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                            <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                           </g>\
                                                          </g>\
                                                         </g>\
                                                            <text id="" y="45%" x="50%" fill="'+color+'" text-anchor="middle" dominant-baseline="middle" font-weight="600" font-size="25" font-family="Microsoft JhengHei">\
                                                                        <tspan>'+typeText+'</tspan>\
                                                                    </text>\
                                                         </svg>\
                                                    </div>\
                                                </td>'
                                }else{
                                    mapDraw += '<td class="cliseat" id="seat_'+j+'_'+i+'" data-position="'+selectData.rowname+''+selectData.number+'" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="unSelect" data-status="unSelect">\
                                                    <div class="s-seat-line">\
                                                    <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                        <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                       <g id="seat" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                         <g id="Seat'+j+'.'+i+'" transform="translate(-302.000000, -334.000000)" fill="'+color+'" fill-rule="nonzero" stroke="#181818" stroke-width="2">\
                                                           <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                              <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                              <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                           </g>\
                                                         </g>\
                                                      </g>\
                                                    </svg>\
                                                    </div>\
                                                </td>'
                                }
                            }

                        }else{
                            mapDraw += '<td class="cliseat" id="seat_'+j+'_'+i+'" data-position="'+selectData.rowname+''+selectData.number+'" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="unSelect" data-status="unSelect">\
                                            <div class="s-seat-line">\
                                            <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                       <g id="seat" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                         <g id="Seat'+j+'.'+i+'" transform="translate(-302.000000, -334.000000)" fill="'+color+'" fill-rule="nonzero" stroke="#181818" stroke-width="2">\
                                                           <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                              <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                              <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                           </g>\
                                                         </g>\
                                                      </g>\
                                                    </svg>\
                                            </div>\
                                        </td>'
                        }

                    }
                }
                mapDraw += '</tr>'
                num++
            }
            this.mapCreate = mapDraw
            this.mapKey += 1
            this.$nextTick(() => {
                $('.cliseat').unbind('click').bind('click', {self: this}, function(e){
                    if(mapSelectPoint.length ==  0){
                        e.data.self.selectcount = 0
                    }
                    
                    if($("#seatarea").data('moved') != true){
                        let prevsrc = $(this).attr('data-status')

                        if(prevsrc == 'unSelect'){
                            let nowId = $(".cliseat").index(this)

                            if(e.data.self.selectcount+1 > 2){
                                let firstPoint = mapSelectPoint[0].nowId
                                $(".cliseat").eq(firstPoint).attr('data-status', 'unSelect')
                                $(".cliseat").eq(firstPoint).removeClass( "active" )
                                mapSelectPoint.splice(0, 1)
                            }
                            $(this).attr('data-status', 'Select')
                            $(this).addClass( "active" );
                            mapSelectPoint.push({nowId})
                            
                            if(e.data.self.selectcount < 2){
                                e.data.self.selectcount++;
                            }
                            seatMapSetting.settingBtnSel = false
                        }else if(prevsrc == 'Select'){
                            let nowId = $(".cliseat").index(this)
                            let num = ''
                            let max = mapSelectPoint.length
                           
                            if(e.data.self.selectcount-1 < 0) return
                                
                            for(let n=0; n<max; n++){
                                if(mapSelectPoint[n].nowId == nowId){
                                    num = n
                                }
                            }
                            mapSelectPoint.splice(num, 1)
                            
                            $(this).attr('data-status', 'unSelect')
                            $(this).removeClass( "active" );

                            if(mapSelectPoint.length == 0){
                                seatMapSetting.settingBtnSel = true
                            }

                            e.data.self.selectcount--

                        }
                        if(e.data.self.selectcount<=0){
                            $("#procsend").removeClass("is-type1");
                            $("#procsendMobile").addClass("deactive");
                        }else{
                            $("#procsend").addClass("is-type1");
                            $("#procsendMobile").removeClass("deactive");
                        }		
                    }
                });
            })

            this.$nextTick(() => {
                let blockId = floor+block
                let blockSelect = document.getElementById(blockId);
                blockSelect.className += " active"
                if(this.blockSelect && this.blockSelect !== blockId){
                    let element = document.getElementById(this.blockSelect);
                    element.classList.remove("active");
                }
                this.blockSelect = blockId
            })

        },
        seatUnitChange:function(){
            let id = this.seatUnit
            let data = this.dataSet[id]
            let color = data.color
            this.nowUnitTotal = data.total
            this.colorNow = color
            this.settingBtnOption = false
            this.nowText =  data.text

            this.selectSeatClear()
        },
        zoomOutTable:function(){
            let explorer = navigator.userAgent 
            this.zoomInBtn = false
            this.scale += 0.2

            if(explorer.indexOf("Firefox") >= 0){
                this.mapStyle = {
                    'transform' : `scale(${parseFloat(this.scale.toFixed(1))})`
                }
            }else{
                this.mapStyle.zoom = parseFloat(this.scale.toFixed(1))
            }

            if(parseFloat(this.scale.toFixed(1)) == 1.4){
                this.zoomOutBtn = true
            }
        },
        zoomInTable:function(){
            let explorer = navigator.userAgent 
            this.zoomOutBtn =  false
            this.scale -= 0.2

            if(explorer.indexOf("Firefox") >= 0){
                this.mapStyle = {
                    'transform' : `scale(${parseFloat(this.scale.toFixed(1))})`
                }
            }else{
                this.mapStyle.zoom = parseFloat(this.scale.toFixed(1))
            }

            if(parseFloat(this.scale.toFixed(1)) == 0.6){
                this.zoomInBtn = true
            }
        },
        saveSeatIsSelect:function(){
            loading.openLoading()
                
            let mapSeatData = []
            let nowFloor = this.nowFloor
            let nowBlock = this.nowBlock
            let nowSeatData =  this.mapData[nowFloor].blockData[nowBlock].seatData
         
            if(mapSelectPoint.length ==  2){
                let xstar,xend,ystar,yend
                let n = 0
                let color = this.colorNow
                let unit = this.seatUnit
                let dataSet = this.dataSet
                let nowSelectStart = mapSelectPoint[0].nowId
                let nowSelectEnd = mapSelectPoint[1].nowId
                let firstPoint = $(".cliseat").eq(nowSelectStart)
                let secondPoint = $(".cliseat").eq(nowSelectEnd)
                let firstPointPosition = firstPoint.attr('data-seatid').split(".")
                let secondPointPosition = secondPoint.attr('data-seatid').split(".")
                
                if(parseInt(firstPointPosition[1]) > parseInt(secondPointPosition[1])){
                    ystar = parseInt(secondPointPosition[1])
                    yend = parseInt(firstPointPosition[1])
                }else{
                    ystar = parseInt(firstPointPosition[1])
                    yend = parseInt(secondPointPosition[1])
                }
                
                if(parseInt(firstPointPosition[0]) > parseInt(secondPointPosition[0])){
                    xstar = parseInt(secondPointPosition[0])
                    xend = parseInt(firstPointPosition[0])
                }else{
                    xstar = parseInt(firstPointPosition[0])
                    xend = parseInt(secondPointPosition[0])
                }

                for(let star = 0; star < $('.cliseat').length; star++){
                    let postionId = $('.cliseat')[star].getAttribute('data-seatid')
                    let seatid = "Seat" + postionId
                    let pointPosition =  $('.cliseat')[star].getAttribute('data-seatid').split(".")
                    let x = parseInt(pointPosition[0])
                    let y = parseInt(pointPosition[1])
                    let seatCode = y+'.'+x
                    
                    if(xstar <= x && x <= xend && ystar <= y && y <= yend){
                        $('.cliseat')[star].setAttribute('data-select', 'select')
                        $('.cliseat')[star].setAttribute('data-unitSel', unit)
                        $('.cliseat')[star].classList.add("hadSetting")
                       
                        if(this.publish){
                            let typeData  = {
                                id: this.dataSet[this.seatUnit].id,
                                text: this.dataSet[this.seatUnit].text,
                                title: this.dataSet[this.seatUnit].title,
                                type: this.dataSet[this.seatUnit].type,
                                type_id: this.dataSet[this.seatUnit].typeId,
                                color: this.dataSet[this.seatUnit].color,
                            }
                            
                            nowSeatData[seatCode].typeData = typeData
                            nowSeatData[seatCode].type = this.dataSet[this.seatUnit].type
                            nowSeatData[seatCode].num = this.dataSet[this.seatUnit].id
                            nowSeatData[seatCode].typeId = this.dataSet[this.seatUnit].typeId
                            nowSeatData[seatCode].status = 'U'
                        }else{
                            if(typeof nowSeatData[seatCode].respectiveData == 'undefined'){
                                nowSeatData[seatCode].respectiveData = []
                            }

                            let filterData = ({
                                'dateValue' :  this.dateValue,
                                'ruleId' : this.ruleId,
                            })

                            let respectiveId = nowSeatData[seatCode].respectiveData.findIndex(function(ele, index, arr){
                                return ele.dateValue == filterData.dateValue && ele.ruleId == filterData.ruleId
                            }, filterData)
                            
                            let changeData = true
                            let typeSame = false

                            if(typeof(nowSeatData[seatCode].typeData) !== 'undefined'){
                                switch (nowSeatData[seatCode].typeData.type) {
                                    case 'specSeat':
                                        if(this.dataSet[this.seatUnit].type == 1){
                                            typeSame = true
                                        }
                                        break;
                                    case 'ticketSetting':
                                        if(this.dataSet[this.seatUnit].type == 2){
                                            typeSame = true
                                        }
                                        break;
                                }
                                if(nowSeatData[seatCode].typeId == this.dataSet[this.seatUnit].index && typeSame){
                                    changeData = false
                                }
                            }

                            if(respectiveId >= 0){
                                if(changeData){
                                    nowSeatData[seatCode].respectiveData[respectiveId].type = this.dataSet[this.seatUnit].type
                                    nowSeatData[seatCode].respectiveData[respectiveId].index = this.dataSet[this.seatUnit].index
                                }else{
                                    nowSeatData[seatCode].respectiveData.splice(respectiveId, 1);
                                }
                            }else if(changeData){
                                nowSeatData[seatCode].respectiveData.push({
                                    dateValue: this.dateValue,
                                    ruleId: this.ruleId,
                                    type: this.dataSet[this.seatUnit].type,
                                    index:this.dataSet[this.seatUnit].index,
                                })
                            }
                        }     
                    }

                    if(x == xend && y == yend){
                        break
                    }
                }
                
                this.mapData[nowFloor].blockData[nowBlock].seatData =  nowSeatData
                this.countSeatSelect()
                this.selectSeatClear()
            }else{
                let nowSelect = mapSelectPoint[0].nowId
                let id = $(this).attr("name")
                let status = $(this).attr('data-status')
                let color = this.colorNow
                let unit = this.seatUnit
                let dataSet = this.dataSet
                let seatSelId = $(this).attr('data-id')
                let pointPosition =  $('.cliseat')[nowSelect].getAttribute('data-seatid').split(".")
                let x = parseInt(pointPosition[0])
                let y = parseInt(pointPosition[1])
                let seatCode = y+'.'+x

                $(".cliseat").eq(nowSelect).attr('data-select', 'select')
                $(".cliseat").eq(nowSelect).attr('data-unitSel', unit)
                $('.cliseat')[nowSelect].classList.add("hadSetting")

                $('.cliseat').each(function() {
                    let id = $(this).attr("name")
                    let status = $(this).attr('data-status')
                    let seatSelId = $(this).attr('data-id')

                    mapSeatData.push({
                        id: id,
                        seatSelId: seatSelId,
                        status: status,
                        color: color
                    })
                });

                if(this.publish){
                    let typeData  = {
                        id: this.dataSet[this.seatUnit].id,
                        text: this.dataSet[this.seatUnit].text,
                        title: this.dataSet[this.seatUnit].title,
                        type: this.dataSet[this.seatUnit].type,
                        type_id: this.dataSet[this.seatUnit].typeId,
                        color: this.dataSet[this.seatUnit].color,
                    }
                    
                    nowSeatData[seatCode].typeData = typeData
                    nowSeatData[seatCode].type = this.dataSet[this.seatUnit].type
                    nowSeatData[seatCode].num = this.dataSet[this.seatUnit].id
                    nowSeatData[seatCode].typeId = this.dataSet[this.seatUnit].typeId
                    nowSeatData[seatCode].status = 'U'
                }else{
                    if(typeof nowSeatData[seatCode].respectiveData == 'undefined'){
                        nowSeatData[seatCode].respectiveData = []
                    }

                    let filterData = ({
                        'dateValue' :  this.dateValue,
                        'ruleId' : this.ruleId,
                    })

                    let respectiveId = nowSeatData[seatCode].respectiveData.findIndex(function(ele, index, arr){
                        return ele.dateValue == filterData.dateValue && ele.ruleId == filterData.ruleId
                    }, filterData)
                    
                    let changeData = true
                    let typeSame = false

                    if(typeof(nowSeatData[seatCode].typeData) !== 'undefined'){
                        switch (nowSeatData[seatCode].typeData.type) {
                            case 'specSeat':
                                if(this.dataSet[this.seatUnit].type == 1){
                                    typeSame = true
                                }
                                break;
                            case 'ticketSetting':
                                if(this.dataSet[this.seatUnit].type == 2){
                                    typeSame = true
                                }
                                break;
                        }
                        if(nowSeatData[seatCode].typeId == this.dataSet[this.seatUnit].index && typeSame){
                            changeData = false
                        }
                    }

                    if(respectiveId >= 0){
                        if(changeData){
                            nowSeatData[seatCode].respectiveData[respectiveId].type = this.dataSet[this.seatUnit].type
                            nowSeatData[seatCode].respectiveData[respectiveId].index = this.dataSet[this.seatUnit].index
                        }else{
                            nowSeatData[seatCode].respectiveData.splice(respectiveId, 1);
                        }
                    }else if(changeData){
                        nowSeatData[seatCode].respectiveData.push({
                            dateValue: this.dateValue,
                            ruleId: this.ruleId,
                            type: this.dataSet[this.seatUnit].type,
                            index:this.dataSet[this.seatUnit].index,
                        })
                    }
                }
              
                this.countSeatSelect()
                this.selectSeatClear()
            }

            this.mapData[nowFloor].blockData[nowBlock].seatData = nowSeatData

            loading.closeLoading()

            this.seatDataChange(this.nowFloor, this.nowBlock)
        },
        seatClearDataSet:function(seat){
           
            if(this.publish){
                let typeData  = {
                    id: '',
                    text: '',
                    title: '',
                    type: '',
                    color: '#FFFFFF',
                }
            
                seat.typeData = typeData
                seat.type = '0'
                seat.typeId = '0'
                seat.status = 'U'
            }else{
                if(typeof seat.respectiveData == 'undefined'){
                    seat.respectiveData = []
                }
                let changeData = true
                let typeSame = false

                let filterData = ({
                    'dateValue' :  this.dateValue,
                    'ruleId' : this.ruleId,
                })

                let respectiveId = seat.respectiveData.findIndex(function(ele, index, arr){
                    return ele.dateValue == filterData.dateValue && ele.ruleId == filterData.ruleId
                }, filterData)

                if(typeof(seat.typeData) == 'undefined'){
                    changeData = false
                }

                if(respectiveId >= 0){
                    if(changeData){
                        seat.respectiveData[respectiveId].type = 0
                        seat.respectiveData[respectiveId].index = 0
                    }else{
                        seat.respectiveData.splice(respectiveId, 1);
                    }
                }else if(changeData){
                    seat.respectiveData.push({
                        dateValue: this.dateValue,
                        ruleId: this.ruleId,
                        type: 0,
                        index: 0,
                    })
                }
            }
            return seat
        },
        clearSeatIsSelect:function(){
            let mapSeatData = []
            let nowFloor = this.nowFloor
            let nowBlock = this.nowBlock
            let nowSeatData =  this.mapData[nowFloor].blockData[nowBlock].seatData
         
            if(mapSelectPoint.length ==  2){
                let xstar,xend,ystar,yend
                let n = 0
                let color = this.colorNow
                let unit = this.seatUnit
                let dataSet = this.dataSet
                let nowSelectStart = mapSelectPoint[0].nowId
                let nowSelectEnd = mapSelectPoint[1].nowId
                let firstPoint = $(".cliseat").eq(nowSelectStart)
                let secondPoint = $(".cliseat").eq(nowSelectEnd)
                let firstPointPosition = firstPoint.attr('data-seatid').split(".")
                let secondPointPosition = secondPoint.attr('data-seatid').split(".")
                
                if(parseInt(firstPointPosition[1]) > parseInt(secondPointPosition[1])){
                    ystar = parseInt(secondPointPosition[1])
                    yend = parseInt(firstPointPosition[1])
                }else{
                    ystar = parseInt(firstPointPosition[1])
                    yend = parseInt(secondPointPosition[1])
                }
                
                if(parseInt(firstPointPosition[0]) > parseInt(secondPointPosition[0])){
                    xstar = parseInt(secondPointPosition[0])
                    xend = parseInt(firstPointPosition[0])
                }else{
                    xstar = parseInt(firstPointPosition[0])
                    xend = parseInt(secondPointPosition[0])
                }

                for(let star = 0; star < $('.cliseat').length; star++){
                    let postionId = $('.cliseat')[star].getAttribute('data-seatid')
                    let seatid = "Seat" + postionId
                    let pointPosition =  $('.cliseat')[star].getAttribute('data-seatid').split(".")
                    let x = parseInt(pointPosition[0])
                    let y = parseInt(pointPosition[1])
                    let seatCode = y+'.'+x
                    
                    if(xstar <= x && x <= xend && ystar <= y && y <= yend){
                        $('.cliseat')[star].setAttribute('data-select', 'select')
                        $('.cliseat')[star].setAttribute('data-unitSel', unit)
                        $('.cliseat')[star].classList.add("hadSetting")

                        nowSeatData[seatCode] = this.seatClearDataSet(nowSeatData[seatCode])
                    }

                    if(x == xend && y == yend){
                        break
                    }
                }
                
                this.mapData[nowFloor].blockData[nowBlock].seatData =  nowSeatData
              
                this.countSeatSelect()
            }else{
                let nowSelect = mapSelectPoint[0].nowId
                let id = $(this).attr("name")
                let status = $(this).attr('data-status')
                let color = this.colorNow
                let unit = this.seatUnit
                let dataSet = this.dataSet
                let seatSelId = $(this).attr('data-id')
                let pointPosition =  $('.cliseat')[nowSelect].getAttribute('data-seatid').split(".")
                let x = parseInt(pointPosition[0])
                let y = parseInt(pointPosition[1])
                let seatCode = y+'.'+x

                $(".cliseat").eq(nowSelect).attr('data-select', 'select')
                $(".cliseat").eq(nowSelect).attr('data-unitSel', unit)
                $('.cliseat')[nowSelect].classList.add("hadSetting")

                
                nowSeatData[seatCode] = this.seatClearDataSet(nowSeatData[seatCode])
            
                this.countSeatSelect()
            }
            this.countTotal()
            this.seatDataChange(this.nowFloor, this.nowBlock)
        },
        selectSeatClear:function(){
            $('.cliseat').each(function() {
                $(this).removeClass( "active" )
                $(this).attr('data-status', 'unSelect')
            });
            
            mapSelectPoint = []
            this.settingBtnSel = true
        },
        countSeatSelect:function(){
            let total=0 

            this.reserveSeat.forEach(function(data, index, arr){
                arr[index].total=0
            })
            
            var reserveSeat = this.reserveSeat
            $.each(this.mapData, function(index, value) {
                $.each(value['blockData'], function(index, block) {
                    $.each(block['seatData'], function(index, seat) {
                        let seatInf = seatMapSetting.getRespectiveData(seat)
                        if(seatInf.type == 1){
                            let typeId = seatInf.typeId
                            reserveSeat[typeId].total++
                            total++
                        }
                    }); 
                }); 
            }); 
            
            this.reserveSeat =  reserveSeat

            this.typeSeat.forEach(function(data, index, arr){
                if(data.seat_class_kbn == {!! addslashes(\Config::get('constant.seat_class_kbn.reserved')) !!}){
                    arr[index].typeTotal = 0
                }
            })
        
            let typeSeat = this.typeSeat
            $.each(this.mapData, function(index, value) {
                $.each(value['blockData'], function(index, block) {
                    $.each(block['seatData'], function(index, seat) {
                        let seatInf = seatMapSetting.getRespectiveData(seat)
                        if(seatInf.type == 2){
                            typeSeat.forEach(function(element, index) {
                                let typeId = seatInf.typeId
                                if(seatMapSetting.publish){
                                    if(element.class_id == seat.typeId){ 
                                        typeSeat[index].typeTotal++
                                        total++
                                    }
                                }else{
                                    if(element.index == typeId && seatInf.type == element.type){ 
                                        typeSeat[index].typeTotal++
                                        total++
                                    }
                                }
                            });
                        }
                    }); 
                }); 
            }); 
            
            this.typeSeat =  typeSeat

            this.$nextTick(() => {
                this.seatSettingInf()
            })

            this.countTotal()
        },
        getRespectiveData:function(seat){
            let seatInf =  ({
                'type' :  0,
                'typeId' : 0,
            }) 
            
            if(!this.publish){
                let typeOri = 0
                let typeId = 0
                if(typeof(seat.typeData) !== 'undefined'){
                    switch (seat.typeData.type) {
                        case 'specSeat':
                            typeOri = 1
                            break;
                        case 'ticketSetting':
                            typeOri = 2
                            break;
                    }

                    typeId = typeId = parseInt(seat.typeData.id, 10)
                    
                    seatInf =  ({
                        'type' :  typeOri,
                        'typeId' : typeId,
                    }) 
                }
                if(typeof seat.respectiveData !== 'undefined'){
                    let filterData = ({
                        'dateValue' :  this.dateValue,
                        'ruleId' : this.ruleId,
                    })

                    let respectiveId = seat.respectiveData.findIndex(function(ele, index, arr){
                        return ele.dateValue == filterData.dateValue && ele.ruleId == filterData.ruleId
                    }, filterData)

                    if(respectiveId >= 0){
                        seatInf =  ({
                            'type' :  seat.respectiveData[respectiveId].type,
                            'typeId' : seat.respectiveData[respectiveId].index,
                        }) 
                    }
                }
            }else{
                let type = -1
                let typeId = 0
                if(seat.type == 1){
                    if(seat.typeId > 0){
                        type = seat.type
                        typeId = parseInt(seat.num, 10)-1
                    }
                }else{
                    type = seat.type
                    typeId = parseInt(seat.typeId, 10)
                }
                seatInf =  ({
                    'type' : type,
                    'typeId' : typeId,
                })
            }

            return seatInf
        },
        /**
            座位總數
         */
        countTotal:function(){
            let total=0 

            this.reserveSeat.forEach(function(data){
                total += data.total
            })

            this.seatReserve =total

            this.typeSeat.forEach(function(data){
                if(!data.errorStatus){
                    total += parseInt(data.typeTotal)
                }
            })

            this.seatTotal = total
        }
    }
});
$('#saetSettingColorPick').colorPick({
    'initialColor': '#FF748A',
    'allowRecent': true,
    'recentMax': 5,
    'palette': [{!! \Config::get('constant.color_text') !!}],
    'onColorSelected': function() {
        let color = this.color
        seatMapSetting.specSeatColor = color
        this.element.css({'backgroundColor': this.color, 'color': this.color});
    }
});
$('#updateBtn').click(function (e) {
    e.preventDefault()
    let checkInf = seatMapSetting.checkInf()
    
    if(checkInf){
        let json = []
        let reserveSeat = seatMapSetting.reserveSeat
        let mapData = seatMapSetting.mapData
        let typeSeat = seatMapSetting.typeSeat

        json.push({
            publish : "{{ $eventsInf['data']['publish_status'] }}",
            performanceId : {{ $eventsInf['data']['performanceId'] }},
            draftId : {{ $eventsInf['data']['draftId'] }},
            dateValue : {{ $eventsInf['data']['date_value'] }},
            ruleId : {{ $eventsInf['data']['rule_id'] }},
            scheduleId : {{ $eventsInf['data']['scheduleId'] }},
            reserveSeatData : reserveSeat,
            mapData  : mapData,
            typeSeat : typeSeat,
        })

        loading.openLoading()
        window.is_confirm = false
        document.getElementById('settingContent').value = JSON.stringify(json)
        document.getElementById("settingSend").submit();
    }
})
</script>
@stop
