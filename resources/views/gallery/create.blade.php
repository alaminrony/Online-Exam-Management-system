@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-file-image-o"></i>@lang('label.CREATE_GALLERY') </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::open(array('role' => 'form', 'url' => 'gallery', 'files'=> true, 'class' => 'form-horizontal', 'id'=>'galleryCreate')) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.THUMB') :</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select Thumb </span>
                                        <span class="fileinput-exists"> Change </span>
                                        {{Form::file('thumb', array('id' => 'sortpicture'))}}
                                    </span>
                                    <span class="help-block">@lang('label.ONLY_SUPPORTED')</span>
                                    <span class="help-block text-danger">{{ $errors->first('thumb') }}</span>
                                </div>
                            </div>
                        </div>								

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.PHOTO') :</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">@lang('label.SELECT_PHOTO')  </span>
                                        <span class="fileinput-exists">@lang('label.CHANGE') </span>
                                        {{Form::file('photo', array('id' => 'sortpicture1'))}}
                                    </span>
                                    <span class="help-block text-danger">{{ $errors->first('photo') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.SHOW_HOME') :</label>

                            <div class="col-md-6">
                                <div class="md-checkbox">
                                    <input type="checkbox" name="home" class="checkboxes" id="obj_auto_selected" value="1">
                                    <label for="obj_auto_selected">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span></label>
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.HOME_ORDER') : <span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {{ Form::select('order',$orderList,Request::get('order'), array('id'=> 'home_order', 'class' => 'form-control js-source-states')) }}
                                <span class="help-block text-danger"> {{ $errors->first('order') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.STATUS') :</label>
                            <div class="col-md-5">
                                {{Form::select('status', array('1' => 'Active', '2' => 'Inactive'), Request::get('status'), array('class' => 'form-control js-source-states-hidden-search', 'id' => 'courseStatus'))}}
                                <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green testt">Submit</button>
                        <a href="{{URL::to('gallery')}}">
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

<script type="text/javascript">
    $(function () {
        $(document).on("submit", '#galleryCreate', function (event) {
            //This function use for sweetalert confirm message
            event.preventDefault();
            var form = this;
            swal({
                title: 'Are you sure you want to Submit?',
//            text: '<strong></strong>',
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

