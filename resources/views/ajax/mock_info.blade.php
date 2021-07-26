<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{__('label.MOCK_DETAILS_INFO')}}</h4>
</div>
<div class="modal-body">

    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th>{{__('label.SUBJECT_NAME')}} </th>	
            <td> {{$mockTestInfo->subject_title}} </td>
        </tr>	
        <tr>
            <th>{{__('label.TITLE')}} </th>

            <td> {{$mockTestInfo->title}} </td>
        </tr>
        <tr>
            <th>{{__('label.EPE_TITLE')}} </th>

            <td> {{$mockTestInfo->epe_title}} </td>
        </tr>  
        <tr>
            <th>{{__('label.TOTAL_NUMBER_OF_QUESTIONS')}} </th>

            <td> {{$mockTestInfo->obj_no_question}} </td>
        </tr>
        <tr>
            <th>{{__('label.START_DATE_TIME')}} </th>

            <td> {{$mockTestInfo->start_at}} </td>
        </tr>
        <tr>
            <th>{{__('label.END_DATE_TIME')}} </th>

            <td> {{$mockTestInfo->end_at}} </td>
        </tr>
        <tr>
            <th>{{__('label.DURATION')}} </th>
            <td> {{(strlen($mockTestInfo->duration_hours) === 1) ? '0'.$mockTestInfo->duration_hours : $mockTestInfo->duration_hours }}:{{(strlen($mockTestInfo->duration_minutes) === 1) ? '0'.$mockTestInfo->duration_minutes : $mockTestInfo->duration_minutes }} </td>
        </tr>
        <tr>
            <th>{{__('label.QUESTION_AUTO_SELECTION')}}</th>
            <td>
                 @if ($mockTestInfo->obj_auto_selected == '1')
                    <span class="label label-primary">{{ __('label.AUTO')}}</span>
                @else
                    <span class="label label-warning">{{ __('label.MANUAL') }}</span>
                @endif
            </td>
        </tr>
    </table>
</div>
