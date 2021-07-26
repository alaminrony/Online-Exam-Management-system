<div class="bg-blue-hoki bg-font-blue-hoki">
    <h5 style="padding: 10px;">
        {{trans('english.SUBJECT')}} : <strong>{{$epeInfo->Subject->title }} |</strong> 
        {{trans('english.TOTAL_SUBMISSION')}} : <strong>{{$targetArr->count() }} |</strong> 
        {{trans('english.WAITING_FOR_ASSESSMENT')}} : <strong>{{ $dsStatusArr[0]['total'] }} |</strong>
        {{trans('english.ASSESSED')}} : <strong>{{ $dsStatusArr[1]['total'] }} |</strong>
        {{trans('english.LOCKED')}} : <strong>{{ $dsStatusArr[2]['total'] }} |</strong>
        {{trans('english.SUBMISSION_DATELINE')}} : <strong>{{$epeInfo->submission_deadline }} |</strong>
        {{trans('english.RESULT_PUBLISHED_DATE')}} : <strong>{{ $epeInfo->result_publish }}</strong>
    </h5>
</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">{{trans('english.SL_NO')}}</th>
            <th class="text-center">{{trans('english.SUBMISSION_ID')}}</th>
            <th class="text-center">{{trans('english.STUDENT')}}</th>
            <th class="text-center">{{trans('english.OBJECTIVE')}}</th>
            <th class="text-center">{{trans('english.SUBJECTIVE')}}</th>
            <th class="text-center">{{trans('english.ACHIEVED_MARK')}}</th>
            <th class="text-center">{{trans('english.ACHIEVED_MARK'). '(%)'}}</th>
            <th class="text-center">{{trans('english.DS_ASSESSMENT_STATUS')}}</th>
            <th class="text-center">{{trans('english.RESULT')}} {{trans('english.STATUS')}}</th>
            <th class="text-center">{{trans('english.ACTION')}}</th>
        </tr>
    </thead>
    <tbody>

        @if (!$targetArr->isEmpty())
        <?php
        $sl = 0;
        $isLockable = true;
        $hasUnlockRequest = false;
        ?>
        @foreach($targetArr as $item)


        <?php
        $isLockable = ($item->ds_status != '2') ? false : $isLockable; //DS status locked == 2
        $hasUnlockRequest = ($item->unlock_request != '0') ? true : $hasUnlockRequest;
        ?>

        <tr class="contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td class="text-center">{{$item->id}}</td>
            <td class="text-left">
                <a class="tooltips zoom-in" data-tooltip="tooltip" data-toggle="modal" data-target="#view-modal" data-id="{{$item->user_id}}" href="#view-modal" id="getStudentInfo" title="View Student Details" data-container="body" data-trigger="hover" data-placement="top">
                    {{$item->name}}
                </a>
            </td>
            <td class="text-center">{{ ($item->objective_earned_mark == null) ? trans('english.BLANK') :  $item->objective_earned_mark}}</td>
            <td class="text-center">{{ ($item->subjective_earned_mark == null) ? trans('english.BLANK') :  $item->subjective_earned_mark}}</td>
            <td class="text-center">{{ Custom::numberformat($item->objective_earned_mark + $item->subjective_earned_mark)}}</td>
            <td class="text-center">{{ Custom::numberformat(($item->total_mark * ($item->objective_earned_mark + $item->subjective_earned_mark))/100).'%' }}</td>
            <td class="text-center">
                <span class="label label-{{$dsStatusArr[$item->ds_status]['label']}}">
                    {{ $dsStatusArr[$item->ds_status]['text'] }} 
                </span><br/><br/>
                @if($item->unlock_request == '1')
                <label class="label label-danger label-sm">{{trans('english.REQUESTED_TO_UNLOCK')}}</label>
                @endif
            </td>
            <td class="text-center">
                <?php
                $totalMark = $item->objective_earned_mark + $item->subjective_earned_mark;
                ?>
                @if($item->ds_status != '0')
                @if($totalMark >= 50)
                <span class="label label-success">{{trans('english.PASSED')}}</span>
                @elseif($totalMark < 50)
                <span class="label label-danger">{{trans('english.FAILED')}}</span>
                @endif
                @endif
            </td>

            <td class="action-center">
                <div class="text-center user-action">
                    <a data-tooltip="tooltip" href="{{URL::to('ciSubjectiveMarking/'.$item->id)}}" class="btn yellow-crusta btn-sm tooltips" title="{{trans('english.ASSESS_EPE_SUBJECTIVE_SCRIPT')}}"><i class="icon-note" aria-hidden="true"></i></a>
                </div>
            </td>
        </tr>

        @endforeach

        @if($hasUnlockRequest)
        <tr>
            <td colspan="10">
                <span class="text-danger text-left">{{trans('english.THIS_EPE_PENDING_RESULT')}}</span>
            </td>
        </tr>
        @else
        @if($epe_ci_status === 0)  
        <!--if CI not yet locked-->
        @if($isLockable)
        <!--DS locked-->
        <tr>
            <td colspan="10" id="tdlock">
                <button type="button" id="lockResult" class="btn btn-primary">{{ trans('english.LOCK_RESULT') }}</button>
            </td>
        </tr>
        @else
        <tr>
            <td colspan="10">
                <span class="text-danger text-left">{{trans('english.ALL_SCRIPT_HAS_NOT_BEEN_LOCKED_YET')}}</span>
            </td>
        </tr>
        @endif
        @endif
        @endif


        @else
        <tr>
            <td colspan="10">{{trans('english.EMPTY_DATA')}}</td>
        </tr>
        @endif 
    </tbody>
</table>
@if(!empty($epeInfo->ci_status))
<div class="row">
    <div class="col-md-12 text-center">
        <div class="well text-danger">{{ trans('english.THIS_EPE_HAS_BEEN_LOCKED_BY').' '.$lockerInfo->Rank->short_name.' '.$lockerInfo->first_name.' '.$lockerInfo->last_name.' ('.$lockerInfo->username.') at '.$epeInfo->ci_lock_at }}</div>
    </div>
</div>
@endif
<div id="lockedMsg">

</div>
<!--studentIndfo modal-->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-info"><!-- mysql data will load in table -->
        </div>
    </div>
</div>

<script type="text/javascript">
    //student info
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });

    $(document).on('click', '#getStudentInfo', function (e) {
        e.preventDefault();
        var userId = $(this).data('id'); // get id of clicked row

        $('#dynamic-info').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('ajaxresponse/student-details') }}",
            type: "GET",
            data: {
                user_id: userId
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#dynamic-info').html(''); // blank before load.
                $('#dynamic-info').html(response.html); // load here
                $('.date-picker').datepicker({autoclose: true});
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });
</script>

