<?php if(is_string($item)): ?>
    <li class="header"><?php echo e($item); ?></li>
<?php else: ?>
    <li id="<?php echo e($item['position']); ?>" class="<?php echo e($item['class']); ?>" v-show="!<?php echo e($item['position']); ?>">
        <a href="<?php echo e($item['href']); ?>"
           onclick="loading.openLoading()"
           <?php if(isset($item['target'])): ?> target="<?php echo e($item['target']); ?>" <?php endif; ?>
        >
            <i class="fa fa-fw fa-<?php echo e(isset($item['icon']) ? $item['icon'] : 'circle-o'); ?> <?php echo e(isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''); ?>"></i>
            <span><?php echo e(trans($item['text'])); ?></span>
            <?php if(isset($item['label'])): ?>
                <span class="pull-right-container">
                    <span class="label label-<?php echo e(isset($item['label_color']) ? $item['label_color'] : 'primary'); ?> pull-right"><?php echo e($item['label']); ?></span>
                </span>
            <?php elseif(isset($item['submenu'])): ?>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
            <?php endif; ?>
        </a>
        <?php if(isset($item['submenu'])): ?>
            <ul class="<?php echo e($item['submenu_class']); ?>">
                <?php echo $__env->renderEach('adminlte::partials.menu-item', $item['submenu'], 'item'); ?>
            </ul>
        <?php endif; ?>
    </li>
<?php endif; ?>
<?php /**PATH /home/vagrant/code/gettiilite/resources/views/vendor/adminlte/partials/menu-item.blade.php ENDPATH**/ ?>