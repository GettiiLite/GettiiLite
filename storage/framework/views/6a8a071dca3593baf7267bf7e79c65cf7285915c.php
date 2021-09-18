<?php $CommonPresenter = app('App\Presenters\CommonPresenter'); ?>
<?php $EditListPresenter = app('App\Presenters\EditListPresenter'); ?>


<?php $__env->startSection('title', 'Gettii Lite'); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>
    <?php echo e(trans('events.S_mainTitle_01')); ?>

    
</h1>
<!-- 網站導覽 -->
<ol class="breadcrumb">
    
    <li class="active"><?php echo e(trans('events.S_mainTitle_02')); ?></li>
</ol>
<!-- /.網站導覽 -->
<?php if(session('event_info_flg') == 2): ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- 0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
    <?php if(!session('root_account') && session('event_info_flg') == 2): ?>
        <div class="funtion-btn-block">
            <a href="/events/create" class="btn waves-effect waves-light btn-rounded btn-primary"><i class="fas fa-plus ml-0 mr-15x"></i> <?php echo e(trans('events.S_newEvent_btn')); ?>        
            </a>
        </div>
    <?php endif; ?>
    <!-- /.0511 固定功能按鈕 ｜ 匯出，更新，新規登錄，申請 放置區 -->
    <!-- Content Wrapper. Contains page content -->
    <div id="eventList">
      <!-- ========== 刪除設定 ========== -->
            <!-- modal -->
            <div class="modal-mask" id="stop-setting" v-show="showModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- form step 1 刪除確認-->
                                <h4 class="modal-title" v-show="deleteStep == 1"><i class="fas fa-exclamation-triangle"></i> <?php echo e(trans('events.S_eventDelTitle')); ?></h4>
                            <!-- form step 1 刪除確認-->
                            <!-- form step 2 刪除結果-->
                                <h4 class="modal-title" v-show="deleteStep == 2"><?php echo e(trans('events.S_eventDelResult')); ?></h4>
                            <!-- form step 2 刪除結果-->
                        </div>
                        <div class="modal-body">
                            <!-- form step 1 刪除確認-->
                                <div class="row form-horizontal" v-show="deleteStep == 1">
                                    <div class="col-md-12">
                                        <h4 class="text-red">
                                            <i class="fas fa-exclamation-triangle text-red"></i> 
                                             <?php echo e(trans('events.S_eventDelNotice')); ?>

                                        </h4>
                                        <div class="modal-overflow">
                                            <ul class="modal-list">
                                                <li><span class="badge bg-gray-light"><?php echo e(trans('events.S_eventcodeTitle')); ?></span> {{ performanceCode }}</li>
                                                <li><span class="badge bg-gray-light"><?php echo e(trans('events.S_eventMaintitleTitle')); ?></span> {{ performanceName }}</li>
                                                <li><span class="badge bg-gray-light"><?php echo e(trans('sellManage.S_EventOpenDate')); ?></span> {{ performanceStDt }} ~ {{ performanceEndDt }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <!--</div>-->
                            <!-- /.form  -->
                            <!-- form  step 2 結果-->
                            <div class="row form-horizontal" v-show="deleteStep == 2">
                                    <div class="col-md-12">
                                        <h4 class="text-red">
                                            <i class="fas fa-exclamation-triangle text-red"></i> 
                                            {{ deleteMsn }}
                                        </h4>
                                    </div>
                                </div>
                            <!-- /.form  -->
                        </div>
                        <div class="modal-footer">
                            <!-- form step 1 刪除確認-->
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" v-show="deleteStep == 1" v-on:click="cancel()">
                                    <?php echo e(trans('events.S_cancelBtn')); ?>

                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#check-stop-setting" v-show="deleteStep == 1" v-on:click="confirm()">
                                    <?php echo e(trans('events.S_eventDel')); ?>

                                </button>
                            <!-- form step 1 刪除確認-->
                            <!-- form step 2 刪除結果-->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#check-stop-setting" v-show="deleteStep == 2" v-on:click="cancel()">
                                    <?php echo e(trans('events.S_eventDel')); ?>

                                </button>
                            <!-- form step 2 刪除結果-->
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        <!-- ========== End of 刪除設定 ========== -->
        <!-- delete event form -->
        <form id="deleteSend" method="POST" style="visibilitsectionay: table;" action="/events/delete">
            <input type="hidden" name="json" v-model="json">
            <?php echo e(csrf_field()); ?>

        </form>
        <!-- delete event form -->
        <!-- Main content -->
            <!-- 查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
            <form method="GET" action="/events/filter">
                <?php echo e(csrf_field()); ?>

                <div class="box no-border">
                    <div class="box-header with-border-non" data-widget="collapse">
                        <h3 class="box-title"><?php echo e(trans('events.S_searchTitle')); ?></h3>
                        <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="">
                        <div class="col-md-6 form-group">
                            <label><?php echo e(trans('events.S_searchKeyword')); ?></label>
                            <input name="keyword" class="form-control input-sm" type="text" value="<?php echo e($events['keyword']); ?>" placeholder="">
                        </div>
                        <div class="col-md-6 form-group">
                            <label><?php echo e(trans('events.S_searchStatus')); ?></label>
                            <select name="statusSelect[]" class="form-control select2" multiple="multiple" data-placeholder="" style="width: 100%;">
                                <option <?php echo e($events["filterStatus"]['0']  == "0" ? 'selected="selected"' : ""); ?> value="0"><?php echo e(trans('common.S_StatusCode_0')); ?></option>
                                <option <?php echo e($events["filterStatus"]['1']  == "0" ? 'selected="selected"' : ""); ?> value="1"><?php echo e(trans('common.S_StatusCode_1')); ?> </option>
                                <option <?php echo e($events["filterStatus"]['2']  == "0" ? 'selected="selected"' : ""); ?> value="2"><?php echo e(trans('common.S_StatusCode_2')); ?></option>
                                <option <?php echo e($events["filterStatus"]['3']  == "0" ? 'selected="selected"' : ""); ?> value="3"><?php echo e(trans('common.S_StatusCode_2_1')); ?></option>
                                <option <?php echo e($events["filterStatus"]['4']  == "0" ? 'selected="selected"' : ""); ?> value="4"><?php echo e(trans('common.S_StatusCode_3')); ?></option>
                                <option <?php echo e($events["filterStatus"]['5']  == "0" ? 'selected="selected"' : ""); ?> value="5"><?php echo e(trans('common.S_StatusCode_4')); ?></option>
                                <option <?php echo e($events["filterStatus"]['6']  == "0" ? 'selected="selected"' : ""); ?> value="6"><?php echo e(trans('common.S_StatusCode_5')); ?></option>
                                <option <?php echo e($events["filterStatus"]['7']  == "0" ? 'selected="selected"' : ""); ?> value="7"><?php echo e(trans('common.S_StatusCode_6')); ?></option>
                                <option <?php echo e($events["filterStatus"]['8']  == "0" ? 'selected="selected"' : ""); ?> value="8"><?php echo e(trans('common.S_StatusCode_7')); ?></option>
                                <?php if(!session('admin_flg')): ?>
                                    <option <?php echo e($events["filterStatus"]['9']  == "0" ? 'selected="selected"' : ""); ?> value="9"><?php echo e(trans('common.S_StatusCode_8')); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right ">
                    <button type="submit" class="btn waves-effect waves-light btn-angle btn-info"><?php echo e(trans('events.S_SearchBtn')); ?></button>
                </div>
                </div>
                <!-- /.查詢 box1 統一樣式兩欄 + 收合 ＋ 查詢按鈕 -->
                <!-- Filter + Page + table -->
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="m-b-20 " style="display: none;">
                    <!-- Item pre page -->
                    <div class="dataTables_formgroup">
                        <div class="dataTables_length" id="">
                            <label>Item pre page
                                <select name="pageShow" aria-controls="" class="form-control input-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                            </label>
                    </div>
                        <!-- /.Item pre page -->
                        <!-- Filter -->

                        <div class="dataTables_filter" id="">
                            <label>表示順
                                <select name="orderBySelect" aria-controls="" class="form-control input-sm">
                                    <option value="情報公開日">情報公開日</option>
                                    <option value="販售期間">販售期間</option>
                                    <option value="登錄日">登錄日</option>
                                </select>
                            </label>
                        </div>

                        <div class="filter-tools">
                            <button type="button" class="btn btn-filter" data-widget=""><i class="fas fa-sort-amount-up"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.Filter -->
                </div>
            </form>
            <!--  /.Filter & Page -->
            
                <?php if(!is_null($events['data'])): ?>
                    <?php $__currentLoopData = $events['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!-- Box 3   status ＋ 標題 ＋ label ＋ 表格 須加樣式 no-radius-->
                        <div class="box box-solid collapsed-box no-radius">
                            <!---box-header--->
                            <div class="box-header with-border box-s3" data-widget="collapse">
                            <div class="flex-column-center">
                                <div class="status-box <?php echo e($EditListPresenter->getStatusClass($event['status'], $event['sale_type'])); ?>">
                                    <?php echo e($CommonPresenter->getStatusString($event['status'], $event['sale_type'])); ?>

                                </div>
                                <div class="status-timeout">
                                <?php if($event['performance_status'] == \Config::get('constant.performance_status.sale') && !$event['sale_type'] && $event['trans_flg'] == \Config::get('constant.GETTIIS_trans.already')): ?>
                                            <i class="fas fa-stop-circle"></i> 一時停止
                                        <?php endif; ?>
                                </div>
                            </div>
                            <div class="title-row-box">
                                <div class="box-subtitle">
                                    <span class="label label-info-outline"> 
                                        <?php if(config('app.debug') == true): ?>
                                        
                                            <?php echo e($event['performance_id'] ." - ".$event['performance_code']); ?>

                                        <?php else: ?>
                                            <?php echo e($event['performance_code']); ?>

                                        <?php endif; ?>
                                    </span> 
                                    <small class="subtitle">
                                        <?php echo e($event['performance_name_sub']); ?>

                                        <!--<?php if($event['performance_status'] == \Config::get('constant.performance_status.sale') && !$event['draft_sale_flg']): ?>
                                            一時暫停
                                        <?php endif; ?>-->
                                    </small>
                                </div>

                                <h3 class="box-title">
                                    <?php echo e($event['performance_name']); ?>

                                </h3>
                            </div>
                            <!-- 收合 開關 -->
                            <div class="box-tools">
                                <button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.收合 開關 -->
                            </div>
                            <!---/.box-header--->
                            <div class="box-body">
                            <div class="row-group-grid">
                                <!-- Row1 -->
                                <div class="row ">
                                    <div class="col-xs-1 col-sm-1 grid-title"><?php echo e(trans('events.S_EventLocationTitle')); ?></div>
                                    <div class="col-xs-11 col-sm-11 grid-text">
                                        <?php echo e($event['hall_disp_name']); ?>

                                    </div>
                                </div>
                                <!-- /.Row1 -->
                                <!-- Row2 -->
                                <div class="row">
                                    <div class="col-xs-1 col-sm-1 grid-title"><?php echo e(trans('events.S_eventPeriodTitle')); ?></div>
                                    <div class="col-xs-3 col-sm-3 grid-text"><?php echo e($event['performance_st_dt']); ?> ~ <?php echo e($event['performance_end_dt']); ?></div>
                                    <div class="col-xs-1 col-sm-1 grid-title"><?php echo e(trans('events.S_eventPublishDateTitle')); ?></div>
                                    <div class="col-xs-3 col-sm-3 grid-text"><?php echo e($event['disp_start']); ?>

                                        <span class="badge bg-red">
                                            <?php echo e(($event['trans_flg']==config('constant.GETTIIS_trans.yet'))?trans('events.S_eventNotPublish'):trans('events.S_eventPublished')); ?>

                                        </span>
                                        <span class="badge bg-red">
                                            <?php if($event['status'] > config('constant.performance_disp_status.browse')): ?>
                                                <?php if($event['edit_status'] == config('constant.edit_status.not') ): ?>
                                                    <?php echo e(trans('common.S_EditStatus_not')); ?>

                                                <?php elseif($event['edit_status']  == config('constant.edit_status.going')): ?>
                                                    <?php echo e(trans('common.S_EditStatus_going')); ?>

                                                <?php elseif($event['edit_status']  == config('constant.edit_status.complete')): ?>
                                                    <?php echo e(trans('common.S_EditStatus_complete')); ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 grid-title"><?php echo e(trans('events.S_eventSellDateTitle')); ?></div>
                                    <div class="col-xs-3 col-sm-3 grid-text"><?php echo e($event['reserve_st_date']); ?> ~ <?php echo e($event['reserve_cl_date']); ?></div>
                                </div>
                                <!-- /.Row2 -->
                                <!-- Row3 -->
                                <div class="row ">
                                    <!--<div class="col-xs-1 col-sm-1 grid-title"><?php echo e(trans('events.S_eventOperationTitle')); ?></div>-->
                                    <div class="col-xs-12 col-sm-12 grid-btns flex-center">
                                        <?php if($event['status'] !== config('constant.performance_disp_status.deleted')): ?>
                                            <a href="/events/info/<?php echo e($event['performance_id']); ?>" class="btn btn-info-outline btn-mm"><?php echo e(trans('events.S_eventOperationModify')); ?></a> 
                                            <?php if($event['trans_flg']!=config('constant.GETTIIS_trans.yet')): ?>
                                              <a  onclick="copyToClipBoard('copyText<?php echo e($event['performance_id']); ?>');" class="btn btn-darkblue-outline btn-mm"><?php echo e(trans('events.S_eventOperationCopylink')); ?></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <input  type="hidden" id="copyText<?php echo e($event['performance_id']); ?>" value="<?php echo e(config('app.gsdomain')); ?>/event/detail/<?php echo e($event['user_code']); ?>/<?php echo e($event['performance_code']); ?>">
                                        <?php if($event['trans_flg'] === config('constant.GETTIIS_trans.yet') && $event['edit_status'] === config('constant.edit_status.complete')  && $event['status'] >= config('constant.performance_disp_status.browse') &&  session('event_info_flg') == 2): ?>
                                            <form style="display: inline" method="POST" action="/events/trans/" @submit="submit">
                                                <input type="hidden" name="performance_id" value="<?php echo e($event['performance_id']); ?>">
                                                <button type="submit" class="btn btn-primary-outline btn-mm">
                                                    <?php echo e(trans('events.S_eventOperationPublish')); ?>

                                                </button>
                                                <?php echo e(csrf_field()); ?>

                                            </form>
                                        <?php elseif($event['edit_status'] === config('constant.edit_status.complete') && $event['status'] >= config('constant.performance_disp_status.browse') && session('event_info_flg') == 2): ?>
                                            <form style="display: inline" method="POST" action="/events/republish/" @submit="submit">
                                                <input type="hidden" name="performance_id" value="<?php echo e($event['performance_id']); ?>">
                                                <button type="submit" class="btn btn-primary-outline btn-mm">
                                                    <?php echo e(trans('events.S_eventOperatioRenPublish')); ?>

                                                </button>
                                                <?php echo e(csrf_field()); ?>

                                            </form>
                                        <?php endif; ?>
                                        <div class="line"></div>
                                        <?php if(
                                            ($event['status'] == config('constant.performance_disp_status.going')        ||
                                             $event['status']  == config('constant.performance_disp_status.complete')    ||                                
                                             $event['status']  == config('constant.performance_disp_status.browse'))
                                             &&  session('event_info_flg') == 2
                                        ): ?>
                                            <a class="btn btn-danger btn-mm" v-on:click="openDeleteDialog(<?php echo e($loop->index); ?>)"><?php echo e(trans('events.S_eventDelBtn')); ?></a> 
                                            <div class="tip"><span data-tooltip="<?php echo e(trans('events.S_eventDelTooltip')); ?>"><i class="fas fa-info fa-1x"></i></span>
                                            </div> 
                                        <?php elseif($event['status'] == config('constant.performance_disp_status.deleted')): ?>

                                        <?php elseif($event['stop_button_show']&&  session('event_info_flg') == 2): ?>
                                            <a href="/schedule/list/<?php echo e($event['performance_id']); ?>/0" class="btn btn-danger btn-mm"  v-on:click="submit"><?php echo e(trans('events.S_eventSuspension')); ?></a> 
                                            <div class="tip">
                                                <span data-tooltip="<?php echo e(trans('events.S_eventSuspensionTooltip')); ?>">
                                                    <i class="fas fa-info fa-1x"></i>
                                                </span>
                                            </div> 
                                        <?php else: ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- /.Row3 -->
                            </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endif; ?>
            </div>
            <!-- /.Box 3   status ＋ 標題 ＋ label ＋ 表格 -->
            <div class="row m-b-20 ">
                <?php if(!is_null($events['paginator'])): ?>
                    <!-- Page navigation -->
                    <div class="col-sm-12">
                        <nav aria-label="Page navigation" class="pull-right">
                            <?php echo e($events['paginator']->links()); ?>

                        </nav>
                    </div>
                    <!-- /.Page navigation -->
                <?php endif; ?>
            </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    
    
    
    <!-- =============================================== -->
    <?php $__env->startComponent('components/result'); ?>
        
    <?php if (isset($__componentOriginal6441fbc97ccf63f19548e07f87a84d22377426f6)): ?>
<?php $component = $__componentOriginal6441fbc97ccf63f19548e07f87a84d22377426f6; ?>
<?php unset($__componentOriginal6441fbc97ccf63f19548e07f87a84d22377426f6); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <script>
        // sidebar 
        $(document).ready(function () {
        $('.sidebar-menu').tree()
        })
        $(function () {
        //Initialize Select2 Elements - multiple & tag
        $('.select2').select2()
        })
        //debug 需要
        Vue.config.debug = true;
        Vue.config.devtools = true;
        //debug 需要
        var eventList = new Vue({
            el: '#eventList',
            data: { 
                showModal: false,
                deleteStep:'',
                deleteMsn:'',
                performanceData: '',
                performanceId: '',
                performanceCode: '',
                performanceName: '',
                performanceStDt:'',
                performanceEndDt: '',
                json:[],
            },
            mounted:function(){
                let data = '<?php echo addslashes($events["data_json"]); ?>'
                this.performance_data = JSON.parse(data)

                <?php if($events['performance_delete_result']): ?>
                    this.showModal  = true
                    this.deleteStep = 2
                    this.deleteMsn  = '<?php echo e($events['performance_delete_msn']); ?>'
                <?php endif; ?>

                <?php if($events['performance_republish_result']): ?>
                    let errorJson = '<?php echo $events['performance_republish_msn']; ?>'
                    popUpResult.open(errorJson)
                <?php endif; ?>
              
                <?php if(array_key_exists('performance_add_result',$events) &&  $events['performance_add_result']): ?>
                    let errorJson = '<?php echo $events['performance_add_msn']; ?>'
                    popUpResult.open(errorJson)
                <?php endif; ?>
            },
            methods: {
                submit: function(){
                    loading.openLoading()
                },
                openDeleteDialog:function(index){
                    let data = this.performance_data[index]
                    this.deleteStep         = 1
                    this.performanceId      = data['performance_id']
                    this.performanceCode    = data['performance_code'] 
                    this.performanceName    = data['performance_name'] 
                    this.performanceStDt    = data['performance_st_dt'] 
                    this.performanceEndDt   = data['performance_end_dt'] 
                   
                    this.showModal = true
                },
                cancel:function(){
                    this.showModal = false
                    this.deleteStep         = ""
                    this.performanceId      = ""
                    this.performanceCode    = "" 
                    this.performanceName    = ""
                    this.performanceStDt    = ""
                    this.performanceEndDt   = ""
                },
                confirm:function(){
                    let data = []
                    let json = []

                    data.push({
                        performationId : this.performanceId,
                    })
                    json.push({
                        data : data,
                    })
                    this.json = JSON.stringify(json)
                    this.$nextTick(() => {
                        document.getElementById("deleteSend").submit();
                    })
                },
            },
        })
        
        function copyToClipBoard(idname) {
          var anyText= document.getElementById(idname).value;
          var textBox = document.createElement("textarea");
          textBox.setAttribute("id", "target");
          textBox.setAttribute("type", "hidden");
          textBox.textContent = anyText;
          document.body.appendChild(textBox);

          textBox.select();
          document.execCommand('copy');
          document.body.removeChild(textBox);
          
          alert('<?php echo e(trans("events.S_eventUrlCopy")); ?>');
        }

        window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload()
        }
    };
   </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/event/index.blade.php ENDPATH**/ ?>