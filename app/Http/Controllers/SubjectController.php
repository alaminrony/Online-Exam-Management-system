<?php

namespace App\Http\Controllers;

use Validator;
use App\Subject;
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

class SubjectController extends Controller {

    private $controller = 'Subject';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $searchText = $request->search_text;

        $targetArr = Subject::orderBy('order')->orderBy('title');

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('title', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchText . '%');
            });
        }
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/rank?page=' . $page);
        }
        
//        Helper::dump($targetArr);

        // load the view and pass the rank index
        return view('subject.index')->with(compact('targetArr', 'qpArr'));
    }

    public function filter(Request $request) {
        $url = ' search_text=' . $request->search_text;
        return Redirect::to('subject?' . $url);
    }

    public function create(Request $request) {
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('subject.create')->with(compact('orderList'));
    }

    public function store(Request $request) {
        $rules = array(
            'title' => 'required|Unique:subject',
            'code' => 'required',
            'order' => 'required|not_in:0'
        );

        $message = array(
            'title.required' => 'Please give the subject title!',
            'code.required' => 'Please give the subject code!',
            'title.unique' => 'That subject is already taken'
        );

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Redirect::to('subject/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $subject = new Subject;
        $subject->title = $request->title;
        $subject->code = $request->code;
        $subject->order = $request->order;
        $subject->details = !empty($request->details) ? $request->details : null;
        $subject->status = $request->status;
        if ($subject->save()) {
            Helper :: insertOrder($this->controller, $request->order, $subject->id);
            Session::flash('success', $request->title . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('subject');
        } else {
            Session::flash('error', $request->title . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('subject/create');
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
        $subject = Subject::find($id);
        return view('subject.edit')->with(compact('subject', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        // validate
        $rules = array(
            'title' => 'required|Unique:subject,title,' . $id,
            'code' => 'required',
            'order' => 'required|not_in:0'
        );

        $message = array(
            'title.required' => 'Please give the subject title!',
            'code.required' => 'Please give the subject code!',
            'title.unique' => 'That subject is already taken',
        );

        $validator = Validator::make($request->all(), $rules, $message);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('subject/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        // store
        $subject = Subject::find($id);
        $presentOrder = $subject->order;
        $subject->title = $request->title;
        $subject->code = $request->code;
        $subject->order = $request->order;
        $subject->details = !empty($request->details) ? $request->details : null;
        $subject->status = $request->status;
        if ($subject->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $subject->id, $presentOrder);
            }
            Session::flash('success', $request->title . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('subject' . $pageNumber);
        } else {
            Session::flash('error', $request->title . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('subject/' . $id . '/edit' . $pageNumber);
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
//        $hasRelationPhase = PhaseToSubject::where('subject_id', $id)->first();
//
//        if (!empty($hasRelationPhase)) {
//            Session::flash('error', __('label.SUBJECT_HAS_RELATIONSHIP_WITH_PHASE'));
//            return Redirect::to('subject');
//        }
//
//        $hasRelationDs = SubjectToDs::where('subject_id', $id)->first();
//
//        if (!empty($hasRelationDs)) {
//            Session::flash('error', __('label.SUBJECT_HAS_RELATIONSHIP_WITH_DS'));
//            return Redirect::to('course');
//        }
//
//        $hasRelationTae = TAE::where('subject_id', $id)->first();
//
//        if (!empty($hasRelationTae)) {
//            Session::flash('error', __('label.SUBJECT_HAS_RELATIONSHIP_WITH_TAE'));
//            return Redirect::to('subject');
//        }
        
           //check dependency
        $dependencyArr = ['Epe'=>'subject_id','Question'=>'subject_id','SubjectToDs' => 'subject_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();
            
            if(!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                 return Redirect::to('subject'.$pageNumber);
            }
        }

        // delete subject table
        $subjectObj = Subject::find($id);

        if ($subjectObj->delete()) {
            $subjectObj->deleted_by = Auth::user()->id;
            $subjectObj->save();
            
            Helper :: deleteOrder($this->controller, $subjectObj->order);
            Session::flash('success', $subjectObj->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', $subjectObj->title . __('label.COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('subject' . $pageNumber);
    }

}

?>