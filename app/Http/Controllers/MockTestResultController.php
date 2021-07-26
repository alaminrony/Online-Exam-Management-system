<?php
namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\MockMark;
use App\Epe;
use App\MockTest;
use App\MockMarkDetails;
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

class MockTestResultController extends Controller {

    public function index(Request $request) {

        $subjectId = $request->subject_id;
        $studentList = array();
		
		$loggedInUser = Auth::user()->id;

        //get epe 
		if (in_array(Auth::user()->group_id, array(1, 2, 6))){
			$epeList = DB::table('epe')
						->select('epe.id as epe_id',DB::raw("CONCAT(epe.title,' | ', DATE_FORMAT(epe.exam_date, '%d %M , %Y')) AS title"))						
						->where('epe.type', '1')
						->where('epe.status', '1')
						->orderBy('epe.id', 'ASC')
						->pluck('title', 'epe_id')->toArray();
		}else{
			//For CI
			$epeList = 	DB::table('epe')
						->select('epe.id as epe_id', DB::raw("CONCAT(epe.title,' | ', DATE_FORMAT(epe.exam_date, '%d %M , %Y')) AS title"))
						->where('epe.type', '1')
						->orderBy('epe.id', 'ASC')
						->pluck('title', 'epe_id')->toArray();
		}

        $data['epeList'] = array('' => __('label.SELECT_EPE_OPT')) + $epeList;
        $data['studentList'] = array('' => __('label.SELECT_STUDENT_OPT')) + $studentList;
        // load the view and pass the mocktestresult index
        if (in_array($request->type, array('print', 'pdf'))) {
            $printArr = $this->showResult($request->all());
            extract($printArr);
            
            if ($request->type == 'print') {
                return view('mocktestresult.print_showresult')->with(compact('student', 'targetArr', 'epeInfo'));
            } else if ($request->type == 'pdf') {
                $pdf = App::make('dompdf');
                $pdf->loadHTML(view('mocktestresult.print_showresult')->with(compact('student', 'targetArr'
                        , 'epeInfo'))->render())->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            }
        }

        return view('mocktestresult.index', $data);
    }

    public function studentList(Request $request) {

        $epeId = $request->epe_id;
        //Get part list

        $studentList = MockMark::join('mock_test', 'mock_test.id', '=', 'mock_mark.mock_id')
                ->join('student_details', 'student_details.id', '=', 'mock_mark.student_id')
                ->join('users', 'users.id', '=', 'student_details.user_id')
                ->join('rank', 'rank.id', '=', 'users.rank_id')
                ->join('branch', 'branch.id', '=', 'users.branch_id')
                ->select('student_details.id', DB::raw("CONCAT(rank.short_name, ' ', users.first_name, ' ', users.last_name, branch.short_name,' (', users.iss_no, ') ') AS student"))
                ->where('mock_test.epe_id', $epeId)
                ->orderBy('users.registration_no')
                ->groupBy('mock_mark.student_id')
                ->pluck('student', 'id')->toArray();

        $data['studentList'] = array('' => __('label.SELECT_STUDENT_OPT')) + $studentList;
        return Response::json(array('success' => true, 'students' => $studentList), 200);
    }

    public function showResult(Request $request) {

        //Get current student id from session
        $media = $request->type;
        $studentId = $request->student_id;
        $epeId = $request->epe_id;
        

        // $rules = array(
        // 'part_id' => 'required'
        // );
        // $messages = array(
        // 'part_id.required' => 'Part must be selected!'
        // );
        // $validator = Validator::make($request->all(), $rules, $messages);
        // if ($validator->fails()) {
        // return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        // }

        $epeInfo = Epe::where('epe.id', $epeId)->first();
        $data['epeInfo'] = $epeInfo;

        $targetArr = MockMark::join('mock_test', 'mock_test.id', '=', 'mock_mark.mock_id')
                ->join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->select('mock_mark.*', 'mock_test.title')
                ->where('mock_test.epe_id', $epeId)
                ->where('mock_mark.student_id', $studentId)
                ->get();

        $data['targetArr'] = $targetArr;

        //student information
        $student = User::join('student_details', 'users.id', '=', 'student_details.user_id')
                        ->join('rank', 'rank.id', '=', 'users.rank_id')
                        ->join('branch', 'branch.id', '=', 'users.branch_id')
                        ->select('users.*', 'student_details.id as student_id', 'student_details.user_id'
                               , 'student_details.user_id', 'rank.title as rank_name', 'rank.short_name'
                               ,'branch.name as branch_name', 'users.maximum_tenure', 'users.photo', 'users.iss_no')
                        ->where('student_details.id', $studentId)->first();

        $data['student'] = $student;
        if ($media == 'print') {
            $printArr = compact('student', 'targetArr', 'epeInfo');
            return $printArr;
        }
        $returnHTML = view('mocktestresult/showresult', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function questionAnswerSheet(Request $request) {
        $epeId = $request->epe_id;
        $mockMarkId = $request->mock_mark_id;
        $studentId = $request->student_id;
        $data['mockTestInfo'] = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('mock_test.epe_id', $epeId)
                ->select('subject.title as subject_title','epe.title as epe_title', 'mock_test.*')
                ->first();

        //finding objective question
        $questionArr = MockMarkDetails::join('question', 'question.id', '=', 'mock_mark_details.question_id')
                        ->select('mock_mark_details.*', 'question.question', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id', 'question.match_answer')
                        //->orderBy('question.type_id')
                        ->where('mock_mark_details.mock_mark_id', $mockMarkId)
                        ->orderBy('mock_mark_details.id', 'ASC')->get();
        $matchAnswer = array();
        if (!empty($questionArr->toArray())) {
            foreach ($questionArr->toArray() as $answer) {

                if ($answer['type_id'] == 6) {

                    $matchAnswer[$answer['question_id']] = $answer;
                }
            }
        }

        $data['matchAnswer'] = $matchAnswer;
        $data['questionArr'] = $questionArr;
        // $data['trueFalse'] = $questionType5=array();
        // $data['fillingBlank'] = $questionType3=array();
        $returnHTML = view('mocktestresult/questionanswersheet', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}

?>
