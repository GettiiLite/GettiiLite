<div id="pop-up-remind">
    <div class="modal-mask" v-show="show">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">    
                    <h4 class="modal-title">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ title }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <!--儲存結果-->
                                <h4>
                                    {{ subTitle }}
                                </h4>
                             <!--儲存結果-->
                            <div class="">
                                <div class="messages-content messages-save-content-pop">
                                    <p class="lead text-left" v-html="message">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" >
                    <button class="btn btn-default pull-left" v-on:click="close()">
                        <?php echo e(trans('events.S_Close')); ?>

                    </button>
                    <button  v-show='nextBtn' v-bind:class="nextBtnClass" v-on:click="next()">
                        {{ nextBtnText }} <!-- 連結到會員資料填寫 -->
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var remind = new Vue({
        el: '#pop-up-remind',
        data: {
            remindCode: `<?php echo e($remind_code); ?>`,
            show: false,
            title: `<?php echo trans('notice.S_ReminfTittle_1'); ?>`,
            subTitle: `<?php echo trans('notice.S_ReminfSubTittle_1'); ?>`,
            message: '',
            nextBtn: true,
            nextBtnClass: 'btn btn-info pull-right',
            nextBtnText: ''
        },
        mounted(){
            this.setMessage()
        },
        methods: {
            /*
             * 開啟
             */
            openLoading: function(){
                this.show = true
            },
            /*
             * 關閉
             */
            close: function(){
                this.show = false
            },
            /*
             * 設定提示 ui
             */
            setMessage: function(){
                switch(this.remindCode) {
                    case `<?php echo e(Config::get('constant.remind_code.inf')); ?>`:
                        this.subTitle = `<?php echo trans('notice.S_ReminfSubTittle_1'); ?>`
                        this.message = `<?php echo trans('notice.S_ReminfInf_1'); ?>`
                        this.nextBtnText = `<?php echo trans('notice.S_ReminfBtn_1'); ?>`
                        this.openLoading()
                        break;
                    case `<?php echo e(Config::get('constant.remind_code.password')); ?>`:
                        this.subTitle = `<?php echo trans('notice.S_ReminfSubTittle_2'); ?>`
                        this.message =  `<?php echo trans('notice.S_ReminfInf_2'); ?>`
                        this.nextBtnText = `<?php echo trans('notice.S_ReminfBtn_2'); ?>`
                        this.openLoading()
                        break;
                    case `<?php echo e(Config::get('constant.remind_code.draft')); ?>`:
                        this.title = `<?php echo trans('notice.S_ReminfTittle_3'); ?>`
                        this.subTitle = `<?php echo trans('notice.S_ReminfSubTittle_3'); ?>`
                        this.message =  `<?php echo trans('notice.S_ReminfInf_3'); ?>`
                        this.nextBtn = false
                        this.openLoading()
                        break;
                }
            },
            /*
             * 下一步
             */
            next: function(){
                switch(this.remindCode) {
                    case `<?php echo e(Config::get('constant.remind_code.inf')); ?>`:
                        window.location.href = '/userManage/editInf';
                        break;
                    case `<?php echo e(Config::get('constant.remind_code.password')); ?>`:
                        this.close()
                        adminSetting.openDialog()
                        break;
                    case `<?php echo e(Config::get('constant.remind_code.draft')); ?>`:
                        this.close()
                        adminSetting.openDialog()
                        break;
                }
            }
        }
    })
</script>
<?php /**PATH /home/vagrant/code/gettiilite/resources/views/components/remind.blade.php ENDPATH**/ ?>