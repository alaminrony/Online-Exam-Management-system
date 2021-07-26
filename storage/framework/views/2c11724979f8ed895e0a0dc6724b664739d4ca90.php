<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-badge"></i><?php echo app('translator')->get('label.VIEW_RANK_LIST'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('rank/create')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.CREATE_A_RANK'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.TITLE'); ?></th>
                            <th><?php echo app('translator')->get('label.SHORT_NAME'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.ORDER'); ?></th>
                            <th class='text-center'><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class='text-center'><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="contain-center">
                            <td><?php echo e(++$sl); ?></td>
                            <td><?php echo e($value->title); ?></td>
                            <td><?php echo e($value->short_name); ?></td>
                            <td class="text-center"><?php echo e($value->order); ?></td>
                            <td class="text-center">
                                <?php if($value->status == 'active'): ?>
                                <span class="label label-success"><?php echo e($value->status); ?></span>
                                <?php else: ?>
                                <span class="label label-warning"><?php echo e($value->status); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="action-center">
                                <div class='text-center'>
                                    <?php echo e(Form::open(array('url' => 'rank/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <a class='btn btn-primary btn-xs' href="<?php echo e(URL::to('rank/' . $value->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
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
                            <td colspan="4"><?php echo app('translator')->get('label.EMPTY_DATA'); ?></td>
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

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/rank/index.blade.php ENDPATH**/ ?>