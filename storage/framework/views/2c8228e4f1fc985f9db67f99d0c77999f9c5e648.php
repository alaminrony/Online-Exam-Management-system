<div class="row">
    <div class="col-md-12 text-center">
        <button type="button" class="btn green btn-sm tooltips" id="showAssignedStudent" data-id="<?php echo e($examId??''); ?>" data-toggle="modal" data-target="#openStudentModal" title="View Assigned Student">No of Assigned Student:<b>(<?php echo e($noOfAssignStudent??''); ?>)</b></button>
    </div>
</div>
<?php echo e(Form::open(array('role' => 'form', 'url' => '#', 'files'=> true,'class' => 'form-horizontal', 'id'=>'questionSet', 'method'=> 'post'))); ?>

<table class="table table-striped table-bordered table-hover table-checkable order-column dataTables_wrapper" id="dataTable">
    <thead>
        <tr>
            <th class="vcenter text-center" width="15%">
    <div class="md-checkbox has-success">
        <?php echo Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']); ?>

        <label for="checkAll">
            <span class="inc"></span>
            <span class="check mark-caheck"></span>
            <span class="box mark-caheck"></span>
        </label>
        <span class="bold"><?php echo app('translator')->get('label.CHECK_ALL'); ?></span>
    </div>
</th>
<th> <?php echo e(__('label.NAME')); ?> </th>
<th> <?php echo e(__('label.DESIGNATION')); ?> </th>
<th> <?php echo e(__('label.REGION')); ?> </th>
<th> <?php echo e(__('label.CLUSTER')); ?> </th>
<th> <?php echo e(__('label.BRANCH')); ?> </th>
<th> <?php echo e(__('label.DEPARTMENT')); ?> </th>
</tr>
</thead>
<tbody>
    <?php if(!$studentArr->isEmpty()): ?>
    <?php
    $class = 'noStd';
    ?>
    <?php $__currentLoopData = $studentArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    $checked = empty($student->exam_id) ? '' : 'checked';
    ?>
    <tr class="odd gradeX">
        <td class="text-center"> 
            <div class="md-checkbox has-success">
                <input name="employee_id[<?php echo e($student->id); ?>]" type="checkbox" class="md-check bf-check" id="<?php echo e($student->id??''); ?>" <?php echo in_array($student->id, $exmToStudentArr) ? "checked" : ""; ?> value="<?php echo e($student->id??''); ?>"/>
                <label for="<?php echo e($student->id); ?>">
                    <span class="inc"></span>
                    <span class="check mark-caheck"></span>
                    <span class="box mark-caheck"></span>
                </label>
            </div>
        </td>
        <td ><?php echo e(!empty($student->rank->short_name) ? $student->rank->short_name : ''); ?> <?php echo e($student->first_name??''); ?> <?php echo e($student->last_name??''); ?> (<?php echo e($student->username??''); ?>)</td>
        <td><?php echo e(!empty($student->designation_title) ? $student->designation_title: ''); ?></td>
        <td><?php echo e(!empty($student->region_name) ? $student->region_name : ''); ?></td>
        <td><?php echo e(!empty($student->cluster_name) ? $student->cluster_name : ''); ?></td>
        <td><?php echo e(!empty($student->branch_name) ? $student->branch_name: ''); ?></td>
        <td><?php echo e(!empty($student->department_name) ? $student->department_name: ''); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</tbody>
</table>
<div class="form-actions">
    <div class="row">
        <div class="text-center col-md-12 fixed_3">
            <button type="submit" class="btn btn-circle green" id="studentSubmit"><i class="fa fa-save"></i> <?php echo e(__('label.SAVE')); ?></button>
            <a href="<?php echo e(URL::to('examtostudent')); ?>">
                <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> <?php echo e(__('label.CANCEL')); ?></button> 
            </a>
        </div>
    </div>
</div>
<input type="hidden" name="exam_id" value="<?php echo e($examId); ?>"/>
<?php echo e(Form::close()); ?>

<script type="text/javascript">
    $(document).ready(function () {
    var table = $('#dataTable').DataTable()
    $('#questionSet #checkAll').change(function() {
    var checked = $(this).is(":checked");
    $("input", table.rows({search:'applied'}).nodes()).prop( 'checked', checked );
});
 
});
 
    //This function use for save EPE information
    var table;
    $(document).ready(function () {
        table = $('#dataTable').dataTable();
        $(document).on('click', '#studentSubmit', function (e) {
            e.preventDefault();
            // Serialize the form data
            var oTable = $('#dataTable').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#questionSet").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });

            var form_data = new FormData($('#questionSet')[0]);

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('examtostudent/saveStudent')); ?>",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, 'Student has been Assigned to Exam', options);
                            //App.blockUI({ boxed: false });
                            setTimeout(location.reload.bind(location), 1000);
                            var syndicateId = $("#syndicateId").val();
                            var syndicateType = $("#syndicateType").val();
                            var termId = $("#termId").val();
                            var wingId = $("#wingId").val();

                            if (syndicateId == '0') {
                                $('#showStudent').html('');
                                return false;
                            }

                            $.ajax({
                                url: "<?php echo e(URL::to('/studentToTermSyn/getStudent')); ?>",
                                type: "POST",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    syndicate_id: syndicateId,
                                    term_id: termId,
                                    wing_id: wingId,
                                    syndicate_type: syndicateType,
                                },
                                beforeSend: function () {
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    $('#showStudent').html(res.html);
                                    $('.tooltips').tooltip();
                                    $(".js-source-states").select2();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                }
                            });//ajax
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });

    
</script><?php /**PATH C:\xampp\htdocs\oem\resources\views/examtostudent/getStudent.blade.php ENDPATH**/ ?>