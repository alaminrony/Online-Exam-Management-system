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
                        <i class="fa fa-gift"></i>{{trans('english.UPDATE_GRADE')}} </div>
                    <div class="tools">
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {{ Form::model($target, array('route' => array('grades.update', $target->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'gradesUpdate')) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-7">
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.GRADE_LETTER')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('letter', Request::get('letter'), array('id'=> 'letter', 'class' => 'form-control', 'placeholder' => 'Enter Exercise Title', 'required' => 'true')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('letter') }}</span>
                                    </div>
                                </div>                               
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.START_RANGE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('start_range', Request::get('start_range'), array('id'=> 'start_range', 'class' => 'form-control integer-decimal-only', 'placeholder' => 'Enter Exercise Order', 'required' => 'true')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('start_range') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.END_RANGE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('end_range', Request::get('end_range'), array('id'=> 'end_range', 'class' => 'form-control integer-decimal-only', 'placeholder' => 'Enter Exercise Order', 'required' => 'true')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('end_range') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="info">{{trans('english.INFO')}} : </label>
                                    <div class="col-md-8">
                                        {{ Form::textarea('info', Request::get('info'), array('id'=> 'info', 'rows' => 5, 'class' => 'form-control')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('info') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('english.STATUS') }} : </label>
                                    <div class="col-md-8">
                                        {{Form::select('status', array('1' => trans('english.ACTIVE'), '0' => trans('english.INACTIVE')), Request::get('status'), array('class' => 'form-control js-source-states-hidden-search'))}}
                                        <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle green">{{ trans('english.SUBMIT') }}</button>
                                <a href="{{URL::to('grades')}}">
                                    <button type="button" class="btn btn-circle grey-salsa btn-outline">{{ trans('english.CANCEL') }}</button> 
                                </a>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->

<script type="text/javascript">
	$(document).on("submit", '#gradesUpdate', function (e) {
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
