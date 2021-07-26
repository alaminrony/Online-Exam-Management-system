<?php $__env->startSection('data_count'); ?>

<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet light bordered">
        <?php echo e(Form::open(array('role' => 'form', 'url' => '#', 'files'=> true,'class' => 'form-horizontal', 'id'=>'questionSet', 'method'=> 'post'))); ?>

        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="note note-success">
                    <div class="row">
                        <div class="col-md-9 col-md-offset-1 text-center">
                            <address class="text-center">
                                <strong><?php echo e(__('label.SUBJECT')); ?>: </strong> <?php echo e($epeInfo->subject_title); ?>

                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="pull-left">
                                    <strong> <?php echo e(__('label.TIME')); ?> : </strong><?php echo e($epeInfo->obj_duration_hours.':'.$epeInfo->obj_duration_minutes.':00'); ?>

                                </div>
                            </div>

                            <div class="col-md-6 fixed_2"> 
                                <div class="pull-right">
                                    <strong><?php echo e(__('label.SELECTED_QUESTION')); ?> : </strong><span id="selectque"><?php echo e($alreadySelected); ?></span> <?php echo e(__('label.OUT_OF').$epeInfo->obj_no_question); ?>

                                    <div class="pull-right" id="details" style="display:none;"><strong>&nbsp;<?php echo e(__('label.SHOW_DETAILS')); ?></strong></div>
                                    <div id="qusTypeDiv" style="display:none;">
                                        <?php $__currentLoopData = $questionTypeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeId =>$typeName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <strong><?php echo e(!empty($questionTypeList[$typeId])?$questionTypeList[$typeId]:0); ?> : </strong> <span id="selectqueFixed-<?php echo e($typeId); ?>"><?php echo e(!empty($alreadySelectArr[$typeId])?$alreadySelectArr[$typeId]:0); ?></span> Out of <span id="typeWiseTotal-<?php echo e($typeId); ?>"><?php echo e(!empty($epeQusTypeList[$typeId])?$epeQusTypeList[$typeId]:0); ?> </span><br />
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php $__currentLoopData = $questionTypeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeId =>$typeName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <strong><?php echo e(!empty($questionTypeList[$typeId])?$questionTypeList[$typeId]:0); ?> : </strong> <span id="selectque-<?php echo e($typeId); ?>"><?php echo e(!empty($alreadySelectArr[$typeId])?$alreadySelectArr[$typeId]:0); ?></span> Out of <?php echo e(!empty($epeQusTypeList[$typeId])?$epeQusTypeList[$typeId]:0); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column dataTables_wrapper" id="exampleWrapper">
                <thead>
                    <tr>
                        <th>#</th>
                        <th> <?php echo e(__('label.SL')); ?> </th>
                        <th> <?php echo e(__('label.QUESTION_TYPE')); ?> </th>
                        <th> <?php echo e(__('label.QUESTION')); ?> </th>
                        <th> <?php echo e(__('label.CONTENT_TYPE')); ?> </th>
                        <?php if($epeInfo->questionnaire_format=='1'): ?>
                        <th> <?php echo e(__('label.TIME')); ?> </th>
                        <?php endif; ?>
                        <th> <?php echo e(__('label.MARK')); ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!$questions->isEmpty()): ?>
                    <?php
                    $sl = 0;
                    $class = 'noQus';
                    ?>
                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $checked = empty($question->epe_id) ? '' : 'checked';
                    ?>
                    <tr class="odd gradeX">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input name="question_id[<?php echo e($question->id); ?>]" type="checkbox" data-id="<?php echo e($question->id); ?>" data-sl-no="<?php echo e($sl); ?>" data-qus-type-id="<?php echo e($question->type_id); ?>" class="checkboxes <?php echo e($class); ?> <?php echo e(!empty($classArr[$question->type_id])?$classArr[$question->type_id]:''); ?>" <?php echo $checked; ?> value="<?php echo e($question->id); ?>" />
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <?php $slNo = ++$sl ?>
                            <?php echo e($slNo); ?><input type="hidden" name="sl_no[<?php echo e($question->id); ?>]" value="<?php echo e($slNo); ?>"/></td>
                        <td> <?php echo e($question->name); ?> </td>
                        <td> <?php echo e($question->question); ?> </td>
                        <td class="text-center">
                            <?php if(!empty($question->content_type_id) && $question->content_type_id =='1'): ?>
                            <span class="label label-success tooltips" title="Image"><i class="fa fa-image"></i></span>
                            <?php elseif(!empty($question->content_type_id) && $question->content_type_id =='2'): ?>
                            <span class="label label-warning tooltips" title="Audio File"><i class="fa fa-file-audio-o"></i></span>
                            <?php elseif(!empty($question->content_type_id) && $question->content_type_id =='3'): ?>
                            <span class="label label-success tooltips" title="Video File"><i class="fa fa-video-camera"></i></span>
                            <?php elseif(!empty($question->content_type_id) && $question->content_type_id =='4'): ?>
                            <span class="label label-warning tooltips" title="Pdf File"><i class="fa fa-file-pdf-o"></i></span>
                            <?php endif; ?>
                        </td>
                          <?php if($epeInfo->questionnaire_format=='1'): ?>
                        <td>
                            <div class="input-icon">
                                <i class="fa fa-clock-o"></i>
                                <input type="text" name="time[<?php echo e($question->id); ?>]" class="form-control timepicker w-100" data-id="<?php echo e($question->id); ?>" value="<?php echo e(!empty($question->time)?$question->time:''); ?>" readonly="readonly" />
                            </div>
                        </td>
                          <?php endif; ?>
                        <td>
                            <div class="input-group">
                                <input type="text" name="mark[<?php echo e($question->id); ?>]" class="form-control w-100 integer-decimal-only" data-id="<?php echo e($question->id); ?>"  value="<?php echo e(!empty($question->mark)?$question->mark:''); ?>" />
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="text-center col-md-12 fixed_3">
                    <button type="submit" class="btn btn-circle green" id="questionSetSubmit"><i class="fa fa-save"></i> <?php echo e(__('label.SAVE')); ?></button>
                    <a href="<?php echo e(URL::to('epe')); ?>">
                        <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> <?php echo e(__('label.CANCEL')); ?></button> 
                    </a>
                </div>
            </div>
        </div>
        <input type="hidden" name="epe_id" value="<?php echo e($epeInfo->id); ?>"/>
        <input type="hidden" name="total_noque" id="currentv" value="<?php echo e($epeInfo->obj_no_question); ?>"/>
        <?php echo e(Form::close()); ?>

    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
</div>
<script>
    $(document).ready(function () {
        $("#details").click(function () {
            $("#qusTypeDiv").toggle(500);
        });

        $('.timepicker').timepicker(
                {
                    showSeconds: true,
                    showMeridian: false,
                    defaultTime: 'value'
                }
        );
        $(".integer-only").each(function () {
            $(this).keypress(function (e) {
                var code = e.charCode;

                if (((code >= 48) && (code <= 57)) || code == 0) {
                    return true;
                } else {
                    return false;
                }
            });
        });
        $(".integer-decimal-only").each(function () {
            $(this).keypress(function (e) {
                var code = e.charCode;

                if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                    return true;
                } else {
                    return false;
                }
            });
        });
        $(document).on("click", '.checkoption', function () {
            var checklimit = $(this).attr("data-id");
            //var selectque = $("#selectque").text();
            var table = $('#exampleWrapper').DataTable();
            var currentcheck = table
                    .rows()
                    .nodes()
                    .to$()
                    .find('input[type="checkbox"].checkoption:checked').length;
            //alert(currentcheck);return false;
            if (checklimit < currentcheck) {
                swal("You have already selected " + checklimit + " questions");
                return false;
            } else {
                $("#selectque").text(currentcheck);
            }
        });

        $(document).on("click", '.noQus', function () {
            var qustypeId = $(this).attr("data-qus-type-id");
            var checklimit = $('#typeWiseTotal-' + qustypeId).text();
            var table = $('#exampleWrapper').DataTable();
            if (qustypeId == '1') {
<?php if (!empty($classArr[1])) { ?>
                    var currentcheck = table
                            .rows()
                            .nodes()
                            .to$()
                            .find('input[type="checkbox"].<?php echo $classArr[1] ?>:checked').length;
                    if (checklimit < currentcheck) {
                        swal("You have already selected " + checklimit + " questions of type " + "<?php echo $questionTypeList[1] ?>");
                        return false;
                    }
<?php } ?>
            } else if (qustypeId == '3') {
<?php if (!empty($classArr[3])) { ?>
                    var currentcheck = table
                            .rows()
                            .nodes()
                            .to$()
                            .find('input[type="checkbox"].<?php echo $classArr[3] ?>:checked').length;
                    if (checklimit < currentcheck) {
                        swal("You have already selected " + checklimit + " questions of type " + "<?php echo $questionTypeList[3] ?>");
                        return false;
                    }
<?php } ?>
            } else if (qustypeId == '4') {
<?php if (!empty($classArr[4])) { ?>
                    var currentcheck = table
                            .rows()
                            .nodes()
                            .to$()
                            .find('input[type="checkbox"].<?php echo $classArr[4] ?>:checked').length;
                    if (checklimit < currentcheck) {
                        swal("You have already selected " + checklimit + " questions of type " + "<?php echo $questionTypeList[4] ?>");
                        return false;
                    }
<?php } ?>
            } else if (qustypeId == '5') {
<?php if (!empty($classArr[5])) { ?>
                    var currentcheck = table
                            .rows()
                            .nodes()
                            .to$()
                            .find('input[type="checkbox"].<?php echo $classArr[5] ?>:checked').length;
                    if (checklimit < currentcheck) {
                        swal("You have already selected " + checklimit + " questions of type " + "<?php echo $questionTypeList[5] ?>");
                        return false;
                    }
<?php } ?>
            }

            var totalCheck = table
                    .rows()
                    .nodes()
                    .to$()
                    .find('input[type="checkbox"].noQus:checked').length;

            $("#selectque").text(totalCheck);
            $("#selectque-" + qustypeId).text(currentcheck);
            $("#selectqueFixed-" + qustypeId).text(currentcheck);

        });

        var fixmeTop = $('.fixed_2').offset().top;
        $(window).scroll(function () {
            var currentScroll = $(window).scrollTop();
            if (currentScroll >= fixmeTop) {
                $('.fixed_2').css({
                    position: 'fixed',
                    top: '49px',
                    right: '4%',
                    padding: '4px 20px',
                    background: '#32c5d2',
                    'z-index': '9999',
                    width: 'auto',
                    border: '1px solid #000000',
                    'border-radius': '',
                });
                $('.fixed_2').each(function () {
                    this.style.setProperty('border-radius', '0px 0px 5px 5px', 'important');
                });
                $('#details').css({
                    display: 'block',
                    cursor: 'pointer',
                });
            } else {
                $('.fixed_2').css({
                    position: 'static',
                    top: '0%',
                    right: '0%',
                    padding: '0px',
                    background: 'none',
                    width: '',
                    border: '',
                });
                $('#details').css({
                    display: 'none',
                });
                $('#qusTypeDiv').css({
                    display: 'none',
                });
            }
        });
        $('#exampleWrapper').DataTable();
        //This function use for save EPE information
        var table;
        table = $('#exampleWrapper').dataTable();
        $('#questionSet').submit(function (event) {
            event.preventDefault();
            swal({
                title: 'Are you sure you want to Save?',
                text: '',
                type: 'warning',
                html: true,
                allowOutsideClick: true,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Yes, I agree',
                cancelButtonText: 'No, I do not agree',
            },
                    function (isConfirm) {
                        if (isConfirm) {

                            var sData = $('input', table.fnGetNodes()).serialize();
                            var nData = $(":hidden").serialize();
                            $.ajax({
                                url: "<?php echo e(URL::to('epe/updatedQuestionSet')); ?>",
                                type: "POST",
                                data: sData + "&" + nData,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    toastr.success(response.data, "Success", {"closeButton": true});
                                    //Ending ajax loader
                                    App.unblockUI();
                                    //page reload
                                    setTimeout(function () {
                                        $("#questionSetSubmit").prop("disabled", false);
                                        window.location.reload();
                                    }, 3000);
                                },
                                beforeSend: function () {
                                    $("#questionSetSubmit").prop("disabled", true);
                                    // For ajax loader
                                    App.blockUI({
                                        boxed: true
                                    });
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    var errorsHtml = '';
                                    if (jqXhr.status == 400) {
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value + '</li>';
                                        });
                                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                                    } else if (jqXhr.status == 500) {
                                        toastr.error("Something went wrong", jqXhr.statusText, {"closeButton": true});
                                    } else if (jqXhr.status == 401) {
                                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                                    } else {
                                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                                    }
                                    $("#questionSetSubmit").prop("disabled", false);
                                    // Ending ajax loader
                                    App.unblockUI();
                                }

                            });
                        } else {
                            event.preventDefault();
                        }

                    });
        });
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/epe/questionset.blade.php ENDPATH**/ ?>