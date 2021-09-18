@extends('adminlte::page')

@inject('PurviewHelpers', 'App\Helpers\PurviewHelpers')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
    <h1>
        {{ trans('member.S_MemberDetail') }}
        <small></small>
    </h1>
    <!-- 網站導覽 -->
    <ol class="breadcrumb">
        <li><a href="/member">{{ trans('member.S_MemberList') }}</a></li>
        <li class="active">{{ trans('member.S_MemberDetail') }}</li>
    </ol>
@stop

@section('content')
@if($events['status']['status'])
  <div id="memberInformation">
    <form id="filterData" method="GET" style="visibilitsectionay: table;" action="/member/information/{{ $events['status']['userId'] }}">
      <input type="hidden" name="search" v-model="search">
      <input type="hidden" name="orderStatus" v-model="orderStatus">
      <input type="hidden" name="keyWord" v-model="keyWord">
      {{ csrf_field() }}
    </form>
    <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
    <div class="box box-solid">
      <div class="box-header with-border">
        <span class="badge status-badge bg-red ml-15x">
          {{ $events['data']['userInf']['status'] }}
        </span>
        <h3 class="box-title text-black"> 
          <b>{{ trans('member.S_MemberId') }}</b>
          <b class="text-gray pl-10">
            {{ $events['data']['userInf']['user_id'] }}
          </b>
        </h3>
      </div>
      <div class="box-body pb-0"> 
        <!--  Row Data -->
        <!--  TABLE3 直式樣式  -->
        <div class="col-xs-6">
          <table id="" class="table table-striped table-normal mb-0">
            <tbody>
              <tr>
                <th>{{ trans('member.S_MemberName') }}</th>
                <td>{{ $events['data']['userInf']['name'] }}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_MobapassId') }}</th>
                <td>{{ $PurviewHelpers->hideInformation($events['data']['userInf']['mobapass_app_id']) }}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_MailAddress') }}</th>
                <td>{{ $PurviewHelpers->hideInformation($events['data']['userInf']['email'], 'email') }}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_Gender') }}</th>
                <td>{{ $events['data']['userInf']['gender'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!--  /. TABLE3 直式樣式  -->
        <!--  TABLE3 直式樣式  -->
        <div class="col-xs-6">
          <table id="" class="table table-striped table-normal mb-0">
            <tbody>
              <tr>
                <th>{{ trans('member.S_BirthDay') }}</th>
                <td>{{ $events['data']['userInf']['birthdate'] }}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_Phone') }}</th>
                <td>{{ $PurviewHelpers->hideInformation($events['data']['userInf']['tel']) }}</td>
              </tr>
              <!-- <tr>
                <th>住址</th>
                <td>11445 台北市內湖區洲子街**號*樓之1</td>
              </tr> !-->
            </tbody>
          </table>
        </div>
        <!--  /. TABLE3 直式樣式  -->
        <!-- /.Row Data -->
        <!-- TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
      </div>
      <!-- /.box-body -->
      <hr class="w-95">
      <!-- box-body -->
      <div class="box-body">
          <!--  Row Data -->
          <!--  TABLE3 直式樣式  -->
        <div class="col-xs-6">
          <table id="" class="table table-striped table-normal">
            <tbody>
              <tr>
                <th>{{ trans('member.S_MailMagazine') }}</th>
                <td>{{ ($events['data']['userInf']['email_status'])?'有':'無'}}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_Favorite') }}</th>
                <td>{{ $events['data']['userInf']['favoriteCount'] }}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_Purchase') }}</th>
                <td>{{ $events['data']['userInf']['purchase_ticket_count'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!--  /. TABLE3 直式樣式  -->
        <!--  TABLE3 直式樣式  -->
        <div class="col-xs-6">
          <table id="" class="table table-striped table-normal">
            <tbody>
              <tr>
                <th>{{ trans('member.S_Registration') }}</th>
                <td>{{ $events['data']['userInf']['created_at'] }}</td>
              </tr>
              <tr>
                <th>{{ trans('member.S_LastUpdate') }}</th>
                <td>{{ $events['data']['userInf']['logined_at'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!--  /. TABLE3 直式樣式  -->
        <!-- /.Row Data -->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->

    <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->



    <!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->


    <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title text-black pt-6x"><b>{{ trans('member.S_History') }}</b></h3>
        <!--  -->
        <div class="col-md-8 pull-right">
          <div class="form-group form-mb text-right col-md-3 col-xs-3">
            <div class="form-checkbox">
              <label class="control control--radio">
                <input type="radio" name="all" value="all" v-model="search">{{ trans('member.S_All') }}
                <div class="control__indicator"></div>  
              </label>
             <!-- 0511 調整<label>{{ trans('member.S_All') }}</label>-->
            </div>
          </div>
          <div class="form-group form-mb col-md-9 col-xs-9">
            <!--0511 調整-->
            <div class="form-checkbox form-group-flex-normal">
              <label class="control control--radio">
                <input type="radio" name="all" value="search" v-model="search">
                <div class="control__indicator"></div>
              </label>
              <div class="col col-xs-3">
                <select class="form-control" @change="onSreach()" v-model="orderStatus">
                  <option value="0">{{ trans('member.S_All') }}</option>
                  <option value="1">{{ trans('member.S_TempReserved') }}</option>
                  <option value="2">{{ trans('member.S_OnProcessing') }}</option>
                  <option value="3">{{ trans('member.S_Confirm') }}</option>
                </select>
              </div>
              <div class="col col-xs-9">
                <input type="text" class="form-control" placeholder="{{ trans('member.S_SearchPlacehoder') }}" v-on:blur="onSreach()" v-model="keyWord">
              </div>
              <div class="">
                <button type="button" class="btn btn-info" v-on:click="keyWordSend()">{{ trans('member.S_Search') }}</button>
              </div>
            </div>
          </div>
        </div>
        <!--  -->
      </div>
      <div class="box-body">
        <!--  Row Data -->
        <!--  TABLE3 直式樣式  -->
        <div class="col-xs-12">
          <div class="table-responsive">
            <table id="" class="table table-striped">
              <thead>
                <tr>
                  <th>{{ trans('member.S_OrderStatus') }}</th>
                  <th>{{ trans('member.S_OrderId') }}</th>
                  <th>{{ trans('member.S_OrderDate') }}</th>
                  <th>{{ trans('member.S_EventName') }}</th>
                  <th>{{ trans('member.S_Stage') }}</th>
                  <th>{{ trans('member.S_TicketType') }}</th>
                  <th>{{ trans('member.S_Payment') }}</th>
                  <th>{{ trans('member.S_PickUp') }}</th>
                  <th class="text-right">{{ trans('member.S_Num') }}</th>
                  <th class="text-right">{{ trans('member.S_Total') }}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($events['data']['orderInf'] as $event)
                  <tr>
                    <td>
                        {{$event['seat_status']}}
                    </td>
                    <td>{{ $event['reserve_no'] }}</td>
                    <td>{{ $event['reserve_date'] }}</td>
                    <td>
                        {{ $event['performance_name'] }}
                        @if($event['performance_status'] == -1)
                          <span class="badge bg-red">{{ trans('member.S_Stop') }}</span>
                        @endif
                    </td>
                    <td>{{ $event['eventStartDate'] }}</td>
                    <td>{{ $event['seat_class_name'] }}</td>
                    <td class="text-center">
                      @if($event['payment_fly'] == 0)
                        <i class="fas fa-times"></i>
                      @elseif ($event['payment_fly'] > 0)
                        <i class="fas fa-check"></i>
                      @else
                        <i class="fas fa-minus"></i>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($event['issue_flg'] == 0)
                        <i class="fas fa-times"></i>
                      @else
                        <i class="fas fa-check"></i>
                      @endif
                    </td>
                    <td class="text-right">{{ $event['total_ticket'] }}</td>
                    <td class="text-right">{{ $event['payment'] }}</td>
                    <td class="text-right"><a href="/member/orders/{{ $events['status']['userId'] }}/{{ $event['reserve_no'] }}" class="btn btn-info-outline btn-mm">{{ trans('member.S_OrderDetail') }}</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!--  /. TABLE3 直式樣式  -->
        <div class="m-b-20">
          <!-- Page navigation -->
          <div class="col-xs-12">
            <nav aria-label="Page navigation" class="pull-right">
            {{ $events['status']['paginator']->links() }}
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
Vue.config.debug = true;
 Vue.config.devtools = true;
var memberInformation = new Vue({
    el: '#memberInformation',
    data: { 
      search: '',
      orderStatus:'',
      keyWord:'',
    },
    watch: {
      search: function (val) {
        if(val == 'all'){
          this.orderStatus  = 0
          this.keyWord      = null
        }
      }
    },
    mounted:function(){
      this.search       = "{{ $events['status']['search'] }}"
      this.orderStatus  = "{{ $events['status']['orderStatus'] }}"
      this.keyWord      = "{{ $events['status']['keyWord'] }}"

      if(!this.search){
        this.search = 'all'
        this.orderStatus  = 0
      }
    },
    methods: {
      keyWordSend:function(){
        document.getElementById("filterData").submit();
      },
      onSreach:function(){
        this.search = "search"
      }
    },
})
</script>
@stop
