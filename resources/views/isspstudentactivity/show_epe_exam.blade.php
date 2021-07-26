<div class="pricing-content-1" style="background-color:#eef1f5;">
    <div class="col-md-offset-3 col-md-5 col-sm-offset-2 col-sm-8 col-xs-offset-0 col-xs-12">
        <div class="price-column-container border-active">
            <div class="price-table-head bg-green">
                <h2 class="no-margin">{{$epeExamInfoArr->Course->title}}</h2>
            </div>
            <div class="price-table-content epe-exam">
                <div class="row mobile-padding">
                    <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.PHASE').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{$epeExamInfoArr->Phase->name}}</div>
                </div>
                <div class="row mobile-padding">
                    <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.SUBJECT').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{$epeExamInfoArr->Subject->title}}</div>
                </div>
                 <div class="row mobile-padding">
                     <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.START_TIME').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{$epeExamInfoArr->start_time}}</div>
                </div>
                <div class="row mobile-padding">
                     <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.END_TIME').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{$epeExamInfoArr->end_time}}</div>
                </div>
                <div class="row mobile-padding">
                    <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.DURATION').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{($epeExamInfoArr->obj_duration_hours > 0) ? $epeExamInfoArr->obj_duration_hours.' hour' : ''}} {{$epeExamInfoArr->obj_duration_minutes.' min'}}</div>
                </div>
                <div class="row mobile-padding">
                    <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.TOTAL_QUESTIONS').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{$epeExamInfoArr->obj_no_question}}</div>
                </div>
                @if(!empty($markDistribution->epe_total_mark))
                <div class="row mobile-padding">
                     <div class="col-xs-5 text-right mobile-padding">
                       {{ trans('english.TOTAL_MARK').' : '}}
                    </div>
                    <div class="col-xs-7 text-left mobile-padding">{{$markDistribution->epe_total_mark}}</div>
                </div>
                @endif
            </div>
            <div class="arrow-down arrow-grey"></div>
            <div class="price-table-footer">
                <a href="{{URL::to('epeExam?id='.$epeExamInfoArr->id)}}" class="btn green price-button btn-circle uppercase">Start</a>
                <a href="{{URL::to('isspstudentactivity/myepe')}}">
                    <button type="button" class="btn btn-circle grey-salsa btn-outline uppercase">{{ trans('english.CANCEL') }}</button> 
                </a>
            </div>
        </div>
    </div>
</div>