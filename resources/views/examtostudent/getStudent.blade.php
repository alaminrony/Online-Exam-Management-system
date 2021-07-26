<div class="row">
    <div class="col-md-12 text-center">
        <button type="button" class="btn green btn-sm tooltips" id="showAssignedStudent" data-id="{{$examId??''}}" data-toggle="modal" data-target="#openStudentModal" title="View Assigned Student">No of Assigned Student:<b>({{$noOfAssignStudent??''}})</b></button>
    </div>
</div>
{{ Form::open(array('role' => 'form', 'url' => '#', 'files'=> true,'class' => 'form-horizontal', 'id'=>'questionSet', 'method'=> 'post')) }}
<table class="table table-striped table-bordered table-hover table-checkable order-column dataTables_wrapper" id="dataTable">
    <thead>
        <tr>
            <th class="vcenter text-center" width="15%">
    <div class="md-checkbox has-success">
        {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']) !!}
        <label for="checkAll">
            <span class="inc"></span>
            <span class="check mark-caheck"></span>
            <span class="box mark-caheck"></span>
        </label>
        <span class="bold">@lang('label.CHECK_ALL')</span>
    </div>
</th>
<th> {{__('label.NAME')}} </th>
<th> {{__('label.DESIGNATION')}} </th>
<th> {{__('label.REGION')}} </th>
<th> {{__('label.CLUSTER')}} </th>
<th> {{__('label.BRANCH')}} </th>
<th> {{__('label.DEPARTMENT')}} </th>
</tr>
</thead>
<tbody>
    @if(!$studentArr->isEmpty())
    <?php
    $class = 'noStd';
    ?>
    @foreach($studentArr as $student)
    <?php
    $checked = empty($student->exam_id) ? '' : 'checked';
    ?>
    <tr class="odd gradeX">
        <td class="text-center"> 
            <div class="md-checkbox has-success">
                <input name="employee_id[{{$student->id}}]" type="checkbox" class="md-check bf-check" id="{{$student->id??''}}" <?php echo in_array($student->id, $exmToStudentArr) ? "checked" : ""; ?> value="{{$student->id??''}}"/>
                <label for="{{$student->id}}">
                    <span class="inc"></span>
                    <span class="check mark-caheck"></span>
                    <span class="box mark-caheck"></span>
                </label>
            </div>
        </td>
        <td >{{!empty($student->rank->short_name) ? $student->rank->short_name : ''}} {{$student->first_name??''}} {{$student->last_name??''}} ({{$student->username??''}})</td>
        <td>{{!empty($student->designation_title) ? $student->designation_title: ''}}</td>
        <td>{{!empty($student->region_name) ? $student->region_name : ''}}</td>
        <td>{{!empty($student->cluster_name) ? $student->cluster_name : ''}}</td>
        <td>{{!empty($student->branch_name) ? $student->branch_name: ''}}</td>
        <td>{{!empty($student->department_name) ? $student->department_name: ''}}</td>
    </tr>
    @endforeach
    @endif
</tbody>
</table>
<div class="form-actions">
    <div class="row">
        <div class="text-center col-md-12 fixed_3">
            <button type="submit" class="btn btn-circle green" id="studentSubmit"><i class="fa fa-save"></i> {{__('label.SAVE')}}</button>
            <a href="{{URL::to('examtostudent')}}">
                <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> {{__('label.CANCEL')}}</button> 
            </a>
        </div>
    </div>
</div>
<input type="hidden" name="exam_id" value="{{$examId}}"/>
{{ Form::close() }}
<script type="text/javascript">
    $(document).ready(function () {
    var table = $('#dataTable').DataTable()
    $('#questionSet #checkAll').change(function() {
    var checked = $(this).is(":checked");
    $("input", table.rows({search:'applied'}).nodes()).prop( 'checked', checked );
});
 
});
 
    //This function use for save EPE information
    var table;
    $(document).ready(function () {
        table = $('#dataTable').dataTable();
        $(document).on('click', '#studentSubmit', function (e) {
            e.preventDefault();
            // Serialize the form data
            var oTable = $('#dataTable').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#questionSet").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });

            var form_data = new FormData($('#questionSet')[0]);

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('examtostudent/saveStudent')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, 'Student has been Assigned to Exam', options);
                            //App.blockUI({ boxed: false });
                            setTimeout(location.reload.bind(location), 1000);
                            var syndicateId = $("#syndicateId").val();
                            var syndicateType = $("#syndicateType").val();
                            var termId = $("#termId").val();
                            var wingId = $("#wingId").val();

                            if (syndicateId == '0') {
                                $('#showStudent').html('');
                                return false;
                            }

                            $.ajax({
                                url: "{{ URL::to('/studentToTermSyn/getStudent')}}",
                                type: "POST",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    syndicate_id: syndicateId,
                                    term_id: termId,
                                    wing_id: wingId,
                                    syndicate_type: syndicateType,
                                },
                                beforeSend: function () {
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    $('#showStudent').html(res.html);
                                    $('.tooltips').tooltip();
                                    $(".js-source-states").select2();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                }
                            });//ajax
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });

    
</script>