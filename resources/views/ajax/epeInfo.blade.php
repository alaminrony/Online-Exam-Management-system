<div class="modal-header clone-modal-header">
    <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    <h4 class="modal-title bold text-center">
        @lang('label.EPE_DETAILS_INFORMATION')
    </h4>
</div>
<div class="modal-body">

    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center" colspan="2">{{__('label.BASIC_INFORMATION')}}</th>
        </tr>
        <tr>
            <th>{{__('label.SUBJECT_NAME')}} </th>	
            <td> {{$epeArr->subject_title}} </td>
        </tr>	
        <tr>
            <th>{{__('label.EXAM_TYPE')}} </th>	
            <td> {{$epeArr->type == '1'?__('label.REGULAR'):__('label.RETAKE')}} </td>
        </tr>	
        <tr>
            <th>{{__('label.TITLE')}} </th>
            <td> {{$epeArr->title}} </td>
        </tr>
        <tr>
            <th>{{__('label.TOTAL_MARK')}} </th>
            <td> {{$epeArr->total_mark}} </td>
        </tr>
        <tr>
            <th>{{__('label.EXAM_DATE')}} </th>
            <td> {{$epeArr->exam_date}} </td>
        </tr>
        <?php
        $datetime1 = new DateTime($epeArr->exam_date . ' ' . $epeArr->start_time);
        $datetime2 = new DateTime($epeArr->exam_date . ' ' . $epeArr->end_time);
        $interval = $datetime1->diff($datetime2);
        ?>
        <tr>                                  
            <th>{{__('label.TIME')}}</th>
            <td>{{ substr($epeArr->start_time,0,5) }} {{__('label.TO')}} {{ substr($epeArr->end_time,0,5) }} ({{$interval->format('%H').":".$interval->format('%I')}} Hours)</td>
        </tr>	
        <tr> 	
            <th>{{__('label.RESULT_SUBMISSION_DEADLINE')}}</th>
            <td>{{ !empty($epeArr->submission_deadline) ? Helper::formatDatetime($epeArr->submission_deadline) : __('label.N_A')}}</td>
        </tr>
        <tr> 	
            <th>{{__('label.RESULT_PUBLISHED_DATE')}}</th>
            <td>{{ !empty($epeArr->result_publish) ? Helper::formatDatetime($epeArr->result_publish) : __('label.N_A')}}</td>
        </tr>	

        <tr> 	
            <th>{{__('label.NO_OF_MOCK_TEST')}}</th>
            <td>{{ $epeArr->no_of_mock }}</td>
        </tr>	

        <tr> 	
            <th>{{__('label.STATUS')}}</th>
            <td>
                @if ($epeArr->status == '1')
                <span class="label label-success">{{ __('label.ACTIVE')}}</span>
                @else
                <span class="label label-warning">{{ __('label.INACTIVE') }}</span>
                @endif
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center" colspan="2"> {{__('label.QUESTION_SETUP')}}</th>
        </tr>

        <tr>
            <th width="40%">{{__('label.DURATION')}} </th>
            <td width="60%">{{(strlen($epeArr->obj_duration_hours) === 1) ? '0'.$epeArr->obj_duration_hours : $epeArr->obj_duration_hours }}:{{(strlen($epeArr->obj_duration_minutes) === 1) ? '0'.$epeArr->obj_duration_minutes : $epeArr->obj_duration_minutes }}</td>
        </tr>
        <tr> 
            <th>{{__('label.TOTAL_NUMBER_OF_QUESTIONS')}}</th>
            <td>{{ $epeArr->obj_no_question }}</td>
        </tr>	
        <tr> 	
            <th>{{__('label.QUESTION_AUTO_SELECTION')}}</th>
            <td>
                @if ($epeArr->obj_auto_selected == '1')
                <span class="label label-primary">{{ __('label.AUTO')}}</span>
                @else
                <span class="label label-warning">{{ __('label.MANUAL') }}</span>
                @endif
            </td>
        </tr>	
        <tr> 	
            <th>{{__('label.QUESTIONNAIRE_FORMAT')}}</th>
            <td>
                @if ($epeArr->questionnaire_format == '1')
                <span class="label label-primary">{{ __('label.ONE_TO_ONE')}}</span>
                @elseif($epeArr->questionnaire_format == '2')
                <span class="label label-warning">{{ __('label.ONE_TO_MANY') }}</span>
                @endif
            </td>
        </tr>	
        <tr> 	
            <th>{{__('label.UPLOAD_PDF_FILE')}}</th>
            <td>
                @if (!empty($epeArr->file ))
                <span class="label label-primary">{{ __('label.YES')}}</span>
                @else
                <span class="label label-warning">{{ __('label.NO') }}</span>
                @endif
            </td>
        </tr>	
    </table>

    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center" colspan="2"> {{__('label.SELECT_TYPE_QUESTIONS')}}</th>
        </tr>
        
        @if($qusTypeArr->isNotEmpty())
        @foreach($qusTypeArr as $qusTypeId => $typeName)
        <tr>
            <th width="40%">{{ $typeName }}</th>
            <td width="60%">{{ !empty($qusQusTypeDetailList[$qusTypeId])?$qusQusTypeDetailList[$qusTypeId]:'' }}</td>
        </tr>
        @endforeach
        @endif
    </table>

    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
    </div>
</div>
