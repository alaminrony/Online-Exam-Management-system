<style>
    table.table tr:last-child{
        border-bottom: 1px solid #ddd;
    }

    table.table td,
    table.table th
    {
        vertical-align:middle!important;
    }
    .signature-section p{
        margin: 0;
    }
</style>
<html>
    <head>
        <title>{{trans('english.CSTI_FULL')}}</title>
        <link rel="shortcut icon" type="image/icon" href="{{URL::to('/')}}/public/img/favicon.ico"/>
        {{ HTML::style('public/css/print.css'); }}
        @if($type == 'pdf')
        {{ HTML::style('public/css/pdf.css'); }}
        @endif
    </head>
    <body>
        @if($type == 'pdf')
        <header>
            <div class="page-header">
                <h6>{{trans('english.CONFIDENTIAL')}}</h6>
            </div>
        </header>
        <footer>
            <div class="page-footer">
                <h6>{{trans('english.CONFIDENTIAL')}}</h6>
            </div>
        </footer>
        @endif
        <!--<main>-->
        @if(!$targetArr->isEmpty())

        <div class="table-scrollable" style="padding-bottom: 20px;">
            @if($type != 'pdf')
            <div class="row">
                <div class="col-md-12">
                    <div class="confidential">
                        <p>{{trans('english.CONFIDENTIAL')}}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12" style="margin-left: 10px;">
                    <table class="table table-bordered table-striped">    
                        <tbody>
                            <tr>
                                <td rowspan="4">
                                    <div class="profile-userpic text-center">
                                        @if(!empty($student->photo))
                                        @if(Request::get('type') == 'pdf')
                                        <img  src="{{base_path()}}/public/uploads/user/{{$student->photo}}" class="img-responsive img-circle" style="height: 100px; width: 100px;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                        @elseif(Request::get('type') == 'print')
                                        <img  src="{{URL::to('/')}}/public/uploads/user/{{$student->photo}}" class="img-responsive img-circle" style="height: 100px; width: 100px;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                        @endif
                                        @else
                                        <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive img-circle" style="height: 100px; width: 100px;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                        @endif
                                    </div>
                                </td>
                                <td colspan="2"><b>{{ $student->short_name.' '.$student->first_name.' '.$student->last_name }}</b></td>

                            </tr>
                            <tr>
                                <td>{{ trans('english.REGISTRATION_NO').' : '.$student->registration_no }}</td>
                                <td>{{ trans('english.OFFICIAL_NAME').' : '.$student->official_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('english.SERVICE_NO').' : '.$student->service_no }}</td>
                                <td>{{ trans('english.BRANCH').' : '.$student->branch_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('english.ISS_NO').' : '.$student->iss_no }}</td>
                                <td>{{ trans('english.REGISTRATION_EXPIRY_DATE').' : '.$student->maximum_tenure }}</td>
                            </tr>        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-left: 10px;">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>{{trans('english.SL_NO')}}</th>
                            <th class="text-center">{{trans('english.OBJECTIVE')}}</th>
                            <th class="text-center">{{trans('english.SUBJECTIVE')}}</th>
                            <th class="text-center">{{trans('english.ACHIEVED_MARK')}}</th>
                            <th class="text-center">{{trans('english.ACHIEVED_MARK'). '(%)'}}</th>
                            <th class="text-center">{{trans('english.RESULT')}} {{trans('english.STATUS')}}</th>
                        </tr>

                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $result)
                        <tr>
                            <td >{{++$sl}}</td>
                            <td class="text-center">{{ ($result->objective_earned_mark == null) ? trans('english.BLANK') :  $result->objective_earned_mark}}</td>
                            <td class="text-center">{{ ($result->subjective_earned_mark == null) ? trans('english.BLANK') :  $result->subjective_earned_mark}}</td>
                            <td class="text-center">{{ Custom::numberformat($result->objective_earned_mark + $result->subjective_earned_mark)}}</td>
                            <td class="text-center">{{ Custom::numberformat(($result->total_mark * ($result->objective_earned_mark + $result->subjective_earned_mark))/100).'%' }}</td>
                            <td class="text-center">
                                <?php
                                $totalMark = $result->objective_earned_mark + $result->subjective_earned_mark;
                                ?>
                                @if($result->ds_status != '0')
                                @if($totalMark >= $result->pass_mark)
                                <span class="label label-success">{{trans('english.PASSED')}}</span>
                                @elseif($totalMark < $result->pass_mark)
                                <span class="label label-danger">{{trans('english.FAILED')}}</span>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @if($type != 'pdf')
            <br/><br/>
            <div class="row">
                <div class="col-md-12">
                    <div class="confidential">
                        <p>{{trans('english.CONFIDENTIAL')}}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @else
        <h2 class="text-center text-danger">{{trans('english.THERE_IS_NO_RESULT_AVAILABLE_FOR_THIS_STUDENT')}}</h2>
        @endif
        <!--</main>-->
        <script>
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
                //window.close();
            });
        </script>
    </body>
</html>
