@extends('layouts.default.master')
@section('data_count')
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
                        @if(isset($userInfo->photo))
                        <img  src="{{URL::to('/')}}/public/uploads/user/{{$userInfo->photo}}" class="img-responsive" alt="{{ $userInfo->first_name.' '.$userInfo->last_name }}">
                        @else
                        <img  src="{{URL::to('/')}}/public/img/unknown.png" class="img-responsive" alt="{{ $userInfo->first_name.' '.$userInfo->last_name }}">
                        @endif
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name"> {{$userInfo->first_name.' '.$userInfo->last_name}} </div>
                        <div class="profile-usertitle-job"> {{$userInfo->rank->title}} ({{$userInfo->designation->title}})</div>
                        <div class="profile-usertitle-job">{{$userInfo->branch->name}}</div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                    <!-- SIDEBAR BUTTONS -->
                    <div class="profile-userbuttons">
                        <button type="button" class="btn btn-circle red btn-sm tooltips" title="{{__('label.MY_REGISTRATION_NO')}}">{{$userInfo->registration_no}}</button>
                        <button type="button" class="btn btn-circle green btn-sm tooltips" title="{{__('label.MY_SERVICE_NO')}}">{{$userInfo->service_no}}</button>
                    </div>
                    <!-- END SIDEBAR BUTTONS -->
                    <!-- SIDEBAR MENU -->
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li class="active">
                                <a href="{{URL::to('student/student_profile/'.$userId)}}">
                                    <i class="icon-home"></i> {{__('label.OVERVIEW')}} </a>
                            </li>
                            <li>
                                <a href="{{URL::to('student/account_setting/'.$userId)}}">
                                    <i class="icon-settings"></i> {{__('label.ACCOUNT_SETTINGS')}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- END MENU -->
                </div>
                <!-- END PORTLET MAIN -->
                <!-- PORTLET MAIN -->
                <div class="portlet light ">
                    <div>
                        <h4 class="profile-desc-title">{{$userInfo->first_name.' '.$userInfo->last_name}} </h4>
                        <div class="margin-top-20 profile-desc-link">
                            <i class="fa fa-th"></i>
                            <a href="#">{{$userInfo->service_no}}</a>
                        </div>
                        <div class="margin-top-20 profile-desc-link">
                            <i class="fa fa-phone"></i>
                            <a href="#">{{$userInfo->phone_no}}</a>
                        </div>
                        <div class="margin-top-20 profile-desc-link">
                            <i class="fa fa-envelope"></i>
                            <a href="#">{{$userInfo->email}}</a>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET MAIN -->
            </div>

                </div>
               

<!-- END CONTENT BODY -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<link href="{{ asset('public/assets/pages/css/profile.min.css')}}" rel="stylesheet" type="text/css"></link>
<script src="{{ asset('public/assets/global/plugins/typeahead/typeahead.css')}}" type="text/javascript"></script>


<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on("click", "#saveChildrenInformation", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-children-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    name: $('#nameOfChild').val(),
                    birthday: $('#dateOfBirth').val(),
                    education_level: $('#educationLevel').val(),
                    gender: $('input[name=gender]:checked').val(),
                    order: $('#childrenOrder').val()
                },
                success: function (response) {
                    toastr.success("Children information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                    location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });


        // ****************** Ajax Code for Family Information *****************
        $(document).on("click", "#saveFamilyInformation", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-family-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    name: $('#nameOfperson').val(),
                    type: $('#rltype').val(),
                    age: $('#age').val(),
                    occupation: $('#occupation').val(),
                    gender: $('input[name=gender]:checked').val(),
                    order: $('#orderFamilyInformation').val(),
                    address: $('#address').val()
                },
                success: function (response) {
                    toastr.success("Family information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Family Information*********//

        // ****************** Ajax Code for Civil Education Information *****************
        $(document).on("click", "#savecivilInformation", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-civil-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    institute: $('#nameOfInstitute').val(),
                    year: $('#year').val(),
                    examination: $('#examination').val(),
                    result: $('#result').val()
                },
                success: function (response) {
                    toastr.success("Civil Education information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Civil Education Information*********//

        // ****************** Ajax Code for Service Record Information *****************
        $(document).on("click", "#saveServiceInformation", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-service-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    organization: $('#nameOfOrganization').val(),
                    appointment_held: $('#appointment_held').val(),
                    year: $('#ServiceYear').val()
                },
                success: function (response) {
                    toastr.success("Service Record information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Service Record Information*********//
        // ****************** Ajax Code for Honor/Award Information *****************
        $(document).on("click", "#saveHonorInformation", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-honor-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    awards: $('#nameOfAward').val(),
                    year: $('#AwardOfyear').val(),
                    reason: $('#reason').val()

                },
                success: function (response) {
                    toastr.success("Honor/Award information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Honor/Award Information*********//

        // ****************** Ajax Code for Punishment Information *****************
        $(document).on("click", "#savePunishmentInformation", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-punishment-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    punishment: $('#nameOfPunishment').val(),
                    year: $('#PunishmentOfyear').val(),
                    reason: $('#Punishmentreason').val()

                },
                success: function (response) {
                    toastr.success("Punishment information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Punishment Information*********//

        // ****************** Ajax Code for Relative in Defence Services*****************
        $(document).on("click", "#saveDefenceService", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-defence-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    rank: $('#RankTitle').val(),
                    name: $('#nameOfRank').val(),
                    service_location: $('#serviceofLocation').val(),
                    relation_student: $('#relationwithStudent').val(),
                    remark: $('#remarkofRank').val(),

                },
                success: function (response) {
                    toastr.success("Relative Defence created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Relative in Defence Services *********//

        // ****************** Ajax Code for Course Attended*****************
        $(document).on("click", "#saveCourseAttended", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-course-attended-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    course: $('#nameofCourse').val(),
                    institution: $('#nameOfInstitution').val(),
                    year: $('#courseofYear').val(),
                    grading: $('#Grading').val()

                },
                success: function (response) {
                    toastr.success("Course Attended created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Course Attended Information *********//

        // ****************** Ajax Code for Return to Unit *****************
        $(document).on("click", "#saveReturnUnit", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-unit-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    title: $('#nameOfUnit').val(),
                    description: $('#unitDescription').val()

                },
                success: function (response) {
                    toastr.success("Return to Unit created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }
                }
            });
        });

        //*********** End Ajax Code For Return to Unit *********//
        // ****************** Ajax Code for Medical Details Information *****************
        $(document).on("click", "#saveMedicalInfo", function () {

            toastr.info("Loading...", "Please Wait...", {"closeButton": true, "positionClass": "toast-bottom-right"});

            $.ajax({
                url: "{{ URL::to('ajaxresponse/create-medical-information') }}",
                type: "POST",
                data: {
                    user_id: $('#userId').val(),
                    category: $('#medical_category').val(),
                    blood_group: $('#bltype').val(),
                    date_of_birth: $('#MedicaldateOfBirth').val(),
                    height: $('#height').val(),
                    weight: $('#weight').val(),
                    over_weight: $('#OverWeight').val(),
                    any_disease: $('input[name=any_disease]:checked').val(),
                    disease_description: $('#DescriptionoFDisease').val()

                },
                success: function (response) {
                    toastr.success("Medical Details Information created successfully!", "Success", {"closeButton": true, "positionClass": "toast-bottom-right"});
//                  location.reload();
                    window.location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true, "positionClass": "toast-top-right"});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true, "positionClass": "toast-top-right"});
                    }

                }
            });
        });

        //*********** End Ajax Code For Medical Details Information *********//
        
        // ****************** Ajax Code for children edit *****************
        $(document).on('click', '#getChildren', function (e) {
            e.preventDefault();

            var childrenId = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-children') }}",
                type: "GET",
                data: {
                    id:childrenId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        //*********** End Ajax Code For Edit Children Information *********//
        
        //This function use for update student children information
        $(document).on('submit', '#updateChildrenInformation', function (event) {
            event.preventDefault();
            var childrenInfo = $("#updateChildrenInformation").serialize();
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_child') }}",
                type: "POST",
                data: childrenInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Children information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        //*********** End Ajax Code For Edit Children Information *********//
        // ****************** Ajax Code for civil education info edit *****************
        $(document).on('click', '#getCivilEducationInfo', function (e) {
            e.preventDefault();

            var civilEducationId = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-civil-education') }}",
                type: "GET",
                data: {
                    id:civilEducationId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        //This function use for update student children information
        $(document).on('submit', '#updateCivilEducationInfo', function (event) {
            event.preventDefault();
            var updateCivilInfo = $("#updateCivilEducationInfo").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_civil') }}",
                type: "POST",
                data: updateCivilInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Civil education information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for brothers & sisters info edit *****************
        $(document).on('click', '#getFamilyInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-family-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateFamilyInformation', function (event) {
            event.preventDefault();
            var updateFamilyInfo = $("#updateFamilyInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_family') }}",
                type: "POST",
                data: updateFamilyInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Family information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for service record info edit *****************
        $(document).on('click', '#getServiceInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-service-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateServiceInformation', function (event) {
            event.preventDefault();
            var updateServiceInfo = $("#updateServiceInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_service') }}",
                type: "POST",
                data: updateServiceInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Service information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for Award info edit *****************
        $(document).on('click', '#getAwardInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-award-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateAwardInformation', function (event) {
            event.preventDefault();
            var updateServiceInfo = $("#updateAwardInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_award') }}",
                type: "POST",
                data: updateServiceInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Award information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for punishment info edit *****************
        $(document).on('click', '#getPunishmentInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-punishment-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updatePunishmentInformation', function (event) {
            event.preventDefault();
            var updateInfo = $("#updatePunishmentInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_punishment') }}",
                type: "POST",
                data: updateInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Punishment information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for relative in defence info edit *****************
        $(document).on('click', '#getDefenceInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-defence-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateDefenceInformation', function (event) {
            event.preventDefault();
            var updateInfo = $("#updateDefenceInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_defence') }}",
                type: "POST",
                data: updateInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Defence information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for unit information info edit *****************
        $(document).on('click', '#getUnitInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-unit-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateUnitInformation', function (event) {
            event.preventDefault();
            var updateUnitInfo = $("#updateUnitInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_unit') }}",
                type: "POST",
                data: updateUnitInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Unit information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for course info edit *****************
        $(document).on('click', '#getCourseInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-course-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateCourseInformation', function (event) {
            event.preventDefault();
            var updateCourseInfo = $("#updateCourseInformation").serialize();
            
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_course') }}",
                type: "POST",
                data: updateCourseInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Course information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
        
        // ****************** Ajax Code for course info edit *****************
        $(document).on('click', '#getMedicalInfo', function (e) {
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/edit-medical-info') }}",
                type: "GET",
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose:true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
             });
        });
        
        $(document).on('submit', '#updateMedicalInformation', function (event) {
            event.preventDefault();
            var updateMedicalInfo = $("#updateMedicalInformation").serialize();
           
            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('student/update_medical') }}",
                type: "POST",
                data: updateMedicalInfo,
                dataType: "json",
                success: function (response) {
                    toastr.success("Medical information updated successfully", "Success", {"closeButton": true});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    
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
