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
use App\Subject;
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

class EmployeeWiseResultController extends Controller {

    private $controller = 'EmployeeWiseResult';

    public function index(Request $request) {
//        Helper::dump($request->all());
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        $employeeArr = Epe::select(DB::raw("CONCAT(users.first_name,' ',users.last_name,'(',users.username,')') as employee_name"), 'epe_mark.employee_id as id')
                        ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                        ->join('users', 'users.id', '=', 'epe_mark.employee_id');
        if (Auth::user()->group_id == '3') {
            $employeeArr->where('epe_mark.employee_id', Auth::user()->id);
        }
        $employeeArr = $employeeArr->pluck('employee_name', 'id')->toArray();
        
        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')] + $employeeArr;

//        Helper::dump($employeeArr);

        $subjectArr = Subject::join('epe', 'epe.subject_id', '=', 'subject.id')
                        ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                        ->where('epe_mark.employee_id', $request->fill_employee_id)
                        ->select('subject.id', 'subject.title')
                        ->pluck('subject.title', 'subject.id')->toArray();



        $examInfoArr = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                ->where('epe.subject_id', $request->fill_subject_id);
        if (Auth::user()->group_id == '3') {
            $examInfoArr->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                    ->where('epe_mark.employee_id', Auth::user()->id);
        }
        $examInfoArr = $examInfoArr->pluck('title', 'id')->toArray();
        $examInfoArr = ['' => __('label.SELECT_EXAM_OPT')] + $examInfoArr;




//        ->where('epe.subject_id', $request->subject_id)
//                        ->where('epe.id', $request->exam_id)
//, DB::raw("SUM(epe_mark_details.final_mark) as final_mark")

        $examResult = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                ->join('users', 'users.id', '=', 'epe_mark.employee_id');
        if (Auth::user()->group_id == '3') {
            $examResult = $examResult->where('epe_mark.employee_id', Auth::user()->id)
                    ->where('epe.result_publish', '<=', DB::raw("'" . $currentDateTime . "'"))
                    ->where('epe_mark.ds_status', '=', '2');
        }
        $examResult = $examResult->select(DB::raw("CONCAT(users.first_name,' ',users.last_name,'(',users.username,')') as employee_name")
                        , 'epe_mark.employee_id'
                        , 'epe_mark.subjective_earned_mark', 'epe_mark.objective_earned_mark', 'epe_mark.total_mark', 'epe.obj_no_question'
                        , 'epe.subject_id', 'epe.title', DB::raw("SUM(epe_mark_details.final_mark) as final_mark"), DB::raw("SUM(epe_mark_details.ds_mark) as ds_mark"), 'epe_mark.id as epe_mark_id', 'epe.exam_date', 'epe.result_publish','epe.total_mark')
                ->where('epe_mark.employee_id', $request->fill_employee_id)
                ->where('epe.subject_id', $request->fill_subject_id)
                ->groupBy('epe_mark.id');

        

        if (!empty($request->fill_exam_id)) {
            $examResult = $examResult->where('epe_mark.epe_id', $request->fill_exam_id);
        }

        $examResult = $examResult->get();
        
        

        $employeeForReport = User::select(DB::raw("CONCAT(users.first_name,'',users.last_name) as employee_name"), 'id')->where('group_id', '3')->pluck('employee_name', 'id')->toArray();




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
                $finalArr[$result->epe_mark_id]['epe_mark_id'] = $result->epe_mark_id;
                $finalArr[$result->epe_mark_id]['title'] = $result->title;
                $finalArr[$result->epe_mark_id]['exam_date'] = $result->exam_date;
                $finalArr[$result->epe_mark_id]['result_publish'] = $result->result_publish;
                $finalArr[$result->epe_mark_id]['exam_name'] = $result->exam_name;
                $finalArr[$result->epe_mark_id]['exam_date'] = $result->exam_date;
                $finalArr[$result->epe_mark_id]['result_publish'] = $result->result_publish;
                $finalArr[$result->epe_mark_id]['total_mark'] = $result->total_mark;
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
        
//        Helper::dump($finalArr);

        

        $downloadFileName = 'EmployeeWiseResult-' . date('d-m-Y');
        if ($request->view == 'print') {
            return view('report.employeeWiseResult.printEmployeeWiseReport')->with(compact('finalArr', 'request','subjectArr', 'examInfoArr', 'employeeArr'));
        } else if ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.employeeWiseResult.printEmployeeWiseReport', compact('finalArr', 'request','subjectArr', 'examInfoArr', 'employeeArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName . '.pdf');
        } else if ($request->view == 'excel') {
            $viewFile = 'report.employeeWiseResult.printEmployeeWiseReport';
            $downloadFileName = $downloadFileName . '.xlsx';
            $data['finalArr'] = $finalArr;
            $data['request'] = $request;
            $data['subjectArr'] = $subjectArr;
            $data['examInfoArr'] = $examInfoArr;
            $data['employeeArr'] = $employeeArr;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }
        return view('report.employeeWiseResult.employeeWiseResultReport')->with(compact('finalArr', 'request','subjectArr', 'examInfoArr', 'employeeArr'));
    }

    public function getSubject(Request $request) {
        $subjectArr = Subject::join('epe', 'epe.subject_id', '=', 'subject.id')
                        ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                        ->where('epe_mark.employee_id', $request->employee_id)
                        ->select('subject.id', 'subject.title')
                        ->pluck('subject.title', 'subject.id')->toArray();

        $subjectArr = ['' => __('label.SELECT_SUBJECT_OPT')] + $subjectArr;
        $data['subjectArr'] = $subjectArr;
        $returnHTML = view('report.employeeWiseResult.getSubject', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function getExam(Request $request) {
        $examInfoArr = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                ->where('epe.subject_id', $request->subject_id);
        if (Auth::user()->group_id == '3') {
            $examInfoArr->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                    ->where('epe_mark.employee_id', Auth::user()->id);
        }
        $examInfoArr = $examInfoArr->pluck('title', 'id')->toArray();
        $examInfoArr = ['' => __('label.SELECT_EXAM_OPT')] + $examInfoArr;
        $data['examInfoArr'] = $examInfoArr;
        $returnHTML = view('report.employeeWiseResult.getExam', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

//    public function getEmployee(Request $request) {
//
//        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')] + Epe::select(DB::raw("CONCAT(users.first_name,'',users.last_name) as employee_name"), 'epe_mark.employee_id as id')
//                        ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
//                        ->join('users', 'users.id', '=', 'epe_mark.employee_id')
//                        ->where('epe.subject_id', $request->subject_id)
//                        ->where('epe.id', $request->exam_id)
//                        ->pluck('employee_name', 'id')->toArray();
//
//        $data['employeeArr'] = $employeeArr;
//        $returnHTML = view('report.employeeWiseResult.getEmployee', $data)->render();
//        return Response::json(array('success' => true, 'html' => $returnHTML));
//    }

    public function generate(Request $request) {
        $rules = [
            'employee_id' => 'required',
            'subject_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        $url = 'fill_employee_id=' . $request->employee_id . '&fill_subject_id=' . $request->subject_id . '&fill_exam_id=' . $request->exam_id;
        if ($validator->fails()) {
            return redirect('employeeWiseResult?' . $url)->withErrors($validator);
        }
        return redirect('employeeWiseResult?generate=true&' . $url);
    }

}
