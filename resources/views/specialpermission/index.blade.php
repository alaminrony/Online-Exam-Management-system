@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i>{{trans('english.VIEW_CC_TAKEN_ABSENT_SUBMISSION_LIST')}}
                    </div>
                    <div class="actions"></div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'specialPermissionFilter')) }}
                        <div class="row">
                            <div class="col-md-offset-2 col-md-7">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="specialPermissionTypeTaeEpe">{{trans('english.SELECT_TAE_EPE')}}:<span class="required">*</span></label>
                                    <div class="col-md-8">
                                        {{Form::select('type_tae_epe', $type, Request::get('type_tae_epe'), array('class' => 'form-control js-source-states', 'id' => 'specialPermissionTypeTaeEpe'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-offset-2 col-md-7">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="specialPermissionType">{{trans('english.SELECT_TYPE')}}:<span class="required">*</span></label>
                                    <div class="col-md-8">
                                        {{Form::select('type', $typeList, Request::get('type'), array('class' => 'form-control js-source-states', 'id' => 'specialPermissionType'))}}
                                    </div>
                                </div>
                            </div>
                            <div id="show_tae_epe"><!--AJAX CALL FOR TAE/EPE--></div>
                        </div>
                        {{Form::close()}}
                        <div class="row">
                            <div class="table-responsive">
                                <div class="col-md-12" id="show_students">
                                    <!--AJAX CALL FOR SUMITTED TAE-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--This module use for student marks assign-->
<div id="show-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="specialPermissionRemarks">
        </div>
    </div>
</div>
<!-- END MODAL BODY -->

<script type="text/javascript">
    $(document).ready(function () {

        $("#specialPermissionTypeTaeEpe, #specialPermissionType").change(function () {

            var typeEpeType = $("#specialPermissionTypeTaeEpe").val();
            var type = $("#specialPermissionType").val();
            if (typeEpeType != '' && type != '') {
                $.ajax({
                    url: "{{ URL::to('specialpermission/show_tae_epe') }}",
                    type: "GET",
                    data: {type_tae_epe: typeEpeType, type: type},
                    success: function (res) {
                        $('#show_tae_epe').html(res.html);
                        $('#specialPermissionTaeId, #specialPermissionEpeId').select2();
                    },
                    beforeSend: function () {

                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                    }
                });
            }
        });

    });

    $(document).on('change', '.show-students', function (e) {

        var typeEpeType = $("#specialPermissionTypeTaeEpe").val();
        var type = $("#specialPermissionType").val();
        var taeId = '';
        var epeId = '';
        if (typeEpeType == 1) {
            var taeId = $("#specialPermissionTaeId").val();
        } else if (typeEpeType == 2) {
            var epeId = $("#specialPermissionEpeId").val();
        }

        $.ajax({
            url: "{{ URL::to('specialpermission/show_student_lists') }}",
            type: "POST",
            data: {type_tae_epe: typeEpeType, type: type, tae_id: taeId, epe_id: epeId},
            success: function (response) {
                $(".tooltips").tooltip()
                $('#show_students').html(response.html);
                //Ending ajax loader
                App.unblockUI();
            },
            beforeSend: function () {
                $('#show_students').empty();
                //For ajax loader
                App.blockUI({
                    boxed: true
                });
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#show_students').empty();
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

                //Ending ajax loader
                App.unblockUI();
            }
        });
    });

    // ****************** Ajax Code for show set status from *****************
    $(document).on('click', '.specialpermission-tae-model', function (e) {
        e.preventDefault();
        var taeId = $("#specialPermissionTaeId").val();
        var studentId = $(this).data('student_id'); // get student_id of clicked row
        var status = $(this).data('status'); // get tae_id of clicked row
        $('#specialPermissionRemarks').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('specialpermission/show_tae_status_form') }}",
            type: "POST",
            data: {
                student_id: studentId,
                tae_id: taeId,
                status: status,
            },
            success: function (response) {
                $('#specialPermissionRemarks').html(''); // blank before load.
                $('#specialPermissionRemarks').html(response.html); // load here
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#specialPermissionRemarks').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });
    //*********** End Ajax Code For show set status form Information *********//
    //*********** This function use for student assignment special permission data save *********//
    $(document).on('click', '#saveTaeSpecialPermission', function (e) {
        var specialPermissionData = new FormData($('#specialPermissionForm')[0]);
        var statusText = ($("#specialPermissionStatus").val() == '2') ? 'CC Taken' : 'Absent';
        e.preventDefault();

        var typeEpeType = $("#specialPermissionTypeTaeEpe").val();
        var type = $("#specialPermissionType").val();
        var taeId = '';
        var epeId = '';
        if (typeEpeType == 1) {
            var taeId = $("#specialPermissionTaeId").val();
        } else if (typeEpeType == 2) {
            var epeId = $("#specialPermissionEpeId").val();
        }

        if (confirm("Are you sure you want to " + statusText + "?")) {
            $.ajax({
                url: "{{ URL::to('specialpermission/store_tae_special_permission/') }}",
                type: "POST",
                data: specialPermissionData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                async: true,
                success: function (response) {
                    //console.log(response);
                    //                console.log(response.data);
//                    if ($("#specialPermissionStatus").val() == '2') {
//                        $("td.set_status_" + response.data.id).html('<span class="label btn-success">CC Taken</span>');
//                    } else if ($("#specialPermissionStatus").val() == '3') {
//                        $("td.set_status_" + response.data.id).html('<span class="label btn red-thunderbird">Absent</span>');
//                    }
//                    $("td.remove_action_" + response.data.id).text("");
                    toastr.success(response.message, "Success", {"closeButton": true});
                    $('#show-modal').modal('toggle');
                    //show_student_lists ajax start
                    $.ajax({
                        url: "{{ URL::to('specialpermission/show_student_lists') }}",
                        type: "POST",
                        data: {type_tae_epe: typeEpeType, type: type, tae_id: taeId, epe_id: epeId},
                        success: function (response) {
                            $(".tooltips").tooltip()
                            $('#show_students').html(response.html);
                            //Ending ajax loader
                            App.unblockUI();
                        },
                        beforeSend: function () {
                            $('#show_students').empty();
                            //For ajax loader
                            App.blockUI({
                                boxed: true
                            });
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            $('#show_students').empty();
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

                            //Ending ajax loader
                            App.unblockUI();
                        }
                    });  //show_student_lists ajax end
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

    // ****************** Ajax Code for show set status from *****************
    $(document).on('click', '.specialpermission-epe-model', function (e) {
        e.preventDefault();
        var epeId = $("#specialPermissionEpeId").val();
        var studentId = $(this).data('student_id'); // get student_id of clicked row
        var status = $(this).data('status'); // get tae_id of clicked row
        $('#specialPermissionRemarks').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('specialpermission/show_epe_status_form') }}",
            type: "POST",
            data: {
                student_id: studentId,
                epe_id: epeId,
                status: status,
            },
            success: function (response) {
                $('#specialPermissionRemarks').html(''); // blank before load.
                $('#specialPermissionRemarks').html(response.html); // load here
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#specialPermissionRemarks').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });

    //*********** This function use for EPE special permission data save *********//
    $(document).on('click', '#saveEpeSpecialPermission', function (e) {
        var specialPermissionData = new FormData($('#specialPermissionEpeForm')[0]);
        var statusText = ($("#specialPermissionStatus").val() == '2') ? 'CC Taken' : 'Absent';
        e.preventDefault();

        var typeEpeType = $("#specialPermissionTypeTaeEpe").val();
        var type = $("#specialPermissionType").val();
        var taeId = '';
        var epeId = '';
        if (typeEpeType == 1) {
            var taeId = $("#specialPermissionTaeId").val();
        } else if (typeEpeType == 2) {
            var epeId = $("#specialPermissionEpeId").val();
        }

        if (confirm("Are you sure you want to " + statusText + "?")) {
            $.ajax({
                url: "{{ URL::to('specialpermission/store_epe_special_permission/') }}",
                type: "POST",
                data: specialPermissionData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                async: true,
                success: function (response) {
//                    if ($("#specialPermissionStatus").val() == '2') {
//                        $("td.set_status_" + response.data.id).html('<span class="label btn-success">CC Taken</span>');
//                    } else if ($("#specialPermissionStatus").val() == '3') {
//                        $("td.set_status_" + response.data.id).html('<span class="label btn red-thunderbird">Absent</span>');
//                    }
//                    $("td.remove_action_" + response.data.id).text("");
                    toastr.success(response.message, "Success", {"closeButton": true});
                    $('#show-modal').modal('toggle');

                    //show_student_lists ajax start
                    $.ajax({
                        url: "{{ URL::to('specialpermission/show_student_lists') }}",
                        type: "POST",
                        data: {type_tae_epe: typeEpeType, type: type, tae_id: taeId, epe_id: epeId},
                        success: function (response) {
                            $(".tooltips").tooltip()
                            $('#show_students').html(response.html);
                            //Ending ajax loader
                            App.unblockUI();
                        },
                        beforeSend: function () {
                            $('#show_students').empty();
                            //For ajax loader
                            App.blockUI({
                                boxed: true
                            });
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            $('#show_students').empty();
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

                            //Ending ajax loader
                            App.unblockUI();
                        }
                    });  //show_student_lists ajax end
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
</script>
@stop
