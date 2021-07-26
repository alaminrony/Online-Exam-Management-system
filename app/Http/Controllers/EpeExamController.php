<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\EpeQusTypeDetails;
use App\MockTest;
use App\EpeMark;
use App\AttendeeRecord;
use App\EpeToQuestion;
use App\Question;
use App\EpeQusSubmitDetails;
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
use Image;
use Response;
use Illuminate\Http\Request;

class EpeExamController extends Controller {

    public function disclaimer(Request $request) {
        //Find the EPE Type for this EPE
        $existEpe = Epe::find($request->id);
        if ($existEpe->type == '1') {
            $epe = Epe::find($request->id);
        } else if (in_array($existEpe->type, array('2', '3'))) {
            $epe = Epe::join('epe_details', 'epe.id', '=', 'epe_details.epe_id')
                    ->select('epe.*', 'epe_details.id as epe_details_id')
                    ->with(array('Subject'))
                    ->where('epe.id', $request->id)
                    ->where(function ($query) {
                        $query->whereNull('epe_details.branch_id')
                        ->orWhere('epe_details.branch_id', Auth::user()->branch_id);
                    })
                    ->first();
        }
        if (empty($epe)) {
            $message['short'] = 'Invalid EPE';
            $message['long'] = 'The EPE you are trying to access doesn\'t exists!';
            return view('epeExam.examUnavailable')->with(compact('message'));
        }
        if (Session::get('disclaimer') == $request->id) {
            return Redirect::to('/epeExam?id=' . $request->id);
        }
        return view('epeExam.disclaimer')->with(compact('epe'));
    }

    public function setDisclaimer(Request $request) {
        Session::put('disclaimer', $request->epe_id);
        return Response::json(array('success' => true));
    }

    public function exam(Request $request) {
        
        if (Session::get('disclaimer') != $request->id) {
            return Redirect::to('/disclaimer?id=' . $request->id);
        }

        //Completed mock test
        $mockTest = 0;
        //Find the EPE Type for this EPE
        $existEpe = Epe::find($request->id);



        $epeQusTypeList = EpeQusTypeDetails::where('epe_id', $existEpe->id)->pluck('total_qustion', 'qustion_type_id')->toArray();

        $examId = $request->id;
        $epe = Epe::with(array('Subject'))->find($request->id);



        if (empty($epe)) {
            $message['short'] = 'Invalid Exam';
            $message['long'] = 'The Exam you are trying to access doesn\'t exists!';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }

        //EPE 
        $numberOfExam = MockTest::join('mock_mark', 'mock_test.id', '=', 'mock_mark.mock_id', 'left')
                ->where('mock_test.epe_id', $request->id)
                ->where('mock_mark.pass', '1')
                ->where('mock_mark.employee_id', Auth::user()->id)
                ->count();

        if ($epe->no_of_mock > $numberOfExam) {
            //Complete your mock test
            $mockTest = 1;
            $message['short'] = 'Exam Incompetency';
            $message['long'] = 'You did\'t complete your mock test';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }

        if ((strtotime(date('H:i:s')) > strtotime($epe->end_time)) || (strtotime(date('H:i:s')) < strtotime($epe->start_time))) {
//        if (strtotime(date('H:i:s') < strtotime($epe->start_time))) {
            $mockTest = 0;
            $message['short'] = 'Exam Not Available';
            $message['long'] = 'Exam is not available at this time!';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }

        if (empty($epe->total_mark)) {
            $mockTest = 0;
            $message['short'] = 'Mark Not Set';
            $message['long'] = 'Mark has not set for this Exam yet!';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }

        //get Question Set for this EPE
        $questionIdArr = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->whereIn('question.type_id', array_keys($epeQusTypeList))
                        ->where('epe_id', $epe->id)->pluck('question_id')->toArray();


        if (empty($questionIdArr)) {
            $mockTest = 0;
            $message['short'] = 'No Question Found';
            $message['long'] = 'No question found for this Exam!';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }


        $examDuration = ($epe->obj_duration_hours * 60) + $epe->obj_duration_minutes;

        //Look for if already Student is running this test

        $target = EpeMark::where('epe_id', $epe->id)->where('employee_id', Auth::user()->id)->first();




        if (empty($target)) {

            //Student just begun this test; insert a new record for him
            $target = new EpeMark;
            $target->epe_id = $request->id;
            $target->employee_id = Auth::user()->id;
            $target->exam_date = date('Y-m-d');
            $target->objective_start_time = date('H:i:s');

            $endTime = date("H:i:s", strtotime('+' . $examDuration . ' minutes', strtotime($target->objective_start_time)));
            $maxEndTime = $epe->exam_date . ' ' . $epe->end_time;

            if (strtotime($endTime) > strtotime($maxEndTime)) {
                $endTime = $epe->end_time;
            }

            $target->objective_end_time = $endTime;

            $target->total_mark = $epe->total_mark;
            $target->objective_mark = null;
            $target->subjective_mark = null;


            $target->epe_details_id = $epe->epe_details_id;


            $target->save();
            //Array prepared for store student attendee record
            $attendeeRecord = new AttendeeRecord;
            $attendeeRecord->employee_id = Auth::user()->id;
            $attendeeRecord->epe_id = $request->id;
            $attendeeRecord->epe_details_id = $epe->epe_details_id;
            $attendeeRecord->status = 1;
            $attendeeRecord->save();
        } else {
            if ($target->submitted == '1') { //2 == Subjective submitted
                $message['short'] = 'Exam Already Submitted';
                $message['long'] = 'You have already submitted your answers!';
                return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
            }
            //objective extended end time

            $cookieTime = !empty($_COOKIE['objTimeArr']) ? $_COOKIE['objTimeArr'] : '';
            $objTimeArr = json_decode($cookieTime);
            $currentTime = date('H:i:s');
            if (!empty($objTimeArr)) {
                $totalExamDurationSecond = $objTimeArr->hours * 3600 + $objTimeArr->minutes * 60 + $objTimeArr->seconds;
                $endTime = date("H:i:s", strtotime('+' . $totalExamDurationSecond . 'seconds', strtotime($currentTime)));
                $maxEndTime = $epe->exam_date . ' ' . $epe->end_time;
                if (strtotime($endTime) > strtotime($maxEndTime)) {
                    $endTime = $epe->end_time;
                }
                $target->objective_extended_end_time = $endTime;
                $target->save();
            }
        }

        $questionData = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->whereIn('question.type_id', array_keys($epeQusTypeList))
                        ->where('epe_id', $epe->id)->select('question_id', 'mark', 'time')->get();
        $epeToQusList = array();
        foreach ($questionData as $question) {
            $epeToQusList[$question->question_id]['mark'] = $question->mark;
            $currentTime1 = date("H:i:s");
            $time = "$question->time";
            $parsed = date_parse($time);
            $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
            $result = date("H:i:s", strtotime('+' . $seconds . 'seconds', strtotime($currentTime1)));
            $epeToQusList[$question->question_id]['time'] = $result;
        }
        $questionArr = Question::where('status', '1')->whereIn('id', $questionIdArr)->orderBy('id', 'asc')->get()->toArray();

        $iQuestionArr = array(); //IndexQuestionArr
        foreach ($questionArr as $qItem) {
            $iQuestionArr[$qItem['id']] = $qItem;
        }

        $questionQueue = ''; //to know the question order in time of reload
        $prevQuestionSet = Session::get('epeExam.' . $request->id . '.answerArr');
        if (isset($prevQuestionSet)) {
            $questionQueue = Session::get('epeExam.' . $request->id . '.question_queue');

            $srArr = explode(',', $questionQueue);

            $sortedArr = array();
            foreach ($srArr as $val) {
                // $sortedArr[$val] = isset($iQuestionArr[$val]) ? $iQuestionArr[$val] : '';
                $sortedArr[$val] = $iQuestionArr[$val];
            }

            $iQuestionArr = $sortedArr;
        } else {
            $iQuestionArr = $this->shuffle_assoc($iQuestionArr);
            $questionQueue = implode(',', array_keys($iQuestionArr));
        }

        $questionArr = $iQuestionArr;



        // new proses
        // one to one question start
        if ($epe->questionnaire_format == '1') {
            $qesSeData = [];
            $sl = 1;
            foreach ($questionArr as $qusId => $qus) {
                $qesSeData[$sl] = $qusId;
                $sl++;
            }


            $epeQusSubDetailArr = EpeQusSubmitDetails::where('epe_id', $epe->id)->where('employee_id', Auth::user()->id)->first();

            if (empty($epeQusSubDetailArr)) {
                $qusSerialNo = 1;
                $qusNo = isset($qesSeData[$qusSerialNo]) ? $qesSeData[$qusSerialNo] : '';
                $queDetail = array();
                foreach ($qesSeData as $qSId => $qId) {
                    $queDetail[$qSId]['epe_id'] = $epe->id;
                    $queDetail[$qSId]['employee_id'] = Auth::user()->id;
                    $queDetail[$qSId]['serial_id'] = $qSId;
                    $queDetail[$qSId]['question_id'] = $qId;
                    $queDetail[$qSId]['time'] = ($qusNo == $qId) ? $epeToQusList[$qusNo]['time'] : null;
                }
                EpeQusSubmitDetails::insert($queDetail);
            } else {
//                $seriarArr = EpeQusSubmitDetails::where('epe_id', $epe->id)->where('employee_id', Auth::user()->id)
//                                ->whereRaw('`serial_id` = (select min(`serial_id`) from epe_qus_submit_details)')->first();
                $seriarArr = EpeQusSubmitDetails::where('epe_id', $epe->id)->where('employee_id', Auth::user()->id)
                                ->orderBy('serial_id','asc')->first();
                $qusSerialNo = $seriarArr->serial_id;
                $qusNo = $seriarArr->question_id;
                $epeToQusList[$qusNo]['time'] = $seriarArr->time;
            }

            return view('epeExam.examOne')->with(compact('questionArr', 'epe', 'target', 'qusSerialNo', 'qusNo'
                                    , 'questionQueue', 'epeToQusList', 'examId', 'existEpe'));
            // one to one question end
        } else {

            return view('epeExam.examMany')->with(compact('questionArr', 'epe', 'target'
                                    , 'questionQueue', 'prevQuestionSet', 'examId', 'existEpe'));
        }
    }

    public function viewFile(Request $request) {
        $examInfo = Epe::where('id',$request->examId)->first();
        $file = public_path() . "/uploads/exam/pdf/" . $examInfo->file;
        $filename = 'book.pdf';
        if (empty($examInfo->file)) {
            echo 'Book Not Found';
            exit;
        }
        return Response::make(file_get_contents($file), 200, [
                    'Content-Type'
                    => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function submitExam(Request $request) {

        //Find the EPE Type for this EPE
        $existEpe = Epe::find($request->epe_id);


        $epe = Epe::with(array('Subject'))->find($request->epe_id);


//        $examMark = $markDistribution->objective;
        //$passMark = $markDistribution->epe_passing_mark;
        //$passed = 2; //Assumed that; student has failed initially


        $markList = EpeToQuestion::where('epe_id', $epe->id)->pluck('mark', 'question_id')->toArray();
        $questionArr = array();
        if ($epe->questionnaire_format == '1') {
            $qid = $request->question_id;
            if (!empty($qid)) {
                $questionArr = Question::where('id', $qid)->select('id', 'type_id', 'mcq_answer', 'ftb_answer', 'tf_answer', 'match_answer')->get();
            }
        } else {
            $qidArr = $request->question_id;
            if (!empty($qidArr)) {
                $questionArr = Question::whereIn('id', $qidArr)->select('id', 'type_id', 'mcq_answer', 'ftb_answer', 'tf_answer', 'match_answer')->get();
            }
        }
        $examData = array();
        $corretArr = array();
        if (!$questionArr->isEmpty()) {
            foreach ($questionArr as $question) {
                $examData[$question->id]['epe_mark_id'] = $request->epe_mark_id;
                $examData[$question->id]['question_id'] = $question->id;

                if ($question->type_id == '1') {
                    $corretArr[$question->id] = $question->mcq_answer;
                } else if ($question->type_id == '3') {
                    $corretArr[$question->id] = trim($question->ftb_answer);
                } else if ($question->type_id == '5') {
                    $corretArr[$question->id] = $question->tf_answer;
                } else if ($question->type_id == '6') {
                    $corretArr[$question->id] = $question->id;
                } else if ($question->type_id == '4') {
                    $corretArr[$question->id] = NULL;
                }

                $examData[$question->id]['correct_answer'] = $corretArr[$question->id];

                //Follwing data will be overrided later
                $examData[$question->id]['submitted_answer'] = '';
                $examData[$question->id]['correct'] = NULL;
                $examData[$question->id]['final_mark'] = null;
            }
        }
        $answerArr = $request->question;

        $totalMark = 0;
        if (!empty($answerArr)) {
            foreach ($answerArr as $qid => $val) {
                $examData[$qid]['submitted_answer'] = $val;
                if (!empty($val)) {
                    $examData[$qid]['correct'] = 0;
                }

                // if (strtolower(trim($val)) == strtolower($corretArr[$qid])) {
                if (strcasecmp(trim($val), $corretArr[$qid]) == 0) { //Added by bakibilah
                    $examData[$qid]['correct'] = 1;
                    //$examData[$qid]['ds_mark'] = !empty($markList[$qid])?$markList[$qid]:null;
                    $examData[$qid]['final_mark'] = !empty($markList[$qid]) ? $markList[$qid] : null;
                    $totalMark++;
                }
            }
        }
        //Insert data into details table
        EpeMarkDetails::insert($examData);

        // one to one start
        if ($epe->questionnaire_format == '1') {
            $epeQusSumArr = EpeQusSubmitDetails::where('epe_id', $existEpe->id)
                            ->where('employee_id', Auth::user()->id)
                            ->where('question_id', $qid)->first();
            if (!empty($epeQusSumArr)) {
                EpeQusSubmitDetails::where('epe_id', $existEpe->id)
                        ->where('employee_id', Auth::user()->id)
                        ->where('question_id', $qid)->delete();
                $seriarArr = EpeQusSubmitDetails::where('epe_id', $epe->id)->where('employee_id', Auth::user()->id)
                                ->orderBy('serial_id','asc')->first();
                if (empty($seriarArr)) {
                    $target = EpeMark::find($request->epe_mark_id);
                    $target->objective_submission_time = date('Y-m-d H:i:s');
                    $target->objective_earned_mark = null;
                    $target->objective_no_of_question = $epe->obj_no_question;
                    $target->objective_no_correct_answer = null;
                    $target->submitted = 1; //Objective submitted
                    $target->save();
                    EpeQusSubmitDetails::where('epe_id', $existEpe->id)
                            ->where('employee_id', Auth::user()->id)->delete();
                    Session::forget('epeExam');
                    return Response::json(['success' => true, 'url' => URL::to('/subjectiveComplete?id=' . $existEpe->id)], 200);
                } else {
                    $epeToQusArr = EpeToQuestion::where('epe_id', $existEpe->id)
                                    ->where('question_id', $seriarArr->question_id)->first();
                    $currentTime1 = date("H:i:s");
                    $time = "$epeToQusArr->time";
                    $parsed = date_parse($time);
                    $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
                    $result = date("H:i:s", strtotime('+' . $seconds . 'seconds', strtotime($currentTime1)));
                    EpeQusSubmitDetails::where('epe_id', $existEpe->id)->where('employee_id', Auth::user()->id)
                            ->where('serial_id', $seriarArr->serial_id)
                            ->update(array('time' => $result));
                    return Response::json(['success' => true, 'url' => URL::to('/epeExam?id=' . $request->epe_id)], 200);
                }
            }
        } else {
            $target = EpeMark::find($request->epe_mark_id);
            $target->objective_submission_time = date('Y-m-d H:i:s');
            $target->objective_earned_mark = null;
            $target->objective_no_of_question = $epe->obj_no_question;
            $target->objective_no_correct_answer = null;
            $target->submitted = 1; //Objective submitted
            $target->save();
            Session::forget('epeExam');
            return Response::json(['success' => true, 'url' => URL::to('/subjectiveComplete?id=' . $existEpe->id)], 200);
        }


        // one to one end
    }

    public function examSubjective(Request $request) {

        //Find the EPE Type for this EPE
        $existEpe = Epe::find($request->id);

        if ($existEpe->type == '1') {
            $epe = Epe::with(array('Subject'))->find($request->id);
        } else if (in_array($existEpe->type, array('2', '3'))) {
            $epe = Epe::join('epe_details', 'epe.id', '=', 'epe_details.epe_id')
                    ->select('epe.*', 'epe_details.id as epe_details_id', 'epe_details.course_id', 'epe_details.part_id', 'epe_details.phase_id', 'epe_details.branch_id')
                    ->with(array('Subject'))
                    ->where('epe.id', $request->id)
                    ->where('epe_details.course_id', Auth::user()->studentBasicInfo->course_id)
                    ->where('epe_details.part_id', Auth::user()->studentBasicInfo->part_id)
                    ->where(function ($query) {
                        $query->whereNull('epe_details.branch_id')
                        ->orWhere('epe_details.branch_id', Auth::user()->branch_id);
                    })
                    ->first();
        }

        if (empty($epe)) {
            $message['short'] = 'Invalid EPE';
            $message['long'] = 'The EPE you are trying to access doesn\'t exists!';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }

        if ((strtotime(date('Y-m-d H:i:s')) > strtotime($epe->exam_date . ' ' . $epe->end_time)) || (strtotime(date('Y-m-d H:i:s')) < strtotime($epe->exam_date . ' ' . $epe->start_time))) {
            $message['short'] = 'Exam Not Available';
            $message['long'] = 'Exam is not available at this time!';
            return view('epeExam.examUnavailable')->with(compact('message', 'mockTest'));
        }

        //check Objective has been submitted
        $epeMarkInfo = EpeMark::where('epe_id', $request->id)->where('student_id', Auth::user()->studentBasicInfo->id)->first();

        if (empty($epeMarkInfo) || ($epeMarkInfo->submitted == '0')) {
            $message['short'] = 'Objective not submitted';
            $message['long'] = 'Your objective script is not submitted yet!';
            return view('epeExam.examUnavailable')->with(compact('message'));
        }

        if ($epeMarkInfo->submitted == '2') {
            $message['short'] = 'Answer script already submitted!';
            $message['long'] = 'You have already submitted your answer script!';
            return view('epeExam.examUnavailable')->with(compact('message'));
        }

        //let gather all question
        $questionArr = Question::where('type_id', 4)->select('id', 'question', 'note')->get();



        $examDuration = ($epe->duration_hours * 60) + $epe->duration_minutes;

        //Look for if already Student is running this test
        $target = EpeMark::where('epe_id', $epe->id)->where('student_id', Auth::user()->studentBasicInfo->id)->first();

        if ($target->subjective_start_time == '00:00:00') {
            $target->subjective_start_time = date('H:i:s');

            $subExamDuration = ($epe->sub_duration_hours * 60) + $epe->sub_duration_minutes;
            $subEndTime = date("H:i:s", strtotime('+' . $subExamDuration . ' minutes', strtotime($target->subjective_start_time)));

            $maxEndTime = $epe->exam_date . ' ' . $epe->end_time;
            if (strtotime($subEndTime) > strtotime($maxEndTime)) {
                $subEndTime = $epe->end_time;
            }
            $target->subjective_end_time = $subEndTime;
            $target->save();
        } else {

            //subjective extended end time
            $cookieTime = $_COOKIE['timeArr'];
            $subTimeArr = json_decode($cookieTime);

            $currentTime = date('H:i:s');
            $subExamDurationSecond = $subTimeArr->hours * 3600 + $subTimeArr->minutes * 60 + $subTimeArr->seconds;

            $subExtendedEndTime = date("H:i:s", strtotime('+' . $subExamDurationSecond . 'seconds', strtotime($currentTime)));

            $maxEndTime = $epe->exam_date . ' ' . $epe->end_time;
            if (strtotime($subExtendedEndTime) > strtotime($maxEndTime)) {
                $subExtendedEndTime = $epe->end_time;
            }

            $target->subjective_extended_end_time = $subExtendedEndTime;
            $target->save();
        }


        $epeSubQusSetPre = EpeSubQusSet::where('epe_id', $epe->id)->get()->toArray();

        $epeSubQusSet = array();
        if (!empty($epeSubQusSetPre)) {
            foreach ($epeSubQusSetPre as $item) {
                $epeSubQusSet[$item['set_id']] = $item;
            }
        }

        $epeSubQus = EpeSubQus::join('question', 'question.id', '=', 'epe_sub_to_question.question_id')
                        ->select('epe_sub_to_question.*', 'question.question', 'question.note', 'question.image')
                        ->where('epe_id', $epe->id)
                        ->get()->toArray();

        $questionSetArr = array();

        if (!empty($epeSubQus)) {
            foreach ($epeSubQus as $item) {
                $questionSetArr[$item['set_id']][] = $item;
            }
        }


        //Get existing data for subjective
        $getSubjectiveMarkExistingDataArr = SubjectiveMark::where('epe_mark_id', '=', $target->id)->get()->toArray();
        $existingDataArr = array();
        if (!empty($getSubjectiveMarkExistingDataArr)) {
            foreach ($getSubjectiveMarkExistingDataArr as $item) {
                $existingDataArr[$item['set_id']]['question'][$item['question_id']] = $item;
            }
        }

        //Get lock question set List
        $lockQuestionSetList = SubjectiveMark::where('epe_mark_id', '=', $target->id)->where('lock', '=', '1')->groupBy('set_id')->pluck('lock', 'set_id')->toArray();

        $hasOptionSetList = SubjectiveMark::where('epe_mark_id', '=', $target->id)->where('has_options', '=', '1')->groupBy('set_id')->pluck('has_options', 'set_id')->toArray();
        //Get MAx Selected question for ontional question set
        $maxSelectedQuestionAnswerForOptionalSetList = SubjectiveMark::select(DB::raw("count(id) as total_answer"), 'set_id')->where('epe_mark_id', '=', $target->id)
                        ->where('has_options', '=', '1')
                        ->groupBy('set_id')->pluck('total_answer', 'set_id')->toArray();


        return view('epeExam.subjectiveExam')->with(compact('epe', 'target', 'epeSubQusSet', 'existingDataArr'
                                , 'maxSelectedQuestionAnswerForOptionalSetList', 'lockQuestionSetList'
                                , 'hasOptionSetList', 'epeSubQus', 'questionSetArr', 'getSubjectiveMarkExistingDataArr'));
    }

    //This function use for subjective single question save
    public function saveSingleSubjective(Request $request) {

        $rules = array(
            'set_id' => 'required',
            'question_id' => 'required',
            'epe_mark_id' => 'required'
        );

        $messages = array(
            'set_id.required' => 'Invalid question set!',
            'question_id.required' => 'Invalid question!',
            'epe_mark_id.required' => 'Invalid EPE mark ID!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $subjectiveMarkArr = new SubjectiveMark;
        $subjectiveMarkArr->epe_mark_id = $request->epe_mark_id;
        $subjectiveMarkArr->set_id = $request->set_id;
        $subjectiveMarkArr->question_id = $request->question_id;
        $subjectiveMarkArr->answer = $request->answer;
        $subjectiveMarkArr->has_options = $request->has_options;

        //Existing data delete
        $deleteSubjectiveMark = SubjectiveMark::where('epe_mark_id', '=', $subjectiveMarkArr->epe_mark_id)
                ->where('set_id', '=', $subjectiveMarkArr->set_id)
                ->where('question_id', '=', $subjectiveMarkArr->question_id)
                ->delete();
        if ($subjectiveMarkArr->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.ANSWER_HAS_BEEN_SAVE_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Save Failed', 'message' => __('label.ANSWER_COULD_NOT_BE_SAVE')), 401);
        }
    }

    //This function use for single question save & lock
    public function saveLockSingleSubjective(Request $request) {

        $rules = array(
            'set_id' => 'required',
            'question_id' => 'required',
            'epe_mark_id' => 'required'
        );

        $messages = array(
            'set_id.required' => 'Invalid question set!',
            'question_id.required' => 'Invalid question!',
            'epe_mark_id.required' => 'Invalid EPE mark ID!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $subjectiveMarkArr = new SubjectiveMark;
        $subjectiveMarkArr->epe_mark_id = $request->epe_mark_id;
        $subjectiveMarkArr->set_id = $request->set_id;
        $subjectiveMarkArr->question_id = $request->question_id;
        $subjectiveMarkArr->answer = $request->answer;
        $subjectiveMarkArr->has_options = $request->has_options;
        $subjectiveMarkArr->lock = 1;

        //Existing data delete
        $deleteSubjectiveMark = SubjectiveMark::where('epe_mark_id', '=', $subjectiveMarkArr->epe_mark_id)
                ->where('set_id', '=', $subjectiveMarkArr->set_id)
                ->where('question_id', '=', $subjectiveMarkArr->question_id)
                ->delete();
        if ($subjectiveMarkArr->save()) {
            return Response::json(array('success' => TRUE, 'data' => __('label.ANSWER_HAS_BEEN_LOCKED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Save & Lock Failed', 'message' => __('label.ANSWER_COULD_NOT_BE_LOCKED')), 401);
        }
    }

    public function submitSubjective(Request $request) {

        $epeId = $request->epe_id;
        $questionSetArray = $request->question_set;
        $hasOptionsArray = $request->has_option;

        $i = 0;
        if (!empty($request->answer)) {
            foreach ($request->answer as $setId => $answerArr) {
                if ($questionSetArray[$setId] == '1') {//1 = means selected question set
                    if ($hasOptionsArray[$setId] == '1') {
                        $optionalQuestionAnswerArr = $request->optional_question_answer;
                        foreach ($answerArr as $questionId => $answer) {
                            if (array_key_exists($questionId, $optionalQuestionAnswerArr[$setId])) {
                                $targetArr[$i]['epe_mark_id'] = $request->epe_mark_id;
                                $targetArr[$i]['set_id'] = $setId;
                                $targetArr[$i]['question_id'] = $questionId;
                                $targetArr[$i]['answer'] = $answer;
                                $targetArr[$i]['has_options'] = 1; //1 means this question has optional
                                $i++;
                            }//If only has options selected question
                        }
                    } else {
                        foreach ($answerArr as $questionId => $answer) {
                            $targetArr[$i]['epe_mark_id'] = $request->epe_mark_id;
                            $targetArr[$i]['set_id'] = $setId;
                            $targetArr[$i]['question_id'] = $questionId;
                            $targetArr[$i]['answer'] = $answer;
                            $targetArr[$i]['has_options'] = 0;
                            $i++;
                        }
                    }
                }//If Question Set Selected
            }//foreach
        }//If not empty answer


        DB::beginTransaction();
        try {

            if (!empty($targetArr)) {
                //Existing data delete
                $deleteSubjectiveMark = SubjectiveMark::where('epe_mark_id', '=', $request->epe_mark_id)->delete();
                SubjectiveMark::insert($targetArr);
            }

            $target = EpeMark::find($request->epe_mark_id);
            $target->subjective_submission_time = date('Y-m-d H:i:s');
            $target->submitted = 2; //Subjective submitted
            $target->save();

            DB::commit();
            // all good
            Session::flash('success', __('label.ANSWER_SCRIPT_SUBMITTED_SUCCESSFULLY'));
            return Redirect::to('/subjectiveComplete?id=' . $epeId);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('success', __('label.ANSWER_SCRIPT_COULD_NOT_BE_SUBMITTED'));
            return Redirect::to('/examSubjective?id=' . $epeId);
        }
    }

    public function deleteSubjectiveQuestionSetAnswers(Request $request) {


        $rules = array(
            'set_id' => 'required',
            'epe_mark_id' => 'required'
        );

        $messages = array(
            'set_id.required' => 'Invalid question set!',
            'epe_mark_id.required' => 'Invalid EPE mark ID!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //Delete subjective marks for this question set
        $deleteSubjectiveMark = SubjectiveMark::where('epe_mark_id', '=', $request->epe_mark_id)->where('set_id', '=', $request->set_id)->delete();

        return Response::json(array('success' => TRUE, 'data' => 'You\'ve lost all your answers for this Question Set'), 200);
    }

    public function deleteSubjectiveIndividualAnswer(Request $request) {


        $rules = array(
            'set_id' => 'required',
            'epe_mark_id' => 'required',
            'question_id' => 'required'
        );

        $messages = array(
            'set_id.required' => 'Invalid question set!',
            'epe_mark_id.required' => 'Invalid EPE mark ID!',
            'question_id.required' => 'Invalid Question!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //Delete subjective marks for this question set
        $deleteSubjectiveMark = SubjectiveMark::where('epe_mark_id', '=', $request->epe_mark_id)->where('set_id', '=', $request->set_id)->where('question_id', '=', $request->question_id)->delete();
        return Response::json(array('success' => TRUE, 'data' => 'You\'ve lost the answer for this question.'), 200);
    }

    public function subjectiveComplete(Request $request) {

        $id = $request->id;
        $epeInfo = EpeMark::join('epe', 'epe_mark.epe_id', '=', 'epe.id')
                        ->select(
                                'epe_mark.*', 'epe.title'
                        )
                        ->where('epe_id', $id)
                        ->where('employee_id', Auth::user()->id)->first();
        return view('epeExam.subjectiveComplete')->with(compact('epeInfo'));
    }

    private function shuffle_assoc($array) {
        // Initialize
        $shuffled_array = array();


        // Get array's keys and shuffle them.
        $shuffled_keys = array_keys($array);
        shuffle($shuffled_keys);


        // Create same array, but in shuffled order.
        foreach ($shuffled_keys AS $shuffled_key) {

            $shuffled_array[$shuffled_key] = $array[$shuffled_key];
        } // foreach
        // Return
        return $shuffled_array;
    }

    public function objectiveTempSave(Request $request) {

        $epe = Epe::find($request->epe_id);
        $targetArr = [];
        if ($epe->questionnaire_format == '1') {
            $targetArr[$request->question_id] = $request->question[$request->question_id];

            Session::put('epeExam.' . $request->epe_id . '.answerArr', $targetArr);
            Session::put('epeExam.' . $request->epe_id . '.question_queue', $request->question_queue);

            // echo '<pre>';print_r(Input::get('m_answer_queue'));exit;
            if (!empty($request->m_answer_queue)) {
                foreach ($request->m_answer_queue as $chunkKey => $content) {
                    Session::put('epeExam.' . $request->epe_id . '.m_answer_queue.' . $chunkKey, $content);
                }
            }
            print_r(Session::get('epeExam.' . $request->epe_id));
        } else {

            if (!empty($request->question_id)) {
                foreach ($request->question_id as $item) {
                    $targetArr[$item] = !empty($request->question[$item]) ? $request->question[$item] : '';
                }//foreach
            }//if

            Session::put('epeExam.' . $request->epe_id . '.answerArr', $targetArr);
            Session::put('epeExam.' . $request->epe_id . '.question_queue', $request->question_queue);

            print_r(Session::get('epeExam.' . $request->epe_id));
        }
    }

//function
}
