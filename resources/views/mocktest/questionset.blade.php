@extends('layouts.default.master')
@section('data_count')

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
                            <div class="col-md-9 col-md-offset-1 text-center">
                                <address class="text-center">
                                    <br><strong>{{__('label.SUBJECT')}}: </strong> {{$mockTestInfo->subject_title}}
                                </address>
                            </div>
                        </div>
                        <div class="row fixed">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="pull-left">
                                        <strong>  {{__('label.TIME')}} : </strong>{{$mockTestInfo->duration_hours.':'.$mockTestInfo->duration_minutes}}
                                    </div>
                                </div>
                                <div class="col-md-6 fixed_2">
                                    <div class="pull-right">
                                        {!! '<strong>Selected Question :</strong> <span id="selectque">'. $alreadySelected.'</span> Out of '.$mockTestInfo->obj_no_question !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTables_wrapper" id="example_wrapper">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> {{__('label.SL')}} </th>
                            <th> {{__('label.QUESTION_TYPE')}} </th>
                            <th> {{__('label.QUESTION')}} </th>
                            <th> {{__('label.CONTENT_TYPE')}} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($questions))
                        <?php
                        $sl = 0;
                        $i = 0;
                        $class = 'noQus';
                        ?>
                        @foreach($questions as $question)
                        <?php
                        $checked = empty($question->mock_id) ? '' : 'checked';
                        ?>
                        <tr class="odd gradeX">
                            <td>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input name="question_id[]" type="checkbox" data-id="{{$question->id}}" class="checkboxes {{$class}}" <?php echo $checked; ?> value="{{$question->id}}" />
                                    <span></span>
                                </label>
                            </td>
                            <td>{{++$sl}}</td>
                            <td> {{$question->name}} </td>
                            <td> {{$question->question}} </td>
                            <td class="text-center">
                                @if (!empty($question->content_type_id) && $question->content_type_id =='1')
                                <span class="label label-success tooltips" title="Image"><i class="fa fa-image"></i></span>
                                @elseif(!empty($question->content_type_id) && $question->content_type_id =='2')
                                <span class="label label-warning tooltips" title="Audio File"><i class="fa fa-file-audio-o"></i></span>
                                @elseif(!empty($question->content_type_id) && $question->content_type_id =='3')
                                <span class="label label-success tooltips" title="Video File"><i class="fa fa-video-camera"></i></span>
                                @elseif(!empty($question->content_type_id) && $question->content_type_id =='4')
                                <span class="label label-warning tooltips" title="Pdf File"><i class="fa fa-file-pdf-o"></i></span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <input type="hidden" name="mock_id" value="{{$mockTestInfo->id}}"/>
            <input type="hidden" name="set_id" value="{{$mockTestInfo->no_of_exam}}"/>
            <input type="hidden" name="total_noque" id="currentv" value="{{$mockTestInfo->obj_no_question}}"/>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green" id="epeSubmit"><i class="fa fa-save"></i> {{__('label.SAVE')}}</button>
                        <a href="{{URL::to('mock_test')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> {{__('label.CANCEL')}}</button> 
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<script>
    $(document).ready(function () {
        $(document).on("click", '.checkoption', function () {
            var checklimit = $(this).attr("data-id");
            //var selectque = $("#selectque").text();
            var table = $('#example_wrapper').DataTable();
            var currentcheck = table
                    .rows()
                    .nodes()
                    .to$()
                    .find('input[type="checkbox"].checkoption:checked').length;
            //alert(currentcheck);return false;
            if (checklimit < currentcheck) {
                //event.preventDefault();
                swal("You have already selected " + checklimit + " questions");
                return false;
            } else {
                $("#selectque").text(currentcheck);
            }
        });

        $(document).on("click", '.noQus', function () {
            var checklimit = $('#currentv').val();
            var table = $('#example_wrapper').DataTable();
            var currentcheck = table
                    .rows()
                    .nodes()
                    .to$()
                    .find('input[type="checkbox"].noQus:checked').length;
            if (checklimit < currentcheck) {
                swal("You have already selected " + checklimit + " questions");
                return false;
            }
            $("#selectque").text(currentcheck);

        });


        var fixmeTop = $('.fixed_2').offset().top;
        $(window).scroll(function () {
            var currentScroll = $(window).scrollTop();
            if (currentScroll >= fixmeTop) {
                $('.fixed_2').css({
                    position: 'fixed',
                    top: '49px',
                    right: '4%',
                    padding: '4px 20px',
                    background: '#32c5d2',
                    'z-index': '9999',
                    width: 'auto',
                    border: '1px solid #000000',
                    'border-radius': '',
                });
                $('.fixed_2').each(function () {
                    this.style.setProperty('border-radius', '0px 0px 5px 5px', 'important');
                });
            } else {
                $('.fixed_2').css({
                    position: 'static',
                    top: '0%',
                    right: '0%',
                    padding: '0px',
                    background: 'none',
                    width: '',
                    border: '',
                });
            }
        });
    });

    $(document).ready(function () {
        $('#example_wrapper').DataTable();
    });

    var table;
    $(document).ready(function () {
        table = $('#example_wrapper').dataTable();
        //This function use for save EPE information
        $("#questionSet").submit(function (event) {
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

                            var sData = $('input', table.fnGetNodes()).serialize();
                            var nData = $(":hidden").serialize();

                            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                            $.ajax({
                                url: "{{ URL::to('mock_test/updated_question_set')}}",
                                type: "POST",
                                data: sData + "&" + nData,
                                dataType: 'json',
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
                        } else {
                            event.preventDefault();
                        }
                    });


        });

    });
</script>
@stop

