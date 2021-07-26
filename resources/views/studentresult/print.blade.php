<html>
    <head>
        <title>{{trans('english.CSTI_FULL')}}</title>
        <link rel="shortcut icon" type="image/icon" href="{{URL::to('/')}}/public/img/favicon.ico"/>
        {{ HTML::style('public/css/print.css'); }}
    </head>
    <body>
        @if(!$targetArr->isEmpty())
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
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="margin-left: 10px;">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>{{trans('english.SL_NO')}}</th>
                        <th class="text-center">{{trans('english.EXAM')}}</th>
                        <th class="text-center">{{trans('english.OBJECTIVE')}}</th>
                        <th class="text-center">{{trans('english.SUBJECTIVE')}}</th>
                        <th class="text-center">{{trans('english.ACHIEVED_MARK')}}</th>
                        <th class="text-center">{{trans('english.ACHIEVED_MARK'). '(%)'}}</th>
                        <th class="text-center">{{trans('english.RESULT')}} {{trans('english.STATUS')}}</th>
                    </tr>

                    <?php
                    $sl = 0;
                    ?>
                    @foreach($targetArr as $result)
                    <tr>
                        <td >{{++$sl}}</td>
                        <td class="text-center">{{ $result->epe_title}}</td>
                        <td class="text-center">{{ ($result->objective_earned_mark == null) ? trans('english.BLANK') :  $result->objective_earned_mark}}</td>
                        <td class="text-center">{{ ($result->subjective_earned_mark == null) ? trans('english.BLANK') :  $result->subjective_earned_mark}}</td>
                        <td class="text-center">{{ Custom::numberformat($result->objective_earned_mark + $result->subjective_earned_mark)}}</td>
                        <td class="text-center">{{ Custom::numberformat(($result->total_mark * ($result->objective_earned_mark + $result->subjective_earned_mark))/100).'%' }}</td>
                        <td class="text-center">
                            <?php
                            $totalMark = $result->objective_earned_mark + $result->subjective_earned_mark;
                            ?>
                            @if($result->ds_status != '0')
                            @if($totalMark >= $result->pass_mark)
                            <span class="label label-success">{{trans('english.PASSED')}}</span>
                            @elseif($totalMark < $result->pass_mark)
                            <span class="label label-danger">{{trans('english.FAILED')}}</span>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <br/><br/>
        @if(!empty($signatoryInfoObjArr))
        <table class="table2" border="0">
            <tr>
                <td>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">{{trans('english.PREPARED_BY')}}:</p>
                        {{htmlspecialchars_decode(stripslashes($signatoryInfoObjArr->prepared_by))}}
                    </div>
                </td>
                <td>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">{{trans('english.COMPILED_BY')}}:</p>
                        {{$signatoryInfoObjArr->compiled_by}}
                    </div>
                </td>
                <td>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">&nbsp;</p>
                        {{$signatoryInfoObjArr->ci_signature}}
                    </div>
                </td>
                <td>
                    <div class="col-lg-3 col-md-6">
                        <p class="margin-bottom-25">&nbsp;</p>
                        {{$signatoryInfoObjArr->oc_signature}}
                    </div>
                </td>
            </tr>
        </table>
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
@endif

<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        window.print();
        //window.close();
    });
</script>
</body>
</html>