<div id="timeCourse">
    <!-- //time-course-wrap 時間回數專用class -->
    <div class="time-course-wrap">
        <div class="form-horizontal">
            <!--  //統一樣式 + radiobox + 錯誤提示-->
            <!--錯誤提示-->
            <div class="callout callout-info">
                <h4><?php echo e(trans('events.S_stageMainTitle')); ?></h4>
                <?php echo trans('events.S_stageMainDesc'); ?>

            </div>
            <!--/.錯誤提示-->
            <div class="time-course-content">
                <!-- Block 1 -->
                <!-- Form -->
                <div class="form-horizontal">
                    <div class="">
                        <!--//col-md-12-->
                        <div class="col-md-12">
                            <!-- //radiobox 1-->
                            <div class="form-group form-group-flex mb-0">
                                <div class="form-checkbox">
                                    <label class="control control--radio">
                                        <input id="spec-radio" type="radio" name="settingSelect" value="spec" v-model="settingRadio" :disabled="statucControl[0].sch_kbn">
                                        <?php echo e(trans('events.S_SpecificDateSelect')); ?>

                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <!--/.radiobox 1-->
                            <!-- //Form + form-feedback -->
                            <div class="form-group" v-show="(settingRadio == 'spec') ? true : false">
                                <label class="col-md-2 control-label"><?php echo e(trans('events.S_evensShowDate')); ?><b><?php echo e(trans('events.S_RequiredMark')); ?></b></label>
                                <div class="col-md-10 has-feedback form-feedback">
                                    <input name="spec-title" type="text" maxlength="30" v-model="specTitle" v-validate="'sej_format:8'"  v-bind:style="{ borderColor: (checkResult['specTitle'].status)?'#e44e2d':'' }" v-on:blur="specTitleChange()" :disabled="statucControl[0].basis" class="form-control" placeholder="<?php echo e(trans('events.S_evensShowDatePlaceholder')); ?>">
                                    <span v-show="checkResult['specTitle'].status|errors.has('spec-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ checkResult['specTitle'].msn }} {{ errors.first('spec-title') }}</span>
                                    <!-- <span class="glyphicon glyphicon-remove-sign form-control-feedback"></span>-->
                                    <!-- STS 2021/07/26 task 38 -->
                                    <span class="help is-danger" v-show="specTitle && specTitle != specTitle.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                </div>
                            </div>
                            <!-- /.Form + form-feedback -->
                            <!-- //Form + timepicker-->
                            <div class="form-group" v-show="(settingRadio == 'spec') ? true : false">
                                <label class="col-md-2 control-label"><?php echo e(trans('events.S_stageDailyTime')); ?></label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input type="text" id="specTimepicker" name='specTime' class="form-control timepicker" :disabled="statucControl[0].performance_date" v-validate="'required'">
                                        <!--<input id="specTime" dusk="event-spec-date" type="text" name='specTime' class="form-control time-picker" v-model="specDate" :disabled="statucControl[0].performance_date" v-validate="'required'">-->
                                        <div class="input-group-addon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                                <span v-show="errors.has('specTime')" class="help is-danger col-md-offset-2"><i class="fas fa-exclamation-circle"></i> {{ errors.first('specTime') }}</span>
                            </div>
                            <!-- /.Form + timepicker-->
                            <!-- //Form + form-feedback-->
                            <!-- removed by ph3 GL#1461
                            <div class="form-group" v-show="(settingRadio == 'spec') ? true : false">
                                <label class="col-md-2 control-label"><?php echo e(trans('events.S_stageDailyTimeDisp')); ?></label>
                                <div class="col-md-9 has-feedback form-feedback">
                                    <input dusk="event-spec-even-title" maxlength="20" type="text" class="form-control" v-model="specEvenTitle" v-on:blur="specEvenTitleChange()" placeholder="<?php echo e(trans('events.S_stageShowTimeDispPlaceholder')); ?>">
                                </div>
                                <div class="col-md-1 form-checkbox">
                                    <label class="control control--checkbox mtb-1 pull-right">
                                        <input type="checkbox" v-model="specEvenStatus" v-on:change="specEvenStatusChange()"> <?php echo e(trans('events.S_display')); ?>

                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            -->
                        </div>
                    <!-- /.Form + form-feedback-->
                    </div>
                <!--/.col-md-12-->
                </div>
            </div>
            <!-- /.Form -->
        </div>
        <!--  /.統一樣式 + radiobox  -->
        <!--  //box1 統一樣式 + radiobox -->
        <!-- Block 1 -->
        <!-- Form -->
        <div class="form-horizontal">
            <!--//col-md-12-->
            <div class="col-md-12">
                <!-- //radiobox 1-->
                <div class="form-group form-group-flex mb-0">
                    <div class="form-checkbox">
                        <label class="control control--radio">
                            <input id="nom-radio" type="radio" dusk="normal-radio" name="settingSelect" value="normal" v-model="settingRadio" :disabled="statucControl[0].sch_kbn"> 
                            <?php echo e(trans('events.S_BasisDateSetting')); ?>

                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <!--/.radiobox 1-->
                <!-- //box  -->
                <div class="box no-border ml-15x" v-show="(settingRadio == 'normal') ? true : false ">
                    <div class="box-header with-border-non"  data-widget="collapse">
                        <h3 class="box-title"><?php echo e(trans('events.S_stageNameSettingTitle')); ?></h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool"><i
                                class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <template v-for="(rule, index) in ruleInf">
                            <div class="form-group flex-around" v-if="!rule.del">
                                <label class="col-md-2 control-label"><?php echo e(trans('events.S_stage')); ?> - {{ rule.id }}</label>
                                <div class="col-md-9 has-feedback form-feedback">
                                    <input name="text" maxlength="20" type="text" class="form-control" @change="ruleStatus(index)" :disabled="statucControl[0].basis" v-model="rule.title">
                                    <!-- STS 2021/07/26 task 38 -->
                                    <span class="help is-danger" v-show="rule.title && rule.title != rule.title.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                </div>

                                <!-- 1203 欄位調整 -->
                                <div class="col-md-1">
                                    <!--1225隱藏-->
                                    <!--<div class="plr-15"><input type="checkbox" v-bind:id="`display_${rule.id}`" class="i-display" :disabled="rule.del" v-model="rule.status"><label  title="表示" v-bind:for="`display_${rule.id}`"></label></div>-->
                                    <div class="plr-15" v-show="ruleSale(rule.id)"><input type="checkbox" v-bind:id="`delete_${rule.id}`" class="i-delete"  @click="reconfirm(index, rule.id)" :checked="rule.del"><label v-bind:for="`delete_${rule.id}`"></label></div>
                                </div>
                                <!-- /.1203 欄位調整 -->
                                <!-- <div class="col-md-1 form-checkbox">
                                    <label class="control control--checkbox mtb-1 pull-right">
                                        <input type="checkbox" v-model="rule.status"> <?php echo e(trans('events.S_display')); ?>

                                        <div class="control__indicator"></div>
                                    </label>
                                </div>-->
                            </div>
                        </template>
                        <div class="col-md-12">
                            <button name="dateSubTitle" class="btn btn-block waves-effect waves-light btn-rounded btn-inverse" @click="ruleRowAdd" :disabled="statucControl[0].dateSubTitle">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- //box  -->
            </div>
            <!--/.col-md-12-->
        </div>
        <!-- /.Form -->
        <!--  /.box1 統一樣式 + radiobox  -->
        <!--  //box2 統一樣式 + radiobox -->
        <!-- Block 1 -->
        <!-- Form -->
        <div class="form-horizontal" v-show="(settingRadio == 'normal') ? true : false ">
            <!--//col-md-12-->
            <div class="col-md-12">
                <!-- box  -->
                <div class="box no-border ml-15x" v-show="!statucControl[0].timeSetting">
                    <div class="box-header with-border-non">
                    </div>
                    <div class="box-body">
                        <!-- //radiobox 1-->
                        <div class="form-group pl-15">
                            <div class="col-sm-12" v-show='!statucControl[0].weekSetting'>
                                <div class="">
                                    <div class="form-checkbox pl-15">
                                        <label class="control control--radio col-md-2">
                                            <input id="date-set-radio-nom" type="radio" name="date" value="week" v-model="dateSelect"/>
                                            <?php echo e(trans('events.S_BasisWeekSetting')); ?>

                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <!---->
                                    <div class="form-group-flex">
                                    <template v-if="(dateSelect == 'week') ? true : false ">
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                                <input dusk="week-mon" type="checkbox" value="" v-model="mon"> <?php echo e(trans('events.S_stageWeek_Mon')); ?>

                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                            <input dusk="week-tue" type="checkbox" value="" v-model="tue"> <?php echo e(trans('events.S_stageWeek_Tue')); ?>

                                            <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                            <input dusk="week-wed" type="checkbox" value="" v-model="wed"> <?php echo e(trans('events.S_stageWeek_Wed')); ?>

                                            <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                            <input dusk="week-thu" type="checkbox" value="" v-model="thu"> <?php echo e(trans('events.S_stageWeek_Thu')); ?>

                                            <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                            <input dusk="week-fri" type="checkbox" value="" v-model="fri"> <?php echo e(trans('events.S_stageWeek_Fri')); ?>

                                            <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                            <input dusk="week-sat" type="checkbox" value="" v-model="sat"> <?php echo e(trans('events.S_stageWeek_San')); ?>

                                            <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
                                        <div class="form-checkbox form-checkbox-fix">
                                            <label class="control control--checkbox">
                                                <input id="date-set-week-sun" dusk="week-sun" type="checkbox" value="" v-model="sun"> <?php echo e(trans('events.S_stageWeek_Sun')); ?>

                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <!---->
    
                                    </template>
                                    </div>
                                </div>
                            </div>
                            <!---->
                            <div class="col-sm-12">
                                <div class="form-checkbox pl-15">
                                    <label class="control control--radio col-md-2" v-show="(basisSetting.performance_st_dt) ? true : false">
                                        <input id="date-set-radio-spec" type="radio" name="date" value="specDay" v-model="dateSelect"/>
                                        <?php echo e(trans('events.S_SpecificDate')); ?>

                                        <div class="control__indicator"></div>
                                    </label>
                                    <div class="col-md-10" v-show="(dateSelect == 'specDay' &&  basisSetting.performance_st_dt) ? true : false">
                                        <div class="input-group">
                                            <input id="nomSetDateSpec" type="text" class="form-control pull-right daterangeSingle" readonly>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--/.radiobox 1-->
                        <!-- //Form + form-feedback-->
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="col-md-4 control-label"><?php echo e(trans('events.S_stage')); ?>

                                </label>
                                <div class="col-md-8 pl-20">
                                    <select id="ruleNum" class="form-control" @change="ruleNoChange()" v-model="ruleNo">
                                        <option  value="-1" disabled><?php echo e(trans('events.S_select')); ?></option>
                                        <option  v-for="item in ruleInf" v-bind:value="item.id" v-show="!item.del">{{ item.id }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="">
                                    <label class="col-md-4 control-label"><?php echo e(trans('events.S_stageShowTime')); ?></label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input id="ruleTimePicker" type="text" class="form-control input-small timepicker" :disabled="statucControl[0].performance_date" :readonly="(settingRadio == 'spec') ? true : false">
                                            <div class="input-group-addon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.Form + form-feedback-->
                        <!-- //Form + form-feedback-->
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="col-md-2 control-label"><?php echo e(trans('events.S_evensShowDate')); ?></label>
                                <div class="col-md-10 has-feedback form-feedback">
                                    <input name="even-date-tittle" type="text" maxlength="30" id="even-date-tittle" class="form-control" placeholder="" v-validate="'sej_format:8'" v-model='dateTitle'>
                                    <span v-show="errors.has('even-date-tittle')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('even-date-tittle') }}</span>
                                    <!-- STS 2021/07/26 task 38 -->
                                    <span class="help is-danger" v-show="dateTitle && dateTitle != dateTitle.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- /.Form + form-feedback-->
                        <!-- <div class="col-md-12" @click="ruleAdd" @mouseover="disabledBtn">
                            <button  id="date-import-btn" type="button" class="btn btn-block waves-effect waves-light btn-rounded btn-inverse" :disabled="cheackData || ruleNo < 0 || errors.has('even-date-tittle')">
                                <?php echo e(trans('events.S_DateSettingAddButton')); ?>

                                <i class="fas fa-angle-double-down"></i>
                            </button>
                        </div> -->
                        <!-- STS 2021/07/27 task 38 -- START -->
                        <div class="col-md-12" @click="ruleAdd" @mouseover="disabledBtn">
                            <button  id="date-import-btn" type="button" class="btn btn-block waves-effect waves-light btn-rounded btn-inverse" :disabled="(dateTitle && dateTitle != dateTitle.replace(/-+/g,'-')) || cheackData || ruleNo < 0 || errors.has('even-date-tittle')">
                                <?php echo e(trans('events.S_DateSettingAddButton')); ?>

                                <i class="fas fa-angle-double-down"></i>
                            </button>
                        </div>
                        <!-- STS 2021/07/27 task 38 -- END -->
                   </div>
                    
                    <!-- //box  -->
                </div>
                <!--/.col-md-12-->   
            <!-- table 統一樣式 ＋ table-ticket 券種表單使用-->                   
                <div class="box no-border ml-15x">
                        <div class="box-body">
                            <div class="table-responsive" v-show="(settingRadio == 'spec') ? false : true ">
                                <table class="table table-striped table-boxrows">
                                    <thead>
                                        <tr>
                                            <th width="20%"> <?php echo e(trans('events.S_stageDateTitle')); ?></th>
                                            <th width="">
                                                <div class="col-sm-4"><?php echo e(trans('events.S_stageTimeTitle')); ?></div>
                                                <div class="col-sm-8"><div class="flex-end"><div class="status-dot bg-blue-light"></div><small>押えあり／予約あり</small></div></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr is="time-list" 
                                            v-for="(day, key, index) in allDay"
                                            starDate="DateRangeStar"
                                            v-bind:endDate="DateRangeEnd"
                                            v-bind:day="day">
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- /.table 統一樣式 ＋ table-ticket 券種表單使用-->
            <!-- /.Form -->
            <!--  /.box1 統一樣式 + radiobox  -->
        </div>
    </div>
    <!-- /.time-course-wrap 時間回數專用class -->
    <!-- modal -->
    <transition name="slide-fade">
        <div v-show='showModal' class="modal-mask" style="display: none">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(trans('events.S_stageDateTitle')); ?> ： {{ ModalData.date}} </h4>
                    </div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                        <label class="col-md-4 control-label"><?php echo e(trans('events.S_stage')); ?></label>
                                    <div class="col-md-8">
                                        <select id="calRuleNum" class="form-control"  v-model="ruleNo" :disabled="statucControl[0].basis">
                                            <template v-for="num in ruleCanSel">
                                                <option bind:value="num">{{ num }}</option>
                                            </template>  
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?php echo e(trans('events.S_stageShowTime')); ?></label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" id="speRuleTimePicker" class="form-control input-small timepicker" :disabled="statucControl[0].basis">
                                            <div class="input-group-addon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><?php echo e(trans('events.S_stageShowTimeDisp')); ?></label>
                                    <div class="col-md-10">
                                        <input name="even-date-title" type="text" maxlength="20" v-validate="'sej_format:8'" class="form-control input-small" v-model='evenDateTitle' :disabled="statucControl[0].basis">
                                        <span v-show="errors.has('even-date-title')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('even-date-title') }}</span>
                                        <!-- STS 2021/07/26 task 38 -->
                                        <span class="help is-danger" v-show="evenDateTitle && evenDateTitle != evenDateTitle.replace(/-+/g,'-') "><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" @mouseover="disabledInsBtn">
                    <button duck="calendarBackBtn" class="btn btn-default pull-left" @click="modalClose">
                        <?php echo e(trans('events.S_cancelBtn')); ?>

                    </button>
                    <?php if($eventData['performanceDispStatus'] >= 0 && $eventData['performanceDispStatus'] < 7): ?>
                       <!-- STS 2021/07/27 task 38 --START -->
                       <!--  <button class="btn btn-danger" @click="delSpeEven" v-if="(!statucControl[0].scheduleDel && !scheduleIsSale) || ModalData.status == 'I'" v-show="(ModalStatic == 'change') ? true : false ">
                            <?php echo e(trans('events.S_deleteBtn')); ?>

                        </button>
                        <button class="btn btn-inverse" @click="changeSpeEven" v-show="(ModalStatic == 'change') ? true : false " :disabled="errors.has('even-date-title')">
                            <?php echo e(trans('events.S_updateBtn')); ?>

                        </button>
                        <button dusk="calendarAddBtn" class="btn btn-inverse" :disabled="cheackInsData || errors.has('even-date-title')" @click="calAddSpeEven" v-show="(ModalStatic == 'add') ? true : false "> 
                            <?php echo e(trans('events.S_addBtn')); ?>

                        </button>
                        <button class="btn btn-normal" @click="recoverySpeEven" v-show="(ModalStatic == 'recovery') ? true : false ">
                            <?php echo e(trans('events.S_recoveryBtn')); ?>

                        </button> -->

                         <button class="btn btn-danger" @click="delSpeEven" v-if="(!statucControl[0].scheduleDel && !scheduleIsSale) || ModalData.status == 'I'" v-show="(ModalStatic == 'change') ? true : false " :disabled="evenDateTitle != '' && evenDateTitle != evenDateTitle.replace(/-+/g,'-')">
                            <?php echo e(trans('events.S_deleteBtn')); ?>

                        </button>
                        <button class="btn btn-inverse" @click="changeSpeEven" v-show="(ModalStatic == 'change') ? true : false " :disabled="errors.has('even-date-title') || evenDateTitle != ''  && evenDateTitle != evenDateTitle.replace(/-+/g,'-')">
                            <?php echo e(trans('events.S_updateBtn')); ?>

                        </button>
                        <button dusk="calendarAddBtn" class="btn btn-inverse" :disabled="cheackInsData || errors.has('even-date-title') || evenDateTitle != ''  && evenDateTitle != evenDateTitle.replace(/-+/g,'-')" @click="calAddSpeEven" v-show="(ModalStatic == 'add') ? true : false "> 
                            <?php echo e(trans('events.S_addBtn')); ?>

                        </button>
                        <button class="btn btn-normal" @click="recoverySpeEven" v-show="(ModalStatic == 'recovery') ? true : false " :disabled="evenDateTitle != ''  && evenDateTitle != evenDateTitle.replace(/-+/g,'-')">
                            <?php echo e(trans('events.S_recoveryBtn')); ?>

                        </button>
                        <!-- STS 2021/07/27 task 38 --END -->
                    <?php endif; ?>
                </div>
            </div>
            </div>
        </div>
    </transition>
    <!-- /.modal -->
    <!--需要設定通用版-->
    <div class="modal-mask" v-show="reconfirmDialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-white border-non"></div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <center>
                            <i class="fas fa-exclamation-triangle text-red fa-2x"></i>
                            <h4><?php echo e(trans('events.S_deleteSession')); ?></h4>
                        </center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default pull-left"  @click="reconfirmResult(false)"><?php echo e(trans('events.S_cancelBtn')); ?></button>
                    <button class="btn btn-danger" id="PasswordSend"  @click="reconfirmResult(true)"><?php echo e(trans('events.S_deleteBtn')); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- time rule list-->
<template id="ruleItem">
    <div class="form-group">
        <label class="col-md-2 control-label"><?php echo e(trans('events.S_stage')); ?> - {{ rule.id }}</label>
        <div class="col-md-9 has-feedback form-feedback">
            <input name="text" type="text" maxlength="20" class="form-control" v-model="title" v-on:blur="insertTitle()" >
            <!-- <span class="glyphicon glyphicon-remove-sign form-control-feedback"></span>-->
        </div>
        <div class="col-md-1 form-checkbox">
            <label class="control control--checkbox mtb-1 pull-right">
                <input type="checkbox" v-model="status" @click="statusChange()"> <?php echo e(trans('events.S_display')); ?>

                <div class="control__indicator"></div>
            </label>
        </div>
    </div>
    <!-- /.Form + form-feedback-->
</template>

<!-- time rule list-->
<template id="timeList">
    <tr v-show="day.hadEvens">
        <td :class="classSetting(day.day)">
            {{ day.date }}<br>
            （{{ weekend }}）
        </td>
        <td>
            <div class="card-box-rows">
                <time-item  
                    v-for="item in day.rule"
                    v-bind:item="item"
                    v-bind:title="day.date">
                </time-item>
                <?php if($eventData['performanceDispStatus'] >= 0 && $eventData['performanceDispStatus'] < 7): ?>
                    <div class="card-box">
                        <button id="calendarAddBtn1" class="card card-wite card-small card-more-xs stats-xs" @click="addSpeEven(day.date)">
                            <div class="card-body">
                                <div class="flex-column">
                                <i class="fas fa-plus"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</template>

<!-- time rule item list-->
<template id="timeItem" v-if="item !== 0">
    <!--
    <div  style="float: left;">
        <div class="row rule-item" style="margin-right: 30px;">
            <label class="form-control">{{ item.id }}</label>
            <label class="form-control" @click="setting(item)">{{ item.time }}</label>
        </div>
    </div>
    -->
    <div class="card-box" v-show="(item.status !== 'DD' && item.status !== 'D') ? true : false">
        <button class="card card-wite card-xs stats-xs" @click="setting(item)">
            <div class="card-body" v-if="(item.status !== 'D') ? true : false">
                <div class="box-column">
                    <div class="card-item" v-bind:class="{'bg-blue-light': isSale(item)}">{{ item.id }}</div>
                    <div class="card-content">
                        <div class="card-content-time">{{ item.time }}</div>
                        <div class="card-content-sub">{{ item.title }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="opacity: .4;">
                <div class="box-column">
                    <div class="card-item">{{ item.id }}</div> 
                    <div class="card-content">
                        <div class="card-content-time">{{ item.time }}</div> 
                        <div class="card-content-sub" style="color: #fe6882;"><?php echo e(trans('events.S_delete')); ?></div>
                    </div>
                </div>
            </div>
        </button>
    </div>
</template>

<script>

<?php if(!empty($eventData["timeTitle"])): ?>
sessionStorage.setItem('ruleData','<?php echo addslashes($eventData["timeTitle"]); ?>')
<?php endif; ?>

<?php if(!empty($eventData["calenderData"])): ?>
sessionStorage.setItem('calenderData','<?php echo addslashes($eventData["calenderData"]); ?>')
<?php endif; ?>


Vue.config.devtools = true;
var nowDate = new Date();
var starDate = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0)
var endDate = new Date(nowDate.getFullYear(), nowDate.getMonth()+1, nowDate.getDate(), 0, 0, 0, 0)

Vue.component('time-list', {
  data: function () {
    return {
      title: this.day.dateTitle,
      date: this.day.date,
      weekend : this.day.weekend
    }
  },
  template:'#timeList',
  props:['key','day','showModaltet'],
  methods: {
    classSetting:function(weekend){
        let dayClass = ''
    
        switch(weekend){
            case 'mon':
                dayClass = "";
                break;
            case 'tue':
                dayClass = "";
                break;
            case 'wed':
                dayClass = "";
                break;
            case 'thu':
                dayClass = "";
            case 'fri':
                dayClass = "";
                break;
            case 'sat':
                dayClass = "text-blue font-500";
                break;
            case 'sun':
                dayClass = "text-red font-500";
                break;
        }
        
        return dayClass
    },
    addSpeEven:function (date) {
        timeCourse.modalShowAdd(date)
    },
    inputClaer:function(value){
        this.title = ''

        let calenderData = sessionStorage.getItem("calenderData")
        calenderData = JSON.parse(calenderData)
        let dateTitle = this.day.date
        let title = this.title
        let now = 0
       
        calenderData.forEach(function(date) {
            let ruleDateNow = new Date(value)
            let calDate = new Date(calenderData[now].date.date)

            if(ruleDateNow.getTime() == calDate.getTime()){
                calenderData[now].date.dateTitle = title
            }
            now++
        });
        sessionStorage.setItem('calenderData', JSON.stringify(calenderData));

    },
    titleChange:function (value){

        let calenderData = sessionStorage.getItem("calenderData")
        calenderData = JSON.parse(calenderData)
        let dateTitle = this.day.date
        let title = this.title
        let now = 0
       
        calenderData.forEach(function(date) {
            let ruleDateNow = new Date(value)
            let calDate = new Date(calenderData[now].date.date)

            if(ruleDateNow.getTime() == calDate.getTime()){
                calenderData[now].date.dateTitle = title
            }
            now++
        });
        sessionStorage.setItem('calenderData', JSON.stringify(calenderData));
    }
  }
})

Vue.component('time-item', {
  template:'#timeItem',
  props: ['item'],
    data: function () {
    return {
      title: this.day.dateTitle,
      date: this.day.date,
      weekend : this.day.weekend
    }
  },
  methods: {
    setting:function (item) {
        let isSale = this.isSale(item)
        timeCourse.modalShow(item, isSale)
    },
    isSale:function(item){
        let result = false 
        let dbId = item.dbId

        if(SaleInfo.has_sale){
            result = SaleInfo.schedule_info.some(function(item, index, array){
                return item.schedule_id == dbId && item.stage_sale            
            });
        }
        return result
    }
  }
})

var timeCourse = new Vue({
  el: '#timeCourse',
  data: {
    specTitle:'',
    specDate:'12:00',
    specEvenTitle:'',
    specEvenStatus:'',
    specDateId:'',
    cheackData: true,
    cheackInsData: true,
    settingRadio: '',
    showModal: false,
    ModalStatic:'',
    ModalData:[],
    MinDateRangeStar:starDate,
    MaxDateRangeEnd:endDate,
    DateRangeStar:starDate ,
    DateRangeEnd:endDate ,
    allDay: [],
    dateSelect: 'week',//week specDay #the radio select static
    SpecificDate:'',
    mon: false,
    tue: false,
    wed: false,
    thu: false,
    fri: false,
    sat: false,
    sun: false,
    starDate: '12:00',
    dateTitle: '',
    evenDateTitle: '',
    ruleNo: 1,
    ruleTotal: 1,
    ruleCanSel:[],
    ruleInf: [],
    ruleList: [],
    statucControl :[],
    dateError: false,
    checkResult:[],
    scheduleIsSale: false,
    reconfirmDialog: false, 
    ruleReconfirmInf: [],
    addButtonErrorStatus: false, //STS 2021/07/26 task 38
    saleType: basisSetting.saleType, //STS 2021/07/26 task 38
  },
  watch: {
    settingRadio: function(value)
    {
        this.checkhyphens()
        this.checkHyphenDate()
        let data = [] 
        data.push({type:value})
        
        sessionStorage.setItem('timeDataSel', JSON.stringify(data));

        if(
            basisSetting.locationName && 
            ticketSetting.typeTicketSetting !== 'freeSeat' &&
            this.settingRadio == "normal"
        ){
            tagControl.seatSettingTag = true
        }else{
            tagControl.seatSettingTag = false
        }

        if(!this.specDate){
            this.specDate = "12:00"
        } 

        if(value == "spec"){
            closeTimepicker()
            ticketSetting.typeTicketSetting = "freeSeat"
            this.specDateChange()
            ticketSetting.ticketOnSiteStatus()
        }else{
            ticketSetting.ticketOnSiteShow = true
        }
    },
    allDay:{
        handler(){
            this.saveLocalStock(this.allDay)
        },
        deep: true
    },
    ruleInf:{
        handler(){
            this.checkhyphens() // STS 2021/07/24 Task 38
            sessionStorage.setItem('ruleData', JSON.stringify(this.ruleInf));
        },
        deep: true
    },
    starDate: function(){
        this.disabledBtn()
    },
    //STS 2021/07/26 task 38 -- START
    evenDateTitle: function() {
         this.checkHyphenDate()
    },
     saleType: function() {
        this.checkhyphens()
        this.checkHyphenDate()
    },
    //STS 2021/07/26 task 38 --END
    dateTitle: function(){
        this.disabledBtn()
        this.checkHyphenDate() //STS 2021/07/26 task 38
    },
    mon: function(){
        this.disabledBtn()
    },
    tue: function(){
        this.disabledBtn()
    },
    wed: function(){
        this.disabledBtn()
    },
    thu: function(){
        this.disabledBtn()
    },
    fri: function(){
        this.disabledBtn()
    },
    sat: function(){
        this.disabledBtn()
    },
    sun: function(){
        this.disabledBtn()
    },
    specTitle:function(){
        this.specTitleRule()
        this.checkHyphenDate() //STS 2021/07/26 task 38
    },
    errors:{
        handler(){
            this.settingCheack()
        },
        deep: true
    },
  },
  methods: {
    //STS 2021/07/26 task 38 START
    checkHyphenDate: function(){
           if(((this.settingRadio === 'spec' && this.specTitle && this.specTitle != this.specTitle.replace(/-+/g,'-')) || (this.settingRadio === 'normal' && this.dateTitle && this.dateTitle != this.dateTitle.replace(/-+/g,'-')) || (this.evenDateTitle && this.evenDateTitle != this.evenDateTitle.replace(/-+/g,'-'))) &&this.saleType == 1 ) {
            this.addButtonErrorStatus = true;
                errorMsnCheack.addBtnCheack();
            } else if(((this.settingRadio === 'spec' && this.specTitle && this.specTitle != this.specTitle.replace(/-+/g,'-')) || (this.settingRadio === 'normal' && this.dateTitle && this.dateTitle != this.dateTitle.replace(/-+/g,'-')) || (this.evenDateTitle && this.evenDateTitle != this.evenDateTitle.replace(/-+/g,'-'))) && this.saleType != 1 ) {
                this.addButtonErrorStatus = false;
                errorMsnCheack.addBtnCheack();
            }  else {
                this.addButtonErrorStatus = false;
                errorMsnCheack.addBtnCheack(); 
          }
    },
    checkhyphens: function() {
        for(i=0; i < this.ruleInf.length; i++ ) {
          if(this.settingRadio === 'normal' && (this.ruleInf[i].title && this.ruleInf[i].title != this.ruleInf[i].title.replace(/-+/g,'-')) && this.saleType == 1) {
                this.addButtonErrorStatus = true;
                errorMsnCheack.addBtnCheack();
                break;
          } else if(this.settingRadio === 'normal' && (this.ruleInf[i].title && this.ruleInf[i].title != this.ruleInf[i].title.replace(/-+/g,'-')) && this.saleType != 1) {
                this.addButtonErrorStatus = false;
                errorMsnCheack.addBtnCheack();
          }  else {
            this.addButtonErrorStatus = false;
                errorMsnCheack.addBtnCheack(); 
          }
        }
    },
    //STS 2021/07/26 task 38 END
    setStageRange:function(){
        let firstStageDate, lastStageDate
        let result = true

        let hasStageDate = this.allDay.filter(function(item, index, array){
            return item.hadEvens;
        });

        if(hasStageDate.length > 0){
            firstStageDate = hasStageDate[0].date
            lastStageDate = hasStageDate[hasStageDate.length-1].date
            if(Date.parse(basisSetting.maxDate) > Date.parse(firstStageDate)){
                basisSetting.maxDate = firstStageDate
                renewPerformanceStarDate()
            }
           
            if(Date.parse(basisSetting.minDate) < Date.parse(lastStageDate)){
                basisSetting.minDate = lastStageDate
                renewPerformanceEndDate()
            }

        }else{
            result = false
        }

        return result
    },
    ruleSale:function(stage_index){
        let result = false 

        if(SaleInfo.has_sale){
            result = SaleInfo.schedule_info.some(function(item, index, array){
                return item.stage_index == stage_index && item.stage_sale            
            });
        }
       
        if(this.statucControl[0].scheduleDel){
            result = true 
        }
       
        return !result
    },
    ruleStatus: function(index){
        let rule = this.ruleInf[index]

        if(rule.title.length > 0){
            rule.status = true
        }else{
            rule.status = false
        }
        
        this.ruleInf[index] = rule
    },
    reconfirm:function(index, id){
        this.reconfirmDialog = true
        this.ruleReconfirmInf = {
            index :index,
            id : id
        }
    },
    reconfirmResult(select){
        if(select){
            this.ruleDel()
        }else{
            this.ruleReconfirmInf = []
        }
        this.reconfirmDialog = false
        //STS 2021/07/27 task 38 --START
        for(i=0; i < this.ruleInf.length; i++) {
            if(this.ruleInf[i].del === true) {
                this.ruleInf[i].status = false
            }
        }
        //STS 2021/07/27 task 38 --END
    },
    /**
     * 場次刪除
     * @param  index - 陣列 index
     * @param  id - 場次 id
     */
    ruleDel: function(){
        let status = 'U'
        this.ruleNo = -1
        let index =  this.ruleReconfirmInf.index
        let id =  this.ruleReconfirmInf.id
        
        if(this.ruleInf[index].del){
            this.ruleInf[index].del = false
        }else{
            this.ruleInf[index].del = true
            status = 'D'
        }
       

        this.allDay.forEach(function(date) {
            date.rule.forEach(function(session) {
                if(session.id == id){
                    session.status = status
                }
            })
        })

        //強制更新 element
        this.$forceUpdate()
        if([5, 6, 8, 7].includes(parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10))){
            this.setStageRange()
        }
    },
    specTitleRule: function(val = this.specTitle){
        try {
            this.checkResult['specTitle']['status'] = false
            this.checkResult['specTitle']['msn'] = ''
            
            if(val.length == 0){
                throw (new Error('表示公演日時を入力してください。'))
            }

            return true
        }catch (e){
            this.checkResult['specTitle']['status'] = true
            this.checkResult['specTitle']['msn']  = e.message
            
            return false
        }
    },
    /**
     * 取得錯誤信息
     * @return  {bool} 
     */
    getError:function(){
        if(
            this.errors.has('specTime') 
        ){
            return true;
        }else{
            return false;
        }
    },
    settingCheack:function (item) {
        let hasError = this.getError();

        if(hasError || this.dateError){
            tagControl.timeCourseWarning = true
        }else{
            tagControl.timeCourseWarning = false
        }
        errorMsnCheack.updataBtnCheack()
    },
    dateReset:function(){
        $.getScript("<?php echo e(asset('js/daterangepicker.js')); ?>", function(){
            $('#nomSetDateSpec').daterangepicker({
                "locale": {
                    "format": "YYYY/MM/DD"
                },
                "singleDatePicker": true,
                "startDate": basisSetting.performance_st_dt,
                "endDate": basisSetting.performance_end_dt,
                "minDate": basisSetting.performance_st_dt,
                "maxDate": basisSetting.performance_end_dt,
            })

            $('#nomSetDateSpec').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'))
                timeCourse.nomSetDateSpec = picker.startDate.format('YYYY/MM/DD')
            });
        });
    },
    getTimeSettingData:function(){
        let timeData = []
        let calenderDate = [] 
        let status = []
        let getSpecData = sessionStorage.getItem('specDate')
        let getRuluList = sessionStorage.getItem('ruleData')
        let getCalenderData = sessionStorage.getItem('calenderData')
        let ruleList = ""
        let specDate = ""
        let calenderData = ""

        if (getRuluList !== "") {
            ruleList = JSON.parse(getRuluList)
        }
       
        if (getSpecData !== "") {
            specDate = JSON.parse(getSpecData)
        }
        
        if (getCalenderData !== ""){
            calenderData = JSON.parse(getCalenderData)
        }
        
        for (let data of calenderData) {
            //if(data.date.hadEvens){
                calenderDate.push(data)
          //  }
        }

        status.push({
            status: this.settingRadio,
            minDate: this.MinDateRangeStar,
            maxDate: this.MaxDateRangeEnd,
        })

        timeData.push({
           status: status,
           ruleList: ruleList,
           specDate: specDate,
           calenderDate: calenderDate,
        })

        return timeData
    },
    evenDateTitleClear:function(){
        this.evenDateTitle = ''
    },
    specTitleChange:function(){
        let specDataStor = sessionStorage.getItem("specDate")
        specDataStor = JSON.parse(specDataStor)
      
        specDataStor[0].specTitle = this.specTitle
        
        sessionStorage.setItem('specDate', JSON.stringify(specDataStor));
    },
    specDateChange:function(event){
        let specDataStor = sessionStorage.getItem("specDate")
        if (specDataStor !== null && specDataStor !== undefined && specDataStor !== "") {
            specDataStor = JSON.parse(specDataStor)
            specDataStor[0].specDate = this.specDate
        }
        else {
            specDataStor = "";
        }
        sessionStorage.setItem('specDate', JSON.stringify(specDataStor));
    },
    specEvenTitleChange:function(){
        let specDataStor = sessionStorage.getItem("specDate")
        specDataStor = JSON.parse(specDataStor)
      
        specDataStor[0].specEvenTitle = this.specEvenTitle
        
        sessionStorage.setItem('specDate', JSON.stringify(specDataStor));
    },
    specEvenStatusChange:function(){
        let specDataStor = sessionStorage.getItem("specDate")
        specDataStor = JSON.parse(specDataStor)
        specDataStor[0].specEvenStatus = this.specEvenStatus
        
        sessionStorage.setItem('specDate', JSON.stringify(specDataStor));
    },
    disabledInsBtn: function(){
  
        let starDate = false
        let dateSpec = false
        let dateVal= $('#calRuleNum').val()
 
        if(this.starDate){
            starDate = true
        }
        
        if(dateVal){
            dateSpec = true
        }

        if(dateSpec && starDate){
            this.cheackInsData = false
        }else{
            this.cheackInsData = true
        }
        
    },
    disabledBtn: function(){
        
        let weekSet = false
        let starDate = false
        let dateSpec = false
        let dateVal= $('#nomSetDateSpec').val()

        if( this.mon||this.tue||this.wed||this.thu|| this.fri||this.sat||this.sun){
                weekSet = true
        }

        if(this.starDate){
            starDate = true
        }
       
        if(dateVal){
            dateSpec = true
        }

        if( this.dateSelect == 'week' ){
            if(weekSet && starDate){
                this.cheackData = false
            }else{
                this.cheackData = true
            }
        }
        
        if( this.dateSelect == 'specDay' ){
            if(dateSpec && starDate){
                this.cheackData = false
            }else{
                this.cheackData = true
            }
        }
    },
    modalShow: function(item, isSale = false){
       closeTimepicker()

        if(item.status == 'D'){
            this.ModalStatic = 'recovery'
        }else{
            this.ModalStatic = 'change'
        }
 
       this.ModalData = []
       this.scheduleIsSale = isSale
       this.showModal = true
       this.ModalData = item
       this.ruleNo = item.id
       this.starDate = item.time
       this.evenDateTitle = item.title
       document.body.style.overflowY = "hidden";
       setTimeInf('#speRuleTimePicker', item.time)
       let sel = []
       sel.push(item.id)

       this.ruleCanSel = sel
     
    },
    modalShowAdd: function(date){
        closeTimepicker()
        
        this.starDate = "12:00"
        document.body.style.overflowY = "hidden";

        let item = []
        let num = 0
        
        item.push({date:date})

        this.ModalStatic = 'add'
        this.ModalData = []
        this.showModal = true
        this.ModalData = item[0]
        this.evenDateTitle = ''
        $('#nomSetDateSpec').val(item[0].date.toString())

        let allDay = this.allDay
        let addDay = Date.parse(item[0].date).valueOf()
        let ruleHad = []
        let ruleTotal =  this.ruleTotal
        let ruleCanSel = []
   
        this.allDay.forEach(function(date) {
            let dateTime = Date.parse(date.date).valueOf()
       
            if(dateTime == addDay){
                for(let n=0; n<allDay[num].rule.length; n++){
                    if(allDay[num].rule[n].status !== 'D'){
                        ruleHad.push(allDay[num].rule[n].id.toString())
                    }else{
                        ruleHad.push(-1)
                    }
                }
                
            }
            num++
        })
       
        for(let n = 1; n <= ruleTotal; n++){
            let num = n-1
            if(ruleHad.indexOf(n.toString()) == -1 && !this.ruleInf[num].del){
                ruleCanSel.push(n)   
            }
            
        }
        this.ruleCanSel = ruleCanSel
    },
    modalClose: function(){
        this.showModal =  false
        this.ModalStatic = ""
        document.body.style.overflowY = "scroll"
    },
    calAddSpeEven: function(){
         this.checkhyphens() //STS 2021/07/27 task 38

        let ruleNow = this.ruleNo -1
        let ruleData = this.ruleList[ruleNow]

        ruleData.mon = this.mon
        ruleData.tue = this.tue 
        ruleData.wed = this.wed 
        ruleData.thu = this.thu 
        ruleData.fri = this.fri 
        ruleData.sat = this.sat 
        ruleData.sun = this.sun 
        ruleData.starDate = this.starDate 
        ruleData.showDate = this.showDate 


        this.addSpecDay()
        this.modalClose()
        this.saveLocalStock(this.allDay)
    },
    changeSpeEven: function(){
        this.checkhyphens() //STS 2021/07/27 task 38
        let changeDate = Date.parse(this.ModalData.date).valueOf()
        let allDay = this.allDay
        let num = 0
        let nowId = this.ModalData.id
        let starDate = this.starDate
        let static =  this.ModalStatic
        let addSelectNo = this.ruleNo
        let dateTitle = this.evenDateTitle
        let add = true
        this.allDay.forEach(function(date) {
            let dateTime = Date.parse(date.date).valueOf()
       
            if(dateTime == changeDate){
                for(let n=0; n<allDay[num].rule.length; n++){
                   if(allDay[num].rule[n].id == nowId ){
                        allDay[num].rule[n].title = dateTitle
                        allDay[num].rule[n].time = starDate
                        if(allDay[num].rule[n].dbId)
                            allDay[num].rule[n].status = 'U'
                        else
                            allDay[num].rule[n].status = 'I'
                   }
                }
            }
            num++
        })
    
        this.allDay = allDay
        this.modalClose()
        this.saveLocalStock(this.allDay)
    },
    recoverySpeEven: function(){
        this.checkhyphens() //STS 2021/07/27 task 38
        let changeDate = Date.parse(this.ModalData.date).valueOf()
        let allDay = this.allDay
        let num = 0
        let nowId = this.ModalData.id
        let starDate = this.starDate
        let static =  this.ModalStatic
        let addSelectNo = this.ruleNo
        let dateTitle = this.evenDateTitle
        let add = true
        this.ruleInf[nowId-1].del = false

        this.allDay.forEach(function(date) {
            let dateTime = Date.parse(date.date).valueOf()
       
            if(dateTime == changeDate){
                for(let n=0; n<allDay[num].rule.length; n++){
                   if(allDay[num].rule[n].id == nowId ){
                        allDay[num].rule[n].title = dateTitle
                        allDay[num].rule[n].time = starDate
                        allDay[num].rule[n].status = 'U'
                   }
                }
            }
            num++
        })
    
        this.allDay = allDay
        this.modalClose()
        this.saveLocalStock(this.allDay)
    },
    delSpeEven: function(){
        let changeDate = Date.parse(this.ModalData.date).valueOf()
        let allDay = this.allDay
        let num = 0
        let nowId = this.ModalData.id
        let title = this.starDate

        this.allDay.forEach(function(date) {
            let dateTime = Date.parse(date.date).valueOf()
       
            if(dateTime == changeDate){
                for(let n=0; n<allDay[num].rule.length; n++){
                   if(allDay[num].rule[n].id == nowId ){
                        allDay[num].rule[n].status = 'D'
                   }
                }
            }
            num++
        })
        this.checkStagesStatus(nowId)
        this.allDay = allDay
        this.modalClose()
        this.saveLocalStock(this.allDay)
        this.ruleNo = -1
        if([5, 6, 8, 7].includes(parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10))){
            this.setStageRange()
        }
    },
    /**
     * 檢查特定 rule 的所有活動是否被刪除
     * @param  {int} id
     * @return  {bool} result
     */
    checkStagesStatus:function(id){
        let result = true
        for (let date of this.allDay) {
            if(date.hadEvens){
                let stageRuleStage = date.rule.some(function(item, index, array){
                    if(item.status != 'D' && item.id == id){
                        if(item.status != 'DD' && item.id == id){
                            return true
                        }else{
                            return false
                        }
                    }else{
                        return false
                    }
                });

                if(stageRuleStage){
                    result = false
                    break
                }
            }
        }
        if(result){
            this.ruleInf[id-1].del = true
        }
    
        return result
    },
    ruleNoChange: function(){

        let ruleNow = this.ruleNo - 1
        let ruleData = this.ruleList[ruleNow]

        this.dateTitle = ''

        if(ruleData.status){
            this.mon = ruleData.mon
            this.tue = ruleData.tue
            this.wed = ruleData.wed
            this.thu = ruleData.thu
            this.fri = ruleData.fri
            this.sat = ruleData.sat
            this.sun = ruleData.sun
        }
    },
    saveLocalStock: function(allDate){
        let stockData = []
       
        allDate.forEach(function(date) {
            stockData.push({
                date
            })
        });
        
        this.allDay = allDate
        this.saveRule()
        sessionStorage.setItem('calenderData', JSON.stringify(stockData));
    },
    saveRule: function(){

        if( this.ruleInf.length < this.ruleNo ){

            this.ruleInf.push({
                id: this.ruleNo,
                title: '',
                status: false,
                del: false,
            })
        }
        sessionStorage.setItem('ruleData', JSON.stringify(this.ruleInf));
    },
    ruleAdd: function(){
        let ruleNow = this.ruleNo - 1
        let dateTitle = this.dateTitle
        let starDate = this.starDate

        this.ruleList[ruleNow].status = true
        this.ruleList[ruleNow].mon = this.mon
        this.ruleList[ruleNow].tue = this.tue 
        this.ruleList[ruleNow].wed = this.wed 
        this.ruleList[ruleNow].thu = this.thu 
        this.ruleList[ruleNow].fri = this.fri 
        this.ruleList[ruleNow].sat = this.sat 
        this.ruleList[ruleNow].sun = this.sun 
        this.ruleList[ruleNow].starDate = this.starDate 
        this.ruleList[ruleNow].dateTitle = this.dateTitle 

        this.allDate()
        if([5, 6, 8, 7].includes(parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10))){
            this.setStageRange()
        }
    },
    ruleRowAdd: function(){
        let ruleData = sessionStorage.getItem("ruleData")
        this.ruleData = JSON.parse(ruleData)

        this.ruleList.push({
            id: this.ruleTotal + 1, 
            show: true,
            text: '',
            status: false,
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            sat: false,
            sun: false,
            starDate: '12:00',
            dateTitle: ''
        })

        this.ruleInf.push({
            id: this.ruleInf.length + 1,
            title: '',
            status: false,
            del: false,
        })

        sessionStorage.setItem('ruleData', JSON.stringify(this.ruleInf));
        this.saveLocalStock(this.allDay)
        this.ruleTotal += 1
        
    },
    ruleAddToDay: function(){

        /* 
        call by functino: allDate
        */
       
        let allDate = this.allDay  
        let ruleNo = this.ruleNo-1
        let ruleList = this.ruleList
        let ruleSet = ruleList[ruleNo]
        let evenTitle =  this.dateTitle
        let dateTitle = this.dateTitle
        let startDate = new Date(this.DateRangeStar)
        let endDate = new Date(this.DateRangeEnd)
        var now = 0
        
        this.allDay.forEach(function(date) {

           
            let ruleDate = new Date(date.date) 
           
            if(startDate.getTime() <= ruleDate.getTime() && ruleDate.getTime() <= endDate.getTime()){

                let static = true
                let num = 0
                allDate[now].dateTitle = evenTitle

                if(typeof allDate[now].rule !== 'undefined'){
                    

                    allDate[now].rule.forEach(function(rule){
                        if(rule.id == ruleNo+1){
                            if(eval(' ruleSet.'+date.day))
                            {
                                //add and update
                                allDate[now].rule[num].time = ruleSet.starDate
                                allDate[now].rule[num].title = dateTitle

                                if(allDate[now].rule[num].dbId){
                                    allDate[now].rule[num].status = 'U' 
                                }
                            
                                static = false
                            }
                            else {
                                //delete
                                if(allDate[now].rule[num].dbId){
                                    allDate[now].rule[num].status = 'D' 
                                }
                                else {
                                    allDate[now].rule.splice(num, 1);
                                }

                            }
                        }
                        num++
                    });

                    if(allDate[now].rule.length != 0) {
                        allDate[now].hadEvens = true;
                    }
                    else {
                        allDate[now].hadEvens = false;
                    }
                    
                }
                                
                if(static && eval(' ruleSet.'+date.day) ){
                    allDate[now].hadEvens = true
                    let msnConten = {
                        phone:{
                            status:'',
                            type:'',
                            msm:'',
                        },
                        qrpass:{
                            status:'',
                            type:'',
                            title:'',
                            msm:'',
                        },
                        ibon:{
                            status:'',
                            type:'',
                            title:'',
                            msm:'',
                        },
                        sevenEleven:{
                            cheacked:'',
                            template:'',
                            id: '',
                            title: '',
                            message1:'',
                            message2:'',
                            status: '', 
                            logoPath: '',
                        }
                    }
                    
                    allDate[now].rule.push({
                        id: ruleNo+1,
                        time: ruleSet.starDate,
                        title: dateTitle,
                        date: date.date,
                        status: 'I',
                        dbId: '',
                        layoutCashId: '',
                        ticketMsm:msnConten,
                    })
                }

                //rule sort 
                if(allDate[now].rule.length > 1){
                    for(let m = 0; m < allDate[now].rule.length-1; m++){
                        if(allDate[now].rule[m].id > allDate[now].rule[m+1].id){
                            let change = allDate[now].rule[m+1]
                            allDate[now].rule[m+1] = allDate[now].rule[m]
                            allDate[now].rule[m] = change
                        }
                    }
                }
            }
            now++
        });

        this.saveLocalStock(allDate)
    },
    addSpecDay: function(){

        let day = $('#nomSetDateSpec').val()
        let changeDate = Date.parse(day).valueOf()
      
        let allDate = this.allDay  
        let ruleNo = this.ruleNo-1
        let ruleList = this.ruleList
        let ruleSet = ruleList[ruleNo]
        var now = 0
        let dateTitle = ''

        if(this.ModalStatic == 'add'){
            dateTitle = this.evenDateTitle
        }else{
            dateTitle = this.dateTitle
        }
       
        this.allDay.forEach(function(date) {
            let ruleDateNow = new Date(day)
            let calDate = new Date(date.date)
      
            if(ruleDateNow.getTime() == calDate.getTime()){
                allDate[now].hadEvens = true
                let static = true
                let num = 0

                if(allDate[now].rule){
                    allDate[now].rule.forEach(function(rule){
                        if(rule.id == ruleNo+1){
                            allDate[now].rule[num].time = ruleSet.starDate
                            allDate[now].rule[num].title = dateTitle
                            if(rule.dbId)
                                allDate[now].rule[num].status = 'U'
                            else
                                allDate[now].rule[num].status = 'I'
                            static = false
                        }
                        num++
                    });
                }

                if(static){
                    let msnConten = {
                        phone:{
                            status:'',
                            type:'',
                            title:'',
                            msm:'',
                        },
                        qrpass:{
                            status:'',
                            type:'',
                            title:'',
                            msm:'',
                        },
                        ibon:{
                            status:'',
                            type:'',
                            title:'',
                            msm:'',
                        },
                        sevenEleven:{
                            cheacked:'',
                            template:'',
                            id: '',
                            title: '',
                            message1:'',
                            message2:'',
                            status: '', 
                            logoPath: '',
                        }
                    }

                    allDate[now].rule.push({
                        id: ruleNo+1,
                        time: ruleSet.starDate,
                        title: dateTitle,
                        date: date.date,
                        status: 'I',
                        dbId: '',
                        layoutCashId: '',
                        ticketMsm:msnConten,
                    })
                }
                
                //rule sort 
                if(allDate[now].rule.length > 1){
                    for(let m = 0; m < allDate[now].rule.length-1; m++){
                        if(allDate[now].rule[m].id > allDate[now].rule[m+1].id){
                            let change = allDate[now].rule[m+1]
                            allDate[now].rule[m+1] = allDate[now].rule[m]
                            allDate[now].rule[m] = change
                        }
                    }
                }

            }
            
            now++
        });
        
        this.saveLocalStock(allDate)
        
    },
    allDate: function(){

        let startDate = new Date(this.DateRangeStar)
        let endDate = new Date(this.DateRangeEnd)
        
        if( this.dateSelect == 'specDay' ){
            this.addSpecDay()
        }

        if( this.dateSelect == 'week' ){
             this.ruleAddToDay()
        }
    },
    changeDateRange: function(){
        /*
            隱藏之外的日期
        */
        this.DateRangeStar = basisSetting.performance_st_dt
        this.DateRangeEnd = basisSetting.performance_end_dt
   
       
        let minStartDate = new Date(this.MinDateRangeStar)
        let maxEndDate = new Date(this.MaxDateRangeEnd)
        let startDate = new Date(this.DateRangeStar)
        let endDate = new Date(this.DateRangeEnd)
        let dateData = this.allDay

        this.allDay = []
        
        if( startDate < minStartDate ){
            this.MinDateRangeStar = this.DateRangeStar
            minStartDate = startDate
        }

        if( endDate > maxEndDate ){
            this.MaxDateRangeEnd = this.DateRangeEnd
            maxEndDate = endDate
        }

        for (
            var d = new Date(minStartDate.getTime());
            d.getTime() <= maxEndDate.getTime();
            d.setDate(d.getDate()+1)
        ) {
            let date = d.toLocaleString('zh-TW',{hour12:false}).split(" ");
            let day_list = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            let day = day_list[d.getDay()]
            let lacale = 'zh-TW';
            if ("<?php echo e(\App::getLocale()); ?>" == 'ja')
            {
              lacale = 'ja-JP-u-ca-japanese';
            }
            let weekend = d.toLocaleString(lacale,{weekday:'long'}); 

            this.allDay.push({
                date: date[0],
                dateValue: Date.parse(d).valueOf(),
                dateTitle: this.dateTitle,
                day: day,
                weekend: weekend,
                hadEvens:false,
                layoutContent: '',
                layoutCasContent: '',
                layoutId: '',
                layoutStatus: '',
                rule:[],
            })
        
        }
    
        let calenderData = sessionStorage.getItem("calenderData")
        calenderData = JSON.parse(calenderData)
      
        this.allDay.forEach(function(date) {
            for(let n = 0; n < calenderData.length; n++){
               if(  date.date  == calenderData[n].date.date ){
                    date.dateTitle = calenderData[n].date.dateTitle
                    date.rule = calenderData[n].date.rule
                    
                    let dateNow = new Date(date.date)
                    let ruleHad = calenderData[n].date.rule.length
                   
                    if( startDate.getTime() <= dateNow.getTime() && dateNow.getTime() <= endDate.getTime() ){
                        
                        if( ruleHad == 0 ){
                            date.hadEvens = false
                        }else{
                            date.hadEvens = true
                            date.rule.forEach(function(stage) {
                                if( stage.dbId ) {
                                    if (stage.status == 'D')
                                        stage.status = "U";
                                }
                                else{
                                    stage.status = "I";
                                }
                            });
                        }
                    }else{
                        date.hadEvens = false
                        if(ruleHad > 0) {
                            date.rule.forEach(function(stage) {
                                if( stage.status )
                                    stage.status = "D";
                            });
                        }
                    }
               }
            }
        });
        
        let all_data = this.allDay

        if(dateData.length > 0){
            dateData.forEach(function(date) {
                all_data.forEach(function(data) {
                    if(date.date == data.date){
                        date = data
                    }
                });
            });
        }
       
        this.saveLocalStock(this.allDay)
    },
    ruleDataInit(){
    /*    this.ruleList.push({
            id: this.ruleTotal, 
            show: true,
            text: '',
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            san: false,
            sun: false,
            starDate: '',
            dateTitle: ''
        })
       */
        this.allDay = []
        let startDate = new Date(this.DateRangeStar)
        let endDate = new Date(this.DateRangeEnd)
       
        for (
            var d = new Date(startDate.getTime());
            d.getTime() <= endDate.getTime();
            d.setDate(d.getDate()+1)
        ) {
            let lacale = 'zh-TW';
            if ("<?php echo e(\App::getLocale()); ?>" == 'ja')
            {
              lacale = 'ja-JP-u-ca-japanese';
            }
            let date = d.toLocaleString('zh-TW',{hour12:false}).split(" ");
            let day_list = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            let day = day_list[d.getDay()]
            let weekend = d.toLocaleString(lacale,{weekday:'long'});

            this.allDay.push({
                date: date[0],
                dateValue: Date.parse(d).valueOf(),
                dateTitle: this.dateTitle,
                layoutContent: '',
                layoutCashContent: '',
                layoutStatus: '',
                day: day,
                weekend : weekend,
                hadEvens:false,
                rule:[],
            })
        }
       
        if(sessionStorage.getItem("calenderData")){

            let calenderData = sessionStorage.getItem("calenderData")
            calenderData = JSON.parse(calenderData)
           
            this.allDay.forEach(function(date) {
                for(let n = 0; n < calenderData.length; n++){
                    
                    if(  date.date  == calenderData[n].date.date ){
                       
                            date.dateTitle = calenderData[n].date.dateTitle
                            date.layoutContent = calenderData[n].date.layoutContent
                            date.hadEvens = calenderData[n].date.hadEvens
                            date.rule = calenderData[n].date.rule
                    }
                }
            });
            
            let ruleData = sessionStorage.getItem("ruleData")
            ruleData = JSON.parse(ruleData)
            for(let n = 0; n < ruleData.length; n++){
                this.ruleInf.push({
                    id: ruleData[n].id,
                    title: ruleData[n].title,
                    status: ruleData[n].status,
                    del: (typeof ruleData[n].del == 'undefined')?false:ruleData[n].del
                })
            }
           
            this.ruleTotal = ruleData.length

            for(let n = 0; n<this.ruleTotal; n++){
                this.ruleList.push({
                    id: n, 
                    show: true,
                    text: '',
                    status:false,
                    mon: false,
                    tue: false,
                    wed: false,
                    thu: false,
                    fri: false,
                    sat: false,
                    sun: false,
                    starDate: '',
                    dateTitle: ''
                })
            }
        }
    }
  },
  mounted(){
    <?php if($eventData["status"] === 'edit' || count($errors) > 0): ?>
        this.settingRadio = '<?php echo e(isset($eventData["timeStatus"]["0"]["status"])?$eventData["timeStatus"]["0"]["status"]:"spec"); ?>'
        this.MinDateRangeStar = '<?php echo e(isset($eventData["timeStatus"]["0"]["minDate"])?$eventData["timeStatus"]["0"]["minDate"]:""); ?>'
        this.MaxDateRangeEnd = '<?php echo e(isset($eventData["timeStatus"]["0"]["maxDate"])?$eventData["timeStatus"]["0"]["maxDate"]:""); ?>'
        let perfomanceStatus = parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10)
         
        <?php if($eventData["specDate"] !== "null"): ?>
            sessionStorage.setItem('specDate','<?php echo addslashes($eventData["specDate"]); ?>')
            let specDate = JSON.parse(sessionStorage.getItem('specDate'))[0]
        
            this.specTitle = specDate.specTitle || ''
            this.specEvenStatus = specDate.specEvenStatus
            this.specEvenTitle = specDate.specEvenTitle || null
            this.specDateId = specDate.specDateId || null
            this.specDate = specDate.specDate || null
            this.DateRangeStar = basisSetting.performance_st_dt
            this.DateRangeEnd = basisSetting.performance_end_dt 

            if(!this.specDate){
                this.specDate = "12:00"
            }
        <?php else: ?>
            let specDate = []

            specDate.push({
                specTitle: '',
                specDate: '12:00',
                specEvenStatus: true,
                specEvenTitle: '',
                specDateId: '',
            })

            sessionStorage.setItem('specDate', JSON.stringify(specDate));
        <?php endif; ?>

            if( this.ruleList === undefined || this.ruleList.length == 0 ){
                this.ruleDataInit()
            }else{
                let dateSetting
                dateSetting.push({type:value})
                sessionStorage.setItem('timeDataSel', JSON.stringify(dateSetting));
            }
            //this.dateReset()
    <?php else: ?>
        let specDate = []
        let perfomanceStatus = -1

        specDate.push({
            specTitle: '',
            specDate: '12:00',
            specEvenStatus: true,
            specEvenTitle: '',
            specDateId: '',
        })
        this.ruleList.push({
            id: 1, 
            show: true,
            text: '',
            status:false,
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            sat: false,
            sun: false,
            starDate: '12:00',
            dateTitle: ''
        })

        this.ruleInf.push({
            id: 1,
            title: '',
            status: false,
            del: false,
        })

        this.settingRadio = 'spec'
        sessionStorage.setItem('specDate', JSON.stringify(specDate));
        this.ruleDataInit()
    <?php endif; ?>

    this.checkResult = {
        specTitle: { 
                status:false,
                msn:''
            },
    }
    this.specTitleRule()
    this.statucControl.push({
       basis: [7, 8].includes(perfomanceStatus),
       sch_kbn: [4, 5, 6, 7, 8].includes(perfomanceStatus),
       performance_date: [7, 8].includes(perfomanceStatus), 
       dateSubTitle: [7, 8].includes(perfomanceStatus),
       scheduleDel: [4, 5, 6, 7, 8].includes(perfomanceStatus),
       timeSetting: [7, 8].includes(perfomanceStatus),
       weekSetting: [4, 5, 6, 7, 8].includes(perfomanceStatus),
    })
    if(!SaleInfo.has_sale){
        //this.statucControl[0].scheduleDel = false
    }
    if(this.statucControl[0].weekSetting){
        this.dateSelect = 'specDay'
    }
  }
})

</script>
<?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/event/editLayout/timeCourse.blade.php ENDPATH**/ ?>