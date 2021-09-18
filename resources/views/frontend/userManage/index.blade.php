
@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
<h1>
    {{ trans('userManage.S_Account') }}
    <small id="account"></small> 
    {{-- <span onclick="userManage.accountDelete()" class="badge bg-red disabled">
        {{ trans('userManage.S_CancelBtn') }}
    </span> --}}
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    <li><a href="#">{{ trans('userManage.S_BC01_Top') }}</a></li>
    {{-- <li class="active">使用者管理</li> --}}
</ol>
<!-- /.網站導覽 -->

@stop

@section('content')
<!-- 0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div class="funtion-btn-block">
    <span id="isUpload" class="badge badge-note">{{ $statusText }}</span>
    @if(session('profile_info_flg') == 2 && $user_status != 8) <!-- 退会申請中非表示-->
        <a  id="efitInfBtn" href="/userManage/editInf" onclick="loading.openLoading()" class="btn waves-effect waves-light btn-rounded btn-inverse" >
            {{ trans('userManage.S_ApplyBtn') }}
        </a>
    @endif
</div>
<!-- /.0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<div id="userManage">
    <form id="formUserData" method="POST" action="/userManage">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
    </form>
    <form id="formChangeData" method="POST" action="/userManage/dataChange">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
    </form>
    <form id="formChangePw" method="POST" action="/userManage/changePassword">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
    </form>
    <form id="deleteAccount" method="POST" action="/userManage/accountDelete">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
    </form>
    <!-- /.modal-dialog -->
    <div dusk="changPassWordDialog" class="modal-mask" v-show="showPasswordModal">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header" v-if="(passwordChangeStatus == 'select') ? true : false">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>-->
                    <h4 class="modal-title">
                        {{ trans('userManage.S_PasswordDialogTittle') }}
                    </h4>
                </div>
                <div class="modal-header" v-if="(passwordChangeStatus !== 'select') ? true : false">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>-->
                    <h4 class="modal-title"  v-if="(userBtnStatic == 'new')">{{ trans('userManage.S_DialogPWShowTitleNew') }}</h4>
                    <h4 class="modal-title"  v-if="(userBtnStatic == 'change')">{{ trans('userManage.S_DialogPWShowTitle') }}</h4>
                </div>
                <div class="modal-body">
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12"  v-if="(passwordChangeStatus == 'select') ? true : false">
                            <!-- form-group -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUsername') }}</label>
                                <div class="col-md-8">
                                    <div class="form-label-pt">@{{ name }}</div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <!-- form-group -->
                            <div class="form-group" >
                                <label class="col-md-4 control-label">{{ trans('userManage.S_PasswordSendWay') }}</label>
                                <div class="col-md-8 form-group-flex">
                                    <div class="form-checkbox">
                                        <label class="control control--radio">
                                            <input type="radio" name="passwordSelect" value="web" v-model="passwordSelect">{{ trans('userManage.S_ShowInWebSite') }}
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="form-checkbox" v-if="mail">
                                        <label class="control control--radio">
                                            <input type="radio" name="passwordSelect" value="mail" v-model="passwordSelect">{{ trans('userManage.S_SendByMail') }}
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="col-md-12" v-if="(passwordChangeStatus == 'web') ? true : false">
                            <!-- form-group -->
                            <h5 class="text-center" v-if="userBtnStatic =='new'">{{ trans('userManage.S_NewPasswordTitle') }}</h5>
                            <h5 class="text-center" v-if="userBtnStatic =='change'">{{ trans('userManage.S_ResetPasswordTitle') }}</h5>
                            <p class="w-85 margin-auto">{!! trans('userManage.S_NewPassword') !!}</p>
                            <h4 id="newPassword" class="text-center text-red">@{{ newPassword }}</h4>
                            <div class="text-center">
                                <button  v-on:click="copyNewPassWord" type="button" class="btn btn-inverse btn-sm">{{ trans('userManage.S_CopyText') }}</button>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.form-group -->
                        <div class="col-md-12" v-if="(passwordChangeStatus == 'mail') ? true : false">
                            <!-- form-group -->
                            <h5 class="text-center">{{ trans('userManage.S_NewPasswordMail') }}</h5>
                        </div>
                    </div>
                </div>
                <!-- /.form  -->
                <div class="modal-footer" >
                    <button v-on:click="closePasswordDialog" class="btn btn-default pull-left"  v-if="(passwordChangeStatus == 'select') ? true : false">{{ trans('userManage.S_DialogCancel') }}</button>
                    <button id="PasswordSend" v-on:click="sendUserNewPassWord(name)" class="btn btn-danger" v-if="(passwordChangeStatus == 'select') ? true : false">{{ trans('userManage.S_PasswordResetBtn') }}</button>
                    <button v-on:click="closePasswordDialog" class="btn btn-info" v-if="(passwordChangeStatus !== 'select') ? true : false">{{ trans('userManage.S_ClosePasswordDialog') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div dusk="userSettingDialog" class="modal-mask" v-show="showModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>-->
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
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <!-- form-group -->    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('userManage.S_DialogUsername') }}</label>
                                    <div class="col-md-8">
                                        <input dusk="userName" type="text" class="form-control" name="user-Name" v-model="name"  maxlength="20" v-validate="'required|alpha_num|min:6|max:20'">
                                        <span dusk="userNameWarn" v-show="errors.has('user-Name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i>@{{ errors.first('user-Name') }}</span>
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
                                    <input dusk="dialog-mail" name="user-mail" type="email" class="form-control" v-model="mail" v-validate="'email'">
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
                </div><!--12-->
                <!-- /.form-group -->
                <div class="col-md-12">
                    <!-- 0527 調整 -->
                    <h5 class="st-line">{{ trans('userManage.S_UserPermission') }}<span></span></h5>
                    <div class="form-group">
                        <!--<label class="col-md-2 control-label">{{ trans('userManage.S_UserPermission') }}</label>-->
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
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
                                <div class="mb-3 form-group">
                                    <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_EM') }}</label>
                                    <div class=" col-md-8">
                                        <select class="form-control select2" v-model="eventInfo">
                                            <option value="0">{{ trans('userManage.S_PermissioSymbolExplain_Disable') }}</option>
                                            <option value="1">{{ trans('userManage.S_PermissioSymbolExplain_Partial') }}</option>
                                            <option value="2">{{ trans('userManage.S_PermissioSymbolExplain_Full') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--/.-->
                            <div class="col-md-6">
                                <div class="mb-3 mr-3 form-group">
                                    <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_SM') }}</label>
                                    <div class=" col-md-8">
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
                                <div class="mb-3 form-group">
                                    <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_MM') }}</label>
                                    <div class=" col-md-8">
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
                            <!--/.-->
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="control-label col-md-4">{{ trans('userManage.S_FuncExplain_PI') }}</label>
                                    <div class=" col-md-8">
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
                    <!-- form-group -->
                    <div class="col-md-12 plr-30" v-if="userBtnStatic =='new'">
                        <div class="mb-3 mr-3 form-group">
                            <label class="col-md-2 control-label">{{ trans('userManage.S_PasswordSendWay') }}</label>
                            <div class="col-md-8 form-group-flex">
                                <div class="form-checkbox">
                                    <label class="control control--radio">
                                        <input type="radio" name="getPassword" value="web" v-model="contact">{{ trans('userManage.S_ShowInWebSite') }}
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="form-checkbox" v-show="mailHad">
                                    <label class="control control--radio">
                                        <input type="radio" name="getPassword" value="mail" v-model="contact">{{ trans('userManage.S_SendByMail') }}
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <div class="col-md-12 plr-30">
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ trans('userManage.S_DialogUserNote') }}</label>
                            <div class="col-md-10">
                            <input type="" class="form-control" id="" placeholder="" maxlength="250" v-model="note">
                            </div>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            <!--</div>-->
                <!-- /.form  -->
            </div>

            <div class="modal-footer">
                <button v-on:click="closeDialog" class="btn btn-default pull-left">{{ trans('userManage.S_DialogCancel') }}</button>
                <button id="dialogUpBtn" v-on:click="addUserData" class="btn btn-info" :disabled="saveBtnDisability">{{ trans('userManage.S_DialogAddData') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- 1116 新增資訊提示 -->
    <div class="callout callout-info">
    <h4>会員情報設定</h4>
    <ul>
    <li>登録済みの情報は【申請情報変更】から変更が可能です。</li>
    <li>登録情報を編集後、【変更】を行ってからリンクステーションにて情報を確認後、申請済みとなります。</li>
    <li>結果登録までは申請いただいてから１~２営業日かかる場合がございます。</li>
    <li>お急ぎの場合はGETTIISサポートセンターまでご連絡ください。（ gettii-lite@e-get.jp ）。</li>
    </ul>
    </div>
    <!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
    <!--0511 調整樣式-->
    <div class="box no-border">
        <div class="box-header with-border-non">
            <h3 class="box-title">
                {{ trans('userManage.S_WebInfTittle') }}
            </h3>
        </div>
        <div class="box-body">
        <div class="">
            <div class="col-md-3">
	            {{-- STS 2021/07/17 Task 26 --}}
                <h5 class="subtitle pt-0 mt-0">{{ trans('userManage.S_GETTIISLogoDisplayStatus_Description') }}
                </h5>
                <div class="drop-image">
                    <input type="file" id="" class="dropify" data-height="300" disabled="disabled"
                        v-bind:data-default-file="sellImg" />
                </div>
                {{-- STS 2021/07/17 Task 26 Start --}}
                <div class="form-checkbox subtitle">
                    <label class="control control--checkbox ">
                        <input type="checkbox" disabled v-model="GETTIIS_logo_disp_flg" true-value='1'
                            false-value='0'>{{ trans('userManage.S_GETTIISLogoDisplayStatus') }}

                        <div class="control__indicator"></div>
                    </label>
                </div>

                {{-- STS 2021/07/17 Task 26 End --}}
	        </div>
            <div class="col-md-1"></div>
            <div class="form-horizontal col-md-8">

            <div class="form-group">
                <label for="" class="col-md-2 control-label">{{ trans('userManage.S_SellTittle') }}</label>
                <div class="col-md-10">
                <input type="" class="form-control" id="sellTittle" v-model="sellTittle" disabled>
                </div>
            </div>
            <!-- 0714 新增 -->
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <div class="form-checkbox">
                        <label class="control control--checkbox">
                            <input type="checkbox" v-model="dispFlg" disabled>{{trans('userManage.S_GETTIIS')}}
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>
            <!-- /.0714 新增 -->
            <div class="form-group">
                <label class="col-md-2 control-label"> 
                    {{ trans('userManage.S_SellUrl') }}
                </label>
                <div class="col-md-10">
                    <input type="" class="form-control" id="" v-model="sellUrl" disabled>
                </div>
            </div>            
            </div>
        </div>
        </div>

        <!-- body-footer 位置 -->
    </div>
    <!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
    <!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
    <!--0511 調整樣式-->
    <div class="box no-border">
        <!-- Block 1 -->
        <div class="box-header with-border-non" v-if="(status === 1) ? true : false">
            <h3 class="box-title">{{ trans('userManage.S_CompanyInfTitle') }}<span></span></h3>
        </div>
        <div class="box-header with-border-non" v-if="(status === 0) ? true : false">
            <h3 class="box-title">{{ trans('userManage.S_PersonalInfTitle') }}<span></span></h3>
        </div>
        <div class="box-body"  v-if="(status === 1) ? true : false">
            <h5 class="st-line">{{ trans('userManage.S_CompanyInf') }}<span></span></h5>
        <!-- form  -->
            <div class="form-horizontal">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('userManage.S_CompanyTitle') }}</label>
                        <div class="col-md-10">
                            <input type="" id="companyName" class="form-control" v-model="companyName" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <!-- 0714 新增 -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('userManage.S_CompanyTitleKana') }}</label>
                        <div class="col-md-10">
                            <input type="" class="form-control" id="" placeholder="" v-model="companyNameKana" disabled>
                        </div>
                    </div>
                    <!-- /. 0714 新增 -->
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('userManage.S_CompanyPlace') }}</label>
                        <div class="col-md-2 txt-flex-mr">
                            <div class="input-unit-left"> 〒 </div>
                            <input type="" class="form-control pl-40" id="" placeholder="" v-model="postDisplay" disabled>
                        </div>
                        <div class="col-md-8">
                            <input type="" class="form-control" id="" placeholder="" v-model="place" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
        <!-- /.form  -->
        </div>
        <div class="box-body"  v-if="(status === 0) ? true : false">
        <!-- form  -->
            <div class="form-horizontal">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            {{ trans('userManage.S_PersonalName') }}
                        </label>
                        <div class="col-md-10">
                            <input type="" id="personalName" class="form-control" v-model="personalName" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    {{-- <div class="form-group">
                        <label class="col-md-4 control-label">
                            {{ trans('userManage.S_PersonalNameKana') }}
                        </label>
                        <div class="col-md-8">
                            <input type="" class="form-control" id="" placeholder="" v-model="personalNameKana" disabled>
                        </div>
                    </div> --}}
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
        <!-- /.form  -->
        </div>
        <!-- body-footer 位置 -->
        <!-- /.Block 1-->
        <!-- Block 2 -->

        <div class="box-body"  v-if="(status === 1) ? true : false">
        <h5 class="st-line">{{ trans('userManage.S_ContactInf') }}<span></span></h5>
        <!-- form  -->
            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> {{ trans('userManage.S_ContactDeparment') }}</label>
                        <div class="col-md-8">
                            <input type="" id="contactDeparment" class="form-control" placeholder="" v-model="contactDeparment" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('userManage.S_ContactPerson') }}</label>
                        <div class="col-md-8">
                            <input type="" class="form-control" id="" placeholder="" v-model="contactName" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-4 control-label">{{ trans('userManage.S_ContactTel') }}</label>
                    <div class="col-md-8">
                        <input type="" class="form-control" placeholder="" v-model="tel" disabled>
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label class="col-md-4 control-label">{{ trans('userManage.S_ContactMail') }}</label>
                    <div class="col-md-8">
                        <input type="" class="form-control" id="" placeholder="" v-model="contactMail" disabled>
                    </div>
                </div>
                <!-- /.form-group -->
            </div>
            <!-- /.col -->
            </div>
        <!-- /.form  -->
        </div>
        <div class="box-body"  v-if="(status === 0) ? true : false">
        <!-- form  -->
            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('userManage.S_ContactTel') }}</label>
                        <div class="col-md-8">
                            <input type="" class="form-control" placeholder="" v-model="tel" disabled>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('userManage.S_ContactMail') }}</label>
                        <div class="col-md-8">
                            <input type="" class="form-control" id="" placeholder="" v-model="contactMail" disabled>
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
        <h5 class="st-line"> {{ trans('userManage.S_BankInf') }} <span></span></h5>
        <!-- form  -->
            <div class="form-horizontal">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-4 control-label">{{ trans('userManage.S_BankName') }}</label>
                    <div class="col-md-8">
                        <input type="" class="form-control" v-model="bankName" disabled>
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label class="col-md-4 control-label">{{ trans('userManage.S_BankAccountNum') }}</label>
                    <div class="col-md-3">
                        <input type="" class="form-control" id="" v-model="bankType" disabled>
                    </div>
                    <div class="col-md-5">
                        <input type="" id="bankAccountNum" class="form-control" id="" v-model="bankAccount" disabled>
                    </div>
                </div>
                <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <div class="form-group">
                <label class="col-md-4 control-label">{{ trans('userManage.S_BankLocation') }}</label>
                <div class="col-md-8">
                    <input type="" class="form-control" id="" v-model="branch" disabled>
                </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                <label class="col-md-4 control-label">{{ trans('userManage.S_BankLocationKana') }}</label>
                <div class="col-md-8">
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
    <!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
            <!-- 1202 推薦代碼新增 -->
            <div class="box no-border">
               <div class="box-header with-border-non">
                  <h3 class="box-title">{{ trans('registered.S_Introduction')}}</h3>
               </div> 
               <div class="box-body">
                   <div class="form-horizontal">
                       <div class="col-md-12">
                           <div class="form-group">
                               <label for="" class="col-md-2 control-label"></label> 
                               <div class="col-md-10">
                                   <input type="" class="form-control" id="introduction" v-model="introduction" disabled>
                               </div>
                           </div>
                       </div>  
                   </div>
               </div>
            </div>
            <!-- /.1202 推薦代碼新增 -->
    <!-- 無框線 box2 一般表格 ＋ 合計欄位 ＋ 按鈕  -->
    <!--0511 調整樣式-->
    <div class="box no-border">
        <div class="box-header with-border-non">
        <h3 class="box-title box-title-fix">{{ trans('userManage.S_SubAccManageTitle') }}</h3>
        <div class="pull-right">
            @if(session('profile_info_flg') == 2)
                <button type="button" class="btn waves-effect waves-light btn-inverse btn-header-right" v-on:click="openDialog"> 
                    {{ trans('userManage.S_UserAddBtn') }} 
                </button>
            @endif
        </div>
        </div>
        <div class="box-body">
        <!-- TABLE8 一般樣式 ＋ 雙層表頭 ＋ 按鈕 -->
        <table id="" class="table table-striped table-row row-set">
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
                <th class="text-center">{{ trans('userManage.S_Func_PF') }}</th>
                <th class="text-center">{{ trans('userManage.S_Func_EM') }}</th>
                <th class="text-center">{{ trans('userManage.S_Func_SM') }}</th>
                <th class="text-center">{{ trans('userManage.S_Func_PI') }}</th>
                <!--1224新增 MM-->
                @if(session('member_info_flg') > 0)
                <th class="text-center">{{ trans('userManage.S_Func_MM') }}</th>
                @endif
                <!--/.1224新增 MM-->
            </tr>
            </thead>
            <tbody>
                <template v-for="(user, key)  in userData">
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
                        <td class="text-center">
                            <template v-if="user.profile_info_flg === 0">
                                <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                            </template>
                            <template v-else-if="user.profile_info_flg === 1">
                               <b class="text-orange"> {!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                            </template>
                            <template v-else-if="user.profile_info_flg === 2">
                                <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                            </template>
                            <template v-else>
                                {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                            </template>
                        </td>
                        <td class="text-center">
                            <template v-if="user.event_info_flg === 0">
                               <b class="text-red"> {!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                            </template>
                            <template v-else-if="user.event_info_flg === 1">
                               <b class="text-orange">  {!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                            </template>
                            <template v-else-if="user.event_info_flg === 2">
                                <b class="text-green">{!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                            </template>
                            <template v-else>
                                {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                            </template>
                        </td>
                        <td class="text-center">
                            <template v-if="user.sales_info_flg === 0">
                               <b class="text-red"> {!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                            </template>
                            <template v-else-if="user.sales_info_flg === 1">
                               <b class="text-orange">  {!! trans('userManage.S_PermissioSymbol_Partial') !!}</b>
                            </template>
                            <template v-else-if="user.sales_info_flg === 2">
                               <b class="text-green"> {!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                            </template>
                            <template v-else>
                                {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                            </template>
                        </td>
                        <td style="text-align: center;">
                            <template v-if="user.personal_info_flg === 0">
                               <b class="text-green"> {!! trans('userManage.S_PermissioSymbol_Full') !!}</b>
                            </template>
                            <template v-else-if="user.personal_info_flg === 1">
                                <b class="text-red">{!! trans('userManage.S_PermissioSymbol_Disable') !!}</b>
                            </template>
                            <template v-else>
                                {{ trans('userManage.S_PermissioSymbol_Ignore') }}
                            </template> 
                        </td>
                        <!--1224新增 MM-->
                        @if(session('member_info_flg') > 0)
                        <td class="text-center">
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
                         <!--/.1224新增 MM-->                        
                        <td>@{{ user.deadlineDate }}</td>
                        <td width="250" class="wbreak">@{{ user.note }}</td>
                        <td width="185">
                            @if(session('profile_info_flg') == 2)
                                <a :id="'changeUserData'+key" class="btn btn-info btn-sm" v-on:click="changeInf(key)">
                                    {{ trans('userManage.S_ChangeInfBtn') }}
                                </a>
                                <a :id="'changeUserPassword'+key" class="btn btn-danger btn-sm" v-on:click="openPasswordDialog(key)">
                                    {{ trans('userManage.S_ChangePassWorld') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </template>
            </tbody>
            <!-- 表尾說明 -->
            <tfoot class="tfoot-light">
            <tr>
                <td colspan="11" class="text-left">
                    {{ trans('userManage.S_SymbolExplainTitle') }}
                <ul>
                    <li> 
                            {{ trans('userManage.S_Func_PF') }} : {{ trans('userManage.S_FuncExplain_PF') }} | 
                            {{ trans('userManage.S_Func_EM') }} : {{ trans('userManage.S_FuncExplain_EM') }} | 
                            {{ trans('userManage.S_Func_SM') }} : {{ trans('userManage.S_FuncExplain_SM') }} | 
                            {{ trans('userManage.S_Func_PI') }} : {{ trans('userManage.S_FuncExplain_PI') }} 
                            @if(session('member_info_flg') > 0)
                            |  {{ trans('userManage.S_Func_MM') }} : {{ trans('userManage.S_FuncExplain_MM') }}  
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
</div>

<script>
     
    var userManage = new Vue({
        el: '#userManage',
        data:{
            GETTIIS_disp_flg:'',
            even:'',
            showModal:'',
            showPasswordModal:'',
            status:'',
            sellTittle:'',
            dispFlg:'',
            sellImg:'',
            sellUrl:'',
            introduction:'',
            companyName:'',
            companyNameKana:'',
            placeNum:'',
            postDisplay:'',
            place:'',
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
            userData:[],
            id:'',
            name:'',
            mail:'',
            mailHad:'',
            permission:'', 
            permissionDeadline:'', 
            deadlineDate:'',
            infPermission:'',
            contact:'',
            userStatus:'',
            note:'',
            userBtnStatic:'',
            nowSelectNo:'',
            passwordSelect:'',
            newPassword:'',
            passwordChangeStatus:'',
            deleteAccount:'',
            profileInfo:'',
            eventInfo:'',
            salesInfo:'',
            memberManage:'',
            personalInfo:'',
            json:'',
            errorMsn:'',
            saveBtnDisability:false, 
            GETTIIS_logo_disp_flg: '0' // STS 2021/07/17 Task 26
        }, 
        watch: {
            mail: function (val) {
                if(val){
                    this.mailHad = true
                }else{
                    this.mailHad = false
                    this.contact = 'web'
                }
            },
            errors:{
                handler(){
                    this.cheackError()
                },
                deep: true
            },
        },
        methods: {
            cheackError:function(){
               
                if(this.errors.any()){
                    this.saveBtnDisability = true
                }else{
                    this.saveBtnDisability = false
                }
            },
            openDialog:function(){
                this.userBtnStatic = 'new'
                this.showModal = true
                this.name = null
                this.mail = null
                this.mailHad = false
                this.permission = '0'
                this.permissionDeadline = 'had'
                this.profileInfo = 1
                this.eventInfo = 1
                this.salesInfo = 1
                this.memberManage = 0
                this.personalInfo = 1
                this.contact = 'web'
                this.userStatus = '0'
                this.note = ''
                this.$validator.clean()

                document.body.style.overflowY = "hidden";
            },
            closeDialog:function(){
                this.showModal = false
                this.errorMsn = false

                document.body.style.overflowY = "scroll";
            },
            openPasswordDialog:function(id){
                this.showPasswordModal = true
                let userInf = this.userData[id]
                this.id = userInf.id
                this.name = userInf.name
                this.mail = userInf.mail

                document.body.style.overflowY = "hidden";
            },
            accountDelete:function(){
                let json = []

                json.push({
                    account: this.account
                })

                this.json =  JSON.stringify(json)

                this.$nextTick(() => {
                    document.getElementById("deleteAccount").submit();
                })
            },
            closePasswordDialog:function(){
                if( this.passwordChangeStatus !== "select"){
                    window.location.replace("/userManage");
                }
                
                this.showPasswordModal = false
                this.passwordChangeStatus = 'select'
                this.newPassword = null
                this.passwordSelect = 'select'
                this.deleteAccount = false
                this.passwordSelect = 'web'
                let userInf = JSON.parse(localStorage.getItem('userInf'))
           
                userInf.showPasswordModal = this.showPasswordModal
                userInf.passwordChangeStatus = this.passwordChangeStatus
                userInf.passwordSelect = this.passwordSelect

                document.body.style.overflowY = "scroll";
            },
            sendUserNewPassWord:function(name){
                let json = []

                json.push({
                    id: this.id,
                    account: this.account,
                    name: this.name, 
                    mail: this.mail,
                    contact: this.passwordSelect
                })

                this.json =  JSON.stringify(json)

                this.$nextTick(() => {
                    document.getElementById("formChangePw").submit();
                })
            },
            copyNewPassWord:function(){
                var TextRange = document.createRange();
                TextRange.selectNode(document.getElementById('newPassword'));
                sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(TextRange);
                document.execCommand("copy");
            },
            getPassword:function(){
                $.get("/userManage/changePassword",function(data,status){
                    console.log( data)
                });
            },
            addUserData:function(){
                let static = this.userBtnStatic 
                let userName = this.name || false
                let userId = this.userData.length
                let deadline = 'had'
                let json = []

                if(this.permissionDeadline !== 'had'){
                    deadline = this.deadlineDate
                }

                this.$validator.validateAll().then(isValid => {
                    if (!isValid) {
                      
                    } else {
                        loading.openLoading()
                        if(static === 'new'){
                            
                            json.push({
                                account: this.account,
                                id: userId,
                                name: this.name, 
                                mail: this.mail,
                                contact: this.contact,
                                permission: this.permission,
                                permissionDeadline: this.permissionDeadline ,
                                deadlineDate: deadline,
                                profileInfo: this.profileInfo,
                                eventInfo: this.eventInfo,
                                salesInfo: this.salesInfo,
                                memberManage: this.memberManage,
                                personalInfo: this.personalInfo,
                                infPermission: this.infPermission,
                                userStatus: this.userStatus,
                                note: this.note,
                                status: 'new'
                            })

                            this.json =  JSON.stringify(json)

                            localStorage.setItem('formUserData', this.json)
                            
                            this.$nextTick(() => {
                                document.getElementById("formUserData").submit();
                            })
                        }else if(static === 'change'){
                            let id = this.nowSelectNo
                           
                            this.userData[id].name = this.name 
                            this.userData[id].mail = this.mail
                            this.userData[id].permission = this.permission
                            this.userData[id].permissionDeadline = this.permissionDeadline 
                            this.userData[id].deadlineDate = deadline
                            this.userData[id].profile_info_flg = this.profileInfo
                            this.userData[id].event_info_flg = this.eventInfo
                            this.userData[id].sales_info_flg = this.salesInfo
                            this.userData[id].member_info_flg = this.memberManage
                            this.userData[id].personal_info_flg = this.personalInfo
                            this.userData[id].infPermission = this.infPermission
                            this.userData[id].userStatus = this.userStatus
                            this.userData[id].note = this.note

                            json.push({
                                arrayId: id,
                                account: this.account,
                                id: this.id,
                                name: this.name, 
                                mail: this.mail,
                                contact: this.contact,
                                permission: this.permission,
                                permissionDeadline: this.permissionDeadline ,
                                deadlineDate: deadline,
                                profileInfo: this.profileInfo,
                                eventInfo: this.eventInfo,
                                salesInfo: this.salesInfo,
                                memberManage: this.memberManage,
                                personalInfo: this.personalInfo,
                                infPermission: this.infPermission,
                                userStatus: this.userStatus,
                                note: this.note,
                                status: 'change'
                            })

                            this.json =  JSON.stringify(json)
                            
                            localStorage.setItem('formUserData', this.json)

                            this.$nextTick(() => {
                                document.getElementById("formChangeData").submit();
                            })
                        }
                    }
                })  
            },
            changeInf:function(id){
                let userInf = this.userData[id]
                let deadlineDate =  userInf.deadlineDate

                if(userInf.permissionDeadline === 'had'){
                    deadlineDate = null
                }

                this.id = userInf.id
                this.nowSelectNo = id
                this.userBtnStatic = 'change'
                this.showModal = true
                this.name = userInf.name
                this.mail = userInf.mail
                this.permission = userInf.permission
                this.permissionDeadline = userInf.permissionDeadline
                this.deadlineDate = deadlineDate
                this.infPermission = (userInf.infPermission)?userInf.infPermission:'1'
                this.profileInfo = userInf.profile_info_flg  
                this.eventInfo = userInf.event_info_flg  
                this.salesInfo = userInf.sales_info_flg 
                this.memberManage =  userInf.member_info_flg 
                this.personalInfo = userInf.personal_info_flg 
                this.contact = userInf.contact
                this.userStatus = userInf.userStatus
                this.$validator.clean()
                this.note = userInf.note
                document.body.style.overflowY = "hidden";
            },
            updateDataStatus:function(){

            }
        },
        mounted(){
            localStorage.setItem('userInf','{!! addslashes($jsonUserData)!!}')
           
            let userInf = JSON.parse(localStorage.getItem('userInf'))
            
            let status = userInf.attributes
            let data = userInf.data
            //isUpload
            this.showModal = false
            this.deleteAccount = status.deleteAccount || false
            this.showPasswordModal = status.showPasswordModal || false
            this.passwordChangeStatus = status.passwordChangeStatus || 'select'
            this.passwordSelect = status.passwordSelect || 'web'
            this.newPassword =  status.password || null
            this.userBtnStatic = status.mode || null
            document.getElementById('account').innerText = data.userData.account

            this.account =  data.userData.account
            this.contact = 'web'
            this.passwordSelect = 'web'
            this.status = data.userData.status
            this.sellTittle =  data.userData.sellTittle
            this.dispFlg =  data.userData.GETTIIS_disp_flg
            this.sellImg =   data.userData.sellImg || "{{ URL::to('/assets/images/gettiis/substitute_img.jpg') }}"
            this.sellUrl =  data.userData.sellUrl
            this.introduction =  '{{ $introduction }}'            
            this.companyName =  data.userData.companyName
            this.companyNameKana =  data.userData.companyNameKana
            this.placeNum =  data.userData.placeNum
            this.postDisplay = data.userData.postDisplay
            this.place =  data.userData.place
            this.contactDeparment =  data.userData.contactDeparment
            this.contactName =  data.userData.contactName
            this.tel =  data.userData.tel
            this.contactMail = data.userData.contactMail
            this.bankName =  data.userData.bankName 
            this.branch =  data.userData.branch
            // this.bankType =  data.userData.bankType || ""
            this.bankAccount =  data.userData.bankAccount || ""
            this.bankAccountKana =  data.userData.bankAccountKana
            this.personalName =  data.userData.companyName
            this.personalNameKana = data.userData.companyNameKana
            this.personalTel =  data.userData.personalTel
            this.personalMail =  data.userData.personalMail 
            this.userData =   data.accountInf || []
            this.GETTIIS_disp_flg = data.userData.GETTIIS_disp_flg || true
           	this.GETTIIS_logo_disp_flg = data.userData.GETTIIS_logo_disp_flg // STS 2021/07/17 Task 26
            if( data.userData.bankType == 1) {
                this.bankType = '{{ trans('userManage.S_BankAccountType01') }}'
            } else if ( data.userData.bankType == 2) {
                this.bankType = '{{ trans('userManage.S_BankAccountType02') }}'
            } else {
                this.bankType = ''
            }
            
            let getDate = new Date()
            let todayDate = getDate.getFullYear()+ "/" + (getDate.getMonth()+1) + "/" + getDate.getDate() 
           
            @if(count($errors) > 0)
                let formUserData = JSON.parse(localStorage.getItem('formUserData'))

                this.errorMsn = true
                this.showModal = true
                this.id = formUserData[0].id
                this.userBtnStatic = formUserData[0].status
                this.account = formUserData[0].account
                this.name = formUserData[0].name
                this.mail = formUserData[0].mail
                this.contact = formUserData[0].contact
                this.permission = formUserData[0].permission
                this.permissionDeadline = formUserData[0].permissionDeadline
                this.profileInfo = formUserData[0].profileInfo
                this.eventInfo = formUserData[0].eventInfo
                this.salesInfo = formUserData[0].salesInfo
                this.memberManage = formUserData[0].memberManage
                this.personalInfo = formUserData[0].personalInfo
                this.infPermission = formUserData[0].infPermission
                this.userStatus = formUserData[0].userStatus
                this.note = formUserData[0].note

                if(formUserData[0].status == "change"){
                    this.nowSelectNo =  formUserData[0].arrayId
                }

                if(formUserData[0].deadlineDate !== 'had'){
                    this.deadlineDate = formUserData[0].deadlineDate
                }
            @endif

            this.$nextTick(() => {

                $('.daterangeSingle').daterangepicker({ 
                    "locale": {
                        "format": "YYYY/MM/DD"
                    },
                    singleDatePicker: true,
                    autoUpdateInput: false
                })

                $('#deadlineDate').on('apply.daterangepicker', function(ev, picker) {
                    userManage.deadlineDate = picker.startDate.format('YYYY/MM/DD')
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
    });


</script>

@stop

