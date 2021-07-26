@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-book"></i>{{__('label.VIEW_PREVIOUS_QUESTION')}}
            </div>
        </div>
        <div class="portlet-body">
            <div class="form-body">
                {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'specialPermissionFilter')) }}
                <div class="row">
                    <div class="col-md-offset-2 col-md-7">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="TypeTaeEpe">{{__('label.SELECT_EPE')}}:<span class="required">*</span></label>
                            <div class="col-md-8">
                                {{Form::select('type_tae_epe', $type, Request::get('type_tae_epe'), array('class' => 'form-control js-source-states', 'id' => 'TypeTaeEpe'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="subjectId">{{__('label.SELECT_SUBJECT')}}:</label>
                            <div class="col-md-8">
                                {{Form::select('subject_id', $subjectArr, Request::get('subject_id'), array('class' => 'form-control js-source-states', 'id' => 'subjectId'))}}
                            </div>
                        </div>
                    </div>
                </div>
                {{Form::close()}}
                <div class="row">
                    <div class="table-responsive">
                        <div class="col-md-12" id="show_previous_question">
                            <!--AJAX CALL FOR SUMITTED TAE-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-modal-lg" id="view_objective_question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{__('label.VIEW_OBJECTIVE_QUESTION')}}</h4>
            </div>
            <div class="modal-body" id="display_objective_question">  </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg" id="view_subjective_question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{__('label.VIEW_SUBJECTIVE_QUESTION')}}</h4>
            </div>
            <div class="modal-body" id="display_subjective_question">  </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>

    // ********* show previous question list  ********//
    $(document).on('change', '#TypeTaeEpe,#subjectId', function (e) {

        var typeEpeType = $("#TypeTaeEpe").val();
        //TypeTaeEpe == 1 tae,TypeTaeEpe==2 epe
        var subjectId = $("#subjectId").val();


        $.ajax({
            url: "{{ URL::to('previousquestion/show_previous_question_lists') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                type_tae_epe: typeEpeType,
                subject_id: subjectId
            },
            success: function (response) {
                $(".tooltips").tooltip()
                $('#show_previous_question').html(response.html);
                //Ending ajax loader
                App.unblockUI();
            },
            beforeSend: function () {
                $('#show_previous_question').empty();
                //For ajax loader
                App.blockUI({
                    boxed: true
                });
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#show_previous_question').empty();
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

    // ****************** Ajax Code for EPE *****************
    $(document).on('click', '.get_objective_Question', function (e) {
        e.preventDefault();
        var type = $(this).data('type'); // get id of clicked row
        var epeId = $(this).data('id'); // get id of clicked row

        $.ajax({
            url: "{{ URL::to('perviousquestion/question_details') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                epe_id: epeId,
                type: type
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#display_objective_question').html(''); // blank before load.
                $('#display_objective_question').html(response.html); // load here
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

    //This function use for view subjective question
    $(document).on('click', '.get_subjective_question', function (e) {
        e.preventDefault();
        var type = $(this).data('type'); // get id of clicked row
        var epeId = $(this).data('id'); // get id of clicked row

        $('#display_subjective_question').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('perviousquestion/subjective_question_details/') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                epe_id: epeId,
                type: type
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#display_subjective_question').html(''); // blank before load.
                $('#display_subjective_question').html(response.html); // load here
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

    $(document).ready(function () {
        $(".tooltips").tooltip({html: true});
    });
</script>
@stop
