<?php if(!$targetArr->isEmpty()): ?>
<div class="bg-blue-hoki bg-font-blue-hoki">
    <h5 style="padding: 10px;">
        <?php echo e(__('label.EPE')); ?> : <strong><?php echo e($epeInfoArr->title); ?> |</strong>
        <?php echo e(__('label.EXAM_DATE')); ?> : <strong><?php echo e($epeInfoArr->exam_date); ?> |</strong>
        <?php echo e(__('label.RESULT_PUBLISH_DATE_TIME')); ?> : <strong><?php echo e(!empty($epeInfoArr->result_publish) ? $epeInfoArr->result_publish: 'N/A'); ?></strong>
    </h5>
</div>

<h5>
    <?php if(!$targetArr->isEmpty()): ?>
    <button type="button" class="btn btn-primary"><?php echo e(__('label.TOTAL_STUDENTS')); ?> : <?php echo e(count($targetArr)); ?></button>&nbsp;
    <?php endif; ?>

    <?php if(!empty($statusCount['submitted'][1])): ?>
    <button type="button" class="btn btn-success"><?php echo $statusCount['submitted'][1]; ?></button>&nbsp;
    <?php endif; ?>
</h5>

<div class="col-md-offset-10 col-md-2 margin-bottom-15">
    <div class="text-right ">
        <button class="btn green tooltips" id="refreshId">
            <i class="fa fa-refresh"></i> <?php echo e(__('label.RELOAD_PAGE')); ?>

        </button>

    </div>
</div>

<?php
$current_time = date('Y-m-d H:i:s');
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center" rowspan="2"><?php echo e(__('label.SL_NO')); ?></th>
            <th rowspan="2"><?php echo e(__('label.STUDENT')); ?></th>
            <th rowspan="2"><?php echo e(__('label.GRADE')); ?></th>
            <th rowspan="2"><?php echo e(__('label.POSITOIN')); ?></th>
            <th rowspan="2"><?php echo e(__('label.EMPLOYEE_ID')); ?></th>
            <th rowspan="2"><?php echo e(__('label.BRANCH')); ?></th>
            <th class="text-center" colspan="3"><?php echo e(__('label.EXAM_TIME')); ?></th>
            <th class="text-center" rowspan="2"><?php echo e(__('label.RUNNING_QUESTION_NO')); ?></th>
            <?php if($targetArr[0]->epe_end_time > $current_time): ?>
            <th class="text-center" rowspan="2"><?php echo e(__('label.ACTION')); ?></th>
            <?php endif; ?>
        </tr>
        <tr>
            <!--objective-->
            <th class="text-center"><?php echo e(__('label.START_TIME')); ?></th>
            <th class="text-center"><?php echo e(__('label.END_TIME')); ?></th>
            <th class="text-center"><?php echo e(__('label.SUBMISSION_TIME')); ?></th>
        </tr>
    </thead>
    <tbody>

        <?php
        $sl = 0;
        ?>
        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php
        $objectiveTime = explode(' ', $item->objective_submission_time);
        $subjectiveTime = explode(' ', $item->subjective_submission_time);
        ?>
        <tr class="contain-center">
            <td class="text-center"><?php echo e(++$sl); ?></td>
            <td>
                <a class="tooltips zoom-in" data-tooltip="tooltip" data-toggle="modal" data-target="#view-modal" data-id="<?php echo e($item->user_id); ?>" href="#view-modal" id="getStudentInfo" title="View Student Details" data-container="body" data-trigger="hover" data-placement="top">
                    <?php echo e($item->student_name); ?> (<?php echo e($item->username); ?>)
                </a>
            </td>

            <td><?php echo e($item->grade); ?></td>
            <td><?php echo e(!empty($positionArr[$item->appointment_id]) ? $positionArr[$item->appointment_id] : ''); ?></td>
            <td><?php echo e($item->employee_id); ?></td>
            <td><?php echo e($item->branch_name); ?></td>
            <!--objective start and end time-->
            <td class="text-center"><?php echo e($item->objective_start_time); ?></td>
            <td class="text-center">
                <?php if(!empty($item->objective_extended_end_time)): ?>
                <span class="text-danger"><?php echo e($item->objective_extended_end_time); ?></span>
                <?php else: ?>
                <?php echo e($item->objective_end_time); ?>

                <?php endif; ?>
            </td>
            <td class="text-center"><?php echo e($objectiveTime[1]); ?></td>
            <td class="text-center"><?php echo e(!empty($firstSerialIdArr[$item->student_id])?$firstSerialIdArr[$item->student_id]:''); ?></td>


            <?php if($item->epe_end_time > $current_time): ?>
            <td class="text-center">
                <div class="form-group">
                    <?php echo e(Form::open(array('url' => '#', 'id' => 'deleteData'))); ?>

                    <!--<?php echo e(Form::hidden('epe_mark_id', $item->id,['id'=>'epeMarkId'])); ?>-->
                    <button data-tooltip="tooltip" class="deleteRecord btn btn-danger btn-xs tooltips" type="button" data-id="<?php echo e($item->id); ?>" data-placement="top" data-rel="tooltip" title="Delete">
                        <i class='fa fa-trash'></i>
                    </button>
                    <?php echo e(Form::close()); ?>

                </div>
<!--                <div class="text-center user-action">
                    <a data-tooltip="tooltip" class="btn grey-cascade btn-sm tooltips" data-toggle="modal" data-target="#view-modal" data-id="<?php echo e($item->id); ?>" href="#view-modal" id="forceSubmit" title="<?php echo e(__('label.FORCE_SUBMITTED')); ?>" data-container="body" data-trigger="hover" data-placement="top">
                        <i class="fa fa-mail-forward" aria-hidden="true"></i>
                    </a>
                </div>-->
            </td>
            <?php endif; ?> 
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<!--User modal -->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetails"></div> 
    </div>
</div>
<!--End user modal -->
<?php else: ?>
<div class="row">
    <div class="col-md-12 text-center">
        <div class="well text-danger"><?php echo e(__('label.EMPTY_DATA')); ?></div>
    </div>
</div>
<?php endif; ?> 
<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });
    $(document).on('click', '#getStudentInfo', function (e) {
        e.preventDefault();
        var userId = $(this).attr('data-id'); // get id of clicked row
        $('#dynamic-info').html(''); // leave this div blank
        $.ajax({
            url: "<?php echo e(URL::to('ajaxresponse/student-details')); ?>",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: userId
            },
            success: function (response) {
                $('#showDetails').html(''); // blank before load.
                $('#showDetails').html(response.html); // load here
                $('.date-picker').datepicker({autoclose: true});
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });


    //delete
    $(document).on('click', '.deleteRecord', function (e) {
//        var deleteData = new FormData($('#deleteData')[0]);

        e.preventDefault();
        var form = this;
        var epeMarkId = $(this).attr('data-id');
        swal({
            title: 'Warning',
            text: 'This data will be permanently  deleted for this student' +
                    'Are you sure you want to Delete?',
            type: 'warning',
            html: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "<?php echo e(url('epeattendee/delete')); ?>",
                    type: "POST",
                    data: {
                        epe_mark_id: epeMarkId
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success(response.message, "Success", {"closeButton": true});
                        setTimeout(location.reload.bind(location), 1000);
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        var errorsHtml = '';
                        if (jqXhr.status == 400) {
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                        } else if (jqXhr.status == 500) {
                            toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                        } else {
                            toastr.error("Error", "Something went wrong", {"closeButton": true});
                        }
                    }
                });


            } else {
                //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

            }
        });
    });

</script>

<?php /**PATH C:\xampp\htdocs\oem\resources\views/epeAttendee/show_attendee_epe.blade.php ENDPATH**/ ?>