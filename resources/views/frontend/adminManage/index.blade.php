
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
    <h1>
        {{trans('userManage.S_UserSetting')}}
        <small></small>
    </h1>
    <!-- 網站導覽 -->
    <ol class="breadcrumb">
        <li class="active"><a href="#">{{trans('userManage.S_UserTop')}}</a></li>
    </ol>
    <!-- /.網站導覽 -->
@stop

@section('content')
<!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
<div id="userInfList" class="box no-border">
    <!---box-header--->
    <div class="box-header with-border-non" data-widget="collapse">
    <h3 class="box-title">{{trans('events.S_SearchBtn')}}</h3>
    <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
    </div>
    </div>

    <template v-if="!mailResult">
      <!-- mail failure modalshow-->
      <!--20201027 新增-->
      <div class="modal-mask">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"> 
              <h4 class="modal-title"><i class="fas fa-exclamation-triangle"></i>エラー通知</h4>
            </div>
            <div class="modal-body"> 
              <div class="row form-horizontal">
                <div class="col-md-12">
                  <h4 class="text-red">メールの送信に失敗しました。</h4>
                  <h4 class="text-red">ステータス更新処理を中止しました</h4>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" data-dismiss="modal" class="btn btn-default pull-left" @click="closeDialog()">閉じる</button>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!---/.box-header--->
    <form method="GET" action="">
        {{ csrf_field() }}
        <!--- box-body --->
        <div class="box-body">
            <div class="form-horizontal form-bordered col-md-6">
                <div class="form-group">
                    <label class="col-md-3 control-label">{{trans('events.S_searchKeyword')}}</label>
                    <div class="col-md-9">
                        <input name="keyword" type="text" class="form-control" id="" value="{{ $data["status"]["keyword"] }}" placeholder="{{trans('events.S_searchKeyword')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{trans('userManage.S_ApplicationDate')}}</label>
                    <div class="col-md-9 form-group-flex-normal">
                        
                            <div class="form-checkbox">
                            <input type="hidden" name="dateFilter" v-model="dateFilter">
                            <label class="control control--radio">
                                <input type="radio" value="all" v-model="dateRadio"/>
                                <div class="control__indicator"></div>
                                    {{trans('userManage.S_ApplicationAll')}}
                            </label>
                            </div>

                            <div class="form-checkbox pl-10">
                            <label class="control control--radio">
                                <input type="radio" value="date" v-model="dateRadio"/>
                                <div class="control__indicator"></div>
                            </label>
                            </div>

                            <div class="input-group with-fit height-fit" style="width: inherit;">
                                <input name="applyDate" type="text" class="form-control pull-right" id="dateRange">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>


                        </div>
                </div>

            </div>
            <div class="form-horizontal form-bordered col-md-6">
                <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('userManage.S_UserType')}}</label>

                <div class="col-sm-9">
                    <select name="userKbn[]" class="form-control select2" multiple="multiple" data-placeholder="" style="width: 100%;">
                        <option {{ $data["status"]["user-kbn-status"]["0"]  == "1" ? 'selected="selected"' : "" }} value="0">{{trans('userManage.S_Personal')}}</option>
                        <option {{ $data["status"]["user-kbn-status"]["1"]  == "1" ? 'selected="selected"' : "" }} value="1">{{trans('userManage.S_Company')}}</option>
                    </select>
                </div>
                </div>
                <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('userManage.S_Status')}}</label>
                <div class="col-sm-9">
                    <select name="adminStatus[]" class="form-control select2" multiple="multiple" data-placeholder="" style="width: 100%;">
                        <option {{ $data["status"]["admin-apply-status"]["0"]  == "1" ? 'selected="selected"' : "" }} value="0">{{trans('userManage.S_StatusUnreview')}}</option>
                        <option {{ $data["status"]["admin-apply-status"]["1"]  == "1" ? 'selected="selected"' : "" }} value="1">{{trans('userManage.S_StatusReviewing')}}</option>
                        <option {{ $data["status"]["admin-apply-status"]["2"]  == "1" ? 'selected="selected"' : "" }} value="9">{{trans('userManage.S_StatusReviewOK')}}</option>
                        <option {{ $data["status"]["admin-apply-status"]["3"]  == "1" ? 'selected="selected"' : "" }} value="2">{{trans('userManage.S_StatusReviewNG')}}</option>
                        <option {{ $data["status"]["admin-apply-status"]["4"]  == "1" ? 'selected="selected"' : "" }} value="8">{{trans('userManage.S_StatusLeaving')}}</option>
                        <option {{ $data["status"]["admin-apply-status"]["5"]  == "1" ? 'selected="selected"' : "" }} value="-2">{{trans('userManage.S_StatusLeaved')}}</option>
                        <option {{ $data["status"]["admin-apply-status"]["6"]  == "1" ? 'selected="selected"' : "" }} value="-1">{{trans('userManage.S_StatusStop')}}</option>
                    </select>
                </div>
                </div>
            </div>
        </div>
        <!---/.box-body --->
        <!---box-footer --->
        <div class="box-footer text-right">
            <button type="submit" value="submit" class="btn waves-effect waves-light btn-angle btn-info">{{trans('events.S_SearchBtn')}}</button>
        </div>
        <!---/.box-footer --->
    </form>
</div>
<!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
<!-- Filter + Page + table -->
<div class="dataTables_wrapper form-inline dt-bootstrap">
    <div class="m-b-20" style="display: none;">
        <div class="dataTables_formgroup">
            <!-- Item pre page -->
            <div class="dataTables_length" id="">
            <label>Item pre page
                <select name="" aria-controls="" class="form-control input-sm">
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
            <label>{{trans('userManage.S_Sort')}}
                <select name="" aria-controls="" class="form-control input-sm">
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
            <!-- /.Filter -->
        </div>
    </div>
    <!--  /.Filter & Page -->

    <!-- TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 + 無tablefoot -->
    <table id="" class="table table-striped">
        <thead>
            <tr>
            <th width="130">{{trans('userManage.S_ID')}}</th>
            <th width="310">{{trans('userManage.S_DispName')}}</th>
            <th>{{trans('userManage.S_UserType')}}</th>
            <th width="150">{{trans('userManage.S_ApplicationDate')}}</th>
            <th>{{trans('userManage.S_Status')}}</th>
            <th>{{trans('events.S_Option')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $data['data']['user-data'] as $adminInf )
                <tr>
                    <td>
                        <div class="text-left">{{ $adminInf['user_code'] }}</div>
                        <div class="text-right">{{ $adminInf['user_id'] }}</div>
                    </td>
                    <td>
                        <div class="text-left">{{ $adminInf['contract_name'] }}</div>
                        <!--<div class="text-left">{{ $adminInf['temporary_info'] }}</div>-->
                    </td>
                    <td>
                        @if ($adminInf['user_kbn'] === 1)
                            <div class="text-left">{{trans('userManage.S_Company')}}</div>
                            <div class="text-left"></div>
                        @elseif ($adminInf['user_kbn'] === 0)
                            <div class="text-left">{{trans('userManage.S_Personal')}}</div>
                        @else
                            <div class="text-left">-</div>
                            <div class="text-left">-</div>
                        @endif
                    </td>
                    <td>{{ $adminInf['app_date'] }}</td>
                    <td>
                        @if ($adminInf['user_status'] === 0)
                            {{trans('userManage.S_StatusUnreview')}}
                        @elseif ($adminInf['user_status'] === 1)
                            {{trans('userManage.S_StatusReviewing')}}
                        @elseif ($adminInf['user_status'] === 9)
                            {{trans('userManage.S_StatusReviewOK')}}
                        @elseif ($adminInf['user_status'] === 2)
                            {{trans('userManage.S_StatusReviewNG')}}
                        @elseif ($adminInf['user_status'] === 8)
                            {{trans('userManage.S_StatusLeaving')}}
                        @elseif ($adminInf['user_status'] === -2)
                            {{trans('userManage.S_StatusLeaved')}}
                        @elseif ($adminInf['user_status'] === -1)
                            {{trans('userManage.S_StatusStop')}}
                        @else
                            -
                        @endif
                    
                    </td>
                    <td><a href="/dataValidation/{{ $adminInf['GLID'] }}" class="btn btn-info-outline btn-ml" onclick="loading.openLoading()">{{trans('userManage.S_Detail')}}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- /.TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 + 無tablefoot -->

    <!-- Page navigation -->
    <div class="row">
    <!-- Page -->
        @if(!is_null($data['paginator']))
            <!-- Page navigation -->
            <div class="col-sm-12">
                <nav aria-label="Page navigation" class="pull-right">
                    {{ $data['paginator']->links() }}
                </nav>
            </div>
            <!-- /.Page navigation -->
        @endif
    <!-- /.Page-->
    </div>
    <!-- /.Page navigation -->
</div>
<script>
//Vue.config.devtools = true
var userInfList = new Vue({
    el: '#userInfList',
    data: {
        dateFilter:true,
        dateRadio:true,
        mailResult:true,
    },
    watch: {
        dateRadio:function(val){
            if(val == 'all'){
                this.dateFilter = null
            }else{
                this.dateFilter = true
            }
        },
    },
    methods: {
              closeDialog:function(){
                this.mailResult = true
                //document.body.style.overflowY = "scroll";
              },
    },
    mounted(){
        this.dateRadio = '{{ $data["status"]["date-filter"] }}'
        @if (session('applyResult'))
           let applyResult = '{!! session()->get( 'applyResult' ) !!}'
           applyResult = JSON.parse(applyResult) 
           this.mailResult = applyResult.mailResult
        @endif
        
        
    }
});

$(function () {
    //Initialize Select2 Elements - multiple & tag
    $('.select2').select2()
})

$('#dateRange').daterangepicker({
    "locale": {
        "format": "YYYY/MM/DD"
    },
    startDate: '{{ $data["status"]["star-date"] }}',
    endDate: '{{ $data["status"]["end-date"] }}'
});

</script>
@stop

