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

class EresourcesController extends Controller {

    public function index() {
        
       if(Session::get('program_id') == '1'){
        
            //Following requirement is very funny, but it has to be implemented as per
            //the requirement of client, !!!!!!!!!!!!!!!!
            // at time of examination running the student will not be able to enter this
            // page (during  the whole time of s)
            // , so we show them a no resource availeble page        

            $studentHasExam = false;
            if (Auth::user()->group_id == '5') {

                $sql = 'select id from epe_mark where student_id = ' . Auth::user()->studentBasicInfo->id . ''
                        . ' and "' . date('Y-m-d H:i:s') . '" between CONCAT(exam_date, \' \', objective_start_time) '
                        . 'and CONCAT(exam_date, \' \', subjective_end_time) limit 1';

                $studentHasExam = DB::select($sql);
            }

            return view('eresources.issp')->with(compact('studentHasExam'));
            
       }else if(Session::get('program_id') == '2'){
           
           return view('eresources.jcsc');
           
       }
    }

}
