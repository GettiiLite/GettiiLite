@extends('adminlte::page')

@inject('PurviewHelpers', 'App\Helpers\PurviewHelpers')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
    <h1>
        {{ trans('member.S_OrderDetailTitle') }}
        <small></small>
    </h1>
    <!-- 網站導覽 -->
    <ol class="breadcrumb">
        <li><a href="/member">{{ trans('member.S_Top') }}</a></li>
        <li><a href="/member/information/{{$events['data']['userInf']['id'] }}">{{ trans('member.S_MemberDetail') }}</a></li>
        <li class="active">{{ trans('member.S_OrderDetailTitle') }}</li>
    </ol>
@stop

@section('content')
<div>
  {{-- <div class="back-group">
    <a href="/member/information/{{$events['data']['userInf']['id'] }}" class="btn waves-effect waves-light btn-angle btn-default">{{ trans('member.S_Back') }}</a>
  </div> --}}
  <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
  <div class="box box-solid collapsed-box">
    <div class="box-header with-border" data-widget="collapse">
      <span class="badge status-badge bg-red ml-15x">{{ $events['data']['userInf']['status'] }}</span>
      <h3 class="box-title text-black pl-15">
        <b>{{ trans('member.S_MemberId') }}</b> 
        <b class="text-gray pl-10">{{ $events['data']['userInf']['user_id'] }}</b>
      </h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body">
      <!--  Row Data -->
      <!--  TABLE3 直式樣式  -->
      <div class="col-xs-6">
        <table id="" class="table table-striped table-normal">
          <tbody>
            <tr>
              <th>{{ trans('member.S_MemberName') }}</th>
              <td>{{ $events['data']['userInf']['name'] }}</td>
            </tr>
            <tr>
              <th>{{ trans('member.S_MobapassId') }}</th>
              <td>{{ $PurviewHelpers->hideInformation($events['data']['userInf']['mobapass_app_id']) }}</td>
            </tr>
            <tr>
              <th>{{ trans('member.S_MailAddress') }}</th>
              <td>{{ $PurviewHelpers->hideInformation($events['data']['userInf']['email'], 'email') }}</td>
            </tr>
            <tr>
              <th>{{ trans('member.S_Gender') }}</th>
              <td>{{ $events['data']['userInf']['gender'] }}</td>
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
              <th>{{ trans('member.S_BirthDay') }}</th>
              <td>{{ $events['data']['userInf']['birthdate'] }}</td>
            </tr>
            <tr>
              <th>{{ trans('member.S_Phone') }}</th>
              <td>{{ $PurviewHelpers->hideInformation($events['data']['userInf']['tel']) }}</td>
            </tr>
            <tr>
              <th>{{ trans('member.S_Location') }}</th>
              <td>-</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--  /. TABLE3 直式樣式  -->
      <!-- /.Row Data -->
      <!-- TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->
  <!-- InfbBox Block 付款＋取票＋備註 -->
  <div class="row">
    <!--1-->
    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-grassgreen text-white">
          <i class="fas fa-hand-holding-usd width-65"></i>
          <br />
          <small>{{ trans('member.S_Payment') }}</small>
        </span>
        <div class="info-box-content">
          <span class="info-box-number">{{  $events['data']['orderInf']['payStatus'] }}</span>
          <span class="info-box-text">
            <span class="badge bg-gray">{{  $events['data']['orderInf']['payMethod'] }}</span> 
          </span>
          <span class="info-box-text">{{ $events['data']['orderInf']['payInfo']}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!--1-->
    <!--2-->
    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-lakeblue text-white"><i
            class="fas fa-ticket-alt width-65"></i><br /><small>{{ trans('member.S_PickUp') }}</small>
        </span>
        <div class="info-box-content">
          <span class="info-box-number">{{  $events['data']['orderInf']['pickupStatus'] }}</span>
          <span class="info-box-text">
            <span class="badge bg-gray">{{  $events['data']['orderInf']['pickupMethod'] }}</span> 
            <!--0000-5678-0000-->
          </span>
          <span class="info-box-text">{{ $events['data']['orderInf']['pickupInfo']}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!--2-->
    <!--3-->
    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-lightpink text-white"><i class="fas fa-file-invoice-dollar width-50"></i><br />
          <small>{{ trans('member.S_Canceled') }}</small>
        </span>
        <div class="info-box-content">
          @if($events['data']['orderInf']['cancel_flg'])
        <span class="info-box-number">{{$events['data']['orderInf']['cancelStatus']}}
          {{-- <small>不含手續費</small> --}}
        </span>
            <span class="info-box-text">{{$events['data']['orderInf']['cancelInfo1']}}</span>
            <span class="info-box-text">{{$events['data']['orderInf']['cancelInfo2']}}</span>
          @else
            <span class="info-box-text">{{ trans('member.S_NoCancel') }}</span>
          @endif
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!--3-->
    <!--4-->
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-lemonyellow text-white">
          <i class="fas fa-clipboard-check width-50"></i><br />
          <small>{{ trans('member.S_Description') }}</small>
        </span>
        <div class="info-box-content">
          <span class="info-box-textrow">{{$events['data']['orderInf']['memo']}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!--4-->
  </div>
  <!-- /.InfbBox Block 付款＋取票＋備註 -->
  <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <span class="badge status-badge bg-red ml-15x">{{ $events['status'] }}</span>
      <h3 class="box-title text-black">
        <span>{{ trans('member.S_OrderId') }}</span>
        <span class="text-gray pl-10">{{ $events['data']['orderInf']['reserveNo'] }}</span>
      </h3>
      <span class="text-black pl-30">
        <i class="fas fa-ticket-alt"></i><b class="text-gray pl-10 pr-10">{{ $events['data']['orderInf']['performanceName'] }}</b>
        <i class="fas fa-calendar-check"></i><b class="text-gray pl-10 pr-10">{{ $events['data']['orderInf']['eventDate'] }}</b>
        <i class="fas fa-clock"></i><b class="text-gray pl-10">{{ $events['data']['orderInf']['startTime'] }}</b>
      </span>
      <h5 class="text-gray pr-30 pull-right">{{ trans('member.S_OrderDate') }}<b class="text-gray pl-10">{{ $events['data']['orderInf']['reserveDate'] }}</b></h5>
    </div>
    <div class="box-body">
      <!--  Row Data -->
      <!--  TABLE3 直式樣式  -->
      <div class="col-xs-12">
        <div class="table-responsive">
          <table id="" class="table table-striped">
            <thead>
              <tr>
                <th width="75">{{ trans('member.S_SeatType') }}</th>
                <th>{{ trans('member.S_TicketType') }}</th>
                <th>{{ trans('member.S_TicketName') }}</th>
                <th>{{ trans('member.S_SeatPosition') }}</th>
                <!-- 金額＆數字置右 -->
                <th class="text-right">{{ trans('member.S_Price') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($events['data']['ticketInf'] as $event)
                <tr>
                  <td>{{ $event['seat_type'] }}</td>
                  <td>{{ $event['seat_class_name'] }}</td>
                  <td>{{ $event['ticket_class_name'] }}</td>
                  <td>{{ $event['saat_position'] }}</td>
                  <td class="text-right">{{ $event['payment'] }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot class="">
              <tr>
                <th colspan="5">
                
                  <div class="total-box w-45">
                    <div class="flex-end box-row">
                      <div class="w-100">{{ trans('member.S_Subtotal') }}</div>
                      <div class="text-right">{{ $events['data']['orderInf']['sumPayment'] }}</div>
                    </div>
                    @if ($events['data']['commissionInf']['commission_sv']['status'])
                      <div class="flex-end box-row">
                        <div class="w-100">{{ trans('member.S_SvFee') }}</div>
                        <div class="text-right">{{ $events['data']['commissionInf']['commission_sv']['total'] }}</div>
                      </div>
                    @endif
                    @if ($events['data']['commissionInf']['commission_payment']['status'])
                      <div class="flex-end box-row">
                        <div class="w-100">{{ trans('member.S_SettlementFee') }}</div>
                        <div class="text-right">{{ $events['data']['commissionInf']['commission_payment']['total'] }}</div>
                      </div>
                    @endif
                    @if ($events['data']['commissionInf']['commission_ticket']['status'])
                      <div class="flex-end box-row">
                        <div class="w-100">{{ trans('member.S_TicketingFee') }}</div>
                        <div class="text-right">{{ number_format($events['data']['commissionInf']['commission_ticket']['total']) }}</div>
                      </div>
                    @endif
                    @if ($events['data']['commissionInf']['commission_delivery']['status'])
                      <div class="flex-end box-row">
                        <div class="w-100">{{ trans('member.S_ShippingFee') }}</div>
                        <div class="text-right">{{ $events['data']['commissionInf']['commission_delivery']['total'] }}</div>
                      </div>
                    @endif
                    @if ($events['data']['commissionInf']['commission_sub']['status'])
                      <div class="flex-end box-row">
                        <div class="w-100">{{ trans('member.S_SubFee') }}</div>
                        <div class="text-right">{{ $events['data']['commissionInf']['commission_sub']['total'] }}</div>
                      </div>
                    @endif
                    @if ($events['data']['commissionInf']['commission_uc']['status'])
                      <div class="flex-end box-row">
                        <div class="w-100">{{ trans('member.S_UcFee') }}</div>
                        <div class="text-right">{{ $events['data']['commissionInf']['commission_uc']['total'] }}</div>
                      </div>
                    @endif
                    <div class="flex-end box-row">
                      <div class="w-100"><b>{{ trans('member.S_TotalAmount') }}</b></div>
                      <div class="text-right">{{ $events['data']['orderInf']['totalPayment'] }}</div>
                    </div>
                  </div>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <!--  /. TABLE3 直式樣式  -->
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->
</div>
@stop
