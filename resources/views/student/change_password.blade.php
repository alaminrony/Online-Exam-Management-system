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
                        <i class="fa fa-gift"></i>{{trans('english.CHANGE_PASSWORD')}} 
                    </div>
                    <div class="tools">
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-2">
                            <table class="table">
                                <tr>
                                    <th rowspan="5">

                                        @if(isset($studentInfo->photo))
                                        <img width="120" class="img-circle" height="120" src="{{URL::to('/')}}/public/uploads/user/{{$studentInfo->photo}}" alt="{{ $studentInfo->first_name.' '.$studentInfo->last_name }}">
                                        @else
                                        <img width="120" class="img-circle" height="120" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $studentInfo->first_name.' '.$studentInfo->last_name }}">
                                        @endif

                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <address class="text-left">
                                            <strong>{{trans('english.NAME')}}: </strong>{{ $studentInfo->first_name }} {{ $studentInfo->last_name }}<br />
                                            <strong>{{trans('english.RANK')}}: </strong>{{$studentInfo->rank->title}}<br />
                                            <strong>{{trans('english.APPOINTMENT')}}: </strong>{{$studentInfo->appointment->title}}<br />
                                            <strong>{{trans('english.BRANCH')}}: </strong>{{!empty($studentInfo->branch->name) ? $studentInfo->branch->name: '';}}<br />
                                        </address>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- BEGIN FORM-->
                    {{ Form::open(array('role' => 'form', 'url' => 'student/pup', 'files'=> true, 'class' => 'form-horizontal','id'=>'pup')) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.PASSWORD')}} :</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            {{ Form::password('password', array('id'=> 'userPassword', 'class' => 'form-control', 'placeholder' => 'Password')) }}
                                            <span class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        </div>
                                        <div class="help-block">{{ trans('english.COMPLEX_PASSWORD_INSTRUCTION') }}</div>
                                        <span class="help-block text-danger"> {{ $errors->first('password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.CONFIRM_PASSWORD')}} :</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            {{ Form::password('password_confirmation', array('id'=> 'userConfirmPassword', 'class' => 'form-control', 'placeholder' => 'Confirm Password')) }}
                                            <span class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        </div>
                                        <span class="help-block text-danger"> {{ $errors->first('password_confirmation') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle green">Submit</button>
                                <a href="javascript:history.back()">
                                    <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button> 
                                </a>
                            </div>
                        </div>
                    </div>
                    {{ Form::hidden('user_id', $user_id) }}
                    {{ Form::hidden('next_url', $next_url) }}
                    {{ Form::close() }}
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).on("submit", '#pup', function (e) {
        //This function use for sweetalert confirm message
        e.preventDefault();
        var form = this;
        swal({
            title: 'Are you sure you want to Submit?',
            text: '<strong></strong>',
            type: 'warning',
            html: true,
            allowOutsideClick: true,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-info',
            cancelButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, I agree',
            cancelButtonText: 'No, I do not agree',
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
