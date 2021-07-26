@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.CREATE_NEW_BRANCH')
            </div>
            <div class="tools">

            </div>
        </div>
        <div class="portlet-body form">

            {{ Form::open(array('role' => 'form', 'url' => 'branch', 'class' => 'form-horizontal', 'id'=>'createBranch')) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="regionId">@lang('label.SELECT_REGION') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('region_id', $regionList, Request::get('region_id'), array('class' => 'form-control js-source-states', 'id' => 'regionId'))}}
                                <span class="help-block text-danger">{{ $errors->first('region_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="clusterId">@lang('label.SELECT_CLUSTER') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('cluster_id', $clusterList, null, array('class' => 'form-control js-source-states', 'id' => 'clusterId'))}}
                                <span class="help-block text-danger">{{ $errors->first('cluster_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="name">@lang('label.NAME') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('name', Request::get('name'), array('id'=> 'name', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label class="col-md-4 control-label" for="solId">@lang('label.SOL_ID') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('sol_id', Request::get('sol_id'), array('id'=> 'solId', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('sol_id') }}</span>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-md-4 control-label" for="Location">@lang('label.LOCATION') :<span class="required"></span></label>
                            <div class="col-md-8">
                                {{ Form::text('location', Request::get('location'), array('id'=> 'Location', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('location') }}</span>
                            </div>
                        </div>


                       
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="order">@lang('label.ORDER') : <span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::select('order',$orderList,Request::get('order'), array('id'=> 'order', 'class' => 'form-control js-source-states')) }}
                                <span class="help-block text-danger"> {{ $errors->first('order') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.STATUS')  : </label>
                            <div class="col-md-8">
                                {{Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), Request::get('status'), array('class' => 'form-control'))}}
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
                        <a href="{{URL::to('branch')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">@lang('label.CANCEL')</button> 
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {

        $(document).on('change', '#regionId', function (e) {
            var regionId = $('#regionId').val();
            $.ajax({
                url: "{{ URL::to('branch/getCluster')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    region_id: regionId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#clusterId').html(res.html);
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        $(document).on("submit", '#createBranch', function (e) {
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

