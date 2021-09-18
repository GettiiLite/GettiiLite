@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
<h1>
    {{ trans('userManage.S_BC02_Title') }}
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    <li>
        <a href="/userManage"> 
            {{ trans('userManage.S_BC01_Top') }}
        </a>
    </li>
    <li class="active">
        {{ trans('userManage.S_BC02') }}
    </li>
</ol>
<!-- /.網站導覽 -->

@stop

@section('content')
<!-- 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div id="userInfEdit" class="userInfEdit">

   <!-- modal-dialog -->
    <div class="modal-mask" v-show="withdrawal">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">
                      <i class="fas fa-exclamation-triangle"></i> 退会申請後は、各機能が閲覧のみとなります。<br>
                      ご注意ください。
                    </h4>
                </div>
                <div class="modal-footer" >
                  <button class="btn btn-default pull-left"  v-on:click="closeModal()">
                    {{ trans('userManage.S_DialogCancel') }}
                  </button>
                  <button class="btn btn-danger pull-right" onclick="userInfEdit.withdrawalApply()">
                    {{ trans('userManage.S_Withdrawal') }}
                  </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-dialog -->


    <div class="funtion-btn-block">
        @if(count($eventOnSale) == 0)
          <!-- 退会申請は販売中の公演がない場合のみ申請可 -->    
          <button  v-on:click="openModal" class="btn waves-effect waves-light btn-rounded btn-danger-outline m-r-10" >
              {{ trans('userManage.S_Withdrawal') }}
          </button>
        @endif  
        <button id="apply-button" type="button" onclick="userInfEdit.infApply()" class="btn waves-effect waves-light btn-rounded btn-inverse"> 
            {{ trans('userManage.S_ApplyBtn2') }} 
        </button>
    </div>
    <!-- /.固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->

    <form id="AccountInf" method="POST" action="/userManage/editInf" enctype="multipart/form-data">
        <input type="hidden" name="json" v-model="json">
        {{ csrf_field() }}
        <!--<div duck="dialog" class="modal-mask" v-show="showModal">
            <div class="modal-wrapper user-setting">
                <div class="modal-container inf-edit-dialog">
                    <div class="modal-header">
                    <slot name="header">
                        <p class="text-center">{{ trans('userManage.S_PasswordDialogTittle') }}</p>
                    </slot>
                    </div>
                    <div class="modal-body">
                        <div dusk="applyComple" class="row" v-show="(infEditStatus == 'success') ? true : false">
                            <div class="col-md-10 col-md-offset-2">
                                <p>{{ trans('userManage.S_InfIsApplyComplete') }}</p>
                            </div>
                        </div>
                        <div dusk="validatorFail" class="row" v-show="(infEditStatus == 'validatorFail') ? true : false">
                            <div class="col-md-10 col-md-offset-2">
                                <p>{{ trans('userManage.S_HadValidatorText') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer inf-edit-col-center" >
                        <slot name="footer">
                            <button id="successBtn" v-on:click="backIndex" class="modal-default-button btn btn-primary btn-sm col-center" v-show="(infEditStatus == 'success') ? true : false">{{ trans('userManage.S_InfIsApplyCompleteBackBtn') }}</button>
                            <button id="closeBtn" v-on:click="closeDialog" class="modal-default-button btn-primary  btn-sm col-center" v-show="(infEditStatus == 'validatorFail') ? true : false">{{ trans('userManage.S_HadValidatorBtn') }}</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>-->
        <!-- box1 統一樣式兩欄 + 圖片區 -->
        <div class="box no-border">

            <div class="box-header with-border-non">
                <h3 class="box-title"> 
                    {{ trans('userManage.S_WebInfTittle') }}
                    <div class="tip"><span data-tooltip="{{trans('events.S_eventImageNotice')}}"><i class="fas fa-info fa-1x fa__thead"></i></span></div>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                    	{{-- STS 2021/07/17 Task 26 --}}
                            <h5 class="subtitle pt-0 mt-0">{{ trans('userManage.S_GETTIISLogoDisplayStatus_Description') }}</h5>
                            <div class="drop-image">
                                <input type="file" id="image" name="logo" class="dropify-logo-user"
                                    @change="imageUpload($event)" data-height="300" v-bind:data-default-file="pathLogo" /
                                    data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]'>
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
                    <div class="col-md-1"></div>
                    <div class="form-horizontal col-md-8">
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">
                                {{ trans('userManage.S_SellTittle') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-md-10">
                                <input type="text" id="sellTittle" class="form-control" name="sell-title"  maxlength="80" v-model="sellTittle" v-validate="'required|max:255'"  placeholder="">
                                <span  dusk="sellTittle" v-show="errors.has('sell-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('sell-title') }}</span>
                            </div>
                        </div>
                        <!-- 0714 新增 -->
                         <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="form-checkbox">
                                <label class="control control--checkbox">
                                    <input id="dispFlg"  name="dispFlg" v-model="dispFlg" type="checkbox" >{{trans('userManage.S_GETTIIS')}}
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div> 
                    <!-- /. 0714 新增 -->
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">
                                {{ trans('userManage.S_SellUrl') }}
                            </label>
                            <div class="col-md-10">
                                <input type="" id="sellUrl" v-validate="'url:require_protocol|max:200'" name="sell-url" maxlength="100" class="form-control" v-model="sellUrl" placeholder="">
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
            <!-- Block 1 -->
            <div class="box-header with-border-non" v-if="(status === 1) ? true : false">
                <h3 class="box-title">
                    {{ trans('userManage.S_CompanyInfTitle') }}
                </h3>
            </div>
            <div class="box-header with-border" v-if="(status === 0) ? true : false">
                <h3 class="box-title">
                    {{ trans('userManage.S_PersonalInfTitle') }}
                </h3>
            </div>
            <div class="box-body">
                <h5 class="st-line">{{ ($user_kbn == 1)?trans('userManage.S_CompanyInf'):trans('userManage.S_PersonalInf') }}<span></span></h5>
                <!-- form  -->
                <div class="form-horizontal">
                    <div class="col-md-12 city-selector-set" v-if="(status === 1) ? true : false">
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                {{ trans('userManage.S_CompanyTitle') }}
                            </label>
                            <div class="col-md-10">
                                <input type=""  id="companyTitle"  class="form-control" name="company-title" maxlength="80" v-model="companyName"  v-validate="'required|max:80'" placeholder="{{ trans('registered.S_CompanyNamePlaceholder')}}">
                                <span  dusk="companyTitle" v-show="errors.has('company-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-title') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <!-- 0714 新增 -->
                        @if((\App::getLocale() == "ja" ))
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                {{ trans('userManage.S_CompanyTitleKana') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-md-10">
                                <input type="" id="companyTitleKana"  class="form-control"  name="company-title-kana" maxlength="160" v-model="companyNameKana" v-validate="'required||regex:^([ァ-ヴ][ァ-ヴー・　]*)$'" placeholder="{{ trans('registered.S_CompanyNamePlaceholderKana')}}">
                                {{-- <span v-show="rulesCheck['companyNameKana']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ rulesCheck['companyNameKana']['msn'] }}</span> --}}
                                <span dusk="companyNameKana" v-show="errors.has('company-title-kana')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-title-kana') }}</span>

                            </div>
                        </div>
                        @endif
                        <!-- /. 0714 新增 -->
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                {{ trans('userManage.S_CompanyPlace') }}
                            </label>
                            <div class="col-md-5">
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
                                <div class="txt-flex-mr">
                                <div class="input-unit-left"> 〒 </div>
                                <input type="" id="postCode" class="form-control postCode" placeholder="" v-show="false">
                                <input type="" class="form-control pl-40" v-model="postDisplay" placeholder="{{ trans('registered.S_PostCodePlaceholder')}}">
</div>
                                <span v-show="rulesCheck['postDisplay']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ rulesCheck['postDisplay']['msn'] }}</span>
                            </div>
                            <div class="col-md-8">
                                <input type="" class="form-control" id="" maxlength="100" name='place-detailed' v-validate="'max:100'" v-model="placeDetailed" placeholder="{{ trans('registered.S_AddressPlaceholder')}}">
                                <span dusk="personalTitle" v-show="errors.has('place-detailed')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('place-detailed') }}</span>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.form  -->
                    <div class="col-md-12" v-if="(status === 0) ? true : false">
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                {{ trans('userManage.S_PersonalName') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-md-10">
                                <input type="" id="personalTitle" class="form-control" name="personal-title"  maxlength="40" v-model="personalName"  v-validate="'required|max:40'" placeholder="{{ trans('registered.S_UserNamePlaceholder')}}">
                                <span dusk="personalTitle" v-show="errors.has('personal-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('personal-title') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        @if((\App::getLocale() == "ja" ))
                            <div class="form-group">
                                <label class="col-md-2 control-label">
                                    {{ trans('userManage.S_PersonalNameKana') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                                </label>
                                <div class="col-md-10">
                                    <input type="" id="personalitleKana" class="form-control"  name="personal-title-kana" maxlength="150" v-model="personalNameKana" placeholder="{{ trans('registered.S_UserNamePlaceholderKana')}}">
                                    <span v-show="rulesCheck['personalNameKana']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ rulesCheck['personalNameKana']['msn'] }}</span>
                                </div>
                            </div> 
                        @endif
                        <!-- /.form-group -->
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- body-footer 位置 -->
            </div>
            <!-- /.Block 1-->
            <!-- Block 2 -->
            <div class="box-body">
                <h5 class="st-line">{{ trans('userManage.S_ContactInf') }}<span></span></h5>
                <!-- form  -->
                <div class="form-horizontal" v-if="(status === 1) ? true : false">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                {{ trans('userManage.S_ContactDeparment') }}
                            </label>
                            <div class="col-sm-8">
                                <input type="" class="form-control" name='contact-deparment' maxlength="80" v-validate="'max:80'" v-model="contactDeparment" placeholder="{{ trans('registered.S_ContactDepartmentPlaceholder')}}">
                                <span v-show="errors.has('contact-deparment')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-deparment') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label"> 
                                {{ trans('userManage.S_ContactPerson') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-sm-8">
                                <input type="" class="form-control"  id="contactPerson" name="contact-name" v-model="contactName" maxlength="80" v-validate="'required|max:80'" placeholder="{{ trans('registered.S_UserNamePlaceholder')}}">
                                <span  dusk="contactPerson" v-show="errors.has('contact-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-name') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                {{ trans('userManage.S_ContactTel') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-sm-8">
                                <input id="contactTel" v-validate="'required|tel_format|max:20'" maxlength="20"  name="contact-tel"  type="tel" class="form-control" v-model="tel" placeholder="{{ trans('registered.S_UserTelPlaceholder')}}">
                                <span dusk="contactTel" v-show="errors.has('contact-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-tel') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                {{ trans('userManage.S_UserMail') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-sm-8">
                                <input type="" class="form-control" id="contactMail" maxlength="200" v-validate="'required|email|max:200'"  name="contact-mail" v-model="contactMail" placeholder="{{ trans('registered.S_UserMailPlaceholder')}}">
                                <span dusk="contactMail" v-show="errors.has('contact-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-mail') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ trans('userManage.S_VerifyPICTitle') }}</label>
                            <div class="col-sm-10">
                                <h5 class="subtitle">{{ trans('userManage.S_VerifyPICNote') }}</h5>
                                <div class=" col-md-4">
                                    <!-- drop1 -->
                                    <div class="funtion-upload">
                                        <!-------------->
                                        <input type="file" name="file_01" id="input-file-now" class="dropify" v-bind:data-default-file="pathImage01" @change="imageUpload($event)" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >
                                        <!-------------->
                                    </div>
                                    <!-- /.drop1 -->
                                </div>
                                <div class=" col-md-4">
                                    <!-- drop2 -->
                                    <div class="funtion-upload">
                                        <!-------------->
                                        <input type="file" name="file_02" id="input-file-now" class="dropify" v-bind:data-default-file="pathImage02" @change="imageUpload($event)" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >
                                        <!-------------->
                                    </div>
                                    <!-- /.drop2 -->
                                </div>
                                <div class="col-md-4">
                                    <!-- drop3 -->
                                    <div class="funtion-upload">
                                        <!-------------->
                                        <input type="file" name="file_03" id="input-file-now" class="dropify" v-bind:data-default-file="pathImage03" @change="imageUpload($event)" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >
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
                <div class="form-horizontal" v-if="(status === 0) ? true : false">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                {{ trans('userManage.S_ContactTel') }}
                            </label>
                            <div class="col-sm-8">
                                <input type="tel" class="form-control" id="personalContactTel" maxlength="20" v-validate="'required|tel_format|max:20'" name="personal-contact-tel" v-model="personalTel" placeholder="">
                                <span dusk="personalContactTel" v-show="errors.has('personal-contact-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('personal-contact-tel') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                {{ trans('userManage.S_UserMail') }}<b>{{ trans('userManage.S_RequiredMark') }}</b>
                            </label>
                            <div class="col-sm-8">
                                <input id="personalContactMail" v-validate="'required|email|max:200'" maxlength="200" name="personal-contact-mail"  type="text" class="form-control" v-model="personalMail" placeholder="">
                                <span dusk="personalContactMail" v-show="errors.has('personal-contact-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('personal-contact-mail') }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ trans('userManage.S_VerifyPICTitle') }}</label>
                            <div class="col-sm-10">
                                <h5 class="subtitle">{{ trans('userManage.S_VerifyPICNote2') }}</h5>
                                <div class=" col-md-4">
                                    <!-- drop1 -->
                                    <div class="funtion-upload">
                                        <!-------------->
                                        <input type="file" name="file_01" id="input-file-now" class="dropify" v-bind:data-default-file="pathImage01" @change="imageUpload($event)" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >
                                        <!-------------->
                                    </div>
                                    <!-- /.drop1 -->
                                </div>
                                <div class=" col-md-4">
                                    <!-- drop2 -->
                                    <div class="funtion-upload">
                                        <!-------------->
                                        <input type="file" name="file_02" id="input-file-now" class="dropify" v-bind:data-default-file="pathImage02" @change="imageUpload($event)" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >
                                        <!-------------->
                                    </div>
                                    <!-- /.drop2 -->
                                </div>
                                <div class="col-md-4">
                                    <!-- drop3 -->
                                    <div class="funtion-upload">
                                        <!-------------->
                                        <input type="file" name="file_03" id="input-file-now" class="dropify" v-bind:data-default-file="pathImage03" @change="imageUpload($event)" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >
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
        <div class="box-body">
            <h5 class="st-line">{{ trans('userManage.S_BankInf') }}<span></span></h5>
            <!-- form  -->
            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            {{ trans('userManage.S_BankName') }}
                        </label>
                        <div class="col-md-10">
                            <input type="" class="form-control" id="bankName" name="bank-Name" maxlength="60" v-model="bankName" v-validate="'required|max:60'">
                            <span dusk="bankName" v-show="errors.has('bank-Name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('bank-Name') }}</span>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            {{ trans('userManage.S_BankAccountNum') }}
                        </label>
                        <div class="col-md-2">
                            <select class="form-control select2" style="width: 100%;" v-model="bankType">
                                <option value="normal">{{ trans('userManage.S_BankAccountType01') }}</option>
                                <option value="spec">{{ trans('userManage.S_BankAccountType02') }}</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="bankAccountNum"  name="bank-account-num" maxlength="20" v-model="bankAccount"  v-validate="'required|numeric|max:20'">
                            <span dusk="bankAccountNum" v-show="errors.has('bank-account-num')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('bank-account-num') }}</span>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            {{ trans('userManage.S_BankLocation') }}
                        </label>
                        <div class="col-md-10">
                            <input type="" class="form-control" id="bankLocation" name="bank-location" v-model="branch" maxlength="60" v-validate="'required|max:60'">
                            <span dusk="bankLocation" v-show="errors.has('bank-location')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('bank-location') }}</span>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            {{ trans('userManage.S_BankLocationKana') }}
                        </label>
                        <div class="col-md-10">
                            <input type="" class="form-control" id="" name="bank-account-kana" v-model="bankAccountKana" maxlength="40">
                            <span v-show="rulesCheck['bankAccountKana']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ rulesCheck['bankAccountKana']['msn'] }}</span>
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
        <!--  box2 統一樣式 -->
        <div class="box no-border">
            <!-- Block 1 -->
            <div class="box-header with-border-non">
                <h3 class="box-title">
                    {{ trans('userManage.S_UserOpenInf') }}
                </h3>
                <small class="subtitle">
                    {{ trans('userManage.S_UserOpenInfNote') }}
                </small>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <textarea class="form-control" rows="5" id="comment" v-model="openInf" placeholder=""></textarea>
                </div>
            </div>
        </div>    
    </form>
</div>

<script>
    window.is_confirm = true
    window.addEventListener('beforeunload', (event) => {
        if (window.is_confirm){
            event.preventDefault();
            event.returnValue = '';
            loading.closeLoading()
        }
    });
    const errorIsNull = '{{ trans("userManage.S_ErrorIsNull") }}'
    const errorIsNotKatakana = '{{ trans("userManage.S_ErrorIsNotKatakana") }}'
    // STS 2021/07/20 Task 26 start
    var logo_dell_flag = false;
    var removeLogo = (flag) => {
        logo_dell_flag = flag;
    }
     // STS 2021/07/20 Task 26 end
    var userInfEdit = new Vue({
        el: '#userInfEdit',
        data:{
            showModal:'',
            infEditStatus:'',
            status:'',
            sellTittle:'',
            sellUrl:'',
            companyName:'',
            companyNameKana:'',
            postCode:'',
            postDisplay:'',
            prefecture:'',
            city:'',
            placeDetailed:'',
            contactDeparment:'',
            contactName:'',
            tel:'',
            contactMail:'',
            bankName:'',
            branch:'',
            bankType:'',
            bankAccount:'',
            bankAccountKana:'',
            personalName:'',
            personalNameKana:'',
            personalTel:'',
            personalMail:'',
            openInf:'',
            countryNoHad: '',
            json: '',
            pathLogo: '',
            pathImage01: '',
            pathImage02: '',
            pathImage03: '',
            dispFlg: '',
            rulesCheck: [],
            withdrawal:'',
            GETTIIS_logo_disp_flg: '0' // STS 2021/07/17 Task 26
        },
        watch: {
            errors:{
                handler(){
                    this.allCheck()
                },
                deep: true
            },
            companyNameKana: function (val) {
                this.companyNameKanaCheck(val)
                this.allCheck()
            },
            bankAccountKana: function (val) {
                this.bankAccountKanaCheck(val)
                this.allCheck()
            },
            personalNameKana: function (val) {
                this.personalNameKanaCheck(val)
                this.allCheck()
            },
            postDisplay: function (val) {
                this.postDisplayCheck(val)
                this.allCheck()
            }
        },
        methods: {
            imageUpload:function($event){
                try {
                    let img = $event.target.files[0]
                    if(img.size > 2097152)
                    {
                        return;
                    }
                    removeLogo(false) //STS 2021/07/20 Task 26 
                }catch (error){
                    console.error(error)
                    $event.stopImmediatePropagation();
                }
            },  
            allCheck(){
                try {
                    for (let key in this.rulesCheck) {
                        if(this.rulesCheck[key].status){
                            throw (new Error('vaid is faid'))
                        }
                    }

                    if(this.errors.any()){
                        throw (new Error('vaid is faid'))
                    }

                    document.getElementById('apply-button').disabled = false

                    return true
                }catch (e){
                    document.getElementById('apply-button').disabled = true

                    return false
                }
            },
            postDisplayCheck:function(val = this.postDisplay){
                try {
                    let re_post_display = /^\d{3}-\d{4}$/
                    this.rulesCheck['postDisplay']['status'] = false
                    this.rulesCheck['postDisplay']['msn'] = ''
                    
                    if(this.postDisplay.length > 0 && this.status === 1){
                        if (!re_post_display.test(this.postDisplay)){
                            throw (new Error('形式が不正です'))
                        }
                    }
                }catch (e) {
                    this.rulesCheck['postDisplay']['status'] = true
                    this.rulesCheck['postDisplay']['msn'] = e.message
                }
            },
            companyNameKanaCheck(val = this.companyNameKana){
                try {
                    this.rulesCheck['companyNameKana']['status'] = false
                    this.rulesCheck['companyNameKana']['msn'] = ''
                   
                    if(this.validIsNull(val) && locale == 'ja'){
                        throw (new Error(errorIsNull))
                    }

                    if(!this.validKana(val)  && locale == 'ja'){
                        throw (new Error(errorIsNotKatakana))
                    }
                }catch (e) {
                    this.rulesCheck['companyNameKana']['status'] = true
                    this.rulesCheck['companyNameKana']['msn']    = e.message
                }

            },
            bankAccountKanaCheck(val = this.bankAccountKana){
                try {
                    this.rulesCheck['bankAccountKana']['status'] = false
                    this.rulesCheck['bankAccountKana']['msn'] = ''
                   
                    if(this.validIsNull(val)){
                        throw (new Error(errorIsNull))
                    }

                    if(!this.validKana(val)  && locale == 'ja'){
                        throw (new Error(errorIsNotKatakana))
                    }
                }catch (e) {
                    this.rulesCheck['bankAccountKana']['status'] = true
                    this.rulesCheck['bankAccountKana']['msn']    = e.message
                }
            },
            personalNameKanaCheck(val = this.personalNameKana){
                try {
                    this.rulesCheck['personalNameKana']['status'] = false
                    this.rulesCheck['personalNameKana']['msn'] = ''
                   
                    if(this.validIsNull(val)  && locale == 'ja'){
                        throw (new Error(errorIsNull))
                    }

                    if(!this.validKana(val) && locale == 'ja'){
                        throw (new Error(errorIsNotKatakana))
                    }
                }catch (e) {
                    this.rulesCheck['personalNameKana']['status'] = true
                    this.rulesCheck['personalNameKana']['msn']    = e.message
                }
            },
            validIsNull(text) {
                if (text.length <= 0 || text === undefined){ 
                    return true;
                }
                return false;
            },
           validKana(text) {
                if (text === null || text === undefined) return null;
                const re = /^[\ｦ-ﾟ\u30a0-\u30ff]+$/;
                return re.test(text)
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                //this.createImage(files[0]);
            },
           
            closeDialog:function(){
                this.showModal = false
            },
            backIndex:function(){
                window.location.replace('/userManage')
            },
            withdrawalApply:function(){
              loading.openLoading()
              userInfEdit.sendApply()
            },            
            infApply:function(){
                if(this.status === 0){
                    this.personalNameKanaCheck()
                    this.bankAccountKanaCheck()
                }else{
                    this.companyNameKanaCheck()
                    this.bankAccountKanaCheck()
                }
                this.postDisplayCheck()
                this.$validator.validateAll().then(() => {
                    if (this.allCheck()){
                        loading.openLoading()
                        userInfEdit.sendApply()
                    }else{
                        loading.closeLoading()
                        userInfEdit.hadValidate()
                    }
                });
            },
            sendApply:function(){
                let json = []

                if(this.status === 1){
                    this.postCode = document.getElementById('postCode').value
                }
                
                json.push({
                    status: this.status,
                    sellTittle: this.sellTittle,
                    dispFlg: this.dispFlg,
                    sellUrl: this.sellUrl,
                    companyName: this.companyName,
                    companyNameKana: this.companyNameKana,
                    prefecture: this.prefecture,
                    city: this.city,
                    postCode: this.postCode,
                    postDisplay: this.postDisplay,
                    placeDetailed: this.placeDetailed,
                    contactDeparment: this.contactDeparment,
                    contactName: this.contactName,
                    tel: this.tel,
                    contactMail: this.contactMail,
                    bankName: this.bankName,
                    branch: this.branch,
                    bankType: this.bankType,
                    bankAccount:  this.bankAccount,
                    bankAccountKana: this.bankAccountKana,
                    personalName: this.personalName,
                    personalNameKana: this.personalNameKana,
                    personalTel: this.personalTel,
                    personalMail: this.personalMail,
                    openInf: this.openInf,
                    withdrawal: this.withdrawal,
                    GETTIIS_logo_disp_flg: this.GETTIIS_logo_disp_flg, // STS 2021/07/17 Task 26
                    logo_dell: logo_dell_flag // STS 2021/07/20 Task 26
                })

                localStorage.setItem('userInf' ,JSON.stringify(json))
                this.json = JSON.stringify(json)
                this.$nextTick(() => {
                    window.is_confirm = false
                    document.getElementById("AccountInf").submit();
                })
            },
            hadValidate:function(){
                userInfEdit.infEditStatus = 'validatorFail'
                userInfEdit.showModal = true
            },
            openModal:function(){
                this.withdrawal = true
            },
            closeModal:function(){
                this.withdrawal = false
            },
        },
        mounted(){
            localStorage.setItem('userInf','{!! addslashes($jsonUserData) !!}')

            let userInf = JSON.parse(localStorage.getItem('userInf'))
            let data = userInf.data
            
            this.showModal = false
            this.status = data.userData.status
            this.sellTittle =  data.userData.sellTittle
            this.dispFlg = data.userData.dispFlg
            this.sellImg = data.userData.sellImg || "{{ URL::to('/assets/images/gettiis/substitute_img.jpg') }}"
            this.sellUrl = data.userData.sellUrl
            this.companyName = data.userData.companyName
            this.companyNameKana = data.userData.companyNameKana
            this.postCode = data.userData.postCode
            this.postDisplay = data.userData.postDisplay || 0
            this.prefecture = data.userData.prefecture
            this.city = data.userData.city
            this.placeDetailed = data.userData.placeDetailed
            this.contactDeparment = data.userData.contactDeparment
            this.contactName = data.userData.contactName
            this.tel = data.userData.tel
            this.contactMail = data.userData.contactMail
            this.bankName = data.userData.bankName 
            this.branch = data.userData.branch
            this.bankType = data.userData.bankType || "normal"
            this.bankAccount = data.userData.bankAccount 
            this.bankAccountKana = data.userData.bankAccountKana || ""
            this.personalName = data.userData.personalName
            this.personalNameKana = data.userData.personalNameKana || ""
            this.personalTel = data.userData.personalTel
            this.personalMail = data.userData.personalMail 
            this.openInf = data.userData.openInf
            this.countryNoHad = false
            this.pathLogo = data.userData.pathLogo || "{{ URL::to('/assets/images/gettiis/substitute_img.jpg') }}"
            this.pathImage01 = data.userData.pathImage01  || "{{ URL::to('/assets/images/gettiis/substitute_img.jpg') }}"
            this.pathImage02 = data.userData.pathImage02  || "{{ URL::to('/assets/images/gettiis/substitute_img.jpg') }}"
            this.pathImage03 = data.userData.pathImage03  || "{{ URL::to('/assets/images/gettiis/substitute_img.jpg') }}"
        	this.GETTIIS_logo_disp_flg = data.userData.GETTIIS_logo_disp_flg // STS 2021/07/17 Task 26
            const errorInf = {
                status : false,
                msn : '',
            }

            const errorIn2f = {
                status : false,
                msn : '',
            }

            if(this.status === 0){
                this.rulesCheck = {
                    personalNameKana :  {
                                            status : false,
                                            msn : '',
                                        },
                    bankAccountKana :  {
                                            status : false,
                                            msn : '',
                                        },
                    postDisplay :  {
                                        status : false,
                                        msn : '',
                                    },
                }
            }else{
                this.rulesCheck = {
                    companyNameKana :   {
                                            status : false,
                                            msn : '',
                                        },
                    bankAccountKana :  {
                                            status : false,
                                            msn : '',
                                        },
                    postDisplay :  {
                                        status : false,
                                        msn : '',
                                    },
                }
            }
           
            this.$nextTick(() => {

                $('.daterangeSingle').daterangepicker({ 
                    singleDatePicker: true,
                    autoUpdateInput: false,
                })

                $('#deadlineDate').on('apply.daterangepicker', function(ev, picker) {
                    userManage.deadlineDate = picker.startDate.format('YYYY/MM/DD')
                });

                // dropify
                $('.dropify').dropify({
                    tpl: {
                           wrap: '<div class="dropify-wrapper"></div>',
                           loader: '<div class="dropify-loader"></div>',
                           message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p>{{trans("common.S_DropifyMsg")}}</p></div>',
                           preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{trans("common.S_DropifyEdit")}}</p></div></div></div>',
                           filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
                           clearButton: '<button type="button" class="btn"></button>',
                           errorLine: '<p class="dropify-error">{{trans("common.S_DropifyErr")}}</p>',
                           errorsContainer: '<div class="dropify-errors-container"><ul>{{trans("common.S_DropifyErr")}}</ul></div>'
                    },
                    error: {
                      'fileSize': '{{trans("common.S_DropifySizeErr")}}'
                    }
                });
                // STS 2021/07/20 Task 26 start
                    var dropifyLogoUser = $('.dropify-logo-user').dropify({
                        tpl: {
                            wrap: '<div class="dropify-wrapper"></div>',
                            loader: '<div class="dropify-loader"></div>',
                            message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p>{{ trans('common.S_DropifyMsg') }}</p></div>',
                            preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ trans('common.S_DropifyEdit') }}</p></div></div></div>',
                            filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
                            clearButton: '<button type="button" onClick="removeLogo(true)" class="dropify-clear">X</button>',
                            errorLine: '<p class="dropify-error">{{ trans('common.S_DropifyErr') }}</p>',
                            errorsContainer: '<div class="dropify-errors-container"><ul>{{ trans('common.S_DropifyErr') }}</ul></div>'
                        },
                        error: {
                            'fileSize': '{{ trans('common.S_DropifySizeErr') }}'
                        }
                    });
                    // STS 2021/07/20 Task 26 end
            })
        }
    });
    @if($user_kbn)
        new TwCitySelector({
            el: '.city-selector-set',
            elCounty: '.county', 
            elDistrict: '.district', 
            elZipcode: '.postCode',
            countyValue: userInfEdit.prefecture,
            districtValue: userInfEdit.city,
            hasZipcode: true,
        });
    @endif
</script>
@stop