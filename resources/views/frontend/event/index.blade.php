@inject('CommonPresenter', 'App\Presenters\CommonPresenter')
@inject('EditListPresenter', 'App\Presenters\EditListPresenter')
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@section('content_header')
<h1>
    {{ trans('events.S_mainTitle_01') }}
    {{-- <small>公演一覽</small> --}}
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    {{-- <li><a href="#">{{ trans('events.S_mainTitle_02') }}</a></li>
    <li><a href="#">活動管理</a></li> --}}
    <li class="active">{{ trans('events.S_mainTitle_02') }}</li>
</ol>
<!-- /.網站導覽 -->
@if(session('event_info_flg') == 2)

@endif
@stop

@section('content')
    <!-- 0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
    @if(!session('root_account') && session('event_info_flg') == 2)
        <div class="funtion-btn-block">
            <a href="/events/create" class="btn waves-effect waves-light btn-rounded btn-primary"><i class="fas fa-plus ml-0 mr-15x"></i> {{ trans('events.S_newEvent_btn') }}        
            </a>
        </div>
    @endif
    <!-- /.0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
    <!-- Content Wrapper. Contains page content -->
    <div id="eventList">
      <!-- ========== 刪除設定 ========== -->
            <!-- modal -->
            <div class="modal-mask" id="stop-setting" v-show="showModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- form step 1 刪除確認-->
                                <h4 class="modal-title" v-show="deleteStep == 1"><i class="fas fa-exclamation-triangle"></i> {{ trans('events.S_eventDelTitle') }}</h4>
                            <!-- form step 1 刪除確認-->
                            <!-- form step 2 刪除結果-->
                                <h4 class="modal-title" v-show="deleteStep == 2">{{ trans('events.S_eventDelResult') }}</h4>
                            <!-- form step 2 刪除結果-->
                        </div>
                        <div class="modal-body">
                            <!-- form step 1 刪除確認-->
                                <div class="row form-horizontal" v-show="deleteStep == 1">
                                    <div class="col-md-12">
                                        <h4 class="text-red">
                                            <i class="fas fa-exclamation-triangle text-red"></i> 
                                             {{ trans('events.S_eventDelNotice') }}
                                        </h4>
                                        <div class="modal-overflow">
                                            <ul class="modal-list">
                                                <li><span class="badge bg-gray-light">{{ trans('events.S_eventcodeTitle') }}</span> @{{ performanceCode }}</li>
                                                <li><span class="badge bg-gray-light">{{ trans('events.S_eventMaintitleTitle') }}</span> @{{ performanceName }}</li>
                                                <li><span class="badge bg-gray-light">{{ trans('sellManage.S_EventOpenDate') }}</span> @{{ performanceStDt }} ~ @{{ performanceEndDt }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <!--</div>-->
                            <!-- /.form  -->
                            <!-- form  step 2 結果-->
                            <div class="row form-horizontal" v-show="deleteStep == 2">
                                    <div class="col-md-12">
                                        <h4 class="text-red">
                                            <i class="fas fa-exclamation-triangle text-red"></i> 
                                            @{{ deleteMsn }}
                                        </h4>
                                    </div>
                                </div>
                            <!-- /.form  -->
                        </div>
                        <div class="modal-footer">
                            <!-- form step 1 刪除確認-->
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" v-show="deleteStep == 1" v-on:click="cancel()">
                                    {{ trans('events.S_cancelBtn') }}
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#check-stop-setting" v-show="deleteStep == 1" v-on:click="confirm()">
                                    {{ trans('events.S_eventDel') }}
                                </button>
                            <!-- form step 1 刪除確認-->
                            <!-- form step 2 刪除結果-->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#check-stop-setting" v-show="deleteStep == 2" v-on:click="cancel()">
                                    {{ trans('events.S_eventDel') }}
                                </button>
                            <!-- form step 2 刪除結果-->
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        <!-- ========== End of 刪除設定 ========== -->
        <!-- delete event form -->
        <form id="deleteSend" method="POST" style="visibilitsectionay: table;" action="/events/delete">
            <input type="hidden" name="json" v-model="json">
            {{ csrf_field() }}
        </form>
        <!-- delete event form -->
        <!-- Main content -->
            <!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
            <form method="GET" action="/events/filter">
                {{ csrf_field() }}
                <div class="box no-border">
                    <div class="box-header with-border-non" data-widget="collapse">
                        <h3 class="box-title">{{ trans('events.S_searchTitle') }}</h3>
                        <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="">
                        <div class="col-md-6 form-group">
                            <label>{{ trans('events.S_searchKeyword') }}</label>
                            <input name="keyword" class="form-control input-sm" type="text" value="{{ $events['keyword'] }}" placeholder="">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('events.S_searchStatus') }}</label>
                            <select name="statusSelect[]" class="form-control select2" multiple="multiple" data-placeholder="" style="width: 100%;">
                                <option {{ $events["filterStatus"]['0']  == "0" ? 'selected="selected"' : "" }} value="0">{{trans('common.S_StatusCode_0')}}</option>
                                <option {{ $events["filterStatus"]['1']  == "0" ? 'selected="selected"' : "" }} value="1">{{trans('common.S_StatusCode_1')}} </option>
                                <option {{ $events["filterStatus"]['2']  == "0" ? 'selected="selected"' : "" }} value="2">{{trans('common.S_StatusCode_2')}}</option>
                                <option {{ $events["filterStatus"]['3']  == "0" ? 'selected="selected"' : "" }} value="3">{{trans('common.S_StatusCode_2_1')}}</option>
                                <option {{ $events["filterStatus"]['4']  == "0" ? 'selected="selected"' : "" }} value="4">{{trans('common.S_StatusCode_3')}}</option>
                                <option {{ $events["filterStatus"]['5']  == "0" ? 'selected="selected"' : "" }} value="5">{{trans('common.S_StatusCode_4')}}</option>
                                <option {{ $events["filterStatus"]['6']  == "0" ? 'selected="selected"' : "" }} value="6">{{trans('common.S_StatusCode_5')}}</option>
                                <option {{ $events["filterStatus"]['7']  == "0" ? 'selected="selected"' : "" }} value="7">{{trans('common.S_StatusCode_6')}}</option>
                                <option {{ $events["filterStatus"]['8']  == "0" ? 'selected="selected"' : "" }} value="8">{{trans('common.S_StatusCode_7')}}</option>
                                @if(!session('admin_flg'))
                                    <option {{ $events["filterStatus"]['9']  == "0" ? 'selected="selected"' : "" }} value="9">{{trans('common.S_StatusCode_8')}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right ">
                    <button type="submit" class="btn waves-effect waves-light btn-angle btn-info">{{ trans('events.S_SearchBtn') }}</button>
                </div>
                </div>
                <!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
                <!-- Filter + Page + table -->
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="m-b-20 " style="display: none;">
                    <!-- Item pre page -->
                    <div class="dataTables_formgroup">
                        <div class="dataTables_length" id="">
                            <label>Item pre page
                                <select name="pageShow" aria-controls="" class="form-control input-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                            </label>
                    </div>
                        <!-- /.Item pre page -->
                        <!-- Filter -->

                        <div class="dataTables_filter" id="">
                            <label>表示順
                                <select name="orderBySelect" aria-controls="" class="form-control input-sm">
                                    <option value="情報公開日">情報公開日</option>
                                    <option value="販售期間">販售期間</option>
                                    <option value="登錄日">登錄日</option>
                                </select>
                            </label>
                        </div>

                        <div class="filter-tools">
                            <button type="button" class="btn btn-filter" data-widget=""><i class="fas fa-sort-amount-up"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.Filter -->
                </div>
            </form>
            <!--  /.Filter & Page -->
            {{-- {{dd($events)}} --}}
                @if(!is_null($events['data']))
                    @foreach ($events['data'] as $event)
                        <!-- Box 3   status ＋ 標題 ＋ label ＋ 表格 須加樣式 no-radius-->
                        <div class="box box-solid collapsed-box no-radius">
                            <!---box-header--->
                            <div class="box-header with-border box-s3" data-widget="collapse">
                            <div class="flex-column-center">
                                <div class="status-box {{ $EditListPresenter->getStatusClass($event['status'], $event['sale_type']) }}">
                                    {{ $CommonPresenter->getStatusString($event['status'], $event['sale_type']) }}
                                </div>
                                <div class="status-timeout">
                                @if($event['performance_status'] == \Config::get('constant.performance_status.sale') && !$event['sale_type'] && $event['trans_flg'] == \Config::get('constant.GETTIIS_trans.already'))
                                            <i class="fas fa-stop-circle"></i> 一時停止
                                        @endif
                                </div>
                            </div>
                            <div class="title-row-box">
                                <div class="box-subtitle">
                                    <span class="label label-info-outline"> 
                                        @if(config('app.debug') == true)
                                        {{-- @if(0) --}}
                                            {{ $event['performance_id'] ." - ".$event['performance_code'] }}
                                        @else
                                            {{ $event['performance_code'] }}
                                        @endif
                                    </span> 
                                    <small class="subtitle">
                                        {{ $event['performance_name_sub'] }}
                                        <!--@if($event['performance_status'] == \Config::get('constant.performance_status.sale') && !$event['draft_sale_flg'])
                                            一時暫停
                                        @endif-->
                                    </small>
                                </div>

                                <h3 class="box-title">
                                    {{ $event['performance_name'] }}
                                </h3>
                            </div>
                            <!-- 收合 開關 -->
                            <div class="box-tools">
                                <button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.收合 開關 -->
                            </div>
                            <!---/.box-header--->
                            <div class="box-body">
                            <div class="row-group-grid">
                                <!-- Row1 -->
                                <div class="row ">
                                    <div class="col-xs-1 col-sm-1 grid-title">{{ trans('events.S_EventLocationTitle') }}</div>
                                    <div class="col-xs-11 col-sm-11 grid-text">
                                        {{ $event['hall_disp_name'] }}
                                    </div>
                                </div>
                                <!-- /.Row1 -->
                                <!-- Row2 -->
                                <div class="row">
                                    <div class="col-xs-1 col-sm-1 grid-title">{{ trans('events.S_eventPeriodTitle') }}</div>
                                    <div class="col-xs-3 col-sm-3 grid-text">{{ $event['performance_st_dt'] }} ~ {{ $event['performance_end_dt'] }}</div>
                                    <div class="col-xs-1 col-sm-1 grid-title">{{ trans('events.S_eventPublishDateTitle') }}</div>
                                    <div class="col-xs-3 col-sm-3 grid-text">{{ $event['disp_start'] }}
                                        <span class="badge bg-red">
                                            {{ ($event['trans_flg']==config('constant.GETTIIS_trans.yet'))?trans('events.S_eventNotPublish'):trans('events.S_eventPublished') }}
                                        </span>
                                        <span class="badge bg-red">
                                            @if($event['status'] > config('constant.performance_disp_status.browse'))
                                                @if($event['edit_status'] == config('constant.edit_status.not') )
                                                    {{trans('common.S_EditStatus_not')}}
                                                @elseif ($event['edit_status']  == config('constant.edit_status.going'))
                                                    {{trans('common.S_EditStatus_going')}}
                                                @elseif ($event['edit_status']  == config('constant.edit_status.complete'))
                                                    {{trans('common.S_EditStatus_complete')}}
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 grid-title">{{ trans('events.S_eventSellDateTitle') }}</div>
                                    <div class="col-xs-3 col-sm-3 grid-text">{{ $event['reserve_st_date'] }} ~ {{ $event['reserve_cl_date'] }}</div>
                                </div>
                                <!-- /.Row2 -->
                                <!-- Row3 -->
                                <div class="row ">
                                    <!--<div class="col-xs-1 col-sm-1 grid-title">{{ trans('events.S_eventOperationTitle') }}</div>-->
                                    <div class="col-xs-12 col-sm-12 grid-btns flex-center">
                                        @if ($event['status'] !== config('constant.performance_disp_status.deleted'))
                                            <a href="/events/info/{{ $event['performance_id'] }}" class="btn btn-info-outline btn-mm">{{ trans('events.S_eventOperationModify') }}</a> 
                                            @if($event['trans_flg']!=config('constant.GETTIIS_trans.yet'))
                                              <a  onclick="copyToClipBoard('copyText{{ $event['performance_id'] }}');" class="btn btn-darkblue-outline btn-mm">{{ trans('events.S_eventOperationCopylink') }}</a>
                                            @endif
                                        @endif
                                        <input  type="hidden" id="copyText{{ $event['performance_id'] }}" value="{{config('app.gsdomain')}}/event/detail/{{ $event['user_code'] }}/{{ $event['performance_code'] }}">
                                        @if($event['trans_flg'] === config('constant.GETTIIS_trans.yet') && $event['edit_status'] === config('constant.edit_status.complete')  && $event['status'] >= config('constant.performance_disp_status.browse') &&  session('event_info_flg') == 2)
                                            <form style="display: inline" method="POST" action="/events/trans/" @submit="submit">
                                                <input type="hidden" name="performance_id" value="{{ $event['performance_id'] }}">
                                                <button type="submit" class="btn btn-primary-outline btn-mm">
                                                    {{ trans('events.S_eventOperationPublish') }}
                                                </button>
                                                {{ csrf_field() }}
                                            </form>
                                        @elseif($event['edit_status'] === config('constant.edit_status.complete') && $event['status'] >= config('constant.performance_disp_status.browse') && session('event_info_flg') == 2)
                                            <form style="display: inline" method="POST" action="/events/republish/" @submit="submit">
                                                <input type="hidden" name="performance_id" value="{{ $event['performance_id'] }}">
                                                <button type="submit" class="btn btn-primary-outline btn-mm">
                                                    {{ trans('events.S_eventOperatioRenPublish') }}
                                                </button>
                                                {{ csrf_field() }}
                                            </form>
                                        @endif
                                        <div class="line"></div>
                                        @if(
                                            ($event['status'] == config('constant.performance_disp_status.going')        ||
                                             $event['status']  == config('constant.performance_disp_status.complete')    ||                                
                                             $event['status']  == config('constant.performance_disp_status.browse'))
                                             &&  session('event_info_flg') == 2
                                        )
                                            <a class="btn btn-danger btn-mm" v-on:click="openDeleteDialog({{ $loop->index }})">{{ trans('events.S_eventDelBtn') }}</a> 
                                            <div class="tip"><span data-tooltip="{{ trans('events.S_eventDelTooltip') }}"><i class="fas fa-info fa-1x"></i></span>
                                            </div> 
                                        @elseif ($event['status'] == config('constant.performance_disp_status.deleted'))

                                        @elseif ($event['stop_button_show']&&  session('event_info_flg') == 2)
                                            <a href="/schedule/list/{{ $event['performance_id'] }}/0" class="btn btn-danger btn-mm"  v-on:click="submit">{{ trans('events.S_eventSuspension') }}</a> 
                                            <div class="tip">
                                                <span data-tooltip="{{ trans('events.S_eventSuspensionTooltip') }}">
                                                    <i class="fas fa-info fa-1x"></i>
                                                </span>
                                            </div> 
                                        @else

                                        @endif
                                    </div>
                                </div>
                                <!-- /.Row3 -->
                            </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    @endforeach

                @endif
            </div>
            <!-- /.Box 3   status ＋ 標題 ＋ label ＋ 表格 -->
            <div class="row m-b-20 ">
                @if(!is_null($events['paginator']))
                    <!-- Page navigation -->
                    <div class="col-sm-12">
                        <nav aria-label="Page navigation" class="pull-right">
                            {{ $events['paginator']->links() }}
                        </nav>
                    </div>
                    <!-- /.Page navigation -->
                @endif
            </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    
    
    
    <!-- =============================================== -->
    @component('components/result')
        
    @endcomponent
    <script>
        // sidebar 
        $(document).ready(function () {
        $('.sidebar-menu').tree()
        })
        $(function () {
        //Initialize Select2 Elements - multiple & tag
        $('.select2').select2()
        })
        //debug 需要
        Vue.config.debug = true;
        Vue.config.devtools = true;
        //debug 需要
        var eventList = new Vue({
            el: '#eventList',
            data: { 
                showModal: false,
                deleteStep:'',
                deleteMsn:'',
                performanceData: '',
                performanceId: '',
                performanceCode: '',
                performanceName: '',
                performanceStDt:'',
                performanceEndDt: '',
                json:[],
            },
            mounted:function(){
                let data = '{!! addslashes($events["data_json"]) !!}'
                this.performance_data = JSON.parse(data)

                @if($events['performance_delete_result'])
                    this.showModal  = true
                    this.deleteStep = 2
                    this.deleteMsn  = '{{ $events['performance_delete_msn'] }}'
                @endif

                @if($events['performance_republish_result'])
                    let errorJson = '{!! $events['performance_republish_msn'] !!}'
                    popUpResult.open(errorJson)
                @endif
              
                @if(array_key_exists('performance_add_result',$events) &&  $events['performance_add_result'])
                    let errorJson = '{!! $events['performance_add_msn'] !!}'
                    popUpResult.open(errorJson)
                @endif
            },
            methods: {
                submit: function(){
                    loading.openLoading()
                },
                openDeleteDialog:function(index){
                    let data = this.performance_data[index]
                    this.deleteStep         = 1
                    this.performanceId      = data['performance_id']
                    this.performanceCode    = data['performance_code'] 
                    this.performanceName    = data['performance_name'] 
                    this.performanceStDt    = data['performance_st_dt'] 
                    this.performanceEndDt   = data['performance_end_dt'] 
                   
                    this.showModal = true
                },
                cancel:function(){
                    this.showModal = false
                    this.deleteStep         = ""
                    this.performanceId      = ""
                    this.performanceCode    = "" 
                    this.performanceName    = ""
                    this.performanceStDt    = ""
                    this.performanceEndDt   = ""
                },
                confirm:function(){
                    let data = []
                    let json = []

                    data.push({
                        performationId : this.performanceId,
                    })
                    json.push({
                        data : data,
                    })
                    this.json = JSON.stringify(json)
                    this.$nextTick(() => {
                        document.getElementById("deleteSend").submit();
                    })
                },
            },
        })
        
        function copyToClipBoard(idname) {
          var anyText= document.getElementById(idname).value;
          var textBox = document.createElement("textarea");
          textBox.setAttribute("id", "target");
          textBox.setAttribute("type", "hidden");
          textBox.textContent = anyText;
          document.body.appendChild(textBox);

          textBox.select();
          document.execCommand('copy');
          document.body.removeChild(textBox);
          
          alert('{{ trans("events.S_eventUrlCopy") }}');
        }

        window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload()
        }
    };
   </script>
@stop
