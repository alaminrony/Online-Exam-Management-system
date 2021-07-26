<?php

namespace App\Http\Controllers;

use Validator;
use App\Rank;
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

class RankController extends Controller {
        private $controller = 'Rank';
    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = Rank::orderBy('order')->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/rank?page=' . $page);
        }
        return view('rank.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('rank.create')->with(compact('qpArr','orderList'));
    }

    public function store(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';

        $rules = [
            'title' => 'required|Unique:rank',
            'short_name' => 'required',
            'order' => 'required|not_in:0'
        ];

        $message = array(
            'title.required' => 'Please give the rank title!',
            'short_name.required' => 'Please give the short name!',
            'order.required' => 'Please give the rank order',
            'title.unique' => 'That title is already taken',
        );

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Redirect::to('rank/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $rank = new Rank;
        $rank->title = $request->title;
        $rank->short_name = $request->short_name;
        $rank->order = $request->order;
        $rank->status = $request->status;
        if ($rank->save()) {
            Helper :: insertOrder($this->controller, $request->order, $rank->id);
            Session::flash('success', $request->title . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('rank');
        } else {
            Session::flash('error', $request->title . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('rank/create' . $pageNumber);
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
        $rank = Rank::find($id);
        // show the edit form and pass the supplier
        return view('rank.edit')->with(compact('rank', 'qpArr','orderList'));
    }

    public function update(Request $request, $id) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        // validate
        $rules = [
            'title' => 'required|Unique:rank,title,' . $id,
            'short_name' => 'required',
            'order' => 'required|not_in:0'
        ];

        $message = array(
            'title.required' => 'Please give the rank title!',
            'short_name.required' => 'Please give the short name!',
            'order.required' => 'Please give the rank order',
            'title.unique' => 'That title is already taken',
        );

        $validator = Validator::make($request->all(), $rules, $message);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('rank/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        // store
        $rank = Rank::find($id);
        $presentOrder = $rank->order;
        $rank->title = $request->title;
        $rank->short_name = $request->short_name;
        $rank->order = $request->order;
        $rank->status = $request->status;
        if ($rank->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $rank->id, $presentOrder);
            }
            Session::flash('success', $request->title . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('rank' . $pageNumber);
        } else {
            Session::flash('error', $request->title . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('rank/' . $id . '/edit' . $pageNumber);
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
//        $hasRelationUser = User::where('rank_id', $id)->first();
//
//        if (!empty($hasRelationUser)) {
//            Session::flash('error', __('label.RANK_HAS_RELATIONSHIP_WITH_USER'));
//            return Redirect::to('rank'.$pageNumber);
//        }
        
         //check dependency
        $dependencyArr = ['User' => 'rank_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();
            
            if(!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                 return Redirect::to('rank'.$pageNumber);
            }
        }

        // delete supplier table
        $rank = Rank::find($id);

        if ($rank->delete()) {
            $rank->deleted_by = Auth::user()->id;
            $rank->save();
            
            Helper :: deleteOrder($this->controller, $rank->order);
            Session::flash('success', $rank->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', $rank->title . __('label.COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('rank'.$pageNumber);
    }

}
