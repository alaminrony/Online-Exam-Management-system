<?php

namespace App\Http\Controllers;

use Validator;
use App\Epe;
use App\EpeMark;
use App\MockTest;
use App\MockMark;
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
use Carbon\Carbon;
use DateTime;
use Response;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class IsspStudentActivityController extends Controller {

    public function myEpe(Request $request) {
        //Get Current date time
        $nowDateObj = Carbon::now();
        $currentDate = $nowDateObj->format('Y-m-d');
        $currentTime = $nowDateObj->format('H:i:s');

        $currentStudentBranchId = Auth::user()->branch_id;

        //Get assign EPE Information
        $activeRegularEpeArr = Epe::leftJoin(DB::raw('(select epe_id, employee_id, submitted FROM epe_mark where submitted IN (1,2) and employee_id = ' . Auth::user()->id . ' ) as submitted_epe'), function($join) {
                    $join->on('epe.id', '=', 'submitted_epe.epe_id');
                })
                ->leftJoin('subject', 'subject.id', '=', 'epe.subject_id')
                ->join('exam_to_student', 'exam_to_student.exam_id', '=', 'epe.id')
                ->select('subject.title as subject_name', 'subject.code as subject_code', 'epe.*', 'submitted_epe.submitted')
                ->where('epe.type', '1')
                ->where('epe.exam_date', '=', DB::raw("'" . $currentDate . "'"))
                ->where('epe.start_time', '<=', DB::raw("'" . $currentTime . "'"))
                ->where('epe.end_time', '>=', DB::raw("'" . $currentTime . "'"))
                ->where('epe.status', 1)
                ->where('exam_to_student.employee_id', Auth::user()->id)
                ->get();

        $regularEpeMark = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                        ->where('epe.type', 1)
                        ->where('epe_mark.employee_id', Auth::user()->id)
                        ->where('epe_mark.submitted', '1')
                        ->pluck('epe_mark.epe_id')->toArray();

        if (count($activeRegularEpeArr) > 0) {
            foreach ($activeRegularEpeArr as $key => $item) {

                if (in_array($item->id, $regularEpeMark)) {
                    unset($activeRegularEpeArr[$key]);
                }
            }
        }

        $data['epeArr'] = $activeRegularEpeArr;
//         Helper::dump($activeRegularEpeArr);
        // load the view and pass the TAE index
        return view('isspstudentactivity.epe', $data);
    }

    public function myEpeExam(Request $request) {
        $epeId = $request->id;
        $epeArr = Epe::find($epeId);

        if ($epeArr->type == '1') {
            $epeObjArr = Epe::select('epe.*', 'has_obj_question.set_obj_question', 'epe_mark.submitted')
                            ->leftJoin(DB::raw('(select epe_id, count(id) as set_obj_question FROM epe_to_question group by epe_id ) as has_obj_question'), function($join) {
                                $join->on('epe.id', '=', 'has_obj_question.epe_id');
                            })->leftJoin('epe_mark', function($join) use ($epeId) {
                                $join->on('epe.id', '=', 'epe_mark.epe_id');
                                $join->where('epe_mark.epe_id', '=', $epeId);
                                $join->where('epe_mark.employee_id', '=', Auth::user()->id);
                            })
                            ->where('epe.id', $epeId)
                            ->with(array('Subject'))->first();
        } else if (in_array($epeArr->type, array('2', '3'))) {

            $epeObjArr = Epe::select('epe.*', 'has_obj_question.set_obj_question', 'epe_mark.submitted')
                            ->leftJoin(DB::raw('(select epe_id, count(id) as set_obj_question FROM epe_to_question group by epe_id ) as has_obj_question'), function($join) {
                                $join->on('epe.id', '=', 'has_obj_question.epe_id');
                            })->leftJoin('epe_mark', function($join) use ($epeId) {
                                $join->on('epe.id', '=', 'epe_mark.epe_id');
                                $join->where('epe_mark.epe_id', '=', $epeId);
                                $join->where('epe_mark.employee_id', '=', Auth::user()->id);
                            })
                            ->where('epe.id', $epeId)
                            ->with(array('subject', 'epeDetail', 'epeDetail.course', 'epeDetail.part'
                                , 'epeDetail.phase', 'epeDetail.branch'))->first();
        }

        $examEndTime = $epeObjArr->exam_date . ' ' . $epeObjArr->end_time;
        $minusExamEndTime = date("Y-m-d H:i:s", strtotime("-" . $epeObjArr->obj_duration_hours . " hour -" . $epeObjArr->obj_duration_minutes . " minutes", strtotime($examEndTime)));
        $presentDatetime = date('Y-m-d H:i:s');

        $checkEmployeeAttendend = EpeMark::where(['epe_id' => $epeId, 'employee_id' => Auth::user()->id])->first();

        $data['epeExamInfoArr'] = $epeObjArr;
        $data['minusExamEndTime'] = $minusExamEndTime;
        $data['presentDatetime'] = $presentDatetime;
        $data['checkEmployeeAttendend'] = $checkEmployeeAttendend;

        $agent = new Agent();
        $browser = $agent->browser();
        $version = $agent->version($browser);
        $message = __('label.SORRY_YOUR_BROWSER_IS_NOT_COMPATIBLE_WITH_REQUIREMENTS');
        $suppBrowser = ['1'=>['browser'=>'Google Chrome','version'=>'63.*'],'2'=>['browser'=>'Mozila Firefox','version'=>'60.*']];
        
//        $returnHTML = view('isspstudentactivity/epeSummary', $data)->render();
        
          if (($browser == 'Chrome' && $version >= 63) || ($browser == 'Firefox' && $version >= 60)) {
             $returnHTML = view('isspstudentactivity/epeSummary', $data)->render();
         }
         else{
              $returnHTML = view('isspstudentactivity/versionErrorModal',compact('message','suppBrowser'))->render(); 
         }

        
        
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function myMockTest(Request $request) {
//Get Current date time

        $nowDateObj = Carbon::now();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        $currentStudentBranchId = Auth::user()->branch_id;
//Get all phase list for this part
//Get assign EPE Information
        $epeArr = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->leftJoin(DB::raw('(select epe_id, count(id) as total_mock FROM mock_test group by epe_id ) as temp_mock'), function($join) {
                    $join->on('epe.id', '=', 'temp_mock.epe_id');
                })
                ->leftJoin('subject', 'subject.id', '=', 'epe.subject_id')
                ->select(
                        'mock_test.title as mock_title', 'mock_test.start_at', 'mock_test.end_at', 'subject.title as subject_name', 'subject.code as subject_code', 'epe.*', DB::raw("IFNULL(temp_mock.total_mock,0) as total_mock")
                )
                ->where('mock_test.start_at', '<=', DB::raw("'" . $currentDateTime . "'"))
                ->where('mock_test.end_at', '>=', DB::raw("'" . $currentDateTime . "'"))
                ->where('mock_test.status', 1)
//->groupby('mock_test.epe_id')have an error solve this problem
                ->get();

        $data['epeArr'] = $epeArr;
// load the view and pass the TAE index
        return view('isspstudentactivity.mockTest', $data);
    }

    public function myMockList(Request $request) {
//Get Current date time
        $nowDateObj = Carbon::now();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        $epeId = $request->epe_id;

//Get EPE Information
        $epe = Epe::with(array('Subject'))->find($epeId);
        $data['epe'] = $epe;

//Get Total Completed Mock Test
        $totalConpletedMockCount = MockMark::where('employee_id', Auth::user()->id)->groupBy('mock_id')->count();
        $data['completedMock'] = $totalConpletedMockCount;

//Get mock information
        $mockObjArr = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->leftJoin(DB::raw('(select mock_mark.mock_id, mock_mark.pass, mock_mark.attempt FROM mock_mark where employee_id = ' . Auth::user()->id . ' group by mock_mark.mock_id ) as temp_mark'), function($join) {
                    $join->on('mock_test.id', '=', 'temp_mark.mock_id');
                })
                ->select(
                        'mock_test.*', 'epe.title as epe_title', 'epe.subject_id', 'epe.no_of_mock', 'temp_mark.pass', DB::raw("IFNULL(temp_mark.attempt,0) as total_attempt")
                )
                ->where('mock_test.epe_id', $epeId)
                ->where('mock_test.status', 1)
                ->where('mock_test.start_at', '<=', DB::raw("'" . $currentDateTime . "'"))
                ->where('mock_test.end_at', '>=', DB::raw("'" . $currentDateTime . "'"))
                ->get();
        $data['mockListArr'] = $mockObjArr;

        $returnHTML = view('isspstudentactivity/my_mock_list', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function mockPlay(Request $request) {
        $mockId = $request->mock_id;
        $mockObjArr = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->leftJoin('subject', 'subject.id', '=', 'epe.subject_id')
                ->leftJoin(DB::raw('(select mock_mark.mock_id, mock_mark.pass, mock_mark.attempt FROM mock_mark where employee_id = ' . Auth::user()->id . ' group by mock_mark.mock_id ) as temp_mark'), function($join) {
                    $join->on('mock_test.id', '=', 'temp_mark.mock_id');
                })
                ->leftJoin(DB::raw('(select mock_id, count(id) as total_question FROM mock_to_question group by mock_id ) as has_question'), function($join) {
                    $join->on('mock_test.id', '=', 'has_question.mock_id');
                })
                ->select(
                        'mock_test.*', 'epe.title as epe_title', 'epe.subject_id', 'epe.no_of_mock', 'has_question.total_question', 'temp_mark.pass', DB::raw("IFNULL(temp_mark.attempt,0) as total_attempt"), 'subject.title as subject_title'
                )
                ->where('mock_test.id', $mockId)
                ->first();

        $data['mockExamInfoArr'] = $mockObjArr;

        $epeInfo = Epe::where('id', $mockObjArr->epe_id)
                ->first();

        $data['epeInfo'] = $epeInfo;
        $returnHTML = view('isspstudentactivity/show_play_box', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}
?>
