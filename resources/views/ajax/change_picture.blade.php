<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.UPDATE_USER_PHOTO')}}</h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => 'dashboard/change_picture', 'files'=> true, 'class' => 'form-horizontal', 'id'=>'changsdePicture')) }}
<div class="modal-body">


    <div class="form-group">
        <div class="col-md-10">
            <div class="form-group last">
                <label class="control-label col-md-3"> Photo: </label>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="{{URL::to('/')}}/public/img/no-image.png" alt=""> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                        <div>
                            <span class="btn default btn-file">
                                <span class="fileinput-new"> Select image </span>
                                <span class="fileinput-exists"> Change </span>
                                {{Form::file('photo', array('id' => 'sortpicture'))}}
                            </span>
                            <span class="help-block text-danger">{{ $errors->first('photo') }}</span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> {{trans('english.REMOVE')}} </a>
                        </div>
                    </div>
                    <div class="clearfix margin-top-10">
                        <span class="label label-danger">{{trans('english.NOTE')}}</span> {{trans('english.USER_AND_STUDENT_IMAGE_FOR_IMAGE_DESCRIPTION')}}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- modal body -->
<div class="modal-footer">
    <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    <button type="submit" class="btn green" id="updateChildrenInformation"><i class="fa fa-check"></i> {{trans('english.UPDATE')}}</button>
</div>
{{ Form::hidden('id', $userInfo->id, array('id' => 'userId')) }}
{{ Form::close() }}


