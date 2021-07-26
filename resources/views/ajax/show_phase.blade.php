 <div class=" col-md-12">

            <div class="form-group" >
                <label class="col-md-4 control-label">{{trans('english.SELECT_PART')}} :<span class="required"> *</span></label>
                <div class="col-md-8" >
                    {{Form::select('part_id', $relatedPartArr, Input::old('part_id'), array('class' => 'form-control dopdownselect', 'id' => 'relatePartWithCourse'))}}
                    <span class="help-block text-danger">{{ $errors->first('part_id') }}</span>
                </div>
            </div>
</div>

