<div class="row">
    <div class="col-md-10">
        <?php echo e($targetArr->appends(Request::all())->links()); ?>

        <?php
        $start = empty($targetArr->total()) ? 0 : (($targetArr->currentPage() - 1) * $targetArr->perPage() + 1);
        $end = ($targetArr->currentPage() * $targetArr->perPage() > $targetArr->total()) ? $targetArr->total() : ($targetArr->currentPage() * $targetArr->perPage());
        ?> <br />
        <?php echo app('translator')->get('label.SHOWING'); ?> <?php echo e($start); ?> <?php echo app('translator')->get('label.TO'); ?> <?php echo e($end); ?> <?php echo app('translator')->get('label.OF'); ?>  <?php echo e($targetArr->total()); ?> <?php echo app('translator')->get('label.RECORDS'); ?>
    </div>
    <div class="col-md-2" id="recordPerPageHolder">					
        <?php echo Form::open(array('group' => 'form', 'url' => 'setRecordPerPage', 'class' => '')); ?>

        <div class="input-group">
            <div class="input-icon">
                <i class="fa fa-list fa-fw"></i>
                <?php echo Form::text('record_per_page', Session::get('paginatorCount'), ['class' => 'form-control integer-only tooltips'
                , 'title' => __('label.RECORDS_PER_PAGE'), 'placeholder' => __('label.RECORDS_PER_PAGE'), 'id' => 'recordPerPage',
                'maxlength' => 3]); ?>

            </div>
            <span class="input-group-btn">
                <button id="" class="btn btn-success" type="submit">
                    <i class="fa fa-arrow-right fa-fw"></i></button>
            </span>
        </div>
        <?php echo Form::close(); ?>

    </div>
</div><?php /**PATH C:\xampp\htdocs\oem\resources\views/layouts/paginator.blade.php ENDPATH**/ ?>