<html>
    <head>
        <title>{{trans('english.CSTI_FULL')}}</title>
        <link rel="shortcut icon" type="image/icon" href="{{URL::to('/')}}/public/img/favicon.ico"/>
        {{ HTML::style('public/css/print.css'); }}
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
            /*:::style*/
            .float-right-text{
                float: right;
                text-align: right;
                margin-right: 15px;
                font-weight: bold;
            }
            .mt-step-col{
                padding-top: 10px;
                padding-bottom: 10px;
            }
            .col-md-6 {
                width: 50%;
                position: relative;
                min-height: 1px;
                padding-left: 15px;
                padding-right: 15px;

            }
            .col-md-right {
                width: 50%;
                position: relative;
                min-height: 1px;
                padding-left: 15px;
                padding-right: 15px;
                float: right;

            }
            .col-md-left {
                width: 50%;
                position: relative;
                min-height: 1px;
                padding-left: 15px;
                padding-right: 15px;
                float: left;

            }
            .text-left{
                text-align:left;
            }
            table.no-border, th.no-border{
                border: 0;
            }
            .width-style{
                width: 100%;
            }
            .float-left{
                float: left;
                width: 50%;
                text-align: left;
            }
            .float-right{
                float: right;
                width: 49%;
                text-align: left;
            }
            .font-bold{
                font-weight: bold;
            }

        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-md-12">
                <?php
                $courseArr = $phaseArr = $partArr = array();
                $courseName = $partName = $phaseName = '';
                foreach ($epeInfo->epeDetail as $key => $item) {

                    if (!in_array($item->course->id, $courseArr)) {
                        $courseArr[$key] = $item->course->id;
                        $courseName .= $item->course->title . ', ';
                    }

                    if (!in_array($item->part->id, $partArr)) {
                        $partArr[$key] = $item->part->id;
                        $partName .= $item->part->title . ', ';
                    }

                    if (!in_array($item->phase->id, $phaseArr)) {
                        $phaseArr[$key] = $item->phase->id;
                        $phaseName .= $item->phase->full_name . ', ';
                    }
                }
                ?>
                <p class="font-bold text-center" style="text-decoration: underline;">
                    {{trim($courseName, ', ')}}
                    <br>{{trim($partName, ', ')}}
                    <br>{{trim($phaseName, ', ')}}
                    <br>{{trans('english.END_OF_PHASE_EXAMINATION_EPE')}}
                    <br>{{trans('english.SUBJECT')}}: {{$epeInfo->subject->title}}
                </p>
            </div>
            <div class="col-md-12">
                <label class="text-left font-bold" style="width: 50%">{{trans('english.TIME')}}: {{(strlen($epeInfo->obj_duration_hours) === 1) ? '0'.$epeInfo->obj_duration_hours : $epeInfo->obj_duration_hours }}:{{(strlen($epeInfo->obj_duration_minutes) === 1) ? '0'.$epeInfo->obj_duration_minutes : $epeInfo->obj_duration_minutes }}</label>
                <label class="float-right-text" style="width: 50%"><strong>{{trans('english.FULL_MARKS')}}:</strong> {{ Custom::numberFormat($markDistribution->objective) }}</label>
            </div>
        </div>
        <div class="mt-element-step">
            @if((!$objective->isEmpty()) || (!$trueFalse->isEmpty()) || (!$fillingBlank->isEmpty()) || (!$matchingArr->isEmpty()))
            <?php $i = 1; ?>
            @if(!$objective->isEmpty())
            @foreach($objective as $question)

            <div class="row step-no-background-thin width-style">
                <div class="mt-step-desc width-style">
                    <div class="h5 bg-font-grey-cararra"><h5 class="bold">{{$i.'.  '.$question->question}}</h5></div>
                </div>

                @if(!empty($question->image))
                <div class="col-md-12 text-center">
                    <img class="question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question->image }}" alt="{{ $question->image }}"> 
                </div>
                @endif

                <div class="width-style">
                    <div class="float-left">
                        <div class="mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">a</div>
                            <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_1}}</div>
                        </div>
                        <div class="mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">c</div>
                            <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_3}}</div>
                        </div>
                    </div>
                    <div class="float-right">
                        <div class="mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">b</div>
                            <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_2}}</div>
                        </div>
                        <div class="mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">d</div>
                            <div class="mt-step-content bg-font-grey-cararra">{{$question->opt_4}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++; ?>
            @endforeach
            @endif

            @if(!$trueFalse->isEmpty())
            <h3><b>{{trans('english.MARK_TRUE_FALSE').': '}}</b></h3>
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
            <h3><b>{{trans('english.FILLING_THE_BLANK').': '}}</b></h3>
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

            @if(!$matchingArr->isEmpty())
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
                    <div class="h4 bg-font-grey-cararra"><h4 class="bold margin-bottom-20">{{trans('english.QUESTION_INSTRUCTION_FOR_MATCHING')}}</h4></div>
                </div>
            </div>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center col-md-1">{{trans('english.SL_NO')}}</th>
                        <th class="text-center"><strong>{{trans('english.COLUMN_A')}}</strong></th>
                        <th class="text-center"><strong>{{trans('english.COLUMN_B')}}</strong></th>

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
            <h3 class="text-center text-danger">{{trans('english.NO_QUESTION_FOUND_FOR_THIS_EPE')}}</h3>
            @endif
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
                //window.close();
            });
        </script>
    </body>
</html>
