<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\Region;
use App\Cluster;
use App\PasswordSetup;
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

class PasswordSetupController extends Controller {
    
    public function index(Request $request) {
        $qpArr = $request->all();
        $target = PasswordSetup::first();
        return view('passwordSetup.index')->with(compact('target'));
    }
    
    public function create(){
        $selectValue = ['1'=>__('label.YES'),'2'=>__('label.NO')];
        return view('passwordSetup.create')->with(compact('selectValue'));
    }
    
    public function store(Request $request){
        $target = new PasswordSetup;
        $target->maximum_length = $request->maximum_length;
        $target->minimum_length = $request->minimum_length;
        $target->special_character = $request->special_character;
        $target->lower_case = $request->lower_case;
        $target->upper_case = $request->upper_case;
        $target->expeired_of_password = $request->expeired_of_password;
        $target->space_not_allowed = $request->space_not_allowed;
        if ($target->save()) {
            Session::flash('success', __('label.PASSWORD_SETUP_CREATED_SUCESSFULLY'));
            return Redirect::to('passwordSetup');
        } else {
            Session::flash('error', __('label.PASSWORD_SETUP_NOT_CREATED'));
            return Redirect::to('passwordSetup/create');
        }
    }
    
    public function edit(Request $request){
//        Helper::dump($request->all());
        $selectValue = ['1'=>__('label.YES'),'2'=>__('label.NO')];
        $target = PasswordSetup::findOrFail($request->id);
        return view('passwordSetup.edit')->with(compact('target','selectValue'));
    }
    
    public function update(Request $request){
        $target = PasswordSetup::findOrFail($request->id);
        $target->maximum_length = $request->maximum_length;
        $target->minimum_length = $request->minimum_length;
        $target->special_character = $request->special_character;
        $target->lower_case = $request->lower_case;
        $target->upper_case = $request->upper_case;
        $target->expeired_of_password = $request->expeired_of_password;
        if ($target->save()) {
            Session::flash('success', __('label.PASSWORD_RULES_UPDATED_SUCESSFULLY'));
            return Redirect::to('passwordSetup');
        } else {
            Session::flash('error', __('label.PASSWORD_RULES_NOT_UPDATED'));
            return Redirect::to('passwordSetup/'.$target->id.'/edit');
        }
    }

}
