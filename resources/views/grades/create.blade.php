@extends('layouts.default.master')
@section('data_count')
<div class="page-content">

    @include('layouts.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-question"></i>{{trans('english.CREATE_NEW_GRADE')}} 
                    </div>
                    <div class="tools">

                    </div>
                </div>
                <div class="portlet-body form">

                    {{ Form::open(array('role' => 'form', 'url' => 'grades', 'class' => 'form-horizontal', 'id'=>'gradesCreate')) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-7">
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="letter">{{trans('english.GRADE_LETTER')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('letter', Request::get('letter'), array('id'=> 'letter', 'class' => 'form-control', 'required' => 'true')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('letter') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="start_range">{{trans('english.START_RANGE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('start_range', Request::get('start_range'), array('id'=> 'start_range', 'class' => 'form-control integer-decimal-only', 'required' => 'true')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('start_range') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="end_range">{{trans('english.END_RANGE')}} :<span class="required"> *</span> </label>
                                    <div class="col-md-8">
                                        {{ Form::text('end_range', Request::get('end_range'), array('id'=> 'end_range', 'class' => 'form-control integer-decimal-only', 'required' => 'true')) }}
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

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).on("submit", '#gradesCreate', function (e) {
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

