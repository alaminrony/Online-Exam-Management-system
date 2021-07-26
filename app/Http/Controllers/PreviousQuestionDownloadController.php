<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\Student;
use App\Subject;
use App\Epe;
use App\EpeToQuestion;
use App\EpeSubQus;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Response;
use Image;
use Illuminate\Http\Request;

class PreviousQuestionDownloadController extends Controller {

    public function index(Request $request) {
        $data['type'] = array('0' => __('label.SELECT_EPE'), '2' => __('label.EPE'));

        $studentInfo = Student::where('user_id', Auth::user()->id)->first();
        $subjectArr = Subject::select(DB::raw("CONCAT(subject.title,' >> ',subject.code) AS name"), 'subject.id')
                        ->pluck('name', 'id')->toArray();
        $subjectArr = ['0' => __('label.SELECT_SUBJECT_OPT')] + $subjectArr;

        $data['subjectArr'] = $subjectArr;

        return view('previousquestion.index', $data);
    }

    public function showPreviousQuestion(Request $request) {

        $typeTaeEpe = $request->type_tae_epe;

        if (empty($typeTaeEpe)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => 'TAE' . '/' . 'EPE must be selected!'), 401);
        }

        $subjectList = Subject::select(DB::raw("CONCAT(title,' >> ',code) AS name"), 'id')->pluck('name', 'id')->toArray();

        $studentInfo = Student::where('user_id', Auth::user()->id)->first();


        if ($typeTaeEpe == 1) { //For TAE

            $data['subjectList'] = $subjectList;

            $returnHTML = view('previousquestion/show_tae_question_list', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        } else if ($typeTaeEpe == 2) { //For EPE
            //Find out the list of Regular EPE
            $epeArr = Epe::where('epe.result_publish', '>', date("Y-m-d H:i:s"))
                    ->select('epe.id as epe_id', 'epe.subject_id', DB::raw('CONCAT(`epe`.`title`) AS `title`'), 'epe.result_publish'
                            , 'epe.type')
                    ->orderBy('epe.id', 'DESC');

            if (!empty($request->subject_id)) {
                $epeArr = $epeArr->where('epe.subject_id', $request->subject_id);
            }
            $epeArr = $epeArr->get()->toArray();

            //Find out the list of Irregular/Reschedule EPE
            $irregularEpeArr = Epe::join('epe_details', 'epe_details.epe_id', '=', 'epe.id')
                    ->where('epe.result_publish', '<', date("Y-m-d H:i:s"))
                    ->select('epe.id as epe_id', 'epe.subject_id', DB::raw('CONCAT(`epe`.`title`) AS `title`'), 'epe.result_publish', 'epe.type')
                    ->orderBy('epe.id', 'DESC');
            if (!empty($request->subject_id)) {
                $irregularEpeArr = $irregularEpeArr->where('epe.subject_id', $request->subject_id);
            }
            $irregularEpeArr = $irregularEpeArr->get()->toArray();

            //Merge Regular and Irregular/Reschedule EPE to produce Unified array of all EPE
            $allEpeArr = array_merge($epeArr, $irregularEpeArr);
            //Set information to the Data array to pass to View file
            $data['allEpeArr'] = $allEpeArr;
            $data['subjectList'] = $subjectList;

            $returnHTML = view('previousquestion/show_epe_question_list', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        }
    }

//This function use for epe objective question view
    public function questionDetails(Request $request) {

        $epeId = $request->epe_id;
        $type = $request->type;

        if ($type == '1') {
            //   regullar e
//Get EPE Information
            $epeInfo = Epe::where('epe.id', $epeId)->with(array('subject'))->first();
            if (empty($epeInfo)) {
                return Response::json(array('success' => false, 'heading' => 'EPE Invalid', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
            }
            $data['epeInfo'] = $epeInfo;




//Finding Multiple Choice Single Answer question
            $questionType1 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 1)
                            ->where('epe_id', $epeId)->get();

//Finding true or false question
            $questionType5 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 5)
                            ->where('epe_id', $epeId)->get();

//Finding Filling the Blank question
            $questionType3 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 3)
                            ->where('epe_id', $epeId)->get();

//Finding Matching question
            $questionType6 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id', 'question.match_answer')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 6)
                            ->where('epe_id', $epeId)->get();
 $questionType4 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 4)
                            ->where('epe_id', $epeId)->get();

            $data['objective'] = $questionType1;
            $data['trueFalse'] = $questionType5;
            $data['fillingBlank'] = $questionType3;
            $data['subjective'] = $questionType4;

            if ($request->view == 'print') {
                return view('previousquestion/print/objective_question_print', $data);
            }
            $returnHTML = view('previousquestion/objective_question_view', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        } elseif ($type == '2' || $type == '3') {
            //   iregullar && rechedule
            //Get EPE Information
            $epeInfo = Epe::where('epe.id', $epeId)->with(array('subject', 'epeDetail', 'epeDetail.branch'))->first();
            if (empty($epeInfo)) {
                return Response::json(array('success' => false, 'heading' => 'EPE Invalid', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
            }
            $data['epeInfo'] = $epeInfo;

            //Get EPE Mark details
            $markDistribution = MarksDistribution::where('id', $epeInfo->marks_distribution_id)->first();

            if (empty($markDistribution->objective)) {
                return Response::json(array('success' => false, 'heading' => 'Mark Distribution Not Set', 'message' => __('label.OBJECTIVE_MARK_HAS_NOT_SET_FOR_THIS_EPE_YET')), 401);
            }
            $data['markDistribution'] = $markDistribution;

            //Finding Multiple Choice Single Answer question
            $questionType1 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 1)
                            ->where('epe_id', $epeId)->get();

            //Finding true or false question
            $questionType5 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 5)
                            ->where('epe_id', $epeId)->get();

            //Finding Filling the Blank question
            $questionType3 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 3)
                            ->where('epe_id', $epeId)->get();
            
            $questionType4 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                            ->select('question.question', 'question.image', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                            ->orderBy('question.type_id')
                            ->where('question.type_id', 4)
                            ->where('epe_id', $epeId)->get();

            //Finding Matching question


            $data['objective'] = $questionType1;
            $data['trueFalse'] = $questionType5;
            $data['fillingBlank'] = $questionType3;
            $data['subjective'] = $questionType4;

            if ($request->view == 'print') {
                return view('previousquestion/irregularreschedule/print/objective_question_print', $data);
            }

            $returnHTML = view('previousquestion/irregularreschedule/objective_question_view', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        }
    }

//This function use for epe subjective question details
    public function subjectiveQuestionDetails(Request $request) {
        $epeId = $request->epe_id;
        $type = $request->type;

        if ($type == '1') {
            //regular
//Get EPE Information
            $epeInfo = Epe::where('epe.id', $epeId)->with(array('subject'))->first();
            if (empty($epeInfo)) {
                return Response::json(array('success' => false, 'heading' => 'EPE Invalid', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
            }
            $data['epeInfo'] = $epeInfo;

//Select subjective question details
            $questionDetails = EpeSubQus::join('question', 'question.id', '=', 'epe_sub_to_question.question_id')
                            ->join('epe_sub_qus_set', function($join) {
                                $join->on('epe_sub_qus_set.epe_id', '=', 'epe_sub_to_question.epe_id');
                                $join->on('epe_sub_qus_set.set_id', '=', 'epe_sub_to_question.set_id');
                            })
                            ->select('epe_sub_to_question.*', 'question.question', 'question.note', 'question.image'
                                    , 'epe_sub_qus_set.set_title', 'epe_sub_qus_set.mark as total_mark'
                                    , 'epe_sub_qus_set.options', 'epe_sub_qus_set.no_of_qus', 'epe_sub_qus_set.answer')
                            ->orderBy('epe_sub_to_question.set_id')
                            ->where('epe_sub_to_question.epe_id', $epeId)->get();

            $targetArr = array();
            if (!empty($questionDetails)) {
                foreach ($questionDetails as $key => $value) {
                    $targetArr[$value->set_id]['set_title'] = $value->set_title;
                    $targetArr[$value->set_id]['has_option'] = $value->options;
                    $targetArr[$value->set_id]['no_of_qus'] = $value->no_of_qus;
                    $targetArr[$value->set_id]['no_of_answer'] = $value->answer;
                    $targetArr[$value->set_id]['total_marks'] = $value->total_mark;
                    $targetArr[$value->set_id]['question_set'][$key]['question'] = $value->question;
                    $targetArr[$value->set_id]['question_set'][$key]['marks'] = $value->mark;
                    $targetArr[$value->set_id]['question_set'][$key]['note'] = $value->note;
                    $targetArr[$value->set_id]['question_set'][$key]['image'] = $value->image;
                }
            }

            $data['targetArr'] = $targetArr;

            if ($request->view == 'print') {
                return view('previousquestion/print/subjective_question_print', $data);
            }
            $returnHTML = view('previousquestion/subjective_question_view', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        } elseif ($type == '2' || $type == '3') {
            //irregular or rechedule

            $epeId = $request->epe_id;

            //Get EPE Information
            $epeInfo = Epe::where('epe.id', $epeId)->with(array('subject', 'epeDetail', 'epeDetail.branch'))->first();
            if (empty($epeInfo)) {
                return Response::json(array('success' => false, 'heading' => 'EPE Invalid', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
            }
            $data['epeInfo'] = $epeInfo;

            //Select subjective question details
            $questionDetails = EpeSubQus::join('question', 'question.id', '=', 'epe_sub_to_question.question_id')
                            ->join('epe_sub_qus_set', function($join) {
                                $join->on('epe_sub_qus_set.epe_id', '=', 'epe_sub_to_question.epe_id');
                                $join->on('epe_sub_qus_set.set_id', '=', 'epe_sub_to_question.set_id');
                            })
                            ->select('epe_sub_to_question.*', 'question.question', 'question.note', 'question.image'
                                    , 'epe_sub_qus_set.set_title', 'epe_sub_qus_set.mark as total_mark'
                                    , 'epe_sub_qus_set.options', 'epe_sub_qus_set.no_of_qus', 'epe_sub_qus_set.answer')
                            ->orderBy('epe_sub_to_question.set_id')
                            ->where('epe_sub_to_question.epe_id', $epeId)->get();

            $targetArr = array();
            if (!empty($questionDetails)) {
                foreach ($questionDetails as $key => $value) {
                    $targetArr[$value->set_id]['set_title'] = $value->set_title;
                    $targetArr[$value->set_id]['has_option'] = $value->options;
                    $targetArr[$value->set_id]['no_of_qus'] = $value->no_of_qus;
                    $targetArr[$value->set_id]['no_of_answer'] = $value->answer;
                    $targetArr[$value->set_id]['total_marks'] = $value->total_mark;
                    $targetArr[$value->set_id]['question_set'][$key]['question'] = $value->question;
                    $targetArr[$value->set_id]['question_set'][$key]['marks'] = $value->mark;
                    $targetArr[$value->set_id]['question_set'][$key]['note'] = $value->note;
                    $targetArr[$value->set_id]['question_set'][$key]['image'] = $value->image;
                }
            }

            $data['targetArr'] = $targetArr;

            if ($request->view == 'print') {
                return view('previousquestion/irregularreschedule/print/subjective_question_print', $data);
            }
            $returnHTML = view('previousquestion/irregularreschedule/subjective_question_view', $data)->render();
            return Response::json(array('success' => true, 'html' => $returnHTML));
        }
    }

}

?>
