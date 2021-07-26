<h5>
    {{trans('english.SUBJECT')}} : <strong>{{$epeObjArr->subject->title}} |</strong> 
    {{trans('english.SUBMISSION_DATELINE')}} : <strong>{{$epeObjArr->submission_deadline}} |</strong>
    {{trans('english.RESULT_PUBLISH_DATE_TIME')}} : <strong>{{!empty($epeObjArr->result_publish) ? $epeObjArr->result_publish: 'NOT SET'}}</strong>
</h5>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>{{trans('english.SL_NO')}}</th>
            <th>{{trans('english.COURSE')}}</th>
            <th>{{trans('english.ISS_NO')}}</th>
            <th>{{trans('english.SERVICE_NO')}}</th>
            <th>{{trans('english.STUDENT')}}</th>
            <th>{{trans('english.REG_NO')}}</th>
            <th>{{trans('english.BRANCH')}}</th>
            <th class="text-center">{{trans('english.STATUS')}}</th>
            <th class="text-center" width="10%">{{trans('english.ACTION')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (!$studentList->isEmpty())
        <?php $sl = 0; ?>
        @foreach($studentList as $value)

        <tr class="contain-center">
            <td>{{++$sl}}</td>
            <td>{{$value->title}}</td>
            <td>{{$value->iss_no}}</td>
            <td>{{$value->service_no}}</td>
            <td>{{$value->rank->short_name}} {{$value->student_name}}</td>
            <td>{{$value->registration_no}}</td>
            <td>{{$value->branch->short_name}}</td>
            <td class="set_status_{{$value->id}} text-center">
                @if($value->status == '2')
                <span class="label btn yellow-soft"> {{trans('english.CC_TAKEN')}} </span>
                @elseif($value->status == '3')
                <span class="label label-warning"> {{trans('english.ABSENT')}} </span>
                @else
                <span class="label btn red">{{trans('english.NOT_SUBMITTED')}}</span>
                @endif
            </td>
            <td class="remove_action_{{$value->id}} text-center">
                @if($value->status == '0')
                <a data-tooltip="tooltip" data-toggle="modal" data-target="#show-modal" data-student_id="{{$value->id}}" data-status="2" href="#show-modal" class="btn green btn-sm specialpermission-epe-model tooltips" title="{{trans('english.CC_TAKEN')}}" data-container="body" data-trigger="hover" data-placement="top"> <i class="fa fa-edit" aria-hidden="true"></i></a>

                <a data-tooltip="tooltip" data-toggle="modal" data-target="#show-modal" data-student_id="{{$value->id}}" data-status="3" href="#show-modal" class="btn red-thunderbird btn-sm specialpermission-epe-model tooltips" title="{{trans('english.ABSENT')}}" data-container="body" data-trigger="hover" data-placement="top"> <i class="fa fa-edit" aria-hidden="true"></i></a>
                @endif
                @if($value->status == '2')
                <a data-tooltip="tooltip"  data-student_id="{{$value->id}}" data-status="2"  href="#" class="epeUndoToken btn green-sharp btn-sm specialpermission-tae-model tooltips" title="{{trans('english.UNDO_CC_TAKEN')}}"> <i class="fa fa-mail-reply"></i></a>
                @elseif($value->status == '3')
                <a data-tooltip="tooltip"  data-student_id="{{$value->id}}" data-status="3"  href="#" class="epeUndoToken btn btn-danger btn-sm specialpermission-tae-model tooltips" title="{{trans('english.UNDO_ABSENT')}}"> <i class="fa fa-mail-reply"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="9">{{trans('english.EMPTY_DATA')}}</td>
        </tr>
        @endif 
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });
    //tae undo token
    $(document).on('click', '.epeUndoToken', function (e) {
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

//        var taeId = $(this).data('specialPermissionTypeTaeEpe');
        var studentId = $(this).data('student_id');
        var status = $(this).data('status');

        if (status == '2') {
            var undo = 'Undo CC Taken';
        } else if (status == '3') {
            var undo = 'Undo Absen';
        }

        swal({
            title: "Are you sure you want to " + undo + "?",
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Undo",
            closeOnConfirm: true,
            timer: 3000
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{url('specialpermission/taeOrEpeUndoTaken')}}",
                            type: "POST",
                            data: {
                                student_id: studentId,
                                status: status,
                                type_tae_epe: typeEpeType, type: type, tae_id: taeId, epe_id: epeId
                            },
                            dataType: 'json',
                            success: function (response) {
                                toastr.success(response.message, "Success", {"closeButton": true});
//                                setTimeout(location.reload.bind(location), 400);
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
                        //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");
                    }
                });
    });
</script>