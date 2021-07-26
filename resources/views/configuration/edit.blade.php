@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>@lang('label.UPDATE_CONFIGURATION') </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::model($configuration, array('route' => array('configuration.update', $configuration->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'configurationUpdate')) }}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="adminEmail">@lang('label.ADMIN_EMAIL') :</label>
                            <div class="col-md-4">
                                {{ Form::email('admin_email', Request::get('admin_email'), array('id'=> 'adminEmail', 'class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="aboutUs">@lang('label.ABOUT_US') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::textarea('about_us', null, ['class' => 'form-control summernote_1','size' => '50x5','id'=>'aboutUs']) }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="history">@lang('label.HISTORY') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::textarea('history', null, ['class' => 'form-control summernote_1','size' => '50x5','id'=>'history']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green">@lang('label.SUBMIT')</button>
                        <a href="{{URL::to('configuration')}}">
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
        $('.summernote_1').summernote();
        $(document).on("submit", '#configurationUpdate', function (e) {
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
