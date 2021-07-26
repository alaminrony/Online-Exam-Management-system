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
<div class="row">
    <div class="col-md-12 text-right">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a target="_blank" href="{{ URL::to('/mocktestresult?type=print&epe_id='.Request::get('epe_id')).'&student_id='.Request::get('student_id') }}"  id="print" class="btn blue" title="Print Result">
            <i class="fa fa-print"></i> {{ __('label.PRINT') }}
        </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a target="_blank" href="{{ URL::to('/mocktestresult?type=pdf&epe_id='.Request::get('epe_id')).'&student_id='.Request::get('student_id') }}"  id="pdf" class="btn green" title="Download Result">
            <i class="fa fa-file-pdff-o"></i> {{ __('label.DOWNLOAD') }}
        </a>
    </div>    
</div>

<h5>
    <strong>{{__('label.EPE')}}: </strong>{{$epeInfo->title}} |
    <strong>{{__('label.SUBJECT')}}: </strong> {{$epeInfo->subject->title}}
</h5>

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
        <div class="col-md-12" style="margin-left: 10px;">
            <table class="table table-striped table-bordered">

                <tr>
                    <th>{{__('label.SL_NO')}}</th>
                    <th>{{__('label.TITLE')}}</th>
                    <th>{{__('label.SUBMITTED_DATE')}}</th>
                    <th>{{__('label.NO_OF_QUESTION')}}</th>
                    <th>{{__('label.CORRECT_ANSWER')}}</th>
                    <th>{{__('label.ATTEMPT')}}</th>
                    <th>{{__('label.ACHIEVED_MARK')}}</th>
                    <th>{{__('label.STATUS')}}</th>
                    <th>{{__('label.ACTION')}}</th>
                </tr>
                <?php $sl = 0; ?>
                @foreach($targetArr as $value)
                <tr>
                    <td>{{++$sl}}</td>
                    <td>{{$value->title}}</td>
                    <td>{{$value->submission_time}}</td>
                    <td>{{$value->no_of_question}}</td>
                    <td>{{$value->no_correct_answer}}</td>
                    <td>{{$value->attempt}}</td>
                    <td>{{$value->converted_mark}}</td>

                    <td>
                        @if($value->pass == '1')
                        <span class="label label-success"> {{__('label.PASSED')}} </span>
                        @else
                        <span class="label label-danger"> {{__('label.FAILED')}} </span>
                        @endif
                    </td>

                    <td>
                        <a class="tooltips question_answer_sheet" data-toggle="modal" data-target="#question_answer_sheet" data-id="{{$value->id}}" href="#question_answer_sheet" id="questionAnswerSheet{{$value->id}}" title="{{ __('label.VIEW_QUESTION_AND_ANSWER_SHEET') }}" data-container="body" data-trigger="hover" data-placement="top">
                            <span class="btn btn-success btn-sm yellow-soft "> 
                                &nbsp;<i class='fa fa-sticky-note-o'></i>&nbsp;
                            </span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <br/><br/>
</div>
@else
<h2 class="text-center text-danger">{{__('label.THERE_IS_NO_RESULT_AVAILABLE_FOR_THIS_STUDENT')}}</h2>
@endif

<div class="modal fade bs-modal-lg" id="question_answer_sheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{__('label.VIEW_QUESTION_AND_ANSWER_SHEET')}}</h4>
            </div>
            <div class="modal-body" id="display_question_answer_sheet">  </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });
</script>
