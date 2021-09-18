@extends('adminlte::page')

@section('adminlte_css')
    <link href="{{ asset('css/Login.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/font-awesome/css/all.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    <script src="{{ asset('js/vee-validate-dictionary.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="https://www.google.com/recaptcha/api.js?render={!! \Config::get('constant.googlr_recaptcha_data')['site_key'] !!}"></script>
    @yield('css')
@stop
@section('body')
<section dusk="login_page" class="login-section"><div id="myCaptcha"></div> 
    <div id="loginPage"  class="login-container">
        <div class="login-wrap">
            <div class="logo-wrap">
                <div class="logo-wrap-image"><img src="{{ URL::to('/assets/images/logo/logo.png') }}" alt="IMG"></div>
                <div class=" sub-txt">{{ trans('basisInf.S_LoginPage') }}</div>
                @if($errors->any())
                    <!-- 錯誤訊息提示 -->
                        <div dusk="errors" class="tip-box">
                            <div class="tip-error">
                                <i class="fas fa-exclamation-circle fa-lg"></i> {{ $errors->first() }}
                            </div>
                        </div>
                    <!-- /.錯誤訊息提示 -->
                @endif
            </div>
            <!------------- [ 0909調整 ] ----------------------------------------------------
            1. 台灣區左邊為圖片 請使用 login-intro data-img 
            2. 其他區左邊為文字＋註冊按鈕 請使用 login-intro data-tilt 
            --------------------------------------------------------------------------------->
            <div class="login-intro data-img">
                <!-- 台灣區 -->
                <img src="{{ URL::to('/assets/images/login/img-login.png') }}" alt="IMG">
                <!-- /.台灣區 -->

                <!-- 其他區 -->
                <!--<span class="login-form-title">
                    {{ trans('basisInf.S_UserRegistered') }}
                </span>
                <p>
                    {{ trans('basisinf.S_ApplyDescription') }}
                </p>
                <div class="login-container-form-btn">
                    <a  href="/register" class="btn-application">
                        {{ trans('basisInf.S_Apply') }}
                    </a>
                </div>-->
                <!-- /.其他區 -->  
            </div>
            <!---------------------------------  /.0909調整  ----------------------------------->
            <form id="formLogin" method="POST" action="/login" autocomplete="off">
                {{ csrf_field() }}
                <input type="hidden" name="json" v-model="json">
                <span class="login-form-title">
                    {{ trans('basisInf.S_UserLogin') }}
                </span>

                <div class="input-wrap validate-input" data-validate="Valid userID is required">
                    <input id="user_id" dusk="user_id" type="text" class="input" name="company-id" placeholder="{{ trans('basisInf.S_CompanyId') }}"  onkeypress="nextInput(event, 'account_id')" v-model="companyId" v-validate="'required'" autocomplete="off">
                    <span class="focus-input"></span>
                    <span class="symbol-input">
                        <i class="fas fa-building"></i>
                    </span>
                    <span dusk="company_id" v-show="errors.has('company-id')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-id') }}</span> 
                    
                </div>
                <div class="input-wrap validate-input" data-validate="Valid userID is required">
                
                    <input id="account_id" dusk="account_id" type="text" class="input" name="user-id"  placeholder="{{ trans('basisInf.S_UserId') }}"  onkeypress="nextInput(event, 'account_password')" v-model="userId" v-validate="'required'" autocomplete="off">
                    <span class="focus-input"></span>
                    <span class="symbol-input">
                        <i class="fas fa-user"></i>
                    </span>
                    <span dusk="user_id" v-show="errors.has('user-id')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-id') }}</span> 
                    
                </div>
                <div class="input-wrap validate-input" data-validate="Password is required">
                    <input id="account_password" dusk="account_password" type="text" onfocus="this.type='password'" class="input" name="user-password" placeholder="{{ trans('basisInf.S_UserPassword') }}"  onkeypress="enterLogin(event)" v-model="userPassword" v-validate="'required'" autocomplete="off">
                    <span class="focus-input"></span>
                    <span class="symbol-input">
                        <i class="fas fa-lock"></i>
                    </span>
                    <span dusk="user_password" v-show="errors.has('user-password')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-password') }}</span>
                </div>
                <input type="hidden" name="recaptcha" id="recaptcha"/>
                <!--reCaptcha驗證區-->
              <!--  <div class="captcha-box"><img src="dist/img/newCaptchaAnchor.gif" alt="img" style="width: 200px;">
                </div>-->

                <!--/.reCaptcha驗證區-->
                <div class="login-container-form-btn">
                    <a dusk="login_button"  class="btn-login" id="buttonLogin" v-on:click="login()">
                        {{ trans('basisInf.S_Login') }}
                    </a>
                </div>
                <div class="text-center tip-text">
                    <a id="buttonLosePassword" dusk="reminder_button" href="/accountReminder" class="tip-link">
                        {{ trans('basisInf.S_ForgetPassword') }}
                        <i class="far fa-arrow-alt-circle-right"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{!! \Config::get("constant.googlr_recaptcha_data")["site_key"] !!}', {action: 'login'}).then(function(token) {
            document.getElementById('recaptcha').value = token
        });
    });

    function nextInput(e, position){
        if (e.keyCode == 13) {
            document.getElementById(position).focus()
        }
    }

    function enterLogin(e) {
        if (e.keyCode == 13) {
            loginPage.login()
        }
    }

    Vue.config.devtools = true;
    var loginPage = new Vue({
        el: '#loginPage',
        data:{
            companyId:'',
            userId:'',
            userPassword:'',
            json:'',
        },
        methods:{
            login:function(){
                this.$validator.validateAll().then(isValid => {
                    if (!isValid) {
                       
                    } else {
                        let json = []

                        json.push({
                            companyId: this.companyId,
                            userId: this.userId,
                            userPassword: this.userPassword,
                        })

                        this.json =  JSON.stringify(json)
                        localStorage.setItem('loginData' ,this.json)

                        this.$nextTick(() => {
                            document.getElementById("formLogin").submit();
                        })
                    }
                }) 
            } 
        },
    })
</script>
@stop

@section('adminlte_js')

@stop
