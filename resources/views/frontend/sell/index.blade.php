@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
<h1>
    {{trans('sellManage.S_MainTitle')}}
    {{-- <small>{{trans('sellManage.S_SubTitle_1')}}</small> --}}
</h1>
<ol class="breadcrumb">
    <li class="active">{{trans('sellManage.S_SubTitle_1')}}</li>
</ol>


@stop

@section('content')
<!-- 0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div class="funtion-btn-block">
    <button type="button" onclick="createCsvFile()" class="btn waves-effect waves-light btn-rounded btn-inverse"> {{trans('sellManage.S_CsvButton')}} <!--<i class="fas fa-file-export"></i>--> </button>
</div>
<!-- /.0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
<!--0511 調整樣式-->
    <form method="POST" action="">
        {{ csrf_field() }}
        <div class="box no-border">
            <div class="box-header with-border-non" data-widget="collapse">
                <h3 class="box-title">{{trans('sellManage.S_Search')}}</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="">
                    <div class="col-md-6 form-group">
                        <label>{{trans('sellManage.S_Keyword')}}</label>
                        <input name="keyword" class="form-control input-sm" type="text" value="{{ $events['keyword'] }}" placeholder="">
                    </div>
    
                    <div class="col-md-6 form-group">
                        <label>{{trans('sellManage.S_Status')}}</label>
                        <select name="statusSelect[]" class="form-control select2" multiple="multiple" data-placeholder="" style="width: 100%;">
                        <option {{ $events["filterStatus"]['3']  == "0" ? 'selected="selected"' : "" }} value="4">{{trans('common.S_StatusCode_3')}}</option>
                        <option {{ $events["filterStatus"]['4']  == "0" ? 'selected="selected"' : "" }} value="5">{{trans('common.S_StatusCode_4')}}</option>
                        <option {{ $events["filterStatus"]['5']  == "0" ? 'selected="selected"' : "" }} value="6">{{trans('common.S_StatusCode_5')}}</option>
                        <option {{ $events["filterStatus"]['6']  == "0" ? 'selected="selected"' : "" }} value="7">{{trans('common.S_StatusCode_6')}}</option>
                        <option {{ $events["filterStatus"]['7']  == "0" ? 'selected="selected"' : "" }} value="8">{{trans('common.S_StatusCode_7')}}</option>
                        </select>
                    </div>
            </div>
        </div>
        <div class="box-footer text-right">
            <button type="submit" value="submit" class="btn waves-effect waves-light btn-angle btn-info">{{trans('events.S_SearchBtn')}}</button>
        </div>
    </div>
</form>
<!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->

    
<!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
<div class="box box-solid">
    <div class="box-body">
    <!-- TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
    <table id="" class="table table-striped">
        <thead>
        <tr>
            <th>{{trans('sellManage.S_EventTitle')}}</th>
            <th>{{trans('sellManage.S_EventStatus')}}</th>
            <th class="text-right">{{trans('sellManage.S_EventSeatTotal')}}
<!--20200909  新增小i提示-->
<div class="tip">
    <span data-tooltip="指定席數／自由席數">
 <i class="fas fa-info fa-1x fa__thead"></i>
</span>
</div>
<!--/.20200909  新增小i提示-->
            </th>
            <!-- 0901新增 -->
            <th class="text-right">{{trans('sellManage.S_EventOnPorcessTotal')}}</th>
            <th class="text-right">{{trans('sellManage.S_EventSellTotal')}}</th>
            <th class="text-right">{{trans('sellManage.S_EventNoSellTotal')}}</th>
            <th class="text-right">{{trans('sellManage.S_EventRestOfSeat')}}</th>
            <th class="text-right">{{trans('sellManage.S_EventMaxTotal')}}
<!--20200909  新增小i提示-->
<div class="tip">
    <span data-tooltip="発券数／押え席数">
 <i class="fas fa-info fa-1x fa__thead"></i>
</span>
</div>
<!--/.20200909  新增小i提示-->

            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach ($events["data"] as $event) 
                <tr>
                    <td>{{ $event["performanceTilte"] }}</td>
                    <td>{{ $event["performanceDispStatus"] }}</td>
                    <td class="text-right">{{ $event["seatTotal"] }}</td>
                    <td class="text-right">{{ $event["inpayTotal"] }}</td>
                    <td class="text-right">{{ $event["sellTotal"] }}</td>
                    <td class="text-right">{{ $event["seatReserveTotal"] }}</td>
                    <td class="text-right">{{ $event["unsellTotal"] }}</td>
                    <td class="text-right">{{ $event["sellPriceTotal"] }}</td>
                    <td><a href='/sell/manage/{{ $event["perfomanceId"] }}' class="btn btn-info-outline btn-sm" onclick="loading.openLoading()">{{trans('sellManage.S_EventShowBtn')}}</a></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="tfoot-light">
        <tr>
            <th colspan="3" class="text-center">{{ trans('sellManage.S_TableTotal') }}</th>
            <th class="text-right">{{ $events["allInpayTotal"] }}</th>
            <th class="text-right">{{ $events["allSellTotal"] }}</th>
            <th class="text-right">{{ $events["allSeatReserveTotal"] }}</th>
            <th class="text-right">{{ $events["allUnsellTotal"] }}</th>
            <th class="text-right">{{ $events["allIncomeTotal"] }}</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
    <!-- /.TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
    </div>
    <!-- /.box-body -->
</div>
<!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->


<script>
    // table - toggle & footable
    $('.accordian-body').on('show.bs.collapse', function () {
      $(this).closest("table")
        .find(".collapse.in")
        .not(this)
        .collapse('toggle')
    })
    $(function () {
      //Initialize Select2 Elements - multiple & tag
      $('.select2').select2()
    })
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
