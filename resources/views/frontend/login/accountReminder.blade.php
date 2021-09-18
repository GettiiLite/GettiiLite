@extends('adminlte::page')

@section('adminlte_css')
    
    <link href="{{ asset('css/font-awesome/css/all.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/Login.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/Main.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    <script src="{{ asset('js/vee-validate-dictionary.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="https://www.google.com/recaptcha/api.js?render={!! \Config::get('constant.googlr_recaptcha_data')['site_key'] !!}"></script>
    @yield('css')
@stop
@if($errors->any())
    <h1>{{ $errors->first() }}</h1>
@endif
@section('body')
<section id="accountReminder" class="login-section">
    <form id="formAccountReminder" method="POST" action="/accountReminder">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="recaptcha" id="recaptcha"/>
    </form>
    <div class="login-container">
        <div class="login-wrap">
            <div class="logo-wrap logo-wrap-fix">
                <div class="logo-wrap-image"><img  src="{{ URL::to('/assets/images/logo/logo.png') }}" alt="IMG"></div>
                <div class="sub-txt">{{ trans('basisInf.S_ApplyNewPassword') }}</div>
            </div>
            <!--  box3 註冊頁面統一樣式 -->
            <div class="box box-login-bg">
                <!-- Block 1 -->
                <div class="box-header with-border">
                    <h3 class="box-title"></i> 
                        {!! trans('basisInf.S_WarnTitle') !!}
                    </h3>
                    <h3 class="box-title"> 
                        {{ trans('basisInf.S_WarnSubTitle') }}
                    </h3>
                </div>
                <div class="box-body">
                    <!-- form  -->
                    <!-- Tip -->
                    <div class="callout callout-login-warning">
                        <span class="i-warning"><i class="fas fa-exclamation-triangle fa-2x text-red"></i></i></span>
                        <p>
                            {!! trans('basisInf.S_NoteMSG') !!}
                        </p>
                    </div>
                    <!-- /.Tip -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('basisInf.S_CompanyId') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="company-id" placeholder="{{ trans('basisInf.S_CompanyIdPlaceholder') }}" v-model="companyId" v-validate="'required'">
                                    <span dusk="companyId" v-show="errors.has('company-id')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-id') }}</span>  
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('basisInf.S_UserMail') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="user-mail"  placeholder="{{ trans('basisInf.S_MailPlaceholder') }}" v-model="mail"   v-validate="'required|email'">
                                    <span dusk="userMail" v-show="errors.has('user-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-mail') }}</span> 
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('basisInf.S_Tel') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="user-tel" placeholder="{{ trans('basisInf.S_TelPlaceholder') }}" v-model="tel" v-validate="'required|numeric'">
                                    <span dusk="userTel" v-show="errors.has('user-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-tel') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <!-- /.col -->
                        </div>
                        <!-- /.form  -->
                    </div>
                    <!-- body-footer 位置 -->
                </div>
                <!-- /.Block 1-->
            </div>
            <!-- /.box3 註冊頁面統一樣式 -->
            <div class="login-container-form-btn">
                <button id="buttonApply" v-on:click="sendApply()" class="btn-login btn-inverse"> 
                    {{ trans('basis.S_Seacrch') }}
                </button>
            </div>

        </div>
    </div>
</section>
<script>
    Vue.config.devtools = true;
    var accountReminder = new Vue({
        el: '#accountReminder',
        data:{
            companyId:'',
            mail:'',
            tel:'',
            json:'',
        },
        methods:{
            sendApply:function(){
                this.$validator.validateAll().then(isValid => {
                    if (!isValid) {
                       
                    } else {
                        let json = []

                        json.push({
                            companyId: this.companyId,
                            mail: this.mail,
                            tel: this.tel,
                        })

                        this.json =  JSON.stringify(json)

                        this.$nextTick(() => {
                            document.getElementById("formAccountReminder").submit();
                        })
                    }
                }) 
            } 
        },
    })
    grecaptcha.ready(function() {
        grecaptcha.execute('{!! \Config::get("constant.googlr_recaptcha_data")["site_key"] !!}', {action: 'accountReminder'}).then(function(token) {
            document.getElementById('recaptcha').value = token
        });
    });
</script>
@stop

@section('adminlte_js')

@stop
