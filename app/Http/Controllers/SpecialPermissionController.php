<?php
namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\PhaseToSubject;
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
class SpecialPermissionController extends Controller {

    public function index() {

        $data['type'] = array('1' => __('label.TAE'), '2' => __('label.EPE'));
        $data['typeList'] = array('' => __('label.SELECT_TYPE_OPT'), '1' => __('label.REGULAR'), '2' => __('label.IRREGULAR'), '3' => __('label.RESCHEDULE'));

        // load the view and pass the TAE index
        return view('specialpermission.index', $data);
    }

    public function showTaeEpe() {
        $typeTaeEpeId = Input::get('type_tae_epe');
        $typeId = Input::get('type');
        //Get Current date time
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        if ($typeTaeEpeId == '1') {
            $taeList = Tae::select('tae.id as id', DB::raw("CONCAT(tae.title,' | Submission Deadline: ', DATE_FORMAT(tae.deadline, '%Y-%m-%d')) AS title"))
                            ->where('tae.result_publish', '>=', DB::raw("'" . $currentDateTime . "'"))
                            ->where('tae.status', '1')->where('type', $typeId)->orderBy('id', 'DESC')->pluck('title', 'id');
            $data['taeList'] = array('' => __('label.SELECT_TAE_OPT')) + $taeList;
        } else {
            $epeList = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title, ' | Examination Date: ', DATE_FORMAT(epe.exam_date, '%Y-%m-%d')) AS title"))
                            ->where('epe.result_publish', '>=', DB::raw("'" . $currentDateTime . "'"))
                            ->where('epe.status', '1')->where('type', $typeId)->orderBy('id', 'DESC')->pluck('title', 'id');
            $data['epeList'] = array('' => __('label.SELECT_EPE_OPT')) + $epeList;
        }

        $data['typeTaeEpeId'] = $typeTaeEpeId;
        $returnHTML = view('specialpermission/show_tae_epe', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function showStudentList() {
        

        $rules = array(
            'type_tae_epe' => 'required',
            'type' => 'required'
        );

        $messages = array(
            'type_tae_epe.required' => 'TAE/EPE must be selected!',
            'type.required' => 'Type must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $typeTaeEpe = Input::get('type_tae_epe');
        $type = Input::get('type');

        if ($typeTaeEpe == 1) {

            $taeId = Input::get('tae_id');
            $courseIdArr = $partIdArr = $branchIdArr = array();
            if ($type == '1') {
                //Get Regular TAE information for this TAE
                $taeObjArr = Tae::leftJoin('phase_to_subject', function($join) {
                                    $join->on('tae.course_id', '=', 'phase_to_subject.course_id');
                                    $join->on('tae.part_id', '=', 'phase_to_subject.part_id');
                                    $join->on('tae.phase_id', '=', 'phase_to_subject.phase_id');
                                    $join->on('tae.subject_id', '=', 'phase_to_subject.subject_id');
                                })
                                ->select('tae.*', 'phase_to_subject.branch_id')
                                ->where('tae.id', $taeId)->with(array('subject'))->first();

                $courseIdArr[] = $taeObjArr->course_id;
                $partIdArr[] = $taeObjArr->part_id;
                if (!empty($taeObjArr->branch_id)) {
                    $branchIdArr[] = $taeObjArr->branch_id;
                }
            } else {
                //Get Irregular/Reschdule TAE information for this TAE
                $taeObjArr = Tae::where('tae.id', $taeId)->with(array('subject', 'taeDetail'))->first();

                if (!empty($taeObjArr['taeDetail'])) {
                    foreach ($taeObjArr['taeDetail'] as $key => $taeDetail) {
                        $courseIdArr[] = $taeDetail->course_id;
                        $partIdArr[] = $taeDetail->part_id;
                        if (!empty($taeDetail->branch_id)) {
                            $branchIdArr[] = $taeDetail->branch_id;
                        }
                    }
                }
            }

            //Finding the get all students list for this course & subject
            $studentList = User::join('student_details', 'users.id', '=', 'student_details.user_id')
                    ->join('course', 'course.id', '=', 'student_details.course_id')
                    ->select(
                            'student_details.id', 'student_details.user_id', 'student_details.course_id', 'student_details.part_id', 'users.program_id', 'users.rank_id', 'users.appointment_id', 'users.branch_id', 'users.service_no', 'users.registration_no', 'users.iss_no', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name"), 'users.official_name', 'users.email', 'users.phone_no', 'course.title', DB::raw("0 AS status")
                    )
                    ->whereIn('student_details.course_id', $courseIdArr)
                    ->whereIn('student_details.part_id', $partIdArr);
            if (!empty($branchIdArr)) {
                $studentList = $studentList->whereIn('users.branch_id', $branchIdArr);
            }
            $studentList = $studentList->orderBy('course.title')->orderBy('users.registration_no')->with(array('rank', 'appointment', 'branch', 'program'))->get();


            //Get pass regular students list for this subject 
            $regularTaePassArr = Tae::join('attendee_records', 'tae.id', '=', 'attendee_records.tae_id')
                    ->select(
                            'attendee_records.student_id as student_id', 'attendee_records.status as status'
                    )
                    ->whereIn('tae.course_id', $courseIdArr)
                    ->whereIn('tae.part_id', $partIdArr)
                    ->where('tae.subject_id', $taeObjArr->subject_id)
                    ->where('attendee_records.status', 4)//4 = Pass
                    ->groupBy('attendee_records.student_id')
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //Get regular students list for this subject already submitted but DS not marking
            $regularSubmittedTaeArr = Tae::join('attendee_records', 'tae.id', '=', 'attendee_records.tae_id')
                    ->join('tae_to_student', function($join) {
                        $join->on('attendee_records.tae_id', '=', 'tae_to_student.tae_id');
                        $join->on('attendee_records.student_id', '=', 'tae_to_student.student_id');
                    })
                    ->select(
                            'attendee_records.student_id as student_id', 'attendee_records.status as status'
                    )
                    ->whereIn('tae.course_id', $courseIdArr)
                    ->whereIn('tae.part_id', $partIdArr)
                    ->where('tae.subject_id', $taeObjArr->subject_id)
                    ->where('attendee_records.status', 1)//1 = pending
                    ->where('tae_to_student.status', 1)//Student finally assignment submitted
                    ->groupBy('attendee_records.student_id')
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //Tae Regular pass/submitted student marge
            $regularTaePassList = $regularTaePassArr + $regularSubmittedTaeArr;

            //Get irregular/reschdule only pass students list for this subject
            $irregReschTaePassArr = Tae::join('tae_details', 'tae_details.tae_id', '=', 'tae.id')
                    ->join('attendee_records', 'attendee_records.tae_details_id', '=', 'tae_details.id')
                    ->select(
                            'attendee_records.student_id as student_id', 'attendee_records.status as status'
                    )
                    ->whereIn('tae_details.course_id', $courseIdArr)
                    ->whereIn('tae_details.part_id', $partIdArr)
                    ->where('tae.subject_id', $taeObjArr->subject_id)
                    ->where('attendee_records.status', 4)//4 = pass
                    ->groupBy('attendee_records.student_id')
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //Get irregular/reschdule students list for this subject already submitted TAE but DS not marking
            $irregReschSubmittedTaeArr = Tae::join('tae_details', 'tae_details.tae_id', '=', 'tae.id')
                    ->join('attendee_records', 'attendee_records.tae_details_id', '=', 'tae_details.id')
                    ->join('tae_to_student', function($join) {
                        $join->on('attendee_records.tae_id', '=', 'tae_to_student.tae_id');
                        $join->on('attendee_records.student_id', '=', 'tae_to_student.student_id');
                    })
                    ->select(
                            'attendee_records.student_id as student_id', 'attendee_records.status as status'
                    )
                    ->whereIn('tae_details.course_id', $courseIdArr)
                    ->whereIn('tae_details.part_id', $partIdArr)
                    ->where('tae.subject_id', $taeObjArr->subject_id)
                    ->where('attendee_records.status', 1)//1 = pending
                    ->where('tae_to_student.status', 1)
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //Irregular/reschdule TAE pass/submitted student marge
            $irregReschTaePassList = $irregReschTaePassArr + $irregReschSubmittedTaeArr;

            //Get Failed students list for this TAE
            $taeFailedStudentArr = AttendeeRecord::where('tae_id', $taeObjArr->id)
                    ->where('status', 5)
                    ->pluck('status', 'student_id');

            //Array marge regular/irregular/reschdule pass, pending and this TAE failed student list
            $nonEligibleTaeStudentsList = $regularTaePassList + $irregReschTaePassList + $taeFailedStudentArr;

            //Non-eligible student remove from main studentList array 
            if (!empty($studentList)) {
                foreach ($studentList as $key => $value) {

                    if (array_key_exists($value->id, $nonEligibleTaeStudentsList)) {
                        unset($studentList[$key]);
                    }
                }
            }

            //Get students list for this TAE already CC Taken/Absent 
            $ccTakenAbsentArr = AttendeeRecord::where('tae_id', $taeObjArr->id)
                    ->whereIn('status', array(2, 3))
                    ->pluck('status', 'student_id');

            if (!empty($studentList)) {
                foreach ($studentList as $key => $value) {
                    if (array_key_exists($value->id, $ccTakenAbsentArr)) {
                        /**
                         * Assign value for status filed
                         * Default 0 meaning Not Submitted, 2 = CC Taken & 3 = Absent
                         */
                        $studentList[$key]->status = $ccTakenAbsentArr[$value->id];
                    }
                }
            }

            $data['studentList'] = $studentList;
            $data['taeObjArr'] = $taeObjArr;

            $returnHTML = view('specialpermission/show_tae_student_list', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        } else if ($typeTaeEpe == 2) {

            $epeId = Input::get('epe_id');
            $courseIdArr = $partIdArr = $branchIdArr = array();
            if ($type == '1') {
                //Get Regular EPE information for this TAE
                $epeObjArr = Epe::leftJoin('phase_to_subject', function($join) {
                                    $join->on('epe.course_id', '=', 'phase_to_subject.course_id');
                                    $join->on('epe.part_id', '=', 'phase_to_subject.part_id');
                                    $join->on('epe.phase_id', '=', 'phase_to_subject.phase_id');
                                    $join->on('epe.subject_id', '=', 'phase_to_subject.subject_id');
                                })
                                ->select('epe.*', 'phase_to_subject.branch_id')
                                ->where('epe.id', $epeId)->with(array('subject'))->first();

                $courseIdArr[] = $epeObjArr->course_id;
                $partIdArr[] = $epeObjArr->part_id;

                if (!empty($epeObjArr->branch_id)) {
                    $branchIdArr[] = $epeObjArr->branch_id;
                }
            } else {
                //Get Irregular/Reschdule EPE information for this TAE
                $epeObjArr = Epe::where('epe.id', $epeId)->with(array('subject', 'epeDetail'))->first();

                if (!empty($epeObjArr['epeDetail'])) {
                    foreach ($epeObjArr['epeDetail'] as $key => $epeDetail) {
                        $courseIdArr[] = $epeDetail->course_id;
                        $partIdArr[] = $epeDetail->part_id;

                        if (!empty($epeDetail->branch_id)) {
                            $branchIdArr[] = $epeDetail->branch_id;
                        }
                    }
                }
            }

            //Finding the get all students list for this course & subject
            $studentList = User::join('student_details', 'users.id', '=', 'student_details.user_id')
                    ->join('course', 'course.id', '=', 'student_details.course_id')
                    ->select(
                            'student_details.id', 'student_details.user_id', 'student_details.course_id', 'student_details.part_id', 'users.program_id', 'users.rank_id', 'users.appointment_id', 'users.branch_id', 'users.service_no', 'users.registration_no', 'users.iss_no', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name"), 'users.official_name', 'users.email', 'users.phone_no', 'course.title', DB::raw("0 AS status")
                    )
                    ->whereIn('student_details.course_id', $courseIdArr)
                    ->whereIn('student_details.part_id', $partIdArr);
            if (!empty($branchIdArr)) {
                $studentList = $studentList->whereIn('users.branch_id', $branchIdArr);
            }
            $studentList = $studentList->orderBy('course.title')->orderBy('users.registration_no')->with(array('rank', 'appointment', 'branch', 'program'))->get();

            //Get pass regular students list for this subject 
            $regularEpePassArr = Epe::join('attendee_records', 'epe.id', '=', 'attendee_records.epe_id')
                    ->select('attendee_records.student_id as student_id', 'attendee_records.status as status')
                    ->whereIn('epe.course_id', $courseIdArr)
                    ->whereIn('epe.part_id', $partIdArr)
                    ->where('epe.subject_id', $epeObjArr->subject_id)
                    ->where('attendee_records.status', 4)
                    ->groupBy('attendee_records.student_id')
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            $regularSubmittedEpeArr = Epe::join('attendee_records', 'epe.id', '=', 'attendee_records.epe_id')
                    ->select('attendee_records.student_id as student_id', 'attendee_records.status as status')
                    ->join('epe_mark', function($join) {
                        $join->on('attendee_records.epe_id', '=', 'epe_mark.epe_id');
                        $join->on('attendee_records.student_id', '=', 'epe_mark.student_id');
                    })
                    ->whereIn('epe.course_id', $courseIdArr)
                    ->whereIn('epe.part_id', $partIdArr)
                    ->where('epe.subject_id', $epeObjArr->subject_id)
                    ->where('attendee_records.status', 1)//1 = pending
                    ->where('epe_mark.submitted', 2)//2 = Student subjective submitted
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //EPE Regular pass/submitted student marge
            $regularEpePassList = $regularEpePassArr + $regularSubmittedEpeArr;

            //Get irregular/reschdule only pass students list for this subject
            $irregReschEpePassArr = Epe::join('epe_details', 'epe.id', '=', 'epe_details.epe_id')
                    ->join('attendee_records', 'attendee_records.epe_details_id', '=', 'epe_details.id')
                    ->select('attendee_records.student_id as student_id', 'attendee_records.status as status')
                    ->whereIn('epe_details.course_id', $courseIdArr)
                    ->whereIn('epe_details.part_id', $partIdArr)
                    ->where('epe.subject_id', $epeObjArr->subject_id)
                    ->where('attendee_records.status', 4)
                    ->groupBy('attendee_records.student_id')
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //Get irregular/reschdule students list for this subject already submitted EPE but CI Mark not locked
            $irregReschSubmittedEpeArr = Epe::join('epe_details', 'epe.id', '=', 'epe_details.epe_id')
                    ->join('attendee_records', 'attendee_records.epe_details_id', '=', 'epe_details.id')
                    ->join('epe_mark', function($join) {
                        $join->on('attendee_records.epe_id', '=', 'epe_mark.epe_id');
                        $join->on('attendee_records.student_id', '=', 'epe_mark.student_id');
                    })
                    ->select('attendee_records.student_id as student_id', 'attendee_records.status as status')
                    ->whereIn('epe_details.course_id', $courseIdArr)
                    ->whereIn('epe_details.part_id', $partIdArr)
                    ->where('epe.subject_id', $epeObjArr->subject_id)
                    ->where('attendee_records.status', 1)//1 = pending
                    ->where('epe_mark.submitted', 2)//2 = Student subjective submitted
                    ->groupBy('attendee_records.student_id')
                    ->orderBy('attendee_records.id', 'DESC')
                    ->pluck('status', 'student_id');

            //Irregular/reschdule EPE pass/submitted student marge
            $irregReschEpePassList = $irregReschEpePassArr + $irregReschSubmittedEpeArr;

            //Get Failed students list for this EPE
            $epeFailedStudentArr = AttendeeRecord::where('epe_id', $epeObjArr->id)
                    ->where('status', 5)
                    ->pluck('status', 'student_id');

            //Array marge regular/irregular/reschdule pass, pending and this EPE failed student list
            $nonEligibleEpeStudentsList = $regularEpePassList + $irregReschEpePassList + $epeFailedStudentArr;

            //Submitted EPE Remove student from main studentList array 
            if (!empty($studentList)) {
                foreach ($studentList as $key => $value) {

                    if (array_key_exists($value->id, $nonEligibleEpeStudentsList)) {
                        unset($studentList[$key]);
                    }
                }
            }

            //Get students list for this EPE already CC Taken/Absent 
            $ccTakenAbsentArr = AttendeeRecord::where('epe_id', $epeObjArr->id)
                    ->whereIn('status', array(2, 3))
                    ->pluck('status', 'student_id');

            if (!empty($studentList)) {
                foreach ($studentList as $key => $value) {
                    if (array_key_exists($value->id, $ccTakenAbsentArr)) {
                        /**
                         * Assign value for status filed
                         * Default 0 meaning Not Submitted, 2 = CC Taken & 3 = Absent
                         */
                        $studentList[$key]->status = $ccTakenAbsentArr[$value->id];
                    }
                }
            }

            $data['studentList'] = $studentList;
            $data['epeObjArr'] = $epeObjArr;
            $returnHTML = view('specialpermission/show_epe_student_list', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        }
    }

    //This function use for Show TAE CC Taken & Absent Form
    public function showTaeStatusForm() {
        
        $studentId = Input::get('student_id');
        $taeId = Input::get('tae_id');
        $status = Input::get('status');
        //Get student info
        $studentInfoArr = Student::join('users', 'users.id', '=', 'student_details.user_id')
                ->select(
                        'student_details.id', 'users.registration_no', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('student_details.id', $studentId)
                ->first();
        //Get TAE Info
        $taeArr = Tae::find($taeId);

        if (empty($studentInfoArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.STUDENT_WAS_NOT_FOUND')), 401);
        }

        if (empty($taeArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
        }

        $data['studentInfoObj'] = $studentInfoArr;
        $data['taeInfoObj'] = $taeArr;
        $data['status'] = $status;

        $returnHTML = view('specialpermission/show_tae_status_form', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function for TAE Student assign marks
    public function storeTaeSpecialPermission() {

        

        $rules = array(
            'student_id' => 'required|numeric',
            'tae_id' => 'required|numeric',
            'status' => 'required|numeric',
            'remarks' => 'required'
        );

        $messages = array(
            'student_id.required' => 'Student could\'t found!',
            'tae_id.required' => 'TAE could\'t found',
            'status.required' => 'Status must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $studentId = Input::get('student_id');
        $taeId = Input::get('tae_id');
        //Get student info
        $studentInfoArr = Student::join('users', 'users.id', '=', 'student_details.user_id')
                ->select(
                        'student_details.id', 'student_details.course_id', 'student_details.part_id', 'users.registration_no', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('student_details.id', $studentId)
                ->first();
        //Get TAE Info
        $taeArr = Tae::find($taeId);

        if (empty($studentInfoArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.STUDENT_WAS_NOT_FOUND')), 401);
        }

        if (empty($taeArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
        }

        $statusText = (Input::get('status') == '2') ? __('label.CC_TAKEN_FOR') : __('label.ABSENT_FOR');

        $attendeeRecord = new AttendeeRecord;
        $attendeeRecord->type = 1;
        $attendeeRecord->tae_id = $taeId;
        $attendeeRecord->student_id = $studentId;
        $attendeeRecord->status = Input::get('status');
        $attendeeRecord->remarks = Input::get('remarks');

        if (in_array($taeArr->type, array('2', '3'))) {
            $taeDetailsInfo = TaeDetail::where('tae_id', $taeId)->where('course_id', $studentInfoArr->course_id)->where('part_id', $studentInfoArr->part_id)->first();
            $attendeeRecord->tae_details_id = $taeDetailsInfo->id;
        }

        if ($attendeeRecord->save()) {
            return Response::json(array('success' => TRUE, 'data' => $studentInfoArr, 'message' => $statusText . ' ' . $studentInfoArr->student_name . ' (' . $studentInfoArr->registration_no . ')'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => __('label.CC_TAKEN_ABSENT_FAILED'), 'message' => __('label.CC_TAKEN_ABSENT_COULD_NOT_BE_SET')), 401);
        }
    }

    //This function use for Show EPE CC Taken & Absent Form
    public function showEpeStatusForm() {

        
        $studentId = Input::get('student_id');
        $epeId = Input::get('epe_id');
        $status = Input::get('status');
        //Get student info
        $studentInfoArr = Student::join('users', 'users.id', '=', 'student_details.user_id')
                ->select(
                        'student_details.id', 'users.registration_no', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('student_details.id', $studentId)
                ->first();
        //Get EPE Info
        $epeArr = Epe::find($epeId);

        if (empty($studentInfoArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.STUDENT_WAS_NOT_FOUND')), 401);
        }

        if (empty($epeArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
        }

        $data['studentInfoObj'] = $studentInfoArr;
        $data['epeInfoObj'] = $epeArr;
        $data['status'] = $status;

        $returnHTML = view('specialpermission/show_epe_status_form', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function for EPE Student assign marks
    public function storeEpeSpecialPermission() {
        

        $rules = array(
            'student_id' => 'required|numeric',
            'epe_id' => 'required|numeric',
            'status' => 'required|numeric',
            'remarks' => 'required'
        );

        $messages = array(
            'student_id.required' => 'Student could\'t found!',
            'epe_id.required' => 'EPE could\'t found',
            'status.required' => 'Status must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $studentId = Input::get('student_id');
        $epeId = Input::get('epe_id');
        //Get student info
        $studentInfoArr = Student::join('users', 'users.id', '=', 'student_details.user_id')
                ->select(
                        'student_details.id', 'student_details.course_id', 'student_details.part_id', 'users.registration_no', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('student_details.id', $studentId)
                ->first();
        //Get TAE Info
        $epeArr = Epe::find($epeId);

        if (empty($studentInfoArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.STUDENT_WAS_NOT_FOUND')), 401);
        }

        if (empty($epeArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
        }

        $statusText = (Input::get('status') == '2') ? __('label.CC_TAKEN_FOR') : __('label.ABSENT_FOR');

        $attendeeRecord = new AttendeeRecord;
        $attendeeRecord->type = 2;
        $attendeeRecord->epe_id = $epeId;
        $attendeeRecord->student_id = $studentId;
        $attendeeRecord->status = Input::get('status');
        $attendeeRecord->remarks = Input::get('remarks');
        if (in_array($epeArr->type, array('2', '3'))) {
            $epeDetailsInfo = EpeDetail::where('epe_id', $epeId)->where('course_id', $studentInfoArr->course_id)->where('part_id', $studentInfoArr->part_id)->first();
            $attendeeRecord->epe_details_id = $epeDetailsInfo->id;
        }

        if ($attendeeRecord->save()) {
            return Response::json(array('success' => TRUE, 'data' => $studentInfoArr, 'message' => $statusText . ' ' . $studentInfoArr->student_name . ' (' . $studentInfoArr->registration_no . ')'), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => __('label.CC_TAKEN_ABSENT_FAILED'), 'message' => __('label.CC_TAKEN_ABSENT_COULD_NOT_BE_SET')), 401);
        }
    }

    //This function use for pdf file show
    public function showFile() {
        $id = Input::get('id');
        $assignmentInfoObjArr = TaeToStudent::find($id);
        return Response::json(array('success' => TRUE, 'data' => $assignmentInfoObjArr), 200);
    }

    //This function use for assignment file download
    public function assignmentDownload($id) {

        $submittedAssignmentInfo = TaeToStudent::find($id);
        if (empty($submittedAssignmentInfo)) {
            Session::flash('success', __('label.ASSIGNMENT_COULD_NOT_FOUND'));
            return Redirect::to('taetostudent');
        }
        //PDF file is stored under project/public/upload/info.pdf
        $file = public_path() . '/uploads/assignment/' . $submittedAssignmentInfo->assignment;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, 'TAE-submitted-assignment-' . date("Ymd") . '.pdf', $headers);
    }

    public function taeOrEpeUndoTaken() {

        $studentId = Input::get('student_id');
        $taeId = Input::get('tae_id');
        $epeId = Input::get('epe_id');
        $status = Input::get('status');
        $typeTaeEpe = Input::get('type_tae_epe');
        $type = Input::get('type');


        $attendeeRecord = AttendeeRecord::where('student_id', $studentId)
                ->where('status', $status);
        if (!empty($taeId)) {
            $attendeeRecord = $attendeeRecord->where('tae_id', $taeId);
        }
        if (!empty($epeId)) {
            $attendeeRecord = $attendeeRecord->where('epe_id', $epeId);
        }
        $attendeeRecord = $attendeeRecord->delete();

        $data['typeTaeEpe'] = $typeTaeEpe;
        $data['taeId'] = $taeId;
        $data['epeId'] = $epeId;
        $data['type'] = $type;

        if ($attendeeRecord) {
            if ($status == '2') {
                $undeMessage = __('label.UNDO_CC_TAKEN_SUCCESSFULLY');
                $undeError = __('label.UNDO_CC_TAKEN_FAILED');
            } elseif ($status == '3') {
                $undeMessage = __('label.UNDO_ABSENT_SUCCESSFULLY');
                $undeError = __('label.UNDO_ABSENT_FAILED');
            }
            return Response::json(array('success' => TRUE, 'data' => $data, 'message' => $undeMessage), 200);
        } else {
            return Response::json(array('success' => false, 'message' => $undeError), 401);
        }
    }

}

?>
