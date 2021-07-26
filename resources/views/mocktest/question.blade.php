<div class="portlet light bordered">
    <div class="portlet-body ">
        <div class="row">
            <div class="col-md-12">
                <p class="bold text-center" style="text-decoration: underline; font-size: 16px;">
                    {{$mockTestInfo->course_title}}
                    <br>{{$mockTestInfo->part_title}}, {{$mockTestInfo->phase_titlee}}
                    <br>{{__('label.SUBJECT')}}: {{$mockTestInfo->subject_title}}
                    <br>{{__('label.EPE')}}: {{$mockTestInfo->epe_title}}
                    <br>{{__('label.MOCK_TEST')}}: {{$mockTestInfo->title}}
                </p>
            </div>
            <div class="col-md-12">
                <label class="text-left bold" style="width: 50%; font-size: 16px;">{{__('label.TIME')}}: {{(strlen($mockTestInfo->duration_hours) === 1) ? '0'.$mockTestInfo->duration_hours : $mockTestInfo->duration_hours }}:{{(strlen($mockTestInfo->duration_minutes) === 1) ? '0'.$mockTestInfo->duration_minutes : $mockTestInfo->duration_minutes }}</label>
            </div>
        </div>
        <?php $i = 1; ?>
        <div class="mt-element-step">
            @if((!$objective->isEmpty()) || (!$trueFalse->isEmpty()) || (!$fillingBlank->isEmpty()) || (!$matchingArr->isEmpty()))
            @if(!$objective->isEmpty())
            @foreach($objective as $question)

            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="font-grey-cascade"><h4>{{$i.'.  '.$question->question}}</h4></div>
                </div>

                @if(!empty($question->image))
                <div class="col-md-12 text-center">
                    <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question->image }}" alt="{{ $question->image }}"> 
                </div>
                @endif

                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">a</div>
                    <div class="mt-step-content font-grey-cascade">{{$question->opt_1}}</div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">b</div>
                    <div class="mt-step-content font-grey-cascade">{{$question->opt_2}}</div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">c</div>
                    <div class="mt-step-content font-grey-cascade">{{$question->opt_3}}</div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">d</div>
                    <div class="mt-step-content font-grey-cascade">{{$question->opt_4}}</div>
                </div>
            </div>
            <?php $i++; ?>
            @endforeach
            @endif


            @if(!$trueFalse->isEmpty())
            <h4><b>{{__('label.MARK_TRUE_FALSE').': '}}</b></h4>
            <?php //$i = 1; ?>
            @foreach($trueFalse as $question2)
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="font-grey-cascade"><h4>{{$i.'.  '.$question2->question}}</h4></div>
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
            <h4><b>{{__('label.FILLING_THE_BLANK').': '}}</b></h4>
            <?php //$i = 1; ?>
            @foreach($fillingBlank as $question3)
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="font-grey-cascade"><h4>{{$i.'.  '.$question3->question}}</h4></div>
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

            @if(!$matchingArr->isEmpty())
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="h4 bg-font-grey-cararra"><h4 class="bold margin-bottom-20">{{__('label.MATCH_EACH_WORD_OF_COLUMN_A_WITH_THE_WORDS_OF_COLUMN_B').': '}}</h4></div>
                </div>
            </div>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center col-md-1">{{__('label.SL_NO')}}</th>
                        <th class="text-center"><strong>{{__('label.COLUMN_A')}}</strong></th>
                        <th class="text-center"><strong>{{__('label.COLUMN_B')}}</strong></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($matchingArr as $question6)
                    <tr>
                        <td class="text-center">{{ $i }} </td>
                        <td>
                            {{$question6->question}}
                            @if(!empty($question6->image))
                            <div class="col-md-12 text-center">
                                <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question6->image }}" alt="{{ $question6->image }}"> 
                            </div>
                            @endif
                        </td>
                        <td>{{$question6->match_answer}}</td>
                    </tr>

                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            @endif


            @else
            <h3 class="text-center text-danger">{{__('label.NO_QUESTION_FOUND_FOR_THIS_MOCK_TEST')}}</h3>
            @endif
        </div>
    </div>
</div>

<style type="text/css">

    .question-script-image{
        padding: 20px;
        max-height: 300px;
    }

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

</style>