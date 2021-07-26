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
        <title>{{__('label.CSTI_FULL')}}</title>
        <link rel="shortcut icon" type="image/icon" href="{{URL::to('/')}}/public/img/favicon.ico"/>
         <link href="{{asset('public/css/print.css')}}" rel="stylesheet" type="text/css">
        @if(Request::get('type') == 'pdf')
           <link href="{{asset('public/css/pdf.css')}}" rel="stylesheet" type="text/css">
        @endif
    </head>
    <body>
            @if(!empty($targetArr))
            <h5>
                <strong>{{__('label.EPE')}}: </strong>{{$epeInfo->title}} |
                <strong>{{__('label.SUBJECT')}}: </strong> {{$epeInfo->subject->title}}
            </h5>
            <div class="table-scrollable" style="padding-bottom: 20px;">
                <div class="row">
                    <div class="col-md-12" style="margin-left: 10px;">
                        <table class="table table-bordered table-striped">    
                            <tbody>
                                <tr>
                                    <td rowspan="4">
                                        <div class="profile-userpic text-center">
                                            @if(Request::get('type') == 'pdf')
                                            @if(!empty($student->photo))
                                            <img  src="{{base_path()}}/public/uploads/user/{{$student->photo}}" class="img-responsive img-circle" style="height: 100px; width: 100px;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                            @else
                                            <img  src="{{base_path()}}/public/img/unknown.png" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:10px 10px;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                            @endif
                                            @elseif(Request::get('type') == 'print')
                                            @if(!empty($student->photo))
                                            <img  src="{{URL::to('/')}}/public/uploads/user/{{$student->photo}}" class="img-responsive img-circle" style="height: 100px; width: 100px;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                            @else
                                            <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive img-circle" style="height: 100px; width: 100px; overflow: hidden; display: block;margin:auto;" alt="{{ $student->first_name.' '.$student->last_name }}">
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td colspan="2"><b>{{ $student->short_name.' '.$student->first_name.' '.$student->last_name }}</b></td>

                                </tr>
                                <tr>
                                    <td>{{ __('label.REGISTRATION_NO').' : '.$student->registration_no }}</td>
                                    <td>{{ __('label.OFFICIAL_NAME').' : '.$student->official_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('label.SERVICE_NO').' : '.$student->service_no }}</td>
                                    <td>{{ __('label.BRANCH').' : '.$student->branch_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('label.ISS_NO').' : '.$student->iss_no }}</td>
                                    <td>{{ __('label.REGISTRATION_EXPIRY_DATE').' : '.$student->maximum_tenure }}</td>
                                </tr>        
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-left: 10px;">
                        <table class="table table-striped table-bordered">

                            <tr>
                                <th>{{__('label.SL_NO')}}</th>
                                <th>{{__('label.TITLE')}}</th>
                                <th>{{__('label.SUBMITTED_DATE')}}</th>
                                <th>{{__('label.NO_OF_QUESTION')}}</th>
                                <th>{{__('label.CORRECT_ANSWER')}}</th>
                                <th>{{__('label.ATTEMPT')}}</th>
                                <th>{{__('label.ACHIEVED_MARK')}}</th>
                                <th>{{__('label.STATUS')}}</th>
                            </tr>
                            <?php $sl = 0; ?>
                            @foreach($targetArr as $value)
                            <tr>
                                <td>{{++$sl}}</td>
                                <td>{{$value->title}}</td>
                                <td>{{$value->submission_time}}</td>
                                <td>{{$value->no_of_question}}</td>
                                <td>{{$value->no_correct_answer}}</td>
                                <td>{{$value->attempt}}</td>
                                <td>{{$value->converted_mark}}</td>

                                <td>
                                    @if($value->pass == '1')
                                    <span class="label label-success"> {{__('label.PASSED')}} </span>
                                    @else
                                    <span class="label label-danger"> {{__('label.FAILED')}} </span>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <br/><br/>
            </div>
            @else
            <h2 class="text-center text-danger">{{__('label.THERE_IS_NO_RESULT_AVAILABLE_FOR_THIS_STUDENT')}}</h2>
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
