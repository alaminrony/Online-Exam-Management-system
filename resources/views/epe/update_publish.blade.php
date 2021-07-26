<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('label.UPDATE_RESULT_SUBMISSION_DEADLINE_N_RESULT_PUBLISH_DATE_TIME')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'updatedPublishResult')) }}
<div class="modal-body">
    <div class="form-group">
        <label class="col-md-4 control-label">{{trans('label.RESULT_SUBMISSION_DEADLINE')}} :<span class="required"> *</span></label>
        <div class="col-md-7">
            <div class="input-group date form_datetime">
                {{ Form::text('submission_deadline', ($target) ? $target->submission_deadline : null, array('id'=> 'taeResultSubmissionDateline', 'class' => 'form-control', 'placeholder' => 'Enter Result Submission Dateline', 'readonly' => true, 'size' => '16')) }}
                <span class="input-group-btn">
                    <button class="btn default date-set" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-md-1">
            <span class="text-danger date-remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('taeResultSubmissionDateline');" title="Remove Date" remove="taeResultSubmissionDateline"></i></span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">{{trans('label.RESULT_PUBLISH_DATE_TIME')}} :<span class="required"> *</span></label>
        <div class="col-md-7">
            <div class="input-group date form_datetime">
                {{ Form::text('result_publish', ($target) ? $target->result_publish : null, array('id'=> 'taeResultPublishedDeadline', 'class' => 'form-control', 'placeholder' => 'Enter Result Publish Date Time', 'size' => '16', 'readonly' => true,)) }}
                <span class="input-group-btn">
                    <button class="btn default date-set" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-md-1">
            <span class="text-danger date-remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('taeResultPublishedDeadline');" title="Remove Date" remove="taeResultPublishedDeadline"></i></span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('label.CANCEL')}}</button>
    <button type="submit" class="btn green"><i class="fa fa-check"></i> {{trans('label.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $target->id, array('id' => 'epeId')) }}
{{ Form::close() }}
<script>
    function remove_date(e) {
        var id = e;
        $("#" + id).val('');
    }
</script>
<style>
    .date-remove{
        margin-left: -26px;
    }
</style>
