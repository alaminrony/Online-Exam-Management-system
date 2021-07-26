<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" class="btn btn-circle  white pull-right tooltips" data-dismiss="modal" title="@lang('label.CLICK_TO_CLOSE')" >@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center" id="exampleModalLabel">@lang('label.PREVIEW_EPE')</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>

                            <tr>
                                <td width="20%"> @lang('label.SUBJECT')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($subjectList[$prevData['subject_id']])?$subjectList[$prevData['subject_id']]:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.TITLE')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['title'])?$prevData['title']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.EXAM_TOTAL_MARK')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['total_mark'])?$prevData['total_mark']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.EXAM_DATE')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['exam_date'])?$prevData['exam_date']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.TIME')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['start_time'])?$prevData['start_time']:''}} To {{!empty($prevData['end_time'])?$prevData['end_time']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.RESULT_SUBMISSION_DEADLINE')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['submission_deadline'])?$prevData['submission_deadline']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.RESULT_PUBLISH_DATE_TIME')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['result_publish'])?$prevData['result_publish']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.NO_OF_MOCK_TEST')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['no_of_mock'])?$prevData['no_of_mock']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.DURATION')</td>
                                <td width="2%">:</td>
                                <td width=75%"> {{!empty($prevData['obj_duration_hours'])?$prevData['obj_duration_hours']:'0'}} {{trans('label.HOURS')}} {{!empty($prevData['obj_duration_minutes'])?$prevData['obj_duration_minutes']:'0'}} {{trans('label.MINUTES')}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.TOTAL_NUMBER_OF_QUESTIONS')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['obj_no_question'])?$prevData['obj_no_question']:''}}</td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.SELECT_TYPE_QUESTIONS')</td>
                                <td width="2%">&nbsp;</td>
                                <td width=75%">&nbsp;</td>
                            </tr>
                            @if(!empty($prevData['qus_type']))

                            @foreach($prevData['qus_type'] as $key=>$value)
                            <tr>
                                <td width="20%">&nbsp;</td>
                                <td width=2%" >:</td>
                                <td width=75%" >{{!empty($qusTypeList[$key])?$qusTypeList[$key]:''}}  :  {{!empty($prevData['qus_type_total'][$key])?$prevData['qus_type_total'][$key]:''}}</td>
                            </tr>
                            @endforeach
                            @endif
                             <tr>
                                <td width="20%"> @lang('label.QUESTIONNAIRE_FORMAT')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($qusFormatList[$prevData['questionnaire_format']])?$qusFormatList[$prevData['questionnaire_format']]:''}} </td>
                            </tr>
                            <tr>
                                <td width="20%"> @lang('label.QUESTION_AUTO_SELECTION')</td>
                                <td width="2%">:</td>
                                <td width=75%">{{!empty($prevData['obj_auto_selected'])? trans('label.YES'):trans('label.NO')}}</td>
                            </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="button" class="btn btn-circle green epeSubmit" id="epeSubmit"><i class="fa fa-save"></i> {{trans('label.SAVE')}}</button>               
                <button type="button" class="btn dark tooltips btn-circle" data-dismiss="modal" title="@lang('label.CLICK_TO_CLOSE')" >@lang('label.CLOSE')</button>
            </div>
        </div>
    </div>
</div>