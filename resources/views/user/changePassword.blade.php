@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>@lang('label.CHANGE_PASSWORD') 
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
                                @if(isset($userInfo->photo))
                                <img width="120" class="img-circle" height="120" src="{{URL::to('/')}}/public/uploads/user/{{$userInfo->photo}}" alt="{{ $userInfo->first_name.' '.$userInfo->last_name }}">
                                @else
                                <img width="120" class="img-circle" height="120" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $userInfo->first_name.' '.$userInfo->last_name }}">
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <address class="text-left">
                                    <strong>@lang('label.NAME'): </strong>{{ $userInfo->first_name .' '.$userInfo->last_name }}<br />
                                    <strong>@lang('label.RANK'): </strong>{{$userInfo->rank_title}}<br />
                                    <strong>@lang('label.APPOINTMENT'): </strong>{{$userInfo->designation->title}}<br />
                                    <strong>@lang('label.BRANCH'): </strong>{{!empty($userInfo->branch->name) ? $userInfo->branch->name: ''}}<br />
                                </address>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- BEGIN FORM-->
            {{ Form::open(array('group' => 'form', 'url' => 'changePassword', 'class' => 'form-horizontal', 'id'=>'pup')) }}
            {{ Form::hidden('filter', Helper::queryPageStr($qpArr)) }}
            {{ Form::hidden('id', $id) }}
            {{ Form::hidden('next_url', $nextUrl) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.PASSWORD') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::password('password', array('id'=> 'userPassword', 'class' => 'form-control')) }}
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                                  <span class="help-block">
                                    Password must be a combination of at least <?php  if($passwordSetup->upper_case =='1') {echo "one upper case ,";}?>  <?php  if($passwordSetup->lower_case =='1') {echo "one lower case ,";}?>
                                     <?php  if($passwordSetup->special_character =='1') {echo "one special character ,";}?> <?php if($passwordSetup->space_not_allowed == '1'){echo "White space not allowed ,";}?> minimum {{$passwordSetup->minimum_length}} & maximum {{$passwordSetup->maximum_length}} character.
                                 </span>
                                <span class="help-block text-danger"> {{ $errors->first('password') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.CONFIRM_PASSWORD') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::password('password_confirmation', array('id'=> 'userConfirmPassword', 'class' => 'form-control')) }}
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
                        <button type="submit" class="btn btn-circle green">@lang('label.SUBMIT')</button>
                        <a href="javascript:history.back()">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">@lang('label.CANCEL')</button> 
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            <!-- END FORM-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("submit", '#pup', function (e) {
            //This function use for sweetalert confirm message
            e.preventDefault();
            var form = this;
            swal({
                title: 'Are you sure you want to Submit?',
//                text: '<strong></strong>',
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
    });
</script>
@stop
