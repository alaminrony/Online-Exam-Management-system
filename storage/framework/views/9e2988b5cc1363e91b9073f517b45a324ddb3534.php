<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title"><?php echo e(__('label.VIEW_QUESTION_AND_ANSWER_SHEET')); ?></h4>
        </div>
        <div class="modal-body">
            <div class="portlet light bordered">
                <div class="portlet-body ">
                    <?php $i = 1; ?>
                    <div class="mt-element-step">
                        <?php if(!$questionArr->isEmpty()): ?>
                        <?php $__currentLoopData = $questionArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row step-no-background-thin">
                            <div class="mt-step-desc">
                                <?php if($question->type_id  != '6'): ?>
                                <div class="font-grey-cascade"><h4><?php echo e($i.'.  '.$question->question); ?></h4></div>
                                <?php endif; ?>
                            </div>
                            <?php $mark = '0.00'; ?>
                            <?php if($question->type_id  == '1'): ?>
                            <?php
                            $objRightAns = 'font-green-jungle';
                            $objFalseAns = 'font-red';
                            if ($question->correct == 1) {
                                $mark = $question->mark;
                            }
                            ?>
                            <div class="col-md-6 mt-step-col">
                                <div class="mt-step-number font-grey-cascade">a</div>
                                <?php
                                $answer1 = '';
                                if ($question->correct_answer == 1) {
                                    $answer1 = $objRightAns;
                                } else if ($question->submitted_answer == 1 && $question->correct == 0) {
                                    $answer1 = $objFalseAns;
                                }
                                ?>
                                <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer1; ?>"><?php echo e($question->opt_1); ?></span></div>
                            </div>
                            <div class="col-md-6 mt-step-col">
                                <div class="mt-step-number font-grey-cascade">b</div>
                                <?php
                                $answer2 = '';
                                if ($question->correct_answer == 2) {
                                    $answer2 = $objRightAns;
                                } else if ($question->submitted_answer == 2 && $question->correct == 0) {
                                    $answer2 = $objFalseAns;
                                }
                                ?>
                                <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer2; ?>"><?php echo e($question->opt_2); ?></span></div>
                            </div>
                            <div class="col-md-6 mt-step-col">
                                <div class="mt-step-number font-grey-cascade">c</div>
                                <?php
                                $answer3 = '';
                                if ($question->correct_answer == 3) {
                                    $answer3 = $objRightAns;
                                } else if ($question->submitted_answer == 3 && $question->correct == 0) {
                                    $answer3 = $objFalseAns;
                                }
                                ?>
                                <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer3; ?>"><?php echo e($question->opt_3); ?></span></div>
                            </div>
                            <div class="col-md-6 mt-step-col">
                                <div class="mt-step-number font-grey-cascade">d</div>
                                <?php
                                $answer4 = '';
                                $class = '';
                                if ($question->correct_answer == 4) {
                                    $answer4 = $objRightAns;
                                } else if ($question->submitted_answer == 4 && $question->correct == 0) {
                                    $answer4 = $objFalseAns;
                                }
                                ?>
                                <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer4; ?>"><?php echo e($question->opt_4); ?></span></div>
                            </div>
                            <div class="mt-step-desc">
                                <div class="font-grey-cascade"><strong>Mark : </strong><span class="<?php echo $class; ?>"><?php echo e($mark); ?></span></div>
                            </div>

                            <?php endif; ?>

                            <?php if($question->type_id  == '3'): ?>
                            <?php
                            $class = '';

                            if ($question->correct == 1) {
                                $class = 'font-green-jungle';
                                $mark = $question->mark;
                            } else {
                                $class = 'font-red font-lg';
                            }
                            ?>
                            <div class="mt-step-desc">
                                <div class="font-grey-cascade"><strong>Answer : </strong><span class="<?php echo $class; ?>"><span title="<?php echo e($question->submitted_answer); ?>" class="tooltips"><?php echo e($question->correct_answer); ?></span></span></div>
                            </div>
                            <div class="mt-step-desc">
                                <div class="font-grey-cascade"><strong>Mark : </strong><span class="<?php echo $class; ?>"><?php echo e($mark); ?></span></div>
                            </div>
                            <?php endif; ?>	

                            <?php if($question->type_id  == '5'): ?>
                            <?php
                            $trueFalseclass = '';
                            if ($question->correct == 1) {
                                $trueFalseclass = 'font-green-jungle';
                                $mark = $question->mark;
                            } else {
                                $trueFalseclass = 'font-red font-lg';
                            }
                            ?>
                            <div class="mt-step-desc">
                                <div class="font-grey-cascade"><strong>Answer : </strong><span class="<?php echo $trueFalseclass; ?>"><?php echo e((empty($question->correct_answer)) ?'False':'True'); ?></span></div>
                            </div>
                            <div class="mt-step-desc">
                                <div class="font-grey-cascade"><strong>Mark : </strong><span class="<?php echo $trueFalseclass; ?>"><?php echo e($mark); ?></span></div>
                            </div>
                            <?php endif; ?>

                        </div>

                        <?php $i++; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<style>
    .mt-element-step .step-no-background-thin .mt-step-content {
        padding-left: 60px;
        margin-top: 5px;
    }
    .mt-element-step .step-no-background-thin .mt-step-number {
        font-size: 20px;
        border-radius: 50%!important;
        float: left;
        margin: auto;
        padding: 0px 8px;
        border: 1px solid #e5e5e5;
    }
    .mt-element-step .step-no-background-thin .mt-step-number {
        font-size: 20px;
        border-radius: 50%!important;
        float: left;
        margin: auto;
        padding: 0px 8px;
        border: 1px solid #000000;
    }
    .font-grey-cascade {
        color: #000000!important;
    }

</style><?php /**PATH C:\xampp\htdocs\oem\resources\views/epeDsMarking/questionanswersheet.blade.php ENDPATH**/ ?>