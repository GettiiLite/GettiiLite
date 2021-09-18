@extends('adminlte::page')

@inject('PurviewHelpers', 'App\Helpers\PurviewHelpers')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
<h1>
    {{ trans('member.S_MemberManage') }}
    <small></small>
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    <li class="active">{{ trans('member.S_MemberList') }}</li>
</ol>
@stop

@section('content')
{{-- {{dd($events)}} --}}
@if($events['status']['status'])
  <div id="memberList">
    <form id="keyWordSend" method="GET" style="visibilitsectionay: table;" action="/member">
        <input type="hidden" name="keyWord" v-model="keyWord">
        <input type="hidden" name="orderId" v-model="orderId">
        {{ csrf_field() }}
    </form>
    <!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
    <!--0511 調整樣式-->
    <div class="box no-border">
      <div class="box-header with-border-non" data-widget="collapse">
        <h3 class="box-title">{{ trans('member.S_Search') }}</h3>
        <div class="box-tools pull-right">
          <!--  icon 樣式 <i class="fas fa-ellipsis-h"></i>  -->
          <button type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="form-horizontal">
            <div class="col-md-12">
              <!--  form-group 1-->
              <div class="form-group">
                <div class="col-md-6">
                  <label class="control-label col-md-4">{{ trans('member.S_Keyword') }}</label>
                  <div class="col-md-8">
                    <input class="form-control input-sm" type="text" placeholder="{{ trans('member.S_KeywordPlaceholder') }}" v-model="keyWord">
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="control-label col-md-4">{{ trans('member.S_OrderId') }}</label>
                  <div class="col-md-8">
                    <input class="form-control input-sm" type="text" placeholder="{{ trans('member.S_OrderIdPlaceholder') }}" v-model="orderId">
                  </div>
                </div>
              </div>
              <!--/.form-group 1-->
            </div>
          </div>
        </div>
      </div>
      <!--0511 調整text-right-->
      <div class="box-footer text-right">
        <button type="button" class="btn waves-effect waves-light btn-angle btn-info" v-on:click="keyWordSend()">{{ trans('member.S_Search') }}</button>
      </div>
    </div>
    <!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
    <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
    <div class="box box-solid">
      <div class="box-body">
        <!-- TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
        <div class="table-responsive">
          @if($events['status']['search'])
              <table id="" class="table table-striped">
                <thead>
                  <tr>
                    <th>{{ trans('member.S_MemberId') }}</th>
                    <th>{{ trans('member.S_MemberName') }}</th>
                    <th>{{ trans('member.S_MailAddress') }}</th>
                    <th>{{ trans('member.S_Phone') }}</th>
                    <th>{{ trans('member.S_Status') }}</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($events['data']['userInf'] as $event)
                    <tr>
                      <td>{{ $event['user_id'] }}</td>
                      <td>{{ $event['name'] }}</td>
                      <td>{{ $PurviewHelpers->hideInformation($event['email'], 'email') }}</td>
                      <td>{{ $PurviewHelpers->hideInformation($event['tel']) }}</td>
                      <td>{{ $event['status'] }}</td>
                      <td><a href="/member/information/{{ $event['id'] }}" class="btn btn-info-outline btn-mm">{{ trans('member.S_Detail') }}</a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
          @else
            <div class="col-md-9 callout callout-tip-warning ">
                <!-- -->
                <div class="icon">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="">{{ $events['data']['errorData']['msn']  }}</p>
            </div>
          @endif
        </div>
        <!-- /.TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
        <div class="m-b-20">
            <!-- Page navigation -->
          <div class="col-xs-12">
            <nav aria-label="Page navigation" class="pull-right">
              @if($events['status']['search'])
                  {{ $events['status']['paginator']->links() }}
              @endif
            </nav>
          </div>
          <!-- /.Page navigation -->
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->
  </div>
@else
  @component('frontend.messages.messages',  $events['data']['errorData'])
    
  @endcomponent
@endif
<script>

var memberList = new Vue({
    el: '#memberList',
    data: { 
      keyWord: '',
      orderId: '',
    },
    mounted:function(){
      this.keyWord  = "{{ $events['status']['keyWord'] }}"
      this.orderId  = "{{ $events['status']['orderId'] }}"
    },
    methods: {
      keyWordSend:function(){
        document.getElementById("keyWordSend").submit();
      },
    },
})
</script>
@stop
