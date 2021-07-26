<?php
namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\EpeMark;
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

class UnlockRequestCiController extends Controller {
       
    public $controller = 'UnlockRequestCi';
    public function index() {
        $epeMarking = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('subject', 'subject.id', '=', 'epe.subject_id')
                ->join('users', 'users.id', '=', 'epe_mark.unlock_request_by')
                ->join('rank', 'rank.id', '=', 'users.rank_id')
                ->where('epe_mark.unlock_request', '1')
                ->select('epe_mark.id',  'subject.title as subject_name', 'epe.type', 'epe.title as epe_title'
                        , 'epe_mark.remarks', 'epe_mark.unlock_request', 'epe_mark.unlock_request_at'
                        , DB::raw("CONCAT(rank.short_name,' ',users.first_name,' ',users.last_name) AS name")
                        , 'users.id as user_id', 'users.photo')
                ->get();

        $data['epeMarking'] = $epeMarking;
        // load the view and pass the Exam index
        return view('epeUnlockRequest.index', $data);
    }

    public function unlock(Request $request) {
        $empmarkId = $request->epe_mark_id;
        $empMark = EpeMark::find($empmarkId);

        if (empty($empMark)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }

        if ($empMark->subjective_earned_mark != '0.00') {
            $empMark->ds_status = '1';
        } else {
            $empMark->ds_status = '0';
        }
        $empMark->unlock_request = '0';
        $empMark->remarks = null;
        $empMark->unlock_request_at = null;
        $empMark->unlock_request_by = null;

        if ($empMark->save()) {
            return Response::json(array('success' => TRUE, 'message' => __('label.SUCCESSFULLY_UNLOCK')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.UNLOCK_REQUEST_FAILED')), 401);
        }
    }

    public function deny(Request $request) {
        $empmarkId = $request->epe_mark_id;
        $empMark = EpeMark::find($empmarkId);

        if (empty($empMark)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }

        $empMark->unlock_request = '0';
        $empMark->remarks = null;
        $empMark->unlock_request_at = null;
        $empMark->unlock_request_by = null;

        if ($empMark->save()) {
            return Response::json(array('success' => TRUE, 'message' => __('label.SUCCESSFULLY_DENY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.UNLOCK_REQUEST_DENY_FAILED')), 401);
        }
    }

}
