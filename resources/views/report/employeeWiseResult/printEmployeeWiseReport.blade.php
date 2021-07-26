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
                <span>@lang('label.EMPLOYEE_WISE_RESULT')</span>
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
                <p style="height: 20px;"></p>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="text-center">
                <p>
                    <label><b>@lang('label.EMPLOYEE'):</b> {{!empty($employeeArr[Request::get('fill_employee_id')])?$employeeArr[Request::get('fill_employee_id')]: 'N/A'}}</label>
                    <label><b>@lang('label.SUBJECT'):</b> {{!empty($subjectArr[Request::get('fill_subject_id')]) ? $subjectArr[Request::get('fill_subject_id')] : 'N/A'}}</label>
                    @if(!empty(Request::get('fill_exam_id')))
                    <label><b>@lang('label.EXAM'):</b> {{!empty($examInfoArr[Request::get('fill_exam_id')])?$examInfoArr[Request::get('fill_exam_id')]: 'N/A'}}</label>
                    @endif
                </p>
            </div>
        </div>
        @endif

        <!--Laravel Excel not supported body & other tags, only Table tag accepted-->
        @if(!empty($request->generate) && $request->generate == 'true')
        @if(!empty($finalArr))

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="center">
                        <th>@lang('label.SL_NO')</th>
                        <th>@lang('label.EXAM')</th>
                        <th>@lang('label.EXAM_DATE')</th>
                        <th>@lang('label.RESULT_PUBLISH_DATE_TIME')</th>
                        <th>@lang('label.TOTAL_MARK')</th>
                        <th>@lang('label.EMPLOYEE_NAME')</th>
                        <th>@lang('label.OBJECTIVE_MARK')</th>
                        <th>@lang('label.SUBJECTIVE_MARK')</th>
                        <th>@lang('label.ACHIEVED_MARK')</th>
                        <th>{{__('label.ACHIEVED_MARK'). '(%)'}}</th>
                        <th>{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $page = Request::get('page');
                    $page = empty($page) ? 1 : $page;
                    $sl = ($page - 1) * Session::get('paginatorCount');
                    ?>
                    @foreach($finalArr as $result)
                    <tr>
                        <td>{{ ++$sl }}</td>
                        <td>{{ $result['title'] }}</td>
                        <td>{{ Helper::printDate($result['exam_date']) }}</td>
                        <td>{{ Helper::formatDateTime($result['result_publish']) }}</td>
                        <td>{{ $result['total_mark'] }}</td>
                        <td>{{ $result['employee_name'] }}</td>
                        <td>{{ Helper::numberformat($result['objective_mark']) }}</td>
                        <td>{{ Helper::numberformat($result['subjective_mark']) }}</td>
                        <td>{{ Helper::numberformat($result['achieved_mark'])}}</td>
                        <td>{{ Helper::numberformat($result['achieved_mark_per'],2) }}%</td>
                        <td>{{ Helper::findGrade($result['achieved_mark_per']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            @if(Auth::user()->group_id == 3)
            <h2 class="text-center text-danger"> @lang('label.THE_RESULT_HAVE_NOT_PUBLISHED')</h2>
            @else
            <h2 class="text-center text-danger"> @lang('label.NO_DATA_FOUND')</h2>
            @endif
            @endif
        </div>
        @endif
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








