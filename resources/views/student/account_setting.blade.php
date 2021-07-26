@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PROFILE SIDEBAR -->
            <div class="profile-sidebar">
                <!-- PORTLET MAIN -->
                <div class="portlet light profile-sidebar-portlet ">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        @if(isset($userInfoArr->photo))
                            <img  src="{{URL::to('/')}}/public/uploads/user/{{$userInfoArr->photo}}" class="img-responsive" alt="{{ $userInfoArr->first_name.' '.$userInfoArr->last_name }}">
                        @else
                            <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive" alt="{{ $userInfoArr->first_name.' '.$userInfoArr->last_name }}">
                        @endif
                        <!--<img src="{{URL::to('/')}}/public/assets/pages/media/profile/profile_user.jpg" class="img-responsive" alt="">--> 
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name"> {{$userInfoArr->first_name.' '.$userInfoArr->last_name}} </div>
                        <div class="profile-usertitle-job"> {{$userInfoArr->rank->title}} ({{$userInfoArr->appointment->title}})</div>
                        <div class="profile-usertitle-job">{{$userInfoArr->branch->name}}</div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                     <!-- SIDEBAR BUTTONS -->
                    <div class="profile-userbuttons">
                        <button type="button" class="btn btn-circle red btn-sm tooltips" title="{{trans('english.MY_REGISTRATION_NO')}}">{{$userInfoArr->registration_no}}</button>
                        <button type="button" class="btn btn-circle green btn-sm tooltips" title="{{trans('english.MY_SERVICE_NO')}}">{{$userInfoArr->service_no}}</button>
                    </div>
                    <!-- END SIDEBAR BUTTONS -->
                    <!-- SIDEBAR MENU -->
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li>
                                <a href="{{URL::to('student/student_profile/'.$userId)}}">
                                    <i class="icon-home"></i> {{trans('english.OVERVIEW')}} </a>
                            </li>
                            <li class="active">
                                <a href="{{URL::to('student/account_setting/'.$userId)}}">
                                    <i class="icon-settings"></i> {{trans('english.ACCOUNT_SETTINGS')}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- END MENU -->
                </div>
                <!-- END PORTLET MAIN -->
            </div>
            <!-- END BEGIN PROFILE SIDEBAR -->
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">Basic Information</span>
                                </div>
                                <div class="portlet portlet-title nav-tabs">
                                    <a href="javascript:;" class="btn btn-outline btn-circle btn-sm green account-setting">
                                        <i class="fa fa-edit"></i> {{trans('english.EDIT')}} 
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                {{ Form::open(array('role' => 'form', 'url' => 'student/basic_information', 'id'=>'createStudentBasicInformation')) }}
                                <div class="tab-content" id="display-basic-information-from">
                                    <div class="portlet-body">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <tr>
                                                <td class="col-md-6">Service ID Card No</td>
                                                <td class="col-md-6">{{$studentInfoArr->id_card_no}}</td>
                                            </tr>
                                            <tr>
                                                <td>Present Organization/Unit</td>
                                                <td>{{$studentInfoArr->present_organization}}</td>
                                            </tr>
                                            <tr>
                                                <td>Place of Birth</td>
                                                <td>{{$studentInfoArr->place_of_birth}}</td>
                                            </tr>
                                            <tr>
                                                <td>Religion</td>
                                                <td>{{ucfirst($studentInfoArr->religion)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Nationality</td>
                                                <td>{{$studentInfoArr->nationality}}</td>
                                            </tr>
                                            <tr>
                                                <td>Gender</td>
                                                <td>{{ucfirst($studentInfoArr->gender)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Marital Status</td>
                                                <td>{{ucfirst($studentInfoArr->marital_status)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Course in Bangladesh Air Force Academy</td>
                                                <td>{{$studentInfoArr->course_in_bangladesh_aire_force_academy}}</td>
                                            </tr>
                                            <tr>
                                                <td>Father's Name</td>
                                                <td>{{$studentInfoArr->fathers_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Mother's Name</td>
                                                <td>{{$studentInfoArr->mothers_name}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="{{$userId}}" id="userId"/>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
{{ HTML::style('public/assets/pages/css/profile.min.css'); }}
<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    $(document).ready(function () {
        //This function use for show the student basic from
        $(document).on("click", ".account-setting", function () {
            
            $.ajax({
                url: "{{ URL::to('student/basic_info_from') }}",
                type: "GET",
                data: {user_id: $("#userId").val()},
                success: function (response) {
                    $('#display-basic-information-from').html(response.html)
                    $( "#commissionDdate" ).datepicker({dateFormat: "yyyy-mm-dd", autoclose:true});
                    $("[name='approintment_in_bangladesh_military_academy']").bootstrapSwitch();
                    $(".account-setting").hide();
                    
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if(jqXhr.status == 500){
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                    }else if(jqXhr.status == 401){
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    }else{
                        toastr.error("Something went wrong", "Error", {"closeButton": true});
                    }
                }
            });
        });
        
        //This function use for save student basic information
        $("#createStudentBasicInformation").submit(function (event) {
            event.preventDefault();
            var datastring = $("#createStudentBasicInformation").serialize();
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/basic_info') }}",
                type: "POST",
                data: datastring,
                dataType: "json",
                success: function (response) {
                    toastr.success("Student basic information updated successfully", "Success", {"closeButton": true});
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    var errorsHtml= '';
                    if(jqXhr.status == 400){
                        var errors = jqXhr.responseJSON.message;
                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>' + value[0] + '</li>'; 
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                    }else if(jqXhr.status == 500){
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                    }else if(jqXhr.status == 401){
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    }else{
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                    
                }
            });
        });
    });
</script>
@stop
