<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i><?php echo app('translator')->get('label.DEPARTMENT_STATUS'); ?>
            </div>
            <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
            <?php if(!empty($targetArr)): ?>
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="<?php echo e(URL::to('departmentStatusReport?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&department_id='.Request::get('department_id').'&view=print')); ?>"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="<?php echo e(URL::to('departmentStatusReport?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&department_id='.Request::get('department_id').'&view=pdf')); ?>"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="<?php echo e(URL::to('departmentStatusReport?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&department_id='.Request::get('department_id').'&view=excel')); ?>"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'departmentStatusReport/generate','class' => 'form-horizontal')); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="departmentId"><?php echo app('translator')->get('label.DEPARTMENT'); ?>:<span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('department_id',$departmentList,Request::get('department_id'),['class' => 'form-control js-source-states','id'=>'departmentId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('department_id')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?>:</label>
                    <div class="col-md-8">
                        <div class="input-group date datepicker" style="z-index: 9994 !important">
                            <?php echo Form::text('from_date',Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']); ?>

                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="fromDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger"><?php echo e($errors->first('from_date')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?>:</label>
                    <div class="col-md-8">
                        <div class="input-group date datepicker" style="z-index: 9994 !important">
                            <?php echo Form::text('to_date',Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']); ?>

                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="toDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger"><?php echo e($errors->first('to_date')); ?></span> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                    </button>
                </div>
                <div class="col-md-6">
                    <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
                    <?php if(!empty($targetArr)): ?>
                    <div class="col-md-6 pull-right">
                        <div class="pull-right">
                            <button  type="button" class="btn btn-md green btn-outline filter-submit margin-bottom-20" id="graphShow" data-id="1">
                                <i class="fa fa-line-chart"></i><span class="left-margin"><?php echo app('translator')->get('label.GRAPHICAL_VIEW'); ?></span>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo Form::close(); ?>


            <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
            <div class="bg-blue-hoki bg-font-blue-hoki">
                <h5 style="padding: 10px;">
                    <?php echo app('translator')->get('label.DEPARTMENT'); ?> : <strong><?php echo e(!empty($departmentList[$request->department_id]) ? $departmentList[$request->department_id] : 'N/A'); ?> |</strong>
                    <?php echo e(__('label.EXAM_DATE')); ?> : <strong><?php echo e(!empty($request->from_date) ? $request->from_date : 'N/A'); ?> |</strong>
                    <?php echo e(__('label.TO_DATE')); ?> : <strong><?php echo e(!empty($request->to_date) ? $request->to_date : 'N/A'); ?> </strong>
                </h5>
            </div>
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.EXAM_TITLE'); ?></th>
                            <th><?php echo app('translator')->get('label.EXAM_DATE'); ?></th>
                            <th><?php echo e(__('label.AVERAGE_MARKS'). '(%)'); ?></th>
                            <th><?php echo e(__('label.RESULT')); ?> <?php echo e(__('label.STATUS')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($targetArr)): ?>
                        <?php
                        $sl = 0;
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $epeId=>$totalPercentage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(++$sl); ?></td>
                            <td><?php echo e($epeList[$epeId]['title']); ?></td>
                            <td><?php echo e(Helper::dateFormat($epeList[$epeId]['exam_date'])); ?></td>
                            <td><?php echo e(Helper::numberformat($totalPercentage,2)); ?>%</td>
                            <td><?php echo e(Helper::findGrade($totalPercentage)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="10"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            <div id="graphicalView" style="display:none">
                <div id="chart">

                </div> 
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('public/js/apexchart.js')); ?>"></script>
<script src="<?php echo e(asset('public/js/ohlc.js')); ?>"></script>
<script>
    $(document).ready(function(){
    $(document).on('click', '#graphShow', function(){
    var status = $(this).attr('data-id');
    if (status == '1'){
    $(this).attr('data-id', '2');
    $(this).find("i.fa").toggleClass("fa-line-chart").toggleClass("fa-table");
    $(this).find('span').text('Tabuler View')
            $('#graphicalView').show('slow');
    $('#tableData').hide();
    } else{
    $(this).attr('data-id', '1');
    $(this).find("i.fa").toggleClass("fa-table").toggleClass("fa-line-chart");
    $(this).find('span').text('Graphical View')
            $('#tableData').show('slow');
    $('#graphicalView').hide();
    }
    });
    });
    var colors = ['#449DD1', '#F86624', '#EA3546', '#662E9B', '#C5D86D'];
    var options = {
    series: [{
    name: "<?php echo app('translator')->get('label.AVERAGE_MARKS'); ?>",
            data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $epeId => $totalPercentage) {
        ?>
                    "<?php echo e(Helper::numberformat($totalPercentage)); ?>",
        <?php
    }
}
?>
            ],
    }],
            chart: {
            height: 350,
                    type: 'bar',
            },
            fill: {
            type: 'gradient',
                    gradient: {
                    shade: 'light',
                            type: "horizontal",
                            shadeIntensity: 0.25,
                            gradientToColors: undefined,
                            inverseColors: true,
                            opacityFrom: 0.95,
                            opacityTo: 0.95,
                            stops: [50, 0, 100]
                    },
            },
            colors: colors,
            plotOptions: {
            bar: {
            dataLabels: {
            position: 'top', // top, center, bottom
            },
                    columnWidth: '35%',
                    distributed: true,
                    endingShape: 'rounded'
            }
            },
            dataLabels: {
            enabled: true,
                    formatter: function (val) {
                    return val + '%';
                    },
                    offsetY: - 20,
                    style: {
                    fontSize: '12px',
                            colors: ["#304758"]
                    }
            },
            legend: {
            show: false
            },
            xaxis: {
            categories: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $epeId => $totalPercentage) {
        ?>
                    "<?php echo e($epeList[$epeId]['title']); ?>",
        <?php
    }
}
?>
            ],
                    labels: {
                    rotate: - 55,
                            style: {
                            colors: colors,
                                    fontSize: '12px'
                            }
                    },
                    //             tickPlacement: 'off'
            },
            tooltip: {
            y: {
            formatter: function(val) {
            return  val + "%"
            },
            }
            },
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/report/departmentStatus/departmentStatusReport.blade.php ENDPATH**/ ?>