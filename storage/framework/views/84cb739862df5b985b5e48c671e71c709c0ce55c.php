<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-tag"></i><?php echo app('translator')->get('label.VIEW_QUESTION_BANK'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('question/create')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.CREATE_NEW_QUESTION'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'question/filter', 'class' => ''))); ?>

            <?php echo e(Form::hidden('page', Helper::queryPageStr($qpArr))); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="subjectId"><?php echo app('translator')->get('label.SELECT_SUBJECT'); ?></label>
                        <div class="col-md-8">
                            <?php echo e(Form::select('subject_id', $subjectList, Request::get('fill_subject_id'), array('id'=> '', 'class' => 'form-control js-source-states'))); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="typeId"><?php echo app('translator')->get('label.SELECT_QUESTION_TYPE'); ?></label>
                        <div class="col-md-6">
                            <?php echo e(Form::select('type_id', $typeList, Request::get('fill_type_id'), array('id'=> '', 'class' => 'form-control js-source-states'))); ?>

                        </div>
                    </div>
                </div>                        
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="searchText"><?php echo app('translator')->get('label.SEARCH_TEXT'); ?></label>
                        <div class="col-md-8">
                            <?php echo e(Form::text('search_text', Request::get('search_text'), array('id'=> 'searchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT')))); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="status"><?php echo app('translator')->get('label.STATUS'); ?></label>
                        <div class="col-md-9">
                            <?php echo e(Form::select('status',$statusList,  Request::get('fill_status'), array('id'=> 'status', 'class' => 'form-control'))); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                    </button>
                    <a class="btn btn-md red  filter-submit margin-bottom-20" href="<?php echo e(URL::to('question?generate=true&search_text='.Request::get('search_text').'&type_id='.Request::get('type_id').'&subject_id='.Request::get('subject_id').'&status='.Request::get('status').'&view=excel')); ?>"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.SUBJECT'); ?></th>
                            <th><?php echo app('translator')->get('label.QUESTION_TYPE'); ?></th>
                            <th><?php echo app('translator')->get('label.QUESTION'); ?></th>
                            <th><?php echo app('translator')->get('label.CONTENT_TYPE'); ?></th>
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
                            <td class="text-center"><?php echo e(++$sl); ?></td>
                            <td><?php echo e($value->Subject->title); ?></td>
                            <td><?php echo e($value->QuestionType->name); ?></td>
                            <td><?php echo e($value->question); ?></td>
                            <td class="text-center">
                                <?php if(!empty($value->content_type_id) && $value->content_type_id =='1'): ?>
                                <span class="label label-success tooltips" title="Image"><i class="fa fa-image"></i></span>
                                <?php elseif(!empty($value->content_type_id) && $value->content_type_id =='2'): ?>
                                <span class="label label-warning tooltips" title="Audio File"><i class="fa fa-file-audio-o"></i></span>
                                <?php elseif(!empty($value->content_type_id) && $value->content_type_id =='3'): ?>
                                <span class="label label-success tooltips" title="Video File"><i class="fa fa-video-camera"></i></span>
                                <?php elseif(!empty($value->content_type_id) && $value->content_type_id =='4'): ?>
                                <span class="label label-warning tooltips" title="Pdf File"><i class="fa fa-file-pdf-o"></i></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($value->status == '1'): ?>
                                <span class="label label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="action-center">
                                <div class='text-center'>
                                    <?php echo e(Form::open(array('url' => 'question/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <a class="btn btn-primary btn-xs tooltips" title="Edit" href="<?php echo e(URL::to('question/' . $value->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
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

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/question/index.blade.php ENDPATH**/ ?>