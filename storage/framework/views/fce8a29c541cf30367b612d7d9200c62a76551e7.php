<!DOCTYPE html>
<html lang="<?php echo e(\App::getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="<?php echo e(\App::getLocale()); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title_prefix', config('adminlte.title_prefix', '')); ?>
<?php echo $__env->yieldContent('title', config('adminlte.title', 'AdminLTE 2')); ?>
<?php echo $__env->yieldContent('title_postfix', config('adminlte.title_postfix', '')); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css')); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css')); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css')); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/dist/css/AdminLTE.min.css')); ?>">

    <?php if(config('adminlte.plugins.datatables')): ?>
        <!-- DataTables with bootstrap 3 style -->
        <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css">
    <?php endif; ?>

    <?php if(config('adminlte.plugins.select2')): ?>
        <!-- Select2 -->
        <link href="<?php echo e(asset('css/select2.min.css')); ?>" rel="stylesheet"/>
    <?php endif; ?>
   
    
    <?php echo $__env->yieldContent('adminlte_css'); ?>

    <!-- Select2 -->
    <script src="<?php echo e(asset('js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/i18n/ja.min.js')); ?>"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TZPWQ78');

        const locale = '<?php echo e(\App::getLocale()); ?>'
              token = document.querySelector('meta[name="csrf-token"]').content;
    </script>
    <!-- End Google Tag Manager -->
</head>
<body class="hold-transition <?php echo $__env->yieldContent('body_class'); ?>">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TZPWQ78"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php echo $__env->yieldContent('body'); ?>

<script src="<?php echo e(asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>


<?php if(config('adminlte.plugins.datatables')): ?>
    <!-- DataTables with bootstrap 3 renderer -->
    <script src="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
<?php endif; ?>

<?php if(config('adminlte.plugins.chartjs')): ?>
    <!-- ChartJS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
<?php endif; ?>

<?php echo $__env->yieldContent('adminlte_js'); ?>

    <script>
        VeeValidate.Validator.setLocale('<?php echo e(\App::getLocale()); ?>')

        function allThousandsTransform(){
            var elements = document.getElementsByClassName('__comma');

            Array.prototype.forEach.call(elements, function(el) {
                let val = el.textContent
                el.textContent = thousandsTransform(val)
            });
        }

        allThousandsTransform()
    </script>
</body>
</html>
<?php /**PATH /home/vagrant/code/gettiilite/resources/views/vendor/adminlte/master.blade.php ENDPATH**/ ?>