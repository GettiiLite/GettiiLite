<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Gettii Lite - Preview</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:site_name" content="GETTIIS">
    <meta property="og:type" content="website">
    <meta name="robots" content="all">
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" href={{ asset('css/gettiis.css') }}>
</head>

<body class="is-chrome is-pc">
    <!-- /.l-header -->
    <div id="preview" class="l-document">
        <main class="l-contents">
            @if($settingData->contentType != 'image')    
              <div class="carousel2">
                <div class="carousel2_inner is-slickStyle2 js-slickType1">
                  <div>
                    <div style="position: relative;">
                      <div id="player_wrapper">
                       <iframe id="player" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" title="YouTube video player" width="100%" src="//www.youtube.com/embed/{{$settingData->contentVidioUrl}}?autoplay=1&mute=1" ></iframe>
                      </div> 
                      <div id="modal_movie">
                        <a href="" class="popup_youtube" style="cursor: default; pointer-events: none;"></a>
                      </div> 
                      <div class="carousel2_bookmark"><button type="submit" style="cursor: default;"><span class="srOnly">bookmark</span></button></div>
                    </div>
                    <p class="article_atc_comment top_comment"> {{ $settingData->contentComment }}</p>
                  </div>
                </div>
              </div>
              <div class="label2">
                <ul class="label2_lists is-eventTag label2_listsItemToHorizontal">
                  <!--<li class="label2_listsItem"><span data-show="">{{ trans('GETTIIS.S_Onsale') }}</span></li>-->
                  @if (App::getLocale() == "ja")
                    <li class="label2_listsItem label2_listsItemPoint"><span data-show="">ポイント</span></li>
                  @endif
                  @if ($settingData->payment_credit)
                    <li class="label2_listsItemBlockDesignation creditCard"><span data-show=""> {{ trans('GETTIIS.S_CreditCard') }} </span></li>
                  @endif
                  @if ($settingData->payment_seven && App::getLocale() == "ja")
                    <li class="label2_listsItemBlockDesignation sevenEleven"><span data-show="">セブン‐イレブン</span></li>
                  @endif
                </ul>
              </div>
              <h2 class="title5 article">
                <div class="title5_title sub">{{ $settingData->eventSubTitle }}</div>
                <div class="title5_title">{{ $settingData->eventTitle }}</div>
              </h2>
            @else
                <template v-if="imageStatus != 2">
                    <div class="event_list_info"><dl>
                    <dt>
                        {{-- <img v-bind:src="logoImage"> --}}
                        <img src="{{ URL::to('/assets/images/gettiis/genre_default/'. $settingData->eventType .'.png' ) }}">
                    </dt> 
                    <dd>
                    <div class="label2" style="margin-top: 10px;">
                        <ul class="label2_lists is-eventTag label2_listsItemToHorizontal">
                        <!--<li class="label2_listsItem"><span data-show="">NEW</span></li>--> 
                       @if (App::getLocale() == "ja")
                          <li class="label2_listsItem label2_listsItemPoint"><span data-show="">ポイント</span></li>
                       @endif
                       @if ($settingData->payment_credit)
                          <li class="label2_listsItemBlockDesignation creditCard"><span data-show=""> {{ trans('GETTIIS.S_CreditCard') }} </span></li>
                        @endif
                        @if ($settingData->payment_seven && App::getLocale() == "ja")
                          <li class="label2_listsItemBlockDesignation sevenEleven"><span data-show="">セブン‐イレブン</span></li>
                        @endif
                        </ul>
                    </div> 

                    <h2 class="title5 article">
                        <div class="title5_title sub">{{ $settingData->eventSubTitle }}</div> 
                        <div class="title5_title">{{ $settingData->eventTitle }}</div>
                    </h2>
                    </dd> 
                    <dd class="bookmark">
                        <div class="carousel2_bookmark no_topImage">
                        <button type="submit" style="cursor: default;"><span class="srOnly">bookmark</span></button>
                        </div>
                    </dd>
                    </div> 
                </template>
                <template v-else-if="imageStatus == 2">
                    <div class="carousel2">
                        <div class="carousel2_inner is-slickStyle2 js-slickType1">
                            <div>
                                <div style="position: relative;">
                                    <img v-bind:src="contentImage"/>
                                    <p class="article_atc_comment top_comment"> {{ $settingData->contentComment }}</p>
                                    <div class="carousel2_bookmark">
                                    <button type="submit"><span class="srOnly">bookmark</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="label2">
                        <ul class="label2_lists is-eventTag label2_listsItemToHorizontal">
                            <!--<li class="label2_listsItem"><span data-show="">{{ trans('GETTIIS.S_Onsale') }}</span></li>-->
                            @if (App::getLocale() == "ja")
                              <li class="label2_listsItem label2_listsItemPoint"><span data-show="">ポイント</span></li>
                            @endif
                            @if ($settingData->payment_credit)
                              <li class="label2_listsItemBlockDesignation creditCard"><span data-show=""> {{ trans('GETTIIS.S_CreditCard') }} </span></li>
                            @endif
                            @if ($settingData->payment_seven && App::getLocale() == "ja")
                              <li class="label2_listsItemBlockDesignation sevenEleven"><span data-show="">セブン‐イレブン</span></li>
                            @endif
                        </ul>
                    </div>
                    <h2 class="title5 article">
                        <div class="title5_title sub">{{ $settingData->eventSubTitle }}</div>
                        <div class="title5_title">{{ $settingData->eventTitle }}</div>
                    </h2>
                </template>
                <template v-else>
                </template>
            @endif
            <div style="clear: both;"></div>
            <div class="share1">
                @if(!empty($settingData->eventUrl))
                  <div class="official">
                      <span><a style="cursor: default;">{{ trans('GETTIIS.S_EventURL') }}</a></span>
                  </div>
                @endif
                <ul class="sns_list">
                    <li><img src="{{ URL::to('/assets/images/gettiis/'.App::getLocale().'/icon_facebook3.svg') }}" alt="Facebook"></li>
                    <li><img src="{{ URL::to('/assets/images/gettiis/'.App::getLocale().'/icon_twitter3.svg') }}" alt="Twitter"></li>
                    <li><img src="{{ URL::to('/assets/images/gettiis/'.App::getLocale().'/icon_line3.svg') }}" alt="Line"></li>
                </ul>
            </div>
            @if ($settingData->salesPeriod)
            <div class="bigloginBtn" id="bigloginBtn">
              <div class="bigitemloginBtn" id="bigitemloginBtn">
                  <a class="button is-type9 button-purchase">{{ trans('GETTIIS.S_Purchase') }}</a>
              </div>
            </div>            
            @endif
            <div class="wysiwyg1 wysiwyg_1_1" style="display: block;">
             <span style="font-weight: bold;">@if ($settingData->editContentPreview) 概要 @endif</span>
             {!! $settingData->editContentPreview !!}
            </div>

            <div id='wysiwyg_1_1' class="wysiwyg1 wysiwyg_1_1" style="display:{{ strtoupper(App::getLocale()) !== strtoupper(config('app.locale')) ? 'none' : 'block' }}">
            @foreach (json_decode($settingData->article, true) as $index => $content)
              <p>{!! html_entity_decode($content['text']) !!}</p>
              @if (isset($content['type']))
                <div class="article_atc_box">
                  @if ($content['type'] == '1')
                    <p style="display:inline;"><img src="{{ $content['image_url'] }}" /></p>
                  @elseif ($content['type'] == '2')
                    <div class="movie">
                       <iframe id="player" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" title="YouTube video player" width="100%" src="//www.youtube.com/embed/{{$content['video_url']}}?autoplay=1&mute=1" ></iframe>
                      <div id="content_player{{ $index }}"></div>
                    </div>
                  @endif
                </div>
              @endif
            @endforeach
            </div><!-- /.wysiwyg1_1 -->
            
            <section>
                <div class="title1">
                    <h2 class="title1_title"><span class="title1_mainLabel">{{ trans('GETTIIS.S_DetailInfo') }}</span></h2>
                </div>
                <div class="performanceDetail1">
                    <ul class="performanceDetail1_lists">
                        <li class="performanceDetail1_listsItem is-area">
                            <div class="performanceDetail1_listsItemInner">
                                <span class="performanceDetail1_listsItemLabel">
                                    <span class="label_oneLine">{{ trans('GETTIIS.S_Location') }}</span> 
                                </span> 
                                <span class="performanceDetail1_listsItemText">
                                    {{ $settingData->hallName }}@if ($settingData->country)({{ $settingData->country }} {{ $settingData->city }})@endif
                                    @if ($settingData->localUrl)
                                      <br> 
                                      <span style="border-top: none; display: inline; color: rgb(32, 129, 255);">{{ trans('GETTIIS.S_Access') }}»</span>
                                    @endif
                                </span>
                            </div>
                        </li>
                        <li class="performanceDetail1_listsItem is-performancePeriod">
                            <div class="performanceDetail1_listsItemInner">
                                <span class="performanceDetail1_listsItemLabel">
                                    <span class="label_oneLine">{{ trans('GETTIIS.S_EventDate') }}</span> 
                                 </span> 
                                 <span class="performanceDetail1_listsItemText">
                                     @if ($settingData->performance_st_dt != $settingData->performance_end_dt)
                                         {{ $settingData->performance_st_dt }} ～
                                     @endif
                                     <span>{{ $settingData->performance_end_dt }}</span>
                                     @if ($settingData->kaientm)
                                       <span>{{ $settingData->kaientm }}</span>
                                     @endif
                                 </span>
                            </div>
                        </li>
                        @if ($settingData->sale_type == 1)
                        <li class="performanceDetail1_listsItem is-price">
                            <div class="performanceDetail1_listsItemInner">
                                <span class="performanceDetail1_listsItemLabel">
                                    <span class="label_oneLine">{{ trans('GETTIIS.S_Price') }}</span> 
                                </span> 
                                <span class="performanceDetail1_listsItemText">
                                    @if ($settingData->maxPrice != $settingData->minPrice)
                                      <span>{{ $settingData->minPrice}}</span>{{ trans('GETTIIS.S_Currency') }}～
                                    @endif
                                    <span>{{ $settingData->maxPrice }}</span>{{ trans('GETTIIS.S_Currency') }}{{ trans('GETTIIS.S_Tax') }}       
                                    <span class="payment_tyle">
                                        <ul>
                                            @if ($settingData->payment_seven  && App::getLocale() == "ja")
                                              <li><img src="{{ URL::to('/assets/images/gettiis/icon_711_logo.png') }}"></li>
                                            @endif
                                            @if ($settingData->payment_credit)
                                              <li><img src="{{ URL::to('/assets/images/gettiis/logo_visa.gif') }}"> 
                                                 <img src="{{ URL::to('/assets/images/gettiis/logo_mastercard.gif') }}">
                                              </li>
                                            @endif
                                        </ul>
                                    </span>
                                </span>
                            </div>
                        </li>
                        @endif
                        <li class="performanceDetail1_listsItem is-contact">
                            <div class="performanceDetail1_listsItemInner">
                                <span class="performanceDetail1_listsItemLabel">
                                    <span class="label_oneLine">{{ trans('GETTIIS.S_Contact') }}</span> 
                                </span> 
                                <span class="performanceDetail1_listsItemText">
                                    <span>
                                       {{ $settingData->eventContact }}
                                       @if($settingData->eventContactTel)
                                        <br>
                                        TEL: {{ $settingData->eventContactTel }}
                                       @endif
                                    </span>
                                </span>
                            </div>
                        </li>
                        <li class="performanceDetail1_listsItem is-salesPeriod2">
                            <div class="performanceDetail1_listsItemInner">
                                <span class="performanceDetail1_listsItemLabel">
                                    <span class="label_oneLine">{{ trans('GETTIIS.S_Distributer') }}</span> 
                                </span> 
                                <span class="performanceDetail1_listsItemText">
                                    <a class="pointer distributor">
                                        {{ $settingData->disp_name }}
                                    </a>
                                </span>
                            </div>
                        </li>
                        @if ($settingData->sale_type == 1)
                        <li class="performanceDetail1_listsItem is-salesPeriod2">
                            <div class="performanceDetail1_listsItemInner">
                                <span class="performanceDetail1_listsItemLabel">
                                    <span class="label_oneLine">{{ trans('GETTIIS.S_SalesTerm') }}</span> 
                                </span> 
                                <span class="performanceDetail1_listsItemText">
                                    @if ($settingData->erlybird)
                                      @if (App::getLocale() == 'ja')
                                        <span class="ic-general" style="background-image: url('/assets/images/gettiis/ja/icon_prereserve.svg'); background-repeat: no-repeat; background-position:center;"></span>
                                      @else
                                        <span class="ic-general" style="background-image: url('/assets/images/gettiis/zh-tw/icon_prereserve.svg'); background-repeat: no-repeat; background-position:center;"></span>
                                      @endif
                                        {{ $settingData->earlyBirdDateStart }}～  
                                        <span class="date_span_to" style="display: block;">
                                            {{ $settingData->earlyBirdDateEnd }} 
                                        </span> 
                                    @endif
                                    @if ($settingData->salesPeriod && $settingData->normalDateStart)
                                      <span class="ic-general" style="background-image: url('/assets/images/gettiis/icon_general.svg'); background-repeat: no-repeat; background-position:center;"></span>
                                         {{ $settingData->normalDateStart }}～ 
                                        <span class="date_span_to" style="display: block;">
                                             {{ $settingData->normalDateEnd }}
                                        </span>                                     
                                    @endif
                                </span>
                            </div>
                        </li>
                        @endif
                    </ul>
                    <ul class="performanceDetail1_lists">
                        <li>
                            <div class="performanceDetail1_listsItemInner is-salesLink">
                                @if (!$settingData->salesPeriod)
                                  <span class="notice1_title">{{ trans('GETTIIS.S_NotSalesTerm') }}</span>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </section> <br>
        </main>

    </div><!-- /.l-document -->
    <script>

    var preview = new Vue({
        el: "#preview",
        data: {
            imageStatus   : '', 
            contentImage  : '',
            logoImage     : '',
        },
        watch: {

        },
        methods: {

        },
        mounted(){
            let previewData = sessionStorage.getItem('previewData')
            let json

            if(typeof(previewData) === "undefined"){

            }else{
                json                = JSON.parse(previewData)
                this.imageStatus    = json.status
                this.contentImage   = json.data.contentImage
                this.logoImage      = json.data.logoImage
            }
        }
    });
</script>
</body>

</html>