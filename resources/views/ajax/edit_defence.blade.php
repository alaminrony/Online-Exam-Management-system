<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_RELATIVE_IN_DEFENCE_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateDefenceInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.RANK')}} <span class="required"> * </span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                {{ Form::input('text','rank',$defenceInfoArr->rank,['class'=>'form-control','id'=>'RankTitle','required'=>'required']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.NAME')}}<span class="required"> * </span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                {{ Form::input('text','name',$defenceInfoArr->name,['class'=>'form-control','id'=>'nameOfRank','required'=>'required']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.LOCATION_OF_SERVICE')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-map-marker"></i>
                </span>
                {{ Form::input('text','service_location',$defenceInfoArr->service_location,['class'=>'form-control','id'=>'serviceofLocation']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.RELATION_WITH_STUDENT')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                {{ Form::input('text','relation_student',$defenceInfoArr->relation_student,['class'=>'form-control','id'=>'relationwithStudent']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.REMARK')}} </label>
        <div class="col-sm-8">
            <div class="input-group">
                {{ Form::textarea('remark', $defenceInfoArr->remark, ['class' => 'form-control','size' => '50x5','id'=>'remarkofRank']) }}
            </div>
        </div>
    </div>
</div><!-- modal body -->
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $defenceInfoArr->id, array('id' => 'awardId')) }}
{{ Form::close() }}


