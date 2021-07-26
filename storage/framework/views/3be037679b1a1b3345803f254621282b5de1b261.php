<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i><?php echo app('translator')->get('label.PARTICIPATION_STATUS'); ?>
            </div>
            <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
            <?php if(!empty($targetArr)): ?>
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="<?php echo e(URL::to('participationStatus?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=print')); ?>"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="<?php echo e(URL::to('participationStatus?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=pdf')); ?>"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="<?php echo e(URL::to('participationStatus?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=excel')); ?>"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'participationStatus/generate','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('generate','true'); ?>


            <div class="row">
                <div class="col-md-4">
                    <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?>:<span class="text-danger">*</span></label>
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
                    <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?>:<span class="text-danger">*</span></label>
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
                <div class="col-md-2 text-center">
                    <div>
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
                    <?php if(!empty($targetArr)): ?>
                    <div class="pull-right">
                        <button  type="button" class="btn btn-md green btn-outline filter-submit margin-bottom-20" id="graphShow" data-id="1">
                            <i class="fa fa-line-chart"></i><span class="left-margin"><?php echo app('translator')->get('label.GRAPHICAL_VIEW'); ?></span>
                        </button>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo Form::close(); ?>

            <!-- End Filter -->
            <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
            <div class="bg-blue-hoki bg-font-blue-hoki">
                <h5 style="padding: 10px;">
                    <?php echo e(__('label.FROM_DATE')); ?> : <strong><?php echo e(!empty($request->from_date) ? $request->from_date : 'N/A'); ?> |</strong>
                    <?php echo e(__('label.TO_DATE')); ?> : <strong><?php echo e(!empty($request->to_date) ? $request->to_date : 'N/A'); ?> </strong>
                </h5>
            </div>
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.EXAM'); ?></th>
                            <th><?php echo app('translator')->get('label.ENROLL'); ?></th>
                            <th><?php echo app('translator')->get('label.ATTENDENT'); ?></th>
                            <th><?php echo app('translator')->get('label.ABSENT'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($targetArr)): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examId => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(++$sl); ?></td>
                            <td><?php echo e($examList[$examId]); ?></td>
                            <td><a type="button" class="tooltips studentDetails" data-toggle="modal" title="<?php echo e(__('label.VIEW_ENROLL_STUDENT')); ?>" data-target="#viewStudentModal" data-id="<?php echo e($examId); ?>" data-type="1"><?php echo e($result['enroll']); ?></a></td>
                            <td><a type="button" class="tooltips studentDetails" data-toggle="modal" title="<?php echo e(__('label.VIEW_ATTENDENT_STUDENT')); ?>" data-target="#viewStudentModal" data-id="<?php echo e($examId); ?>" data-type="2" ><?php echo e($result['attendend']); ?></a></td>
                            <td><a type="button" class="tooltips studentDetails"  data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_ABSENT_STUDENT'); ?>" data-target="#viewStudentModal" data-id="<?php echo e($examId); ?>" data-type="3" ><?php echo e($result['absent']); ?></a></td>
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
        </div>	

        <div id="graphicalView" style="display: none">
            <div id="chart">

            </div>  
        </div>
    </div>
</div>


<!--view contact Number Modal -->
<div class="modal fade" id="viewStudentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="sudentDetailsShow">
        </div>
    </div>
</div>
<!--end view Modal -->


<script src="<?php echo e(asset('public/js/apexchart.js')); ?>"></script>
<script src="<?php echo e(asset('public/js/ohlc.js')); ?>"></script>
<script>
$(document).ready(function () {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
    $(document).on('click', '#graphShow', function () {
        var status = $(this).attr('data-id');
        if (status == '1') {
            $(this).attr('data-id', '2');
            $(this).find("i.fa").toggleClass("fa-line-chart").toggleClass("fa-table");
            $(this).find('span').text('Tabuler View')
            $('#graphicalView').show('slow');
            $('#tableData').hide();
            $('#groupIcon').hide();
        } else {
            $(this).attr('data-id', '1');
            $(this).find("i.fa").toggleClass("fa-table").toggleClass("fa-line-chart");
            $(this).find('span').text('Graphical View')
            $('#tableData').show('slow');
            $('#graphicalView').hide();
            $('#groupIcon').show('slow');
        }
    });
    
    $(document).on('click','.studentDetails',function(){
        var examId = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(examId != '' && type!=''){
            $.ajax({
                url:"<?php echo e(url('participationStatus/getEmployeeDetails')); ?>",
                type:"post",
                dataType:"json",
                data:{exam_id:examId,type:type},
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $('#sudentDetailsShow').html(data.html);
                }
            });
        }
    });

    var colors = ["#F2784B", "#8E44AD", "#525E64"];
    var options = {
        series: [{
                name: "<?php echo app('translator')->get('label.TOTAL_NO_OF_ENROLLED_STUDENTS'); ?>",
                data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo $result['enroll'] . ',';
    }
}
?>
                ]
            }, {
                name: "<?php echo app('translator')->get('label.TOTAL_NO_OF_STUDENTS_ATTENDED_EXAMS'); ?>",
                data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo $result['attendend'] . ',';
    }
}
?>
                ]
            }, {
                name: "<?php echo app('translator')->get('label.TOTAL_NO_OF_ABSENT_STUDENTS_EXAMS'); ?>",
                data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo $result['absent'] . ',';
    }
}
?>
                ]
            }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        colors: colors,
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: "<?php echo app('translator')->get('label.PARTICIPATION_STATUS_FOR_ALL_EXAM'); ?>",
            align: 'center'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo "'$examList[$examId]',";
    }
}
?>
            ],
            title: {
                text: "<?php echo app('translator')->get('label.MONTHS'); ?>",
            }
        },
        yaxis: {
            title: {
                text: "<?php echo app('translator')->get('label.NO_OF_STUDENTS'); ?>",
            },
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/report/participationStatus/participationStatus.blade.php ENDPATH**/ ?>