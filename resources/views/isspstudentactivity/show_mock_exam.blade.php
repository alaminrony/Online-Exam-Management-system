<div class="pricing-content-1" style="background-color:#eef1f5;">
    <div class="col-md-12">
        <div class="price-column-container border-active">
            <div class="price-table-head bg-green">
                <h2 class="no-margin">{{$mockExamInfoArr->course_title}}</h2>
            </div>
            <div class="price-table-content epe-exam">
                <div class="row mobile-padding">
                    <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.PHASE').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->phase_name}}</div>
                </div>
                <div class="row mobile-padding">
                    <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.SUBJECT').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->subject_title}}</div>
                </div>
                 <div class="row mobile-padding">
                     <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.START_DATE_TIME').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->start_at}}</div>
                </div>
                <div class="row mobile-padding">
                     <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.END_DATE_TIME').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->end_at}}</div>
                </div>
                <div class="row mobile-padding">
                    <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.DURATION').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->duration_hours.':'.$mockExamInfoArr->duration_minutes.' '. __('label.MINUTE') }}</div>
                </div>
                <div class="row mobile-padding">
                    <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.TOTAL_QUESTIONS').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->obj_no_question}}</div>
                </div>
                @if(!empty($markDistribution->epe_total_mark))
                <?php 
                $passMark = ceil(($markDistribution->epe_passing_mark * $mockExamInfoArr->obj_no_question)/$markDistribution->epe_total_mark);
                ?>
                <div class="row mobile-padding">
                     <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.TOTAL_MARK').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$mockExamInfoArr->obj_no_question}}</div>
                </div>
                <div class="row mobile-padding">
                     <div class="col-md-5 text-right mobile-padding">
                       {{ __('label.PASSING_MARK').' : '}}
                    </div>
                    <div class="col-md-7 text-left mobile-padding">{{$passMark}}</div>
                </div>
                @endif
            </div>
            <div class="arrow-down arrow-grey"></div>
            <div class="price-table-footer">
                <a href="{{URL::to('mockExam?id='.$mockExamInfoArr->id)}}" class="btn green price-button btn-circle uppercase">Start</a>
                <a href="{{URL::to('isspstudentactivity/mymocktest')}}">
                    <button type="button" class="btn btn-circle grey-salsa btn-outline uppercase">{{ __('label.CANCEL') }}</button> 
                </a>
            </div>
        </div>
    </div>
</div>