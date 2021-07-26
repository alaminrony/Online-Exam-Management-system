@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-pencil-square-o"></i>{{trans('english.EPE_SUBJECTIVE_QUESTION_SET')}}
                    </div>

                </div>
                <div class="portlet-body">
                    {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'epequsSubmit')) }}
                    <div class="row">
                        <div class="table-responsive">
                            <div class="col-md-12 col-xs-12">
                                <div class="well">
                                    <address class="text-center" style="margin-bottom: 0;">
                                        <h4 class="h4" style="margin: 0;padding: 0;"> {{$epeInfo->course->title}}</h4>
                                        <strong>{{trans('english.PART')}}: </strong> {{$epeInfo->part->title}}, 
                                        <strong>{{trans('english.PHASE')}}: </strong> {{$epeInfo->phase->full_name}}
                                        <br><strong>{{trans('english.SUBJECT')}}: </strong> {{$epeInfo->subject->title}}
                                        <br><strong>{{trans('english.EPE')}}: </strong> {{$epeInfo->title}}
                                    </address>
                                    <address style="margin-bottom: 0;"><label class="text-left" style="width: 50%"><strong>{{trans('english.EXAM_DATE')}}:</strong> {{ $epeInfo->exam_date }}</label><label class="text-right" style="width: 50%"><strong>{{trans('english.NEED_TO_ANSWER')}}:</strong> {{ $epeInfo->sub_no_mandatory }} {{trans('english.OUT_OF')}} {{ $epeInfo->sub_no_question }}</label>
                                    </address>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr class="contain-center">
                                            <th>{{trans('english.QUESTION_NO')}}</th>
                                            <th>{{trans('english.MARK')}}</th>
                                            <th width="30%">{{trans('english.TITLE')}}</th>
                                            <th>{{trans('english.HAS_OPTIONS')}}</th>
                                            <th>{{trans('english.NO_OF_QUESTION')}}</th>
                                            <th>{{trans('english.ACTION')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($epeInfo) && !empty($markDistribution))
                                        <?php
                                        $mark = $markDistribution->subjective / $epeInfo->sub_no_mandatory;
                                        $readonly = 'readonly';
                                        ?>
                                        {{ Form::hidden('epe_id', $epeInfo->id, array('id' => 'epe_id')) }}
                                        <tr class="contain-center">

                                            @for($i = 1; $i <= (int)$epeInfo->sub_no_question; $i++)
                                            <?php
                                            if (!empty($previousData[$i]['options'])) {
                                                $displayNoOfQuestion = 'style="display:none;"';
                                                $displayMandatoryAnswer = 'style="display:block;"';
                                            } else {
                                                $displayMandatoryAnswer = 'style="display:none;"';
                                                $displayNoOfQuestion = 'style="display:block;"';
                                            }
                                            ?>
                                            <td>
                                                Q{{$i}}
                                                {{ Form::hidden('set_id['.$i.']', $i, array('id' => 'set_'.$i)) }}
                                            </td>
                                            <td>
                                                {{ Form::number('mark['.$i.']',Custom::numberFormat($mark), array('id'=> 'mark', 'class' => 'form-control',$readonly ))}}
                                            </td>
											<td>
												{{ Form::text('set_title['.$i.']', (empty($previousData[$i]['set_title']) ? null:$previousData[$i]['set_title']),array('class'=>'form-control' ,'id'=>'setTitle'))}}
											</td>
                                            <td>
                                                <div class="md-checkbox">
                                                    <input type="checkbox" name="options[{{$i}}]" id="options-{{$i}}" data-set-id="{{$i}}" class="hasOptional" {{(!empty($previousData[$i]['options'])) ? 'checked="checked"' : ''}} value="1">
                                                    <label for="options-{{$i}}">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                    </label>
                                                </div>
                                            </td>

                                            <td>
                                                <div id="show_no_of_question_{{$i}}">
                                                    @if(!empty($previousData[$i]['options']))
                                                        <div class="col-ms-12" id="mandatoryAnswer{{$i}}" <?php echo $displayMandatoryAnswer; ?>>
                                                            <div class="input-group">
                                                                {{ Form::number('answer['.$i.']',(empty($previousData[$i]['answer']) ? null:$previousData[$i]['answer']), array('id'=> 'answer_'.$i, 'class' => 'form-control answer_'.$i, 'placeholder' => 'Answer'))}} 
                                                                <span class="input-group-addon answer-{{$i}}"> Out of </span>
                                                                {{ Form::number('no_of_qus['.$i.']', (empty($previousData[$i]['no_of_qus']) ? null:$previousData[$i]['no_of_qus']), array('id'=> 'no_of_qus_'.$i, 'class' => 'form-control no_of_qus_'.$i, 'placeholder' => 'Total Question'))}} 
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <div class="col-ms-12" id="noOfQuestion{{$i}}" <?php echo $displayNoOfQuestion; ?>>
                                                            {{ Form::number('no_of_qus['.$i.']', (empty($previousData[$i]['no_of_qus']) ? null:$previousData[$i]['no_of_qus']), array('id'=> 'no_of_qus_'.$i, 'class' => 'form-control no_of_qus_'.$i, 'placeholder' => 'Total Question'))}} 
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if(!empty($previousData[$i]))
                                                <a class='btn btn-primary btn-sm green tooltips' href="{{ URL::to('epe/subquestion?epe_id='.$epeInfo->id.'&set_id='.$i)}}"  title="{{trans('english.SUBJECTIVE_QUESTION_SET_FOR').' Q'.$i}}">
                                                    <i class='fa fa-question-circle'></i>
                                                </a>
                                                @else
                                                <span class="fa fa-ban font-red tooltips" title="{{trans('english.QUESTION_IS_NOT_SET_YET')}}"></span>
                                                @endif
                                            </td> 
                                        </tr>
                                        @endfor
                                        @else
                                        <tr>
                                            <td colspan="5">{{trans('english.EMPTY_DATA')}}</td>
                                        </tr>
                                        @endif 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-circle green qus-submit" id="epequsSubmit"><i class="fa fa-save"></i> {{trans('english.SAVE')}}</button>
                                    <a href="{{URL::to('epe')}}">
                                        <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> {{trans('english.CANCEL')}}</button> 
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).on('click', '.hasOptional', function (e) {
        var questionSetId = $(this).attr("data-set-id");
        
        if ($(this).prop('checked')) {
            var noOfQuestionAndAnser = '';
            noOfQuestionAndAnser +='<div class="col-ms-12">';
            noOfQuestionAndAnser +='<div class="input-group">';
            noOfQuestionAndAnser +='<input id="answer_'+questionSetId+'" class="form-control answer_'+questionSetId+'" data-set-id="'+questionSetId+'" placeholder="Answer" name="answer['+questionSetId+']" type="number">';
            noOfQuestionAndAnser +='<span class="input-group-addon"> Out of </span>';
            noOfQuestionAndAnser +='<input id="no_of_qus_'+questionSetId+'" class="form-control no_of_qus_'+questionSetId+'" placeholder="Total Question" name="no_of_qus['+questionSetId+']" type="number">';
            noOfQuestionAndAnser +='</div>';
            noOfQuestionAndAnser +='</div>';      

            $("#show_no_of_question_"+questionSetId).html(noOfQuestionAndAnser);
        } else {
            var noOfQuestion = '<div class="col-ms-12"><input id="no_of_qus_'+questionSetId+'" class="form-control no_of_qus_'+questionSetId+'" placeholder="Total Question" name="no_of_qus['+questionSetId+']" type="number"></div>';
            $("#show_no_of_question_"+questionSetId).html(noOfQuestion);
        }
    });
    // ****************** Ajax Submit For Subjective Question Set *****************

    $(document).on("click", '.qus-submit', function (event) {
		event.preventDefault();
        var taeData = new FormData($('#epequsSubmit')[0]);
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
        
						toastr.info("Loading...", "Please Wait.", {"closeButton": true});
						$.ajax({
							url: "{{ URL::to('epe/storesubqusset') }}",
							type: "POST",
							data: taeData,
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							async: true,
							success: function (response) {

								toastr.success(response.data, "Success", {"closeButton": true});
								//Ending ajax loader
								App.unblockUI();
								window.location.reload();
							},
							beforeSend: function () {
								//For ajax loader
								App.blockUI({
									boxed: true
								});
							},
							error: function (jqXhr, ajaxOptions, thrownError) {
								//alert(thrownError);return false;
								var errorsHtml = '';
								if (jqXhr.status == 400) {
									var errors = jqXhr.responseJSON.message;
									$.each(errors, function (key, value) {

										errorsHtml += '<li>' + value[0] + '</li>';
									});
									toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, });

								} else if (jqXhr.status == 500) {
									toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
								} else if (jqXhr.status == 401) {
									toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
								} else {
									toastr.error("Error", "Something went wrong", {"closeButton": true});
								}
								//Ending ajax loader
								App.unblockUI();
							}
						});
				} else {
					event.preventDefault();
				}

            });		
					
					
    });

    $(document).on("keyup", '.answer', function (event) {
        var answer = $(this).val();
        var qusSerialId = $(this).attr("data-set-id");
        var noOfQus = $('.no_of_qus_' + qusSerialId).val();

        if (parseFloat(answer) > parseFloat(noOfQus)) {
            alert("Answer must be less than  total Question");
            $(this).val('');
            return false;
        }
    });

</script>
@stop
