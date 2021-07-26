<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Department;
use App\EpeMark;
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

class DepartmentStatusReportController extends Controller {

    private $controller = 'DepartmentStatus';

    public function index(Request $request) {

        $departmentList = ['' => __('label.SELECT_DEPARTMENT_OPT')] + Department::pluck('name', 'id')->toArray();
        $targetArr = $epeList = [];

        if ($request->generate == 'true') {


            $departmentStatus = Department::join('users', 'users.department_id', '=', 'department.id')
                    ->join('epe_mark', 'epe_mark.employee_id', '=', 'users.id')
                    ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                    ->join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                    ->select('epe_mark.employee_id'
                            , 'epe_mark.subjective_earned_mark', 'epe_mark.objective_earned_mark'
                            , 'epe_mark.total_mark', 'epe_mark.id as epe_mark_id', 'epe_mark.epe_id'
                            , 'department.name as department_name', 'department.id as department_id'
                            , DB::raw("SUM(epe_mark_details.final_mark) as final_mark"), 'epe_mark.exam_date')
                    ->groupBy('epe_mark.id')
                    ->where('department.id', $request->department_id);

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $departmentStatus = $departmentStatus->whereBetween('epe_mark.exam_date', [$request->from_date, $request->to_date]);
            }
            $departmentStatus = $departmentStatus->get();


            $epeArr = Epe::select('*');
            if (!empty($request->from_date) && !empty($request->to_date)) {
                $epeArr = $epeArr->whereBetween('epe.exam_date', [$request->from_date, $request->to_date]);
            }
            $epeArr = $epeArr->get();

            if (!$epeArr->isEmpty()) {
                foreach ($epeArr as $epe) {
                    $epeList[$epe->id]['title'] = $epe->title;
                    $epeList[$epe->id]['exam_date'] = $epe->exam_date;
                }
            }
            $objectiveQuestion = EpeMark::join('users', 'users.id', '=', 'epe_mark.employee_id')
                    ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                    ->join('question', 'question.id', '=', 'epe_mark_details.question_id')
                    ->join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                    ->join('epe_to_question', 'epe_to_question.epe_id', '=', 'epe_mark.epe_id')
                    ->select('epe_to_question.mark'
                            , 'epe_mark_details.question_id', 'epe_mark_details.epe_mark_id')
                    ->where('question.type_id', '!=', 4)
                    ->get();

            $objectiveArr = [];
            if ($objectiveQuestion->isNotEmpty()) {
                foreach ($objectiveQuestion as $question) {
                    $objectiveArr[$question->epe_mark_id][$question->question_id] = $question->mark;
                }
            }


            $objTotalMks = [];
            foreach ($objectiveArr as $epeMksId => $objective) {
                $objTotalMks[$epeMksId] = array_sum($objective);
            }


            $departmentArr = [];
            if ($departmentStatus->isNotEmpty()) {
                foreach ($departmentStatus as $department) {
                    if (!empty($department->subjective_earned_mark)) {
                        $departmentArr[$department->epe_id][$department->epe_mark_id] = ($department->final_mark * 100) / $department->total_mark;
                    } else {
                        $departmentArr[$department->epe_id][$department->epe_mark_id] = ($department->final_mark * 100) / $objTotalMks[$department->epe_mark_id];
                    }
                }
            }
            if (!empty($departmentArr)) {
                foreach ($departmentArr as $epeId => $result) {
                    $targetArr[$epeId] = array_sum($result) / count($result);
                }
            }
        }
        $downloadFileName = 'DepartmentStatus-'.date('d-m-Y');
        if ($request->view == 'print') {
            return view('report.departmentStatus.print.departmentStatusReport')->with(compact('targetArr', 'departmentList', 'request', 'epeList'));
        } else if ($request->view == 'pdf') {

            $pdf = PDF::loadView('report.departmentStatus.print.departmentStatusReport', compact('targetArr', 'departmentList', 'request', 'epeList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName.'.pdf');
        } else if ($request->view == 'excel') {
            $viewFile = 'report.departmentStatus.print.departmentStatusReport';
            $downLoadFileName = $downloadFileName.'.xlsx';
            $data['departmentList'] = $departmentList;
            $data['targetArr'] = $targetArr;
            $data['request'] = $request;
            $data['epeList'] = $epeList;
            return Excel::download(new ExcelExport($viewFile, $data), $downLoadFileName);
        }
        return view('report.departmentStatus.departmentStatusReport')->with(compact('targetArr', 'departmentList', 'request', 'epeList'));
    }

    public function generate(Request $request) {
        $rules = [
            'department_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&department_id=' . $request->department_id;
        if ($validator->fails()) {
            return redirect('departmentStatusReport?' . $url)->withErrors($validator);
        }
        return redirect('departmentStatusReport?generate=true&' . $url);
    }

}
