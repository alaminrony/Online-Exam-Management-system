<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i><?php echo app('translator')->get('label.VIEW_BRANCH_LIST'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('branch/create')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.CREATE_NEW_BRANCH'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'branch/filter', 'class' => '', 'id' => 'branchFilter'))); ?>

            <?php echo e(Form::hidden('page', Helper::queryPageStr($qpArr))); ?>

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="searchText"><?php echo app('translator')->get('label.SEARCH_TEXT'); ?></label>
                        <div class="col-md-8">
                            <?php echo e(Form::text('search_text', Request::get('search_text'), array('id'=> 'searchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT')))); ?>

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
                            <th><?php echo app('translator')->get('label.ID_HASH'); ?></th>
                            <th><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th><?php echo app('translator')->get('label.SOL_ID'); ?></th>
                            <th><?php echo app('translator')->get('label.REGION'); ?></th>
                            <th><?php echo app('translator')->get('label.CLUSTER'); ?></th>
                            <th><?php echo app('translator')->get('label.LOCATION'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.ORDER'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.ACTION'); ?></th>
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
                            <td><?php echo e($value->name); ?></td>
                            <td><?php echo e($value->sol_id); ?></td>
                            <td><?php echo e($value->region); ?></td>
                            <td><?php echo e($value->cluster); ?></td>
                            <td><?php echo e($value->location); ?></td>
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
                                    <?php echo e(Form::open(array('url' => 'branch/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('branch/' . $value->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php echo e(Form::close()); ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9"><?php echo app('translator')->get('label.EMPTY_DATA'); ?></td>
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

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/branch/index.blade.php ENDPATH**/ ?>