@if(!empty($employeeTypeList))
<div class="form-group">
    <label class="col-md-4 control-label" for="employeeType">@lang('label.SELECT_EMPLOYEE_TYPE') :</label>
    <div class="col-md-8">
        {{Form::select('employee_type', $employeeTypeList, Request::get('employee_type'), array('class' => 'form-control js-source-states', 'id' => 'employeeType'))}}
        <span class="help-block text-danger">{{ $errors->first('employee_type') }}</span>
    </div>
</div>
@endif
@if(!empty($supervisorList))
<div class="form-group">
    <label class="col-md-4 control-label" for="userId">@lang('label.SELECT_SUPERVISOR') :</label>
    <div class="col-md-8">
        {{Form::select('user_id', $supervisorList, Request::get('user_id'), array('class' => 'form-control js-source-states', 'id' => 'userId'))}}
        <span class="help-block text-danger">{{ $errors->first('user_id') }}</span>
    </div>
</div>
@endif