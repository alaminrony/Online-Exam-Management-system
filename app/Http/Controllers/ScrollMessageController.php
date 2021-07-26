<?php

namespace App\Http\Controllers;

use Validator;
use App\Message;
use App\MessageScope;
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

class ScrollMessageController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Message::with('messagescope')->select('message.*')->orderBy('message.id', 'DESC')
                ->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/rank?page=' . $page);
        }

        return view('scrollmessage.index')->with(compact('targetArr', 'qpArr'));
    }

    public function create(Request $request) {
        $statusList = array('1' => 'Active', '0' => 'Inactive');
        return view('scrollmessage.create')->with(compact('statusList'));
    }

    public function store(Request $request) {
        // validate
        $rules = array(
            'message' => 'required',
            'status' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        );

        $message = array(
            'message.required' => 'Please give the message!',
            'status.required' => 'Status Must Be Selected!',
            'from_date.required' => 'Please give the From Date!',
            'to_date.required' => 'Please give To Date!',
        );
        $validator = Validator::make($request->all(), $rules, $message);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('scrollmessage/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        // store
        $scopeArr = $request->scope;
        if (empty($scopeArr)) {
            Session::flash('error', 'Message ' . __('label.AT_LEAST_ONE_SCOPE_MUST_BE_SELECTED'));
            return Redirect::to('scrollmessage/create');
        }

        $message = new Message;
        $message->message = $request->message;
        $message->status = $request->status;
        $message->from_date = $request->from_date;
        $message->to_date = $request->to_date;

        $saveFields = array();
        if ($message->save()) {
            $lastInsertId = $message->id;
            if ((!empty($scopeArr)) && (!empty($lastInsertId))) {
                $q = 1;
                foreach ($scopeArr as $scope) {
                    $saveFields[$q]['message_id'] = $lastInsertId;
                    $saveFields[$q]['scope_id'] = $scope;
                    $q++;
                }
            }
            if (MessageScope::insert($saveFields)) {
                Session::flash('success', 'Message ' . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
                return Redirect::to('scrollmessage');
            } else {
                Session::flash('error', 'Message ' . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
                return Redirect::to('scrollmessage/create');
            }
        } else {
            Session::flash('error', 'Message ' . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('scrollmessage/create');
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();

        $message = Message::find($id);
        $msgScopeArr = MessageScope::where('message_id', $id)->get();
        $selectedArr = array();
        if (!empty($msgScopeArr->toArray())) {
            foreach ($msgScopeArr as $msg) {
                $selectedArr[$msg['scope_id']] = $msg['scope_id'];
            }
        }
        $statusList = array('1' => 'Active', '0' => 'Inactive');
        // show the edit form and pass the supplier
        return view('scrollmessage.edit')->with(compact('message', 'statusList', 'selectedArr', 'qpArr'));
    }

    public function update(Request $request, $id) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        // validate
        $rules = array(
            'message' => 'required',
            'status' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        );

        $message = array(
            'message.required' => 'Please give the message!',
            'status.required' => 'Status Must Be Selected!',
            'from_date.required' => 'Please give the From Date!',
            'to_date.required' => 'Please give To Date!',
        );

        $validator = Validator::make($request->all(), $rules, $message);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('scrollmessage/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $scopeArr = $request->scope;
        if (empty($scopeArr)) {
            Session::flash('error', 'Message ' . __('label.AT_LEAST_ONE_SCOPE_MUST_BE_SELECTED'));
            return Redirect::to('scrollmessage/' . $id . '/edit' . $pageNumber);
        }
        // store

        $message = Message::find($id);
        $message->message = $request->message;
        $message->status = $request->status;
        $message->from_date = $request->from_date;
        $message->to_date = $request->to_date;

        if ($message->save()) {
            if ((!empty($scopeArr)) && (!empty($id))) {
                $q = 1;
                foreach ($scopeArr as $scope) {
                    $saveFields[$q]['message_id'] = $id;
                    $saveFields[$q]['scope_id'] = $scope;
                    $q++;
                }
            }

            MessageScope::where('message_id', $id)->delete();

            if (MessageScope::insert($saveFields)) {
                Session::flash('success', 'Message ' . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
                return Redirect::to('scrollmessage' . $pageNumber);
            } else {
                Session::flash('error', 'Message ' . __('label.COUD_NOT_BE_UPDATED'));
                return Redirect::to('scrollmessage/' . $id . '/edit' . $pageNumber);
            }
        } else {
            Session::flash('error', 'Message ' . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('scrollmessage/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        //begin back same page after delete
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after delete
        // delete supplier table
        $message = Message::find($id);
        if ($message->delete()) {
            $message->deleted_by = Auth::user()->id;
            $message->save();
            
            MessageScope::where('message_id', $id)->delete();
            Session::flash('success', 'Message ' . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', 'Message ' . __('label.COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('scrollmessage' . $pageNumber);
    }
}
?>