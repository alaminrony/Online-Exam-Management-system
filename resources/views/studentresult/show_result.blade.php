<style>
    table.table tr:last-child{
        border-bottom: 1px solid #ddd;
    }

    table.table td,
    table.table th
    {
        vertical-align:middle!important;
    }
    .signature-section p{
        margin: 0;
    }
</style>
@if(!empty($targetArr))
    <div class="table-scrollable" style="padding-bottom: 20px;">
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px;">
                <div class="portlet-title">
                    <h4 class="text-center uppercase">{{trans('english.CONFIDENTIAL')}}</h4>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-12" style="margin-left: 10px;">
            <table class="table table-bordered table-striped">    
                <tbody>
                    <tr>
                        <td rowspan="4">
                            <div class="profile-userpic text-center">
                                @if(!empty($student->photo))
                                <img  src="{{URL::to('/')}}/public/uploads/user/{{$student->photo}}" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:auto;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                @else
                                <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:auto" alt="{{ $student->first_name.' '.$student->last_name }}">
                                @endif
                            </div>
                        </td>
                        <td colspan="2"><b>{{ $student->short_name.' '.$student->first_name.' '.$student->last_name }}</b></td>

                    </tr>
                    <tr>
                        <td>{{ trans('english.REGISTRATION_NO').' : '.$student->registration_no }}</td>
                        <td>{{ trans('english.OFFICIAL_NAME').' : '.$student->official_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('english.SERVICE_NO').' : '.$student->service_no }}</td>
                        <td>{{ trans('english.BRANCH').' : '.$student->branch_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('english.ISS_NO').' : '.$student->iss_no }}</td>
                        <td>{{ trans('english.REGISTRATION_EXPIRY_DATE').' : '.$student->maximum_tenure }}</td>
                    </tr>        
                    <tr>
                        <td>{{ trans('english.PROGRAM').' : '.$student->program_name }}</td>
                        <td>{{ trans('english.CURRENT_COURSE').' : '. $student->course_name }}</td>
                        <td>{{ trans('english.PART').' : '.$student->part_name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12" style="margin-left: 10px;">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th rowspan="2">{{trans('english.SL_NO')}}</th>
                        <th rowspan="2">{{trans('english.COURSE')}}</th>
                        <th rowspan="2">{{trans('english.TYPE_OF_EXAM')}}</th>
                        <th class="text-center" colspan="{{count($phaseArr)}}">{{trans('english.PHASE_WISE_MARKS')}}</th>
                        <th rowspan="2">{{trans('english.TOTAL_MARKS')}}</th>
                        <th rowspan="2">{{trans('english.GRAND_TOTAL_MARKS')}}</th>
                        <th rowspan="2">{{trans('english.AVERAGE_MARKS')}}</th>
                        <th rowspan="2">{{trans('english.LAST_DATE_OF_PASSING')}}</th>
                    </tr>
                    <tr>
                        @if(count($phaseArr))
                            @foreach($phaseArr as $phase )
                            <th>{{$phase->name}}</th>
                            @endforeach
                        @endif
                    </tr>
                    @if(count($phaseArr))
                        <?php 
                        $sl = 0; 
                        ?>
                        @foreach($targetArr as $studentId => $result)
                            
                            @foreach($result['course'] as $ck => $courseWise)
                                 <?php 
                                $taeTotal = isset($courseWise['tae_total']) ? Custom::numberFormat($courseWise['tae_total']) : 0;
                                $epeTotal = isset($courseWise['epe_total']) ? Custom::numberFormat($courseWise['epe_total']) : 0;

                                $grandTotal = $taeTotal + $epeTotal;
                                $totalTaeExam = isset($courseWise['TAE']['marks']) ? count($courseWise['TAE']['marks']) : 0;
                                $totalEpeExam = isset($courseWise['EPE']['marks']) ? count($courseWise['EPE']['marks']) : 0;
                                $totalExam = $totalTaeExam + $totalEpeExam;
                                $averageMark = ($grandTotal/$totalExam);
                                ?>
                                <tr>
                                    <td rowspan="2">{{++$sl}}</td>
                                    <td rowspan="2">{{$courseWise['course_title']}}</td>
                                  
                                    <td>{{trans('english.TAE')}}</td>
                                    @if(count($phaseArr))
                                        @foreach($phaseArr as $phase )
                                        <td>
                                            <?php $courseWise['TAE']['marks'] = isset($courseWise['TAE']['marks']) ? $courseWise['TAE']['marks'] : array(); ?>
                                                
                                            @if(array_key_exists($phase->id, $courseWise['TAE']['marks']))
                                                @if($courseWise['TAE']['result_status'][$phase->id] == 2)
                                                    <span class="tooltips text-danger text-center" data-tooltip="tooltip" title="{{Custom::numberFormat($courseWise['TAE']['marks'][$phase->id])}}">F</span>
                                                @else
                                                    {{Custom::numberFormat($courseWise['TAE']['marks'][$phase->id])}}
                                                @endif
                                            @endif
                                        </td>
                                        @endforeach
                                    @endif
                                    <td> {{!empty($taeTotal) ? Custom::numberFormat($taeTotal) : ''}}</td>
                                    @if($sl == '1')
                                    <td rowspan="{{ count($result['course'])*2 }}">{{Custom::numberFormat($grandTotal)}}</td>
                                    <td rowspan="{{ count($result['course'])*2 }}">{{Custom::numberFormat($averageMark)}}</td>
                                    <td class="text-center" rowspan="{{ count($result['course'])*2 }}">{{$result['expiry_date']}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>{{trans('english.EPA')}}</td>
                                    @if(count($phaseArr))
                                        @foreach($phaseArr as $phase )
                                            <td>
                                                @if(count($phaseArr))
                                                    @if(!empty($courseWise['EPE']))
                                                        <?php $courseWise['EPE']['marks'] = isset($courseWise['EPE']['marks']) ? $courseWise['EPE']['marks'] : array(); ?>
                                                        @if(array_key_exists($phase->id, $courseWise['EPE']['marks']))
                                                            @if($courseWise['EPE']['status'][$phase->id] == 2)
                                                                <span class="tooltips text-danger text-center" title="{{trans('english.STATUS').': '.trans('english.FAILED')}}<br/>{{trans('english.OBJECTIVE').': <strong>'.$courseWise['EPE']['objective_earned_mark'][$phase->id].'</strong>'}} <br/> {{trans('english.SUBJECTIVE').': <strong>'.$courseWise['EPE']['subjective_earned_mark'][$phase->id].'</strong>'}}">{{Custom::numberFormat($courseWise['EPE']['marks'][$phase->id])}}</span>
                                                            @else
                                                                <span class="tooltips text-center" title="{{trans('english.OBJECTIVE').': <strong>'.$courseWise['EPE']['objective_earned_mark'][$phase->id].'</strong>'}} <br/> {{trans('english.SUBJECTIVE').': <strong>'.$courseWise['EPE']['subjective_earned_mark'][$phase->id].'</strong>'}}">{{empty($courseWise['EPE']['marks'][$phase->id]) ? Custom::numberFormat(0) : Custom::numberFormat($courseWise['EPE']['marks'][$phase->id])}}</span>
                                                            @endif
                                                        @endif
                                                    @else 
                                                        &nbsp;
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif
                                    <td>{{ !empty($epeTotal) ? Custom::numberFormat($epeTotal) : '' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
        <br/><br/>
        @if(!empty($signatoryInfoObjArr))
            <div class="m-grid m-grid-demo signature-section">
                <div class="m-grid-row margin-bottom-25">
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">{{trans('english.PREPARED_BY')}}:</p>
                        {{htmlspecialchars_decode(stripslashes($signatoryInfoObjArr->prepared_by))}}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">{{trans('english.COMPILED_BY')}}:</p>
                        {{$signatoryInfoObjArr->compiled_by}}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">&nbsp;</p>
                        {{$signatoryInfoObjArr->ci_signature}}
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">&nbsp;</p>
                        {{$signatoryInfoObjArr->oc_signature}}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12" style="padding-top: 20px;">
                <div class="portlet-title">
                    <h4 class="text-center uppercase">{{trans('english.CONFIDENTIAL')}}</h4>
                </div>
            </div>
        </div>
    </div>
@else
<h2 class="text-center text-danger">{{trans('english.THE_RESULT_HAVE_NOT_PUBLISHED')}}</h2>
@endif
