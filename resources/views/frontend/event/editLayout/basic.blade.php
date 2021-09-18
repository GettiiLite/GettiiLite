<div id="basisSetting">
    <!-- //basis-setting-wrap 基本情報專用class -->
    <div class="basis-setting-wrap">
        <div class="form-horizontal">
            <!-- 上方圖片區 -->
                <div class="row">
                    <!--  box4 統一樣式左邊單欄 ＋ 活動圖片區-->
                        <div class="col-md-8">
                            <div class="box no-border"> 
                                <div class="box-header with-border-non">
                                    <h3 class="box-title">{{ trans('events.S_eventDescriptionTitle') }}
                                    <div class="tip"><span data-tooltip="{{ trans('events.S_eventImageNotice') }}"><i class="fas fa-info fa-1x fa__thead"></i></span></div>
                                    </h3>
                                    <!--<div class="__remarks">▸ イベント詳細ページのTOPに表示されます</div>-->
                                    <!-- Radiobox -->
                                        <div class="box-tools pull-right">
                                            <div class="form-group-flex">
                                                <div class="form-checkbox">
                                                    <label class="control control--radio">
                                                        <input type="radio" name="contentType" v-model="contentType" value="image" :disabled="statucControl[0].top_content_type" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >{{ trans('events.S_image') }}
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="form-checkbox">
                                                    <label class="control control--radio">
                                                        <input type="radio" name="contentType" v-model="contentType" value="vidio" :disabled="statucControl[0].top_content_type" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' >{{ trans('events.S_Video') }}
                                                        <div class="control__indicator" :disabled="statucControl[0].top_content_type"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <!--/.Radiobox-->
                                </div>
                                <!-- box-body -->
                                <div class="box-body box-510 pt-x">
                                    <div class="col-md-12">
                                        <small class="subtitle">{{ trans('events.S_eventDescriptionTitleDesc')}}</small><!--  STS 2021/05/28 -->
                                        <!-- 圖片區 -->
                                            <div id="contentImageBox" class="drop-image"  v-show="(contentType == 'image')?true:false">
                                                <!--STS 2021/07/28 Task 41 -->
                                                <input type="file" id="contentImnage" name="imageshow" class="dropify-config" data-height="420" v-bind:data-default-file="contentImageShow" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' @change="imageUpload($event, 'content')" :disabled="statucControl[0].top_content_image"/>
                                            </div>
                                            <!--form5-->
                                            <div class="form-group mtb-2" v-show="(contentType == 'vidio')?true:false">
                                                <label class="col-md-3 control-label">{{ trans('events.S_VideoContent') }}<b>{{ trans('events.S_RequiredMark') }}</b></label>
                                                <div class="col-md-9">
                                                    <input name='top-content-url' type="text" class="form-control" v-validate="'max:250'"  maxlength="255" v-model="contentVidioUrl" :disabled="statucControl[0].top_content_url" placeholder="{{ trans('events.S_VideoContentPlaceholder') }}">
                                                    <span v-show="errors.has('top-content-url')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('top-content-url') }}</span>
                                                </div>
                                            </div>
                                            <!--/.form5-->
                                            <div class="form-group mtb-2">
                                                <label class="col-md-3 control-label">{{ trans('events.S_eventTopCommentTitle') }}</label>
                                                <div class="col-md-9">
                                                    <input name='top-content-comment' v-validate="'max:255'"  maxlength="250" type="" class="form-control" v-model="contentComment" :disabled="statucControl[0].top_content_comment" placeholder="{{ trans('events.S_eventTopCommentPlaceholder') }}">
                                                    <span v-show="errors.has('top-content-comment')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('top-content-comment') }}</span>
                                                     <p><small class="subtitle">{{ trans('events.S_eventTopCommentDesc') }}</small></p><!--  STS 2021/05/28 -->
                                                </div>
                                            </div>
                                            <!--<div class="__remarks text-right">▸ 画像・動画の下に表示されます</div>-->
                                        <!--圖片區-->           
                                    </div>
                                </div>
                            <!-- /.box-body -->
                            </div>
                        </div>
                    <!--  /.box4 統一樣式左邊單欄 ＋ 活動圖片區 -->
                    <!-- box1 統一樣式右邊單欄 + 列表圖片區 -->
                        <div class="col-md-4">
                            <div class="box no-border">
                                <div class="box-header with-border-non">
                                    <h3 class="box-title">{{ trans('events.S_basicInfoTitle') }}
                                        <div class="tip"><span data-tooltip="{{trans('events.S_eventImageNotice')}}"><i class="fas fa-info fa-1x fa__thead"></i></span></div>
                                    </h3>
                                    <!--<div class="__remarks">▸ イベント一覧で表示されます</div>-->
                                </div>
                                <div class="box-body box-510 pt-x">
                                    <div class="">
                                        <div class="col-md-12">
                                            <small class="subtitle">{{ trans('events.S_basicInfoTitleDesc')}}</small><!--  STS 2021/05/28 -->
                                            <div id="logoImage" class="drop-image">
                                                <!--STS 2021/07/28 Task 41 -->
                                                <input type="file" id="basisLogo" class="dropify-config" data-height="420" v-bind:data-default-file="eventLogo" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' @change="imageUpload($event, 'logo')" :disabled="statucControl[0].thumbnail"/>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.row-->
                                </div>
                            <!--/.box-body-->
                            </div> 
                        </div>          
                    <!-- /. box1 統一樣式右邊單欄 + 列表圖片區  -->
                </div>
            <!-- /.上方圖片區 -->
            <!-- box1 統一樣式 + 基本情報設定 -->
                <div class="box no-border">
                    <div class="box-header with-border-non form-group-flex">
                        <h3 class="box-title">{{ trans('events.S_basicInfoTitle') }}</h3>
                        <!-- 英文欄位 switch -->
                            <div class="form-checkbox">
                                <div class="checkbox checbox-switch switch-info">
                                    <label>
                                        <input type="checkbox" name="" v-model="enInformation.status.performanceStatus" :disabled="statucControl[0].basis">
                                        <span></span>{{ trans('events.S_eventDispEnglish') }}
                                    </label>
                                </div>          
                            </div>
                        <!-- /.英文欄位 switch -->
                    </div>
                    <div class="box-body">
                        <div class="form-horizontal">
                            <!---->
                                <div class="form-group">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventcodeTitle') }}<b>{{ trans('events.S_RequiredMark') }}</b></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control"  name="{{ trans('events.S_eventcodeTitle') }}" v-model="eventId" maxlength="10" v-validate="'required|alpha_num|min:4|max:10'" :disabled="statucControl[0].performance_code" placeholder="{{ trans('events.S_eventcodePlaceholder') }}">
                                        <span v-show="errors.has('{{ trans('events.S_eventcodeTitle') }}')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('{!! trans('events.S_eventcodeTitle') !!}') }}</span>
                                    </div>
                                </div>
                            <!---->
                                <div class="form-group mtb-1">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventMaintitleTitle') }}<b>{{ trans('events.S_RequiredMark') }}</b></label>
                                    <div class="col-md-10">
                                        <input id="eventTitle" type="text"  v-validate="'max:255|sej_format:21'" maxlength="250" name="event-title" class="form-control" v-model="eventTitle" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_eventMaintitlePlaceholder') }}">
                                        <span v-on:change="setting()" dusk="eventTitleValidate" v-show="errors.has('event-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('event-title') }}</span>
 										<!-- STS 2021/07/26 task 38 -->
                                         <span class="help is-danger" v-show="eventTitle && eventTitle != eventTitle.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                    </div>
                                </div>
                                <div class="form-group mtb-1" v-if="enInformation.status.performanceStatus">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventMaintitleTitle') }}(英)</label>
                                    <div class="col-md-10">
                                        <input type="text" name="event-title-en" v-validate="'max:255'" maxlength="250"  class="form-control" v-model="enInformation.data.performanceName" :disabled="statucControl[0].tenporary_info">
                                        <span v-show="errors.has('event-title-en')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('event-title-en') }}</span>
 										<!-- STS 2021/07/26 task 38 -->
                                         <span class="help is-danger" v-show="enInformation.data.performanceName && enInformation.data.performanceName != enInformation.data.performanceName.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                    </div>
                                </div>
                            <!---->
                                <div class="form-group mtb-1">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventSubtitleTitle') }}</label>
                                    <div class="col-md-10">
                                        <input id="eventSubTitle" type="text" v-validate="'max:255|sej_format:21'" maxlength="250" name="event-sub-title" class="form-control" v-model="eventSubTitle" :disabled="statucControl[0].performance_name_sub" placeholder="{{ trans('events.S_eventSubtitlePlaceholder') }}">
                                        <span dusk="eventSubTitleValidate" v-show="errors.has('event-sub-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('event-sub-title') }}</span>
 										<!-- STS 2021/07/26 task 38 -->
                                    	<span class="help is-danger" v-show="eventSubTitle && eventSubTitle != eventSubTitle.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                    </div>
                                </div>
                            <div class="form-group mtb-1" v-show="enInformation.status.performanceStatus">
                                <label for="" class="col-md-2 control-label">{{ trans('events.S_eventSubtitleTitle') }}(英)</label>
                                <div class="col-md-10">
                                    <input name='performance-name-sub' v-validate="'max:255'" maxlength="250" type="text" class="form-control" v-model="enInformation.data.performanceNameSub" :disabled="statucControl[0].performance_name_sub">
                                    <span v-show="errors.has('performance-name-sub')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('performance-name-sub') }}</span>
									<!-- STS 2021/07/26 task 38 -->
                                    <span class="help is-danger" v-show="enInformation.data.performanceNameSub && enInformation.data.performanceNameSub != enInformation.data.performanceNameSub.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                </div>
                            </div>
                            <!--//提示訊息 -->
                                <div class="form-group" v-show="dateError">
                                    <div class="col-md-12">
                                        <div class="col-md-9 callout callout-tip-warning mb-0">
                                            <!-- -->
                                            <div class="icon">
                                                <i class="fas fa-exclamation-circle fa-lg"></i> 
                                            </div>
                                            <p class="">@{{ dateErrorM }}</p>
                                        </div>
                                    </div>
                                </div>
                            <!--/.提示訊息 -->
                            <!-- 活動公開日 -->
                                <div class="form-group form-group-flex">
                                    <label class="col-sm-2 control-label">{{ trans('events.S_eventPublishDateTitle') }}</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input id="infOpenDate"  name="inf-open-date" v-validate=""  type="text" class="form-control pull-right" style="background-color: white;" :disabled="statucControl[0].disp_start" readonly>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <span dusk="infOpenDate" v-show="errors.has('inf-open-date')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('inf-open-date') }}</span>
                                    </div>
                                </div>
                            <!--/.活動公開日-->
                            <!-- 活動結束日 -->
                            {{-- 
                                <!-- Disable at hotfix_R06 -->
                                <div class="form-group form-group-flex-normal">
                                    <label class="col-sm-2 control-label">{{ trans('events.S_eventEndDateTitle') }}</label>
                                    <div class="col-sm-10">
                                            <div class="form-group-flex-normal">
                                                <div class="form-checkbox">
                                                    <label class="control control--radio">
                                                        <input type="radio" name="contentType" id="" value="All" :disabled="statucControl[0].basis" v-model='dateEnd.setFlg'>無期限
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="form-checkbox pl-20" v-show='infOpenDate !== ""'>
                                                    <label class="control control--radio">
                                                        <input type="radio" name="contentType" id="" value="EndDate" :disabled="statucControl[0].basis" v-model='dateEnd.setFlg'>情報公開終了日
                                                        <div class="control__indicator"></div>
                                                    </label> 
                                                </div>  
                                                <div class="w-100" v-show='infOpenDate !== ""'>
                                                    <div class="input-group">
                                                        <input id="end-date" name="inf-end-date" type="text" :disabled="dateEnd.setFlg !== 'EndDate'" class="form-control pull-right" aria-required="false" aria-invalid="false" style="background-color: white;" readonly> 
                                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                    </div> 
                                                    <span class="help is-danger" style="display: none;"></span>
                                                </div>
                                            </div> 
                                    </div>
                                    <!--/.-->
                                </div>
                            <!--/.活動結束日-->
                             --}}
                            <!-- 活動期間 -->
                                <div class="form-group form-group-flex">
                                    <label class="col-sm-2 control-label">{{ trans('events.S_eventPeriodTitle') }}</label>
                                    <!-- 20201201欄位調整 -->
                                    <div class="col-sm-5">
                                        <div class="input-group" id="" data-target-input="nearest">
                                            <input id="performance_st_dt"  name="" v-validate=""  type="text" class="form-control pull-right" data-target="" :disabled="statucControl[0].performance_dt" readonly>
                                            <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <span dusk="" v-show="" class="help is-danger"><i class="fas fa-exclamation-circle"></i> </span>
                                    </div>
                                    <div class="col-auto text-center font-700 plr-15"> ∼ </div>
                                    <div class="col-sm-5">
                                        <div class="input-group" id="" data-target-input="nearest"> 
                                            <input id="performance_end_dt"  name="" v-validate="" type="text" class="form-control pull-right" :disabled="!performance_st_dt || statucControl[0].performance_dt" data-target="" readonly>
                                            <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                        </div>
                                        <span dusk="" v-show="" class="help is-danger"><i class="fas fa-exclamation-circle"></i> </span>
                                    </div>
                                </div>
                            <!--/.活動期間-->
                            <!--活動類型  0410新增調整-->
                                <div class="form-group">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventTypeTitle') }}</label>
                                    <div class="col-md-10">
                                        <!-- 舞台劇 ｜ 劇場-->
                                        <a class="btn btn-app" :class="{ active: eventType == 300 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(300)">
                                            <span class="badge bg-aqua" v-if="eventType == 300">
                                                <i class="fa fa-check"></i>
                                            </span>
                                           <!-- <i class="fa fa-edit"></i>-->
                                           <i class="active-style"><img src="/assets/images/icon/i-stage.svg"></i>
                                            {{ trans('common.S_eventType_300') }}   
                                        </a>
                                        <!-- 運動賽事 -->
                                        <a class="btn btn-app" :class="{ active: eventType == 200 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(200)">
                                            <span class="badge bg-aqua" v-if="eventType == 200">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <!--<i class="fa fa-envelope"></i>-->
                                            <i class="active-style"><img src="/assets/images/icon/i-sport.svg"></i>
                                            {{ trans('common.S_eventType_200') }}   
                                        </a>
                                        <!--電影--> 
                                        <a class="btn btn-app" :class="{ active: eventType == 700 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(700)">
                                            <span class="badge bg-aqua" v-if="eventType == 700">
                                                <i class="fa fa-check"></i>
                                            </span>
                                           <!-- <i class="fa fa-edit"></i>-->
                                            <i class="active-style"><img src="/assets/images/icon/i-movie.svg"></i>
                                            {{ trans('common.S_eventType_700') }}   
                                        </a>
                                        <!--活動 ｜ 展覽 ｜藝術-->
                                        <a class="btn btn-app" :class="{ active: eventType == 500 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(500)">
                                            <span class="badge bg-aqua" v-if="eventType == 500">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <!--<i class="fa fa-edit"></i>-->
                                            <i class="active-style"><img src="/assets/images/icon/i-museum.svg"></i>
                                            {{ trans('common.S_eventType_500') }}   
                                        </a>
                                        <!--音樂演唱會-->
                                        <a class="btn btn-app" :class="{ active: eventType == 100 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(100)">
                                            <span class="badge bg-aqua" v-if="eventType == 100">
                                                <i class="fa fa-check"></i>
                                            </span>
                                           <!-- <i class="fa fa-edit"></i>-->
                                             <i class="active-style"><img src="/assets/images/icon/i-piano.svg"></i>
                                            {{ trans('common.S_eventType_100') }}   
                                        </a>
                                        <!--休閒 ｜ 娛樂-->
                                        <a class="btn btn-app" :class="{ active: eventType == 600 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(600)">
                                            <span class="badge bg-aqua" v-if="eventType == 600">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <!--<i class="fa fa-edit"></i>-->
                                            <i class="active-style"><img src="/assets/images/icon/i-goround.svg"></i>
                                            {{ trans('common.S_eventType_600') }}   
                                        </a>
                                        <!--古典 ｜ 歌劇-->
                                        <a class="btn btn-app" :class="{ active: eventType == 400 }"  v-bind:style="pointerEvents" v-on:click="eventTypeSelect(400)">
                                            <span class="badge bg-aqua" v-if="eventType == 400">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <!--<i class="fa fa-edit"></i>-->
                                            <i class="active-style"><img src="/assets/images/icon/i-masks.svg"></i>
                                            {{ trans('common.S_eventType_400') }}   
                                        </a>
                                        <!--節慶祭典-->
                                        <a class="btn btn-app" :class="{ active: eventType == 1000 }" v-on:click="eventTypeSelect(1000)">
                                            <span class="badge bg-aqua" v-if="eventType == 1000">
                                                <i class="fa fa-check"></i> 
                                            </span>
                                            <i class="active-style"><img src="/assets/images/icon/i-festival.svg"></i>
                                            {{ trans('common.S_eventType_1000') }}   
                                        </a>
                                        <!--其他-->
                                        <a class="btn btn-app" :class="{ active: eventType == 900 }" v-bind:style="pointerEvents" v-on:click="eventTypeSelect(900)">
                                            <span class="badge bg-aqua" v-if="eventType == 900">
                                                <i class="fa fa-check"></i> 
                                            </span>
                                            <!--<i class="fa fa-edit"></i>-->
                                            <i class="active-style"><img src="/assets/images/icon/i-others.svg"></i>
                                            {{ trans('common.S_eventType_900') }}   
                                        </a>
                                    </div>
                                </div> 
                            <!--/.活動類型  0410新增調整-->
                        </div>
                    </div>
                </div>
            <!-- /. box1 統一樣式 + 基本情報設定  -->  

            <!--  box2 統一樣式 + 活動日期 20200818調整 -->
                <div class="box no-border">
                    <!-- Block 1 -->
                        <div class="box-header with-border-non form-group-flex">
                            <h3 class="box-title">{{ trans('events.S_eventDatetimeTitle') }}</h3>
                        </div>
                        <!--//提示訊息 -->
                                <div class="row" v-show="advancedDateError">
                                    <div class="col-md-12">
                                        <div class="col-md-9 callout callout-tip-warning mb-0">
                                            <!-- -->
                                            <div class="icon">
                                                <i class="fas fa-exclamation-circle fa-lg"></i> 
                                            </div>
                                            <p class="">@{{ advancedDateErrorM }}</p>
                                        </div>
                                    </div>
                                </div>
                            <!--/.提示訊息 -->
                    <!-- Form -->
                        <div class="form-horizontal">
                                    <div class="box-body">
                                        <!--form3-->
                                            <div class="form-group form-group-flex">
                                                <div class="form-checkbox col-sm-1">
                                                    <label class="control control--checkbox pull-right mtb-1">
                                                        <input v-if="infOpenIsNull" type="checkbox" v-model="earlyBirdDateChecked" :disabled="!performance_end_dt || statucControl[0].reserve_dt">{{ trans('events.S_eventPresaleTitle') }}
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <label class="col-sm-2 control-label text-left">{{ trans('events.S_eventSellDateTitle') }}</label>
                                                <!-- 20201201欄位調整 -->
                                                <div class="col-sm-4 pr-x">
                                                    <div class="input-group" data-target-input="nearest">
                                                        <input id="earlyBirdDateStart"  name="" v-validate="" v-bind:disabled='!earlyBirdDateChecked || statucControl[0].reserve_dt'  type="text" class="form-control pull-right" data-target="" readonly>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <span dusk="" v-show="" class="help is-danger"><i class="fas fa-exclamation-circle"></i> </span>
                                                </div>
                                                <div class="col-sm-1 text-center font-700 plr-x"> ∼ </div>
                                                <div class="col-sm-4 pl-x">
                                                    <div class="input-group" data-target-input="nearest">
                                                        <input id="earlyBirdDateEnd"  name="" v-validate=""  v-bind:disabled='!earlyBirdDateStart || !earlyBirdDateChecked || statucControl[0].reserve_dt' type="text" class="form-control pull-right" data-target="" readonly>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <span dusk="" v-show="" class="help is-danger"><i class="fas fa-exclamation-circle"></i> </span>
                                                </div>
                                            </div>
                                        <!--/.form3-->
                                        <!--form4-->
                                            <div class="form-group form-group-flex">
                                                <div class="form-checkbox col-sm-1">
                                                    <label class="control control--checkbox pull-right mtb-1">
                                                        <input  v-if="infOpenIsNull" type="checkbox" v-model="normalDateChecked" :disabled="!performance_end_dt || statucControl[0].sell_dt">{{ trans('events.S_eventSaleTitle') }}
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <label class="col-sm-2 control-label text-left">{{ trans('events.S_eventSellDateTitle') }}</label>
                                                <!-- 20201201欄位調整 -->
                                                <div class="col-sm-4 pr-x">
                                                    <div class="input-group" id="" data-target-input="nearest">
                                                        <input id="normalDateStart"  name="" v-validate=""  v-bind:disabled='!normalDateChecked || statucControl[0].reserve_dt' type="text" class="form-control pull-right" data-target="" readonly>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <span dusk="" v-show="" class="help is-danger"><i class="fas fa-exclamation-circle"></i> </span>
                                                </div>
                                                <div class="col-sm-1 text-center font-700 plr-x"> ∼ </div>
                                                <div class="col-sm-4 pl-x">
                                                    <div class="input-group" id="" data-target-input="nearest">
                                                        <input id="normalDateEnd"  name="" v-validate=""  v-bind:disabled='!normalDateStart || !normalDateChecked || statucControl[0].reserve_dt'  type="text" class="form-control pull-right" data-target="" readonly>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <span dusk="" v-show="" class="help is-danger"><i class="fas fa-exclamation-circle"></i> </span>
                                                </div>
                                            </div>
                                        <!--/.form4-->
                                    </div>
                                </div>
                        <!-- /.Form -->
                </div>
            <!--  /.box2 統一樣式 + 活動日期 -->

            <!--  box3 統一樣式 + 活動場館 -->
                <div class="box no-border">
                    <!-- Block 1 -->
                        <div class="box-header with-border-non form-group-flex">
                            <h3 class="box-title">{{ trans('events.S_eventHallInfoTitle') }}</h3>
                            <!-- 英文欄位 switch -->
                                <div class="form-checkbox">
                                    <div class="checkbox checbox-switch switch-info">
                                        <label>
                                            <input type="checkbox" name="" v-model="enInformation.status.hallStatus" :disabled="statucControl[0].basis">
                                            <span></span>{{ trans('events.S_eventDispEnglish') }}
                                        </label>
                                    </div>          
                                </div>
                            <!-- /.英文欄位 switch -->
                        </div>
                    <!-- Form -->
                        <div class="form-horizontal">
                            <div class="box-body">
                                <div class="col-md-12">   
                                    <!--form1-->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">{{ trans('events.S_eventHallNameTitle') }}<b>{{ trans('events.S_RequiredMark') }}</b></label>
                                            <div class="col-md-10">
                                                <input id="placeTitle" v-validate="'max:80'" maxlength="80" name="place-name"  type="text" class="form-control" v-model="locationName" :disabled="hallDisable || statucControl[0].hall_name" placeholder="{{ trans('events.S_eventHallNamePlaceholder') }}">
                                                <span dusk="placeTitleValidate" v-show="errors.has('place-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('place-name') }}</span>
                                            </div>
                                        </div>
                                    <!--/.form1-->
                                    {{-- <!--form2-->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">会場名カナ</label>
                                            <div class="col-md-10">
                                                <input id="placeKanaTitle" v-validate="'required|max:160'" maxlength="160" name="place-name-kana" type="text" class="form-control" v-model="locationNameKana" :disabled="hallDisable" placeholder="会場名カナ">
                                                <span dusk="placeKanaTitleValidate" v-show="errors.has('place-name-kana')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('place-name-kana') }}</span>
                                            </div>
                                        </div>
                                    <!--/.form2--> --}}
                                    <!--/.col-md-12-->
                                    <!--form2-->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">{{ trans('events.S_eventHallDisplayTitle') }}<b>{{ trans('events.S_RequiredMark') }}</b></label>
                                            <div class="col-md-10">
                                                <input name="hall-name" type="" class="form-control" v-validate="'max:80'" maxlength="80" placeholder="{{ trans('events.S_eventHallDisplayPlaceholder') }}" v-model="hallName" :disabled="statucControl[0].hall_name_set">
                                                <span dusk="placeKanaTitleValidate" v-show="errors.has('hall-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('hall-name') }}</span>
                                                <!-- STS 2021/07/26 task 38 -->
                                                <span class="help is-danger" v-show="hallName && hallName != hallName.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                            </div>
                                        </div>
                                    <!--/.form2-->
                                    <!--form2 英文-->
                                        <div class="form-group" v-show="enInformation.status.hallStatus">
                                            <label class="col-md-2 control-label">{{ trans('events.S_eventHallDisplayTitle') }}(英)</label>
                                            <div class="col-md-10">
                                                <input name='hall-disp-name' v-validate="'max:100'" maxlength="100" type="" class="form-control" v-model="enInformation.data.hallDispName" :disabled="statucControl[0].hall_name_set">
                                                <span v-show="errors.has('hall-disp-name')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('hall-disp-name') }}</span>
                                                <!-- STS 2021/07/26 task 38 -->
                                                <span class="help is-danger" v-show="enInformation.data.hallDispName && enInformation.data.hallDispName != enInformation.data.hallDispName.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                            </div>
                                        </div>
                                    <!--/.form2-->
                                    <!--form3-->
                                        <div class="row city-selector-set-has-value">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">{{ trans('events.S_eventHallLocationTitle') }}</label>
                                                    <input id="zipcode" class="zipcode" v-show="false" > 
                                                    <div class="col-md-8">
                                                        <select class="form-control select2 county" style="width: 100%;" :disabled="hallDisable || statucControl[0].prefecture" v-model="country">
                                                        <!--  <option>--</option>-->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">{{ trans('events.S_eventHallSublocationTitle') }}</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control select2 district" :disabled="hallDisable || statucControl[0].city" style="width: 100%;" v-model="city">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!--/.form3-->
                                    <!--form4-->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">{{ trans('events.S_eventHallURLTitle') }}</label>
                                            <div class="col-md-10">
                                                <input id="placeUrl" v-validate="'url:require_protocol|max:200'" maxlength="200" :disabled="hallDisable || statucControl[0].hall_url" name="place-url" type="text" class="form-control" v-model="localUrl" placeholder="{{ trans('events.S_eventHallURLPlaceholder') }}">
                                                <span dusk="placeUrlValidate" v-show="errors.has('place-url')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('place-url') }}</span>
                                            </div>
                                        </div>
                                    <!--/.form4-->
                                    <!--form5-->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">{{ trans('events.S_eventHallNoteTitle') }}</label>
                                            <div class="col-md-10">
                                                <input name='hall-disable' type="text" class="form-control" :disabled="hallDisable || statucControl[0].description" v-validate="'max:80'" maxlength="80" v-model="locationDescription" placeholder="{{ trans('events.S_eventHallNotePlaceholder') }}">
                                                <span v-show="errors.has('hall-disable')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('hall-disable') }}</span>
                                            </div>
                                        </div>
                                    <!--/.form5-->
                                </div>
                            </div>
                        </div>
                        <!-- /.Form -->
                    <div class="box-footer text-right" v-show="!statucControl[0].hall_name">
                        <button type="button" class="btn waves-effect waves-light btn-angle btn-info-outline" v-on:click="clearHill()">{{ trans('events.S_eventClearHallBtn') }}</button>
                        <button type="button" class="btn waves-effect waves-light btn-angle btn-info" v-on:click="openDialog()" data-toggle="modal" data-target="#user-select">{{ trans('events.S_eventHavingHallBtn') }}</button>
                    </div>
                </div>
            <!--  /.box3 統一樣式 + 活動場館 -->

             <!--  box4 統一樣式 + 活動聯絡資訊 20200818調整 -->
                <div class="box no-border">
                    <!-- Block 1 -->
                        <div class="box-header with-border-non form-group-flex">
                            <h3 class="box-title">{{ trans('events.S_eventContactInfoTitle') }}</h3>
                            <!-- 英文欄位 switch -->
                                    <div class="form-checkbox">
                                        <div class="checkbox checbox-switch switch-info">
                                            <label>
                                                <input type="checkbox" name="" v-model="enInformation.status.informationStatus" :disabled="statucControl[0].basis">
                                                <span></span>{{ trans('events.S_eventDispEnglish') }}
                                            </label>
                                        </div>          
                                    </div>
                                <!-- /.英文欄位 switch -->
                        </div>
                        
                    <!-- Form -->
                    <div class="form-horizontal">
                       <div class="box-body">
                                <div class="form-group">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventContactNameTitle') }}<b>{{ trans('events.S_RequiredMark') }}</b></label> <!-- STS 2021/08/25 Task 47 -->
                                    <div class="col-md-10">
                                        <input id="customerInfTitle" type="text"  v-validate="'max:80'" maxlength="80"  name="title" class="form-control" v-model="eventContact" :disabled="statucControl[0].information_nm" placeholder="{{ trans('events.S_eventContactNamePlaceholder') }}">
                                        <span dusk="customerInfTitleValidate" v-show="errors.has('title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('title') }}</span>
                                        <!-- STS 2021/07/26 task 38 -->
                                        <span class="help is-danger" v-show="eventContact && eventContact != eventContact.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                    </div>
                                </div>
                                <div class="form-group" v-show="enInformation.status.informationStatus">
                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_eventContactNameTitle') }}(英)</label>
                                    <div class="col-md-10">
                                        <input name='information-name-en' v-validate="'max:80'"  maxlength="80" type="text"   class="form-control" v-model="enInformation.data.informationNm" :disabled="statucControl[0].information_nm">
                                        <span v-show="errors.has('information-name-en')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('information-name-en') }}</span>
									    <!-- STS 2021/07/26 task 38 -->
                                        <span class="help is-danger" v-show="enInformation.data.informationNm && enInformation.data.informationNm != enInformation.data.informationNm.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> {{ trans('events.S_basicerrorMsn') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{ trans('events.S_eventContactEmailTitle') }}</label>
                                    <div class="col-md-10">
                                        <input name="email"  type="text" style="display:none">
                                        <input id="customerInfEmail" v-validate="'email|max:200'"  maxlength="200"  name="email"  type="text" class="form-control" v-model="eventContactMail" :disabled="statucControl[0].mail_address" placeholder="{{ trans('events.S_eventContactEmailPlaceholder') }}" >
                                        <span dusk="customerInfEmailValidate" v-show="errors.has('email')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{ trans('events.S_eventContactTelTitle') }}</label>
                                    <div class="col-md-10">
                                        <input id="customerInfTel" v-validate="'tel_format|min:10|max:13'"  maxlength="13"  name="tel" type="tel" class="form-control" v-model="eventContactTel" :disabled="statucControl[0].information_tel" placeholder="{{ trans('events.S_eventContactTelPlaceholder') }}">
                                        <span dusk="customerInfTelValidate" v-show="errors.has('tel')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('tel') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                        <!-- /.Form -->
                </div>
            </div>
            <!--  /.box4 統一樣式 + 活動聯絡資訊 -->

            <!-- ===============================================以下進階設定=========================================== --> 
            
            <!--  Accordion 統一樣式 + 進階設定 -->
            <details>
                <summary>
                    <span class="summary-title">{{ trans('events.S_eventAdvanced') }}</span>
                    <i class="summary-chevron-up"></i>
                </summary>
                <div class="summary-content">
                    <!--0602 非會員-->
                    <div class="box no-border no-radius">
                        <div class="box-header box-s4 with-border"><h3 class="box-title">{{ trans('events.S_nonMemberTitle') }}</h3></div> 
                            <div class="form-horizontal">
                                <div class="box-body pad">
                                    <div class="form-checkbox">
                                        <div class="checkbox checbox-switch checbox-switch-non switch-info">
                                            <label><input type="checkbox" :disabled="statucControl[0].basis" v-model='sellAnyone'> <span></span>{{ trans('events.S_nonMemberSwitch') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.-->
                    <!--  box5 統一樣式 + 活動介紹 -->
                        <div class="box no-border no-radius">
                            <div class="box-header box-s4 with-border">
                                <h3 class="box-title">{{ trans('events.S_eventIntroTitle') }}</h3>
                            </div>
                            <!-- Form -->
                                <div class="form-horizontal">
                                    <div class="box-body pad" v-bind:style="pointerEvents">
                                        <form>
                                        
                                            <textarea name="content"  id="editor" class="editor"  v-on:blur="editData()" v-model='editContent'>
                                                @{{ editContent}}
                                            </textarea>
                                            <div class="editor-count-area">
                                            ▶︎ {{ trans('events.S_inputLength') }} ： @{{ wordCount['editor'].total }}  （{{ trans('events.S_limitLength') }} ： @{{ wordCount['editor'].limit}}）<span class="editor-count-error" v-show='wordCount["editor"].errorStatus'> <i class="fas fa-exclamation-circle"></i> {{ trans('events.S_limitLengthOver') }} @{{ this.wordCount['editor'].overTotal }}</span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <!---->
                            <!---box-footer --->
                            <!-- 1210 移除調整-->
                            <!--<div class="box-footer">
                                <button id="preview"  type="button" class="btn waves-effect waves-light btn-angle btn-info">{{ trans('events.S_eventPreviewBtn') }}</button>
                            </div>-->
                            <!---/.box-footer --->
                        </div>
                        <!--  box5 統一樣式 + フリーアンケート #1757 2021/03/20 LS-Itabashi -->
                        <!--  box5 統一樣式 + フリーアンケート- Change position STS 2021/06/09 Task 15 Start -->
                        <!--  STS 2021/06/23 Change appearance location: Line 600 -> 629-->
                         <!--Title-->
                        <div class="box no-border no-radius no-border-bottom">
                            <div class="box-header with-border box-s4">
                                <h3 class="box-title">
                                    {{trans('events.S_FreeQuestionTitle')}}
                                </h3>
                            </div>
                        </div>
                        <!--/.Title--> 
                        <!-- sortable-wraper -->
                        <div class="sortable-wraper"><!--  v-show="saleType == 1" STS Task 15-2 -->
                            <div class="questionnaire-sortable-box-wraper ui-sortable">
                                <div v-bind:id="qIndex" v-for="(questionnaire, qIndex) in questionnaires">
                                    <!--Box -->
                                    <div class="box box-solid"> 
                                        <!--Box header -->
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                <!-- 使用する switch -->
                                                <div class="form-checkbox">
                                                    <div class="checkbox checbox-switch switch-info checbox-switch-non">
                                                        <label>
                                                            <input type="checkbox" name="" v-model="questionnaire.use" :disabled="statucControl[0].basis">
                                                            <span></span>
                                                        </label>
                                                        <h3 class="box-title">@{{questionnaire.langs.ja.title}}</h3>
                                                    </div>          
                                                </div>
                                                <!-- /.使用する switch -->
                                            </h3>
                                            <div class="box-tools pull-right" v-show='!statucControl[0].basis'>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                <button type="button" class="btn btn-box-tool" data-widget="remove" v-on:click="deleteQuestionnaire(qIndex)" :disabled="statucControl[0].disp_start"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <!--/.Box header -->

                                        <!-- Form -->
                                        <div class="form-horizontal">
                                            <div class="box-body" v-bind:style="pointerEvents">
                                                <!-- ja -->
                                                <div class="form-group">
                                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_FreeQuestionCapt') }}</label>
                                                    <div class="col-md-10">
                                                        <input :id="'questionTitle-ja-' + qIndex" type="text" v-validate="'max:255'" maxlength="255" name="question-title" class="form-control" v-model="questionnaire.langs.ja.title" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_FreeQuestionCaptPlaceholder') }}">
                                                        <span v-show="errors.has('question-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('question-title') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">{{ trans('events.S_FreeQuestionDesc') }}</label>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" rows="4" v-validate="'max:2000'" style="resize:none" maxlength="2000" name="question-desc" v-model='questionnaire.langs.ja.text' :disabled="statucControl[0].basis" placeholder="{{ trans('events.S_FreeQuestionDescPlaceholder') }}"></textarea>
                                                        <span v-show="errors.has('question-desc')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('question-desc') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-md-2 control-label">{{ trans('events.S_FreeQuestionAns') }}</label>
                                                    <div class="col-md-10">
                                                        <div class="row">
                                                            <div class="col-md-2 control-label-checkbox">
                                                                <label class="control control--checkbox" >
                                                                    <input type="checkbox" v-model="questionnaire.required" :disabled="statucControl[0].basis">{{ trans('events.S_FreeQuestionRequired')}}
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input :id="'answerPlaceholder-ja-' + qIndex" type="text" v-validate="'max:255'" maxlength="255" name="answer-placeholder" class="form-control" v-model="questionnaire.langs.ja.placeholder" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_FreeQuestionAnsPlaceholder') }}">
                                                                <span v-show="errors.has('answer-placeholder')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('answer-placeholder') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- en -->
                                                <div v-show="questionnaire.langs.en.selected">
                                                    <div class="form-group">
                                                        <label for="" class="col-md-2 control-label">{{ trans('events.S_FreeQuestionCapt') }}(英)</label>
                                                        <div class="col-md-10">
                                                            <input :id="'questionTitle-en-' + qIndex" type="text" v-validate="'max:255'" maxlength="255" name="question-title" class="form-control" v-model="questionnaire.langs.en.title" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_FreeQuestionCaptPlaceholder') }}">
                                                            <span v-on:change="setting()" dusk="" v-show="errors.has('question-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('question-title') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label">{{ trans('events.S_FreeQuestionDesc') }}(英)</label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" rows="4" v-validate="'max:2000'" style="resize:none" maxlength="2000" name="question-desc" v-model='questionnaire.langs.en.text' :disabled="statucControl[0].basis" placeholder="{{ trans('events.S_FreeQuestionDescPlaceholder') }}"></textarea>
                                                            <span v-on:change="setting()" dusk="" v-show="errors.has('question-desc')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('question-desc') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="" class="col-md-2 control-label">{{ trans('events.S_FreeQuestionAns') }}(英)</label>
                                                        
                                                        <div class="col-md-10">
                                                            <div class="row">
                                                                <div class="col-md-2 control-label-checkbox">
                                                                    <label class="control control--checkbox" >
                                                                        <input type="checkbox" v-model="questionnaire.required" :disabled="statucControl[0].basis">{{ trans('events.S_FreeQuestionRequired')}}
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <input :id="'answerPlaceholder-en-' + qIndex" type="text" v-validate="'max:255'" maxlength="255" name="answer-placeholder" class="form-control" v-model="questionnaire.langs.en.placeholder" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_FreeQuestionAnsPlaceholder') }}">
                                                                    <span v-on:change="setting()" dusk="" v-show="errors.has('answer-placeholder')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('answer-placeholder') }}</span>
                                                                </div>
                                                            </div>   
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- zh-tw -->
                                                <div v-show="questionnaire.langs.zh_tw.selected">
                                                    <div class="form-group">
                                                        <label for="" class="col-md-2 control-label">{{ trans('events.S_FreeQuestionCapt') }}(中)</label>
                                                        <div class="col-md-10">
                                                            <input :id="'questionTitle-zh-tw-' + qIndex" type="text" v-validate="'max:255'" maxlength="255" name="question-title" class="form-control" v-model="questionnaire.langs.zh_tw.title" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_FreeQuestionCaptPlaceholder') }}">
                                                            <span v-on:change="setting()" dusk="" v-show="errors.has('question-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('question-title') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label">{{ trans('events.S_FreeQuestionDesc') }}(中)</label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" rows="4" v-validate="'max:2000'" style="resize:none" maxlength="2000" name="question-desc" v-model='questionnaire.langs.zh_tw.text' :disabled="statucControl[0].basis" placeholder="{{ trans('events.S_FreeQuestionDescPlaceholder') }}"></textarea>
                                                            <span v-on:change="setting()" dusk="" v-show="errors.has('question-desc')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('question-desc') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="" class="col-md-2 control-label">{{ trans('events.S_FreeQuestionAns') }}(中)</label>
                                                        <div class="col-md-10">
                                                            <div class="row">
                                                                <div class="col-md-2 control-label-checkbox">
                                                                    <label class="control control--checkbox" >
                                                                        <input type="checkbox" v-model="questionnaire.required" :disabled="statucControl[0].basis">{{ trans('events.S_FreeQuestionRequired')}}
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <input :id="'answerPlaceholder-zh-tw-' + qIndex" type="text" v-validate="'max:255'" maxlength="255" name="answer-placeholder" class="form-control" v-model="questionnaire.langs.zh_tw.placeholder" :disabled="statucControl[0].tenporary_info" placeholder="{{ trans('events.S_FreeQuestionAnsPlaceholder') }}">
                                                                    <span v-on:change="setting()" dusk="" v-show="errors.has('answer-placeholder')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ errors.first('answer-placeholder') }}</span>
                                                                </div>
                                                            </div>   
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.Box body -->
                                        </div>
                                        <!-- /.Form -->
                                    </div> 
                                    <!--/.Box -->
                                </div>
                                <!-- /.V-For  -->
                            </div>
                            <!-- /.sortable-box-wraper  -->
                            <p id="log"></p>
                        </div>
                        <!--/.sortable-wraper-->
                        <!--  /.box5 統一樣式 + フリーアンケート STS 2021/06/09 Task 15 End -->
                        <!--  /.box5 統一樣式 + フリーアンケート -->
                         <!-- 新增活動按鈕 置底-->
                        <div class="btn-group mb-5">
                            <button  v-on:click="addQuestionnaire"class="btn btn-block waves-effect waves-light btn-add":disabled=" statucControl[0].basis || limitQuestionnaire == false "readonly>{{ trans('events.S_eventAddQuestionnaire') }}</button>
                            {{-- <button  v-on:click="addContent" class="btn btn-block waves-effect waves-light btn-add" :disabled="statucControl[0].basis">{{trans('events.S_eventAddArticle')}}</button> --}}
                        </div>
                        <!--/.新增活動按鈕 置底-->
                        <!--  box5 統一樣式 + 活動介紹 -->
                    <!--  /.box5 統一樣式 + 活動介紹 -->

                          <!-- 0714 新增 box4 統一樣式 -->
                          <!--  內文自動翻譯 -->
                          
                          <div class="box no-border no-radius">
                            <div class="box-header box-s4 with-border">
                              <h3 class="box-title">{{ trans('events.S_eventAutoTranslate') }}</h3>
                            </div>
                            <!-- Form -->
                            <div class="form-horizontal">
                              <div class="box-body pad" v-bind:style="pointerEvents">
                                <div class="form-checkbox">
                                  <div class="checkbox checbox-switch checbox-switch-non switch-info"><label><input type="checkbox"  v-model="autoTransChecked" :disabled="statucControl[0].basis"> <span></span>{{ trans('events.S_eventAutoTranslateCheck') }}
                                    </label>
                                  </div>
                                </div>
                                <div class="col-md-12 form-checkbox-groupbox">
                                  <div class="form-checkbox-title">{{ trans('events.S_eventSlectLanguage') }}</div>
                                    <div class="col-md-2 form-checkbox">
                                      <label class="control control--checkbox" >
                                        <input type="checkbox" v-model="autoTransZhtw" :disabled="statucControl[0].basis">{{ trans('events.S_eventTranslateZhtw') }}
                                        <div class="control__indicator"></div>
                                      </label>
                                    </div>
                                    <div class="col-md-2 form-checkbox">
                                      <label class="control control--checkbox" >
                                        <input type="checkbox" v-model="autoTransEng" :disabled="statucControl[0].basis">{{ trans('events.S_eventTranslateEng') }}
                                        <div class="control__indicator"></div>
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <!--  /.內文自動翻譯 -->
                          <!--  0714 新增 box4 統一樣式 -->
                    <!-- 1225 新增  start-->
                    <!--  box7 統一樣式 + 關鍵字 -->
                        <div class="box no-border no-radius">
                            <div class="box-header box-s4 with-border">
                                <h3 class="box-title">KEYWORDS</h3>
                            </div>
                            <!-- Form -->
                                <div class="form-horizontal">
                                    <div class="box-body pad" v-bind:style="pointerEvents">
                                        <form :disabled="statucControl[0].basis">
                                            <input name="keywords"  maxlength="500" id="keywords"  v-model='keywords' size="1" />
                                        </form>
                                    </div>
                                </div>
                            <!-- /.Form -->
                        </div>
                    <!--  /.box7 統一樣式 + 關鍵字 --> 
                    <!-- 1225 新增  end-->
                    <!--  box8 統一樣式 + 記事タイトル -->
                        <div class="box no-border no-radius">
                            <div class="box-header box-s4 with-border">
                                <h3 class="box-title">記事タイトル</h3>
                            </div>
                            <!-- Form -->
                                <div class="form-horizontal">
                                    <div class="box-body pad" v-bind:style="pointerEvents">
                                        <form>  
                                           <textarea class="form-control" rows="4" maxlength="255" v-model='articleTitle' :disabled="statucControl[0].basis"></textarea>
                                        </form>
                                    </div>
                                </div>
                            <!-- /.Form -->
                        </div>
                    <!--  /.box8 統一樣式 + 記事タイトル --> 
                    
                    <!-- 1210 新增  -->
                    <!--  box6 統一樣式 + 活動說明＆新增 --> 
                    <!--Title-->
                        <div class="box no-border no-radius no-border-bottom">
                            <div class="box-header with-border box-s4">
                                <h3 class="box-title">
                                    {{trans('events.S_eventArticle')}}
                                    <div class="tip"><span data-tooltip="{{trans('events.S_eventArticleNotice')}}"><i class="fas fa-info fa-1x fa__thead"></i></span></div>
                                </h3>
                            </div>
                        </div>
                    <!--/.Title--> 
                    <!-- sortable-wraper -->
                        <div class="sortable-wraper">
                            <div class="sortable-box-wraper ui-sortable">
                                <div v-bind:id="index" v-for="(article, index) in articles" v-cloak>
                                    <!--   box6 統一樣式 活動說明新增-->
                                        <div class="box box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title"><div class="item-cicle">@{{ index + 1 }}</div></h3>
                                                <div class="box-tools pull-right" v-show='!statucControl[0].basis'>
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                    <button type="button" class="btn btn-box-tool" data-widget="remove" v-on:click="deleteContent(index)"><i class="fa fa-times"></i></button>
                                                    <!--<button type="button" class="btn btn-box-tool" v-on:click="deleteContent(index)"><i class="fa fa-times"></i></button>-->
                                                </div>
                                            </div>
                                            <!-- Form -->
                                                <div class="form-horizontal">
                                                    <div class="box-body" v-bind:style="pointerEvents">
                                                        <div class="col-md-12">
                                                            <form>
                                                                <textarea name="article"  v-bind:id="'editor' +  index" v-on:blur="editData()" v-model='article.text'>                                    
                                                                @{{ article.text}}
                                                                </textarea>
                                                            </form>
                                                            <!--圖片與影片-->
                                                                <div class="form-group-flex mb-4">
                                                                    <div class="form-checkbox">
                                                                    <label class="control control--radio" v-bind:for="'content_type' + index + '-1'">
                                                                        <input v-model="article.type" v-bind:name="'type' + index" v-bind:id="'content_type' + index + '-1'" type="radio" value="1" :disabled="statucControl[0].top_content_type">{{ trans('events.S_image') }}
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    </div>
                                                                    <div class="form-checkbox">
                                                                    <label class="control control--radio" v-bind:for="'content_type' + index + '-2'">
                                                                        <input v-model="article.type" v-bind:name="'type' + index" v-bind:id="'content_type' + index + '-2'" type="radio" value="2" :disabled="statucControl[0].top_content_type">{{ trans('events.S_Video') }}
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    </div>                          
                                                                </div>
                                                            <!--上傳圖片-->
                                                            <div class="col-md-12">
                                                                <div id="articleImageBox" class="drop-image" v-show="(article.type == '1')?true:false">
                                                                    <!--STS 2021/07/28 Task 41 -->
                                                                    <input type="file" v-bind:id="index" class="dropify-config" data-height="400" v-bind:data-default-file="article.image_url" data-max-file-size="2M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' @change="imageUploadArticle($event,index)" :disabled="statucControl[0].top_content_image"/>
                                                                </div>
                                                                <!--form5-->
                                                                <div class="form-group mtb-2" v-show="(article.type == '2')?true:false">
                                                                    <label class="col-md-2 control-label">{{ trans('events.S_VideoContent') }}</label>
                                                                    <div class="col-md-10">
                                                                        <input type="text"  maxlength="255" class="form-control" v-model="article.video_url" :disabled="statucControl[0].top_content_url" placeholder="{{ trans('events.S_VideoContentPlaceholder') }}">
                                                                    </div>
                                                                </div>
                                                                <!--/.form5-->
                                                            </div>
                                                            <!--/.col-->
                                                        </div>
                                                        <!--/.col-->
                                                    </div>
                                                    <!--/.body-->
                                                </div>
                                            <!-- /.Form -->
                                        </div>
                                    <!--/.box-->
                                </div>
                                <!--/.div-->
                            </div>
                            <!-- /.sortable-box-wraper  -->
                            <p id="log"></p>
                        </div>
                    <!--/.sortable-wraper-->
                    <!--  /.box6 統一樣式 + 活動說明＆新增 --> 
                    <!-- 新增活動按鈕 置底-->
                        <div class="btn-group mb-5">
                            <button  v-on:click="addContent" class="btn btn-block waves-effect waves-light btn-add" :disabled="statucControl[0].basis">{{trans('events.S_eventAddArticle')}}</button>
                        </div>
                    <!--/.新增活動按鈕 置底-->
                    <!-- /.1210 新增  -->
                </div>
                <div class="summary-chevron-down">
                            
                </div>
            </details>
             <!--  Accordion 統一樣式 + 進階設定 -->

            <!--==================進階設定==================================-->
        </div>
    </div>
    <!-- /.basis-setting-wrap 基本情報專用class -->
    <!-- modal -->
        <transition name="slide-fade">
            <div class="modal-mask" v-show='showModal' style="display: none">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans('events.S_eventHavingHallTitle') }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row form-horizontal">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">{{ trans('events.S_eventHallLocationTitle') }}</label>
                                            <div class="col-md-8 city-selector">
                                                <select class="form-control select2 county" v-model="dialogCountry" style="width: 100%;"></select>
                                                <select class="district" style="display: none;"></select>
                                                <!--<select class="form-control select2" v-model="dialogCountry" style="width: 100%;">
                                                    <option value="0">--</option>
                                                </select>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group"><label class="col-md-2 control-label">{{ trans('events.S_eventHallNameTitle') }}</label>
                                        <div class="col-md-10">
                                            <input type="text"  maxlength="80" class="form-control" v-model="hallName2">
                                            <span dusk="userNameWarn" class="help is-danger" style="display: none;"><i class="fas fa-exclamation-circle"></i> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button v-on:click="clearHillSreach()" type="button" class="btn btn-inverse btn-mm">{{ trans('events.S_resetBtn') }}</button>
                                    <button v-on:click="hallList()" id="" class="btn btn-info btn-mm ml-15x">{{ trans('events.S_SearchBtn') }}</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                </div>
                                <!--  -->
                                <!-- //結果列表區 -->
                                <div class="col-md-12 modal-result-list">
                                    <div class="result-list-section">
                                        <div class="result-list-content">
                                            <div class="result-list-content_text">
                                                <h4></h4>
                                                <div class="col-md-12 result-list-title">
                                                    <div class="col-md-5">{{ trans('events.S_eventHallNameTitle') }}</div>
                                                    <div class="col-md-5">{{ trans('events.S_eventHallNoteTitle') }}</div>
                                                    <div class="col-md-2">{{ trans('events.S_eventHallLocationTitle') }}</div>
                                                </div>
                                                <!---->
                                                <div class="result-list-rows">
                                                    <template v-for="data of hallData">
                                                        <a v-bind:id="'hall'+data.hall_code" class="col-md-12 result-list-line hall-list" v-on:click="selectHall('hall'+data.hall_code, data.hall_code)">
                                                            <div class="col-md-5">@{{ data.hall_name }}</div>
                                                            <div class="col-md-5">@{{ data.description }}</div>
                                                            <div class="col-md-2">@{{ data.prefecture }}</div>
                                                        </a>
                                                    </template>
                                                </div>
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/.結果列表區 -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" v-on:click="closeDialog()">{{ trans('events.S_cancelBtn') }}</button>
                            <button id="dialogUpBtn" class="btn btn-primary" v-on:click="confirmDialog()">{{ trans('events.S_selectBtn') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
     <!-- modal -->
</div>
<!-- HTML -->
<script>

    var articles = {!! old('article') ? old('article') : ($eventData['article'] ? $eventData['article'] : "[{text: '', type: 1, 'subContent':true, 'image_url': null, 'video_url':null}]") !!};
    var articlesDisp;
    var questionnaires = []; //STS 2021/06/09 Task 15
    window.article = {};

    $(function() {
      $.each(articles, function(index, article) {
        setArticleTextArea(index)
      });
    });        
    
    function setArticleTextArea(index) {
        //"imageUpload",
        ClassicEditor
        .create( document.querySelector( '#editor'  + index),{
            toolbar: [
                      "heading", 
                      "|", 
                      "alignment:left", 
                      "alignment:center", 
                      "alignment:right", 
                      "alignment:adjust", 
                      "|", 
                      "bold", 
                      "italic", 
                      "blockQuote", 
                      "link", 
                      "|",           
                      "fontSize", 
                      "fontFamily", 
                      "fontColor", 
                      "fontBackgroundColor",
                      "|",
                      "bulletedList", 
                      "numberedList", 
                      "|", 
                      "undo", 
                      "redo"
                    ],
            ckfinder: {
                uploadUrl: "/upload_image?_token={{csrf_token()}}&location={!! $eventData['evenId'] !!}"
            }
        } )
        .then(editor => {
            window.article[ index ] = editor;
        })
        .catch( error => {
            console.error( error );
        } );
    } 
    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        var charactersLength = characters.length;

        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
    var basisSetting = new Vue({
        el: '#basisSetting',
        data:{
            perfomanceStatus:'',
            imageLocation:'',
            eventIdDisable:'',
            showModal:'',
            eventLogo:'',
            eventId:'',
            eventTitle:'',
            eventSubTitle:'',
            eventType:'',
            eventUrl:'',
            eventContact:'',
            eventContactMail:'',
            eventContactTel:'',
            infOpenDate:'',
            infOpenIsNull:'',
            earlyBirdDateId:'',
            earlyBirdDateStart:'',
            earlyBirdDateEnd:'',
            earlyBirdDateChecked:'',
            autoTransChecked:'',
            autoTransZhtw:'',
            autoTransEng:'',
            normalDateId:'',
            normalDateStart:'',
            normalDateEnd:'',
            normalDateChecked:'',
            localStatus:'',
            localId:'',
            locationName:'',
            locationNameKana:'',
            country:'',
            dialogCountry:'',
            city:'',
            localUrl:'',
            locationDescription:'',
            contentType:'',
            contentImageShow:'',
            contentImage:'',
            contentVidioUrl:'',
            contentComment:'',
            performance_st_dt:'',
            performance_end_dt:'',
            hallName:'',
            hallName2:'',
            hallData:[],
            hallOriData:[],
            hallId:'',
            selectEven:'',
            hallNameSelect:'',
            hallDisable:'',
            dateError: false,
            dateErrorM:'',
            advancedDateError: false,
            advancedDateErrorM:'',
            editContent:'',
            post_code:'',
            statucControl:[],
            articles:articles,
            keywords:'',
            saleType:0,
            enInformation:[],
            wordCount:[],
            dateEnd:[],
            articleTitle:'',
            loaclEditStatus:'',
            perfomanceInfStatus:'',
            maxDate:'',
            minDate:'',
            pointerEvents:'',
            // questionnaires:[], STS 2021/06/09 Task 15
            questionnaires:questionnaires,// STS 2021/06/09 Task 15
            addButtonErrorStatus: false, 
            sellAnyone: false,
            oldID:'',//STS 2021-06-10 Task 16: Get old id of textbox 
            imageShow_del_flag: false, //STS 2021/07/28 Task 41
            eventLogo_del_flag: false, //STS 2021/07/28 Task 41
            imageArticle_del_flag:[], //STS 2021/07/28 Task 41
        },
        watch: {
        	//STS 2021/07/26 task 38 -- START
            saleType: function() {
              this.checkhyphens()
             },
             eventTitle: function() {
              this.checkhyphens()
            },
            eventSubTitle: function() {
              this.checkhyphens()
            },
            hallName: function() {
              this.checkhyphens()
            },
            eventContact: function() {
              this.checkhyphens()
            },
            enInformation: {
                handler(){
                    this.checkhyphens()
                },
                deep: true
            },
             //STS 2021/07/26 task 38 -- END
            infOpenDate: function (val) {
                this.$validator.validateAll()
                this.dateCheack('basis')
            },
            dateEnd:{
                handler(){
                    this.$validator.validateAll()
                    this.dateCheack('basis')
                },
                deep: true
            },
            performance_st_dt: function (val) {
                this.dateCheack('basis')
                if(this.performance_st_dt && this.performance_end_dt){
                    tagControl.timeCourseTag = true
                }
            },
            performance_end_dt: function (val) {
                this.dateCheack('basis')
                if(this.performance_st_dt && this.performance_end_dt){
                    tagControl.timeCourseTag = true
                }
            },
            earlyBirdDateChecked: function (val) {
                this.dateCheack('advanced')
                if(this.earlyBirdDateChecked || this.normalDateChecked) {
                    tagControl.ticketTag = true
                    tagControl.sellSettingTag = true
                    // tagControl.ticketViewTag = true
                }
                else {
                    tagControl.ticketTag = false
                    tagControl.sellSettingTag = false
                    // tagControl.ticketViewTag = false
                }
            },
            normalDateChecked: function (val) {
                this.dateCheack('advanced')
                if(this.earlyBirdDateChecked || this.normalDateChecked) {
                    tagControl.ticketTag = true
                    tagControl.sellSettingTag = true
                    // tagControl.ticketViewTag = true
                }
                else {
                    tagControl.ticketTag = false
                    tagControl.sellSettingTag = false
                    // tagControl.ticketViewTag = false
                }
            },
            earlyBirdDateStart: function (val) {
                this.dateCheack('advanced')
            },
            earlyBirdDateEnd: function (val) {
                this.dateCheack('advanced')
            },
            normalDateStart: function (val) {
                this.dateCheack('advanced')
            },
            normalDateEnd: function (val) {
                this.dateCheack('advanced')
            },
            locationName: function (val){
                if(this.locationName &&  timeCourse.settingRadio == "normal" && ticketSetting.typeTicketSetting !== 'freeSeat'){
                    tagControl.seatSettingTag = true
                }else{
                    tagControl.seatSettingTag = false
                }
                if(this.loaclEditStatus == 'select'){
                    this.localStatus = 'U'
                }
            },
            country: function (val) {
                if(this.loaclEditStatus == 'select'){
                    this.localStatus = 'U'
                }
            },
            city: function (val) {
                if(this.loaclEditStatus == 'select'){
                    this.localStatus = 'U'
                }
            },
            localUrl: function (val) {
                if(this.loaclEditStatus == 'select'){
                    this.localStatus = 'U'
                }
            },
            localStatus: function(val){
                if(this.localStatus == 'I'){
                    this.loaclEditStatus = 'insert'
                }else{
                    this.loaclEditStatus = 'select'
                }
            },
            errors:{
                handler(){
                    this.settingCheack()
                    this.checkhyphens()//STS 2021/07/26 Task 38
                },
                deep: true
            },
        },
        methods: {
        	// STS 2021/07/26 task 38 -- START
            checkhyphens: function() {
                var eventTitle = this.eventTitle
                var eventTitleEn = this.enInformation.data.performanceName
                var eventSubTitle = this.eventSubTitle
                var eventSubTitleEn = this.enInformation.data.performanceNameSub
                var hallName = this.hallName
                var hallNameEn = this.enInformation.data.hallDispName
                var eventContact = this.eventContact
                var eventContactEn = this.enInformation.data.informationNm
                var checkEn = this.enInformation.status.performanceStatus
                var hallNameCheckEn = this.enInformation.status.hallStatus
                var InfoCheckEn = this.enInformation.status.informationStatus
                if(((eventTitle && eventTitle != eventTitle.replace(/-+/g,'-')) || (checkEn && eventTitleEn && eventTitleEn != eventTitleEn.replace(/-+/g,'-')) || (eventSubTitle && eventSubTitle != eventSubTitle.replace(/-+/g,'-')) || (checkEn && eventSubTitleEn && eventSubTitleEn != eventSubTitleEn.replace(/-+/g,'-')) || (hallName && hallName != hallName.replace(/-+/g,'-')) || (hallNameCheckEn && hallNameEn && hallNameEn != hallNameEn.replace(/-+/g,'-')) || (eventContact && eventContact != eventContact.replace(/-+/g,'-')) || (InfoCheckEn && eventContactEn && eventContactEn != eventContactEn.replace(/-+/g,'-'))) && basisSetting.saleType == 1) {
                    this.addButtonErrorStatus = true;
                    errorMsnCheack.addBtnCheack();
                } else if(((eventTitle && eventTitle != eventTitle.replace(/-+/g,'-')) || (checkEn && eventTitleEn && eventTitleEn != eventTitleEn.replace(/-+/g,'-')) || (eventSubTitle && eventSubTitle != eventSubTitle.replace(/-+/g,'-')) || (checkEn && eventSubTitleEn && eventSubTitleEn != eventSubTitleEn.replace(/-+/g,'-')) || (hallName && hallName != hallName.replace(/-+/g,'-')) || (hallNameCheckEn && hallNameEn && hallNameEn != hallNameEn.replace(/-+/g,'-')) || (eventContact && eventContact != eventContact.replace(/-+/g,'-')) || (InfoCheckEn && eventContactEn && eventContactEn != eventContactEn.replace(/-+/g,'-'))) && basisSetting.saleType != 1) {
                    this.addButtonErrorStatus = false;
                    errorMsnCheack.addBtnCheack();
                } else {
                    this.addButtonErrorStatus = false;
                    errorMsnCheack.addBtnCheack();
                }
            },
            //  STS 2021/07/26 task 38 --END
            wordLimit:function(type){
                let article = myEditor.getData()
                this.wordCount['editor'].total = (article)?article.length:0
                this.wordCount['editor'].overTotal = 0

                if(this.wordCount['editor'].total > this.wordCount['editor'].limit){
                    this.wordCount['editor'].errorStatus = true
                    this.wordCount['editor'].overTotal = this.wordCount['editor'].total - this.wordCount['editor'].limit
                }else{
                    this.wordCount['editor'].errorStatus = false
                }

            },
            eventTypeSelect:function(value){
                this.eventType = value
            },
           settingArticleImage:function(){        
                  $.getScript("{{ asset('js/dropify.min.js') }}", function(){
                    //STS 2021/07/28 Task 41
                    $('.dropify-config').dropify({
                            tpl: {
                            wrap: '<div class="dropify-wrapper"></div>',
                            loader: '<div class="dropify-loader"></div>',
                            message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p>{{trans("common.S_DropifyMsg")}}</p></div>',
                            preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{trans("common.S_DropifyEdit")}}</p></div></div></div>',
                            filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
                            clearButton: '<button type="button" class="dropify-clear">X</button>', //STS 2021/07/28 Task 41
                            errorLine: '<p class="dropify-error">{{trans("common.S_DropifyErr")}}</p>',
                            errorsContainer: '<div class="dropify-errors-container"><ul>{{trans("common.S_DropifyErr")}}</ul></div>'
                        },
                        error: {
                            'fileSize': '{{trans("common.S_DropifySizeErr")}}'
                        }
                    });
                });      
            },
            getError:function(){
                if(
                   this.errors.has('top-content-url') ||
                   this.errors.has('top-content-comment') ||
                   this.errors.has('{{ trans("events.S_eventcodeTitle") }}') ||
                   this.errors.has('eventid') ||
                   this.errors.has('event-title-en') ||
                   //this.errors.has('event-sub-title') ||
                   this.errors.has('performance-name-sub') ||
                   this.errors.has('placeTitle') ||
                   this.errors.has('placeKanaTitle') ||
                   this.errors.has('hall-name') ||
                   this.errors.has('hall-disp-name') ||
                   this.errors.has('placeUrlValidate') ||
                   this.errors.has('title') ||
                   this.errors.has('information-name-en') ||
                   this.errors.has('email') ||
                   this.errors.has('tel') 
                ){
                    return true;
                }else{
                    return false;
                }
            },
            settingCheack:function (item) {
                let hasError = this.getError();

                if(this.errors.has('eventid') || this.errors.has('{{ trans("events.S_eventcodeTitle") }}')){
                    this.addButtonErrorStatus = true;
                    errorMsnCheack.addBtnCheack();
                }else{
                    this.addButtonErrorStatus = false;
                    errorMsnCheack.addBtnCheack();
                }
                
                if(hasError || this.dateError || this.advancedDateError){
                    tagControl.basisWarning = true
                }else{
                    tagControl.basisWarning = false
                }
            },
            selectHall:function(eventId, id){
                if(this.selectEven){
                    document.getElementById(this.selectEven).classList.remove("active");
                }
                this.selectEven = eventId
                document.getElementById(eventId).classList.add("active");
                this.hallId = id
            },
            hallList:function(){
                let dialogCountry = this.dialogCountry
                let hillList = [] 
                let hillLength = $('.hall-list').length
                let hallData = this.hallOriData
                let eventId = this.eventId
                let hallName2 = this.hallName2
                var re = new RegExp(hallName2,"gi")

                $('.hall-list').each(function(index) {
                    $(this)[0].style.display = "block"
                });

                if(dialogCountry){
                    for(let num=0; num<hillLength; num++){
                        if(hallData[num].prefecture.toString() !== dialogCountry.toString()){
                            $('.hall-list')[num].style.display = "none" 
                        }
                    }
                }
             
                if(hallName2){
                    var tt;
                    for(let num=0; num<hillLength; num++){
                        tt = hallData[num].hall_name.toString().match(re);
                        if(!tt || tt === ""){
                            $('.hall-list')[num].style.display = "none" 
                        }
                    }
                }

            },
            confirmDialog(){
               let hallData = this.hallOriData
               let hallId = this.hallId
               let hillLength = hallData.length
             
                for(let num=0; num<hillLength; num++){
                    if(hallData[num].hall_code.toString() === hallId.toString()){
                        this.localId = hallData[num].hall_code
                        this.locationName = hallData[num].hall_name
                        this.locationNameKana = hallData[num].hall_name_kana
                        this.country = hallData[num].prefecture
                        this.city = hallData[num].city
                        this.localUrl = hallData[num].home_page
                        this.post_code = hallData[num].post_code
                        this.locationDescription = hallData[num].description
                    }
                }

                tcs.setValue(this.country, this.city);
                this.hallDisable = true

                if(this.hallId === 0){
                    this.hallDisable = false
                    this.localId = null
                    this.locationName = null
                    this.locationNameKana = null
                    this.country = null
                    this.city = null
                    this.localUrl = null
                    this.post_code = null
                    this.locationDescription = null
                }
                document.getElementById(this.selectEven).classList.remove("active");
                this.showModal = false
                this.localStatus = 'N'
            },
            openDialog(){
                this.showModal = true
            },
            closeDialog(){
                this.showModal = false
                if(this.localId){
                    this.hallDisable = true
                }else{
                    this.hallDisable = false
                }
                if(this.selectEven){
                    document.getElementById(this.selectEven).style.backgroundColor = "rgba(255, 255, 255,1)";
                }
            },
            clearHillSreach(){
                tcs.setValue('', '');
                this.dialogCountry = ""
                this.hallName2 = ""
                
            },
            clearHill(){
                this.hallDisable = false
                this.localId = null
                this.locationName = null
                this.locationNameKana = null
                this.country = null
                this.city = null
                this.localUrl = null
                this.post_code = ""
                this.locationDescription = null
                this.$nextTick(() => {
                    this.localStatus = 'I'
                })
                document.getElementById('zipcode').value = ""
            },
            saveLocalStock:function(){
                let json = []
                let result 
                let editContent = escape(myEditor.getData())
                let articleInfo = "[";
                let cnt = 0; 

                if(articleSeq.length == 0)
                {
                  //記事の順番を変更していない場合
                  for (let index = 0; index < articles.length; index++) 
                  {
                    articleSeq.push(index); 
                  }                
                }

                if(articleSeq.length != articles.length)
                {
                  //記事の順番変更後に記事追加を行った場合
                  seqLength = articleSeq.length;
                  for (let index = 0; index < articles.length - seqLength; index++) 
                  {
                    articleSeq.push(articleSeq.length);
                  }
                }

                let temp = new Array();
                for (let index = 0; index < articles.length; index++) 
                { 
                  i = articleSeq[index];                  
                  if (articles[i].del == true)
                  { 
                    continue;    
                  }    
                  if(cnt > 0)
                  {
                   articleInfo = articleInfo + ",";    
                  }   
                  let image_url = '';
                  //STS 2021/07/28 Task 41
                  if (articles[i].image_url != undefined && basisSetting.imageArticle_del_flag[i] != true) 
                  {
                    image_url = articles[i].image_url;
                  }
                  let video_url = '';
                  if (articles[i].video_url != undefined) 
                  {
                    video_url = articles[i].video_url;
                  }
                  if ( image_url != '' && image_url.indexOf('{{url('/')}}') == -1) {
                    image_url = '{{url('/')}}' + image_url;
                  }
                  //STS 2021/07/28 - Task 42 - START
                  // if(index == 0){
                  articleInfo = articleInfo + '{"title":"'+escape(this.articleTitle)+
                                            '","text": "' + escape(window.article[i].getData()) +
                                            '", "subContent":"true"' +
                                            ', "type":"' + articles[i].type +
                                            '", "image_url":"' + image_url +
                                            '", "video_url":"' + video_url +
                                           '"}';
                  // }else{
                  //   articleInfo = articleInfo + '{"text": "' + escape(window.article[i].getData()) +
                  //                             '", "subContent":"true"' +
                  //                             ', "type":"' + articles[i].type +
                  //                             '", "image_url":"' + image_url +
                  //                             '", "video_url":"' + video_url +
                  //                            '"}';
                  // }
                  //STS 2021/07/28 - Task 42 - END
                  articles[i].text = window.article[i].getData();
                  temp.push(articles[i]);
                  cnt = cnt + 1;
                }
                articleInfo = articleInfo + "] ";
                this.post_code = document.getElementById('zipcode').value
               
                articlesDisp = temp;
                //STS 2021/06/09 Task 15 Start
                let qTemp = new Array();
                if(this.questionnaires.length > 0){
                    for(let k = 0; k < this.questionnaires.length; k++){
                        if(this.questionnaires[k].del == true){
                            continue;
                        }
                        qTemp.push(this.questionnaires[k]);

                    }
                    this.questionnaires = qTemp;
                    //console.log(this.questionnaires);
                }
                ///.STS 2021/06/09 Task 15 End
                json.push({
                    imageLocation : this.imageLocation,
                    eventLogo : (this.eventLogo_del_flag) ? null : this.eventLogo, //STS 2021/07/28 Task 41
                    eventId : this.eventId,
                    eventTitle : this.eventTitle,
                    eventSubTitle : this.eventSubTitle,
                    eventType : this.eventType,
                    eventUrl : this.eventUrl,
                    eventContact : this.eventContact,
                    eventContactMail : this.eventContactMail,
                    eventContactTel : this.eventContactTel,
                    infOpenDate : this.infOpenDate,
                    earlyBirdId : this.earlyBirdId,
                    earlyBirdDateStart : this.earlyBirdDateStart,
                    earlyBirdDateEnd : this.earlyBirdDateEnd,
                    earlyBirdDateChecked : this.earlyBirdDateChecked,
                    autoTransChecked : this.autoTransChecked,
                    autoTransZhtw : this.autoTransZhtw,
                    autoTransEng : this.autoTransEng,
                    normalDateId :  this.normalDateId,
                    normalDateStart : this.normalDateStart,
                    normalDateEnd : this.normalDateEnd,
                    normalDateChecked : this.normalDateChecked,
                    localId : this.localId,
                    localStatus : this.localStatus,
                    locationName : this.locationName,
                    locationNameKana : this.locationNameKana,
                    country : this.country,
                    city : this.city,
                    localUrl : this.localUrl,
                    locationDescription : this.locationDescription,
                    contentType : this.contentType ,
                    contentImage : (this.imageShow_del_flag) ? null : this.contentImageShow, //STS 2021/07/28 Task 41
                    contentVidioUrl : this.contentVidioUrl,
                    contentComment : this.contentComment,
                    performance_st_dt : this.performance_st_dt,
                    performance_end_dt : this.performance_end_dt,
                    editContent : editContent,
                    editContentPreview : myEditor.getData(),
                    hallName : this.hallName,
                    post_code : this.post_code,  
                    article :articleInfo,
                    keywords : document.getElementById('keywords').value,
                    sale_type : this.saleType,
                    enInformation : JSON.stringify(this.enInformation),
                    dateEnd : this.dateEnd,
                    questionnaires : this.questionnaires,
                    sellAnyone : this.sellAnyone,
                })
              
                sessionStorage.setItem('basisData' ,JSON.stringify(json[0]))

                return json
            },
            dateCheack:function(type){
                this.dateError          = false
                this.advancedDateError  = false

                let result = this.dateCheackRule(type)
                let errorType =   result.type

                if(errorType == 'basis'){
                    if(result.status){
                        this.dateError = false
                    }else{
                        this.dateError  = true
                        this.dateErrorM = result.msn
                    }
                }else if(errorType == 'advanced'){
                    if(result.status){
                        this.advancedDateError = false
                    }else{
                        this.advancedDateError  = true
                        this.advancedDateErrorM = result.msn
                    }
                }
                
                this.settingCheack()
            },
            dateCheackRule:function(type){
                let openDate =  (Date.parse(this.infOpenDate)).valueOf() 
                let dateEnd =  (Date.parse(this.dateEnd.date)).valueOf() 
                //開催開始の時間を 23:59固定 
                performanceS = null
                if(this.performance_st_dt != null)
                {
                  performanceS = new Date(this.performance_st_dt)
                  performanceS= performanceS.setHours(performanceS.getHours() + 23);
                  performanceS= new Date(performanceS).setMinutes(new Date(performanceS).getMinutes() + 59);
                }
                let performanceE = (this.performance_end_dt)?(Date.parse(this.performance_end_dt)).valueOf():null
                let earlyBirdDateS = (this.earlyBirdDateStart)?(Date.parse(this.earlyBirdDateStart)).valueOf():null
                let earlyBirdDateE = (this.earlyBirdDateEnd)?(Date.parse(this.earlyBirdDateEnd)).valueOf():null
                let normalDateS = (this.normalDateStart)?(Date.parse(this.normalDateStart)).valueOf():null
                let normalDateE = (this.normalDateEnd)?(Date.parse(this.normalDateEnd)).valueOf():null
                let result = {
                    status  : true,
                    msn     : '',
                    type    : ''
                }
                if(openDate != null &&  performanceS != null && openDate > performanceS){
                    result.status   = false
                    result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_1') }}"
                    result.type = 'basis'
                    return result
                }
                if(performanceS){
                    if(performanceE == null) {
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_2-6') }}"
                        result.type = 'basis'
                        return result
                    }
                    if(performanceS != null && performanceE != null && performanceS > performanceE){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_2-7') }}"
                        result.type = 'basis'
                        return result
                    }
                }
                if(this.dateEnd.setFlg == 'EndDate'){
                    if(isNaN(dateEnd)) {
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_4-1') }}"
                        result.type = 'basis'
                        return result
                    }
                    if(dateEnd < openDate) {
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_4-3') }}"
                        result.type = 'basis'
                        return result
                    }
                    if(dateEnd < performanceE) {
                        //日付のみ判定用
                        let dateEndDate =  (Date.parse(this.dateEnd.date.substr(0,10))).valueOf() 
                        let performanceEDate = Date.parse(this.performance_end_dt.substr(0,10)).valueOf();
                        if(dateEndDate != performanceEDate)
                        {
                          result.status   = false
                          result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_4-2') }}"
                          result.type = 'basis'
                          return result
                        }
                    }
                }
                if(this.earlyBirdDateChecked) {
                    if(isNaN(earlyBirdDateS) || isNaN(earlyBirdDateE)){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_2-1') }}"
                        result.type = 'advanced'
                        return result
                    }
                    if(earlyBirdDateS == null){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-8')}}"
                        result.type = 'advanced'
                        return result
                    }
                    if(earlyBirdDateE == null){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-9')}}"
                        result.type = 'advanced'
                        return result
                    }
                    if(earlyBirdDateS != null && earlyBirdDateE != null && (Date.parse(this.earlyBirdDateStart)).valueOf() > (Date.parse(this.earlyBirdDateEnd)).valueOf()){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-10')}}"
                        result.type = 'advanced'
                        return result
                    }
                    if(performanceS < earlyBirdDateS){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_2-2') }}"
                        result.type = 'advanced'
                        return result
                    }
                    if(earlyBirdDateE > performanceE ){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_2-3') }}"
                        result.type = 'advanced'
                        return result
                    }
                    if(openDate != null && earlyBirdDateS != null && openDate > (Date.parse(this.earlyBirdDateStart)).valueOf()){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_2-4') }}"
                        result.type = 'advanced'
                        return result
                    }


                }
                if(this.normalDateChecked) {
                    if(isNaN(normalDateS) || isNaN(normalDateE) ) {                    
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-1') }}"
                        result.type = 'advanced'
                        return result
                    }
                    if(normalDateS == null){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-11')}}"
                        result.type = 'advanced'
                        return result
                    }
                    if(normalDateE == null){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-12')}}"
                        result.type = 'advanced'
                        return result
                    }
                    if(normalDateS != null && normalDateE != null && (Date.parse(this.normalDateStart)).valueOf() > (Date.parse(this.normalDateEnd)).valueOf()){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-13')}}"
                        result.type = 'advanced'
                        return result
                    }
                    if(openDate != null && normalDateS != null && openDate > (Date.parse(this.normalDateStart)).valueOf() ){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-2') }}"
                        result.type = 'advanced'
                        return result
                    }
                    if(normalDateE != null &&  performanceE != null && normalDateE > performanceE){
                        result.status   = false
                        result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-3') }}"
                        result.type = 'advanced'
                        return result
                    }
                    if(!this.earlyBirdDateChecked) {
                      //fixed by LS#1395 先行+一般の場合許容
                      if(normalDateS > performanceS){
                          result.status   = false
                          result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-5') }}"
                          result.type = 'advanced'
                          return result
                      }
                    }
                    if(this.earlyBirdDateChecked) {
                        if(normalDateE != null && earlyBirdDateS != null && normalDateE <= earlyBirdDateS){
                            result.status   = false
                            result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-4') }}"
                            result.type = 'advanced'
                            return result
                        }
                        
                        //日付のみ判定用
                        let performanceStartDate   = Date.parse(this.performance_st_dt.substr(0,10)).valueOf();  //開催 s
                        let earlyBirdDateStartDate = Date.parse(this.earlyBirdDateStart.substr(0,10)).valueOf(); //先行 s

                        //if(normalDateS == earlyBirdDateS && normalDateE == earlyBirdDateE && earlyBirdDateStartDate != performanceStartDate){
                            //ph3 LS#1395
                            //先行と一般の販売期間が被った場合にはエラー。
                            //発売開始日と開催初日が同じというケースは許可
                        //    result.status   = false
                        //    result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-6') }}"
                        //    result.type = 'advanced'
                        //    return result                    
                        //}


                        //時間含む判定
                        let EBDateE = (Date.parse(this.earlyBirdDateEnd)).valueOf() 
                        let NRDateS = (Date.parse(this.normalDateStart)).valueOf() 
                        //先行販売期間(To)>一般販売期間(From)
                        if(EBDateE > NRDateS)
                        {
                          //一般が先行に含まれる(重複)
                          result.status   = false
                          result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-6') }}"
                          result.type = 'advanced'
                          return result
                        }

                        if(earlyBirdDateS == normalDateS && earlyBirdDateE > normalDateE && earlyBirdDateStartDate != performanceStartDate){
                            //ph3 LS#1395
                            //先行と一般を同じ開始日、先行の終了日を一般より後ろにした場合はエラー
                            //発売開始日と開催初日が同じというケースは許可
                            result.status   = false
                            result.msn      = "{{ trans('events.S_verMsg_Date') . trans('events.S_verMsg_Date_3-7') }}"
                            result.type = 'advanced'
                            return result                    
                        }
                    }
                }
                return result
            },
            /**
             * 開始日期轉換 [YYYY/MM/DD HH:mm] TO [YYYY/MM/DD]
             */
            transDateFormat:function(date){
                let dateOri  = new Date(date)
                let st_Y     = dateOri.getFullYear()        
                let st_M     = (dateOri.getMonth()+1 < 10)? '0'+(dateOri.getMonth()+1) : dateOri.getMonth()+1 
                let st_D     = dateOri.getDate()
                
                return st_Y + '/' + st_M  + '/' + st_D
            },
            imageUpload:function($event, type){
                try {
                    let img = $event.target.files[0]
                    if(img.size > 2097152)
                    {
                        return;
                    }
                    
                    const form = new FormData();
                    form.append('file', img);
                    form.append('location', this.imageLocation)
                    form.append('type', type)

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    
                    if(type == 'logo'){
                        $.ajax({
                            url: '/eventImage/import',
                            type: 'POST',
                            data: form,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function(data, textStatus, jqXHR)
                            {
                                basisSetting.eventLogo_del_flag = false //STS 2021/07/28 Task 41
                                basisSetting.eventLogo = data.url
                            },
                            error: function(jqXHR, textStatus, errorThrown)
                            {
                                console.log('ERRORS: ' + textStatus)
                                basisSetting.eventLogo_del_flag = true //STS 2021/07/28 Task 41
                            }
                        });
                    }else if(type == 'content'){
                        $.ajax({
                            url: '/eventImage/import',
                            type: 'POST',
                            data: form,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function(data, textStatus, jqXHR)
                            {
                                basisSetting.imageShow_del_flag = false //STS 2021/07/28 Task 41
                                basisSetting.contentImageShow = data.url
                            },
                            error: function(jqXHR, textStatus, errorThrown)
                            {
                                console.log('ERRORS: ' + textStatus)
                                basisSetting.imageShow_del_flag = true //STS 2021/07/28 Task 41
                            }
                        });
                    }else{
                        console.log('type is defined')
                    }
                }catch (error){
                    console.error(error)
                    $event.stopImmediatePropagation();
                }
            },  
             imageUploadArticle:function($event, index){
                try {
                    let img = $event.target.files[0]
                    if(img.size > 2097152)
                    {
                    return;
                    }

                    const form = new FormData();
                    form.append('file', img);
                    form.append('location', this.imageLocation)
                    form.append('type', 'article')

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                    url: '/eventImage/import',
                    type: 'POST',
                    data: form,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(data, textStatus, jqXHR)
                    {
                        articles[index].image_url = data.url
                        basisSetting.imageArticle_del_flag[index] = false //STS 2021/07/28 Task 41
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        basisSetting.imageArticle_del_flag[index] = true //STS 2021/07/28 Task 41
                        console.log('ERRORS: ' + textStatus)
                    }
                    });
                }catch (error){
                    console.error(error)
                    $event.stopImmediatePropagation();
                }
            },             
            addContent : function() {
              this.settingArticleImage();          
              var index = this.articles.length;
              this.articles.push({
               text: '',
               type: '1',
               image_url:'',
               video_url:''
              })
              //--- ※evnet after finished v-for
              this.$nextTick(() => {
                setArticleTextArea(index);
                //--- ※activate dynamically created remove/collapse function
                $('.box-solid').boxWidget({
                  animationSpeed : 500,
                  collapseTrigger: '[data-widget="collapse"]',
                  removeTrigger  : '[data-widget="remove"]',
                  collapseIcon   : 'fa-minus',
                  expandIcon     : 'fa-plus',
                  removeIcon     : 'fa-times'
                });
              });
            },
            deleteContent: function(index) {        
              this.articles[index].del = true;
            },     
            //STS 2021/06/09 Task 15 Start
            addQuestionnaire : function() {       
                var qIndex = this.questionnaires.length;
                var sortIndex = qIndex + 1;
                // var langId = qIndex - 1; STS 2021/06/23 Task 30
                this.questionnaires.push({
                    id: -1,//STS 2021/06/23 Task 30 Edited: langId -> -1
                    use: true,
                    langs: {
                        ja: {
                            id: -1,
                            title: "",
                            text: "",
                            selected: true,
                            placeholder:""
                        },
                        en: {
                            id: -1,
                            title: "",
                            text: "",
                            selected: false,
                            placeholder:""
                        },
                        zh_tw: {
                            id: -1,
                            title: "",
                            text: "",
                            selected: false,
                            placeholder:""
                        }
                    },
                    required: false,
                    sort: sortIndex,
                    del : false
                    
                });
                questionnaires = this.questionnaires;
                this.$nextTick(() => {
                    //--- ※activate dynamically created remove/collapse function
                    $('.box-solid').boxWidget({
                        animationSpeed : 500,
                        collapseTrigger: '[data-widget="collapse"]',
                        removeTrigger  : '[data-widget="remove"]',
                        collapseIcon   : 'fa-minus',
                        expandIcon     : 'fa-plus',
                        removeIcon     : 'fa-times'
                        });
                });

            },
            deleteQuestionnaire: function(qIndex) { 
                this.questionnaires[qIndex].del = true;
            }
            ///.STS 2021/06/09 Task 15 End       
        },
        mounted(){  
            sessionStorage.setItem('hall', '{!! addslashes($eventData["hallData"]) !!}')
            let hallData = sessionStorage.getItem('hall')
            if(hallData){
                hall = JSON.parse(hallData)
            }else{
                hall = null
            }

            this.perfomanceStatus = '{!! $eventData["status"] !!}'

            @if( $eventData["status"] === 'edit' || $eventData["status"] === 'new2' || count($errors) > 0)  
                sessionStorage.setItem('basisData','{!! addslashes($eventData["basisData"]) !!}')                
            
                let userInf = JSON.parse(sessionStorage.getItem('basisData'))
                let data = userInf.data
                let perfomanceStatus = parseInt('{{ $eventData['performanceDispStatus'] }}', 10);

                if(this.perfomanceStatus == 'new2'){
                    perfomanceStatus = -1
                }

                this.showModal = false
                this.eventIdDisable = true
                this.eventLogo = data.eventLogo || null
                this.imageLocation = data.imageLocation || null
                this.eventId = data.eventId || null
                this.eventTitle = data.eventTitle || ''
                this.eventSubTitle = data.eventSubTitle || null
                this.eventType = data.eventType || null
                this.eventUrl = data.eventUrl || null
                this.eventContact = data.eventContact || null
                this.eventContactMail = data.eventContactMail || null
                this.eventContactTel = data.eventContactTel || null
                this.infOpenDate = data.infOpenDate || null
                this.earlyBirdDateStart = data.earlyBirdDateStart || null
                this.earlyBirdDateEnd = data.earlyBirdDateEnd || null
                this.earlyBirdDateChecked = data.earlyBirdDateChecked || null
                this.autoTransChecked = data.autoTransChecked || null
                this.autoTransZhtw = data.autoTransZhtw || null
                this.autoTransEng = data.autoTransEng || null
                this.normalDateStart = data.normalDateStart || null
                this.normalDateEnd = data.normalDateEnd || null
                this.normalDateChecked = data.normalDateChecked || null
                this.locationName = data.locationName || null
                this.locationNameKana = data.locationNameKana || null
                this.country = data.country || null
                this.dialogCountry = null
                this.city = data.city || null
                this.localId = data.localId || null
                this.localUrl = data.localUrl || null
                this.locationDescription = data.locationDescription || null
                this.contentType = data.contentType || "image"
                this.contentImageShow = data.contentImage || null
                this.contentVidioUrl = data.contentVidioUrl || null
                this.contentComment = data.contentComment || null
                this.performance_st_dt = data.performance_st_dt || null
                this.performance_end_dt = data.performance_end_dt || null
                this.hallData = hall || null
                this.hallOriData = hall || null
                this.earlyBirdId = data.earlyBirdId
                this.normalDateId = data.normalDateId
                this.editContent = unescape(data.editContent) || null
                this.hallName = data.hallName || null
                this.post_code = data.post_code || null
                this.hallDisable = false
                this.questionnaires = data.questionnaires || []
                questionnaires = this.sortedQuestionnaires //STS 2021/06/09 Task 15
                if(typeof(data.article) !== 'undefined'){
                    articles = JSON.parse(data.article)
                    this.articles = articles
                }

                for (let i = 0; i < this.articles.length; i++){
                  articles[i].text = unescape(articles[i].text)
                 
                  if(i == 0 && typeof(this.articles[i].title) !== 'undefined'){
                    this.articleTitle = unescape(this.articles[i].title)
                  }
                }
                this.keywords = data.keywords
                this.saleType = data.sale_type || 0
                this.sellAnyone = data.sellAnyone || false
                errorMsnCheack.saleType = this.saleType

                if(this.localId){
                    this.hallDisable = true
                }else{
                    this.hallDisable = false
                }

                if(this.infOpenDate){
                    this.infOpenIsNull = true
                }else{
                    this.infOpenIsNull = false
                }
                /*
                情報公開終了日 
                setFlg : All - 選擇設定日期 | EndDate - 不設定
                */
                if(typeof data.dateEnd === 'undefined'){
                    this.dateEnd = {
                        setFlg : 'All',
                        date : null
                    } 
                }else{
                    this.dateEnd = data.dateEnd
                    if(this.dateEnd.setFlg != 'EndDate'){
                      this.dateEnd.date = null;
                    }
                }
                if(typeof data.enInformation === 'undefined'){
                    this.enInformation = {
                        status:{
                            performanceStatus   : false,
                            hallStatus          : false,
                            informationStatus   : false,
                            lang                : 'en',
                        },
                        data:{
                            langId              :   '',
                            performanceName     :   '',
                            performanceNameSub  :   '',
                            hallDispName        :   '',
                            informationNm       :   '',
                        }
                    }
                }else{
                    this.enInformation       = JSON.parse(data.enInformation)
                }

                this.$nextTick(() => {
                    $('#infOpenDate').val(data.infOpenDate)

                    if(data.performance_st_dt){
                        $('#performance_st_dt').val(this.transDateFormat(data.performance_st_dt))
                    }
                    if(data.performance_end_dt){
                        $('#performance_end_dt').val(this.transDateFormat(data.performance_end_dt))
                    }
                    if(typeof data.dateEnd !== 'undefined'){
                        if(this.dateEnd.setFlg  == 'EndDate'){
                            $('#end-date').val(this.dateEnd.date)
                        }
                    }
                    if(data.earlyBirdDateStart){
                        $('#earlyBirdDateStart').val(data.earlyBirdDateStart)
                    }
                    if(data.earlyBirdDateEnd){
                        $('#earlyBirdDateEnd').val(data.earlyBirdDateEnd)
                    }
                    if(data.normalDateStart){
                        $('#normalDateStart').val(data.normalDateStart)
                    }
                    if(data.normalDateEnd){
                        $('#normalDateEnd').val(data.normalDateEnd)
                    }
                    this.localStatus = data.localStatus || 'N'
                })

            @else
                let date = new Date()
                let perfomanceStatus = -1
                this.eventId = '{!! $eventData['evenId'] !!}'
                this.imageLocation = '{!! $eventData['evenId'] !!}'
                this.eventIdDisable = false
                this.hallDisable = false
                this.contentType = 'image'
                this.hallData = hall || null
                this.hallOriData = hall || null
                errorMsnCheack.saleType = this.saleType
                
                //this.eventContact   =   '{!! $eventData["contact_inf"]["contact_person"] !!}'
                //this.eventContactMail   =   '{!! $eventData["contact_inf"]["mail_address"] !!}'
                //this.eventContactTel    =   '{!! $eventData["contact_inf"]["tel_num"] !!}'

                this.questionnaires = @php echo isset($eventData['questionnaires']) ? json_encode($eventData['questionnaires']) : json_encode([]); @endphp,

                this.enInformation = {
                    status:{
                        performanceStatus   : false,
                        hallStatus          : false,
                        informationStatus   : false,
                        lang                : 'en',
                    },
                    data:{
                        langId              :   '',
                        performanceName     :   '',
                        performanceNameSub  :   '',
                        hallDispName        :   '',
                        informationNm       :   '',
                    }
                }

                /*
                情報公開終了日初始
                setFlg : true - 選擇設定日期 | false - 不設定
                */
                this.dateEnd = {
                    setFlg : 'All',
                    date : null
                } 
            @endif
            


            //字數資料
            this.wordCount = {
                editor:{
                    limit : 1500,
                    total : 0,
                    overTotal: 0,
                    errorStatus : false,
                },
            }
            
            //說明文初始字數計算
            this.wordCount['editor'].total =  (this.editContent)?this.editContent.length:0
            let articleRule = this.wordCount['editor']
            if(this.wordCount['editor'].total > this.wordCount['editor'].limit){
                this.wordCount['editor'].errorStatus = true
                this.wordCount['editor'].overTotal = this.wordCount['editor'].total - this.wordCount['editor'].limit
            }
            
            this.perfomanceInfStatus = perfomanceStatus

            this.statucControl.push({
                basis: [7, 8].includes(perfomanceStatus),
                thumbnail: [7, 8].includes(perfomanceStatus),
                performance_code: [0, 1, 2, 3, 4, 5, 6, 7, 8].includes(perfomanceStatus),
                tenporary_info: [7, 8].includes(perfomanceStatus),
                performance_name_sub: [7, 8].includes(perfomanceStatus),
                offcial_url: [7, 8].includes(perfomanceStatus),
                information_nm: [7, 8].includes(perfomanceStatus),
                mail_address: [7, 8].includes(perfomanceStatus),
                information_tel: [7, 8].includes(perfomanceStatus),
                disp_start: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                performance_dt: [7, 8].includes(perfomanceStatus),
                reserve_dt: [7, 8].includes(perfomanceStatus),
                sell_dt: [7, 8].includes(perfomanceStatus),
                hall_name: [7, 8].includes(perfomanceStatus),
                hall_name_set: [ 7, 8].includes(perfomanceStatus),
                prefecture: [7, 8].includes(perfomanceStatus),
                city: [7, 8].includes(perfomanceStatus),
                hall_url: [7, 8].includes(perfomanceStatus),
                description: [7, 8].includes(perfomanceStatus),
                top_content_type: [7,8].includes(perfomanceStatus),
                top_content_image: [7, 8].includes(perfomanceStatus),
                top_content_url: [7, 8].includes(perfomanceStatus),
                top_content_comment: [7, 8].includes(perfomanceStatus),
                sale_type: [7, 8].includes(perfomanceStatus),
                context: [7, 8].includes(perfomanceStatus),
            })

            if(this.statucControl[0].basis){
                this.pointerEvents = 'pointer-events : none'
            }          
        },
        updated : function() {
            tcs.setValue(this.country, this.city);
        },
         //STS 2020/06/09 Task 15 Start
        computed:{
            sortedQuestionnaires: function() {
                function compare(a, b) {
                    if (a.sort < b.sort)
                        return -1;
                    if (a.sort > b.sort)
                        return 1;
                    return 0;
                }

                return this.questionnaires.sort(compare);  
            },
            limitQuestionnaire: function(){    
                let limit = 10;
                let count = 0;
                if(this.questionnaires.length >= limit){
                    for(let i = 0; i < this.questionnaires.length; i ++){
                        if(this.questionnaires[i].del == false){
                            count++;
                            if(count == limit){
                                return false;
                            }
                        }
                    }
                }
                return true;
            }
        }
        ///STS 2020/06/09 End
    })

    $( "#er" ).change(function() {
        alert( "Handler for .change() called." );
    });
       
    var other = new TwCitySelector({
        el: '.city-selector',
        elCounty: '.county', 
        elDistrict: '.district',
    });
    
    var tcs = new TwCitySelector({
        el: '.city-selector-set-has-value',
        elCounty: '.county',
        elDistrict: '.district',
        elZipcode: '.zipcode',
        countyValue: basisSetting.country,
        districtValue: basisSetting.city,
        hasZipcode: true,
        hiddenZipcode: true,
    });

    function readFile() {
  
  if (this.files && this.files[0]) {
    
    var FR= new FileReader();
    
    FR.addEventListener("load", function(e) {
     // document.getElementById("img").src       = e.target.result;
      //document.getElementById("b64").innerHTML = e.target.result;
      console.log(e.target.result)
    }); 
    
    FR.readAsDataURL( this.files[0] );
  }
  
}


</script>
