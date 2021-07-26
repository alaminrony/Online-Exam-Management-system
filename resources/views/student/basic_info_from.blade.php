<!-- PERSONAL INFO TAB -->
<div class="tab-pane active">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Service ID Card No:<span class="required"> *</span> </label>
                {{ Form::text('id_card_no', $studentInfo->id_card_no, array('class' => 'form-control', 'placeholder' => 'Enter Id Card NO')) }}
                <span class="help-block text-danger"> {{ $errors->first('id_card_no') }}</span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Present Organization/Unit: <span class="required"> *</span> </label>
                {{ Form::text('present_organization', $studentInfo->present_organization, array('class' => 'form-control', 'placeholder' => 'Enter Present Organization/Unit')) }}
                <span class="help-block text-danger"> {{ $errors->first('present_organization') }}</span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Place of Birth: <span class="required"> *</span> </label>
                {{ Form::text('place_of_birth', $studentInfo->place_of_birth, array('class' => 'form-control', 'placeholder' => 'Enter Place of Birth')) }}
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Religion: <span class="required"> *</span> </label>
                {{Form::select('religion', $religionList, $studentInfo->religion, array('class' => 'form-control js-source-states', 'id' => 'studentReligion'))}}
                <span class="help-block text-danger">{{ $errors->first('religion') }}</span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Nationality: <span class="required"> *</span> </label>
                {{Form::select('nationality', $countriesList, $studentInfo->nationality, array('class' => 'form-control js-source-states', 'id' => 'studentNationality'))}}
                <span class="help-block text-danger">{{ $errors->first('nationality') }}</span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Gender: <span class="required"> *</span></label>
                {{Form::select('gender', array('' =>'--Select Gender--', 'Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'),$studentInfo->gender, array('class' => 'form-control js-source-states', 'id' => 'studentGender'))}}
                <span class="help-block text-danger">{{ $errors->first('gender') }}</span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Marital Status: <span class="required"> *</span> </label>
                {{Form::select('marital_status', array('' =>'--Select Marital Status--', 'single' => 'Single', 'married' => 'Married'), $studentInfo->marital_status, array('class' => 'form-control js-source-states', 'id' => 'studentMaritalStatus'))}}
                <span class="help-block text-danger">{{ $errors->first('marital_status') }}</span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Course in Bangladesh Air Force Academy: </label>
                {{ Form::text('course_in_bangladesh_aire_force_academy', $studentInfo->course_in_bangladesh_aire_force_academy, array('class' => 'form-control')) }}
                <span class="help-block text-danger"> {{ $errors->first('course_in_bangladesh_aire_force_academy') }}</span>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Father's Name: </label>
                {{ Form::text('fathers_name', $studentInfo->fathers_name, array('class' => 'form-control', 'placeholder' => 'Enter Father\'s Name')) }}
                <span class="help-block text-danger"> {{ $errors->first('fathers_name') }}</span>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Mother's Name: </label>
                {{ Form::text('mothers_name', $studentInfo->mothers_name, array('class' => 'form-control', 'placeholder' => 'Enter Mother\'s Name')) }}
                <span class="help-block text-danger"> {{ $errors->first('mothers_name') }}</span>
            </div>
        </div>
      
    </div>
    <div class="margiv-top-10">
        {{ Form::hidden('student_id', $studentInfo->id, array('id' => 'student_id')) }}
        {{ Form::submit('Save Changes', array('class' => 'btn green')) }}
        <a href="{{URL::to('student/account_setting/'.$userId)}}" class="btn default"> Cancel </a>
    </div>
</div>
<!-- END PERSONAL INFO TAB -->