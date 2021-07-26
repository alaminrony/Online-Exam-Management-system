<div class="row">
    <div class="col-md-12 text-right">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a target="_blank" href="{{ URL::to('/eperesult?type=print&course_id='.Request::get('course_id')).'&part_id='.Request::get('part_id').'&subject_id='.Request::get('subject_id') }}"  id="print" class="btn blue" title="Print Result">
            <i class="fa fa-print"></i> {{ trans('english.PRINT') }}
        </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a target="_blank" href="{{ URL::to('/eperesult?type=pdf&course_id='.Request::get('course_id')).'&part_id='.Request::get('part_id').'&subject_id='.Request::get('subject_id') }}"  id="pdf" class="btn green" title="Download Result">
            <i class="fa fa-file-pdf-o"></i> {{ trans('english.DOWNLOAD') }}
        </a>
    </div>    
</div>
<br/>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">{{trans('english.SL_NO')}}</th>
            <th class="text-center">{{trans('english.EXAM_TYPE')}}</th>
            <th class="text-center">{{trans('english.STUDENT_TYPE')}}</th>
            <th class="text-center">{{trans('english.ISS_NO')}}</th>
            <th class="text-left">{{trans('english.STUDENT')}}</th>
            <th class="text-center">{{trans('english.REG_NO')}}</th>
            <th class="text-center">{{trans('english.OBJECTIVE_SUBMITTED_DATETIME')}}</th>
            <th class="text-center">{{trans('english.SUBJECTIVE_SUBMITTED_DATETIME')}}</th>
            <th class="text-center">{{trans('english.OBJECTIVE')}}</th>
            <th class="text-center">{{trans('english.SUBJECTIVE')}}</th>
            <th class="text-center">{{trans('english.ACHIEVED_MARKS')}}</th>
            <th class="text-center">{{trans('english.ACHIEVED')}}(%)</th>
            <th class="text-center">{{trans('english.STATUS')}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($result_showable == '1')
        @if (!empty($eperesultFinalArr))
        <?php $sl = 0; ?>
        @foreach($eperesultFinalArr as $value)
        @if(!is_array($value))
        <tr class="contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td class="text-center">
                @if($value->type == '1')
                <span class="label label-success"> {{trans('english.REGULAR')}} </span>
                @elseif($value->type == '2')
                <span class="label label-warning"> {{trans('english.IRREGULAR')}} </span>
                @else
                <span class="label label-info"> {{trans('english.RESCHEDULE')}} </span>
                @endif
            </td>
            <td class="text-center">
                @if($value->totalAttempt > 1)
                <span class="label bg-red bg-font-red"> {{trans('english.IRREGULAR')}} </span>
                @else
                <span class="label bg-blue bg-font-blue"> {{trans('english.REGULAR')}} </span>
                @endif
            </td>
            <td class="text-center">{{$value->iss_no}}</td>
            <td class="text-left">{{$value->student_name}}</td>
            <td class="text-center">{{$value->registration_no}}</td>
            <td class="text-center">{{(strtotime($value->objective_submission_time) > 0 ) ?  $value->objective_submission_time : trans('english.N_A')}}</td>
            <td class="text-center">{{(strtotime($value->subjective_submission_time) > 0) ?  $value->subjective_submission_time : trans('english.N_A')}}</td>
            <td class="text-right">{{ ($value->objective_earned_mark == null) ? trans('english.BLANK') :  $value->objective_earned_mark}}</td>
            <td class="text-right">{{ ($value->subjective_earned_mark == null) ? trans('english.BLANK') :  $value->subjective_earned_mark}}</td>
            <td class="text-right">{{ ($value->converted_mark == null) ? trans('english.BLANK') :  $value->converted_mark}}</td>
            <td class="text-right">{{ ($value->converted_mark == null) ? trans('english.BLANK') : (($value->total_mark * $value->converted_mark)/100).'%' }}</td>
            <td class="text-center">
                @if($value->pass == 1)
                <span class="label label-success"> {{trans('english.PASSED')}} </span>
                @else
                <span class="label label-danger"> {{trans('english.FAILED')}} </span>
                @endif
            </td>
        </tr>
        @else        
        <tr>
            <td class="text-center">{{++$sl}}</td>
            <td></td>
            <td></td>
            <td class="text-center">{{$value['iss_no']}}</td>
            <td class="text-left">{{$value['student_name']}}</td>
            <td class="text-center">{{$value['registration_no']}}</td>
            <td></td>
            <td></td>
            <td></td> <td></td> <td></td> <td></td>
            <td class="text-center">
                @if($value['attendee_status'] == '2')
                <span class="label btn yellow-soft text-center"> {{trans('english.CC_TAKEN')}} </span>
                @elseif($value['attendee_status'] == '3')
                <span class="label label-warning text-center"> {{trans('english.ABSENT')}} </span>
                @endif
            </td>
        </tr>        
        @endif
        @endforeach
        @endif
        @else
        <tr>
            <td colspan="13">{{trans('english.EMPTY_DATA')}}</td>
        </tr>
        @endif
    </tbody>
</table>
