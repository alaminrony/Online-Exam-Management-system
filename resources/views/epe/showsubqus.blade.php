<div class="portlet light bordered">
    <div class="portlet-body">
        <div class="row sub_qus">
            <div class="col-md-12">
                
                <?php
                $answer = $subqussetArr->no_of_qus;
                $mark = '';
                $mark_entry = 'mark-entry';
                $class = 'noQus';
                $readonly = '';
                if ($subqussetArr->options == 1) {
                    $mark = $subqussetArr->mark / $subqussetArr->answer;
                    $answer = $subqussetArr->answer;
                    $mark_entry = '';
                    $class = 'noOptionQus';
                    $readonly = 'readonly';
                }
                ?>
                
					<address class="text-center" style="margin-bottom: 0;">
						<h4 class="h4" style="margin: 0;padding: 0;"> {{$epeInfo->course->title}}</h4>
						<strong>{{trans('english.PART')}}: </strong> {{$epeInfo->part->title}}, 
						<strong>{{trans('english.PHASE')}}: </strong> {{$epeInfo->phase->full_name}}
						<br><strong>{{trans('english.SUBJECT')}}: </strong> {{$epeInfo->subject->title}}
						@if(!empty($subqussetArr['set_title']))<br><strong class="font-green-jungle">{{trans('english.TITLE')}} :  {{ (empty($subqussetArr['set_title']) ? null : $subqussetArr['set_title']) }} </strong>@endif
						<br><strong>{{trans('english.NEED_TO_ANSWER')}}:</strong>  <span id="selectAns">{{$answer}}</span>  {{trans('english.OUT_OF')}} <span id="selectQus">{{$subqussetArr->no_of_qus}}</span>
					</address>
            </div>
        </div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-bordered table-hover table-checkable order-column" id="example_wrapper">
					<thead>
						<tr>
							<th>#</th>
							<th> {{trans('english.QUESTION')}} </th>
							<th> {{trans('english.MARK') .' ('.$subqussetArr->mark .')'}} </th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($questions))

						@foreach($questions as $question)
						<?php
						$checked = empty($question->set_id) ? '' : 'checked';

						$questionId = $question->id;
						//Get exists data for assign subject
						$display = 'display: none;';
						$targetArr = array_filter($previousData, function($elem) use($questionId) {
							return $elem['question_id'] === $questionId;
						});

						$existsDataArr = reset($targetArr);

						if (!empty($existsDataArr)) {
							$disabled = '';
							$display = '';
						} else {
							$disabled = 'disabled';
						}
						// check box disabled ......
						$tarArr = array_filter($alreadyData, function($e) use($questionId, $subqussetArr) {
							if ($e['set_id'] != $subqussetArr->set_id) {
								return $e['question_id'] === $questionId;
							}
						});

						$existDataArr = reset($tarArr);

						if (!empty($existDataArr)) {
							$qus_disabled = 'disabled';
							$title = "Already selected at set Q" . $alreadyData[$questionId]['set_id'];
							$cursor = 'cursor:not-allowed';
						} else {
							$qus_disabled = '';
							$title = '';
							$cursor = '';
						}
						?>
						<tr class="odd gradeX">
							<td >
								<span class="tooltips" style="<?php echo $cursor ;?>"title="<?php echo $title; ?>"  >
									<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
										<input name="question_id[{{$question->id}}]" type="checkbox"  data-id="{{$question->id}}" class="checkboxes {{$class}}" <?php echo $checked . ' ' . $qus_disabled; ?> value="{{$question->id}}" />
										<span></span>
									</label>
								</span>
							</td>
							<td> {{$question->question}} </td>
							<td> 
								{{ Form::number('mark['.$question->id.']',(empty($previousData[$question->id]['mark']) ? $mark:$previousData[$question->id]['mark']), array('id'=> 'mark', 'class' => 'form-control  dis_'.$question->id .' '. $mark_entry,'data-question-id' => $question->id,'style'=>$display ,$readonly))}}
							</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
				<input type="hidden" name="epe_id" value="{{$subqussetArr->epe_id}}">	
				<input type="hidden" name="set_id" value="{{$subqussetArr->set_id}}">
			</div>
		</div>
    </div>
	
</div>


                                <!--/*/ $(document).on("keyup", '.mark-entry', function () {
                                    // var sum = 0;
                                                // var totalMark = $("#totalMark").text();
                                                // $(".mark-entry").each(function(){
                                                        // sum += +$(this).val();
                                                        // return sum;
                                                // });
                                                // if(sum > totalMark){
                                                        // var msg = "Put maximum marks less then "+totalMark;
                                                        // toastr.error(msg, {"closeButton": true});
                                                        // return false;
                                                // }
                              // });-->
<script>  
	$(document).ready(function () {
		$(".tooltips").tooltip({html: true});
		$('#example_wrapper').DataTable();
	});
</script>