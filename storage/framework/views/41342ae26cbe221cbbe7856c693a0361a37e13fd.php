<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i><?php echo app('translator')->get('label.CREATE_MOCK_TEST'); ?> </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo e(Form::open(array('role' => 'form', 'url' => '#', 'files'=> true,'class' => 'form-horizontal', 'id'=>'manageMockTest', 'method'=> 'post'))); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.SELECT_EPE'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'epeId'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('epe_id')); ?></span>
                            </div>
                        </div>

                        <div id="showMockTest"><!--AJAX CALL FOR TAE--></div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green" id="mockTestSubmit"><i class="fa fa-save"></i> <?php echo app('translator')->get('label.SAVE'); ?></button>
                        <a href="<?php echo e(URL::to('mock_test')); ?>">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> <?php echo app('translator')->get('label.CANCEL'); ?></button> 
                        </a>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

            <!-- END FORM-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->

<script type="text/javascript">
    $(document).ready(function () {
        /* Show the part*/
        $("#epeId").change(function () {
            var epeId = $(this).val();
            if (epeId != '') {
                $.ajax({
                    url: "<?php echo e(URL::to('mock_test/show_mock_test_info')); ?>",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {epe_id: epeId},
                    success: function (res) {
                        $('#showMockTest').html(res.html);
                        $('.mock_test_datetime').datetimepicker({
                            format: "yyyy-mm-dd hh:ii",
                            autoclose: true,
                            isRTL: App.isRTL(),
                            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
                        });
                        App.unblockUI();
                    },
                    beforeSend: function () {
                        App.blockUI({
                            boxed: true
                        });
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        $('#showMockTest').html('');
                        if (jqXhr.status == 500) {
                            toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                        } else {
                            toastr.error("Error", "Something went wrong", {"closeButton": true});
                        }

                        App.unblockUI();
                    }
                });
            } else {
                $('#showMockTest').html('');
            }

        });


        //This function use for save EPE information
        $("#manageMockTest").submit(function (event) {
            var mockTestData = new FormData($('#manageMockTest')[0]);
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

                            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                            $.ajax({
                                url: "<?php echo e(URL::to('mock_test/manage')); ?>",
                                type: "POST",
                                data: mockTestData,
                                dataType: 'json',
                                 headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                cache: false,
                                contentType: false,
                                processData: false,
                                async: true,
                                success: function (response) {
                                    toastr.success(response.data, "Success", {"closeButton": true});

                                    //Ending ajax loader
                                    App.unblockUI();

                                    //page reload
                                    setTimeout(function () {
                                        $("#mockTestSubmit").prop("disabled", false);
                                         window.location.href = "<?php echo e(URL::to('mock_test')); ?>";
                                    }, 3000);

                                },
                                beforeSend: function () {
                                    $("#mockTestSubmit").prop("disabled", true);
                                    //For ajax loader
                                    App.blockUI({
                                        boxed: true
                                    });
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
                                    $("#mockTestSubmit").prop("disabled", false);
                                    //Ending ajax loader
                                    App.unblockUI();
                                }
                            });
                        } else {
                            event.preventDefault();
                        }

                    });

        });

        $(document).on("keyup", '#mockTestObjNoQuestion', function (event) {
            var noOfQuestion = parseInt($(this).val());
            var totalObjectiveQuestion = parseInt($('#total_objective_questions').val());
            if (noOfQuestion > totalObjectiveQuestion) {
                alert(totalObjectiveQuestion + ' Questions Avaiable At Question Bank');
                $(this).val("");
                return false;
            }
        });

    });

    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });

    function remove_date(e) {
        var id = e;
        $("#" + id).val('');
    }
</script>
<style>
    .date-remove{
        margin-left: -26px;
    }
</style>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/mocktest/create.blade.php ENDPATH**/ ?>