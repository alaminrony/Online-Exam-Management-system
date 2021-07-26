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

class EpeCiMarkingController extends Controller {

    public function index() {

        //Find the regular EPE
        $regularEpeList = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                ->where('epe.type', '1')
                ->where('epe.status', '1')
                ->orderBy('epe.id', 'asc')
                ->pluck('title', 'id');

        $epeList = array('' => __('label.SELECT_EPE_OPT')) + $regularEpeList;
        $data['epeList'] = $epeList;

        // load the view and pass the TAE index
        return view('epeCiMarking.index', $data);
    }

    public function showSubmittedEpe() {
        

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
        //Get EPE information
        $epeInfo = Epe::where('epe.id', Input::get('epe_id'))->with(array('Subject'))->first();

        $data['epeInfo'] = $epeInfo;

        $lockerInfo = null;
        if (!empty($epeInfo->ci_lock_by)) {
            $lockerInfo = User::with('rank')->find($epeInfo->ci_lock_by);
        }

        $data['lockerInfo'] = $lockerInfo;

        $submittedInfo = null;
        if (!empty($epeInfo->ds_lock_by)) {
            $submittedInfo = User::with('rank')->find($epeInfo->ds_lock_by);
        }

        $data['submittedInfo'] = $submittedInfo;

        //Get EPE Mark information
        $targetArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('student_details', 'student_details.id', '=', 'epe_mark.student_id')
                ->join('users', 'users.id', '=', 'student_details.user_id')
                ->join('rank', 'rank.id', '=', 'users.rank_id')
                ->where('epe.id', Input::get('epe_id'))
//                ->where('epe_mark.ds_status', 2)
                ->select('epe_mark.id', 'epe_mark.objective_earned_mark', 'epe_mark.subjective_earned_mark'
                        , 'epe_mark.converted_mark', 'epe_mark.total_mark', 'epe_mark.ds_status'
                        , 'epe.id as epe_id', 'epe.ci_status as epe_ci_status', 'epe_mark.unlock_request'
                        , 'epe_mark.pass_mark', 'epe_mark.total_mark'
                        , DB::raw("CONCAT(rank.short_name,' ',users.first_name,' ',users.last_name) AS name"), 'users.id as user_id')
                ->orderBy('epe_mark.id', 'asc')
                ->get();

        $data['targetArr'] = $targetArr;
        if (!$targetArr->isEmpty()) {
            $data['epe_ci_status'] = $targetArr[0]['epe_ci_status'];
        }


        $data['dsStatusArr'] = array('0' => array('total' => 0, 'text' => __('label.WAITING_FOR_ASSESSMENT'), 'label' => 'danger'),
            '1' => array('total' => 0, 'text' => __('label.ASSESSED'), 'label' => 'success'),
            '2' => array('total' => 0, 'text' => __('label.LOCKED'), 'label' => 'primary'));

        if (!empty($targetArr)) {
            foreach ($targetArr as $item) {
                $data['dsStatusArr'][$item->ds_status]['total'] ++;
            }
        }


        $returnHTML = view('epeCiMarking/show_submitted_epe', $data)->render();
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
                    ->with(array('Subject', 'epeDetail', 'epeDetail.Branch'))
                    ->first();
        }

        //find questions set of this epe
        $epeSubQus = EpeToQuestion::join('question', 'question.id', '=', 'epe_to_question.question_id')
                        ->select('epe_to_question.*', 'question.question', 'question.note', 'question.image', 'question.type_id')
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
        $qusWiseTotalMark = array();
        if (!empty($epeSubQus)) {
            foreach ($epeSubQus as $item) {
                $qusSubArr[] = $item;
                $qusWiseTotalMark[$item['question_id']] = $item['mark'];
            }
        }
        //prepare answerArr
        $epeAnswer = EpeMarkDetails::where('epe_mark_id', $epeMarkId)->get();
        $answerArr = $ciMarkLimit = array();

        if (!empty($epeAnswer)) {
            foreach ($epeAnswer as $item) {
                $answerArr[$item->question_id] = $item;
                if (!empty($qusWiseTotalMark[$item->question_id])) {
                    if (!empty($answerArr[$item->question_id]->ds_mark)) {
                        $ciMarkLimit[$item->question_id]['min'] = $answerArr[$item->question_id]->ds_mark - ($answerArr[$item->question_id]->ds_mark * $epeInfo->ci_review / 100);
                        $maxValue = $answerArr[$item->question_id]->ds_mark + ($answerArr[$item->question_id]->ds_mark * $epeInfo->ci_review / 100);
                        $ciMarkLimit[$item->question_id]['max'] = ($maxValue >$qusWiseTotalMark[$item['question_id']])?$qusWiseTotalMark[$item['question_id']]:$maxValue;
                    }
                }
            }
        }

        return view('epeCiMarking.subjectiveMarking')->with(compact('epeMarkInfo', 'epeInfo', 'qusSubArr'
                , 'answerArr', 'ciMarkLimit','epeSubSum','epeObjSum'));
    }

    public function saveSubjectiveMarking($epeMarkId = null) {
        //get EpeMark info
        $epeMarkInfo = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                        ->select('epe_mark.*')
                        ->where('epe_mark.id', $epeMarkId)->first();

        $targetArr = array();
        $totalMark = 0;
        if (!empty(Input::get('ci_mark'))) {
            foreach (Input::get('ci_mark') as $key => $val) {
                $totalMark += $val;
                EpeMarkDetails::where('id', $key)->limit(1)
                        ->update(array('final_mark'=>$val,'ci_mark' => $val, 'ci_remarks' => Input::get('ci_remarks.' . $key)));
            }
        }

        if (Input::get('submit') == 'save') {
            Session::flash('success', __('label.MARKS_HAVE_BEEN_SAVED'));
        }

        EpeMark::where('id', Input::get('epe_mark_id'))->limit(1)
                ->update(array('subjective_earned_mark' => $totalMark));

        return Redirect::to('epecimarking?epe_id=' . $epeMarkInfo->epe_id);
    }

    public function lockSubjectiveMarking() {

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

        //Get EPE information
        $epeInfo = Epe::where('epe.id', Input::get('epe_id'))->first();

        //check if there is peding EpeMarking from DS which has not been locked
        $pendingEpeSubjectiveScript = EpeMark::where('epe_id', $epeInfo->id)->where('ds_status', '!=', '2')->get()->count();
        if (!empty($pendingEpeSubjectiveScript)) {
            return Response::json(array('success' => false, 'heading' => 'Operation Failed', 'message' => $pendingEpeSubjectiveScript . ' scripts is waiting to be submitted by DS for this Exam'), 401);
        }

        DB::beginTransaction();
        try {

            $updateField['ci_status'] = 1; //1 == Locked
            $updateField['ci_lock_by'] = Auth::user()->id;
            $updateField['ci_lock_at'] = date('Y-m-d H:i:s');


            $locked = Epe::where('epe.id', Input::get('epe_id'))
                    ->update($updateField);

            //get EPE Mark Info
            //Calculate student marking for this EPE
            // DB::select(DB::raw('UPDATE `epe_mark` as `a` JOIN `epe_mark` as `b` ON `a`.`id` = `b`.`id` SET `a`.`converted_mark` = (`b`.`objective_earned_mark` + `b`.`subjective_earned_mark`), `a`.`pass` = (IF((`b`.`objective_earned_mark` + `b`.`subjective_earned_mark`) >= `a`.`pass_mark` , 1, 2)) where `a`.`epe_id` = "'.$epeInfo->id.'"'));
            $query1 = 'UPDATE `epe_mark` as `a` JOIN `epe_mark` as `b` ON `a`.`id` = `b`.`id` SET `a`.`converted_mark` = (`b`.`objective_earned_mark` + `b`.`subjective_earned_mark`), `a`.`pass` = (IF((`b`.`objective_earned_mark` + `b`.`subjective_earned_mark`) >= `a`.`pass_mark` , 1, 2)) where `a`.`epe_id` = "' . $epeInfo->id . '"';

            //Update attendee_records
            //DB::select(DB::raw('UPDATE `epe_mark` JOIN `attendee_records` ON `epe_mark`.`epe_id` = `attendee_records`.`epe_id` and `epe_mark`.`student_id` = `attendee_records`.`student_id` SET `attendee_records`.`status` = (CASE WHEN `epe_mark`.`pass` = 1 THEN 4 WHEN `epe_mark`.`pass` = 2 THEN 5 END) where `epe_mark`.`epe_id` = "'.$epeInfo->id.'"'));
            $query2 = 'UPDATE `epe_mark` JOIN `attendee_records` ON `epe_mark`.`epe_id` = `attendee_records`.`epe_id` and `epe_mark`.`student_id` = `attendee_records`.`student_id` SET `attendee_records`.`status` = (CASE WHEN `epe_mark`.`pass` = 1 THEN 4 WHEN `epe_mark`.`pass` = 2 THEN 5 END) where `epe_mark`.`epe_id` = "' . $epeInfo->id . '"';
            DB::connection()->getPdo()->exec($query1);
            DB::connection()->getPdo()->exec($query2);

            //Get student list for this EPE
//            if ($epeInfo->type == '1') {
//                $getStudentList = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
//                                ->select('epe_mark.id', 'epe_mark.student_id')->where('epe_mark.epe_id', $epeInfo->id)->where('epe_mark.pass', '1')->get();
//            } else if (in_array($epeInfo->type, array('2', '3'))) {
//                $getStudentList = EpeMark::join('epe_details', 'epe_details.id', '=', 'epe_mark.epe_details_id')
//                                ->select('epe_mark.id', 'epe_mark.student_id')->where('epe_mark.epe_id', $epeInfo->id)->where('pass', '1')->get();
//            }
//            if (count($getStudentList) > 0) {
//                foreach ($getStudentList as $student) {
//                    //Get student total completed phase & Initial course
//                    $studentCompletedPhaseHistory = Student::select('student_details.id as student_id', DB::raw('IFNULL(temp_migration.course_id, student_details.course_id) as initial_course'), DB::raw('IFNULL(completed_tae.total_phase, 0) as completed_tae_phase'), DB::raw('IFNULL(completed_epe.total_phase, 0) as completed_epe_phase'))
//                            ->leftJoin(DB::raw('(select student_id, course_id FROM migration where student_id = ' . $student->student_id . ' AND part_id = ' . $student->part_id . ' order by id ASC limit 1) as temp_migration'), function($join) {
//                                $join->on('student_details.id', '=', 'temp_migration.student_id');
//                            })
//                            ->leftJoin(DB::raw('(select tae_to_student.student_id, count(tae_to_student.id) as total_phase FROM tae_to_student INNER JOIN tae on tae.id = tae_to_student.tae_id WHERE tae.part_id = ' . $student->part_id . ' AND tae_to_student.result_status = 1 GROUP BY tae_to_student.student_id) as completed_tae'), function($join) {
//                                $join->on('completed_tae.student_id', '=', 'student_details.id');
//                            })
//                            ->leftJoin(DB::raw('(select epe_mark.student_id, count(epe_mark.id) as total_phase FROM epe_mark INNER JOIN epe on epe.id = epe_mark.epe_id WHERE epe.part_id = ' . $student->part_id . ' AND epe_mark.pass = 1 GROUP BY epe_mark.student_id) as completed_epe'), function($join) {
//                                $join->on('completed_epe.student_id', '=', 'student_details.id');
//                            })
//                            ->where('student_details.id', DB::raw("'" . $student->student_id . "'"))
//                            ->first();
//
//                    //Get total completed TAE phase
//                    $totalCompletedTaePhase = $studentCompletedPhaseHistory->completed_tae_phase;
//                    //Get total completed EPE phase
//                    $totalCompletedEpePhase = $studentCompletedPhaseHistory->completed_epe_phase;
//                    //IF already exists in ISSP accomplished table
//                    //$alreadyAccomplished = IsspAccomplish::where('part_id', $partId)->where('student_id', $student->student_id)->first();
//                    if (($totalCompletedTaePhase == '8') && ($totalCompletedEpePhase == '8')) {
//                        //Part wise student accomplish course
//                        $accomplish = New IsspAccomplish;
//                        $accomplish->initial_course_id = $studentCompletedPhaseHistory->initial_course;
//                        $accomplish->finish_course_id = $student->course_id;
//                        $accomplish->part_id = $student->part_id;
//                        $accomplish->student_id = $student->student_id;
//                        $accomplish->created_at = date('Y-m-d H:i:s');
//                        $accomplish->created_by = Auth::user()->id;
//                        $accomplish->save();
//                    }
//                }//foreach
//            }//if


            $lockerInfo = null;
            $lockedMsg = '<div class="col-md-12 text-center"><div class="well text-danger">Exam has been locked</div></div>';
            if (!empty($epeInfo->ci_lock_by)) {
                $lockerInfo = User::with('rank')->find($epeInfo->ci_lock_by);
                $lockedMsg = '<div class="col-md-12 text-center"><div class="well text-danger">' . __('label.THIS_EPE_HAS_BEEN_LOCKED_BY') . $lockerInfo->Rank->short_name . $lockerInfo->first_name . $lockerInfo->last_name . ' (' . $lockerInfo->username . ')' . ' at ' . $epeInfo->ci_lock_at . '</div></div>';
            }

            DB::commit();
            return Response::json(array('success' => TRUE, 'data' => $lockedMsg), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => __('label.COULD_NOT_BE_SUBMITTED_RESULT')), 401);
        }
    }

}
