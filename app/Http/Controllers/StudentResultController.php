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
use DateTime;
use Illuminate\Http\Request;

class StudentResultController extends Controller {

    public function showResult(Request $request) {

        //Get current student id from session
        $currentStudentId = Auth::user()->id;
         //echo $currentStudentId;exit;
        //Get Current date time
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        $targetArr = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                ->join('users', 'users.id', '=', 'epe_mark.employee_id')
                ->select(
                        'epe_mark.id', 'epe_mark.employee_id', 'epe.id as epe_id'
                        , 'epe.subject_id','epe.title as epe_title'
                        , 'epe_mark.pass', 'epe_mark.total_mark','epe_mark.converted_mark'
                        , 'epe_mark.objective_earned_mark'
                        , 'epe_mark.subjective_earned_mark',DB::raw('DATE_FORMAT(users.maximum_tenure, "%d.%m.%Y") as maximum_tenure')
                        
                )
                ->where('epe.type', '1')
                ->where('epe_mark.employee_id', $currentStudentId)
                ->where('epe.result_publish', '>=', DB::raw("'" . $currentDateTime . "'"))
                ->whereIn('epe_mark.pass', array(0,1, 2))
                ->get();

        $data['targetArr'] = $targetArr;


        //student information
        $studentInfoObjArr = User::join('rank', 'rank.id', '=', 'users.rank_id')
                        ->join('branch', 'branch.id', '=', 'users.branch_id')
                        ->join('appointment', 'appointment.id', '=', 'users.appointment_id')
                        ->select('users.*'
                                , 'rank.title as rank_name', 'appointment.title as appointment_name'
                                , 'branch.name as branch_name')
                        ->where('users.id', $currentStudentId)->first();
        $data['student'] = $studentInfoObjArr;
        $signatoryInfoObjArr = array();
        //Get signatory for this course
//        $signatoryInfoObjArr = Signatory::where('program_id', 1)->where('course_id', $studentInfoObjArr->course_id)->first();
        $data['signatoryInfoObjArr'] = $signatoryInfoObjArr;

        if ($request->type == 'print') {
            return view('studentresult.print', $data);
        }
        return view('studentresult.index', $data);
    }

    public function arrayMergeRecursiveDistinct() {
        $arrays = func_get_args();
        /**
         * $arrays[0] is TAE full result array
         * $arrays[1] is EPE full result array
         */
        $base = !empty($arrays[0]) ? array_shift($arrays) : $arrays[1];

        // $base = array_shift($arrays);
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

?>
