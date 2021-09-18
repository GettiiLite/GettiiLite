@extends('adminlte::page')

@section('adminlte_css')
    <link href="{{ asset('css/Login.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/font-awesome/css/all.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/Main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropify.min.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    <script src="{{ asset('js/vee-validate-dictionary.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="https://www.google.com/recaptcha/api.js?render={!! \Config::get('constant.googlr_recaptcha_data')['site_key'] !!}"></script>
    @if((\App::getLocale() == "ja" ))
        <script src="{{ asset('js/jp-city-selector.min.js') }}"></script>
    @else
        <script src="{{ asset('js/tw-city-selector.min.js') }}"></script>
    @endif
    @yield('css')
@stop

@section('body')
<section  id="registeredPage" class="login-section">
    <form id="formRegister" method="POST" action="/register">
        {{ csrf_field() }}
        <input type="hidden" name="json" v-model="json">
        <input type="hidden" name="recaptcha" id="recaptcha"/>
    </form>
    <div class="login-container">
        <div class="login-wrap">
            <div class="logo-wrap logo-wrap-fix">
                <div class="logo-wrap-image"><img src="{{ URL::to('/assets/images/logo/logo.png') }}" alt="IMG"></div>
                <div class="sub-txt">
                    {{ trans('registered.S_Apply') }}
                </div>
            </div>
            <!--  box3 註冊頁面統一樣式  -->
            <div class="box box-login-bg">
                <!-- Block 1 -->
                <div class="box-header with-border">
                    <div class="small-i pl-10"><div class="tip"><span><i class="fas fa-info fa-1x fa__thead"></i> <small> 会員IDはご利用単位、ユーザーIDは担当者単位で、ログインにご利用できる任意のコードです。<br>&ensp;&ensp;&ensp;お客様指定の任意の英数文字を設定できます。</small></span></div></div>
                </div>
                <div class="box-body">
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_ApplyId') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="16" name="apply-id" v-model="applyId"  v-validate="'required|alpha_num|min:6|max:16'" placeholder="{{ trans('registered.S_ApplyIdPlaceholder')}}">
                                    <span dusk="applyId" v-show="errors.has('apply-id')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('apply-id') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_adminName') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="16" name="admin-name" v-model="adminName" v-validate="'required|alpha_num|min:6|max:16'" placeholder="{{ trans('registered.S_adminNamePlaceholder')}}">
                                    <span dusk="adminName" v-show="errors.has('admin-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('admin-name') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- /.box-body -->
                <!-- /.Block 1-->
            </div>
            <!-- /.box3 註冊頁面統一樣式 -->
            <!--  box3 註冊頁面統一樣式  -->
            <div class="box box-login-bg">
                <!-- Block 1 -->
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('registered.S_Identity') }}
                        <div class="radio-row control-label-radio">
                            <label class="control control--radio">
                                <input type="radio"  name="identity" value="personal" v-model="identity"/>
                                <div class="control__indicator"></div>
                                {{ trans('registered.S_Personal') }}
                            </label>
                            <label class="control control--radio">
                                <input type="radio"  name="identity" value="company" v-model="identity"/>
                                <div class="control__indicator"></div>
                                {{ trans('registered.S_Company') }}
                            </label>
                        </div>
                    </h3>
                </div>
                <!-- box-body  聯絡資訊 個人-->
                <div class="box-body" dusk="personalInf" v-show="(identity == 'personal') ? true : false">
                    <h5 class="st-line">{{ trans('registered.S_UserInf') }}<span></span></h5>
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_UserName') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9"  v-if="(identity == 'personal') ? true : false">
                                    <input type="text" maxlength="80" class="form-control" name="user-name"  v-model="userName" v-validate="'required'" placeholder="{{ trans('registered.S_UserNamePlaceholder')}}">
                                    <span dusk="userName" v-show="errors.has('user-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"> {{ trans('registered.S_UserNameKana') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9"  v-if="(identity == 'personal') ? true : false">
                                    <input type="text" maxlength="160" class="form-control" name="user-name-kana" v-model="userNameKana"　v-validate="'required||regex:^([ァ-ヴ][ァ-ヴー・]*)$'" placeholder="{{ trans('registered.S_UserNamePlaceholderKana')}}">
                                    <span dusk="companyNameKana" v-show="errors.has('user-name-kana')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-name-kana') }}</span>

                                </div>
                            </div>

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- /.box-body 聯絡資訊 個人-->
                <div class="box-body" dusk="personalContact" v-show="(identity == 'personal') ? true : false">
                    <h5 class="st-line">{{ trans('registered.S_CompanyContact') }}<span></span></h5>
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_UserTel') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9"  v-if="(identity == 'personal') ? true : false">
                                    <input type="text" maxlength="20" class="form-control" name="user-tel"  v-model="userTel" v-validate="'required|tel_format|max:20'" placeholder="{{ trans('registered.S_UserTelPlaceholder')}}" >
                                    <span dusk="userTel" v-show="errors.has('user-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-tel') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group"  v-if="(identity == 'personal') ? true : false">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_ContactMail') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" maxlength="100" class="form-control" name="user-mail" v-model="userMail" v-validate="'required|email'" placeholder="{{ trans('registered.S_UserMailPlaceholder')}}">
                                    <span dusk="userMail" v-show="errors.has('user-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('user-mail') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- /.box-body 個人-->
                <!-- box-body  聯絡資訊 法人-->
                <div class="box-body" dusk="campanyInf" v-show="(identity == 'company') ? true : false">
                    <h5 class="st-line">{{ trans('registered.S_CompanyInf') }}<span></span></h5>
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12 city-selector-set">
                            <div class="form-group" v-if="(identity == 'company') ? true : false">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_CompanyName') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" maxlength="80" class="form-control" name="company-name" v-model="companyName" v-validate="'required'"　placeholder="{{ trans('registered.S_CompanyNamePlaceholder')}}">
                                    <span dusk="companyName" v-show="errors.has('company-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-name') }}</span>
                                </div>
                            </div>
                            <div class="form-group" v-if="(identity == 'company') ? true : false">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_CompanyNameKana') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" maxlength="160" class="form-control" name="company-name-kana" v-model="companyNameKana" v-validate="'required||regex:^([ァ-ヴ][ァ-ヴー・]*)$'"　placeholder="{{ trans('registered.S_CompanyNamePlaceholderKana')}}">
                                    <span dusk="companyNameKana" v-show="errors.has('company-name-kana')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('company-name-kana') }}</span>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-sm-offset-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{ trans('events.S_eventHallLocationTitle') }}</label>
                                        <div class="col-md-8">
                                            <select class="form-control select2 county" style="width: 100%;" v-model="prefecture">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{ trans('events.S_eventHallSublocationTitle') }}</label>
                                        <div class="col-md-8">
                                            <select class="form-control select2 district" style="width: 100%;" v-model="city">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                            <div class="col-sm-offset-3">
                            <div class="">
                                <div class="col-md-4">
                                    <div class="txt-flex-mr">
                                     <div class="input-unit-left"> 〒 </div>
                                    <input type="" id="postCode" class="form-control postCode" disabled v-show='false'>
                                    <input type="" class="form-control pl-40" v-model='postDisplay' maxlength="8" placeholder="{{ trans('registered.S_PostCodePlaceholder')}}">
                                    </div>
                                    <span v-show="error.postDisplay.status" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ error.postDisplay.msn }}</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="" class="form-control" maxlength="100" id="" v-model="placeDetailed"　placeholder="{{ trans('registered.S_AddressPlaceholder')}}">
                                </div>
                            </div>
                            </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- /.box-body 聯絡資訊 法人-->
                <div class="box-body" dusk="campanyInf" v-if="(identity == 'company') ? true : false"  >
                    <h5 class="st-line">{{ trans('registered.S_CompanyContact') }}<span></span></h5>
                    <!-- form  -->
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_ContactDepartment') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" maxlength="80" class="form-control" name="contact-department"  v-model="contactDepartment" placeholder="{{ trans('registered.S_ContactDepartmentPlaceholder')}}">
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_ContactPerson') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="80" name="contact-person" v-model="contactPerson" v-validate="'required'" placeholder="{{ trans('registered.S_UserNamePlaceholder')}}">
                                    <span v-show="errors.has('contact-person')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-person') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_ContactTel') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="20" name="contact-tel"  v-model="contactTel" v-validate="'required|tel_format'" placeholder="{{ trans('registered.S_UserTelPlaceholder')}}">
                                    <span dusk="contactTel" v-show="errors.has('contact-tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-tel') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('registered.S_ContactMail') }}<b>{{ trans('registered.S_RequiredMark') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="100" name="contact-mail" v-model="contactMail"  v-validate="'required|email'" placeholder="{{ trans('registered.S_UserMailPlaceholder')}}">
                                    <span dusk="contactMail" v-show="errors.has('contact-mail')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('contact-mail') }}</span>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.form  -->
                </div>
                <!-- /.box-body  法人-->
                <!-- /.Block 1-->   
            </div>
            <!-- /.box3 註冊頁面統一樣式 -->
            <!-- 1202 新增推薦代碼 -->
            <div class="box box-login-bg">
                <!-- /.1201 新增 --> 
                <div class="box-body" >
                <div class="row form-horizontal">
                   <label class="col-md-3 control-label">{{ trans('registered.S_Introduction')}}</label> 
                   <div class="col-md-9">
                   <input type="text" class="form-control" maxlength="1000" name="intoroduction" v-model="introduction" placeholder="{{ trans('registered.S_IntroductionPlaceholder')}}">
                </div>
                </div>
                </div>
                <!-- /.1201 新增 --> 
                </div>
            <!-- /.1202 新增推薦代碼 -->
            <!-- box3 註冊頁面統一樣式 -->
            <div class="box box-login-bg">
                <!-- Block 1 -->
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('registered.S_ServiceAccept') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <!-- 安全須知資訊區 -->
                        <div class="row">
                            <div class="document-section">
                                <div class="document-content">
                                    <div class="document-content_text">
                                        <h4>ご利用規約</h4>
                                        <ol class="document-info">
                                            <b>第１条（定義）</b>
                                            <li>本規約は、株式会社リンクステーション（以下「当社」といいます。）が提供する本サイトにおいて、イベント主催者等がチケットを販売することを可能にするサービス「Gettii Lite」（以下「本サービス」といいます。）の利用者に適用される利用規約（以下「本規約」といいます。）です。</li>
                                            <li>「本サイト」とは、当社がイベント主催者等の公演・興行その他のイベント（以下「イベント」といいます。）の情報・チケット情報等（以下「チケット等情報」といいます。）を連携・集約し、イベント主催者等が売主となって、利用者にチケット等を販売することができる機能及びこれに付随する各種機能を有する、総合案内サイト「GETTIIS（ゲッティーズ）」をいいます。</li>
                                            <li>「イベント主催者等」とは、各種チケットを販売するイベントの主催者及び運営主体、その他各種チケットを販売する権限を有する個人、法人その他の団体をいいます。</li>
                                            <li>「GETTIIS利用者」とは、本サイトを利用する一切の方をいいます。</li>
                                            <li>「Gettii Lite会員」とは、本規約に同意のうえ当社の定める方法により会員登録を申込み、当社がID・パスワードを付与することにより本サービスの提供を承諾した方をいいます。</li>
                                            <li>「チケット等購入者」とは、GETTIIS利用者のうち、当社の定める方法によりチケット等を予約・購入した方をいいます。</li>
                                            <li>「本サービス利用料金」とは、本サービス利用の対価として当社が定める料金をいいます。</li>
                                            <li>「チケット等販売代金」とは、チケット等購入者がGettii Lite会員に支払うチケット等の販売代金をいいます（決済事業者又は収納代行業者から支払われるチケット等の代金に相当する金員を含みます。）。</li>
                                        </ol>

                                        <ol class="document-info">
                                            <b>第２条（Gettii Liteのご利用について）</b>
                                            <li>当社は、Gettii Lite会員に対し、本規約の各条項に従い、Gettii Lite会員が、自己の又は別に存在するイベント主催者等のイベントにかかるチケット等情報を入力し、本サイトを通じてGETTIIS利用者に対しチケットを販売することができる機能を有する、本サービスのシステムの非独占的な使用を許諾します。本条項に基づく使用権は、譲渡不可、再使用許諾不可、及び担保提供不可のものであり、Gettii Lite会員は、当社の事前の書面による承諾を得ない限り、本サービスに係るソフトウェアその他のシステムもしくはその複製物等を他に譲渡、転貸、担保提供してはならず、又は本サービスの予定する目的以外の目的をもって第三者が使用可能な状態に供してはなりません。</li>
                                            <li>本サービスは、Gettii Lite会員とGETTIIS利用者間の各種イベントにかかるチケット等の売買の場・機会を提供するものであり、両者間の売買契約、出品、購入等は、全て両者の自己責任とします。当社は、自らチケット等の売買を行うものではなく、売買の委託を受けるものでもありません。当社は、チケット等の売買契約の取消、解約・解除、返品・返金、イベントの開催の有無や内容に関し、一切責任を負いません。</li>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第３条（規約への同意及び変更）</b>
                                        
                                            <li>本サービスをご利用いただけるのは、本規約に同意されたGettii Lite会員のみです。Gettii Lite会員が本規約に違反した場合には、本サービスのご利用をお断りする場合があります。</li>
                                            <li>Gettii Lite会員は、本サービスの利用にあたり、当社がガイドライン・注意事項等その他の利用条件を定めた場合、又は本サービスに付帯する外部サービスの機能を利用する場合には、本規約に定める条項のほか、当該その他の利用条件や外部サービス提供者が設定した規約その他の定めに従うものとします。</li>
                                            <li>当社は、Gettii Lite会員に対して事前の通知又は予告をすることなく本規約の全部又は一部を合理的な範囲・方法で変更することがありますので、本サービスを利用される際は必ず最新の本規約をご確認ください。変更後の本規約は本サイト上で表示された時点（又は表示時に予告期間を定めている場合には、当該予告期間の満了時点）で効力を生じるものとし、当該効力発生後に本サービスをご利用になった場合には、変更後の内容に同意したものとみなし、変更後の内容のみを有効とさせていただきます。</li>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第４条（Gettii Lite会員）</b>
                                            <li>Gettii Lite会員の会員登録は、会員登録を希望する方が必要事項を当社に通知し、これに対して当社が所定の審査を行ったうえで、本サービス利用のためのID・パスワードを付与することにより会員登録希望を承諾した時点をもって、完了するものとします。</li>
                                            <li>当社は、会員登録を希望する方が以下の事由に該当する場合、会員登録を承諾しない場合があります。なお、当社は、以下の事由の有無を調査する義務を負うものではなく、また、会員登録を承諾しない理由を開示する義務を負うものでもありません。</li>
                                        <ul class="document-info-list" style="list-style: none;">
                                          <li>①当社への通知内容について虚偽があり又は不正確であると合理的に認められる場合</li>
                                          <li>②制限責任能力者が親権者等法定代理人の同意等を得ていない場合</li>
                                          <li>③過去に会員登録が取り消されていた場合</li>
                                          <li>④反社会的勢力、又は反社会的勢力に実質的に関与していると合理的に認められる場合</li>
                                          <li>⑤会員登録名等登録の必要事項について当社が適当でないと認め、変更を要請したにもかかわらず変更に応じない場合</li>
                                          <li>⑥その他合理的理由により当社が会員登録を適当でないと認める場合</li>
                                        </ul>
                                        <li>Gettii Lite会員は、当社に通知した必要事項に変更があった場合には、速やかに、所定の方法により当社に対し変更の通知を行うものとします。本規約に基づき当社がGettii Lite会員に対して行う通知は、別途定めのない限り、Gettii Lite会員が当社に通知した連絡先宛に行います。当該変更の通知を怠ったことにより当社からの通知が到達しない等の何らかの不利益が生じた場合でも、当社は一切責任を負いません。</li>
                                        <li>Gettii Lite会員は、自己の責任において、当社より付与されたID・パスワードを適切に管理及び保管するものとし、これを第三者に貸与、譲渡、名義変更その他方式を問わず利用させてはならないものとします。当社は、当社に通知・登録されたIDとパスワードの組み合わせと合致したログインがなされた場合、当該利用は登録されているGettii Lite会員による利用とみなすことができるものとします。Gettii Lite会員のID・パスワード等の管理不十分、使用上の過誤、第三者の使用等による損害の責任はGettii Lite会員が負うものとし、当社は一切責任を負いません。</li>
                                        <li>Gettii Lite会員が以下のいずれかの事由に該当する場合、当社は事前の通知なしに、直ちに会員登録を取り消すことができます。なお、当社は会員登録取消しの理由を開示する義務を負うものでなく、また、当該会員登録取消しに伴う本サービス提供中止に関し一切責任を負わないものとします。</li>
                                        <ul class="document-info-list" style="list-style: none;">
                                          <li>①本条第2項の事由に該当することが後に判明した場合</li>
                                          <li>②第12条に定める禁止事項を行ったと当社が合理的に認める場合</li>
                                          <li>③当社への支払債務の不履行その他本規約に違反した場合</li>
                                          <li>④当社から付与されたID・パスワードを第三者に使用させた場合</li>
                                          <li>⑤当社からの問合せ等の連絡に対して10日以上応答がない場合</li>
                                        </ul>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第５条（サービス内容の変更、停止・中止等）</b>
                                            <li>当社は、本サイトや本サービスについて、その裁量により必要であると判断した場合、事前通知又は予告をすることなく当該内容や仕様を変更することができるものとします。</li>
                                            <li>当社は、以下の事由が生じた場合、本サービスの一部又は全部の提供を停止したり中止したりすることができるものとします。但し、当社は、緊急やむを得ない場合を除き、本サービスの一部又は全部の提供の停止・中止を行う場合には、あらかじめ相当期間前に本サイト上又はその他適当な方法でGettii Lite会員に通知するものとします。</li>
                                        <ul class="document-info-list" style="list-style: none;">
                                          <li>①第4条の会員登録取消事由の調査のため必要があると当社が判断した場合</li>
                                          <li>②本サービスにかかるシステムの保守、修繕、もしくは工事上やむを得ない場合（第三者による故意の攻撃や不正アクセスの有無、アクセス集中その他の原因によるシステムの過大負荷の有無の調査、その他のセキュリティ診断又は負荷試験を実施する必要がある場合を含みます）</li>
                                          <li>③天災、戦争、テロリズム、ストライキ又はその他の労働争議、暴動、内乱、火災、政府による命令・処分、本サービスにかかるシステムの管理業務委託先もしくはサービスプロバイダのサービス履行の遅れ、インターネット・通信・電力その他の公共サービス事業体によって引き起こされたサービスの中断、第三者による本サービスにかかるシステムへの故意の攻撃や不正アクセス等、乙の合理的な制御の及ぶ範囲を超えた非常事態が発生し、又は発生するおそれがある場合</li>
                                          <li>④その他当社の裁量により本サービスの継続が困難と合理的に判断した場合</li>
                                        </ul>
                                        <li>当社は、理由の如何を問わず、前各項の本サイトや本サービスの内容・仕様変更、提供停止・中止によって生じた損害につき、一切責任を負わないものとします。</li>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第６条（利用環境）</b>
                                            <li>Gettii Lite会員は、本サービスを利用する際は、自己の責任と負担において本サービスを利用するために必要なコンピュータ、携帯電話その他の機器（以下「機器等」といいます。）の動作環境・通信環境を整えるものとします。</li>
                                            <li>本サービスは、インターネット・電子メールその他Gettii Lite会員の機器等上の各種設定が適切になされていることを前提にしており、不適切な機器等の動作環境・通信環境により本サービスが正しく利用できない場合でも、当社は一切責任を負いません。</li>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第７条（本サービス利用料金）</b>
                                            <ul style="list-style: none;">
                                              <li>Gettii Lite会員は、本サービスを利用してのチケット等の売買契約が成立した場合、チケット等販売代金の４％（税抜）と振込金額に応じた振込手数料（３万円未満の場合は６６０円、３万円以上の場合は８８０円）を当社が指定する方法により当社に対し支払うものとします。また、返金手続が生じた場合、およびその他別途当社が定める場合においても、別途当社が定める本サービス利用料金を同様の方法で当社に対し支払うものとします。</li>
                                            </ul>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第８条（チケット等販売代金）</b>
                                            <li>Gettii Lite会員は、当社に対して、チケット等購入者から支払われるチケット等販売代金を代理受領する権限を付与するものとします。また、Gettii Lite会員は、当社が決済事業者及び収納代行業者を指定した場合には、当該決済事業者及び収納代行業者に対して、チケット等販売代金を代理受領する権限を付与するものとします。</li>
                                            <li>Gettii Lite会員は、イベントの終了日の翌月末日（但し、金融機関の営業日でない場合は直後の営業日とします。以下「販売代金受領予定日」といいます。）に、チケット等販売代金から前条の本サービス利用料金を控除した金額を受領することができるものとします。当社からGettii Lite会員への支払いに伴う手数料は、Gettii Lite会員が負担するものとします。</li>
                                            <li>金融機関情報その他チケット等販売代金の送金に必要な情報が未登録又は不正確な場合、当社は、登録又は訂正されるまで前項の支払いを留保することができるものとします。なお、当該情報が不正確のため当社が送金できなかった場合において、訂正後の再度の送金に改めて生じる手数料はGettii Lite会員が負担するものとします。当社が金融機関情報その他チケット等販売代金の送金に必要な情報の提供を求めたにもかかわらず、Gettii Lite会員が販売代金受領予定日から180日を経過しても当該情報の登録又は訂正を行わなかった場合には、当社は、Gettii Lite会員が、当社に対するチケット等販売代金の支払請求権を放棄したとみなすことができるものとします。</li>
                                            <li>当社は、以下のいずれかに該当する場合、当社の裁量により、Gettii Lite会員に対しチケット等販売代金の支払いを行わないことができるものとします。また、以下のいずれかに該当する場合であって、当社がチケット等販売代金を既にGettii Lite会員に支払済みのときは、当社は、Gettii Lite会員に対し、当社の定める方法により支払済みの金銭の返還を請求することができるものとします</li>
                                            <ul class="document-info-list" style="list-style: none;">
                                              <li>①当社がGettii Lite会員に対して支払おうとするチケット等販売代金が、当社の定める期間中に当社所定の金額に到達していない場合</li>
                                              <li>②Gettii Lite会員が本規約その他のルール等に違反し又は違反するおそれがある場合</li>
                                              <li>③その他Gettii Lite会員へのチケット等販売代金の支払いが不適切であると当社が判断する場合</li>
                                            </ul>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第９条（イベントの中止の場合）</b>
                                            <li>イベント主催者等がイベントをその終了前にやむを得ず中止する場合、Gettii Lite会員はチケット等購入者に対し、必要な対応を実施しなければならないものとします。</li>
                                            <li>前項の場合、Gettii Lite会員はチケット等購入者に対し、当該イベントにかかるチケット等販売代金の全額を返金する義務を負うものとします。なお、この場合であっても、当社は既に受領した本サービス利用料金を返金する義務は負わず、チケット等販売代金から控除された本サービス利用料金は、Gettii Lite会員が負担するものとします。</li>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第１０条（知的財産権等）</b>
                                            <ul style="list-style: none;">
                                              <li>本サービス及び本サイト上を構成する文章、画像、プログラムその他データ等についての所有権、知的財産権、肖像権、パブリシティー権その他一切の権利は、当社又は当該権利を有する第三者に帰属するものとし（但し、Gettii Lite会員が本サービスの提供に関し作成したコンテンツにかかる権利を除く。）、当社は、Gettii Lite会員に対し、第２条その他本規約に定めるもののほかいかなる権利も付与するものではありません。Gettii Lite会員は、方法又は形態の如何を問わず、これらを当社又は当該第三者に無断で複製、複写、転載、転送、蓄積、販売、出版その他本サービスの利用の範囲を超えて使用してはならないものとします。</li>
                                            </ul>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１１条（個人情報）</b>
                                            <li>当社は、Gettii Lite会員から個人情報の提供を受ける場合がありますが、当該個人情報については、当社のプライバシーポリシーに従って取り扱います。なお、当社は、請求、財務報告、モニタリング、製品最適化及びその他の類似の目的で、Gettii Lite会員を含むGETTIIS利用者への本サービスの提供に関する個人を特定できない形での統計的及び分析的データを収集、編纂、及び使用することができるものとします。また、当社は、決済事業者又は収納代行業者や外部サービス提供者を含む第三者に、本サービスの提供のために必要な場合と必要な範囲に限り、個人情報を提供する場合があります。</li>
                                            <li>Gettii Lite会員は、チケット等購入者を含むGETTIIS利用者の個人情報を取得する場合には、これを適正に管理し、本人の同意又は当社の同意なく、チケット等の郵送その他事前に公表又は本人に通知した利用目的以外の目的で利用してはならず、違法に第三者に開示、提供してはならないものとします。</li>
                                        </ol>

                                        <ol class="document-info">
                                        <b>第１２条（禁止事項）</b>
                                            <li>Gettii Lite会員は、本サービスの利用にあたって、以下の行為を行ってはならないものとします。</li>
                                             <ul class="document-info-list" style="list-style: none;">
                                              <li>(1).	他のGettii Lite会員を含む他のGETTIIS利用者、第三者もしくは当社の著作権その他の知的財産権、名誉、プライバシー、肖像権、もしくはその他の権利を侵害する行為、又は侵害するおそれのある行為</li>
                                              <li>(2).	他のGettii Lite会員を含む他のGETTIIS利用者、第三者もしくは当社に対する、暴力的もしくは法的な責任を超えた不当な要求行為、取引に関する脅迫的な言動又は暴力行為、風説の流布、偽計もしくは威力を用いた信用毀損又は業務妨害行為、その他不利益もしくは損害を与える行為、又は与えるおそれのある行為</li>
                                              <li>(3).	公序良俗に反する行為もしくはそのおそれのある行為、又は公序良俗に反する情報を他のGettii Lite会員を含む他のGETTIIS利用者もしくは第三者に提供する行為</li>
                                              <li>(4).	犯罪行為、もしくは犯罪行為に結び付く行為、又はそのおそれのある行為</li>
                                              <li>(5).	政治、宗教、性風俗に関する行為</li>
                                              <li>(6).	本サービスを、本サービスの予定する目的と異なる商業目的で利用する行為</li>
                                              <li>(7).	他のGETTIIS利用者や第三者からID・パスワードを入手したり、他のGETTIIS利用者や第三者にID・パスワードを開示する行為</li>
                                              <li>(8).	本サイトを無断で改変する行為、他のGettii Lite会員を含む他のGETTIIS利用者、第三者もしくは当社の情報を権原なく改ざん、消去する行為</li>
                                              <li>(9).	コンピュータウイルス等の有害なプログラムを、本サービスを通じてもしくは本サービスに関連して使用し、もしくは提供する行為、又は当社のネットワークもしくはシステム等に不正にアクセスしもしくはそれを試みる行為</li>
                                              <li>(10).	本サービスのネットワークもしくはシステム等に過度な負荷をかけ、又はその他本サービスの運営を妨げるような行為（当社へ正当な事由もなく本サービスの利用に関する問い合わせ以外の内容の電話・メールを繰り返し、不当な義務等を強要し、威嚇等をもって嫌がらせをし、その他恐喝もしくは脅迫に類する行為を含みます。）</li>
                                              <li>(11).	特定興行入場券の不正転売の禁止等による興行入場券の適正な流通の確保に関する法律その他の関連法令に違反するチケット等の転売行為、その他のチケット等の不正転売行為</li>
                                              <li>(12).	特定商取引に関する法律その他の通信販売にかかる法令に違反する取引行為</li>
                                              <li>(13).	消費者契約法の規定に基づき取消が可能である取引行為</li>
                                              <li>(14).	本規約もしくは法令に違反する行為、又は違反するおそれのある行為</li>
                                              <li>(15).	その他、当社が不適切と判断する行為</li>
                                            </ul>
                                            <li>当社は、当社の判断又は法令もしくは被害者等の第三者による適法かつ正当な申告に基づき、Gettii Lite会員の行為が前項各号のいずれかに該当し又は該当するおそれがあると認められる場合、直ちに、禁止事項に該当する情報の送信防止措置（非表示措置を含みます。）を執ることができるものとします。但し、当社は、禁止事項の調査義務又は本項の措置を執る義務を負うものではなく、また上記措置を執ることによりGettii Lite会員に損害が生じた場合でも、一切責任を負いません。</li>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１３条（責任、免責事項等）</b>
                                            <li>当社は、Gettii Lite会員が入力したチケット等情報の完全性、正確性、確実性、有用性等その他に関して一切関知せず、一切の責任を負いません。</li>
                                            <li>本サービスの操作、運用、表示、不作動又は誤作動によりGettii Lite会員その他の第三者に生じた一切の損害について、当社は損害賠償責任その他名目の如何を問わず何らの責任を負いません。但し、当該損害が当社の故意又はこれと同視しうる重過失に起因して生じたものである場合はこの限りではありません。</li>
                                            <li>Gettii Lite会員は、本規約その他当社の指示に違反して本サービスを使用し、又は本サービスが想定していない不適切な方法で使用したことにより、他のGETTIIS利用者その他の第三者に発生した損害については、当社は一切の責任を負わないものとし、Gettii Lite会員は、これにより当社が被った損害・費用その他の損失を補償します。</li>
                                            <li>Gettii Lite会員は、チケット等の予約内容及びチケット等の内容（イベントの内容を含みます）自体に関する問い合わせ・要望等に対応するための正確かつ完全な連絡先・問合先の情報を表示しなければならないものとし、直接GETTIIS利用者やチケット等購入者からの問合せ・要望等への対応を行うものとします。但し、当社は、本サービスの運営に関する事項（個々のチケット等の内容に関わりのない事項に限ります。）やシステムトラブル等に関する問い合わせ等については対応いたします。</li>
                                            <li>本サービスを通じた各種チケット等の購入・キャンセル・払戻等の条件がある場合は、Gettii Lite会員において正確かつ完全に表示しなければならないものとし、これらに関して生じたGettii Lite会員、イベント主催者等、GETTIIS利用者やチケット等購入者との間のトラブルについて、当社は一切責任を負いません。</li>
                                            <li>本サービスに関し、本規約の定めに関わらず法令の定めにより当社が責任を負う場合であっても、当社は、Gettii Lite会員その他の損害賠償請求者が現実に直接被った損害以外の損害（付随的損害、間接損害、特別損害、将来の損害及び逸失利益にかかる損害）については、賠償する責任を負わないものとし、また当社の損害賠償額は、損害が生じた該当イベントに関してGettii Lite会員が当社に対し支払った本サービス利用料金の総額を上限とします。</li>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１４条（退会）</b>
                                            <ul style="list-style: none;">
                                              <li>Gettii Lite会員は、当社所定の手続きにより退会することができます。但し、当社に対する利用料金の支払債務等について消滅・免責されるものではありません。</li>
                                            </ul>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１５条（遅延損害金）</b>
                                            <ul style="list-style: none;">
                                              <li>Gettii Lite会員は、当社に対する支払いを怠った場合、支払期日の翌日より支払いが完了するまでの期間につき年14.6％の割合による遅延損害金を支払わなければなりません。</li>
                                            </ul>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１６条（分離可能性）</b>
                                            <ul style="list-style: none;">
                                              <li>本規約のいずれかの条項又はその一部が、民法、消費者契約法その他の法令等に抵触することにより無効又は執行不能と判断される可能性がある場合には、当該規定は当該抵触の範囲内において当然に修正されて適法に解釈されるものとし、また本規約のうち無効もしくは執行不能と判断された規定の残りの部分は、継続して完全に効力を有するものとします。</li>
                                            </ul>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１７条（準拠法）</b>
                                            <ul style="list-style: none;">
                                              <li>本規約の準拠法は日本法とします。</li>
                                            </ul>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１８条（管轄裁判所等）</b>
                                            <li>本サービスに関連して、Gettii Lite会員と当社の間で紛争が生じた場合には、双方誠意をもって協議の上解決するものとします。</li>
                                            <li>協議をしても紛争を解決できない場合には、東京地方裁判所を第一審の専属的合意管轄裁判所とします。</li>
                                        </ol>
                                        
                                        <ol class="document-info">
                                        <b>第１９条（付則）</b>
                                            <ul style="list-style: none;">
                                              <li>本規約は2020年10月1日（午前0時）より発効するものとします。</li>
                                            </ul>
                                        </ol>
                                        <br>
                                        <p class="text-right">
                                            2020年10月1日 制定<br>
                                            2021年1月18日 改訂<br>
                                            2021年7月27日 改訂<br>
                                            2021年9月1日 改訂
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end of  安全須知資訊區 -->
                        <div>
                        <div class="form-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="agree">{{ trans('registered.S_ServiceRuleCheack') }}  <a data-toggle="modal" href="https://www.gettii.jp/privacy/"  target="_blank">{{ trans('registered.S_PrivacyPolicy') }}</a>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <!-- 0908 新增錯誤問題提示，用於使用者未check「ご利用規約に同意する」，需增加提示訊息請使用者勾選 -->
                        <span class="help is-danger" v-show='!agree'><i class="fas fa-exclamation-circle fa-lg"></i> {{ trans('registered.S_CheckServiceAccept') }}</span>
                    </div>
                    </div>
                </div>
            </div>
            <!-- /.box3 註冊頁面統一樣式 -->
            <!--reCaptcha驗證區-->
           <!-- <div class="col-md-12">
                <div class="captcha-box"><img src="dist/img/newCaptchaAnchor.gif" alt="img" style="width: 200px;"></div>
            </div>-->
            <!--/.reCaptcha驗證區-->
            <div class="login-container-form-btn">   
                <button id="buttonApply" v-on:click="sendApply()" :disabled="!agree"  class="btn-login btn-inverse">
                    {{ trans('registered.S_ServiceAgree') }}
                </button>
            </div>
        </div>
    </div>  
</section>

<!-- 0908 新增隱私權說明內文 -->
<div class="modal fade" id="privacy">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ trans('registered.S_PrivacyPolicy') }}</h4>
            </div>
            <div class="modal-body">
<!-- 隱私權政策內文區 -->
    <div class="row">
        <div class="document-section">
            <div class="document-content">
                <div class="document-content_text">

                    <ol class="document-info">
                                            <b>個人情報保護方針</b>
                                           <p class="text-right">
                                               <small>制定　平成19年7月1日</small><br>
	<small>最終更新日　平成26年10月26日</small><br>
	<small>株式会社リンクステーション</small><br>
	<small>代表取締役　大嶋&nbsp;憲通</small></p>
                                        </ol>
                                        <hr>
                    <ol class="document-info">
                        <h5>個人情報保護理念</h5>
                        <div class="">
                            <p>当社は、オンラインチケット等の販売を目的とするASP型票券管理システム　Gettii／ゲッティの運営管理事業及びチケット等販売を中心としたポータルサイト運営事業（以下「当社事業」といいます）を行っております。当社のこれらの事業は、関係者（お客様、お取引先様、株主様、及び従業員）との信頼の上に成り立っていると考えています。
                            </p>
                            <p>当社は、当社が事業活動をする上でお預かりする全ての個人情報をより厳正に取扱うため、役員及び従業員等が遵守すべき行動基準として本個人情報保護方針を定め、その遵守の徹底を図ることといたします。
                            </p>
                            <p>本個人情報保護方針において「個人情報」とは、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日その他の記述等により特定の個人を識別することができるもの（他の情報と容易に照合することができ、それにより特定の個人を識別することができることとなるものを含みます）をいいます。当社は、予め特定された利用目的の達成に必要な範囲を超えて個人情報を利用しません。
                            </p>
                            <p>また、当社は、日本工業規格JISQ15001：2006「個人情報保護に関する個人情報保護マネジメントシステム－要求事項」及び個人情報の取扱いに関する法令、国が定める指針その他の規範を遵守して個人情報を取扱います。
                            </p>
                            <p>なお、この個人情報保護方針における用語の定義は、個人情報の保護に関する法律（以下「個人情報保護法」といいます）に準じます。</p>
                        </div>
                        <li>当社は、当社事業で取扱う個人情報並びに役員及び従業員の個人情報に関して、個人情報保護に関する法令、国が定める指針及びその他の規範を遵守するため、JISQ15001個人情報保護マネジメントシステム要求事項に準拠した個人情報保護マネジメントシステムを策定し、適切に運用します。
                        </li>
                        <li>当社は、事業遂行のために必要な範囲内で利用目的を明確に定め、適切に個人情報の取得、利用、及び提供を行います。取得した個人情報は利用目的の範囲内でのみ利用し、目的外利用を行わないための措置を講じます。但し、当社は、当社が定めた利用目的と相当の関連性を有すると合理的に認められる範囲内で適宜利用目的を変更することができるものとします。但し、次に掲げる場合については、その変更について公表しないことがあります。
                        </li>

                        <ul class="document-info-list">
                            <li>利用目的を本人に通知し、又は公表することにより本人又は第三者の生命、身体、財産その他の権利、利益を害するおそれがある場合</li>
                            <li>利用目的を本人に通知し、又は公表することにより当社の権利又は正当な利益を害するおそれがある場合</li>
                            <li>国の機関又は地方公共団体が法令の定める事務を遂行することに対して協力する必要がある場合であって、利用目的を本人に通知し、又は公表することにより当該事務の遂行に支障を及ぼすおそれがあるとき
                            </li>
                            <li>取得の状況からみて利用目的が明らかであると認められる場合</li>
                        </ul>
                        <li>当社は、前項の措置により取得した個人情報の取扱いの全部又は一部を委託する場合には、十分な保護水準を満たした者を選定し、契約等により適切な措置を講じます。</li>
                        <li>当社は、個人情報への不正アクセス、個人情報の紛失、改ざん、漏洩等のリスクに対して、合理的な安全対策及び是正措置を講じます。</li>
                        <li>当社は、本人からの当該個人情報の開示、訂正、削除、利用停止等の要請及び苦情や相談に対して遅滞なく対応します。</li>
                        <li>当社は、個人情報の取扱いとそのシステムに関して、管理責任者を明確にし、個人情報保護マネジメントシステムを構築するとともに、これを継続的に見直し改善します。</li>
                    </ol>

                    <ol class="document-info">
                        <h5>個人情報の取扱い</h5>
                        <li class="font-600">事業者の氏名又は名称</li>
                        <p class="ml-20x">株式会社リンクステーション</p>
                        <li class="font-600">個人情報保護管理者</li>
                        <p class="ml-20x">総務管理部長</p>
                        <li class="font-600">個人情報の利用目的</li>
                        <table class="table table-striped table-modal" width="90%">
                            <thead>
                                <tr>
                                    <th width="130">情報項目</th>
                                    <th width="100">開示/非開示</th>
                                    <th>利用目的</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>お客様</td>
                                    <td>開示</td>
                                    <td>
                                        <ul class="pl-20">
                                            <li>お客様に関する情報の管理のため</li>
                                            <li>当社事業に関わるご案内、契約、申込処理、請求収納、商品・サービスの提供（チケット予約、チケット発送、希望者へのメールマガジン送信、スタンプラリーサービス、ポイントサービス、認証サービス等）、品質管理、アフターサービス、商品・サービスの改善のため
                                            </li>
                                            <li>当社事業の運営上必要な事項の通知（電子メールによるものを含む）のため</li>
                                            <li>債権保全のために回収機関に情報を提供するため</li>
                                            <li>マーケティングデータの調査・分析、新たなサービス開発のため</li>
                                            <li>業務提携企業に提供する統計資料作成のため</li>
                                            <li>取引時にお客様に個別に同意いただいた目的のため</li>
                                        </ul>

                                    </td>
                                </tr>
                                <tr>
                                    <td>お問い合わせのお客様</td>
                                    <td>開示</td>
                                    <td>
                                        <ul class="pl-20">
                                            <li>お問い合わせに対するご回答のため</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>株主様</td>
                                    <td>開示</td>
                                    <td>
                                        <ul class="pl-20">
                                            <li>会社法に基づく権利の行使・義務の履行及び株主優待のため</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>採用応募者・従業員及び退職者</td>
                                    <td>開示</td>
                                    <td>
                                        <ul class="pl-20">
                                            <li>採用選考のため</li>
                                            <li>その他、従業員情報管理規定に定める目的のため</li>
                                            <li>雇用管理、福利厚生のため</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>受託業務</td>
                                    <td>非開示</td>
                                    <td>
                                        <ul style="padding-left:20px;">
                                            <li>当社事業における受託業務を処理するため</li>
                                            <li>上記事業における決済代行会社その他の業務委託先、又は業務提携先に情報提供するため</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>全般</td>
                                    <td>開示</td>
                                    <td>
                                        <ul class="pl-20">
                                            <li>個人データの開示などの求めに対応するため</li>
                                            <li>当社施設・ネットワークの安全管理のため</li>
                                        </ul>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        
                        <li class="font-600">個人情報の第三者提供</li>
                        <p class="ml-20x">当社は、以下のいずれかの場合を除いて、取得時同意いただいた範囲を超えて個人情報を第三者に提供いたしません。</p>
                        <ul class="document-info-list">
                            <li>前項の利用目的における受託業務を処理する場合</li>
                            <li>本人の同意がある場合</li>
                            <p class="ml-20x">※なお、この場合、原則として機密保持、再提供の禁止、ご本人からのお申し出があれば利用を停止することを第三者への提供の条件といたします</p>
                            <li>法令等により開示を求められた場合</li>
                            <li>本人又は公衆の生命、身体又は財産の保護のために必要がある場合であって、本人の同意を得ることが困難であるとき</li>
                            <li>公衆衛生の向上又は児童の健全な育成の推進のために特に必要がある場合であって、本人の同意を得ることが困難であるとき</li>
                            <li>国の機関若しくは地方公共団体又はその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、本人の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがあるとき
                            </li>
                        </ul>
                        <li class="font-600">委託先の監督</li>
                        <p class="ml-20x">
                            当社事業に関して個人情報を取扱う業務（システムの開発・運用・保守、個人情報を保存・管理するサーバーの設置・管理・保守、チケット等の配送、コンテンツ配信、登録ユーザーからのお問い合わせ対応等）を外部の会社に委託することがあります。但し、その場合には、安全管理対策の充実した委託先を選定し、かつ安全管理対策を契約において義務付けます。
                        </p>
                        <li class="font-600">個人情報の提供の任意性</li>
                        <p class="ml-20x">
                            当社がお客様などご本人に個人情報の提供をお願いした場合、ご本人から当社への個人情報の提供は任意です。但し、ご提供いただけない情報の種類によって、当社は当該ご本人へサービスの一部又は全部をご提供できない場合があります。
                        </p>
                        <li class="font-600">本人が容易に認識できない方法による個人情報の取得</li>
                        <p class="ml-20x">
                            当社施設における安全管理のために、防犯カメラによる監視を行っております。なお、クッキーやウェブビーコン等により、ご本人が容易に認識できない方法による個人情報の取得は行っておりません。
                        </p>
                        <li class="font-600">個人情報に関するお問い合わせ</li>
                        <p class="ml-20x">
                            当社は、お預かりする個人情報に関し、ご本人から利用目的の通知、開示、内容の訂正、追加又は削除、利用の停止、消去および第三者への提供の停止に関するご要請があれば、ご本人の確認をさせていただいた上で、法令の特別の定めがない限り、個人情報保護法の規定に従い、合理的な範囲及び方法で速やかに対応します。また、当社の個人情報の取扱いに関するご質問、ご相談にも対応いたします。但し、個人データの削除については、法的な保管義務に抵触する場合にはご希望に添えない場合があります。
                        </p>
                        <p class="ml-20x">
                            当社の個人情報に関するお問い合わせは、以下の窓口で承ります。お問い合わせの内容により必要な書類提出や質問へのご回答をお願いすることがあります。なお、当社の基本的な対応方法は以下のとおりとします。
                        </p>
                        <li class="font-600">免責</li>
                        <p class="ml-20x">
                            当社サイト及び当社のチケット販売を中心としたポータルサイトからのリンク先ウェブサイトにおけるお客様の個人情報保護については、当社は責任を負うことができません。お客様において、当該リンク先ウェブサイトの個人情報安全確保体制をご確認いただきますようお願いいたします。
                        </p>
                        <li class="font-600">改訂</li>
                        <p class="ml-20x">当社は、本個人情報保護方針の一部又は全部を改訂することがあります。改訂がある場合には、都度当社サイト上でお知らせいたします。</p>

                    </ol>

                    <ol class="document-info">
                        <h5>個人情報に関するお問い合わせ</h5>

                        <table class="table table-boxrows" width="90%">
                            <tbody>
                                <tr>
                                    <th>担当</th>
                                    <td>PMS個人情報相談窓口</td>
                                </tr>
                                <tr>
                                    <th>電話</th>
                                    <td>017-718-5770　（受付時間：平日10時～18時）</td>
                                </tr>
                                <tr>
                                    <th>FAX</th>
                                    <td>017-718-5771　（受付時間：24時間・翌営業日対応）</td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td><a href="mailto:pms@linkst.jp">pms@linkst.jp</a>　（受付時間：24時間・翌営業日対応）</td>
                                </tr>
                            </tbody>
                        </table>
                    </ol>
                    <ol class="document-info">
                        <h5>個人情報に関するお問い合わせ</h5>

                        <p>一般財団法人　日本情報経済社会推進協会</p>
                        <small>（苦情解決の連絡先）</small>
                        <p>プライバシーマーク推進センター　個人情報保護苦情相談室</p>
                        <p>住所：〒106-0032　東京都港区六本木1-9-9　六本木ファーストビル12F</p>
                        <p>電話：03-5860-7565　　0120-700-779</p>
                        <p>※当社の商品、サービスに関する問い合わせ先ではございません</p>
                    </ol>

                </div>
            </div>
        </div>
    </div>
    <!--end of  隱私權政策內文區 -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-inverse" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
      <!-- /.0908 新增隱私權說明內文 -->
@component('components/loading')

@endcomponent
@component('components/result2')

@endcomponent
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{!! \Config::get("constant.googlr_recaptcha_data")["site_key"] !!}', {action: 'login'}).then(function(token) {
            document.getElementById('recaptcha').value = token
        });
    });
    
    var registeredPage = new Vue({
        el: '#registeredPage',
        data:{
            curtain:true,
            applyId:'',
            adminName:'',
            identity:'',
            userName:'',
            userNameKana:'',
            userTel:'',
            userMail:'',
            companyName:'',
            companyNameKana:'',
            prefecture:'',
            city:'',
            postCode:'',
            postDisplay:'',
            placeDetailed:'',
            contactDepartment:'',
            contactPerson:'',
            contactTel:'',
            contactMail:'',
            introduction:'',
            sellChecked:'',
            agree:false,
            unCountryLocation:false,
            hadError:false,
            error:[],
            json:'',
        },
        watch: {
            postDisplay: function (val) {
               this.checkRule()
            }
        },
        methods: {
            checkRule:function(){
                let re_post_display = /^\d{3}-\d{4}$/
                this.hadError   =   false
                this.error.postDisplay.status = false
                this.error.postDisplay.msn    = ''

                if(this.postDisplay.length > 0){
                    if (!re_post_display.test(this.postDisplay)){
                        this.hadError   =   true 
                        this.error.postDisplay.status = true
                        this.error.postDisplay.msn    = '形式が不正です'
                    }
                }
            },
            sendApply:function(){
                document.getElementById('buttonApply').blur();
                let json = []
                this.checkRule()
                this.$validator.validateAll().then(isValid => {
                    if (!isValid ||  this.hadError) {
                        console.info(`had error`);
                    } else {
                        loading.openLoading()
                        this.postCode = document.getElementById('postCode').value

                        json.push({
                            applyId : this.applyId,
                            adminName : this.adminName,
                            identity : this.identity,
                            userName : this.userName,
                            userNameKana : this.userNameKana,
                            userTel : this.userTel,
                            userMail : this.userMail,
                            companyName : this.companyName,
                            companyNameKana : this.companyNameKana,
                            prefecture: this.prefecture,
                            city: this.city,
                            postCode: this.postCode,
                            postDisplay: this.postDisplay,
                            placeDetailed : this.placeDetailed,
                            contactDepartment : this.contactDepartment,
                            contactPerson : this.contactPerson,
                            contactTel : this.contactTel,
                            contactMail : this.contactMail,
                            introduction : this.introduction,                            
                        })

                        this.json =  JSON.stringify(json)
                        localStorage.setItem('registerData' ,this.json)

                        this.$nextTick(() => {
                            document.getElementById("formRegister").submit();
                        })
                    }
                })  
            },
        }, 
        mounted(){  
            loading.closeLoading()
            this.identity = "personal"
            this.error = {
                postDisplay : { 
                                status:false,
                                msn:''
                              },
            }
            @if($errors->any())
                sessionStorage.setItem('errorsMsg', '{!! addslashes($errors->first()) !!}')
                let errorsMsg = JSON.parse(sessionStorage.getItem('errorsMsg'))
                popUpResult.open(errorsMsg)

                let registerData = JSON.parse(localStorage.getItem('registerData'))[0]
               
                this.applyId = registerData.applyId
                this.adminName = registerData.adminName
                this.identity = registerData.identity
                this.userName = registerData.userName
                this.userNameKana = registerData.userNameKana
                this.userTel = registerData.userTel
                this.userMail = registerData.userMail
                this.companyName = registerData.companyName
                this.companyNameKana = registerData.companyNameKana
                this.prefecture = registerData.prefecture
                this.city = registerData.city
                this.postCode = registerData.postCode
               this.postDisplay = registerData.postDisplay
                this.placeDetailed = registerData.placeDetailed
                this.contactDepartment = registerData.contactDepartment
                this.contactPerson = registerData.contactPerson
                this.contactTel = registerData.contactTel 
                this.contactMail = registerData.contactMail
                this.introduction = registerData.introduction
                this.agree = registerData.agree
            @endif

            this.curtain = false
        }
    });

    new TwCitySelector({
        el: '.city-selector-set',
        elCounty: '.county', 
        elDistrict: '.district', 
        elZipcode: '.postCode',
        countyValue: registeredPage.prefecture,
        districtValue: registeredPage.city,
    });

</script>
@stop

@section('adminlte_js')
<script src="{{ asset('js/imageUpload.js') }}"></script>
@stop
