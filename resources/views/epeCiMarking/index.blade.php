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
                        <i class="fa fa-book"></i>{{trans('english.EPE_MARKING_SUBJECTIVE')}}
                    </div>
                    <div class="actions"></div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        {{ Form::open(array('role' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'lockData')) }}
                        <div class="row">
                            <div class="col-md-offset-2 col-md-7">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="courseId">{{trans('english.EXAM')}}:<span class="required">*</span></label>
                                    <div class="col-md-8">
                                        {{Form::select('epe_id', $epeList, Request::get('epe_id'), array('class' => 'form-control js-source-states', 'id' => 'epeId'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                        <div class="row">
                            <div class="table-responsive">
                                <div class="col-md-12" id="show_submited_epe">
                                    <!--AJAX CALL FOR SUMITTED TAE-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--This module use for student marks assign-->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-content"><!-- mysql data will load in table -->
        </div>
    </div>
</div>
<!-- END MODAL BODY -->

<!-- SRART DIALOG BOX-->
<div id="dialog" style="display: none">
    <div id="loading">
        <p> Please Wait..</p>
    </div>
</div>
<!-- END DIALOG BOX-->
<style type="text/css">
    .ui-dialog{
        z-index: 9999!important;
    }
</style>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).ready(function () {
        
    });

    $("#epeId").change(function () {
        var epeId = $(this).val();
        epeAssignMarks(epeId);
        
    });
    
    
    function epeAssignMarks(epeId){
         $.ajax({
            url: "{{ URL::to('epecimarking/show_submitted_epe') }}",
            type: "POST",
            data: {epe_id: epeId},
            success: function (response) {
                $('#show_submited_epe').html(response.html);
                //Ending ajax loader
                App.unblockUI();
            },
            beforeSend: function () {
                $('#show_submited_epe').empty();
                //For ajax loader
                App.blockUI({
                    boxed: true
                });
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
                $('#show_submited_epe').empty();
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
            }
        });
    }
    
    $( window ).on( "load", function() {
       
        var epeId = $("#epeId").val();
        if(epeId != ''){
            epeAssignMarks(epeId);
        }
    });
    
    // ****************** Ajax Code for assign marks *****************
    $(document).on('click', '.show-assign-marks', function (e) {
        e.preventDefault();
        
        var id = $(this).data('id'); // get id of clicked row
        $('#dynamic-content').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('epecimarking/show_marks_form/') }}",
            type: "GET",
            data: {
                id:id
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#dynamic-content').html(''); // blank before load.
                $('#dynamic-content').html(response.html); // load here
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
         });
    });
    //*********** End Ajax Code For Edit assign mark Information *********//
    
    $(document).on('click', '#saveAssignMarks', function (e) {
        var assignMarksData = new FormData($('#formAssigMarks')[0]);
        e.preventDefault();
        if(confirm("Are you sure you want to marks submitted?")){
            $.ajax({
                url: "{{ URL::to('epecimarking/store_assign_marks/') }}",
                type: "POST",
                data: assignMarksData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                async: true,
                success: function (response) {
                    $("td.achieved_marks_"+response.data.id).text(parseFloat(response.data.achieved_marks).toFixed(2))
                    toastr.success(response.message, "Success", {"closeButton": true});
                    $('#view-modal').modal('toggle');
                    //window.location.reload();
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
                }
             });
         }else{
             return false;
         }
    });
    
    // ****************** Ajax Code for assign marks *****************
    $(document).on('click', '#marks-locked', function (e) {
        e.preventDefault();
       // var students = $('input:hidden.assignMarksStudentId').serialize();
//        console.log(students);
//        return false;
        var taeId = $(this).data('tae_id'); // get id of clicked row
        if(confirm('Student marks not yet put. Are you sure, you want to lock?')){
            $.ajax({
                url: "{{ URL::to('epecimarking/marks_lock') }}",
                type: "POST",
                data: {
                    tae_id:taeId
                },
                dataType:"json",
                success: function (response) {
                    $('.assign-marks-locked').html('');
                    toastr.success(response.message, "Success", {"closeButton": true});
                    location.reload(true);
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
                }
             });
        }else{
            return false;
        }
    });
    //*********** End Ajax Code For Edit assign mark Information *********//
    
    // ****************** Ajax Code for assign marks *****************
    $(document).on('click', '#marks-unlock', function (e) {
        e.preventDefault();

        var taeId = $(this).data('tae_id'); // get id of clicked row
        if(confirm('Are you sure you want to unlock this marks?')){
            $.ajax({
                url: "{{ URL::to('epecimarking/marks_unlock') }}",
                type: "POST",
                data: {
                    tae_id:taeId
                },
                success: function (response) {
                    $('.assign-marks-unlock').html('');
                    toastr.success(response.message, "Success", {"closeButton": true});
                    location.reload(true);
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
                }
             });
        }else{
            return false;
        }
    });
    //*********** End Ajax Code For Edit assign mark Information *********//
    
    $(function () {
        $(document).on("click", "#showAssignment", function () {
            var assignmentId = $(this).data("id");
            $.ajax({
                url: "{{ URL::to('epecimarking/show_file') }}",
                type: "POST",
                data: {id: assignmentId},
                success: function (response) {
                    //console.log(response);
                    var originName = response.data.original_file;
                    var fileName = response.data.assignment;
                    var src = "{{URL::to('/')}}/public/uploads/assignment/" + fileName;
                   
                    $("#dialog").dialog({
                        modal: true,
                        title: originName,
                        width: 700,
                        height: 600,
                        buttons: {
                            Close: function () {
                                $(this).dialog('close');
                            }
                        },
                        show: {
                            effect: "blind",
                            duration: 1000
                        },
                        hide: {
                            effect: "explode",
                            duration: 1000
                        },
                        open: function () {
                            var object = '<div id="mypdf"><iframe src="' + src + '#zoom=65" style="width: 100%; height: 800px;" frameborder="0" scrolling="no"><p>Your web browser doesnt support iframes.</p></iframe></div>';
                            $("#dialog").html(object);
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

    });
	
	// this function use lock ci
	 $(document).on('click', '#lockResult', function (e) {
     
        // var pendingLock = $('#pendingLock').val();
        
        // if(pendingLock != '0'){
            // alert(pendingLock + ' script has not been locked yet!');
            // return false;
        // }
        
        var epeId = $('#epeId').val();
        
        swal({
                title: 'Are you sure you want to lock this result?',
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
				//if(confirm("Are you sure you want to lock this result?")){
					var taeData = new FormData($('#lockData')[0]);
					$.ajax({
						url: "{{ URL::to('lockSubjectiveMarking') }}",
						type: "POST",
						data: taeData,
						dataType: 'json',
						cache: false,
						contentType: false,
						processData: false,
						async: true,
						success: function (response) {
							toastr.success('Result Locked Successfully', "Success", {"closeButton": true});
							//toastr.success(response.data, "Success", {"closeButton": true});
							$("#tdlock").hide();
							$('#lockedMsg').html(response.data);
							return false;
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
						}
					 });
         }else{
             return false;
		}
		});
    });
</script>
@stop
