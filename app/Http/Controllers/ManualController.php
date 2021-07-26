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

class ManualController extends Controller {

    public function __construct() {
        $this->beforeFilter('ciAdmin', array('except' => array('index')));
    }

    public function index() {

        if (Session::get('program_id') == 1) {
            $manualArr = array('1' => 'Admin', '2' => 'OC', '3' => 'CI-ISSP', '4' => 'DS-ISSP', '5' => 'Student-ISSP', '6' => 'Admin-Assistant');
        } else if (Session::get('program_id') == 2) {
            $manualArr = array('1' => 'Admin', '2' => 'OC', '3' => 'CI-JCSC', '4' => 'DS-JCSC', '5' => 'Student-JCSC', '6' => 'Admin-Assistant');
        }
        
        $file = 'public/pdf/manual/' .$manualArr[Auth::user()->group_id].'-Manual-for-HORIZON-CSTI-BAF-V.1.2.pdf';
        
        if (file_exists($file)) {
            $content = file_get_contents($file);
            return Response::make($content, 200, array('content-type' => 'application/pdf'));
        } else {
            return Response::view('error');
        }
        
    }

}
