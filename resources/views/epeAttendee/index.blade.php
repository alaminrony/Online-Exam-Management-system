@extends('layouts.default.master')
@section('data_count')

<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-book"></i>{{__('label.EPE_ATTENDEE')}}
            </div>
            <div class="actions"></div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'lockData')) }}
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="subjectId">{{__('label.SUBJECT')}}:<span class="required">*</span></label>
                            <div class="col-md-7">
                                {{Form::select('subject_id', $subjectList, Request::get('subject_id'), array('class' => 'form-control js-source-states', 'id' => 'subjectId'))}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="epeId">{{__('label.EPE')}}:<span class="required">*</span></label>
                            <div class="col-md-7">
                                {{Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'epeId'))}}
                            </div>
                        </div>
                    </div>
                </div>
                {{Form::close()}}
                <div class="row">
                    <div class="table-responsive">
                        <!--                                <div class="col-md-offset-4 col-md-2" id="refreshShow" style="display:none">
                                                            <div class="text-right ">
                                                                <button class="btn btn-circle green-jungle tooltips" id="refreshId">
                                                                    <i class="fa fa-refresh" title="{{__('label.REFRESH')}}"></i>
                                                                </button>
                        
                                                            </div>
                                                        </div>-->
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

        /* Assign Subject to Phase On Submitation*/
        $("#subjectId").change(function () {
            var subjectId = $("#subjectId").val();
            $.ajax({
                url: "{{ URL::to('epeattendee/show_epe') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {subjectId: subjectId},
                dataType: "json",
                success: function (response) {
                    $('select#epeId').empty();
                    $('select#epeId').append('<option value="">--Select Exam--</option>');
                    $.each(response.epes, function (i, val) {
                        $('select#epeId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                },
                beforeSend: function () {
                    $('select#epeId').empty();
                    $('select#epeId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('select#epeId').empty();
                    $('select#epeId').append('<option value="">--Select Exam--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                }
            });
        });
    });

    $("#epeId").change(function () {
        var subjectId = $("#subjectId").val();
        var epeId = $("#epeId").val();
        epeAssignMarks(subjectId, epeId);

    });


    function epeAssignMarks(subjectId, epeId) {
        $.ajax({
            url: "{{ URL::to('epeattendee/show_attendee_epe') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {subject_id: subjectId, epe_id: epeId},
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

//    $(window).on("load", function () {
//
//        var courseId = $("#courseId").val();
//        var partId = $("#assignMarksPartId").val();
//        var subjectId = $("#assignMarksSubjectId").val();
//        var type = $("#epeType").val();
//        if (type != '') {
//            epeAssignMarks(courseId, partId, subjectId, type);
//        }
//    });

    //refresh
    $(document).on('change', '#epeType', function () {
        var type = $("#epeType").val();
        if (type != '') {
            $('#refreshShow').show();
        } else {
            $('#refreshShow').hide();
        }

    });

    $(document).on('click', '#refreshId', function () {

        var subjectId = $("#subjectId").val();
        var epeId = $("#epeId").val();
        $.ajax({
            url: "{{ URL::to('epeattendee/show_attendee_epe') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {subject_id: subjectId, epe_id: epeId},
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
    });

    //orderBy asc
    $(document).on('click', '#serviceNo', function () {

        var courseId = $("#courseId").val();
        var partId = $("#assignMarksPartId").val();
        var subjectId = $("#assignMarksSubjectId").val();
        var type = $("#epeType").val();
        var serviceNo = val('1');

        $.ajax({
            url: "{{ URL::to('epeattendee/show_attendee_epe') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                course_id: courseId, part_id: partId, subject_id: subjectId, type: type,
                service_no: serviceNo
            },
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
    });

    //get force submit
    $(document).on('click', '#forceSubmit', function (e) {
        e.preventDefault();
        var epeMarkId = $(this).data('id'); // get id of clicked row

        $('#dynamic-info').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('epeattendee/forceSubmit') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                epe_mark_id: epeMarkId
            },
            success: function (response) {
                $('#dynamic-info').html(''); // blank before load.
                $('#dynamic-info').html(response.html); // load here

            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });

    //    save force submit
    $(document).on('click', '#saveForceSubmit', function (e) {
        var formData = new FormData($('#formData')[0]);
        e.preventDefault();
        $.ajax({
            url: "{{ URL::to('epeattendee/saveForceSubmit') }}",
            type: "POST",
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            async: true,
            success: function (response) {
                toastr.success('{{trans("label.FORCE_SUBMITTED_SUCCESSFULLY")}}', "Success", {"closeButton": true});
                setTimeout(location.reload.bind(location), 1000);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').modal('show');
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
    });
</script>
@stop
