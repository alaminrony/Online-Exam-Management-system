<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
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

class ExamResultReportController extends Controller {

    private $controller = 'ExamResultReport';

    public function index(Request $request) {
//        Helper::dump($request->all());
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        $examInfoArr = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"));
        if (Auth::user()->group_id == '3') {
            $examInfoArr->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                    ->where('epe_mark.employee_id', Auth::user()->id);
        }
        $examInfoArr = $examInfoArr->pluck('title', 'id')->toArray();
        $examInfoArr = ['' => __('label.SELECT_EXAM_OPT')] + $examInfoArr;



        $employeeArr =  Epe::select(DB::raw("CONCAT(users.first_name,'',users.last_name,'(',users.username,')') as employee_name"), 'epe_mark.employee_id as id')
                        ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                        ->join('users', 'users.id', '=', 'epe_mark.employee_id')
                        ->where('epe.id', $request->fill_exam_id)
                        ->pluck('employee_name', 'id')->toArray();

        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')]+$employeeArr;
        
        

        $examResult = EpeMark::join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                ->join('users', 'users.id', '=', 'epe_mark.employee_id')
                ->join('epe', 'epe.id', '=', 'epe_mark.epe_id');
        if (Auth::user()->group_id == '3') {
            $examResult = $examResult->where('epe_mark.employee_id', Auth::user()->id)
                    ->where('epe.result_publish', '<=', DB::raw("'" . $currentDateTime . "'"))
                    ->where('epe_mark.ds_status', '=', '2');
        }
        $examResult = $examResult->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name")
                        , 'epe_mark.id as epe_mark_id', 'epe_mark.employee_id', DB::raw("SUM(epe_mark_details.final_mark) as final_mark")
                        , DB::raw("SUM(epe_mark_details.ds_mark) as ds_mark"), 'epe_mark.subjective_earned_mark', 'epe_mark.objective_earned_mark', 'epe_mark.total_mark'
                        , 'epe.obj_no_question', 'epe.exam_date', 'epe.result_publish', 'users.username')
                ->groupBy('epe_mark.id');

        if (!empty($request->fill_exam_id)) {
            $examResult = $examResult->where('epe_mark.epe_id', $request->fill_exam_id);
        }
        if (!empty($request->fill_employee_id)) {
            $examResult = $examResult->where('epe_mark.employee_id', $request->fill_employee_id);
        }
        $examResult = $examResult->get();


        $employeeForReport = User::select(DB::raw("CONCAT(users.first_name,'',users.last_name) as employee_name"), 'id')->where('group_id', '3')->pluck('employee_name', 'id')->toArray();
        $examInfoForReport = Epe::where('id', $request->fill_exam_id)->first();


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



        $finalArr = [];
        if ($examResult->isNotEmpty()) {
            foreach ($examResult as $result) {
                $finalArr[$result->epe_mark_id]['employee_name'] = $result->employee_name;
                $finalArr[$result->epe_mark_id]['username'] = $result->username;
                $finalArr[$result->epe_mark_id]['epe_mark_id'] = $result->epe_mark_id;
                $finalArr[$result->epe_mark_id]['exam_date'] = $result->exam_date;
                $finalArr[$result->epe_mark_id]['result_publish'] = $result->result_publish;
                $finalArr[$result->epe_mark_id]['exam_name'] = $result->exam_name;
                $finalArr[$result->epe_mark_id]['achieved_mark'] = $result->final_mark;
                $finalArr[$result->epe_mark_id]['subjective_mark'] = $result->ds_mark;
                $finalArr[$result->epe_mark_id]['objective_mark'] = $result->final_mark - $result->ds_mark;
                if (!empty($result->subjective_earned_mark)) {
                    $finalArr[$result->epe_mark_id]['achieved_mark_per'] = ($result->final_mark * 100) / $result->total_mark;
                } else {
                    $finalArr[$result->epe_mark_id]['achieved_mark_per'] = ($result->final_mark * 100) / $objTotalMks[$result->epe_mark_id];
                }
            }
        }


        $downloadFileName = 'ExamResult-' . date('d-m-Y');
        if ($request->view == 'print') {
            return view('report.examResult.printExamReport')->with(compact('finalArr', 'request', 'objectiveArr', 'examInfoForReport', 'examInfoArr'));
        } else if ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.examResult.printExamReport', compact('finalArr', 'request', 'objectiveArr', 'examInfoForReport', 'examInfoArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName . '.pdf');
        } else if ($request->view == 'excel') {
            $viewFile = 'report.examResult.printExamReport';
            $downloadFileName = $downloadFileName . '.xlsx';
            $data['finalArr'] = $finalArr;
            $data['objectiveArr'] = $objectiveArr;
            $data['examInfoForReport'] = $examInfoForReport;
            $data['request'] = $request;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }
        return view('report.examResult.examResultReport', compact('examInfoArr', 'employeeArr', 'finalArr', 'objectiveArr', 'request'));
    }

    public function getEmployee(Request $request) {

        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')] + Epe::select(DB::raw("CONCAT(users.first_name,'',users.last_name,'(',users.username,')') as employee_name"), 'epe_mark.employee_id as id')
                        ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                        ->join('users', 'users.id', '=', 'epe_mark.employee_id')
                        ->where('epe.id', $request->exam_id)
                        ->pluck('employee_name', 'id')->toArray();

        $data['employeeArr'] = $employeeArr;
        $returnHTML = view('report.employeeWiseResult.getEmployee', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function generate(Request $request) {
        $rules = [
            'exam_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        $url = 'fill_exam_id=' . $request->exam_id . '&fill_employee_id=' . $request->employee_id;
        if ($validator->fails()) {
            return redirect('examResultReport?' . $url)->withErrors($validator);
        }
        return redirect('examResultReport?generate=true&' . $url);
    }

}
