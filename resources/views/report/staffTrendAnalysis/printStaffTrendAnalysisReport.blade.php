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
                <span>@lang('label.STAFF_TREND_ANALYSIS')</span>
            </div>
            <div class="logoCityBank">
                @if(Request::get('view') == 'pdf')
                <img src="{!! base_path() !!}/public/img/logo.png"/> 
                @else
                <img src="{!! asset('public/img/logo.png') !!}"  />
                @endif
            </div>
        </div>
        @if(Request::get('view') == 'pdf')
        <div class="row">
            <div class="text-center">
                <p style="height: 25px;"></p>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="text-center">
                <label><b>@lang('label.EMPLOYEE'):</b> {{!empty($employeeArr[Request::get('employee_id')]) ? $employeeArr[Request::get('employee_id')] : 'N/A'}}</label>
                @if(!empty(Request::get('from_date')))
                <label><b>@lang('label.FROM_DATE'):</b> {{!empty(Request::get('from_date')) ? Request::get('from_date') : 'N/A'}}</label>
                @endif
                @if(!empty(Request::get('to_date')))
                <label><b>@lang('label.TO_DATE'):</b> {{!empty(Request::get('to_date')) ? Request::get('to_date') : 'N/A'}}</label>
                @endif
            </div>
        </div>
        @endif
        @if(Request::get('view') == 'excel')
        <table>
            <tr>
                <td>
                    <label>@lang('label.EMPLOYEE'): {{$employeeArr[Request::get('employee_id')]>0 ? $employeeArr[Request::get('employee_id')] : 'N/A'}}</label>
                    <label>@lang('label.FROM_DATE'): {{!empty(Request::get('from_date')) ? Request::get('from_date') : 'N/A'}}</label>
                    <label>@lang('label.TO_DATE'): {{!empty(Request::get('to_date')) ? Request::get('to_date') : 'N/A'}}</label>
                </td>
            </tr>
        </table>
        @endif
        <!--Laravel Excel not supported body & other tags, only Table tag accepted-->
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="center">
                    <th>@lang('label.SL_NO')</th>
                    <th>@lang('label.EXAM')</th>
                    <th>@lang('label.EXAM_DATE')</th>
                    <th>@lang('label.ACHIEVED_MARK')</th>
                    <th>{{__('label.ACHIEVED_MARK'). '(%)'}}</th>
                    <th>{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($finalArr))
                <?php
                $page = Request::get('page');
                $page = empty($page) ? 1 : $page;
                $sl = ($page - 1) * Session::get('paginatorCount');
                ?>
                @foreach($finalArr as $result)
                <?php
                ?>
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ $result['exam_name'] }}</td>
                    <td>{{ $result['exam_date'] }}</td>
                    <td>{{ Helper::numberformat($result['achieved_mark'])}}</td>
                    <td>{{ Helper::numberformat($result['achieved_mark_per'],2) }}%</td>
                    <td>{{ Helper::findGrade($result['achieved_mark_per']) }}</td>
                </tr>
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
</html>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {
        window.print();
    });
</script>
@endif








