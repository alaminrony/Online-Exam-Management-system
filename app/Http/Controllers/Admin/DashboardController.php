<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subject;
use App\Question;
use App\Branch;
use App\Cluster;
use App\Epe;
use App\SubjectToDs;
use App\EpeMark;
use App\ExamToStudent;
use App\Message;
use Debugbar;
use DateTime;
use Helper;
use DB;

class DashboardController extends Controller {

    public function __construct() {
        //$this->middleware('auth');
    }

    public function index() {


        $today = date('Y-m-d');
        $currentDateTime = date('Y-m-d H:i:s');
        $weekAgo = date('Y-m-d', strtotime('-1 week'));
        $fifteenDaysLater = date('Y-m-d', strtotime('+15 days'));
        $sixMonthsAgo = date('Y-m-d', strtotime('-5 months'));

        $beginDay = new DateTime($today);
        $endDay = new DateTime($fifteenDaysLater);
        $beginMonthDay = new DateTime($sixMonthsAgo);
        $endMonthDay = new DateTime($today);
        $monthFromToday = [];

        $examScheduleList = [];
        $monthDayFromToday = [];
        $sixMonthsExamScheduleList = [];

        $enrolledStudentList = [];
        $attendedStudentList = [];
        $absentStudentList = [];
        $sixMonthsEnrolledStudentList = [];
        $sixMonthsAttendedStudentList = [];
        $sixMonthsAbsentStudentList = [];

        $lastSixExamResultList = [];

        $scrollmessageList = Message::leftJoin('message_scope', 'message_scope.message_id', '=', 'message.id')
                ->where('message.from_date', '<=', DB::raw("'" . $today . "'"))
                ->where('message.to_date', '>=', DB::raw("'" . $today . "'"))
                ->where('message.status', '1')
                ->where('message_scope.scope_id', 1)
                ->orderBy('message.from_date', 'DESC')
                ->get();
        if (in_array(Auth::user()->group_id, [1, 4])) {
            //dashboard for admin
            //total subject list
            $subjectList = Subject::pluck('id')->toArray();

            //total question list
            $questionList = Question::pluck('id')->toArray();

            //total branch list
            $branchList = Branch::pluck('id')->toArray();

            //total cluster list
            $clusterList = Cluster::pluck('id')->toArray();

            //todays exam
            $todaysExam = Epe::where('exam_date', $today)->where('status', '1')->pluck('id')->toArray();

            //exam schedule
            $totalExamList = Epe::whereBetween('exam_date', [$today, $fifteenDaysLater])
                            ->where('epe.status', '1')
                            ->select(DB::raw("COUNT(epe.id) as total_exam"), 'exam_date')
                            ->groupBy('exam_date')
                            ->pluck('total_exam', 'exam_date')->toArray();


            for ($i = $beginDay; $i < $endDay; $i->modify('+1 day')) {
                $day = $i->format("Y-m-d");
                $examScheduleList[$day] = !empty($totalExamList[$day]) ? $totalExamList[$day] : 0;

                $monthFromToday[] = $day;
            }
            //end :: exam schedule
            //6 months examinee stat
            $totalEnrolledStudentInSixMonths = ExamToStudent::join('epe', 'epe.id', '=', 'exam_to_student.exam_id')
                            ->whereBetween('epe.exam_date', [$sixMonthsAgo, $today])
                            ->where('epe.status', '1')
                            ->select(DB::raw("COUNT(exam_to_student.employee_id) as total_student"), 'epe.exam_date')
                            ->groupBy('epe.exam_date')
                            ->pluck('total_student', 'epe.exam_date')->toArray();

            $totalAttendedStudentInSixMonths = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                            ->whereBetween('epe.exam_date', [$sixMonthsAgo, $today])
                            ->where('epe.status', '1')
                            ->select(DB::raw("COUNT(epe_mark.employee_id) as total_student"), 'epe.exam_date')
                            ->groupBy('epe.exam_date')
                            ->pluck('total_student', 'epe.exam_date')->toArray();

            $c = 0;
            for ($j = $beginMonthDay; $j <= $endMonthDay; $j->modify('+1 day')) {
                $day = $j->format("Y-m-d");
                $month = $j->format("M y");
                $enrolledStudentList[$day] = !empty($totalEnrolledStudentInSixMonths[$day]) ? $totalEnrolledStudentInSixMonths[$day] : 0;
                $attendedStudentList[$day] = !empty($totalAttendedStudentInSixMonths[$day]) ? $totalAttendedStudentInSixMonths[$day] : 0;
                $absentStudentList[$day] = $enrolledStudentList[$day] - $attendedStudentList[$day];

                $monthDayFromToday[$month] = $month;
            }

            if (!empty($enrolledStudentList)) {
                foreach ($enrolledStudentList as $date => $c) {
                    $month = date("M y", strtotime($date));

                    $sixMonthsEnrolledStudentList[$month] = !empty($sixMonthsEnrolledStudentList[$month]) ? $sixMonthsEnrolledStudentList[$month] : 0;
                    $sixMonthsEnrolledStudentList[$month] += $c;
                }
            }
            if (!empty($attendedStudentList)) {
                foreach ($attendedStudentList as $date => $c) {
                    $month = date("M y", strtotime($date));

                    $sixMonthsAttendedStudentList[$month] = !empty($sixMonthsAttendedStudentList[$month]) ? $sixMonthsAttendedStudentList[$month] : 0;
                    $sixMonthsAttendedStudentList[$month] += $c;
                }
            }
            if (!empty($absentStudentList)) {
                foreach ($absentStudentList as $date => $c) {
                    $month = date("M y", strtotime($date));

                    $sixMonthsAbsentStudentList[$month] = !empty($sixMonthsAbsentStudentList[$month]) ? $sixMonthsAbsentStudentList[$month] : 0;
                    $sixMonthsAbsentStudentList[$month] += $c;
                }
            }
            //end :: 6 months examinee stat
        } else if (Auth::user()->group_id == '2') {
            //dashboard for examiner
            //upcoming week assessment
            $examToExaminer = SubjectToDs::leftJoin('epe', 'epe.subject_id', '=', 'subject_to_ds.subject_id')
                            ->leftJoin('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                            ->where('subject_to_ds.user_id', Auth::user()->id)
                            ->whereBetween('epe.exam_date', [$weekAgo, $today])
                            ->where('epe.status', '1')
                            ->pluck('epe_mark.ds_status', 'epe.id')->toArray();

            $upcomingWeekAssessmentList = [];
            if (!empty($examToExaminer)) {
                foreach ($examToExaminer as $examId => $dsStatus) {
                    if ($dsStatus == '0') {
                        $upcomingWeekAssessmentList[$examId] = $examId;
                    }
                }
            }
            //end :: upcoming week assessment
            //exam schedule
            $totalExamList = SubjectToDs::leftJoin('epe', 'epe.subject_id', '=', 'subject_to_ds.subject_id')
                            ->where('subject_to_ds.user_id', Auth::user()->id)
                            ->whereBetween('epe.exam_date', [$today, $fifteenDaysLater])
                            ->where('epe.status', '1')
                            ->select(DB::raw("COUNT(epe.id) as total_exam"), 'epe.exam_date')
                            ->groupBy('epe.exam_date')
                            ->pluck('total_exam', 'epe.exam_date')->toArray();


            for ($i = $beginDay; $i < $endDay; $i->modify('+1 day')) {
                $day = $i->format("Y-m-d");
                $examScheduleList[$day] = !empty($totalExamList[$day]) ? $totalExamList[$day] : 0;

                $monthFromToday[] = $day;
            }
            //end :: exam schedule
            //6 months exam schedule
            $totalExamListInSixMonths = SubjectToDs::leftJoin('epe', 'epe.subject_id', '=', 'subject_to_ds.subject_id')
                            ->where('subject_to_ds.user_id', Auth::user()->id)
                            ->whereBetween('epe.exam_date', [$sixMonthsAgo, $today])
                            ->where('epe.status', '1')
                            ->select(DB::raw("COUNT(epe.id) as total_exam"), 'epe.exam_date')
                            ->groupBy('epe.exam_date')
                            ->pluck('total_exam', 'epe.exam_date')->toArray();

            $c = 0;
            for ($j = $beginMonthDay; $j <= $endMonthDay; $j->modify('+1 day')) {
                $day = $j->format("Y-m-d");
                $month = $j->format("M y");
                $examSchedule[$day] = !empty($totalExamListInSixMonths[$day]) ? $totalExamListInSixMonths[$day] : 0;

                $monthDayFromToday[$month] = $month;
            }

            if (!empty($examSchedule)) {
                foreach ($examSchedule as $date => $c) {
                    $month = date("M y", strtotime($date));

                    $sixMonthsExamScheduleList[$month] = !empty($sixMonthsExamScheduleList[$month]) ? $sixMonthsExamScheduleList[$month] : 0;
                    $sixMonthsExamScheduleList[$month] += $c;
                }
            }
            //end :: 6 months exam schedule
//            echo '<pre>';
//            print_r($examScheduleList);
//            exit;
        } else if (Auth::user()->group_id == '3') {
            //dashboard for employee
            //todays exam

            $alreadyAttendExam = EpeMark::select('epe_id')
                    ->where(['epe_mark.exam_date' => $today, 'employee_id' => Auth::user()->id])
                    ->pluck('epe_id')
                    ->toArray();


            $todaysExam = ExamToStudent::join('epe', 'epe.id', '=', 'exam_to_student.exam_id')
                            ->where('exam_to_student.employee_id', Auth::user()->id)
                            ->where('epe.exam_date', $today)
                            ->whereNotIn('epe.id', $alreadyAttendExam)
                            ->where('epe.status', '1')
                            ->pluck('epe.id')->toArray();

            //todays mock test
            $todaysMockTest = ExamToStudent::join('epe', 'epe.id', '=', 'exam_to_student.exam_id')
                            ->join('mock_test', 'mock_test.epe_id', '=', 'epe.id')
                            ->where('exam_to_student.employee_id', Auth::user()->id)
                            ->where('mock_test.start_at', '<=', DB::raw("'" . $currentDateTime . "'"))
                            ->where('mock_test.end_at', '>=', DB::raw("'" . $currentDateTime . "'"))
                            ->where('epe.status', '1')
                            ->pluck('epe.id')->toArray();

            

            //exam schedule
            $totalExamList = ExamToStudent::join('epe', 'epe.id', '=', 'exam_to_student.exam_id')
                            ->where('exam_to_student.employee_id', Auth::user()->id)
                            ->whereBetween('epe.exam_date', [$today, $fifteenDaysLater])
                            ->where('epe.status', '1')
                            ->select(DB::raw("COUNT(epe.id) as total_exam"), 'epe.exam_date')
                            ->groupBy('epe.exam_date')
                            ->pluck('total_exam', 'epe.exam_date')->toArray();


            for ($i = $beginDay; $i < $endDay; $i->modify('+1 day')) {
                $day = $i->format("Y-m-d");
                $examScheduleList[$day] = !empty($totalExamList[$day]) ? $totalExamList[$day] : 0;

                $monthFromToday[] = $day;
            }
            //end :: exam schedule
            //last 6 exam result
            $targetArr = EpeMark::join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                            ->join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                            ->select('epe_mark.employee_id', 'epe.exam_date'
                                    , 'epe_mark_details.final_mark', 'epe_mark.id as epe_mark_id'
                                    , DB::raw("SUM(epe_mark_details.final_mark) as final_mark")
                                    , 'epe.title as exam_name', 'epe_mark.subjective_earned_mark'
                                    , 'epe_mark.objective_earned_mark', 'epe_mark.total_mark', 'epe.obj_no_question')
                            ->groupBy('epe_mark.id')
                            ->where('epe_mark.employee_id', Auth::user()->id)
                            ->where('epe.result_publish', '<=', $currentDateTime)
                            ->where('epe_mark.ds_status', '=', '2')->get();

            $objectiveQuestion = EpeMark::join('epe_mark_details', 'epe_mark_details.epe_mark_id', '=', 'epe_mark.id')
                    ->join('question', 'question.id', '=', 'epe_mark_details.question_id')
                    ->join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                    ->join('epe_to_question', 'epe_to_question.epe_id', '=', 'epe_mark.epe_id')
                    ->select('epe_to_question.mark', 'epe_mark_details.question_id'
                            , 'epe_mark_details.epe_mark_id')
                    ->where('question.type_id', '!=', 3)
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

            $finalArr = [];
            if ($targetArr->isNotEmpty()) {
                foreach ($targetArr as $result) {
                    $finalArr[$result->epe_mark_id]['exam_name'] = $result->exam_name;
                    if (!empty($result->subjective_earned_mark)) {
                        $finalArr[$result->epe_mark_id]['achieved_mark_per'] = ($result->final_mark * 100) / $result->total_mark;
                    } else {
                        $finalArr[$result->epe_mark_id]['achieved_mark_per'] = ($result->final_mark * 100) / $objTotalMks[$result->epe_mark_id];
                    }
                }
            }

            if (!empty($finalArr)) {
                $lastSixExamResultList = count($finalArr) > 6 ? array_slice($finalArr, -6) : $finalArr;
            }
            //end :: last 6 exam result
//            echo '<pre>';
//            print_r($lastSixExamResultList);
//            exit;
        } else {
            //dashboard for supvisor
        }




        if (Auth::user()->group_id == '1') {
            return view('admin.adminDashboard')->with(compact('subjectList'
                                    , 'questionList', 'branchList', 'clusterList'
                                    , 'todaysExam', 'monthFromToday'
                                    , 'examScheduleList', 'monthDayFromToday'
                                    , 'sixMonthsEnrolledStudentList', 'sixMonthsAttendedStudentList'
                                    , 'sixMonthsAbsentStudentList', 'scrollmessageList'));
        } else if (Auth::user()->group_id == '2') {
            return view('admin.examinerDashboard')->with(compact('upcomingWeekAssessmentList'
                                    , 'monthFromToday', 'examScheduleList'
                                    , 'monthDayFromToday', 'sixMonthsExamScheduleList', 'scrollmessageList'));
        } else if (Auth::user()->group_id == '3') {
            return view('admin.employeeDashboard')->with(compact('todaysExam', 'todaysMockTest'
                                    , 'monthFromToday', 'examScheduleList', 'lastSixExamResultList', 'scrollmessageList'));
        } else if (Auth::user()->group_id == '4') {
            return view('admin.supervisorDashboard')->with(compact('subjectList'
                                    , 'questionList', 'branchList', 'clusterList'
                                    , 'todaysExam', 'monthFromToday'
                                    , 'examScheduleList', 'monthDayFromToday'
                                    , 'sixMonthsEnrolledStudentList', 'sixMonthsAttendedStudentList'
                                    , 'sixMonthsAbsentStudentList', 'scrollmessageList'));
        } else {
            return view('admin.dashboard');
        }
    }

    public function alluser() {
        $users = User::count();
        //dd($users);
        return view('admin.dashboard')->with(['users' => $users]);
    }

    //get todays exam details
    public function getTodaysExamDetails() {
        $todaysExamDetails = Epe::join('subject', 'subject.id', '=', 'epe.subject_id')
                        ->select('epe.id', 'epe.subject_id', 'subject.title as subject'
                                , 'epe.type', 'epe.questionnaire_format', 'epe.total_mark'
                                , 'epe.title', 'epe.obj_duration_hours', 'epe.obj_duration_minutes'
                                , 'epe.submission_deadline', 'epe.result_publish')
                        ->where('exam_date', date('Y-m-d'))->get();

        $view = view('admin.showTodaysExamDetails', compact('todaysExamDetails'))->render();
        return response()->json(['html' => $view]);
    }

}
