@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-graduation-cap"></i>{{trans('english.VIEW_STUDENTS')}}
                    </div>
                    <div class="actions">
                        <a href="{{ URL::to('student/create') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-plus"></i> {{trans('english.CREATE_A_STUDENT')}} </a>
                    </div>
                </div>
                <div class="portlet-body">

                    {{ Form::open(array('role' => 'form', 'url' => 'student/filter', 'class' => '', 'id' => 'studentFilter')) }}
                    <div class="row">
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">{{trans('english.SELECT_RANK')}}</label>
                                {{Form::select('rank_id', $rankList, Request::get('rank_id'), array('class' => 'form-control js-source-states', 'id' => 'studentRankId'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">{{trans('english.SELECT_APPROINTMENT')}}</label>
                                {{Form::select('appointment_id', $appointmentList, Request::get('appointment_id'), array('class' => 'form-control js-source-states', 'id' => 'studentApprointmentId'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">{{trans('english.SELECT_BRANCH')}}</label>
                                {{Form::select('branch_id', $branchList, Request::get('branch_id'), array('class' => 'form-control js-source-states', 'id' => 'studentBranchId'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">{{trans('english.ACCOUNT_CONFIRMED')}}</label>
                                {{Form::select('account_confirmed', $accountConfirmedStatus, Request::get('account_confirmed'), array('class' => 'form-control js-source-states', 'id' => 'accountConfirmedId'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group tooltips" title="Search by Username/First Name/Last Name/Official Name/Registration No/Service No/ISS No/JCSC Index">
                                <label class="control-label">{{trans('english.SEARCH_TEXT')}}</label>
                                {{ Form::text('search_text', Request::get('search_text'), array('id'=> 'studentSearchText', 'class' => 'form-control', 'placeholder' => 'Enter Search Text')) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                    {{Form::close()}}
                    <div class="row">
                        <div class="table-responsive">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{trans('english.SL_NO')}}</th>
                                            <th>{{trans('english.REGISTRATION_NO')}}</th>
											@if(Session::get('program_id') == '1')
                                            <th>{{trans('english.ISS_NO')}}</th>
                                            @endif
                                            <th>{{trans('english.NAME')}}</th>
                                            <th>{{trans('english.APPOINTMENT')}}</th>
                                            <th>{{trans('english.BRANCH')}}</th>     
                                            <th>{{trans('english.USERNAME')}}</th>
                                            <th class='text-center'>{{trans('english.PHOTO')}}</th>
                                            <th class="text-center">{{trans('english.ACCOUNT_CONFIRMED')}}</th>
                                            <th class="text-center">{{trans('english.DATE_OF_COMMISSION')}}</th>
                                            <th class="text-center">{{trans('english.STATUS')}}</th>
                                            <th class='text-center'>{{trans('english.ACTION')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!$studentArr->isEmpty())
                                        <?php
                                        $page = Request::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page - 1) * 10;
                                        ?>
                                        @foreach($studentArr as $key => $value)

                                        <tr class="contain-center">
                                            <td>{{++$sl}}</td>
                                            <td>{{$value->registration_no}}</td>
											@if(Session::get('program_id') == '1')
                                            <td>{{ $value->iss_no }}</td>
                                            @endif
                                            <td>{{$value->rank->short_name}} {{$value->first_name}} {{$value->last_name}}</td>
                                            <td>{{$value->appointment->title}}</td>
                                            <td>{{$value->branch->name}}</td>
                                            <td>{{ $value->username }}</td>
                                            <td class="text-center">
                                                @if(isset($value->photo))
                                                <img width="100" height="100" src="{{URL::to('/')}}/public/uploads/thumbnail/{{$value->photo}}" alt="{{ $value->first_name.' '.$value->last_name }}">
                                                @else
                                                <img width="100" height="100" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $value->first_name.' '.$value->last_name }}">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($value->password_changed == '1')
                                                <span class="label label-success">{{trans('english.YES')}}</span>
                                                @else
                                                <span class="label label-warning">{{trans('english.NO')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($value->commission_date))
                                                <span class="bold">{{$value->commission_date}}</span>
                                                @else
                                                <span>--</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($value->status == 'active')
                                                <span class="label label-success">{{ $value->status }}</span>
                                                @else
                                                <span class="label label-warning">{{ $value->status }}</span>
                                                @endif
                                            </td>
                                            <td class="action-center">
                                                <div class="text-center student-action">
                                                    {{ Form::open(array('url' => 'student/' . $value->id, 'id' => 'delete')) }}
                                                    {{ Form::hidden('_method', 'DELETE') }}

                                                    <?php
                                                    $dd = Input::query();
                                                    if (!empty($dd)) {
                                                        $param = '';
                                                        $sn = 1;

                                                        foreach ($dd as $key => $item) {
                                                            if ($sn === 1) {
                                                                $param .= $key . '=' . $item;
                                                            } else {
                                                                $param .= '&' . $key . '=' . $item;
                                                            }
                                                            $sn++;
                                                        }//foreach
                                                    }
                                                    ?>
                                                    <a class='btn btn-info btn-xs tooltips student-action-list' href="{{ URL::to('student/activate/' . $value->id ) }}@if(isset($param)){{'?'.$param }} @endif" data-rel="tooltip" title="@if($value->status == 'active') Inactivate @else Activate @endif" data-placement="top">
                                                        @if($value->status == 'active')
                                                        <i class='fa fa-remove'></i>
                                                        @else
                                                        <i class='fa fa-check-circle'></i>
                                                        @endif
                                                    </a>
                                                    <a class='btn btn-primary btn-xs tooltips student-action-list' href="{{ URL::to('student/' . $value->id . '/edit') }}" title="Edit Student Information" data-placement="left">
                                                        <i class='fa fa-edit'></i>
                                                    </a>
                                                    <a class="tooltips student-action-list" href="{{ URL::to('student/cp/' . $value->id) }}@if(isset($param)){{'?'.$param }} @endif" data-original-title="Change Password" data-placement="left">
                                                        <span class="btn btn-success btn-xs"> 
                                                            <i class="fa fa-key"></i>
                                                        </span>
                                                    </a>
                                                    <button class="btn btn-danger btn-xs tooltips student-action-list" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                    @if($value->group_id == '5')
                                                    <a class="tooltips student-action-list" href="{{ URL::to('student/student_profile/' . $value->id) }}" title="Student Details Information" data-placement="left">
                                                        <span class="btn btn-xs grey-cascade"> 
                                                            <i class="fa fa-share"></i>
                                                        </span>
                                                    </a>
                                                    @endif
                                                    {{ Form::close() }}
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="13">{{trans('english.EMPTY_DATA')}}</td>
                                        </tr>
                                        @endif 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-sm-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                Showing {{($studentArr->getCurrentPage()-1)*$studentArr->getPerPage()+1}} to {{$studentArr->getCurrentPage()*$studentArr->getPerPage()}}
                                of  {{$studentArr->getTotal()}} records
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            {{ $studentArr->appends(Input::all())->links()}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).ready(function () {
        /* Show the part*/
        $("#studentIsspCourseId").change(function () {
            if ($(this).val() != '') {
                $.ajax({
                    url: "{{ URL::to('ajaxresponse/relate-part-list') }}",
                    type: "GET",
                    dataType: "json",
                    data: {course_id: $(this).val()},
                    success: function (res) {
                        $('select#studentPartId').empty();
                        $('select#studentPartId').append('<option value="">--Select Part--</option>');
                        $.each(res.parts, function (i, val) {
                            $('select#studentPartId').append('<option value="' + val.id + '">' + val.title + '</option>');
                        });
                    },
                    beforeSend: function () {
                        $('select#studentPartId').empty();
                        $('select#studentPartId').append('<option value="">Loading...</option>');
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        $('select#studentPartId').empty();
                        $('select#studentPartId').append('<option value="">--Select Part--</option>');
                        if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                        } else {
                            toastr.error("Error", "Something went wrong", {"closeButton": true});
                        }
                    }
                });
            } else {
                $('select#studentPartId').empty();
                $('select#studentPartId').append('<option value="">--Select Part--</option>');
            }

        });
    });
    $(document).on("submit", '#delete', function (e) {
        //This function use for sweetalert confirm message
        e.preventDefault();
        var form = this;
        swal({
            title: 'Are you sure you want to Delete?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                        form.submit();
                    } else {
                        //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                    }
                });
    });

</script>
@stop
