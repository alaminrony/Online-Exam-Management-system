<?php 
$courseArr = $phaseArr = $partArr = array();
$courseName = $partName = $phaseName = '';
foreach ($epeArr->epeDetail as $key => $item){ 
    
    if(!in_array($item->course->id, $courseArr)){
        $courseArr[$key] = $item->course->id;
        $courseName .= $item->course->title.', ';
    }

    if(!in_array($item->part->id, $partArr)){
        $partArr[$key] = $item->part->id;
        $partName .= $item->part->title.', ';
    }

    if(!in_array($item->phase->id, $phaseArr)){
        $phaseArr[$key] = $item->phase->id;
        $phaseName .= $item->phase->full_name.', ';
    }
}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EPE_DETAILS_INFORMATION')}}</h4>
</div>
<div class="modal-body">

    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center" colspan="2">{{trans('english.BASIC_INFORMATION')}}</th>
        </tr>
        <tr>
            <th width="40%">{{trans('english.COURSE_NAME')}} </th>
            <td width="60%"> {{trim($courseName, ', ')}} </td>
        </tr>

        <tr>
            <th>{{trans('english.PART')}} </th>
            <td> {{trim($partName, ', ')}} </td>
        </tr>
        <tr>
            <th>{{trans('english.PHASE')}} </th>
            <td> {{trim($phaseName, ', ')}} </td>
        </tr>
        <tr>
            <th>{{trans('english.SUBJECT_NAME')}} </th>	
            <td> {{$epeArr->subject->title}} </td>
        </tr>	
        <tr>
            <th>{{trans('english.TITLE')}} </th>
            <td> {{$epeArr->title}} </td>
        </tr>
        <tr>
            <th>{{trans('english.EXAM_DATE')}} </th>
            <td> {{$epeArr->exam_date}} </td>
        </tr>
        <tr>                                  
            <th>{{trans('english.TIME')}}</th>
            <td>{{ substr($epeArr->start_time,0,5) }} {{trans('english.TO')}} {{ substr($epeArr->end_time,0,5) }}</td>
        </tr>	
        <tr>
            <th>{{trans('english.DURATION')}}</th>
            <td>
                <?php
                $datetime1 = new DateTime($epeArr->exam_date . ' ' . $epeArr->start_time);
                $datetime2 = new DateTime($epeArr->exam_date . ' ' . $epeArr->end_time);
                $interval = $datetime1->diff($datetime2);
                ?>
                {{$interval->format('%H').":".$interval->format('%I')}}
            </td>
        </tr>	
        <!-- <tr> 	
            <th>{{trans('english.NO_OF_MOCK_TEST')}}</th>
            <td>{{ $epeArr->no_of_mock }}</td>
        </tr> -->	
        <tr> 	
            <th>{{trans('english.RESULT_SUBMISSION_DEADLINE')}}</th>
            <td>{{ !empty($epeArr->submission_deadline) ? $epeArr->submission_deadline : trans('english.N_A')}}</td>
        </tr>
        <tr> 	
            <th>{{trans('english.RESULT_PUBLISHED_DATE')}}</th>
            <td>{{ !empty($epeArr->result_publish) ? $epeArr->result_publish : trans('english.N_A')}}</td>
        </tr>	
        <tr> 	
            <th>{{trans('english.STATUS')}}</th>
            <td>
                @if ($epeArr->status == '1')
                <span class="label label-success">{{ trans('english.ACTIVE')}}</span>
                @else
                <span class="label label-warning">{{ trans('english.INACTIVE') }}</span>
                @endif
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center" colspan="2"> {{trans('english.OBJECTIVE_INFORMATION')}}</th>
        </tr>

        <tr>
            <th width="40%">{{trans('english.DURATION')}} </th>
            <td width="60%">{{(strlen($epeArr->obj_duration_hours) === 1) ? '0'.$epeArr->obj_duration_hours : $epeArr->obj_duration_hours }}:{{(strlen($epeArr->obj_duration_minutes) === 1) ? '0'.$epeArr->obj_duration_minutes : $epeArr->obj_duration_minutes }}</td>
        </tr>
        <tr> 
            <th>{{trans('english.TOTAL_NUMBER_OF_QUESTIONS')}}</th>
            <td>{{ $epeArr->obj_no_question }}</td>
        </tr>	
        <tr> 	
            <th>{{trans('english.QUESTION_AUTO_SELECTION')}}</th>
            <td>
                @if ($epeArr->obj_auto_selected == '1')
                <span class="label label-primary">{{ trans('english.AUTO')}}</span>
                @else
                <span class="label label-warning">{{ trans('english.MANUAL') }}</span>
                @endif
            </td>
        </tr>	
    </table>
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center" colspan="2"> {{trans('english.SUBJECTIVE_INFORMATION')}} </th>
        </tr>

        <tr>
            <th width="40%">{{trans('english.DURATION')}} </th>
            <td width="60%">{{(strlen($epeArr->sub_duration_hours) === 1) ? '0'.$epeArr->sub_duration_hours : $epeArr->sub_duration_hours }}:{{(strlen($epeArr->sub_duration_minutes) === 1) ? '0'.$epeArr->sub_duration_minutes : $epeArr->sub_duration_minutes }}</td>
        </tr>
        <tr> 
            <th>{{trans('english.NEED_TO_ANSWER')}}</th>
            <td>{{ $epeArr->sub_no_mandatory }} {{trans('english.OUT_OF')}} {{ $epeArr->sub_no_question }}</td>
        </tr>	
    </table>
</div>
