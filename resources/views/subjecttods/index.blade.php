@extends('layouts.default.master')
@section('data_count')
@include('layouts.flash')
<!-- END PORTLET-->
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>{{__('label.ASSIGN_SUBJECT_TO_EXAMINER')}} </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::open(array('role' => 'form', 'url' => 'subjecttods/relatedData', 'files'=> true, 'class' => 'form-horizontal', 'id'=>'formsubjecttods')) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <table id="user" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%">&nbsp;</th>
                                    <th width="25%">{{__('label.SUBJECT')}}</th>
                                    <th width="40%">{{__('label.EXAMINER')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($targetArr))
                                @foreach($targetArr as $item)
                                <tr>
                                    <td>
                                        <?php
                                        $subjectId = $item->id;
                                        //Get exists data for assign subject 
                                        $targetArr = array_filter($previousData, function($elem) use($subjectId) {
                                            return $elem['subject_id'] == $subjectId;
                                        });

                                        $existsDataArr = reset($targetArr);

                                        if (!empty($existsDataArr)) {
                                            $checked = 'checked="checked"';
                                            $selectedDs = $existsDataArr['user_id'];
                                            $disabled = '';
                                        } else {
                                            $checked = '';
                                            $selectedDs = null;
                                            $disabled = 'disabled';
                                        }
                                        ?>
                                        <div class="md-checkbox">
                                            <input type="checkbox" name="subject_id[{{$item->id}}]" id="subject-id-{{$item->id}}" class="checkboxes subjectId" value="{{$item->id}}" {{$checked}}>
                                            <label for="subject-id-{{$item->id}}">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> </label>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $item->subject_name}}
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            {{Form::select('user_id['.$item->id.']', $dsList, $selectedDs, array('class' => 'form-control js-source-states assign_subject_id', $disabled, 'id' => 'user_id'.$item->id))}}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4">{{__('label.EMPTY_DATA')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-10 text-center">
                        <button type="submit" class="btn btn-circle green">{{__('label.SUBMIT')}}</button>
                        <a href="{{URL::to('subjecttods')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">{{__('label.CANCEL')}}</button> 
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            <!-- END FORM-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).ready(function () {
        $(".subjectId").change(function () {
            var id = $("#" + this.id).val();
            if (this.checked) {
                $("#user_id" + id).prop("disabled", false);
                //            $("#mark_type_" + id).prop("disabled", false);
            } else {
                $("#user_id" + id).prop("disabled", true);
                //            $("#mark_type_" + id).prop("disabled", true);
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
                                    toastr.success("Instructor  has been assigned to Subject", "Success", {"closeButton": true});
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
</script>
@stop
