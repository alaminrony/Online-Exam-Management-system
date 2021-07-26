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
                        <i class="fa fa-gift"></i>{{trans('english.UPDATE_NOTICE')}} </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {{ Form::model($notice, array('route' => array('notice.update', $notice->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'noticeUpdate','enctype'=>'multipart/form-data')) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="title">{{trans('english.SELECT_COURSE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-4">
                                        {{Form::select('course_id', $courseArr, null, array('class' => 'form-control js-source-states', 'id' => 'course_id'))}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('english.TITLE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-6">
                                        {{ Form::text('title', Request::get('title'), array('id'=> 'noticeTitle', 'class' => 'form-control', 'placeholder' => 'Enter Notice Title')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('title') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('english.SHORT_INFO')}} :</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            {{ Form::textarea('short_info', null, ['class' => 'form-control ','size' => '65x5','id'=>'unitShortInfon']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('english.PUBLISH_DATE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-3">
                                        <div class="input-group date date-picker" data-date="{{ date("Y-m-d")}}" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar-plus-o"></i>
                                            </span>
                                            {{ Form::text('published_date',Request::get('published_date'), array('id'=> 'publishedDate', 'class' => 'form-control ','readonly' => true)) }}
                                        </div>
                                        <span class="help-block text-danger"> {{ $errors->first('published_date') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('english.CLOSING_DATE_ONLY')}} :<span class="required"> *</span></label>
                                    <div class="col-md-3">
                                        <div class="input-group date date-picker" data-date="{{ date("Y-m-d")}}" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar-plus-o"></i>
                                            </span>
                                            {{ Form::text('closing_date',Request::get('closing_date'), array('id'=> 'closingDate', 'class' => 'form-control ','readonly' => true)) }}
                                        </div>
                                        <span class="help-block text-danger"> {{ $errors->first('closing_date') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('english.ATTACHMENT')}} :</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            {{ Form::file('fileInfo', null,array('id'=> 'uploadedFile', 'class' => 'form-control ')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{trans('english.DESCRIPTION')}} :</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            {{ Form::textarea('description', null, ['class' => 'form-control summernote_1','size' => '50x5', 'id'=>'summernote_1']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">{{ trans('english.STATUS') }} : </label>
                                    <div class="col-md-3">
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
                                <button type="submit" class="btn btn-circle green">Submit</button>
                                <a href="{{URL::to('notice')}}">
                                    <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button> 
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
{{ HTML::style('public/assets/global/plugins/bootstrap-summernote/summernote.css'); }}
{{ HTML::script('public/assets/pages/scripts/components-editors.min.js') }}
{{ HTML::script('public/assets/global/plugins/bootstrap-summernote/summernote.min.js') }}

<script type="text/javascript">
	$(document).on("submit", '#noticeUpdate', function (e) {
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
