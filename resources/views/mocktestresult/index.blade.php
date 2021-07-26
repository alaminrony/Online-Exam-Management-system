@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>{{__('label.VIEW_MOCK_TEST_RESULT')}}
            </div>
            <div class="actions"></div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'taeResult')) }}
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mockTestResultEpeId">{{__('label.SELECT_EPE')}}:<span class="required">*</span></label>
                        <div class="col-md-5">
                            {{Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'mockTestResultEpeId'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mockTestResultStudentId">{{__('label.SELECT_STUDENT')}}:<span class="required">*</span></label>
                        <div class="col-md-5">
                            {{Form::select('student_id', $studentList, Request::get('student_id'), array('class' => 'form-control js-source-states', 'id' => 'mockTestResultStudentId'))}}
                        </div>
                    </div>
                </div>
                {{Form::close()}}
                <div class="row margin-top-20">
                    <div class="table-responsive">
                        <div class="col-md-12" id="show_result">
                            <!--AJAX CALL-->
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
        $("#mockTestResultEpeId").change(function () {
            if ($(this).val() != '') {
                $.ajax({
                    url: "{{ URL::to('mocktestresult/studentlist') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {epe_id: $(this).val()},
                    success: function (res) {
                        $('select#mockTestResultStudentId').empty();
                        $('select#mockTestResultStudentId').append('<option value="">--Select Student--</option>');
                        $.each(res.students, function (i, val) {
                            $('select#mockTestResultStudentId').append('<option value="' + i + '">' + val + '</option>');
                        });
                    },
                    beforeSend: function () {
                        $('select#mockTestResultStudentId').empty();
                        $('select#mockTestResultStudentId').append('<option value="">Loading...</option>');
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        $('select#mockTestResultStudentId').empty();
                        $('select#mockTestResultStudentId').append('<option value="">--Select Student--</option>');
                        if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                        } else {
                            toastr.error("Error", "Something went wrong", {"closeButton": true});
                        }
                    }
                });
            } else {
                $('#show_result').empty();
                $('select#mockTestResultStudentId').empty();
                $('select#mockTestResultStudentId').append('<option value="">--Select Student--</option>');
            }

        });

    });

    $(document).on("change", '#mockTestResultStudentId', function () {
        var epeId = $("#mockTestResultEpeId").val();
        var studentId = $("#mockTestResultStudentId").val();
        if (studentId != '') {
            $.ajax({
                url: "{{ URL::to('mocktestresult/showresult') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {epe_id: epeId, student_id: studentId},
                success: function (response) {

                    $('#show_result').html(response.html);
                    $(".tooltips").tooltip({html: true});
                    //Ending ajax loader
                    App.unblockUI();
                },
                beforeSend: function () {
                    $('#show_result').empty();
                    //For ajax loader
                    App.blockUI({
                        boxed: true
                    });
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#show_result').empty();
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
        } else {
            $('#show_result').empty();
        }

    });

    //This function use for view question ans answer sheet
    $(document).on('click', '.question_answer_sheet', function (e) {
        e.preventDefault();
        var mockMarkId = $(this).data('id'); // get id of clicked row
        var epeId = $("#mockTestResultEpeId").val();
        var studentId = $("#mockTestResultStudentId").val();
        //console.log(mockId);return false;
        $('#display_question_answer_sheet').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('mocktestresult/questionanswersheet/') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                mock_mark_id: mockMarkId, epe_id: epeId, student_id: studentId
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#display_question_answer_sheet').html(''); // blank before load.
                $('#display_question_answer_sheet').html(response.html); // load here
                $(".tooltips").tooltip({html: true});
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

</script>
@stop
