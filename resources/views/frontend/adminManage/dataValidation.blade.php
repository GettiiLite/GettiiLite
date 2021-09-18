@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
<h1>
    {{trans('userManage.S_UserDetail')}}
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    <li><a href="/adminManage" onclick="loading.openLoading()">{{trans('userManage.S_UserTop')}}</a></li>
    <li class="active">{{trans('userManage.S_UserDetail')}}</li>
</ol>
<!-- /.網站導覽 -->

@stop

@section('content')
<!-- 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
@if ($data['data']['user_data']['user_status'] != -2)
<div class="funtion-btn-block">
    <a href="/dataDetail/{{ $data['status']['GLID'] }}" class="btn waves-effect waves-light btn-rounded btn-normal-outline m-r-10" onclick="loading.openLoading()">{{trans('userManage.S_Advanced')}}</a>
    <button id='update-button' type="button" onclick="userInfManage.infApply()" class="btn waves-effect waves-light btn-rounded btn-normal"> {{trans('userManage.S_UserUpdate')}}
    </button>
</div>
@endif
<!-- /.固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div id="userInfManage">
    <!-- json -->
    <form id="userInf" method="POST" action="/dataValidation/upload">
        <input type="hidden" name="GLID" value="{{ $data['status']['GLID'] }}">
        <input type="hidden" name="user_id" value="{{ $data['data']['user_data']['user_id'] }}">
        <input type="hidden" name="reviewStatus" v-model="reviewStatus">
        <input type="hidden" name="reviewStatusOld" value="{!! $data["data"]["user_data"]["user_status"] !!}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="json" v-model="json">
    </form>
    <form id="AccountInf" method="POST" action="/dataValidation/accountInf/upload">
        <input type="hidden" name="GLID" value="{{ $data['status']['GLID'] }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="json" v-model="accountJson">
    </form>
    <!--  box2 統一樣式 -->
    <div class="box no-border">
        <!-- Block 1 -->
        <div class="box-body">
            <div class="form-horizontal">
                <!-- col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('userManage.S_UserCode')}}</label>
                        <div class="col-sm-9">
                            <input type="" class="form-control" id="" value="{{ $data['data']['user_data']['user_code'] }}" disabled>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <!-- col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('userManage.S_UserId')}}</label>
                        <div class="col-sm-9">
                            <input type="" class="form-control" id="" value="{{ $data['data']['user_data']['user_id'] }}" disabled>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <!-- col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('userManage.S_ReviewStatus')}}</label>
                        <div class="col-sm-9">
                            <div class="form-text-500 text-red">@{{ reviewStatusText }}</div>
                        </div>
                        @if ($data['data']['user_data']['user_status'] != -2)
                        <div class="col-sm-offset-3 col-sm-9">
                            <select class="form-control select2" style="width: 100%;" v-model="reviewStatus" >
                                <option value="0" v-show="statucControl[0].unReview">{{trans('userManage.S_StatusUnreview')}}</option>
                                <option value="1" v-show="statucControl[0].reviewing">{{trans('userManage.S_StatusReviewing')}}</option>
                                <option value="9" v-show="statucControl[0].reviewOk">{{trans('userManage.S_StatusReviewOK')}}</option>
                                <option value="-1" v-show="statucControl[0].stop">{{trans('userManage.S_StatusStop')}}</option>
                                <option value="8" v-show="statucControl[0].leave">{{trans('userManage.S_StatusLeaving')}}</option>
                                <option value="-2" v-show="statucControl[0].leave">{{trans('userManage.S_StatusLeaved')}}</option>
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- /.col -->
                <!-- col -->
{{-- 
                <div class="col-md-6">
                    <div class="form-group form-m0">
                        <label class="col-sm-3 control-label">{{trans('userManage.S_Commission')}}</label>
                        <div class="col-sm-9">
                            <div class="form-text-500 text-red">
                                {!! addslashes($data["data"]["user_data"]["comission_percent"]) !!} % 
                                ＋ 
                                {!! addslashes($data["data"]["user_data"]["comission_fee"]) !!} {{trans('userManage.S_Unit')}}
                            </div>
                        </div>
                        <div class="col-sm-offset-3 col-sm-3 txt-flex-mr">
                            <input type="" class="form-control" id="" v-model="comissionPercent">
                            <!-- 0527 調整 -->
                            <div class="input-unit">%</div>
                        </div>
                        <div class="col-sm-1 txt-flex-mr"> ✚ </div>
                        <div class="col-sm-5 txt-flex-mr">
                            <input type="" class="form-control" id="" v-model="comissionFee">
                            <div class="input-unit">{{trans('userManage.S_Unit')}}</div>
                            <!-- /.0527 調整 -->
                        </div>
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="form-text-200"> {{trans('userManage.S_Notice')}}</div>
                        </div>
                    </div>
                </div>
                 --}}
                <!-- /.col -->
            </div>
        </div>
    </div>
    <!-- /.box2 統一樣式-->
    <!-- box1 統一樣式兩欄 + 圖片區 -->
    <div class="box no-border">
        <div class="box-header with-border-non">
            <h3 class="box-title"> {{trans('userManage.S_WebDisplay')}}</h3>        
        </div>
        <div class="box-body box-body-bottom">
            <div class="row">
                <div class="col-md-3">
                    {{-- STS 2021/07/17 Task 26 --}}
                        <h5 class="subtitle pt-0 mt-0">{{ trans('userManage.S_GETTIISLogoDisplayStatus_Description') }}</h5>
                        <div class="drop-image">
                        <input type="file" id="" class="dropify" v-bind:data-default-file="pathLogo" disabled="disabled"/>
                        </div>
                        {{-- STS 2021/07/17 Task 26 Start --}}
                        <div class="form-checkbox subtitle">
                            <label class="control control--checkbox ">
                                <input type="checkbox" v-model="GETTIIS_logo_disp_flg" true-value='1'
                                    false-value='0'>{{ trans('userManage.S_GETTIISLogoDisplayStatus') }}

                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        {{-- STS 2021/07/17 Task 26 End --}}
                </div>
                <div class="form-horizontal col-md-9">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">{{trans('userManage.S_Distributor')}}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                        <div class="col-sm-9">
                            <div class="form-text-500">{{ $data['data']['user_data']['disp_name'] }}</div>
                        </div>
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="" class="form-control" id=""name="sell-title" maxlength="80" v-validate="'required|max:80'" v-model="sellTittle">
                            <span  dusk="sellTittle" v-show="errors.has('sell-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('sell-title') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="form-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" :checked="{{ $data['data']['user_data']['disp_flg'] }}" disabled>{{trans('userManage.S_GETTIIS')}}
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            
                            <div class="form-checkbox">
                                <label class="control control--checkbox">
                                    <input type="checkbox" v-model="dispFlg">{{trans('userManage.S_GETTIIS')}}
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">{{trans('userManage.S_DistributorURL')}}</label>
                        <div class="col-sm-9">
                            <div class="form-text-500">{{ $data['data']['user_data']['home_page'] }}</div>
                        </div>
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="" class="form-control" id="" v-validate="'url:require_protocol|max:200'" maxlength="100" name="sell-url" v-model="sellUrl">
                            <span dusk="sellUrl" v-show="errors.has('sell-url')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('sell-url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body-footer 位置 -->
    </div>
    <!-- /. box1 統一樣式兩欄 + 圖片區  -->
    <!--  box2 統一樣式-->
    <div class="box no-border">
        @if($data['data']['user_data']['user_kbn'] === 1)
            <!-- Block 1 -->
            <div class="box-header with-border-non">
                <h3 class="box-title">{{trans('userManage.S_CompanyInfo')}}</h3>
            </div>
            <div class="box-body box-body-bottom">
                <!-- form  -->
                <div class="row form-horizontal">
                    <div class="col-md-12 city-selector-set">
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{trans('userManage.S_CompanyName')}}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-md-10">
                                <div class="form-text-500">{{ $data['data']['user_data']['contract_name'] }}</div>
                            </div>
                            <div class="col-md-offset-2 col-md-10">
                                <input type=""  id="companyTitle"  class="form-control" name="company-title" placeholder="" maxlength="80" v-model="companyName"  v-validate="'required|max:80'">
                                <span  dusk="companyTitle" v-show="errors.has('company-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-title') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <!-- 0714 新增 -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ trans('userManage.S_CompanyTitleKana') }}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-md-10">
                                <div class="form-text-500">{{ $data['data']['user_data']['contract_name_kana'] }}<!-- 法人名カナ --></div>
                            </div>
                            <div class="col-md-offset-2 col-md-10">
                                <input type="" id="companyTitleKana" class="form-control"  name="company-title-kana" maxlength="160" v-model="companyNameKana" v-validate="'required|max:160'">
                                <span  dusk="companyTitleKana" v-show="errors.has('company-title-kana')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-title-kana') }}</span>
                            </div>
                        </div>
                        <!-- /. 0714 新增 -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                {{trans('userManage.S_Address')}}
                            </label>
                            <div class="col-sm-10">
                                <div class="form-text-500">{{ $data['data']['user_data']['location'] }}</div>
                            </div>
                            <div class="col-sm-offset-2 col-md-5">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <select class="form-control select2 county" style="width: 100%;" v-model="prefecture">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <select class="form-control select2 district" style="width: 100%;" v-model="city">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group">
                            
                            <div class="col-md-2 col-md-offset-2">
                                <input type="" id="postCode" class="form-control postCode" v-show='false'>
                                <div class="form-text-500">〒 {{ $data['data']['user_data']['post_display'] }}</div>
                                <div class="txt-flex-mr">
                                    <div class="input-unit-left"> 〒 </div>
                                    <input type="" class="form-control pl-40" v-model="postDisplay">
                                </div>
                                <span v-show="rulesCheck['postDisplay']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ rulesCheck['postDisplay']['msn'] }}</span>
                            </div>
                            <div class="col-md-8">
                                <div class="form-text-500">{{ $data['data']['user_data']['address'] }}</div>
                                <input type="" class="form-control" id="" placeholder="" maxlength="100" name='place-detailed' v-validate="'max:100'" v-model="placeDetailed">
                                <span  v-show="errors.has('place-detailed')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('place-detailed') }}</span>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                <!-- /.form  -->
                </div>
                <!-- body-footer 位置 -->
            </div>
            <!-- /.Block 1-->
        @elseif ($data['data']['user_data']['user_kbn'] === 0)
            <!-- Block 1 -->
            <div class="box-header with-border-non">
                <h3 class="box-title">{{trans('userManage.S_PersonalInfo')}}</h3>
            </div>
            <div class="box-body box-body-bottom">
                <!-- form  -->
                <div class="form-horizontal">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{trans('userManage.S_PersonalName')}}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-md-10">
                                <div class="form-text-500">{{ $data['data']['user_data']['contract_name'] }}</div>
                            </div>
                            <div class="col-md-offset-2 col-md-10 ">
                                <input type="" id="personalTitle" class="form-control" name="personal-title" placeholder="" maxlength="40" v-model="personalName"  v-validate="'required|max:40'">
                                <span v-show="errors.has('personal-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('personal-title') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- body-footer 位置 -->
            </div>
            <!-- /.Block 1-->
        @else

        @endif
            <!-- Block 2 -->
            <div class="box-header with-border-non">
                <h3 class="box-title">{{trans('userManage.S_ContactInfo')}}</h3>
            </div>
            <div class="box-body box-body-bottom">
                <!-- form  -->
                <div class="form-horizontal">
                @if($data['data']['user_data']['user_kbn'] === 1)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{trans('userManage.S_Department')}}</label>
                            <div class="col-md-8">
                                <div class="form-text-500">{{ $data['data']['user_data']['department'] }}</div>
                            </div>
                            <div class="col-md-offset-4 col-md-8">
                                <input type="" class="form-control" name='contact-deparment' maxlength="80" v-validate="'max:80'" v-model="contactDeparment">
                                <span v-show="errors.has('contact-deparment')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-deparment') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{trans('userManage.S_PersonInCharge')}}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                                <div class="col-md-8">
                                    <div class="form-text-500">{{ $data['data']['user_data']['contact_person'] }}</div>
                                </div>
                            <div class="col-md-offset-4 col-md-8">
                                <input type="" class="form-control" id="" name="contact-person" maxlength="80" v-validate="'required|max:80'" v-model="contactName">
                                <span  dusk="contactPerson" v-show="errors.has('contact-person')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-person') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{trans('userManage.S_ContactTel')}}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-sm-8">
                                <div class="form-text-500">{{ $data['data']['user_data']['tel_num'] }}</div>
                            </div>
                            <div class="col-md-offset-4 col-md-8">
                                <input type="" class="form-control" v-validate="'required|tel_format|max:20'" maxlength="20"  name="contact-tel" v-model="tel">
                                <span dusk="contactTel" v-show="errors.has('contact-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-tel') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-md-8">
                                <div class="form-text-500">{{ $data['data']['user_data']['mail_address'] }}</div>
                            </div>
                            <div class="col-md-offset-4 col-md-8">
                                <input type="" class="form-control" v-validate="'required|email|max:200'" maxlength="200"  name="contact-mail" v-model="contactMail">
                                <span dusk="contactMail" v-show="errors.has('contact-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-mail') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                @elseif ($data['data']['user_data']['user_kbn'] === 0)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{trans('userManage.S_TEL')}}<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-md-8">
                            <div class="form-text-500">{{$data['data']['user_data']['tel_num']}}</div>
                            </div>
                            <div class="col-md-offset-4 col-md-8">
                                <input type="" class="form-control" v-validate="'required|tel_format|max:20'" maxlength="20"  name="contact-tel" v-model="personalTel">
                                <span dusk="contactTel" v-show="errors.has('contact-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-tel') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email<b>{{trans('userManage.S_RequiredMark')}}</b></label>
                            <div class="col-md-8">
                            <div class="form-text-500">{{$data['data']['user_data']['mail_address']}}</div>
                            </div>
                            <div class="col-md-offset-4 col-md-8">
                            <input type="" class="form-control" v-validate="'required|email|max:200'" maxlength="200"  name="contact-mail" v-model="personalMail">
                            <span dusk="contactMail" v-show="errors.has('contact-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-mail') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                @else
                @endif
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{trans('userManage.S_VerifyPICTitle')}}</label>
                        <div class="col-md-10">
                            <h5 class="subtitle">{{trans('userManage.S_IdentificationEx')}}</h5>
                            <div class="col-md-4">
                                <!-- drop1 -->
                                <div class="funtion-upload">
                                    <!-------------->
                                    <div class="user-documents">
                                        <img src="{!! $data['data']['user_data']['pathImage01'] !!}" onclick="onClick(this)" class="modal-hover-opacity">
                                    </div>
                                    <!-------------->
                                </div>
                                <!-- /.drop1 -->
                            </div>
                            <div class="col-md-4">
                                <!-- drop2 -->
                                <div class="funtion-upload">
                                    <!-------------->
                                    <div class="user-documents">
                                        <img src="{!! $data['data']['user_data']['pathImage02'] !!}" onclick="onClick(this)" class="modal-hover-opacity">
                                    </div>
                                    <!-------------->
                                </div>
                                <!-- /.drop2 -->
                            </div>
                            <div class="col-md-4">
                                <!-- drop3 -->
                                <div class="funtion-upload">
                                    <!-------------->
                                    <div class="user-documents">
                                        <img src="{!! $data['data']['user_data']['pathImage03'] !!}" onclick="onClick(this)" class="modal-hover-opacity">
                                    </div>
                                    <!-------------->
                                </div>
                                <!-- /.drop3 -->
                            </div>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
        <!-- /.form  -->
        </div>
        <!-- body-footer 位置 -->
        <!-- /.Block 2 -->
        <!-- Block 3 -->
        <div class="box-header with-border-non">
            <h3 class="box-title">{{trans('userManage.S_BankInf_2')}}</h3>
        </div>
        <div class="box-body box-body-bottom">
        <!-- form  -->
            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{trans('userManage.S_BankName')}}</label>
                        <div class="col-md-8">
                            <div class="form-text-500">{{ $data['data']['user_data']['bank_name'] }}</div>
                        </div>
                        <div class="col-md-offset-4 col-md-8">
                            <input type="" class="form-control" v-model="bankName" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{trans('userManage.S_BankAccountNum')}}</label>
                        <div class="col-md-8">
                            <div class="form-text-500">{{ ($data['data']['user_data']['account_kbn'] == 2)? trans('userManage.S_BankAccountType02'):trans('userManage.S_BankAccountType01')}}-{{ $data['data']['user_data']['account_num'] }}</div>
                        </div>
                        <div class="col-md-offset-4 col-md-3">
                        <select class="form-control select2" style="width: 100%;" disabled>
                            <option selected="selected">@{{ (bankType == "spec")? "{!!trans('userManage.S_BankAccountType02')!!}":"{!!trans('userManage.S_BankAccountType01')!!}" }}</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="" class="form-control" id="" v-model="bankAccount" disabled>
                    </div>
                </div>
            <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-4 control-label">{{trans('userManage.S_BankLocation')}}</label>
                        <div class="col-md-8">
                            <div class="form-text-500">{{ $data['data']['user_data']['branch_name'] }}</div>
                        </div>
                        <div class="col-md-offset-4 col-md-8">
                            <input type="" class="form-control" id="" v-model="branch" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{trans('userManage.S_BankLocationKana')}}</label>
                        <div class="col-md-8">
                            <div class="form-text-500">{{ $data['data']['user_data']['account_name'] }}</div>
                        </div>
                        <div class="col-md-offset-4 col-md-8">
                            <input type="" class="form-control" id="" v-model="bankAccountKana" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.form  -->
        </div>
        <!-- body-footer 位置 -->
        <!-- /.Block 3 -->
    </div>
    <!-- /.box2 統一樣式-->

    <!-- 1202 推薦代碼新增 -->
    <div class="box no-border">
        <div class="box-header with-border-non">
           <h3 class="box-title">{{trans('registered.S_Introduction')}}</h3>
        </div> 
    <div class="box-body">
        <div class="form-horizontal">
            <div class="col-md-12">
                <div class="form-group">
                 <label class="col-md-2 control-label"></label> 
                    <div class="col-md-10">
                      <input type="" class="form-control" id="introduction" v-model="introduction" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--/.1202 推薦代碼新增-->

    <!--  box2 統一樣式 -->
    <div class="box no-border">
        <!-- Block 1 -->
        <div class="box-header with-border-non">
            <h3 class="box-title">{{trans('userManage.S_UserOpenInf_2')}}</h3>
            <small class="subtitle"> * {{trans('userManage.S_UserOpenInfNote_2')}}</small>
        </div>
        <div class="box-body">
            <div class="form-group">
                <textarea class="form-control" rows="5" v-model="openInf" disabled></textarea>
            </div>
        </div>
    </div>
    <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
    <div class="box no-border">
        <div class="box-header with-border-non">
            <h3 class="box-title">{{trans('userManage.S_Permission')}}</h3>
        </div>
        <div class="box-body">
            <!-- TABLE8 一般樣式 ＋ 雙層表頭 ＋ 按鈕 -->
            <table id="" class="table table-striped table-row">
                <thead>
                <tr>
                    <th rowspan="2">{{ trans('userManage.S_UserName') }}</th>
                    <th rowspan="2">{{ trans('userManage.S_UserMail') }}</th>
                    <th rowspan="2">{{ trans('userManage.S_UserStatus') }}</th>
                    <th colspan="{{(session('member_info_flg') > 0)? 5 : 4 }}" class="text-center">{{ trans('userManage.S_UserPermission') }}</th>
                    <th rowspan="2">{{ trans('userManage.S_UserDeadline') }}</th>
                    <th rowspan="2">{{ trans('userManage.S_UserNote') }}</th>
                    <th rowspan="2">{{ trans('userManage.S_UserOperate') }}</th>
                </tr>
                <tr>
                    <th>{{ trans('userManage.S_Func_PF') }}</th>
                    <th>{{ trans('userManage.S_Func_EM') }}</th>
                    <th>{{ trans('userManage.S_Func_SM') }}</th>
                    <th>{{ trans('userManage.S_Func_PI') }}</th>
                    @if(session('member_info_flg') > 0)
                    <th>{{ trans('userManage.S_Func_MM') }}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    <template v-for="(user, key)  in accountData">
                        <tr>
                            <td>@{{ user.name }}</td>
                            <td>@{{ user.mail }} </td>
                            <td>
                                <template v-if="user.userStatus === 1">
                                    {{ trans('userManage.S_UserStatusValid') }}
                                </template>
                                <template v-else-if="user.userStatus === -1">
                                    {{ trans('userManage.S_UserStatusDeleted') }}
                                </template>
                                <template v-else>
                                    {{ trans('userManage.S_UserStatusInvalid') }}
                                </template>
                            </td>
                            <td>
                                <template v-if="user.profile_info_flg === 0">                                   
                                    <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>                                   
                                </template>
                                <template v-else-if="user.profile_info_flg === 1">
                                    <b class="text-orange">{!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                                </template>
                                <template v-else-if="user.profile_info_flg === 2">
                                   <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                                </template>
                                <template v-else>
                                    {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                                </template>
                            </td>
                            <td>
                                <template v-if="user.event_info_flg === 0">
                                    <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                                </template>
                                <template v-else-if="user.event_info_flg === 1">
                                    <b class="text-orange">{!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                                </template>
                                <template v-else-if="user.event_info_flg === 2">
                                    <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                                </template>
                                <template v-else>
                                    {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                                </template>
                            </td>
                            <td>
                                <template v-if="user.sales_info_flg === 0">
                                    <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                                </template>
                                <template v-else-if="user.sales_info_flg === 1">
                                    <b class="text-orange">{!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                                </template>
                                <template v-else-if="user.sales_info_flg === 2">
                                    <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                                </template>
                                <template v-else>
                                    {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                                </template>
                            </td>
                            <td>
                                <template v-if="user.personal_info_flg === 0">
                                    <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                                </template>
                                <template v-else-if="user.personal_info_flg === 1">
                                    <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                                </template>
                                <template v-else>
                                    {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                                </template> 
                            </td>
                            @if(session('member_info_flg') > 0)
                            <td>
                                <template v-if="user.member_info_flg === 0">
                                    <b class="text-red"> {!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                                </template>
                                <template v-else-if="user.member_info_flg === 1">
                                    <b class="text-orange">  {!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                                </template>
                                <template v-else-if="user.member_info_flg === 2">
                                    <b class="text-green"> {!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                                </template>
                                <template v-else>
                                    {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                                </template>
                            </td>
                            @endif
                            <td>@{{ user.deadlineDate }}</td>
                            <td width="250" class="wbreak">@{{ user.note }}</td>
                            <td width="185">
                                @if(session('profile_info_flg') == 2 && $data['data']['user_data']['user_status'] != -2)
                                    <template v-if="user.id === 0 && user.mail">
                                        <a :id="'changeUserData'+key" class="btn btn-info btn-sm" v-on:click="changeInf(key)">
                                            {{ trans('userManage.S_ChangeInfBtn') }}
                                        </a>
                                        <a :id="'changeUserPassword'+key" class="btn btn-danger btn-sm" v-on:click="openPasswordDialog(user.id)">
                                            {{ trans('userManage.S_ChangePassWorld') }}
                                        </a>
                                    </template>
                                    <template v-else-if="user.mail && user.userStatus === 1">
                                        <a :id="'changeUserPassword'+key" class="btn btn-danger btn-sm" v-on:click="openPasswordDialog(user.id)">
                                            {{ trans('userManage.S_ChangePassWorld') }}
                                        </a>
                                    </template>
                                    <template v-else>

                                    </template> 
                                @endif
                            </td>
                        </tr>
                    </template>                 
                </tbody>
                <!-- 表尾說明 -->
                <tfoot class="tfoot-light">
                <tr>
                    <td colspan="11" class="text-left">
                    {{ trans('userManage.S_SymbolExplainTitle') }}：
                    <ul>
                        <li> PF：{{ trans('userManage.S_FuncExplain_PF') }}｜EM：{{ trans('userManage.S_FuncExplain_EM') }}｜SM：{{ trans('userManage.S_FuncExplain_SM') }}｜PI：{{ trans('userManage.S_FuncExplain_PI') }}
                            @if(session('member_info_flg') > 0)
                            ｜MM：{{ trans('userManage.S_FuncExplain_MM') }}
                            @endif
                        <li> 
                          <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b> : {{ trans('userManage.S_PermissioSymbolExplain_Full') }} | 
                          <b class="text-orange">{!! trans('userManage.S_PermissioSymbol_Partial') !!}</b> : {{ trans('userManage.S_PermissioSymbolExplain_Partial') }} | 
                          <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b> : {{ trans('userManage.S_PermissioSymbolExplain_Disable') }}
                        </li>
                    </ul>
                    </td>
                </tr>
                </tfoot>
                <!-- /.表尾說明 -->
            </table>
            <!-- /.TABLE1 一般樣式 ＋ 合計 ＋ 按鈕 -->
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕 -->
    <div class="modal-mask" v-show="showModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('userManage.S_DialogUserSettingTitle') }}</h4>
                </div>
                <div class="modal-body">
                    <!--//提示訊息 -->
                    <div class="row" v-show="errorMsn">
                        <div class="col-md-12 col-md-offset-2">
                            <div class="col-md-9 callout callout-tip-warning ">
                                <!-- -->
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                @foreach ($errors->all() as $error)
                                    <p class="">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!--/.提示訊息 -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <!-- form-group -->    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUsername') }}</label>
                                    <div class="col-md-8">
                                        <input dusk="userName" type="text" class="form-control" name="user-Name" v-model="userName" maxlength="20" v-validate="'required|alpha_num|min:4|max:20'">
                                        <span dusk="userNameWarn" v-show="errors.has('user-Name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-Name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <!-- form-group -->                            
                            <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUserStatus') }}</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2" style="width: 100%;" v-model="userStatus">
                                            <option value="1">{{ trans('userManage.S_UserStatusValid') }}</option>
                                            <option value="0">{{ trans('userManage.S_UserStatusInvalid') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form-group --> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUserMail') }}</label>
                                    <div class="col-md-8">
                                        <input dusk="dialog-mail" name="user-mail" type="email" class="form-control" v-model="userMail" maxlength="200" v-validate="'email|max:200'">
                                        <span v-show="errors.has('user-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-mail') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- /.form-group -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUserExpiry') }}</label>
                                    <div class="col-md-8 form-group-flex-normal">
                                    <div class="form-checkbox">
                                        <label class="control control--radio">
                                            <input type="radio" name="deadline" value="had" v-model="permissionDeadline">{{ trans('userManage.S_DialogUserNoExpiry') }}
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="form-checkbox">
                                        <label class="control control--radio">
                                            <input type="radio" name="deadline" value="date" v-model="permissionDeadline">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="input-group w-60">
                                        <input id="deadlineDate" type="text" class="form-control pull-right daterangeSingle" v-model="deadlineDate">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="col-md-12">
                            <!-- 0527 調整 -->
                            <h5 class="st-line">{{ trans('userManage.S_UserPermission') }}<span></span></h5>
                            <div class="form-group">
                               <!-- <label class="col-md-2 control-label">{{ trans('userManage.S_UserPermission') }}</label>-->
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_PF') }}</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2" v-model="profileInfo">
                                                <option value="0">{{ trans('userManage.S_PermissioSymbolExplain_Disable') }}</option>
                                                    <option value="1">{{ trans('userManage.S_PermissioSymbolExplain_Partial') }}</option>
                                                    <option value="2">{{ trans('userManage.S_PermissioSymbolExplain_Full') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_EM') }}</label>
                                            <div class=" col-md-8">
                                                <select class="form-control select2"  v-model="eventInfo">
                                                    <option value="0">{{ trans('userManage.S_PermissioSymbolExplain_Disable') }}</option>
                                                    <option value="1">{{ trans('userManage.S_PermissioSymbolExplain_Partial') }}</option>
                                                    <option value="2">{{ trans('userManage.S_PermissioSymbolExplain_Full') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_SM') }}</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2" v-model="salesInfo">
                                                    <option value="0">{{ trans('userManage.S_PermissioSymbolExplain_Disable') }}</option>
                                                    <option value="1">{{ trans('userManage.S_PermissioSymbolExplain_Partial') }}</option>
                                                    <option value="2">{{ trans('userManage.S_PermissioSymbolExplain_Full') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.-->
                                    @if(session('member_info_flg') > 0)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_MM') }}</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2" v-model="memberManage">
                                                    <option value="0">{{ trans('userManage.S_PermissioSymbolExplain_Disable') }}</option>
                                                    <option value="1">{{ trans('userManage.S_PermissioSymbolExplain_Partial') }}</option>
                                                    <option value="2">{{ trans('userManage.S_PermissioSymbolExplain_Full') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <!--/.-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_PI') }}</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2" v-model="personalInfo">
                                                    <option value="0">{{ trans('userManage.S_DialogPIApprove') }}</option>
                                                    <option value="1">{{ trans('userManage.S_DialogPINotApprove') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.-->
                                </div>
                                <!--/.col-md-9-->
                            </div>
                        </div>
                        <div class="col-md-12 plr-30">
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('userManage.S_DialogUserNote') }}</label>
                                <div class="col-md-10">
                                <input type="" class="form-control" id="" placeholder="" v-model="userNote">
                                </div>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <!-- /.form  -->
                    
                </div>
                
            </div>
            <div class="modal-footer">
                <button v-on:click="closeDialog" class="btn btn-default pull-left">{{ trans('userManage.S_DialogCancel') }}</button>
                <button id="dialogUpBtn" v-on:click="saveUserData" class="btn btn-inverse" :disabled="saveBtnDisability">{{ trans('userManage.S_DialogAddData') }}</button>
             </div>
        </div>
    </div>
    </div>
    <!-- /.modal-dialog -->
    <div class="modal-mask" v-show="showPasswordModal">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ trans('userManage.S_PasswordDialogTittle') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <template v-if="sendPasswordStatus">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">@{{ sendStatus }}</label>
                                </div>
                            </template>
                            <template v-else>
                                <!-- form-group -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUsername') }}</label>
                                    <div class="col-md-8">
                                        <div class="form-label-pt">@{{ passwordAccount }}</div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                                <!-- form-group -->
                                <div class="form-group" >
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_PasswordSendWay') }}</label>
                                    <label class="col-md-1 control-label">{{ trans('userManage.S_SendByMail') }}</label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" >
                    <template v-if="sendPasswordStatus">
                        <button class="btn btn-info" v-on:click="closePasswordDialog()">{{ trans('userManage.S_ClosePasswordDialog') }}</button>
                    </template>
                    <template v-else>
                        <button  class="btn btn-default pull-left" v-on:click="closePasswordDialog()">{{ trans('userManage.S_DialogCancel') }}</button>
                        <button id="PasswordSend"  class="btn btn-danger" v-on:click="sendPassword()">{{ trans('userManage.S_PasswordResetBtn') }}</button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- modal -->
    <div id="modal-user" class="modal" onclick="this.style.display='none'">
      <span class="close"><i class="fas fa-times"></i></span>
      <div class="modal-content">
        <img id="document" style="max-width:100%">
      </div>
    </div>
<!-- /.modal -->
<script>
    Vue.config.devtools = true;
    var userInfManage = new Vue({
        el: '#userInfManage',
        data: {
            showModal           :'',
            reviewStatus        :'',
            reviewStatusText    :'',
            comissionPercent    :'',
            comissionFee        :'',
            infEditStatus       :'',
            status              :'',         
            sellTittle          :'',
            dispFlg             :'',
            sellUrl             :'',
            introduction        :'',
            companyName         :'',
            companyNameKana     :'',
            postCode            :'',
            postDisplay         :'',
            prefecture          :'',
            city                :'',
            placeDetailed       :'',
            contactDeparment    :'',
            contactName         :'',
            tel                 :'',
            contactMail         :'',
            bankName            :'',
            branch              :'',
            bankType            :'',
            bankAccount         :'',
            bankAccountKana     :'',
            personalName        :'',
            personalNameKana    :'',
            personalTel         :'',
            personalMail        :'',
            openInf             :'',
            countryNoHad        : '',
            json                : '',
            pathLogo            : '',
            pathImage01         : '',
            pathImage02         : '',
            pathImage03         : '',
            accountData         : '',
            mailHad             : false,
            saveBtnDisability   : false,
            salesInfo           : '', 
            personalInfo        : '',
            memberManage        : '',
            eventInfo           : '',
            profileInfo         : '',
            permissionDeadline  : '',
            errorMsn            : false,
            userName            : '',
            userStatus          : '',
            userMail            : '',
            deadlineDate        : '',
            userNote            : '',
            userContact         : '',
            statucControl       : [],
            showPasswordModal   : false,
            accountNumber       : '',
            passwordAccount     : '',
            sendPasswordStatus  : false,
            sendStatus          : '',
            accountJson         : '',
            rulesCheck          : [],
            hadError            : false,
            GETTIIS_logo_disp_flg		: '0' // STS 2021/07/17 Task 26
        },
        watch: {
            errors:{
                handler(){
                    let cheackStatus = this.cheackError()
                    if(cheackStatus){
                        document.getElementById('update-button').disabled = true
                    }else{
                        document.getElementById('update-button').disabled = false
                    }
                },
                deep: true
            },
            postDisplay: function (val) {
                this.postDisplayCheck(val)
            }
        },
        methods: {
            postDisplayCheck:function(val = this.postDisplay){
                this.hadError = false
                try {
                    let re_post_display = /^\d{3}-\d{4}$/
                    this.rulesCheck['postDisplay']['status'] = false
                    this.rulesCheck['postDisplay']['msn'] = ''

                    if(this.postDisplay.length > 0){
                        if (!re_post_display.test(this.postDisplay)){
                            throw (new Error('形式が不正です'))
                        }
                    }
                }catch (e) {
                    this.hadError = true
                    this.rulesCheck['postDisplay']['status'] = true
                    this.rulesCheck['postDisplay']['msn'] = e.message
                }
            },
            cheackError:function(){
               if(
                    this.errors.has('company-title') || 
                    this.errors.has('company-title-kana') ||
                    this.errors.has('sell-title') || 
                    this.errors.has('sell-url') ||
                    this.errors.has('contact-person') ||
                    this.errors.has('contact-tel')  ||
                    this.errors.has('contact-mail')
                ){  
                    return true
                }else{  
                    return false
                }
            },
            AccountCheackError:function(){
               if(
                   this.errors.has('user-Name') || 
                   this.errors.has('user-mail')
                ){
                    return false
               }else{
                    return true
               }
            },
            //子賬號資料修改
            AccountInfUpdate:function(){
                let accountData = []
                let data = []
                let accountInf = this.accountData[0]

                accountData.push({
                    name                : accountInf.name,
                    userStatus          : accountInf.userStatus, 
                    mail                : accountInf.mail,
                    permissionDeadline  : accountInf.permissionDeadline,
                    deadlineDate        : accountInf.deadlineDate, 
                    profile_info_flg    : accountInf.profile_info_flg, 
                    sales_info_flg      : accountInf.sales_info_flg, 
                    event_info_flg      : accountInf.event_info_flg, 
                    personal_info_flg   : accountInf.personal_info_flg, 
                    member_info_flg     : accountInf.member_info_flg,
                    note                : accountInf.note, 
                })

                data.push({
                    userKbn     : "{{ $data['data']['user_data']['user_kbn'] }}",
                    accountData  : accountData,
                })

                this.accountJson = JSON.stringify(data)
                this.$nextTick(() => {
                    document.getElementById("AccountInf").submit();
                })
            },
            //資料更新送出
            infApply:function(){
                this.$validator.validateAll()
                this.postDisplayCheck()
                let cheackStatus = this.cheackError()
                if(!cheackStatus && !this.hadError){
                    loading.openLoading()

                    let userData = []
                    let accountData = []
                    let data = []
                    let address =  this.placeDetailed

                    if(document.getElementById('postCode')){
                        this.postCode = document.getElementById('postCode').value
                    }

                    userData.push({
                        status              : this.status,
                        comissionPercent    : this.comissionPercent,
                        comissionFee        : this.comissionFee,
                        sellTittle          : this.sellTittle,
                        dispFlg             : this.dispFlg,
                        sellUrl             : this.sellUrl,
                        introduction        : '',
                        companyName         : this.companyName,
                        companyNameKana     : this.companyNameKana,
                        prefecture          : this.prefecture,
                        city                : this.city,
                        postCode            : this.postCode,
                        postDisplay         : this.postDisplay,
                        placeDetailed       : this.placeDetailed,
                        contactDeparment    : this.contactDeparment,
                        contactName         : this.contactName,
                        tel                 : this.tel,
                        contactMail         : this.contactMail,
                        bankName            : this.bankName,
                        branch              : this.branch,
                        bankType            : this.bankType,
                        bankAccount         : this.bankAccount,
                        bankAccountKana     : this.bankAccountKana,
                        personalName        : this.personalName,
                        personalNameKana    : this.personalNameKana,
                        personalTel         : this.personalTel,
                        personalMail        : this.personalMail,
                        openInf             : this.openInf,
                        pathLogo            : this.pathLogo,
                        pathImage01         : this.pathImage01,
                        pathImage02         : this.pathImage02,
                        pathImage03         : this.pathImage03,
                        GETTIIS_logo_disp_flg		: this.GETTIIS_logo_disp_flg // STS 2021/07/17 Task 26
                    })

                    data.push({
                        userKbn     : "{{ $data['data']['user_data']['user_kbn'] }}",
                        userData     : userData,
                    })

                    this.json = JSON.stringify(data)
                    this.$nextTick(() => {
                        document.getElementById("userInf").submit();
                    })
                }
            },
            //開啟子帳號資料修改表單
            changeInf:function(id){
                this.showModal = true

                let accountData = this.accountData[0]

                this.userName = accountData.name
                this.userStatus = accountData.userStatus
                this.userMail = accountData.mail
                this.permissionDeadline = accountData.permissionDeadline
                this.deadlineDate = accountData.deadlineDate
                this.profileInfo = accountData.profile_info_flg
                this.salesInfo = accountData.sales_info_flg
                this.eventInfo = accountData.event_info_flg
                this.personalInfo = accountData.personal_info_flg
                this.memberManage = accountData.member_info_flg
                this.userNote = accountData.note
                document.body.style.overflowY = "hidden";
            },
            //主帳號修改資料存檔
            saveUserData:function(){    
                let valication = this.AccountCheackError()
                if(valication){
                    let accountData = this.accountData[0]

                    accountData.name                = this.userName
                    accountData.userStatus          = this.userStatus 
                    accountData.mail                = this.userMail 
                    accountData.permissionDeadline  = this.permissionDeadline
                    accountData.deadlineDate        = this.deadlineDate 
                    accountData.profile_info_flg    = parseInt(this.profileInfo, 10)
                    accountData.sales_info_flg      = parseInt(this.salesInfo, 10)
                    accountData.event_info_flg      = parseInt(this.eventInfo, 10) 
                    accountData.personal_info_flg   = parseInt(this.personalInfo, 10) 
                    accountData.member_info_flg     = parseInt(this.memberManage, 10) 
                    accountData.note = this.userNote 

                    this.accountData[0] = accountData 

                    this.AccountInfUpdate()
                }
            },
            //子帳號 Dialog 關閉
            closeDialog:function(){
                this.showModal = false
                document.body.style.overflowY = "scroll";
            },
            //開啟密碼修改視窗
            openPasswordDialog:function(id){
                this.sendPasswordStatus = false
                this.showPasswordModal = true 
                this.accountNumber     = id
                this.passwordAccount   = this.accountData[id].name
                document.body.style.overflowY = "hidden";
            },
            //關閉密碼修改視窗
            closePasswordDialog:function(){
                this.showPasswordModal = false 
                document.body.style.overflowY = "scroll";
            },
            //密碼修改狀態
            passwordIsSend:function(data){
                this.sendPasswordStatus = true
                if(data == 'success'){
                    this.sendStatus = '{{trans("userManage.S_CompletedChangePwd")}}'
                }else{
                    this.sendStatus = '{{trans("userManage.S_FailedChangePwd")}}'
                }
            },
            //修改使用者密碼
            sendPassword:function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/accountPasswordChange',
                    type: 'POST',
                    data: { GLID: "{{ $data['status']['GLID'] }}", accountNumber: userInfManage.accountNumber},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR)
                    {
                        userInfManage.passwordIsSend(data.msg)
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        console.log('ERRORS: ' + textStatus + errorThrown)
                    }
                });
            }
        },
        mounted(){
            localStorage.setItem('userData',  '{!! addslashes($data["data"]["user_temporaryInfo"]) !!}')
            localStorage.setItem('accountData',  '{!! addslashes($data["data"]["account_data"]) !!}')

            let data        = JSON.parse(localStorage.getItem('userData'))
            let accountData = JSON.parse(localStorage.getItem('accountData'))
          
            this.showModal          = false
            this.status             = data.status
            this.sellTittle         = data.sellTittle
            this.dispFlg            = data.dispFlg
            this.comissionPercent   = '{!! addslashes($data["data"]["user_data"]["comission_percent"]) !!}',
            this.comissionFee       = '{!! addslashes($data["data"]["user_data"]["comission_fee"]) !!}',
            this.sellImg            = data.sellImg || ""
            this.sellUrl            = data.sellUrl
            this.introduction       = '{!! addslashes($data["data"]["user_data"]["introductionCode"]) !!}'
            this.companyName        = data.companyName
            this.companyNameKana    = data.companyNameKana
            this.postCode           = data.postCode
            this.postDisplay        = data.postDisplay || ''
            this.prefecture         = data.prefecture
            this.city               = data.city
            this.placeDetailed      = data.placeDetailed
            this.contactDeparment   = data.contactDeparment
            this.contactName        = data.contactName
            this.tel                = data.tel
            this.contactMail        = data.contactMail
            this.bankName           = data.bankName 
            this.branch             = data.branch
            this.bankType           = data.bankType || "normal"
            this.bankAccount        = data.bankAccount 
            this.bankAccountKana    = data.bankAccountKana
            this.personalName       = data.personalName
            this.personalNameKana   = data.personalNameKana
            this.personalTel        = data.personalTel
            this.personalMail       = data.personalMail 
            this.openInf            = data.openInf
            this.countryNoHad       = false
            this.pathLogo           = data.pathLogo || ""
            this.pathImage01        = data.pathImage01  || ""
            this.pathImage02        = data.pathImage02  || ""
            this.pathImage03        = data.pathImage03  || ""
            this.accountData        = accountData
            this.reviewStatus       = {!! $data["data"]["user_data"]["user_status"] !!}
            this.GETTIIS_logo_disp_flg 		= data.GETTIIS_logo_disp_flg // STS 2021/07/17 Task 26
 
            //review potion status control
            this.statucControl.push({
                unReview: [0].includes(this.reviewStatus),
                reviewing: [0, 1].includes(this.reviewStatus),
                reviewOk: [1, 9, -1].includes(this.reviewStatus),
                stop: [1, 9, -1].includes(this.reviewStatus),
                leave: [8, -2].includes(this.reviewStatus),
            })

            this.rulesCheck = {
                postDisplay :  {
                                    status : false,
                                    msn : '',
                                },
            }

            //review status text 
            switch(this.reviewStatus) {
                case 0:
                    this.reviewStatusText = '{{trans("userManage.S_StatusUnreview")}}'
                    break;
                case 1:
                    this.reviewStatusText = '{{trans("userManage.S_StatusReviewing")}}'
                    break;
                case 8:
                    this.reviewStatusText = '{{trans("userManage.S_StatusLeaving")}}'
                    break;
                case 9:
                    this.reviewStatusText = '{{trans("userManage.S_StatusReviewOK")}}'
                    break;
                case -1:
                    this.reviewStatusText = '{{trans("userManage.S_StatusStop")}}'
                    break;
                case -2:
                    this.reviewStatusText = '{{trans("userManage.S_StatusLeaved")}}'
                    break;
                default:
                    默认代码块
            } 

            this.$nextTick(() => {

                $('.daterangeSingle').daterangepicker({ 
                    "locale": {
                        "format": "YYYY/MM/DD"
                    },
                    singleDatePicker: true,
                    autoUpdateInput: false
                })

                $('#deadlineDate').on('apply.daterangepicker', function(ev, picker) {
                    userInfManage.deadlineDate = picker.startDate.format('YYYY/MM/DD')
                });

                // dropify
                $('.dropify').dropify({
                    tpl: {
                           wrap: '<div class="dropify-wrapper dropify-wrapper-h420"></div>',
                           loader: '<div class="dropify-loader"></div>',
                           message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p>{{trans("common.S_DropifyMsg")}}</p></div>',
                           preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{trans("common.S_DropifyEdit")}}</p></div></div></div>',
                           filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
                           clearButton: '<button type="button" class="btn"></button>',
                           errorLine: '<p class="dropify-error">{{trans("common.S_DropifyErr")}}</p>',
                           errorsContainer: '<div class="dropify-errors-container"><ul>{{trans("common.S_DropifyErr")}}</ul></div>'
                    }
                });
            })
        }
    })    

    //地區選擇器
    @if($data['data']['user_data']['user_kbn'] === 1)
        new TwCitySelector({
            el: '.city-selector-set',
            elCounty: '.county', 
            elDistrict: '.district', 
            elZipcode: '.postCode',
            countyValue: userInfManage.prefecture,
            districtValue: userInfManage.city,
            hasZipcode: true,
        });
    @endif

    // 文件點擊放大
    function onClick(element) {
        document.getElementById("document").src = element.src;
        document.getElementById("modal-user").style.display = "block";
    };

    //身份證明書浮水印效果
    $(document).ready(function(){
        $(".user-documents img").each(function(){
            var url = this.src;
            var imgObj = this;
            console.log(imgObj);
            watermark(url,imgObj);
            
        });
    });
</script>
@stop
