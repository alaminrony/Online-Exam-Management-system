<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i><?php echo app('translator')->get('label.VIEW_SUBJECT_LIST'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('subject/create')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.CREATE_NEW_SUBJECT'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'subject/filter', 'class' => '', 'id' => 'branchFilter'))); ?>

            <?php echo e(Form::hidden('page', Helper::queryPageStr($qpArr))); ?>

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="col-md-4 control-label"><?php echo app('translator')->get('label.SEARCH_TEXT'); ?></label>
                        <div class="col-md-8">
                            <?php echo e(Form::text('search_text', Request::get('search_text'), array('id'=> 'userSearchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT')))); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                    </button>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.SUBJECT_NAME'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.SUBJECT_CODE'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.SUBJECT_DETAILS'); ?></th>
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
                            <td><?php echo e($value->code); ?></td>
                            <td><?php echo e($value->details); ?></td>
                            <td class="text-center"><?php echo e($value->order); ?></td>
                            <td class="text-center">
                                <?php if($value->status == '1'): ?>
                                <span class="label label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="action-center">
                                <div class='text-center'>
                                    <?php echo e(Form::open(array('url' => 'subject/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <a class='btn btn-primary btn-xs' href="<?php echo e(URL::to('subject/' . $value->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
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
                            <td colspan="7"><?php echo app('translator')->get('label.EMPTY_DATA'); ?></td>
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

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/subject/index.blade.php ENDPATH**/ ?>