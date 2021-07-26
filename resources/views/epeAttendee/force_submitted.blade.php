<div class="modal-header bg-green bg-font-green">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title bold">{{trans('label.FORCE_SUBMITTED')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id'=>'formData')) }}
{{ Form::hidden('epe_mark_id', $empMarkId) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 form-group">
            <div class="text-center bold">{{trans('label.EPE_SUBJECTIVE_SUBMITTED_FORCEFULLY')}}</div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-3 control-label">{{trans('label.REMARKS')}} :<span class="required"> *</span></label>
                <div class="col-md-9">
                    {{ Form::textarea('remarks', null, array('id'=> 'remarksId', 'class' => 'form-control', 'required' => 'true','size'=>'30x4')) }}
                    <span class="help-block text-danger"> {{ $errors->first('remarks') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-center text-danger bold">{{trans('label.ARE_YOU_SURE_SUBMIT')}}</div>
        </div>
    </div>
</div>
<div class="modal-footer bg-default">
    <div class="text-center">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">{{trans('label.CANCEL')}}</button>
        <button type="button" id="saveForceSubmit" class="btn green">{{trans('label.CONFIRM')}}</button>     
    </div>

</div>
{{ Form::close() }}
<!--end form-->