<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-pencil-square-o"></i><?php echo app('translator')->get('label.VIEW_MOCK_TEST'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('mock_test/create')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.CREATE_MOCK_TEST'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">

            <?php echo e(Form::open(array('role' => 'form', 'url' => 'mock_test/filter', 'class' => '', 'id' => 'mockTestFilter'))); ?>

            <?php echo e(Form::hidden('page', Helper::queryPageStr($qpArr))); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><?php echo app('translator')->get('label.SELECT_EPE'); ?></label>
                        <?php echo e(Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'mockTestCourseId'))); ?>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label"><?php echo app('translator')->get('label.SEARCH_TEXT'); ?></label>
                        <?php echo e(Form::text('search_text', Request::get('search_text'), array('id'=> 'mockTestSearchText', 'class' => 'form-control', 'placeholder' => 'Search by Title/Duration'))); ?>

                    </div>
                </div>
                <div class="col-md-1">
                    <label class="control-label">&nbsp;</label>
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                    </button>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

            <div class="row">
                <div class="table-responsive">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover mock-management">
                            <thead>
                                <tr class="contain-center">
                                    <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th><?php echo app('translator')->get('label.SUBJECT'); ?></th>
                                    <th><?php echo app('translator')->get('label.EPE_TITLE'); ?></th>
                                    <!--<th><?php echo app('translator')->get('label.TITLE'); ?>}}</th>-->
                                    <th class="text-center"><?php echo app('translator')->get('label.TOTAL_NUMBER_OF_QUESTIONS'); ?></th>
                                    <th><?php echo app('translator')->get('label.START_DATE_TIME'); ?></th>
                                    <th><?php echo app('translator')->get('label.END_DATE_TIME'); ?></th>
                                    <th><?php echo app('translator')->get('label.DURATION'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('label.QUESTION_AUTO_SELECTION'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
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
                                <?php $expiryMock = ($value->end_at < date("Y-m-d H:i:s")) ? 'bg-red-thunderbird bg-font-red' : ''; ?>
                                <tr class="<?php echo e($expiryMock); ?> contain-center">
                                    <td><?php echo e(++$sl); ?></td>
                                    <td><?php echo e($value->subject_title); ?></td>
                                    <td><?php echo e($value->epe_title); ?></td>
                                    <!--<td><?php echo e($value->title); ?></td>-->
                                    <td class="text-center"><?php echo e($value->obj_no_question); ?></td>
                                    <td><?php echo e($value->start_at); ?></td>
                                    <td><?php echo e($value->end_at); ?></td>
                                    <td><?php echo e((strlen($value->duration_hours) === 1) ? '0'.$value->duration_hours : $value->duration_hours); ?>:<?php echo e((strlen($value->duration_minutes) === 1) ? '0'.$value->duration_minutes : $value->duration_minutes); ?></td>
                                    <td class="text-center">
                                        <?php if($value->obj_auto_selected == '1'): ?>
                                        <span class="label label-primary"><?php echo app('translator')->get('label.AUTO'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-warning"><?php echo app('translator')->get('label.MANUAL'); ?></span>
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
                                        <div class="text-center user-action">
                                            <?php echo e(Form::open(array('url' => 'mock_test/' . $value->id, 'id' => 'delete'))); ?>

                                            <?php echo e(Form::hidden('_method', 'DELETE')); ?>


                                            <?php if(empty($value->attempt)): ?>
                                            <a class='btn btn-primary btn-sm tooltips' href="<?php echo e(URL::to('mock_test/' . $value->id . '/edit')); ?>" title="Edit for <?php echo e($value->title); ?>">
                                                <i class='fa fa-edit'></i>
                                            </a>
                                            <a class='btn btn-primary btn-sm green tooltips' href="<?php echo e(URL::to('mock_test/questionset/' . $value->id)); ?>"  title="<?php echo app('translator')->get('label.QUESTION_SETS'); ?>">
                                                <i class='fa fa-question-circle'></i>
                                            </a>
                                            <?php endif; ?>
                                            <a class="btn btn-primary btn-sm yellow tooltips view_question" data-toggle="modal" data-target="#view_question" data-id="<?php echo e($value->id); ?>" href="#view_question" id="get_question_<?php echo e($value->id); ?>}" title="<?php echo app('translator')->get('label.VIEW_QUESTION'); ?>" data-container="body" data-trigger="hover" data-placement="top">
                                                <i class='fa fa-question'></i>
                                            </a>
                                            <a class="tooltips" data-toggle="modal" data-target="#view-modal" data-id="<?php echo e($value->id); ?>" href="#view-modal" id="getmockInfo" title="<?php echo app('translator')->get('label.DETAILS_MOCK_TEST'); ?>" data-container="body" data-trigger="hover" data-placement="top">
                                                <span class="btn btn-warning btn-sm"> 
                                                    &nbsp;<i class='fa fa-info'></i>&nbsp;
                                                </span>
                                            </a>
                                            <?php if(empty($value->attempt)): ?>
                                            <button class="btn btn-danger btn-sm tooltips" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete EPE <?php echo e($value->title); ?>" title="Delete Mock Test for <?php echo e($value->title); ?>">
                                                <i class='fa fa-trash'></i>
                                            </button>
                                            <?php endif; ?>

                                            <?php echo e(Form::close()); ?>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="10"><?php echo app('translator')->get('label.EMPTY_DATA'); ?></td>
                                </tr>
                                <?php endif; ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="view_question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo app('translator')->get('label.VIEW_MOCK_TEST_QUESTION'); ?></h4>
            </div>
            <div class="modal-body" id="show_question">  </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo app('translator')->get('label.CLOSE'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

</div>
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-content"><!-- mysql data will load in table -->

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // ****************** Ajax Code for children edit *****************
        $(document).on('click', '.view_question', function (e) {
            e.preventDefault();
            var mockID = $(this).data('id'); // get id of clicked row

            $('#show_question').html(''); // leave this div blank
            $.ajax({
                url: "<?php echo e(URL::to('mock_test/question_details')); ?>",
                type: "GET",
                data: {
                    mock_id: mockID
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#show_question').html(''); // blank before load.
                    $('#show_question').html(response.html); // load here
                    //Ending ajax loader
                    App.unblockUI();
                },
                beforeSend: function () {
                    //For ajax loader
                    App.blockUI({
                        boxed: true
                    });
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }

                    //Ending ajax loader
                    App.unblockUI();
                }
            });
        });

        $(document).on('click', '#getmockInfo', function (e) {
            e.preventDefault();
            var mockId = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "<?php echo e(URL::to('ajaxresponse/mock-info')); ?>",
                type: "GET",
                data: {
                    mock_id: mockId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose: true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
            });
        });

        $(document).on("submit", '#delete', function (e) {
            //This function use for sweetalert confirm message
            e.preventDefault();
            var form = this;
            swal({
                title: 'Are you sure you want to Delete?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete",
                closeOnConfirm: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                    form.submit();
                } else {
                    //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/mocktest/index.blade.php ENDPATH**/ ?>