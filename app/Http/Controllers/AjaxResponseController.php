<?php

namespace App\Http\Controllers;

use Validator;
use App\Epe;
use App\EpeQusTypeDetails;
use App\EpeToQuestion;
use App\QuestionType;
use App\MockTest;
use App\User;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Image;
use Response;
use Illuminate\Http\Request;

class AjaxResponseController extends Controller {

    public function getIndex() {
        echo 'Ajax Page';
    }

    //This function use for show all part
    public function getPartList() {

        $courseId = Input::get('course_id');
        //Get part list
        $partList = Part::orderBy('title')->select('id', 'title')->get();
        $data['partList'] = $partList;

        $relatedPart = DB::table('relate_part_with_course')->where('course_id', '=', $courseId)->lists('part_id', 'id');
        $data['relatedPartArr'] = $relatedPart;

        //IF part already assign (Relate Phase with Part)
        $hasRelationPhaseArr = RelatePhaseWithPart::select('id', 'part_id')->where('course_id', '=', $courseId)->groupBy('part_id')->lists('part_id', 'id');
        $data['hasRelationPhaseArr'] = $hasRelationPhaseArr;
//        return View::make('ajax/show_part', $data);
        $returnHTML = View::make('ajax/show_part', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for Relate Part with Course/Student managemnet
    public function getRelatePartList() {

        $courseId = Input::get('course_id');
        //Get part list
        $partList = DB::table('relate_part_with_course')
                ->join('part', 'part.id', '=', 'relate_part_with_course.part_id')
                ->where('relate_part_with_course.course_id', '=', $courseId)
                ->select('part.id', 'part.title')
                ->orderBy('part.order', 'ASC')
                ->get();
        if (empty($partList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => trans('english.NO_PART_OF_THIS_COURDE_IS_TO_ASSIGN')), 401);
        }
        return Response::json(array('success' => true, 'parts' => $partList), 200);
    }

    //This function use for Create TAE Show subject
    public function postShowSubject() {

        $courseId = Input::get('course_id');
        $partId = Input::get('part_id');
        //Get part list
        $subjectList = DB::table('phase_to_subject')
                ->join('subject', 'subject.id', '=', 'phase_to_subject.subject_id')
                ->where('phase_to_subject.course_id', '=', $courseId)
                ->where('phase_to_subject.part_id', '=', $partId)
                ->select('subject.id as id ', DB::raw("CONCAT(subject.title, ' (', subject.code, ')') AS title"))
                ->orderBy('subject.order', 'ASC')
                ->get();

        if (empty($subjectList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => trans('english.NO_SUBJECT_OF_THIS_PART_IS_NOT_ASSIGN')), 401);
        }
        return Response::json(array('success' => true, 'subjects' => $subjectList), 200);
    }

    //This function use for create children information
    public function postCreateChildrenInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $childrenArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();
        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => trans('english.STUDENT_WAS_NOT_FOUND')), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'name' => 'required',
            'birthday' => 'required'
        );

        $message = array(
            'name.required' => 'Please give the name of child!',
            'birthday.required' => 'Please give the Date of Birth'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        //$message = $validator->errors();

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $childrenInfo = new ChildrenInfo;
        $childrenInfo->student_id = $childrenArray->id;
        $childrenInfo->name = Input::get('name');
        $childrenInfo->education_level = Input::get('education_level');
        $childrenInfo->birthday = Input::get('birthday');
        $childrenInfo->gender = Input::get('gender');
        $childrenInfo->order = Input::get('order');
        if ($childrenInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Children added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // *********** Ajax Family Information ***************
    public function postCreateFamilyInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $studentArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'name' => 'required',
            'age' => 'required'
        );

        $message = array(
            'name.required' => 'Please give the name of Family Person!',
            'age.required' => 'Please fill the age field'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        //$message = $validator->errors();

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $familyInfo = new FamilyInfo;
        $familyInfo->student_id = $studentArray->id;
        $familyInfo->name = Input::get('name');
        $familyInfo->type = Input::get('type');
        $familyInfo->age = Input::get('age');
        $familyInfo->occupation = Input::get('occupation');
        $familyInfo->gender = Input::get('gender');
        $familyInfo->order = Input::get('order');
        $familyInfo->address = Input::get('address');

        if ($familyInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Family Information added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Family Information *****************
    // *********** Ajax Civil Education Information ***************
    public function postCreateCivilInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $civilArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();
        /*
          echo '<pre>';
          print_r(Input::get()) ;
          exit; */

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'institute' => 'required',
            'examination' => 'required',
            'result' => 'required'
        );

        $message = array(
            'institute.required' => 'Please give the name of Institution!',
            'examination.required' => 'Please fill the Examination Field',
            'result.required' => 'Please Enter Result'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $civilInfo = new CivilInfo;
        $civilInfo->student_id = $civilArray->id;
        $civilInfo->institute = Input::get('institute');
        $civilInfo->year = Input::get('year');
        $civilInfo->examination = Input::get('examination');
        $civilInfo->result = Input::get('result');

        if ($civilInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Civil Education  Information added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Civil Education Information *****************
    // *********** Ajax Service Record Information ***************
    public function postCreateServiceInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $serviceArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();
        /*
          echo '<pre>';
          print_r(Input::get()) ;
          exit; */

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'organization' => 'required',
            'appointment_held' => 'required',
        );

        $message = array(
            'organization.required' => 'Please give the name of Organization!',
            'appointment_held.required' => 'Please fill the Appointment Field',
            'year.Integer' => 'Please Provide Year in yyyy Format'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $serviceInfo = new ServiceInfo;
        $serviceInfo->student_id = $serviceArray->id;
        $serviceInfo->organization = Input::get('organization');
        $serviceInfo->appointment_held = Input::get('appointment_held');
        $serviceInfo->year = Input::get('year');


        if ($serviceInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Service Record Information added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Service Record Information *****************
    // *********** Ajax Honor/Award Information ***************
    public function postCreateHonorInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $awardArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();
        /*
          echo '<pre>';
          print_r(Input::get()) ;
          exit; */


        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'awards' => 'required',
            'year' => 'required'
        );

        $message = array(
            'organization.required' => 'Please give the name of Organization!',
            'year.required' => 'Please fill the Year Field'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $awardInfo = new AwardInfo;
        $awardInfo->student_id = $awardArray->id;
        $awardInfo->awards = Input::get('awards');
        $awardInfo->reason = Input::get('reason');
        $awardInfo->year = Input::get('year');


        if ($awardInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Service Record Information added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Honor/Award Information *****************
    // *********** Ajax Punishment Information ***************
    public function postCreatePunishmentInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $punishmentArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();

        /*                echo '<pre>';
          print_r(Input::get()) ;
          exit; */


        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'punishment' => 'required',
            'year' => 'required'
        );

        $message = array(
            'punishment.required' => 'Please give the name of Punishment!',
            'year.required' => 'Please fill the Year Field'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $punishmentInfo = new PunishmentInfo;
        $punishmentInfo->student_id = $punishmentArray->id;
        $punishmentInfo->punishment = Input::get('punishment');
        $punishmentInfo->reason = Input::get('reason');
        $punishmentInfo->year = Input::get('year');


        if ($punishmentInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Punishment Information added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Punishment Information *****************
    // *********** Ajax Relative Defence Information ***************
    public function postCreateDefenceInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $defenceArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();

        /*        echo '<pre>';
          print_r(Input::get()) ;
          exit; */

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'rank' => 'required',
            'name' => 'required'
        );

        $message = array(
            'rank.required' => 'Please give the name of Rank!',
            'name.required' => 'Please fill the name Field'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $defenceInfo = new DefenceInfo;
        $defenceInfo->student_id = $defenceArray->id;
        $defenceInfo->rank = Input::get('rank');
        $defenceInfo->name = Input::get('name');
        $defenceInfo->service_location = Input::get('service_location');
        $defenceInfo->relation_student = Input::get('relation_student');
        $defenceInfo->remark = Input::get('remark');


        if ($defenceInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Relative Defence added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Relative Defence Information *****************
    // *********** Ajax Course Attended Information ***************
    public function postCreateCourseAttendedInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $courseArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();

        /*        echo '<pre>';
          print_r(Input::get()) ;
          exit; */

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'course' => 'required',
            'year' => 'required'
        );

        $message = array(
            'course.required' => 'Please give the name of course!',
            'year.required' => 'Please fill the year Field'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $courseInfo = new CourseInfo;
        $courseInfo->student_id = $courseArray->id;
        $courseInfo->course = Input::get('course');
        $courseInfo->institution = Input::get('institution');
        $courseInfo->year = Input::get('year');
        $courseInfo->grading = Input::get('grading');


        if ($courseInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Relative Defence added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax Course Attended *****************
    // *********** Ajax Code for Return to Unit Information ***************
    public function postCreateUnitInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $unitArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();

        /*        echo '<pre>';
          print_r(Input::get()) ;
          exit; */

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'title' => 'required'
        );

        $message = array(
            'title.required' => 'Please give the name of Title!'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $unitInfo = new UnitInfo;
        $unitInfo->student_id = $unitArray->id;
        $unitInfo->title = Input::get('title');
        $unitInfo->description = Input::get('description');


        if ($unitInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Return to unit added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax for Return to Unit *****************
    // *********** Ajax Code for Return to Unit Information ***************
    public function postCreateMedicalInformation() {

        $userInfo = User::find(Input::get('user_id'));
        $medicalArray = Student::where('user_id', Input::get('user_id'))->select('id')->first();

        /*                echo '<pre>';
          print_r(Input::get()) ;
          exit; */

        if (empty($userInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Unauthorised', 'message' => 'Student was not found'), 401);
        }

        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules = array(
            'category' => 'required'
        );

        $message = array(
            'category.required' => 'Please give the name of Category!'
        );

        $validator = Validator::make(Input::all(), $rules, $message);
        /* $message = $validator->errors(); */

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $medicalInfo = new MedicalInfo;
        $medicalInfo->student_id = $medicalArray->id;
        $medicalInfo->category = Input::get('category');
        $medicalInfo->blood_group = Input::get('blood_group');
        $medicalInfo->date_of_birth = Input::get('date_of_birth');
        $medicalInfo->height = Input::get('height');
        $medicalInfo->weight = Input::get('weight');
        $medicalInfo->over_weight = Input::get('over_weight');
        $medicalInfo->any_disease = Input::get('any_disease');
        $medicalInfo->disease_description = Input::get('disease_description');


        if ($medicalInfo->save()) {
            return Response::json(array('success' => TRUE, 'data' => 'Medical Information added success'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => 'Please, try again'), 401);
        }
    }

    // ********** End Ajax for Return to Unit *****************
    //This function use for get children edit information
    public function getEditChildren() {
        $id = Input::get('id');
        // get the children information
        $childrenInfoArr = ChildrenInfo::find($id);
        $data['childrenInfoArr'] = $childrenInfoArr;
        $returnHTML = View::make('ajax/edit_children', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get children edit information
    public function getEditCivilEducation() {
        $id = Input::get('id');
        // get the children information
        $civilInfoArr = CivilInfo::find($id);

        $data['civilInfoArr'] = $civilInfoArr;
        $returnHTML = View::make('ajax/edit_civil_education', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get family information
    public function getEditFamilyInfo() {
        $id = Input::get('id');
        // get the children information
        $familyInfoArr = FamilyInfo::find($id);

        $data['familyInfoArr'] = $familyInfoArr;
        $returnHTML = View::make('ajax/edit_family_info', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get service information
    public function getEditServiceInfo() {
        $id = Input::get('id');
        // get the children information
        $serviceInfoArr = ServiceInfo::find($id);

        $data['serviceInfoArr'] = $serviceInfoArr;
        $returnHTML = View::make('ajax/edit_service', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get award information
    public function getEditAwardInfo() {
        $id = Input::get('id');
        // get the children information
        $awardInfoArr = AwardInfo::find($id);

        $data['awardInfoArr'] = $awardInfoArr;
        $returnHTML = View::make('ajax/edit_award', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get punishment information
    public function getEditPunishmentInfo() {
        $id = Input::get('id');
        // get the children information
        $punishmentInfoArr = PunishmentInfo::find($id);

        $data['punishmentInfoArr'] = $punishmentInfoArr;
        $returnHTML = View::make('ajax/edit_punishment', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get DefenceInfo information
    public function getEditDefenceInfo() {
        $id = Input::get('id');
        // get the children information
        $defenceInfoArr = DefenceInfo::find($id);

        $data['defenceInfoArr'] = $defenceInfoArr;
        $returnHTML = View::make('ajax/edit_defence', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get unit information
    public function getEditUnitInfo() {
        $id = Input::get('id');
        // get the children information
        $unitInfoArr = UnitInfo::find($id);

        $data['unitInfoArr'] = $unitInfoArr;
        $returnHTML = View::make('ajax/edit_unit', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get course information
    public function getEditCourseInfo() {
        $id = Input::get('id');
        // get the children information
        $courseInfoArr = CourseInfo::find($id);

        $data['courseInfoArr'] = $courseInfoArr;
        $returnHTML = View::make('ajax/edit_course', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for get medical information
    public function getEditMedicalInfo() {
        $id = Input::get('id');
        // get the children information
        $medicalInfoArr = MedicalInfo::find($id);

        $data['medicalInfoArr'] = $medicalInfoArr;
        $returnHTML = View::make('ajax/edit_medical', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getUserInfo() {
        $userId = Input::get('user_id');
        $userInfo = User::join('program', 'program.id', '=', 'users.program_id', 'left')
                ->join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('appointment', 'appointment.id', '=', 'users.appointment_id', 'left')
                ->where('users.id', $userId)
                ->select('users.*', 'program.name as program_name', 'user_group.name as group_name', 'rank.title as rank_title', 'appointment.title as appointment_title', 'branch.name as branch_name')
                ->first();

        $data['userInfo'] = $userInfo;
        $returnHTML = View::make('ajax/user_info', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //uploading userprofile picture
    public function getChangePicture() {
        $id = Input::get('id');
        // get the children information
        $userInfo = User::find($id);
        $data['userInfo'] = $userInfo;
        $returnHTML = View::make('ajax/change_picture', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //notice details
    public function getNoticeDetails() {
        $noticeId = Input::get('notice_id');
        // get the children information
        $noticeDetails = Notice::find($noticeId);
        $data['noticeDetails'] = $noticeDetails;
        $returnHTML = View::make('ajax/notice_details', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getStudentInfo() {
        $userId = Input::get('user_id');
        $studentInfo = User::join('program', 'program.id', '=', 'users.program_id', 'left')
                ->join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('appointment', 'appointment.id', '=', 'users.appointment_id', 'left')
                ->join('student_details', 'student_details.user_id', '=', 'users.id', 'inner')
                ->join('course2', 'course2.id', '=', 'student_details.course_id', 'inner')
                ->where('users.id', $userId)
                ->select('users.*', 'program.name as program_name', 'user_group.name as group_name', 'rank.title as rank_title', 'appointment.title as appointment_title', 'branch.name as branch_name', 'course2.title as course2_title')
                ->first();
        $data['studentInfo'] = $studentInfo;
        $returnHTML = View::make('ajax/student_info', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getMockInfo(Request $request) {
        $mockId = $request->mock_id;

        $mockTestInfo = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('mock_test.id', $mockId)
                ->select('epe.subject_id', 'epe.title as epe_title', 'subject.title as subject_title', 'mock_test.*')
                ->first();
        $data['mockTestInfo'] = $mockTestInfo;
        $returnHTML = view('ajax/mock_info', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getEpeInfo(Request $request) {
        $epeId = $request->epe_id;

        $epeArr = Epe::join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('epe.id', $epeId)
                ->select('subject.title as subject_title', 'epe.*')
                ->first();



        $qusTypeArr = QuestionType::orderBy('id', 'asc')->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id');
        
        $qusQusTypeDetailList = [];
        if (!empty($epeArr)) {
            $qusQusTypeDetailList = EpeQusTypeDetails::where('epe_id', $epeArr->id)->pluck('total_qustion', 'qustion_type_id');
        }

        $data['epeArr'] = $epeArr;
        $data['qusTypeArr'] = $qusTypeArr;
        $data['qusQusTypeDetailList'] = $qusQusTypeDetailList;
        $returnHTML = view('ajax/epeInfo', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getIrregularEpeInfo() {
        $epeId = Input::get('epe_id');
        $epeArr = Epe::where('epe.id', $epeId)->with(array('subject', 'epeDetail', 'epeDetail.course', 'epeDetail.part', 'epeDetail.phase', 'epeDetail.branch'))->first();

        $data['epeArr'] = $epeArr;
        $returnHTML = View::make('ajax/irregular_epe_info', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getStudentNoticeDetails() {
        $noticeId = Input::get('notice_id');
        // get the children information
        $noticeDetails = Notice::find($noticeId);
        $data['noticeDetails'] = $noticeDetails;
        $returnHTML = View::make('ajax/student_notice_details', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getStudentDetails(Request $request) {
        $userId = $request->user_id;

        $target = User::join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('region', 'region.id', '=', 'branch.region_id', 'left')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id', 'left')
                ->join('designation', 'designation.id', '=', 'users.designation_id', 'left')
                ->join('department', 'department.id', '=', 'users.department_id', 'left')
                ->where('users.id', $userId)
                ->select('users.*', 'user_group.name as group_name'
                        , 'rank.title as rank_title', 'designation.title as designation_title'
                        , 'branch.name as branch_name','department.name as department_name','region.name as region_name'
                        ,'cluster.name as cluster_name')
                ->first();
        
        $data['target'] = $target;

        $returnHTML = view('ajax/student_details', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}
