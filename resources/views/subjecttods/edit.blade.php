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
                        <i class="fa fa-gift"></i>{{trans('english.UPDATE_PHASE')}} </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {{ Form::model($phase, array('route' => array('phase.update', $phase->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'phaseUpdate')) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.SELECT_PART')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{Form::select('part_id', $partList, Request::get('part_id'), array('class' => 'form-control js-source-states', 'id' => 'phasePartId'))}}
                                        <span class="help-block text-danger">{{ $errors->first('part_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.PHASE_NAME')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('name', Request::get('name'), array('id'=> 'phaseTitle', 'class' => 'form-control', 'placeholder' => 'Enter Phase Name')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.PHASE_DETAILS')}} :</label>
                                    <div class="col-md-8">
                                        {{ Form::text('details', Request::get('details'), array('id'=> 'phaseDetails', 'class' => 'form-control', 'placeholder' => 'Enter Phase Details')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('details') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.ORDER')}} :</label>
                                    <div class="col-md-8">
                                        {{ Form::number('order', Request::get('order'), array('id'=> 'phaseOrder', 'min' => 0, 'class' => 'form-control', 'placeholder' => 'Enter Phase Order', 'required' => 'true')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('order') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.MULTI_PHASE_SUBJECT')}} :</label>
                                    <div class="col-md-8">
                                        <input name="multi_phase_subject" type="checkbox" class="make-switch" <?php echo (!empty($phase->multi_phase_subject)) ? 'checked' : ''; ?> data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" value="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{trans('english.STATUS')}} :</label>
                                    <div class="col-md-8">
                                        {{Form::select('status', array('active' => 'Active', 'inactive' => 'Inactive'), Request::get('status'), array('class' => 'form-control js-source-states-hidden-search', 'id' => 'phaseStatus'))}}
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
                                <a href="{{URL::to('phase')}}">
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

<script type="text/javascript">

</script>
@stop
