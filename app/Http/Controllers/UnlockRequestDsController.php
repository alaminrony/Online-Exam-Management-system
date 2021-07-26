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

class UnlockRequestDsController extends Controller {

    public function index() {


        $taeToStudent = TaeToStudent::join('student_details', 'student_details.user_id', '=', 'tae_to_student.unlock_request_by')
                ->join('course', 'course.id', '=', 'student_details.course_id')
                ->join('tae', 'tae.id', '=', 'tae_to_student.tae_id')
                ->join('subject', 'subject.id', '=', 'tae.subject_id')
                ->join('part', 'part.id', '=', 'student_details.part_id')
                ->join('users', 'users.id', '=', 'tae_to_student.unlock_request_by')
                ->join('rank', 'rank.id', '=', 'users.rank_id')
                ->where('tae_to_student.unlock_request', '1')
                ->select('tae_to_student.id', 'course.title as course_name', 'subject.title as subject_name'
                        , 'part.title as part_title'
                        , 'tae_to_student.unlock_request_remarks', 'tae_to_student.unlock_request'
                        , 'tae_to_student.unlock_request_at', 'tae_to_student.assignment as assignment_file'
                        , 'tae.id as tae_id', 'tae.ebook_file', 'tae.question_file', 'tae.title as tae_title'
                        , DB::raw("CONCAT(rank.short_name,' ',users.first_name,' ',users.last_name) AS name")
                        , 'users.id as user_id', 'users.photo')
                ->get();

        $data['taeToStudent'] = $taeToStudent;
        // load the view and pass the TAE index
        return view('taeUnlockRequest.index', $data);
    }

    public function unlock() {
        $teaToStudentId = Input::get('tea_to_student_id');
        $taeToStudent = TaeToStudent::find($teaToStudentId);

        if (empty($taeToStudent)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }

        if (!empty($taeToStudent)) {
            $assignment = 'public/uploads/assignment/' . $taeToStudent->assignment;
            $dsAttachment = 'public/uploads/dstae/' . $taeToStudent->ds_attachment;
            if (File::exists($assignment)) {
                File::delete($assignment);
            }
            if (File::exists($dsAttachment)) {
                File::delete($dsAttachment);
            }
        }

        $attendeeRecord = AttendeeRecord::where('tae_id', $taeToStudent->tae_id)
                        ->where('student_id', $taeToStudent->student_id)->delete();

        if ($taeToStudent->delete()) {
            return Response::json(array('success' => TRUE, 'message' => __('label.SUCCESSFULLY_UNLOCK')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.UNLOCK_REQUEST_FAILED')), 401);
        }
    }

    public function deny() {
        $teaToStudentId = Input::get('tea_to_student_id');
        $taeToStudent = TaeToStudent::find($teaToStudentId);

        if (empty($taeToStudent)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }

        $taeToStudent->unlock_request = '0';
        $taeToStudent->unlock_request_remarks = null;
        $taeToStudent->unlock_request_at = null;
        $taeToStudent->unlock_request_by = null;

        if ($taeToStudent->save()) {
            return Response::json(array('success' => TRUE, 'message' => __('label.SUCCESSFULLY_DENY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.UNLOCK_REQUEST_DENY_FAILED')), 401);
        }
    }

}
