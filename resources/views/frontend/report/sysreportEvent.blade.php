@extends('adminlte::page')

@inject('PurviewHelpers', 'App\Helpers\PurviewHelpers')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
<h1>
    {{ trans('report.S_ReportManage') }}
    <small></small>
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
  <li><a href="/report">{{ trans('report.S_ReportList') }}</a></li>
  <li class="active">{{ trans('report.S_SystemReport') }}</li>
</ol>
@stop
@section('content')
<!--<form method="GET"  action="/systemreport/output"> -->
    <!-- box - 檢索 -->
    <div id="report">
    <form id="outputRepot" method="POST" action="/systemreport/systemreport" target="_blank">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
    </form>
<!--0520 調整-->
    <div class="box no-border">
      <div class="box-header">
        <h3 class="table-title"></h3>
        <p class="margin-fix"><span class="text-gray">{{trans('report.S_Term')}} ｜ </span>{{$date}}</p>
        <p class="margin-fix"><span class="text-gray">{{trans('report.S_Distributor')}} ｜ </span> {{$user['data']['user_data']['contract_name']}}</p>
      </div>
    </div>
    <div class="box no-border">
      <div class="box-body">
        <div class="col-md-12">
          <div class="form-horizontal form-bordered">
            <div class="form-body">
              <table class="table table-striped table-normal">
                <thead>
                  <tr>
                    <th width="90"></th>
                    <th>{{trans('report.S_Event')}} </th>
                    <th>{{trans('report.S_Date')}} </th>
                    <th>{{trans('report.S_Status')}} </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach( $eventData as $event )
                    <tr>
                      <td>
                        <div class="form-checkbox form-checkbox-fix">
                          <label class="control control--checkbox">
                            <input type="checkbox"  class="chk"> 
                              <div class="control__indicator__normal"></div>
                          </label>
                        </div>
                      </td>
                      <input type="hidden" class="performance" value="{{$event['performance_id'] }}">
                      <td>
                        <div class="box-subtitle">
                          <span class="label label-info-outline"> 
                            {{$event['performance_code']}}
                          </span> 
                        </div>
                        {{$event['performance_name'] }}
                      </td>
                      <td>
                        {{$event['performance_st_dt'] }} - {{$event['performance_end_dt'] }}
                      </td>
                      <td>
                        {{$event['status'] }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.form-body-->
          </div>
        </div>
      </div>
      <div class="box-footer text-right">
        <button v-on:click="outputReport()" class="btn waves-effect waves-light btn-angle btn-info"> {{ trans('report.S_Output') }}</button>
      </div> 
      </div> 
    </div>

    
    <!-- /.box - 檢索 -->   
<!--</form>-->
<script>    
var report = new Vue({
  el: '#report',
  data:{
      json: '',
  }, 
  methods: {
    outputReport:function(){
      let json    = []
      let performance    = []        
        
      var CHK = document.getElementsByClassName('chk');
      var PerformanceId = document.getElementsByClassName('performance');
      for (var i = 0; CHK.length > i; i++) {
        if(CHK[i].checked){
          performance.push(PerformanceId[i].value)
        }
      }
      if(performance.length == 0) {
        alert(' {{ trans('report.S_Msg1') }}');
        return;
      }
      json.push({
         glid: '{{$glid}}',
         date: '{{$date}}',
         performance: performance,
       })     
       this.json = JSON.stringify(json)
       
       this.$nextTick(() => {
         document.getElementById("outputRepot").submit()
       })
    }
  },
});
</script>
@stop
