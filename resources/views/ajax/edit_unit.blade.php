<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_RETURN_TO_UNIT_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateUnitInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.TITLE')}} <span class="required"> *</span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-paper-plane"></i>
                </span>
                {{ Form::input('text','title',$unitInfoArr->title,['class'=>'form-control','id'=>'nameOfUnit','required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.DESCRIPTION')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                {{ Form::textarea('description', $unitInfoArr->description, ['class' => 'form-control','size' => '50x5','id'=>'unitDescription']) }}
            </div>
        </div>
    </div>
</div><!-- modal body -->
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $unitInfoArr->id, array('id' => 'unitId')) }}
{{ Form::close() }}


