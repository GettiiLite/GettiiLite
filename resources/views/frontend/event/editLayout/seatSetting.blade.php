<div id="seatSetting"> 
    <div class="row form-horizontal">
        <!-- col -->
        <div class="col-md-12">
        <!-- callout info -->
        <div class="callout callout-info mb-5">
            <h4></h4>
            {!! trans('events.S_seatMainDesc') !!}
            <p class="pl-20"><small>{!! trans('events.S_seatMainMemo') !!}</small></p>
        </div>
        <!-- /.callout info -->
            <!-- BOX 1 -->
            <div class="box no-border"  v-show="!statucControl[0].excel">
                <div class="box-header with-border-non" data-widget="collapse"> 
                    <h3 class="box-title">@{{ (basisSetting.hallName)?basisSetting.hallName:basisSetting.locationName }} - {{ trans('events.S_VenueSetting') }}</h3>
                    <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool">
                        <i class="fa fa-minus"></i>
                    </button>
                    </div>
                </div>
                <!---/.box-header--->
                <div class="box-body">
                    <div class="col-md-12">
                      <div id="uploaderror"></div>
                        <div class="upload-wrapper">
                            <div class="col-md-4">
                                <div class="upload-btn-wrapper">
                                    <button class="btn btn-block btn-success btn-upload-seat">
                                        {{ trans('events.S_updateSeatMapBtn') }}
                                    </button> 
                                    <input id="flieMap" name="flieMap" type="file" id="imgInp" @change="reconfirm()" :disabled="statucControl[0].excel">
                                </div>
                                <!--<small> 
                                    {!! trans('events.S_createFileDesc') !!}
                                </small>-->
                            </div>

                            {{-- Disable at TW ph1 --}}
                            {{-- <div class="">
                                <div class="col-md-4">
                                    <div class="form-checkbox">
                                    <label class="control marginrow control--checkbox">
                                        <input type="checkbox" v-model="createSeat" :disabled="statucControl[0].excel"> {{ trans('events.S_commissionSeatMap') }}
                                        <div class="control__indicator"></div>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-checkbox">
                                    <label class="control marginrow control--checkbox">
                                        <input type="checkbox" v-model="createSite" :disabled="statucControl[0].excel"> {{ trans('events.S_commissionHallPic') }}
                                        <div class="control__indicator"></div>
                                    </label>
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>

                {{-- <div class="box-footer">
                        {{ trans('events.S_commissionDesc') }}
                </div> --}}

            </div>
            <!-- /.BOX 1-->
            <!-- BOX 2 - box-title + form group -->
            <div class="box no-border">
                <div class="box-header with-border-non" data-widget="collapse">
                    <div class="row form-horizontal box-title-block">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-black">{{ trans('events.S_SeatTotal') }}</label>
                            <div class="col-sm-8">
                                <input id="seatTotal" class="form-control" type="text" v-model="settingTotal" readonly>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-5 control-label text-black">{{ trans('events.S_SeatUnSet') }}</label>
                            <div class="col-sm-7">
                                <input id="seatUnsetTotal" class="form-control" type="text" v-model="isSettingTotal" readonly>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-5 control-label text-black">{{ trans('events.S_freeSeatTotal') }}</label>
                            <div class="col-sm-7">
                                <input id="freeSeatTotal" class="form-control" type="text" v-model="freeTicketTotal" readonly>
                                <span v-show="checkResult['freeTicketTotal']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> @{{ checkResult['freeTicketTotal']['msn'] }}</span>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    </div>
                    <!-- /.col -->
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!---/.box-header--->
                    <div class="box-body"><div class="row">
                        <!-- 指定席 ＋ 自由席  -->
                        <div class="card-wrap">
                            <template v-for="(data, index) in ticketSetting.settingSeatData">
                                <div class="col-lg col-md-3 col-sm-4 mb-4" v-if="!data.seatFree && data.seatStatus !== 'D'">
                                    <div class="stats-small card card-small" :style="{backgroundColor:data.seatColor}">
                                        <div class="card-body ">
                                            <div class="flex-column m-auto">
                                                <div class="stats-small__title text-left">
                                                    <div class="stats-small__label text-uppercase" :style="{color :seatCardStyle(data.seatColor)}">
                                                        @{{ data.seatName }}
                                                    </div>
                                                    <div class="stats-small__value count my-3"></div>
                                                </div>
                                                <div class="stats-small__form-group">
                                                <div class="stats-small__forms stats-small_forms__1">
                                                    <div class="form-flex stats-small__one w-30" :style="{color :seatCardStyle(data.seatColor)}">🈯︎</div>
                                                    <div class="form-flex w-70" :style="{color :seatCardStyle(data.seatColor)}">
                                                        <input type="" class="form-control in-small" :style="{color :seatCardStyle(data.seatColor)}" id="" :value="(typeof(data.seatTotal) === 'undefined')?'0':data.seatTotal" disabled="disabled">
                                                        {{ trans('events.S_SeatUnit') }}
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-sm-4 mb-4" v-if="data.seatFree && data.seatStatus !== 'D'">
                                    <div class="stats-small stats-small card card-wite card-small">
                                        <div class="card-body">
                                            <div class="flex-column m-auto">
                                                <div class="stats-small__title text-left">
                                                <div class="stats-small__label text-uppercase">
                                                        @{{ data.seatName }}
                                                </div>
                                                <div class="stats-small__value count my-3" >
                                                    <span class="help is-danger" v-show='data.seatSettingErrorStatus'>
                                                        <i class="fas fa-exclamation-circle"></i> @{{ data.seatSettingErrorMsn }}
                                                    </span>

                                                </div>
                                                </div>
                                                <div class="stats-small__form-group">
                                                    <div class="stats-small__forms stats-small_forms__1">
                                                        <div class="form-flex stats-small__one w-30">🈚︎</div> 
                                                        <!--<div class="form-flex w-50">
                                                            <input type="" class="form-control in-small border__blue" id="" placeholder="{{ trans('events.S_feeSeatType') }}" disabled="disabled">
                                                        </div>-->
                                                        <div class="form-flex w-70">
                                                        <input type="number" min="0" class="form-control in-small" id="" :value="(typeof(data.seatTotal) === 'undefined')?'0':data.seatTotal"  @change="freeSeatTotal(index, event)" :disabled="(typeof(statucControl[0].seat_setting) === 'undefined')?false:statucControl[0].seat_setting">
                                                        {{ trans('events.S_SeatUnit') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <!-- /.指定席 ＋ 自由席  -->
                        <!-- 保留席  -->
                        <div class="card-wrap">
                            <template v-for="data in ticketSetting.specSeatData">
                                <div class="col-lg col-md-3 col-sm-4 mb-4" v-if="data.ticketStatus !== 'D'">
                                    <div class="stats-small stats-small card card-wite card-small">
                                        <div class="card-body">
                                            <div class="flex-column m-auto">
                                                <div class="stats-small__title text-left">
                                                    <div class="stats-small__label text-uppercase">@{{ data.ticketName }}</div>
                                                    <div class="stats-small__value stats-roundbox count" :style="{backgroundColor: data.ticketColor}">@{{ data.ticketText }}</div>
                                                </div>
                                                <div class="stats-small__form-group">
                                                    <div class="stats-small__forms stats-small_forms__1">
                                                        <div class="form-flex stats-small__one w-30">
                                                            <span class="stats-small__box">押え</span>
                                                            <!--<input type="" class="form-control in-small border__blue" id="" placeholder="{{ trans('events.S_holdSeatType') }}" readonly>-->
                                                        </div>
                                                        
                                                        <div class="form-flex w-70">
                                                            <input type="" class="form-control in-small" id="" :value="(typeof(data.ticketTotal) === 'undefined')?'0':data.ticketTotal" disabled="disabled">
                                                            {{ trans('events.S_SeatUnit') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="col-lg col-md-3 col-sm-4 mb-4">
                                <button class="stats-small stats-small card card-wite card-small card-more" @click="openShowModel()" v-show="!statucControl[0].reserve_add">  
                                    <div class="card-body">
                                        <div class="flex-column m-auto">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <!-- /.保留席  -->
                    </div>
                </div>
                <!-- /.box-body-->
            </div>
            <!-- /.BOX 2 -->
        </div>
        <!-- /.col -->

        <!-- 樓層場地 -->
        <div class="col-md-12" v-show="tabShow">
            <!--  樓層tab -->
            <ul id="tagControl" class="nav nav-tabs nav-tabs-floor">
                <template v-for="(floor, key, index) in mapData">
                        <li v-bind:class="{active: index == 0 }">
                            <a id="basisInfPage" :href="'#'+key" class="tabs-basic" data-toggle="tab" aria-expanded="true" @click="settingFunction(key)">
                                @{{ key }} 
                            </a>
                        </li>
                </template>
            </ul>
            <div class="tab-content tab-floor-content">
                <!-- BLOCK -->
                <!--<button v-on:click="getMapData()">lalala</button>-->
                <template v-for="(floor, key, index) in mapData">
                    <div class="tab-pane content-width" v-bind:class="{active: index == 0 }" :id="key">
                        <div id="floorSetting-1f">
                            <div class="row form-horizontal">
                                <!-- content-->
                                <div class="col-md-12 floor-content">
                                 <div class="col-md-4 floor-map">
                                    <h5 class="text-center mb-4">{{ trans('events.S_venuePicTitle') }}</h5>
                                    <div class="small-i pl-10">
                                      <div class="tip">
                                       <span><i class="fas fa-info fa-1x fa__thead"></i>
                                      <small>
                                        500px以上の場合、GETTIISでは縮小して表示されます。
                                      </small>
                                    </span>
                                       </div>
                                     </div>
                                    <div class="dr op-image">
                                        <input name="image" type="file" id="" class="dropify floor-img" :data-default-file="floor.imageUrl" data-allowed-file-extensions='["png", "jpeg", "jpg"]' @change="imageUpload" data-max-file-size="2M" :disabled="statucControl[0].thumbnail"/>
                                    </div>
                                </div>
                                <div class="col-md-8 floor-direction">
                                    <h5 class="text-center mb-4">{{ trans('events.S_blockListTitle') }}
                                    {{-- <button type="button"
                                        class="btn btn-xs waves-effect waves-light btn-rounded btn-success pull-right">全座席表示</button> --}}
                                    </h5>
                                    <div class="col-sm-12">
                                        <template v-for="data in floor.blockData">
                                            <div :id="key+data.blockTittle" class="col-sm-2 d-block " v-on:click="seatDataChange(key, data.blockTittle)">
                                                <div class="d-block-name">@{{data.blockTittle}}</div>
                                                <div v-if="data.direction == 1" class="d-block-arrow"><i class="far fa-arrow-alt-circle-up"></i></div>
                                                <div v-else-if="data.direction == 2" class="d-block-arrow"><i class="far fa-arrow-alt-circle-down"></i></div>
                                                <div v-else-if="data.direction == 3" class="d-block-arrow"><i class="far fa-arrow-alt-circle-left"></i></div>
                                                <div v-else class="d-block-arrow"><i class="far fa-arrow-alt-circle-right"></i></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <!---->
                                </div>
                                <!-- content-->
                                <!-- 座席設定-->
                                <div class="col-sm-12">
                                <h5 class="text-center floor-settings-title"  v-show="tableHidden">
                                    {{ trans('events.S_seatSettingTilte') }} - @{{ nowFloor }} @{{ nowBlock }}
                                </h5>
                                <div class="row floor-settings"  v-show="tableHidden">
                                    <div class="col-md-12 d-flex-bt">
                                        <div class="form-group form-d-flex">
                                            <div class="input-group my-colorpicker2 colorpicker-element form-group-flex col-sm-5" v-if="!statucControl[0].seat_setting">
                                                <select id="SeatSettingOption" class="form-control m-r-10" @change="seatUnitChange()" v-model="seatUnit" >
                                                    <template v-for="(data, index) in dataSet">
                                                        <option :value="index" v-if="!data.seatFree && data.status !== 'D'">
                                                            @{{ data.title }}
                                                        </option> 
                                                    </template>    
                                                </select>
                                                <div class="input-group-addon input-group-h35" v-bind:style="{backgroundColor: colorNow}">
                                                    
                                                        @{{ nowText }}
                                                    
                                                </div>
                                            </div>
                                            <label class="d-h-spec" v-show="!statucControl[0].seat_setting">@{{ nowUnitTotal }}{{ trans('events.S_SeatUnit') }}</label>
                                            <!-- /.input group -->
                                            <div class="floor-btn-group" v-show="!statucControl[0].seat_setting">
                                                <button type="button" class="btn btn-info" v-on:click="saveSeatIsSelect()" :disabled="settingBtn">{{ trans('events.S_btnSeatSetup') }}</button>
                                                <button type="button" class="btn btn-inverse" v-on:click="clearSeatIsSelect()">{{ trans('events.S_btnSeatRelease') }}</button>
                                            </div>
                                    </div>
                                        <!-- 方向表示 -->
                                    <div class="col-sm-2 d-block"  v-show="tableHidden">
                                    <div class="d-block-name">@{{ nowBlock }}</div>
                                    <div v-if="mapDirection == 1" class="d-block-arrow"><i class="far fa-arrow-alt-circle-up"></i></div>
                                    <div v-else-if="mapDirection == 2" class="d-block-arrow"><i class="far fa-arrow-alt-circle-down"></i></div>
                                    <div v-else-if="mapDirection == 3" class="d-block-arrow"><i class="far fa-arrow-alt-circle-left"></i></div>
                                    <div v-else class="d-block-arrow"><i class="far fa-arrow-alt-circle-right"></i></div>
                                    </div>
                                        <!-- /．方向表示 -->
                                        <div class="form-group form-d-flex ">
                                            <div class="floor-btn-group">
                                                <button type="button" class="btn btn-default" :disabled="zoomOutBtn" @click="zoomOutTable">{{ trans('events.S_btnZoomin') }}</button>
                                                <button type="button" class="btn btn-default" :disabled="zoomInBtn"  @click="zoomInTable">{{ trans('events.S_btnZoomout') }}</button>
                                            </div>
                                        </div>
                               </div>
                                </div>
                                
                                <!-- /.座席設定-->
                                </div>
                                <!-- /.content-->
                                <!-- 座席位置表 -->
                               <!-- <div class="col-sm-2 d-block"  v-show="tableHidden">
                                    <div class="d-block-name">@{{ nowBlock }}</div>
                                    <div v-if="mapDirection == 1" class="d-block-arrow"><i class="far fa-arrow-alt-circle-up"></i></div>
                                    <div v-else-if="mapDirection == 2" class="d-block-arrow"><i class="far fa-arrow-alt-circle-down"></i></div>
                                    <div v-else-if="mapDirection == 3" class="d-block-arrow"><i class="far fa-arrow-alt-circle-left"></i></div>
                                    <div v-else class="d-block-arrow"><i class="far fa-arrow-alt-circle-right"></i></div>
                                </div>-->
                                <div id="seatMap" class="floor-settings-table" v-show="tableHidden" >
                                       <table id='map-table' class="table table-non-bordered" v-bind:style="[mapStyle]"> 
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th v-for="num in rowLenght">@{{  num  }}</th>
                                            </tr>    
                                        </thead>
                                        <tbody v-html="mapCreate"></tbody>
                                    </table>
                                </div>
                                
                            </div>
                            <!--/.form-horizontal-->
                        </div>
                        <!--/.floorSetting-1f-->
                    </div>
                </template>
                <!-- /.BLOCK -->        
            </div>
            <!--  /.樓層tab -->
        </div>
        <!--  /.樓層場地 -->
    </div>
    <transition name="slide-fade">  
        <div class="modal-mask" v-show='showModal'>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('events.S_setReserved') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-horizontal">
                                <div class="col-md-12">
                                 <!--/.radiobox 1-->
                                    <div class="" v-show="checkResult['specTicket']['status']">
                                        <div class="callout callout-txt-warning ">
                                            <div class="icon">
                                                <i class="fas fa-exclamation-circle fa-lg"></i> 
                                            </div>
                                            <p>@{{ checkResult['specTicket']['msn'] }}</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">{{ trans('events.S_reserveNameTitle') }}</label>
                                        <div class="col-md-10">
                                            <input  name="text" type="text" v-bind:style="{ borderColor: (checkResult['specTicket']['title'])?'#e44e2d':'' }" class="form-control" v-model="specSeatTitle">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <!--修改中-->
                                        <label class="col-md-2 control-label">{{ trans('events.S_reserveSymbol') }}</label>
                                        <div class="col-md-10 form-group-flex">
                                            <div class="w-5">
                                                <input name="text" type="text" v-bind:style="{ borderColor: (checkResult['specTicket']['text'])?'#e44e2d':'' }" maxlength="1" class="form-control" placeholder="" v-model="specSeatText">
                                            </div>
                                            <div class="has-feedback mml-2 w-20">
                                                <div class="colorpick-box">
                                                    <div id="saetSettingColorPick" v-bind:style="{ borderColor: (checkResult['specTicket']['color'])?'#e44e2d':'' }" data-color="#2ECC71" class="colorPickSelector form-control" style="background-color: rgb(46, 204, 113); color: rgb(46, 204, 113);border-width: 2px;">
                                                    </div>
                                                    <!--0513調整-->
                                                    <div class="color-arrow" ><i class="fas fa-caret-down"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" @click="closeDialog()">{{ trans('events.S_cancelBtn') }}</button>
                        <button id="" class="btn btn-inverse" @click="addSpecTicket()">{{ trans('basisInf.S_Apply') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
    <!--需要設定通用版-->
    <div class="modal-mask" v-show="reconfirmDialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-white border-non"></div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <center>
                            <i class="fas fa-exclamation-triangle text-red fa-2x"></i>
                            <h4>{{ trans('events.S_changeSeatMap') }}</h4>
                        </center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default pull-left"  @click="reconfirmResult(false)">{{ trans('events.S_cancelBtn') }}</button>
                    <button class="btn btn-danger" id="PasswordSend"  @click="reconfirmResult(true)">{{ trans('events.S_btnSaveMSG') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax= arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }
    
    const seatSetting = new Vue({
        el: '#seatSetting',
        data: {
            index:0,      
            map : {"x_min":1,"x_max":10,"y_min":1,"y_max":20,"seats":{"1.1":{"x":1,"y":1,"sid":2,"sale":true,"vacant":true},"1.2":{"x":1,"y":2,"sid":3,"sale":true,"vacant":true}},"lines":{"1":1,"2":2}},
            mapDirection: 1,
            venueAreaName: [22,17,23,28,8,13,15,7,26,15],
            qty: 2,
            totalSeat:'',
            unSetting:'',
            colorNow:'',
            seatUnit:'',
            nowUnitTotal:'',
            freeTicketTotal:'',
            settingBtn:true,
            settingBtnOption:true,
            settingBtnSel:true,
            dataSet:[],
            mapData:[],
            ticketData:'',
            freeTicketData:'',
            mapCreate:"",
            nowFloor:"",
            nowBlock:"",
            nowText:"",
            rowLenght: "",
            mapStyle: {
                zoom: '1'
            },
            scale: 1,
            zoomOutBtn: false,
            zoomInBtn: false,
            tableHidden: true,
            settingTotal: 0,
            isSettingTotal: 0,
            createSeat: false,
            createSite: false,
            specSeatTitle: '',
            specSeatText: '',
            specSeatColor: '',
            tabShow: false,
            showModal: false,
            blockChangeNull: false,
            mapstatus: '',
            seat_profile_cd:null,
            statucControl:[],
            blockSelect: '',
            checkResult: [],
            seatError: [],
            reconfirmDialog: false,
        },
        watch: {
            settingBtnSel: function (val) {
                if(this.settingBtnOption || val){
                    this.settingBtn = true
                }
                if(!val && !this.settingBtnOption){
                    this.settingBtn = false
                }
            },
            settingBtnOption: function (val) {
                if(!val && !this.settingBtnSel){
                    this.settingBtn = false
                }
            },
            nowFloor: function(val){
                if(!this.blockChangeNull){
                    this.nowBlock = ""
                }
            },
            nowBlock: function(val){
                if(val == ""){
                    this.tableHidden = false
                }else{
                    this.tableHidden = true
                }
            },
            freeTicketTotal: function(val){
               this.freeTicketTotalRule(val)
            },
            specSeatTitle: function(val){
                let data = {
                    title: this.specSeatTitle,
                    text: this.specSeatText,
                    color: this.specSeatColor,
                }

                this.specTicketValidate(data)
            },
            specSeatText: function(val){
                let data = {
                    title: this.specSeatTitle,
                    text: this.specSeatText,
                    color: this.specSeatColor,
                }

                this.specTicketValidate(data)
            },
            specSeatColor: function(val){
                let data = {
                    title: this.specSeatTitle,
                    text: this.specSeatText,
                    color: this.specSeatColor,
                }

                this.specTicketValidate(data)
            },
        },
        created: function() {

        },
        mounted:function(){
            @if($eventData["status"] === 'edit' || count($errors) > 0)
                sessionStorage.setItem('mapData','{!! (array_key_exists("mapData",$eventData))?addslashes($eventData["mapData"]):null  !!}')
                let mapData = sessionStorage.getItem("mapData")
                let perfomanceStatus = parseInt('{{ $eventData['performanceDispStatus'] }}', 10)


                if(mapData){
                    mapData = JSON.parse(mapData)
                    this.seat_profile_cd = mapData[0]['seat_profile_cd'] || null
                    this.mapstatus = mapData[0]['status'] || ''
                    this.mapData = mapData[0]['data'][0]['mapData']
                    this.createSeat = mapData[0]['data'][0]['createSeat'] || null
                    this.createSite = mapData[0]['data'][0]['createSite'] || null
                    this.settingTotal = mapData[0]['data'][0]['settingTotal'] || null
                    this.isSettingTotal = mapData[0]['data'][0]['isSettingTotal'] || null

                    if(this.mapstatus != ''){
                        this.tabShow = true
                    }
                }

                this.$nextTick(() => {
                    this.countTypeTotal()
                })

            @else
                let perfomanceStatus = -1   
            @endif

            this.checkResult = {
                freeTicketTotal : { 
                    status:false,
                    msn:''
                },
                specTicket : { 
                    status:false,
                    msn:'',
                    title:false,
                    text:false,
                    color:false,
                },
            }
            @if($eventData["transFlg"] > 0)
                this.statucControl.push({
                    excel: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                    thumbnail: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                    reserve_add: [4, 5, 6, 7, 8].includes(perfomanceStatus), 
                    seat_setting: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                })

                if(!SaleInfo.has_sale && perfomanceStatus < 7){
                    this.statucControl[0].reserve_add = false
                }
                if(MapHadStageSeat){
                    this.statucControl[0].seat_setting = true
                }
            @else
                this.statucControl.push({
                    basis: [7, 8].includes(perfomanceStatus),
                    excel: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                    thumbnail: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                    reserve_add: [7, 8].includes(perfomanceStatus), 
                    seat_setting: [4, 5, 6, 7, 8].includes(perfomanceStatus),
                })  
            @endif
        },
        methods: {
            reconfirm: function(){
                if(this.mapstatus != ''){
                    this.reconfirmDialog = true
                }else{
                    this.uploadFile()
                }
            },
            reconfirmResult: function(select){
                if(select){
                    this.uploadFile()
                }
                this.reconfirmDialog = false
            },
            seatCardStyle: function(backgroundColor){
                let color = ['#AE8445','#44C1B4','#67A934','#D96C7D','#8F4AC5','#385AB0','#7A5C2F','#1A747B','#22730A','#B1253B','#60238F','#013573']
                let style = '#3A3A3A'

                if(color.includes(backgroundColor)){
                    style = '#FFFFFF'
                }
                
                return style
            },
            freeTicketTotalRule: function(val = this.freeTicketTotal){
                try {
                    this.checkResult['freeTicketTotal']['status'] = false
                    this.checkResult['freeTicketTotal']['msn'] = ''
                    
                    if(isNaN(val)){
                        throw (new Error('数字で入力ください'))
                    }

                    if(parseInt(val) < 0){
                        throw (new Error('正の整数を入力してください'))
                    }

                    return true
                }catch (e){
                    this.checkResult['freeTicketTotal']['status'] = true
                    this.checkResult['freeTicketTotal']['msn']  = e.message
                    
                    return false
                }
            },
            /**
            * 檢查自由席是否符合規範
            */
            checkTypeSeat: function(){
                let errorStatus = false
                let reNum       =/^[0-9]*$/
                let num         =  /^\d+$/

                tagControl.seatSettingWarning = false

                ticketSetting.settingSeatData.forEach(function(item) {
                    item.seatSettingErrorStatus = false
                    item.seatSettingErrorMsn = ''
                    if(item.seatFree && item.seatStatus != 'D'){
                        try{
                            if(!num.test(item.seatTotal)){
                                throw (new Error('数字で入力ください'))
                            }
                            if(parseInt(item.seatTotal) < 0){
                                throw (new Error('正の整数を入力してください'))
                            }
                        }catch (e){
                            item.seatSettingErrorStatus = true
                            item.seatSettingErrorMsn = e.message
                            errorStatus = true
                            tagControl.seatSettingWarning = true
                        }
                    }
                })
                
                return errorStatus
            },
            freeSeatTotal:function(key, event){
                ticketSetting.settingSeatData[key].seatTotal = event.target.value
                this.checkTypeSeat()
                this.freeSeatCountTotal()
            },
            /**
             * 計算自由席總數
             */
            freeSeatCountTotal:function(){
                let total = 0
                
                ticketSetting.settingSeatData.forEach(function(element) {
                    if(element.seatStatus !== 'D' && element.seatFree){
                        if(element.seatTotal !== ''){
                            total += parseInt(element.seatTotal, 10);
                        }
                    }
                })

                this.freeTicketTotal = total
            },
            getMapSettingData:function(){
                let mapSettingData = []
                let status = this.mapstatus ? this.mapstatus : '';
                if(this.seat_profile_cd == null && status !== 'I')
                {
                    status = '';
                }
                let data = []

                data.push({
                    createSeat: this.createSeat,
                    createSite: this.createSite,
                    settingTotal: this.settingTotal,
                    isSettingTotal: this.isSettingTotal,
                    mapData: this.mapData
                })

                mapSettingData.push({
                    status: status,
                    seat_profile_cd: this.seat_profile_cd,
                    data: data,
                })

                return mapSettingData

            },
            imageUpload:function($event){
                try {
                    let img = $event.target.files[0]
                    if(img.size > 2097152)
                    {
                    return;
                    }
                    const form = new FormData();
                    form.append('file', img);
                    form.append('location', basisSetting.imageLocation)
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '/blockImage/import',
                        type: 'POST',
                        data: form,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(data, textStatus, jqXHR)
                        {
                            seatSetting.mapData[seatSetting.nowFloor]['imageUrl'] = data.url
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            console.log('ERRORS: ' + textStatus)
                        }
                    });
                }catch (error){
                    console.error(error)
                    $event.stopImmediatePropagation();
                }

            },
            zoomOutTable:function(){
                let explorer = navigator.userAgent 
                this.zoomInBtn = false
                this.scale += 0.2

                if(explorer.indexOf("Firefox") >= 0){
                    this.mapStyle = {
                        'transform' : `scale(${parseFloat(this.scale.toFixed(1))})`
                    }
                }else{
                    this.mapStyle.zoom = parseFloat(this.scale.toFixed(1))
                }

                if(parseFloat(this.scale.toFixed(1)) == 1.4){
                    this.zoomOutBtn = true
                }
            },
            zoomInTable:function(){
                let explorer = navigator.userAgent 
                this.zoomOutBtn =  false
                this.scale -= 0.2

                if(explorer.indexOf("Firefox") >= 0){
                    this.mapStyle = {
                        'transform' : `scale(${parseFloat(this.scale.toFixed(1))})`
                    }
                }else{
                    this.mapStyle.zoom = parseFloat(this.scale.toFixed(1))
                }

                if(parseFloat(this.scale.toFixed(1)) == 0.6){
                    this.zoomInBtn = true
                }
            },
            openShowModel:function(){
                this.specSeatTitle = ''
                this.specSeatText = ''
                this.specSeatColor = '#2ECC71'
                this.checkResult['specTicket']['status'] = false
                this.checkResult['specTicket']['msn'] = ''
                this.checkResult['specTicket']['title'] = false
                this.checkResult['specTicket']['text'] = false
                this.checkResult['specTicket']['color'] = false

                this.showModal = true
                document.body.style.overflowY = "hidden";
            },
            closeDialog:function(){
                this.showModal = false
                document.body.style.overflowY = "scroll";
            },
            specTicketValidate:function(data){

                if(data.title.length <=0 && data.text.length <=0 && data.color.length <= 0)
                {
                  return;
                }
                this.checkResult['specTicket']['status'] = false
                this.checkResult['specTicket']['msn'] = ''
                this.checkResult['specTicket']['title'] = false
                this.checkResult['specTicket']['text'] = false
                this.checkResult['specTicket']['color'] = false

                let result = {
                    status: true,
                    errorMsg: '',
                }
                
                let status = true
                let errorMsg = ''

                //保留席名稱 ： 不能空，不能相同
                if(typeof data.title == null || data.title.length <= 0){
                    errorMsg += '{{ trans("events.S_SeatErrMsg_01") }}'
                    status = false
                    this.checkResult['specTicket']['title'] = true
                }else{
                    let valideResult = ticketSetting.specSeatData.some((item, index, arr) => {
                        if(item.ticketStatus == 'D')
                        {
                          return null;
                        }
                        return item.ticketName == data.title.toLowerCase()
                    },data);

                    if(valideResult){
                        errorMsg += '{{ trans("events.S_SeatErrMsg_02") }} '
                        status = false
                        this.checkResult['specTicket']['title'] = true
                    }
                }

                    //保留席字號 ： 不能空，不能相同
                if(typeof data.text == null || data.text.length <= 0){
                    if(!status){
                        errorMsg += ','
                    }
                    errorMsg += '{{ trans("events.S_SeatErrMsg_03") }} '
                    status = false
                    this.checkResult['specTicket']['text'] = true
                }else{
                    
                    
                    let valideResult = ticketSetting.specSeatData.some((item, index, arr) => {
                        if(item.ticketStatus == 'D')
                        {
                          return null;
                        }
                        return item.ticketText == data.text
                    },data);

                    if(valideResult){
                       
                        if(!status){
                            errorMsg += ','
                        }
                        errorMsg += '{{ trans("events.S_SeatErrMsg_04") }}'
                        status = false
                        this.checkResult['specTicket']['text'] = true
                    }
                }

                    //保留席顏色 ： 不能相同
                if(data.color){
                    let valideResult = ticketSetting.specSeatData.some((item, index, arr) => {
                        if(item.ticketStatus == 'D')
                        {
                          return null;
                        }
                        return item.ticketColor == data.color
                    },data);

                    if(valideResult){
                        if(!status){
                            errorMsg += ','
                        }
                        errorMsg += '{{ trans("events.S_SeatErrMsg_05") }}'
                        status = false
                        this.checkResult['specTicket']['color'] = true
                    }
                }
                
                if(!status){
                    this.checkResult['specTicket']['status'] = true
                    this.checkResult['specTicket']['msn'] = errorMsg
                  
                    result.status = status
                    result.errorMsg = errorMsg
                }
                
                return result
            },
            addSpecTicket:function(){

                let data = {
                    title: this.specSeatTitle,
                    text: this.specSeatText,
                    color: this.specSeatColor,
                }

                let validateResult = this.specTicketValidate(data)

                if(validateResult.status){
                    ticketSetting.selectOption.push({
                        id: 0,
                        value: this.specSeatTitle,
                    })

                    if(typeof(ticketSetting.specSeatData) == 'undefined'){
                        ticketSetting.specSeatData = []
                        let id = 0
                        let colId = "#ssd-"+id
                        let selectId = '#ssd-select-'+id

                        ticketSetting.specSeatData.push({
                            tickerId: id,
                            ticketName: this.specSeatTitle,
                            ticketText: this.specSeatText,
                            ticketColor: this.specSeatColor,
                            ticketStatus: "I",
                            ticketCode: "0",
                            ticketTotal: 0,
                        })

                        ticketSetting.initColorPick(colId)
                        ticketSetting.initSelectOption(selectId)
                    }else{
                        let id = ticketSetting.specSeatData.length 
                        let colId = "#ssd-"+id
                        let selectId = '#ssd-select-'+id

                        ticketSetting.specSeatData.push({
                            tickerId: id,
                            ticketName: this.specSeatTitle,
                            ticketText: this.specSeatText,
                            ticketColor: this.specSeatColor,
                            ticketStatus: "I",
                            ticketCode: "0",
                            ticketTotal: 0,
                        })
                        
                        ticketSetting.initColorPick(colId)
                        ticketSetting.initSelectOption(selectId)
                    }

                    this.specSeatTitle = ''
                    this.specSeatText = ''
                    this.specSeatColor = ''
                    this.closeDialog()
                    this.$nextTick(() => {
                        this.seatSettingInf()
                    })
                }
            },
            seatSettingInf:function(){
                //Seatoption
                let seatSettingData = []
                let tickketInf = ticketSetting.settingSeatData
                let specSeatData = ticketSetting.specSeatData
                let num = 0

                if(tickketInf){
                    tickketInf.forEach(function(element) {
                        seatSettingData.push({
                            type: 'ticketSetting',
                            setting: element.seatNextSeat,
                            seatFree: element.seatFree,
                            id: num,
                            title: element.seatName,
                            color: element.seatColor,
                            text: '',
                            status: element.seatStatus,
                            total: 0,
                        })
                        num++
                    })
                }

                num = 0
                if(specSeatData){
                    specSeatData.forEach(function(element) {
                        seatSettingData.push({
                            type: 'specSeat',
                            setting: true,
                            seatFree: false,
                            id: num,
                            title: element.ticketName,
                            color: element.ticketColor,
                            text: element.ticketText,
                            status: element.ticketStatus,
                            total: 0,
                        })
                        num++
                    })
                }

                //計算自由席總數
                this.freeSeatCountTotal()

                this.$nextTick(() => {
                    this.dataSet = seatSettingData
                })
            },
            seatDataChange:function(floor, block, init = false){
                this.blockChangeNull = true
                let mapData = this.mapData[floor]['blockData'][block]
                let xLine = this.mapData[floor]['blockData'][block].x
                let xLineSort = []
                let yLineSort = []
                let yLine  = this.mapData[floor]['blockData'][block].y
                let mapTilteOri = this.mapData[floor]['blockData'][block].line
                let mapTilte = []
                let mapSubTilteOri = this.mapData[floor]['blockData'][block].lineNum
                let mapSubTilte = []
                this.mapDirection = this.mapData[floor].blockData[block].direction
                this.nowFloor = floor
                this.nowBlock = block
                let nowSeatData = this.mapData[floor].blockData[block].seatData
                let x_max = mapData['x_max']
                let x_min = mapData['x_min']
                let y_min = mapData['y_min']
                let y_max = mapData['y_max']
                let mapDraw = ''
                let reservedSid = []
                let num = 0
                let seatDirCN = ''
		        let seatDirTextCN = 0
                
                this.selectSeatClear()
                this.drawSeat()

                this.tableHidden = true
                switch(this.mapDirection) {
                    case 1:
                        mapTilte = []
                        $.each(mapTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(yLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                      
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(yLine, function(index, seat){
                            titleSet[index-starNum] = mapTilte[lineNum]
                            lineNum++
                        });
                       
                        mapSubTilte = titleSet
                        break;
                    case 2:
                        mapTilte = []
                        $.each(mapTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(yLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                      
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(yLine, function(index, seat){
                            titleSet[index-starNum] = mapTilte[lineNum]
                            lineNum++
                        });
                       
                        mapSubTilte = titleSet
                        break;
                    case 3:
                        mapTilte = []
                        $.each(mapSubTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(yLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(yLine, function(index, seat){
                            // titleSet[index-starNum] = mapTilte[lineNum]
                            titleSet[index-starNum] = ""
                            lineNum++
                        });
                       
                        mapSubTilte = titleSet
                        break;
                    case 4:
                        mapTilte = []
                        $.each(mapSubTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(yLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(yLine, function(index, seat){
                            // titleSet[index-starNum] = mapTilte[lineNum]
                            titleSet[index-starNum] = ""
                            lineNum++
                        });
                       
                        mapSubTilte = titleSet
                        break;
                    default:
                        this.rowLenght = (x_max - x_min)+1
                } 

                switch(this.mapDirection) {
                    case 1:
                        mapTilte = []
                        $.each(mapSubTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(xLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                      
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(xLine, function(index, seat){
                            titleSet[index-starNum] = mapTilte[lineNum]
                            lineNum++
                        });
                       
                        // this.rowLenght = titleSet
                        this.rowLenght = 0
                        break;
                    case 2:
                        mapTilte = []
                        $.each(mapSubTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                    
                        $.each(xLine, function(index, data){
                            let num = parseInt(index, 10) + 1
                            if(typeof(xLine[num]) == 'undefined' && Object.keys(xLineSort).length < Object.keys(xLine).length-1){
                                        xLineSort.push(data)
                                        xLineSort.push('')
                            }else{
                                    xLineSort.push(data)
                            }
                        }); 
                        
                        var xLineNum = 0
                        $.each(xLineSort, function(index, data){
                            if(data){   
                                xLineSort[index] = mapTilte[xLineNum]
                                xLineNum++
                            }
                        });  
                        
                        mapTilte = xLineSort                   
                        // this.rowLenght = mapTilte
                        this.rowLenght = 0
                        break;
                    case 3:
                        mapTilte = []
                        $.each(mapTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(xLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                      
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(xLine, function(index, seat){
                            titleSet[index-starNum] = mapTilte[lineNum]
                            lineNum++
                        });
                       
                        this.rowLenght = titleSet
                        break;
                    case 4:
                        mapTilte = []
                        $.each(mapTilteOri, function(index, seat){
                            mapTilte.push(seat)
                        }); 
                        
                        var keys = Object.keys(xLine)
                        var arrayLenght = (parseInt(keys[keys.length-1], 10) - parseInt(keys[0], 10))+1
                        var titleSet = new Array(arrayLenght)
                        var starNum = keys[0]
                       
                      
                        $.each(titleSet, function(index, seat){
                            titleSet[index] = ""
                        });

                        var lineNum = 0
                        $.each(xLine, function(index, seat){
                            titleSet[index-starNum] = mapTilte[lineNum]
                            lineNum++
                        });
                       
                        this.rowLenght = titleSet
                        break;
                    default:
                        this.rowLenght = (x_max - x_min)+1
                } 

                for(let i = y_min; i <=  y_max; i++){
                    let leftTittle = num
                        
                    switch(this.mapDirection) {
                        case 1:
                            leftTittle = (typeof(mapSubTilte) === 'undefined')?'':mapSubTilte[num]
                            seatDirCN = 'rotate-0'
			                seatDirTextCN = 180
                            break;
                        case 2:
                            leftTittle = (typeof(mapSubTilte) === 'undefined')?'':mapSubTilte[num]
                            seatDirCN = 'rotate-180'
                            seatDirTextCN = -180
                            break;
                        case 3:
                            leftTittle = (typeof(mapSubTilte) === 'undefined')?'':mapSubTilte[num]
                            seatDirCN = 'rotate-270'
                            seatDirTextCN = 180
                            break;
                        case 4:
                            leftTittle = (typeof(mapSubTilte) === 'undefined')?'':mapSubTilte[num]
                            seatDirCN = 'rotate-90'
                            seatDirTextCN = -180
                            break;
                        default:
                            this.rowLenght = (x_max - x_min)+1
                    } 

                    mapDraw += '<tr><td>'+leftTittle+'</td>'

                    for(let j = x_min; j <= x_max; j++){
                        let xy =  i+'.'+j
                        let nonseat = xy in mapData.seatData
                        let selectData = nowSeatData[xy]
                        
                        if(!nonseat){
                            mapDraw += '<td><div class="s-seat-line">　</div></td>'
                        }else{
                            let color = '#ffffff'
                            
                            if(typeof(selectData['typeData']) !== 'undefined' && typeof(selectData['typeData']['color']) !== 'undefined'){
                                color = selectData['typeData'].color
                            }

                            if(typeof(selectData['typeData']) !== 'undefined'){
                               
                                if(selectData['typeData']['type'] == 'specSeat'){
                                    mapDraw += '<td class="cliseat"  id="seat_'+j+'_'+i+'" data-position="'+selectData.rowname+''+selectData.number+'" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="unSelect" data-status="unSelect">\
                                                    <div class="s-seat-line">\
                                                    <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                        <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 55 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                         <g id="Gettii-Lite" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                          <g id="Gettii-Lite_Seating-Plan_Template" transform="translate(-302.000000, -334.000000)" fill-rule="nonzero" fill="#FFFFFF" stroke="#FFA800" stroke-width="2">\
                                                           <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                            <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                            <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                           </g>\
                                                          </g>\
                                                         </g>\
                                                            <text id=""y="45%" x="50%" fill="'+color+'" text-anchor="middle" dominant-baseline="middle" font-weight="600" font-size="25" font-family="Microsoft JhengHei">\
                                                                        <tspan>'+selectData['typeData']['text']+'</tspan>\
                                                                    </text>\
                                                         </svg>\
                                                    </div>\
                                                </td>'
                                }else{
                                    mapDraw += '<td class="cliseat" id="seat_'+j+'_'+i+'"  data-position="'+selectData.rowname+''+selectData.number+'" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="unSelect" data-status="unSelect">\
                                                <div class="s-seat-line">\
                                                <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                    <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                       <g id="seat" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                         <g id="Seat'+j+'.'+i+'" transform="translate(-302.000000, -334.000000)" fill="'+color+'" fill-rule="nonzero" stroke="#181818" stroke-width="2">\
                                                           <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                              <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                              <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                           </g>\
                                                         </g>\
                                                      </g>\
                                                    </svg>\
                                                </div>\
                                            </td>'
                                }
                            }else{
                                mapDraw += '<td class="cliseat" id="seat_'+j+'_'+i+'" data-position="'+selectData.rowname+''+selectData.number+'" data-unitSel="" data-seatId="'+j+'.'+i+'" data-select="unSelect" data-status="unSelect">\
                                                <div class="s-seat-line">\
                                                <div class="tip__seatbox"><span data-tooltip="'+selectData.rowname+'&#xa;'+selectData.number+'"><i class="fas fa-info fa-1x fa__thead"></i></span></div>\
                                                    <svg preserveAspectRatio="xMinYMin meet" class="'+seatDirCN+'" width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\
                                                       <g id="seat" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                         <g id="Seat'+j+'.'+i+'" transform="translate(-302.000000, -334.000000)" fill="'+color+'" fill-rule="nonzero" stroke="#181818" stroke-width="2">\
                                                           <g id="seat" transform="translate(303.000000, 335.000000)">\
                                                              <path d="M46.7970547,20.2674843 L46.7970547,10.4834805 C46.7970547,4.72314906 42.7047969,0.0367008783 37.6748125,0.0367008783 L14.3251875,0.0367008783 C9.29520313,0.0367008783 5.20294531,4.72314906 5.20294531,10.4834805 L5.20294531,19.4083644 C2.22452344,20.2110145 0,22.5617297 0,26.2139562 L0,36.8136824 C0,44.6657191 5.18344531,51.1563562 11.8373125,52.0367009 L40.1627891,52.0367009 C46.8166563,51.1563562 52,44.6657191 52,36.8136824 L52,26.2138398 C52,22.5617297 49.7754766,21.0701344 46.7970547,20.2674843 Z" id="Path">\</path>\
                                                              <path d="M37.6748125,0.0367008783 C42.7047969,0.0367008783 46.7970547,4.72314906 46.7970547,10.4834805 L46.7970547,10.4834805 L46.7970547,20 C49.7754766,20.8026501 52,22.5617297 52,26.2138398 L52,26.2138398 L52,36.8136824 C52,44.6657191 46.8166563,51.1563562 40.1627891,52.0367009 L40.1627891,52.0367009 L11.8373125,52.0367009 C5.18344531,51.1563562 5.77815573e-13,44.6657191 5.77815573e-13,36.8136824 L5.77815573e-13,36.8136824 L5.77815573e-13,26.2139562 C5.77815573e-13,22.5617297 2.22452344,20.8026501 5.20294531,20 L5.20294531,20 L5.20294531,10.4834805 C5.20294531,4.72314906 9.29520313,0.0367008783 14.3251875,0.0367008783 L14.3251875,0.0367008783 Z M46.9177511,21 C44.7678868,21 42.6545563,23.1102872 42.6545563,25.6811647 L42.6545563,25.6811647 L42.6545563,37.4809693 C42.6545563,39.573562 41.2309858,41.2759138 39.4810812,41.2759138 L39.4810812,41.2759138 L12.5795186,41.2759138 C10.8295057,41.2759138 9.40593522,39.573562 9.40593522,37.4809693 L9.40593522,37.4809693 L9.40593522,25.6811647 C9.40593522,23.1104167 7.3359093,21 5.18593676,21 C3.03607249,21 0.4992,23.1102872 0.4992,25.6811647 L0.4992,25.6811647 L0.4992,38.0374703 C0.4992,45.271818 6.52988543,51.7819302 12.5796269,51.7819302 L12.5796269,51.7819302 L39.4811894,51.7819302 C45.5309309,51.7819302 51.5612915,44.715317 51.5612915,37.4809693 L51.5612915,37.4809693 L51.5612915,25.6811647 C51.5612915,23.1104167 49.0677236,21 46.9177511,21 Z" id="Combined-Shape">\</path>\
                                                           </g>\
                                                         </g>\
                                                      </g>\
                                                    </svg>\
                                                </div>\
                                            </td>'
                            }

                        }
                    }
                //    mapDraw += '<th>'

                  mapDraw += '</tr>'
                  num++
                }
                this.mapCreate = mapDraw
                
                this.$nextTick(() => {
                    this.drawSeat()
                })

                this.$nextTick(() => {
                    this.countTypeTotal()
                    
                    let blockId = floor+block
                   
                    let blockSelect = document.getElementById(blockId);
                    blockSelect.className += " active"
                    if(this.blockSelect && this.blockSelect !== blockId && !init){ 
                        let element = document.getElementById(this.blockSelect);
                        element.classList.remove("active");
                    }
                    this.blockSelect = blockId
                    this.blockChangeNull = false
                })
            },
            countTypeTotal:function(){
                let total = 0 
                this.dataSet.forEach(function(data){
                    data.total = 0
                })
                
                let dataSet = this.dataSet
                $.each(this.mapData, function(index, value) {
                    $.each(value['blockData'], function(index, block) {
                        $.each(block['seatData'], function(index, seat) {
                            if(seat.typeId !== ""){
                                dataSet[seat.typeId].total++
                                total++
                            }
                        }); 
                    }); 
                }); 
            
                this.dataSet = dataSet
                this.dataSet.forEach(function(data){
                    if(data.type == "ticketSetting" && !data.seatFree){
                       ticketSetting.settingSeatData[data.id].seatTotal = data.total
                    }
                    if(data.type == "specSeat" && !data.seatFree){
                       ticketSetting.specSeatData[data.id].ticketTotal = data.total
                    }
                })
                this.$nextTick(() => {
                    this.dataSet = dataSet
                    this.isSettingTotal = this.settingTotal - total
                    ticketSetting.updateTicketSettingData()
                    ticketSetting.updateSpecTicketSettingData()
                })
            },
            initTypeTotal:function(){
                let total = 0 
                this.dataSet.forEach(function(data){
                    data.total = 0
                })

                let dataSet = this.dataSet
                $.each(this.mapData, function(index, value) {
                    $.each(value['blockData'], function(index, block) {
                        $.each(block['seatData'], function(index, seat) {
                            if(seat.typeId !== ""){
                                dataSet[seat.typeId].total++
                                total++
                            }
                        }); 
                    }); 
                }); 
            
                this.dataSet = dataSet
                this.dataSet.forEach(function(data){
                    if(data.type == "ticketSetting" && !data.seatFree){
                        ticketSetting.settingSeatData[data.id].seatTotal = 0
                    }
                    if(data.type == "specSeat" && !data.seatFree){
                        ticketSetting.specSeatData[data.id].ticketTotal = 0
                    }
                })
                
                this.isSettingTotal = this.settingTotal - total
                ticketSetting.updateTicketSettingData()
                ticketSetting.updateSpecTicketSettingData()
            },
            getTicketData:function(){
                let ticketData = sessionStorage.getItem("ticketSetting")
                ticketData = JSON.parse(ticketData)
                this.ticketData  =  ticketData 

                let freeTicketData = sessionStorage.getItem("specTicketSetting")
                freeTicketData = JSON.parse(freeTicketData)
                this.freeTicketData  = freeTicketData 
            },
            getMapData:function(){
                let mapData = sessionStorage.getItem("mapData")
                mapData = JSON.parse(mapData, true)
                this.mapstatus = 'I';
                this.seat_profile_cd = null;

                this.mapData = mapData;
            },
            
            initMapSelect:function(){
                if(typeof(this.mapData) !== 'undefined'){
                    let floor = Object.keys(seatSetting.mapData)

                    if(typeof(floor[0]) !== 'undefined'){
                        this.nowFloor = floor[0]
                        let block = Object.keys(this.mapData[floor[0]]['blockData'])
                       
                        if(typeof(block[0]) !== 'undefined'){
                            this.$nextTick(() => {
                                this.nowFloor = floor[0]
                                this.nowBlock = block[0]
                                this.seatDataChange(this.nowFloor, this.nowBlock, true)
                            })
                        }
                    }
                }
            },
            uploadFile:function(){
                var files = $('#flieMap').prop('files');
                var data = new FormData();
                data.append('flieMap', files[0]);
                this.mapData = null
                this.nowBlock = ""
                this.nowFloor = ""
                this.tabShow = false
                this.nowUnitTotal = 0
                loading.openLoading()
              
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url('/excel/import') }}',
                    type: 'POST',  
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(result)
                    {  
                       document.getElementById("uploaderror").innerHTML = ''
                     
                       popUpResult.open(result.update_data)
                       if(result.errors.length > 0){
                       
                         document.getElementById("flieMap").value = ''
                         var msg = '<div class="callout callout-danger">' 
                         for (var i = 0, len = result.errors.length; i < len; ++i) 
                         {
                           msg = msg + '<i class="fas fa-exclamation-circle fa-lg"></i> ' + result.errors[i].title + '<br>'
                         }
                         msg = msg + '</div>'
                         document.getElementById("uploaderror").innerHTML = msg
                       }else if(result.status){
                            sessionStorage.setItem('mapData', JSON.stringify(result.data))
                            seatSetting.settingTotal = result.totalSeat
                            seatSetting.getMapData()
                            seatSetting.initTypeTotal()
                            seatSetting.tabShow = true
                            seatSetting.initMapSelect()
                            seatSetting.blockChangeNull = true
                            seatSetting.settingFunction()
                       }else{
                           console.log('err')
                       }
                       loading.closeLoading()
                    },
                    error: function(e)
                    {   
                        console.log(e)
                        errmsg = '{"status":{"update_status":false,"msn_status":"W","title_custom":true,"note_custom":true},"data":{"title":"エラー","note":"アップロードできませんでした","note_sub":"問題が発生したため、アップロードできませんでした。以下の内容を確認し、修正したファイルを再度アップロードしてください。","msn":[{"title":"Microsoft Excel 2007 以降で作成してください。","msn":""},{"title":"Excelブック(*.xlsx) 形式で保存してください。","msn":""},{"title":"環境依存文字や文字化けしたデータは含めないでください。","msn":""},{"title":"書式やオブジェクトが含まれている場合は削除してください。","msn":""}]}}'
                        document.getElementById("uploaderror").innerHTML = ''
                        popUpResult.open(errmsg)
                        document.getElementById("flieMap").value = ''
                        var msg = '<div class="callout callout-danger">' 
                        msg = msg + '<i class="fas fa-exclamation-circle fa-lg"></i> アップロード問題発生 <br>'
                        msg = msg + '</div>'
                        document.getElementById("uploaderror").innerHTML = msg
                        loading.closeLoading()
                    }
                });
            },
            countSeatSelect:function(){
                let dataSet = this.dataSet
                let nowSelUnit = this.seatUnit
                
                for(let n = 0; n<dataSet.length; n++){
                    dataSet[n].total = 0
                }

                $('.hadSetting').each(function() {
                  
                    let unit =  $(this).attr('data-unitsel')
                    
                    for(let n = 0; n<dataSet.length; n++){
                        if(dataSet[n].id == unit){
                            dataSet[n].total++
                           
                        }
                    }
                });        

                for(let n = 0; n<dataSet.length; n++){
                    if(dataSet[n].id == nowSelUnit){
                        this.nowUnitTotal = dataSet[n].total
                    }
                }
                
            },     
            selectSeatClear:function(){
                $('.cliseat').each(function() {
                   $(this).removeClass( "active" )
                   $(this).attr('data-status', 'unSelect')
                });
                mapSelectPoint = []
                this.settingBtnSel = true
            },
            saveSeatIsSelect:function(){
                let mapSeatData = []
                let nowFloor = this.nowFloor
                let nowBlock = this.nowBlock
                let nowSeatData =  this.mapData[nowFloor].blockData[nowBlock].seatData
              
                if(mapSelectPoint.length ==  2){
                    let xstar,xend,ystar,yend
                    let n = 0
                    let color = this.colorNow
                    let unit = this.seatUnit
                    let dataSet = this.dataSet
                    let nowSelectStart = mapSelectPoint[0].nowId
                    let nowSelectEnd = mapSelectPoint[1].nowId
                    let firstPoint = $(".cliseat").eq(nowSelectStart)
                    let secondPoint = $(".cliseat").eq(nowSelectEnd)
                    let firstPointPosition = firstPoint.attr('data-seatid').split(".")
                    let secondPointPosition = secondPoint.attr('data-seatid').split(".")
                    
                    if(parseInt(firstPointPosition[1]) > parseInt(secondPointPosition[1])){
                        ystar = parseInt(secondPointPosition[1])
                        yend = parseInt(firstPointPosition[1])
                    }else{
                        ystar = parseInt(firstPointPosition[1])
                        yend = parseInt(secondPointPosition[1])
                    }
                  
                    if(parseInt(firstPointPosition[0]) > parseInt(secondPointPosition[0])){
                        xstar = parseInt(secondPointPosition[0])
                        xend = parseInt(firstPointPosition[0])
                    }else{
                        xstar = parseInt(firstPointPosition[0])
                        xend = parseInt(secondPointPosition[0])
                    }

                    for(let star = 0; star < $('.cliseat').length; star++){
                        let postionId = $('.cliseat')[star].getAttribute('data-seatid')
                        let seatid = "Seat" + postionId
                        let pointPosition =  $('.cliseat')[star].getAttribute('data-seatid').split(".")
                        let x = parseInt(pointPosition[0])
                        let y = parseInt(pointPosition[1])
                        let seatCode = y+'.'+x
                       
                        if(xstar <= x && x <= xend && ystar <= y && y <= yend){
                            $('.cliseat')[star].setAttribute('data-select', 'select')
                            $('.cliseat')[star].setAttribute('data-unitSel', unit)
                            $('.cliseat')[star].classList.add("hadSetting")

                            let typeData  = {
                                id: this.dataSet[this.seatUnit].id,
                                text: this.dataSet[this.seatUnit].text,
                                title: this.dataSet[this.seatUnit].title,
                                type: this.dataSet[this.seatUnit].type,
                                color: this.dataSet[this.seatUnit].color,
                            }
                            
                            nowSeatData[seatCode].typeData = typeData
                            nowSeatData[seatCode].typeId = this.seatUnit
                            if(nowSeatData[seatCode].status !== 'I')
                                nowSeatData[seatCode].status = 'U';
                        }

                        if(x == xend && y == yend){
                            break
                        }
                    }
                    
                    this.mapData[nowFloor].blockData[nowBlock].seatData =  nowSeatData
                    this.countSeatSelect()
                    this.selectSeatClear()
                }else{
                    //let postionId = $('.cliseat')[star].getAttribute('data-seatid')
                   // let seatid = "Seat" + postionId
                    let nowSelect = mapSelectPoint[0].nowId
                    let id = $(this).attr("name")
                    let status = $(this).attr('data-status')
                    let color = this.colorNow
                    let unit = this.seatUnit
                    let dataSet = this.dataSet
                    let seatSelId = $(this).attr('data-id')
                    let pointPosition =  $('.cliseat')[nowSelect].getAttribute('data-seatid').split(".")
                    let x = parseInt(pointPosition[0])
                    let y = parseInt(pointPosition[1])
                    let seatCode = y+'.'+x

                    $(".cliseat").eq(nowSelect).attr('data-select', 'select')
                    $(".cliseat").eq(nowSelect).attr('data-unitSel', unit)
                    $('.cliseat')[nowSelect].classList.add("hadSetting")

                    $('.cliseat').each(function() {
                        let id = $(this).attr("name")
                        let status = $(this).attr('data-status')
                        let seatSelId = $(this).attr('data-id')

                        mapSeatData.push({
                            id: id,
                            seatSelId: seatSelId,
                            status: status,
                            color: color
                        })
                    });

                    let typeData  = {
                        id: this.dataSet[this.seatUnit].id,
                        text: this.dataSet[this.seatUnit].text,
                        title: this.dataSet[this.seatUnit].title,
                        type: this.dataSet[this.seatUnit].type,
                        color: this.dataSet[this.seatUnit].color,
                    }
                    
                    nowSeatData[seatCode].typeData = typeData
                    nowSeatData[seatCode].typeId = this.seatUnit
                    if(nowSeatData[seatCode].status !== 'I')
                        nowSeatData[seatCode].status = 'U';
                   
                    this.countSeatSelect()
                    this.selectSeatClear()
                }
                this.seatDataChange(this.nowFloor, this.nowBlock)
                this.countTypeTotal()
            },            
            clearTicketType:function(typeId, type){
                let mapData = this.mapData
                let ticketType = ""
                let id = ""
                let filterData = ({
                    'index' : typeId,
                    'type' : 0,
                })

                this.seatSettingInf()
                this.nowBlock = ""

                this.$nextTick(() => {
                    if(type === 'ssd'){
                        ticketType = "specSeat"
                        filterData.type = 1
                    }else if(type === 'ticketSetting'){
                        ticketType = "ticketSetting"
                        filterData.type = 2
                    }else{

                    }
                    
                    this.dataSet.forEach(function(data, Index) {
                        if(data.id == typeId && data.type == ticketType){
                            id = Index.toString()
                        }
                    })
                 
                    $.each(mapData, function(index, data) {
                        $.each(data.blockData, function(floorIndex, floorData) {
                            $.each(floorData.seatData, function(seatIndex, seatData) {
                                if(typeof(seatData.typeId) !== 'undefined' && typeof(seatData.typeData.id) !== 'undefined' && typeof(seatData.typeData.type) !== 'undefined'){
                                    if(seatData.typeData.id.toString() === typeId.toString() && seatData.typeData.type.toString() === ticketType){
                                        seatData.typeId = ""
                                        seatData.typeData.text = ''
                                        seatData.typeData.title = ''
                                        seatData.typeData.type = ''
                                        seatData.typeData.color = '#FFFFFF'
                                    }
                                }
                                if(typeof seatData.respectiveData !== 'undefined'){
                                    let respectiveId = seatData.respectiveData.findIndex(function(ele, index, arr){
                                        return ele.index == filterData.index && ele.type == filterData.type
                                    }, filterData)
                                    seatData.respectiveData.splice(respectiveId, 1)
                                }
                                
                            });
                        });
                    }); 

                    this.mapData = mapData 
                })
            },
            clearTicketTypeTitle:function(typeId, type, title){
                let mapData = this.mapData
                let ticketType = ""
                let id = ""

                this.seatSettingInf()
                this.nowBlock = ""

                this.$nextTick(() => {
                    if(type === 'ssd'){
                        ticketType = "specSeat"
                    }else if(type === 'ticketSetting'){
                        ticketType = "ticketSetting"
                    }else{

                    }
                    
                    this.dataSet.forEach(function(data, Index) {
                        if(data.id == typeId && data.type == ticketType){
                            id = Index.toString()
                        }
                    })
                    
                    $.each(mapData, function(index, data) {
                        $.each(data.blockData, function(floorIndex, floorData) {
                            $.each(floorData.seatData, function(seatIndex, seatData) {
                                if(typeof(seatData.typeId) !== 'undefined' && typeof(seatData.typeData.id) !== 'undefined' && typeof(seatData.typeData.type) !== 'undefined'){
                                    if(seatData.typeData.id.toString() === typeId.toString() && seatData.typeData.type.toString() === ticketType){
                                        seatData.typeData.title = title
                                    }
                                }
                            });
                        });
                    }); 

                    this.mapData = mapData 
                })
            },
            clearTicketTypeColor:function(typeId, type, color){
                let mapData = this.mapData
                let ticketType = ""
                let id = ""

                this.seatSettingInf()
                //this.nowBlock = ""

                this.$nextTick(() => {
                    if(type === 'ssd'){
                        ticketType = "specSeat"
                    }else if(type === 'colorSet'){
                        ticketType = "ticketSetting"
                    }else{

                    }
                    
                    this.dataSet.forEach(function(data, Index) {
                        if(data.id == typeId && data.type == ticketType){
                            id = Index.toString()
                        }
                    })
                    
                    $.each(mapData, function(index, data) {
                        $.each(data.blockData, function(floorIndex, floorData) {
                            $.each(floorData.seatData, function(seatIndex, seatData) {
                                if(typeof(seatData.typeId) !== 'undefined' && typeof(seatData.typeData.id) !== 'undefined' && typeof(seatData.typeData.type) !== 'undefined'){
                                    if(seatData.typeData.id.toString() === typeId.toString() && seatData.typeData.type.toString() === ticketType){
                                        seatData.typeData.color = color
                                    }
                                }
                            });
                        });
                    }); 

                    this.mapData = mapData 
                })
            },
            clearTicketTypeText:function(typeId, type, text){
                let mapData = this.mapData
                let ticketType = ""
                let id = ""

                this.seatSettingInf()
                this.nowBlock = ""

                this.$nextTick(() => {
                    if(type === 'ssd'){
                        ticketType = "specSeat"
                    }else if(type === 'colorSet'){
                        ticketType = "ticketSetting"
                    }else{

                    }
                    
                    this.dataSet.forEach(function(data, Index) {
                        if(data.id == typeId && data.type == ticketType){
                            id = Index.toString()
                        }
                        
                    })
                    
                    $.each(mapData, function(index, data) {
                        $.each(data.blockData, function(floorIndex, floorData) {
                            $.each(floorData.seatData, function(seatIndex, seatData) {
                                if(typeof(seatData.typeId) !== 'undefined' && typeof(seatData.typeData.id) !== 'undefined' && typeof(seatData.typeData.type) !== 'undefined'){
                                    if(seatData.typeData.id.toString() === typeId.toString() && seatData.typeData.type.toString() === ticketType){
                                        seatData.typeData.text = text
                                    }
                                }
                            });
                        });
                    }); 

                    this.mapData = mapData 
                })
            },
            clearSeatIsSelect:function(){
                let mapSeatData = []
                let nowFloor = this.nowFloor
                let nowBlock = this.nowBlock
                let nowSeatData =  this.mapData[nowFloor].blockData[nowBlock].seatData
               
                if(mapSelectPoint.length ==  2){
                    let xstar,xend,ystar,yend
                    let n = 0
                    let dataSet = this.dataSet
                    let unit = this.seatUnit
                    let color = this.colorNow
                    let nowSelectStart = mapSelectPoint[0].nowId
                    let nowSelectEnd = mapSelectPoint[1].nowId
                    let firstPoint = $(".cliseat").eq(nowSelectStart)
                    let secondPoint = $(".cliseat").eq(nowSelectEnd)
                    let firstPointPosition = firstPoint.attr('data-seatid').split(".")
                    let secondPointPosition = secondPoint.attr('data-seatid').split(".")
                    
                    if(parseInt(firstPointPosition[1]) > parseInt(secondPointPosition[1])){
                        ystar = parseInt(secondPointPosition[1])
                        yend = parseInt(firstPointPosition[1])
                    }else{
                        ystar = parseInt(firstPointPosition[1])
                        yend = parseInt(secondPointPosition[1])
                    }
                  
                    if(parseInt(firstPointPosition[0]) > parseInt(secondPointPosition[0])){
                        xstar = parseInt(secondPointPosition[0])
                        xend = parseInt(firstPointPosition[0])
                    }else{
                        xstar = parseInt(firstPointPosition[0])
                        xend = parseInt(secondPointPosition[0])
                    }

                    for(let star = 0; star < $('.cliseat').length; star++){
                        let pointPosition =  $('.cliseat')[star].getAttribute('data-seatid').split(".")
                        let x = parseInt(pointPosition[0])
                        let y = parseInt(pointPosition[1])
                        
                        if(xstar <= x && x <= xend && ystar <= y && y <= yend){
                            let x = parseInt(pointPosition[0])
                            let y = parseInt(pointPosition[1])
                            let seatCode = y+'.'+x 
                            
                            nowSeatData[seatCode].typeData.id = ''
                            nowSeatData[seatCode].typeData.text = ''
                            nowSeatData[seatCode].typeData.title = ''
                            nowSeatData[seatCode].typeData.type = ''
                            nowSeatData[seatCode].typeData.color = '#FFFFFF'
                            nowSeatData[seatCode].typeId = ''
                        }

                        if(x == xend && y == yend){
                            break
                        }
                    }
                    this.mapData[nowFloor].blockData[nowBlock].seatData =  nowSeatData
                    this.countSeatSelect()
                    this.selectSeatClear()

                }else{
                    let nowSelect = mapSelectPoint[0].nowId
                    let id = $(this).attr("name")
                    let status = $(this).attr('data-status')
                    let color = this.colorNow
                    let unit = this.seatUnit
                    let dataSet = this.dataSet
                    let seatSelId = $(this).attr('data-id')
                    // let postionId = $('.cliseat')[star].getAttribute('data-seatid')
                    // let seatid = "Seat" + postionId
                    let pointPosition =  $('.cliseat')[nowSelect].getAttribute('data-seatid').split(".")
                    let x = parseInt(pointPosition[0])
                    let y = parseInt(pointPosition[1])
                    let seatCode = y+'.'+x

                    $(".cliseat").eq(nowSelect).attr('data-select', 'unSelect')
                    $(".cliseat").eq(nowSelect).attr('data-unitSel', '')
                   // document.getElementById(seatid).style.fill = 'rgba(230,230,230,0.5)'
                    $('.cliseat')[nowSelect].classList.remove("hadSetting")

                    for(let n = 0; n<dataSet.length; n++){
                        if(dataSet[n].id == unit){
                            dataSet[n].total--
                            $('#nowUnitSelect').text(dataSet[n].total)
                        }
                    }
                    nowSeatData[seatCode].typeData.id = ''
                    nowSeatData[seatCode].typeData.text = ''
                    nowSeatData[seatCode].typeData.title = ''
                    nowSeatData[seatCode].typeData.type = ''
                    nowSeatData[seatCode].typeData.color = '#FFFFFF'
                    nowSeatData[seatCode].typeId = ''
                    
                    this.countSeatSelect()
                    this.selectSeatClear()
                }
                this.seatDataChange(this.nowFloor, this.nowBlock)
                this.$nextTick(() => {
                    this.countTypeTotal()
                })
            },
            seatUnitChange:function(){
                let id = this.seatUnit
                let data = this.dataSet[id]
                let color = data.color
                this.nowUnitTotal = data.total
                this.colorNow = color
                this.settingBtnOption = false
                this.nowText =  data.text

                this.selectSeatClear()
            },
            seatColorSetting:function(id, color){
                let dataSet = this.dataSet
                let num = 0
                dataSet.forEach(function(data) {
                    if(id == data.id){
                        dataSet[num].color = color
                    }
                    num++
                })

                this.dataSet = dataSet 
            },
            drawSeat:function(){
                var form = document.getElementById("blockSeatForm");        
                      
                if($(window).width()>480){ 
                    Theatreseat(form, (this.map.x_max - this.map.x_min + 1), (this.map.y_max - this.map.y_min + 1), this.qty, false, false, 45);
                }else{
                    var newHeight = ($(window).height() -350)/$(window).height();
                    Theatreseat(form, (this.map.x_max - this.map.x_min + 1), (this.map.y_max - this.map.y_min + 1), this.qty, true, 0.54, 45, true );
                }

                //$("#seatarea").dragScroll();
                var ua = navigator.userAgent;
                if( ua.search(/Android 2.[123]/) != -1 ) {
                var scroll_start_x = 0;
                var scroll_start_y = 0;
                $('.scroll-box').each(function() {
                    $(this).on({
                        'touchstart' : function(e) {
                            scroll_start_x = e.originalEvent.touches[0].pageX;
                            scroll_start_y = e.originalEvent.touches[0].pageY;
                        },
                        'touchmove' : function(e) {
                            if($('#zoombtn_out').is(':disabled')) return;
                            e.preventDefault();
                            var scroll_end_x = e.originalEvent.touches[0].pageX;
                            var scroll_end_y = e.originalEvent.touches[0].pageY;
                            $(this).scrollTop($(this).scrollTop() - ((scroll_end_y - scroll_start_y)*0.5));
                            $(this).scrollLeft($(this).scrollLeft() - ((scroll_end_x - scroll_start_x)*0.5));
                        }
                    });
                });
                } 

            },      
            settingFunction:function(floor){
                this.nowFloor = floor
                this.seatSettingInf()
                $.getScript("{{ asset('js/dropify.min.js') }}", function(){
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
                    },
                    error: {
                      'fileSize': '{{trans("common.S_DropifySizeErr")}}'
                      }
                  });
                });
                this.blockChangeNull = false
            },
            submitForm: function() {
            $('#blockSeatForm').submit();
            },
            showPhoto: function() {
            seatSetting.showModal('#photoModal', 'normal');
            },
            confirm: function() {
            seatSetting.showModal('#confirmModal', 'normal');
            }
        }
    });
    window.onpageshow = function(){
    seatSetting.onSubmit = true;
    if($(document).width()>480){    //PCの場合のHTMLの更新
        // $("#seatMap").before($("#blockInfo"));
        /// $("#seatMap").before($("#buttonBlockInfo"));
        //$("#seatMap").after($("#seatStatus"));
        //$("#zoombtn_in").appendTo("#buttonBlockInfo");
        //$("#zoombtn_out").appendTo("#buttonBlockInfo");
    }

    $("#photoModal>.modal1_inner>.title1>.imageHolder").on("click", ".closeModal", function() {
        seatSetting.closeModal('#photoModal', 'normal');
        });
    };

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader()
        
        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result)
            sessionStorage.setItem("venuaImage",JSON.stringify(e.target.result))
        }

        reader.readAsDataURL(input.files[0])
    }
}

$("#imgInp").change(function() {
    readURL(this)
});

function mapDataFileUpload(even){
    event.stopPropagation(); 
    event.preventDefault();
 
    var data = new FormData();
    let files = event.target.files;

    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });    
}

$("#mapDataUpload").change(function() {
    mapDataFileUpload(this)
});


</script>
