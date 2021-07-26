@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>{{__('label.ASSIGN_EXAM_TO_STUDENT')}} </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>

        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 margin-bottom-10">
                        <div class="form-group">
                            <label class="col-md-3 text-right control-label">{{__('label.SELECT_EXAM')}} :<span class="required"> *</span></label>
                            <div class="col-md-6">
                                {{Form::select('exam_id', $examList, Request::get('exam_id'), array('class' => 'form-control js-source-states', 'id' => 'examId'))}}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="showStudent"><!--AJAX CALL FOR STUDENT--></div>
                    </div>
                </div>
            </div>
            <!-- END FORM-->
        </div>
    </div>
</div>

<!--Assigned Student Modal -->
<div class="modal fade select2-error" id="openStudentModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="showAssignedStudentData">

        </div>
    </div>
</div>
<!--end Assigned Student Modal -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#examId").change(function () {
            var examId = $("#examId").val();
            $('#showStudent').html('');
            if (examId != '') {
                $.ajax({
                    url: "{{ URL::to('examtostudent/getStudent') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {exam_id: examId},
                    success: function (res) {
                        $('#showStudent').html(res.html);
                        App.unblockUI();
                    },
                    beforeSend: function () {
                        App.blockUI({
                            boxed: true
                        });
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                        $('#showStudent').html('');
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

        /* Assign Subject to Phase On Submitation*/
        $("#formsubjecttods").submit(function (event) {
            event.preventDefault();
            swal({
                title: 'Are you sure you want to Submit?',
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

                            var datastring = $("#formsubjecttods").serialize();
                            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                            $.ajax({
                                url: "{{ URL::to('subjecttods/relatedData') }}",
                                type: "POST",
                                data: datastring,
                                dataType: "json",
                                success: function (response) {
                                    toastr.success("Subject assigned successfully", "Success", {"closeButton": true});
                                    window.location.reload();
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
                            event.preventDefault();
                        }

                    });

        });
    });
    
    $(document).ready(function () {
        $(document).on('click', '#showAssignedStudent', function () {
            var examId = $(this).attr('data-id');
            if(examId !=''){
                $.ajax({
                    url:"{{route('epe.getAssignedStudent')}}",
                    type:"post",
                    data:{exam_id:examId},
                    dataType:"json",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                       $('#showAssignedStudentData').html(data.html);
                    }
                });
            }
        });
    });
</script>
@stop
