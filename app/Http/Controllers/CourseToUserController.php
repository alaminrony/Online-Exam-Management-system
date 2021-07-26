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

class CourseToUserController extends Controller {


    public function index() {

        $courseArr = array('0' => __('label.SELECT_COURSE_OPT')) + Course2::orderBy('order', 'asc')
                ->where('status', '1')->orderBy('order', 'asc')
                ->select(DB::raw("CONCAT(title, ' (', code, ')') as title "), 'id')->pluck('title', 'id');

        return view('coursetouser.index')
                        ->with(compact('courseArr'));
    }

    public function getCourseWiseUser() {

        $moduleArr = Module::join('course_to_module', 'course_to_module.module_id', '=', 'module.id')
                        ->where('course_to_module.course_id', $request->course_id)->orderBy('order', 'asc')
                        ->where('status', '1')
                        ->select('course_id', 'module_id', 'title')->get();

        $subjectPreArr = Subject2::join('module_to_subject', 'module_to_subject.subject_id', '=', 'subject2.id')
                        ->where('module_to_subject.course_id', $request->course_id)->orderBy('order', 'asc')
                        ->where('status', '1')
                        ->select('course_id', 'module_to_subject.module_id', 'subject_id', 'title', 'code')->get();


        $subjectArr = array();
        if (!empty($subjectPreArr)) {
            foreach ($subjectPreArr as $item) {
                $subjectArr[$item->module_id][$item->subject_id] = $item;
            }
        }

//        echo '<pre>';
//        //print_r($moduleArr);
//        print_r($subjectArr);
//        exit;
//        
//array('0' => __('label.SELECT_CI_OPT')) + 
        $ciArr = array('0' => __('label.SELECT_CI_OPT')) + User::join('rank', 'rank.id', '=', 'users.rank_id')
                        ->orderBy('first_name', 'asc')->where('group_id', '3')->where('program_id', '2')
                        ->where('users.status', 'active')->select('users.id', DB::raw('CONCAT(rank.short_name, \' \', first_name, \' \', last_name, \' (\', username, \')\') as full_name'))
                        ->pluck('full_name', 'id');

        $dsArr = array('0' => __('label.SELECT_DS_OPT')) + User::join('rank', 'rank.id', '=', 'users.rank_id')
                        ->orderBy('first_name', 'asc')->where('group_id', '4')->where('program_id', '2')
                        ->where('users.status', 'active')->select('users.id', DB::raw('CONCAT(rank.short_name, \' \', first_name, \' \', last_name, \' (\', username, \')\') as full_name'))
                        ->pluck('full_name', 'id');

        $existingCi = CourseToCi2::where('course_id', $request->course_id)->first();
        $ciId = empty($existingCi) ? null : $existingCi->user_id;

        $existingPreDs = CourseToDs2::where('course_id', $request->course_id)->get();

        $existingDs = array();
        if (!empty($existingPreDs)) {
            foreach ($existingPreDs as $item) {
                $existingDs[$item->module_id][$item->subject_id] = $item->user_id;
            }
        }

        return view('coursetouser.getCourseWiseUser')
                        ->with(compact('ciArr', 'dsArr', 'ciId', 'existingDs', 'moduleArr', 'subjectArr'));
    }

    public function save() {

        

        CourseToCi2::where('course_id', $request->course_id)->delete();

        if (!empty($request->ci_id)) {
            $ciArr = array();
            $ciArr['course_id'] = $request->course_id;
            $ciArr['user_id'] = $request->ci_id;
            $ciArr['created_by'] = Auth::user()->id;
            $ciArr['created_at'] = date('Y-m-d H:i:s');
            CourseToCi2::insert($ciArr);
        }

        //Delete old data by course
        CourseToDs2::where('course_id', Input::get('course_id'))->delete();

        if (!empty(Input::get('ds_id'))) {

            $dsArr = array();
            $i = 0;
            foreach (Input::get('ds_id') as $moduleId => $module) {
                if (!empty($module)) {
                    foreach ($module as $subjectId => $dsId) {

                        if (!empty($dsId)) {
                            $dsArr[$i]['course_id'] = Input::get('course_id');
                            $dsArr[$i]['module_id'] = $moduleId;
                            $dsArr[$i]['subject_id'] = $subjectId;
                            $dsArr[$i]['user_id'] = $dsId;
                            $dsArr[$i]['created_by'] = Auth::user()->id;
                            $dsArr[$i]['created_at'] = date('Y-m-d H:i:s');
                            $i++;
                        }
                    }
                }
            }

            //insert new data
            CourseToDs2::insert($dsArr);
        }

        Session::flash('success', __('label.CS_DS_HAS_BEEN_RELATED_WITH_COURSE_SUCCESSFULLY'));
        return Redirect::to('coursetouser');
    }

}
