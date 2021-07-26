@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green portlet2">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-pencil-square-o"></i>{{trans('english.EPE_SUBJECTIVE_QUESTION')}}
                    </div>

                </div>

                <div class="form-wizard">
                    <div class="form-body">
                        <ul class="nav nav-pills nav-justified steps nav2">
                            <li></li>
                            <?php
                            $currentSetId = Request::getinput('set_id');
                            foreach ($epesubqussetArr as $epesubqusset) {
                                ?>
                                <li class="<?php echo ($currentSetId == $epesubqusset->set_id) ? 'active' : ''; ?>">
                                    <a href="#tab1" data-toggle="tab" class="step no_of_qus" data-set-id="{{$epesubqusset->set_id}}" data-epe-id="{{$epesubqusset->epe_id}}" aria-expanded="true">
                                        <span class="number"> Q{{$epesubqusset->set_id}}</span>

                                    </a>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                    </div>
                </div>	
            </div>
        </div>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '#','class' => 'form-horizontal', 'id'=>'epequestion', 'method'=> 'post')) }}

    <div id="sub_qus">
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
									<td>
										<span class="tooltips" style="<?php echo $cursor;?>"title="<?php echo $title; ?>"  >
											<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
												<input name="question_id[{{$question->id}}]" type="checkbox" data-id="{{$question->id}}" class="checkboxes {{$class}}" <?php echo $checked . ' ' . $qus_disabled; ?> value="{{$question->id}}" />
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
    </div>
	<div class="form-actions">
		<div class="row">
			<div class="col-md-offset-4 col-md-4">
				<button type="submit" class="btn btn-circle green qus-submit" id="epequsSubmit"><i class="fa fa-save"></i> {{trans('english.SAVE')}}</button>
				<a href="{{URL::to('epe/subquestionset/'.$subqussetArr->epe_id)}}">
					<button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> {{trans('english.CANCEL')}}</button> 
				</a>
			</div>
		</div>
	</div>
{{Form::close()}}
</div>
<script>
	// ****************** Ajax Code for children edit *****************
	$(document).on('click', '.checkboxes', function (e) {
		var qusSerialId = $(this).attr("data-serial-id");
		if ($(this).is(':checked')) {
			$(".answer-" + qusSerialId).show();
		} else {
			$(".answer-" + qusSerialId).hide();
		}

	});



	/* Show the question*/
	$(document).on("click", '.no_of_qus', function () {
		var setId = $(this).attr("data-set-id");

		var epeId = $(this).attr("data-epe-id");
		var value = {set_id: setId, epe_id: epeId, };
		$.ajax({
			url: "{{ URL::to('epe/showsubqus') }}",
			type: "GET",
			data: value,
			success: function (res) {

				$('#sub_qus').html(res);
				//Ending ajax loader
				App.unblockUI();
			},
			beforeSend: function () {

				//For ajax loader
				App.blockUI({
					boxed: true
				});
			},
			error: function (jqXhr, ajaxOptions, thrownError) {
				if (jqXhr.status == 401) {
					toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
				} else {
					toastr.error("Error", "Something went wrong", {"closeButton": true});
				}
			}
		});
	});

	// mark question id insert.............
var table;
$(document).ready(function() {
	$('#epequestion').submit( function(event) {
		table = $('#example_wrapper').dataTable();
		 event.preventDefault();
		 swal({
                title: 'Are you sure you want to Save?',
                text: '',
                type: 'warning',
                html: true,
                allowOutsideClick: true,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Yes, I agree',
                cancelButtonText: 'No, I do not agree',
            },
                function (isConfirm) {
                    if (isConfirm) {
						var sData = $('input', table.fnGetNodes()).serialize();
						var nData = $(":hidden").serialize();
						$.ajax({
							url: "{{ URL::to('epe/storesubqus') }}",
							type: "POST",
							data: sData+"&"+nData,
							dataType: 'json',
							success: function (response) {
							   toastr.success(response.data, "Success", {"closeButton": true});
								//Ending ajax loader
								App.unblockUI();
								$(".qus-submit").prop("disabled", true);
								 //page reload
								setTimeout(function () {
									window.location.reload();
								}, 3000);
							},
							beforeSend: function () {
								$(".qus-submit").prop("disabled", false);
							   // For ajax loader
								App.blockUI({
									boxed: true
								});
							},
							error: function (jqXhr, ajaxOptions, thrownError) {
								var errorsHtml = '';
								if (jqXhr.status == 400) {
									var errors = jqXhr.responseJSON.message;
									$.each(errors, function (key, value) {
										errorsHtml += '<li>' + value[0] + '</li>';
									});
									toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
								} else if (jqXhr.status == 500) {
									toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
								} else if (jqXhr.status == 401) {
									toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
								} else {
									toastr.error("Error", "Something went wrong", {"closeButton": true});
								}
							   // Ending ajax loader
								App.unblockUI();
							}
						
						});
			    } else {
                    event.preventDefault();
                }
            });		
	});
});

	$(document).on("change", '.noQus', function () {
		var id = $(this).attr("data-id");
		if (this.checked) {
			$(".dis_" + id).show();
		} else {
			$(".dis_" + id).hide();
		}
		var table = $('#example_wrapper').DataTable();	
		var currentcheck = table
			.rows()
			.nodes()
			.to$() 
		.find('input[type="checkbox"].noQus:checked').length;
		var checklimit = $("#selectAns").text();
		if (checklimit < currentcheck) {
			 swal("Maximum number of Question is already selected!");
			$(".dis_" + id).hide();
			$(this).attr('checked', false);
			return false;
		}
	});

	$(document).on("change", '.noOptionQus', function () {
		var id = $(this).attr("data-id");
		if (this.checked) {
			$(".dis_" + id).prop("readonly", true);
			$(".dis_" + id).show();
		} else {
			$(".dis_" + id).hide();
		}
		var table = $('#example_wrapper').DataTable();	
		var currentcheck = table
			.rows()
			.nodes()
			.to$() 
		.find('input[type="checkbox"].noOptionQus:checked').length;
		var checklimit = $("#selectQus").text();
		if (checklimit < currentcheck) {
			 swal("Maximum number of Question is already selected!");
			$(".dis_" + id).hide();
			$(this).attr('checked', false);
			return false;
		}
	});
	
	$(document).ready(function () {
		$(".tooltips").tooltip({html:true});
		$('#example_wrapper').DataTable();
	});
</script>
@stop
