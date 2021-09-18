
<div id="ticketContentSetting">
    <!-- //ticketcontent-setting-wrap 票券樣式專用class -->
    <div class="ticketcontent-setting-wrap">
        <div class="form-horizontal">
            <!-- box1 統一樣式兩欄 + 圖片區 -->
            <!--10/08 新增 調整-->
            <div class="">
            <!--<div class="box-header with-border">
                <h3 class="box-title">電子票券樣式</h3>
                </div>-->
                <!--/.10/08 新增 調整-->
                <div class="step-box-body">
                    <!--- 10/08 新增 調整  -->
                    <div class="ticket-tab">
                        <!-- Tab 1 -->
                        <input type="radio" name="tabset" id="tab1" aria-controls="mobapass"> 
                        <label for="tab1" v-show="sellSetting.onlineGetTicket"><?php echo e(trans('events.S_mobapass')); ?></label>
                        <?php if((\App::getLocale() == "zh-tw" )): ?>
                          <!-- Tab 2 -->
                          <input type="radio" name="tabset" id="tab2" aria-controls="qrpass">
                          <label for="tab2"  v-show="sellSetting.qrPassEmail || sellSetting.qrPassSms">qrpass</label>
                          <!-- Tab 3 -->
                          <input type="radio" name="tabset" id="tab3" aria-controls="ibon">
                          <label for="tab3"  v-show="sellSetting.ibon || sellSetting.ibonGetTicket">ibon</label>
                        <?php endif; ?>
                        <?php if((\App::getLocale() == "ja" )): ?>
                        <!-- Tab 4 -->
                          <input type="radio" name="tabset" id="tab4" aria-controls="seven-eleven">
                          <label for="tab4"  v-show="sellSetting.sevenEleven || sellSetting.sevenElevenGetTicket">セブンイレブン</label>
                        <?php endif; ?>
                        <div class="tab-panels">
                            <section id="mobapass" class="tab-panel" v-show="sellSetting.onlineGetTicket">
                                <!-- mobapass -->
                                <!-- BLOCK3 -->
                                <div class="col-md-5 step-section-gray">
                                    <div class="step-title"><?php echo e(trans('events.S_preview')); ?></div>
                                    <div class="step-content">
                                        <div class="mobile-preview">
                                            <div class="mobile" style="background: url('<?php echo e(URL::to('/assets/images/mobapass/img-mobile.png')); ?>') no-repeat #fff;background-size: 100%;">
                                                <!---->
                                                <div class="mobile-content">
                                                    <hgroup>
                                                        <div class="mobile-content__header" style="background: url('<?php echo e(URL::to('/assets/images/mobapass/img-mobile-header.png')); ?>') no-repeat #fff;background-size: 100%;"></div>
                                                        <h1 class="mobile__max-height">{{  ticketView['eventTitle'] }}</h1>
                                                        <div class="top-section">
                                                            <div class="preview-pic">
                                                                <img id="logoImg"  v-bind:src="mobapassLogo" class="img">
                                                            </div>
                                                            <div class="preview-mainbox">
                                                                <div class="preview-maintitle">
                                                                    <h5>{{ ticketView['mbpsdate'] }}</h5>
                                                                    <small>{{ ticketView['mbpstime'] }}</small>
                                                                </div>
                                                                <div class="preview-subtitle">
                                                                    {{ ticketView['hallName'] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                         <div class="small-memo">◼︎ チケット用紙への印字内容と同様 ◼︎</div>
                                                        <div class="main-section">
                                                            <p>{{ ticketView['eventSubTitle'] }}</p>
                                                            <p>{{ ticketView['eventTitle'] }}</p>
                                                            <p>{{ ticketView['hallName'] }}</p>
                                                            <p>{{ ticketView['date'] }}</p>
                                                            <p class="pl-15"><?php echo e(trans('events.S_showTime')); ?>{{ ticketView['time'] }}</p>
                                                            <p>{{ ticketView['seatName'] }}{{ ticketView['ticketName'] }} {{ ticketView['ticketPrice'] }}（税込）</p>
                                                            <p>{{ ticketView['seatInf'] }}</p>
                                                            <hr>
                                                            <p>R1608-182</p>
                                                            <p>{{ ticketView['eventContact'] }}</p>
                                                            <p>{{ ticketView['eventContactTel'] }}</p>
                                                            <p> {{ mobapassContent }} </p>
                                                        </div>
                                                        <div class="mobile-content__tabbar" style="background: url('<?php echo e(URL::to('/assets/images/mobapass/img-mobile-tabbar.png')); ?>') no-repeat #fff;background-size: 100%;"></div>
                                                    </hgroup>
                                                </div>
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.BLOCK3 -->
                                <div class="col-md-7">
                                    <!-- BLOCK2 -->
                                    <div class="col-md-12">
                                        <div class="step-title flex-start"><?php echo e(trans('events.S_displayPIC')); ?>

                                            <div class="tip"><span data-tooltip="<?php echo e(trans('events.S_eventImageNoticeMbps')); ?>"><i class="fas fa-info fa-1x fa__thead"></i></span></div>
                                        </div>
                                        <div class="step-content">
                                            <div class="step-content-drop">
                                                <div class="drop-image">
                                                    <input id="mobapassLogo" type="file"  class="dropify" v-bind:data-default-file="mobapassLogo" data-max-file-size="1M" data-allowed-file-extensions='["png", "jpeg", "jpg"]' @change="imageUpload($event, 'mobapassImage')" :disabled="statucControl[0].thumbnail"/> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.BLOCK2 -->
                                    <!-- BLOCK1 -->
                                    <div class="col-md-12">
                                        <div class="step-title"><?php echo e(trans('events.S_displayMemo')); ?></div>
                                        <div class="step-content">
                                        <!---->
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <select class="form-control select2" @change="stageMsn($event)" v-model="mobapassDate" style="width: 100%;">
                                                        <option value="all"><?php echo e(trans('events.S_defaultMSG')); ?></option>
                                                        <template  v-for="(inf, index) in dateOption">
                                                                <option :value="index">{{ inf.date }}&nbsp&nbsp{{ inf.time }}</option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <textarea class="form-control" rows="5" v-model="mobapassContent" maxlength="1999" :disabled="statucControl[0].free_word"></textarea>
                                                </div>
                                            </div>
                                            <div class="btn-footer text-left">
                                                <button type="button" class="btn btn-block btn-rounded btn-ll waves-effect waves-light btn-info" v-show="!statucControl[0].free_word" v-on:click="updateMobapassData()"><?php echo e(trans('events.S_btnSaveMSG')); ?></button>
                                            </div>
                                            <!---->
                                        </div>
                                    </div>
                                    <!-- /.BLOCK1 -->
                                </div>
                            <!-- /.mobapass -->
                            </section>
                            <?php if((\App::getLocale() == "zh-tw" )): ?>
                            <section id="qrpass" class="tab-panel" v-show="sellSetting.qrPassEmail || sellSetting.qrPassSms">
                                <!-- qrpass -->
                                <!-- BLOCK3 -->
                                <div class="col-md-5 step-section-gray">
                                    <div class="step-title">qrpass 範例</div>
                                    <div class="step-content step-imgblock">
                                        <div class="mobile-preview">
                                            <img src="<?php echo e(URL::to('/assets/images/mobapass/img-qrpass.png')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.BLOCK3 -->
                                <div class="col-md-7">
                                    <!-- BLOCK1 -->
                                    <div class="col-md-12">
                                        <div class="step-title">顯示內容資訊設定</div>
                                        <div class="step-content">
                                            <!-- -->
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <select class="form-control select2"  @change="qrpassMsn($event)" v-model="qrpassDate" style="width: 100%;">
                                                        <option value="all"><?php echo e(trans('events.S_defaultMSG')); ?></option>
                                                            <template  v-for="(inf, index) in dateOption">
                                                                    <option :value="index">{{ inf.date }}&nbsp&nbsp{{ inf.time }}</option>
                                                            </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <textarea class="form-control border-blue mb-3" v-model="qrpassTitle" :disabled="statucControl[0].basis" rows="3"
                                                    placeholder="* 請輸入要顯示的資訊內容"></textarea>
                                                    <textarea class="form-control border-green" v-model="qrpassContent" :disabled="statucControl[0].basis" rows="5" placeholder="* 請輸入要顯示的資訊內容"></textarea>
                                                </div>
                                            </div>
                                            <div class="btn-footer text-left">
                                                <button type="button" class="btn btn-block btn-rounded btn-ll waves-effect waves-light btn-inverse" v-on:click="updateqrpassData()">
                                                    儲存
                                                </button>
                                            </div>
                                        <!---->
                                        </div>
                                    </div>
                                    <!-- /.BLOCK1 -->
                                </div>
                            <!-- /.qrpass -->
                            </section>
                            <section id="ibon" class="tab-panel" v-show="sellSetting.ibon || sellSetting.ibonGetTicket">
                                <!-- ibon -->
                                <!-- BLOCK3 -->
                                <div class="col-md-5 step-section-gray">
                                    <div class="step-title">ibon 範例</div>
                                    <div class="step-content step-imgblock">
                                        <div class="mobile-preview">
                                            <img src="<?php echo e(URL::to('/assets/images/mobapass/img-ibon.png')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.BLOCK3 -->
                                <div class="col-md-7">
                                    <!-- BLOCK1 -->
                                    <div class="col-md-12">
                                        <div class="step-title">顯示內容資訊設定</div>
                                        <div class="step-content">
                                            <!-- -->
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <select class="form-control select2" @change="ibonMsn($event)" v-model="ibonDate" style="width: 100%;">
                                                        <option value="all"><?php echo e(trans('events.S_defaultMSG')); ?></option>
                                                        <template  v-for="(inf, index) in dateOption">
                                                                <option :value="index">{{ inf.date }}&nbsp&nbsp{{ inf.time }}</option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <textarea class="form-control border-blue mb-3" v-model="ibonTitle" :disabled="statucControl[0].basis" rows="3" placeholder="* 請輸入要顯示的資訊內容"></textarea>
                                                    <textarea class="form-control border-green" v-model="ibonContent" :disabled="statucControl[0].basis" rows="5" placeholder="* 請輸入要顯示的資訊內容"></textarea>
                                                </div>
                                            </div>
                                            <div class="btn-footer text-left">
                                                <button type="button" class="btn btn-block btn-rounded btn-ll waves-effect waves-light btn-inverse" v-on:click="updateIbonData()">儲存</button>
                                            </div>
                                            <!---->
                                        </div>
                                    </div>
                                    <!-- /.BLOCK1 -->
                                </div>
                            <!-- /.ibon -->
                            </section>
                            <?php endif; ?>
                            <?php if((\App::getLocale() == "ja" )): ?>
                            <section id="seven-eleven" class="tab-panel"  v-show="sellSetting.sevenEleven || sellSetting.sevenElevenGetTicket">
                                <!-- ibon -->
                                <!-- BLOCK3 -->
                                <div class="col col-sm-12 col-md-7 col-set-100 step-section-gray">
                                    <div class="step-title">セブンイレブン サンプル</div>
                                    <div class="form-checkbox">
                                        <label class="control control--radio pr-30">
                                            <input type="radio" name="passwordSelect"  value='1' v-model="sevenElevenTemplate">タイプ1
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio pr-30">
                                            <input type="radio" name="passwordSelect"  value='2' v-model="sevenElevenTemplate">タイプ2
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="passwordSelect"  value='3' v-model="sevenElevenTemplate">タイプ3
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="step-content step-imgblock">
                                        <div class="mobile-preview">
                                            <!-- 0605調整 -->
                                            <div class="step-title">タイプ {{ sevenElevenTemplate }} ｜ <?php echo e(trans('events.S_preview')); ?></div>
                                            <div class="small-i">
                                            <div class="tip">
                                                <span>
                                                    <i class="fas fa-info fa-1x fa__thead"></i>
                                                    <small> ご使用のブラウザ・ＯＳの機能により、サンプルプレビューを表示しております。<br>
                                                        &ensp;&ensp;&ensp;印刷時に、行末の改行位置が若干変わる可能性がありますので、<br>
                                                        &ensp;&ensp;&ensp;文字数に余裕をもったご設定をお願いいたします。<br>
                                                        &ensp;&ensp;&ensp;※例示につきましては、マニュアルもご参照ください。
                                                    </small>
                                                </span>
                                            </div>
                                            </div>
                                            <!-- 日本 7-11  版型-->
                                             <!-- <div class="step-title">
                                                    <?php echo e(trans('events.S_preview')); ?>

                                                </div>-->
                                                <!-- /.0605調整 -->
                                                <div class="step-content">
                                                    <div class="mobile-preview">
                                                        <div :class="sevenElevenTemplateClass">
                                                            <!---->
                                                            <div class="mobile-content ticket-711-content">
                                                                <hgroup>
                                                                   <!-- <h1>{{  sevenElevenTitle }}</h1>-->
                                                                    <div class="col-sm-12 section-711">
                                                                        <div class="col-md-9 block-711-left">
                                                                            <!-- <div v-html="ticketViewContent"> --> 
                                                                            <div class="stub-overflow" v-html="ticketViewContent"> <!-- task 35 -->
                                                                            </div>
                                                                            <div class="stub-bottom">
                                                                                <p class="stub-info font-8"><!-- 0924新增資訊 -->発券店：セブン-イレブン ○○店</p>
                                                                                <p class="stub-info font-8"><!-- 0924新增資訊 -->払込票：1234567890123           2020.01.01　00:00</p>
                                                                            </div>
                                                                        </div>
                                                                        <!--<hr>-->
                                                                        <!--  行數限定 10列 9行-->
                                                                        <div class="col-md-3 block-711-right">
                                                                            <p class='stub-max-width'></p>
                                                                            <div class="stub-overflow" V-html='ticketViewBContent'>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                </hgroup>
                                                               <!-- <p></p>-->
                                                            </div>
                                                            <!---->
                                                        </div>
                                                    </div>
                                                </div>
                                            <!--  日本 7-11 版型-->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.BLOCK3 -->
                                <div class="col col-sm-12 col-md-5 col-set-100">
                                    <!-- BLOCK1 -->
                                    <div class="col-md-12">
                                        <div class="step-title">表示内容設定</div>
                                        <div class="step-content">
                                            <!--form2-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">イベント名称</label>
                                                    <div class="col-md-9">
                                                        <!-- STS 2021/07/29 Task 43 - START -->
                                                        <!-- <input id="sevenElevenTitle" type="" name='seven-eleven-title'  v-validate="'required|sej_format:21'" class="form-control" placeholder="* イベント名称" :disabled="statucControl[0].basis" v-model="sevenElevenTitle"> -->
                                                        <input id="sevenElevenTitle" type="" name='seven-eleven-title' v-validate="'required|max:255|sej_format:21'" maxlength="250" class="form-control" placeholder="* イベント名称" :disabled="statucControl[0].basis" v-model="sevenElevenTitle"> <!--STS 2021/08/20 Task 46-->
                                                        <span dusk="sevenElevenTitle" v-show="errors.has('seven-eleven-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('seven-eleven-title') }}</span> 
                                                        <!-- STS 2021/07/29 Task 43 - END -->
                                                        <!-- STS 2021/07/26 task 38 -->
                                                        <span class="help is-danger" v-show="sevenElevenTitle && sevenElevenTitle != sevenElevenTitle.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                                    </div>                                                
                                                </div>
                                            <!--/.form2-->
                                            <!-- -->
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <select class="form-control select2" @change="sevenElevenMsn($event)" v-model="sevenElevenDate" style="width: 100%;">
                                                        <option value="all"><?php echo e(trans('events.S_defaultMSG')); ?></option>
                                                        <template v-for="(inf, index) in dateOption">
                                                                <option :value="index">{{ inf.date }}&nbsp&nbsp{{ inf.time }}</option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <textarea class="form-control border-blue mb-3" v-model="sevenElevenMessage1" :disabled="statucControl[0].basis" rows="7" maxlength="341" placeholder="* チケットに表示する文言を入力してください。"></textarea>
                                                    <!-- STS 2021/07/26 task 38 -->
                                                    <span class="help is-danger" v-show="sevenElevenMessage1 && sevenElevenMessage1 != sevenElevenMessage1.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                                    <textarea name='seven-eleven-message2' class="form-control border-green" v-validate="'sej_format:21'" v-model="sevenElevenMessage2" :disabled="statucControl[0].basis" rows="5" maxlength="98" placeholder="* 半券に表示する文言を入力してください。"></textarea>
                                                    <span v-show="errors.has('seven-eleven-message2')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('seven-eleven-message2') }}</span>
                                                     <!-- STS 2021/07/26 task 38 -->
                                                    <span class="help is-danger" v-show="sevenElevenMessage2 && sevenElevenMessage2 != sevenElevenMessage2.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                                </div>
                                            </div>
                                            <div class="btn-footer text-left">
                                                <button type="button" class="btn btn-block btn-rounded btn-ll waves-effect waves-light btn-inverse" :disabled="statucControl[0].basis||sejButtonStatus" v-on:click="updateSevenElevenData()">更新</button>
                                            </div>
                                            <!---->
                                        </div>
                                    </div>
                                    <!-- /.BLOCK1 -->
                                </div>
                            <!-- /.日本 7-11 -->
                            </section>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.10/08 新增 調整 -->
                </div>
            </div>
            <!-- /.box1 統一樣式兩欄 + 圖片區 -->
        </div>
    </div>
    <!-- //ticketcontent-setting-wrap 票券樣式專用class -->
</div>
<script>
    //7-11票左側資料格式
    //STS 2021/07/16 task 35 -- START
        const viewFormatA = {
        1 :{
            'null' : {
                'dataChange':false,
                'rowMax':37,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'performance_name_sub' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventSubTitle'],
                'note':'performance_name_sub',
                // 'class':'stub-subtitle font-8 stub-max-width',
                'class':'stub-subtitle2 font-9 stub-max-width',
            },
            'performance_name' : {
                'dataChange':false,
                // 'rowMax':21,
                'rowMax': 0,
                'data':['eventTitle'],
                'note':'performance_name',
                // 'class':'stub-tilte stub-text-center stub-max-width',
                'class':'stub-tilteFix padding-top stub-text-center font-14F stub-max-width',
            },
            'freeline' : {
                'dataChange':true,
                // 'rowMax':37,
                'rowMax': 0,
                'data':['freeline'],
                'note':'freeline',
                // 'class':'stub-memo font-7',
                'class':'padding-left10 stub-memoA font-7',
            },
            'hall_disp_name' : {
                'dataChange':false,
                // 'rowMax':32,
                'rowMax':0,
                'data':['hallName'],
                'note':'hall_disp_name',
                // 'class':'stub-place font-8 stub-max-width',
                'class':'stub-place2 font-9 stub-max-width',
            },
            'null2' : {
                'dataChange':false,
                'rowMax':37,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'displayOT' : {
                'dataChange':false,
                'rowMax':0,
                'data':['displayOT'],
                'note':'start_time_format',
                // 'class':'stub-date font-10 stub-max-width',
                'class':'stub-dateA font-12 stub-max-width',
            },
            'stage_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['stageName'],
                'note':'stage_name, start_time_format',
                // 'class':'stub-time font-10 stub-text-center stub-max-width',
                'class':'padding-left10 stub-time font-12 stub-text-center stub-max-width',
            },
            'null3' : {
                'dataChange':false,
                // 'rowMax':37,
                'rowMax':0,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'position' : {
                'dataChange':false,
                // 'rowMax':26,
                'rowMax':0,
                'data':['seatName','ticketName','seatInf'],
                'note':'seat_class_name, ticket_class_name, gate, floor_name, block_name, seat_cols, seat_cnumver',
                // 'class':'stub-seat font-10 stub-text-right stub-max-width',
                'class':'stub-seat1 font-15 stub-text-right stub-max-width',
            },
            'sale_price' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketPriceTax'],
                'note':'sale_price',
                // 'class':'stub-price font-10 stub-text-right stub-max-width',
                'class':'padding-left10 stub-price font-15 stub-text-right stub-max-width',
            },
            'reserveNo' : {
                'dataChange':false,
                'rowMax':0,
                'data':['reserveNo'],
                // 'class':'stub-number font-7',
                'class':'padding-left10 stub-seat font-7',
                'note':'reserve_no, reserve_seq'
            },
            'eventContact' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventContact', 'eventContactTel'],
                // 'class':'stub-info font-7 stub-max-width',
                'class':'padding-left10 stub-seat font-7 stub-max-width',
                'note':'eventContact'
            },
        },
        2 : {
            'null' : {
                'dataChange':false,
                'rowMax':37,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'performance_name' : {
                'dataChange':false,
                // 'rowMax':21,
                'rowMax':0,
                'data':['eventTitle'],
                'note':'performance_name',
                // 'class':'stub-tilte stub-max-width',
                'class':'stub-tilte2 font-14F stub-max-width',
            },
            'performance_name_sub' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventSubTitle'],
                'note':'performance_name_sub',
                // 'class':'stub-subtitle font-9 stub-max-width',
                'class':'stub-subtitle2 font-11 stub-max-width',
            },
            'null2' : {
                'dataChange':false,
                'rowMax':37,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'hall_disp_name' : {
                'dataChange':false,
                // 'rowMax':32,
                'rowMax':0,
                'data':['hallName'],
                'note':'hall_disp_name',
                // 'class':'stub-place font-8 stub-max-width',
                'class':'stub-place2 font-9 stub-max-width',
            },
            'displayOTS' : {
                'dataChange':false,
                // 'rowMax':32,
                'rowMax':0,
                'data':['displayOTS'],
                'note':'performance_date',
                // 'class':'stub-date font-8 stub-text-center stub-max-width',
                 'class':'stub-date font-10 stub-text-center stub-max-width',
            },
            'displayT2SS' : {
                'dataChange':false,
                'rowMax':0,
                'data':['displayT2SS'],
                'note':'stagename_opentime',
                // 'class':'stub-date font-8 stub-text-center stub-max-width',
                 'class':'stub-date font-9 stub-text-center stub-max-width',
            },
            'class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['seatName', 'ticketName'],
                'note':'seat_class_name, ticket_class_name',
                // 'class':'stub-seat font-8 stub-text-center stub-max-width',
                'class':'stub-seat3 font-10 stub-text-center stub-max-width',
            },
            'position' : {
                'dataChange':false,
                'rowMax':0,
                'data':['seatInf'],
                'note':'gate, floor_name, block_name, seat_cols, seat_cnumver',
                // 'class':'stub-seat font-10 stub-text-right stub-max-width',
                'class':'stub-seat2 font-13 stub-text-right stub-max-width',
            },
            'sale_price' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketPriceTax'],
                'note':'sale_price',
                // 'class':'stub-price font-10 stub-text-right stub-max-width',
                 'class':'stub-price font-15 stub-text-right stub-max-width',
            },
            'freeline' : {
                'dataChange':true,
                  // 'rowMax':37,
                'rowMax':0,
                'data':['freeline'],
                'note':'freeline',
                // 'class':'stub-memo font-7',
                'class':'stub-memoA font-7 padding-left10',
            },
            'reserveNo' : {
                'dataChange':false,
                'rowMax':0,
                'data':['reserveNo'],
                // 'class':'stub-number font-7',
                'class':'stub-seat font-7 padding-left10',
                'note':'reserve_no, reserve_seq'
            },
            'eventContact' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventContact', 'eventContactTel'],
                // 'class':'stub-info font-7 stub-max-width',
                'class':'stub-seat font-7 stub-max-width padding-left10',
                'note':'eventContact'
            },
        },
        3 : {
            'null' : {
                'dataChange':false,
                'rowMax':37,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'performance_name_sub' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventSubTitle'],
                'note':'performance_name_sub',
                // 'class':'stub-subtitle font-9 stub-text-center stub-max-width',
                'class':'stub-subtitle font-11 stub-text-center stub-max-width',
            },
            'performance_name' : {
                'dataChange':false,
                // 'rowMax':23,
                 'rowMax':0,
                'data':['eventTitle'],
                'note':'performance_name',
                // 'class':'stub-tilte stub-text-center stub-max-width',
                'class':'stub-tilteFix stub-text-center padding-top font-14 stub-max-width',
            },
            'null2' : {
                'dataChange':false,
                'rowMax':0,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'null3' : {
                'dataChange':false,
                'rowMax':0,
                'data':['null'],
                'note':'null',
                'class':'stub-memo font-7',
            },
            'hall_disp_name' : {
                'dataChange':false,
                // 'rowMax':26,
                'rowMax':0,
                'data':['hallName'],
                'note':'hall_disp_name',
                // 'class':'stub-place font-10 stub-text-center stub-max-width',
                'class':'stub-place font-15 stub-text-center stub-max-width',
            },
            'displayOT' : {
                'dataChange':false,
                // 'rowMax':26,
                 'rowMax':0,
                'data':['displayOT'],
                'note':'start_time_format',
                // 'class':'stub-date font-10 stub-text-center stub-max-width',
                'class':'stub-date font-15 padding-topA stub-text-center stub-max-width',
            },
            'stage_inf_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['stageName'],
                'note':'stage_name',
                'class':'stub-time font-10 stub-text-center stub-max-width',
            },
            'class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['seatName', 'ticketName'],
                'note':'seat_class_name, ticket_class_name',
                // 'class':'stub-seat font-8 stub-text-center stub-max-width',
                 'class':'stub-seat font-15 stub-text-center stub-max-width',
            },
            'position_sale_price' : {
                'dataChange':false,
                // 'rowMax':26,
                'rowMax':0,
                'data':['seatInf', 'ticketPriceTax'],
                'note':'sale_price',
                // 'class':'stub-seat font-10 stub-text-center stub-max-width',
                'class':'stub-seat font-15 stub-text-center stub-max-width',
            },
            'freeline' : {
                'dataChange':true,
                  // 'rowMax':37,
                'rowMax':0,
                'data':['freeline'],
                'note':'freeline',
                // 'class':'stub-memo font-7',
                'class':'stub-memoA font-7 padding-left10',
            },
            'reserveNo' : {
                'dataChange':false,
                'rowMax':0,
                'data':['reserveNo'],
                // 'class':'stub-number font-7',
                'class':'stub-seat font-7 padding-left10',
                'note':'reserve_no, reserve_seq'
            },
            'eventContact' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventContact', 'eventContactTel'],
                // 'class':'stub-info font-7 stub-max-width',
                'class':'stub-seat font-7 stub-max-width padding-left10',
                'note':'eventContact'
            },
        }
    }
    //7-11票右側資料格式
    const viewFormatB = {
        1 : {
            'performance_code' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventId'],
                // 'class':'stub-code font-8 stub-max-width',
                'class':'stub-price font-11 stub-max-width',
                'note':'performance_code'
            },
            'performance_name_sub' : {
                'dataChange':false,
                // 'rowMax':10,
                'rowMax':0,
                'data':['eventSubTitle'],
                'class':'stub-tilte font-10 stub-max-width',
                'note':'performance_name_sub'
            },
            'performance_name' : {
                'dataChange':false,
                // 'rowMax':10,
                'rowMax':0,
                'data':['eventTitle'],
                // 'class':'stub-tilte font-7 stub-max-width',
                'class':'stub-tilte font-9 stub-max-width',
                'note':'performance_name_sej'
            },
            'displaySOT' : {
                'dataChange':false,
                'rowMax':0,
                'data':['displaySOT'],
                'class':'stub-date font-12 stub-max-width',
                'note':'performance_date'
            },

            'start_time' : {
                'dataChange':false,
                'rowMax':0,
                'data':['time'],
                // 'class':'stub-time font-7 stub-max-width',
                'class':'stub-time font-12 stub-max-width',
                'note':'start_time'
            },
            'stage_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['stageName'],
                'class':'stub-time font-9 stub-max-width',
                'note':'start_time'
            },
            'seat_class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['seatName'],
                // 'class':'stub-seat font-8 stub-max-width',
                'class':'stub-seat font-10 stub-max-width',
                'note':'seat_class_name'
            },
            'ticket_class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketName'],
                // 'class':'stub-seat font-8 stub-max-width',
                'class':'stub-seat font-10 stub-max-width',
                'note':'ticket_class_name'
            },
            'sale_price' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketPrice'],
                // 'class':'stub-price font-8 stub-max-width',
                'class':'stub-price font-10 stub-max-width',
                'note':'sale_price'
            },
            'gate' : {
                'dataChange':false,
                'rowMax':0,
                'data':['gate'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'gate'
            },
            'floor_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['floor'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'floor_name'
            },

            'block_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['block'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'block_name'
            },
            'seat_row' : {
                'dataChange':false,
                'rowMax':0,
                'data':['row'],
                'class':'stub-seat font-12 stub-max-width',
                'note':'seat_cols'
            },
            'seat_cols' : {
                'dataChange':false,
                'rowMax':0,
                'data':['col'],
                'class':'stub-seat font-12 stub-max-width',
                'note':'seat_cols'
            },
            'reserveNo' : {
                'dataChange':false,
                'rowMax':0,
                'data':['reserveNo'],
                'class':'stub-number font-10 stub-max-width',
                'note':'reserve_no, reserve_seq'
            },
            'freeline' : {
                'dataChange':true,
                'rowMax':0,
                'data':['freeline'],
                'class':'stub-memo font-8',
                'note':'freeline'
            },
        },
        2 : {
            'performance_code' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventId'],
                // 'class':'stub-code font-8 stub-max-width',
                'class':'stub-price font-10 stub-max-width',
                'note':'performance_code'
            },
            'performance_name' : {
                'dataChange':false,
                // 'rowMax':10,
                'rowMax':0,
                'data':['eventTitle'],
                // 'class':'stub-tilte font-7 stub-max-width',
                'class':'stub-tilte font-8 stub-max-width',
                'note':'performance_name_sej'
            },
            'performance_name_sub' : {
                'dataChange':false,
                // 'rowMax':10,
                'rowMax':0,
                'data':['eventSubTitle'],
                // 'class':'stub-subtitle font-7 stub-max-width',
                'class':'stub-subtitle font-8 stub-max-width',
                'note':'performance_name_sub'
            },
            'displaySOT' : {
                'dataChange':false,
                // 'rowMax':0,
                'rowMax':0,
                'data':['displaySOT'],
                // 'class':'stub-date font-9 stub-max-width',
                'class':'stub-date font-12 stub-max-width',
                'note':'performance_date'
            },

            'stage_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['stageName'],
                'class':'stub-time font-9 stub-max-width',
                // 'class':'stub-time font-10 stub-max-width',
                'note':'stage_name'
            },
            'start_time' : {
                'dataChange':false,
                'rowMax':0,
                'data':['time'],
                // 'class':'stub-time font-9 stub-max-width',
                'class':'stub-time font-10 stub-max-width',
                'note':'start_time'
            },
            'seat_class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['seatName'],
                // 'class':'stub-seat font-8 stub-max-width',
                'class':'stub-seat font-10F stub-max-width',
                'note':'seat_class_name'
            },
            'ticket_class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketName'],
                // 'class':'stub-seat font-8 stub-max-width',
                'class':'stub-seat font-10F stub-max-width',
                'note':'ticket_class_name'
            },
            'sale_price' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketPrice'],
                // 'class':'stub-price font-8 stub-max-width',
                'class':'stub-price font-10F stub-max-width',
                'note':'sale_price'
            },
            'gate' : {
                'dataChange':false,
                'rowMax':0,
                'data':['gate'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'gate'
            },
            'floor_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['floor'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'floor_name'
            },

            'block_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['block'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'block_name'
            },
            'seat_row' : {
                'dataChange':false,
                'rowMax':0,
                'data':['row'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'seat_cols'
            },
            'seat_cols' : {
                'dataChange':false,
                'rowMax':0,
                'data':['col'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'seat_cols'
            },
            'reserveNo' : {
                'dataChange':false,
                'rowMax':0,
                'data':['reserveNo'],
                // 'class':'stub-number font-8 stub-max-width',
                'class':'stub-number font-11 stub-max-width',
                'note':'reserve_no, reserve_seq'
            },
            'freeline' : {
                'dataChange':true,
                'rowMax':0,
                'data':['freeline'],
                'class':'stub-memo font-7',
                'note':'freeline'
            },
        },
        3 : {
            'performance_code' : {
                'dataChange':false,
                'rowMax':0,
                'data':['eventId'],
                // 'class':'stub-code font-8 stub-max-width',
                'class':'stub-price font-10 stub-max-width',
                'note':'performance_code'
            },
            'performance_name_sub' : {
                'dataChange':false,
                // 'rowMax':10,
                'rowMax':0,
                'data':['eventSubTitle'],
                'class':'stub-tilte font-7 stub-max-width',
                'note':'performance_name_sub'
            },
            'performance_name' : {
                'dataChange':false,
                // 'rowMax':10,
                'rowMax':0,
                'data':['eventTitle'],
                // 'class':'stub-tilte font-7 stub-max-width',
                'class':'stub-tilte font-9 stub-max-width',
                'note':'performance_name_sej'
            },
            'displaySOT' : {
                'dataChange':false,
                'rowMax':0,
                'data':['displaySOT'],
                // 'class':'stub-date font-9 stub-max-width',
                'class':'stub-date font-12 stub-max-width',
                'note':'performance_date'
            },

            'start_time' : {
                'dataChange':false,
                'rowMax':0,
                'data':['time'],
                // 'class':'stub-time font-9 stub-max-width',
                'class':'stub-time font-10 stub-max-width',
                'note':'start_time'
            },
             'stage_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['stageName'],
                'class':'stub-time font-9 stub-max-width',
                // 'class':'stub-time font-10 stub-max-width',
                'note':'stage_name'
            },
            'seat_class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['seatName'],
                // 'class':'stub-seat font-8 stub-max-width',
                'class':'stub-seat font-10F stub-max-width',
                'note':'seat_class_name'
            },
            'ticket_class_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketName'],
                // 'class':'stub-seat font-8 stub-max-width',
                'class':'stub-seat font-10F stub-max-width',
                'note':'ticket_class_name'
            },
            'sale_price' : {
                'dataChange':false,
                'rowMax':0,
                'data':['ticketPrice'],
                // 'class':'stub-price font-8 stub-max-width',
                'class':'stub-price font-10F stub-max-width',
                'note':'sale_price'
            },
            'gate' : {
                'dataChange':false,
                'rowMax':0,
                'data':['gate'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'gate'
            },
            'floor_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['floor'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'floor_name'
            },

            'block_name' : {
                'dataChange':false,
                'rowMax':0,
                'data':['block'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'block_name'
            },
            'seat_row' : {
                'dataChange':false,
                'rowMax':0,
                'data':['row'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'seat_cols'
            },
            'seat_cols' : {
                'dataChange':false,
                'rowMax':0,
                'data':['col'],
                // 'class':'stub-seat font-9 stub-max-width',
                'class':'stub-seat font-12 stub-max-width',
                'note':'seat_cols'
            },
            'reserveNo' : {
                'dataChange':false,
                'rowMax':0,
                'data':['reserveNo'],
                // 'class':'stub-number font-8 stub-max-width',
                'class':'stub-number font-10 stub-max-width',
                'note':'reserve_no, reserve_seq'
            },
            'freeline' : {
                'dataChange':true,
                'rowMax':0,
                'data':['freeline'],
                'class':'stub-memo font-8',
                'note':'freeline'
            },
        },
    }
    //STS 2021/07/16 task 35 -- END
    var mbpsPreviewPrev = '';
    var ticketViewSetting = new Vue({
        el: "#ticketContentSetting",
        data: {
            allPaymentData:[],
            mobapass:true,
            mobapassDate:'all',
            mobapassInf:[],
            mobapassContent:'',
            mobapassLogo:'',
            mobapassViewDate:'',
            mobapassViewTime:'',
            qrpassDate:'all',
            qrpassInf:[],
            qrpassTitle:'',
            qrpassContent:'',
            ibonDate:'all',
            ibonInf:[],
            ibonTitle:'',
            ibonContent:'',
            sevenElevenDate:'all',
            sevenElevenTemplate:"1",
            sevenElevenTemplateClass:"mobile ticket-711 ticket-s1",
            sevenElevenInf:[],
            sevenElevenTitle:'',
            sevenElevenMessage1:'',
            sevenElevenMessage1Output:[],
            sevenElevenMessage2:'',
            sevenElevenMessage2Output:[],
            dateOption:'',
            ticketName:'',
            ticketPrice:'',
            statucControl:[],
            preset:'',
            ticketView:[],
            ticketViewContent:'',
            ticketViewBContent:'',
            lang: '<?php echo e(\App::getLocale()); ?>',
            fbgCtrl: '<?php echo $eventData["fbgCtrl"]; ?>',
            addButtonErrorStatus: false,
            sejButtonStatus: false,
            saleType: basisSetting.saleType, //STS 2021/07/26 task 38
        },
        watch: {
        	//STS 2021/07/26 task 38 
            saleType: function() {
                this.settingCheack()
            },
            errors:{
                handler(){
                    this.settingCheack()
                    this.cheackSejFormat()
                },
                deep: true
            },
            allPaymentData:{
                handler(){
                    this.saveLocalStorage()
                },
                deep: true
            },
            sevenElevenTemplate:function(val) {
                this.sevenElevenTemplateClass = `mobile ticket-711 ticket-s${val}`,
                this.sevenElevenTemplateSort()
                this.sevenElevenTemplateBSort()
            },
            sevenElevenMessage1:function(val){
                this.sevenElevenTemplateSort()
                this.settingCheack() //STS 2021/07/26 task 38
            },
            sevenElevenMessage2:function(val){
                this.sevenElevenTemplateBSort()
                this.settingCheack() //STS 2021/07/26 task 38
            },
            sevenElevenTitle:function(val){
                this.sevenElevenTemplateBSort()
                this.settingCheack() //STS 2021/07/26 task 38
            },
        },
        methods: {
            
            /*
            *檢查
            *
            */
            settingCheack:function (item) {
                // if(this.errors.any()){
                //     tagControl.ticketViewWarning = true
                // }else{
                //     tagControl.ticketViewWarning = false
                // }
                // STS 2021/07/26 task 38 -- START
                  if(((this.sevenElevenTitle && this.sevenElevenTitle != this.sevenElevenTitle.replace(/-+/g,'-')) || (this.sevenElevenMessage1 && this.sevenElevenMessage1 != this.sevenElevenMessage1.replace(/-+/g,'-')) || (this.sevenElevenMessage2 && this.sevenElevenMessage2 != this.sevenElevenMessage2.replace(/-+/g,'-'))) && this.saleType == 1) {
                    this.addButtonErrorStatus = true;
                    errorMsnCheack.addBtnCheack();
                } else if(((this.sevenElevenTitle && this.sevenElevenTitle != this.sevenElevenTitle.replace(/-+/g,'-')) || (this.sevenElevenMessage1 && this.sevenElevenMessage1 != this.sevenElevenMessage1.replace(/-+/g,'-')) || (this.sevenElevenMessage2 && this.sevenElevenMessage2 != this.sevenElevenMessage2.replace(/-+/g,'-'))) && this.saleType != 1) {
                     this.addButtonErrorStatus = false;
                    errorMsnCheack.addBtnCheack();
                } else {
                    this.addButtonErrorStatus = false;
                    errorMsnCheack.addBtnCheack(); 
                }
                // STS 2021/07/26 task 38 -- END
            },
            /**
             * 檢查 SEJ 格式
             * 
             * 
             */
            cheackSejFormat:function(){
                if(this.errors.any()){
                    this.sejButtonStatus = true;
                }else{
                    this.sejButtonStatus = false;
                }
            },
            /**
             * 7-11 文字字數換行處理
             */
            setViewFormat:function(text, type, format){
                let formatRule = ''

                if(format == 'A'){ 
                    formatRule = viewFormatA[this.sevenElevenTemplate][type]
                }else if(format == 'B'){ 
                    formatRule = viewFormatB[this.sevenElevenTemplate][type]
                }else{
                    return ''
                }
      
                let rowMax = formatRule['rowMax']
                let rows =  text.split('\n')
                let message = ''
                let messageGet = []
                let nullArray = ['']
                let rule = new RegExp(`.{0,${rowMax}}`, 'g')

                for(let no = 0; no < rows.length; no++){
                    if(rows[no]){
                        if(type != 'stage_inf_name ') {
                             msnChange   =   rows[no].match(rule).join('<br>')
                        }

                    }else{
                        messageGet  =   messageGet.concat(nullArray)
                    }
                }

                return msnChange
            },
             /*
             *取得活動名稱
             */
            getElevenTitle:function(){
                if(typeof this.sevenElevenTitle == 'undefined' || this.sevenElevenTitle.length == 0 || !this.sevenElevenInf[0].titleCustom){
                        this.sevenElevenTitle = basisSetting.eventTitle
                }
            },
            /*
             *取得票面資料
             *
             *@param  calenderId 場次編號
             */
            getTicketViewInf:function(calenderParentId = 0, calenderId = 0){
                let ticketView = []
                let ticket = {
                    seatName : '',
                    ticketName : '',
                    ticketPrice : 0,
                };
                let seat = {
                    gate : '',
                    floor : '',
                    block : '',
                    col : '',
                    row : '',
                };
                let calender = {
                    date : '',
                    mbpsTitle : '', //モバパスタイトル用
                    mbpsTime : '',  //モバパスタイトル用
                    stage : '',     //モバパスタイトル用
                    time : '',
                    specTitle : '', //表示公演日時
                };
                let ticketType = 1 // 1 : 全席自由 2 : 指定全席自由 3 : 指定席

                this.getElevenTitle()
               
                if(ticketSetting.typeTicketSetting == "selectSeat"){
                    let ticketData = ticketSetting.settingSeatData
                    let filterData = ticketData.filter(function(item, index, array){
                                                        return item.seatStatus != 'D'
                                                  })
                    let seatInfSet = false

                    if(filterData.length > 0){
                        ticketType = 2
                    }
                  
                    for(item of filterData){
                        if(!item.seatFree && item.seatStatus != 'D'){
                            ticketType = 3
                            if(seatInfSet){
                                break
                            }
                        }
                        if(!seatInfSet){
                            for(data of item.data){
                                if(data.ticketStatus != 'D'){
                                    seatInfSet = true
                                    ticket['seatName'] = item.seatName.toString()
                                    ticket['ticketName'] = data.ticketName.toString() 
                                    ticket['ticketPrice'] = data.ticketPrice
                                    break
                                }
                            }
                        }
                    } 
                }else{
                    let ticketData = ticketSetting.freeSeatData
                    let filterData = ticketData.find(function(item, index, array){
                                                        return item.ticketStatus != 'D'
                                                  })
                    if(filterData){
                        ticket['seatName'] = ticketSetting.freeSeatTicketName.toString() 
                        ticket['ticketName'] = filterData.ticketName.toString()
                        ticket['ticketPrice'] = filterData.ticketPrice 
                    }
                }
                

                if(timeCourse.settingRadio == 'normal'){
                    var calenderData = timeCourse.allDay

                    if (typeof calenderData !== 'undefined' && calenderData.length > 0){
                        if(calenderData[0].rule.length === 0 && calenderParentId == 0){
                            var calenderData = timeCourse.allDay.filter(function (inf) {
                                                    return inf.rule.length > 0;
                                                });

                        }
                        if(typeof calenderData[calenderId] !== 'undefined'){
                            var date = new Date(calenderData[calenderId].rule[calenderParentId].date)

                            calender['time'] = calenderData[calenderId].rule[calenderParentId].time

                            if (calenderData[calenderId].rule[calenderParentId].title != ''){
                                //表示公演日時
                                calender['specTitle'] = calenderData[calenderId].rule[calenderParentId].title
                            }
                        }else{
                            var date = new Date()
                        }
                      
                        //ステージ
                        if((timeCourse.ruleInf[calenderParentId])?timeCourse.ruleInf[calenderParentId].status:false){
                            calender['stage'] = (timeCourse.ruleInf[calenderParentId])?timeCourse.ruleInf[calenderParentId].title:''
                        }
                   }
                }else{
                    //期間券
                    var date = new Date(basisSetting.performance_st_dt)
                    //calender['date'] = `${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}`
                    calender['time'] = timeCourse.specDate
                    
                    if (timeCourse.specTitle != '')
                    {
                      //表示公演日時
                      calender['specTitle'] = timeCourse.specTitle
                    }
                    if(timeCourse.specEvenTitle != null)
                    {
                      //ステージ
                      calender['stage'] = timeCourse.specEvenTitle
                    }
                }
                var month = date.getMonth()+1;
                var dayOfWeek = date.getDay() ;
                var dayOfWeekStr = [ "日", "月", "火", "水", "木", "金", "土" ][dayOfWeek] ;
                // calender['date'] = `${date.getFullYear()}年${("0"+month).slice(-2)}月${("0"+date.getDate()).slice(-2)}日（${dayOfWeekStr}）`
                calender['date'] = `${date.getFullYear()}年${month}月${(date.getDate())}日（${dayOfWeekStr}）`
                calender['sdate'] = `${date.getFullYear()}/${("0"+month).slice(-2)}/${("0"+date.getDate()).slice(-2)}`

                //モバパス用
                if(calender['specTitle'] == '' || calender['specTitle'] == null)
                {
                  //表示公演日時無し
                  calender['mbpsTitle'] = calender['date'];
                  calender['mbpsTime'] = calender['stage'] + calender['time'] +' '+ '<?php echo e(trans('events.S_showTime')); ?>';
                }else{
                  calender['mbpsTitle'] = calender['specTitle'] ;
                  calender['mbpsTime'] = '';
                }

                let map = seatSetting.mapData
                
                for(floatId in map){ 
                    let float = map[floatId]
                    seat['floor'] = float.floorTittle.toString()
                    for(blockId in float.blockData){
                        let block = float.blockData[blockId]
                        seat['block'] = block.blockTittle.toString() /*+  '<?php echo e((\App::getLocale() == "ja" )? 'ブロック':'區'); ?>'*/
                        seat['gate'] = block.gate.toString()
                        for(seatId in block.seatData){
                            let seatData = block.seatData[seatId]
                            seat['col'] = seatData.number.toString()
                            seat['row'] = seatData.rowname.toString()
                           
                            break
                        }
                        break
                    }
                    break
                }

                var displayOT = calender['date']+' '+calender['time']+' '+'<?php echo e(trans('events.S_showTime')); ?>'
                var displayOTS = calender['date']
                var displaySOT = calender['sdate'];
                var displayT2SS = calender['stage']
                 if(calender['specTitle'] != null && calender['specTitle'] != '') {
                    if(timeCourse.settingRadio == 'normal'){
                        displayOT = calender['date']+' '+calender['specTitle']
                        displaySOT = calender['sdate']+' '+calender['specTitle']

                        calender['time'] = ''
                    }
                    else {
                        displayOT = calender['specTitle']
                        calender['time'] = ''
                        displaySOT = displayOT
                    }
                    displayOTS = displayOT
                }


                else {
                    displayT2SS += calender['time']+'　'+'<?php echo e(trans('events.S_showTime')); ?>'
                }
                var floor = ''
                var block = ''
                var gate = ''
                var col = ''
                var row = '1'
                let seatInf = ''
                if(ticketSetting.typeTicketSetting == "selectSeat"){
                    if(this.fbgCtrl.indexOf('f') != -1) {
                        floor = seat['floor']
                    }
                    if(this.fbgCtrl.indexOf('b') != -1) {
                        block = seat['block']
                    }
                    if(this.fbgCtrl.indexOf('g') != -1) {
                        gate = seat['gate']
                    }
                    col = seat['col']
                    row = seat['row']
                }
                
                switch (ticketType) {
                    case 1:
                        seatInf = ''
                        floor = ''
                        block = ''
                        gate = ''
                        col = ''
                        row = ''
                        break;
                    case 2:
                        seatInf = 1
                        floor = ''
                        block = ''
                        gate = ''
                        col = ''
                        row = '1'
                        break;
                    case 3:
                    // seatInf = `${gate} ${floor} ${block} ${row} ${col}`
                    //STS 2021/07/16 task 35
                        seatInf = `${gate}　${floor}　${block}　${row}　${col}`
                        break;
                    default:
                        console.log(`Sorry, we are out of ${expr}.`);
                }

                this.ticketView = {
                    eventTitle : basisSetting.eventTitle,
                    eventSubTitle : basisSetting.eventSubTitle,
                    hallName : basisSetting.hallName,
                    eventContact : 'お問合せ先：'+ (basisSetting.eventContact != null ? basisSetting.eventContact : ''),
                    eventContactTel : '電話番号：'+basisSetting.eventContactTel, 
                    mbpsdate : calender['mbpsTitle'], //モバパス用
                    mbpstime :calender['mbpsTime'], //モバパス用
                    date : calender['date'],
                    time : calender['time'],
                    seatName : ticket['seatName'],
                    ticketType : ticketType,
                    ticketName : ticket['ticketName'],
                    ticketPrice : '￥'+this.commaFormat(ticket['ticketPrice']),
                    ticketPriceTax : '￥'+this.commaFormat(ticket['ticketPrice'])+'（税込）',
                    gate : gate,
                    floor : floor,
                    block : block,
                    col : col,
                    row : row,
                    seatInf : seatInf,
                    eventId : basisSetting.eventId,
                    stageName : calender['stage'],
                    displayOT : displayOT,
                    displayOTS : displayOTS,
                    displaySOT : displaySOT,
                    reserveNo : '2002-7600001-111',
                    displayT2SS : displayT2SS
                }
                this.sevenElevenTemplateClass = `mobile ticket-711 ticket-s${this.sevenElevenTemplate}`,
                this.sevenElevenTemplateSort()
                this.sevenElevenTemplateBSort()
            },
            /**
             * 7-11 左側資料呈現
             */
            sevenElevenTemplateSort:function(){
                let content = []
                let data = this.ticketView 
                let ticketViewContent = ''
                let viewFormatARule = viewFormatA[this.sevenElevenTemplate]

                for (let [key, value] of Object.entries(viewFormatARule)) {
                    let infText = ''
                    if(!value.dataChange){
                        for(let no = 0; no < value.data.length; no++){

                            if(data[value.data[no]]){
                                infText += `${data[value.data[no]]}　`
                            }
                            else {
                                infText += ` `
                            }
                        }
                    }

                    if(value.dataChange && key == 'freeline'){
                        let rows        =  this.sevenElevenMessage1.split('\n')
                        let message     = ''
                        let messageGet  = []
                        let nullArray   = ['']

                        for(let no = 0; no < rows.length; no++){
                            if(rows[no]){
                                msnChange   =   rows[no].match(/.{1,36}/g).join('<br>')
                                messageGet  =   messageGet.concat(msnChange.split('<br>'))
                            }else{
                                messageGet = messageGet.concat(nullArray)
                            }
                        }

                        for(let no = 0; no < messageGet.length; no++){
                            if(no > 8){
                                break
                            }
                            infText += `${messageGet[no]}<br>`

                        }
                        infText = infText.slice(0, -4)   //STS 2021/07/16 task 35              
                    }
                    //STS 2021/07/16 task 35 START  
                    //  if(infText != '' && !value.dataChange){
                    //     //task 35    
                    // if(key == 'stage_inf_name' && this.setViewFormat(infText, key, 'A').trim() == '<br>') continue;
                    // if(key == 'stage_name' && this.setViewFormat(infText, key, 'A').trim() == '<br>') continue;  
                    // //task 35 
                    //     ticketViewContent += `<p class='${value.class}'>${this.setViewFormat(infText, key, 'A')}</p>`
                    // }else if(value.dataChange){
                    //     ticketViewContent += `<p class='${value.class}'>${infText}</p>`
                    // }else if(value.note == 'null'){
                    //     ticketViewContent += `<p class='${value.class}'></p>`
                    // }
                    //STS 2021/07/16 task 35 END
                    
                    if(infText != '' && !value.dataChange && value.rowMax != 0){
                    //STS 2021/07/16 task 35 START   
                    if(key == 'stage_inf_name' && this.setViewFormat(infText, key, 'A').trim() == '<br>') continue;
                    if(key == 'stage_name' && this.setViewFormat(infText, key, 'A').trim() == '<br>') continue;  
                    //STS 2021/07/16 task 35 END
                    ticketViewContent += `<p class='${value.class}'>${this.setViewFormat(infText, key, 'A')}</p>`
                    }else if(infText != '' && value.rowMax == 0){
                        ticketViewContent += `<p class='${value.class}'>${infText}</p>`
                    }else if(infText != '' && value.dataChange && key == 'freeline'){
                        ticketViewContent += infText
                    }else if(key == 'null'){
                        ticketViewContent += `<p class='${value.class}'></p>`
                    }
                }

                this.ticketViewContent = ticketViewContent
            },
            /**
             * 7-11 右側資料呈現
             */
            sevenElevenTemplateBSort:function(){
                let content = []
                let data = this.ticketView 
                let ticketViewContent = ''
                let viewFormatRule = viewFormatB[this.sevenElevenTemplate]

                for (let [key, value] of Object.entries(viewFormatRule)) {
                    let infText = ''
                    if(!value.dataChange){
                        for(let no = 0; no < value.data.length; no++){

                            if(data[value.data[no]]){
                                infText += `${data[value.data[no]]} `
                            }

                            if(value.note == 'performance_name_sej'){
                                infText = this.sevenElevenTitle
                            }
                        }
                    }

                    if(value.dataChange && key == 'freeline'){
                        let rows        =  this.sevenElevenMessage2.split('\n')
                        let message     = ''
                        let messageGet  = []
                        let nullArray   = ['']

                        for(let no = 0; no < rows.length; no++){
                            if(rows[no]){
                                msnChange   =   rows[no].match(/.{1,10}/g).join('<br>')
                                messageGet  =   messageGet.concat(msnChange.split('<br>'))
                            }else{
                                messageGet = messageGet.concat(nullArray)
                            }
                        }

                        for(let no = 0; no < messageGet.length; no++){
                            if(no > 7){
                                break
                            }
                            if (messageGet[no].trim() == '') continue; //STS 2021/07/16 task 35
                            infText += `${messageGet[no]}<br>`
                        }

                        
                    }
                    
                    if(infText != '' && !value.dataChange && value.rowMax != 0){
                        ticketViewContent += `<p class='${value.class}'>${this.setViewFormat(infText, key, 'B')}</p>`
                    }else if(infText != '' && value.rowMax == 0){
                        ticketViewContent += `<p class='${value.class}'>${infText}</p>`
                    }else if(infText != '' && value.dataChange && key == 'freeline'){
                        ticketViewContent += infText
                    }else if(key == 'null'){
                        ticketViewContent += `<p class='${value.class}'></p>`
                    }
                }

                this.ticketViewBContent = ticketViewContent
            },
            commaFormat: function(value) {
                return value
                        .toString()
                        .replace(/^(-?\d+?)((?:\d{3})+)(?=\.\d+$|$)/, function(all, pre, groupOf3Digital) {
                            return pre + groupOf3Digital.replace(/\d{3}/g, ',$&');
                        });
            },
            /**
             * 預設票卷編輯畫面顯示
             * 
             */
            presetShow:function(){
                if(sellSetting.onlineGetTicket){
                    document.getElementById('tab1').click()
                }else if((sellSetting.qrPassEmail || sellSetting.qrPassSms) && this.lang == 'zh-tw'){
                    document.getElementById('tab2').click()
                }else if((sellSetting.ibon || sellSetting.ibonGetTicket) && this.lang == 'zh-tw'){
                    document.getElementById('tab3').click()
                }else if(sellSetting.sevenEleven || sellSetting.sevenElevenGetTicket){
                    document.getElementById('tab4').click()
                }

                this.getTicketViewInf()
            },
            getTicketViewData:function(){
                return this.allPaymentData
            },
            /**
             * 日本 7-11 呈現選擇日期的備註訊息
             * @param  event 日期下拉選單
             */
            sevenElevenMsn:function(event){
                
                if(event.target.value == 'all'){
                    let calender = timeCourse.allDay
                    let message1 = this.sevenElevenMessage1  
                    let message2 = this.sevenElevenMessage2
                    
                    if(typeof(this.sevenElevenInf) == "undefined"){
                    
                        this.sevenElevenInf.push({
                            cheacked:'',
                            template:'',
                            id: '',
                            title: '',
                            titleCustom: false,
                            message1:'',
                            message2:'',
                            status: '', 
                            logoPath: '',
                        })

                        this.allPaymentData[0].data[0].sevenEleven = this.sevenElevenInf
                    }

                    this.getTicketViewInf()
                    this.sevenElevenMessage1    = unescape(this.sevenElevenInf[0].message1)
                    this.sevenElevenMessage2    = unescape(this.sevenElevenInf[0].message2)
                }else{
                    let id          = event.target.value
                    let stageData   = this.dateOption[id]
                    let index       = stageData.dateId
                    let parentIndex = stageData.ruleId
                    let selectData  = this.sevenElevenDate
                    let calender    = timeCourse.allDay 

                    if(typeof(calender[index].rule[parentIndex].ticketMsm.sevenEleven) == 'undefined'){
                        let msnContent = {
                            type: '',
                            title: '',
                            msm: '',
                            status: '',
                            layoutId: '',
                        }
                        
                        calender[parentIndex].rule[index].ticketMsm.sevenEleven = msnContent

                        timeCourse.allDay = calender 
                        timeCourse.saveLocalStock(timeCourse.allDay)
                    }

                    let message1 = calender[index].rule[parentIndex].ticketMsm.sevenEleven.message1 
                    let message2 = calender[index].rule[parentIndex].ticketMsm.sevenEleven.message2

                    if(!message1){
                        message1 = this.sevenElevenInf[0].message1
                    }

                    if(!message2){
                        message2 = this.sevenElevenInf[0].message2
                    }

                    this.getTicketViewInf(parentIndex, index)
                    this.sevenElevenMessage1  = unescape(message1)
                    this.sevenElevenMessage2  = unescape(message2)
                }

            },
            /**
             * 日本 7-11 信息更新
             */
            updateSevenElevenData:function(){
                let calender = timeCourse.allDay
                let message1  = this.sevenElevenMessage1 
                let message2  = this.sevenElevenMessage2
                
                if(this.sevenElevenInf[0].id){   
                    this.sevenElevenInf[0].template  = this.sevenElevenTemplate
                    this.sevenElevenInf[0].status    = 'U'
                }else{
                    this.sevenElevenInf[0].template  = this.sevenElevenTemplate
                    this.sevenElevenInf[0].status    = 'I'
                }

                this.allPaymentData[0].data[0].sevenEleven = this.sevenElevenInf

                this.sevenElevenInf[0].title = this.sevenElevenTitle
                this.sevenElevenInf[0].titleCustom = true

                if(this.sevenElevenDate == 'all'){
                    if(this.sevenElevenInf[0].id){
                        this.sevenElevenInf[0].message1 = escape(message1)
                        this.sevenElevenInf[0].message2 = escape(message2)
                        this.sevenElevenInf[0].status   = 'U'
                    }else{
                        this.sevenElevenInf[0].message1 = escape(message1)
                        this.sevenElevenInf[0].message2 = escape(message2)
                        this.sevenElevenInf[0].status   = 'I'
                    }
                    
                    this.allPaymentData[0].data[0].sevenEleven = this.sevenElevenInf
                }else{
                    let id              = this.sevenElevenDate
                    let stageData       = this.dateOption[id]
                    let parentIndex     = stageData.dateId
                    let index           = stageData.ruleId
                    let calenderData    = JSON.parse(sessionStorage.getItem('calenderData'))

                    calender[parentIndex].rule[index].ticketMsm.sevenEleven.message1  = escape(message1)
                    calender[parentIndex].rule[index].ticketMsm.sevenEleven.message2  = escape(message2)
                    calender[parentIndex].rule[index].ticketMsm.sevenEleven.status    = 'U'
                    timeCourse.allDay = calender
                    timeCourse.saveLocalStock(timeCourse.allDay)
                }
                
            },
            /**
             * ibon 呈現選擇日期的備註訊息
             * @param  event 日期下拉選單
             */
            ibonMsn:function(event){

                if(event.target.value == 'all'){
                    let calender = timeCourse.allDay
                    let title    = this.ibonTitle 
                    let content  = this.ibonContent
                    
                    if(typeof(this.ibonInf) == "undefined"){
                    
                        this.ibonInf.push({
                            cheacked:'',
                            id: '',
                            title: '',
                            content: '',
                            status: '', 
                            logoPath: '',
                        })

                        this.allPaymentData[0].data[0].ibon = this.ibonInf
                    }

                    this.ibonTitle   = this.ibonInf[0].title
                    this.ibonContent = this.ibonInf[0].content

                }else{
                    let id = event.target.value
                    let stageData = this.dateOption[id]
                    let parentIndex = stageData.dateId
                    let index = stageData.ruleId
                    let selectData = this.ibonDate
                    let calender = timeCourse.allDay 

                    if(typeof(calender[parentIndex].rule[index].ticketMsm.ibon) == 'undefined'){
                        let msnConten = {
                            type: '',
                            title: '',
                            msm: '',
                            status: '',
                            layoutId: '',
                        }
                        
                        calender[parentIndex].rule[index].ticketMsm.ibon = msnConten

                        timeCourse.allDay = calender 
                        timeCourse.saveLocalStock(timeCourse.allDay)
                    }

                    let title = calender[parentIndex].rule[index].ticketMsm.ibon.title 
                    let content = calender[parentIndex].rule[index].ticketMsm.ibon.msm 

                    if(!title){
                        title = this.ibonInf[0].title
                    }

                    if(!content){
                        content = this.ibonInf[0].content
                    }

                    this.ibonTitle    = unescape(title)
                    this.ibonContent  = unescape(content)
                }

            },
            /**
             * ibon 信息更新 
             */
            updateIbonData:function(){
                let calender = timeCourse.allDay
                let tilte    = this.ibonTitle
                let content  = this.ibonContent
               
                if(this.ibonDate == 'all'){
                    if(this.ibonInf[0].id){
                        this.ibonInf[0].title   = this.ibonTitle
                        this.ibonInf[0].content = this.ibonContent
                        this.ibonInf[0].status  = 'U'
                    }else{
                        this.ibonInf[0].title   = this.ibonTitle
                        this.ibonInf[0].content = this.ibonContent
                        this.ibonInf[0].status  = 'I'
                    }
                
                    this.allPaymentData[0].data[0].ibon = this.ibonInf
                }else{
                    let id = this.ibonDate
                    let stageData = this.dateOption[id]
                    let parentIndex = stageData.dateId
                    let index = stageData.ruleId
                    let calenderData = JSON.parse(sessionStorage.getItem('calenderData'))

                    calender[parentIndex].rule[index].ticketMsm.ibon.title  = escape(tilte)
                    calender[parentIndex].rule[index].ticketMsm.ibon.msm    = escape(content)
                    calender[parentIndex].rule[index].ticketMsm.ibon.status = 'U'
                    timeCourse.allDay = calender
                    timeCourse.saveLocalStock(timeCourse.allDay)
                }
                
            },
            /**
             * qr pass 信息更新
             * @param  event 日期下拉選單
             */
            qrpassMsn:function(event){

                if(event.target.value == 'all'){
                    let calender = timeCourse.allDay
                    let title    = this.qrpassTitle 
                    let content  = this.qrpassContent
                    
                    if(typeof(this.qrpassInf) == "undefined"){
                    
                        this.qrpassInf.push({
                            cheacked:'',
                            id: '',
                            title: '',
                            content: '',
                            status: '', 
                            logoPath: '',
                        })

                        this.allPaymentData[0].data[0].qrpass = this.qrpassInf
                    }

                    this.qrpassTitle   = this.qrpassInf[0].title
                    this.qrpassContent = this.qrpassInf[0].content

                }else{
                    let id = event.target.value
                    let stageData = this.dateOption[id]
                    let parentIndex = stageData.dateId
                    let index = stageData.ruleId
                    let selectData = this.qrpassDate
                    let calender = timeCourse.allDay 

                    if(typeof(calender[parentIndex].rule[index].ticketMsm.qrpass) == 'undefined'){
                        let msnConten = {
                            type: '',
                            title: '',
                            msm: '',
                            status: '',
                            layoutId: '',
                        }
                        
                        calender[parentIndex].rule[index].ticketMsm.qrpass = msnConten

                        timeCourse.allDay = calender 
                        timeCourse.saveLocalStock(timeCourse.allDay)
                    }

                    let title = calender[parentIndex].rule[index].ticketMsm.qrpass.title 
                    let content = calender[parentIndex].rule[index].ticketMsm.qrpass.msm 

                    if(!title){
                        title = this.qrpassInf[0].title
                    }

                    if(!content){
                        content = this.qrpassInf[0].content
                    }

                    this.qrpassTitle    = unescape(title)
                    this.qrpassContent  = unescape(content)
                }

            },
            /**
             * qr pass 呈現選擇日期的備註訊息
             */
            updateqrpassData:function(){
                let calender = timeCourse.allDay
                let tilte    = this.qrpassTitle
                let content  = this.qrpassContent
               
                if(this.qrpassDate == 'all'){
                    if(this.qrpassInf[0].id){
                        this.qrpassInf[0].title   = this.qrpassTitle
                        this.qrpassInf[0].content = this.qrpassContent
                        this.qrpassInf[0].status  = 'U'
                    }else{
                        this.qrpassInf[0].title   = this.qrpassTitle
                        this.qrpassInf[0].content = this.qrpassContent
                        this.qrpassInf[0].status  = 'I'
                    }
                
                    this.allPaymentData[0].data[0].qrpass = this.qrpassInf
                }else{
                    let id = this.qrpassDate
                    let stageData = this.dateOption[id]
                    let parentIndex = stageData.dateId
                    let index = stageData.ruleId
                    let calenderData = JSON.parse(sessionStorage.getItem('calenderData'))

                    calender[parentIndex].rule[index].ticketMsm.qrpass.title  = escape(tilte)
                    calender[parentIndex].rule[index].ticketMsm.qrpass.msm    = escape(content)
                    calender[parentIndex].rule[index].ticketMsm.qrpass.status = 'U'
                    timeCourse.allDay = calender
                    timeCourse.saveLocalStock(timeCourse.allDay)
                }
                
            },
            stageMsn:function(event){

                if(event.target.value == 'all'){
                    let calender = timeCourse.allDay
                    let content = this.mobapassContent
                    
                    if(typeof(this.mobapassInf) == "undefined"){
                       
                        this.mobapassInf.push({
                            cheacked:'',
                            id: '',
                            content: '',
                            status: '', 
                            logoPath: '',
                        })

                        this.allPaymentData[0].data[0].mobapass = this.mobapassInf
                    }

                    this.mobapassContent = this.mobapassInf[0].content

                    if(typeof(this.dateOption[0]) !== 'undefined'){
                        this.mobapassViewDate = this.dateOption[0].date
                        this.mobapassViewTime = this.dateOption[0].time
                    }

                    this.getTicketViewInf()
                }else{
                    let id = event.target.value
                    let stageData = this.dateOption[id]
                    let index = stageData.dateId
                    let parentIndex= stageData.ruleId
                    let selectData = this.mobapassDate
                    let calender = timeCourse.allDay 

                    if(typeof(calender[parentIndex].rule[index].ticketMsm) == 'undefined'){
                        let msnConten = {
                            type:'',
                            msm:'',
                            status:'',
                            layoutId:'',
                        }
                        
                        calender[parentIndex].rule[index].ticketMsm.phone = msnConten

                        timeCourse.allDay = calender 
                        timeCourse.saveLocalStock(timeCourse.allDay)
                    }

                    let content = calender[parentIndex].rule[index].ticketMsm.phone.msm 

                    if(!content){
                        content = this.mobapassInf[0].content
                    }
                    this.getTicketViewInf(parentIndex, index)
                    this.mobapassContent  = unescape(content)
                    this.mobapassViewDate = stageData.date
                    this.mobapassViewTime = stageData.time
                }

            },
            /**
             * 取得時間區間
             */
            getDateData:function(){
                let calenderData = JSON.parse(sessionStorage.getItem('calenderData'))
                let dateStar = Date.parse(basisSetting.performance_st_dt).valueOf()
                let dateEnd =  Date.parse(basisSetting.performance_end_dt).valueOf()
                let sortData = []
           
                if(typeof(this.mobapassInf) == "undefined"){
                    this.mobapassInf = []

                    this.mobapassInf.push({
                        cheacked:'',
                        id: '',
                        content: '',
                        status: '', 
                        logoPath: '',
                    })

                    this.allPaymentData[0].data[0].mobapass = this.mobapassInf
                   
                    sessionStorage.setItem("ticketViewSetting", JSON.stringify(this.allPaymentData))
                }
                
                if(timeCourse.settingRadio === "normal"){
                    calenderData.forEach(function(data, index){
                        let dateNow = Date.parse(data.date.date).valueOf()
                    
                        if(dateStar <= dateNow && dateNow <= dateEnd){
                            data.date.rule.forEach(function(sData, sIndex){
                                
                                if(!(sData.status == 'D' || sData.status == 'DD')) {
                                    sortData.push({
                                    dateId: index.toString(),
                                    ruleId: sIndex.toString(),
                                    date: sData.date,
                                    time: sData.time,
                                    ticketMsm: sData.ticketMsm,
                                    })
                                }
                            })
                        }
                    })

                    if(typeof(sortData[0]) !== 'undefined'){
                        this.mobapassViewDate = sortData[0].date
                        this.mobapassViewTime = sortData[0].specDate
                    }

                }else{
                    this.mobapassViewDate = basisSetting.infOpenDate
                    this.mobapassViewTime = timeCourse.time
                }

                this.dateOption = sortData
                this.getTicketData()
            },
            /**
             * mobapass 儲存選擇日期的備註訊息
             */
            updateMobapassData:function(){
                let calender = timeCourse.allDay
                let content = this.mobapassContent
               
                if(this.mobapassDate == 'all'){
                    if(this.mobapassInf[0].id){
                        this.mobapassInf[0].content = this.mobapassContent
                        this.mobapassInf[0].status = 'U'
                    }else{
                        this.mobapassInf[0].content = this.mobapassContent
                        this.mobapassInf[0].status = 'I'
                    }
                
                    this.allPaymentData[0].data[0].mobapass = this.mobapassInf
                }else{
                    let id = this.mobapassDate
                    let stageData = this.dateOption[id]
                    let parentIndex = stageData.dateId
                    let index = stageData.ruleId
                    let selectData = this.mobapassDate
                    let calenderData = JSON.parse(sessionStorage.getItem('calenderData'))

                    calender[parentIndex].rule[index].ticketMsm.phone.msm = escape(content)
                    calender[parentIndex].rule[index].ticketMsm.phone.status = 'U'
                    timeCourse.allDay = calender
                    timeCourse.saveLocalStock(timeCourse.allDay)
                }
                
            },
            /**
             * 
             */
            mobapassDateChange:function($event){
                let calender = timeCourse.allDay
                let content = this.mobapassContent
                let selectData = this.mobapassDate
                let all = this.mobapassInf
                let viewInf 
    
                calender.forEach(function(element) {
                    if(element.date == selectData){
                        viewInf = element.layoutmobapassContent
                    }
                });
                
                this.mobapassContent = viewInf

                if(selectData == 'all'){
                    this.mobapassContent = all[0].content
                }
            },
            saveLocalStorage:function(){
                sessionStorage.setItem("ticketViewSetting", JSON.stringify(this.allPaymentData))
            },
            /**
             * 設定 mobapasss 預覽樣式資料
             */
            getTicketData:function(){
                let ticketData = ticketSetting.getTicketSettingData()

                if(typeof(ticketData[0].ticketSetting) !== 'undefined'){
                    if(ticketSetting.typeTicketSetting === 'freeSeat'){
                        if(typeof(ticketData[0].ticketSetting.data.data[0]) !== 'undefined' ){
                            this.ticketName = ticketData[0].ticketSetting.data.data[0].ticketName
                            this.ticketPrice = ticketData[0].ticketSetting.data.data[0].ticketPrice
                        }
                    }
                }
            },
            /**
             * 圖上傳
             * @param  event 上傳圖檔
             * @param  type  資料類型
             */
            imageUpload:function($event, type){
                try {
                    let img = $event.target.files[0]
                    const form = new FormData();
                    form.append('file', img);
                    form.append('location', basisSetting.imageLocation)
                    form.append('type', type)

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    
                    if(type == 'mobapassImage'){
                    var file = document.getElementById('mobapassLogo').files[0];
                    if(file.size > 1048576)
                    {
                        return;
                    }
                    $('#logoImg').show();
                    
                        $.ajax({
                            url: '/eventImage/import',
                            type: 'POST',
                            data: form,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function(data, textStatus, jqXHR)
                            {
                                ticketViewSetting.mobapassLogo = data.url
                                ticketViewSetting.allPaymentData[0].data[0].mobapass[0].logoPath = data.url
                                mbpsPreviewPrev = data.url
                                ticketViewSetting.allPaymentData[0].data[0].mobapass[0].status = 'U'
                            },
                            error: function(jqXHR, textStatus, errorThrown)
                            {
                                console.log('ERRORS: ' + textStatus)
                            }
                        });
                    }else{
                        console.log('type is not defined')
                    }
                }catch (error){
                    console.error(error)
                    $event.stopImmediatePropagation();
                }  
            },     
           
        },
        mounted(){
            <?php if( $eventData["status"] === 'edit' || count($errors) > 0 ): ?>   
                sessionStorage.setItem('ticketViewSetting','<?php echo addslashes($eventData["ticketViewContent"]); ?>')
                let ticketViewSetting = JSON.parse(sessionStorage.getItem('ticketViewSetting'))
                let perfomanceStatus = parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10)

                this.allPaymentData  = ticketViewSetting 
                this.mobapassInf     = ticketViewSetting[0].data[0].mobapass 
                //this.mobapass = ticketViewSetting[0].data[0].mobapass[0].cheacked 
                this.mobapassContent = ticketViewSetting[0].data[0].mobapass[0].content 
                this.mobapassLogo    = ticketViewSetting[0].data[0].mobapass[0].logoPath 
                mbpsPreviewPrev      = this.mobapassLogo
                this.qrpassInf       = ticketViewSetting[0].data[0].qrpass 
                this.qrpassTitle     = ticketViewSetting[0].data[0].qrpass[0].title
                this.qrpassContent   = ticketViewSetting[0].data[0].qrpass[0].content
                this.ibonInf         = ticketViewSetting[0].data[0].ibon 
                this.ibonTitle       = ticketViewSetting[0].data[0].ibon[0].title
                this.ibonContent     = ticketViewSetting[0].data[0].ibon[0].content
              
                if(typeof(this.qrpassInf[0]) == "undefined"){
                    this.qrpassInf.push({
                        cheacked:'',
                        id: '',
                        title:'',
                        content: '',
                        status: '', 
                        logoPath: '',
                    })

                    this.allPaymentData[0].data[0].qrpass = this.qrpassInf
                }
                
                if(typeof(this.ibonInf[0]) == "undefined"){
                    this.ibonInf.push({
                        cheacked:'',
                        id: '',
                        title:'',
                        content: '',
                        status: '', 
                        logoPath: '',
                    })

                    this.allPaymentData[0].data[0].ibon = this.ibonInf
                }
              
                if(typeof(ticketViewSetting[0].data[0].sevenEleven) == "undefined"){
                    this.sevenElevenInf.push({
                        cheacked:'',
                        template:'1',
                        id: '',
                        title: '',
                        titleCustom: false,
                        message1:'',
                        message2:'',
                        status: '', 
                        logoPath: '',
                    })

                    this.sevenElevenTemplate  = 1
                    this.sevenElevenMessage1  = ''
                    this.sevenElevenMessage2  = ''

                    this.allPaymentData[0].data[0].sevenEleven = this.sevenElevenInf
                }else{
                    this.sevenElevenInf         = this.allPaymentData[0].data[0].sevenEleven
                    this.sevenElevenTitle       = this.allPaymentData[0].data[0].sevenEleven[0].title
                    this.sevenElevenTemplate    = this.allPaymentData[0].data[0].sevenEleven[0].template
                    this.sevenElevenMessage1    = unescape(this.allPaymentData[0].data[0].sevenEleven[0].message1)
                    this.sevenElevenMessage2    = unescape(this.allPaymentData[0].data[0].sevenEleven[0].message2)
                    
                    if(typeof(this.sevenElevenInf[0].titleCustom) == "undefined"){
                        this.sevenElevenInf[0].titleCustom = false
                    }
                }
                this.getTicketData()
               
            <?php else: ?>
                let dataContent = []
                let perfomanceStatus = -1

                //mobapass 資料結構
                this.mobapassInf.push({
                    cheacked:'',
                    id: '',
                    content: '',
                    status: '', 
                    logoPath: '',
                })

                //qrpass 資料結構
                this.qrpassInf.push({
                    cheacked:'',
                    id: '',
                    title:'',
                    content: '',
                    status: '', 
                    logoPath: '',
                })

                //ibon 資料結構
                this.ibonInf.push({
                    cheacked:'',
                    id: '',
                    title:'',
                    content: '',
                    status: '', 
                    logoPath: '',
                })

                //日本 7-11 資料結構
                this.sevenElevenInf.push({
                    cheacked:'',
                    template:'1',
                    id: '',
                    title: '',
                    titleCustom: false,
                    message1:'',
                    message2:'',
                    status: '', 
                    logoPath: '',
                })

                dataContent.push({
                    mobapass    : this.mobapassInf,
                    qrpass      : this.qrpassInf,
                    ibon        : this.ibonInf,
                    sevenEleven : this.sevenElevenInf,
                })

                this.allPaymentData.push({
                    data: dataContent,
                })
            <?php endif; ?>
          
            this.statucControl.push({
                basis: [7, 8].includes(perfomanceStatus),
                thumbnail: [7, 8].includes(perfomanceStatus),
                performance_date: [7, 8].includes(perfomanceStatus), 
                free_word: [7, 8].includes(perfomanceStatus), 
            })

            this.getDateData()
            this.getTicketViewInf()
            this.$nextTick(() => {
                $("#mobapassLogo").change(function(){
                    var reader = new FileReader();
                
                    reader.onload = function(e) {
                        var file = document.getElementById('mobapassLogo').files[0];
                        if(file.size > 1048576)
                        {
                          //$('#logoImg').hide();
                          var mbpsImg = document.getElementById('logoImg');
                          mbpsImg.setAttribute('src', mbpsPreviewPrev);
                        }else{
                          $('#logoImg').attr('src', e.target.result);
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                });

            })
        },
    });
</script>
<?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/event/editLayout/other.blade.php ENDPATH**/ ?>