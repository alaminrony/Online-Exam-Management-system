<?php

namespace App\Http\Controllers;

use Validator;
use App\UserGroup;
use App\User;
use Session;
use Redirect;
use Auth;
use File;
use Input;
use Illuminate\Http\Request;

class UserGroupController extends Controller {

    private $controller = 'UserGroup';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = UserGroup::orderBy('id', 'asc')->paginate(Session::get('paginatorCount'));
        return view('userGroup.index')->with(compact('targetArr', 'qpArr'));
    }

    public function edit(Request $request, $id) {
        $target = UserGroup::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('userGroup');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('userGroup.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = UserGroup::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter']; 
        //end back same page after update
        $rules = [
            'name' => 'required|unique:user_group,name,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect('userGroup/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->info = $request->info;

        if ($target->save()) {
            Session::flash('success', __('label.USER_GROUP_UPDATED_SUCCESSFULLY'));
            return redirect('userGroup' . $pageNumber);
        } else {
            Session::flash('error', __('label.USER_GROUP_COULD_NOT_BE_UPDATED'));
            return redirect('userGroup/' . $id . '/edit' . $pageNumber);
        }
    }
}
