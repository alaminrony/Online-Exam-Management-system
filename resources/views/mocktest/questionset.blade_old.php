@extends('layouts.default.master')
@section('data_count')

<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">

                {{ Form::open(array('role' => 'form', 'url' => '#', 'files'=> true,'class' => 'form-horizontal', 'id'=>'questionSet', 'method'=> 'post')) }}


                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="note note-success">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4 text-center">
                                    <h3>{{trans('english.COURSE').': '.$mockTestInfo->course_title}}</h3>
                                    <h3>{{trans('english.PART').': '.$mockTestInfo->part_title}}</h3>
                                    <h4>{{trans('english.SUBJECT').': '.$mockTestInfo->subject_title}}</h3>
                                </div>
                            </div>
                            <div class="row fixed">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="pull-left">
                                            {{trans('english.TIME').': '.$mockTestInfo->duration_hours.':'.$mockTestInfo->duration_minutes}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <?php
                                            if (!empty($noQuestion)) {
                                                $countqus = $noQuestion;
                                            } else {
                                                $countqus = count($prevData);
                                            }
                                            ?>
                                            {{'Selected Question <span id="selectque">'. $countqus.'</span> Out of '.$noQue}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> {{trans('english.SL')}} </th>
                                <th> {{trans('english.QUESTION_TYPE')}} </th>
                                <th> {{trans('english.QUESTION')}} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($questions))
                            <?php
                            $sl = 0;
                            $i = 0;
                            ?>
                            @foreach($questions as $question)
                            <?php
                            $checked = '';

                            $class = 'noQus';
                            //echo $noQuestion;exit;
                            if (!empty($noQuestion)) {
                                if ($sl < $noQuestion) {
                                    $checked = 'checked';
                                }
                                $class = 'checkoption';
                            } else {
                                if (in_array($question->id, $prevData)) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                            }
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input name="question_id[]" type="checkbox" data-id="{{$noQuestion}}" class="checkboxes {{$class}}" <?php echo $checked; ?> value="{{$question->id}}" />
                                        <span></span>
                                    </label>
                                </td>
                                <td>{{++$sl}}</td>
                                <td> {{$question->name}} </td>
                                <td> {{$question->question}} </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="mock_id" value="{{$mockTestInfo->id}}"/>
                <input type="hidden" name="set_id" value="{{$mockTestInfo->no_of_exam}}"/>
                <input type="hidden" name="total_noque" id="currentv" value="{{$noQue}}"/>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green" id="epeSubmit"><i class="fa fa-save"></i> {{trans('english.SAVE')}}</button>
                            <a href="{{URL::to('mock_test')}}">
                                <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> {{trans('english.CANCEL')}}</button> 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        //This function use for save EPE information
        $("#questionSet").submit(function (event) {
            var mockData = new FormData($('#questionSet')[0]);
            event.preventDefault();
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('mock_test/updated_question_set')}}",
                type: "POST",
                data: mockData,
                dataType: 'json',
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
                        $("#questionSetSubmit").prop("disabled", false);
                        window.location.reload();
                    }, 3000);

                },
                beforeSend: function () {
                    $("#questionSetSubmit").prop("disabled", true);
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
                    $("#questionSetSubmit").prop("disabled", false);
                    //Ending ajax loader
                    App.unblockUI();
                }
            });
        });

        $(document).on("click", '.checkoption', function () {
            var checklimit = $(this).attr("data-id");
            //var selectque = $("#selectque").text();

            var currentcheck = $('.checkoption:checked').size();
            //alert(currentcheck);return false;
            if (checklimit < currentcheck) {
                alert("You have already selected " + checklimit + " questions");
                return false;
            } else {
                $("#selectque").text(currentcheck);
            }
        });

        $(document).on("click", '.noQus', function () {
            var checklimit = $('#currentv').val();

            var currentcheck = $('.noQus:checked').size();
            if (checklimit < currentcheck) {
                alert("You have already selected " + checklimit + " questions");
                return false;
            }
            $("#selectque").text(currentcheck);

        });
        $('#sample').dataTable({
            paging: false
        });
    });
</script>
@stop

