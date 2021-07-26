<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_FAMILY_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateFamilyInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('english.NAME')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {{ Form::input('text', 'name',$familyInfoArr->name,['class'=>'form-control','id'=>'nameOfperson','required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('english.TYPE')}}</label>
        <div class="col-sm-8">
            <div class="mt-repeater-input" >
                {{--{{ Form::select('type',['brother' => 'Brother','sister' => 'Sister'],['class'=>'form-control'])}}--}}
                <select name="type"  id="rltype" class="form-control">
                    <option value='brother' <?php echo ($familyInfoArr->type == 'brother') ? 'selected' : ''; ?>>{{trans('english.BROTHER')}}</option>
                    <option value='sister' <?php echo ($familyInfoArr->type == 'sister') ? 'selected' : ''; ?>>{{trans('english.SISTER')}}</option>
                </select>

            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('english.AGE')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-child"></i>
                </span>
                {{ Form::input('text', 'age',$familyInfoArr->age,['class'=>'form-control','id'=>'age','required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('english.OCCUPATION')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-meh-o"></i>
                </span>
                {{ Form::input('text', 'occupation',$familyInfoArr->occupation,['class'=>'form-control','id'=>'occupation']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('english.GENDER')}}</label>
        <div class="col-sm-8">
            <div class="mt-radio-inline">
                <label class="mt-radio">
                    <input type="radio" name="gender" id="optionsMale" value="Male" checked="<?php echo ($familyInfoArr->gender == 'Male') ? 'checked' : ''; ?>"> {{trans('english.MALE')}}
                    <span></span>
                </label>
                <label class="mt-radio">
                    <input type="radio" name="gender" id="optionsFemale" value="Female" checked="<?php echo ($familyInfoArr->gender == 'Female') ? 'checked' : ''; ?>"> {{trans('english.FEMALE')}}
                    <span></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group last">
        <label class="col-sm-2 control-label">{{trans('english.ORDER')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                <input type="text" id="orderFamilyInformation" name="order" class="form-control" value="{{$familyInfoArr->order}}"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('english.ADDRESS')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                {{ Form::textarea('address', $familyInfoArr->address, ['class' => 'form-control','size' => '50x5','id'=>'address']) }}

            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $familyInfoArr->id, array('id' => 'familyInfoId')) }}
{{ Form::close() }}


