<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.EDIT_COURSE_INFORMATION')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updateCourseInformation')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.COURSE')}} </label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {{ Form::input('text','course',$courseInfoArr->course,['class'=>'form-control','id'=>'nameofCourse','required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.NAME')}} </label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-university"></i>
                </span>
                {{ Form::input('text','institution',$courseInfoArr->institution,['class'=>'form-control','id'=>'nameOfInstitution','required'=>'required']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.YEAR')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar-o"></i>
                </span>
                {{ Form::input('number','year',$courseInfoArr->year,['class'=>'form-control','maxlength' => '4','minlength'=>'4','id'=>'courseofYear','required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{{trans('english.GRADING')}}</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-bar-chart-o"></i>
                </span>
                {{ Form::input('text','grading',$courseInfoArr->grading,['class'=>'form-control','id'=>'Grading','required'=>'required']) }}
            </div>
        </div>
    </div>
</div><!-- modal body -->
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $courseInfoArr->id, array('id' => 'unitId')) }}
{{ Form::close() }}


