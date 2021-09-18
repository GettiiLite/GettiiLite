<div id="ticketSetting">
    <!-- //ticket-setting-wrap 席種券種專用class -->
    <div class="ticket-setting-wrap">
    <!--1-->
    <div class="form-horizontal">
        <!-- //提示訊息 -->
        <div class="callout callout-info lh-2">
            <?php echo trans('events.S_ticketPrompt'); ?>

        </div>
        <!--/.提示訊息 -->
        <!-- //radiobox 1-->
        
        <div class="">
            <div class="form-group form-group-flex mb-1">
                <div class="form-checkbox col-md-4 pt-6x wkeep wnowrap">
                    <label class="control control--radio">
                        <input type="radio" name="ticketType" value="freeSeat" v-on:click="typeChange('freeSeat')" v-model="typeTicketSetting" :disabled="statucControl[0].seat_class_kbn"><?php echo e(trans('events.S_FreeSeatTitle')); ?>

                        <small>[ ※ 券面に通し番号無 ]</small>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <!--移到下方-->
            </div>
        </div>
        
        <!--/.radiobox 1-->
        <!-- //Box1 + table 統一樣式 -->
        <div class="" v-show="(typeTicketSetting == 'freeSeat') ? true : false">
            <div class="ml-3">
            <div class="form-horizontal">
            <div class="form-checkbox-groupbox-wt p-20 mb-5">
            <div class="form-group-flex">
                <div class="txt-flex-mr"><?php echo e(trans('events.S_freeSeatQtyTitle')); ?></div>
                    <div class="col-md-2">
                        <span v-show="checkResult['freeSeatQty']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ checkResult['freeSeatQty']['msn'] }}</span>
                        <input type="" name="free-seat-qty" class="form-control" v-model="freeSeatQty" :disabled="statucControl[0].stock_limit">
                    </div>
                    <div v-show="!ticketOnSiteShow" class="txt-flex-mr"> <?php echo e(trans('events.S_FreeSeatInf')); ?></div>
            </div>
            </div>
            </div>
            <!-- STS 2021/07/27 Task 38)-->
            <div v-if="(typeTicketSetting == 'freeSeat') ? true : false"  v-show="freeticketErrorStatus | errorMsg.trim().length > 0 | sejErrorStattus">
                <div class="callout callout-txt-warning ">
                    <div class="icon">
                        <i class="fas fa-exclamation-circle fa-lg"></i> 
                    </div>
                     <p>{{ errorMsg }}</p>
                </div>
            </div>    
                <div class="box box-solid">
                    <!-- box-header -->
                    <div class="box-header box-header-forms-gray">
                        <div class="col box-title-item"></div>
                        <!--col-->
                        <div class="col-xs-12 ptb-8x form-group-flex">
                            <div class="col-xs-4 pl-x has-feedback">
                                <!--
                                <input type="text" class="form-control input-sm" v-validate="'required'" v-model="freeSeatTicketName" name="free-Ticket-Name" type="text" @blur="freeSeatNameChange()" :disabled="statucControl[0].seat_class_name" placeholder="<?php echo e(trans('events.S_seatTypePlaceholder')); ?>">
                                <span class="glyphicon glyphicon-remove-sign form-control-feedback" @click="freeSeatNameClear()"></span>
                                -->
                                <input type="text" class="form-control input-sm" maxlength="25" v-bind:style="{ borderColor: (freeticketErrorStatus)?'#e44e2d':'' }"  v-model="freeSeatTicketName" type="text" @change="cheackFreeSeatData()" @blur="freeSeatNameChange()" :disabled="statucControl[0].seat_class_name" placeholder="<?php echo e(trans('events.S_seatTypePlaceholder')); ?>">
                            </div>
                            <div class="col-xs-1 has-feedback"></div>
                            <div class="col-xs-2 form-checkbox wkeep"></div>
                            <div class="col-xs-2 bottons"></div>
                        </div>
                        <!-- /.col -->
                    </div>
                    
                    <!-- /.box-header -->
                    <!-- //box-body -->
                    <div class="box-body table-responsive pt-x pl-70">
                        <!-- //table 統一樣式 -->
                        <table id="freeTicket" class="table table-striped table-ticket">
                            <thead>
                                <tr>
                                    <th width="40px"></th>
                                    <th width="30%"><?php echo e(trans('events.S_TicketNameTitle')); ?></th>
                                    <th width="20%"><?php echo e(trans('events.S_TicketPriceTitle')); ?></th>
                                    <th><?php echo e(trans('events.S_TicketEarlyBirdTitle')); ?> </th>
                                    <th><?php echo e(trans('events.S_TicketNormalTitle')); ?></th>
                                    <th  v-show="ticketOnSiteShow"><?php echo e(trans('events.S_TicketOnSiteTitle')); ?></th>
                                    <th id="insertFreeSeat" class="text-right">
                                        <button class="btn btn-inverse btn-m" v-on:click="insertFreeSeat()" v-show="!statucControl[0].seat_class_name"><?php echo e(trans('events.S_TicketTypeInsertBtn')); ?></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template id="ticketTypeRow" v-for="(item, index) in ticket_items(freeSeatData)">         
                                    <tr v-if="(item.ticketStatus !== 'D')">
                                        <td>
                                            <div class="item-cicle">{{ item.oid }}</div>
                                        </td>
                                        <td>
                                            <!--  STS 2021/06/01  -->
                                            <!-- <div v-bind:class="{ 'select-error-tip': item.ticketNameErrorStatus }">  -->
                                                <select :id="'nomSelect-'+index" :value="item.ticketName" :data-index="index"  onchange="ticketSetting.nomSeatSelectChange(this)" class="form-control select2 select-create"  style="width: 100%;" :disabled="statucControl[0].ticket_class_name">
                                                    
                                                        <!--<option value="" class="not-select" disabled><?php echo e(trans('events.S_ticketTypePlaceholder')); ?></option>-->
                                                    <option value="" class="not-select" selected="true" disabled="disabled"><?php echo e(trans('events.S_ticketTypePlaceholder')); ?></option>
                                                    <template  v-for="inf in selectOption">
                                                        <option v-if="inf.id !== 'nomSelect-'+index" :value="inf.value">{{ inf.value }}</option>
                                                    </template>
                                                </select> 
                                                <span v-show="item.ticketNameErrorStatus" class="help is-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                            <!-- </div> -->
                                        </td>
                                        <td>
                                          <!--    <input name="text" type="number" min="0" class="form-control text-right free-ticket-price" v-bind:style="{ borderColor: (item.ticketErrorStatus)?'#e44e2d':'' }" :value="item.ticketPrice" @blur="inputPrice(index, $event)" :disabled="statucControl[0].price" placeholder="<?php echo e(trans('events.S_ticketPricePlaceholder')); ?>"> -->
                                            <!-- STS 2021/06/12: 小切手 4 つの特殊文字 (+)、(-)、(.)、(,) -->
                                            <input @paste="validateNumber" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="text" type="number" min="0" class="form-control text-right free-ticket-price" v-bind:style="{ borderColor: (item.ticketErrorStatus)?'#e44e2d':'' }" :value="item.ticketPrice" @blur="inputPrice(index, $event)" :disabled="statucControl[0].price" placeholder="<?php echo e(trans('events.S_ticketPricePlaceholder')); ?>">
                                        </td>
                                        <td>
                                            <label class="control control--checkbox">
                                                <input class="ticketEarlyBird" type="checkbox" value="" v-on:click="ticketEarlyBird(index, $event)" :checked="item.ticketEarlyBird" :disabled="statucControl[0].ticket_sales_kbn"> 
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="control control--checkbox">
                                                <input class="ticketNormal" type="checkbox" value="" v-on:click="ticketNormal(index, $event)" :checked="item.ticketNormal" :disabled="statucControl[0].ticket_sales_kbn"> 
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td v-show="ticketOnSiteShow">
                                            <label class="control control--checkbox">
                                                <input class="ticketOnSite" type="checkbox" value="" v-on:click="ticketOnSite(index, $event)" :checked="item.ticketOnSite" :disabled="statucControl[0].ticket_sales_kbn"> 
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-danger btn-m" v-on:click="removeRow(index)" v-if="freeTicketDelBtn(item)">
                                                <?php echo e(trans('events.S_TicketTypeDeleteBtn')); ?>

                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <!-- /.table 統一樣式 -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.Box1 + table 統一樣式 -->
        </div>
        <!--/1-->
        <!-- //radiobox 2-->
        <div class="form-horizontal">
            <div id="selectSeat" class="" v-show="(timeCourse.settingRadio !== 'spec') ? true : false">
                <div class="form-group form-group-flex mb-1">
                    <!-- radiobox -->
                    <div class="form-checkbox col-md-12 pt-6X">
                        <label class="control control--radio">
                            <input type="radio" name="ticketType" v-on:click="typeChange('selectSeat')" v-model="typeTicketSetting" value="selectSeat" :disabled="statucControl[0].seat_class_kbn"><?php echo e(trans('events.S_TicketSeatSelectTitle')); ?>

                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <!-- /.radiobox -->
                </div>
                
                <div class="ml-3" v-show="(typeTicketSetting == 'selectSeat') ? true : false">
                    <div class="form-checkbox-groupbox-wt">
                        <div class="form-checkbox">
                            <label class="control control--checkbox">
                                <input type="checkbox" v-model="selection" :disabled="statucControl[0].base_kbn"><?php echo e(trans('events.S_TicketSeatSelfSelectionTitle')); ?>

                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <p class="pl-30 text-gray"> 
                            <?php echo e(trans('events.S_TicketSeatSelfSelectionDesc')); ?>

                        </p>
                    </div>
                </div>
                
            </div>
            <!-- /.checkbox -->
        </div> 
        <!--/.radiobox 2-->
        <!-- //Box2 + table 統一樣式 -->
        <div class="" v-show="(typeTicketSetting == 'selectSeat') ? true : false">
           <!-- sortable-wraper -->
            <div class="sortable-wraper">
            <div class="ticket-sortable-box-wraper ui-sortable">
            <!---->
            <!--settingSeatData-->
            <template v-for="(item, index) in ticketSortIndex">
                    <div :id="'selectSeatBlock'+item" :data-key="item" class=" ml-3" v-if="(settingSeatData[item].seatStatus !== 'D')">
                    <!-- 錯誤訊息提示 -->
                    
                        <div class="" v-show="settingSeatData[item].seatErrorShowStatus || settingSeatData[item].seatErrorMsn "> 
                        <div class="callout callout-txt-warning ">
                            <div class="icon">
                                <i class="fas fa-exclamation-circle fa-lg"></i> 
                            </div>
                            <p>{{ settingSeatData[item].seatErrorShowMsn }} {{ settingSeatData[item].seatErrorMsn }}</p>
                        </div>
                     </div>
                     <!--/.錯誤訊息提示 -->
                        <div class="box box-solid ticket-setting-box">
                            <!-- box-header -->
                            <div class="box-header box-header-forms-gray">
                                <div class="col box-title-item"><i class="fas fa-arrows-alt-v"></i></div>
                                <!--col-->
                                <div class="col-xs-12 ptb-8x form-group-flex">
                                    <div class="col-xs-5 pl-x has-feedback">
                                        <!--
                                        <span class="glyphicon glyphicon-remove-sign form-control-feedback" @click="settingSeatTitleClear(index)"></span>-->
                                        <input :name="'Ticket-Name'+item" type="text" maxlength="25" v-bind:style="{ borderColor: (!settingSeatData[item].seatName)?'#e44e2d':'' }" class="form-control input-sm" :value="settingSeatData[item].seatName" @blur="settingSeatTitleChange(item, $event)" :disabled="statucControl[0].seat_class_name"  placeholder="<?php echo e(trans('events.S_seatTypePlaceholder')); ?>">
                                    </div>
                                    <div class="col-xs-1 has-feedback">
                                        <div class="colorpick-box" v-show="!settingSeatData[item].seatFree">
                                            <div v-bind:id="'colorPick-'+(item + 1)"  v-bind:style="colorButtonDis" :data-code="'colorPick-'+(item + 1)" :data-position="item" :data-color="(typeof(settingSeatData[item].seatColor)  == 'undefined')?'': settingSeatData[item].seatColor" data-seat-type="colorSet" class="colorPickSelector form-control input-sm"></div>
                                            <div class="color-arrow"><i class="fas fa-caret-down"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 form-checkbox wkeep wnowrap">
                                        <label class="control control--checkbox mtbl-1">
                                            <input type="checkbox" :id="'freeSeat_'+item" @click="settingSeatFreeCheackChange(item, $event, 'colorPick-'+item)"  v-bind:checked="settingSeatData[item].seatFree"  :disabled="freeSeatBtnDis(settingSeatData[item], item)">  <?php echo e(trans('events.S_freeSeatTitle')); ?> <small>[ ※ 券面に通し番号有 ]</small>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-xs-2 form-checkbox wkeep">
                                        <label class="control control--checkbox mtbl-1" v-show="!settingSeatData[item].seatFree">
                                            <input :id="'nextSeat'+item" type="checkbox" @click="seatOnNext(item, $event)" v-bind:checked="settingSeatData[item].seatNextSeat" :disabled="statucControl[0].next_seat_flg"> <?php echo e(trans('events.S_serialSeatTitle')); ?>

                                            
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-xs-2 buttons text-right btn-safari">
                                        <button type="button" class="btn waves-effect waves-light btn-danger btn-header-right btn-m" @click="removeRowAllSettingSeat(item)" v-show="seatDelBtn(settingSeatData[item])"> 
                                            <?php echo e(trans('events.S_seatTypeDeleteBtn')); ?>

                                        </button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.box-header -->
                            <!-- //box-body -->
                            <div class="box-body table-responsive pt-x pl-70">
                                <!-- //table 統一樣式 -->
                                <table class="table table-striped table-ticket">
                                    <thead>
                                        <tr>
                                            <th width="40px"></th>
                                            <th width="30%"><?php echo e(trans('events.S_TicketNameTitle')); ?></th>
                                            <th width="20%"><?php echo e(trans('events.S_TicketPriceTitle')); ?> </th>
                                            <th><?php echo e(trans('events.S_TicketEarlyBirdTitle')); ?>  </th>
                                            <th><?php echo e(trans('events.S_TicketNormalTitle')); ?></th>
                                            <th><?php echo e(trans('events.S_TicketOnSiteTitle')); ?></th>
                                            <th class="text-right">
                                                <button class="btn btn-inverse btn-m" @click="insertSettingSeatType(item)" v-show="!statucControl[0].ticket_sales_kbn"><?php echo e(trans('events.S_TicketTypeInsertBtn')); ?></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(infTicket, id) in ticket_items(settingSeatData[item].data)">
                                            <tr v-show="(infTicket.ticketStatus !== 'D')">
                                                <td>
                                                    <div class="item-cicle">{{ infTicket.oid }}</div>
                                                </td>
                                                <td>
                                                    <!--  STS 2021/06/01  -->
                                                    <!-- <div v-bind:class="{ 'select-error-tip': infTicket.ticketNameErrorStatus }"> -->
                                                        <select :value="infTicket.ticketName" :id="'specSelect-'+item+'-'+id" class="form-control select2 select-create" :data-index="item" :data-id="id" onchange="ticketSetting.settingSeatSelectChange(this)"  style="width: 100%;" :disabled="statucControl[0].ticket_class_name">
                                                            <!--<option value="" class="not-select" disabled><?php echo e(trans('events.S_ticketTypePlaceholder')); ?></option>-->
                                                            <option value="" class="not-select" selected="true" disabled="disabled"><?php echo e(trans('events.S_ticketTypePlaceholder')); ?></option> 
                                                            <template  v-for="inf in selectOption">
                                                                <option v-if="inf.id !== 'specSelect-'+item+'-'+id" :value="inf.value">{{ inf.value }}</option>
                                                            </template>
                                                        </select>
                                                        <span v-show="infTicket.ticketNameErrorStatus" class="help is-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e(trans('events.S_basicerrorMsn')); ?></span>
                                                    <!-- </div>                                          -->
                                                </td>
                                                <td>
                                                    <!-- <input name="text" type="number" min="0" class="form-control text-right  select-seat-price" v-bind:style="{ borderColor: (infTicket.ticketErrorStatus)?'#e44e2d':'' }"  :value="infTicket.ticketPrice"  @blur="settingInputPrice(item, id, $event)" :disabled="statucControl[0].price" placeholder="<?php echo e(trans('events.S_ticketPricePlaceholder')); ?>"> -->
                                                    <!-- STS 2021/06/12: 小切手 4 つの特殊文字 (+)、(-)、(.)、(,) -->
                                                    <input @paste="validateNumber" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="text" type="number" min="0" class="form-control text-right  select-seat-price" v-bind:style="{ borderColor: (infTicket.ticketErrorStatus)?'#e44e2d':'' }"  :value="infTicket.ticketPrice"  @blur="settingInputPrice(item, id, $event)" :disabled="statucControl[0].price" placeholder="<?php echo e(trans('events.S_ticketPricePlaceholder')); ?>">                                                
                                                </td>
                                                <td>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" value="" class="ticketEarlyBird" @click="ticketEarlyBirdSettingSeat(item, id, $event)" :checked="infTicket.ticketEarlyBird" :disabled="statucControl[0].ticket_sales_kbn">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" class="ticketNormal" value="" @click="ticketNormalSettingSeat(item, id, $event)" :checked="infTicket.ticketNormal" :disabled="statucControl[0].ticket_sales_kbn"> 
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" class="ticketOnSite" value="" @click=" ticketOnSiteSettingSeat(item, id, $event)" :checked="infTicket.ticketOnSite" :disabled="statucControl[0].ticket_sales_kbn"> 
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td class="text-right">
                                                    <button class="btn btn-danger btn-m" @click="removeRowSettingSeat(item, id)" v-show="ticketDelBtn(settingSeatData[item], infTicket)">
                                                            <?php echo e(trans('events.S_TicketTypeDeleteBtn')); ?>

                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <!-- /.table 統一樣式 -->
                            </div>
                            <!-- /.box-body -->                  
                        </div>
                        <!-- /.box -->
                    </div>
            </template>
             </div>
            </div>
            <!--/.sortable-wraper-->
        </div>
        <!-- /.Box2 + table 統一樣式 -->
        <!-- //BTN + L -->
        <div class="row" v-show="(typeTicketSetting == 'selectSeat') ? true : false">
            <div class="offset-pl-5 col-xs-12 mb-7">
                <button id="insertSelectSeat" class="btn btn-block waves-effect waves-light btn-rounded btn-inverse" @click="insertSelectSeat()" v-show="!statucControl[0].ticket_sales_kbn">
                    <?php echo e(trans('events.S_seatTypeInsertBtn')); ?>

                </button>
            </div>
        </div>
        <!-- /.BTN + L -->
        <!--//提示訊息 -->
        <div class="" v-show="(typeTicketSetting == 'selectSeat') ? true : false">
            <div class="ml-3">
            <div class="callout callout-info">
                <!-- -->
                <h4><?php echo e(trans('events.S_reserveSeatSettingTitle')); ?></h4>
                <?php echo trans('events.S_reserveSeatSettingDesc'); ?>

            </div>
            </div>
        </div>
        <!--/.提示訊息 -->
        <!-- //Box5 + table 統一樣式 -->
        <div class="" v-show="(typeTicketSetting == 'selectSeat') ? true : false">
            <div class="ml-3">
                <div class="callout callout-txt-warning" v-show="reserveSeatErrorStatus">
                    <div class="icon">
                        <i class="fas fa-exclamation-circle fa-lg"></i> 
                    </div>
                    <p>{{ reserveSeatErrorMsn }}</p>
                </div>
                <div class="box-header with-border-non flex-start">
                    <!-- 0618調整 -->
                    <div class="box-tools pull-right">
                        <button  id="insertSpecSeat" type="button" class="btn waves-effect waves-light btn-inverse btn-m" v-on:click="insertSpecSeat()" v-show="!statucControl[0].basis">   
                            <?php echo e(trans('events.S_reserveSeatInsertBtn')); ?>

                        </button>
                    </div>
                    <div class="col-xs-4 pl-50">
                        <h3 class="box-title"> <?php echo e(trans('events.S_reserveNameTitle')); ?> </h3>
                    </div>
                    <div class="col-xs-8 pl-40">
                        <h3 class="box-title"> <?php echo e(trans('events.S_reserveSymbol')); ?> <small><?php echo e(trans('events.S_reserveShortNameDesc')); ?></small> </h3>
                    </div>
                    <!-- /.0618調整 -->
                </div>
                <div id="specTicket" class="box box-solid">
                    <template id="ticketTypeRow" v-for="(item, index) in ticket_items(specSeatData)">
                        <!-- box-header -->
                        <div class="box-header box-header-forms-gray box-header-forms-rows" v-show="(item['ticketStatus'] !== 'D') ? true : false">
                            <div class="col-xs-1 box-title-item">{{ item.oid }}</div>
                            <!--col-->
                            <div class="col-xs-12 ptb-8x form-group-flex">
                                <div class="col-xs-4 pl-x has-feedback">
                                    <div v-bind:class="{ 'select-error-tip': item.ticketNameErrorStatus }"> 
                                        <select :id="'ssd-select-'+index" class="form-control select2 select-create" style="width: 100%;" :value="item['ticketName']" :data-index="index" onchange="ticketSetting.specSeatSelectChange(this)" :disabled="statucControl[0].reserve_name">
                                            <option value="" class="not-select" selected="true" disabled="disabled"><?php echo e(trans('events.S_reserveSeatPlaceholder')); ?></option> 
                                            <template  v-for="(inf, id) in  selectOption">
                                                <option v-if="inf.id !== 'ssd-select-'+index" :value="inf.value">{{ inf.value }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <!--  //文字 -->
                                <div class="col-xs-1 has-feedback">
                                    <input name="text" type="text"  maxlength="1" class="form-control input-sm pr-x " v-bind:style="{ borderColor: (item.ticketErrorStatus)?'#e44e2d':'' }" :value="item['ticketText']"  @blur="inputSpecText(index, $event)" :disabled="statucControl[0].reserve_symbol">
                                </div>
                                <div class="col-xs-2 has-feedback">
                                    <div class="colorpick-box">
                                        <div v-bind:id="'ssd-'+index" v-bind:style="colorButtonDis" data-seat-type="ssd" :data-position="index" :data-code="'ssd-select-'+index" :data-color="item['ticketColor']"
                                        class="colorPickSelector textColorPickSelector form-control input-sm">
                                        </div>
                                        <div class="color-arrow"><i class="fas fa-caret-down"></i></div>
                                    </div>
                                </div>
                                <!--  /.文字 -->
                                <div class="col-xs-6 pr-x buttons text-right">
                                    <button type="button" class="btn waves-effect waves-light btn-danger btn-header-right btn-m" v-on:click="removeSpecSeatRow(index)" v-show="item['ticketStatus'] == 'I' || !statucControl[0].ticket_del_kbn"> 
                                        <?php echo e(trans('events.S_reserveseatDeleteBtn')); ?> 
                                    </button>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.box-header -->
                    </template> 
                <!-- //box-body -->
                </div>
            <!-- /.box -->
            </div>
        </div>
        <!-- /.Box5 + table 統一樣式 -->
    </div>
</div>
<script>
const IS_SETTING_TICKET = <?php echo json_encode($eventData["is_setting_tikcet"], 15, 512) ?>;
var ticketSetting = new Vue({
    el: "#ticketSetting",
    data: {
        freeDataError: false,
        settingDataError: false,
        sejErrorStattus: false,
        priceErrorMsn:'',
        priceErrorStatus:'',
        typeTicketSetting: 'freeSeat',
        selection: false,
        freeSeatQty:0,
        freeSeatTicketName:'',
        freeSeatStatus:'I',
        freeSeatid:'',
        freeSeatData:[],
        freeSdbid:'',
        settingSeatData:[],
        specSeatData:[],
        selectOption:[],
        init:false,
        statucControl:[],
        errorMsg: '',
        errorStatus: '',
        freeticketErrorStatus: false,
        ticketIndex:1,
        reserveSeatErrorMsn: '',
        reserveSeatErrorStatus: false,
        ticketSort: [],
        ticketSortIndex: [0],
        ticketOnSiteShow: true,
        checkResult:[],
        colorButtonDis: '',
        saleType: basisSetting.saleType,  // STS - 2021/06/11 - Task 17
        addButtonErrorStatus: false // STS - 2021/07/28 Task 38
    },
    watch: {
        // STS - 2021/06/11 - Task 17 - START
        saleType: function() {
            this.cheackFreeSeatData()
            this.settingInputPriceCheackAll()
        },
        // STS - 2021/06/11 - Task 17 - END
        typeTicketSetting:function(val){
            this.inputRuleAllCheack()
            this.updateTicketSettingData()
            this.settingInputPriceCheackAll()
            if(
               basisSetting.locationName && 
               this.typeTicketSetting !== 'freeSeat' &&
               timeCourse.settingRadio == "normal"
            ){
                tagControl.seatSettingTag = true
            }else{
                tagControl.seatSettingTag = false
            }
        },
        selection:function(){
            this.updateTicketSettingData()
        },
        freeSeatQty:function(){
            let checkResult = this.freeSeatQtyRule()
            
            this.cheackData()

            if(checkResult){
                this.updateTicketSettingData()
            }
        },
        freeSeatTicketName:function(){
            this.updateTicketSettingData()
            this.cheackFreeSeatData()
            this.cheackData()
        },
        freeSeatData:{
            handler(){
                this.updateTicketSettingData()
            },
            deep: true
        },
        settingSeatData:{
            handler(){
                this.ticketIndex = 0
                this.updateTicketSettingData()
            },
            deep: true
        },
        specSeatData:{
            handler(){
                this.updateSpecTicketSettingData()
            },
            deep: true
        },
        errors:{
            handler(){
                this.cheackData()
            },
            deep: true
        },
        freeDataError:function(){
            this.cheackData()
        },
    },
    methods: {
        // STS 2021/06/12: 小切手 4 つの特殊文字 (+)、(-)、(.)、(,) -- START
        validateNumber(e) {
            const regex = /^\d+$/;
            if(!regex.test(e.clipboardData.getData('text')))
            {
                e.preventDefault()
            }
        },
        // STS 2021/06/12: 小切手 4 つの特殊文字 (+)、(-)、(.)、(,) -- END
        freeSeatBtnDis: function(seat, id){
            let seatsInfo = SaleInfo['seats_info']
            let seatTypeArr = SaleInfo['seatTypeArr']
            let has_setting = (IS_SETTING_TICKET.find(element => element == id))?true:false

            if(seat.seatTotal != 0 || this.statucControl[0].free_seat_flg || has_setting){
                return true
            }else{
                if(seatsInfo){
                    if(typeof seatsInfo[seat.sdbid] !== 'undefined'){
                        return true
                    }
                }
                if(seatTypeArr){
                    if(seatTypeArr.includes(seat.sdbid)) {
                        return true
                    }
                }
            }

            return false
        },
        seatDelBtn: function(seat){
            let seatsInfo = SaleInfo['seats_info']
           
            if(seat.seatStatus == 'I' || !this.statucControl[0].ticket_del_kbn){
                if(seatsInfo){
                    if(typeof seatsInfo[seat.sdbid] !== 'undefined'){
                        return false
                    }
                }
                return true
            }
            return false
        },
        ticketDelBtn: function(seat, ticket){
            let seatsInfo = SaleInfo['seats_info']
            
            if(ticket.ticketStatus == 'I' || !this.statucControl[0].ticket_del_kbn){
                if(seatsInfo){
                    if(typeof seatsInfo[seat.sdbid] !== 'undefined'){
                        let ticketSale = seatsInfo[seat.sdbid].find(function(item, index, array){
                            return item == ticket.ticketEarlyBirdId || item == ticket.ticketNormalId || item == ticket.ticketOnSiteId
                        });
                        if(ticketSale){
                            return false
                        }
                    }
                }
                return true
            }
            return false
        },
        freeTicketDelBtn: function(ticket){
            let seatsInfo = SaleInfo['seats_info']
           
            if(ticket.ticketStatus == 'I' || !this.statucControl[0].ticket_del_kbn){
                if(seatsInfo){
                    if(typeof seatsInfo[this.freeSdbid] !== 'undefined'){
                        let ticketSale = seatsInfo[this.freeSdbid].find(function(item, index, array){
                            return item == ticket.ticketEarlyBirdId || item == ticket.ticketNormalId || item == ticket.ticketOnSiteId
                        });
                        if(ticketSale){
                            return false
                        }
                    }
                }
                return true
            }
            return false
        },
        freeSeatQtyRule: function(val = this.freeSeatQty){
            try {
                this.checkResult['freeSeatQty']['status'] = false
                this.checkResult['freeSeatQty']['msn'] = ''
                
                if(isNaN(val)){
                    throw (new Error('数字のみで入力してください。'))
                }

                if(parseInt(val) < 0){
                    throw (new Error('正の整数を入力してください'))
                }

                return true
            }catch (e){
                this.checkResult['freeSeatQty']['status'] = true
                this.checkResult['freeSeatQty']['msn']  = e.message
                
                return false
            }
        },
        ticketOnSiteStatus:function(){
            this.freeSeatData.forEach(function(item){
                item.ticketOnSite = false
            })
            this.ticketOnSiteShow = false
        },
        seat_items:function(infTicketArr) {
            let result = [];
            let index = 1;
            
            if(typeof infTicketArr === 'undefined'){
                infTicketArr = []
            }
          
            for(let infTicket of infTicketArr) {
                if(infTicket.seatStatus !== 'D') {
                    infTicket.oid = index++;
                }
            }
            
            return infTicketArr;
        },
        ticket_items:function(infTicketArr) {
            let result = [];
            let index = 1;
            
            if(typeof infTicketArr === 'undefined'){
                infTicketArr = []
            }
          
            for(let infTicket of infTicketArr) {
                if(infTicket.ticketStatus !== 'D') {
                    infTicket.oid = index++;
                }
            }
            
            return infTicketArr;
        },
        typeChange:function(val){
            this.typeTicketSetting = val
            this.cheackData()

        },
        cheackData: function(){
            //this.$validator.validateAll()  
        
            if(this.typeTicketSetting == "selectSeat"){
                if(this.settingDataError || this.errors.any()){
                    tagControl.ticketWarning = true
                }else{
                    tagControl.ticketWarning = false
                }
                if(typeof this.specSeatData == 'undefined'){
                    this.specSeatData = []
                }

            }else{
                this.cheackFreeSeatData()
                if(this.freeDataError || this.errors.any() || !this.freeSeatQtyRule()){
                    tagControl.ticketWarning = true
                }else{
                    tagControl.ticketWarning = false
                }
            }

            errorMsnCheack.updataBtnCheack()
           
        },
        settingInputPriceCheackAll:function(){
            console.info('settingInputPriceCheackAll')
            
            if(typeof this.specSeatData !== 'undefined'){
                this.cheackReserveSeatData()
            }
            
            if(this.typeTicketSetting == "selectSeat"){
                let length = this.settingSeatData.length
                let statuc = false
               this.addButtonErrorStatus = false // STS 2021/07/27 Task 38 
                for(let no = 0; no< length; no++){ 
                    let checkResult = this.settingInputPriceCheack(no)
                  
                    if(checkResult){
                        statuc = checkResult
                        
                    }
                }
                
                errorMsnCheack.addBtnCheack()//STS 2021/07/27 Task 38
                this.settingDataError = statuc
               
                if(this.settingDataError || this.reserveSeatErrorStatus){
                    tagControl.ticketWarning = true
                }else{
                    tagControl.ticketWarning = false
                }
            }else{
                this.cheackFreeSeatData()
                if(this.freeDataError || this.reserveSeatErrorStatus){
                    tagControl.ticketWarning = true
                }else{
                    tagControl.ticketWarning = false
                }
            }
            this.cheackData()
            errorMsnCheack.updataBtnCheack()
        },
        //edit page call, get ticket data
        getTicketSettingData:function(all=0){
            let ticketSetting = JSON.parse(sessionStorage.getItem('ticketSetting'))
            let specTicketSetting = ""
            if(sessionStorage.getItem('specTicketSetting') ){
                specTicketSetting = JSON.parse(sessionStorage.getItem('specTicketSetting'))
            }            
            let ticketData = []

            this.selectOption.forEach(function(element) {
                element.id = 0
            });

            if(ticketSetting.settingType == 'freeSeat'){
                
                let cData = []

                ticketSetting.data.data.forEach(function(element) {
                    if((element.ticketStatus !== 'D' && (element.ticketCode == '0' && element.ticketStatus !== 'D')) || all ){
                        cData.push({
                            ticketName: element.ticketName,
                            ticketPrice: element.ticketPrice,
                            ticketStatus: element.ticketStatus,
                            ticketCode: element.ticketCode,
                            ticketEarlyBird: element.ticketEarlyBird,
                            ticketEarlyBirdId: element.ticketEarlyBirdId,
                            ticketNormal: element.ticketNormal,
                            ticketNormalId: element.ticketNormalId,
                            ticketOnSite: element.ticketOnSite,
                            ticketOnSiteId: element.ticketOnSiteId,
                        })
                    }
                });

                ticketSetting.data.data = cData
            }
            
            if(ticketSetting.settingType == 'selectSeat'){
                let settingTikcet = []
                
                ticketSetting.data.forEach(function(element, index) {
                    if(element.seatStatus !== 'D' && (element.seatCode == '0' && element.seatStatus !== 'D') || all ){
                        let cData = []

                        element.data.forEach(function(ele) {
                            if(ele.ticketStatus !== 'D' && (ele.ticketCode == '0' && ele.ticketStatus !== 'D') || all){
                                cData.push({
                                    ticketName: ele.ticketName,
                                    ticketPrice: ele.ticketPrice,
                                    ticketStatus: ele.ticketStatus,
                                    ticketCode: ele.ticketCode,
                                    ticketEarlyBird: ele.ticketEarlyBird,
                                    ticketEarlyBirdId: ele.ticketEarlyBirdId,
                                    ticketNormal: ele.ticketNormal,
                                    ticketNormalId: ele.ticketNormalId,
                                    ticketOnSite: ele.ticketOnSite,
                                    ticketOnSiteId: ele.ticketOnSiteId,
                                })
                            }
                        });
                        
                        settingTikcet.push({
                            seatName: element.seatName,
                            seatStatus: element.seatStatus,
                            seatFree: element.seatFree,
                            seatNextSeat: element.seatNextSeat,
                            seatColor: element.seatColor,
                            seatid: element.seatid,
                            seatCode: element.seatCode,
                            seatTotal: element.seatTotal,
                            sdbid: element.sdbid,
                            respectiveData: element.respectiveData,
                            data: cData,
                        })
                    }
                });
                
                if (typeof this.ticketSort !== 'undefined' && this.ticketSort.length > 0) {
                    let sortId = 1
                    this.ticketSort.forEach(function(ele, index) {
                        if(settingTikcet[ele].seatStatus != 'D'){
                            settingTikcet[ele].seatStatus = 'U'
                            settingTikcet[ele].seatid = sortId
                            sortId++
                        }
                    })
                }
                
                var filterTicket = settingTikcet.filter(function(item, index, array){
                    return item.seatStatus == 'U';    
                });

                let sortId = filterTicket.length+1
                settingTikcet.forEach(function(item){ 
                    if(item.seatStatus == 'I'){
                        item.seatid = sortId
                        sortId++
                    }
                })
                ticketSetting.data = settingTikcet
            }

            if(typeof(specTicketSetting.data) !== 'undefined'){
                let specData = []
                // [TODO] James 07/31 : 保留席全數刪除時會發生錯誤 ：specTicketSetting.data.forEach is not a function
                specTicketSetting.data.forEach(function(element) {
                    if(element.ticketStatus !== 'D' && (element.ticketCode == '0' && element.ticketStatus !== 'D') || all){
                        specData.push({
                            tickerId: element.tickerId,
                            ticketCode: element.ticketCode,
                            ticketColor: element.ticketColor,
                            ticketName: element.ticketName,
                            ticketStatus: element.ticketStatus,
                            ticketText: element.ticketText,
                            ticketTotal: element.ticketTotal,
                        })
                    }
                });
                
                specTicketSetting.data = specData
            }

            ticketData.push({
                ticketSetting: ticketSetting,
                specTicketSetting: specTicketSetting,
                option: this.selectOption,
            })

            return ticketData
        },
        freeSeatNameChange(){
            if(this.freeSeatStatus !== "I"){
                this.freeSeatStatus = "U"
            }
        },
        //初始化 color pick
        initColorPick:function(id, color){
            $.getScript("<?php echo e(asset('js/colorPick.js')); ?>", function(){
                $(id).colorPick({
                    'initialColor': color,
                    'allowRecent': true,
                    'recentMax': 5,
                    'palette': _colorList,
                    'onColorSelected': function() {
                        
                        let id = this.element.data("position") 
                        let type = this.element.data("seat-type") 
                        let color = this.color
            
                        this.element.css({'backgroundColor': this.color, 'color': this.color});
                        ticketSetting.changeSeatColor(id, type, color)
                    }
                });
            });
        },
        //初始化 color text pick (色系)
        initTextColorPick:function(id, color){
           
            $.getScript("<?php echo e(asset('js/colorPick.js')); ?>", function(){
                $(id).colorPick({
                    'initialColor': color,
                    'allowRecent': true,
                    'recentMax': 5,
                    'palette': _textColorList,
                    'onColorSelected': function() {
                        
                        let id = this.element.data("position") 
                        let type = this.element.data("seat-type") 
                        let color = this.color
            
                        this.element.css({'backgroundColor': this.color, 'color': this.color});
                        ticketSetting.changeSeatColor(id, type, color)
                    }
                });
            });
        },
        //初始化 select option
        initSelectOption:function(id){
            // STS 2021/06/01 place holderを"券種"に変更してください。
            var _placeholder;

            if(id.includes('specSelect')){
                _placeholder = '券種';
            }
            else if(id.includes('nomSelect')){
                _placeholder = '券種';
            }
            else{
                _placeholder = '券種';
            }
            $.when(
                $.Deferred(function(deferred ){
                    $( deferred.resolve );
                })
            ).done(function(){
                $(id).select2({
                    tags: true,
                    language: "ja",
                    placeholder: _placeholder,
                    maximumInputLength: 30,
                    closeOnSelect:true,
                    matcher: function(params, data) {
                            // If there are no search terms, return all of the data
                            if ($.trim(params.term) === '') {
                            //   return data;
                                if(tt == null)
                                    return data;
                                params.term = tt;
                            }

                            // Do not display the item if there is no 'text' property
                            if (typeof data.text === 'undefined') {
                            return null;
                            }

                            // `params.term` should be the term that is used for searching
                            // `data.text` is the text that is displayed for the data object
                            if (data.text.indexOf(params.term) > -1) {
                            var modifiedData = $.extend({}, data, true);
                            //   modifiedData.text += ' (matched)';

                            // You can return modified objects from here
                            // This includes matching the `children` how you want in nested data sets
                            return modifiedData;
                            }

                            // Return `null` if the term should not be displayed
                            return null;
                        } ,
                    language: {
                                    errorLoading:function(){return"結果が読み込まれませんでした"},
                                    inputTooLong:function(e) {
                                        var t=e.input.length-e.maximum,n="券種は"+e.maximum+"文字以下で入力してください。";
                                        return n
                                        },
                                    inputTooShort:function(e){var t=e.minimum-e.input.length,n="少なくとも "+t+" 文字を入力してください";return n},
                                    loadingMore:function(){return"読み込み中…"},maximumSelected:function(e){var t=e.maximum+" 件しか選択できません";return t},
                                    noResults:function(){return"対象が見つかりません"},
                                    searching:function(){return"検索しています…"}
                                },
                }).on('select2:open', function( event ) {
                    //STS - 2021/06/10: Task 16.- START
                    basisSetting.oldID=id;
                    if($('.select2-search__field').val()!="")
                    {
                        oldValue = $('.select2-search__field').val()
                    }
                    else 
                    {
                        oldValue="";
                    }
                    let val = $(this).val()
                    tt = val
                    $('.select2-search__field').val(val).change()
                    $(this).val(null).trigger('change')
                }).on('select2:closing', function( event ) {
                    if(typeof event.params.args.originalSelect2Event !== 'undefined') {
                        var data = event.params.args.originalSelect2Event.data
                        if ($(this).find("option[value='" + data.id + "']").length) {
                            $(this).val(data.id).trigger('change');
                        } else { 
                            // Create a DOM Option and pre-select by default
                            var newOption = new Option(data.text, data.id, true, true);
                            // Append it to the select
                            $(this).append(newOption).trigger('change');
                           
                        } 
                    }
                    else {
                        if(oldValue!="")
                        {
                             $(this).val(oldValue).trigger('change');
                             oldValue="";
                        }
                         //STS - 2021/06/10: Task 10: Allow re-blank ticket name. - START
                        else if($('.select2-search__field').val()=="")
                            {
                                $(this).val(null).trigger('change');
                            }
                         //STS - 2021/06/10: Task 10: Allow re-blank ticket name. - END
                        else if(basisSetting.oldID!=id)
                        {
                            $(this).val(null).trigger('change');
                        }
                        else $(this).val(tt).trigger('change');
                    }
                })
                //STS - 2021/06/10: Task 16.- END
            });
        },
        changeSeatColor:function(id, type, color){
           
            if(type === 'ssd'){
                this.inputSpecColor(id, color)
            }else if(type === 'colorSet'){
                this.inputTIcketColor(id, color)
            }else{
            
            }
            if(this.init){
                seatSetting.clearTicketTypeColor(id, type, color)
            }
        },
        insertSpecSeat:function(){
           
            if(typeof(this.specSeatData) == 'undefined'){
                this.specSeatData = []
                let id = 0
                let colId = "#ssd-"+id
                let selectId = '#ssd-select-'+id
                let color = "#FF748A"
                this.specSeatData.push({
                    tickerId: id,
                    ticketName: "",
                    ticketText: "",
                    ticketColor: color,
                    ticketStatus: "I",
                    ticketCode: "0",
                    ticketTotal: 0,
                    ticketErrorMsn: "",
                    ticketErrorStatus: false,
                    ticketNameErrorStatus: false,
                })

                this.initTextColorPick(colId, color)
                this.initSelectOption(selectId)
            }else{
                let id = this.specSeatData.length 
                let colId = "#ssd-"+id
                let selectId = '#ssd-select-'+id
                let color = this.getTextColor(_textColorList, this.specSeatData)

                this.specSeatData.push({
                    tickerId: id,
                    ticketName: "",
                    ticketText: "",
                    ticketColor: color,
                    ticketStatus: "I",
                    ticketCode: "0",
                    ticketTotal: 0,
                    ticketErrorMsn: "",
                    ticketErrorStatus: false,
                    ticketNameErrorStatus: false,
                })

                this.initTextColorPick(colId, color)
                this.initSelectOption(selectId)
            }

            this.settingInputPriceCheackAll()
        },
        inputSpecTitle:function(id, event){
            let specTicketAll = document.getElementById('specTicket')
            let parentCol = specTicketAll.getElementsByClassName('spec-seat-tittle').length

            this.specSeatData[id].ticketName = event.target.value
        },
        inputSpecText:function(id, event){
            let specTicketAll = document.getElementById('specTicket')
            let parentCol = specTicketAll.getElementsByClassName('spec-seat-tittle').length

            this.specSeatData[id].ticketText = event.target.value
            seatSetting.clearTicketTypeText(id, 'ssd', event.target.value)
            if(this.specSeatData[id].ticketStatus !== "I"){
                this.specSeatData[id].ticketStatus = "U"
            }

            this.settingInputPriceCheackAll()
        },
        inputSpecColor:function(id, color){
            let specTicketAll = document.getElementById('specTicket')
            let parentCol = specTicketAll.getElementsByClassName('spec-seat-tittle').length

            this.specSeatData[id].ticketColor = color
            
            if(this.specSeatData[id].ticketStatus !== "I" && this.specSeatData[id].ticketStatus !== "D"){
                this.specSeatData[id].ticketStatus = "U"
            }
        },
        inputTIcketColor:function(id, color){
            this.settingSeatData[id].seatColor = color
         
            if(this.settingSeatData[id].seatStatus !== "I" && this.settingSeatData[id].seatStatus !== "D"){
                this.settingSeatData[id].seatStatus = "U"
            }
        },
        inputRuleAllCheack:function(){
            if(this.typeTicketSetting === 'freeSeat'){
                let parentCol = document.getElementById('freeTicket')
                let ticketCol = parentCol.getElementsByClassName('free-seat-tittle')
                let id = 0

                for(let col of ticketCol) {
                    let colLength = col.value.length
                    let warnId = 'ticketWarn' + id
                    
                    this.inputDataCheack(colLength, warnId, id)
                    this.cheackFreeSeatData()
                    id++
                }
            }else{ 
                let selectSeat = document.getElementById("selectSeat")
                let parentCol = selectSeat.getElementsByClassName('ticket-setting-box')
                let parentId = 0
                let id = 0

                for(let block of parentCol){
                    id = 0
                    let ticketCol = block.getElementsByClassName('select-seat-tittle')
                    for(let col of ticketCol) {
                        let inputValue = col.value
                        let inputLen = col.value.length
                        let warnId = 'ticketWarn' +  parentId + id 
                        let blockId = 'selectSeatBlock' + parentId 
                        
                        this.inputSelectSeatDataCheack(inputLen, warnId, blockId, id)
                        this.settingInputPriceCheack(parentId, id)

                        id++
                    }
                    parentId++
                }
                
            }
        },
        inputDataCheack:function(stringLen, warnId, id){
            let warnText = document.getElementById(warnId)
            let colNum = this.freeSeatData.length
            let cheackPass = true
            let warnContent = ''
            let colStar = 0

            if(stringLen ===  0 && colNum >= 2){
                cheackPass = false
                warnContent += '券種名未入力(925)'
            }

            if(cheackPass){
                warnText.style.display = 'none'
            }else{
                warnText.textContent = warnContent 
                warnText.style.display = 'block'
            }

            this.allCheackBoxRule(id)
        },
        freeSeatNameClear:function(){
            this.freeSeatTicketName = ""
        },
        inputChange:function(id, event){
            let freeSeatData = this.freeSeatData
            let warnId = 'ticketWarn' + id
            let inputLen = event.target.value.length
            let inputValue = event.target.value
            
            freeSeatData[id].ticketName = inputValue
            this.freeSeatData = freeSeatData
            this.inputDataCheack(inputLen, warnId, id)
            ticketSetWarn(this.typeTicketSetting)
        },
        clearInput:function(id){
            let freeSeatData = this.freeSeatData
            freeSeatData[id].ticketName = ""
            $(".free-seat-tittle")[id].value = ""
            this.freeSeatData = freeSeatData
        },
        insertFreeSeat:function(){
            this.freeSeatData.push({
                ticketName: "",
                ticketPrice: "0",
                ticketEarlyBird: "",
                ticketNormal: "",
                ticketOnSite: "",
                ticketEarlyBirdId: "0",
                ticketNormalId: "0",
                ticketOnSiteId: "0",
                ticketStatus: "I",
                ticketCode:"0",
                ticketNameErrorStatus:false,//STS 2021/07/27 Task 38
                ticketErrorStatus:false,
                ticketErrorMsn:"",
            })
            
            let id = '#nomSelect-'+ (this.freeSeatData.length - 1)
            this.initSelectOption(id)
            this.cheackFreeSeatData()
        },
        removeRow:function(id){
            // [TODO] James 07/25 : This code is for what???
            if(this.freeSeatData[id].ticketCode !== '0' && this.freeSeatData[id].ticketCode !== null){
                this.freeSeatData[id].ticketStatus = "D"
            }else{
                this.freeSeatData[id].ticketStatus = "D"
            }
            this.cheackFreeSeatData()
            this.settingInputPriceCheackAll()
        },
        removeSpecSeatRow:function(id){   
            this.specSeatData[id].ticketStatus = "D"
            seatSetting.clearTicketType(id, 'ssd')
           
            this.settingInputPriceCheackAll()
        },
        /* 
        保留席 檢查
        */
        cheackReserveSeatData:function(){
            console.info(' 保留席 檢查')

            let reserveSeat         = this.specSeatData 
            let reserveSeatTotal    = this.specSeatData.length
            let errorMsg            =  "<?php echo e(trans('events.S_ErrMsgeTitle')); ?>"
            let status              = false
            let errorStattus        = false
            let errorNum            = ''
            let seatId              = 0
            
            //檢查票別種稱不為空
            for(let n=0; n<reserveSeatTotal; n++){
                if(reserveSeat[n].ticketStatus != 'D'){
                    seatId++
                }

                if(reserveSeat[n].ticketName.trim().length > 0 || reserveSeat[n].ticketStatus == 'D'){
                    reserveSeat[n].ticketErrorMsn         = ""
                    reserveSeat[n].ticketNameErrorStatus  = false
                }else{
                    reserveSeat[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_06')); ?>"
                    reserveSeat[n].ticketNameErrorStatus = true
                    errorStattus && (errorNum += "、")
                    errorNum  += seatId

                    errorStattus    = true
                    status          = true
                }
            }

            if(errorStattus){
                errorMsg && (errorMsg += "、");
                errorMsg += "<?php echo e(trans('events.S_ErrMsge_06')); ?>" + `：<?php echo e(trans('events.S_ErrNo')); ?>(${errorNum})` 
            }

            //檢查記號不為空
            errorStattus = false
            errorNum     = ''
            seatId       = 0

            for(let n=0; n<reserveSeatTotal; n++){
                if(reserveSeat[n].ticketStatus != 'D'){
                    seatId++
                }

                if(reserveSeat[n].ticketText.trim().length > 0 || reserveSeat[n].ticketStatus == 'D'){
                    reserveSeat[n].ticketErrorMsn     = ""
                    reserveSeat[n].ticketErrorStatus  = false
                }else{
                    reserveSeat[n].ticketErrorMsn    = "<?php echo e(trans('events.S_ErrMsge_04')); ?>"
                    reserveSeat[n].ticketErrorStatus = true
                    errorStattus && (errorNum += "、")
                    errorNum  += seatId

                    errorStattus    = true
                    status          = true
                }
            }

            if(errorStattus){
                errorMsg && (errorMsg += "、");
                errorMsg += "<?php echo e(trans('events.S_ErrMsge_04')); ?>" + `：<?php echo e(trans('events.S_ErrNo')); ?>(${errorNum})` 
            }

            //相同票別名稱票價不得相同
            errorStattus = false

            for(let n=0; n<reserveSeatTotal; n++){
                for(let m=n; m<reserveSeatTotal; m++){
                    if(
                        n !== m && 
                        reserveSeat[n].ticketText   == reserveSeat[m].ticketText &&
                        reserveSeat[n].ticketStatus !== 'D' &&
                        reserveSeat[m].ticketStatus !== 'D' &&
                        reserveSeat[n].ticketText.trim().length > 0
                    ){
                        reserveSeat[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_05')); ?>"
                        reserveSeat[n].ticketErrorStatus = true
                        reserveSeat[m].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_05')); ?>"
                        reserveSeat[m].ticketErrorStatus = true
                      
                        errorStattus    = true
                        status          = true
                    }
                }
            }
           
            if(errorStattus){
                errorMsg && (errorMsg += "、");
                errorMsg += "<?php echo e(trans('events.S_ErrMsge_05')); ?>"
            }

            this.reserveSeatErrorMsn    = errorMsg
            this.reserveSeatErrorStatus = status
        },
        /*
         無指定座位／無座位檢查
        */ 
        cheackFreeSeatData:function(){
            let parentCol    = this.freeSeatData.length
            let freeSeatData = this.freeSeatData 
            let errorMsg     =  "<?php echo e(trans('events.S_ErrMsgeTitle')); ?>"
            let status       = false
            let errorStattus = false
            let errorNum     = ''
            let sejErrorStattus = false;
            let sejErrorNum     = '';
            let seatId       = 0;
            let re = new RegExp(`[^\u4E00-\u9FFF\uFF00-\uFF65\uFF9E-\uFFEF\u3000-\u30FC]{21}`);
            let re2 = new RegExp(`[\-]{2}`); // STS 2021/07/27 Task 38
            this.sejErrorStattus = false;
            
            this.addButtonErrorStatus = false;// STS 2021/07/27 Task 38
            //檢查票別種稱不為空
            if(this.freeSeatTicketName.trim().length === 0){
                errorMsg += "<?php echo e(trans('events.S_ErrMsge_07')); ?>"
                this.freeticketErrorStatus = true
            }else{
                this.freeticketErrorStatus = false
            }

            if(re.test(this.freeSeatTicketName)){
                errorMsg += "席種名は半角文字・半角スペースを21文字以上続けないでください。"
                this.freeticketErrorStatus = true
            }
            // STS 2021/07/27 Task 38 start
            if(re2.test(this.freeSeatTicketName)){
                errorMsg += "半角ハイフンを2つ以上連続けないでください。"
                this.freeticketErrorStatus = true

                if(this.saleType == 1){
                    status = true
                    this.addButtonErrorStatus = true
                }
            }
            // STS 2021/07/27 Task 38 end
            //檢查票別名稱不為空
            for(let n=0; n<parentCol; n++){
                if(freeSeatData[n].ticketStatus != 'D'){
                    seatId++
                }

                if(freeSeatData[n].ticketName.trim().length > 0 || freeSeatData[n].ticketStatus == 'D'){
                    this.freeSeatData[n].ticketErrorMsn         = ""
                    this.freeSeatData[n].ticketNameErrorStatus  = false
                }
                // STS 2021/06/01 券種名がブランクでもエラーメッセージを表示しないようにしてください
                // else{
                //     this.freeSeatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_01')); ?>"
                //     this.freeSeatData[n].ticketNameErrorStatus = true
                //     errorStattus && (errorNum += "、")
                //     errorNum  += seatId

                //     errorStattus    = true
                //     status          = true
                // }
                // STS 2021/07/27 Task 38 start
                // if(re.test(freeSeatData[n].ticketName) && freeSeatData[n].ticketStatus != 'D'){
                //     this.freeSeatData[n].ticketErrorMsn = " 券種名は半角文字・半角スペースを21文字以上続けないでください。"
                //     this.freeSeatData[n].ticketNameErrorStatus = true
                //     sejErrorStattus && (sejErrorNum += "、")
                //     sejErrorNum  += seatId

                //     sejErrorStattus    = true
                // }
                if(freeSeatData[n].ticketStatus != 'D'){
                    if(re.test(freeSeatData[n].ticketName)){
                        sejErrorStattus && (sejErrorNum += "、")
                        sejErrorNum         += seatId
                        sejErrorStattus    = true
                    }
                    if(re2.test(freeSeatData[n].ticketName)){   
                        this.freeSeatData[n].ticketNameErrorStatus = true
                        if(this.saleType == 1) {
                            status = true
                            this.addButtonErrorStatus = true
                        }
                    }else this.freeSeatData[n].ticketNameErrorStatus = false
                }
                // STS 2021/07/27 Task 38 end

            }
            
            if(errorStattus){
                errorMsg += `<?php echo e(trans('events.S_ErrMsge_01')); ?>：<?php echo e(trans('events.S_ErrNo')); ?>(${errorNum})` 
            }

            if(sejErrorStattus){
                errorStattus && (errorMsg += "、");
                errorMsg += `券種名は半角文字・半角スペースを21文字以上続けないでください。：<?php echo e(trans('events.S_ErrNo')); ?>(${sejErrorNum})` 
            }

            //票價不得低於零
            errorStattus = false
            errorNum     = ''
            seatId       = 0

            //STS - 2021/06/11 - Comment - START
            //  for(let n=0; n<parentCol; n++){
            //     if(freeSeatData[n].ticketStatus != 'D'){
            //         seatId++
            //     }

            //     if(freeSeatData[n].ticketPrice >= 0 || freeSeatData[n].ticketStatus == 'D'){
            //         this.freeSeatData[n].ticketErrorMsn     = ""
            //         this.freeSeatData[n].ticketErrorStatus  = false
            //     }else{
            //         this.freeSeatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_02')); ?>"
            //         this.freeSeatData[n].ticketErrorStatus = true
            //         errorStattus && (errorNum += "、")
            //         errorNum  += seatId

            //         errorStattus    = true
            //         status          = true
            //     }
            // }
            //STS - 2021/06/11 - Comment - END

            // STS - 2021/06/11 - Task 17 - START 
    
            for(let n=0; n<parentCol; n++){
                if(freeSeatData[n].ticketStatus != 'D'){
                    seatId++
                }
                 if(this.saleType != 1 || freeSeatData[n].ticketStatus == 'D' || (freeSeatData[n].ticketPrice.length > 0 && freeSeatData[n].ticketPrice >= 0)){
                    this.freeSeatData[n].ticketErrorMsn     = ""
                    this.freeSeatData[n].ticketErrorStatus  = false
                }else{
                    this.freeSeatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_02')); ?>"
                    this.freeSeatData[n].ticketErrorStatus = true
                    errorStattus && (errorNum += "、")
                    errorNum  += seatId
                    errorStattus    = true
                    status          = true
                }
            }
            // STS - 2021/06/11 - Task 17 - END
            
            if(errorStattus){
                errorMsg && (errorMsg += "、");
                errorMsg += "<?php echo e(trans('events.S_ErrMsge_02')); ?>" + `：<?php echo e(trans('events.S_ErrNo')); ?>(${errorNum})`
            }

            //相同票別名稱票價不得相同
            errorStattus = false

            for(let n=0; n<parentCol; n++){
                for(let m=n; m<parentCol; m++){
                    // STS 2021/06/09 : task 10 parseInt ticketPrice START
                    let ticketPriceN = parseInt(freeSeatData[n].ticketPrice) 
                    let ticketPriceM = parseInt(freeSeatData[m].ticketPrice)
                    if(
                        n !== m && 
                        freeSeatData[n].ticketName == freeSeatData[m].ticketName && 
                        // freeSeatData[n].ticketPrice == freeSeatData[m].ticketPrice &&
                        ticketPriceN === ticketPriceM && 
                    // STS 2021/06/09 : task 10 parseInt ticketPrice END
                        freeSeatData[n].ticketStatus !== 'D' &&
                        freeSeatData[m].ticketStatus !== 'D' &&
                        freeSeatData[n].ticketPrice > 0
                    ){
                        this.freeSeatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_03')); ?>"
                        this.freeSeatData[n].ticketErrorStatus = true
                        this.freeSeatData[m].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_03')); ?>"
                        this.freeSeatData[m].ticketErrorStatus = true
                        
                        errorStattus    = true
                        status          = true
                    }
                }
            }
           
            if(errorStattus){
                errorMsg && (errorMsg += "、");
                errorMsg += "<?php echo e(trans('events.S_ErrMsge_03')); ?>"
            }

            this.errorMsg           = errorMsg
            this.priceErrorStatus   = status
            this.freeDataError      = status
            this.sejErrorStattus = sejErrorStattus;
            errorMsnCheack.addBtnCheack()
            return status
        },
        inputPrice:function(id, event){
            let freeTicketAll = document.getElementById('freeTicket')
            let parentCol = this.freeSeatData.length
            let freeSeatData = this.freeSeatData
            let priceErrorId = "priceError"+id 

            this.freeSeatData[id].ticketPrice = event.target.value
           
            this.cheackFreeSeatData()

            if(this.freeSeatData[id].ticketStatus !== 'I'){
                this.freeSeatData[id].ticketStatus = 'U'
            }
        },
        allCheackBoxRule:function(id){
            var cheackType = ['ticketEarlyBird', 'ticketNormal', 'ticketOnSite']

            for(let cheackTittle of cheackType){
                this.ticketCheackBox(id, cheackTittle)
            }
        },
        ticketCheackBox:function(id, cheackType){
            let ticketData = this.freeSeatData
            let idTitle = this.freeSeatData[id].ticketName
            let numTittle = 0

            // if(idTitle !== ''){ // STS 2021/06/01 check duplicate
                for(let event of ticketData ){ 
                    if(id !== numTittle && idTitle === event.ticketName){
                        eval('ticketData['+numTittle+'].'+cheackType+'= false') 
                    }
                    numTittle++
                }
            // }
        },
        ticketEarlyBird:function(id, event){
            this.freeSeatData[id].ticketEarlyBird = event.target.checked
            if(this.freeSeatData[id].ticketStatus !== 'I'){
                this.freeSeatData[id].ticketStatus = 'U'
            }
            // this.freeSeatData[id].ticketOnSite = false

            this.ticketCheackBox(id, 'ticketEarlyBird')
        },
        ticketNormal:function(id, event){
            this.freeSeatData[id].ticketNormal = event.target.checked
            if(this.freeSeatData[id].ticketStatus !== 'I'){
                this.freeSeatData[id].ticketStatus = 'U'
            }
            //this.freeSeatData[id].ticketOnSite = false

            this.ticketCheackBox(id, 'ticketNormal')
        },
        ticketOnSite:function(id, event){
            this.freeSeatData[id].ticketOnSite = event.target.checked
            if(this.freeSeatData[id].ticketStatus !== 'I'){
                this.freeSeatData[id].ticketStatus = 'U'
            }  
            // this.freeSeatData[id].ticketEarlyBird = false
            // this.freeSeatData[id].ticketNormal = false
            
            this.ticketCheackBox(id, 'ticketOnSite')
        },
        getSeatColor:function(colorList, seatData){
            let result = '#E9C489'

            for (i = 0; i < colorList.length; i++) {
                let color = colorList[i]
                let isSet = seatData.some(function(item, index, array){   
                    return item.seatColor == color && item.seatStatus !== 'D'
                });
                
                if(!isSet){
                    result = color
                    break
                }
            }

            return result
        },
        getTextColor:function(colorList, seatData){
            let result = '#FF748A'

            for (i = 0; i < colorList.length; i++) {
                let color = colorList[i]
                let isSet = seatData.some(function(item, index, array){   
                    return item.ticketColor == color && item.ticketStatus !== 'D'
                });
                
                if(!isSet){
                    result = color
                    break
                }
            }

            return result
        },
        insertSelectSeat:function(){
            let id = this.settingSeatData.length + 1
            let colorId = '#colorPick-'+id
            let color = this.getSeatColor(_colorList, this.settingSeatData)

            this.settingSeatData.push({
                seatName: "",
                seatStatus: "I",
                seatFree: false,
                seatNextSeat: false,
                seatColor: color,
                seatid: id,
                seatCode: "0",
                seatTotal: 0,
                seatErrorMsn:"",
                seatErrorStatus:false,
                seatSettingErrorMsn:"",
                seatSettingErrorStatus:false,
                seatErrorShowMsn:"",
                seatErrorShowStatus:false,
                data: []
            })

            this.$nextTick(() => {
                this.ticketSortIndex.push(id-1)
                this.initColorPick(colorId, color)
                this.settingInputPriceCheackAll()
            })
        },
        insertSettingSeatType:function(index){
            let settingSeatData = this.settingSeatData
            let data = []
               
            settingSeatData[index].data.push({
                ticketName: "",
                // ticketPrice: 0,
                ticketPrice: "0", //task 17
                ticketEarlyBird: "",
                ticketNormal: "",
                ticketOnSite: "",
                ticketEarlyBirdId: "0",
                ticketNormalId: "0",
                ticketOnSiteId: "0",
                ticketStatus: "I",
                ticketCode:"0",
                ticketErrorMsn:"",
                ticketErrorStatus:false,
                ticketNameErrorStatus:false,//STS 2021/07/27 Task 38
            })
         
            let id =  settingSeatData[index].data.length - 1
            let selectId = '#specSelect-'+index+'-'+id
           
            this.initSelectOption(selectId)
            this.settingSeatData = settingSeatData
            
            this.settingInputPriceCheackAll()
        },
        settingSeatTitleChange:function(id, event){
            let settingSeatData = this.settingSeatData
            settingSeatData[id].seatName =  event.target.value
            this.settingSeatData = settingSeatData
           
            if( this.settingSeatData[id].seatStatus !== "I"){
                this.settingSeatData[id].seatStatus = "U"
            }
       
            //this.settingInputPriceCheack(id)
            this.settingInputPriceCheackAll()
            seatSetting.clearTicketTypeTitle(id, "ticketSetting",  event.target.value)
        },
        settingSeatFreeCheackChange:function(id, event, colorId){
            let settingSeatData = this.settingSeatData
            settingSeatData[id].seatFree = event.target.checked
            settingSeatData[id].seatNextSeat = false
            this.settingSeatData = settingSeatData

            if( this.settingSeatData[id].seatStatus !== "I"){
                this.settingSeatData[id].seatStatus = "U"
            }
            this.settingInputPriceCheackAll()
        },
        seatOnNext:function(id, event){
            let settingSeatData = this.settingSeatData
            settingSeatData[id].seatNextSeat = event.target.checked

            if(settingSeatData[id].seatFree){
                settingSeatData[id].seatTotal = 0
            }

            settingSeatData[id].seatFree = false
            this.settingSeatData = settingSeatData

            if( this.settingSeatData[id].seatStatus !== "I"){
                this.settingSeatData[id].seatStatus = "U"
            }
            this.settingInputPriceCheackAll()
        },
        selectionCheackChange:function(event){
            this.settingSeatData.selection = event.target.checked
        },
        settingSeatTitleClear:function(id){
            let settingSeatData = this.settingSeatData
            settingSeatData[id].seatName =  ""
            this.settingSeatData = settingSeatData
        },
        inputSelectRuleCheack:function(){
            let allFreeSeatTittle = document.getElementsByClassName('select-ticket-warn')
            let hadWarn = false

            for(let col of allFreeSeatTittle) {
                if(col.style.display === "block"){
                    hadWarn = true
                    break
                }
            }
            
            ticketSetWarn(this.typeTicketSetting)
        },
        inputSelectSeatDataCheack:function(stringLen, warnId, blockId, id){
            let parentCol = document.getElementById(blockId)
            let ticketCol = parentCol.getElementsByClassName('select-seat-tittle')
            let colNum =  ticketCol.length
            let warnText = document.getElementById(warnId)
            let cheackPass = true
            let warnContent = ''
            let colStar = 0
           
            if(stringLen ===  0 && colNum >= 2){
                cheackPass = false
                warnContent += '券種名未入力(926)'
            }

            if(cheackPass){
                warnText.style.display = 'none'
            }else{
                warnText.textContent = warnContent 
                warnText.style.display = 'block'
            }

            this.inputSelectRuleCheack()
        },
        specSeatSelectChange:function(event){
            let optionId = '#'+event.id
            let option = this.selectOption
            let optionData = document.getElementById(event.id)
            let colId = optionData.getAttribute('data-id')
            let id = optionData.getAttribute('data-index')
            let isNot = true

            for(let num = 0; num<option.length; num++){
                if(option[num].id === event.id){
                    option[num].id = 0
                }
                if(option[num].value === event.value){
                   isNot = false
                }
            }

            this.selectOption = option

            if(isNot){
                this.selectOption.push({
                    id: event.id,
                    value: event.value,
                })
            }
           
            this.specSeatData[id].ticketName = event.value

            if(this.specSeatData[id].ticketStatus !== "I"){
                this.specSeatData[id].ticketStatus = "U"
            }

            seatSetting.clearTicketTypeTitle(id, "ssd",  event.value)
            this.settingInputPriceCheackAll()
        },
        settingSeatSelectChange:function(event){
            let optionId = '#'+event.id
            let option = this.selectOption
            let optionData = document.getElementById(event.id)
            let colId = optionData.getAttribute('data-id')
            let id = optionData.getAttribute('data-index')
            let isNot = true

            for(let num = 0; num<option.length; num++){
                if(option[num].id === event.id){
                    option[num].id = 0
                }
                if(option[num].value === event.value){
                   isNot = false
                }
            }

            this.selectOption = option

            if(isNot){
                this.selectOption.push({
                    id: event.id,
                    value: event.value,
                })
            }
           
            this.settingSeatData[id].data[colId].ticketName = event.value
            this.settingSeatData[id].data[colId].ticketEarlyBird = false
            this.settingSeatData[id].data[colId].ticketNormal = false
            this.settingSeatData[id].data[colId].ticketOnSite = false
            this.settingInputPriceCheack(id)

            this.settingInputPriceCheackAll()
            //this.settingSeatInputChange(id, colId, event)
        },
        nomSeatSelectChange:function(event){
            let optionId = '#'+event.id
            let option = this.selectOption
            let optionData = document.getElementById(event.id)
            let id = optionData.getAttribute('data-index')
            let optionCheack = true
            let freeTicketAll = document.getElementById('freeTicket')
            let isNot = true

            for(let num = 0; num<option.length; num++){
                if(option[num].id === event.id){
                    option[num].id = 0
                }
                if(option[num].value === event.value){
                   isNot = false
                }
            }

            this.selectOption = option

            if(isNot){
                this.selectOption.push({
                    id: event.id,
                    value: event.value,
                })
            }

            this.freeSeatData[id].ticketName = event.value
            if(this.freeSeatData[id].ticketStatus !== 'I'){
                this.freeSeatData[id].ticketStatus = 'U'
            }

            this.freeSeatData[id].ticketName = event.value
            this.freeSeatData[id].ticketEarlyBird = false
            this.freeSeatData[id].ticketNormal = false
            this.freeSeatData[id].ticketOnSite = false
            this.updateTicketSettingData()
            this.cheackFreeSeatData()
            //this.settingSeatInputChange(id, colId, event)
        },
        settingSeatInputChange:function(id, colId, event){
            let settingSeatData = this.settingSeatData
            let warnId = 'ticketWarn' + id + colId
            let blockId = 'selectSeatBlock' + id
        },
        clearSettingSeatInput:function(id, colId, event){
            let settingSeatData = this.settingSeatData
            settingSeatData[id].data[colId].ticketName  = ""
            document.getElementById(event).value = ""
            this.settingSeatData = settingSeatData
        },
        removeRowSettingSeat:function(id, colId){
            let settingSeatData = this.settingSeatData
           
            if(this.settingSeatData[id].ticketCode !== '0' && this.settingSeatData[id].ticketCode !== null && typeof(this.settingSeatData[id].ticketCode) !== "undefined"){
                this.settingSeatData[id].data[colId].ticketEarlyBird = false
                this.settingSeatData[id].data[colId].ticketNormal = false
                this.settingSeatData[id].data[colId]. ticketOnSite = false
                this.settingSeatData[id].data[colId].ticketStatus = "D"
            }else{
                this.settingSeatData[id].data[colId].ticketStatus = "D"
            }

            this.settingSeatData = settingSeatData

            this.$nextTick(() => {
                this.settingInputPriceCheackAll()
            });
        },
        removeRowAllSettingSeat:function(id){
            if(this.settingSeatData[id].ticketCode !== '0' && this.settingSeatData[id].ticketCode !== null && typeof(this.settingSeatData[id].ticketCode) !== "undefined"){
                this.settingSeatData[id].seatStatus = "D"
                seatSetting.clearTicketType(id, 'ticketSetting')
            }else{
                this.settingSeatData[id].seatStatus = "D"
                seatSetting.clearTicketType(id, 'ticketSetting')
            }
            
            this.settingInputPriceCheackAll()
        },
        settingInputPriceCheack:function(id){
            let parentCol       = this.settingSeatData[id].data.length
            let seatData        = this.settingSeatData[id].data 
            let errorMsg        = "<?php echo e(trans('events.S_ErrMsgeTitle')); ?>"
            let status          = false
            let errorStattus    = false
            let sejErrorStattus = false
            let errorNum        = ''
            let sejErrorNum = '';
            let seatId          = 0
            let re = new RegExp(`[^\u4E00-\u9FFF\uFF00-\uFF65\uFF9E-\uFFEF\u3000-\u30FC]{21}`);
            let re2 = new RegExp(`[\-]{2}`); //STS 2021/07/27 Task 38

            if(this.settingSeatData[id].seatStatus != 'D'){

                this.settingSeatData[id].seatErrorShowMsn =  ""
                this.settingSeatData[id].seatErrorShowStatus = false

                //檢查票別種稱不為空
                if(this.settingSeatData[id].seatName.trim().length === 0){
                    this.settingSeatData[id].seatErrorShowMsn =  "<?php echo e(trans('events.S_ErrMsge_07')); ?>"
                    this.settingSeatData[id].seatErrorShowStatus = true
                }

                if(re.test(this.settingSeatData[id].seatName)){
                    this.settingSeatData[id].seatErrorShowMsn =  "席種名は半角文字・半角スペースを21文字以上続けないでください。"
                    this.settingSeatData[id].seatErrorShowStatus = true
                }
                //STS 2021/07/27 Task 38 start
                if(re2.test(this.settingSeatData[id].seatName)){
                    this.settingSeatData[id].seatErrorShowMsn =  "半角ハイフンを2つ以上連続けないでください。"
                    this.settingSeatData[id].seatErrorShowStatus = true    
                    if (this.saleType == 1 && !this.addButtonErrorStatus){
                        status = true
                        this.addButtonErrorStatus = true
                    }     
                    
                }
                
                //STS 2021/07/27 Task 38 end

                //檢查自由與指定席位名稱不能相同
                if(this.settingSeatData[id].seatName.trim().length > 0){
                    let hasErroe = false
                    this.settingSeatData.forEach((item, index, arr) => {
                        if(id != index && arr[id].seatName == arr[index].seatName && arr[index].seatStatus != 'D'){
                            hasErroe = true
                        }
                    });
                    if(hasErroe){
                        this.settingSeatData[id].seatErrorShowMsn =  "席種名が重複しています。"
                        this.settingSeatData[id].seatErrorShowStatus = true
                    }
                }

                //檢查票別名稱不為空
                for(let n=0; n<parentCol; n++){
                    if(seatData[n].ticketStatus != 'D'){
                        seatId++
                    }

                    if(seatData[n].ticketName.trim().length > 0 || seatData[n].ticketStatus == 'D'){
                        seatData[n].ticketErrorMsn      = ''
                        seatData[n].ticketNameErrorStatus   = false
                    }
                     // STS 2021/06/01 券種名がブランクでもエラーメッセージを表示しないようにしてください
                    // else{
                        // if (!checkMsg) {
                        //     seatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_01')); ?>"
                        //     seatData[n].ticketNameErrorStatus = true
                        //     errorStattus && (errorNum += "、")
                        //     errorNum  += seatId

                        //     errorStattus    = true
                        //     status          = true
                        // } 
                        // checkMsg = false
                    // }
                    // STS - 2021/07/27 Task 38 Start
                    // if(re.test(seatData[n].ticketName) && seatData[n].ticketStatus != 'D'){
                    //     seatData[n].ticketErrorMsn += "券種名は半角文字・半角スペースを21文字以上続けないでください。";
                    //     seatData[n].ticketNameErrorStatus = true
                    //     sejErrorStattus && (sejErrorNum += "、")
                    //     sejErrorNum  += seatId
                    //     sejErrorStattus  = true
                    // }
                    if(seatData[n].ticketStatus != 'D'){
                        if(re.test(seatData[n].ticketName)){
                            sejErrorStattus && (sejErrorNum += "、")
                            sejErrorNum  += seatId
                            sejErrorStattus  = true
                        }
                         if(re2.test(seatData[n].ticketName))
                         {
                            seatData[n].ticketErrorMsn += "券種名は半角文字・半角スペースを21文字以上続けないでください。";
                            seatData[n].ticketNameErrorStatus = true
                            if(this.saleType == 1) {
                                status = true
                                this.addButtonErrorStatus = true
                            }
                         }
                         else {
                            seatData[n].ticketNameErrorStatus = false
                          
                         }
                        
                    }
                    
                     // STS - 2021/07/27 Task 38 End
                }
               
                if(errorStattus){
                    errorMsg && (errorMsg += "、");
                    errorMsg += "<?php echo e(trans('events.S_ErrMsge_01')); ?>" + `：<?php echo e(trans('events.S_ErrNo')); ?>(${errorNum})`; 
                }
                if(sejErrorStattus){
                    errorMsg && (errorMsg += "、");
                    errorMsg += `券種名は半角文字・半角スペースを21文字以上続けないでください。：<?php echo e(trans('events.S_ErrNo')); ?>(${sejErrorNum})` 
                }

                //票價不得低於零
                errorStattus = false
                errorNum     = ''
                seatId       = 0

                 //STS - 2021/06/11 - Comment - START
                // for(let n=0; n<parentCol; n++){
                //     if(seatData[n].ticketStatus != 'D'){
                //         seatId++
                //     }

                //     if(seatData[n].ticketPrice >= 0 || seatData[n].ticketStatus == 'D'){
                //         seatData[n].ticketErrorMsn    = ''
                //         seatData[n].ticketErrorStatus = false
                //     }else{
                //         seatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_02')); ?>"
                //         seatData[n].ticketErrorStatus = true
                //         errorStattus && (errorNum += "、")
                //         errorNum  += seatId

                //         errorStattus    = true
                //         status          = true
                //     }
                // }
                //STS - 2021/06/11 - Comment - END

                // STS - 2021/06/11 - Task 17 START
                for (let n = 0; n < parentCol; n++) {
                    if (seatData[n].ticketStatus != 'D') {
                        seatId++
                    }
                    // if (this.saleType != 1 || seatData[n].ticketStatus == 'D' ||  && seatData[n].ticketPrice >= 0) {
                        //STS 2021/07/28 task 38
                    if (this.saleType != 1 || seatData[n].ticketStatus == 'D' || seatData[n].ticketPrice.toString().length > 0 && seatData[n].ticketPrice >= 0) {
                        seatData[n].ticketErrorMsn = ''
                        seatData[n].ticketErrorStatus = false
                    } 
                    else {
                        seatData[n].ticketErrorMsn = "<?php echo e(trans('events.S_ErrMsge_02')); ?>"
                        seatData[n].ticketErrorStatus = true
                        errorStattus && (errorNum += "、")
                        errorNum += seatId

                        errorStattus = true
                        status = true
                    }
                }
                // STS - 2021/06/11 - Task 17 END
                
                
                if(errorStattus){
                    errorMsg && (errorMsg += "、");
                    errorMsg += "<?php echo e(trans('events.S_ErrMsge_02')); ?>" + `：<?php echo e(trans('events.S_ErrNo')); ?>(${errorNum})` 
                }

                //相同票別名稱票價不得相同
                errorStattus = false

                for(let n=0; n<parentCol; n++){
                    for(let m=n; m<parentCol; m++){
                        // STS 2021/06/09 : task 10 parseInt ticketPrice START
                    let ticketPriceN = parseInt(seatData[n].ticketPrice) 
                    let ticketPriceM = parseInt(seatData[m].ticketPrice)
                        if(
                            n !== m && 
                            seatData[n].ticketName == seatData[m].ticketName && 
                            // seatData[n].ticketPrice == seatData[m].ticketPrice &&
                            ticketPriceN === ticketPriceM &&
                        // STS 2021/06/09 : task 10 parseInt ticketPrice END
                            seatData[n].ticketStatus !== 'D' &&
                            seatData[m].ticketStatus !== 'D' &&
                            seatData[n].ticketPrice > 0
                        ){
                            seatData[n].ticketErrorMsn = "料金重複(927)"
                            seatData[n].ticketErrorStatus = true
                            seatData[m].ticketErrorMsn = "料金重複(927)"
                            seatData[m].ticketErrorStatus = true
                        
                            errorStattus    = true
                            status          = true
                        }
                    }
                }
                
                if(errorStattus){
                    errorMsg && (errorMsg += "、");
                    errorMsg += "<?php echo e(trans('events.S_ErrMsge_03')); ?>"
                }
            }

            
            this.settingSeatData[id].seatErrorMsn = errorMsg
            this.settingSeatData[id].seatErrorStatus = status
            this.settingSeatData[id].data = seatData
            //this.settingDataError = status
           
            return status
        },
        settingInputPrice:function(id, colId, event){
            let settingSeatData = this.settingSeatData
            let selectSeatBlock = 'selectSeatBlock' +  id
            let parentBlock = document.getElementById(selectSeatBlock)
            let parentCol = parentBlock.getElementsByClassName('select-seat-tittle').length
            settingSeatData[id].data[colId].ticketPrice = event.target.value
            this.settingSeatData = settingSeatData
          
            this.settingInputPriceCheack(id)
         
            if( this.settingSeatData[id].data[colId].ticketStatus !== "I"){
                this.settingSeatData[id].data[colId].ticketStatus = "U"
            }

            this.settingInputPriceCheackAll()
        },
        allTicketSettingCheackBoxRule:function(id){
            var cheackType = ['ticketEarlyBird', 'ticketNormal', 'ticketOnSite']

            for(let cheackTittle of cheackType){
                this.ticketSettingCheackBox(id, cheackTittle)
            }
        },
        ticketSettingCheackBox:function(id, colId, cheackType){
            let ticketData = this.settingSeatData[id].data
            let cheackTittle =  this.settingSeatData[id].data[colId].ticketName
            let starNum = 0
        
            // if(cheackTittle){ STS 2021/06/01 check duplicate
                for(let event of ticketData){ 
                    if(colId !==starNum && cheackTittle === event.ticketName){
                        eval('ticketData['+starNum+'].'+cheackType+'= false') 
                    }
                    starNum++
                }
            // }
        },
        ticketEarlyBirdSettingSeat:function(id, colId, event){
            this.settingSeatData[id].data[colId].ticketEarlyBird = event.target.checked
            if( this.settingSeatData[id].data[colId].ticketStatus !== "I"){
                this.settingSeatData[id].data[colId].ticketStatus = "U"
            }

            // this.settingSeatData[id].data[colId].ticketOnSite = false

            this.ticketSettingCheackBox(id, colId, 'ticketEarlyBird')
        },
        ticketNormalSettingSeat:function(id, colId, event){
            this.settingSeatData[id].data[colId].ticketNormal = event.target.checked
            if( this.settingSeatData[id].data[colId].ticketStatus !== "I"){
                this.settingSeatData[id].data[colId].ticketStatus = "U"
            }

            // this.settingSeatData[id].data[colId].ticketOnSite = false

            this.ticketSettingCheackBox(id, colId, 'ticketNormal')
        },
        ticketOnSiteSettingSeat:function(id, colId, event){
            this.settingSeatData[id].data[colId].ticketOnSite = event.target.checked
            if( this.settingSeatData[id].data[colId].ticketStatus !== "I"){
                this.settingSeatData[id].data[colId].ticketStatus = "U"
            }
           
            // this.settingSeatData[id].data[colId].ticketEarlyBird = false
            // this.settingSeatData[id].data[colId].ticketNormal = false
          
            this.ticketSettingCheackBox(id, colId, 'ticketOnSite')
        },
        updateTicketSettingData:function(){      
            console.info('updateTicketSettingData')
            let Qty = 0
            let settingData = {}

            if(this.typeTicketSetting === 'freeSeat'){
                if(this.freeSeatStatus !== 'I'){
                    this.freeSeatStatus = 'U'
                }
                settingData = this.freeSeatData
                Qty = this.freeSeatQty
                settingData = {
                    data: this.freeSeatData,
                    seatName: this.freeSeatTicketName,
                    seatStatus: this.freeSeatStatus,
                    sdbid: this.freeSdbid,
                    seatid: this.freeSeatid,
                    ticketEarlyBird: this.ticketEarlyBird,
                    ticketNormal: this.ticketNormal,
                    ticketOnSite: this.ticketOnSite,
                    seatQty: Qty,
                    respectiveData: this.freeRespectiveData,
                }
            }else{
                settingData = this.settingSeatData
            }

               
            let data = {
                settingType: this.typeTicketSetting,
                seatQty: Qty,
                // seatStatus: this.freeSeatStatus,
                seatStatus: settingData.seatStatus,
                seatid: this.freeSeatid,
                selection: this.selection,
                data: settingData
            }
          
            sessionStorage.setItem("ticketSetting",JSON.stringify(data))
        },
        updateSpecTicketSettingData:function(){      
            let Qty = 0
            let settingData = {}

            settingData = this.specSeatData
       
            let data = {
                settingType: 'spec',
                data: settingData
            }
           
            sessionStorage.setItem("specTicketSetting",JSON.stringify(data))
        },
        cheackStorageData:function(){
            let json = sessionStorage.getItem("ticketSetting")
            let data = ''
            let ticketSortIndex = []
            let specSeatData = JSON.parse(sessionStorage.getItem("specTicketSetting"))
            this.specSeatData = specSeatData['data']
            this.selectOption = JSON.parse(sessionStorage.getItem("ticketOption"))
           
            if(typeof( specSeatData['data'] ) === 'object'){
                let transData = []
                $.each(specSeatData['data'], function (key, val) {
                    val.ticketErrorMsn          = ""
                    val.ticketErrorStatus       = false
                    val.ticketNameErrorStatus   = false

                    transData.push(val)
                });
                this.specSeatData = transData
            }
            
            if(!this.selectOption){
                this.selectOption = []
                this.selectOption.push({
                    id: 0,
                    value: '預定',
                })
            }
            data = JSON.parse(json)
           
            if(json){
                this.typeTicketSetting = data['settingType']
                if(data.settingType === 'freeSeat'){
                    let freeSeatData = data.data.data

                    this.freeSeatTicketName = data.data.seatName
                    this.freeSeatQty        = data.seatQty
                    this.freeSeatStatus     = data.data.seatStatus 
                    this.freeSdbid         = data.data.sdbid || 0
                    this.freeSeatid         = data.data.seatId || 0
                    this.freeRespectiveData =  data.data.respectiveData || []

                    //資料結構修改 檢查 【ticketErrorMsn & ticketErrorStatus & ticketNameErrorStatus】新增
                    for(let n=0; n<freeSeatData.length; n++){
                        freeSeatData[n].ticketErrorMsn        = ""
                        freeSeatData[n].ticketErrorStatus     = false
                        freeSeatData[n].ticketNameErrorStatus = false

                        if(freeSeatData[n].ticketName.trim().length < 0){
                            freeSeatData[n].ticketNameErrorStatus = true
                        }
                    } 
                    //資料結構修改 檢查

                    this.freeSeatData = freeSeatData
                }else if(data.settingType === 'selectSeat'){
                    let ticket = []
                    for (let ticketNum in  data['data']) {
                        ticket.push(data['data'][ticketNum])
                    }
        
                    //資料結構修改 檢查 【ticketErrorMsn & ticketErrorStatus & ticketNameErrorStatus】新增
                    for(let n=0; n<ticket.length; n++){
                        ticket[n].seatErrorShowMsn = ""
                        ticket[n].seatErrorShowStatus = false
                        ticket[n].seatErrorMsn = ""
                        ticket[n].seatErrorStatus = false
                        ticket[n].seatSettingErrorMsn = ''
                        ticket[n].seatSettingErrorStatus = false

                        if(ticket[n].seatStatus !== 'D'){
                            ticketSortIndex[ticket[n].seatid-1] = n
                        }
                        //STS 2021/07/27 Task 38 Check length
                        // for(let m=0; m<ticket[n].data.length; m++){ 
                        //     ticket[n].data[m].ticketErrorMsn        = ""
                        //     ticket[n].data[m].ticketErrorStatus     = false
                        //     ticket[n].data[m].ticketNameErrorStatus = false

                        //     if(ticket[n].data[m].ticketName.trim().length < 0){
                        //         ticket[n].data[m].ticketNameErrorStatus = true
                        //     }
                        // }
                        //STS 2021/07/27 Task 38 Check length end
                    } 
                    // 資料結構修改 檢查

                    this.ticketSortIndex = ticketSortIndex
                    this.settingSeatData = ticket 
                    this.selection = data['selection']
                }
            }
        },
    },
    mounted(){
            <?php if($eventData["status"] === 'edit' || count($errors) > 0): ?>
                sessionStorage.setItem('ticketSetting','<?php echo addslashes($eventData["ticketSetting"]); ?>')
                sessionStorage.setItem('specTicketSetting','<?php echo addslashes($eventData["specTicketSetting"]); ?>')
                sessionStorage.setItem('ticketOption','<?php echo addslashes($eventData["ticketOption"]); ?>')
                let id = this.settingSeatData.length + 1
                let perfomanceStatus = parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10)
                
                this.settingSeatData.push({
                    seatName: "",
                    seatStatus: "I",
                    seatFree: false,
                    seatNextSeat: false,
                    seatColor: '',
                    seatid: id,
                    seatCode: "0",
                    seatTotal: 0,
                    seatSettingErrorMsn:"",
                    seatSettingErrorStatus:false,
                    seatErrorShowMsn: "",
                    seatErrorShowStatus: false,
                    data: []
                })

                this.cheackStorageData()
            <?php else: ?>
                let id = this.settingSeatData.length + 1
                let perfomanceStatus = -1

                this.settingSeatData.push({
                        seatName: "",
                        seatStatus: "I",
                        seatFree: false,
                        seatNextSeat: false,
                        seatColor: '',
                        seatid: id,
                        seatCode: "0",
                        seatTotal: 0,
                        seatSettingErrorMsn:"",
                        seatSettingErrorStatus:false,
                        seatErrorShowMsn: "",
                        seatErrorShowStatus: false,
                        data: []
                    })
            <?php endif; ?>
           
        if(timeCourse.settingRadio == "spec"){
            this.ticketOnSiteShow = false
        }

        this.checkResult = {
            freeSeatQty : { 
                status:false,
                msn:''
            },
        }
       
        this.statucControl.push({
            basis: [7, 8].includes(perfomanceStatus), 
            base_kbn: [7, 8].includes(perfomanceStatus),
            seat_class_kbn: [4, 5, 6, 7, 8].includes(perfomanceStatus),
            stock_limit: [4, 5, 6, 7, 8].includes(perfomanceStatus), 
            seat_class_name: [7, 8].includes(perfomanceStatus),
            seat_class_color: [7, 8].includes(perfomanceStatus), 
            free_seat_flg: [4, 5, 6, 7, 8].includes(perfomanceStatus),
            next_seat_flg: [7, 8].includes(perfomanceStatus), 
            ticket_class_name: [7, 8].includes(perfomanceStatus),
            price: [7, 8].includes(perfomanceStatus), 
            ticket_sales_kbn: [7, 8].includes(perfomanceStatus), 
            ticket_del_kbn: [4, 5, 6, 7, 8].includes(perfomanceStatus), 
            reserve_name: [7, 8].includes(perfomanceStatus),
            reserve_symbol: [7, 8].includes(perfomanceStatus), 
            reserve_color: [7, 8].includes(perfomanceStatus), 
        })

        if(this.statucControl[0].basis){
            this.colorButtonDis = 'pointer-events : none'
        }

        this.settingInputPriceCheackAll()
    }
    
});

</script>
<?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/event/editLayout/ticketSetting.blade.php ENDPATH**/ ?>