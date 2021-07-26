<?php
namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\PhaseToSubject;
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
use Illuminate\Http\Request;

class EpeResultController extends Controller {

    public function index() {

        $courseId = Input::get('course_id');
        $partId = Input::get('part_id');
        $subjectId = Input::get('subject_id');

        //Get course list
        if (Auth::user()->group_id == 3) {
            $courseList = Course::join('course_to_ci', 'course.id', '=', 'course_to_ci.course_id')
                    ->select('course.id as id', 'course.title as title')
                    ->where('course_to_ci.user_id', '=', Auth::user()->id)
                    ->where('course.status', 'active')
                    ->pluck('title', 'id');
        } else if (Auth::user()->group_id == 4) {
            $courseList = Course::join('subject_to_ds', 'course.id', '=', 'subject_to_ds.course_id')
                    ->select('course.id as id', 'course.title as title')
                    ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                    ->where('course.status', 'active')
                    ->pluck('title', 'id');
        } else {
            $courseList = Course::where('status', 'active')->orderBy('title')->pluck('title', 'id');
        }
        $data['courseList'] = array('' => __('label.SELECT_COURSE_OPT')) + $courseList;
        $data['partList'] = array('' => __('label.SELECT_PART_OPT'));
        $data['subjectList'] = array('' => __('label.SELECT_SUBJECT_OPT'));
        $type = Input::get('type');
        if (in_array($type, array('print', 'pdf'))) {
            $targetArr = $this->showMarksheet('print');
            extract($targetArr);

            if ($type == 'print') {
                return view('eperesult.print_show_marksheet')->with(compact('eperesultArr', 'eperesultFinalArr', 'courseRelatedInfo', 'type'
                                        , 'attendeeRecord', 'result_showable'));
            } else if ($type == 'pdf') {
                $pdf = App::make('dompdf');
                $pdf->loadHTML(view('eperesult.print_show_marksheet')->with(compact('eperesultArr', 'courseRelatedInfo'
                                                , 'type', 'attendeeRecord', 'eperesultFinalArr', 'result_showable'))
                                ->render())
                        ->setPaper('a4', 'landscape')->setWarnings(false);

                return $pdf->stream();
            }
        }
        // load the view and pass the TAE index
        return view('eperesult.index', $data);
    }

    //This function use for Relate Part with Course/Student managemnet
    public function relatePartList() {

        $courseId = Input::get('course_id');
        //Get part list
        if (Auth::user()->group_id == 4) {
            $partList = DB::table('subject_to_ds')
                    ->join('part', 'part.id', '=', 'subject_to_ds.part_id')
                    ->where('subject_to_ds.course_id', '=', $courseId)
                    ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                    ->select('part.id', 'part.title')
                    ->distinct('subject_to_ds.part_id')
                    ->orderBy('part.order', 'ASC')
                    ->get();
        } else {
            $partList = DB::table('relate_part_with_course')
                    ->join('part', 'part.id', '=', 'relate_part_with_course.part_id')
                    ->where('relate_part_with_course.course_id', '=', $courseId)
                    ->select('part.id', 'part.title')
                    ->orderBy('part.order', 'ASC')
                    ->get();
        }

        if (empty($partList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => __('label.NO_PART_OF_THIS_COURDE_IS_TO_ASSIGN')), 401);
        }
        return Response::json(array('success' => true, 'parts' => $partList), 200);
    }

    //This function use for RESULT TAE Show subject
    public function showSubject() {

        $courseId = Input::get('course_id');
        $partId = Input::get('part_id');
        //Get subject list

        if (Auth::user()->group_id == 4) {

            $subjectList = DB::table('subject_to_ds')
                    ->join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                    ->join(DB::raw('(select subject_id FROM epe group by subject_id ) as tem_subject'), function($join) {
                        $join->on('subject.id', '=', 'tem_subject.subject_id');
                    })
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
                            'subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title")
                    )
                    ->orderBy('subject.order', 'ASC')
                    ->get();
        } else {
            $subjectList = DB::table('phase_to_subject')
                    ->join('subject', 'subject.id', '=', 'phase_to_subject.subject_id')
                    ->join(DB::raw('(select subject_id FROM epe group by subject_id ) as tem_subject'), function($join) {
                        $join->on('subject.id', '=', 'tem_subject.subject_id');
                    })
                    ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
                    ->where('phase_to_subject.course_id', '=', $courseId)
                    ->where('phase_to_subject.part_id', '=', $partId)
                    ->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title"))
                    ->orderBy('subject.order', 'ASC')
                    ->get();
        }

        if (empty($subjectList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => __('label.NO_SUBJECT_OF_THIS_PART_IS_NOT_ASSIGN')), 401);
        }
        return Response::json(array('success' => true, 'subjects' => $subjectList), 200);
    }

    public function showMarksheet($media = null) {
        

        $rules = array(
            'course_id' => 'required',
            'part_id' => 'required',
            'subject_id' => 'required'
        );

        $messages = array(
            'course_id.required' => 'Course must be selected!',
            'part_id.required' => 'Part must be selected!',
            'subject_id.required' => 'Subject must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }



        $courseId = Input::get('course_id');
        $partId = Input::get('part_id');
        $subjectId = Input::get('subject_id');

        //Get Course, Part, Phase & subject information
        $courseRelatedInfo = PhaseToSubject::where('course_id', $courseId)
                        ->where('part_id', $partId)
                        ->where('subject_id', $subjectId)
                        ->with(array('Course', 'Part', 'Subject', 'Phase'))->first();

        $data['courseRelatedInfo'] = $courseRelatedInfo;

        //Get Reguklar EPE Marks Details
        $regularEpeResultArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->leftJoin('student_details', 'student_details.id', '=', 'epe_mark.student_id')
                ->leftJoin('users', 'users.id', '=', 'student_details.user_id')
                ->leftJoin('rank', 'rank.id', '=', 'users.rank_id')
                ->leftJoin('branch', 'branch.id', '=', 'users.branch_id')
                ->select(
                        'epe_mark.id', 'epe.type', 'epe_mark.pass', 'epe_mark.converted_mark', 'epe_mark.objective_earned_mark'
                        , 'epe_mark.subjective_earned_mark', 'epe_mark.total_mark', 'epe_mark.objective_submission_time'
                        , 'epe_mark.subjective_submission_time', 'epe.id as epe_id', 'epe.course_id', 'epe.part_id'
                        , 'epe.subject_id', 'epe.phase_id', 'student_details.id as student_id', 'epe.title as tae_title'
                        , 'users.registration_no', 'users.iss_no'
                        , DB::raw("CONCAT(rank.short_name, ' ',users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('epe.type', '1')
                ->where('epe.course_id', $courseId)
                ->where('epe.part_id', $partId)
                ->where('epe.subject_id', $subjectId)
                ->where('epe.ci_status', 1)
                ->whereIn('epe_mark.pass', array(1, 2))
                ->orderBy('users.iss_no', 'asc')
                ->get();

        $irregularEpeResultArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join(DB::raw("(SELECT MAX(`epe_mark`.`id`) AS id
                            FROM  `epe_mark`
                            INNER JOIN `epe` ON `epe`.`id` = `epe_mark`.`epe_id`
                            LEFT JOIN `epe_details` ON `epe_details`.`id` = `epe_mark`.`epe_details_id` 
                            LEFT JOIN `student_details` ON `student_details`.`id` = `epe_mark`.`student_id` 
                            WHERE `epe`.`type` = 2 AND `epe_mark`.`pass` IN (1,2) AND 
                            `epe_details`.`course_id` = '" . $courseId . "' AND `epe_details`.`part_id` = '" . $partId . "' AND `epe`.`subject_id` = '" . $subjectId . "' 
                            GROUP BY `epe_mark`.`student_id`
                            ORDER BY `epe_mark`.`id` DESC) as t2"), function ($q) {

                    $q->on('epe_mark.id', '=', 't2.id');
                })
                ->leftJoin('epe_details', 'epe_details.id', '=', 'epe_mark.epe_details_id')
                ->leftJoin('student_details', 'student_details.id', '=', 'epe_mark.student_id')
                ->leftJoin('users', 'users.id', '=', 'student_details.user_id')
                ->leftJoin('rank', 'rank.id', '=', 'users.rank_id')
                ->leftJoin('branch', 'branch.id', '=', 'users.branch_id')
                ->select(
                        'epe_mark.id', 'epe.type', 'epe_mark.pass', 'epe_mark.converted_mark', 'epe_mark.objective_earned_mark'
                        , 'epe_mark.subjective_earned_mark', 'epe_mark.total_mark', 'epe_mark.objective_submission_time'
                        , 'epe_mark.subjective_submission_time', 'epe.id as epe_id', 'epe.course_id', 'epe.part_id'
                        , 'epe.subject_id', 'epe.phase_id', 'student_details.id as student_id', 'epe.title as tae_title'
                        , 'users.registration_no', 'users.iss_no'
                        , DB::raw("CONCAT(rank.short_name, ' ',users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('epe.type', '2')
                ->where('epe_details.course_id', $courseId)
                ->where('epe_details.part_id', $partId)
                ->where('epe.subject_id', $subjectId)
                ->where('epe.ci_status', 1)
                ->whereIn('epe_mark.pass', array(1, 2))
                ->orderBy('users.iss_no', 'asc')
                ->get();

        $rescheduleEpeResultArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join(DB::raw("(SELECT MAX(`epe_mark`.`id`) AS id
                            FROM  `epe_mark`
                            INNER JOIN `epe` ON `epe`.`id` = `epe_mark`.`epe_id`
                            LEFT JOIN `epe_details` ON `epe_details`.`id` = `epe_mark`.`epe_details_id` 
                            LEFT JOIN `student_details` ON `student_details`.`id` = `epe_mark`.`student_id` 
                            WHERE `epe`.`type` = 3 AND `epe_mark`.`pass` IN (1,2) AND 
                            `epe_details`.`course_id` = '" . $courseId . "' AND `epe_details`.`part_id` = '" . $partId . "' AND `epe`.`subject_id` = '" . $subjectId . "' 
                            GROUP BY `epe_mark`.`student_id`
                            ORDER BY `epe_mark`.`id` DESC) as t2"), function ($q) {

                    $q->on('epe_mark.id', '=', 't2.id');
                })
                ->leftJoin('epe_details', 'epe_details.id', '=', 'epe_mark.epe_details_id')
                ->leftJoin('student_details', 'student_details.id', '=', 'epe_mark.student_id')
                ->leftJoin('users', 'users.id', '=', 'student_details.user_id')
                ->leftJoin('rank', 'rank.id', '=', 'users.rank_id')
                ->leftJoin('branch', 'branch.id', '=', 'users.branch_id')
                ->select(
                        'epe_mark.id', 'epe.type', 'epe_mark.pass', 'epe_mark.converted_mark', 'epe_mark.objective_earned_mark'
                        , 'epe_mark.subjective_earned_mark', 'epe_mark.total_mark', 'epe_mark.objective_submission_time'
                        , 'epe_mark.subjective_submission_time', 'epe.id as epe_id', 'epe.course_id', 'epe.part_id'
                        , 'epe.subject_id', 'epe.phase_id', 'student_details.id as student_id', 'epe.title as tae_title'
                        , 'users.registration_no', 'users.iss_no'
                        , DB::raw("CONCAT(rank.short_name, ' ',users.first_name, ' ', users.last_name) AS student_name")
                )
                ->where('epe.type', '3')
                ->where('epe_details.course_id', $courseId)
                ->where('epe_details.part_id', $partId)
                ->where('epe.subject_id', $subjectId)
                ->where('epe.ci_status', 1)
                ->whereIn('epe_mark.pass', array(1, 2))
                ->orderBy('users.iss_no', 'asc')
                ->get();

        $regularIrregularEpeResultMarge = $regularEpeResultArr->merge($irregularEpeResultArr);
        $allResultArr = $regularIrregularEpeResultMarge->merge($rescheduleEpeResultArr);

        //Get student wise number of total attempt
        $getStudentWiseAttemptArr = Epe::join('attendee_records', 'epe.id', '=', 'attendee_records.epe_id')
                ->select(
                        'attendee_records.student_id as student_id', DB::raw("IFNULL(count(attendee_records.id), 0) as attempt")
                )
                ->where('attendee_records.type', 2)
                ->where('epe.subject_id', $subjectId)
                ->whereIn('attendee_records.status', array(3, 4, 5))// 3 = Absent, 4 = Pass, 5 = Failed
                ->groupBy('attendee_records.student_id')
                ->pluck('attempt', 'student_id');

        //attendee record cc taken & absent
        $attendeeRecord = AttendeeRecord::join('epe', 'epe.id', '=', 'attendee_records.epe_id')
                        ->join('student_details', 'student_details.id', '=', 'attendee_records.student_id')
                        ->join('users', 'users.id', '=', 'student_details.user_id')
                        ->join('rank', 'rank.id', '=', 'users.rank_id')
                        ->where('epe.subject_id', '=', $subjectId)
                        ->where('student_details.course_id', '=', $courseId)
                        ->where('student_details.part_id', '=', $partId)
                        ->where('attendee_records.type', '2')
                        ->whereIn('attendee_records.status', ['2', '3'])
                        ->select('attendee_records.status as attendee_status', 'student_details.id as student_id'
                                , 'users.registration_no', 'users.iss_no', 'users.id as user_id', 'attendee_records.created_at'
                                , DB::raw("CONCAT(rank.short_name, ' ',users.first_name, ' ', users.last_name) AS student_name")
                                , DB::raw("MAX(attendee_records.created_at)")
                        )
                        ->groupBy('attendee_records.student_id')
                        ->orderBy('users.iss_no')
                        ->get()->toArray();


        $eperesultArr = array();
        if (!$allResultArr->isEmpty()) {
            foreach ($allResultArr as $key => $value) {

                if (array_key_exists($value->student_id, $eperesultArr)) {

                    if ($eperesultArr[$value->student_id]['id'] < $value->id) {
                        $eperesultArr[$value->student_id] = $value;
                        $eperesultArr[$value->student_id]->totalAttempt = !empty($getStudentWiseAttemptArr[$value->student_id]) ? $getStudentWiseAttemptArr[$value->student_id] : 0;
                    }
                } else {
                    $eperesultArr[$value->student_id] = $value;
                    $eperesultArr[$value->student_id]->totalAttempt = !empty($getStudentWiseAttemptArr[$value->student_id]) ? $getStudentWiseAttemptArr[$value->student_id] : 0;
                }
            }
        }

        //Unset CC Taken from Attendee Record if corresponding Attendee Appear in the EPE (Irregular/Reschedule) and Pass
        if (!empty($attendeeRecord)) {
            foreach ($attendeeRecord as $key => $value) {
                if (array_key_exists($value['student_id'], $eperesultArr)) {
                    unset($attendeeRecord[$key]);
                }
            }
        }

        
        $result_showable = 0;
        if (!empty($eperesultArr)) {
            $result_showable = 1;
        }

        $eperesultTempArr = [];
        if (!empty($eperesultArr)) {
            foreach ($eperesultArr as $key => $value) {
                $eperesultTempArr[$value->iss_no] = $value;
            }
        }
        
        $ccTakenAndAbsentArr = [];
        if (!empty($attendeeRecord)) {
            foreach ($attendeeRecord as $key => $value) {
                $ccTakenAndAbsentArr[$value['iss_no']] = $value;
            }
        }

        $eperesultFinalArr = array_merge($ccTakenAndAbsentArr, $eperesultTempArr);
        ksort($eperesultFinalArr);

        $data['eperesultFinalArr'] = $eperesultFinalArr;
        $data['attendeeRecord'] = $attendeeRecord;
        $data['result_showable'] = $result_showable;

        if ($media == 'print') {
            $targetArr = compact('eperesultArr', 'eperesultFinalArr', 'courseRelatedInfo', 'attendeeRecord', 'result_showable');
            return $targetArr;
        }
        $returnHTML = view('eperesult/show_marksheet', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}

?>
