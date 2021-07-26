<?php

namespace App\Http\Controllers;

use Validator;
use App\Student;
use App\User;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Input;
use Image;
use Illuminate\Http\Request;

class StudentController extends Controller {

    private $programId;

    public function __construct() {

        Validator::extend('complexPassword', function($attribute, $value, $parameters) {

            $password = $parameters[0];
            if (preg_match('/^\S*(?=\S{8,})(?=\S*[A-Z])(?=\S*[a-z])(?=\S*[0-9])(?=\S*[`~!?@#$%^&*()\-_=+{}|;:,<.>])(?=\S*[\d])\S*$/', Input::get('password'))) {
                return true;
            }

            return false;
        });

    }

    public function index() {

        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student

        $branchId = Input::get('branch_id');
        $rankId = Input::get('rank_id');
        $appointmentIid = Input::get('appointment_id');
        $searchText = Input::get('search_text');
        $accountConfirmed = Input::get('account_confirmed');
        if ($this->programId == 1) {
            $studentArr = User::join('student_details', 'users.id', '=', 'student_details.user_id')
                    ->select(
                            'iss_no', 'jc_sc_index', 'users.id', 'users.program_id'
                            , 'users.group_id', 'users.rank_id', 'users.appointment_id', 'users.branch_id'
                            , DB::raw('DATE_FORMAT(student_details.commission_date, "%d.%m.%Y") as commission_date')
                            , 'users.maximum_tenure', 'users.service_no', 'users.registration_no'
                            , 'users.first_name', 'users.last_name', 'users.official_name'
                            , 'users.email', 'users.phone_no', 'users.username', 'users.photo'
                            , 'users.status', 'student_details.user_id', 'student_details.id_card_no'
                            , 'users.password_changed'
                    )
                    ->where('users.group_id', '=', 5)
                    ->where('users.program_id', '=', $this->programId);
        }
        if (!empty($rankId)) {
            $studentArr = $studentArr->where('users.rank_id', '=', $rankId);
        }

        if (!empty($appointmentIid)) {
            $studentArr = $studentArr->where('users.appointment_id', '=', $appointmentIid);
        }

        if (!empty($branchId)) {
            $studentArr = $studentArr->where('users.branch_id', '=', $branchId);
        }

        if (!empty($searchText)) {
            $studentArr->where(function ($query) use ($searchText) {
                $query->where('users.username', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.service_no', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.jc_sc_index', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.iss_no', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.registration_no', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('users.official_name', 'LIKE', '%' . $searchText . '%');
            });
        }

        if ($accountConfirmed == 1) {
            $studentArr->where('password_changed', 1);
        } else if ($accountConfirmed == 2) {
            $studentArr->whereNull('password_changed');
        }

        $studentArr = $studentArr->orderBy('users.registration_no', 'DESC')->orderBy('users.iss_no', 'DESC')->orderBy('users.username')->with(array('UserGroup', 'rank', 'appointment', 'branch', 'program'))->paginate(Session::get('paginatorCount'));
        $data['studentArr'] = $studentArr;
        //dd(DB::getQueryLog());
        //Get rank list
        $rankList = DB::table('rank')->where('status', '=', 'active')->orderBy('order')->pluck('title', 'id');
        $data['rankList'] = array('' => '--Select Rank--') + $rankList;

        //Get approinment list
        $appointmentList = DB::table('appointment')->where('status', '=', 'active')->orderBy('order')->pluck('title', 'id');
        $data['appointmentList'] = array('' => '--Select Approintment--') + $appointmentList;

        //Get branch list
        $branchList = Branch::orderBy('order')
                ->select('id', DB::raw("CONCAT(name, ' » ',short_name) AS name"))
                ->where('status', 1)
                ->pluck('name', 'id');
        $data['branchList'] = array('' => __('label.SELECT_BRANCH_OPT')) + $branchList;


//        dd(DB::getQueryLog());
        //Get approinment list
        $accountConfirmedStatus = array('' => __('label.SELECT_STATUS_OPT'), '1' => __('label.YES'), '2' => __('label.NO'));
        $data['accountConfirmedStatus'] = $accountConfirmedStatus;

        // load the view and pass the user index
        return view('student.index', $data);
    }

    public function filter() {
        $rankId = Input::get('rank_id');
        $branchId = Input::get('branch_id');
        $appointmentIid = Input::get('appointment_id');
        $searchText = Input::get('search_text');
        $accountConfirmed = Input::get('account_confirmed');
        return Redirect::to('student?rank_id=' . $rankId . '&appointment_id=' . $appointmentIid . '&branch_id=' . $branchId . '&account_confirmed=' . $accountConfirmed . '&search_text=' . $searchText);
    }

    public function create() {
        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student
        //Get rank list
        $rankList = DB::table('rank')->where('status', '=', 'active')->orderBy('order')->pluck('title', 'id');
        $data['rankList'] = array('' => '--Select Rank--') + $rankList;

        //Get approinment list
        $appointmentList = DB::table('appointment')->where('status', '=', 'active')->orderBy('title')->pluck('title', 'id');
        $data['appointmentList'] = array('' => '--Select Approintment--') + $appointmentList;

        //Get branch list
        $branchList = Branch::orderBy('order')
                ->select('id', DB::raw("CONCAT(name, ' » ',short_name) AS name"))
                ->where('status', 1)
                ->pluck('name', 'id');
        $data['branchList'] = array('' => __('label.SELECT_BRANCH_OPT')) + $branchList;


        $data['status'] = array('active' => 'Active', 'inactive' => 'Inactive');
        return view('student.create', $data);
    }

    public function store() {
        $service_no_precheck = Input::get('service_no');
        if ($this->programId == 1) {
            $studentInfo = DB::table('users')
                            ->join('student_details', 'users.id', '=', 'student_details.user_id')
                            ->select('users.id', 'users.service_no')
                            ->where('users.service_no', '=', $service_no_precheck)
                            ->where('users.program_id', '=', $this->programId)->first();
        }


        $rules = array(
            'rank_id' => 'required',
            'appointment_id' => 'required',
            'branch_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'official_name' => 'required',
            'password' => 'Required|min:8|Confirmed|complex_password:,' . Input::get('password'),
            'password_confirmation' => 'required',
            'username' => 'required|alpha_num|min:4|max:45|unique:users',
            'email' => 'required|email|unique:users',
                //'service_no' => 'required|unique:users,service_no,'.$studentInfo->service_no,
        );


        if (!empty($studentInfo->service_no)) {
            $rules['service_no'] = 'required|unique:users,service_no,' . $studentInfo->service_no;
        }

        if ($this->programId == 1) {
            $rules['iss_no'] = 'required|unique:users';
        }

        if (Input::hasFile('photo')) {
            $rules['photo'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }

        $messages = array(
            'rank_id.required' => 'Rank must be selected!',
            'appointment_id.required' => 'Approiment must be selected!',
            'branch_id.required' => 'Branch must be selected!',
            'first_name.required' => 'Please give the first name',
            'last_name.required' => 'Please give the last Name',
            'username.required' => 'Please give the username',
            'username.unique' => 'That username is already taken',
            'service_no.required' => 'Please give the Service No',
            'service_no.unique' => 'That Service No is already taken',
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
            'iss_no.required' => 'Please give ISS no!',
            'iss_no.unique' => 'That ISS No is already taken',
            'jc_sc_index.required' => 'Please give JC & SC index!',
            'jc_sc_index.unique' => 'That JC & SC index is already taken',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('student/create')
                            ->withErrors($validator)
                            ->withInput($request->except(array('password', 'photo', 'password_confirmation')));
        }


        //User photo upload
        $imageUpload = TRUE;
        $imageName = FALSE;
        if (Input::hasFile('photo')) {
            $file = Input::file('photo');
            $destinationPath = public_path() . '/uploads/user/';
            $filename = uniqid() . $file->getClientOriginalName();
            $uploadSuccess = Input::file('photo')->move($destinationPath, $filename);
            if ($uploadSuccess) {
                $imageName = TRUE;
            } else {
                $imageUpload = FALSE;
            }

            //Create More Small Thumbnails :::::::::::: Resize Image
            $this->load(public_path() . '/uploads/user/' . $filename);
            $this->resize(100, 100);
            $this->save(public_path() . '/uploads/thumbnail/' . $filename);

            //delete original image
            //unlink(public_path() . '/uploads/user' . $filename);
        }

        if ($imageUpload === FALSE) {
            Session::flash('error', 'Image Coul\'d not be uploaded');
            return Redirect::to('student/create')
                            ->withInput($request->except(array('photo', 'password', 'password_confirmation')));
        }

        /** Select student max registration no & generate registration no** */
        $lastRegistrationNo = User::where('group_id', 5)->orderBy('registration_no', 'DESC')->first();

        if (!empty($lastRegistrationNo)) {
            $newRegistrationNo = intval($lastRegistrationNo->registration_no) + 1;
            $registrationLast4Digit = substr($newRegistrationNo, -4);
            $registrationNo = date("Ym") . $registrationLast4Digit;
        } else {
            $registrationNo = date("Ym") . '0001';
        }
        $maximumTenure = date('Y-m-d', strtotime('+3 years'));

        //Send mail for student
        $allData = $request->all();



        DB::beginTransaction();
        try {

            $user = new User;
            $user->group_id = 5;
            $user->program_id = $this->programId;
            $user->registration_no = $registrationNo;
            $user->rank_id = Input::get('rank_id');
            $user->appointment_id = Input::get('appointment_id');
            $user->branch_id = Input::get('branch_id');
            if ($this->programId == 1) {
                $user->iss_no = Input::get('iss_no');
            }
            if ($this->programId == 2) {
                $user->jc_sc_index = Input::get('jc_sc_index');
            }
            $user->first_name = Input::get('first_name');
            $user->maximum_tenure = $maximumTenure;
            $user->last_name = Input::get('last_name');
            $user->official_name = Input::get('official_name');
            if (!empty(Input::get('phone_no'))) {
                $user->phone_no = Input::get('phone_no');
            }
            $user->username = Input::get('username');
            $user->password = Hash::make(Input::get('password'));
            $user->email = Input::get('email');
            $user->service_no = Input::get('service_no');
            if ($imageName !== FALSE) {
                $user->photo = $filename;
            }
            $user->status = Input::get('status');
            $user->save();
            $lastInsertedId = $user->id; //get last inserted record's user id value
            //If group student then data insert student_details table
            $student = new Student;
            $student->user_id = $lastInsertedId;
            if (!empty(Input::get('commission_date'))) {
                $student->commission_date = Input::get('commission_date');
            }

            $student->save();

            DB::commit();


            //Get From mail
            $configurationInfoObjArr = Configuration::first();
            $fromMail = !empty($configurationInfoObjArr->admin_email) ? $configurationInfoObjArr->admin_email : __('label.FROM_MAIL');

            // note, to use $subject within your closure below you have to pass it along in the "use (...)" clause.
            $subject = __('label.YOUE_ACCOUNT_CREDENTIALS');

            Mail::send('emails.send_mail', $allData, function($message) use ($allData, $subject) {
                // note: if you don't set this, it will use the defaults from config/mail.php
                $message->to($allData['email'], $allData['official_name'])
                        ->subject($subject);
            });


            Session::flash('success', $registrationNo . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('student');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', $registrationNo . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('student');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student
        // get the user
        $student = User::join('student_details', 'users.id', '=', 'student_details.user_id')
                        ->select('users.id', 'users.program_id', 'users.group_id', 'users.rank_id'
                                , 'users.appointment_id', 'users.branch_id', 'student_details.id as student_id'
                                , 'student_details.commission_date'
                                , 'users.maximum_tenure', 'users.service_no', 'users.registration_no', 'users.first_name'
                                , 'users.last_name', 'users.official_name', 'users.email', 'users.phone_no', 'users.username'
                                , 'users.photo', 'users.status', 'student_details.user_id', 'student_details.id_card_no'
                                , 'users.iss_no'
                        )
                        ->where('users.id', $id)
                        ->with(array('UserGroup', 'rank', 'appointment', 'branch', 'program'))->first();
        //$student = User::find($id);
        $data['student'] = $student;

        //Get rank list
        $rankList = DB::table('rank')->where('status', '=', 'active')->orderBy('order')->pluck('title', 'id');
        $data['rankList'] = array('' => '--Select Rank--') + $rankList;

        //Get approinment list
        $appointmentList = DB::table('appointment')->where('status', '=', 'active')->orderBy('title')->pluck('title', 'id');
        $data['appointmentList'] = array('' => '--Select Approintment--') + $appointmentList;

        //Get branch list
        $branchList = Branch::orderBy('order')
                ->select('id', DB::raw("CONCAT(name, ' » ',short_name) AS name"))
                ->where('status', 1)
                ->pluck('name', 'id');
        $data['branchList'] = array('' => __('label.SELECT_BRANCH_OPT')) + $branchList;

        $data['status'] = array('active' => 'Active', 'inactive' => 'Inactive');

        // show the edit form and pass the usere
        return view('student.edit', $data);
    }

    public function update($id) {

        // validate
        $rules = array(
            'rank_id' => 'required',
            'appointment_id' => 'required',
            'branch_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'official_name' => 'required',
            'username' => 'required|alpha_num|min:2|max:45|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'service_no' => 'required',
        );

        if ($this->programId == 1) {
            $rules['iss_no'] = 'required|unique:users,iss_no,' . $id;
        }
        if (Input::hasFile('photo')) {
            $rules['photo'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }

        $message = array(
            'rank_id.required' => 'Rank must be selected!',
            'appointment_id.required' => 'Approiment must be selected!',
            'branch_id.required' => 'Branch must be selected!',
            'first_name.required' => 'Please give the first name',
            'last_name.required' => 'Please give the last Name',
            'username.required' => 'Please give the username',
            'username.unique' => 'That username is already taken',
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        if (!empty(Input::get('password'))) {
            $rules['password'] = 'Required|min:8|Confirmed|complex_password:,' . Input::get('password');
            $rules['password_confirmation'] = 'required';
        }

        if ($this->programId == 1) {
            $messages = array(
                'iss_no.required' => 'Please give ISS no!',
                'iss_no.unique' => 'That ISS No is already taken',
            );
        }

        $validator = Validator::make($request->all(), $rules, $message);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('student/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->except('password', 'password_confirmation', 'photo'));
        }


        if (Input::hasFile('photo')) {

            $target = User::where('id', $id)->select('photo')->first();
            $prevfileName = 'public/uploads/user/' . $target->photo;
            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }

            $thumfileName = 'public/uploads/thumbnail/' . $target->photo;
            if (File::exists($thumfileName)) {
                File::delete($thumfileName);
            }
        }
        //User photo upload
        $imageUpload = TRUE;
        $imageName = FALSE;
        if (Input::hasFile('photo')) {
            $file = Input::file('photo');
            $destinationPath = public_path() . '/uploads/user/';
            $filename = uniqid() . $file->getClientOriginalName();
            $uploadSuccess = Input::file('photo')->move($destinationPath, $filename);
            if ($uploadSuccess) {
                $imageName = TRUE;
            } else {
                $imageUpload = FALSE;
            }

            //Create More Small Thumbnails :::::::::::: Resize Image
            $this->load(public_path() . '/uploads/user/' . $filename);
            $this->resize(100, 100);
            $this->save(public_path() . '/uploads/thumbnail/' . $filename);
        }

        if ($imageUpload === FALSE) {
            Session::flash('error', 'Image Coul\'d not be uploaded');
            return Redirect::to('student/' . $id . '/edit')
                            ->withInput($request->except(array('photo', 'password', 'password_confirmation')));
        }

        $password = Input::get('password');

        // store
        $user = User::find($id);
        $studentExistFile = $user->photo;
        $user->rank_id = Input::get('rank_id');
        $user->appointment_id = Input::get('appointment_id');
        $user->branch_id = Input::get('branch_id');
        $user->first_name = Input::get('first_name');
        $user->last_name = Input::get('last_name');
        $user->official_name = Input::get('official_name');
        if (!empty(Input::get('phone_no'))) {
            $user->phone_no = Input::get('phone_no');
        }
        if ($this->programId == 1) {
            $user->iss_no = Input::get('iss_no');
        }
        $user->username = Input::get('username');
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }
        $user->email = Input::get('email');
        $user->service_no = Input::get('service_no');
        if ($imageName !== FALSE) {
            $user->photo = $filename;
        }
        $user->status = Input::get('status');

        DB::beginTransaction();
        try {

            /* =============================== */
            if ($imageName !== FALSE) {
                $studentExistsOrginalFile = public_path() . '/uploads/user/' . $studentExistFile;
                if (file_exists($studentExistsOrginalFile)) {
                    File::delete($studentExistsOrginalFile);
                }//if student uploaded success

                $studentExistsThumbnailFile = public_path() . '/uploads/thumbnail/' . $studentExistFile;
                if (file_exists($studentExistsThumbnailFile)) {
                    File::delete($studentExistsThumbnailFile);
                }//if student uploaded success
            }//if file uploaded success

            $user->save();
            //Update student table
            $student = Student::where('user_id', '=', $id)->first();

            if (!empty(Input::get('commission_date'))) {
                $student->commission_date = Input::get('commission_date');
            }

            $student->save();

            DB::commit();
            Session::flash('success', $user->registration_no . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('student');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', $user->registration_no . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('student/' . $id . '/edit');
        }
    }

    //User Active/Inactive Function
    public function active($id, $param = null) {
        if ($param !== null) {
            $url = 'student?' . $param;
        } else {
            $url = 'student';
        }
        $student = User::find($id);

        if ($student->status == 'active') {
            $student->status = 'inactive';
            $msgText = $student->username . __('label.SUCCESSFULLY_INACTIVATE');
        } else {
            $student->status = 'active';
            $msgText = $student->username . __('label.SUCCESSFULLY_ACTIVATE');
        }
        $student->save();
        // redirect
        Session::flash('success', $msgText);
        return Redirect::to($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student
        //check dependency
        $studentRelatedData = DB::table('student_details')
                ->join('tae_to_student', 'tae_to_student.student_id', '=', 'student_details.id')
                ->where('student_details.user_id', '=', $id)
                ->first();

        if (!empty($studentRelatedData)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_TAE'));
            return Redirect::to('student');
        }


        // delete user table
        $user = User::where('id', '=', $id)->with(array('studentBasicInfo'))->first();

        $studentInfo = Student::where('user_id', $id)->first();
        $hasRelationChildrenInfo = ChildrenInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationChildrenInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_CHILDREN'));
            return Redirect::to('student');
        }

        $hasRelationFamilyInfo = FamilyInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationFamilyInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_FAMILY_INFORMATION'));
            return Redirect::to('student');
        }

        $hasRelationAwardInfo = AwardInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationAwardInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_AWARED_INFORMATION'));
            return Redirect::to('student');
        }

        $hasRelationCivilInfo = CivilInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationCivilInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_CIVILE_EDUCATION_INFORMATION'));
            return Redirect::to('student');
        }

        $hasRelationCourseInfo = CourseInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationCourseInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_COURSE_ATTENDED'));
            return Redirect::to('student');
        }

        $hasRelationServiceInfo = ServiceInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationServiceInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_SERVICE_RECORDS'));
            return Redirect::to('student');
        }

        $hasRelationUnitInfo = UnitInfo::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($hasRelationUnitInfo)) {
            Session::flash('error', __('label.STUDENT_HAS_RELATIONSHIP_WITH_UNIT_INFORMATION'));
            return Redirect::to('student');
        }


        //Check ISSP Dependency
        //For TAE - has been covered at top
        //For EPE/CC/Absent
        $attendeeRecord = AttendeeRecord::where('student_id', $user->studentBasicInfo->id)->first();

        if (!empty($attendeeRecord)) {
            Session::flash('error', __('label.STUDENT_HAS_RECORD_AT_ATTENDEE_INFO'));
            return Redirect::to('student');
        }

        $studentExistsOrginalFile = public_path() . '/uploads/user/' . $user->photo;
        if (file_exists($studentExistsOrginalFile)) {
            File::delete($studentExistsOrginalFile);
        }//if student uploaded success

        $studentExistsThumbnailFile = public_path() . '/uploads/thumbnail/' . $user->photo;
        if (file_exists($studentExistsThumbnailFile)) {
            File::delete($studentExistsThumbnailFile);
        }//if student uploaded success

        DB::beginTransaction();
        try {
            //Student delete from user table
            $user->delete();
            //Student delete from student_details table
            $studentDelete = DB::table('student_details')->where('user_id', '=', $id)->delete();
            Session::flash('success', $user->registration_no . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            DB::commit();
            return Redirect::to('student');
        } catch (Exception $ex) {
            DB::rollback();
            Session::flash('error', $user->registration_no . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student');
        }
    }

    public function change_pass($id, $param = null) {
        if ($param !== null) {
            $url = 'student?' . $param;
        } else {
            $url = 'student';
        }
        $data['next_url'] = $url;
        $data['user_id'] = $id;
        $studentInfo = User::join('program', 'program.id', '=', 'users.program_id', 'left')
                ->join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('appointment', 'appointment.id', '=', 'users.appointment_id', 'left')
                ->join('student_details', 'student_details.user_id', '=', 'users.id', 'inner')
                ->where('users.id', $id)
                ->select('users.*', 'program.name as program_name', 'user_group.name as group_name', 'rank.title as rank_title', 'appointment.title as appointment_title', 'branch.name as branch_name')
                ->first();
        $data['studentInfo'] = $studentInfo;

        return view('student/change_password', $data);
    }

    public function pup() {
        $next_url = Input::get('next_url');

        $rules = array(
            'password' => 'Required|min:8|Confirmed|complex_password:,' . Input::get('password'),
            'password_confirmation' => 'Required',
        );

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('student/cp/' . Input::get('user_id'))
                            ->withErrors($validator)
                            ->withInput($request->all());
        } else {
            $user = User::find(Input::get('user_id'));

            $user->password = Hash::make(Input::get('password'));
            if ($user->save()) {
                Session::flash('success', $user->first_name . ' ' . $user->last_name . __('label.PASSWORD_CHANGE_SUCCESSFUL'));
                return Redirect::to('student');
            } else {
                Session::flash('error', $user->first_name . ' ' . $user->last_name . __('label.PASSWORD_COULDNOT_CHANGE'));
                return Redirect::to('student/cp/' . Input::get('user_id'))->withInput($request->all());
            }
        }
    }

    public function cpself() {

        if (Request::isMethod('post')) {

            $rules = array(
                'oldPassword' => 'Required',
                'password' => 'Required|Confirmed',
                'password_confirmation' => 'Required',
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Redirect::to('student/cpself')
                                ->withErrors($validator)
                                ->withInput($request->all());
            } else {
                $user = User::find(Auth::user()->id);
                if (Hash::check(Input::get('oldPassword'), $user->password)) {
                    $user->password = Hash::make(Input::get('password'));
                    $user->save();
                    Session::flash('success', $user->username . ' ' . __('label.PASSWORD_CHANGE_SUCCESSFUL'));
                    return Redirect::to('student/cpself');
                } else {
                    Session::flash('error', trans('Your current password doesn\'t match'));
                    return Redirect::to('student/cpself');
                }
            }
        }
    }

    //This function use for student profile details
    public function studentProfile(Request $request,$userId) {

        if ((Auth::user()->id != $userId)) {
            Session::flash('error', __('label.ACCESS_DENIED_FOR_USER'));
            return Redirect::to('dashboard');
        }//if group student

        $userInfo = User::where('id', '=', $userId)->with(array('UserGroup', 'rank', 'designation'))->first();
        
        if (empty($userInfo)) {
            Session::flash('error', __('label.THIS_USER_IS_NOT_A_STUDENT'));
            return Redirect::to('student/student_profile/' . $userId);
        }

        // show the edit form and pass the usere
        return view('student.student_profile')->with(compact('userInfo', 'userId'));
    }

    //This function use for student account setting
    public function accountSetting($userId) {
        if (in_array(Auth::user()->group_id, [5]) && (Auth::user()->id != $userId)) {
            Session::flash('error', __('label.ACCESS_DENIED_FOR_USER'));
            return Redirect::to('dashboard/students');
        }//if group student
        //Get student basic information
        $userInfoArr = User::where('id', '=', $userId)->with(array('UserGroup', 'rank', 'appointment', 'studentBasicInfo'))->first();
        $studentInfoArr = Student::where('user_id', '=', $userId)->first();
        if (empty($studentInfoArr)) {
            Session::flash('error', __('label.THIS_USER_IS_NOT_A_STUDENT'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        // show the edit form and pass the usere
        return view('student.account_setting')->with(compact('userInfoArr', 'studentInfoArr', 'userId'));
    }

    //This function use for show student basic information from
    public function basicInfoFrom() {

        $userId = Input::get('user_id');
        if (in_array(Auth::user()->group_id, [5]) && (Auth::user()->id != $userId)) {
            Session::flash('error', __('label.ACCESS_DENIED_FOR_USER'));
            return Redirect::to('dashboard/students');
        }//if group student
        //Get student basic information
        $userInfoArr = User::where('id', '=', $userId)->with(array('UserGroup', 'rank', 'appointment', 'studentBasicInfo'))->first();
        $studentInfo = Student::where('user_id', '=', $userId)->first();
        if (empty($studentInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.STUDENT_WAS_NOT_FOUND')), 401);
        }

        //Get religion list
        $religionList = array(
            '' => '--Select Religion--',
            'Islam' => 'Islam',
            'Hinduism' => 'Hinduism',
            'Buddhists' => 'Buddhists',
            'Christians' => 'Christians',
            'Other' => 'Other'
        );

        //Get countries list
        $countryList = DB::table('countries')->orderBy('name')->pluck('name', 'id');
        $data['countriesList'] = array('' => __('label.SELECT_COUNTRIES_OPT')) + $countryList;

        $returnHTML = view('student/basic_info_from', $data)->with(compact('userInfoArr', 'studentInfo', 'userId', 'religionList'))->render();
        return Response::json(array('success' => true, 'html' => $returnHTML), 200);
    }

    //This function use for student basic information save
    public function saveBasicInformation() {
        $userId = Input::get('user_id');
        $studentId = Input::get('student_id');
        $studentInfo = Student::find($studentId);
        if (empty($studentInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.STUDENT_WAS_NOT_FOUND')), 401);
        }



        $rules = array(
            'id_card_no' => 'required',
            'present_organization' => 'required',
            'place_of_birth' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'marital_status' => 'required'
        );

        $message = array(
            'id_card_no.required' => 'Service id card no is required',
            'present_organization.required' => 'Present Organization/Unit is required',
            'place_of_birth.required' => 'Place of Birth is required',
            'religion.required' => 'Religion is required',
            'gender.required' => 'Gender is required',
            'marital_status.required' => 'Marital Status is required'
        );

        $validator = Validator::make($request->all(), $rules, $message);
        $message = $validator->errors();

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $studentInfo->id_card_no = Input::get('id_card_no');
        $studentInfo->present_organization = Input::get('present_organization');
        $studentInfo->place_of_birth = Input::get('place_of_birth');
        $studentInfo->religion = Input::get('religion');
        $studentInfo->nationality = Input::get('nationality');
        $studentInfo->gender = Input::get('gender');
        $studentInfo->marital_status = Input::get('marital_status');
        $studentInfo->course_in_bangladesh_aire_force_academy = Input::get('course_in_bangladesh_aire_force_academy');
        $studentInfo->out = Input::get('out');
        $studentInfo->fathers_name = Input::get('fathers_name');
        $studentInfo->fathers_occuupation = Input::get('fathers_occuupation');
        $studentInfo->fathers_contact = Input::get('fathers_contact');
        $studentInfo->fathers_detail = Input::get('fathers_detail');
        $studentInfo->mothers_name = Input::get('mothers_name');
        $studentInfo->mothers_occuupation = Input::get('mothers_occuupation');
        $studentInfo->present_address = Input::get('present_address');
        $studentInfo->permanent_address = Input::get('permanent_address');
        if ($studentInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.STUDENT_BASIC_INFORMATION_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updated Failed', 'message' => 'Please, try agin'), 401);
        }
    }

    protected function destroyChildren($id) {

        /*         * ***** Delete for ChildrenInfo *** */
        $childrenObj = ChildrenInfo::find($id);
        $studentId = $childrenObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($childrenObj->delete()) {
            Session::flash('success', $childrenObj->name . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $childrenObj->name . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for ChildrenInfo *** */
    }

    protected function destroyFamily($id) {

        /*         * ***** Delete for FamilyInfo *** */
        $familyObj = FamilyInfo::find($id);
        $studentId = $familyObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($familyObj->delete()) {
            Session::flash('success', $familyObj->name . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $familyObj->name . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for FamilyInfo *** */
    }

    protected function destroyCivil($id) {

        /*         * ***** Delete for CivilInfo *** */
        $civilObj = CivilInfo::find($id);
        $studentId = $civilObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($civilObj->delete()) {
            Session::flash('success', $civilObj->institute . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $civilObj->institute . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for CivilInfo *** */
    }

    protected function destroyService($id) {

        /*         * ***** Delete for ServiceInfo *** */
        $serviceObj = ServiceInfo::find($id);
        $studentId = $serviceObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($serviceObj->delete()) {
            Session::flash('success', $serviceObj->organization . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $serviceObj->organization . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for ServiceInfo *** */
    }

    protected function destroyAward($id) {

        /*         * ***** Delete for AwardInfo *** */
        $awardObj = AwardInfo::find($id);
        $studentId = $awardObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($awardObj->delete()) {
            Session::flash('success', $awardObj->awards . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $awardObj->awards . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for AwardInfo *** */
    }

    protected function destroyPunishment($id) {

        /*         * ***** Delete for PunishmentInfo *** */
        $punishmentObj = PunishmentInfo::find($id);
        $studentId = $punishmentObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($punishmentObj->delete()) {
            Session::flash('success', $punishmentObj->punishment . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $punishmentObj->punishment . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for PunishmentInfo *** */
    }

    protected function destroyDefence($id) {

        /*         * ***** Delete for DefenceInfo *** */
        $defenceObj = DefenceInfo::find($id);
        $studentId = $defenceObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($defenceObj->delete()) {
            Session::flash('success', $defenceObj->name . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $defenceObj->name . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for DefenceInfo *** */
    }

    protected function destroyUnit($id) {

        /*         * ***** Delete for UnitInfo *** */
        $unitObj = UnitInfo::find($id);
        $studentId = $unitObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($unitObj->delete()) {
            Session::flash('success', $unitObj->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $unitObj->title . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for UnitInfo *** */
    }

    protected function destroyCourse($id) {

        /*         * ***** Delete for CourseInfo *** */
        $courseObj = CourseInfo::find($id);
        $studentId = $courseObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($courseObj->delete()) {
            Session::flash('success', $courseObj->course . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $courseObj->course . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for UnitInfo *** */
    }

    protected function destroyMedical($id) {

        /*         * ***** Delete for MedicalInfo *** */
        $medicalObj = MedicalInfo::find($id);
        $studentId = $medicalObj->student_id;

        $studentArr = Student::find($studentId);
        $userId = $studentArr->user_id;
        //echo $userId;


        if ($medicalObj->delete()) {
            Session::flash('success', $medicalObj->category . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('student/student_profile/' . $userId);
        } else {
            Session::flash('error', $medicalObj->category . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('student/student_profile/' . $userId);
        }
        /*         * ***** End Delete for MedicalInfo *** */
    }

    //This function use for student children information update
    protected function updateChild() {

        $id = Input::get('id');
        $childrenInfo = ChildrenInfo::find($id);
        if (empty($childrenInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_CHILDREN_FOUND')), 401);
        }



        $rules = array(
            'name' => 'required',
            'birthday' => 'required'
        );

        $messages = array(
            'name.required' => 'Please give the name of child!',
            'birthday.required' => 'Please give the Date of Birth'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $childrenInfo->name = Input::get('name');
        $childrenInfo->education_level = Input::get('education_level');
        $childrenInfo->birthday = Input::get('birthday');
        $childrenInfo->gender = Input::get('gender');
        $childrenInfo->order = Input::get('order');
        if ($childrenInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Children updated success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student brothe/sister information update
    public function updateFamily() {

        $id = Input::get('id');
        $target = FamilyInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_FAMILY_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'name' => 'required',
            'age' => 'required'
        );

        $messages = array(
            'name.required' => 'Please give the name!',
            'age.required' => 'Please give the age!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->name = Input::get('name');
        $target->type = Input::get('type');
        $target->age = Input::get('age');
        $target->occupation = Input::get('occupation');
        $target->gender = Input::get('gender');
        $target->order = Input::get('order');
        $target->address = Input::get('address');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.FAMILY_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for civil information update
    public function updateCivil() {
        $id = Input::get('id');
        $target = CivilInfo::find($id);
        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_CIVIL_EDUCATION_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'institute' => 'required',
            'result' => 'required'
        );

        $messages = array(
            'institute.required' => 'Please give the institute!',
            'result.required' => 'Please give the result!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->institute = Input::get('institute');
        $target->result = Input::get('result');
        $target->year = Input::get('year');
        $target->examination = Input::get('examination');
        $target->result = Input::get('result');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.CIVIL_EDUCATION_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student service information update
    public function updateService() {

        $id = Input::get('id');
        $target = ServiceInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_SERVICE_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'organization' => 'required',
            'appointment_held' => 'required'
        );

        $messages = array(
            'name.required' => 'Please give the organization!',
            'appointment_held.required' => 'Please give the appointment held!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->organization = Input::get('organization');
        $target->appointment_held = Input::get('appointment_held');
        $target->year = Input::get('year');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.SERVICE_RECORD_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student award information update
    public function updateAward() {

        $id = Input::get('id');
        $target = AwardInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_AWARD_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'awards' => 'required',
            'year' => 'required'
        );

        $messages = array(
            'awards.required' => 'Please give the awards!',
            'year.required' => 'Please give the year!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->awards = Input::get('awards');
        $target->reason = Input::get('reason');
        $target->year = Input::get('year');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.AWARD_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student punishment information update
    public function updatePunishment() {

        $id = Input::get('id');
        $target = PunishmentInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_PUNISHMENT_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'punishment' => 'required',
            'year' => 'required'
        );

        $messages = array(
            'punishment.required' => 'Please give the punishment!',
            'year.required' => 'Please give the year!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->punishment = Input::get('punishment');
        $target->reason = Input::get('reason');
        $target->year = Input::get('year');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.PUNISHMENT_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student defence information update
    public function updateDefence() {

        $id = Input::get('id');
        $target = DefenceInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_DEFENCE_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'rank' => 'required',
            'name' => 'required'
        );

        $messages = array(
            'rank.required' => 'Please give the rank!',
            'name.required' => 'Please give the name!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->rank = Input::get('rank');
        $target->name = Input::get('name');
        $target->service_location = Input::get('service_location');
        $target->relation_student = Input::get('relation_student');
        $target->remark = Input::get('remark');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.PUNISHMENT_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student unit information update
    public function updateUnit() {

        $id = Input::get('id');
        $target = UnitInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_UNIT_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'title' => 'required',
        );

        $messages = array(
            'title.required' => 'Please give the title!',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->title = Input::get('title');
        $target->description = Input::get('description');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.UNIT_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student course information update
    public function updateCourse() {

        $id = Input::get('id');
        $target = CourseInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_COURSE_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'course' => 'required',
        );

        $messages = array(
            'course.required' => 'Please give the course!',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->course = Input::get('course');
        $target->institution = Input::get('institution');
        $target->year = Input::get('year');
        $target->grading = Input::get('grading');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.COURSE_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //This function use for student medical information update
    public function updateMedical() {

        $id = Input::get('id');
        $target = MedicalInfo::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => __('label.NO_MEDICAL_INFORMATION_FOUND')), 401);
        }



        $rules = array(
            'category' => 'required',
        );

        $messages = array(
            'category.required' => 'Please give the category!',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->category = Input::get('category');
        $target->blood_group = Input::get('blood_group');
        $target->date_of_birth = Input::get('date_of_birth');
        $target->height = Input::get('height');
        $target->weight = Input::get('weight');
        $target->over_weight = Input::get('over_weight');
        $target->any_disease = Input::get('any_disease');
        $target->any_disease = Input::get('any_disease');
        $target->disease_description = Input::get('disease_description');

        if ($target->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.MEDICAL_INFORMATION_UPDATED_SUCCESS')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation failed', 'message' => 'Please, try again'), 401);
        }
    }

    //***************************************  Thumbnails Generating Functions :: Start *****************************
    public function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    public function output($image_type = IMAGETYPE_JPEG) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function getWidth() {
        return imagesx($this->image);
    }

    public function getHeight() {
        return imagesy($this->image);
    }

    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    //***************************************  Thumbnails Generating Functions :: End *****************************
}
