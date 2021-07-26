@extends('layouts.default.master')
@section('data_count')

<!--Start Course Statistics For JCSC Program-->
<?php
$colorArr = array('green', 'blue', 'blue-madison', 'grey-salsa', 'red-sunglo', 'purple', 'red-pink', 'yellow-crusta', 'purple-soft');
$i = 0;
?>
@if(count($epeArr) > 0)

@foreach($epeArr as $epe)
<?php
$activeEpeInfo = 'Mock Test for  ' . $epe->subject_name . '<br/>Exam Title : ' . $epe->title . '<br/> Total Mock Test : ' . $epe->total_mock;
$activeEpe = 'id="mymocklist" data-id="' . $epe->id . '" data-trigger="hover" data-placement="bottom" data-content="' . $activeEpeInfo . '" data-original-title="Mock Test"';
?>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 dashboard-card {!! $colorArr[$i] !!} popovers" {!! $activeEpe !!}>
        <div class="visual text-center bg-font-blue-hoki current-time-clock" style="width: 50%;">
            <span class="text-center font-lg bold uppercase time-clock"><i class="fa fa-clock-o hidden-xs"></i> <span id="dashboard_card_time"></span></span>
            <h5>Bangladesh Time (GMT+6.00 Hours)</h5>
        </div>
        <div class="details">
            <div class="number exam-details">
                {{$epe->mock_title}}
            </div>
            <div class="desc"><small>{{$epe->subject_name}}</small> </div>
        </div>
    </a>
</div>
<?php $i++; ?>
@endforeach
@else 
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <div class="note note-warning">
                    <h3>{{__('label.THERE_IS_NO_MOCK_TEST_AVAILABLE_AT_THIS_MOMENT') }}</h3>
                </div>
            </div>

        </div>                                
    </div>
</div>
@endif

</div>
<div class="row" id="show-mock-exam-box">
    <!--Ajax Call-->
</div>
<div id="stack1" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><span class="text-center btn green font-lg bold uppercase"><i class="fa fa-clock-o"></i> <span id="mock_details_time"></span></span> <span class="bangladesh-time">Bangladesh Time (GMT+6.00 Hours)</span></h4>
    </div>
    <div class="modal-body">
        <div class="pricing-content-1" style="background-color:#eef1f5;">
            <div id="my_mock_list">

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-circle grey-salsa btn-outline uppercase">Close</button>
    </div>
</div>

<div id="stack2" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" style="padding-left: 16px;"><span class="text-center btn green font-lg bold uppercase"><i class="fa fa-clock-o"></i> <span id="mock_list_card_time"></span></span> <span class="bangladesh-time">Bangladesh Time (GMT+6.00 Hours)</span></h4>
    </div>
    <div class="modal-body">
        <div id="mock_play_box">

        </div>
    </div>
    <div class="modal-footer">&nbsp;</div>
</div>
<link href="{{asset('public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js')}}" rel="stylesheet" type="text/css" />
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
        $('#dashboard_card_time').html(formattedTime);
        $('#mock_list_card_time').html(formattedTime);
        $('#mock_details_time').html(formattedTime);

        timestamp++;
    }

    $(function () {
        setInterval(updateTime, 1000);
    });

    function showExamBox(mockId) {
        $.ajax({
            url: "{{ URL::to('isspstudentactivity/mockexam') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {id: mockId},
            success: function (response) {
                $('#my_mock_list').html(response.html); // load here
                //Ending ajax loader
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

        $(document).on('click', '#mymocklist', function (e) {
            e.preventDefault();
            var epeId = $(this).data('id'); // get id of clicked row

            $('#my_mock_list').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('isspstudentactivity/mymocklist') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {epe_id: epeId},
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#my_mock_list').html(response.html)
                    var $modal = $('#stack1');
                    $modal.modal('show'); // show the modal
                    //Ending ajax loader
                    App.unblockUI();
                    $(".tooltips").tooltip({html: true});
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
        });
        //This function use for mock play box
        $(document).on('click', '.mockplay', function (e) {
            e.preventDefault();
            var mockId = $(this).data('id'); // get id of clicked row

            $('#mock_play_box').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('isspstudentactivity/mockplay') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {mock_id: mockId},
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#mock_play_box').html(''); // blank before load.
                    $('#mock_play_box').html(response.html); // load here
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
        });

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