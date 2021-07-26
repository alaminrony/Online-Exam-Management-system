@extends('layouts.default.master')
@section('data_count')
<!--This section use for Regular EPE-->
@if(count($epeArr) > 0 )
<?php
$colorArr = array('green', 'blue', 'blue-madison', 'grey-salsa', 'red-sunglo', 'purple', 'red-pink', 'yellow-crusta', 'purple-soft');
$i = 0;
?>
@if(count($epeArr) > 0)
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i> My Exam
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body" style="min-height:200px;">
            <div class="portlet">
                <div class="row">

                    @foreach($epeArr as $epe)
                    <?php
                    $durationHrs = !empty($epe->obj_duration_hours) ? $epe->obj_duration_hours : 0;
                    $durationMnt = !empty($epe->obj_duration_minutes) ? $epe->obj_duration_minutes : 0;

                    $objExamDuration = ($epe->obj_duration_hours * 60) + $epe->obj_duration_minutes;
                    $subExamDuration = ($epe->sub_duration_hours * 60) + $epe->sub_duration_minutes;

                    //Get Total Duration
                    $totalMinutes = $objExamDuration + $subExamDuration;
                    $hours = floor($totalMinutes / 60);
                    $minutes = ($totalMinutes % 60);

                    $durationHours = ($hours > 0) ? ($hours > 1) ? $hours . ' hours ' : $hours . ' hour ' : '';
                    $durationMinutes = ($minutes > 0) ? $minutes . ' minutes ' : '';
                    $durationTime = $durationHours . $durationMinutes;

                    //Get objective duration
                    $objectiveHouese = ($epe->obj_duration_hours > 0) ? ($epe->obj_duration_hours > 1) ? $epe->obj_duration_hours . ' hours ' : $epe->obj_duration_hours . ' hour ' : '';
                    $objectiveMinutes = ($epe->obj_duration_minutes > 0) ? $epe->obj_duration_minutes . ' minutes ' : '';

                    //Get subjective duration
                    $subjectiveHouese = ($epe->sub_duration_hours > 0) ? ($epe->sub_duration_hours > 1) ? $epe->sub_duration_hours . ' hours ' : $epe->sub_duration_hours . ' hour ' : '';
                    $subjectiveMinutes = ($epe->sub_duration_minutes > 0) ? $epe->sub_duration_minutes . ' minutes ' : '';

                    $activeEpeInfo = 'Subject : ' . $epe->subject_name . '<br/>Title : ' . $epe->title . '<br/> Total Time : ' . $durationTime . '<br/>';
                    $activeEpe = 'onclick="return showExamBox(' . $epe->id . ')" data-trigger="hover" data-placement="bottom" data-content="' . $activeEpeInfo . '" data-original-title="Exam Summary"';
                    ?>
                    @if($epe->submioltted != 2)

                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-v2 dashboard-card {{$colorArr[$i]}} popovers" {!! $activeEpe !!} style="margin-bottom: 25px;">
                            <div class="visual text-center bg-font-blue-hoki current-time-clock" style="width: 50%;">
                                <span class="text-center font-lg bold uppercase time-clock"><i class="fa fa-clock-o hidden-xs"></i> <span id="dashboard_card"></span></span>
                                <h5>Bangladesh Time (GMT+6.00 Hours)</h5>
                            </div>
                            <div class="details">
                                <div class="number exam-details">
                                    {{$epe->title}}
                                </div>
                                <div class="desc"><small>{{$epe->subject_name}}</small> </div>
                            </div>
                        </a>
                    </div>

                    <?php $i++;
                    ?>
                    @endif

                    @if($i === 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="note note-warning">
                                        <h3>{{ __('label.THERE_IS_NO_EPE_AVAILABLE_AT_THIS_MOMENT') }}</h3>
                                    </div>
                                </div>
                            </div>                                
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>  
</div>
@endif 

@else
<div class="col-md-12">
    <div class="form-group">
        <div class="col-md-12">
            <div class="note note-warning">
                <h3>{{ __('label.THERE_IS_NO_EPE_AVAILABLE_AT_THIS_MOMENT') }}</h3>
            </div>
        </div>

    </div>                                
</div>
@endif
<div class="col-md-12" id="show-epe-exam-box">
    <!--Ajax Call-->
</div>



<div id="epe_summary_modal" class="modal fade" tabindex="-1" data-focus-on="input:first">
        <div id="epe_summary_box">

        </div>
</div>
<link href="{{asset('public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css" />

<script src="{{asset('public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js')}}" type="text/javascript"></script>
<script type="text/javascript">
var timestamp = '<?= time(); ?>';
function updateTime() {
    // Create a new JavaScript Date object based on the timestamp
    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
    var date = new Date(timestamp * 1000);
    // Hours part from the timestamp
    var hours = date.getHours();

    if (hours <= 9) {
        hours = "0" + hours;
    }
    // Minutes part from the timestamp
    var minutes = "0" + date.getMinutes();
    // Seconds part from the timestamp
    var seconds = "0" + date.getSeconds();

    // Will display time in 10:30:23 format
    var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
    $('#time').html(formattedTime);
    $('#dashboard_card, #dashboard_card_time_irregular, #dashboard_card_time_rechdule').html(formattedTime);
    timestamp++;
}

$(function () {
    setInterval(updateTime, 1000);
});

function showExamBox(epeId) {
    $.ajax({
        url: "{{ URL::to('isspstudentactivity/epeexam') }}",
        type: "GET",
        data: {id: epeId},
        success: function (response) {
            $('#epe_summary_box').html(response.html); // load here
            var $modal = $('#epe_summary_modal');
            $modal.modal('show'); // show the modal
            App.unblockUI();
        },
        beforeSend: function () {
            //For ajax loader
            App.blockUI({
                boxed: true
            });
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
            if (jqXhr.status == 401) {
                toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
            } else {
                toastr.error("Error", "Something went wrong", {"closeButton": true});
            }
            App.unblockUI();
        }
    });
}

$(document).ready(function () {
    $(".popovers").popover({html: true});
});

</script>
<style type="text/css">
    a[disabled="disabled"] {
        pointer-events: none;
        cursor: default;
    }
    .pricing-content-1 .price-table-content .row{
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>
@stop