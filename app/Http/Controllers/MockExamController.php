<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\MockTest;
use App\Epe;
use App\MockToQuestion;
use App\MockMark;
use App\Question;
use App\MockMarkDetails;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Image;
use Illuminate\Http\Request;

class MockExamController extends Controller {

    public function exam(Request $request) {
        $mock = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->leftJoin('subject', 'subject.id', '=', 'epe.subject_id')
                ->select(
                        'mock_test.*', 'epe.title as epe_title', 'epe.subject_id', 'epe.no_of_mock', 'subject.title as subject_title'
                )
                ->where('mock_test.id', $request->id)
                ->first();



        if (empty($mock)) {
            $message['short'] = 'Invalid Mock Test';
            $message['long'] = 'The Mock Test you are trying to access doesn\'t exists!';
            return view('mockExam.examUnavailable')->with(compact('message'));
        }

        if ((strtotime(date('Y-m-d H:i:s')) > strtotime($mock->end_at)) || (strtotime(date('Y-m-d H:i:s')) < strtotime($mock->start_at))) {
            $message['short'] = 'Exam Not Available';
            $message['long'] = 'Exam is not available at this time!';
            return view('mockExam.examUnavailable')->with(compact('message'));
        }

        $epeInfo = Epe::where('id', $mock->epe_id)
                ->first();


        if (empty($epeInfo)) {
            $message['short'] = 'Mark Not Set';
            $message['long'] = 'Mark has not set for this Mock Test yet!';
            return view('mockExam.examUnavailable')->with(compact('message'));
        }

        //get Question Set for this Mock Test
        $questionIdArr = MockToQuestion::where('mock_id', $mock->id)->pluck('question_id')->toArray();


        if (empty($questionIdArr)) {
            $message['short'] = 'No Question Found';
            $message['long'] = 'No question found for this Mock Test!';
            return view('mockExam.examUnavailable')->with(compact('message'));
        }

        $examDuration = ($mock->duration_hours * 60) + $mock->duration_minutes;

        //Look for if already Student is running this test

        $target = MockMark::where('mock_id', $mock->id)->where('employee_id', Auth::user()->id)->first();

        //$maxAttemp = MockMark::where('employee_id', Auth::user()->id)->max('attempt');

        if (empty($target)) {

            //Student just begun this test; insert a new record for him
            $target = new MockMark;
            $target->mock_id = $request->id;
            $target->employee_id = Auth::user()->id;
            $target->exam_date = date('Y-m-d');
            $target->start_time = date('H:i:s');
            $endTime = date("H:i:s", strtotime('+' . $examDuration . ' minutes', strtotime($target->start_time)));
            $target->end_time = $endTime;
            $target->attempt = 1;
            $target->save();
        } else {

            if ($target->pass == '1') {
                $message['short'] = 'Mock Test Already Passed';
                $message['long'] = 'You have already passed in this mock test!';
                return view('mockExam.examUnavailable')->with(compact('message'));
            }

//            if (strtotime(date('Y-m-d H:i:s')) > strtotime($target->exam_date . ' ' . $target->end_time)) {
//                $message['short'] = 'Exam Time Over';
//                $message['long'] = 'Exam Time is over!';
//                return view('mockExam.examUnavailable')->with(compact('message'));
//            }
            //Some Fields Update for Mock Mark Table
            //$updateExistingMockMark = MockMark::where('mock_id', $mock->id)->where('employee_id', Auth::user()->id)->first();
            //Entry will be only updated when 
            if ((strtotime(date('Y-m-d H:i:s')) > strtotime($target->exam_date . ' ' . $target->end_time)) || ($target->submitted == 1)) {
                $target->exam_date = date('Y-m-d');
                $target->start_time = date('H:i:s');

                $endTime = date("H:i:s", strtotime('+' . $examDuration . ' minutes', strtotime($target->start_time)));

                $target->end_time = $endTime;
                $target->attempt = $target->attempt + 1;
                $target->submitted = 0;
                $target->save();
                //Update attempt field for Mock Mark table
            }
        }

        $questionArr = Question::where('status', '1')->whereIn('id', $questionIdArr)->orderBy('id', 'asc')->get()->toArray();

        $matchQuestionPre = MockToQuestion::join('question', 'question.id', '=', 'mock_to_question.question_id')
                        ->where('question.type_id', 6)->where('mock_id', $mock->id)
                        ->orderBy(DB::raw('RAND()'))->get()->toArray();


        //if there is more than 8 question split the question bank into two different chunk
        if (count($matchQuestionPre) > 8) {
            $matchQuestion[0] = array_slice($matchQuestionPre, 0, count($matchQuestionPre) / 2);
            $matchQuestion[1] = array_slice($matchQuestionPre, count($matchQuestionPre) / 2);
        } else {
            //keep it in one chunk
            $matchQuestion[0] = $matchQuestionPre;
        }
        $matchAnswer = [];
        $newIndex = count($questionArr) + 1;
        if (!empty($matchQuestion)) {
            foreach ($matchQuestion as $chunkKey => $chunkItem) {

                if (!empty($chunkItem)) {
                    foreach ($chunkItem as $item) {
                        $matchAnswer[$chunkKey][$item['id']] = $item['match_answer'];
                    }//foreach

                    $matchAnswer[$chunkKey] = $this->shuffle_assoc($matchAnswer[$chunkKey]);
                    $matchAnswer[$chunkKey] = array('0' => __('label.SELECT_ANSWER_OPT')) + $matchAnswer[$chunkKey];
                }//if

                $questionArr[$newIndex]['type_id'] = 6;
                $questionArr[$newIndex]['chunk_key'] = $chunkKey;
                $questionArr[$newIndex]['match_item'] = $chunkItem;
                $newIndex++;
            }
        }

        shuffle($questionArr);

        //Update Total Mark and Passing Mark
//        $passMark = ceil(($epeInfo->pass_mark * count($questionIdArr)) / $epeInfo->total_mark);
        $passMark = 50;

        MockMark::where('mock_id', $mock->id)->where('employee_id', Auth::user()->id)
                ->update(array('total_mark' => $epeInfo->total_mark, 'pass_mark' => $passMark));

        $target = MockMark::where('mock_id', $mock->id)->where('employee_id', Auth::user()->id)->first();

        return view('mockExam.exam')->with(compact('questionArr', 'mock', 'target', 'matchQuestion', 'matchAnswer'));
    }

    public function submitExam(Request $request) {

        $mock = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->leftJoin('subject', 'subject.id', '=', 'epe.subject_id')
                ->select(
                        'mock_test.*', 'epe.title as epe_title', 'epe.subject_id', 'epe.no_of_mock', 'subject.title as subject_title'
                )
                ->where('mock_test.id', $request->mock_id)
                ->first();

        $epeInfo = Epe::where('id', $mock->epe_id)
                ->first();
        $examMark = $epeInfo->total_mark;
        $passMark = 50;

        $passed = 2; //Assumed that; student has failed initially

        $qidArr = $request->question_id;

        $questionArr = array();
        if (!empty($qidArr)) {
            $questionArr = Question::whereIn('id', $qidArr)->select('id', 'type_id', 'mcq_answer', 'ftb_answer', 'tf_answer', 'match_answer')->get();
        }

        $examData = array();
        $corretArr = array();
        if (!$questionArr->isEmpty()) {
            foreach ($questionArr as $question) {

                $examData[$question->id]['mock_mark_id'] = $request->mock_mark_id;
                $examData[$question->id]['question_id'] = $question->id;

                if ($question->type_id == '1') {
                    $corretArr[$question->id] = $question->mcq_answer;
                } else if ($question->type_id == '3') {
                    $corretArr[$question->id] = $question->ftb_answer;
                } else if ($question->type_id == '5') {
                    $corretArr[$question->id] = $question->tf_answer;
                } else if ($question->type_id == '6') {
                    $corretArr[$question->id] = $question->id;
                }

                $examData[$question->id]['correct_answer'] = $corretArr[$question->id];

                //Follwing data will be overrided later
                $examData[$question->id]['submitted_answer'] = '';
                $examData[$question->id]['correct'] = NULL;
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

                if ($val == $corretArr[$qid]) {
                    $examData[$qid]['correct'] = 1;
                    $totalMark++;
                }
            }
        }

//         $convertedMark = ($percentage * $examMark) / 100;
//        $percentage = ($totalMark * 100) / count($qidArr); //General percentage; converted with 100
//       
        
        $convertedMark = ($examMark /count($qidArr)) * $totalMark;
        $percentage = ($convertedMark * 100) / $examMark; //General percentage; converted with 100
        
        if ($percentage >= $passMark) {
            $passed = 1; //Override student passStatus
        }

        //Update mock mark table following the fields
        $target = MockMark::find($request->mock_mark_id);
        $target->submission_time = date('Y-m-d H:i:s');
        $target->converted_mark = $convertedMark;
        $target->no_of_question = count($qidArr);
        $target->no_correct_answer = $totalMark; //Alias of $noOfCorrectAnswer
        $target->pass = $passed;
        $target->submitted = 1;

        DB::beginTransaction();
        try {
            $target->save();
            //Delete exists mork mark details data
            $mockMarkDetailsAffectedRows = MockMarkDetails::where('mock_mark_id', '=', $request->mock_mark_id)->delete();

            //Insert data into details table
            MockMarkDetails::insert($examData);

            DB::commit();
            Session::flash('success', __('label.ANSWER_SCRIPT_SUBMITTED_SUCCESSFULLY'));
            return Redirect::to('mockExam/examresult?mock_id=' . $request->mock_id);
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('success', 'Answer script could not be submitted');
            return Redirect::to('mockExam?id=' . $request->mock_id);
        }
    }

    public function submitExamResult(Request $request) {
        $mockId = $request->mock_id;
        $data['mockInfo'] = MockMark::join('mock_test', 'mock_mark.mock_id', '=', 'mock_test.id')
                        ->select(
                                'mock_mark.*', 'mock_test.obj_no_question', 'mock_test.duration_hours', 'mock_test.duration_minutes'
                        )
                        ->where('mock_id', $mockId)
                        ->where('employee_id', Auth::user()->id)->first();

        return view('mockExam.examresult', $data);
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

}
