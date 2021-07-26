<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-tag"></i><?php echo app('translator')->get('label.CREATE_NEW_QUESTION'); ?> 
            </div>
            <div class="tools">

            </div>
        </div>
        <div class="portlet-body form">
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'question', 'files' => true, 'class' => 'form-horizontal', 'id'=>'questionCreate'))); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.SELECT_SUBJECT'); ?>  :<span class="required"> *</span></label>
                            <div class="col-md-4">
                                <?php echo e(Form::select('subject_id', $subjectArr,!empty($subjectId)?subjectId : Request::get('subject_id'), array('class' => 'form-control js-source-states', 'id' => 'subject_id'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('subject_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.QUESTION_TYPE'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-4">
                                <?php echo e(Form::select('type_id', $typeArr, Request::get('type_id'), array('class' => 'form-control js-source-states', 'id' => 'type_id'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('type_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="title"><?php echo app('translator')->get('label.QUESTION'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-9">
                                <?php echo e(Form::textarea('question', Request::get('question'), array('id'=> 'question', 'class' => 'form-control', 'required' => 'true'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('question')); ?></span>
                            </div>
                        </div>

                        <div id="multipleChoice">
                            <?php for($i=1; $i<=4; $i++): ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label"><?php echo e(__('label.OPTION').' '.$i); ?> :<span class="required"> *</span></label>
                                <div class="col-md-6">
                                    <div class="col-md-6">
                                        <?php echo e(Form::text('opt_'.$i, Request::get('opt_'.$i), array('class' => 'form-control'))); ?>

                                        <span class="help-block text-danger"><?php echo e($errors->first('opt_'.$i)); ?></span>
                                    </div>
                                    <div class="col-md-3 mul-ques-radio-btn">
                                        <label class="mt-radio">
                                            <?php echo e(Form::radio('mcq_answer',$i,false,['class'=>'showCrtAns','data-id'=>$i])); ?>

                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="showCorrectAns"><?php echo app('translator')->get('label.CORRECT_ANSWER'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>

                        <div id="ftbAnswer">
                            <div class="form-group">
                                <label class="col-md-2 control-label"><?php echo app('translator')->get('label.CORRECT_ANSWER'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-4">
                                    <?php echo e(Form::text('ftb_answer', Request::get('ftb_answer'), array('class' => 'form-control'))); ?>

                                    <span class="help-block text-danger"><?php echo e($errors->first('ftb_answer')); ?></span>
                                </div>
                            </div>
                        </div>

                        <div id="trueFalseAnswer">
                            <div class="form-group">
                                <label class="col-md-2 control-label"><?php echo app('translator')->get('label.CORRECT_ANSWER'); ?>:<span class="required"> *</span></label>
                                <div class="col-md-4">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input type="radio" name="tf_answer" id="btnTrue" value="1"> <?php echo app('translator')->get('label.TRUE'); ?>
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="tf_answer" id="btnFalse" value="0"><?php echo app('translator')->get('label.FALSE'); ?>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="matchingAnswer">
                            <div class="form-group">
                                <label class="col-md-2 control-label"><?php echo app('translator')->get('label.ANSWER'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-4">
                                    <?php echo e(Form::text('match_answer', Request::get('match_answer'), array('class' => 'form-control'))); ?>

                                    <span class="help-block text-danger"><?php echo e($errors->first('match_answer')); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.CONTENT_TYPE'); ?>:<span class="required"> *</span></label>
                            <div class="col-md-4">
                                <?php echo e(Form::select('content_type_id',$contentTypeList, !empty(Request::get('content_type_id')) ? Request::get('content_type_id') : '0', array('class' => 'form-control','id'=>'contentTypeId'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('content_type_id')); ?></span>
                                <span class="inline help-block text-danger"><?php echo e($errors->first('image')); ?></span>
                                <span class="inline help-block text-danger"><?php echo e($errors->first('audio')); ?></span>
                                <span class="inline help-block text-danger"><?php echo e($errors->first('video')); ?></span>
                                <span class="inline help-block text-danger"><?php echo e($errors->first('pdf')); ?></span>
                            </div>
                        </div>


                        <div class="form-group" id="image" style="display:none">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.UPLOAD_IMAGE'); ?> : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100%; height: 150px;">
                                        <img src="<?php echo e(URL::to('/')); ?>/public/img/no-image.png" alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"><?php echo app('translator')->get('label.BROWSE_IMAGE'); ?></span>
                                            <span class="fileinput-exists"> Change </span>
                                            <?php echo e(Form::file('image', array('id' => 'fileImage','accept'=>'image/*'))); ?>

                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span> <?php echo app('translator')->get('label.QUESTION_IMAGE_DESCRIPTION'); ?>
                            </div>
                        </div>

                        <div class="form-group" id="audio" style="display:none">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.UPLOAD_AUDIO_FILE'); ?> : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"><?php echo app('translator')->get('label.BROWSE_AUDIO'); ?></span>
                                            <span class="fileinput-exists"> Change </span>
                                            <?php echo e(Form::file('audio', array('id' => 'fileAudio','accept'=>'.mp3'))); ?>

                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="video" style="display:none">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.UPLOAD_VIDEO_FILE'); ?> : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"><?php echo app('translator')->get('label.BROWSE_VIDEO'); ?></span>
                                            <span class="fileinput-exists"> Change </span>
                                            <?php echo e(Form::file('video', array('id' => 'fileVideo','accept'=>'.mp4'))); ?>

                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="pdf" style="display:none">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.UPLOAD_PDF_FILE'); ?> : </label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"><?php echo app('translator')->get('label.BROWSE_PDF'); ?></span>
                                            <span class="fileinput-exists"> Change </span>
                                            <?php echo e(Form::file('pdf', array('id' => 'filePdf','accept'=>'application/pdf'))); ?>

                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note"><?php echo app('translator')->get('label.NOTE'); ?> : </label>
                            <div class="col-md-9">
                                <?php echo e(Form::textarea('note', Request::get('note'), array('id'=> 'note', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('note')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo app('translator')->get('label.STATUS'); ?> : </label>
                            <div class="col-md-4">
                                <?php echo e(Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), Request::get('status'), array('class' => 'form-control js-source-states-hidden-search'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('status')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green"><?php echo app('translator')->get('label.SUBMIT'); ?></button>
                        <a href="<?php echo e(URL::to('question')); ?>">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline"><?php echo app('translator')->get('label.CANCEL'); ?></button> 
                        </a>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>


        </div>
    </div>
</div>
<script type="text/javascript">

    $(function () {
    <?php if(old('type_id') != '1'): ?>
            $("#multipleChoice").hide();
            <?php endif; ?>

            <?php if(old('type_id') != '3'): ?>
            $("#ftbAnswer").hide();
            <?php endif; ?>

            <?php if(old('type_id') == '4'): ?>
            $("#appearanceId").hide();
            <?php endif; ?>
            <?php if(old('type_id') != '5'): ?>
            $("#trueFalseAnswer").hide();
            <?php endif; ?>

            <?php if(old('type_id') != '6'): ?>
            $("#matchingAnswer").hide();
            <?php endif; ?>


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
            var redirect_type_id = "<?php echo Request::get('type_id') ?>";
            if (redirect_type_id == '1'){
    $("#appearanceId").show("slow");
            $("#multipleChoice").show("slow");
            $("#ftbAnswer").hide("slow");
            $("#trueFalseAnswer").hide("slow");
            $("#matchingAnswer").hide("slow");
    }
    else if (redirect_type_id == '3'){
    $("#appearanceId").show("slow");
            $("#multipleChoice").hide("slow");
            $("#ftbAnswer").show("slow");
            $("#trueFalseAnswer").hide("slow");
            $("#matchingAnswer").hide("slow");
    }
    else if (redirect_type_id == '5'){
    $("#appearanceId").show("slow");
            $("#multipleChoice").hide("slow");
            $("#ftbAnswer").hide("slow");
            $("#trueFalseAnswer").show("slow");
            $("#matchingAnswer").hide("slow");
    }
    else if (redirect_type_id == '4'){
    $("#appearanceId").hide("slow");
            $("#multipleChoice").hide("slow");
            $("#ftbAnswer").hide("slow");
            $("#trueFalseAnswer").hide("slow");
            $("#matchingAnswer").hide("slow");
    }
    else if (redirect_type_id == '6'){
    $("#multipleChoice").hide("slow");
            $("#ftbAnswer").hide("slow");
            $("#trueFalseAnswer").hide("slow");
            $("#matchingAnswer").show("slow");
    }
    else if (redirect_type_id == '0'){
    $("#multipleChoice").hide("slow");
            $("#ftbAnswer").hide("slow");
            $("#trueFalseAnswer").hide("slow");
            $("#matchingAnswer").hide("slow");
    }
    
    });
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/question/create.blade.php ENDPATH**/ ?>