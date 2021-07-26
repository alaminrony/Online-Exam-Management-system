<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.SCROLL_MESSAGE_LIST'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('scrollmessage/create')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.CREATE_SCROLL_MESSAGE'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.MESSAGE'); ?></th>
                            <th><?php echo app('translator')->get('label.SCOPE'); ?></th>
                            <th><?php echo app('translator')->get('label.PUBLISH'); ?></th>
                            <th><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="contain-center">
                            <td><?php echo e(++$sl); ?></td>
                            <td><?php echo e($item->message); ?></td>
                            <td>
                                <?php if(!empty($item->messagescope)): ?>
                                <?php $__currentLoopData = $item->messagescope; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scope): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($scope->scope_id == 3): ?>
                                <?php echo app('translator')->get('label.HOME_PAGE'); ?> </br>
                                <?php elseif($scope->scope_id == 1): ?>
                                <?php echo app('translator')->get('label.ISSP_DASHBOARD'); ?> </br>
                                <?php elseif($scope->scope_id == 2): ?>
                                <?php echo app('translator')->get('label.JCSC_DASHBOARD'); ?> </br>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($item->from_date. ' To '.$item->to_date); ?>

                            </td>
                            <td>
                                <?php if($item->status == '1'): ?>
                                <span class="label label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php elseif($item->status == '2'): ?>
                                <span class="label label-info"><?php echo app('translator')->get('label.COMMON'); ?></span>
                                <?php else: ?>
                                <span class="label label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="action-center">
                                <div class='text-center'>
                                    <?php echo e(Form::open(array('url' => 'scrollmessage/' . $item->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <a class='btn btn-primary btn-xs' href="<?php echo e(URL::to('scrollmessage/' . $item->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class='fa fa-edit'></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class='fa fa-trash'></i>
                                    </button>
                                    <?php echo e(Form::close()); ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6"><?php echo app('translator')->get('label.EMPTY_DATA'); ?></td>
                        </tr>
                        <?php endif; ?> 
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/scrollmessage/index.blade.php ENDPATH**/ ?>