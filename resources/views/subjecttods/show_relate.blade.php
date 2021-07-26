<table id="user" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th width="10%">&nbsp;</th>
            <th width="25%">{{trans('english.SUBJECT')}}</th>
            <th width="25%">{{trans('english.PHASE')}}</th>
            <th width="40%">{{trans('english.DS')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($targetArr))
        @foreach($targetArr as $item)
        <tr>
            <td>
                <?php
                    $subjectId = $item->id;
                    //Get exists data for assign subject 
                    $targetArr = array_filter($previousData, function($elem) use($subjectId){
                        return $elem['subject_id'] == $subjectId;
                    });
                    
                    $existsDataArr = reset($targetArr);
                    
                    if(!empty($existsDataArr)){
                        $checked = 'checked="checked"';
                        $selectedDs = $existsDataArr['user_id'];
                        $disabled = '';
                    }else{
                        $checked = '';
                        $selectedDs = null;
                        $disabled = 'disabled';
                    }
                ?>
                <div class="md-checkbox">
                    <input type="checkbox" name="subject_id[{{$item->id}}]" id="subject-id-{{$item->id}}" class="checkboxes subjectId" value="{{$item->id}}" {{$checked}}>
                    <label for="subject-id-{{$item->id}}">
                        <span class="inc"></span>
                        <span class="check"></span>
                        <span class="box"></span> </label>
                </div>
            </td>
            <td>
                {{ $item->subject_name}}
            </td>
            <td>
                <input type="hidden" name="phase_id[{{$item->id}}]" value="{{$item->phase_id}}" >
                {{ $item->phase_name}}
            </td>
            <td>
                <div class="col-md-12">
                    {{Form::select('user_id['.$item->id.']', $dsList, $selectedDs, array('class' => 'form-control js-source-states assign_subject_id', $disabled, 'id' => 'user_id'.$item->id))}}
                </div>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="4">{{trans('english.EMPTY_DATA')}}</td>
        </tr>
        @endif
    </tbody>
</table>
<script>
    $(".subjectId").change(function () {
        var id = $("#" + this.id).val();
        if (this.checked) {
            $("#user_id" + id).prop("disabled", false);
//            $("#mark_type_" + id).prop("disabled", false);
        } else {
            $("#user_id" + id).prop("disabled", true);
//            $("#mark_type_" + id).prop("disabled", true);
        }
    });
</script>