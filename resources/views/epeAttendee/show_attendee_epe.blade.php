@if (!$targetArr->isEmpty())
<div class="bg-blue-hoki bg-font-blue-hoki">
    <h5 style="padding: 10px;">
        {{__('label.EPE')}} : <strong>{{$epeInfoArr->title}} |</strong>
        {{__('label.EXAM_DATE')}} : <strong>{{$epeInfoArr->exam_date}} |</strong>
        {{__('label.RESULT_PUBLISH_DATE_TIME')}} : <strong>{{!empty($epeInfoArr->result_publish) ? $epeInfoArr->result_publish: 'N/A'}}</strong>
    </h5>
</div>

<h5>
    @if(!$targetArr->isEmpty())
    <button type="button" class="btn btn-primary">{{__('label.TOTAL_STUDENTS')}} : {{count($targetArr)}}</button>&nbsp;
    @endif

    @if(!empty($statusCount['submitted'][1]))
    <button type="button" class="btn btn-success">{!! $statusCount['submitted'][1] !!}</button>&nbsp;
    @endif
</h5>

<div class="col-md-offset-10 col-md-2 margin-bottom-15">
    <div class="text-right ">
        <button class="btn green tooltips" id="refreshId">
            <i class="fa fa-refresh"></i> {{__('label.RELOAD_PAGE')}}
        </button>

    </div>
</div>

<?php
$current_time = date('Y-m-d H:i:s');
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center" rowspan="2">{{__('label.SL_NO')}}</th>
            <th rowspan="2">{{__('label.STUDENT')}}</th>
            <th rowspan="2">{{__('label.GRADE')}}</th>
            <th rowspan="2">{{__('label.POSITOIN')}}</th>
            <th rowspan="2">{{__('label.EMPLOYEE_ID')}}</th>
            <th rowspan="2">{{__('label.BRANCH')}}</th>
            <th class="text-center" colspan="3">{{__('label.EXAM_TIME')}}</th>
            <th class="text-center" rowspan="2">{{__('label.RUNNING_QUESTION_NO')}}</th>
            @if($targetArr[0]->epe_end_time > $current_time)
            <th class="text-center" rowspan="2">{{__('label.ACTION')}}</th>
            @endif
        </tr>
        <tr>
            <!--objective-->
            <th class="text-center">{{__('label.START_TIME')}}</th>
            <th class="text-center">{{__('label.END_TIME')}}</th>
            <th class="text-center">{{__('label.SUBMISSION_TIME')}}</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $sl = 0;
        ?>
        @foreach($targetArr as $item)

        <?php
        $objectiveTime = explode(' ', $item->objective_submission_time);
        $subjectiveTime = explode(' ', $item->subjective_submission_time);
        ?>
        <tr class="contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td>
                <a class="tooltips zoom-in" data-tooltip="tooltip" data-toggle="modal" data-target="#view-modal" data-id="{{$item->user_id}}" href="#view-modal" id="getStudentInfo" title="View Student Details" data-container="body" data-trigger="hover" data-placement="top">
                    {{$item->student_name}} ({{$item->username}})
                </a>
            </td>

            <td>{{$item->grade}}</td>
            <td>{{!empty($positionArr[$item->appointment_id]) ? $positionArr[$item->appointment_id] : ''}}</td>
            <td>{{$item->employee_id}}</td>
            <td>{{$item->branch_name}}</td>
            <!--objective start and end time-->
            <td class="text-center">{{$item->objective_start_time}}</td>
            <td class="text-center">
                @if(!empty($item->objective_extended_end_time))
                <span class="text-danger">{{$item->objective_extended_end_time}}</span>
                @else
                {{$item->objective_end_time}}
                @endif
            </td>
            <td class="text-center">{{$objectiveTime[1]}}</td>
            <td class="text-center">{{!empty($firstSerialIdArr[$item->student_id])?$firstSerialIdArr[$item->student_id]:''}}</td>


            @if($item->epe_end_time > $current_time)
            <td class="text-center">
                <div class="form-group">
                    {{ Form::open(array('url' => '#', 'id' => 'deleteData')) }}
                    <!--{{ Form::hidden('epe_mark_id', $item->id,['id'=>'epeMarkId']) }}-->
                    <button data-tooltip="tooltip" class="deleteRecord btn btn-danger btn-xs tooltips" type="button" data-id="{{$item->id}}" data-placement="top" data-rel="tooltip" title="Delete">
                        <i class='fa fa-trash'></i>
                    </button>
                    {{ Form::close() }}
                </div>
<!--                <div class="text-center user-action">
                    <a data-tooltip="tooltip" class="btn grey-cascade btn-sm tooltips" data-toggle="modal" data-target="#view-modal" data-id="{{$item->id}}" href="#view-modal" id="forceSubmit" title="{{__('label.FORCE_SUBMITTED')}}" data-container="body" data-trigger="hover" data-placement="top">
                        <i class="fa fa-mail-forward" aria-hidden="true"></i>
                    </a>
                </div>-->
            </td>
            @endif 
        </tr>
        @endforeach
    </tbody>
</table>
<!--User modal -->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetails"></div> 
    </div>
</div>
<!--End user modal -->
@else
<div class="row">
    <div class="col-md-12 text-center">
        <div class="well text-danger">{{__('label.EMPTY_DATA')}}</div>
    </div>
</div>
@endif 
<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });
    $(document).on('click', '#getStudentInfo', function (e) {
        e.preventDefault();
        var userId = $(this).attr('data-id'); // get id of clicked row
        $('#dynamic-info').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('ajaxresponse/student-details') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: userId
            },
            success: function (response) {
                $('#showDetails').html(''); // blank before load.
                $('#showDetails').html(response.html); // load here
                $('.date-picker').datepicker({autoclose: true});
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });


    //delete
    $(document).on('click', '.deleteRecord', function (e) {
//        var deleteData = new FormData($('#deleteData')[0]);

        e.preventDefault();
        var form = this;
        var epeMarkId = $(this).attr('data-id');
        swal({
            title: 'Warning',
            text: 'This data will be permanently  deleted for this student' +
                    'Are you sure you want to Delete?',
            type: 'warning',
            html: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{url('epeattendee/delete')}}",
                    type: "POST",
                    data: {
                        epe_mark_id: epeMarkId
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success(response.message, "Success", {"closeButton": true});
                        setTimeout(location.reload.bind(location), 1000);
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

