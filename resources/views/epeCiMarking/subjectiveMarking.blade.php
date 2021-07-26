@extends('layouts.epeExam')
@section('data_count')

<div class="page-content">
    <div class="portlet box">
        <div class="portlet-body form">
            {{ Form::open(array('role' => 'form', 'url' => 'ciSaveSubjectiveMarking/'. $epeMarkInfo->id , 'class' => 'form-horizontal', 'id'=>'answerScript')) }}

            {{ Form::hidden('epe_id', $epeInfo->id, array('id'=>'epe_id')) }}
            {{ Form::hidden('epe_mark_id', $epeMarkInfo->id, array('id'=>'epe_mark_id')) }}

            <div class="form-body text-center navbar-fixed-top" style="padding: 0px; background: #fff;">

                <div class="row">
                    <div class="col-md-12"><h2>{{ $epeInfo->title }}</h2></div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-1 text-left">{{ trans('english.START_TIME').' : '.$epeMarkInfo->objective_start_time }}</div>
                    <div class="col-md-2 text-left">{{ trans('english.END_TIME').' : '.$epeMarkInfo->objective_end_time }}</div>
                    <div class="col-md-2 col-md-offset-1 text-right">{{ trans('english.DURATION').' : '.$epeInfo->obj_duration_hours.':'.$epeInfo->obj_duration_minutes.' '. trans('english.MINUTE') }}</div>
                </div>

                <div class="row">                            
                    <div class="col-md-4 col-md-offset-1 text-left">{{ trans('english.SUBJECT').' : '.$epeInfo->Subject->title }}<br />
                    </div>
                    <div class="col-md-2 text-left">{{ trans('english.TOTAL_MARK').' : '.$epeMarkInfo->total_mark }}</div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-12 text-center">
                        {{trans('english.SUBMISSION_DATELINE')}} : <strong>{{$epeInfo->submission_deadline}}</strong>&nbsp;|
                        {{trans('english.RESULT_PUBLISHED_DATE')}} : <strong>{{$epeInfo->result_publish}}</strong>
                    </div>
                </div>

                <hr />
            </div>

            <div class="form-body" id="questionBody" style="margin-top: 170px">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <span class="btn btn-success btn-sm yellow-soft"> 
                            <i class='fa fa-file-text'></i> {{trans('english.SUBJECTIVE')}} : {{$epeSubSum->total_mark}}
                        </span>
                        &nbsp;&nbsp;&nbsp;
                        <a class="tooltips question_answer_sheet" data-toggle="modal" data-target="#question_answer_sheet" data-mark-id="{{$epeMarkInfo->id}}" href="#question_answer_sheet" id="questionAnswerSheet{{$epeMarkInfo->id}}" title="{{ trans('english.CLICK_TO_VIEW_QUESTION_AND_ANSWER_SHEET') }}" data-container="body" data-trigger="hover" data-placement="top">
                            <span class="btn btn-success btn-sm green "> 
                                <i class='fa fa-list-ul'></i> {{trans('english.OBJECTIVE')}} : {{$epeObjSum->total_mark}}
                            </span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">

                        @if(!empty($qusSubArr))
                        <?php $j = 1; ?>
                        @foreach($qusSubArr as $question)

                        @if(!empty($answerArr[$question['question_id']]))
                        <?php
                        $remarksRequired = $remarksRequiredAsterisk = '';
                        if ($answerArr[$question['question_id']]->ds_mark != $answerArr[$question['question_id']]->ci_mark) {
                            $remarksRequired = 'required';
                            $remarksRequiredAsterisk = '*';
                        }

                        $readonly = 'readonly';

                        if (!empty($answerArr[$question['question_id']]->ds_mark)) {
                            $readonly = '';
                        }
                        ?>

                        <div class="row">
                            <div class="col-md-11">

                                <div class="subjective-question">
                                    {{ $j.') '.$question['question'] }}
                                    @if(!empty($question['note']))
                                    <span class="tooltips question-node" title="{{$question['note']}}" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-md-1">
                                <div class="subjective-question">{{ $question['mark'] }}</div>
                            </div>
                        </div>

                        @if(!empty($question['image']))
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a class="btn tooltips" title="{{ trans('english.CLICK_TO_EXPAND_IMAGE') }}" href="{{URL::to('/')}}/question/getImage/{{ $question['image'] }}" data-target="#image-loader" data-toggle="modal">
                                    <img class="question-script-image-first-tab" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question['image'] }}" alt="{{ $question['image'] }}"> 
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="note note-info">
                                    <h4 class="block">{{ trans('english.ANSWER') }} : </h4>
                                    {{ $answerArr[$question['question_id']]->submitted_answer }} 
                                </div>
                            </div>
                        </div>

                        @if($epeInfo->ci_status == 1)
                        <div class="row">
                            <div class="col-md-12 font-green-seagreen">{{ trans('english.DS_MARK').' : '.$answerArr[$question['question_id']]->ds_mark }}</div>
                        </div>
                        @endif

                        {{ Form::hidden('ds_org_mark['.$answerArr[$question['question_id']]->id.']', $answerArr[$question['question_id']]->ds_mark, array('id'  => 'ds_org_mark_'.$answerArr[$question['question_id']]->id)) }}
                        <div class="form-group">
                            @if($epeInfo->ci_status == 1)
                            <div class="col-md-12 font-red-pink">{{ trans('english.CI_MARK').' : '.$answerArr[$question['question_id']]->ci_mark }}</div>
                            @else
                            @if($epeMarkInfo->ds_status == '2')
                            @if($epeMarkInfo->unlock_request == '0')
                            <label class="col-md-2 control-label">{{ trans('english.CI_MARK') }} : </label>
                            <div class="col-md-2">
                                <div class="input-group">                                                       
                                    {{ Form::text('ci_mark['.$answerArr[$question['question_id']]->id.']', !empty($answerArr[$question['question_id']]->ci_mark)?$answerArr[$question['question_id']]->ci_mark:$answerArr[$question['question_id']]->ds_mark, array( 'data-max-length'  => $ciMarkLimit[$question['question_id']]['max'], 'data-min-length'  => $ciMarkLimit[$question['question_id']]['min'],  'class' => 'form-control interger-decimal-only ci-mark tooltips','title'=>'Min:'.$ciMarkLimit[$question['question_id']]['min'].'<br />Max:'.$ciMarkLimit[$question['question_id']]['max'],'data-html'=>true,'data-question' => $j, 'id' => 'ci_mark_'.$answerArr[$question['question_id']]->id)) }}
                                </div>
                                <span class="required">{{'Min:'.$ciMarkLimit[$question['question_id']]['min'].' Max:'.$ciMarkLimit[$question['question_id']]['max']}}</span>
                            </div>
                            <div class="col-md-4 control-label" style="text-align: left; ">( {{ trans('english.DS_MARK').' : '.$answerArr[$question['question_id']]->ds_mark }} )</div>
                            @endif
                            @endif
                            @endif
                        </div>
                        <div class="form-group">
                            @if($epeInfo->ci_status == 1)
                            <div class="col-md-2 font-green-seagreen">{{ trans('english.DS_REMARKS').' : '.$answerArr[$question['question_id']]->ds_remarks }}</div>

                            <div class="col-md-12 font-red-pink">{{ trans('english.CI_REMARKS').' : '.$answerArr[$question['question_id']]->ci_remarks }}</div>
                            @else
                            @if($epeMarkInfo->ds_status == '2')
                            @if($epeMarkInfo->unlock_request == '0')
                            <label class="col-md-2 control-label">{{ trans('english.CI_REMARKS') }} : <span class="required" id="{{'remarks_identifier_'.$answerArr[$question['question_id']]->id}}"> {{ $remarksRequiredAsterisk }}</span></label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    {{ Form::textarea('ci_remarks['.$answerArr[$question['question_id']]->id.']', $answerArr[$question['question_id']]->ci_remarks , array('rows' => '2', 'class' => 'form-control', 'id' => 'ci_remarks_'.$answerArr[$question['question_id']]->id, 'data-set' => 1, 'data-question' => $j, $remarksRequired )) }}
                                </div>
                            </div>
                            @endif
                            @endif
                            @endif
                        </div>
                        <div class="form-group">
                            @if($epeInfo->ci_status == 0)
                            <div class="col-md-2 control-label">{{ trans('english.DS_REMARKS').' : ' }}</div>
                            <div class="col-md-10 control-label" style="text-align: left;">{{ $answerArr[$question['question_id']]->ds_remarks }}</div>
                            @endif
                        </div>
                        <?php $j++; ?>
                        @endif
                        @endforeach
                        @else
                        <div class="row">
                            <div class="col-md-12">
                                {{ trans('english.NO_QUESTION_ASSIGNED_FOR_THIS_QUESTION_SET') }}
                            </div>
                        </div>
                        @endif
                    </div>                                    
                </div>
                <div class="row">
                    <div class="form-actions">
                        <div class="col-md-12 text-center">
                            <div class="col-md-12 text-center">
                                @if($epeInfo->ci_status == 1)
                                <div class="text-danger margin-bottom-10">{{ trans('english.THIS_ANSWER_SCRIPT_HAS_BEEN_ASSESSED_AND_LOCKED') }}</div>
                                @else
                                @if($epeMarkInfo->ds_status == '2')
                                @if($epeMarkInfo->unlock_request == '0')
                                <button type="submit" class="btn btn-primary" name="submit" value="save" id="save"><i class="fa fa-save"></i> {{trans('english.SAVE_ANSWERS_SHEET')}}</button>&nbsp;&nbsp;
                                @endif
                                @endif
                                @endif
                                <a href="{{URL::to('epecimarking?epe_id='.$epeInfo->id)}}" class="btn btn-default"><i class="fa fa-close"></i> {{trans('english.CANCEL')}}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
<!-- END FORM-->

<div class="modal fade" id="image-loader" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ trans('english.PRESS_ESC_TO_CLOSE') }}</h4>
            </div>
            <div class="modal-body text-center"> <img src="{{URL::to('/public/assets/global/img/loading-spinner-grey.gif')}}" alt="" class="loading"> </div>
            <div class="modal-footer">
                <button type="button" class="btn green" data-dismiss="modal">{{ trans('english.CLOSE')}}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="question_answer_sheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div id="display_question_answer_sheet"></div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        //Tooltip, activated by hover event
        $(".tooltips").tooltip({html: true});
        //They can be chained like the example above (when using the same selector).

    });

    $(document).ready(function () {

        $('.ci-mark').keyup(function (e) {
            var proceedFlag = 1;
            var max = parseFloat($(this).attr('data-max-length'));
            var min = parseFloat($(this).attr('data-min-length'));
            var number = parseFloat($(this).val());
            var qNo = $(this).attr('data-question');
            if (number > max) {
                swal('Maximum mark can be ' + max + ' for question number ' + qNo);
                proceedFlag = 0;
                $(this).val('')
            }
        });
        $('#save').click(function () {
            var proceedFlag = 1;
            $('.ci-mark').each(function () {
                if (this.value != '') {
                    var max = parseFloat($(this).attr('data-max-length'));
                    var min = parseFloat($(this).attr('data-min-length'));
                    var number = parseFloat($(this).val());
                    var qNo = $(this).attr('data-question');
                    if (number > max) {
                        swal('Maximum mark can be ' + max + ' for question number ' + qNo);
                        proceedFlag = 0;
                    }

                    if (number < min) {
                        swal('Minimum mark can be ' + min + ' for question number ' + qNo);
                        proceedFlag = 0;
                    }
                }
            });
            if (proceedFlag == 0) {
                return false;
            }

            $('textarea[required]').each(function () {
                if ($(this).val() == '') {
                    swal("Please, insert remarks for quesion number " + $(this).data('question'));
                }
            });
        });
        $('#lock').click(function () {
            var c = confirm('Are you sure you want to lock this asnwer script?');
            return c;
        });
        //This function use for view question ans answer sheet
        $(document).on('click', '.question_answer_sheet', function (e) {
            e.preventDefault();
            var epeMarkId = $(this).data('mark-id'); // get id of clicked row
            $('#display_question_answer_sheet').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('epedsmarking/questionanswersheet/') }}",
                type: "get",
                data: {
                    epe_mark_id: epeMarkId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#display_question_answer_sheet').html(''); // blank before load.
                    $('#display_question_answer_sheet').html(response.html); // load here
                    $(".tooltips").tooltip({html: true});
                    //Ending ajax loader
                    App.unblockUI();
                },
                beforeSend: function () {
                    //For ajax loader
                    App.blockUI({
                        boxed: true
                    });
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                    //Ending ajax loader
                    App.unblockUI();
                }
            });
        });
    });
</script>

@stop

