@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>@lang('label.UPDATE_A_USER') </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::model($target, array('route' => array('user.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal', 'id' => 'submitForm')) }}
            {{csrf_field()}}
            {{ Form::hidden('filter', Helper::queryPageStr($qpArr)) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="groupId">@lang('label.SELECT_GROUP') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('group_id', $groupList, Request::get('group_id'), array('class' => 'form-control  js-source-states', 'id' => 'groupId'))}}
                                <span class="help-block text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>
                        <div id="showData">
                            @if(!empty($employeeTypeList))
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="employeeType">@lang('label.SELECT_EMPLOYEE_TYPE') :</label>
                                <div class="col-md-8">
                                    {{Form::select('employee_type', $employeeTypeList, Request::get('employee_type'), array('class' => 'form-control js-source-states', 'id' => 'employeeType'))}}
                                    <span class="help-block text-danger">{{ $errors->first('employee_type') }}</span>
                                </div>
                            </div>
                            @endif

                            @if(!empty($supervisorList))
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="userId">@lang('label.SELECT_SUPERVISOR') :</label>
                                <div class="col-md-8">
                                    {{Form::select('user_id', $supervisorList,Request::get('user_id'), array('class' => 'form-control js-source-states', 'id' => 'userId'))}}
                                    <span class="help-block text-danger">{{ $errors->first('user_id') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                         <div class="form-group">
                            <label class="col-md-4 control-label" for="departmentId">@lang('label.DEPARTMENT') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('department_id',$departmentList,Request::get('department_id'), array('class' => 'form-control js-source-states', 'id' => 'departmentId'))}}
                                <span class="help-block text-danger">{{ $errors->first('department_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="rankId">@lang('label.SELECT_RANK') :<span class="required"></span></label>
                            <div class="col-md-8">
                                {{Form::select('rank_id', $rankList, Request::get('rank_id'), array('class' => 'form-control  js-source-states', 'id' => 'rankId'))}}
                                <span class="help-block text-danger">{{ $errors->first('rank_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="appointmentId">@lang('label.SELECT_APPROINTMENT') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('designation_id', $appointmentList,Request::get('designation_id'), array('class' => 'form-control  js-source-states', 'id' => 'appointmentId'))}}
                                <span class="help-block text-danger">{{ $errors->first('designation_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="firstName">@lang('label.FIRST_NAME') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{ Form::text('first_name',Request::get('first_name'), array('id'=> 'firstName', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('first_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="lastName">@lang('label.LAST_NAME') :<span class="required"></span></label>
                            <div class="col-md-8">
                                {{ Form::text('last_name',Request::get('last_name'), array('id'=> 'lastName', 'class' => 'form-control')) }}
                                <span class="help-block text-danger"> {{ $errors->first('last_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="branchId">@lang('label.SELECT_BRANCH') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('branch_id', $branchList,Request::get('branch_id'), array('class' => 'form-control  js-source-states', 'id' => 'branchId'))}}
                                <span class="help-block text-danger">{{ $errors->first('branch_id') }}</span>
                                <div id="branchDataShow"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="username">@lang('label.USERNAME') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    {{ Form::text('username',Request::get('username'), array('id'=> 'username', 'class' => 'form-control')) }}
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('username') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="password">@lang('label.PASSWORD') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::password('password', array('id'=> 'password', 'class' => 'form-control')) }}
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                                <span class="help-block">@lang('label.COMPLEX_PASSWORD_INSTRUCTION')</span>
                                <span class="help-block text-danger"> {{ $errors->first('password') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="passwordConfirmation">@lang('label.CONFIRM_PASSWORD') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::password('password_confirmation', array('id'=> 'passwordConfirmation', 'class' => 'form-control')) }}
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('password_confirmation') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.EMAIL') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    {{ Form::email('email',Request::get('email'), array('id'=> 'UserEmail', 'placeholder' => 'Email Address', 'class' => 'form-control')) }}
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('email') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="phoneNo">@lang('label.PHONE_NUMBER') :</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-mobile-phone"></i>
                                    {{ Form::text('phone_no',Request::get('phone_no'), array('id'=> 'phoneNo', 'class' => 'form-control')) }}
                                </div>
                                <span class="help-block text-danger"> {{ $errors->first('phone_no') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="status">@lang('label.STATUS') :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                {{Form::select('status', $status,Request::get('status'), array('class' => 'form-control', 'id' => 'status'))}}
                                <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo">@lang('label.PHOTO') :</label>
                            <div class="col-md-8">
                                <!-- input file -->
                                <div class="box">
                                    <input name="prev_photo" type="file" id="photo">
                                    <span class="text-danger">{{ $errors->first('photo') }}</span>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_AND_STUDENT_IMAGE_FOR_IMAGE_DESCRIPTION')
                                    </div>
                                </div>				
                            </div>				
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <div class="col-md-4">
                                    <!-- input file -->
                                    <img class="cropped" src="" alt="">
                                    <input type="hidden" name="crop_photo" id="cropImg" value="">

                                    <div class="box">
                                        <div class="options hide">
                                            <input type="hidden" class="img-w" value="300" min="255" max="255"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo"></label>	
                            <div class="col-md-4">
                                <!-- leftbox -->
                                @if(!empty($target->photo))
                                <img src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" id="prevImage" alt="{{ $target->name}}"/>
                                @endif
                                <div class="result"></div>
                                <!-- crop btn -->
                                <button class="c-btn crop btn-danger hide" type="button">@lang('label.CROP')</button>
                            </div>		
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="button" class="btn btn-circle green" id="submitBtn">@lang('label.SUBMIT')</button>
                        <a href="{{URL::to('user')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">@lang('label.CANCEL')</button> 
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            <!-- END FORM-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(function () {
        
         $(document).on('change', '#groupId', function (e) {
            var groupId = $('#groupId').val();
            $.ajax({
                url: "{{ URL::to('user/getData')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    group_id: groupId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showData').html(res.html);
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        
        //    crop image 
        let result = document.querySelector('.result'),
                img_result = document.querySelector('.img-result'),
                img_w = document.querySelector('.img-w'),
                img_h = document.querySelector('.img-h'),
                options = document.querySelector('.options'),
                crop = document.querySelector('.crop'),
                cropped = document.querySelector('.cropped'),
                dwn = document.querySelector('.download'),
                upload = document.querySelector('#photo'),
                cropper = '';
        var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];
        // on change show image with crop options
        upload.addEventListener('change', function (e) {
            if (e.target.files.length) {
                $('#prevImage').hide();
                // start file reader
                const reader = new FileReader();
                var file = e.target.files[0]; // Get your file here
                var fileExt = file.type.split('/')[1]; // Get the file extension
                if (fileTypes.indexOf(fileExt) !== -1) {
                    reader.onload = function (e) {
                        if (e.target.result) {
                            // create new image
                            let img = document.createElement('img');
                            img.id = 'image';
                            img.src = e.target.result
                            // clean result before
                            result.innerHTML = '';
                            // append new image
                            result.appendChild(img);
                            // show crop btn and options
                            crop.classList.remove('hide');
                            options.classList.remove('hide');
                            // init cropper
                            cropper = new Cropper(img);
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('File not supported');
                    return false;
                }
            }
        });
        // crop on click
        crop.addEventListener('click', function (e) {
            e.preventDefault();
            // get result to data uri
            let
                    imgSrc = cropper.getCroppedCanvas({
                        width: img_w.value // input value
                    }).toDataURL();
            // remove hide class of img
            cropped.classList.remove('hide');
            //	img_result.classList.remove('hide');
            // show image cropped
            cropped.src = imgSrc;
            $('#cropImg').val(imgSrc);
        });
        //crop image end

        $(document).on("click", '#submitBtn', function (e) {
            //This function use for sweetalert confirm message
            e.preventDefault();
            swal({
                title: 'Are you sure you want to Submit?',
                //text: '<strong></strong>',
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
                            $('#submitForm').submit();
                        } else {
                            //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                        }
                    });
        });
        //EOF. Submit Form
    });
    $(document).ready(function () {
        $(document).on('change', '#branchId', function () {
            var branchId = $(this).val();
            if (branchId != '') {
                $.ajax({
                    url: "{{route('user.getBranchData')}}",
                    type: "post",
                    dataType: "json",
                    data: {branch_id: branchId},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data != '') {
                            $('#branchDataShow').html(data.viewData);
                        }
                    }
                });
            }
            $('#branchDataShow').html('');
        });
        var old_branchId = "{{ old('branch_id') ? old('branch_id') : '' }}";
        if (old_branchId != '') {
            $.ajax({
                url: "{{route('user.getBranchData')}}",
                type: "post",
                dataType: "json",
                data: {branch_id: old_branchId},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data != '') {
                        $('#branchDataShow').html(data.viewData);
                    }
                }
            });
        }
        var select_branchId = $('#branchId').val();
        if (select_branchId != '') {
            $.ajax({
                url: "{{route('user.getBranchData')}}",
                type: "post",
                dataType: "json",
                data: {branch_id: select_branchId},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data != '') {
                        $('#branchDataShow').html(data.viewData);
                    }
                }
            });
        }
    });
</script>
@stop

