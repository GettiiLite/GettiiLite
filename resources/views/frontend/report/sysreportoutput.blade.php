<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        /* font setting start*/
        /* 2021/06/01 - STS - START */
        /* @font-face {
            font-family: "Noto Sans CJK JP";
            font-style: normal;
            font-weight: 400;
            src: url("{{ storage_path("fonts/NotoSansCJKjp-Regular.ttf") }}") format("Truetype");
        }
        @font-face {
            font-family: "Noto Sans";
            font-style: normal;
            font-weight: 500;
            src: url("{{ storage_path("fonts/NotoSans-Medium.ttf") }}") format("Truetype");
        }
        body {
            font-family: "Noto Sans CJK JP","Noto Sans" !important;
            font-feature-settings: "palt";
        }
        h1 {
            font-family: "Noto Sans CJK JP","Noto Sans" !important;
            font-weight: 400;
            font-feature-settings: "palt";
        } */
        
        @font-face {
            font-family: "ipaexg";
            font-style: normal;
            font-weight: 400;
            src: url("{{ storage_path("fonts/ipaexg.ttf") }}") format("Truetype");
        }
        @font-face {
            font-family: "ipaexg";
            font-style: normal;
            font-weight: 500;
            src: url("{{ storage_path("fonts/ipaexg.ttf") }}") format("Truetype");
        }
        body {
            font-family: "ipaexg","ipaexg" !important;
            font-feature-settings: "palt";
        }
        h1 {
            font-family: "ipaexg","ipaexg" !important;
            font-weight: 400;
            font-feature-settings: "palt";
        }
		/* 2021/06/01 - STS - END */
        /* font setting end*/
        
        /* 隣接する線を重ねて表示 */
        .table-css { 
          border-collapse: collapse; 
          font-size: 10pt;
        }
 
        /* 左側の線のみ非表示。*/
        .td-leftnone { 
          border: 1px solid black; 
          border-left-style:none; 
        } 

        /* 右側の線のみ非表示。*/
        .td-rightnone { 
          border: 1px solid black; 
          border-right-style:none; 
        } 

        /* 上の線のみ表示。*/
        .td-onlytop { 
          border: 1px solid black; 
          border-bottom-style:none; 
          border-right-style:none; 
          border-left-style:none; 
        } 
        
        /* 下の線のみ表示。*/
        .td-onlybottom { 
          border: 1px solid black; 
          border-top-style:none; 
          border-right-style:none; 
          border-left-style:none; 
        } 

        /* 上下の線のみ表示。*/
        .td-rightleftnone { 
          border: 1px solid black; 
          border-right-style:none; 
          border-left-style:none; 
        } 

        /* 改ページ */
        .page {
          page-break-after: always;                
        }
         /* border style */
        .table-border {
            border-collapse: collapse;
            font-size: .925rem;
            border: 1px solid #000;
            word-wrap: break-word;
   	        table-layout: fixed;
        }
        
        .table-border th,
        .table-border td {
            border-collapse: collapse;
            border: 1px solid #000;
            padding: .125rem .9rem;
            line-height: 1;
            vertical-align: top;
        }
        /* outline + noborder style */        
        .table-noborder {
            border-collapse: collapse;
            font-size: .925rem;
            margin: 0;
            border: none;
            word-wrap: break-word;
   	        table-layout: fixed;
             vertical-align: baseline;
        }
        
        .table-noborder td {
            border: none;
            vertical-align: baseline;
            line-height: 1;
            word-wrap: break-word;
            overflow-wrap:anywhere;
            padding:.25rem 0
        }
                /* text-align setting */
        
        .text-left {
            text-align: left;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<!-- report data -->
<h1 style="text-align:center">Gettii Lite{{ trans('report.S_SystemReport') }}</h1>
<p style="text-align:right">{{ trans('report.S_CreateDate') }} {{ $param["createDate"] }} 1</p>
<p style="text-align:left">{{ trans('report.S_Term') }}  {{ $param["startDate"] }}　-　{{ $param["endDate"] }}</p>

 <?php $sumItme = 'A+B';?>
 <?php $sumA = $param["repoInfo"]["cardSoldPrice"]["sale_price"] + $param["repoInfo"]["cardSoldCommission"]["commission_ticket"] + $param["repoInfo"]["cardSoldCommission"]["commission_sv"] - $param["repoInfo"]["cardCancel"]["refund_payment"];?>
 <?php $sumB = $param["repoInfo"]["storeSoldPrice"]["sale_price"]+$param["repoInfo"]["storeSoldCommission"]["commission_ticket"]+$param["repoInfo"]["storeSoldCommission"]["commission_payment"]+$param["repoInfo"]["storeSoldCommission"]["commission_sv"];?>
 <?php 
       $sumD = $param["repoInfo"]["cardPaymentCommission"]["commission_card_payment"];
       foreach( $param["repoInfo"]["cardPaymentCommission"]["cardCancelCommission"] as $cancelCommission)
       {
         $sumD = $sumD + $cancelCommission['cancel_commission'];
       } 
 ?>
 <?php 
       $sumE = 0;  //$param["repoInfo"]["storeCommission"]["ticketCommission"]["ticket_commission"] + $param["repoInfo"]["storeCommission"]["rceiptCommission"]["receipt_commission"];
       foreach($param["repoInfo"]["storeCommission"]["ticketCommission"] as $ticketCommission)
       {
           $sumE = $sumE + $ticketCommission['ticket_commission'];
       }
       foreach($param["repoInfo"]["storeCommission"]['rceiptCommission'] as $receiptCommission)
       {
           $sumE = $sumE + $receiptCommission['receipt_commission'];    
       }        
       $sumG = $param["repoInfo"]["runingCommission"];
       $fee = $param["repoInfo"]['fee'];
     
       $sum = $sumA + $sumB - $sumD - $sumE - $sumG;
       $get_trans_fee = GLHelpers::getTransFee($sum, $param["trans_fee"]);

      if($get_trans_fee >= 0){
        $fee = $get_trans_fee;
        $sum -= $fee;
        $sum_net = number_format($sum);
      }else{
        $fee = 'error';
        $sum_net = 'error';
      }

      
       
 ?>
 <?php $sumG = $param["repoInfo"]["runingCommission"];?>

    <table class="table-border" width="100%">
        <thead>
            <tr>
                <td class="text-center" width="50%">
                    精算金額(税込) 
                </td>
                <td class="text-center" width="50%">
                    振込先口座
                </td> 
            </tr>
        </thead>
        <tbody>
           <tr>
                <td>
                    <table class="table-noborder" width="100%">
                        <tr>
                            <td width="50%">{{ trans('report.S_Summary') }}  (1)</td>
                            <td class="text-right" >{{number_format($sumA + $sumB - $sumD - $sumE - $sumG)}}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('report.S_SummaryFee') }} (2)</td>
                            <td class="text-right" >{{$fee}}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('report.S_SummaryNet') }}(1)-(2)</td>
                            <td class="text-right" >{{$sum_net}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table-noborder" width="100%">
                        <tr>
                            <td width="25%">{{ trans('report.S_Bank') }}</td>
                            <td>{{$param['bank_inf']['bank_name']}}  {{$param['bank_inf']['branch_name']}}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('report.S_AccountNum') }}</td>
                            <td>{{$param['bank_inf']['account_num']}} </td>
                        </tr>
                        <tr>
                            <td>{{ trans('report.S_AccountName') }}</td>
                            <td>{{$param['bank_inf']['account_name']}}</td>
                        </tr>
                    </table>
                </td>
            </tr>

        </tbody>
    </table>

<table class="table-css" width="100%" >
  <tr>
    <td width="25%">{{ trans('report.S_SummaryTitle') }} </td>
    <td class="td-onlybottom">{{ trans('report.S_CreditSum') }}【A】</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($sumA)}}</td>
  </tr>
  <tr>
    <td></td>
    <td class="td-onlybottom">{{ trans('report.S_SevenSum') }}【B】</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($sumB)}}</td>
  </tr>
  @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCommission"]["amount"]) != 0 ||
      number_format($param["repoInfo"]["CreditCancelCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCancelCommission"]["amount"]) != 0)
    <?php $sumItme = $sumItme . '-D';?>
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_CreditAmountSum') }}【D】</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumD)}}</td>
    </tr>
  @endif
  @if(number_format($param["repoInfo"]["SevenPickup"]["rate"]) != 0 || number_format($param["repoInfo"]["SevenPickup"]["amount"]) != 0)
    <?php $sumItme = $sumItme . '-E';?>
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_SevenAmountSum') }}【E】</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumE)}}</td>
    </tr>
  @endif
  @if(number_format($param["repoInfo"]["RunningCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["RunningCommission"]["amount"]) != 0)
    <?php $sumItme = $sumItme . '-G';?>
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_RunningSum') }}【G】</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumG)}}</td>
    </tr>
  @endif
</table>
<p>
<table  class="table-css" width="100%">
  <tr>
    <td class="td-rightleftnone" ></td>
  </tr>
</table>
<br><br><br>
{{ trans('report.S_TotalDetail') }}

<table  class="table-css" width="100%">
  <tr>
    <td class="td-rightleftnone"  width="25%"></td>
    <td class="td-rightleftnone" width="40%">{{ trans('report.S_Category') }}</td>
    <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_UnitPrice') }}</td>
    <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_Number') }}</td>
    <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_Page') }}</td>
    <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_Price') }}</td>
  </tr>
  <tr>
    <td>{{ trans('report.S_Credit') }}</td>
    <td class="td-onlybottom">{{ trans('report.S_TicketPrice') }} …(11)</td>
    <td class="td-onlybottom" style="text-align:right"></td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardSoldPrice"]["reserve_num"])}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardSoldPrice"]["seats_num"])}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardSoldPrice"]["sale_price"])}}</td>
  </tr>
  @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_TicketingAmount') }} …(12)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardSoldCommission"]["commission_ticket"])}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_ServiceAmount') }} …(15)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardSoldCommission"]["commission_sv"])}}</td>
    </tr>
  @endif
  <tr>
    <td></td>
    <td class="td-onlybottom">{{ trans('report.S_CancelPrice') }} …(18)</td>
    <td class="td-onlybottom" style="text-align:right"></td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardCancel"]["cancel_num"])}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardCancel"]["cancel_sheets_num"])}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardCancel"]["refund_payment"])}}</td>
  </tr>
  <tr>
    <td></td>
    @if(\App::getLocale() == "ja" ) <!-- modified by LS#1475 日本版不要項目 -->
      <td class="td-onlybottom" colspan="4">{{ trans('report.S_CreditSum') }}【A】(11)-(18)</td>
    @else
      <td class="td-onlybottom" colspan="4">{{ trans('report.S_CreditSum') }}【A】(11)+(12)+(15)-(18)</td>
    @endif
    <td class="td-onlybottom" style="text-align:right">{{number_format($sumA)}}</td>
  </tr>
  <tr>
    <td class="td-onlytop">{{ trans('report.S_Seven') }}</td>
    <td class="td-onlybottom">{{ trans('report.S_TicketPrice') }} …(21)</td>
    <td class="td-onlybottom" style="text-align:right"></td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["storeSoldPrice"]["reserve_num"])}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["storeSoldPrice"]["seats_num"])}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["storeSoldPrice"]["sale_price"])}}</td>
  </tr>
  @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
  <tr>
    <td></td>
    <td class="td-onlybottom">{{ trans('report.S_TicketingAmount') }} …(22)</td>
    <td class="td-onlybottom" style="text-align:right"></td>
    <td class="td-onlybottom" style="text-align:right">0</td>
    <td class="td-onlybottom" style="text-align:right">0</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["storeSoldCommission"]["commission_ticket"])}}</td>
  </tr>
  <tr>
    <td></td>
    <td class="td-onlybottom">{{ trans('report.S_PaymentAmount') }} …(24)</td>
    <td class="td-onlybottom" style="text-align:right"></td>
    <td class="td-onlybottom" style="text-align:right">0</td>
    <td class="td-onlybottom" style="text-align:right">0</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["storeSoldCommission"]["commission_payment"])}}</td>
  </tr>
  <tr>
    <td></td>
    <td class="td-onlybottom">{{ trans('report.S_ServiceAmount') }} …(25)</td>
    <td class="td-onlybottom" style="text-align:right"></td>
    <td class="td-onlybottom" style="text-align:right">0</td>
    <td class="td-onlybottom" style="text-align:right">0</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["storeSoldCommission"]["commission_sv"])}}</td>
  </tr>
  @endif
  <tr>
    <td></td>
    @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
      <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenSum') }}【B】(21)+(22)+(24)+(25)</td>
    @else
      <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenSum') }}【B】(21)</td>
    @endif
    <td class="td-onlybottom" style="text-align:right">{{number_format($sumB)}}</td>
  </tr>
  @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCommission"]["amount"]) != 0 ||
      number_format($param["repoInfo"]["CreditCancelCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCancelCommission"]["amount"]) != 0)
    <?php $sumDInfo = '';?>
    @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCommission"]["amount"]) != 0)
      <?php $sumDInfo = '(41)';?>
      <tr>
        <td class="td-onlytop">{{ trans('report.S_CreditAmount') }}</td>
        <td class="td-onlybottom">{{ trans('report.S_SettlementAmount') }} …(41)</td>
        <td class="td-onlybottom" style="text-align:right"></td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardPaymentCommission"]["commission_card_payment"])}}</td>
      </tr>
    @endif
    @if(number_format($cancelCommission["unit_price"]) != 0 || number_format($cancelCommission["unit_rate"]) != 0)
      <?php $sumDInfo = $sumDInfo . ((strlen($sumDInfo) == 0)? '(43)' : '+(43)');?>
      @foreach($param["repoInfo"]["cardPaymentCommission"]["cardCancelCommission"] as $cancelCommission)
        <tr>
          @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) == 0 && number_format($param["repoInfo"]["CreditCommission"]["amount"]) == 0)
            <td class="td-onlytop">{{ trans('report.S_CreditAmount') }}</td>
          @else
            <td></td>
          @endif
          <td class="td-onlybottom">{{ trans('report.S_CancelAmount') }} …(43){{(count($param["repoInfo"]["cardPaymentCommission"]["cardCancelCommission"]) > 1) ? '('. date('n月j日',strtotime($cancelCommission["apply_date"])) . '-)': ''}}</td>
          @if(number_format($cancelCommission["unit_price"]) != 0 && number_format($cancelCommission["unit_rate"]) != 0)
            <!-- rate + 単価 -->
            <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["unit_price"])}}/{{number_format($cancelCommission["unit_rate"])}}%</td>
          @elseif(number_format($cancelCommission["unit_price"]) != 0)
            <!-- 単価 -->
            <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["unit_price"])}}</td>
          @elseif(number_format($cancelCommission["unit_rate"]) != 0)
            <!-- rate -->
            <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["unit_rate"])}}%</td>
          @else
            <td class="td-onlybottom" style="text-align:right">0</td>       
          @endif
          <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["cancel_num"])}}</td>
          <td class="td-onlybottom" style="text-align:right">0</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["cancel_commission"])}}</td>
        </tr>
      @endforeach
    @endif
    <tr>
      <td></td>
      <td class="td-onlybottom" colspan="4">{{ trans('report.S_CreditAmountSum') }}【D】{{$sumDInfo}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumD)}}</td>
    </tr>
  @endif
  @if(number_format($param["repoInfo"]["SevenPickup"]["rate"]) != 0 || number_format($param["repoInfo"]["SevenPickup"]["amount"]) != 0)
    @foreach($param["repoInfo"]["storeCommission"]["ticketCommission"] as $index =>  $ticketCommission)
      <tr>
        @if($index==0 || $index==1)
          <td class="td-onlytop">{{ trans('report.S_SevenAmount') }}</td>
        @else
          <td></td>    
        @endif
        <td class="td-onlybottom">{{ trans('report.S_TicketingAmount') }} …(51){{(count($param["repoInfo"]["storeCommission"]["ticketCommission"]) > 1) ? '('. date('n月j日',strtotime($ticketCommission["apply_date"])) .'-)': ''}}</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($ticketCommission["unit_price"])}}</td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($ticketCommission["seats_num"])}}</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($ticketCommission["ticket_commission"])}}</td>
      </tr>
    @endforeach
    @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
      @foreach($param["repoInfo"]["storeCommission"]['rceiptCommission'] as $receiptCommission)
        <tr>
          <td></td>
          <td class="td-onlybottom">{{ trans('report.S_ReceiptAmount') }} …(53){{(count($param["repoInfo"]["storeCommission"]['rceiptCommission']) > 1) ? '('. date('n月j日',strtotime($receiptCommission["apply_date"])) . '-)': ''}}</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($receiptCommission["unit_price"])}}</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($receiptCommission["reserve_num"])}}</td>
          <td class="td-onlybottom" style="text-align:right">0</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($receiptCommission["receipt_commission"])}}</td>
        </tr>
      @endforeach
    @endif
    <tr>
      <td></td>
      @if(\App::getLocale() == "ja" ) <!-- modified by LS#1475 日本版不要項目 -->
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenAmountSum') }}【E】(51)</td>
      @else
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenAmountSum') }}【E】(51)+(53)</td>
      @endif
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumE)}}</td>
    </tr>
  @endif
  @if(number_format($param["repoInfo"]["RunningCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["RunningCommission"]["amount"]) != 0)
    <tr>
      <td class="td-onlytop">{{ trans('report.S_Running') }}</td>
      <td class="td-onlybottom">{{ trans('report.S_SystemFee') }} …(71)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      {{-- <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["cardSoldPrice"]["seats_num"] + $param["repoInfo"]["storeSoldPrice"]["seats_num"] - $param["repoInfo"]["cardCancel"]["cancel_sheets_num"])}}</td> --}}
      <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["runingCommissionNum"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($param["repoInfo"]["runingCommission"])}}</td>
    </tr>
    <tr>
      <td class="td-onlybottom"></td>
      <td class="td-onlybottom" colspan="4">{{ trans('report.S_RunningSum') }}【G】(71)</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumG)}}</td>
    </tr>  
  @endif
  <tr>
    <td class="td-rightleftnone" style="text-align:center" colspan="5">{{ trans('report.S_Total')  . sprintf('%s'.$sumItme.'%s', '【','】')}}</td>
    <td class="td-onlybottom" style="text-align:right">{{number_format($sumA + $sumB - $sumD - $sumE - $sumG)}}</td>
  </tr>  
</table>
<!--<table class="table-noborder" width="50%">
  <tr>
      <td width="20%">{{ trans('report.S_Bank') }}:</td>
      <td>{{$param['bank_inf']['bank_name']}}  {{$param['bank_inf']['branch_name']}} </td>
  </tr>
  <tr>
      <td>{{ trans('report.S_AccountNum') }}:</td>
      <td>{{$param['bank_inf']['account_num']}} </td>
  </tr>
  <tr>
      <td>{{ trans('report.S_AccountName') }}:</td>
      <td>{{$param['bank_inf']['account_name']}} </td>
  </tr>
</table>-->
<!------2021/05/31-STS-Task11: 45文字で折り返して表示する様にしてください。START----->
<?php
function wrapWord($string, $width = 45, $break = "<br\>") 
  {
    preg_match_all('/./u', $string, $chars);
    if (count($chars[0])<$width) return $string; 
    $output = '';
    $iter = 0;
    foreach ($chars[0] as $char) 
    {
      //echo $key.$break;
      if($iter == $width-1)
      {
        $output.=$break;
        $output.=$char;
        $iter = 1;
      }
      else 
      {
        $output.=$char;
        $iter+=1;
      }
    }
  return $output;
  }
  ?>
<!------------------------------2021/06/01 END----------------------------->
@foreach( $param["repoInfo"]["performance_detail"] as $index => $event)

  <div class="page"></div>
  <h1 style="text-align:center">Gettii Lite{{ trans('report.S_SystemReport') }}{{ trans('report.S_Detail') }}</h1>
  <p style="text-align:right">{{ trans('report.S_CreateDate') }}  {{ $param["createDate"] }} {{ $index+2 }}</p>
  <p style="text-align:left">{{ trans('report.S_Term') }}  {{ $param["startDate"] }}　-　{{ $param["endDate"] }}</p>
<!------2021/05/31-STS -Task11: 45文字で折り返して表示する様にしてください。START----->
<?php
$eventname = "【".$event["performance_code"]."：".$event["performance_name"]."】";
echo wrapWord($eventname). "<br\>";
?>
 {{-- 【{{$evsent["performance_code"]}}：{{$event["performance_name"]}}】 {{ trans('report.S_Hall') }}:{{ $event["hall_disp_name"]}} {{ trans('report.S_EventDate') }}:{{ $event["performance_st_dt"] . $event["day_st"]}}-　{{ $event["performance_end_dt"]. $event["day_end"] }} --}}
 {{ trans('report.S_Hall') }}:{{ $event["hall_disp_name"]}} {{ trans('report.S_EventDate') }}:{{ $event["performance_st_dt"] . $event["day_st"]}}-　{{ $event["performance_end_dt"]. $event["day_end"] }}
<!----------------------------2021/06/01 END---------------------------->
  <table  class="table-css" width="100%">
    <tr>
      <td class="td-rightleftnone"  width="25%"></td>
      <td class="td-rightleftnone" width="40%">{{ trans('report.S_Category') }}</td>
      <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_UnitPrice') }}</td>
      <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_Number') }}</td>
      <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_Page') }}</td>
      <td class="td-rightleftnone" style="text-align:right">{{ trans('report.S_Price') }}</td>
    </tr>
    <tr>
      <td>{{ trans('report.S_Credit') }}</td>
      <td class="td-onlybottom">{{ trans('report.S_TicketPrice') }} …(11)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardSoldPrice"]["reserve_num"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardSoldPrice"]["seats_num"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardSoldPrice"]["sale_price"])}}</td>
    </tr>
    @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
      <tr>
        <td></td>
        <td class="td-onlybottom">{{ trans('report.S_TicketingAmount') }} …(12)</td>
        <td class="td-onlybottom" style="text-align:right"></td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardSoldCommission"]["commission_ticket"])}}</td>
      </tr>
      <tr>
        <td></td>
        <td class="td-onlybottom">{{ trans('report.S_ServiceAmount') }} …(15)</td>
        <td class="td-onlybottom" style="text-align:right"></td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardSoldCommission"]["commission_sv"])}}</td>
      </tr>
    @endif
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_CancelPrice') }} …(18)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardCancel"]["cancel_num"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardCancel"]["cancel_sheets_num"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardCancel"]["refund_payment"])}}</td>
    </tr>
    <tr>
      <td></td>
      <?php $sumA = $event["detail"]["cardSoldPrice"]["sale_price"] + $event["detail"]["cardSoldCommission"]["commission_ticket"] + $event["detail"]["cardSoldCommission"]["commission_sv"] - $event["detail"]["cardCancel"]["refund_payment"];?>
      @if(\App::getLocale() == "ja" ) <!-- modified by LS#1475 日本版不要項目 -->
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_CreditSum') }} 【A】(11)-(18)</td>
      @else
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_CreditSum') }} 【A】(11)+(12)+(15)-(18)</td>      
      @endif
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumA)}}</td>
    </tr>
    <tr>
      <td class="td-onlytop">{{ trans('report.S_Seven') }}</td>
      <td class="td-onlybottom">{{ trans('report.S_TicketPrice') }} …(21)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["storeSoldPrice"]["reserve_num"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["storeSoldPrice"]["seats_num"])}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["storeSoldPrice"]["sale_price"])}}</td>
    </tr>
    @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_TicketingAmount') }} …(22)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["storeSoldCommission"]["commission_ticket"])}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_PaymentAmount') }} …(24)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["storeSoldCommission"]["commission_payment"])}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="td-onlybottom">{{ trans('report.S_ServiceAmount') }} …(25)</td>
      <td class="td-onlybottom" style="text-align:right"></td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">0</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["storeSoldCommission"]["commission_sv"])}}</td>
    </tr>
    @endif
    <tr>
      <td></td>
      <?php $sumB = $event["detail"]["storeSoldPrice"]["sale_price"]+$event["detail"]["storeSoldCommission"]["commission_ticket"]+$event["detail"]["storeSoldCommission"]["commission_payment"]+$event["detail"]["storeSoldCommission"]["commission_sv"];?>
      @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenSum') }} 【B】(21)+(22)+(24)+(25)</td>
      @else
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenSum') }} 【B】(21)</td>      
      @endif
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumB)}}</td>
    </tr>
    @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCommission"]["amount"]) != 0 ||
        number_format($param["repoInfo"]["CreditCancelCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCancelCommission"]["amount"]) != 0)  
      @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["CreditCommission"]["amount"]) != 0)
        <tr>
          <td class="td-onlytop">{{ trans('report.S_CreditAmount') }}</td>
          <td class="td-onlybottom">{{ trans('report.S_SettlementAmount') }} …(41)</td>
          <td class="td-onlybottom" style="text-align:right"></td>
          <td class="td-onlybottom" style="text-align:right">0</td>
          <td class="td-onlybottom" style="text-align:right">0</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardPaymentCommission"]["commission_card_payment"])}}</td>
        </tr>
      @endif
      <?php $sumD = $event["detail"]["cardPaymentCommission"]["commission_card_payment"];?>
      @if(number_format($cancelCommission["unit_price"]) != 0 || number_format($cancelCommission["unit_rate"]) != 0)
        @foreach( $event["detail"]["cardPaymentCommission"]["cardCancelCommission"] as $cancelCommission)
          <tr>
            @if(number_format($param["repoInfo"]["CreditCommission"]["rate"]) == 0 && number_format($param["repoInfo"]["CreditCommission"]["amount"]) == 0)
              <td class="td-onlytop">{{ trans('report.S_CreditAmount') }}</td>
            @else
              <td></td>
            @endif
            <td class="td-onlybottom">{{ trans('report.S_CancelAmount') }} …(43){{(count($event["detail"]["cardPaymentCommission"]["cardCancelCommission"]) > 1) ? '('. date('n月j日',strtotime($cancelCommission["apply_date"])) . '-)': ''}}</td>
            @if(number_format($cancelCommission["unit_price"]) != 0 && number_format($cancelCommission["unit_rate"]) != 0)
              <!-- rate + 単価 -->
              <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["unit_price"])}}/{{number_format($cancelCommission["unit_rate"])}}%</td>
            @elseif(number_format($cancelCommission["unit_price"]) != 0)
              <!-- 単価 -->
              <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["unit_price"])}}</td>
            @elseif(number_format($cancelCommission["unit_rate"]) != 0)
              <!-- rate -->
              <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["unit_rate"])}}%</td>
            @else
              <td class="td-onlybottom" style="text-align:right">0</td>
            @endif
            <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["cancel_num"])}}</td>
            <td class="td-onlybottom" style="text-align:right">0</td>
            <td class="td-onlybottom" style="text-align:right">{{number_format($cancelCommission["cancel_commission"])}}</td>
            <?php $sumD = $sumD + $cancelCommission["cancel_commission"];?>
          </tr>
        @endforeach
      @endif
      <tr>
        <td></td>
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_CreditAmountSum') }} 【D】{{$sumDInfo}}</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($sumD)}}</td>
      </tr>
    @endif
    @if(number_format($param["repoInfo"]["SevenPickup"]["rate"]) != 0 || number_format($param["repoInfo"]["SevenPickup"]["amount"]) != 0)
      <?php $sumE = 0;?>
      @foreach($event["detail"]["storeCommission"]["ticketCommission"] as $index => $ticketCommission)
        <tr>
          @if($index==0 || $index==1)
            <td class="td-onlytop">{{ trans('report.S_SevenAmount') }}</td>
          @else
            <td></td>    
          @endif
          <td class="td-onlybottom">{{ trans('report.S_TicketingAmount') }} …(51){{(count($event["detail"]["storeCommission"]["ticketCommission"]) > 1) ? '('. date('n月j日',strtotime($ticketCommission["apply_date"])) . '-)': ''}}</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($ticketCommission["unit_price"])}}</td>
          <td class="td-onlybottom" style="text-align:right">0</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($ticketCommission["seats_num"])}}</td>
          <td class="td-onlybottom" style="text-align:right">{{number_format($ticketCommission["ticket_commission"])}}</td>
          <?php $sumE = $sumE + $ticketCommission["ticket_commission"];?>
        </tr>
      @endforeach
      @if(\App::getLocale() == "zh_tw" ) <!-- modified by LS#1475 日本版不要項目 -->
        @foreach($event["detail"]["storeCommission"]['rceiptCommission'] as $receiptCommission)
          <tr>
            <td></td>
            <td class="td-onlybottom">{{ trans('report.S_ReceiptAmount') }} …(53){{(count($event["detail"]["storeCommission"]['rceiptCommission']) > 1) ? '('. date('n月j日',strtotime($receiptCommission["apply_date"])) . '-)': ''}}</td>
            <td class="td-onlybottom" style="text-align:right">{{number_format($receiptCommission["unit_price"])}}</td>
            <td class="td-onlybottom" style="text-align:right">{{number_format($receiptCommission["reserve_num"])}}</td>
            <td class="td-onlybottom" style="text-align:right">0</td>
            <td class="td-onlybottom" style="text-align:right">{{number_format($receiptCommission["receipt_commission"])}}</td>
            <?php $sumE = $sumE + $receiptCommission["receipt_commission"];?>
          </tr>
        @endforeach
      @endif
      <tr>
        <td></td>
        @if(\App::getLocale() == "ja" ) <!-- modified by LS#1475 日本版不要項目 -->
          <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenAmountSum') }} 【E】(51)</td>
        @else
          <td class="td-onlybottom" colspan="4">{{ trans('report.S_SevenAmountSum') }} 【E】(51)+(53)</td>      
        @endif
        <td class="td-onlybottom" style="text-align:right">{{number_format($sumE)}}</td>
      </tr>
    @endif
    @if(number_format($param["repoInfo"]["RunningCommission"]["rate"]) != 0 || number_format($param["repoInfo"]["RunningCommission"]["amount"]) != 0)
      <tr>
        <td class="td-onlytop">{{ trans('report.S_Running') }}</td>
        <td class="td-onlybottom">{{ trans('report.S_SystemFee') }} …(71)</td>
        <td class="td-onlybottom" style="text-align:right"></td>
        <td class="td-onlybottom" style="text-align:right">0</td>
        {{-- <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["cardSoldPrice"]["seats_num"] + $event["detail"]["storeSoldPrice"]["seats_num"] - $event["detail"]["cardCancel"]["cancel_sheets_num"])}}</td> --}}
        <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["runingCommissionNum"])}}</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($event["detail"]["runingCommission"])}}</td>
      </tr>
      <tr>
        <td class="td-onlybottom"></td>
        <?php $sumG = $event["detail"]["runingCommission"];?>
        <td class="td-onlybottom" colspan="4">{{ trans('report.S_RunningSum') }} 【G】(71)</td>
        <td class="td-onlybottom" style="text-align:right">{{number_format($sumG)}}</td>
      </tr>  
    @endif
    <tr>
      <td class="td-rightleftnone" style="text-align:center" colspan="5">{{ trans('report.S_Total') . sprintf('%s'.$sumItme.'%s', '【','】')}}</td>
      <td class="td-onlybottom" style="text-align:right">{{number_format($sumA + $sumB - $sumD - $sumE - $sumG)}}</td>
    </tr>  
  </table>
@endforeach
</body>
</html>