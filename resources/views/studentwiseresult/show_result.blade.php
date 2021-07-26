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

@if(!$targetArr->isEmpty())

<div class="col-md-4 text-right">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a target="_blank" href="{{ URL::to('/studentwiseresult?type=print&student_id='.Request::get('student_id')).'&epe_id='.Request::get('epe_id') }}"  id="print" class="btn blue" title="Print Result">
        <i class="fa fa-print"></i> {{ __('label.PRINT') }}
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a target="_blank" href="{{ URL::to('/studentwiseresult?type=pdf&student_id='.Request::get('student_id')).'&epe_id='.Request::get('epe_id') }}"  id="pdf" class="btn green" title="Download Result">
        <i class="fa fa-file-pdf-o"></i> {{ __('label.DOWNLOAD') }}
    </a>
</div>    
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">    
                    <tbody>
                        <tr>
                            <td rowspan="4">
                                <div class="profile-userpic text-center">
                                    @if(!empty($student->photo))
                                    <img  src="{{URL::to('/')}}/public/uploads/user/{{$student->photo}}" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:auto;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                    @else
                                    <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:auto;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                    @endif
                                </div>
                            </td>
                            <td colspan="2"><b>{{ $student->short_name.' '.$student->first_name.' '.$student->last_name }}</b></td>

                        </tr>
                        <tr>
                            <td>{{ __('label.REGISTRATION_NO').' : '.$student->registration_no }}</td>
                            <td>{{ __('label.OFFICIAL_NAME').' : '.$student->official_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('label.SERVICE_NO').' : '.$student->service_no }}</td>
                            <td>{{ __('label.BRANCH').' : '.$student->branch_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('label.ISS_NO').' : '.$student->iss_no }}</td>
                            <td>{{ __('label.REGISTRATION_EXPIRY_DATE').' : '.$student->maximum_tenure }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th>{{__('label.SL_NO')}}</th>
                        <th class="text-center">{{__('label.OBJECTIVE')}}</th>
                        <th class="text-center">{{__('label.SUBJECTIVE')}}</th>
                        <th class="text-center">{{__('label.ACHIEVED_MARK')}}</th>
                        <th class="text-center">{{__('label.ACHIEVED_MARK'). '(%)'}}</th>
                        <th class="text-center">{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
                    </tr>

                    <?php
                    $sl = 0;
                    ?>
                    @foreach($targetArr as $result)
                    <tr>
                        <td >{{++$sl}}</td>
                        <td class="text-center">{{ ($result->objective_earned_mark == null) ? __('label.BLANK') :  $result->objective_earned_mark}}</td>
                        <td class="text-center">{{ ($result->subjective_earned_mark == null) ? __('label.BLANK') :  $result->subjective_earned_mark}}</td>
                        <td class="text-center">{{ Helper::numberformat($result->objective_earned_mark + $result->subjective_earned_mark)}}</td>
                        <td class="text-center">{{ Helper::numberformat(($result->total_mark * ($result->objective_earned_mark + $result->subjective_earned_mark))/100).'%' }}</td>
                        <td class="text-center">
                            <?php
                            $totalMark = $result->objective_earned_mark + $result->subjective_earned_mark;
                            ?>
                            @if($result->ds_status != '0')
                            @if($totalMark >= $result->pass_mark)
                            <span class="label label-success">{{__('label.PASSED')}}</span>
                            @elseif($totalMark < $result->pass_mark)
                            <span class="label label-danger">{{__('label.FAILED')}}</span>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@else
<h2 class="text-center text-danger">{{__('label.THERE_IS_NO_RESULT_AVAILABLE_FOR_THIS_STUDENT')}}</h2>
@endif

