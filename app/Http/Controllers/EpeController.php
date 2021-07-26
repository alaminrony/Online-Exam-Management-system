<?php

namespace App\Http\Controllers;

use Validator;
use App\Epe;
use App\SubjectToDs;
use App\Subject;
use App\QuestionType;
use App\Question;
use App\EpeQusTypeDetails;
use App\EpeToQuestion;
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
use DateTime;
use Illuminate\Http\Request;

class EpeController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        //Get Current date time
        $currentDateTime = new DateTime();

        $searchText = $request->search_text;
        $currentStudentId = Auth::user()->id;

        $targetArr = Epe::leftJoin('subject', 'subject.id', '=', 'epe.subject_id');

        if (Auth::user()->group_id == 4) {
            $targetArr = $targetArr->join('subject_to_ds', function($join) use ($currentStudentId) {
                        $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                        $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                    })
                    ->leftJoin(DB::raw('(select epe_id, count(id) as epe_submitted FROM epe_mark where submitted IN (1,2) group by epe_id ) as temp_epe'), function($join) {
                        $join->on('epe.id', '=', 'temp_epe.epe_id');
                    })
                    ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
                    ->select('epe.*', DB::raw("IFNULL(temp_epe.epe_submitted, '0') as epe_submitted"), 'branch.name as branch_name', 'branch.short_name as branch_short_name', 'subject.title as subject_title');
        } else {
            $targetArr = $targetArr->leftJoin(DB::raw('(select epe_id, count(id) as epe_submitted FROM epe_mark where submitted IN (1,2) group by epe_id ) as temp_epe'), function($join) {
                        $join->on('epe.id', '=', 'temp_epe.epe_id');
                    })
                    ->select('epe.*', DB::raw("IFNULL(temp_epe.epe_submitted, '0') as epe_submitted"), 'subject.title as subject_title');
        }

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('epe.title', 'like', '%' . DB::raw("$searchText") . '%')
                        ->orWhere('epe.exam_date', 'like', '%' . DB::raw("$searchText") . '%');
            });
        }

        $targetArr = $targetArr->orderBy('epe.exam_date', 'DESC')
                ->orderBy('subject.title', 'ASC')
                ->orderBy('epe.type', 'ASC')
                ->with(array('subject'))
                ->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/rank?page=' . $page);
        }

        return view('epe.index')->with(compact('targetArr', 'qpArr'));
    }

    public function filter(Request $request) {
        $searchText = $request->search_text;
        return Redirect::to('epe?search_text=' . $searchText);
    }

    public function create(Request $request) {
        //Get subject list
        if (Auth::user()->group_id == 4) {
            $subjectList = SubjectToDs::join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                            ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                            ->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code)) AS title"))
                            ->orderBy('subject.order', 'ASC')
                            ->pluck('title', 'id')->toArray();
        } else {
            $subjectList = Subject::select('subject.id', DB::raw("CONCAT(subject.title,' » ', subject.code) AS title"))
                            ->orderBy('subject.order', 'ASC')
                            ->pluck('title', 'id')->toArray();
        }
        $data['subjectList'] = [0 => __('label.SELECT_SUBJECT_OPT')] + $subjectList;
        if (!empty($request->exam_id)) {
            $qusTypeArr = QuestionType::orderBy('id', 'asc')->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id');
            $epeObjArr = Epe::find($request->exam_id);
            if (empty($epeObjArr)) {
                Session::flash('error', __('label.EPE_NOT_FOUND'));
                return Redirect::to('epe');
            }
            $data['epeObjArr'] = $epeObjArr;
            $qusQusTypeDetailList = [];
            if (!empty($epeObjArr)) {
                $qusQusTypeDetailList = EpeQusTypeDetails::where('epe_id', $epeObjArr->id)->pluck('total_qustion', 'qustion_type_id');
            }
            $data['epeObjArr'] = $epeObjArr;
            $data['qusQusTypeDetailList'] = $qusQusTypeDetailList;
            $data['qusTypeArr'] = $qusTypeArr;
            $qusFormatList = [0 => __('label.QUESTIONNAIRE_FORMAT_OPT'), 1 => __('label.ONE_TO_ONE'), 2 => __('label.ONE_TO_MANY')];
            $data['qusFormatList'] = $qusFormatList;
            $subjectId = $epeObjArr->subject_id;
            
            //Get subject list
            if (Auth::user()->group_id == 4) {

                $subjectList = SubjectToDs::join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                                ->join('phase_to_subject', 'phase_to_subject.subject_id', '=', 'subject_to_ds.subject_id')
                                ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
                                ->where('subject_to_ds.course_id', '=', $courseId)
                                ->where('subject_to_ds.part_id', '=', $partId)
                                ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                                ->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title"))
                                ->orderBy('subject.order', 'ASC')
                                ->pluck('title', 'id')->toArray();
            } else {
                $subjectList = Subject::select('subject.id', DB::raw("CONCAT(subject.title,' » ', subject.code) AS title"))
                                ->orderBy('subject.order', 'ASC')
                                ->pluck('title', 'id')->toArray();
            }

            $data['subjectList'] = array('' => __('label.SELECT_SUBJECT_OPT')) + $subjectList;

            //Get Avaiable Objective Question
            $objectiveQuestionCount = Question::where('subject_id', $subjectId)->where('status', '1')->whereIn('type_id', [1, 3, 4, 5])->count();
            $data['objectiveQuestionCount'] = $objectiveQuestionCount;

            //Get Avaiable Subjective Question
            $subjectiveQuestionCount = Question::where('subject_id', $subjectId)->where('status', '1')->where('type_id', 4)->count();
            $data['subjectiveQuestionCount'] = $subjectiveQuestionCount;

            $hoursArr = array();
            for ($h = 0; $h <= 23; $h++) {
                $hoursArr[$h] = (strlen($h) === 1) ? '0' . $h : $h;
            }
            $data['hoursList'] = $hoursArr;

            $minutesArr = array();
            for ($m = 0; $m <= 59; $m++) {
                $minutesArr[$m] = (strlen($m) === 1) ? '0' . $m : $m;
            }
            $data['minutesList'] = $minutesArr;
        }
        $subjectList = array('' => __('label.SELECT_SUBJECT_OPT')) + $subjectList;
        $examType = ['' => __('label.SELECT_EXAM_TYPE'), '1' => __('label.REGULER'), '2' => __('label.RETAKE')];
        $data['examType'] = $examType;
        return view('epe.create', $data);
    }

    //This function use for Create EPE Show subject
    public function showSubject(Request $request) {

        //Get EPE submitted subject list
        $alreadySubmittedEpeSubjectList = Epe::select('epe.subject_id as subject_id', 'epe.id as id')
                ->join(DB::raw('(select epe_id FROM epe_mark where submitted IN (1,2) group by epe_id ) as temp_epe'), function($join) {
                    $join->on('epe.id', '=', 'temp_epe.epe_id');
                })
                ->pluck('subject_id', 'id');

        //Get subject list
        if (Auth::user()->group_id == 4) {
            $subjectList = DB::table('subject_to_ds')
                    ->join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                    ->join('phase_to_subject', function($join) {
                        $join->on('phase_to_subject.course_id', '=', 'subject_to_ds.course_id');
                        $join->on('phase_to_subject.phase_id', '=', 'subject_to_ds.phase_id');
                        $join->on('phase_to_subject.subject_id', '=', 'subject_to_ds.subject_id');
                    })
                    ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
                    ->where('subject_to_ds.course_id', '=', $courseId)
                    ->where('subject_to_ds.part_id', '=', $partId)
                    ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                    ->select(
                            'subject.id as id ', 'phase_to_subject.subject_id', 'branch.name as branch_name', 'branch.short_name as branch_short_name', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title")
                    )
                    ->orderBy('subject.order', 'ASC')
                    ->get();
        } else {
            $subjectList = DB::table('subject')->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code)) AS title"))
                    ->orderBy('subject.order', 'ASC')
                    ->get();
        }

        if (empty($subjectList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => __('label.NO_SUBJECT_OF_THIS_PART_IS_NOT_ASSIGN')), 401);
        }

        //Already Submitted EPE remove subject for this subject list
        $i = 0;
        $subjectListNew = array();
        foreach ($subjectList as $value) {

            if (!in_array($value->id, $alreadySubmittedEpeSubjectList)) {

                $subjectListNew[$i] = $value;
                $i++;
            }
        }

        return Response::json(array('success' => true, 'subjects' => $subjectListNew), 200);
    }

    //This function use for EPE information show
    public function showEpeInfo(Request $request) {
        $rules = array(
            'subject_id' => 'required'
        );

        $messages = array(
            'subject_id.required' => 'Subject must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $subjectId = $request->subject_id;

        //Get TAE Information
        $taeInfoObjArr = array();
        $data['taeInfoObjArr'] = $taeInfoObjArr;

        // question type
        $qusTypeArr = QuestionType::orderBy('id', 'asc')->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id');
        //Get Avaiable Objective Question
        $objectiveQuestionCount = Question::where('subject_id', $subjectId)->where('status', '1')->whereIn('type_id', [1, 3, 4, 5, 6])->count();

        $typeWiseQuestionArr = Question::where('subject_id', $subjectId)
                ->where('status', '1')
                ->select(DB::raw("COUNT(type_id) as totalQuestion"), 'type_id')
                ->groupBy('type_id')
                ->pluck('totalQuestion', 'type_id')
                ->toArray();

        $data['typeWiseQuestionArr'] = $typeWiseQuestionArr;

        if (empty($objectiveQuestionCount)) {
            return Response::json(array('success' => false, 'heading' => __('label.EMPTY_DATA'), 'message' => __('label.NO_OBJECTIVE_QUESTION_AVAILABLE_AT_QUESTION_BANK')), 401);
        }//if objective question not avaiable

        $data['objectiveQuestionCount'] = $objectiveQuestionCount;
        $data['qusTypeArr'] = $qusTypeArr;
        $subjectiveQuestionCount = 0;
        //Get Avaiable Subjective Question
//        $subjectiveQuestionCount = Question::where('subject_id', $subjectId)->where('status', '1')->where('type_id', 4)->count();
//        if (empty($subjectiveQuestionCount)) {
//            return Response::json(array('success' => false, 'heading' => __('label.EMPTY_DATA'), 'message' => __('label.NO_SUBJECTIVE_QUESTION_AVAILABLE_AT_QUESTION_BANK')), 401);
//        }//if objective question not avaiable


        $qusFormatList = [0 => __('label.QUESTIONNAIRE_FORMAT_OPT'), 1 => __('label.ONE_TO_ONE'), 2 => __('label.ONE_TO_MANY')];
        $data['qusFormatList'] = $qusFormatList;
        $data['subjectiveQuestionCount'] = $subjectiveQuestionCount;
        $qusQusTypeDetailList = [];

        $data['epeObjArr'] = [];
        $data['qusQusTypeDetailList'] = $qusQusTypeDetailList;



        $hoursArr = array();
        for ($h = 0; $h <= 23; $h++) {
            $hoursArr[$h] = (strlen($h) === 1) ? '0' . $h : $h;
        }
        $data['hoursList'] = $hoursArr;

        $minutesArr = array();
        for ($m = 0; $m <= 59; $m++) {
            $minutesArr[$m] = (strlen($m) === 1) ? '0' . $m : $m;
        }
        $data['minutesList'] = $minutesArr;
        $examType = ['' => __('label.SELECT_EXAM_TYPE'), '1' => __('label.REGULER'), '2' => __('label.RETAKE')];
        $data['examType'] = $examType;
        $returnHTML = view('epe/showEpeInfo', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeEpe(Request $request) {
        $qusTypeList = QuestionType::orderBy('id', 'asc')->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id');
        $epeId = $request->id;
        if (!empty($epeId)) {
            //For TAE Update
            $epeArr = Epe::find($epeId);
            if (empty($epeArr)) {
                return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
            }
        }
        $rules = array(
            'subject_id' => 'required',
            'type' => 'required',
            'title' => 'required|unique:epe',
            'total_mark' => 'required',
            'questionnaire_format' => 'required',
            'exam_date' => 'required|date',
            'obj_no_question' => 'required|numeric',
            'no_of_mock' => 'required|numeric',
        );
        if (!empty($epeId)) {
            $rules['title'] = 'required|unique:epe,title,' . $epeId;
        }
        if (!empty($request->start_time) || !empty($request->end_time)) {
            $rules['start_time'] = 'required';
            $rules['end_time'] = 'required';
            $rules['obj_duration_hours'] = 'required';
            $rules['obj_duration_minutes'] = 'required';
        }

        $messages = array(
            'subject_id.required' => 'Subject must be selected!',
            'exam_date.required' => 'Exam date must be selected!',
            'total_mark.required' => 'Exam total mark is required!',
            'ci_review.required' => 'CI Review is required!',
            'obj_duration_hours.required' => 'Objective HRS is required',
            'obj_duration_minutes.required' => 'Objective minutes is required',
            'start_time.required' => 'Start time is required',
            'end_time.required' => 'End time is required',
            'obj_no_question.required' => 'total number of questions is required',
        );

        $qusType = $request->qus_type;
        $qusTypeTotal = $request->qus_type_total;

        $error = [];
        if (!empty($qusType)) {
            foreach ($qusType as $qusTypeId => $value) {
                if (empty($qusTypeTotal[$qusTypeId])) {
                    $error[$qusTypeId] = [$qusTypeList[$qusTypeId] . ' Total No Qustion is required'];
                }
            }
        } else {
            $error[0] = ['Select Question Type is required'];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if (!empty($error)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $error), 400);
        }

        if (empty($request->obj_duration_hours) && empty($request->obj_duration_minutes)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.OBJECTIVE_EXAM_DURATION_HAS_NOT_SET_FOR_THIS_EPE')), 401);
        }

        //Date time validation
        $examDate = $request->exam_date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $submissionDeadline = $request->submission_deadline;
        $resultPublish = $request->result_publish;

        if (!empty($startTime) || !empty($endTime)) {
            if ((strtotime($startTime)) > (strtotime($endTime))) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.START_TIME_SHOULD_BE_SMALLER_THAN_END_TIME')), 401);
            }
        }

        if (!empty($submissionDeadline)) {
            $endTime = !empty($endTime) ? $endTime : '00:00:00';
            if (strtotime($examDate . ' ' . $endTime) > strtotime($submissionDeadline)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.EXAM_DATE_SHOULD_BE_SMALLER_THAN_RESULT_SUBMISSION_DEATLINE')), 401);
            }
        }

        if (!empty($submissionDeadline)) {
            $endTime = !empty($endTime) ? $endTime : '00:00:00';
            if (strtotime($examDate . ' ' . $endTime) > strtotime($submissionDeadline)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.EXAM_DATE_SHOULD_BE_SMALLER_THAN_RESULT_SUBMISSION_DEATLINE')), 401);
            }
        }
        if ($request->obj_no_question < array_sum($qusType)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.TYPE_QUESTION_TOTAL_SHOULD_BE_SMALLER_THAN_TOTAL_NUMBER_OF_QUESTION')), 401);
        }

        if (!empty($submissionDeadline) && !empty($resultPublish)) {
            if (strtotime($submissionDeadline) > strtotime($resultPublish)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.RESULT_SUBMISSION_DEATLINE_SHOULD_BE_SMALLER_THAN_RESULT_PUBLISH_DATE_TIME')), 401);
            }
        }

        //Subjective total question Greater-than/Less than check
        if ($request->total_objective_questions < $request->obj_no_question) {
            return Response::json(array('success' => false, 'heading' => 'Error', 'message' => __('label.QUESTIONS_BANK_MUST_CONTAIN_40_QUESTIONS')), 401);
        }

        //Get PhasetoSubject information
//        $phaseToSubjectInfoObj = PhaseToSubject::where('course_id', '=', $request->course_id)
//                ->where('part_id', '=', $request->part_id)
//                ->where('subject_id', '=', $request->subject_id'))
//                ->first();

        if (empty($epeId)) {
            $epeArr = new Epe;
        }//only for create

        if ($request->file('file')) {
            $file = $request->file('file');
            $filePath = 'public/uploads/exam/pdf/';
            $fileName = uniqid() . "." . $file->getClientOriginalExtension();
            $file->move($filePath, $fileName);
        }
        
        $epeArr->subject_id = $request->subject_id;
        $epeArr->type = $request->type;
        //$epeArr->phase_id = $phaseToSubjectInfoObj->phase_id;
        $epeArr->title = $request->title;
        $epeArr->total_mark = $request->total_mark;
        $epeArr->questionnaire_format = $request->questionnaire_format;
        $epeArr->exam_date = $request->exam_date;
        $epeArr->start_time = $request->start_time;
        $epeArr->end_time = $request->end_time;
        $epeArr->obj_duration_hours = $request->obj_duration_hours;
        $epeArr->obj_duration_minutes = $request->obj_duration_minutes;
        $epeArr->submission_deadline = !empty($request->submission_deadline) ? $request->submission_deadline : null;
        $epeArr->result_publish = !empty($request->result_publish) ? $request->result_publish : null;
        $epeArr->obj_no_question = $request->obj_no_question;
        if (!empty($request->obj_auto_selected)) {
            $epeArr->obj_auto_selected = $request->obj_auto_selected;
        }
        $epeArr->no_of_mock = $request->no_of_mock;
        if (!empty($request->file)) {
            $epeArr->file = $fileName;
        }

        $epeArr->status = $request->status;

        if ($epeArr->save()) {
            // start epe qus type details
            $epeQusTypeData = [];
            if (!empty($qusType)) {
                foreach ($qusType as $qusTypeId => $value) {
                    $epeQusTypeData[$qusTypeId]['epe_id'] = $epeArr->id;
                    $epeQusTypeData[$qusTypeId]['qustion_type_id'] = $qusTypeId;
                    $epeQusTypeData[$qusTypeId]['total_qustion'] = $qusTypeTotal[$qusTypeId];
                }
            }
            if (!empty($epeQusTypeData)) {
                EpeQusTypeDetails::where('epe_id', $epeArr->id)->delete();
                EpeQusTypeDetails::insert($epeQusTypeData);
            }
            // end epe qus type details
            //if auto selected is set to 1; select questions of this subject 
            //randomly and save in epe_to_question table
            if ($epeArr->obj_auto_selected == '1') {

                //in case of edit, just delete the previous question set
                if (!empty($request->id)) {
                    EpeToQuestion::where('epe_id', $request->id)->delete();
                }

                //we implement the following logic only in case the total number of 
                //question is > 8; else matching questions will not be included
                //matching question will get priority here and then other all type of question

                $noOfRestQuestion = $request->obj_no_question;
                $multipleAnsArr = array();
                if (!empty($qusTypeTotal[1])) {
                    $multipleAnsArr = Question::where('subject_id', $request->subject_id)
                                    ->where('status', '1')
                                    ->where('type_id', 1)
                                    ->orderBy(DB::raw('RAND()'))->limit($qusTypeTotal[1])
                                    ->pluck('question', 'id')->toArray();
                }
                $fillingArr = array();
                if (!empty($qusTypeTotal[3])) {
                    $fillingArr = Question::where('subject_id', $request->subject_id)
                                    ->where('status', '1')
                                    ->where('type_id', 3)
                                    ->orderBy(DB::raw('RAND()'))->limit($qusTypeTotal[3])
                                    ->pluck('question', 'id')->toArray();
                }
                $subjectiveArr = array();
                if (!empty($qusTypeTotal[4])) {
                    $subjectiveArr = Question::where('subject_id', $request->subject_id)
                                    ->where('status', '1')
                                    ->where('type_id', 4)
                                    ->orderBy(DB::raw('RAND()'))->limit($qusTypeTotal[4])
                                    ->pluck('question', 'id')->toArray();
                }
                $trueFalseArr = array();
                if (!empty($qusTypeTotal[5])) {
                    $trueFalseArr = Question::where('subject_id', $request->subject_id)
                                    ->where('status', '1')
                                    ->where('type_id', 5)
                                    ->orderBy(DB::raw('RAND()'))->limit($qusTypeTotal[5])
                                    ->pluck('question', 'id')->toArray();
                }

                $questionArrPre = $trueFalseArr + $subjectiveArr + $fillingArr + $multipleAnsArr;
                $totalMark = 0;
                if (!empty($epeArr->total_mark)) {
                    $totalMark = $epeArr->total_mark;
                }

                $questionArr = array();
                if (!empty($questionArrPre)) {
                    $i = 0;
                    $marks = $totalMark / $noOfRestQuestion;

                    $totalMinute = ($epeArr->obj_duration_hours * 60) + ($epeArr->obj_duration_minutes);

                    $minutes = $totalMinute / $noOfRestQuestion;
                    $zero = new DateTime('@0');
                    $offset = new DateTime('@' . $minutes * 60);
                    $diff = $zero->diff($offset);
                    foreach ($questionArrPre as $qusId => $qustionName) {
                        $questionArr[$i]['epe_id'] = $epeArr->id;
                        $questionArr[$i]['question_id'] = $qusId;
                        $questionArr[$i]['mark'] = $marks;
                        $questionArr[$i]['time'] = $diff->format('%h:%i:%s');
                        $i++;
                    }
                }
                if (!empty($questionArr)) {
                    EpeToQuestion::insert($questionArr);
                }
            }

            if (!empty($epeId)) {
                return Response::json(array('success' => TRUE, 'data' => $request->title . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY')), 200);
            } else {
                return Response::json(array('success' => TRUE, 'data' => $request->title . __('label.HAS_BEEN_CREATED_SUCESSFULLY')), 200);
            }
        } else {
            if (!empty($epeId)) {
                return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => $request->title . __('label.COUD_NOT_BE_UPDATED')), 401);
            } else {
                return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => $request->title . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY')), 401);
            }
        }
    }

    public function previewEpe(Request $request) {
        $qusTypeList = QuestionType::orderBy('id', 'asc')->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id')->toArray();

        $subjectId = $request->subject_id;

        //Get subject list
        if (Auth::user()->group_id == 4) {

            $subjectList = SubjectToDs::join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                            ->join('phase_to_subject', 'phase_to_subject.subject_id', '=', 'subject_to_ds.subject_id')
                            ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
//                    ->where('subject_to_ds.course_id', '=', $courseId)
//                    ->where('subject_to_ds.part_id', '=', $partId)
                            ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                            ->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title"))
                            ->orderBy('subject.order', 'ASC')
                            ->pluck('title', 'id')->toArray();
        } else {
            $subjectList = Subject::select('subject.id', DB::raw("CONCAT(subject.title,' » ', subject.code) AS title"))
                            ->orderBy('subject.order', 'ASC')
                            ->pluck('title', 'id')->toArray();
        }

        $data['subjectList'] = array('' => __('label.SELECT_SUBJECT_OPT')) + $subjectList;
        $qusFormatList = [0 => __('label.QUESTIONNAIRE_FORMAT_OPT'), 1 => __('label.ONE_TO_ONE'), 2 => __('label.ONE_TO_MANY')];
        $data['prevData'] = $request->all();
        $data['qusTypeList'] = $qusTypeList;
        $data['qusFormatList'] = $qusFormatList;

        $returnHTML = view('epe/previewEpe', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id) {
        // Get the EPE Information
        $qusTypeArr = QuestionType::orderBy('id', 'asc')->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id');
        $epeObjArr = Epe::find($id);
        if (empty($epeObjArr)) {
            Session::flash('error', __('label.EPE_NOT_FOUND'));
            return Redirect::to('epe');
        }
        $qusQusTypeDetailList = [];
        if (!empty($epeObjArr)) {
            $qusQusTypeDetailList = EpeQusTypeDetails::where('epe_id', $epeObjArr->id)->pluck('total_qustion', 'qustion_type_id');
        }
        $data['epeObjArr'] = $epeObjArr;
        $data['qusQusTypeDetailList'] = $qusQusTypeDetailList;
        $data['qusTypeArr'] = $qusTypeArr;
        $qusFormatList = [0 => __('label.QUESTIONNAIRE_FORMAT_OPT'), 1 => __('label.ONE_TO_ONE'), 2 => __('label.ONE_TO_MANY')];
        $data['qusFormatList'] = $qusFormatList;
        $subjectId = $epeObjArr->subject_id;

        $typeWiseQuestionArr = Question::where('subject_id', $subjectId)
                ->where('status', '1')
                ->select(DB::raw("COUNT(type_id) as totalQuestion"), 'type_id')
                ->groupBy('type_id')
                ->pluck('totalQuestion', 'type_id')
                ->toArray();

        $data['typeWiseQuestionArr'] = $typeWiseQuestionArr;

        //Get subject list
        if (Auth::user()->group_id == 4) {

            $subjectList = SubjectToDs::join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                            ->join('phase_to_subject', 'phase_to_subject.subject_id', '=', 'subject_to_ds.subject_id')
                            ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
                            ->where('subject_to_ds.course_id', '=', $courseId)
                            ->where('subject_to_ds.part_id', '=', $partId)
                            ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                            ->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title"))
                            ->orderBy('subject.order', 'ASC')
                            ->pluck('title', 'id')->toArray();
        } else {
            $subjectList = Subject::select('subject.id', DB::raw("CONCAT(subject.title,' » ', subject.code) AS title"))
                            ->orderBy('subject.order', 'ASC')
                            ->pluck('title', 'id')->toArray();
        }

        $data['subjectList'] = array('' => __('label.SELECT_SUBJECT_OPT')) + $subjectList;

        //Get Avaiable Objective Question
        $objectiveQuestionCount = Question::where('subject_id', $subjectId)->where('status', '1')->whereIn('type_id', [1, 3, 4, 5])->count();
        $data['objectiveQuestionCount'] = $objectiveQuestionCount;

        //Get Avaiable Subjective Question
        $subjectiveQuestionCount = Question::where('subject_id', $subjectId)->where('status', '1')->where('type_id', 4)->count();
        $data['subjectiveQuestionCount'] = $subjectiveQuestionCount;

        $hoursArr = array();
        for ($h = 0; $h <= 23; $h++) {
            $hoursArr[$h] = (strlen($h) === 1) ? '0' . $h : $h;
        }
        $data['hoursList'] = $hoursArr;

        $minutesArr = array();
        for ($m = 0; $m <= 59; $m++) {
            $minutesArr[$m] = (strlen($m) === 1) ? '0' . $m : $m;
        }
        $data['minutesList'] = $minutesArr;
        $examType = ['' => __('label.SELECT_EXAM_TYPE'), '1' => __('label.REGULER'), '2' => __('label.RETAKE')];
        $data['examType'] = $examType;
        // show the edit form and pass the usere
        return view('epe.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {

        //check dependency
//        $hasRelationMockTest = MockTest::where('epe_id', $id)->first();
//
//        if (!empty($hasRelationMockTest)) {
//            Session::flash('error', __('label.EPE_HAS_RELATIONSHIP_WITH_MOCK_TEST'));
//            return Redirect::to('epe');
//        }
//
//        $hasRelationEpeMark = EpeMark::where('epe_id', $id)->first();
//
//        if (!empty($hasRelationEpeMark)) {
//            Session::flash('error', __('label.THIS_EPE_HAS_DEPENDENT_DATA_SET_CAN_NOT_BE_DELETED'));
//            return Redirect::to('epe');
//        }
        // Get EPE Info
        $epe = Epe::find($id);

        DB::beginTransaction();
        try {

            //check dependency
            $dependencyArr = ['ExamToStudent'=>'exam_id','MockTest' => 'epe_id','AttendeeRecord'=>'epe_id'];
            foreach ($dependencyArr as $model => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();

                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return Redirect::to('epe');
                }
            }

            //Delete data from EpeToQuestion table
            $epeToQuestion = EpeToQuestion::where('epe_id', '=', $id)->delete();
            $epeToTypeDetails = EpeQusTypeDetails::where('epe_id', '=', $id)->delete();

            //Delete EPE from EPE table
            $epe->delete();
            $epe->deleted_by = Auth::user()->id;
            $epe->save();

            DB::commit();

            Session::flash('success', $epe->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('epe');
        } catch (Exception $ex) {
            DB::rollback();
            Session::flash('error', $epe->title . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('epe');
        }
    }

    //This function use for objective question set
    public function questionSet(Request $request, $id) {
        $epeInfo = Epe::join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('epe.id', $id)
                ->select('subject.title as subject_title', 'epe.*')
                ->first();
        $data['epeInfo'] = $epeInfo;
        $epeQusTypeList = EpeQusTypeDetails::where('epe_id', $id)->pluck('total_qustion', 'qustion_type_id')->toArray();
        $questionTypeList = QuestionType::whereIn('id', array_keys($epeQusTypeList))->pluck('name', 'id')->toArray();

        $questionArr = Question::join('question_type', 'question_type.id', '=', 'question.type_id', 'left')
                ->leftJoin('epe_to_question', function($join) use($id) {
                    $join->on('epe_to_question.question_id', '=', 'question.id');
                    $join->where('epe_to_question.epe_id', '=', $id);
                })->select('question.id', 'question_type.name', 'question.question', 'question.type_id', 'question.content_type_id', 'epe_to_question.epe_id'
                        , 'epe_to_question.mark', 'epe_to_question.time', 'epe_to_question.question_id')
                ->where('question.subject_id', $epeInfo->subject_id)
                ->where('question.status', '1')
                ->whereIn('question.type_id', array_keys($epeQusTypeList))
                ->orderBy('epe_to_question.question_id', 'desc')
                ->orderBy('question.id', 'asc')
                ->get();

        //Find out the count how many question is already selected
        $alreadySelected = 0;
        $alreadySelectArr = array();
        $i = $j = $k = $l = 1;
        if (!empty($questionArr)) {
            foreach ($questionArr as $question) {
                if (!empty($question->epe_id)) {
                    $alreadySelected++;
                }
                if ($question->type_id == '1') {
                    $alreadySelectArr[$question->type_id] = $i++;
                    $classArr[$question->type_id] = 'multi-anser';
                }
                if ($question->type_id == '3') {
                    $alreadySelectArr[$question->type_id] = $j++;
                    $classArr[$question->type_id] = 'filing';
                }
                if ($question->type_id == '4') {
                    $alreadySelectArr[$question->type_id] = $k++;
                    $classArr[$question->type_id] = 'subjective';
                }
                if ($question->type_id == '5') {
                    $alreadySelectArr[$question->type_id] = $l++;
                    $classArr[$question->type_id] = 'true-false';
                }
            }
        }
        $data['questions'] = $questionArr;
        $data['alreadySelected'] = $alreadySelected;
        $data['epeQusTypeList'] = $epeQusTypeList;
        $data['questionTypeList'] = $questionTypeList;
        $data['alreadySelectArr'] = $alreadySelectArr;
        $data['classArr'] = $classArr;

        return view('epe.questionset', $data);
    }

    //This function use for store objective question set
    public function updatedQuestionSet(Request $request) {
        $epeInfo = Epe::join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('epe.id', $request->epe_id)
                ->select('subject.title as subject_title', 'epe.*')
                ->first();
        $questionArr = $request->question_id;
        $slNoArr = $request->sl_no;
        $epeId = $request->epe_id;
        $markArr = $request->mark;
        $timeArr = $request->time;
        $totalNoque = $request->total_noque;
        $data = array();
        $error = [];
        if (!empty($questionArr)) {
            foreach ($questionArr as $questionId => $item) {
                if (empty($markArr[$questionId])) {
                    $error['m_' . $questionId] = __('label.SL') . ' : ' . $slNoArr[$questionId] . ' ' . __('label.MARK_IS_REQUIRED');
                }
                if ($epeInfo->questionnaire_format == '1') {
                    if ($timeArr[$questionId] == '0:00:00') {
                        $error['t' . $questionId] = __('label.SL') . ' : ' . $slNoArr[$questionId] . ' ' . __('label.TIME_IS_REQUIRED');
                    }
                }
            }
        }
        if (!empty($error)) {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => $error), 400);
        }
        if (!empty($questionArr)) {
            $i = 0;
            foreach ($questionArr as $questionId => $item) {
                $data[$i]['epe_id'] = $epeId;
                $data[$i]['question_id'] = $questionId;
                $data[$i]['mark'] = $markArr[$questionId];
                if ($epeInfo->questionnaire_format == '1') {
                    $data[$i]['time'] = !empty($timeArr[$questionId]) ? $timeArr[$questionId] : 0;
                }
                $i++;
            }
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => __('label.YOU_HAVE_TO_SELECTED_AT_LEAST') . $totalNoque . ' questions'), 401);
        }
        if ($i < $totalNoque) {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => __('label.YOU_HAVE_TO_SELECTED_AT_LEAST') . $totalNoque . ' questions'), 401);
        }
        //delete existing data
        EpeToQuestion::where('epe_id', $epeId)->delete();

        $questionSet = EpeToQuestion::insert($data);

        if ($questionSet) {
            return Response::json(array('success' => TRUE, 'data' => 'Question Set ' . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => 'Question Set ' . __('label.COUD_NOT_BE_UPDATED')), 401);
        }
    }

    //This function use for objective question view
    public function questionDetails(Request $request) {
        $epeId = $request->epe_id;
        //Get EPE Information
        $epeInfo = Epe::where('epe.id', $epeId)->with(array('subject'))->first();
        if (empty($epeInfo)) {
            return Response::json(array('success' => false, 'heading' => 'EPE Invalid', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
        }
        $data['epeInfo'] = $epeInfo;

        //Finding Multiple Choice Single Answer question
        $questionType1 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 1)
                        ->where('epe_id', $epeId)->get();

        //Finding true or false question
        $questionType5 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 5)
                        ->where('epe_id', $epeId)->get();

        //Finding Filling the Blank question
        $questionType3 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 3)
                        ->where('epe_id', $epeId)->get();

        //Finding Matching question
        $questionType6 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id', 'question.match_answer')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 6)
                        ->where('epe_id', $epeId)->get();
        //Finding Subjective question
        $questionType4 = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id', 'question.match_answer')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 4)
                        ->where('epe_id', $epeId)->get();


        $data['objective'] = $questionType1;
        $data['trueFalse'] = $questionType5;
        $data['fillingBlank'] = $questionType3;
        $data['matchingArr'] = $questionType6;
        $data['sebjectiveArr'] = $questionType4;
         
        
        if ($request->view == 'print') {
            return view('epe/print/objectiveQuestionPrint', $data);
        }
        $returnHTML = view('epe/objectiveQuestionView', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for subjective question set view layout
    public function subQuestionSet(Request $request, $id) {

        //Get EPE Information
        $epeInfo = Epe::where('epe.id', $id)->with(array('subject'))->first();
        if (empty($epeInfo)) {
            Session::flash('error', 'The EPE you are trying to access doesn\'t exists!');
            return Redirect::to('epe');
        }
        //Get EPE Mark details

        $previousData = array();
        $previousArr = EpeSubQusSet::where('epe_id', $id)->get();

        if (!empty($previousArr)) {
            foreach ($previousArr->toArray() as $item) {
                $previousData[$item['set_id']] = $item;
            }
        }

        $data['previousData'] = $previousData;
        $data['epeInfo'] = $epeInfo;
        $data['markDistribution'] = $markDistribution;
        return view('epe.subquestionset', $data);
    }

    //This function use for store subjective question set
    public function storeSubQusSet(Request $request) {

        //Get EPE Information
        $epeInfo = Epe::where('epe.id', $request->epe_id)->first();
        if (empty($epeInfo)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
        }

        $epeId = $request->epe_id;
        $settitleArr = $request->set_title;
        $setIdArr = $request->set_id;
        $markArr = $request->mark;
        $optionsArr = $request->options;
        $noOfQusArr = $request->no_of_qus;
        $answerArr = $request->answer;

        $saveFields = array();
        if (!empty($setIdArr)) {

            for ($q = 1; $q <= count($setIdArr); $q++) {

                //This section is use for validation
                $rules['mark.' . $q] = 'required';
                $rules['no_of_qus.' . $q] = 'required';

                $option = empty($optionsArr[$q]) ? 0 : $optionsArr[$q];
                if (!empty($optionsArr[$q])) {
                    $rules['answer.' . $q] = 'required';
                }

                $messages['mark.' . $q . '.required'] = 'Q' . $q . ' Marks Field Is Required.';
                $messages['no_of_qus.' . $q . '.required'] = 'Q' . $q . ' Total Question Field Is Required.';
                if (!empty($option)) {
                    $messages['answer.' . $q . '.required'] = 'Q' . $q . ' Answer Field Is Required.';
                }

                //Array prepare for subjective question set store
                $saveFields[$q]['epe_id'] = $epeId;
                $saveFields[$q]['set_id'] = $setIdArr[$q];
                $saveFields[$q]['set_title'] = $settitleArr[$q];
                $saveFields[$q]['mark'] = $markArr[$q];
                $saveFields[$q]['options'] = empty($optionsArr[$q]) ? null : $optionsArr[$q];
                $saveFields[$q]['no_of_qus'] = $noOfQusArr[$q];
                $saveFields[$q]['answer'] = empty($answerArr[$q]) ? null : $answerArr[$q];
                $saveFields[$q]['created_by'] = Auth::user()->id;
                $saveFields[$q]['updated_by'] = Auth::user()->id;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        DB::beginTransaction();
        try {
            //Delete existing data where epe_id
            EpeSubQusSet::where('epe_id', $epeId)->delete();

            if (!empty($saveFields)) {
                //Insert for Subjective Question Sets
                EpeSubQusSet::insert($saveFields);
            }
            DB::commit();
            // all good
            return Response::json(array('success' => TRUE, 'data' => __('label.SUBJECTIVE_QUESTION_SET_CREATED') . ' for ' . $epeInfo->title), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => __('label.SUBJECTIVE_QUESTION_SETS_COULD_NOT_BE_CREATED') . ' for ' . $epeInfo->title . '. ' . __('label.PLEASE_TRY_AGAIN')), 401);
        }
    }

    //This function use for subjective question details
    public function subjectiveQuestionDetails(Request $request) {
        $epeId = $request->epe_id;

        //Get EPE Information
        $epeInfo = Epe::where('epe.id', $epeId)->with(array('course', 'part', 'subject', 'phase'))->first();
        if (empty($epeInfo)) {
            return Response::json(array('success' => false, 'heading' => 'EPE Invalid', 'message' => 'The EPE you are trying to access doesn\'t exists!'), 401);
        }
        $data['epeInfo'] = $epeInfo;
        //Get EPE Mark details
        //Select subjective question details
        $questionDetails = EpeSubQus::join('question', 'question.id', '=', 'epe_sub_to_question.question_id')
                        ->join('epe_sub_qus_set', function($join) {
                            $join->on('epe_sub_qus_set.epe_id', '=', 'epe_sub_to_question.epe_id');
                            $join->on('epe_sub_qus_set.set_id', '=', 'epe_sub_to_question.set_id');
                        })
                        ->select('epe_sub_to_question.*', 'question.question', 'question.note', 'question.document'
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
            return view('epe/print/subjective_question_print', $data);
        }
        $returnHTML = view('epe/subjective_question_view', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    //This function use for subjective question
    public function subQuestion(Request $request) {

        $epeId = $request->epe_id;
        $setId = $request->set_id;

        //Get EPE Subjective Question Set Informations
        $epesubqussetArr = EpeSubQusSet::where('epe_id', $epeId)->get();
        // check for empty EPE
        if (empty($epesubqussetArr->toArray())) {
            Session::flash('error', 'The EPE you are trying to access doesn\'t exists!');
            return Redirect::to('epe');
        }
        if ((!empty($epeId)) && (!empty($setId))) {

            $epeInfo = Epe::where('epe.id', $epeId)->first();
            $data['epeInfo'] = $epeInfo;
            $questionArr = Question::join('question_type', 'question_type.id', '=', 'question.type_id', 'left')
                    ->leftJoin('epe_sub_to_question', function($join) use($setId, $epeId) {
                        $join->on('epe_sub_to_question.question_id', '=', 'question.id');
                        $join->where('epe_sub_to_question.set_id', '=', $setId);
                        $join->where('epe_sub_to_question.epe_id', '=', $epeId);
                    })->select('question.id', 'question_type.name', 'question.question', 'epe_sub_to_question.set_id')
                    ->where('question.subject_id', $epeInfo->subject_id)
                    ->where('question.status', '1')
                    ->where('question.type_id', '4')
                    ->orderBy('epe_sub_to_question.question_id', 'desc')
                    ->orderBy('question.id', 'asc')
                    ->get();


            // Find out the question is already selected
            $alreadyArr = EpeSubQus::where('epe_id', $epeId)->get();
            $alreadyData = array();
            if (!empty($alreadyArr)) {
                foreach ($alreadyArr->toArray() as $item) {
                    $alreadyData[$item['question_id']] = $item;
                }
            }
            $alreadySelected = 0;
            if (!empty($questionArr)) {
                foreach ($questionArr as $question) {
                    if (!empty($question->set_id)) {
                        $alreadySelected++;
                    }
                }
            }
            // find epe Sub Question
            $previousData = array();
            $previousArr = EpeSubQus::where('epe_id', $epeId)->where('set_id', $setId)->get();


            if (!empty($previousArr)) {
                foreach ($previousArr->toArray() as $item) {
                    $previousData[$item['question_id']] = $item;
                }
            }

            $subqussetArr = EpeSubQusSet::where('epe_id', $epeId)->where('set_id', $setId)->first();
            $data['subqussetArr'] = $subqussetArr;
            $data['questions'] = $questionArr;
            $data['previousData'] = $previousData;
            $data['alreadyData'] = $alreadyData;
        } else {
            Session::flash('error', 'The EPE you are trying to access doesn\'t exists!');
            return Redirect::to('epe');
        }

        $data['epesubqussetArr'] = $epesubqussetArr;

        return view('epe.subquestion', $data);
    }

    //This function use for show subjective question set wise question
    public function showSubQus(Request $request) {

        $epeId = $request->epe_id;
        $setId = $request->set_id;

        $epeInfo = Epe::where('epe.id', $epeId)->first();

        $data['epeInfo'] = $epeInfo;
        $questionArr = Question::join('question_type', 'question_type.id', '=', 'question.type_id', 'left')
                ->leftJoin('epe_sub_to_question', function($join) use($setId, $epeId) {
                    $join->on('epe_sub_to_question.question_id', '=', 'question.id');
                    $join->where('epe_sub_to_question.set_id', '=', $setId);
                    $join->where('epe_sub_to_question.epe_id', '=', $epeId);
                })->select('question.id', 'question_type.name', 'question.question', 'epe_sub_to_question.set_id')
                ->where('question.subject_id', $epeInfo->subject_id)
                ->where('question.status', '1')
                ->where('question.type_id', '4')
                ->orderBy('epe_sub_to_question.question_id', 'desc')
                ->orderBy('question.id', 'asc')
                ->get();
        // Find out question is already selected

        $alreadyArr = EpeSubQus::where('epe_id', $epeId)->get();
        $alreadyData = array();
        if (!empty($alreadyArr)) {
            foreach ($alreadyArr->toArray() as $item) {
                $alreadyData[$item['question_id']] = $item;
            }
        }
        $alreadySelected = 0;
        if (!empty($questionArr)) {
            foreach ($questionArr as $question) {
                if (!empty($question->set_id)) {
                    $alreadySelected++;
                }
            }
        }
        // find epe Sub Question
        $previousData = array();
        $previousArr = EpeSubQus::where('epe_id', $epeId)->where('set_id', $setId)->get();
        if (!empty($previousArr)) {
            foreach ($previousArr->toArray() as $item) {
                $previousData[$item['question_id']] = $item;
            }
        }

        $subqussetArr = EpeSubQusSet::where('epe_id', $epeId)->where('set_id', $setId)->first();
        $data['subqussetArr'] = $subqussetArr;
        $data['questions'] = $questionArr;
        $data['previousData'] = $previousData;
        $data['alreadyData'] = $alreadyData;

        return view('epe.showsubqus', $data);
    }

    //This function use for store subjective questions
    public function storeSubQus(Request $request) {
        // echo '<pre>';
        // print_r($request->all());exit;



        $epeId = $request->epe_id;
        $setId = $request->set_id;
        $questionArr = $request->question_id;
        $markArr = $request->mark;


        $targetArr = array();
        $i = 0;
        $totalMark = 0;
        $rules = array();

        if (!empty($epeId) && !empty($setId) && !empty($questionArr)) {

            foreach ($questionArr as $key => $question) {
                if (trim($question) != '') {
                    $targetArr[$i]['epe_id'] = $epeId;
                    $targetArr[$i]['set_id'] = $setId;
                    $targetArr[$i]['question_id'] = $question;
                    $targetArr[$i]['mark'] = $markArr[$question];
                    $totalMark += $markArr[$question];
                    $i++;
                }
                if (!empty($epeId) && !empty($setId) && !empty($question)) {
                    $rules['mark.' . $key] = 'required';
                    $messages['mark.' . $key . '.required'] = 'Q' . $setId . ' Marks Is Empty';
                }
            }
        }

        if (!empty($epeId) && !empty($setId) && !empty($questionArr)) {
            $validator = Validator::make($request->all(), $rules, $messages);
            //Get error message
            $errorMessage = $validator->errors()->toArray();

            if ($validator->fails()) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errorMessage), 400);
            }
        }

        //check empty question
        $subqussetArr = EpeSubQusSet::where('epe_id', $epeId)->where('set_id', $setId)->first();
        if (!empty($subqussetArr->no_of_qus)) {
            if ($i < $subqussetArr->no_of_qus) {
                return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => __('label.YOU_HAVE_TO_SELECTED_AT_LEAST') . $subqussetArr->no_of_qus . ' questions'), 401);
            }
        }
        //check maximum marks 
        if ((!empty($subqussetArr->mark)) && (empty($subqussetArr->options))) {
            if (($subqussetArr->mark < $totalMark) || ($subqussetArr->mark > $totalMark)) {
                return Response::json(array('success' => TRUE, 'message' => 'Put maximum marks ' . $subqussetArr->mark), 401);
            }
        }


        if (!empty($targetArr)) {
            //Delete old data
            EpeSubQus::where('epe_id', $epeId)->where('set_id', $setId)->delete();
            if (EpeSubQus::insert($targetArr)) {
                //Session::flash('success', __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
                return Response::json(array('success' => TRUE, 'data' => __('label.EPE_QUESTION_SET_CREATED_SUCESSFULLY')), 200);
            } else {
                //Session::flash('error', __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
                return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => __('label.COULD_NOT_BE_SET_SUCESSFULLY')), 401);
            }
        } else {
            return Response::json(array('success' => TRUE, 'message' => 'Something went wrong'), 401);
        }
    }

    // bakibillah
    public function updatePublish(Request $request) {
        $id = $request->id;
        $target = Epe::where('id', $id)->select('id', 'submission_deadline', 'result_publish')->first();
        $data['target'] = $target;
        $returnHTML = view('epe.update_publish', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function updatedPublish(Request $request) {
        Helper::dump($request->all());

        $id = $request->id;

        $rules['id'] = 'required';
        $rules['submission_deadline'] = 'required';
        $rules['result_publish'] = 'required';
        $messages = array(
            'submission_deadline.required' => 'Result submission deadline is required!',
            'result_publish.required' => 'Result publish date Time is required!'
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $epeArr = Epe::find($id);

        $resultSubmissionDate = $request->submission_deadline;
        $resultPublish = $request->result_publish;

        if ((strtotime($resultSubmissionDate)) > (strtotime($resultPublish))) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.RESULT_PUBLISH_DATE_TIME_MUST_BE_GREATER_THEN_RESULT_SUBMISSION_DEADLINE')), 401);
        }

        $epeArr->submission_deadline = $request->submission_deadline;
        $epeArr->result_publish = !empty($request->result_publish) ? $request->result_publish : null;

        if ($epeArr->save()) {
            if (!empty($id)) {
                return Response::json(array('success' => TRUE, 'data' => __('label.HAS_BEEN_UPDATED_SUCCESSFULLY')), 200);
            }
        } else {
            if (!empty($id)) {
                return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => __('label.COUD_NOT_BE_UPDATED')), 401);
            }
        }
    }

}

?>
