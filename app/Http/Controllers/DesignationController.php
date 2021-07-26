<?php

namespace App\Http\Controllers;

use Validator;
use App\Designation;
use App\User;
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

class DesignationController extends Controller {

    private $controller = 'Designation';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = Designation::orderBy('order')->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/designation?page=' . $page);
        }
        return view('designation.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('designation.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';

        $rules = [
            'title' => 'required|Unique:designation',
            'short_name' => 'required',
            'order' => 'required|not_in:0'
        ];

        $message = array(
            'title.required' => 'Please give the designation title!',
            'short_name.required' => 'Please give the designation short name!',
            'order.required' => 'Please give the designation order',
            'order.not_in' => 'Please give the order grater then zero',
            'title.unique' => 'That title is already taken',
        );

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Redirect::to('designation/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $appointment = new Designation;
        $appointment->title = $request->title;
        $appointment->short_name = $request->short_name;
        $appointment->order = $request->order;
        $appointment->status = $request->status;
        if ($appointment->save()) {
            Helper :: insertOrder($this->controller, $request->order, $appointment->id);
            Session::flash('success', $request->title . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('designation/');
        } else {
            Session::flash('error', $request->title . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('designation/create');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        $appointment = Designation::find($id);
        return view('designation.edit')->with(compact('appointment', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        // validate
        $rules = [
            'title' => 'required',
            'short_name' => 'required',
            'order' => 'required|not_in:0'
        ];

        $message = array(
            'title.required' => 'Please give the designation title!',
            'short_name.required' => 'Please give the designation short name!',
            'order.required' => 'Please give the designation order',
            'order.not_in' => 'Please give the order grater then zero',
            'title.unique' => 'That title is already taken',
        );

        $validator = Validator::make($request->all(), $rules, $message);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('designation/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        // store
        $appointment = Designation::find($id);
        $presentOrder = $appointment->order;
        $appointment->title = $request->title;
        $appointment->short_name = $request->short_name;
        $appointment->order = $request->order;
        $appointment->status = $request->status;
        if ($appointment->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $appointment->id, $presentOrder);
            }
            Session::flash('success', $request->title . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('designation' . $pageNumber);
        } else {
            Session::flash('error', $request->title . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('designation/' . $id . '/edit' . $pageNumber);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
        //begin back same page after delete
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after delete
        //check dependency
//        $hasRelationUser = User::where('designation_id', $id)->first();
//        if (!empty($hasRelationUser)) {
//            Session::flash('error', __('label.APPOINTMENT_HAS_RELATIONSHIP_WITH_USER'));
//            return Redirect::to('designation' . $pageNumber);
//        }
        //check dependency
        $dependencyArr = ['User' => 'designation_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();

            if (!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                return Redirect::to('designation' . $pageNumber);
            }
        }

        // delete supplier table
        $target = Designation::findOrFail($id);

        if ($target->delete()) {
            $target->deleted_by = Auth::user()->id;
            $target->save();

            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('success', $target->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', $target->title . __('label.COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('designation' . $pageNumber);
    }

}

?>