<?php
namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\PhaseToSubject;
use App\GradingSystem;
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

class GradingSystemController extends Controller {
    
    private $controller = 'GradingSystem';

    public function index() {
        $grades = GradingSystem::get();
        return view('report.gradingSystem.index',compact('grades'));
    }
    
}