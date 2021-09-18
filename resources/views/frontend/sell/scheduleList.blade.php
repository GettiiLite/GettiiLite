@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
    <h1>
        {{trans('sellManage.S_EventCancel')}}
        <small></small>
    </h1>
    <!-- 網站導覽 -->
    <ol class="breadcrumb">
        <li><a href="/sell">{{trans('sellManage.S_SubTitle_1')}}</a></li>
        <li><a href="/sell/manage/{{ $data['data']['performance'][0]['performance_id'] }}">{{trans('sellManage.S_SubTitle_2')}}</a></li>
        <li class="active">{{trans('sellManage.S_EventCancel')}}</li>
    </ol>
@stop

@section('content')
<div id="app" class="content-navonly">
    <!-- 新增子選單 -->
    <ul id="" class="nav nav-tabs-sell">
        <li class="active">
            <a id="" onclick="loading.openLoading()"  href="/sell"><span>{{ trans('sellManage.S_sellInfoTab_01') }}</span></a>
        </li>
        <li>
            <a id="" onclick="loading.openLoading()" href="/orders"><span>{{ trans('sellManage.S_sellInfoTab_02') }}</span></a>
        </li>
    </ul>
    <!-- /.新增子選單 -->
<div>
<!-- FORMGROUP 2  Grid + BTN  -->
<div class="box box-solid">
    <div class="box-header">
        <h3 class="table-title">{{ $data['data']['performance'][0]['performance_name'] }}</h3>
        <p class="margin-fix"><span class="text-gray">{{trans('events.S_Venue')}}：</span>{{ $data['data']['performance'][0]['hall_disp_name'] }}</p>
        <p class="margin-fix"><span class="text-gray">{{trans('sellManage.S_EventOpenDate')}}：</span>{{ $data['data']['performance'][0]['performance_st_dt'] }} ~ {{ $data['data']['performance'][0]['performance_end_dt'] }}</p>
    </div>
    <!--<div class="box-body"></div>-->
</div>
<!-- /.FORMGROUP 2  Grid + BTN  -->
<!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
<div id="scheduleList" class="box box-solid">
    <form id="settingSend" method="POST" style="visibility: collapse;display: table;" action="/schedule/cancel">
        {{ csrf_field() }}
        <input id="settingContent" type="hidden" name="json" v-model="json">
    </form>
    <div class="box-body">
    <!-- TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
    <table id="" class="table table-striped table-normal">
        <thead>
            <tr>
                <th width="15"></th>
                <th width="80">
                    <div class="form-checkbox form-checkbox-fix">
                        <label class="control control--checkbox">
                            <input type="checkbox" v-model="allCheck" @change="checkAll()">
                            <div class="control__indicator__stop"></div>
                        </label>
                    </div>
                </th>
                <th width="120">{{trans('sellManage.S_EventOpenDate')}}</th>
                <th width="120">{{trans('sellManage.S_EventOpenTime')}}</th>
                <th width="100">{{trans('sellManage.S_EventTimeSlot')}}</th>
                <th>{{trans('sellManage.S_EventRefund')}}</th>
                <th>{{trans('sellManage.S_EventCancelDescription')}}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data, index) in scheduleData">
                <td>@{{ index+1 }}</td>
                <td v-if="!data['cancel_flg']">
                    <div class="form-checkbox form-checkbox-fix">
                        <label class="control control--checkbox">
                            <input  dusk="week-mon" type="checkbox" v-model="data['check_flg']" @change="clickCheck()">
                            <div class="control__indicator__stop"></div>
                        </label>
                    </div>
                </td>
                <td v-else>
                    <div class="form-checkbox form-checkbox-fix">
                        <label class="control control--checkbox">
                            <input  dusk="week-mon" type="checkbox" checked disabled="disabled">
                            <div class="control__indicator__stop"></div>
                        </label>
                    </div>
                </td>
                <td v-if="!data['cancel_flg']">@{{ data['performance_date'] }}</td>
                <td v-else class="disabled">@{{ data['performance_date'] }}</td>
                <td v-if="!data['cancel_flg']">@{{ data['start_time'] }}</td>
                <td v-else class="disabled">@{{ data['start_time'] }}</td>
                <td v-if="!data['cancel_flg']">@{{ data['disp_performance_date'] }}</td>
                <td v-else class="disabled">@{{ data['disp_performance_date'] }}<span class="badge bg-red">{{trans('sellManage.S_EventCanceled')}}</span></td>
                <td>
                    <div class="flex-start">@{{ data['refund_st_date'] }}~@{{ data['refund_end_date'] }}</div>
                </td>
                <td>
                    <div class="flex-start">@{{ data['cancel_messgae'] }}</div>
                </td>
                <td>
                    <a v-if="data['cancel_flg']" href="#" class="btn btn-info-outline btn-mm" data-toggle="modal" data-target="#edit-setting"  v-on:click="showDialogEdit(index)">{{trans('events.S_eventOperationModify')}}</a>
                </td>
            </tr>
            </tbody>

        </table>
        <!-- /.TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-right">
        <button type="submit" value="submit" class="btn waves-effect waves-light btn-angle btn-danger"data-toggle="modal" data-target="#stop-setting" v-on:click="showDialog()">{{trans('sellManage.S_EventCancelBtn')}}</button>
    </div>
        <!-- =============================================== -->
    <!-- ========== 變更設定 ========== -->
    <!-- modal -->
    <div class="modal-mask" v-show="showModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>-->
                    <!-- step 1 -->
                        <h4 class="modal-title" v-show="step == 1">{{trans('sellManage.S_EventCancelSetting')}}</h4>
                    <!-- step 1 -->
                    <!-- step 2 -->
                        <h4 class="modal-title" v-show="step == 2"><i class="fas fa-exclamation-triangle"></i> {{trans('sellManage.S_EventCancelConfirm')}}</h4>
                    <!-- step 2 -->
                    <!-- step 3 -->
                        <h4 class="modal-title" v-show="step == 3">{{trans('sellManage.S_EventCancelModify')}}  
                            <small class="pl-15 text-dark"><i class="far fa-calendar-alt"></i> <b>{{trans('sellManage.S_EventOpenDate')}} @{{ performanceDatetime }} | {{trans('sellManage.S_EventOpenTime')}} @{{ scheduleDatetime }}</b></small><!-- /.1119新增 -->
                        </h4>
                    <!-- step 3 -->
                    <!-- form step 9 取消結果-->
                        <h4 class="modal-title" v-show="step == 9">{{trans('sellManage.S_EventCancelResult')}}</h4>
                    <!-- form step 9 取消結果-->
                </div>
                <div class="modal-body">
                    <!-- form  -->
                    <!-- step 1 -->
                        <div class="row form-horizontal"  v-show="step == 1">
                            <div class="col-md-12">
                                <!-- /.form-group -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{{trans('sellManage.S_EventCancelRefund')}}</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control pull-left" id="refundDate">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                                <!-- form-group -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label flex-between">
                                            <div class="text-left">{{trans('sellManage.S_EventCancelDescription')}}</div>
                                            <div class="text-right">*{{trans('sellManage.S_EventCancelNotice')}}</div>
                                        </label>
                                        <div class="col-md-12">
                                            <textarea rows="5" class="form-control textarea-fixed" v-model="refundContent"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>
                        <!--12-->
                    <!-- step 1 -->
                    <!-- step 2 -->
                        <!-- form  -->
                        <div class="row form-horizontal" v-show="step == 2">
                            <div class="col-md-12">
                                <h4 class="text-red">
                                    <i class="fas fa-exclamation-triangle text-red"></i> 
                                    {{trans('sellManage.S_EventCancelNotice1')}}
                                </h4>
                                <div class="modal-overflow">
                                    <ul class="modal-list">
                                        <template v-for="(data, index) in scheduleData">
                                            <li v-show="data['check_flg'] && !data['cancel_flg']">
                                                <span class="badge bg-gray-light">{{trans('sellManage.S_EventOpenDate')}}</span> @{{ data['performance_date'] }}
                                                <span class="badge bg-gray-light">{{trans('sellManage.S_EventOpenTime')}}</span> @{{ data['start_time'] }}
                                                <span class="badge bg-gray-light">{{trans('sellManage.S_EventTimeSlot')}}</span> @{{ data['disp_performance_date'] }}
                                            </li>
                                        </template>
                                    </ul>
                                    <div class="modal-info">
                                        <h5>{{trans('sellManage.S_EventCancelDescription')}}</h5>
                                        <p>@{{ refundContent }}</p>
                                    </div> 
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.form  -->
                    <!-- step 2 -->
                    <!-- step 3 -->
                        <!-- form  -->
                        <div class="row form-horizontal" v-show="step == 3">
                        <div class="col-md-12">
                            <!-- /.form-group -->
                            <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{trans('sellManage.S_EventCancelRefund')}}</label>

                                <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control pull-left" id="editRefundDate" v-model="refundDate">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                </div>

                            </div>
                            </div>
                            <!-- /.form-group -->
                            <!-- form-group -->
                            <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12 control-label flex-between">
                                <div class="text-left">{{trans('sellManage.S_EventCancelDescription')}}</div>
                                <div class="text-right">*{{trans('sellManage.S_EventCancelNotice')}}</div>
                                </label>
                                <div class="col-md-12">
                                <textarea rows="5" class="form-control textarea-fixed" v-model="refundContent"></textarea>
                                </div>
                            </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!--12-->
                        </div>
                        <!-- /.col -->
                        <!--</div>-->
                        <!-- /.form  -->
                    <!-- step 3 -->
                    <!-- form  step 9 取消結果-->
                        <div class="row form-horizontal" v-show="step == 9">
                            <div class="col-md-12">
                                <h4 class="text-red">
                                    <i class="fas fa-exclamation-triangle text-red"></i> 
                                    {{trans('sellManage.S_EventCancelSucceed')}}
                                </h4>
                            </div>
                        </div>
                    <!-- form  step 9 取消結果-->
                <!-- /.form  -->
                <!-- /.col -->
                <!--</div>-->
                <!-- /.form  -->
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" v-on:click="closeDialog()" data-dismiss="modal"  v-show="step != 9">{{trans('sellManage.S_EventCancelCloseBtn')}}</button>
                <!-- step 1 -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#check-stop-setting" v-on:click="nextStep()" v-show="step == 1">{{trans('sellManage.S_EventCancelConfirmBtn')}}</button>
                <!-- step 1 -->
                <!-- step 2 -->
                    <button type="button" class="btn btn-danger" v-on:click="cancelSchedule()" v-show="step == 2">{{trans('sellManage.S_EventCancelConfirmBtn')}}</button>
                <!-- step 2 -->
                <!-- step 3 -->
                    <button type="button" class="btn btn-primary" v-on:click="changeScheduleInf()" v-show="step == 3">{{trans('sellManage.S_EventCancelModifyBtn')}}</button>
                <!-- step 3 -->
                <!-- step 9 -->
                    <button type="button" class="btn btn-primary" v-on:click="closeDialog()" v-show="step == 9">確認</button>
                <!-- step 9 -->
            </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- ========== End of 變更設定 ========== -->
    <!-- =============================================== -->
</div>
<!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->
<script>
    //分類選擇
    document.getElementById('sellManage').classList.add('active')
    //debug 需要
    Vue.config.debug = true;
    Vue.config.devtools = true;
    //debug 需要
    try {
        var eventList = new Vue({
            el: '#scheduleList',
            data: { 
                showModal: false,
                step:'',
                allCheck: false,
                index:'',
                performanceDatetime: '',
                scheduleDatetime: '',
                refundDateStart: '',
                refundDateEnd: '',
                refundDate:'',
                refundContent: '',
                scheduleData: '',
                performanceData: '',
                json: '',
            },
            mounted:function(){
                this.performanceData = JSON.parse('{!! addslashes($data["data"]["performance_json"]) !!}')
                this.scheduleData    = JSON.parse('{!! addslashes($data["data"]["schedule"]) !!}')

                @if($data['statuc']['schedule_cancel_result'])
                    this.showModal  = true
                    this.step       = 9
                @endif
                
                //+++ #1540対応 +++//
                let status      = this.allCheck
                let isAllCheck  = true
                let isAllTrue   = true
                let checkChange = ''
                
                this.scheduleData.forEach(function(item){
                    if(item.check_flg !== status){
                        isAllCheck  = false
                        checkChange = item.check_flg
                    }
                            
                    if(!item.check_flg && isAllTrue){
                        isAllTrue = false
                    }
                })
                this.allCheck = isAllTrue
                //+++++++++++++++++//
            },
            watch: {
            },
            methods: {
                /**
                    
                 */
                changeScheduleInf: function(){
                    let cancelSchedule  = []
                    let json            = []
                    let status          = []
                    let data            = []
                    let refundDateStart = this.refundDateStart
                    let refundDateEnd   = this.refundDateEnd
                    let refundContent   = this.refundContent
                    
                    this.scheduleData[this.index].refund_st_date    = refundDateStart
                    this.scheduleData[this.index].refund_end_date   = refundDateEnd
                    this.scheduleData[this.index].cancel_messgae    = refundContent
                    
                    cancelSchedule.push(this.scheduleData[this.index])

                    status.push({
                        allCheck: this.allCheck,
                    })

                    data.push({
                        performance: this.performanceData,
                        schedule: cancelSchedule,
                    })

                    json.push({
                        status: status,
                        data: data,
                    })

                    this.json = JSON.stringify(json)

                    this.$nextTick(() => {
                        document.getElementById("settingSend").submit();
                    })
                },
                /**
                * 場次資訊修改 Dialog
                */
                showDialogEdit: function(index){
                    this.step                   = 3
                    this.showModal              = true
                    this.index                  = index
                    let data                    = this.scheduleData[index]
                    this.performanceDatetime    = data.performance_date
                    this.scheduleDatetime       = data.start_time
                    this.refundDate             = `${ (data.refund_st_date)?data.refund_st_date+' ~ ':'' }${ (data.refund_end_date)?data.refund_end_date:'' }`  
                    this.refundDateStart        = data.refund_st_date
                    this.refundDateEnd          = data.refund_end_date
                    this.refundContent          = data.cancel_messgae
                 
                    $.getScript("{{ asset('js/daterangepicker.js') }}", function(){
                        $('#editRefundDate').daterangepicker({
                            "locale": {
                                "format": "YYYY/MM/DD"
                            },
                            autoUpdateInput: false,
                            minDate: starDat,
                            startDate: (data.refund_st_date)?data.refund_st_date:starDat,
                            endDate: (data.refund_end_date)?data.refund_end_date:starDat,
                        });

                        $('#editRefundDate').on('apply.daterangepicker', function(ev, picker) {
                            let date = picker.startDate.format('YYYY/MM/DD')+'~'+picker.endDate.format('YYYY/MM/DD')
                            $(this).val(date)
                            eventList.refundDateStart =  picker.startDate.format('YYYY/MM/DD')
                            eventList.refundDateEnd =  picker.endDate.format('YYYY/MM/DD')
                        });
                    });

                },
                /**
                * 確認取消場次
                * 
                */
                nextStep: function(){
                    this.step = 2
                },  
                /**
                * 確認取消
                * 
                */
                cancelSchedule: function(){
                    let cancelSchedule  = []
                    let json            = []
                    let status          = []
                    let data            = []
                    let refundDateStart = this.refundDateStart
                    let refundDateEnd   = this.refundDateEnd
                    let refundContent   = this.refundContent
                    
                    this.scheduleData.forEach(function(item){
                        if(item.check_flg && !item.cancel_flg){
                            item.refund_st_date     = refundDateStart
                            item.refund_end_date    = refundDateEnd
                            item.cancel_messgae     = refundContent
                            cancelSchedule.push(item)
                        }
                    })

                    status.push({
                        allCheck: this.allCheck,
                    })

                    data.push({
                        performance: this.performanceData,
                        schedule: cancelSchedule,
                    })

                    json.push({
                        status: status,
                        data: data,
                    })

                    this.json = JSON.stringify(json)

                    this.$nextTick(() => {
                        document.getElementById("settingSend").submit();
                    })
                },
                /**
                * 
                * 
                */
                showDialog: function(){
                    this.step      = 1
                    this.showModal = true
                },
                /**
                * 
                * 
                */
                closeDialog:function(){
                    this.showModal = false
                },
                /**
                * 選取所有場次
                * 
                */
                checkAll: function(){
                    let status = this.allCheck

                    this.scheduleData.forEach(function(item){
                        item.check_flg = status
                    })
                },
                /**
                * 檢查 checkbox is check  
                * 
                */
                clickCheck: function(val){
                    let status      = this.allCheck
                    let isAllCheck  = true 
                    let checkChange = ''
                    let isAllTrue   = true
                    
                this.$nextTick(() => {

                        this.scheduleData.forEach(function(item){
                            if(item.check_flg !== status){
                                isAllCheck  = false
                                checkChange = item.check_flg
                            }
                            
                            if(!item.check_flg && isAllTrue){
                                isAllTrue = false
                            }
                        })
                        this.allCheck = isAllTrue
                })
                }
            },
        })
    }catch (e) {
        console.error(`%c vue 產生錯誤 ${e}`, "color: yellow; font-style: italic; background-color: blue;padding: 2px")
    }
    /*
    *
    */
    const nowDate = new Date();
    const starDat = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0)
    const endDate = new Date(nowDate.getFullYear(), nowDate.getMonth()+3, nowDate.getDate(), 0, 0, 0, 0)

    $('#refundDate').on('apply.daterangepicker', function(ev, picker) {
        let date = picker.startDate.format('YYYY/MM/DD')+'~'+picker.endDate.format('YYYY/MM/DD')
        $(this).val(date)
        eventList.refundDateStart =  picker.startDate.format('YYYY/MM/DD')
        eventList.refundDateEnd =  picker.endDate.format('YYYY/MM/DD')
    });
    
    $('#refundDate').daterangepicker({
        "locale": {
            "format": "YYYY/MM/DD"
        },
        autoUpdateInput: false,
        startDate: starDat,
        minDate: starDat,
    });
</script>
@stop
