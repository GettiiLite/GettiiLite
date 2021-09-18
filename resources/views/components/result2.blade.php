<div id="pop-up-result">
    <div class="modal-mask" v-show="show">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">    
                    <h4 class="modal-title">
                        <i class="fas fa-exclamation-triangle" v-if="msmStatus == '{{ \Config::get('constant.message_status.error') }}'"></i>
                        <i class="fas fa-exclamation-circle" v-if="msmStatus == '{{ \Config::get('constant.message_status.warning') }}'"></i>
                        @{{ title }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <!--儲存結果-->
                                <h3  >
                                    <i class="fas fa-check-circle text-aqua" v-if="updateStatus"></i> 
                                    <i class="fas fa-times-circle text-red"  v-else></i>
                                    @{{ msn}}
                                </h3>
                             <!--儲存結果-->
                            <div class="modal-overflow"  v-if="msgShowFlag">
                                <!-- 0302 調整新增 樣式 -->
                                <div class="messages-content messages-save-content-pop">
                                    <p class="lead text-left">
                                        @{{ noteSub }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" >
                    <button class="btn btn-inverse pull-left" v-on:click="close()">
                        {{ trans('events.S_Close') }}
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
            title: '{{ trans('events.S_Result') }}',
            msn:[],
            msgShowFlag: false,
            note:'',
            noteSub:'',
            noteCustom:false,
        },
        methods: {
            open: function(json){
                let inf = JSON.parse(json.message) 

                this.msmStatus = inf.status
                this.updateStatus   = json.success
                this.title = inf.title   
                this.msn = inf.content

                this.show           = true
            },
            close: function(){
                this.updateStatus   = false
                this.title = '' 
                this.msn = ''

                this.show = false
            }
        }
    })
</script>