<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\SubjectToDs;
use App\Subject;
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

class SubjectToDsController extends Controller {

    public function index(Request $request) {

        $data['previousData'] = SubjectToDs::select('subject_id', 'user_id')->get()->toArray();
       

        $data['targetArr'] = Subject::select('subject.id as id', DB::raw("CONCAT(subject.title, ' ', subject.code) AS subject_name"))
                ->orderBy('subject.order', 'ASC')
                ->get();
        $data['dsList'] = array('' => __('label.SELECT_EXAMINER_OPT')) + User::where('users.group_id', 2)
                        ->where('users.status', 'active')
                        ->select(DB::raw("CONCAT(users.first_name,' ', users.last_name,' (',users.username,')') AS title"), 'users.id as id')
                        ->pluck('title', 'id')->toArray();

//       Helper::dump( $data['dsList']);
        return view('subjecttods.index', $data);
    }

    public function relatedData(Request $request) {

        $subjectId = $request->subject_id;
        $userId = $request->user_id;
        $rules = array();


        //Set validation rules for phase/subject
        if (!empty($subjectId)) {
            foreach ($subjectId as $key => $val) {
                $rules['user_id.' . $val] = 'required';
            }
        }


        //Set validation message for phase/subject
        if (!empty($subjectId)) {
            $row = 1;
            foreach ($subjectId as $key => $val1) {
                $message['user_id.' . $val1 . '.required'] = $this->ordinalSuffix($row) . ' number row examiner must be selected';
                $row++;
            }
        }



        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $previousData = SubjectToDs::pluck('subject_id')->toArray();

        $deletePrevious = SubjectToDs::whereIn('subject_id', $previousData)->delete();
        $data = array();
        if (!empty($userId)) {
            $i = 0;
            foreach ($userId as $subId => $examinerId) {
                    $data[$i]['subject_id'] = $subId;
                    $data[$i]['user_id'] = $examinerId;
                    $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $data[$i]['updated_by'] = Auth::user()->id;
                    $i++;
            }
        }
        
        DB::beginTransaction();
        try {
            if (!empty($data)) {
                $targets = SubjectToDs::insert($data);
            }

            DB::commit();

            // all good
            return Response::json(array('success' => TRUE, 'data' => __('label.SUBJECT_HAS_BEEN_ASSIGNED_SUCESSFULLY')), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'heading' => 'Assigned Failed', 'message' => __('label.SUBJECT_COULD_NOT_BE_ASSIGNED_SUCESSFULLY')), 401);
        }
    }

    public function ordinalSuffix($number, $ss = 0) {
        /**         * check for 11, 12, 13 ** */
        if ($number % 100 > 10 && $number % 100 < 14) {
            $os = 'th';
        }
        /*         * * check if number is zero ** */ elseif ($number == 0) {
            $os = '';
        } else {
            /*             * * get the last digit ** */
            $last = substr($number, -1, 1);
            switch ($last) {
                case "1":
                    $os = 'st';
                    break;
                case "2":
                    $os = 'nd';
                    break;
                case "3":
                    $os = 'rd';
                    break;
                default:
                    $os = 'th';
            }
        }
        /*         * * add super script ** */
        $os = $ss == 0 ? $os : '<sup>' . $os . '</sup>';
        /*         * * return ** */
        return $number . $os;
    }

}

?>