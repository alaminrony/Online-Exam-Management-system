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
            .col-md-10{
                width: 80%;
                float: left;
            }
            .col-md-2{
                width: 20%;
                float: right;
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
                <label class="text-left font-bold" style="width: 50%">{{trans('english.TIME')}}: {{(strlen($epeInfo->sub_duration_hours) === 1) ? '0'.$epeInfo->sub_duration_hours : $epeInfo->sub_duration_hours }}:{{(strlen($epeInfo->sub_duration_minutes) === 1) ? '0'.$epeInfo->sub_duration_minutes : $epeInfo->sub_duration_minutes }}</label>
                <label class="float-right-text" style="width: 50%"><strong>{{trans('english.FULL_MARKS')}}:</strong> {{ Custom::numberFormat($markDistribution->subjective) }}</label>
            </div>
            <div class="col-md-12">
                <p class="text-left font-bold" style="text-decoration: underline;">{{trans('english.THE_FIGURES_IN_THE_MARGIN_INDICATE_ALLOTTED_MARKS')}} (any {{$epeInfo->sub_no_mandatory}})</p>
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

        <script>
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
                //window.close();
            });
        </script>
    </body>
</html>
