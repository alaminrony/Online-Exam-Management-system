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
use App\Cluster;
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

class ClusterResultReportController extends Controller {

    private $controller = 'ClusterResultReport';

    public function index(Request $request) {
        $clusterList = ['' => __('label.SELECT_CLUSTER_OPT')] + Cluster::pluck('name', 'id')->toArray();

        $targetArr = $epeList = [];

        if ($request->generate == 'true') {
            $clusterResult = Cluster::join('branch', 'branch.cluster_id', 'cluster.id')
                    ->join('users', 'users.branch_id', 'branch.id')
                    ->join('epe_mark', 'epe_mark.employee_id', 'users.id')
                    ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                    ->select('branch.id as branch_id', 'branch.name as branch_name'
                            , 'cluster.id as cluster_id', 'epe_mark.employee_id'
                            , 'epe_mark.exam_date', 'epe_mark.id as epe_mark_id', 'epe_mark.epe_id'
                            , 'epe_mark.subjective_earned_mark', 'epe_mark.objective_earned_mark'
                            , 'epe_mark.total_mark', DB::raw("SUM(epe_mark_details.final_mark) as final_mark"))
                    ->groupBy('epe_mark.id')
                    ->where('cluster.id', $request->cluster_id);

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $clusterResult = $clusterResult->whereBetween('epe_mark.exam_date', [$request->from_date, $request->to_date]);
            }
            $clusterResult = $clusterResult->get();

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


            $clusterArr = [];
            if ($clusterResult->isNotEmpty()) {
                foreach ($clusterResult as $cluster) {
                    if (!empty($cluster->subjective_earned_mark)) {
                        $clusterArr[$cluster->epe_id][$cluster->epe_mark_id] = ($cluster->final_mark * 100) / $cluster->total_mark;
                    } else {
                        $clusterArr[$cluster->epe_id][$cluster->epe_mark_id] = ($cluster->final_mark * 100) / $objTotalMks[$cluster->epe_mark_id];
                    }
                }
            }

            if (!empty($clusterArr)) {
                foreach ($clusterArr as $epeId => $result) {
                    $targetArr[$epeId] = array_sum($result) / count($result);
                }
            }

              $downloadFileName = 'ClusterResult-'.date('d-m-Y');
            if ($request->view == 'print') {
                return view('report.clusterResult.printClusterResultReport')->with(compact('targetArr', 'clusterList', 'request','epeList'));
            } else if ($request->view == 'pdf') {

                $pdf = PDF::loadView('report.clusterResult.printClusterResultReport', compact('targetArr', 'clusterList', 'request','epeList'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions(['defaultFont' => 'sans-serif']);
                return $pdf->download($downloadFileName.'.pdf');
            } else if ($request->view == 'excel') {
                $viewFile = 'report.clusterResult.printClusterResultReport';
                $downloadFileName = $downloadFileName.'.xlsx';
                $data['clusterList'] = $clusterList;
                $data['targetArr'] = $targetArr;
                $data['request'] = $request;
                $data['epeList'] = $epeList;
                return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
            }
        }

        return view('report.clusterResult.clusterResultReport')->with(compact('targetArr', 'clusterList', 'request','epeList'));
    }

    public function generate(Request $request) {
        $rules = [
            'cluster_id' => 'required',
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

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&cluster_id=' . $request->cluster_id;
        if ($validator->fails()) {
            return redirect('clusterResult?' . $url)->withErrors($validator);
        }
        return redirect('clusterResult?generate=true&' . $url);
    }

}
