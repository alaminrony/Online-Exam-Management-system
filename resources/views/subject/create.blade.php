@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i>@lang('label.CREATE_NEW_SUBJECT')
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-name="" name=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-name="" name=""> </a>
                <a href="javascript:;" class="reload" data-original-name="" name=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::open(array('role' => 'form', 'url' => 'subject', 'class' => 'form-horizontal', 'id'=>'createSubject')) }}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">@lang('label.SUBJECT_NAME') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('title', Request::get('title'), array('id'=> 'title', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('title') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="code">@lang('label.SUBJECT_CODE') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('code', Request::get('code'), array('id'=> 'code', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('code') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="details">@lang('label.SUBJECT_DETAILS') :</label>
                            <div class="col-md-8">
                                {{ Form::text('details', Request::get('details'), array('id'=> 'details', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('details') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="order">@lang('label.ORDER') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {{ Form::select('order',$orderList,end($orderList),array('id'=> 'order', 'min' => 0, 'class' => 'form-control js-source-states'))}}
                                <span class="help-block text-danger"> {{ $errors->first('order') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {{Form::select('status', array('1' => 'Active', '0' => 'Inactive'), Request::get('status'), array('class' => 'form-control', 'id' => 'status'))}}
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
                        <a href="{{URL::to('subject')}}">
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
        $(document).on("submit", '#createSubject', function (e) {
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

