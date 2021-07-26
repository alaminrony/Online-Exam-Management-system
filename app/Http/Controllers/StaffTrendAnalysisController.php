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

class StaffTrendAnalysisController extends Controller {

    private $controller = 'StaffTrendAnalysis';

    public function index(Request $request) {
//        Helper::dump($request->all());
        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')] + User::join('epe_mark', 'epe_mark.employee_id', '=', 'users.id')
                        ->select(DB::raw("CONCAT(users.first_name,'',users.last_name,' (',users.username,')') as employee_name")
                                , 'epe_mark.employee_id as id')
                        ->pluck('employee_name', 'id')
                        ->toArray();
        
//        Helper::dump($employeeArr);


        $targetArr = EpeMark::join('users', 'users.id', '=', 'epe_mark.employee_id')
                ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                ->join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->select(DB::raw("CONCAT(users.first_name,'',users.last_name) as employee_name")
                        , 'epe_mark.employee_id', 'epe_mark.exam_date'
                        , 'epe_mark_details.final_mark', 'epe_mark.id as epe_mark_id'
                        , DB::raw("SUM(epe_mark_details.final_mark) as final_mark")
                        , 'epe.title as exam_name', 'epe_mark.subjective_earned_mark', 'epe_mark.objective_earned_mark', 'epe_mark.total_mark', 'epe.obj_no_question')
                ->groupBy('epe_mark.id')
                ->where('epe_mark.employee_id', $request->employee_id);

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $targetArr = $targetArr->whereBetween('epe_mark.exam_date', [$request->from_date, $request->to_date]);
        }

        $targetArr = $targetArr->get();

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

//        Helper::dump($objTotalMks);

        $finalArr = [];
        if ($targetArr->isNotEmpty()) {
            foreach ($targetArr as $result) {
                $finalArr[$result->epe_mark_id]['employee_name'] = $result->employee_name;
                $finalArr[$result->epe_mark_id]['exam_date'] = $result->exam_date;
                $finalArr[$result->epe_mark_id]['exam_name'] = $result->exam_name;
                $finalArr[$result->epe_mark_id]['achieved_mark'] = $result->final_mark;
                if (!empty($result->subjective_earned_mark)) {
                    $finalArr[$result->epe_mark_id]['achieved_mark_per'] = ($result->final_mark * 100) / $result->total_mark;
                } else {
                    $finalArr[$result->epe_mark_id]['achieved_mark_per'] = ($result->final_mark * 100) / $objTotalMks[$result->epe_mark_id];
                }
            }
        }

         $downloadFileName = 'StaffTrendAnalysis-'.date('d-m-Y');
        if ($request->view == 'print') {

            return view('report.staffTrendAnalysis.printStaffTrendAnalysisReport')->with(compact('employeeArr', 'finalArr', 'request'));
        } else if ($request->view == 'pdf') {

            $pdf = PDF::loadView('report.staffTrendAnalysis.printStaffTrendAnalysisReport', compact('employeeArr', 'finalArr', 'request'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName.'.pdf');
        } else if ($request->view == 'excel') {

            $viewFile = 'report.staffTrendAnalysis.printStaffTrendAnalysisReport';
            $downloadFileName = $downloadFileName.'.xlsx';
            $data['employeeArr'] = $employeeArr;
            $data['finalArr'] = $finalArr;
            $data['request'] = $request;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }
//      Helper::dump($finalArr);
        return view('report.staffTrendAnalysis.staffTrendAnalysisReport')->with(compact('employeeArr', 'finalArr', 'request'));
    }

    public function generate(Request $request) {
        $rules = [
            'employee_id' => 'required',
            'from_date' => 'required_with:to_date',
            'to_date' => 'required_with:from_date',
        ];
        if (!empty($request->from_date) && !empty($request->to_date)) {
            if ($request->from_date > $request->to_date) {
                $rules = [
                    'to_date' => ['after_or_equal:from_date'],
                ];
            }
        }
        $validator = Validator::make($request->all(), $rules);

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&employee_id=' . $request->employee_id;
        if ($validator->fails()) {
            return redirect('staffTrendAnalysis?' . $url)->withErrors($validator);
        }
        return redirect('staffTrendAnalysis?generate=true&' . $url);
    }

}
