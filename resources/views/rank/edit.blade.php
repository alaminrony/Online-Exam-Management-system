@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-badge"></i>@lang('label.UPDATE_RANK') </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::model($rank, array('route' => array('rank.update', $rank->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'rankUpdate')) }}
            {{csrf_field()}}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!} 
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">@lang('label.TITLE') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('title', Request::get('title'), array('id'=> 'title', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('title') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="shortName">@lang('label.SHORT_NAME') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('short_name', Request::get('short_name'), array('id'=> 'shortName', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('short_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="status">@lang('label.STATUS') : </label>
                            <div class="col-md-8">
                                {{Form::select('status', array('active' => __('label.ACTIVE'), 'inactive' => __('label.INACTIVE')), Request::get('status'), array('class' => 'form-control', 'id' => 'status'))}}
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
                        <a href="{{URL::to('rank')}}">
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
        $(document).on("submit", '#rankUpdate', function (e) {
            //This function use for sweetalert confirm message
            e.preventDefault();
            var form = this;
            swal({
                title: 'Are you sure you want to Submit?',
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
