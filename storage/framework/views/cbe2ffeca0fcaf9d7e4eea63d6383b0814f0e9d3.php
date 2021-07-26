<div class="bg-blue-hoki bg-font-blue-hoki">
    <h5 style="padding: 10px;">
        <?php echo e(__('label.SUBJECT')); ?> : <strong><?php echo e($epeInfo->Subject->title); ?> |</strong> 
        <?php echo e(__('label.TOTAL_SUBMISSION')); ?> : <strong><?php echo e(count($finalArr)); ?> |</strong> 
        <?php echo e(__('label.WAITING_FOR_ASSESSMENT')); ?> : <strong><?php echo e($dsStatusArr[0]['total']); ?> |</strong>
        <?php echo e(__('label.ASSESSED')); ?> : <strong><?php echo e($dsStatusArr[1]['total']); ?> |</strong>
        <?php echo e(__('label.LOCKED')); ?> : <strong><?php echo e($dsStatusArr[2]['total']); ?> |</strong>
        <?php echo e(__('label.SUBMISSION_DATELINE')); ?> : <strong><?php echo e(Helper::formatDateTime($epeInfo->submission_deadline)); ?> |</strong>
        <?php echo e(__('label.RESULT_PUBLISHED_DATE')); ?> : <strong><?php echo e(Helper::formatDateTime($epeInfo->result_publish)); ?></strong>
    </h5>
</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center"><?php echo e(__('label.SL_NO')); ?></th>
            <th class="text-center"><?php echo e(__('label.SUBMISSION_ID')); ?></th>
            <th class="text-center"><?php echo e(__('label.OBJECTIVE')); ?></th>
            <th class="text-center"><?php echo e(__('label.SUBJECTIVE')); ?></th>
            <th class="text-center"><?php echo e(__('label.ACHIEVED_MARK')); ?></th>
            <th class="text-center"><?php echo e(__('label.ACHIEVED_MARK'). '(%)'); ?></th>
            <th class="text-center"><?php echo e(__('label.ASSESSMENT')); ?> <?php echo e(__('label.STATUS')); ?></th>
            <th class="text-center"><?php echo e(__('label.RESULT')); ?> <?php echo e(__('label.STATUS')); ?></th>
            <?php if(Auth::user()->group_id =='2'): ?>
            <th class="text-center"><?php echo e(__('label.ACTION')); ?></th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $pendingLock = 0; ?>
        <?php if(!empty($finalArr)): ?>
        <?php
        $sl = 0;
        ?>
        <?php $__currentLoopData = $finalArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        if ($item['ds_status'] != '2') {
            $pendingLock++;
        }
        $totalPercent = $item['achieved_mark_per'];
        ?>

        <tr class="contain-center">
            <td class="text-center"><?php echo e(++$sl); ?></td>
            <td class="text-center"><?php echo e($item['id']); ?></td>
            <td class="text-center"><?php echo e(($item['objective_mark'] == null) ? __('label.BLANK') :  Helper::numberformat($item['objective_mark'])); ?></td>
            <td class="text-center"><?php echo e(($item['subjective_mark'] == null) ? __('label.BLANK') :  Helper::numberformat($item['subjective_mark'])); ?></td>
            <td class="text-center"><?php echo e(Helper::numberformat($item['achieved_mark'])); ?></td>
            <td class="text-center"><?php echo e(Helper::numberformat($item['achieved_mark_per']).'%'); ?></td>
            <td class="text-center"><span class="label label-<?php echo e($dsStatusArr[$item['ds_status']]['label']); ?>"> <?php echo e($dsStatusArr[$item['ds_status']]['text']); ?> </span></td>
            <td class="text-center">
                <?php echo e(Helper::findGrade($item['achieved_mark_per'])); ?>

            </td>
            <?php if(Auth::user()->group_id =='2'): ?>
            <td class="action-center">
                <?php if($item['ds_status'] == '2'): ?>
                <div class="text-center user-action">
                    <a href="<?php echo e(URL::to('subjectiveMarking/'.$item['id'])); ?>" class="btn yellow-crusta btn-sm tooltips" data-tooltip="tooltip" title="<?php echo e(__('label.ASSESS_EPE_SUBJECTIVE_SCRIPT')); ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                </div>
                <?php else: ?>
                <div class="text-center user-action">
                    <a href="<?php echo e(URL::to('subjectiveMarking/'.$item['id'])); ?>" class="btn yellow-crusta btn-sm tooltips" data-tooltip="tooltip" title="<?php echo e(__('label.ASSESS_EPE_SUBJECTIVE_SCRIPT')); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                </div>
                <?php endif; ?>
                <!--unlocke-->
                <?php if($item['ds_status'] == '2'): ?>
                <?php if($item['unlock_request'] == '0'): ?>
                <div class="text-center user-action">
                    <a data-tooltip="tooltip" class="btn grey-cascade btn-sm tooltips" data-toggle="modal" data-target="#view-modal" data-id="<?php echo e($item['id']); ?>" href="#view-modal" id="remark" title="<?php echo e(__('label.REQUEST_TO_UNLOCK')); ?>" data-container="body" data-trigger="hover" data-placement="top">
                        <i class="fa fa-unlock" aria-hidden="true"></i>
                    </a>
                </div>
                <?php else: ?>
                <div class="text-center text-danger">
                    <?php echo e(__('label.REQUESTED_TO_UNLOCK').' '.__('label.AT').' '.Helper::printDateTime($item->unlock_request_at)); ?>

                </div>
                <?php endif; ?>
                <?php endif; ?>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php else: ?>
        <tr>
            <td colspan="9"><?php echo e(__('label.EMPTY_DATA')); ?></td>

        </tr>

        <?php endif; ?> 

    </tbody>
</table>
<!--unlock request modal-->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-info">
            <!--mysql data will load in table--> 
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });

    //get unlock request
    $(document).on('click', '#remark', function (e) {
        e.preventDefault();
        var epeMarkId = $(this).data('id'); // get id of clicked row

        $('#dynamic-info').html(''); // leave this div blank
        $.ajax({
            url: "<?php echo e(URL::to('epedsmarking/unlockRequest')); ?>",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                epe_mark_id: epeMarkId
            },
//            cache: false,
//            contentType: false,
            success: function (response) {
                $('#dynamic-info').html(''); // blank before load.
                $('#dynamic-info').html(response.html); // load here

            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });

//unlock request save
    $(document).on('click', '#saveRequest', function (e) {
        var formData = new FormData($('#formData')[0]);
        e.preventDefault();
        $.ajax({
            url: "<?php echo e(URL::to('epedsmarking/unlockRequestSave')); ?>",
            type: "POST",
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            async: true,
            success: function (response) {
                toastr.success('Unlock request has been sent to CI', "Success", {"closeButton": true});
                setTimeout(function () {
                    window.location.href = "<?php echo e(URL::to('/')); ?>" + "/epedsmarking?epe_id=" + response.data.epe_id;
                }, 3000);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#attachment-modal').modal('show');
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
    }
    );
</script>

<?php /**PATH C:\xampp\htdocs\oem\resources\views/epeDsMarking/show_submitted_epe.blade.php ENDPATH**/ ?>