

<?php $__env->startSection('title', 'Gettii Lite'); ?>

<?php $__env->startSection('css'); ?>
    <?php if($events['statuc']['CSS_exist']): ?>
        <?php echo html_entity_decode($events['data']['CSS_content']); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components/remind', ['remind_code' => $events['data']['remind_code']]); ?>
    <?php if (isset($__componentOriginal71363fb4a50f4b2095b53e128e7ef2409b35550e)): ?>
<?php $component = $__componentOriginal71363fb4a50f4b2095b53e128e7ef2409b35550e; ?>
<?php unset($__componentOriginal71363fb4a50f4b2095b53e128e7ef2409b35550e); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <?php if($events['statuc']['HTML_exist']): ?>
        <?php echo html_entity_decode($events['data']['HTML_content']); ?>

    <?php else: ?>
        <div class="messages-content text-center">
            <h3><?php echo e(trans('home.S_Welcome')); ?></h3>
            <h1 class="text-green text-space-6x">Gettii Lite </h1>
            <div class="mt-5">
        <a id="help-pdf" type="button" class="btn waves-effect waves-light btn-rounded btn-info-outline btn-ll m-r-10" href="assets/document/GettiiLite_guide.pdf" target="_blank"
> Gettii Liteマニュアル <i class="fas fa-file-pdf help-i-text"></i> 
</a>
        <a id="help-video" type="button" class="btn waves-effect waves-light btn-rounded btn-info-outline btn-ll"  href="assets/video/douga.mp4" target="_blank"
> イベント登録操作動画 <i class="fas fa-play-circle help-i-text"></i></a>
        </div>
<!-- notice content -->   
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/gettiilite/resources/views/frontend/home/notice.blade.php ENDPATH**/ ?>