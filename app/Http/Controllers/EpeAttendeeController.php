<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\MockMark;
use App\Epe;
use App\MockTest;
use App\MockMarkDetails;
use App\EpeMark;
use App\EpeQusSubmitDetails;
use App\Designation;
use App\AttendeeRecord;
use App\EpeMarkDetails;
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
use DateTime;
use Illuminate\Http\Request;

class EpeAttendeeController extends Controller {

    public function index(Request $request) {
        $subjectId = $request->subject_id;
        $loggedInUser = Auth::user()->id;
        $epeList = array('' => __('label.SELECT_EPE_OPT'));
        $subjectList = Epe::join('subject', 'subject.id', '=', 'epe.subject_id')
                        ->select('subject.id as id', DB::raw("CONCAT(subject.title, ' ', subject.code) AS name"))
                        ->where('epe.type', '1')
                        ->orderBy('epe.id', 'ASC')
                        ->pluck('name', 'id')->toArray();
        $subjectList = array('' => __('label.SELECT_SUBJECT_OPT')) + $subjectList;
        $data['subjectList'] = $subjectList;
        $data['epeList'] = $epeList;
        // load the view and pass the TAE index
        return view('epeAttendee.index', $data);
    }

    //This function use for Create TAE Show subject
    public function showEpe(Request $request) {


        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d');


        $subjectId = $request->subjectId;

        $epeList = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                ->where('epe.status', '1')
                ->where('epe.subject_id', $subjectId)
                ->where('epe.exam_date', $currentDateTime)
                ->orderBy('epe.id', 'asc')
                ->get();


        if (empty($epeList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => __('label.NO_SUBJECT_OF_THIS_PART_IS_NOT_ASSIGN')), 401);
        }
        return Response::json(array('success' => true, 'epes' => $epeList), 200);
    }

    public function showAttendeeEpe(Request $request) {


        $rules = array(
            'subject_id' => 'required',
            'epe_id' => 'required'
        );

        $messages = array(
            'subject_id.required' => 'Subject must be selected!',
            'epe_id.required' => 'Exam must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $epeInfoArr = $targetArr = $firstSerialIdArr = array();
        //Get EPE information
        $epeInfoArr = Epe::where('epe.id', $request->epe_id)
                ->first();


        //Get Student information
        $targetArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->leftJoin('users', 'users.id', '=', 'epe_mark.employee_id')
                ->leftJoin('rank', 'rank.id', '=', 'users.rank_id')
                ->leftJoin('branch', 'branch.id', '=', 'users.branch_id')
                ->select(
                        'epe_mark.*'
                        , 'epe.subject_id', 'branch.name as branch_name'
                        , 'epe.title as epe_title'
                        , 'users.registration_no', 'users.service_no', 'users.iss_no'
                        , 'users.id as user_id'
                        , DB::raw("CONCAT(epe.exam_date, ' ',epe.end_time) AS epe_end_time")
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS student_name")
                        , 'rank.title as grade', 'users.designation_id','users.username'
                )
                ->where('epe.id', '=', $request->epe_id)
                ->orderBy('users.iss_no', 'ASC')
                ->orderBy('epe.created_at', 'DESC')
                ->get();



        $firstSerialIdArr = EpeQusSubmitDetails::where('epe_id', $request->epe_id)
                        ->orderBy('serial_id', 'DESC')
                        ->pluck('serial_id', 'employee_id')->toArray();
        $statusArr = [];
        $statusCount = [];
        foreach ($targetArr as $item) {
            if ($item->submitted == 1) {
                $statusArr[$item->submitted]['submitted'][$item->id] = $item->submitted;
                $statusCount['submitted'][1] = 'Submitted : ' . '<strong>' . count($statusArr[1]['submitted']) . ' </strong>';
            } else {
                $statusArr[$item->submitted]['objective'][$item->id] = $item->submitted;
                $statusCount['objective'][0] = 'Objective : ' . ' <strong>' . count($statusArr[0]['objective']) . ' </strong>';
            }
        }

        $userInfo = User::join('rank', 'rank.id', '=', 'users.rank_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->where('users.group_id', 3)
                        ->select('users.id as user_id', DB::raw("CONCAT(rank.short_name, ' ',users.first_name, ' ', users.last_name,' ',',',designation.title,' ','(ISSP).') AS name"))
                        ->pluck('name', 'user_id')->toArray();

        $positionArr = Designation::pluck('title', 'id')->toArray();

        $data['firstSerialIdArr'] = $firstSerialIdArr;
        $data['epeInfoArr'] = $epeInfoArr;
        $data['targetArr'] = $targetArr;
        $data['statusCount'] = $statusCount;
        $data['userInfo'] = $userInfo;
        $data['positionArr'] = $positionArr;


        $returnHTML = view('epeAttendee/show_attendee_epe', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function delete(Request $request) {
        $epeMarkInfo = EpeMark::where('id', $request->epe_mark_id)
                        ->select('id', 'epe_id', 'employee_id')->first();

       

        if (empty($epeMarkInfo)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }

        AttendeeRecord::where('employee_id', $epeMarkInfo->employee_id)
                ->where('epe_id', $epeMarkInfo->epe_id)
                ->delete();

        EpeMarkDetails::where('epe_mark_id', $epeMarkInfo->id)->delete();
        EpeQusSubmitDetails::where('employee_id', $epeMarkInfo->employee_id)
                ->where('epe_id', $epeMarkInfo->epe_id)
                ->delete();

        EpeMark::where('id', $request->epe_mark_id)->delete();

        return Response::json(array('success' => TRUE, 'message' => __('label.ATTENDEE_DELETED_SUCCESSFULLY')), 200);
    }

    public function forceSubmit(Request $request) {
        $empMarkId = $request->epe_mark_id;
        $data['empMarkId'] = $empMarkId;
        $returnHTML = view('epeAttendee/force_submitted', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function saveForceSubmit(Request $request) {
        $target = EpeMark::find($request->epe_mark_id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }

        if (empty($request->remarks)) {
            return Response::json(array('success' => false, 'heading' => __('label.FORCE_SUBMITTED'), 'message' => 'Remarks field required!'), 401);
        }

        $target->subjective_submission_time = date('Y-m-d H:i:s');
        $target->submitted = 1; //Subjective submitted
        $target->force_submitted_by = Auth::user()->id;
        $target->force_submitted_remarks = $request->remarks;
        
        EpeQusSubmitDetails::where('employee_id', $target->employee_id)
                ->where('epe_id', $target->epe_id)
                ->delete();
        if ($target->save()) {
            return Response::json(array('success' => TRUE), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => __('label.FORCE_SUBMITTED'), 'message' => __('label.FORCE_SUBMITTED_COULD_NOT_BE_SUCCESS')), 401);
        }
    }

}
