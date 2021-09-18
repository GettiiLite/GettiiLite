<div id="sellSetting">
    <!-- //sell-setting-wrap 販賣條件設定專用class -->
    <div class="sell-setting-wrap">
        <div class="form-horizontal">
            <!-- col -->
            <div class="col-md-12">
            <!--提示訊息-->
            <div class="callout callout-info">
                <p> <?php echo trans('events.S_sellPrompt'); ?></p>
            </div>
            <!--/.提示訊息-->
            <!-- BOX 1 -->
            <div class="box no-border">
                <!---box-header--->
                <div class="box-header with-border-non">
                    <h3 class="box-title"> <?php echo e(trans('events.S_sellMethodTitle')); ?></h3>
                </div>
                <!---/.box-header--->
                <!---box-body--->
                <div class="box-body">
                    <div class="col-md-12">
                        <!-- 1 -->
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-checkbox">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" v-model="creditCard" :disabled="statucControl[0].pay_method"><?php echo e(trans('events.S_sellMethodCCTitle')); ?>

                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="form-group-flex col-sm-offset-1 mb-3">
                                    <label class="control-label"><?php echo e(trans('events.S_sellLimitTitle')); ?></label>
                                    <div class="col-md-2 w-15">
                                        <select class="form-control" v-model="creditCardLimit" :disabled="statucControl[0].receive_limit">
                                            <option v-for="n in 20" :value="n">{{ n }}</option> 
                                        </select>
                                    </div>
                                    <label class="control-label"><?php echo e(trans('events.S_sellLimitUnit_01')); ?></label>
                                </div>
                                <div class="col-md-11 col-sm-offset-1 form-checkbox-groupbox">
                                    <div class="form-checkbox-title"><?php echo e(trans('events.S_sellPickupTitle')); ?></div>
                                        <!-- Disable mobapass at hotfix_R06 -->
                                        <div class="col-md-2 form-checkbox form-line-m10" v-show="onlineGetTicket">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" v-model="onlineGetTicket" :disabled="onlineGetTicket || statucControl[0].pickup_method || !creditCard"><?php echo e(trans('events.S_sellPickupMobapass')); ?>

                                            <div class="control__indicator"></div>
                                        </label>
                                        </div>
                                    <?php if((\App::getLocale() == "zh-tw" )): ?>
                                      <div class="col-md-2 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="qrPassEmail" :disabled="statucControl[0].pickup_method || !creditCard">QRPASS -Email
                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                      <div class="col-md-2 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="qrPassSms" :disabled="statucControl[0].pickup_method || !creditCard">QRPASS -簡訊
                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                      <div class="col-md-2 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="ibon" :disabled="statucControl[0].pickup_method || !creditCard">ibon
                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                    <?php endif; ?>
                                    <?php if((\App::getLocale() == "ja" )): ?>
                                      <div class="col-md-3 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                            <!--STS 2021-07-22 Task 39-->
                                              <input type="checkbox" v-model="sevenEleven" :disabled="statucControl[0].pickup_method || !creditCard">セブン-イレブン
                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                    <!-- 0826 新增日本版取票方式 -->
                                    <div class="col-md-3 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="resuq" :disabled="statucControl[0].pickup_method || !creditCard"><?php echo e(trans('events.S_sellPickupResuq')); ?>

                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                    <?php endif; ?>
                                    <!-- 0304 新增取票方式-不取券 -->
                                    <div class="col-md-3 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="noTicketing" :disabled="statucControl[0].pickup_method || !creditCard"><?php echo e(trans('events.S_sellPickupNoTicketing')); ?>

                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                    <!--
                                    <div class="form-checkbox form-line-m10">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" v-model="onlineGetTicket" :disabled="statucControl[0].pickup_method"><?php echo e(trans('events.S_sellPickupMobapass')); ?>

                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--checkbox">
                                            <input type="checkbox" v-model="qrPass" :disabled="statucControl[0].pickup_method">QR pass
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    -->
                                    
                                </div>
                                
                            </div>
                        </div>
                        <!-- /.1 -->
                        <?php if((\App::getLocale() == "zh-tw" )): ?>
                        <hr>
                            <!-- 2 -->
                            <!-- 10/4 新增 調整 -->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-checkbox">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" v-model="ibonGetTicket" :disabled="statucControl[0].basis">ibon 付款取票
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="form-group-flex col-sm-offset-1 mb-3">
                                        <label class="control-label">上限張數</label>
                                        <div class="col-md-2 w-15">
                                            <select class="form-control" v-model="ibonTicketLimit" :disabled="statucControl[0].receive_limit">
                                                <option v-for="n in 4" :value="n">{{ n }}</option> 
                                            </select>
                                        </div>
                                        <div class="txt-flex-mr">張 / 訂單</div>
                                    </div>
                                    <!---->
                                    <div class="form-group-flex col-sm-offset-1 mb-3">
                                        <label class="control-label">訂單期限</label>
                                        <div class="col-md-2 w-15">
                                            <select class="form-control" v-model="ibonDateLimit" :disabled="statucControl[0].receive_limit">
                                                <option v-for="n in 10" :value="n">{{ n }}</option> 
                                            </select>
                                        </div>
                                    <div class="txt-flex-mr">日</div>
                                </div>
                            </div>
                          </div>
                          <!-- /.2 -->
                          <div class="col-sm-offset-1 from-memo-list">
                            <ol>
                              <li>ibon取票可分信用卡付款取票與ibon付款取票</li>
                              <li>請注意訂單期限</li>
                            </ol>
                          </div>
                        <?php endif; ?>
                        <?php if((\App::getLocale() == "ja" )): ?>
                          <hr>
                        <!-- 2 -->
                            <!--  711付款取票-->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-checkbox">
                                        <label class="control control--checkbox">
                                            <!--STS 2021-07-22 Task 39-->
                                            <input type="checkbox" v-model="sevenElevenGetTicket" :disabled="statucControl[0].basis">セブン-イレブン決済
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="form-group-flex col-sm-offset-1 mb-3">
                                        <label class="control-label">上限枚数</label>
                                        <div class="col-md-2 w-15">
                                            <select class="form-control" v-model="sevenElevenTicketLimit" :disabled="statucControl[0].receive_limit">
                                                <option v-for="n in 20" :value="n">{{ n }}</option> 
                                            </select>
                                        </div>
                                        <div class="txt-flex-mr">枚 / 予約</div>
                                    </div>
                                    <!---->
                                    <div class="form-group-flex col-sm-offset-1 mb-3">
                                        <label class="control-label">有効期限</label>
                                        <div class="col-md-2 w-15">
                                            <select class="form-control" v-model="sevenElevenDateLimit" :disabled="statucControl[0].receive_limit">
                                                <option v-for="n in 10" :value="n">{{ n }}</option> 
                                            </select>
                                        </div>
                                    <div class="txt-flex-mr">日</div>
                                </div>
                                    <!--0310-->
                                    <div class="col-md-11 col-sm-offset-1 form-checkbox-groupbox">
                                    <div class="form-checkbox-title"><?php echo e(trans('events.S_sellPickupTitle')); ?></div>
                                      <div class="col-md-3 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                            <!--STS 2021-07-22 Task 39-->
                                              <input type="checkbox" v-model="sevenElevenSEJ" :disabled="statucControl[0].basis || !sevenElevenGetTicket">セブン-イレブン
                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                      <!-- 0325 resuq -->
                                      <div class="col-md-3 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="resuqSEJ" :disabled="statucControl[0].basis || !sevenElevenGetTicket"><?php echo e(trans('events.S_sellPickupResuq')); ?>

                                              <div class="control__indicator"></div>
                                          </label>
                                      </div> 
                                    <!-- 不發券 -->
                                    <div class="col-md-3 form-checkbox form-line-m10">
                                          <label class="control control--checkbox">
                                              <input type="checkbox" v-model="noTicketingSEJ" :disabled="statucControl[0].basis || !sevenElevenGetTicket"><?php echo e(trans('events.S_sellPickupNoTicketing')); ?>

                                              <div class="control__indicator"></div>
                                          </label>
                                      </div>
                                </div>
                                    <!-- /.0310 -->
                            </div>
                          </div>
                          <!-- /. -->
                          <div class="col-sm-offset-1 from-memo-list">
                            <ol style="list-style-type:none;"> <!-- STS 2021-07-22: Task 39 -->
                              <li>チケット予約日から取扱い締め切りまでの日数が有効期限より少ない場合、有効期限が短縮されます。</li>
                              <!-- STS 2021-07-22: Task 39 
                              <li>セブン-イレブン支払時のチケット引取方法は、セブン-イレブン発券のみになります。</li> -->
                            </ol>
                          </div>
                        <?php endif; ?>
                          <hr>
                        

                    </div>
                </div>
                <!---/.box-body--->
                <!---box-footer--->
                <div class="box-footer">
                    
                    <div class="col-md-12">
                        <h5><?php echo e(trans('events.S_sellMemberLimitTitle')); ?></h5>
                        <div class="col-sm-offset-1 form-group-flex">
                        <div class="col-sm-12 form-group">
                            <div class="col-sm-2 pr-x">
                                <input type="" maxlength="6" class="form-control" v-model="buyLimit" :disabled="statucControl[0].purchaseable_number">
                                <span v-show="checkResult['buyLimit']['status']" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ checkResult['buyLimit']['msn'] }}</span>
                            </div>
                            <div class="col-sm-10 txt-flex-mr"><?php echo e(trans('events.S_sellLimitUnit_02')); ?></div>
                        </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                </div>
                <!---/.box-footer--->
            </div>
            <!-- /.BOX 1 -->
            </div>
        </div>
    </div>
    <!-- /.sell-setting-wrap 販賣條件專用class -->
</div>
<script>
    var sellSetting = new Vue({
        el: "#sellSetting",
        data: {
            payCreditMobapIdE:'',
            payCreditTicketIdE:'',
            payCashIdE:'',
            payCreditMobapIdN:'',
            payCreditTicketIdN:'',
            payCashIdN:'',
            creditCard:false,
            creditCardLimit:'',
            onlineGetTicket:false,
            qrPassEmail:false,
            qrPassSms:false,
            resuq:false,
            noTicketing:false,
            ibon:false,
            ibonGetTicket:false,
            ibonTicketLimit:4,
            ibonDateLimit:3,
            sevenEleven:false,
            sevenElevenIdE:'',
            sevenElevenIdN:'',
            sevenElevenGetTicket:false,
            sevenElevenTicketLimit:4,
            sevenElevenDateLimit:3,
            sevenElevenSEJ:false,
            resuqSEJ:false,
            noTicketingSEJ:false,
            getTicket:'',
            payCash:'',
            payCashLimit:'',
            cashDate:'',
            cashDateLimit:'',
            buyLimit:'',
            statucControl :[],
            checkResult:[],
        },
        watch: {
            creditCard:function(){
                this.ticketViewTag()
                this.localStorageSave()
       
                if(!this.creditCard ){
                    this.onlineGetTicket = false
                    this.qrPassEmail     = false
                    this.qrPassSms       = false
                    this.ibon            = false
                    this.sevenEleven     = false
                    this.resuq           = false
                    this.noTicketing     = false
                }

            },
            sevenElevenGetTicket:function(){
                this.ticketViewTag()
                this.localStorageSave()
                
                if(!this.sevenElevenGetTicket){
                    this.sevenElevenSEJ = false
                    this.resuqSEJ       = false
                    this.noTicketingSEJ = false
                }
            },
            sevenElevenSEJ:function(){
                this.ticketViewTag()
                this.localStorageSave()
            },
            resuqSEJ:function(){
                this.ticketViewTag()
                this.localStorageSave()
            },
            noTicketingSEJ:function(){
                this.ticketViewTag()
                this.localStorageSave()
            },
            creditCardLimit:function(){
                this.localStorageSave()
            },
            onlineGetTicket:function(){
                this.localStorageSave()
            },
            qrPass:function(){
                this.localStorageSave()
            },
            qrPassEmail:function(){
                this.localStorageSave()
            },
            qrPassSms:function(){
                this.localStorageSave()
            },
            ibon:function(){
                this.localStorageSave()
            },
            resuq:function(){
                this.localStorageSave()
            },
            noTicketing:function(){
                this.localStorageSave()
            },       
            sevenEleven:function(){
                this.ticketViewTag()
                this.localStorageSave()
            },
            getTicket:function(){
                this.localStorageSave()
            },
            payCash:function(){
                this.ticketViewTag()
                this.localStorageSave()
            },
            payCashLimit:function(){
                this.localStorageSave()
            },
            cashDate:function(){
                this.localStorageSave()
            },
            cashDateLimit:function(){
                this.localStorageSave()
            },
            buyLimit:function(){
                let checkResult = this.buyLimitRule()
                this.cheackData()
                if(checkResult){
                    this.localStorageSave()
                }
            },
            ibonGetTicket:function(){
                this.ticketViewTag()
                this.localStorageSave()
            },
            ibonTicketLimit:function(){
                this.localStorageSave()
            },
            ibonDateLimit:function(){
                this.localStorageSave()
            },
        },
        methods: {
            ticketViewTag: function(){
                if(sellSetting.onlineGetTicket || this.ibonGetTicket || this.sevenElevenSEJ || this.sevenEleven){
                    tagControl.ticketViewTag = true
                }else{
                    tagControl.ticketViewTag = false
                }
            },
            cheackData: function(){

                if(!this.buyLimitRule()){
                    tagControl.sellSettingWarning = true
                }else{
                    tagControl.sellSettingWarning = false
                }
              
                errorMsnCheack.updataBtnCheack()
            },
            buyLimitRule: function(val = this.buyLimit){
                try {
                    this.checkResult['buyLimit']['status'] = false
                    this.checkResult['buyLimit']['msn'] = ''
                    //STS 2021/05/28
                    $regex = /^\d*$/;
                    if(isNaN(val) || val == '' || !$regex.test(val)){
                        throw (new Error('数字のみで入力してください。'))
                    }

                    if(parseInt(val) < 0){
                        throw (new Error('正の整数を入力してください'))
                    }

                    return true
                }catch (e){
                    this.checkResult['buyLimit']['status'] = true
                    this.checkResult['buyLimit']['msn']  = e.message
                    
                    return false
                }
            },
            getSellSettingData:function(){
                let sellData = []

                let payCreditMobap = {
                    idE: this.payCreditMobapIdE,
                    idN: this.payCreditMobapIdN,
                    status:'',
                }

                let payCreditTicket = {
                    idE: this.payCreditTicketIdE,
                    idN: this.payCreditTicketIdN,
                    status:'',
                }

                let payCredit = {
                    creditCard: this.creditCard,
                    creditCardLimit: this.creditCardLimit,
                    onlineGetTicket: this.onlineGetTicket,
                    qrPass: this.qrPass,
                    qrPassEmail: this.qrPassEmail,
                    qrPassSms:  this.qrPassSms,
                    ibon:   this.ibon,
                    sevenEleven: this.sevenEleven,
                    resuq: this.resuq,
                    noTicketing: this.noTicketing,
                    getTicket: this.getTicket,
                    Mobap: payCreditMobap,
                    Ticket: payCreditTicket,
                }

                let payIbon ={
                    idE: '',
                    idN: '',
                    status: this.ibonGetTicket,
                    ibonTicketLimit: this.ibonTicketLimit,
                    ibonDateLimit: this.ibonDateLimit,
                }

                 //日本 7-11
                 let paySEJ ={
                    idE: this.sevenElevenIdE,
                    idN: this.sevenElevenIdN,
                    status: this.sevenElevenGetTicket,
                    SEJTicketLimit: this.sevenElevenTicketLimit,
                    SEJDateLimit: this.sevenElevenDateLimit,
                    sevenElevenSEJ: this.sevenElevenSEJ,
                    resuqSEJ: this.resuqSEJ,
                    noTicketingSEJ: this.noTicketingSEJ,
                }
              
                let data = {
                    buyLimit: this.buyLimit,
                    payCredit: payCredit,
                    // payCash: payCash,
                    payIbon: payIbon,
                    paySEJ: paySEJ,
                }
                
                sellData.push({
                    sellSetting: data,
                })

                return sellData
            },
            localStorageSave:function(){
                let payCreditMobap = {
                    idE: this.payCreditMobapIdE,
                    idN: this.payCreditMobapIdN,
                    status:'',
                }

                let payCreditTicket = {
                    idE: this.payCreditTicketIdE,
                    idN: this.payCreditTicketIdN,
                    status:'',
                }

                let payCredit = {
                    creditCard: this.creditCard,
                    creditCardLimit: this.creditCardLimit,
                    onlineGetTicket: this.onlineGetTicket,
                    qrPassEmail: this.qrPassEmail,
                    qrPassSms:  this.qrPassSms,
                    ibon:   this.ibon,
                    sevenEleven: this.sevenEleven,
                    noTicketing: this.noTicketing,
                    getTicket: this.getTicket,
                    Mobap: payCreditMobap,
                    Ticket: payCreditTicket,
                }

                let payIbon ={
                    idE: '',
                    idN: '',
                    status: this.ibonGetTicket,
                    ibonTicketLimit: this.ibonTicketLimit,
                    ibonDateLimit: this.ibonDateLimit,
                }

                //日本 7-11
                let paySEJ ={
                    idE: this.sevenElevenIdE,
                    idN: this.sevenElevenIdN,
                    status: this.sevenElevenGetTicket,
                    SEJTicketLimit: this.sevenElevenTicketLimit,
                    SEJDateLimit: this.sevenElevenDateLimit,
                    sevenElevenSEJ: this.sevenElevenSEJ,
                    resuqSEJ: this.resuqSEJ,
                    noTicketingSEJ: this.noTicketingSEJ,
                    // payCashLimit: this.sevenElevenTicketLimit,
                    // cashDateLimit: this.sevenElevenDateLimit,
                }
              
                let data = {
                    buyLimit: this.buyLimit,
                    payCredit: payCredit,
                    // payCash: payCash,
                    payIbon:　payIbon,
                    paySEJ: paySEJ,
                }
            
                sessionStorage.setItem("sellSetting", JSON.stringify(data))


            },
        },
        mounted(){
            <?php if($eventData["status"] === 'edit' || count($errors) > 0): ?>   
                sessionStorage.setItem('sellSetting','<?php echo addslashes($eventData["sellSetting"]); ?>')
                let sellData = JSON.parse(sessionStorage.getItem('sellSetting'))
                let perfomanceStatus = parseInt('<?php echo e($eventData['performanceDispStatus']); ?>', 10);

                this.payCreditMobapIdE      = sellData.payCredit.Mobap.idE
                this.payCreditTicketIdE     = sellData.payCredit.Ticket.idE
                this.payCreditMobapIdN      = sellData.payCredit.Mobap.idN
                this.payCreditTicketIdN     = sellData.payCredit.Ticket.idN
                this.creditCard             = sellData.payCredit.creditCard　
                this.creditCardLimit        = sellData.payCredit.creditCardLimit || 4
                this.onlineGetTicket        = sellData.payCredit.onlineGetTicket
                this.qrPassEmail            = sellData.payCredit.qrPassEmail || false
                this.qrPassSms              = sellData.payCredit.qrPassSms || false
                this.ibon                   = sellData.payCredit.ibon || false
                this.getTicket              = sellData.payCredit.getTicket
                this.sevenEleven            = sellData.payCredit.sevenEleven || false
                this.resuq                  = sellData.payCredit.resuq || false
                this.noTicketing            = sellData.payCredit.noTicketing || false

                if(sellData.payCash) {
                    this.payCashIdE             = sellData.payCash.idE
                    this.payCashIdN             = sellData.payCash.idN
                    $('#sellDate').val(sellData.payCash.cashDate)
                }
                else {
                    this.payCashIdE             = ''
                    this.payCashIdN             = ''
                    // $('#sellDate').val(sellData.payCash.cashDate)
                }
           
                if(sellData.paySEJ) {
                    this.sevenElevenIdE         = sellData.paySEJ.idE
                    this.sevenElevenIdN         = sellData.paySEJ.idN
                    this.sevenElevenGetTicket   = sellData.paySEJ.status || false
                    this.sevenElevenTicketLimit = sellData.paySEJ.SEJTicketLimit || 4
                    this.sevenElevenDateLimit   = sellData.paySEJ.SEJDateLimit || 3

                    if(typeof  sellData.paySEJ.noTicketingSEJ == 'undefined'){
                        this.sevenElevenSEJ         = sellData.paySEJ.status || false
                        this.noTicketingSEJ         = sellData.paySEJ.noTicketingSEJ || false
                    }else{
                        this.sevenElevenSEJ         = sellData.paySEJ.sevenElevenSEJ || false
                        this.noTicketingSEJ         = sellData.paySEJ.noTicketingSEJ || false
                    }
                    if(typeof  sellData.paySEJ.resuqSEJ == 'undefined'){
                        this.sevenElevenSEJ         = sellData.paySEJ.status || false
                        this.resuqSEJ         = sellData.paySEJ.resuqSEJ || false
                    }else{
                        this.sevenElevenSEJ         = sellData.paySEJ.sevenElevenSEJ || false
                        this.resuqSEJ         = sellData.paySEJ.resuqSEJ || false
                    }
                }
                else {
                    this.sevenElevenIdE         = ''
                    this.sevenElevenIdN         = ''
                    this.sevenElevenGetTicket   = false
                    this.sevenElevenTicketLimit = 4
                    this.sevenElevenDateLimit   = 3
                    this.sevenElevenSEJ         = false
                    this.resuqSEJ               = false
                    this.noTicketingSEJ         = false
                }
                this.buyLimit               = sellData.buyLimit || 10
                this.ibonGetTicket          = sellData.payIbon.status || false
                this.ibonTicketLimit        = sellData.payIbon.ibonTicketLimit || 4 
                this.ibonDateLimit          = sellData.payIbon.ibonDateLimit  || 3
                
            <?php else: ?>
                this.creditCardLimit = 4
                this.payCashLimit = 4
                this.buyLimit = 10
                this.cashDateLimit = 3
                let perfomanceStatus = -1
            <?php endif; ?>
            
            this.checkResult = {
                buyLimit : { 
                    status:false,
                    msn:''
                },
            }

            this.statucControl.push({
                basis: [7, 8].includes(perfomanceStatus),
                pay_method: [7, 8, 9].includes(perfomanceStatus),
                receive_limit: [7, 8, 9].includes(perfomanceStatus), 
                pickup_method: [7, 8, 9].includes(perfomanceStatus), 
                treat_end_date: [7, 8, 9].includes(perfomanceStatus),
                reserve_period: [7, 8, 9].includes(perfomanceStatus), 
                purchaseable_number: [7, 8, 9].includes(perfomanceStatus),
            })
        },
    });
</script><?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/event/editLayout/sellSetting.blade.php ENDPATH**/ ?>