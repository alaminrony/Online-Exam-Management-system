<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_SERVICE_RECORD_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateServiceInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.UNIT_ORGANIZATION')}}<span class="required"> * </span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                {{ Form::input('text','organization',$serviceInfoArr->organization,['class'=>'form-control','id'=>'nameOfOrganization']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.APPOINTMENT_HELD')}}<span class="required"> * </span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {{ Form::input('text','appointment_held',$serviceInfoArr->appointment_held,['class'=>'form-control','id'=>'appointment_held','required' => 'required']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.YEAR')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                {{ Form::input('text','year',$serviceInfoArr->year,['class'=>'form-control','id'=>'ServiceYear']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $serviceInfoArr->id, array('id' => 'cheildrenId')) }}
{{ Form::close() }}


