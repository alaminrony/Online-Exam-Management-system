<!--scrollbar start-->
<?php if(!$scrollmessageList->isEmpty()): ?>
<section id="scroll_section" class="scroll_body">
    <div class="container">
        <div class="row">
            <marquee onmouseover="this.stop();" onmouseout="this.start();">
                <div class="marquee marquee2">

                    <?php
                    $str = '';
                    ?>
                    <?php $__currentLoopData = $scrollmessageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $str .= '<i class="fa fa-envelope-o"></i> ' . $message->message . ' | '; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    echo trim($str, " | ");
                    ?>

                </div>
            </marquee>
        </div>
    </div>
</section>
<?php endif; ?>
<!--end scrollbar section --><?php /**PATH C:\xampp\htdocs\oem\resources\views/home/home_scroll.blade.php ENDPATH**/ ?>