@extends('layouts.epeExam')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box">

        <div class="portlet-body form">
            <div class="form-body text-center" id="objectiveExamHeader">
                <div id="script-header">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 confidential">{{ __('label.CONFIDENTIAL') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.EXAM_HEADER') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.SUBJECT') .': '.$epe->Subject->title }}</div>
                    </div>
                    <br />
                </div>

            </div>



            <div class="form-body" id="">

                <div class="row">
                    <div class="col-md-10 col-md-offset-1 text-center">
                        <br />                                
                        <i class="fa fa-exclamation-triangle" style="font-size: 80px;"></i>
                        <h3>@lang('label.EXAM_START_INSTRUCTION')</h3>
                        <br />
                    </div>
                </div>                        

                <div class="row">
                    <div class="form-actions">
                        <div class="col-md-12 text-center">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-primary" id="confirmDisclaimer"><i class="fa fa-check"></i> {{__('label.YES_I_AGREE')}}</button>
                                <a class="btn btn-warning" id="cancel" href="{{ URL::to('isspstudentactivity/myepe/') }}"><i class="fa fa-ban"></i> {{__('label.CANCEL')}}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- END FORM-->
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

    //Tooltip, activated by hover event
    $(".tooltips").tooltip({html: true});
            //They can be chained like the example above (when using the same selector).

            //Disable cut copy paste
//            $('body').bind('cut copy paste', function (e) {
//    e.preventDefault();
//    });
            //Disable mouse right click
//            $("body").on("contextmenu", function (e) {
//    return false;
//    });
            window.addEventListener("beforeunload", function (e) {

            var confirmationMessage = 'Your quiz progress will lost if you leave this page!';
                    var redirect = $("#redirect").val();
                    if (redirect == '0') {
            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
            }

            });
            $(document).on("click", '#confirmDisclaimer', function (e) {
    e.preventDefault();
            var epeId = {{ Request::get('id') }};
            $.ajax({
            url: "{{ URL::to('disclaimer') }}",
                    type: "POST",
                    data: {"epe_id": epeId},
                    dataType: 'json',
                     headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    //cache: false,
//                contentType: false,
//                processData: false,
                    //async: true,
                    success: function (response) {
                    window.location = "{!! URL::to('/epeExam?id=" + epeId + "') !!}";
                    },
                    beforeSend: function () {
                    App.blockUI({
                    boxed: true
                    });
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                    alert('Something went wrong, try refreshing')
                    }
            });
    });
    }
    );

</script> 

@stop

