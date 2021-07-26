
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="contain-center">
            <th class="text-center">{{__('label.SL_NO')}}</th>
            <th class="text-center">{{__('label.TYPE')}}</th>
            <th>{{__('label.SUBJECT_INFO')}}</th>
            <th>{{__('label.TITLE')}}</th>
            <th class="text-center">{{__('label.RESULT_PUBLISH_DATE_TIME')}}</th>
            <th class='text-center'>{{__('label.ACTION')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($allEpeArr))
        <?php
        $sl = 0;
        ?>
        @foreach($allEpeArr as $value)
        <?php
        $resultPublishDateTime = $value['result_publish'];  
        $tadayDateTime = date("Y-m-d H:i:s");
        ?>
        @if($resultPublishDateTime > $tadayDateTime)
		
        <tr class=" contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td class="text-center">
                @if($value['type'] == '1')
                <label class="label label-primary label-sm">{{__('label.REGULAR')}}</label>
                @endif
            </td>
            <td>

                <label>{{__('label.SUBJECT')}} : {{$subjectList[$value['subject_id']]}}</label><br/>
            </td>
            <td>{{$value['title'] }}</td>
            <td class="text-center">{{Helper::printDateTime($value['result_publish']) }}</td>
            
            <td class="action-center">
                <div class="text-center user-action">

                    <a class="tooltips get_objective_Question" data-toggle="modal" data-target="#view_objective_question" data-id="{{$value['epe_id']}}" data-type="{{$value['type']}}" href="#view_objective_question" id="getObjectiveQuestion{{$value['epe_id']}}" title="{{ __('label.VIEW_OBJECTIVE_QUESTION') }}" data-container="body" data-trigger="hover" data-placement="top">
                        <span class="btn btn-success btn-sm yellow "> 
                            &nbsp;<i class='fa fa-question'></i>&nbsp;
                        </span>
                    </a>
                    <!--objective question print-->
                    <a class="tooltips" href="{{url('perviousquestion/question_details?view=print&epe_id='.$value['epe_id'].'&type='.$value['type'])}}" title="{{ __('label.PRINT_OBJECTIVE_QUESTION') }}" target="_blank">
                        <span class="btn btn-sm yellow-mint"> 
                            &nbsp;<i class='fa fa-print'></i>&nbsp;
                        </span>
                    </a>   
                    <a class="tooltips get_subjective_question" data-toggle="modal" data-target="#view_subjective_question" data-id="{{$value['epe_id']}}" data-type="{{$value['type']}}" href="#view_subjective_question" id="getSubjectiveQuestion{{$value['epe_id']}}" title="{{ __('label.VIEW_SUBJECTIVE_QUESTION') }}" data-container="body" data-trigger="hover" data-placement="top">
                        <span class="btn btn-success btn-sm yellow-soft "> 
                            &nbsp;<i class='fa fa-question'></i>&nbsp;
                        </span>
                    </a>
                    <!--subjective question print-->
                    <a class="tooltips" href="{{url('perviousquestion/subjective_question_details?view=print&epe_id='.$value['epe_id'].'&type='.$value['type'])}}" title="{{ __('label.PRINT_SUBJECTIVE_QUESTION') }}" target="_blank">
                        <span class="btn btn-sm yellow-gold"> 
                            &nbsp;<i class='fa fa-print'></i>&nbsp;
                        </span>
                    </a>

                </div>
            </td>
        </tr>
        @endif
        @endforeach
        @else
        <tr>
            <td colspan="7">{{__('label.EMPTY_DATA')}}</td>
        </tr>
        @endif 
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function () {
        $(".tooltips").tooltip({html: true});
    });
</script>