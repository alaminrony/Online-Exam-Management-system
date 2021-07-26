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

class BranchResultController extends Controller {

    private $controller = 'BranchResult';

    public function index(Request $request) {

        $branchList = ['' => __('label.SELECT_BRANCH_OPT')] + Branch::pluck('name', 'id')->toArray();
        $targetArr = $epeList = [];

        if ($request->generate == 'true') {


            $branchResult = Branch::join('users', 'users.branch_id', '=', 'branch.id')
                    ->join('epe_mark', 'epe_mark.employee_id', '=', 'users.id')
                    ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                    ->join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                    ->select('epe_mark.employee_id'
                            , 'epe_mark.subjective_earned_mark', 'epe_mark.objective_earned_mark'
                            , 'epe_mark.total_mark', 'epe_mark.id as epe_mark_id', 'epe_mark.epe_id'
                            , 'branch.name as branch_name', 'branch.id as branch_id'
                            , DB::raw("SUM(epe_mark_details.final_mark) as final_mark"), 'epe_mark.exam_date')
                    ->groupBy('epe_mark.id')
                    ->where('branch.id', $request->branch_id);

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $branchResult = $branchResult->whereBetween('epe_mark.exam_date', [$request->from_date, $request->to_date]);
            }
            $branchResult = $branchResult->get();


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


            $branchArr = [];
            if ($branchResult->isNotEmpty()) {
                foreach ($branchResult as $branch) {
                    if (!empty($branch->subjective_earned_mark)) {
                        $branchArr[$branch->epe_id][$branch->epe_mark_id] = ($branch->final_mark * 100) / $branch->total_mark;
                    } else {
                        $branchArr[$branch->epe_id][$branch->epe_mark_id] = ($branch->final_mark * 100) / $objTotalMks[$branch->epe_mark_id];
                    }
                }
            }
            if (!empty($branchArr)) {
                foreach ($branchArr as $epeId => $result) {
                    $targetArr[$epeId] = array_sum($result) / count($result);
                }
            }
        }
         $downloadFileName = 'BranchStatus-'.date('d-m-Y');
        if ($request->view == 'print') {
            return view('report.branchResult.print.branchResultReport')->with(compact('targetArr', 'branchList', 'request', 'epeList'));
        } else if ($request->view == 'pdf') {

            $pdf = PDF::loadView('report.branchResult.print.branchResultReport', compact('targetArr', 'branchList', 'request', 'epeList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName.'.pdf');
        } else if ($request->view == 'excel') {
            $viewFile = 'report.branchResult.print.branchResultReport';
            $downloadFileName = $downloadFileName.'.xlsx';
            $data['branchList'] = $branchList;
            $data['targetArr'] = $targetArr;
            $data['request'] = $request;
            $data['epeList'] = $epeList;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }
        return view('report.branchResult.branchResultReport')->with(compact('targetArr', 'branchList', 'request', 'epeList'));
    }

    public function generate(Request $request) {
        $rules = [
            'branch_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&branch_id=' . $request->branch_id;
        if ($validator->fails()) {
            return redirect('branchResult?' . $url)->withErrors($validator);
        }
        return redirect('branchResult?generate=true&' . $url);
    }

}
