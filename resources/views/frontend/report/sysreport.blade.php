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
  <div id="report">
    <form id="outputRepot" method="POST" action="/systemreport/systemreport" target="_blank">
        {{ csrf_field() }}
        <input type="hidden" name="jsonRepo" v-model="jsonRepo">
    </form>
    <form id="searchEvent" method="POST" action="/systemreport" >
        {{ csrf_field() }}
        <input type="hidden" name="jsonEvent" v-model="jsonEvent">
    </form>
    <!-- box - 檢索 -->
    <div class="box no-border">
      <!---box-header--->
      <div class="box-header with-border-non">
        <h3 class="box-title">{{ trans('sellManage.S_SearchTitle2') }}</h3>
      </div>
      <!---/.box-header  --->
      <div class="box-body">
        <div class="form-horizontal form-bordered">
          <div class="form-body col-md-12">
            <!--form-group 1-->
            <div class="form-group">
              <label class="control-label col-md-2">{{trans('report.S_Term')}}</label>
              <div class="col-md-10">
                <div class="input-group">
                  <input type="text" class="form-control pull-left" id="dateRange">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>              
            </div>
            <!--/.form-group 1-->
            @if(session('GLID') == '1')
              <!-- 0521 新增調整 改為 select --->
              <!--form-group 2-->      
              @if(session('GLID') == '1')
                <div class="form-group">
                  <label class="control-label col-md-2">{{trans('report.S_Distributor')}}</label> 
                  <div class="col-md-10">
                    <select id="client" name="client" class="form-control" style="width: 100%;" onChange="changeSelect()">
                      <option value="0">{{trans('report.S_SelectDistributor')}}</option>
                      @foreach( $clients['data']['user-data'] as $adminInf )
                        <option {{ $glid == $adminInf['GLID'] ? 'selected="selected"' : "" }} value="{{ $adminInf['GLID'] }}">{{ $adminInf['contract_name'] }}</option>
                      @endforeach
                    </select>
                  </div> 
                </div> 
              @endif
              <!--/.form-group 2-->  
              <!-- /.0521 新增調整 改為 select --->       
            @endif
          </div>
        </div>
      </div>
      <!--0603 調整順序-->
      <!---box-footer  --->
         <div class="box-footer text-right">
           @if(session('GLID') == '1')
             <button id="selectClientReport" v-on:click="eventSearch()" class="btn waves-effect waves-light btn-angle btn-info" disabled>{{ trans('report.S_Search') }}</button>
           @elseif(session('GLID') != '1')
             <button v-on:click="eventSearch()" class="btn waves-effect waves-light btn-angle btn-info">{{ trans('report.S_Search') }}</button>           
           @endif
         </div>
      <!---/.box-footer  --->
      <!--/.0603 調整順序-->
    </div>
     <div class="box no-border">
      <div class="box-header">
        <h3 class="table-title"></h3>
        <p class="margin-fix"><span class="text-gray">{{trans('report.S_Term')}} ｜ </span>{{str_replace('-','/',$date['startDate'])}} - {{str_replace('-','/',$date['endDate'])}}</p>
        <!-- <p class="margin-fix"><span class="text-gray">{{trans('report.S_Distributor')}} ｜ </span> {{$user['data']['user_data']['contract_name']}}</p> -->
         <p class="margin-fix"><span class="text-gray">{{ trans('report.S_Distributor') }} ｜ </span>
                    {{ isset($user['data']['user_data']['contract_name']) ? $user['data']['user_data']['contract_name'] : '' }}
                </p>
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
<!--</form>-->
<script>   
var report = new Vue({
  el: '#report',
  data:{
      jsonRepo: '',
      jsonEvent: '',
  }, 
  methods: {
    eventSearch:function(){
      //buton click event(for client user)
      let range = document.getElementById("dateRange").value.split('/').join('').replace(' ', '');
      range = range.replace(' ', '');
      let glid = '{{ session("GLID") }}'
      if(glid == 1)
      {
        //LS user
        glid = document.getElementById("client").value;
      }
      
      let json    = []
      json.push({
         glid: glid,
         date: range,
         performance: performance,
      })     
      this.jsonEvent = JSON.stringify(json)
      
      this.$nextTick(() => {
         document.getElementById("searchEvent").submit()
      })
    },
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
         date: '{{str_replace("-","/",$date["startDate"])}} - {{str_replace("-","/",$date["endDate"])}}',
         performance: performance,
       })     
       this.jsonRepo = JSON.stringify(json)
       
       this.$nextTick(() => {
         document.getElementById("outputRepot").submit()
       })
    }
  },
});    
    
$(function(){
  setButtonDisabled();
});
function setButtonDisabled() {
  const GLID = document.getElementById("client").value;
  if(GLID==0)
  {
    document.getElementById("selectClientReport").disabled = true;
  }else{
    document.getElementById("selectClientReport").disabled = false;  
  }
} 
$('#dateRange').daterangepicker({
    "locale": {
        "format": "YYYY/MM/DD"
    },
    startDate: '{{ $date["startDate"]}}',
    endDate: '  {{ $date["endDate"]}}'
});

function changeSelect(){
  setButtonDisabled();
}
</script>
@stop
