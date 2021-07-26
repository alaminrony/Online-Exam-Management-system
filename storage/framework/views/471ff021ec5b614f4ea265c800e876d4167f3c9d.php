<div class="portlet-body">
    <div class="mt-element-list">
        <div class="mt-list-head list-default ext-1 green-haze">
            <div class="row">
                <div class="col-xs-8">
                    <div class="list-head-title-container">
                        <h3 class="list-title uppercase sbold"><?php echo e($epe->Subject->title); ?></h3>
                        <!-- <div class="list-date"><?php echo e($epe->start_at); ?> - <?php echo e($epe->end_at); ?></div> -->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="list-head-summary-container">
                        <div class="list-pending">
                            <div class="list-count badge badge-default "><?php echo e($epe->no_of_mock); ?></div>
                            <div class="list-label"><?php echo e(__('label.MOCK_REQUIRED')); ?></div>
                        </div>
                        <div class="list-done">
                            <div class="list-count badge badge-default last"><?php echo e($completedMock); ?></div>
                            <div class="list-label"><?php echo e(__('label.MOCK_COMPLETED')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-list-container list-default ext-1">
            <?php if(!$mockListArr->isEmpty()): ?>
                <ul>
                    <?php $__currentLoopData = $mockListArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                            if($mock->pass == '1'){
                                $statusClass = 'done';
                                $icon = '<i class="icon-check"></i>';
                                $exam = '<a onclick="return false;">'.$mock->title.'</a>';
                                $title = 'Successfully Completed the Mock Test';
                            }else if($mock->pass == '2'){
                                $statusClass = '';
                                $icon = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'"><i class="icon-close"></i></a>';
                                $exam = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'">'.$mock->title.'</a>';
                                $title = 'Attempt Taken But Failed!';
                            }else{
                                $statusClass = '';
                                $icon = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'"><i class="icon-target"></i></a>';
                                $exam = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'">'.$mock->title.'</a>';
                                $title = 'No Attempt Taken Yet';
                            }
                        ?>
                    <li class="mt-list-item <?php echo $statusClass; ?> tooltips" title="<?php echo $title; ?>">
                            <div class="list-icon-container">
                                <?php echo $icon; ?>

                            </div>
                            <div class="list-datetime">
                                <span class="badge badge-default"><?php echo e((strlen($mock->duration_hours) === 1) ? '0'.$mock->duration_hours : $mock->duration_hours); ?>:<?php echo e((strlen($mock->duration_minutes) === 1) ? '0'.$mock->duration_minutes : $mock->duration_minutes); ?></span>
                            </div>
                            <div class="list-item-content">
                                <h3 class="uppercase">
                                    <?php echo $exam; ?>

                                </h3>
                                <p><?php echo e(__('label.TOTAL_NO_OF_QUESTION')); ?>: <?php echo e($mock->obj_no_question); ?></p>
                                <p class="text-warning"><small>(<?php echo e($mock->start_at); ?> <?php echo e(__('label.TO')); ?> <?php echo e($mock->end_at); ?>)</small></p>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="uppercase bold text-center"><?php echo e(__('label.NO_ACTIVE_MOCK_TEST_FOR')); ?> <?php echo e($epe->Subject->title); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php /**PATH C:\xampp\htdocs\oem\resources\views/isspstudentactivity/my_mock_list.blade.php ENDPATH**/ ?>