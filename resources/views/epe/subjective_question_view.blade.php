<div class="portlet light bordered">
    <div class="portlet-body ">
        <div class="row">
            <div class="col-md-12">
                <p class="bold text-center" style="text-decoration: underline;">
                    {{$epeInfo->course->title}}
                    <br>{{$epeInfo->part->title}}, {{$epeInfo->phase->full_name}}
                    <br>{{trans('english.END_OF_PHASE_EXAMINATION_EPE')}}
                    <br>{{trans('english.SUBJECT')}}: {{$epeInfo->subject->title}}
                </p>
            </div>
            <div class="col-md-12">
                <label class="text-left bold" style="width: 50%">{{trans('english.TIME')}}: {{(strlen($epeInfo->sub_duration_hours) === 1) ? '0'.$epeInfo->sub_duration_hours : $epeInfo->sub_duration_hours }}:{{(strlen($epeInfo->sub_duration_minutes) === 1) ? '0'.$epeInfo->sub_duration_minutes : $epeInfo->sub_duration_minutes }}</label><label class="text-right" style="width: 50%"><strong>{{trans('english.FULL_MARKS')}}:</strong> {{ Custom::numberFormat($markDistribution->subjective) }}</label>
            </div>
            <div class="col-md-12">
                <p class="bold text-left" style="text-decoration: underline;">{{trans('english.THE_FIGURES_IN_THE_MARGIN_INDICATE_ALLOTTED_MARKS')}} (any {{$epeInfo->sub_no_mandatory}})</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(!empty($targetArr))
                @foreach($targetArr as $key => $questionSet)
                <div class="subject_question">
                    <div class="bg-font-grey-cararra"><h4>{{'Q'.$key.'.  '.$questionSet['set_title']}}</h4></div>
                </div>
                @if(!empty($questionSet['question_set']))
                <ol type="a">
                    @foreach($questionSet['question_set'] as $question)
                    <div class="row">
                        <div class="col-md-10">
                            <li>{{$question['question']}}</li>
                        </div>
                        <div class="col-md-2">
                            {{$question['marks']}}
                        </div>
                    </div>

                    @if(!empty($question['image']))
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question['image'] }}" alt="{{ $question['image'] }}"> 
                        </div>
                    </div>
                    @endif
                    
                    @endforeach
                </ol>
                @endif
                <p style="margin: 0;padding: 0;">&nbsp;</p>
                @endforeach
                @else 
                <h3 class="text-center text-danger">{{trans('english.NO_QUESTION_FOUND_FOR_THIS_EPE')}}</h3>
                @endif
            </div>
        </div>

    </div>
</div>


<style type="text/css">
    .mt-element-step .step-no-background-thin .mt-step-content {
        padding-left: 60px;
        margin-top: 5px;
    }
    .mt-element-step .step-no-background-thin .mt-step-number {
        font-size: 20px;
        border-radius: 50%!important;
        float: left;
        margin: auto;
        padding: 0px 8px;
        border: 1px solid #e5e5e5;
    }
    .mt-element-step .step-no-background-thin .mt-step-number {
        font-size: 20px;
        border-radius: 50%!important;
        float: left;
        margin: auto;
        padding: 0px 8px;
        border: 1px solid #000000;
    }
    .font-grey-cascade {
        color: #000000!important;
    }

    .question-script-image{
        padding: 20px;
        max-height: 300px;
    }

</style>