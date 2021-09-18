<div id="pop-up-result">
    <div class="modal-mask" v-show="show">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">    
                    <h4 class="modal-title">
                        <i class="fas fa-exclamation-triangle" v-if="msmStatus == '<?php echo e(\Config::get('constant.message_status.error')); ?>'"></i>
                        <i class="fas fa-exclamation-circle" v-if="msmStatus == '<?php echo e(\Config::get('constant.message_status.warning')); ?>'"></i>
                        <!--0302 調整新增-->
                       <!-- <i class="fas fa-info-circle" v-if="msmStatus == 'I'"></i>-->
                        <template v-if="!titleCustom">
                            <?php echo e(trans('events.S_Result')); ?>

                        </template>
                        <template v-else>
                            {{ title }}
                        </template>  
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <!--儲存結果-->
                                <h3  v-if="updateStatus">
                                    <!--0302 調整新增-->
                                    <i class="fas fa-check-circle text-aqua"></i> 
                                    <template v-if="!titleCustom">
                                        <?php echo e(trans('events.S_Success')); ?>

                                    </template>
                                    <template v-else>
                                        {{ msn[0].msn }}
                                    </template>
                                    <!--0302 調整新增-->
                                </h3>
                                <h3  v-else>
                                    <!--0302 調整新增-->
                                    <i class="fas fa-times-circle text-red"></i> 
                                    <template v-if="!noteCustom">
                                        <?php echo e(trans('events.S_Failed')); ?>

                                    </template>
                                    <template v-else>
                                        {{ note }}
                                    </template>
                                    <!--0302 調整新增-->
                                </h3>
                               <!--0302 調整新增
                                <h3  v-else>
                                    <i class="fas fa-exclamation-circle text-yellow"></i> <?php echo e(trans('events.S_Warning')); ?>

                                </h3>
                                -->
                             <!--儲存結果-->
                            <div class="modal-overflow"  v-if="msgShowFlag">
                                <!-- 0302 調整新增 樣式 -->
                                <div class="messages-content messages-save-content-pop">
                                    <p class="lead text-left" v-if="!updateStatus">
                                        <template v-if="!noteCustom">
                                            <?php echo e(trans('events.S_Notice')); ?>

                                        </template>
                                        <template v-else>
                                            {{ noteSub }}
                                        </template>
                                    </p>
                                    <p class="lead text-left" v-else>
                                        <?php echo e(trans('events.S_Notice2')); ?>

                                    </p>
                                    <ol class="result-tab-title">
                                        <!--<li>票券資訊設定
                                            <ul class="result-tab-subtitle">
                                                若有需要標註的文字可以用 <span class="text-blue"> 文字 </span>
                                                <li><span class="text-blue">( 代碼 089578897 )</span> 您的商戶圖片格式錯誤，因而無法成功使用，請確認格式</li>
                                                <li>您的商戶名稱欄位有誤</li>
                                            </ul>
                                        </li>-->
                                        <li v-for="data in msn">{{ data.title }}
                                            <ul class="result-tab-subtitle">
                                                <li v-if="data.msn.length > 0">{{ data.msn }}</li>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" >
                    <button class="btn btn-inverse pull-left" v-on:click="close()">
                        <?php echo e(trans('events.S_Close')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var popUpResult = new Vue({
        el: '#pop-up-result',
        data: {
            show: false,
            updateStatus:'',
            msmStatus:'',
            titleCustom: false,
            title: '',
            msn:[],
            msgShowFlag: false,
            note:'',
            noteSub:'',
            noteCustom:false,
        },
        methods: {
            open: function(json){
                console.info('popUpResult')
                let inf = JSON.parse(json) 
                
                this.updateStatus   = inf.status.update_status
                this.msmStatus      = inf.status.msn_status
                this.msn            = inf.data.msn

                if(typeof(inf.status.title_custom) == 'undefined'){
                    this.titleCustom = false
                }else{
                    this.titleCustom = true
                    this.title       = inf.data.title   
                }
               
                if(typeof(inf.status.note_custom) != 'undefined'){
                    this.noteCustom = true
                    this.note = inf.data.note
                    this.noteSub = inf.data.note_sub
                }

                if( !this.updateStatus || (this.msn.length > 0 && !this.titleCustom)) {
                    this.msgShowFlag = true
                }
                else {
                    this.msgShowFlag = false
                }
                
                this.show           = true
                
            },
            close: function(){
                this.updateStatus   = false
                this.msmStatus      = ''
                this.msn            = []

                this.show = false
                this.msgShowFlag = false
            }
        }
    })
</script><?php /**PATH /home/vagrant/code/gettiilite/resources/views/components/result.blade.php ENDPATH**/ ?>