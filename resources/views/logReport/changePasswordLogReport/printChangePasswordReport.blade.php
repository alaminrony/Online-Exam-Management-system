@if($request->view == 'print' || $request->view == 'pdf')
<html>
    <head>
        <title>@lang('label.COMMAND_AND_STAFF_TRAINING_INSTITUTE_CSTI_BAF')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />

        @elseif(Request::get('view') == 'pdf')
        <link rel="shortcut icon" href="{!! base_path() !!}/public/img/favicon.ico" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'}}" rel="stylesheet" type="text/css"/>
        @endif
    </head>
    <body>
        <div class="header">
            <div class="logoRetail">
                @if(Request::get('view') == 'pdf')
                <img src="{!! base_path() !!}/public/img/retail_logo.png" /> 
                @else
                <img src="{!! asset('public/img/retail_logo.png') !!}"  /> 
                @endif
            </div>
            <div class="logoTile">
                <span>@lang('label.CHANGE_PASSWORD_REPORT')</span>
            </div>
            <div class="logoCityBank">
                @if(Request::get('view') == 'pdf')
                <img src="{!! base_path() !!}/public/img/logo.png"/> 
                @else
                <img src="{!! asset('public/img/logo.png') !!}"  />
                @endif
            </div>
        </div>
       
        <div>
            <p>
                <b>@lang('label.FROM_DATE'):</b>{{Helper::printDate(Request::get('from_date'))}} 
                <b>@lang('label.TO_DATE'):</b>{{Helper::printDate(Request::get('to_date'))}}
            </p>
        </div>
        @endif
        <!--Laravel Excel not supported body & other tags, only Table tag accepted-->
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="center">
                    <th>@lang('label.SL_NO')</th>
                    <th>@lang('label.DATE')</th>
                    <th>@lang('label.AFFECTED_USER')</th>
                    <th>@lang('label.REFORMING_USER')</th>
                    <th>@lang('label.ACTION_TOKEN')</th>
                    <th>@lang('label.DATE_OF_ACTION')</th>
                    <th>@lang('label.OPERATING_SYSTEM')</th>
                    <th>@lang('label.BROWSER')</th>
                    <th>@lang('label.IP_ADDRESS')</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($targetArr))
                <?php
                $sl = 0;
                ?>
                @foreach($targetArr as $target)
                <tr>

                    <td>{{ ++$sl }}</td>
                    <td>{{Helper::printDate($target['date'])}}</td>
                    <td>{{$userList[$target['affected_user_id']]??''}}</td>
                    <td>{{$userList[$target['reforming_user_id']]??''}}</td>
                    <td>{{$target['action']}}</td>
                    <td>{{Helper::formatDateTime($target['date_time'])}}</td>
                    <td>{{$target['operating_system']}}</td>
                    <td>{{$target['browser']}}</td>
                    <td>{{$target['ip_address']}}</td>

                    @endforeach
                    @else
                <tr>
                    <td colspan="10">@lang('label.NO_DATA_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
        <!--Laravel Excel not supported  body & other tags, only Table tag accepted-->


        @if($request->view == 'print' || $request->view == 'pdf')
        <div class="row">
            <div class="col-md-4">
                <div class="col-md-4">
                    <p>@lang('label.REPORT_GENERATED_ON') {{ Helper::printDateTime(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name}}</p>
                </div>
            </div>
            <div class="col-md-8 print-footer">
                <p><b>{{__('label.ONLINE_EXAM_MANAGEMENT')}}</b></p>
            </div>
        </div>

    </body>
    <script src="{{asset('public/js/jquery.min.js')}}"></script>
    <script>
$(document).ready(function () {
    window.print();
});
    </script>
</html>
@endif
























