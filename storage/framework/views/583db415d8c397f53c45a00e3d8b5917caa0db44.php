<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title bold text-center">
            <?php echo app('translator')->get('label.VIEW_OBJECTIVE_QUESTION'); ?>
        </h4>
    </div>
    <div class="modal-body" id="display_objective_question">
        <div class="portlet light bordered">
            <div class="portlet-body ">
                <div class="row">
                    <div class="col-md-12">
                        <p class="bold text-center" style="text-decoration: underline;">
                            <br><?php echo e(__('label.SUBJECT')); ?>: <?php echo e($epeInfo->subject->title); ?>

                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="text-left bold" style="width: 50%"><?php echo e(__('label.TIME')); ?>: <?php echo e((strlen($epeInfo->obj_duration_hours) === 1) ? '0'.$epeInfo->obj_duration_hours : $epeInfo->obj_duration_hours); ?>:<?php echo e((strlen($epeInfo->obj_duration_minutes) === 1) ? '0'.$epeInfo->obj_duration_minutes : $epeInfo->obj_duration_minutes); ?></label><label class="text-right" style="width: 50%"><strong><?php echo e(__('label.FULL_MARKS')); ?>: <?php echo e(!empty($epeInfo->total_mark)?$epeInfo->total_mark : 0); ?></strong> </label>
                    </div>
                </div>
                <div class="mt-element-step">
                    <?php if((!$objective->isEmpty()) || (!$trueFalse->isEmpty()) || (!$fillingBlank->isEmpty()) || (!$matchingArr->isEmpty())): ?>
                    <?php $i = 1; ?>
                    <?php if(!$objective->isEmpty()): ?>
                    <?php $__currentLoopData = $objective; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="row step-no-background-thin">
                        <div class="mt-step-desc">
                            <div class="h5 bg-font-grey-cararra"><h5 class="bold"><?php echo e($i.'.  '.$question->question); ?></h5></div>
                        </div>

                        <?php if(!empty($question->image)): ?>
                        <div class="col-md-12 text-center">
                            <img class="question-script-image" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($question->image); ?>" alt="<?php echo e($question->image); ?>"> 
                        </div>
                        <?php endif; ?>

                        <div class="col-md-6 mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">a</div>
                            <div class="mt-step-content bg-font-grey-cararra"><?php echo e($question->opt_1); ?></div>
                        </div>
                        <div class="col-md-6 mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">b</div>
                            <div class="mt-step-content bg-font-grey-cararra"><?php echo e($question->opt_2); ?></div>
                        </div>
                        <div class="col-md-6 mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">c</div>
                            <div class="mt-step-content bg-font-grey-cararra"><?php echo e($question->opt_3); ?></div>
                        </div>
                        <div class="col-md-6 mt-step-col">
                            <div class="mt-step-number bg-font-grey-cararra">d</div>
                            <div class="mt-step-content bg-font-grey-cararra"><?php echo e($question->opt_4); ?></div>
                        </div>
                    </div>

                    <?php $i++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>


                    <?php if(!$trueFalse->isEmpty()): ?>
                    <h3><b><?php echo e(__('label.MARK_TRUE_FALSE').': '); ?></b></h3>
                    <?php //$i = 1; ?>
                    <?php $__currentLoopData = $trueFalse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row step-no-background-thin">
                        <div class="mt-step-desc">
                            <div class="bg-font-grey-cararra"><h5 class="bold"><?php echo e($i.'.  '.$question2->question); ?></h5></div>
                        </div>
                    </div>

                    <?php if(!empty($question2->image)): ?>
                    <div class="col-md-12 text-center">
                        <img class="question-script-image" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($question2->image); ?>" alt="<?php echo e($question2->image); ?>"> 
                    </div>
                    <?php endif; ?>

                    <?php $i++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?> 

                    <?php if(!$fillingBlank->isEmpty()): ?>
                    <h3><b><?php echo e(__('label.FILLING_THE_BLANK').': '); ?></b></h3>
                    <?php //$i = 1; ?>
                    <?php $__currentLoopData = $fillingBlank; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row step-no-background-thin">
                        <div class="mt-step-desc">
                            <div class="bg-font-grey-cararra"><h5 class="bold"><?php echo e($i.'.  '.$question3->question); ?></h5></div>
                        </div>
                    </div>

                    <?php if(!empty($question3->image)): ?>
                    <div class="col-md-12 text-center">
                        <img class="question-script-image" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($question3->image); ?>" alt="<?php echo e($question3->image); ?>"> 
                    </div>
                    <?php endif; ?>

                    <?php $i++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <?php if(!$matchingArr->isEmpty()): ?>
                    <div class="row step-no-background-thin">
                        <div class="mt-step-desc">
                            <div class="h4 bg-font-grey-cararra"><h4 class="bold margin-bottom-20"><?php echo e(__('label.QUESTION_INSTRUCTION_FOR_MATCHING')); ?></h4></div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center col-md-1"><?php echo e(__('label.SL_NO')); ?></th>
                                <th class="text-center"><strong><?php echo e(__('label.COLUMN_A')); ?></strong></th>
                                <th class="text-center"><strong><?php echo e(__('label.COLUMN_B')); ?></strong></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $matchingArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question6): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($i); ?> </td>
                                <td>
                                    <?php echo e($question6->question); ?> 
                                    <?php if(!empty($question6->image)): ?>
                                    <div class="col-md-12 text-center">
                                        <img class="question-script-image" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($question6->image); ?>" alt="<?php echo e($question6->image); ?>"> 
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($question6->match_answer); ?></td>
                            </tr>
                            <?php $i++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                    <?php if(!$sebjectiveArr->isEmpty()): ?>
                    <h3><b><?php echo e(__('label.SUBJECTIVE').': '); ?></b></h3>
                    <?php //$i = 1; ?>
                    <?php $__currentLoopData = $sebjectiveArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question4): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row step-no-background-thin">
                        <div class="mt-step-desc">
                            <div class="bg-font-grey-cararra"><h5 class="bold"><?php echo e($i.'.  '.$question4->question); ?></h5></div>
                        </div>
                    </div>

                    <?php if(!empty($question4->image)): ?>
                    <div class="col-md-12 text-center">
                        <img class="question-script-image" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($question3->image); ?>" alt="<?php echo e($question3->image); ?>"> 
                    </div>
                    <?php endif; ?>

                    <?php $i++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php else: ?>
                    <h3 class="text-center text-danger"><?php echo e(__('label.NO_QUESTION_FOUND_FOR_THIS_EPE')); ?></h3>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <style type="text/css">

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
            .bg-font-grey-cararra {
                color: #000000!important;
            }

            .question-script-image{
                padding: 20px;
                max-height: 300px;
            }

        </style>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\oem\resources\views/epe/objectiveQuestionView.blade.php ENDPATH**/ ?>