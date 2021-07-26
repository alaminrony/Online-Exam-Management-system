<?php

namespace App\Http\Controllers;

use Validator;
use App\Epe;
use App\User;
use App\ExamToStudent;
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
use Response;
use Illuminate\Http\Request;

class ExamToStudentController extends Controller {

    public function __construct() {
        $this->programId = 1;
    }

    public function index(Request $request) {

        $currentStudentId = Auth::user()->id;
        if (Auth::user()->group_id == 4) {
            $examList = Epe::join('course', 'course.id', '=', 'epe.course_id')
                            ->join('subject_to_ds', function($join) use ($currentStudentId) {
                                $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                                $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                            })
                            ->select('epe.id as id', B::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                            ->where('epe.type', '1')
                            ->where('epe.status', 1)
                            ->orderBy('epe.exam_date', 'DESC')
                            ->pluck('title', 'id')->toArray();
        } else {
            //$epeList = Epe::where('status', 1)->where('type', '1')->pluck('title', 'id'); 
            $examList = Epe::where('epe.type', 1)
                            ->where('epe.status', 1)
                            ->orderBy('epe.exam_date', 'DESC')
                            ->select(DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"), 'epe.id')
                            ->pluck('title', 'id')->toArray();
        }
        $data['examList'] = array('' => __('label.SELECT_EXAM_OPT')) + $examList;

        return view('examtostudent.index', $data);
    }

    public function saveStudent(Request $request) {
        $examId = $request->exam_id;
        $employeeIdArr = $request->employee_id;

        $data = array();
        if (!empty($employeeIdArr)) {
            $i = 0;
            foreach ($employeeIdArr as $key => $employeeId) {
                $data[$i]['exam_id'] = $examId;
                $data[$i]['employee_id'] = $employeeId;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }

        $deletePrevious = ExamToStudent::where('exam_id', $examId)->delete();
        $targets = ExamToStudent::insert($data);
        if ($targets) {
            return Response::json(array('success' => TRUE, 'data' => __('label.STUDENT_HAS_BEEN_ASSIGNED_TO_EXAM')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Assigned Failed', 'message' => __('label.STUDENT_COULD_NOT_BE_ASSIGNED')), 401);
        }
    }

    public function getStudent(Request $request) {

        $examId = $request->exam_id;
        $studentArr = User::leftJoin('exam_to_student', function($join) use($examId) {
                    $join->on('exam_to_student.employee_id', '=', 'users.id');
                    $join->where('exam_to_student.exam_id', '=', $examId);
                })->join('branch', 'branch.id', '=', 'users.branch_id')
                ->join('region', 'region.id', '=', 'branch.region_id')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                ->join('department', 'department.id', '=', 'users.department_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.username', 'branch.name as branch_name'
                        , 'region.name as region_name', 'cluster.name as cluster_name', 'exam_to_student.employee_id'
                        , 'department.name as department_name','designation.title as designation_title')
                ->where('users.group_id', '=', 3)
                ->where('users.status', 'active')
                ->orderBy('exam_to_student.employee_id', 'DESC')
                ->get();


        $exmToStudentArr = ExamToStudent::where('exam_id', $examId)->pluck('employee_id', 'employee_id')->toArray();
        $noOfAssignStudent = count($exmToStudentArr);
//        Helper::dump($noOfAssignStudent);
        $data['studentArr'] = $studentArr;
        $data['examId'] = $examId;
        $data['exmToStudentArr'] = $exmToStudentArr;
        $data['noOfAssignStudent'] = $noOfAssignStudent;

        $returnHTML = view('examtostudent/getStudent', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getAssignedStudent(Request $request) {
        $examId = $request->exam_id;
        $studentArr = User::join('exam_to_student', function($join) use($examId) {
                    $join->on('exam_to_student.employee_id', '=', 'users.id');
                    $join->where('exam_to_student.exam_id', '=', $examId);
                })->join('branch', 'branch.id', '=', 'users.branch_id')
                ->join('region', 'region.id', '=', 'branch.region_id')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                ->join('department', 'department.id', '=', 'users.department_id')
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.username', 'branch.name as branch_name'
                        , 'region.name as region_name', 'cluster.name as cluster_name', 'exam_to_student.employee_id'
                        , 'department.name as department_name')
                ->where('users.group_id', '=', 3)
                ->where('users.status', 'active')
                ->orderBy('exam_to_student.employee_id', 'DESC')
                ->get();
        $data['studentArr'] = $studentArr;

        $returnHTML = view('examtostudent/getAssignedStudent', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}

?>