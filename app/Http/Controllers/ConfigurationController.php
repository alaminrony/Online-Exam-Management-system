<?php
namespace App\Http\Controllers;

use Validator;
use App\Configuration;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Input;
use Illuminate\Http\Request;

class ConfigurationController extends Controller {
    
    public function index(Request $request) {
        $targetArr = Configuration::first();
        return view('configuration.index')->with(compact('targetArr'));
    }
    
    public function edit(Request $request,$id) {
        $configuration = Configuration::find($id);
        return view('configuration.edit')->with(compact('configuration'));
    }

    public function update(Request $request,$id) {
       
        // validate
        $rules = array(
            'admin_email' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('configuration/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }
           
        // store
        $configuration = Configuration::find($id);
        
        $configuration->admin_email = $request->admin_email;
        $configuration->highlights_message = $request->highlights_message;
        $configuration->about_us = $request->about_us;
        $configuration->history = $request->history;
        
        if ($configuration->save()) {
            Session::flash('success', 'Configuration ' . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('configuration');
        } else {
            Session::flash('error', $request->admin_email . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('configuration/' . $id . '/edit');
        }
        
    }
    
}
?>