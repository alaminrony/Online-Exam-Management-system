@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-tag"></i>@lang('label.UPDATE_QUESTION')
            </div>
            <div class="tools">

            </div>
        </div>
        <div class="portlet-body form">         
            {{ Form::model($target, array('route' => array('question.update', $target->id), 'files' => true, 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'questionUpdate')) }}
            {{ Form::hidden('filter', Helper::queryPageStr($qpArr)) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label class="col-md-2 control-label">@lang('label.SELECT_SUBJECT') :<span class="required"> *</span></label>
                            <div class="col-md-4">
                                {{Form::select('subject_id', $subjectArr,!empty($target->subject_id) ? $target->subject_id : Request::get('subject_id'), array('class' => 'form-control js-source-states', 'id' => 'subject_id'))}}
                                <span class="help-block text-danger">{{ $errors->first('subject_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">@lang('label.QUESTION_TYPE') :<span class="required"> *</span></label>
                            <div class="col-md-4">
                                {{Form::select('type_id', $typeArr, !empty($target->type_id) ? $target->type_id : Request::get('type_id'), array('class' => 'form-control js-source-states', 'id' => 'type_id'))}}
                                <span class="help-block text-danger">{{ $errors->first('type_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="title">@lang('label.QUESTION') :<span class="required"> *</span></label>
                            <div class="col-md-9">
                                {{ Form::textarea('question', Request::get('question'), array('id'=> 'question', 'class' => 'form-control', 'required' => 'true')) }}
                                <span class="help-block text-danger"> {{ $errors->first('question') }}</span>
                            </div>
                        </div>

                        <div id="multipleChoice">

                            @for($i=1; $i<=4; $i++)
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ __('label.OPTION').' '.$i }} :<span class="required"> *</span></label>
                                <div class="col-md-6">
                                    <div class="col-md-6">
                                        {{Form::text('opt_'.$i, Request::get('opt_'.$i), array('class' => 'form-control'))}}
                                        <span class="help-block text-danger">{{ $errors->first('opt_'.$i) }}</span>
                                    </div>
                                    <div class="col-md-3 mul-ques-radio-btn">
                                        <label class="mt-radio">
                                            {{Form::radio('mcq_answer',$i,$target->mcq_answer == $i ? true :false)}}
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="showCorrectAns">@lang('label.CORRECT_ANSWER')</div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <div id="ftbAnswer">
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('label.CORRECT_ANSWER') :<span class="required"> *</span></label>
                                <div class="col-md-4">
                                    {{Form::text('ftb_answer', Request::get('ftb_answer'), array('class' => 'form-control'))}}
                                    <span class="help-block text-danger">{{ $errors->first('ftb_answer') }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="matchingAnswer">
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('label.ANSWER') :<span class="required"> *</span></label>
                                <div class="col-md-4">
                                    {{Form::text('match_answer', Request::get('match_answer'), array('class' => 'form-control'))}}
                                    <span class="help-block text-danger">{{ $errors->first('match_answer') }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="trueFalseAnswer">
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('label.CORRECT_ANSWER') :<span class="required"> *</span></label>
                                <div class="col-md-4">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input type="radio" name="tf_answer" id="btnTrue" value="1" <?php
                                            if ($target->tf_answer == '1') {
                                                echo 'checked="checked"';
                                            }
                                            ?> > {{ __('label.TRUE') }}
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="tf_answer" id="btnFalse" value="0" <?php
                                            if ($target->tf_answer == '0') {
                                                echo 'checked="checked"';
                                            }
                                            ?>> {{ __('label.FALSE') }}
                                            <span></span>
                                        </label>
                                        <span class="help-block text-danger">{{ $errors->first('ftb_answer') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-2 control-label">@lang('label.CONTENT_TYPE'):<span class="required"> *</span></label>
                            <div class="col-md-4">
                                {{Form::select('content_type_id',$contentTypeList, !empty($target->content_type_id) ? $target->content_type_id : '0', array('class' => 'form-control','id'=>'contentTypeId'))}}
                                <span class="help-block text-danger">{{ $errors->first('content_type_id') }}</span>
                                <span class="inline help-block text-danger">{{ $errors->first('image') }}</span>
                                <span class="inline help-block text-danger">{{ $errors->first('audio') }}</span>
                                <span class="inline help-block text-danger">{{ $errors->first('video') }}</span>
                                <span class="inline help-block text-danger">{{ $errors->first('pdf') }}</span>
                            </div>
                        </div>


                        <div class="form-group" id="image" style="display:none">
                            <label class="col-md-2 control-label">@lang('label.UPLOAD_IMAGE') : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100%; height: 150px;">
                                        <img src="{{URL::to('/')}}/public/img/no-image.png" alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new">@lang('label.BROWSE_IMAGE')</span>
                                            <span class="fileinput-exists"> Change </span>
                                            {{Form::file('image', array('id' => 'fileImage','accept'=>'image/*'))}}
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.QUESTION_IMAGE_DESCRIPTION')
                            </div>
                        </div>

                        <div class="form-group" id="audio" style="display:none">
                            <label class="col-md-2 control-label">@lang('label.UPLOAD_AUDIO_FILE') : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new">@lang('label.BROWSE_AUDIO')</span>
                                            <span class="fileinput-exists"> Change </span>
                                            {{Form::file('audio', array('id' => 'fileAudio','accept'=>'.mp3'))}}
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="video" style="display:none">
                            <label class="col-md-2 control-label">@lang('label.UPLOAD_VIDEO_FILE') : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new">@lang('label.BROWSE_VIDEO')</span>
                                            <span class="fileinput-exists"> Change </span>
                                            {{Form::file('video', array('id' => 'fileVideo','accept'=>'.mp4'))}}
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="pdf" style="display:none">
                            <label class="col-md-2 control-label">@lang('label.UPLOAD_PDF_FILE') : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new">@lang('label.BROWSE_PDF')</span>
                                            <span class="fileinput-exists"> Change </span>
                                            {{Form::file('pdf', array('id' => 'filePdf','accept'=>'application/pdf'))}}
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note">@lang('label.NOTE') : </label>
                            <div class="col-md-9">
                                {{ Form::textarea('note', Request::get('note'), array('id'=> 'note', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('note') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ __('label.STATUS') }} : </label>
                            <div class="col-md-4">
                                {{Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), Request::get('status'), array('class' => 'form-control js-source-states-hidden-search'))}}
                                <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green">@lang('label.SUBMIT')</button>
                        <a href="{{URL::to('question')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">@lang('label.CANCEL')</button> 
                        </a>
                    </div>
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
</div>
<script type="text/javascript">

   
    $(function () {

        @if (empty(old('type_id')))
                @if ($target->type_id != '1')
                $("#multipleChoice").hide();
                @endif
                @if ($target->type_id != '3')
                $("#ftbAnswer").hide();
                @endif
                @if ($target->type_id == '4')
                $("#appearanceId").hide();
                @endif
                @if ($target->type_id != '5')
                $("#trueFalseAnswer").hide();
                @endif
                @if ($target->type_id != '6')
                $("#matchingAnswer").hide();
                @endif
                @if (Request::get('type_id') == '0')
                $("#multipleChoice").hide("slow");
                $("#ftbAnswer").hide("slow");
                $("#trueFalseAnswer").hide("slow");
                $("#matchingAnswer").hide("slow");
                @endif
        @else
                @if (old('type_id') != '1')
                $("#multipleChoice").hide();
                @endif
                @if (old('type_id') != '3')
                $("#ftbAnswer").hide();
                @endif
                 @if (old('type_id') == '4')
                $("#appearanceId").hide();
                @endif
                @if (old('type_id') != '5')
                $("#trueFalseAnswer").hide();
                @endif
                @if (old('type_id') != '6')
                $("#matchingAnswer").hide();
                @endif
                @if (old('type_id') == '0')
                $("#multipleChoice").hide("slow");
        $("#ftbAnswer").hide("slow");
        $("#trueFalseAnswer").hide("slow");
        $("#matchingAnswer").hide("slow");
                @endif
                @endif
                //target

        $(document).on('change', '#type_id', function () {
            var type_id = $("#type_id").val();

            if (type_id == '1') {
                  $("#appearanceId").show("slow");    
                $("#multipleChoice").show("slow");
                $("#ftbAnswer").hide("slow");
                $("#trueFalseAnswer").hide("slow");
                $("#matchingAnswer").hide("slow");
            } else if (type_id == '3') {
                  $("#appearanceId").show("slow");    
                $("#multipleChoice").hide("slow");
                $("#ftbAnswer").show("slow");
                $("#trueFalseAnswer").hide("slow");
                $("#matchingAnswer").hide("slow");
            } else if (type_id == '5') {
                  $("#appearanceId").show("slow");    
                $("#multipleChoice").hide("slow");
                $("#ftbAnswer").hide("slow");
                $("#trueFalseAnswer").show("slow");
                $("#matchingAnswer").hide("slow");
            } else if (type_id == '4') {
                  $("#appearanceId").hide("slow");    
                $("#multipleChoice").hide("slow");
                $("#ftbAnswer").hide("slow");
                $("#trueFalseAnswer").hide("slow");
                $("#matchingAnswer").hide("slow");
            } else if (type_id == '6') {
                  $("#appearanceId").show("slow");    
                $("#multipleChoice").hide("slow");
                $("#ftbAnswer").hide("slow");
                $("#trueFalseAnswer").hide("slow");
                $("#matchingAnswer").show("slow");
            } else if (type_id == '0') {
                $("#multipleChoice").hide("slow");
                $("#ftbAnswer").hide("slow");
                $("#trueFalseAnswer").hide("slow");
                $("#matchingAnswer").hide("slow");
            }

        });
    });

</script>

<script>
    $(document).ready(function () {
        $(document).on('change', '#contentTypeId', function () {
            var contentType = $("#contentTypeId").val();
            $('#image').hide();
            $('#audio').hide();
            $('#video').hide();
            $('#pdf').hide();

            if (contentType == '1') {
                $('#image').show();
            }
            if (contentType == '2') {
                $('#audio').show();
            }
            if (contentType == '3') {
                $('#video').show();
            }
            if (contentType == '4') {
                $('#pdf').show();
            }
        });

<?php if ($target->content_type_id == '1') { ?>
            $('#image').show();
<?php } ?>

<?php if ($target->content_type_id == '2') { ?>
            $('#audio').show();
<?php } ?>

<?php if ($target->content_type_id == '3') { ?>
            $('#video').show();
<?php } ?>
<?php if ($target->content_type_id == '4') { ?>
            $('#pdf').show();
<?php } ?>
    });
</script>

@stop

