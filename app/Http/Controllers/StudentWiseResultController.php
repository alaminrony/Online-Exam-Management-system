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

class StudentWiseResultController extends Controller {

    public function index(Request $request) {

        if (Auth::user()->group_id == 4) {
            $epeList = Epe::join('subject_to_ds', function($join) use ($currentStudentId) {
                                $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                                $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                            })
                            ->select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                            ->where('type', '1')
                            ->where('status', 1)->pluck('title', 'id')->toArray();
        } else {
            $epeList = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                    ->where('status', 1)
                    ->where('type', '1')
                    ->pluck('title', 'id')->toArray();
        }

        $data['epeList'] = array('' => __('label.SELECT_EPE_OPT')) + $epeList;
        $data['studentList'] = array('' => __('label.SELECT_STUDENT_OPT'));
        $type = $request->type;
        if (in_array($type, array('print', 'pdf'))) {
            $printArr = $this->showResult('print');
            extract($printArr);

            if ($type == 'print') {
                return view('studentwiseresult.print_show_result')->with(compact('epeInfo', 'student', 'targetArr', 'type'));
            } else if ($type == 'pdf') {
                $pdf = App::make('dompdf');
                $pdf->loadHTML(view('studentwiseresult.print_show_result')->with(compact('epeInfo', 'student', 'targetArr'
                                        , 'type'))->render())->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            }
        }
        // load the view
        return view('studentwiseresult.index', $data);
    }

    //This function use for get students this part
    public function showStudents(Request $request) {
        $epeId = $request->epe_id;
        //Get part list
//        $studentList = User::join('student_details', 'student_details.user_id', '=', 'users.id')
//                ->join('rank', 'rank.id', '=', 'users.rank_id')
//                ->join('branch', 'branch.id', '=', 'users.branch_id')
//                ->select('student_details.id', DB::raw("CONCAT(rank.short_name, ' ', users.first_name, ' ', users.last_name, branch.short_name,' (', users.iss_no, ')',' (', users.service_no, ')') AS student"))
//                ->where('users.program_id', 1)
//                ->where('users.group_id', '5')
//                ->where('users.status', 'active')
//                ->orderBy('users.registration_no')
//                ->get();


        $studentList = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('student_details', 'student_details.id', '=', 'epe_mark.student_id')
                ->join('users', 'users.id', '=', 'student_details.user_id')
                ->join('rank', 'rank.id', '=', 'users.rank_id')
               
                ->select(DB::raw("CONCAT(rank.short_name,' ',users.first_name,' ',users.last_name) AS student"), 'student_details.id')
                 ->where('epe.id', $request->epe_id)
                ->orderBy('epe_mark.id', 'asc')
                ->get();
        return Response::json(array('success' => true, 'students' => $studentList), 200);
    }

    public function showResult(Request $request,$media = null) {

        //Get current student id from session
        $studentId = $request->student_id;
        $currentStudentId = $studentId;
        //Get Current date time
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        

        $rules = array(
            'epe_id' => 'required'
        );

        $messages = array(
            'epe_id.required' => 'Exam must be selected!'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

       $epeId = $request->epe_id;
        //Get regular EPE Result
        $targetArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('student_details', 'student_details.id', '=', 'epe_mark.student_id')
                ->join('users', 'users.id', '=', 'student_details.user_id')
                ->select(
                        'epe_mark.id', 'epe_mark.student_id', 'epe.id as epe_id', 'epe.subject_id'
                        , 'epe_mark.pass', 'epe_mark.pass_mark', 'epe_mark.total_mark', 'epe_mark.converted_mark'
                        , 'epe_mark.objective_earned_mark'
                        , 'epe_mark.subjective_earned_mark', 'users.registration_no'
                        , 'users.iss_no', DB::raw('DATE_FORMAT(users.maximum_tenure, "%d.%m.%Y") as maximum_tenure')
                        , DB::raw('DATE_FORMAT(student_details.commission_date, "%d.%m.%Y") as commission_date')
                )
                ->where('epe.type', '1')
                ->where('epe.id', $epeId)
                ->where('epe_mark.student_id', $currentStudentId)
                ->whereIn('epe_mark.pass', array(0,1, 2)) // ci lock pass update hobe 
                ->get();

        $data['targetArr'] = $targetArr;

        //student information
        $student = User::join('student_details', 'users.id', '=', 'student_details.user_id')
                        ->join('rank', 'rank.id', '=', 'users.rank_id')
                        ->join('branch', 'branch.id', '=', 'users.branch_id')
                        ->select('users.*', 'student_details.id as student_id', 'student_details.user_id'
                                , 'student_details.user_id', 'rank.title as rank_name', 'rank.short_name'
                                , 'branch.name as branch_name', 'users.maximum_tenure', 'users.photo', 'users.iss_no')
                        ->where('student_details.id', $studentId)->first();

        $data['student'] = $student;

        //Get my information
        $epeInfo = Epe::find($epeId);
        $data['selectedEpeInfo'] = $epeInfo;

        if ($media == 'print') {
            $printArr = compact('epeInfo', 'student', 'targetArr');
            return $printArr;
        }
        $returnHTML = view('studentwiseresult/show_result', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Marge arrays recursively and distinct
     * 
     * Merges any number of arrays / parameters recursively, replacing 
     * entries with string keys with values from latter arrays. 
     * If the entry or the next value to be assigned is an array, then it 
     * automagically treats both arguments as an array.
     * Numeric entries are appended, not replaced, but only if they are 
     * unique
     *
     * @param  array $array1 Initial array to merge.
     * @param  array ...     Variable list of arrays to recursively merge.
     *
     */
    public function arrayMergeRecursiveDistinct() {
        $arrays = func_get_args();
        /**
         * $arrays[0] is TAE full result array
         * $arrays[1] is EPE full result array
         */
        $base = !empty($arrays[0]) ? array_shift($arrays) : $arrays[1];

        if (!is_array($base))
            $base = empty($base) ? array() : array($base);

        foreach ($arrays as $append) {
            if (!is_array($append))
                $append = array($append);
            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) and ! is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (is_array($value) or is_array($base[$key])) {
                    $base[$key] = $this->arrayMergeRecursiveDistinct($base[$key], $append[$key]);
                } else if (is_numeric($key)) {
                    if (!in_array($value, $base))
                        $base[] = $value;
                }
                else {
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }

}
