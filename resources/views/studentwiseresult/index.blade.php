@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>{{__('label.STUDENT_WISE_RESULT')}}
            </div>
            <div class="actions">
                <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'studentWiseResult')) }}
                <div class="row">
                    <div class=" col-md-4">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="epeId">{{__('label.EXAM')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'epeId'))}}
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-4">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="studentId">{{__('label.STUDENT')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('student_id', $studentList, Request::get('student_id'), array('class' => 'form-control js-source-states', 'id' => 'studentId'))}}
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}

                    <div id="show_result">
                        <div class="table-responsive">
                            <!--AJAX CALL FOR SUMITTED TAE-->
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
        $("#epeId").change(function () {
            var epeId = $(this).val();
            if (epeId != '') {
                $.ajax({
                    url: "{{ URL::to('studentwiseresult/show_student_list') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {epe_id: $(this).val()},
                    success: function (res) {
                        $('select#studentId').empty();
                        $('select#studentId').append('<option value="">--Select Student--</option>');
                        $.each(res.students, function (i, val) {
                            $('select#studentId').append('<option value="' + val.id + '">' + val.student + '</option>');
                        });
                    },
                    beforeSend: function () {
                        $('select#studentId').empty();
                        $('select#studentId').append('<option value="">Loading...</option>');
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        $('select#studentId').empty();
                        $('select#studentId').append('<option value="">--Select Student--</option>');
                        if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                        } else {
                            toastr.error("Error", "Something went wrong", {"closeButton": true});
                        }
                    }
                });
            } else {
                $('select#studentId').empty();
                $('select#studentId').append('<option value="">--Select Student--</option>');
                $('#show_result').empty();
            }
        });

        $("#studentId").change(function () {
            var epeId = $("#epeId").val();
            var studentId = $("#studentId").val();
            $.ajax({
                url: "{{ URL::to('studentwiseresult/show_result') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {epe_id: epeId, student_id: studentId},
                success: function (response) {
                    $('#show_result').html(response.html);
                    $('.tooltips').tooltip({html: true});

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
        });
    });
</script>
@stop
