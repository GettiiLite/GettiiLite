@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
<h1>
    {{trans('userManage.S_Advanced')}}
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    <li><a href="/adminManage" onclick="">{{trans('userManage.S_UserTop')}}</a></li>
    <li><a href="/dataValidation/{{ $event['data']['GLID'] }}" onclick="">{{trans('userManage.S_UserDetail')}}</a></li>
    <li class="active">{{trans('userManage.S_Advanced')}}</li>
</ol>
<!-- /.網站導覽 -->

@stop


@section('content')
<!-- 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div class="funtion-btn-block">
    <button id='data-update' type="button" onclick="userInfManage.update()" class="btn waves-effect waves-light btn-rounded btn-inverse"> {{trans('userManage.S_UserUpdate')}}
    </button>
</div>
<!-- /.固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div id="user-detail">
    <form id="gssite_data_update" method="POST" style="visibilitsectionay: table;" action='/dataDetail/{{ $event["data"]["GLID"] }}'>
        <input type="hidden" name="json" v-model="json">
        {{ csrf_field() }}
    </form>
     <!--  box2 統一樣式 -->
     <div class="box box-solid">
         <!---box-header--->
        <div class="box-header with-border-non">
        <!-- 1214 新增title -->
          <div><h3 class="box-title">{{trans('userManage.S_urlSetting')}}</h3></div>
        <!-- /.1214 新增title -->
        </div>
        <!-- Block 1 -->
        <div class="box-body">
            <div class="form-horizontal">
                <!--1214 調整位置 - 0526-->
          <div class="col-md-12">
          <div class="form-group-flex pb-15">
            <div class="form-checkbox">
                 <label class="control control--checkbox ml-15x mr-15x">
                    <input type="checkbox" v-model="GettiisDispFlg">{{trans('userManage.S_useGETTIIS')}}
                    <div class="control__indicator"></div>
                </label>
            </div>
             <div class="form-checkbox">
                 <label class="control control--checkbox">
                 <input type="checkbox"  :disabled="SID > 1" v-model="GSSITE">{{trans('userManage.S_IndepentGETTIIS')}}
                 <div class="control__indicator"></div>
                 </label>
             </div>
         </div>
         </div>
         <!-- /.1214 調整位置 - 0526-->
                <!-- col -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">urlGs</label>
                        <div class="col-sm-10">
                            <input type="" class="form-control" id="" v-model="urlGs">
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <!-- col -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">urlApi</label>
                        <div class="col-sm-10">
                            <input type="" class="form-control" id="" v-model="urlApi">
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        </div>
    </div>
    <!-- /.box2 統一樣式-->
    <!-- 1214 新增その他設定 box2 統一樣式 -->
     <div class="box box-solid">
         <!---box-header--->
        <div class="box-header with-border-non">
        <!-- 1214 新增title -->
          <div><h3 class="box-title">{{trans('userManage.S_freeTixTitle')}}</h3></div>
        <!-- /.1214 新增title -->
        </div>
        <!-- Block 1 -->
        <div class="box-body">
            <div class="form-horizontal">
            <div class="col-md-12">
            <div class="form-group-flex">
             <!--1211 add-->
             <div class="form-checkbox">
                 <label class="control control--checkbox">
                  <input type="checkbox" v-model="freetix">{{trans('userManage.S_freeTix')}}
                 <div class="control__indicator"></div>
                 </label>
             </div>
             <!--1211 add-->
            </div>
            </div>
            </div>
        </div>
      </div>
     <!-- /. 1214 新增その他設定 box2 統一樣式-->
    <!-- 0701 新增 -->
    <div class="box no-border">
      <div class="box-header with-border-non"></div>
      <div class="box-body">
        <div class="row">
          @foreach($event["data"]['validCommission'] as $commission)
            <!-- rate-box- -->
            <div class="min-20 col-md-2 col-sm-6 col-xs-12">
              <div class="rate-box">
                <div class="rate-box-content">
                  @if($commission->commission_type == config('constant.client_commission.system'))
                    <span class="rate-box-text">{{trans('userManage.S_SystemRate')}}</span>
                  @elseif($commission->commission_type == config('constant.client_commission.card_payment'))
                    <span class="rate-box-text">{{trans('userManage.S_CreditRate')}}</span>
                  @elseif($commission->commission_type == config('constant.client_commission.cancel'))
                    <span class="rate-box-text">{{trans('userManage.S_CancelAmount')}}</span>
                  @elseif($commission->commission_type == config('constant.client_commission.seven_payment'))
                    <span class="rate-box-text">{{trans('userManage.S_SevenAmount')}}</span>
                  @elseif($commission->commission_type == config('constant.client_commission.seven_pickup'))
                    <span class="rate-box-text">{{trans('userManage.S_SevenIssueAmount')}}</span>
                  @endif
                  <div class="flex-end pt-6x">
                    <span class="rate-box-number">{{$commission->rate}}<small>%</small></span> 
                    <span class="mmlr-1">＋</span>
                    <span class="rate-box-number">{{$commission->amount}}<small>{{trans('userManage.S_AmoubtCurrency')}}</small></span>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.rate-box- -->
          @endforeach
        </div>
      </div>
    </div>
    <!-- /.0701 新增 -->

    <!-- 0701 新增 手續費設定-->
    <div class="box no-border">
      <div class="box-header with-border-non">
        <div><h3 class="box-title">{{trans('userManage.S_RateAmount')}}</h3></div>
      </div>
      <div class="box-body">
        <div class="">
          <div class="form-group">
            <div class="col-sm-3">
              <select name="addType" class="form-control" style="width: 100%;" @change="commissionChange($event)">
                <option value="-1">{{trans('userManage.S_SelectRateAmount')}}</option> 
                <option value="{{config('constant.client_commission.system')}}">{{trans('userManage.S_SystemRate')}}</option>
                <option value="{{config('constant.client_commission.card_payment')}}">{{trans('userManage.S_CreditRate')}}</option>
                <option value="{{config('constant.client_commission.cancel')}}">{{trans('userManage.S_CancelAmount')}}</option>
                @if (\App::getLocale() == "zh-tw")
                <option value="{{config('constant.client_commission.seven_payment')}}">{{trans('userManage.S_SevenAmount')}}</option>
                @endif
                <option value="{{config('constant.client_commission.seven_pickup')}}">{{trans('userManage.S_SevenIssueAmount')}}</option>
              </select>
            </div>
            <div class="col-sm-2">
              <div class="txt-flex-mr">
                <input type="text" name="rate" v-validate="'required|numeric|min_value:0|max_value:100'" min="0" id="rate" class="form-control text-right pr-40" v-model='rate'>
              <div class="input-unit"> % </div>
              </div>
              <span v-show="errors.has('rate')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('rate') }}</span>
            </div>
            <div class="col-sm-1 txt-flex-mr-center"> ✚ </div>
            <div class="col-sm-3">
              <div class="txt-flex-mr">
              <input type="text" min="0" id="amount" name="amount" v-validate="'required|numeric|min_value:0'" min="0" class="form-control text-right pr-40" v-model='amount'>
              <div class="input-unit"> {{trans('userManage.S_AmoubtCurrency')}} </div>
              </div>
              <span v-show="errors.has('amount')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('amount') }}</span>
            </div>
            <div class="col-sm-3">
              <div class="input-group">
                <input id="applyDate" name="applyDate"  class="form-control pull-right" style="background-color: white;" placeholder="{{trans('userManage.S_ApplyDate')}}" readonly>
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer text-right">
        <button type="submit" value="submit" class="btn waves-effect waves-light btn-angle btn-inverse" v-on:click="commissionAdd()">{{trans('userManage.S_Add')}}</button>
      </div>
    </div>
    <!-- /.0701 新增 手續費設定-->

    <!-- 0701 新增 手續費歷史紀錄 -->
    <div class="box box-solid">
      <div class="box-header with-border-non">
        <h3 class="box-title">{{trans('userManage.S_RateAmountHis')}}</h3>
        <div class="col-md-8 pull-right">
          <div class="form-group form-mb text-right col-md-3 col-xs-3">
            <div class="form-checkbox">
              <label class="control control--radio"><input type="radio" name="search" value="all" checked>全部
                <div class="control__indicator"></div>
              </label>
            </div>
          </div>
          <div class="form-group form-mb col-md-9 col-xs-9">
            <div class="form-checkbox form-group-flex-normal">
              <label class="control control--radio">
                <input type="radio" name="search" value="search">
                  <div class="control__indicator"></div>
              </label>
              <div class="col col-xs-3">
                <select name = 'type' class="form-control">
                  <option value="{{config('constant.client_commission.system')}}">{{trans('userManage.S_SystemRate')}}</option>
                  <option value="{{config('constant.client_commission.card_payment')}}">{{trans('userManage.S_CreditRate')}}</option>
                  <option value="{{config('constant.client_commission.cancel')}}">{{trans('userManage.S_CancelAmount')}}</option>
                  <option value="{{config('constant.client_commission.seven_payment')}}">{{trans('userManage.S_SevenAmount')}}</option>
                  <option value="{{config('constant.client_commission.seven_pickup')}}">{{trans('userManage.S_SevenIssueAmount')}}</option>
                </select>
              </div>
              <div><button type="button" class="btn btn-info" v-on:click="commissionHistorySearch()">{{trans('userManage.S_RateAmountSearch')}}</button></div>
            </div>
          </div>
        </div>
      </div>
    <div class="box-body">
    <div class="col-xs-12">
      <div class="table-responsive">
        <table id="" class="table table-striped">
          <thead>
            <tr>
              <th>{{trans('userManage.S_RateAmountType')}}</th>
              <th width="250" class="text-right">{{trans('userManage.S_Value')}}</th>
              <th width="50" ></th>
              <th>{{trans('userManage.S_ApplyDate')}}</th>
              <th>設定日</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <template v-for="(item, index) in commissionDataDisp">  
            <tr>
              <td v-if="(item.commission_type == '{{config('constant.client_commission.system')}}')">{{trans('userManage.S_SystemRate')}}</td>
              <td v-if="(item.commission_type == '{{config('constant.client_commission.card_payment')}}')">{{trans('userManage.S_CreditRate')}}</td>
              <td v-if="(item.commission_type == '{{config('constant.client_commission.cancel')}}')">{{trans('userManage.S_CancelAmount')}}</td>
              <td v-if="(item.commission_type == '{{config('constant.client_commission.seven_payment')}}')">{{trans('userManage.S_SevenAmount')}}</td>
              <td v-if="(item.commission_type == '{{config('constant.client_commission.seven_pickup')}}')">{{trans('userManage.S_SevenIssueAmount')}}</td>
              <td class="text-right">@{{ item.rate }} %  +  @{{ item.amount }} {{trans('userManage.S_AmoubtCurrency')}}</td>
              <td></td>
              <td>@{{ item.apply_date }}</td>
              <td>@{{ item.create_date }}</td>
              <td><button type="button" class="btn btn btn-danger-outline btn-mm"  v-on:click="commissionDel(item.commission_type,item.apply_date)">{{trans('userManage.S_Delete')}}</button></td>
            </tr>
         
          </template>
          </tbody>
        </table>
      </div>
    </div>
    <div class="m-b-20">
      <div class="col-xs-12">
        <nav aria-label="Page navigation" class="pull-right"></nav>
      </div>
    </div>
  </div>
</div>
    <!-- /.0701 新增 手續費歷史紀錄-->
</div>
@component('components/result')
        
@endcomponent
<script>
    $(function() {
      initDatePicker();
    });       
    
    function initDatePicker() {
      $.getScript("{{ asset('js/daterangepicker.js') }}", function(){
        $('input[name="applyDate"]').daterangepicker({
          "locale": {
            "format": "YYYY/MM/DD"
          },
          timePicker: false,
          singleDatePicker: true,
          autoUpdateInput: false,
        })
        
        $('input[name="applyDate"]').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('YYYY/MM/DD'))
        });
      });                    
    }
    
    Vue.config.devtools = true;
    var userInfManage = new Vue({
        el: '#user-detail',
        data: {
            json:'',
            GettiisDispFlg:'',
            GSSITE:false,
            freetix:false,
            SID:'',
            aid:'',
            xcdkey:'',
            urlGs:'',
            urlApi:'',
            rate:0,
            amount:0,
            disableUpdate:true,
            commissionData:         [], //料率/手数料履歴
            commissionDataDisp:     [], //料率/手数料履歴 表示用
        },
        watch: {
          errors:{
              handler(){
                  this.settingCheack()
              },
              deep: true
          },
        },
        methods: {
          /*
           * commission option 選擇，調整 rate
           * @param {object} event
           */
          commissionChange:function(event){
            let type = event.target.value;
            let rate = 0;

            switch(type){
              case '0':
                rate = 4;
                break;
              default:
                rate = 0;
            }

            this.rate = rate;
          },
            settingCheack:function () {
              if(this.errors.any()){
              　document.getElementById('data-update').disabled=true;　
                return true
              }else{
              　document.getElementById('data-update').disabled=false;　
                return false
              }
            },
            /**
            *
             */
            update: function(){
                let data = []
                let json = []
                                
                data.push({
                    'GLID'              : '{{ $event["data"]["GLID"] }}',
                    'GETTIIS_disp_flg'  : (this.GettiisDispFlg)?1:0,
                    'GSSITE'            : this.GSSITE,
                    'freetix'           : this.freetix,
                    'SID'               : this.SID,
                    'aid'               : this.aid,
                    'xcdkey'            : this.xcdkey,
                    'url_gs'            : this.urlGs,
                    'url_api'           : this.urlApi,
                    'commission_info'   : this.commissionData,
                })

                json.push({
                    data : data,
                })

                this.json = JSON.stringify(json)

                if(!this.settingCheack()){
                  this.$nextTick(() => {
                      document.getElementById("gssite_data_update").submit();
                  })
                }
            },
            //料率/手数料追加
            commissionAdd:function(){
                var type = document.getElementsByName("addType");
                type = type[0].options[type[0].selectedIndex].value;
                if(type == -1) return;
                
                var rate = document.getElementById("rate").value;
                var amount = document.getElementById("amount").value;
                var applyDate = document.getElementById("applyDate").value;
                
                if(rate == "" && amount == "") return;
                if(applyDate == "") return;
                
                if(rate != "")
                {
                  //rate validation
                  if(rate < 0)
                  {
                    alert("{{trans('userManage.S_RateAmountErr_1')}}");
                    return;                      
                  }
                  integer = Math.floor(rate);
                  if(String(integer).length > 2)
                  {
                     alert("{{trans('userManage.S_RateAmountErr_2')}}");
                    return;
                  }
                  decimal = parseFloat((String(rate)).split(".")[1]);
                  if(!isNaN(decimal) && String(decimal).length > 2)
                  {
                    alert("{{trans('userManage.S_RateAmountErr_3')}}");
                    return;
                  }
                }

                if(amount != "")
                {
                  //amount validation
                  if(amount < 0)
                  {
                    alert("{{trans('userManage.S_RateAmountErr_4')}}");
                    return;                      
                  }
                  integer = Math.floor(amount);
                  if(String(integer).length > 3)
                  {
                    alert("{{trans('userManage.S_RateAmountErr_5')}}");
                    return;
                  }
                  decimal = parseFloat((String(amount)).split(".")[1]);
                  if(!isNaN(decimal) && String(decimal).length > 0)
                  {
                    alert("{{trans('userManage.S_RateAmountErr_5')}}");
                    return;
                  }
                }
                
                //設定済チェック
                for (let i = 0; i < this.commissionData.length; i++)
                {
	          if(this.commissionData[i].commission_type == type && this.commissionData[i].apply_date == document.getElementById("applyDate").value)
                  {
                    if(this.commissionData[i].delete_flg == 1)
                    {
                      this.commissionData.splice(i,1);
                    }else{    
                      return;
                    }
                  }
	        }                  
                
                //新規追加
                this.commissionDataDisp.unshift({
                                                  'commission_type': type,
                                                  'apply_date': applyDate,
                                                  'rate': rate,
                                                  'amount': amount,
                                                  'create_date' : '',
                                                  'delete_flg': 0,
                                                  });
                this.commissionData.unshift({
                                              'commission_type': type,
                                              'apply_date': applyDate,
                                              'rate': rate,
                                              'amount': amount,
                                              'create_date' : '',
                                              'delete_flg': 0,
                                           });                                                  
            },
            //料率/手数料削除
            commissionDel:function(commission_type,apply_date){

              //更新用
              for (let i = 0; i < this.commissionData.length; i++)
              {
	        if(this.commissionData[i].commission_type == commission_type && this.commissionData[i].apply_date == apply_date)
                {
                  this.commissionData[i].delete_flg = 1;
                }
	      }   
              //表示用
              for (let i = 0; i < this.commissionDataDisp.length; i++)
              {
	        if(this.commissionDataDisp[i].commission_type == commission_type && this.commissionDataDisp[i].apply_date == apply_date)
                {
                  this.commissionDataDisp.splice(i,1);
                }
	      }   
            },
            //料率/手数料履歴検索
            commissionHistorySearch:function(){
              this.commissionDataDisp = [];
              var radio = document.getElementsByName( "search" ) ;
              let condition;
              for (let i = 0; i < radio.length; i++)
              {
		if(radio[i].checked)
                { 
		  condition = radio[i].value;
		  break;
		}
	      }
              if(condition == 'all')
              {
                //全部
                for (let i = 0; i < this.commissionData.length; i++)
                {
		  if(this.commissionData[i].delete_flg != 1)
                  {
                     this.commissionDataDisp.push(this.commissionData[i]);   
                  }
	        }    
              }else if(condition == 'search'){
                //特定の料率/手数料
                var type = document.getElementsByName( "type" );
                type= type[0].options[type[0].selectedIndex].value;
                
                for (let i = 0; i < this.commissionData.length; i++)
                {
		  if(this.commissionData[i].commission_type == type && this.commissionData[i].delete_flg != 1)
                  {
                     this.commissionDataDisp.push(this.commissionData[i]);   
                  }
	        }               
              }
            },
        },
        mounted(){
            let gssiteData = JSON.parse('{!! addslashes($event["data"]["gssite_data"]) !!}')
      
            this.GettiisDispFlg = gssiteData.GETTIIS_disp_flg
            this.SID            = gssiteData.SID
            this.aid            = gssiteData.aid
            this.xcdkey         = gssiteData.xcdkey
            this.urlGs          = gssiteData.url_gs
            this.urlApi         = gssiteData.url_api
            
            if(this.SID > 1){
                this.GSSITE = true
            }else{
                this.GSSITE = false
            }

            let freetix = '{{$event["data"]["freeTix"]}}'
            if(freetix == 1){
                this.freetix = true
            }else{
                this.freetix = false
            }

            @if($event["status"]["status"] == 'update')
                let errorJson = '{!! addslashes($event["data"]["gssite_update"]) !!}'
                console.log(errorJson);
                popUpResult.open(errorJson)
            @endif

            //料率/手数料履歴
            this.commissionData = JSON.parse('{!! addslashes($event["data"]["commissionHistory"]) !!}')
            //料率/手数料履歴 表示用
            this.commissionDataDisp = JSON.parse('{!! addslashes($event["data"]["commissionHistory"]) !!}')
        }
    })    
</script>
@stop
