<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_PUNISHMENT_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updatePunishmentInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.PUNISHMENT')}}<span class="required"> *</span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                {{ Form::input('text','punishment',$punishmentInfoArr->punishment,['class'=>'form-control','id'=>'nameOfPunishment','required'=>'required']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.YEAR')}} <span class="required"> * </span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                {{ Form::input('text','year',$punishmentInfoArr->year,['class'=>'form-control','id'=>'PunishmentOfyear','required'=>'required']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.REASON')}} </label>
        <div class="col-sm-8">
            <div class="input-group">
                {{ Form::textarea('reason', $punishmentInfoArr->reason, ['class' => 'form-control','size' => '50x5','id'=>'Punishmentreason']) }}
            </div>
        </div>
    </div>
</div><!-- modal body -->
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $punishmentInfoArr->id, array('id' => 'awardId')) }}
{{ Form::close() }}


