<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\EpeMark;
use App\ExamToStudent;
use App\EpeToQuestion;
use App\EpeMarkDetails;
use App\Exports\ExcelExport;
use App\Epe;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Input;
use Response;
use Image;
use DateTime;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ParticipationStatusController extends Controller {

    private $controller = 'ParticipationStatus';

    public function index(Request $request) {

        $targetArr = $examList = [];
        if ($request->generate == 'true') {
            $examList = Epe::pluck('title', 'id')->toArray();
            $enrollExamList = Epe::join('exam_to_student', 'exam_to_student.exam_id', '=', 'epe.id')
                    ->whereBetween('epe.exam_date', [$request->from_date, $request->to_date])
                    ->select(DB::raw("COUNT(exam_to_student.employee_id) as exam_enrolled"), 'exam_to_student.exam_id')
                    ->groupBy('exam_to_student.exam_id')
                    ->pluck('exam_enrolled', 'exam_to_student.exam_id')
                    ->toArray();



            $attendExamList = Epe::join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                    ->whereBetween('epe.exam_date', [$request->from_date, $request->to_date])
                    ->select(DB::raw("COUNT(epe_mark.employee_id) as exam_attendend"), 'epe_mark.epe_id')
                    ->groupBy('epe_mark.epe_id')
                    ->pluck('exam_attendend', 'epe_mark.epe_id')
                    ->toArray();



            if (!empty($enrollExamList)) {
                foreach ($enrollExamList as $examId => $enrolled) {
                    $targetArr[$examId]['enroll'] = $enrolled;
                    $targetArr[$examId]['attendend'] = !empty($attendExamList[$examId]) ? $attendExamList[$examId] : 0;
                    $targetArr[$examId]['absent'] = $targetArr[$examId]['enroll'] - $targetArr[$examId]['attendend'];
                }
            }
        }
        $downloadFileName = 'participationStatus-' . date('d-m-Y');
        
        if ($request->view == 'print') {
            return view('report.participationStatus.print.participationStatus')->with(compact('targetArr', 'request', 'examList'));
        } else if ($request->view == 'pdf') {

            $pdf = PDF::loadView('report.participationStatus.print.participationStatus',compact('targetArr', 'request', 'examList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName . '.pdf');
        } else if ($request->view == 'excel') {

            $viewFile = 'report.participationStatus.print.participationStatus';
            $downloadFileName = $downloadFileName . '.xlsx';
            $data['targetArr'] = $targetArr;
            $data['request'] = $request;
            $data['examList'] = $examList;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }
        return view('report.participationStatus.participationStatus')->with(compact('targetArr', 'request', 'examList'));
    }

    public function generate(Request $request) {
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];
        if (!empty($request->from_date) && !empty($request->to_date)) {
            if ($request->from_date > $request->to_date) {
                $rules = [
                    'to_date' => ['after_or_equal:from_date'],
                ];
            }
        }
        $validator = Validator::make($request->all(), $rules);

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        if ($validator->fails()) {
            return redirect('participationStatus?' . $url)->withErrors($validator);
        }
        return redirect('participationStatus?generate=true&' . $url);
    }

    public function getEmployeeDetails(Request $request) {
        if ($request->type == '1') {
            $enrollExamList = ExamToStudent::join('users', 'users.id', '=', 'exam_to_student.employee_id')
                    ->join('department', 'department.id', '=', 'users.department_id')
                    ->join('rank', 'rank.id', '=', 'users.rank_id')
                    ->join('designation', 'designation.id', '=', 'users.designation_id')
                    ->join('branch', 'branch.id', '=', 'users.branch_id')
                    ->join('region', 'region.id', '=', 'branch.region_id')
                    ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                    ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name")
                            , 'users.username', 'users.photo', 'department.name as department_name'
                            , 'rank.title as grade', 'designation.title as designation_title'
                            , 'branch.name as branch_name', 'region.name as region_name'
                            , 'cluster.name as cluster_name')
                    ->where('exam_to_student.exam_id', $request->exam_id)
                    ->get();
            $data['studentDetails'] = $enrollExamList;
        } else if ($request->type == '2') {
            $attendExamList = EpeMark::join('users', 'users.id', '=', 'epe_mark.employee_id')
                    ->join('department', 'department.id', '=', 'users.department_id')
                    ->join('rank', 'rank.id', '=', 'users.rank_id')
                    ->join('designation', 'designation.id', '=', 'users.designation_id')
                    ->join('branch', 'branch.id', '=', 'users.branch_id')
                    ->join('region', 'region.id', '=', 'branch.region_id')
                    ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                    ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name")
                            , 'users.username', 'users.photo', 'department.name as department_name'
                            , 'rank.title as grade', 'designation.title as designation_title'
                            , 'branch.name as branch_name', 'region.name as region_name'
                            , 'cluster.name as cluster_name')
                    ->where('epe_mark.epe_id', $request->exam_id)
                    ->get();
            $data['studentDetails'] = $attendExamList;
        } else if ($request->type == '3') {

            $attendExamList = EpeMark::where('epe_id', $request->exam_id)
                    ->pluck('employee_id', 'employee_id')
                    ->toArray();

            $enrollStudentArr = ExamToStudent::join('users', 'users.id', '=', 'exam_to_student.employee_id')
                    ->join('department', 'department.id', '=', 'users.department_id')
                    ->join('rank', 'rank.id', '=', 'users.rank_id')
                    ->join('designation', 'designation.id', '=', 'users.designation_id')
                    ->join('branch', 'branch.id', '=', 'users.branch_id')
                    ->join('region', 'region.id', '=', 'branch.region_id')
                    ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                    ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name")
                            , 'users.username', 'users.photo', 'users.id as employee_id', 'department.name as department_name'
                            , 'rank.title as grade', 'designation.title as designation_title'
                            , 'branch.name as branch_name', 'region.name as region_name'
                            , 'cluster.name as cluster_name')
                    ->where('exam_to_student.exam_id', $request->exam_id)
                    ->get();

            $targetArr = [];
            if ($enrollStudentArr->isNotEmpty()) {
                foreach ($enrollStudentArr as $enrollStudent) {
                    $targetArr[$enrollStudent->employee_id] = $enrollStudent;
                }
            }

            if (!empty($targetArr)) {
                foreach ($targetArr as $employeeId => $result) {
                    if (!empty($attendExamList[$employeeId])) {
                        if ($employeeId == $attendExamList[$employeeId]) {
                            unset($targetArr[$employeeId]);
                        }
                    }
                }
            }

            $data['studentDetails'] = $targetArr;
        }
        $returnHTML = view('report/participationStatus/studentDetails', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}
