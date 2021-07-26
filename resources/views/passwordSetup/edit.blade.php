@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.UPDATE_PASSWORD_SETUP') </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::open(['route' => array('passwordSetup.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal']) }}
            {!! csrf_field() !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="maximumLength">@lang('label.MAX_LENGTH') :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::text('maximum_length',!empty($target->maximum_length)? $target->maximum_length : '', array('id'=> 'maximumLength', 'class' => 'form-control')) }}
                                    <span class="input-group-addon">@lang('label.CHARACTERS')</span>
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('maximum_length') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="minimumLength">@lang('label.MIN_LENGTH') :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::text('minimum_length',!empty($target->minimum_length)? $target->minimum_length : '', array('id'=> 'minimumLength', 'class' => 'form-control')) }}
                                    <span class="input-group-addon">@lang('label.CHARACTERS')</span>
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('minimum_length') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="specialCharacter">@lang('label.SPECIAL_CHARECTER') :<span class="required"> </span></label>
                            <div class="col-md-8">
                                {{ Form::select('special_character',$selectValue,!empty($target->special_character)? $target->special_character : '',array('id'=> 'specialCharacter', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('special_character') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="lowerCase">@lang('label.LOWER_CASE') :<span class="required"> </span></label>
                            <div class="col-md-8">
                                {{ Form::select('lower_case',$selectValue,!empty($target->lower_case)? $target->lower_case : '',array('id'=> 'lowerCase', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('lower_case') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="upperCase">@lang('label.UPPER_CASE') :<span class="required"> </span></label>
                            <div class="col-md-8">
                                {{ Form::select('upper_case',$selectValue,!empty($target->upper_case)? $target->upper_case : '',array('id'=> 'upperCase', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('upper_case') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="expeiredPassword">@lang('label.EXPEIRED_OF_PASSWORD') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::text('expeired_of_password', !empty($target->expeired_of_password)? $target->expeired_of_password : '', array('id'=> 'expeiredPassword', 'class' => 'form-control')) }}
                                    <span class="input-group-addon">@lang('label.DAYS')</span>
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('expeired_of_password') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="spaceNotAllowed">@lang('label.SPACE_NOT_ALLOWED') : <span class=""></span></label> 
                            <div class="col-md-8 margin-top-10">
                                <label>@lang('label.NOT_ALLOWED')</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green">@lang('label.SUBMIT')</button>
                        <a href="{{URL::to('passwordSetup')}}">
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
<script type="text/javascript">
    $(function () {
        $(function () {
            $(document).on("submit", '#branchUpdate', function (e) {
                //This function use for sweetalert confirm message
                e.preventDefault();
                var form = this;
                swal({
                    title: 'Are you sure you want to Submit?',
                    text: '',
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
    });
</script>
@stop
