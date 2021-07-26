<div class="modal-header portlet-title" style="background-color: #32c5d2; color: #fff;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><strong>{{($status == '2') ? trans('english.CC_TAKEN_FOR') : trans('english.ABSENT_FOR')}} {{$studentInfoObj->student_name}} ({{$studentInfoObj->registration_no}})</strong></h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'specialPermissionForm')) }}
<div class="modal-body">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-md-3 control-label" for="specialPermissionRemarks">{{trans('english.REMARKS')}}:<span class="required">*</span></label>
            <div class="col-md-9">
                <textarea class="form-control" rows="3" name="remarks" id="specialPermissionRemarks"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"> </div>
<div class="modal-footer" style="background-color: #f5f5f5;border-top: 1px solid #e7ecf1;">
    <button type="button" class="btn btn-circle green" id="saveTaeSpecialPermission"><i class="fa fa-check"></i> {{trans('english.SUBMIT')}}</button>
    <button type="button" class="btn grey-salsa btn-circle" data-dismiss="modal"><i class="fa fa-close"></i> {{trans('english.CANCEL')}}</button>
</div>
{{ Form::hidden('status', $status, array('id' => 'specialPermissionStatus')) }}
{{ Form::hidden('student_id', $studentInfoObj->id, array('id' => 'studentId')) }}
{{ Form::hidden('tae_id', $taeInfoObj->id, array('id' => 'taeId')) }}
{{ Form::close() }}


