@extends('layouts.default.master')
@section('data_count')

<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>{{__('label.VIEW_EPE_PHASE_WISE_RESULT')}}
            </div>
            <div class="actions"></div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'epeResult')) }}
                <div class="row">
                    <div class="col-md-offset-2 col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="epeResultCourseId">{{__('label.SELECT_COURSE')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('course_id', $courseList, Request::get('course_id'), array('class' => 'form-control js-source-states', 'id' => 'epeResultCourseId'))}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-2 col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="epeResultPartId">{{__('label.SELECT_PART')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('part_id', $partList, Request::get('part_id'), array('class' => 'form-control js-source-states', 'id' => 'epeResultPartId'))}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-2 col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="epeResultSubjectId">{{__('label.SELECT_SUBJECT')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('subject_id', $subjectList, Request::get('subject_id'), array('class' => 'form-control js-source-states', 'id' => 'epeResultSubjectId'))}}
                            </div>
                        </div>
                    </div>
                </div>
                {{Form::close()}}
                <div class="row">
                    <div class="table-responsive">
                        <div class="col-md-12" id="show_tae_result">
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

<script type="text/javascript">
    $(document).ready(function () {
        /* Show the part*/
        $("#epeResultCourseId").change(function () {
            $.ajax({
                url: "{{ URL::to('eperesult/show_part_list') }}",
                type: "GET",
                dataType: "json",
                data: {course_id: $(this).val()},
                success: function (res) {
                    $('select#epeResultPartId').empty();
                    $('select#epeResultPartId').append('<option value="">--Select Part--</option>');
                    $.each(res.parts, function (i, val) {
                        $('select#epeResultPartId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                },
                beforeSend: function () {
                    $('select#epeResultPartId').empty();
                    $('select#epeResultPartId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('select#epeResultPartId').empty();
                    $('select#epeResultPartId').append('<option value="">--Select Part--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                }
            });
        });

        /* Assign Subject to Phase On Submitation*/
        $("#epeResultPartId").change(function () {
            var courseId = $("#epeResultCourseId").val();
            $.ajax({
                url: "{{ URL::to('eperesult/show_subject') }}",
                type: "POST",
                data: {course_id: courseId, part_id: $(this).val()},
                dataType: "json",
                success: function (response) {
                    $('select#epeResultSubjectId').empty();
                    $('select#epeResultSubjectId').append('<option value="">--Select Subject--</option>');
                    $.each(response.subjects, function (i, val) {
                        $('select#epeResultSubjectId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                },
                beforeSend: function () {
                    $('select#epeResultSubjectId').empty();
                    $('select#epeResultSubjectId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('select#epeResultSubjectId').empty();
                    $('select#epeResultSubjectId').append('<option value="">--Select Subject--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                }
            });
        });
    });

    $("#epeResultSubjectId").change(function () {
        var courseId = $("#epeResultCourseId").val();
        var partId = $("#epeResultPartId").val();
        var subjectId = $(this).val();
        $.ajax({
            url: "{{ URL::to('eperesult/show_marksheet') }}",
            type: "POST",
            data: {course_id: courseId, part_id: partId, subject_id: subjectId},
            success: function (response) {
                $('#show_tae_result').html(response.html);
                //Ending ajax loader
                App.unblockUI();
            },
            beforeSend: function () {
                $('#show_tae_result').empty();
                //For ajax loader
                App.blockUI({
                    boxed: true
                });
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#show_tae_result').empty();
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


</script>
@stop
