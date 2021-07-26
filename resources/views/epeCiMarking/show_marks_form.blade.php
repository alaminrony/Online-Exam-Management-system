<div class="modal-header portlet-title" style="background-color: #32c5d2; color: #fff;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><strong>{{trans('english.ASSIGN_MARKS_TO')}} {{$submittedTaeObjArr->student_name}} ({{$submittedTaeObjArr->registration_no}})</strong></h4>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal form-row-seperated', 'id' => 'formAssigMarks')) }}
<div class="modal-body">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-md-4 control-label">{{trans('english.EPE_TOTAL_MARKS')}} :</label>
            <div class="col-md-6">
                <div class="input-group">
                    {{ Form::number('tae_total_mark', ($taeMarksDistribution) ? $taeMarksDistribution->tae_total_mark : null, array('id'=> 'tae_total_mark', 'class' => 'form-control', 'readonly' => true)) }}
                    <span class="input-group-addon"> {{trans('english.PASSING')}} </span>
                    {{ Form::number('tae_passing_mark', ($taeMarksDistribution) ? $taeMarksDistribution->tae_passing_mark : null, array('id'=> 'tae_passing_mark', 'class' => 'form-control', 'readonly' => true)) }}
                </div>
            </div>
        </div>
        <?php 
        $existingQuestionArr = json_decode($submittedTaeObjArr->question_set_marks);
        $totalQuestionCount = !empty((array)$existingQuestionArr) ? count((array)$existingQuestionArr) : 5;
        $totalAchievedMarks = $submittedTaeObjArr->achieved_marks;
        $row = 1;
        ?>
        @if(!empty($existingQuestionArr))
           
            @foreach($existingQuestionArr as $existingQuestion)
                <div class="form-group">
                    <div class="col-sm-12 nopadding">
                        <label class="col-md-4 control-label">Q{{$row}}:<span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="number" maxlength="5" class="form-control marks-entry valueSconto" id="marks-{{$row}}" name="marks[]" value="{{$existingQuestion->marks}}" placeholder="Enter Marks">
                        </div>
                    </div>
                </div>
            <?php 
            $row++;
            ?>
            @endforeach
        @else
        <div class="form-group">
            <div class="col-sm-12 nopadding">
                <label class="col-md-4 control-label">Q1 : <span class="required">*</span></label>
                <div class="col-md-6">
                    <input type="number" maxlength="5" class="form-control marks-entry valueSconto" id="marks-1" name="marks[]" value="" placeholder="Enter Marks">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 nopadding">
                <label class="col-md-4 control-label">Q2:</label>
                <div class="col-md-6">
                    <input type="number" maxlength="5" class="form-control marks-entry valueSconto" id="marks-2" name="marks[]" value="" placeholder="Enter Marks">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 nopadding">
                <label class="col-md-4 control-label">Q3:</label>
                <div class="col-md-6">
                    <input type="number" maxlength="5" class="form-control marks-entry valueSconto" id="marks-3" name="marks[]" value="" placeholder="Enter Marks">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 nopadding">
                <label class="col-md-4 control-label">Q4:</label>
                <div class="col-md-6">
                    <input type="number" maxlength="5" class="form-control marks-entry valueSconto" id="marks-4" name="marks[]" value="" placeholder="Enter Marks">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 nopadding">
                <label class="col-md-4 control-label">Q5:</label>
                <div class="col-md-6">
                    <input type="number" maxlength="5" class="form-control marks-entry valueSconto" id="marks-5" name="marks[]" value="" placeholder="Enter Marks">
                </div>
            </div>
        </div>
        @endif
        <div id="assign_mark_fields"></div>
        @if(Auth::user()->group_id == '3')
         <div class="form-group">
             <label class="col-md-4 control-label" for="assignMarksCiRemarks">{{trans('english.CI_REMARKS')}}:<span class="required">*</span></label>
            <div class="col-md-6">
                <textarea class="form-control" rows="3" name="ci_remarks" id="assignMarksCiRemarks">{{!empty($submittedTaeObjArr->ci_remarks) ? $submittedTaeObjArr->ci_remarks : null}}</textarea>
            </div>
        </div>
        @endif
        <div class="form-group static-info">
            <label class="col-md-4 control-labele"><strong></strong>{{trans('english.TOTAL_ARCHIEVED_MARKS')}}:</label>
            <div class="col-sm-6">
                <input type="number" class="form-control achieved_marks" id="total-achieved-marks" name="achieved_marks" value="{{$totalAchievedMarks}}" readonly="true">
            </div>
        </div>
<!--        <div class="form-group static-info">
            <div class="col-sm-6 text-right">
                <label class="control-label name"><strong></strong>{{trans('english.TOTAL_ARCHIEVED_MARKS')}}:</label>
            </div>
            <div class="col-sm-6 nopadding">
                <input type="number" class="form-control achieved_marks" id="total-achieved-marks" name="achieved_marks" value="{{$totalAchievedMarks}}" readonly="true">
            </div>
        </div>-->
    </div>
</div>
<div class="clearfix"> </div>
<div class="modal-footer text-center" style="background-color: #f5f5f5;border-top: 1px solid #e7ecf1;">
    @if(Auth::user()->group_id == '4')
    <button class="btn btn-circle btn-info" type="button"  onclick="assign_mark_fields();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{trans('english.ADD_QUESTION')}}</button>
    @endif
    <button type="button" class="btn btn-circle green" id="saveAssignMarks"><i class="fa fa-check"></i> {{trans('english.SUBMIT')}}</button>
    <button type="button" class="btn grey-salsa btn-circle" data-dismiss="modal">{{trans('english.CANCEL')}}</button>
    
</div>
{{ Form::hidden('total_question_count', $totalQuestionCount, array('id' => 'total_question_count')) }}
{{ Form::hidden('id', $submittedTaeObjArr->id, array('id' => 'assignmentId')) }}
{{ Form::hidden('tae_id', $submittedTaeObjArr->tae_id, array('id' => 'taeId')) }}
{{ Form::close() }}
<script type="text/javascript">
//    
    function assign_mark_fields() {
        var row = parseInt($('#total_question_count').val())+1;
        $('#total_question_count').val(row);
       
        var objTo = document.getElementById('assign_mark_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + row);
        var rdiv = 'removeclass' + row;
        var html = '<div class="col-sm-12 nopadding"><label class="col-md-4 control-label" style="margin-right:13px;">Q' + row + ':</label><div class="col-md-6 input-group"><input type="number" maxlength="5" class="marks-entry form-control valueSconto" id="marks-' + row + '" name="marks[]" value="" placeholder="Enter Marks"><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_assign_mark_fields(' + row + ');"> <span class="fa fa-trash" aria-hidden="true"></span> </button></div></div></div>';

        divtest.innerHTML = html;

        objTo.appendChild(divtest)
    }
    
    function remove_assign_mark_fields(rid) {
        $('.removeclass' + rid).remove();
        var row = parseInt($('#total_question_count').val())-1;
        $('#total_question_count').val(row);
        
        //Remove question then total achieved mark sum
        var questionTotalMarks = 0;
        $('input[type="number"].marks-entry').each(function(){
                questionTotalMarks = parseFloat($(this).val()) + questionTotalMarks;
        });
        $('.achieved_marks').val(parseFloat(questionTotalMarks).toFixed(2));
        
    }
    
    $(document).on("change", ".marks-entry", function() {
        //console.log();
        $(this).val(function(i, val) {
          if(val > 20){
              alert("Put maximum marks less then 20");
              return false;
          }
          return val.replace(/\d{3}|[^\d{2}\.]|^\./g, "");
        });
        
        var sum = 0;
        $(".marks-entry").each(function(){
            sum += +$(this).val();
        });
       var achived_marks = parseFloat(sum).toFixed(2)
        $('.achieved_marks').val(achived_marks)
    });
    
    //Per question less then 20 marks put
//    $(".valueSconto").on("input", function() {
//        $(this).val(function(i, val) {
//          if(val > 20){
//              alert("Put maximum marks less then 20");
//              return false;
//          }
//          return val.replace(/\d{3}|[^\d{2}\.]|^\./g, "");
//        });
//    });
</script>



