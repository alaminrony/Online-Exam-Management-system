@if($typeTaeEpeId == 1)
<div class="col-md-offset-2 col-md-7">
    <div class="form-group">
        <label class="col-md-3 control-label" for="specialPermissionTypeTaeEpe">{{trans('english.SELECT_TAE')}}:<span class="required">*</span></label>
        <div class="col-md-8">
            {{Form::select('tae_id', $taeList, Request::get('tae_id'), array('class' => 'form-control js-source-states show-students', 'id' => 'specialPermissionTaeId'))}}
        </div>
    </div>
</div>
@elseif($typeTaeEpeId == 2)

<div class="col-md-offset-2 col-md-7">
    <div class="form-group">
        <label class="col-md-3 control-label" for="specialPermissionType">{{trans('english.SELECT_EPE')}}:<span class="required">*</span></label>
        <div class="col-md-8">
            {{Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states show-students', 'id' => 'specialPermissionEpeId'))}}
        </div>
    </div>
</div>
@endif