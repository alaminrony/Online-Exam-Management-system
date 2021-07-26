<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_CHILDREN_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateChildrenInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.NAME_OF_CHILD')}}: <span class="required"> *</span></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {{ Form::text('name', $childrenInfoArr->name, array('id'=> 'nameOfChild', 'class' => 'form-control')) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.DATE_OF_BIRTH')}}: <span class="required"> *</span></label>
        <div class="col-sm-8">
            <div class="input-group date date-picker" data-date="{{ date("Y-m-d")}}" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                <span class="input-group-addon">
                    <i class="fa fa-calendar-plus-o"></i>
                </span>
                {{ Form::text('birthday', $childrenInfoArr->birthday, array('id'=> 'dateOfBirth', 'class' => 'form-control', 'readonly' => true)) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.EDUCATION_LEVEL')}}:</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-graduation-cap"></i>
                </span>
                {{ Form::text('education_level', $childrenInfoArr->education_level, array('id'=> 'educationLevel', 'class' => 'form-control')) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.GENDER')}}:</label>
        <div class="col-sm-8">
            <div class="mt-radio-inline">
                <label class="mt-radio">
                    <input type="radio" name="gender" id="optionsMale" value="Male" {{($childrenInfoArr->gender == 'Male') ? 'checked="checked"' : ''}}> Male
                    <span></span>
                </label>
                <label class="mt-radio">
                    <input type="radio" name="gender" id="optionsFemale" value="Female" {{($childrenInfoArr->gender == 'Female') ? 'checked="checked"' : '';}}> Female
                    <span></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">{{trans('english.ORDER')}} :</label>
        <div class="col-md-8">
            {{ Form::number('order', $childrenInfoArr->order, array('id'=> 'childrenOrder', 'min' => 0, 'class' => 'form-control', 'placeholder' => 'Enter Children Order')) }}
            <span class="help-block text-danger"> {{ $errors->first('order') }}</span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $childrenInfoArr->id, array('id' => 'cheildrenId')) }}
{{ Form::close() }}


