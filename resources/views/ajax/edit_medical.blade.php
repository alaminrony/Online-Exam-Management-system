<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_MEDICAL_DETAILS_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateMedicalInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.MEDICAL_CATEGORY')}}<span class="required"> *</span> </label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {{ Form::input('text','category',$medicalInfoArr->category,['class'=>'form-control','id'=>'medical_category','required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.BLOOD_GROUP')}}</label>
        <div class="col-sm-8">
            <div class="mt-repeater-input" >
                {{--{{ Form::select('type',['brother' => 'Brother','sister' => 'Sister'],['class'=>'form-control'])}}--}}
                <select name="type"  id="bltype" class="form-control">
                    <option value='A+'> A+</option>
                    <option value='B+'> B+</option>
                    <option value='AB+'> AB+</option>
                    <option value='AB-'> AB-</option>
                    <option value='O+'> O+</option>
                </select>

            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.DATE_OF_BIRTH')}}</label>
        <div class="col-sm-8">
            <div class="input-group date date-picker" data-date="{{ date("Y-m-d")}}" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                <span class="input-group-addon">
                    <i class="fa fa-calendar-plus-o"></i>
                </span>
                {{ Form::input('text','date_of_birth',$medicalInfoArr->date_of_birth,['class'=>'form-control','id'=>'MedicaldateOfBirth','readonly']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.HEIGHT')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-child"></i>
                </span>
                {{ Form::input('text','height',$medicalInfoArr->height,['class'=>'form-control','id'=>'height']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.WEIGHT')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-child"></i>
                </span>
                {{ Form::input('text','weight',$medicalInfoArr->weight,['class'=>'form-control','id'=>'weight']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.OVER_WEIGHT')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-child"></i>
                </span>
                {{ Form::input('text','over_weight',$medicalInfoArr->over_weight,['class'=>'form-control','id'=>'OverWeight']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.ANY_DISEASE')}}</label>
        <div class="col-sm-8">
            <div class="mt-radio-inline">
                <label class="mt-radio">
                    <input type="radio" name="any_disease" id="AnyOneDisease" value="Yes" checked=""> {{trans('english.YES')}}
                    <span></span>
                </label>
                <label class="mt-radio">
                    <input type="radio" name="any_disease" id="AnyTwoDisease" value="No" checked="">{{trans('english.NO')}}
                    <span></span>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.DISEASE_DESCRIPTION')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                {{ Form::textarea('disease_description', $medicalInfoArr->disease_description, ['class' => 'form-control','size' => '50x5','id'=>'DescriptionoFDisease']) }}
            </div>
        </div>
    </div>
</div><!-- modal body -->
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $medicalInfoArr->id, array('id' => 'awardId')) }}
{{ Form::close() }}


