<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @if (App::environment('production') && !empty(\Config::get('constant.GTMCode')))
        <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{\Config::get('constant.GTMCode')}}');
        </script>
        <!-- End Google Tag Manager -->
    @endif
    <title>WEBチケット販売サービス『Gettii Lite』</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="imagetoolbar" content="false">

    <link rel="alternate" href="https://lite.gettiis.jp/" hreflang="ja" />
    <!-- animate -->
    <link rel="stylesheet" href="css/animate.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- font-awesome -->
    <link rel="stylesheet" href="css/font-awesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/font-awesome/css/all.css">
    <!-- fullpage -->
    <link rel="stylesheet" href="css/jquery.fullPage.css">
    <!-- custom -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="{{ asset('css/Main.min.css') }}">
    <!-- google font -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700,800' rel='stylesheet' type='text/css'>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    <script src="{{ asset('js/vee-validate-dictionary.js') }}"></script>
</head>

<body>
    @if (App::environment('production') && !empty(\Config::get('constant.GTMCode')))
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{\Config::get('constant.GTMCode')}}" height="0" width="0" style="display:none;visibility:hidden">
            </iframe>
        </noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    <!-- logo section  -->
    <header>
        {{-- {{\Config::get('constant.GTMCode')}} --}}
        <div class="navbar">
            <div class="logo">
                <a href="#"><img src="assets/images/home/logo.png" width="150px"></a>
            </div>  
        </div>
        <!-- 0720 新增註冊入口 -->
        <div class="register">
            <a target="_blank" dusk="register" href="register" >{{ trans('basisInf.S_Apply') }}</a>
        </div>
        <!-- /.0720 新增註冊入口 -->
        <div class="login">
            <a dusk="login" href="login">{{trans('home.S_LoginApplication')}}</a>
        </div>
    </header>
    <!-- /.logo section  -->

    <!-- ======================================================== -->

    <!--  #fullpage -->
    <div id="fullpage">
        <!-- start home -->
        <div id="home" class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 text-left">
                        <h2 class="wow fadeIn" data-wow-delay="0.6s">{{trans('home.S_IndexNotice1')}}</h2>
                        <h1 class="wow fadeIn" data-wow-delay="0.8s">{{trans('home.S_IndexNotice2')}}</h1>
                        <p class="wow fadeIn" data-wow-delay="1s">{{trans('home.S_IndexNotice3')}}</p>
                        <a href="#contact" class="btn btn-default smoothScroll wow fadeInUp" data-wow-delay="1.2s">{{ trans('home.S_Query') }}</a>
                    </div>
                    <div class="col-md-7"></div>
                </div>
            </div>
        </div>
        <!-- end home -->

        <!-- start intro -->
        <div id="intro" class="section">
            <div class="container">
                <div class="">
                    <div class="content-width">
                        <div class="slideshow">
                            <!-- Slideshow Items -->
                            <div class="slideshow-items">
                                <div class="item">
                                    <div class="item-image-container">
                                        <img class="item-image" src="assets/images/home/intro-img01.png" />
                                    </div>
                                    <!-- Staggered Header Elements -->
                                    <div class="item-header">
                                        <span class="vertical-part"><b>{{trans('home.S_IndexNotice4')}}</b></span>
                                    </div>
                                    <!-- Staggered Description Elements -->
                                    <div class="item-description">
                                        <span class="vertical-part"><b>{!!trans('home.S_IndexNotice5')!!}</b></span>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="item-image-container">
                                        <img class="item-image" src="assets/images/home/intro-img02.png" />
                                    </div>
                                    <!-- Staggered Header Elements -->
                                    <div class="item-header">
                                        <span class="vertical-part"><b>{{trans('home.S_IndexNotice6')}}</b></span>
                                    </div>
                                    <!-- Staggered Description Elements -->
                                    <div class="item-description">
                                        <span class="vertical-part"><b>{{trans('home.S_IndexNotice7')}}</b></span>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="item-image-container">
                                        <img class="item-image" src="assets/images/home/intro-img03.png" />
                                    </div>
                                    <!-- Staggered Header Elements -->
                                    <div class="item-header">
                                        <span class="vertical-part"><b>{{trans('home.S_IndexNotice8')}}</b></span>
                                    </div>
                                    <!-- Staggered Description Elements -->
                                    <div class="item-description">
                                        <span class="vertical-part"><b>{{trans('home.S_IndexNotice9')}}</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="controls">
                                <ul>
                                    <li class="control" data-index="0">1</li>
                                    <li class="control" data-index="1">2</li>
                                    <li class="control" data-index="2">3</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end intro -->


        <!-- start funtion -->
        <div id="funtion" class="section">
            <div class="container">
                <!-- content -->
                <div class="main">
                    <div class="cd-slider">
                        <ul>
                            <li>
                                <div class="image" style="background-image:url(assets/images/home/funtion-img01.png);"></div>
                                <div class="content">
                                    <h2>{{trans('home.S_IndexNotice10')}}</h2>
                                    <p>{{trans('home.S_IndexNotice11')}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="image" style="background-image:url(assets/images/home/funtion-img02.png);"></div>
                                <div class="content">
                                    <h2>{{trans('home.S_IndexNotice12')}}</h2>
                                    <p>{!!trans('home.S_IndexNotice13')!!}</p>
                                </div>
                            </li>
                            <li>
                                <div class="image" style="background-image:url(assets/images/home/funtion-img03.jpg);"></div>
                                <div class="content">
                                    <h2>{{trans('home.S_IndexNotice14')}}</h2>
                                    <p>{!!trans('home.S_IndexNotice15')!!}
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="image" style="background-image:url(assets/images/home/funtion-img04.jpg);"></div>
                                <div class="content">
                                    <h2>{{trans('home.S_IndexNotice16')}}</h2>
                                    <p>{{trans('home.S_IndexNotice17')}}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--/.cd-slider-->

                </div>
                <!-- /.content -->
            </div>
        </div>
        <!-- end funtion -->


        <!--user-->
        <div id="user" class="section">
            <div class="container">
                <div class="row">
                    <div class=" col-md-12 user-content">

                        <!--User user-block-->
                        <div class="col-md-4 user-block classic">
                            <img src="assets/images/home/icon-user01.png" alt="User" />
                            <h5>{{trans('home.S_IndexNotice18')}}</h5>
                            <p>{{trans('home.S_IndexNotice19')}}</p>
                        </div>
                        <!-- End of user-block-->

                        <!--User user-block-->
                        <div class="col-md-4 user-block classic">
                            <img src="assets/images/home/icon-user02.png" alt="User" />
                            <h5>{{trans('home.S_IndexNotice20')}}</h5>
                            <p>{{trans('home.S_IndexNotice21')}}</p>
                        </div>
                        <!-- End of user-block-->

                        <!--User user-block-->
                        <div class="col-md-4 user-block classic">
                            <img src="assets/images/home/icon-user03.png" alt="User" />
                            <h5>{{trans('home.S_IndexNotice22')}}</h5>
                            <p>{{trans('home.S_IndexNotice23')}}</p>
                        </div>
                        <!-- End of user-block-->
                    </div>
                </div>
            </div>
        </div>
        <!--End of user-->

        <!-- ================================================== -->

        <!-- start contact -->
        <div id="contact" class="section">
            <div id='mail-to' class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-5 wow fadeInUp block" data-wow-delay="0.6s">
                        <address>
							<p class="contact-title">{{trans('home.S_Contact')}}</p>
							<p><i class="fas fa-building"></i>{{trans('home.S_CompanyName')}}</p>
							<p><i class="fas fa-phone-alt"></i> {{trans('home.S_Phone')}}</p>
							<p><i class="fas fa-fax"></i> {{trans('home.S_Fax')}}</p>
							<p><i class="fas fa-envelope"></i> {{config('constant.email')}} </p>
							<p><i class="fas fa-map-marker-alt"></i> {{trans('home.S_Location')}}</p>
			             </address>

			             <!-- <p class="faqs"><a dusk="faqs" href="faqs.html">{{trans('home.S_FAQ')}}</a> </p>  -->
                        <a type="button" dusk="faqs" href="faqs.html"><p class="faqs">{{trans('home.S_FAQ')}}</p></a>

                    </div>
                    
                    <div class="col-lg-7 col-md-7 col-xs-12 wow fadeInUp" data-wow-delay="0.6s">
                    
                    <a v-show='false'  id='question' :href="action"></a>

                    <input name="name" type="text" class="form-control" id="name" placeholder="{{trans('home.S_Name')}}" v-model='name'>
                    <input name="company" type="text" class="form-control" id="company" placeholder="{{trans('home.S_Company')}}" v-model='company'>
                    <input name="phone" type="phone" v-validate="'numeric|max:13'" class="form-control" id="phone" placeholder="{{trans('home.S_CompanyPhone')}}" v-model='phone'>
                    <span v-show="errors.has('phone')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('phone') }}</span>
                    <input id="email" type="email" v-validate="'email|max:200'"  maxlength="200"  name="email" class="form-control" placeholder="{{ trans('events.S_eventContactEmailPlaceholder') }}" v-model="email">
                    <span v-show="errors.has('email')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('email') }}</span>
                    <textarea name="message" rows="5" class="form-control" id="message" placeholder="{{trans('home.S_Message')}}" v-model='message'></textarea>
                    <!-- 按鈕 -->
                    <div class="btn-box">
                        <button class="btn btn-info w-100" value="{{trans('home.S_Send')}}" @click='send'>{{trans('home.S_Send')}}</button>
                    </div>
                            <!-- checkbox -->
                            <!--<h5><b>*</b>預計活動場次</h5>
                            <div class="option-other">
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1"
                                        > 1場
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> 2場
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3"> 3場以上
                                </label>
                                <label class="checkbox-inline flex-center">
                                    <input type="radio" name="optionsRadios" id="optionsRadios4" value="option4"> 其他
                                    <input name="text" type="text" class="form-control" id="othrt" placeholder="請輸入"
                                        style="width: 150px;margin-left: 10px;">
                                </label>
                            </div>-->
                            <!-- /.checkbox -->
                            <!-- checkbox -->
                            <!--<h5><b>*</b>活動類型 （可複選）</h5>
                            <div class="option-other">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="optionscheckbox" id="optionscheckbox1" value="option1">
                                    展覽
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="optionscheckbox" id="optionscheckbox2" value="option2">
                                    講座
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="optionscheckbox" id="optionscheckbox3" value="option3">
                                    表演
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="optionscheckbox" id="optionscheckbox4" value="option4">
                                    演唱會
                                </label>
                                <label class="checkbox-inline flex-center">
                                    <input type="checkbox" name="optionscheckbox" id="optionscheckbox5" value="option5">
                                    其他
                                    <input name="text" type="text" class="form-control" id="othrt" placeholder="請輸入"
                                        style="width: 150px;margin-left: 10px;">
                                </label>
                            </div>-->
                            <!-- /.checkbox -->

                            <!-- checkbox -->
                            <!--<h5><b>*</b>預計開始使用日期</h5>
                            <div class="option-other">
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios3" id="optionsRadios1" value="option1"> 1個月內
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios3" id="optionsRadios2" value="option2"> ３個月內
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios3" id="optionsRadios3" value="option3"> 6個月內
                                </label>
                                <label class="checkbox-inline flex-center">
                                    <input type="radio" name="optionsRadios3" id="optionsRadios4" value="option4"> 其他
                                    <input name="text" type="text" class="form-control" id="othrt" placeholder="請輸入"
                                        style="width: 150px;margin-left: 10px;">
                                </label>
                            </div>-->
                            <!-- /.checkbox -->

                            <!-- checkbox -->
                            <!--<h5><b>*</b>票券類型</h5>
                            <div class="option-other">
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios4" id="optionsRadios1" value="option1"
                                        > 單次使用入場票
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios4" id="optionsRadios2" value="option2"> 多次入場
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="optionsRadios4" id="optionsRadios3" value="option3"> 優惠券
                                </label>
                                <label class="checkbox-inline flex-center">
                                    <input type="radio" name="optionsRadios4" id="optionsRadios4" value="option4"> 其他
                                    <input name="text" type="text" class="form-control" id="othrt" placeholder="請輸入"
                                        style="width: 150px;margin-left: 10px;">
                                </label>
                            </div>-->
                            <!-- /.checkbox -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end contact -->

        <!-- start footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 wow fadeIn" data-wow-delay="0.9s">
                        <p>Copyright © 2019 Gettii Lite. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->

    </div>
    <!-- / #fullpage -->

    <!-- ======================================================== -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- fullpage -->
    <script src="js/jquery.fullPage.js"></script>
    <!-- smoothScroll -->
    <script src="js/smoothscroll.js"></script>
    <!-- wow -->
    <script src="js/wow.min.js"></script>
    <!-- text rotater -->
    <script src="js/jquery.simple-text-rotator.js"></script>
    <!-- custom -->
    <script src="js/slider.js"></script>
    <script src="js/funtion.js"></script>
    <script src="js/custom.js"></script>

    <script>
    VeeValidate.Validator.setLocale('{{ \App::getLocale() }}')

    var app = new Vue({
        el: '#mail-to',
        data:{
            name:'',
            company:'',
            phone:'',
            email:'',
            message:'',
            action:''
        },
        methods:{
            send:function(){
                this.$validator.validateAll().then(isValid => {
                    if (!isValid) {
                       
                    } else {

                        this.action = `mailto:{{config('constant.email')}}?subject={{urlencode(trans('home.S_Subject'))}}&body={{urlencode(trans('home.S_Body'))}}%0A%0A
                                       1.{{urlencode(trans('home.S_Name'))}}%20:%20${encodeURI(this.name)}%0A%0A
                                       2.{{urlencode(trans('home.S_Company'))}}%20:%20${encodeURI(this.company)}%0A%0A
                                       3.{{urlencode(trans('home.S_CompanyPhone'))}}%20:%20${encodeURI(this.phone)}%0A%0A
                                       4.{{urlencode(trans('home.S_CompanyMail'))}}%20:%20${encodeURI(this.email)}%0A%0A
                                       5.{{urlencode(trans('home.S_Message'))}}%20:%20${encodeURI(this.message)}%0A%0A
                                       `.replace(/\s*/g,"")

                        this.$nextTick(() => {
                            document.getElementById("question").click();
                        })
                    }
                }) 
            } 
        },
    })

    </script>
</body>

</html>