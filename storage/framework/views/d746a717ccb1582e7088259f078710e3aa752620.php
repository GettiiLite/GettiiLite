

<?php $__env->startSection('adminlte_css'); ?>
    <link rel="stylesheet"
          href="<?php echo e(asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')); ?> ">
    <?php echo $__env->yieldPushContent('css'); ?>
    <link href="<?php echo e(asset('css/bootstrap-datepicker.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/daterangepicker.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/jquery.tagsinput.css')); ?>"  rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('css/colorPick.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/bootstrap-timepicker.min.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/all.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/mdtimepicker.css')); ?>" rel="stylesheet"/>
    <script src="<?php echo e(asset('js/jquery-3.3.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <script src="<?php echo e(asset('js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/theatreseat.js')); ?>"></script>
    <script src="<?php echo e(asset('js/daterangepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.tagsinput.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('js/ckeditor.js')); ?>"></script>
    <script src="<?php echo e(asset('js/colorPick.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-timepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('js/vee-validate.js')); ?>"></script>
    <script src="<?php echo e(asset('js/vee-validate-dictionary.js')); ?>"></script>
    <script src="<?php echo e(asset('js/dropify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mdtimepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('js/common.js')); ?>"></script>
    <?php if((\App::getLocale() == "ja" )): ?>
        <script src="<?php echo e(asset('js/jp-city-selector.min.js')); ?>"></script>
    <?php else: ?>
        <script src="<?php echo e(asset('js/tw-city-selector.min.js')); ?>"></script>
    <?php endif; ?>
    <script src="<?php echo e(asset('js/watermark.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
        <!--bootstrap3-wysihtml5 -->
    <link href="<?php echo e(asset('css/bootstrap3-wysiwyg/bootstrap3-wysihtml5.min.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/bootstrap3-wysiwyg/bootstrap-theme.min.css')); ?>" rel="stylesheet"/>
    <script src="<?php echo e(asset('js/bootstrap3-wysihtml5-js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap3-wysihtml5-js/handlebars.runtime.min.js')); ?>"></script>
    <!--bootstrap3-wysihtml5 -->
    <link href="<?php echo e(asset('css/font-awesome/css/all.css')); ?>" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/dropify.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/Main.min.css')); ?>">
    <style>
        <?php echo $__env->yieldContent('css'); ?>
    </style> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'skin-' . config('adminlte.skin', 'none') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : '')); ?>

<?php $__env->startSection('body'); ?>   
    <?php $__env->startComponent('components/loading'); ?>
        
    <?php if (isset($__componentOriginalc3b282425e14b73cbaa1f0502a6ba3f7509c1428)): ?>
<?php $component = $__componentOriginalc3b282425e14b73cbaa1f0502a6ba3f7509c1428; ?>
<?php unset($__componentOriginalc3b282425e14b73cbaa1f0502a6ba3f7509c1428); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

    <?php echo $__env->yieldContent('content_header_setting'); ?>
    <div class="wrapper">
        <!-- Main Header -->
        <header  id="adminSetting" class="main-header">
            <div class="curtain" v-if="curtain"></div>
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="navbar-brand">
                            <?php echo config('adminlte.logo', '<b>G</b>ettii Lite'); ?>

                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <?php echo $__env->renderEach('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item'); ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            <?php else: ?>
            <!-- Logo --><!--href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>"-->
            <a class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?php echo config('adminlte.logo_mini', '<b>G</b>'); ?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?php echo config('adminlte.logo', '<b>G</b>ettii Lite'); ?></span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only"><?php echo e(trans('adminlte::adminlte.toggle_navigation')); ?></span>
                </a>
            <?php endif; ?>
                <!-- Content Header (Page header) -->
                <!-- 0410新增調整 -->
                <section class="content-header">
                    <?php echo $__env->yieldContent('content_header'); ?>
                </section>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">
                    <!--0916新增-->
                    
                    <?php if(session('profile_info_flg')): ?>
                        <li class="dropdown user user-menu">
                            <a href="/userManage" class="dropdown-toggle">
                            <span class="hidden-xs">会員情報</span>
                                <i class="fas fa-building"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!--/.0916新增-->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                <span class="hidden-xs"><?php echo e(session('account_code')); ?></span>
                                <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a v-on:click="openDialog()">
                                        <span aria-hidden="true" class="glyphicon glyphicon-cog"></span>
                                        <span class="hidden-xs"><?php echo e(trans('menu.S_PasswordChange')); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <?php if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<')): ?>
                                        <a href="<?php echo e(url(config('adminlte.logout_url', 'auth/logout'))); ?>">
                                            <i class="fa fa-fw fa-power-off"></i> <?php echo e(trans('adminlte::adminlte.log_out')); ?>

                                        </a>
                                    <?php else: ?>
                                        <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        >
                                            <i class="fa fa-fw fa-power-off"></i> <?php echo e(trans('adminlte::adminlte.log_out')); ?>

                                        </a>
                                        <form id="logout-form" action="<?php echo e(url(config('adminlte.logout_url', 'auth/logout'))); ?>" method="GET" style="display: none;">
                                            <?php if(config('adminlte.logout_method')): ?>
                                                <?php echo e(method_field(config('adminlte.logout_method'))); ?>

                                            <?php endif; ?>
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    <?php endif; ?>
                                </li>
                                
                            </ul>
                        </li>
                    </ul>
                </div>
                <?php if(config('adminlte.layout') == 'top-nav'): ?>
                </div>
                <?php endif; ?>
            </nav>
            <form id="formPassword" method="POST" action="/passwordChange">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="json" v-model="json">
            </form>
            <div class="modal-mask" v-show="showModal" style="display: none">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 v-if="step == '1'" class="modal-title"><?php echo e(trans('menu.S_PasswordChange')); ?></h4>
                            <h4 v-if="step == '-1'" class="modal-title"><?php echo e(trans('menu.S_PasswordChange')); ?></h4>
                        </div>
                        <div class="modal-body">
                            <template v-if="step == '1'">
                                <!--提示訊息 -->
                                <div class="row" v-show="errorMsnStatus">
                                    <div class="col-md-12 col-md-offset-2">
                                        <div class="col-md-9 callout callout-tip-warning ">
                                            <!-- -->
                                            <div class="icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                            <p class="">
                                                {{ errorMsn }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!--提示訊息 -->
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label"><?php echo e(trans('menu.S_Password')); ?></label>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control" name="old-password" v-model="oldPassword" v-validate="'required'" placeholder="<?php echo e(trans('menu.S_PasswordNotice1')); ?>">
                                            <span dusk="oldPassword" v-show="errors.has('old-password')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('old-password') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label"><?php echo e(trans('menu.S_NewPassword')); ?></label>
                                        <div class="col-md-10">
                                            <!-- STS 2021/28/08 Task 48 No 4 --START -->
                                             <!-- <input type="password" class="form-control" name="new-password" v-model="newPassword" v-validate="'required|min:6|max:20'" placeholder="<?php echo e(trans('menu.S_PasswordNotice2')); ?>"> -->
                                            <input type="password" class="form-control" name="new-password" v-model="newPassword" v-validate="{ required: true, regexPass: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z \d@$!%*?&#()^{}<>?_\-|\[\]\+\/\:\\=,.';`~]{8,20}$/}" placeholder="<?php echo e(trans('menu.S_PasswordNotice2')); ?>">
                                            <!-- STS 2021/28/08 Task SQL Injection --END -->
                                            <span dusk="newPassword" v-show="errors.has('new-password')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('new-password') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label"><?php echo e(trans('menu.S_ConNewPassword')); ?></label>
                                        <div class="col-md-10">
                                            <!-- STS 2021/28/08 Task SQL Injection --START -->
                                             <!-- <input  type="password" class="form-control" name="new-se-password" v-model="newSePassword" v-validate="'required|min:6|max:20'" placeholder="<?php echo e(trans('menu.S_PasswordNotice2')); ?>"> -->
                                            <input  type="password" class="form-control" name="new-se-password" v-model="newSePassword" v-validate="{ required: true, regexPass: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z \d@$!%*?&#()^{}<>?_\-|\[\]\+\/\:\\=,.';`~]{8,20}$/}" placeholder="<?php echo e(trans('menu.S_PasswordNotice3')); ?>">
                                            <!-- STS 2021/28/08 Task 48 No 4 --END -->
                                            <span dusk="newSePassword" v-show="errors.has('new-se-password')" class="help is-danger"><i class="fas fa-exclamation-circle"></i> {{ errors.first('new-se-password') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-if="step == '-1'">
                            <!--結果-->
                                <!--修改結果-->
                                    <h3  v-if="resultStatus">
                                        <i class="fas fa-check-circle text-aqua"></i> 
                                        {{ resultMsg }}
                                    </h3>
                                    <h3  v-else>
                                        <i class="fas fa-times-circle text-red"></i> 
                                        {{ resultMsg }}
                                    </h3>
                                <!--修改結果-->
                            </template>
                        </div>
                        <div class="modal-footer">
                            <template v-if="step == '1'">
                                <button id="changePsApply" v-on:click="changePasswork" class="btn btn-primary" :disabled="saveBtnDisability"><?php echo e(trans('menu.S_ChangeBtn')); ?></button>
                            </template>
                            <!--共同按鈕 -->
                            <button id="changePsClose" v-on:click="closeDialog" class="btn btn-default pull-left" data-dismiss="modal"><?php echo e(trans('menu.S_CancelBtn')); ?></button>
                            <!--共同按鈕 -->
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php if(config('adminlte.layout') != 'top-nav'): ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside id="mainSidebar" class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
        
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <!--User logo設定 -->
                    <img src="<?php echo e(session('logo')); ?>">
                    <!-- /.User logo設定 -->
                    <!-- 商戶編輯 -->
                    <div id="profile" class="file-text" v-show="!profile">
                        <span><?php echo e(session('account_title')); ?></span>
                       <!-- <a href="/userManage" onclick="loading.openLoading()" class="user-edit"><i class="fas fa-pen"></i></a>-->
                    </div>
                    <!-- /.商戶編輯 -->     
                </div>
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <?php echo $__env->renderEach('adminlte::partials.menu-item', $adminlte->menu(), 'item'); ?>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->

        </aside>
        <?php endif; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <div class="container">
            <?php endif; ?>

            <!-- Main content -->
            <section class="content">

                <?php echo $__env->yieldContent('content'); ?>

            </section>
            <!-- /.content -->
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            </div>
            <!-- /.container -->
            <?php endif; ?>
        </div>
        <!-- /.content-wrapper -->
        <!-- Footer-Copyright-Version -->
        <footer class="main-footer">
            <?php if(config('app.debug') == true || true): ?>
                <div class="pull-right hidden-xs">
                <b>Version : <?php echo e(config('app.app_reversion')); ?></b>
                </div>
            <?php endif; ?>
            <strong>Copyright &copy; 2019 <a href="＃">Gettii Lite</a>.</strong> All rights
            reserved.
        </footer>
        <!-- ./Footer-Copyright-Version -->
    </div>
    <!-- ./wrapper -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('adminlte_js'); ?>
    
    <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/imageUpload.js')); ?>"></script>
    <script>
   
        Vue.use(VeeValidate);
        var adminSetting = new Vue({
            el: '#adminSetting',
            data: {
                curtain:'',
                showModal:'',
                oldPassword:'',
                newPassword:'',
                newSePassword:'',
                passwordNotEquat:'',
                saveBtnDisability:'',
                json:'',
                errorMsnStatus:false,
                errorMsn:'',
                step:1,
                resultStatus:true,
                resultMsg:'',
            },
            watch: {
                errors:{
                    handler(){
                        this.cheackError()
                    },
                    deep: true
                },
                newSePassword: function(){
                    this.cheackPassword()
                    this.cheackError()
                },
            },
            methods: {
                cheackPassword:function(){
                    if (this.newPassword  !== this.newSePassword){
                        this.passwordNotEquat = true
                        this.errorMsnStatus = true
                        this.errorMsn = '<?php echo e(trans('common.S_PassInputErr')); ?>'
                    }else{
                        this.errorMsnStatus = false
                        this.errorMsn = ''
                    }
                },
                cheackError:function(){
               
                    if(this.errors.any() || this.errorMsnStatus){
                        this.saveBtnDisability = true
                    }else{
                        this.saveBtnDisability = false
                    }
                },
                openDialog:function(){
                    this.errors.clear()
                    this.oldPassword = ''
                    this.newPassword = ''
                    this.newSePassword = ''
                    this.$validator.clean();
                    this.showModal = true
                    this.step = 1
                },
                closeDialog:function(){
                    if(this.step == -1 && this.resultStatus != 1){
                        this.step = 1
                    }else{
                        this.showModal = false
                    }
                },
                changePasswork:function(){
                    let json = []

                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) {
                           
                        }else if (this.newPassword  !== this.newSePassword){
                            this.passwordNotEquat = true
                            this.errorMsnStatus = true
                            this.errorMsn = '<?php echo e(trans('common.S_PassInputErr')); ?>'
                        }else {
                            json.push({
                                oldPassword : this.oldPassword,
                                newPassword : this.newPassword,
                                newSePassword : this.newSePassword,
                            })

                            this.json =  JSON.stringify(json)

                            this.$nextTick(() => {
                                document.getElementById("formPassword").submit();
                            })
                        }
                    })  
                }
            },
            mounted(){
                this.showModal = false
                this.curtain = false

                <?php if(session()->has('message')): ?>
                    this.showModal = true
                    this.step = -1
                    this.resultStatus = "<?php echo e(session('change_status')); ?>"
                    this.resultMsg = "<?php echo e(session('message')); ?>"
                <?php endif; ?>
            }
        })
        var mainSidebar = new Vue({
            el: '#mainSidebar',
            data: {
                profile: true,
                eventManage: true,
                sellManage: true,
                adminManage: true,
                memberManage: true,
                notice: false,
            },
            methods: {
                
            },
            mounted(){
                let profile         = parseInt('<?php echo e(session('profile_info_flg')); ?>', 10);
                let eventManage     = parseInt('<?php echo e(session('event_info_flg')); ?>', 10);
                let sellManage      = parseInt('<?php echo e(session('sales_info_flg')); ?>', 10);
                let memberManage    = parseInt('<?php echo e(session('member_info_flg')); ?>', 10);
                let adminManage     = parseInt('<?php echo e(session('admin_flg')); ?>', 10);
                let help            = parseInt('<?php echo e(session('help_flg')); ?>', 10);

                this.profile        = [0].includes(profile)
                this.eventManage    = [0].includes(eventManage)
                this.sellManage     = [0].includes(sellManage)
                this.memberManage   = [0].includes(memberManage)
                this.reportManage   = [0].includes(sellManage)
                this.help           = [0].includes(help)
                this.adminManage    = [1].includes(adminManage)
                loading.closeLoading()
            }
        })
    </script>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/gettiilite/resources/views/vendor/adminlte/page.blade.php ENDPATH**/ ?>