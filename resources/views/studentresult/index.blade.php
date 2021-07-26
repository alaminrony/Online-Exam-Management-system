@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>{{__('label.VIEW_MY_RESULT')}}
            </div>
            <div class="actions">
                <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
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
                    <div class="row">
                        <div class="col-md-12 text-right">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--                            <a target="_blank" href="{{ URL::to('/myresult?type=print') }}"  id="print" class="btn blue" title="Print Result">
                                <i class="fa fa-print"></i> {{ __('label.PRINT') }}
                            </a>-->
                        </div>    
                    </div>
                    <div class="table-scrollable" style="padding-bottom: 20px;">
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
                                                    <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:auto" alt="{{ $student->first_name??''}} {{$student->last_name??'' }}">
                                                    @endif
                                                </div>
                                            </td>
                                            <td colspan="2"><b>{{ $student->short_name.' '.$student->first_name.' '.$student->last_name }}</b></td>

                                        </tr>
                                        <tr>
                                            <td>{{ __('label.EMPLOYEE_ID').' : '.$student->username }}</td>
                                            <td>{{ __('label.GRADE').' : '.$student->rank_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('label.APPOINTMENT').' : '.$student->appointment_name }}</td>
                                            <td>{{ __('label.BRANCH').' : '.$student->branch_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('label.PHONE_NO').' : '.$student->phone_no }}</td>
                                            <td>{{ __('label.EMAIL').' : '.$student->email }}</td>
                                        </tr>        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-left: 10px;">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th>{{__('label.SL_NO')}}</th>
                                        <th class="text-center">{{__('label.EXAM')}}</th>
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
                                    <?php
                                    $totalPercent = ($result->total_mark * ($result->objective_earned_mark + $result->subjective_earned_mark)) / 100;
                                    ?>
                                    <tr>
                                        <td >{{++$sl}}</td>
                                        <td class="text-center">{{ $result->epe_title}}</td>
                                        <td class="text-center">{{ ($result->objective_earned_mark == null) ? __('label.BLANK') :  $result->objective_earned_mark}}</td>
                                        <td class="text-center">{{ ($result->subjective_earned_mark == null) ? __('label.BLANK') :  $result->subjective_earned_mark}}</td>
                                        <td class="text-center">{{ Helper::numberformat($result->objective_earned_mark + $result->subjective_earned_mark)}}</td>
                                        <td class="text-center">{{ Helper::numberformat($totalPercent).'%' }}</td>
                                        <td class="text-center">
                                            {{ Helper::findGrade($totalPercent)}} 
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <br/><br/>
                        @if(!empty($signatoryInfoObjArr))
                        <div class="m-grid m-grid-demo signature-section">
                            <div class="m-grid-row margin-bottom-25">
                                <div class="col-lg-3 col-md-6">
                                    <p class="margin-bottom-25">{{__('label.PREPARED_BY')}}:</p>
                                    {{htmlspecialchars_decode(stripslashes($signatoryInfoObjArr->prepared_by))}}
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p class="margin-bottom-25">{{__('label.COMPILED_BY')}}:</p>
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
                    </div>
                    @else
                    <h2 class="text-center text-danger">{{__('label.THE_RESULT_HAVE_NOT_PUBLISHED')}}</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip({html: true});
    });
</script>
@stop
