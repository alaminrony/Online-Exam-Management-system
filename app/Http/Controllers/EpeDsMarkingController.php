<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\EpeMark;
use App\EpeToQuestion;
use App\EpeMarkDetails;
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
use Illuminate\Http\Request;

class EpeDsMarkingController extends Controller {

    public function index() {
        //Get Current date time
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        //Find the regular EPE

        $regularEpeList = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' (', 'Regular', ') | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                ->join('subject_to_ds', function($join) {
                    $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                })
                ->where('epe.type', '1')
                ->where('subject_to_ds.user_id', '=', Auth::user()->id);

        $regularEpeList = $regularEpeList->where('epe.status', '1')
                        ->orderBy('epe.id', 'DESC')
                        ->where('epe.submission_deadline', '>=', DB::raw("'" . $currentDateTime . "'"))
                        ->pluck('title', 'id')->toArray();

        $tempArr = $regularEpeList;
        ksort($tempArr);
        $epeList = array('' => __('label.SELECT_EPE_OPT')) + $tempArr;
        $data['epeList'] = $epeList;



        // load the view and pass the TAE index
        return view('epeDsMarking.index', $data);
    }

    public function showSubmittedEpe(Request $request) {

        $rules = array(
            'epe_id' => 'required'
        );

        $messages = array(
            'epe_id.required' => 'Exam must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //Get EPE information
        $epeInfo = Epe::where('epe.id', $request->epe_id)->with(array('Subject'))->first();

        $lockerInfo = null;
        if (!empty($epeInfo->ds_lock_by)) {
            $lockerInfo = User::with('rank')->find($epeInfo->ds_lock_by);
        }

        //Get EPE Mark information
        $targetArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                ->where('epe.id', $request->epe_id)
                ->select('epe_mark.id', 'epe_mark.employee_id', 'epe_mark.objective_earned_mark', 'epe_mark.subjective_earned_mark'
                        , 'epe_mark.converted_mark', 'epe_mark.total_mark', 'epe_mark.ds_status', 'epe.id as epe_id'
                        , 'epe_mark.unlock_request', 'epe_mark.unlock_request_at', 'epe_mark.unlock_request_by'
                        , DB::raw("SUM(epe_mark_details.final_mark) as final_mark")
                        , DB::raw("SUM(epe_mark_details.ds_mark) as ds_mark"), 'epe_mark.id as epe_mark_id'
                        , 'epe.exam_date', 'epe.result_publish')
                ->groupBy('epe_mark.id')
                ->orderBy('epe_mark.id', 'asc')
                ->get();


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

//        Helper::dump($targetArr);

        $finalArr = [];
        if ($targetArr->isNotEmpty()) {
            foreach ($targetArr as $result) {
                $finalArr[$result->epe_mark_id]['id'] = $result->id;
                $finalArr[$result->epe_mark_id]['ds_status'] = $result->ds_status;
                $finalArr[$result->epe_mark_id]['unlock_request'] = $result->unlock_request;
                $finalArr[$result->epe_mark_id]['unlock_request_at'] = $result->unlock_request_at;
                $finalArr[$result->epe_mark_id]['epe_mark_id'] = $result->epe_mark_id;
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

        $data['finalArr'] = $finalArr;
        $data['epeInfo'] = $epeInfo;
        $data['lockerInfo'] = $lockerInfo;

        $data['dsStatusArr'] = array('0' => array('total' => 0, 'text' => __('label.WAITING_FOR_ASSESSMENT'), 'label' => 'danger'),
            '1' => array('total' => 0, 'text' => __('label.ASSESSED'), 'label' => 'success'),
            '2' => array('total' => 0, 'text' => __('label.LOCKED'), 'label' => 'primary'));

        if (!empty($targetArr)) {
            foreach ($targetArr as $item) {
                $data['dsStatusArr'][$item->ds_status]['total'] ++;
            }
        }



        $returnHTML = view('epeDsMarking/show_submitted_epe', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function subjectiveMarking($epeMarkId = null) {

        //get EpeMark info
        $epeMarkInfo = EpeMark::find($epeMarkId);

        //Finding the epe type
        $existEpe = Epe::find($epeMarkInfo->epe_id);
        if ($existEpe->type == '1') {
            $epeInfo = Epe::with(array('Subject'))->find($existEpe->id);
        } else if (in_array($existEpe->type, array('2', '3'))) {
            $epeInfo = Epe::where('epe.id', $existEpe->id)
                    ->select('epe.*')
                    ->with(array('subject', 'epeDetail', 'epeDetail.branch'))
                    ->first();
        }



        //find question by Set
        $epeSubQus = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('epe_to_question.*', 'question.question', 'question.note', 'question.document', 'question.type_id')
                        ->where('epe_to_question.epe_id', $epeInfo->id)
                        ->where('question.type_id', '4')
                        ->get()->toArray();

        $epeSubSum = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                ->select(DB::raw("SUM(epe_to_question.mark) as total_mark"))
                ->where('epe_to_question.epe_id', $epeInfo->id)
                ->where('question.type_id', '4')
                ->first();

        $epeObjSum = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                ->select(DB::raw("SUM(epe_to_question.mark) as total_mark"))
                ->where('epe_to_question.epe_id', $epeInfo->id)
                ->where('question.type_id', '!=', '4')
                ->first();
        $qusSubArr = [];
        if (!empty($epeSubQus)) {
            foreach ($epeSubQus as $item) {
                $qusSubArr[] = $item;
            }
        }
        //prepare answerArr
        $epeAnswer = EpeMarkDetails::where('epe_mark_id', $epeMarkId)->get();

        $answerArr = array();
        if (!empty($epeAnswer)) {
            foreach ($epeAnswer as $item) {
                $answerArr[$item->question_id] = $item;
            }
        }
        $lockerInfo = null;
        if (!empty($epeMarkInfo->ds_lock_by)) {
            $lockerInfo = User::with('rank')->find($epeMarkInfo->ds_lock_by);
        }

        return view('epeDsMarking.subjectiveMarking')->with(compact('epeMarkInfo', 'epeInfo', 'qusSubArr'
                                , 'answerArr', 'lockerInfo', 'epeSubSum', 'epeObjSum'));
    }

    public function saveSubjectiveMarking(Request $request) {

        //get EpeMark info
        $epeMarkInfo = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                        ->select('epe_mark.*')
                        ->where('epe_mark.id', $request->epe_mark_id)->first();

        $epeAnswerArr = EpeMarkDetails::join('question', 'question.id', '=', 'epe_mark_details.question_id')
                ->join('epe_to_question', 'question.id', '=', 'epe_to_question.question_id')
                ->where('epe_mark_details.epe_mark_id', $request->epe_mark_id)
                ->where('question.type_id', '!=', '4')
                ->select('epe_mark_details.*', 'epe_to_question.mark')
                ->get();

        $targetArr = array();
        $totalMark = 0;
        if (!empty($request->ds_mark)) {
            foreach ($request->ds_mark as $key => $val) {
                $totalMark += $val;
                EpeMarkDetails::where('id', $key)->limit(1)
                        ->update(array('final_mark' => $val, 'ds_mark' => $val, 'ds_remarks' => $request->ds_remarks[$key]));
            }
        }
        $totalObjMark = 0;
        if (!$epeAnswerArr->isEmpty()) {
            foreach ($epeAnswerArr as $epeAnswer) {
                if ($epeAnswer->correct == 1) {
                    $totalObjMark += $epeAnswer->mark;
                    //objective Marking code
//                    EpeMarkDetails::where('id', $epeAnswer->id)->limit(1)
//                            ->update(array('final_mark' => $epeAnswer->mark, 'ds_mark' => $epeAnswer->mark, 'ds_remarks' => null));
                }
            }
        }
        $updateField = array('subjective_earned_mark' => $totalMark, 'objective_earned_mark' => $totalObjMark);

        if ($request->submit == 'save') {
            Session::flash('success', __('label.MARKS_HAVE_BEEN_SAVED'));
            $updateField['ds_status'] = 1; //1 == Assessed
        } else if ($request->submit == 'lock') {
            Session::flash('success', __('label.ANSWER_SCRIPT_HAS_BEEN_ASSESSED_AND_LOCKED'));
            $updateField['ds_status'] = 2; //2 == Locked
            $updateField['ds_lock_by'] = Auth::user()->id;
            $updateField['ds_lock_at'] = date('Y-m-d H:i:s');
        }

        EpeMark::where('id', $request->epe_mark_id)->limit(1)
                ->update($updateField);


        return Redirect::to('epedsmarking?epe_id=' . $epeMarkInfo->epe_id);
    }

    public function submitSubjectiveMarking(Request $request) {
        $rules = array(
            'epe_id' => 'required'
        );

        $messages = array(
            'epe_id.required' => 'EPE must be selected!'
        );


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $updateField['ds_status'] = 1; //1 == Submitted
        $updateField['ds_lock_by'] = Auth::user()->id;
        $updateField['ds_lock_at'] = date('Y-m-d H:i:s');


        $submitted = Epe::where('id', $request->epe_id)->update($updateField);

        if ($submitted) {
            //Get EPE information
            $epeInfo = Epe::where('epe.id', $request->epe_id)->first();

            $lockerInfo = null;
            if (!empty($epeInfo->ds_lock_by)) {
                $lockerInfo = User::with('rank')->find($epeInfo->ds_lock_by);
            }

            $lockedMsg = '<div class="col-md-12 text-center"><div class="well text-danger">' . __('label.THIS_EPE_HAS_BEEN_SUBMITTED_BY') . $lockerInfo->Rank->short_name . $lockerInfo->first_name . $lockerInfo->last_name . ' (' . $lockerInfo->username . ')' . ' at ' . $epeInfo->ds_lock_at . '</div></div>';

            return Response::json(array('success' => TRUE, 'data' => $lockedMsg), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => __('label.COULD_NOT_BE_SUBMITTED_RESULT')), 401);
        }
    }

    public function getUnlockRequest(Request $request) {
        $empMarkId = $request->epe_mark_id;
        $data['empMarkId'] = $empMarkId;
        $returnHTML = view('epeDsMarking/unlock_request', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function UnlockRequestSave(Request $request) {

        $epeMark = EpeMark::find($request->epe_mark_id);

        if (empty($epeMark)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
        }


        if (empty($request->remarks)) {
            return Response::json(array('success' => false, 'heading' => __('label.UNLOCK_REQUEST'), 'message' => 'Remarks field required!'), 401);
        }
        $epeMark->unlock_request = '1';
        $epeMark->remarks = $request->remarks;
        $epeMark->unlock_request_at = date('Y-m-d H:i:s');
        $epeMark->unlock_request_by = Auth::user()->id;

        $epeArr = EpeMark::where('id', $request->epe_mark_id)->first();

        if ($epeMark->save()) {
            return Response::json(array('success' => true, 'data' => $epeArr), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => __('label.UNLOCK_REQUEST'), 'message' => __('label.UNLOCK_REQUEST_FAILED')), 401);
        }
    }

    public function questionAnswerSheet(Request $request) {

        $epeMarkId = $request->epe_mark_id;

        //finding objective question
        $questionArr = EpeMarkDetails::join('question', 'question.id', '=', 'epe_mark_details.question_id')
                        ->join('epe_to_question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('epe_mark_details.*', 'question.question', 'question.opt_1', 'question.opt_2'
                                , 'question.opt_3', 'question.opt_4', 'question.mcq_answer'
                                , 'question.ftb_answer', 'question.tf_answer', 'question.type_id'
                                , 'epe_to_question.mark')
                        ->where('epe_mark_details.epe_mark_id', $epeMarkId)
                        ->where('question.type_id', '!=', '4')// 4 subjective
                        ->groupBy('epe_mark_details.id')
                        ->orderBy('epe_mark_details.id', 'ASC')->get();
      
        $data['questionArr'] = $questionArr;
        // $data['trueFalse'] = $questionType5=array();
        // $data['fillingBlank'] = $questionType3=array();
        $returnHTML = view('epeDsMarking/questionanswersheet', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}
