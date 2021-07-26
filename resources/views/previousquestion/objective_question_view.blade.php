<div class="portlet light bordered">
    <div class="portlet-body ">
        <div class="row">
            <div class="col-md-12">
                <p class="bold text-center" style="text-decoration: underline;">
                    <br>{{__('label.END_OF_PHASE_EXAMINATION_EPE')}}
                    <br>{{__('label.SUBJECT')}}: {{$epeInfo->subject->title}}
                </p>
            </div>
            <div class="col-md-12">
                <label class="text-left bold" style="width: 50%">{{__('label.TIME')}}: {{(strlen($epeInfo->obj_duration_hours) === 1) ? '0'.$epeInfo->obj_duration_hours : $epeInfo->obj_duration_hours }}:{{(strlen($epeInfo->obj_duration_minutes) === 1) ? '0'.$epeInfo->obj_duration_minutes : $epeInfo->obj_duration_minutes }}</label><label class="text-right" style="width: 50%"><strong>{{__('label.FULL_MARKS')}}:</strong> </label>
            </div>
        </div>
        <div class="mt-element-step">
            @if((!$objective->isEmpty()) || (!$trueFalse->isEmpty()) || (!$fillingBlank->isEmpty()))
            <?php $i = 1; ?>
            @if(!$objective->isEmpty())
            @foreach($objective as $question)

            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="h5 bg-font-grey-cararra"><h5 class="bold">{{$i.'.  '.$question->question}}</h5></div>
                </div>

                @if(!empty($question->image))
                <div class="col-md-12 text-center">
                    <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question->image }}" alt="{{ $question->image }}"> 
                </div>
                @endif

                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number bg-font-grey-cararra">a</div>
                    <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_1}}</div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number bg-font-grey-cararra">b</div>
                    <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_2}}</div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number bg-font-grey-cararra">c</div>
                    <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_3}}</div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number bg-font-grey-cararra">d</div>
                    <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_4}}</div>
                </div>
            </div>

            <?php $i++; ?>
            @endforeach
            @endif


            @if(!$trueFalse->isEmpty())
            <h3><b>{{__('label.MARK_TRUE_FALSE').': '}}</b></h3>
            <?php //$i = 1; ?>
            @foreach($trueFalse as $question2)
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="bg-font-grey-cararra"><h5 class="bold">{{$i.'.  '.$question2->question}}</h5></div>
                </div>
            </div>

            @if(!empty($question2->image))
            <div class="col-md-12 text-center">
                <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question2->image }}" alt="{{ $question2->image }}"> 
            </div>
            @endif

            <?php $i++; ?>
            @endforeach
            @endif 

            @if(!$fillingBlank->isEmpty())
            <h3><b>{{__('label.FILLING_THE_BLANK').': '}}</b></h3>
            <?php //$i = 1; ?>
            @foreach($fillingBlank as $question3)
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="bg-font-grey-cararra"><h5 class="bold">{{$i.'.  '.$question3->question}}</h5></div>
                </div>
            </div>

            @if(!empty($question3->image))
            <div class="col-md-12 text-center">
                <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question3->image }}" alt="{{ $question3->image }}"> 
            </div>
            @endif

            <?php $i++; ?>
            @endforeach
            @endif

            @if(!$subjective->isEmpty())
            <h3><b>{{__('label.SUBJECTIVE').': '}}</b></h3>
            <?php //$i = 1; ?>
            @foreach($subjective as $question4)
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="bg-font-grey-cararra"><h5 class="bold">{{$i.'.  '.$question4->question}}</h5></div>
                </div>
            </div>

            @if(!empty($question4->image))
            <div class="col-md-12 text-center">
                <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question4->image }}" alt="{{ $question2->image }}"> 
            </div>
            @endif

            <?php $i++; ?>
            @endforeach
            @endif 
            @else
            <h3 class="text-center text-danger">{{__('label.NO_QUESTION_FOUND_FOR_THIS_EPE')}}</h3>
            @endif
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
    .bg-font-grey-cararra {
        color: #000000!important;
    }

    .question-script-image{
        padding: 20px;
        max-height: 300px;
    }

</style>