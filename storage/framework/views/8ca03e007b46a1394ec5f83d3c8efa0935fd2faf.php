<?php $CommonPresenter = app('App\Presenters\CommonPresenter'); ?>



<?php $__env->startSection('title', 'Gettii Lite'); ?>


<?php $__env->startSection('content_header'); ?>
<!-- /.網站導覽 -->
<!-- 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<?php if( $eventData["status"] === 'edit'): ?> 
    <h1>
        <?php echo e(trans('events.S_mainTitle_01')); ?>

        
    <!-- <small class="edit-status">
        </small>-->
    </h1>
    <!-- 網站導覽 -->
    <ol class="breadcrumb">
        <li><a href="/events" onclick="loading.openLoading()"><?php echo e(trans('events.S_mainTitle_02')); ?></a></li>
        <li class="active"><?php echo e(trans('events.S_mainTitle_04')); ?></li>
    </ol>
<?php else: ?>
    <h1>
        <?php echo e(trans('events.S_mainTitle_01')); ?>

        
    </h1>
    <!-- 網站導覽 -->
    <ol class="breadcrumb">
        <li><a href="/events"><?php echo e(trans('events.S_mainTitle_02')); ?></a></li>
        <li class="active"><?php echo e(trans('events.S_mainTitle_03')); ?></li>
    </ol>    
<?php endif; ?>

<?php if( $eventData["status"] === 'edit'): ?>
    <form id="settingSend" method="POST" style="visibility: collapse;display: table;" action="/events/info/<?php echo e($eventData['performanceId']); ?>" enctype="multipart/form-data">
        <input id="settingContent" type="hidden" name="json" >
        <?php echo e(csrf_field()); ?>

    </form>
<?php else: ?>
    <form id="settingSend" method="POST" style="visibility: collapse;" action="/events/create" enctype="multipart/form-data">
        <input id="settingContent" type="hidden" name="json" >
        <?php echo e(csrf_field()); ?>

        <script>
          sessionStorage.removeItem('calenderData');
          sessionStorage.removeItem('ruleData');
        </script>
    </form>
<?php endif; ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('content_header_setting'); ?>
    <?php $__env->startComponent('components/remind', ['remind_code' => $eventData['remind_code']]); ?>
    <?php if (isset($__componentOriginal71363fb4a50f4b2095b53e128e7ef2409b35550e)): ?>
<?php $component = $__componentOriginal71363fb4a50f4b2095b53e128e7ef2409b35550e; ?>
<?php unset($__componentOriginal71363fb4a50f4b2095b53e128e7ef2409b35550e); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <!-- 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
    <!-- 0410新增調整 -->
    <div id="FinalSetting" class="funtion-btn-block"> 
        <!-- 0410新增調整 -->
        <div class="switch">
            <input type="radio" class="switch-input" name="saleType" id="sale-type-nosale" v-model="saleType" value="0" :disabled="statucControl[0].sale_type" >
            <label for="sale-type-nosale" class="switch-label switch-label-off"><?php echo e(trans('events.S_eventSaleTypeInformation')); ?></label>
            <input type="radio" class="switch-input" type="radio" name="saleType"  id="sale-type-sale" v-model="saleType" value="1" :disabled="statucControl[0].sale_type">
            <label for="sale-type-sale" class="switch-label switch-label-on"><?php echo e(trans('events.S_eventSaleTypeSale')); ?></label>
            <span class="switch-selection"></span>
        </div>
        <!-- 0917調整 -->
        <small class="edit-status">
        <!--已經發布活動狀態 ：--> 
        <span class="text-gray-light"><?php echo e($CommonPresenter->getStatusString($eventData['performanceDispStatus'], $eventData['sale_type'])); ?>

        <?php if(config('app.debug') == true): ?>
            (<?php echo e($eventData['performanceDispStatus']); ?>)
        <?php endif; ?>
        </span>
        <?php if($eventData["transFlg"]): ?>
        <span class="text-gray-light font-600 row-5"> ⇢ </span>  
        <!--現編輯活動狀態 ：-->
        <span class="font-600"><?php echo e($CommonPresenter->getStatusString($eventData['draft_dispaly_status'], $eventData['sale_type'])); ?>

            <?php if(config('app.debug') == true): ?>
                (<?php echo e($eventData['draft_dispaly_status']); ?>)
            <?php endif; ?>
        <?php endif; ?>
        </span>
<span class="font-600 row-5">
       ｜ <?php if( $eventData["status"] === 'edit' && $eventData['performanceDispStatus'] >= config('constant.performance_disp_status.browse')): ?>
           <?php if($eventData['edit_status'] == config('constant.edit_status.not') ): ?>
                <?php echo e(trans('common.S_EditStatus_not')); ?>

            <?php elseif($eventData['edit_status']  == config('constant.edit_status.going')): ?>
                <?php echo e(trans('common.S_EditStatus_going')); ?>

            <?php elseif($eventData['edit_status']  == config('constant.edit_status.complete')): ?>
                <?php echo e(trans('common.S_EditStatus_complete')); ?>

            <?php endif; ?>
            
        <?php endif; ?>
</span>
    </small>
        <!-- /.0410新增調整 -->
        <?php if( $eventData["status"] === 'edit'): ?> 
            <?php if($eventData['performanceDispStatus'] < 7 && session('event_info_flg') == 2): ?>
                <!-- 1210 調整 -->
                <button id="preview"  type="button" class="btn waves-effect waves-light btn-rounded btn-normal-outline m-r-10">
                    <?php echo e(trans('events.S_eventPreviewBtn')); ?>

                </button>
                <!-- /.1210 調整 -->  
                <button id="updateBtn" type="button" class="btn waves-effect waves-light btn-rounded btn-normal" :disabled="!updataBtn"> 
                        <?php echo e(trans('events.S_SaveBtn')); ?>

                </button>
            <?php endif; ?>
        <?php else: ?>
            <button id="preview"  type="button" class="btn waves-effect waves-light btn-rounded btn-normal-outline m-r-10">
                <?php echo e(trans('events.S_eventPreviewBtn')); ?>

            </button>
            <button id="addBtn" type="button" class="btn waves-effect waves-light btn-rounded btn-normal" :disabled="!addBtn"> 
                <?php echo e(trans('events.S_SaveBtn')); ?>

            </button>
        <?php endif; ?>
    </div>
    <!-- /.固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
    <script>
        window.is_confirm = true
        window.addEventListener('beforeunload', (event) => {
            if (window.is_confirm){
                event.preventDefault();
                event.returnValue = '';
                loading.closeLoading()
            }
        });
    <?php if(count($errors) > 0): ?> 

    <?php else: ?>
        sessionStorage.clear();
    <?php endif; ?>
    
    //是否有單場設定
    const MapHadStageSeat = '<?php echo e(isset($eventData["map_had_stage_seat"])?$eventData["map_had_stage_seat"]:false); ?>'
    //販賣資料
    const SaleInfo = <?php echo json_encode($eventData["sale_info"] , 15, 512) ?>

    Vue.config.devtools = true;
    var errorMsnCheack = new Vue({
            el: '#FinalSetting',
            data: {
                updataBtn : true, 
                addBtn : true,
                saleType : 0,
                statucControl:[],
            },
            watch:{
                saleType: function(val){
                    basisSetting.saleType = val
                    ticketSetting.saleType = val    // STS - 2021/06/11 - Task 17
					timeCourse.saleType = val //STS 201/07/26 task 38
                    ticketViewSetting.saleType = val //STS 201/07/26 task 38
                },
                //STS 2021/07/28 Task 38
                updataBtn: function() {
                    this.addBtnCheack()
                }
            },
            methods: {
                updataBtnCheack:function(){
                	//STS 2021/07/27 Task 38
                    if(!basisSetting.addButtonErrorStatus || !ticketViewSetting.addButtonErrorStatus || !timeCourse.addButtonErrorStatus )
                    this.updataBtn = tagControl.getTicketViewData()
                },
                //STS 2021/07/26 Task 38 START
                // addBtnCheack:function(){
                //     if(
                //         basisSetting.addButtonErrorStatus 
                //     ){
                //         this.addBtn = false
                //     }else{
                //         this.addBtn = true
                //     }
                // }, 
                addBtnCheack:function(){
                    if(
                        basisSetting.addButtonErrorStatus || ticketViewSetting.addButtonErrorStatus || timeCourse.addButtonErrorStatus || ticketSetting.addButtonErrorStatus
                    ){
                        this.addBtn = false
                        this.updataBtn = false
                    }else{
                        this.addBtn = true
                        this.updataBtn = true
                    }
                },
                //STS 2021/07/26 Task 38 END
            },
            mounted(){
                let perfomanceStatus = parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10);

                this.statucControl.push({
                    sale_type: [7, 8].includes(perfomanceStatus),
                })  
            }
        });
        
        function errorMsgCheck(){
        
            let disable = false

            $(".error-warn").each(function(){
                let status = $( this ).attr('data-error')
                if(status == 'had'){
                    disable = true
                }
            });
            
            if(disable){
                errorMsnCheack.updataBtn = true
            }else{
                errorMsnCheack.updataBtn = false
            }
    }
    </script>
    <!-- /.0410新增調整 -->
    <!-- /.固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"> 

<!-- 訊息提示 -->
<!-- /.訊息提示 -->
<div class="content-navonly">	
    <!--<small class="edit-status">
        <?php echo e($CommonPresenter->getStatusString($eventData['performanceDispStatus'], $eventData['sale_type'])); ?>


        <?php if(config('app.debug') == true): ?>
            (<?php echo e($eventData['performanceDispStatus']); ?>)
        <?php endif; ?>

        <?php if( $eventData["status"] === 'edit' && $eventData['performanceDispStatus'] >= config('constant.performance_disp_status.browse')): ?>
            <?php if($eventData['edit_status'] == config('constant.edit_status.not') ): ?>
                <?php echo e(trans('common.S_EditStatus_not')); ?>

            <?php elseif($eventData['edit_status']  == config('constant.edit_status.going')): ?>
                <?php echo e(trans('common.S_EditStatus_going')); ?>

            <?php elseif($eventData['edit_status']  == config('constant.edit_status.complete')): ?>
                <?php echo e(trans('common.S_EditStatus_complete')); ?>

            <?php endif; ?>
        <?php endif; ?>
    </small>-->
        <!-- 
        ============================================================================================================
        1. 提示效果錯誤訊息     <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
        2. li 被選取樣式       active
        3. li 燈號樣式         -light
        4. li disabled樣式    -disabled
        ============================================================================================================
        -->
    <ul id='tagControl' class="nav nav-tabs nav-tabs-basic">
        <li  class="active" v-show="basisTag">
            <a id="basisInfPage" href="#basis" data-toggle="tab"><span><?php echo e(trans('events.S_eventInfoTab_01')); ?></span></a>
            <!-- 提示效果 - 錯誤訊息 -->
            <div class="notify" id="basisWarn" data-error="" v-show="basisWarning"> 
                <span class="heartbit"></span> 
                <span class="point"></span> 
            </div>
            <!-- /.提示效果 - 錯誤訊息  -->
        </li>
        <li v-show="timeCourseTag">
            <a id="timePage" href="#date" data-toggle="tab" @click="timePageSetting"><span><?php echo e(trans('events.S_eventInfoTab_02')); ?></span></a>
            <!-- 提示效果 - 錯誤訊息 -->
            <div class="notify" data-error="" v-show="timeCourseWarning"> 
                <span class="heartbit"></span> 
                <span class="point"></span> 
            </div>
            <!-- /.提示效果 - 錯誤訊息  -->
        </li>
        <li v-show="ticketTag">
            <a id="ticketSetPage" href="#ticket" data-toggle="tab" @click="ticketsettingSeat"><span><?php echo e(trans('events.S_eventInfoTab_03')); ?></span></a>
            <!-- 提示效果 - 錯誤訊息 -->
            <div class="notify" id="ticketSetWarn" data-error="" v-show="ticketWarning"> 
                <span class="heartbit"></span> 
                <span class="point"></span> 
            </div>
            <!-- /.提示效果 - 錯誤訊息  -->
        </li>
        <li v-show="sellSettingTag">
            <a href="#sell" data-toggle="tab"><span><?php echo e(trans('events.S_eventInfoTab_04')); ?></span></a>
            <!-- 提示效果 - 錯誤訊息 -->
            <div class="notify" data-error="" v-show="sellSettingWarning"> 
                <span class="heartbit"></span> 
                <span class="point"></span> 
            </div>
            <!-- /.提示效果 - 錯誤訊息  -->
        </li>
        <li v-show="seatSettingTag">
            <a href="#seat" data-toggle="tab" @click="settingSeat"><span><?php echo e(trans('events.S_eventInfoTab_05')); ?></span></a>
            <!-- 提示效果 - 錯誤訊息 -->
            <div class="notify" data-error="" v-show="seatSettingWarning">
                <span class="heartbit"></span> 
                <span class="point"></span> 
            </div>
            <!-- /.提示效果 - 錯誤訊息  -->
        </li>
        <li v-show="ticketViewTag">
            <a id="ticketContent" href="#other" data-toggle="tab" @click="settingticketView"><span><?php echo e(trans('events.S_eventInfoTab_06')); ?></span></a>
            <!-- 提示效果 - 錯誤訊息 -->
            <div class="notify" data-error="" v-show="ticketViewWarning">
                <span class="heartbit"></span> 
                <span class="point"></span> 
            </div>
            <!-- /.提示效果 - 錯誤訊息  -->
        </li>
    </ul>
    <?php $__env->startComponent('components/result'); ?>
        
    <?php if (isset($__componentOriginal6441fbc97ccf63f19548e07f87a84d22377426f6)): ?>
<?php $component = $__componentOriginal6441fbc97ccf63f19548e07f87a84d22377426f6; ?>
<?php unset($__componentOriginal6441fbc97ccf63f19548e07f87a84d22377426f6); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <div class="row tab-content tab-basic-content">
        <div class="active tab-pane content-width" id="basis">
            <?php echo $__env->make('frontend.event.editLayout.basic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane content-width time-setting-block" id="date">
            <?php echo $__env->make('frontend.event.editLayout.timeCourse', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane content-width" id="ticket">
            <?php echo $__env->make('frontend.event.editLayout.ticketSetting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane content-width" id="sell">
            <?php echo $__env->make('frontend.event.editLayout.sellSetting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane" id="seat">
            <?php echo $__env->make('frontend.event.editLayout.seatSetting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane content-width" id="other">
            <?php echo $__env->make('frontend.event.editLayout.other', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
  </div>
  <script>
       var articleSeq = new Array();
       var tagControl = new Vue({
        el: "#tagControl",
        data: {
           basisTag:'',
           timeCourseTag:'',
           ticketTag:'',
           sellSettingTag:'',
           seatSettingTag:'',
           ticketViewTag:'',
           basisWarning: false,
           timeCourseWarning: false,
           ticketWarning: false,
           sellSettingWarning: false,
           seatSettingWarning: false,
           ticketViewWarning: false,
        },
        watch: {
            basisWarning: function(){
                errorMsnCheack.updataBtnCheack()
            },
            timeCourseWarning: function(){
                errorMsnCheack.updataBtnCheack()
            },
            ticketWarning: function(){
                errorMsnCheack.updataBtnCheack()
            },
            sellSettingWarning: function(){
                errorMsnCheack.updataBtnCheack()
            },
            seatSettingWarning: function(){
                errorMsnCheack.updataBtnCheack()
            },
            ticketViewWarning: function(){
                errorMsnCheack.updataBtnCheack()
            },
        },
        methods: {
            getTicketViewData:function(){
                if(
                    this.basisWarning ||
                    this.timeCourseWarning ||
                    this.ticketWarning ||
                    this.sellSettingWarning ||
                    this.seatSettingWarning ||
                    this.ticketViewWarning
                ){
                    return false
                }else{
                    return true
                }
            },
            settingticketView:function(){
                ticketViewSetting.presetShow()
            },
            settingSeat:function(){
                seatSetting.seatUnit = ""
                seatSetting.colorNow = ""
                seatSetting.nowUnitTotal = ""
                seatSetting.tableHidden = false
                seatSetting.seatSettingInf()
                seatSetting.initMapSelect()
            },
            ticketsettingSeat:function(){
                ticketSetting.init = true
            },
            timePageSetting:function(){
                timeCourse.dateReset()
            },
            tagControlCheack:function(){
                //場次設定
                if(basisSetting.performance_st_dt && basisSetting.performance_end_dt){
                    this.timeCourseTag = true
                }else{
                    this.timeCourseTag = false
                }
                //票種與票別設定
                if(basisSetting.earlyBirdDateChecked || basisSetting.normalDateChecked) {
                    this.ticketTag = true
                }
                else {
                    this.ticketTag = false
                }
                //販賣條件設定
                if(basisSetting.earlyBirdDateChecked || basisSetting.normalDateChecked) {
                    this.sellSettingTag = true
                }
                else {
                    this.sellSettingTag = false
                }
                //席位設定
                if(this.timeCourseTag && this.ticketTag){
                    if(basisSetting.locationName &&  timeCourse.settingRadio == "normal" && ticketSetting.typeTicketSetting !== 'freeSeat'){
                        this.seatSettingTag = true
                    }else{
                        this.seatSettingTag = false
                    }
                }else{
                    this.seatSettingTag = false
                }
                //票劵資訊設定
                if(this.timeCourseTag && this.sellSettingTag){
                    if(sellSetting.ibonGetTicket  || sellSetting.onlineGetTicket || sellSetting.sevenEleven || sellSetting.sevenElevenSEJ){
                        this.ticketViewTag = true
                    }else{
                        this.ticketViewTag = false
                    }
                }else{
                    this.ticketViewTag = false
                }

            },
            tagSave:function(){

                let tagStatus = {
                    timeCourseTag: this.timeCourseTag,
                    ticketTag: this.ticketTag,
                    sellSettingTag: this.sellSettingTag,
                    seatSettingTag: this.seatSettingTag,
                    ticketViewTag: this.ticketViewTag,
                }

                sessionStorage.setItem('tagStatus', JSON.stringify(tagStatus))
            },
        },
        mounted(){
            
            <?php if(count($errors) > 0): ?> 
                let tagStatus = JSON.parse(sessionStorage.getItem('tagStatus'))
                this.basisTag = true
                this.timeCourseTag = tagStatus.timeCourseTag
                this.ticketTag = tagStatus.ticketTag
                this.sellSettingTag = tagStatus.sellSettingTag
                this.seatSettingTag = tagStatus.seatSettingTag
                this.ticketViewTag = tagStatus.ticketViewTag

                let errorJson = '<?php echo addslashes($errors->first()); ?>'
                popUpResult.open(errorJson)
            <?php else: ?>
                <?php if( $eventData["status"] === 'edit'): ?>   
                    this.basisTag = true
                    this.timeCourseTag = true
                    this.ticketTag = true
                    this.sellSettingTag = true
                    this.seatSettingTag = true

                    <?php if($eventData["has_msn"]): ?> 

                        let msnJson = '<?php echo addslashes($eventData["msn_json"]); ?>'

                        popUpResult.open(msnJson)
                    <?php endif; ?>

                    this.tagControlCheack()
                <?php else: ?>
                    this.basisTag = true
                    this.timeCourseTag = false
                    this.ticketTag = false
                    this.sellSettingTag = false
                    this.seatSettingTag = false
                    this.ticketViewTag = false
                <?php endif; ?>

            <?php endif; ?>

        },
    });

    var earlyBirdDateEnd
    var basisStarDate = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0)
    var basisCloseDate = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 23, 55, 0, 0)
    var basisEndDate = new Date(nowDate.getFullYear(), nowDate.getMonth()+12, nowDate.getDate(), 0, 0, 0, 0)
    // <!-- STS 2021/05/28 販売期間のポップアップの販売開始の初期値を10：00に変更してください。  -->
    var basisSalesStarDate = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 10, 0, 0, 0)
    /**
        * 
        * 
        */
    if(basisSetting.infOpenDate){
        let initDate = new Date(basisSetting.infOpenDate)
        var referenceDate = new Date(initDate.getFullYear(), initDate.getMonth(), initDate.getDate(), 10, 0, 0, 0)
        if(Date.parse(basisSetting.infOpenDate) >  Date.parse(basisStarDate)){
            referenceDate = basisStarDate
        }
    }else{
        var referenceDate = basisStarDate
    }

        /**
         * 活動公開日期初始化
         * init to basis information page
         */
        $('#infOpenDate').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD"
            },
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            autoUpdateInput: false
        })
        /*
         * 活動公開日同步至 basisSetting 資料中
         */
        $('#infOpenDate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD HH:mm'))
            basisSetting.infOpenDate = picker.startDate.format('YYYY/MM/DD HH:mm')
            
            basisSetting.infOpenIsNull = true
         });
        /**
         * 情報公開終了日
         */
        $('#end-date').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD"
            },
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            autoUpdateInput: false,
            minDate: referenceDate,
            maxDate: basisEndDate,
            startDate: (basisSetting.dateEnd.date)?basisSetting.dateEnd.date:basisCloseDate
        })
        /*
         * 情報公開終了日 basisSetting 資料中
         */
        $('#end-date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD HH:mm'))
            basisSetting.dateEnd.date = picker.startDate.format('YYYY/MM/DD HH:mm')
         });
        let maxDate = basisEndDate
        let minDate = referenceDate
        if([5, 6, 8, 7].includes(parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10))){
            maxDate = SaleInfo.first_stage_date
            minDate = SaleInfo.last_stage_date
        }
        basisSetting.maxDate = maxDate
        basisSetting.minDate = minDate
        /*
         * 活動期間日期初始化
         */
        const performanceStDt = $('#performance_st_dt').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD"
            },
            autoUpdateInput: false,
            singleDatePicker: true,
            minDate: referenceDate,
            maxDate: maxDate,
            startDate: (basisSetting.performance_st_dt)?basisSetting.performance_st_dt:referenceDate,
        }).on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD')
            $(this).val(date)
            basisSetting.performance_st_dt =  picker.startDate.format('YYYY/MM/DD') + '  00:00'
            //設定場次設定時間區間
            if(typeof timeCourse !== "undefined"){
                timeCourse.DateRangeStar = picker.startDate.format('YYYY/MM/DD')
            }
            /**
             * 修改場次設定 【具有特定場次、時間】 選項目，場次時間區間
             * call timeCourse function
             */
            if(timeCourse.DateRangeStar &&  timeCourse.DateRangeEnd){
                timeCourse.dateReset()
                timeCourse.changeDateRange()
            }
        });
        /*
         *  設定活動開始時間可選取範圍
         */
        function renewPerformanceStarDate(){
            // Destroy previous datepicker
            performanceStDt.daterangepicker('destroy');
            // Set previous value
            performanceStDt.daterangepicker({
                "locale": {
                    "format": "YYYY/MM/DD"
                },
                autoUpdateInput: false,
                singleDatePicker: true,
                minDate: basisSetting.infOpenDate,
                maxDate:basisSetting.infOpenDate,
                startDate: basisSetting.performance_st_dt,
            }).on('apply.daterangepicker', function(ev, picker) {
                let date = picker.startDate.format('YYYY/MM/DD')
                $(this).val(date)
                basisSetting.performance_st_dt =  picker.startDate.format('YYYY/MM/DD') + '  00:00'
                //設定場次設定時間區間
                if(typeof timeCourse !== "undefined"){
                    timeCourse.DateRangeStar = picker.startDate.format('YYYY/MM/DD')
                }
                /**
                * 修改場次設定 【具有特定場次、時間】 選項目，場次時間區間
                * call timeCourse function
                */
                if(timeCourse.DateRangeStar &&  timeCourse.DateRangeEnd){
                    timeCourse.dateReset()
                    timeCourse.changeDateRange()
                }
            });
            $('#performance_st_dt').val(basisSetting.transDateFormat(basisSetting.performance_st_dt))
        }
        //開催期間結束
        const performanceEndDt = $('#performance_end_dt').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD"
            },
            autoUpdateInput: false,
            singleDatePicker: true,
            minDate: minDate,
            maxDate: basisEndDate,
            startDate: (basisSetting.performance_end_dt)?basisSetting.performance_end_dt:basisCloseDate,
        }).on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD')
            $(this).val(date)
            basisSetting.performance_end_dt =  picker.startDate.format('YYYY/MM/DD') + '  23:59'
            //設定場次設定時間區間
            if(typeof timeCourse !== "undefined"){
                timeCourse.DateRangeEnd = picker.startDate.format('YYYY/MM/DD')
            }
            /**
             * 修改場次設定 【具有特定場次、時間】 選項目，場次時間區間
             * call timeCourse function
             */
            if(timeCourse.DateRangeStar &&  timeCourse.DateRangeEnd){
                timeCourse.dateReset()
                timeCourse.changeDateRange()
            }
        });
        /*
         *  設定活動結束時間可選取範圍
         */
        function renewPerformanceEndDate(){
            let endDate = new Date(nowDate.getFullYear(), nowDate.getMonth()+12, nowDate.getDate(), 0, 0, 0, 0)
            // Destroy previous datepicker
            performanceEndDt.daterangepicker('destroy');
            // Set previous value
            performanceEndDt.daterangepicker({
                "locale": {
                    "format": "YYYY/MM/DD"
                },
                autoUpdateInput: false,
                singleDatePicker: true,
                minDate: basisSetting.minDate,
                maxDate: endDate,
                startDate:basisSetting.performance_end_dt,
            }).on('apply.daterangepicker', function(ev, picker) {
                let date = picker.startDate.format('YYYY/MM/DD')
                $(this).val(date)
                basisSetting.performance_st_dt =  picker.startDate.format('YYYY/MM/DD') + '  00:00'
                //設定場次設定時間區間
                if(typeof timeCourse !== "undefined"){
                    timeCourse.DateRangeStar = picker.startDate.format('YYYY/MM/DD')
                }
                /**
                * 修改場次設定 【具有特定場次、時間】 選項目，場次時間區間
                * call timeCourse function
                */
                if(timeCourse.DateRangeStar &&  timeCourse.DateRangeEnd){
                    timeCourse.dateReset()
                    timeCourse.changeDateRange()
                }
            });
            $('#performance_end_dt').val(basisSetting.transDateFormat(basisSetting.performance_end_dt))
        }
        //預先售時間開始
        $('#earlyBirdDateStart').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD HH:mm"
            },
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            singleDatePicker: true,
            minDate: referenceDate,
            maxDate: basisEndDate,
            // <!-- STS 2021/05/28 販売期間のポップアップの販売開始の初期値を10：00に変更してください。  -->
            startDate: (basisSetting.earlyBirdDateStart)?basisSetting.earlyBirdDateStart:basisSalesStarDate,
        }).on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD  HH:mm')
            $(this).val(date)
            basisSetting.earlyBirdDateStart =  picker.startDate.format('YYYY/MM/DD  HH:mm')
         });
        //預先售時間結束
        $('#earlyBirdDateEnd').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD HH:mm"
            },
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            singleDatePicker: true,
            minDate: referenceDate,
            maxDate: basisEndDate,
            startDate: (basisSetting.earlyBirdDateEnd)?basisSetting.earlyBirdDateEnd:referenceDate,
        }).on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD  HH:mm')
            $(this).val(date)
            basisSetting.earlyBirdDateEnd =  picker.startDate.format('YYYY/MM/DD  HH:mm')
         });
         //販售日期開始
        $('#normalDateStart').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD  HH:mm"
            },
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            minDate: referenceDate,
            maxDate: basisEndDate,
            // <!-- STS 2021/05/28 販売期間のポップアップの販売開始の初期値を10：00に変更してください。  -->
            startDate: (basisSetting.normalDateStart)?basisSetting.normalDateStart:basisSalesStarDate,
        }).on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD  HH:mm')
            $(this).val(date)
            basisSetting.normalDateStart =  picker.startDate.format('YYYY/MM/DD  HH:mm')
        });
         //販售日期結束
         $('#normalDateEnd').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD  HH:mm"
            },
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            minDate: referenceDate,
            maxDate: basisEndDate,
            startDate: (basisSetting.normalDateEnd)?basisSetting.normalDateEnd:referenceDate,
        }).on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD  HH:mm')
            $(this).val(date)
            basisSetting.normalDateEnd =  picker.startDate.format('YYYY/MM/DD  HH:mm')
        });
         /**
          * sellsetting.blade (未)
          */
        $('#sellDate').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD HH:mm"
            },
            singleDatePicker: true,
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            minDate: basisStarDate,
            maxDate: basisEndDate
        });
        /**
          * sellsetting.blade (未)
          */
        $('#sellDate').on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('YYYY/MM/DD  HH:mm')
            $(this).val(date)
            sellSetting.cashDate =  picker.startDate.format('YYYY/MM/DD  HH:mm')
         });
    (function() {
         /**
          * @description  get logo imaga and content image data, save in local stock
          */
        function previewData(){
            let json = []
            let data = []
            let iamgeStatus = 0
            let logoImageStatus = false
            let contentImageStatus = false

            //get logo image scr data 
            let logoImageBox =document.getElementById('logoImage')
            let logoImagePreview =logoImageBox.getElementsByClassName('dropify-preview')[0]
            let logoImage = logoImagePreview.querySelectorAll('img')
            
            if(typeof(logoImage[0]) === "undefined"){
                logoImageStatus = false
                logoImageData = null
            }else{
                logoImageStatus = true
                logoImageData = logoImage[0].getAttribute('src')
            }

            //get content image scr data 
            let contentImageBox =document.getElementById('contentImageBox')
            let contentImagePreview = contentImageBox.getElementsByClassName('dropify-preview')[0]
            let contentImage = contentImagePreview.querySelectorAll('img')
            
            if(typeof(contentImage[0]) === "undefined"){
                contentImageStatus = false
                contentImageData = null
            }else{
                contentImageStatus = true
                contentImageData = contentImage[0].getAttribute('src')
            }

            
            if(contentImageStatus){
                iamgeStatus = 2
            }else if(logoImageStatus){
                iamgeStatus = 1
            }else{
                iamgeStatus = 0
            }

            data.push({
                logoImage   : logoImageData,
                contentImage: contentImageData
            })

            json.push({
                status: iamgeStatus,
                data  : data[0]
            })

             sessionStorage.setItem('previewData' ,JSON.stringify(json[0]))
        }
        /**
         * 活動管理資料存儲
         */
        <?php if( $eventData["status"] === 'edit'): ?>   
          $('#updateBtn,#preview').click(function (e) {
              e.preventDefault()
              tagControl.tagSave()
              previewData()

              //基本資訊檢查
              basisSetting.$validator.validateAll()  

              basisSetting.settingCheack()

              //票資料設定檢查
              if(tagControl.ticketTag){
                ticketSetting.settingInputPriceCheackAll()
              }

              //票面資料呈現頁檢查
              if(tagControl.ticketViewTag){
                ticketViewSetting.settingCheack()
                ticketViewSetting.$validator.validateAll() //STS 2021/07/30 Task 43
              }

              //let result = tagControl.getTicketViewData()
              let result = true
              if($(event.target).attr('id')=='updateBtn'){
                result = tagControl.getTicketViewData()
              }
              if(result){
                  let json = []
                  let basisData = basisSetting.saveLocalStock()
                  let timeData = timeCourse.getTimeSettingData()
                  let ticketData = ticketSetting.getTicketSettingData(1)
                  let sellData = sellSetting.getSellSettingData()
                  let ticketView = ticketViewSetting.getTicketViewData()
                  let mapData = seatSetting.getMapSettingData()

                //   document.getElementById('logo').files = document.getElementById('basisLogo').files
                //   document.getElementById('basisContent').files = document.getElementById('contentImnage').files
                //   document.getElementById('ticketViewLogo').files = document.getElementById('mobapassLogo').files
                  
                  json.push({
                      basisData : JSON.stringify(basisData),
                      timeData  : JSON.stringify(timeData),
                      ticketData : JSON.stringify(ticketData),
                      sellData: JSON.stringify(sellData),
                      mapData:  JSON.stringify(mapData),
                      ticketView: JSON.stringify(ticketView),
                      fbgCtrl: '<?php echo $eventData["fbgCtrl"]; ?>',
                      entry_time : '<?php echo e($eventData['entry_time']); ?>'
                  })
                  document.getElementById('settingContent').value = JSON.stringify(json)
                  $('<input/>', {type: 'hidden', name: 'article', value: JSON.stringify(articlesDisp)}).appendTo('#settingSend');
                  if($(event.target).attr('id')=='updateBtn'){
                    window.is_confirm = false
                    loading.openLoading()
                    $('#settingSend').attr('action', '/events/info/<?php echo e($eventData['performanceId']); ?>');
                    document.getElementById("settingSend").target = "";
                    document.getElementById("settingSend").submit();
                  } else if($(event.target).attr('id')=='preview'){
                    $('#settingSend').attr('action', '/events/preview/<?php echo e($eventData['performanceId']); ?>');
                    window.open('/events/preview/<?php echo e($eventData['performanceId']); ?>', 'preview','width=1200,height=800,scrollbars=yes');
                    document.getElementById("settingSend").target = "preview";  //TARGETをopen.window時のTARGETと合わせる
                    document.getElementById("settingSend").submit();
                  }
              }
          })
        <?php endif; ?>
        /**
         * 更新票卷資訊設定頁資料
         * other.blade
         */
        $('#ticketContent').click(function (e) {
            ticketViewSetting.getDateData()
        })
        /**
         * 活動管理資料新增
         */
        <?php if( $eventData["status"] !== 'edit'): ?>
          $('#addBtn,#preview').click(function (e) {
              e.preventDefault()
              tagControl.tagSave()
              previewData()
              let json = []
              let basisData = basisSetting.saveLocalStock()
              let timeData = timeCourse.getTimeSettingData()
              let ticketData = ticketSetting.getTicketSettingData(1)
              let sellData = sellSetting.getSellSettingData()
              let ticketView = ticketViewSetting.getTicketViewData()
              let mapData = seatSetting.getMapSettingData()
             
            //   document.getElementById('logo').files = document.getElementById('basisLogo').files
            //   document.getElementById('basisContent').files = document.getElementById('contentImnage').files
            //   document.getElementById('ticketViewLogo').files = document.getElementById('mobapassLogo').files
             
              json.push({
                 basisData : JSON.stringify(basisData),
                 timeData  : JSON.stringify(timeData),
                 ticketData : JSON.stringify(ticketData),
                 sellData: JSON.stringify(sellData),
                 mapData:  JSON.stringify(mapData),
                 ticketView: JSON.stringify(ticketView),
                 entry_time : '<?php echo e($eventData['entry_time']); ?>'
              })
              
              document.getElementById('settingContent').value = JSON.stringify(json)
              $('<input/>', {type: 'hidden', name: 'article', value: JSON.stringify(articles)}).appendTo('#settingSend');
              if($(event.target).attr('id')=='addBtn'){
                loading.openLoading()
                window.is_confirm = false
                $('#settingSend').attr('action', '/events/create');
                document.getElementById("settingSend").target = "";
                document.getElementById("settingSend").submit();
              } else if($(event.target).attr('id')=='preview'){
                $('#settingSend').attr('action', '/events/preview/' + basisSetting.eventId);
                window.open('/events/preview/' + basisSetting.eventId, 'preview','width=1200,height=800,scrollbars=yes');
                document.getElementById("settingSend").target = "preview";  //TARGETをopen.window時のTARGETと合わせる
                document.getElementById("settingSend").submit();
              }

          })
        <?php endif; ?>
    })();
    (function($) {
      // Tags Input
      var tagCount = 0;
      $('#keywords').tagsInput({
        width:'auto',
        defaultText: '<?php echo e(trans("events.S_add")); ?>',
        onAddTag: function(tag) {

          var text = $(this).val();
          var items = (text).split(/[ ,]+/);

          if (items.length > 100) {
            $(this).removeTag(tag);
          }
        },
      });     
      
      //sortable 1210 新增
      $(".sortable-box-wraper").sortable({
        tolerance: 'pointer',
        handle: '.box-header',
        forcePlaceholderSize: true,
        placeholder: 'placeholder',
        connectWith: '.sortable-box-wraper',
     
        forceHelperSize: true,
        start: function(event, ui) {
          ui.placeholder.height(ui.item.height());
          ui.placeholder.width(ui.item.width());
        },
        update: function(){
          //記事の順番を保持
          articleSeq = $(".sortable-box-wraper").sortable("toArray");
        }
      });  
     //sortable ticket 
     $(".ticket-sortable-box-wraper").sortable({
        tolerance: 'pointer',
        handle: '.box-header',
        forcePlaceholderSize: true,
        placeholder: 'placeholder',
        connectWith: '.sortable-box-wraper',
     
        forceHelperSize: true,
        start: function(event, ui) {
          ui.placeholder.height(ui.item.height());
          ui.placeholder.width(ui.item.width());
        },
        update: function(){
          //記事の順番を保持
          ticketSetting.ticketSort = $(this).sortable('toArray', { attribute:'data-key'})
        }
      });  
             //STS 2021/06/09 Task 15: sortable questionnaire  START
        $(".questionnaire-sortable-box-wraper").sortable({
            tolerance: 'pointer',
            handle: '.box-header',
            forcePlaceholderSize: true,
            placeholder: 'placeholder',
            connectWith: '.questionnaire-sortable-box-wraper',

            forceHelperSize: true,
            start: function(event, ui) {
                ui.placeholder.height(ui.item.height());
                ui.placeholder.width(ui.item.width());
            },
            update: function() {
                //記事の順番を保持
                questionnaireSeq = $(".questionnaire-sortable-box-wraper").sortable("toArray");
                for (let k = 0; k < questionnaireSeq.length; k++) {
                    let u = k + 1;
                    questionnaires[questionnaireSeq[k]].sort = u;
                }
            }
        });
        //STS 2021/06/09 Task 15 END 
    }(jQuery));

    (function() {
        var nowDate = new Date();
        var rangeOnChange = false
        var starDate = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0)
        var endDate = new Date(nowDate.getFullYear(), nowDate.getMonth()+1, nowDate.getDate(), 0, 0, 0, 0)
        var tt
       


        $('.daterange').daterangepicker({
            "locale": {
                "format": "YYYY/MM/DD"
            },
            "minDate": starDate,
            "startDate": starDate,
            "endDate": endDate
        });
 
        
        // var allEditors = document.querySelectorAll('.editor');
        // for (var i = 0; i < allEditors.length; ++i) {
            //"imageUpload",
            ClassicEditor
            .create( document.querySelector('#editor'),{
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
            } )
            .then(editor => {  
                myEditor = editor
                myEditor.model.document.on( 'change:data', () => {
                    basisSetting.wordLimit('editor')
                } );
            })
            .catch( error => {
                console.error( error );
            } );
    })();

    $(".select-create").select2({
        tags: true,
        language: "ja",
        tags: true,
        // STS 2021/06/01 place holderを"券種"に変更してください。
        placeholder: '券種',
        maximumInputLength: 30,
        closeOnSelect:true,
        matcher: matchCustom,
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
        basisSetting.oldID = event.target.id;
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
            else if(basisSetting.oldID != event.target.id)
            {
                $(this).val(null).trigger('change');
            }
            else $(this).val(tt).trigger('change');
        }
        //STS - 2021/06/10: Task 16.- END
    });

    function matchCustom(params, data) {
        //STS 2021/07/24 Task 38
       if(params.term.trim() !== ""){
            var tempStr = params.term.replace(/-+/g,'-');
            params.term = tempStr
        }
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
}


    const _colorList = [<?php echo \Config::get('constant.color'); ?>]
    const _textColorList = [<?php echo \Config::get('constant.color_text'); ?>]
    $('.colorPickSelector').colorPick({
        'initialColor': '#E9C489',
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
    //seat setting color pick
    $('#saetSettingColorPick').colorPick({
        'initialColor': '#FF748A',
        'allowRecent': true,
        'recentMax': 5,
        'palette': _textColorList,
        'onColorSelected': function() {
            let color = this.color
            seatSetting.specSeatColor = color
            this.element.css({'backgroundColor': this.color, 'color': this.color});
        }
    });
    $('.textColorPickSelector').colorPick({
        'allowRecent': true,
        'recentMax': 5,
        'palette':  [<?php echo \Config::get('constant.color_text'); ?>],
        'onColorSelected': function() {
            
            let id = this.element.data("position") 
            let type = this.element.data("seat-type") 
            let color = this.color

            this.element.css({'backgroundColor': this.color, 'color': this.color});
            ticketSetting.changeSeatColor(id, type, color)
        }
    });
    // dropify  0530新增
    $('.dropify').dropify({
      tpl: {
        wrap: '<div class="dropify-wrapper"></div>',
        loader: '<div class="dropify-loader"></div>',
        message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p><?php echo e(trans("common.S_DropifyMsg")); ?></p></div>',
        preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message"><?php echo e(trans("common.S_DropifyEdit")); ?></p></div></div></div>',
        filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
        clearButton: '<button type="button" class="btn"></button>',
        errorLine: '<p class="dropify-error"><?php echo e(trans("common.S_DropifyErr")); ?></p>',
        errorsContainer: '<div class="dropify-errors-container"><ul><?php echo e(trans("common.S_DropifyErr")); ?></ul></div>'
      },
      error: {
        'fileSize': '<?php echo e(trans("common.S_DropifySizeErr")); ?>'
        }
    });
    //STS Task 41 START
    var dropifyConfig = $('.dropify-config').dropify({
            tpl: {
            wrap: '<div class="dropify-wrapper"></div>',
            loader: '<div class="dropify-loader"></div>',
            message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p><?php echo e(trans("common.S_DropifyMsg")); ?></p></div>',
            preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message"><?php echo e(trans("common.S_DropifyEdit")); ?></p></div></div></div>',
            filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
            clearButton: '<button type="button" class="dropify-clear">X</button>',
            errorLine: '<p class="dropify-error"><?php echo e(trans("common.S_DropifyErr")); ?></p>',
            errorsContainer: '<div class="dropify-errors-container"><ul><?php echo e(trans("common.S_DropifyErr")); ?></ul></div>'
        },
        error: {
            'fileSize': '<?php echo e(trans("common.S_DropifySizeErr")); ?>'
        }
    });

    dropifyConfig.on('dropify.afterClear', function(event, element){
        if(event.target.id == 'contentImnage')
        {
            basisSetting.imageShow_del_flag = true
        } 
        else if(event.target.id == 'basisLogo')
        {
            basisSetting.eventLogo_del_flag = true
        }
        else
        {
            basisSetting.imageArticle_del_flag[event.target.id] = true
        }
    });
    //STS Task 41 END
  
    //ph3 1502で設置したがbugの為未使用
    //理想としてはfilesize時のメッセージを分けたい。 S_DropifySizeErr or S_DropifySizeErrMbps
    /*
    $('.dropifymbps').dropify({
      tpl: {
        wrap: '<div class="dropify-wrapper"></div>',
        loader: '<div class="dropify-loader"></div>',
        message: '<div class="dropify-message"><i class="fas fa-cloud-upload-alt"/> <p><?php echo e(trans("common.S_DropifyMsg")); ?></p></div>',
        preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message"><?php echo e(trans("common.S_DropifyEdit")); ?></p></div></div></div>',
        filename: '<p class="dropify-filename"><i class="fas fa-cloud-upload-alt"></i>  <span class="dropify-filename-inner"></span></p>',
        clearButton: '<button type="button" class="btn"></button>',
        errorLine: '<p class="dropify-error"><?php echo e(trans("common.S_DropifyErr")); ?></p>',
        errorsContainer: '<div class="dropify-errors-container"><ul><?php echo e(trans("common.S_DropifyErr")); ?></ul></div>'
      },
      error: {
        'fileSize': '<?php echo e(trans("common.S_DropifySizeErrMbps")); ?>'
        }
    });
*/

    //【返回上一頁】按鈕到訪，將自動重新整理網頁
    if ((!!window.performance && window.performance.navigation.type === 2) || (!!window.performance && window.performance.navigation.type === 255)){
       console.info('reload')
       window.location.reload();
    }

    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload()
        }
    };
    //timepicker
    $('.timepicker').mdtimepicker({
        // format of the time value (data-time attribute)
        timeFormat: 'hh:mm', 
        // format of the input value
        format: 'hh:mm tt',      
        // theme of the timepicker
        // 'red', 'purple', 'indigo', 'teal', 'green', 'dark'
        theme: 'blue',        
        // determines if input is readonly
        readOnly: true,       
        // determines if display value has zero padding for hour value less than 10 (i.e. 05:30 PM); 24-hour format has padding by default
        hourPadding: false,
        is24hour: true,
        // determines if clear button is visible  
        clearBtn: false

    });

    $('#specTimepicker').mdtimepicker().on('timechanged', function(e){
        timeCourse.specDate = e.time
        timeCourse.specDateChange()
    });

    $('#specTimepicker').mdtimepicker('setValue', timeCourse.specDate);

    $('#ruleTimePicker').mdtimepicker().on('timechanged', function(e){
        timeCourse.starDate = e.time
    });

    $('#ruleTimePicker').mdtimepicker('setValue', timeCourse.starDate);

    $('#speRuleTimePicker').mdtimepicker().on('timechanged', function(e){
        timeCourse.starDate = e.time
    });

    $('#speRuleTimePicker').mdtimepicker('setValue', timeCourse.starDate);
   
    function closeTimepicker(){
        $('.mdtp__button.cancel').click()
    }

    function setTimeInf(id, value) {
        $(id).val(value)
            .attr('data-time', value)
            .attr('value', value);
    }
    //timepicker

    window.setTimeout(function(){
        basisSetting.$validator.validateAll();
        timeCourse.$validator.validateAll();
        ticketViewSetting.$validator.validateAll(); //STS 2021/07/30 task 43
    }, 3000);
  </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/event/edit.blade.php ENDPATH**/ ?>