@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>@lang('label.CREATE_SCROLL_MESSAGE') </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::open(array('role' => 'form', 'url' => 'scrollmessage', 'class' => 'form-horizontal', 'id'=>'message')) }}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label align-middle">@lang('label.SELECT_SCOPE') : <span class="required"> *</span></label>
                            <div class="col-md-5">
                                <div class="md-checkbox margin-bottom-10">
                                    <input type="checkbox" name="scope[]" class="checkboxes" id="home" value="3">
                                    <label for="home">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> @lang('label.HOME_PAGE') 
                                    </label>
                                </div>
                                <div class="md-checkbox margin-bottom-10">
                                    <input type="checkbox" name="scope[]" class="checkboxes" id="issp" value="1">
                                    <label for="issp">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>@lang('label.ISSP_DASHBOARD') 
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.MESSAGE') : <span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('message', null, ['class' => 'form-control input-large']) }}
                                <span class="help-block text-danger">{{ $errors->first('message') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.PUBLISH') : <span class="required"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group input-large" data-date="2017-01-01" data-date-format="yyyy-mm-dd">
                                    {{ Form::text('from_date', Request::get('from_date'), array('id'=> 'courseFromDate', 'class' => 'form-control', 'placeholder' => 'Enter From Date', 'readonly' => true)) }}
                                    <span class="input-group-addon"> to </span>
                                    {{ Form::text('to_date', Request::get('to_date'), array('id'=> 'courseToDate', 'class' => 'form-control', 'placeholder' => 'Enter To Date', 'readonly' => true)) }}
                                </div>
                                <span class="help-block text-danger">{{ $errors->first('from_date') }}</span>
                                <span class="help-block text-danger">{{ $errors->first('to_date') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.STATUS') : <span class="required"> *</span></label>
                            <div class="col-md-5">
                                {{Form::select('status', $statusList, Request::get('status'), array('class' => 'form-control', 'id' => 'courseStatus'))}}
                                <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green">@lang('label.SUBMIT')</button>
                        <a href="{{URL::to('scrollmessage')}}">
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
       $('#courseFromDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
       $('#courseToDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $(document).on("submit", '#message', function (e) {
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
</script>
@stop
