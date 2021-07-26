@extends('layouts.default.master')
@section('data_count')

<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-book"></i>{{__('label.EPE_MARKING_SUBJECTIVE')}}
            </div>
            <div class="actions"></div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                {{ Form::open(array('role' => 'form', 'url' => 'submitSubjectiveMarking', 'class' => 'form-horizontal', 'id' => 'submitData')) }}
                <div class="row">
                    <div class="col-md-offset-2 col-md-7">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="epeId">{{__('label.SELECT_EPE')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'epeId'))}}
                            </div>
                        </div>
                    </div>
                </div>
                {{Form::close()}}
                <div class="row">
                    <div class="table-responsive">
                        <div class="col-md-12" id="show_submited_epe">
                            <!--AJAX CALL FOR SUMITTED TAE-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--This module use for student marks assign-->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-content"><!-- mysql data will load in table -->
        </div>
    </div>
</div>
<!-- END MODAL BODY -->

<!-- SRART DIALOG BOX-->
<div id="dialog" style="display: none">
    <div id="loading">
        <p> Please Wait..</p>
    </div>
</div>
<!-- END DIALOG BOX-->
<style type="text/css">
    .ui-dialog{
        z-index: 9999!important;
    }
</style>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).ready(function () {
        /* Show the part*/
        $("#courseId").change(function () {
            $.ajax({
                url: "{{ URL::to('epedsmarking/show_part_list') }}",
                type: "GET",
                dataType: "json",
                data: {course_id: $(this).val()},
                success: function (res) {
                    $('select#partId').empty();
                    $('select#partId').append('<option value="">--Select Part--</option>');
                    $.each(res.parts, function (i, val) {
                        $('select#partId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                },
                beforeSend: function () {
                    $('select#partId').empty();
                    $('select#partId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('select#partId').empty();
                    $('select#partId').append('<option value="">--Select Part--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                }
            });
        });

        /* Assign Subject to Phase On Submitation*/
        $("#partId").change(function () {
            var courseId = $("#courseId").val();
            $.ajax({
                url: "{{ URL::to('epedsmarking/show_subject') }}",
                type: "POST",
                data: {course_id: courseId, part_id: $(this).val()},
                dataType: "json",
                success: function (response) {
                    $('select#subjectId').empty();
                    $('select#subjectId').append('<option value="">--Select Subject--</option>');
                    $.each(response.subjects, function (i, val) {
                        $('select#subjectId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                },
                beforeSend: function () {
                    $('select#subjectId').empty();
                    $('select#subjectId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('select#subjectId').empty();
                    $('select#subjectId').append('<option value="">--Select Subject--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                }
            });
        });
    });

    function epeAssignMarks(epeId) {
        $.ajax({
            url: "{{ URL::to('epedsmarking/show_submitted_epe') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {epe_id: epeId},
            success: function (response) {
                $('#show_submited_epe').html(response.html);
                //Ending ajax loader
                App.unblockUI();
            },
            beforeSend: function () {
                $('#show_submited_epe').empty();
                //For ajax loader
                App.blockUI({
                    boxed: true
                });
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
                $('#show_submited_epe').empty();
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

    $("#epeId").change(function () {
        var epeId = $(this).val();

        epeAssignMarks(epeId);

    });

    $(window).on("load", function () {

        var epeId = $("#epeId").val();
        if (epeId != '') {
            epeAssignMarks(epeId);
        }
    });



    $(document).on('click', '#submitResult', function (e) {

        var pendingLock = $('#pendingLock').val();

        if (pendingLock != '0') {
            swal(pendingLock + ' script has not been locked yet!');
            return false;
        }

        var epeId = $('#epeId').val();

        swal({
            title: 'Are you sure you want to submit this result?',
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

                        //if(confirm("Are you sure you want to submit this result?")){
                        var resultData = new FormData($('#submitData')[0]);

                        $.ajax({
                            url: "{{ URL::to('submitSubjectiveMarking') }}",
                            type: "POST",
                            data: resultData,
                            dataType: 'json',
                            cache: false,
                            contentType: false,
                            processData: false,
                            async: true,
                            success: function (response) {
                                toastr.success('Result Submitted Successfully', "Success", {"closeButton": true});
                                //toastr.success(response.data, "Success", {"closeButton": true});
                                $("#tdsubmit").hide();
                                $('#lockedMsg').html(response.data);
                                return false;
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
                        return false;
                    }
                });
    });

    // ****************** Ajax Code for assign marks *****************
    $(document).on('click', '#marks-locked', function (e) {
        e.preventDefault();
        // var students = $('input:hidden.assignMarksStudentId').serialize();
//        console.log(students);
//        return false;
        var taeId = $(this).data('tae_id'); // get id of clicked row
        if (confirm('Student marks not yet put. Are you sure, you want to lock?')) {
            $.ajax({
                url: "{{ URL::to('epedsmarking/marks_lock') }}",
                type: "POST",
                data: {
                    tae_id: taeId
                },
                dataType: "json",
                success: function (response) {
                    $('.assign-marks-locked').html('');
                    toastr.success(response.message, "Success", {"closeButton": true});
                    location.reload(true);
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
            return false;
        }
    });
    //*********** End Ajax Code For Edit assign mark Information *********//

    // ****************** Ajax Code for assign marks *****************
    $(document).on('click', '#marks-unlock', function (e) {
        e.preventDefault();
        var taeId = $(this).data('tae_id'); // get id of clicked row
        if (confirm('Are you sure you want to unlock this marks?')) {
            $.ajax({
                url: "{{ URL::to('epedsmarking/marks_unlock') }}",
                type: "POST",
                data: {
                    tae_id: taeId
                },
                success: function (response) {
                    $('.assign-marks-unlock').html('');
                    toastr.success(response.message, "Success", {"closeButton": true});
                    location.reload(true);
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
            return false;
        }
    });
    //*********** End Ajax Code For Edit assign mark Information *********//

    $(function () {
        $(document).on("click", "#showAssignment", function () {
            var assignmentId = $(this).data("id");
            $.ajax({
                url: "{{ URL::to('epedsmarking/show_file') }}",
                type: "POST",
                data: {id: assignmentId},
                success: function (response) {
                    //console.log(response);
                    var originName = response.data.original_file;
                    var fileName = response.data.assignment;
                    var src = "{{URL::to('/')}}/public/uploads/assignment/" + fileName;

                    $("#dialog").dialog({
                        modal: true,
                        title: originName,
                        width: 700,
                        height: 600,
                        buttons: {
                            Close: function () {
                                $(this).dialog('close');
                            }
                        },
                        show: {
                            effect: "blind",
                            duration: 1000
                        },
                        hide: {
                            effect: "explode",
                            duration: 1000
                        },
                        open: function () {
                            var object = '<div id="mypdf"><iframe src="' + src + '#zoom=65" style="width: 100%; height: 800px;" frameborder="0" scrolling="no"><p>Your web browser doesnt support iframes.</p></iframe></div>';
                            $("#dialog").html(object);
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
    });
</script>
@stop
