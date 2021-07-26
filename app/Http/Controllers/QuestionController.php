<?php

namespace App\Http\Controllers;

use Validator;
use App\Subject;
use App\QuestionType;
use App\Question;
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
use Excel;
use App\Exports\ExcelExport;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class QuestionController extends Controller {

    public function index(Request $request) {
//        Helper::dump($request->all());
        //passing param for custom function
        $qpArr = $request->all();

        $subjectList = array('0' => __('label.SELECT_SUBJECT_OPT')) + Subject::orderBy('order', 'asc')
                        ->select(DB::raw("CONCAT(title, ' (',code, ')') as title"), 'id')->pluck('title', 'id')->toArray();

        $typeList = array('0' => __('label.SELECT_QUESTION_TYPE_OPT')) + QuestionType::orderBy('id', 'asc')
                        ->whereIn('id', array(1, 3, 4, 5, 6))->pluck('name', 'id')->toArray();
        
        $statusList = ['' => __('label.SELECT_STATUS_OPT'), '1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')];

        $targetArr = Question::with('QuestionType', 'Subject')->orderBy('id', 'desc');

        $searchText = $request->search_text;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('question', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('note', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->fill_type_id)) {
            $targetArr = $targetArr->where('type_id', $request->fill_type_id);
        }

        if (!empty($request->fill_subject_id)) {
            $targetArr = $targetArr->where('subject_id', $request->fill_subject_id);
        }

        if ($request->fill_status != '') {
            $targetArr = $targetArr->where('status', $request->fill_status);
        }
        if ($request->view == 'excel') {
            $targetArr = $targetArr->get();
            $viewFile = 'question.print.question';
            $downloadFileName = 'question-'.date('d-m-Y').'.xlsx';
            $data['targetArr'] = $targetArr;
            $data['typeList'] = $typeList;
            $data['subjectList'] = $subjectList;
            $data['request'] = $request;
            $data['statusList'] = $statusList;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        return view('question.index')->with(compact('targetArr', 'typeList', 'subjectList', 'qpArr','statusList'));
    }

    public function filter(Request $request) {
        $url = 'search_text=' . $request->search_text . '&fill_type_id=' . $request->type_id . '&fill_subject_id=' . $request->subject_id . '&fill_status=' . $request->status;
        return Redirect::to('question?' . $url);
    }

    public function create(Request $request) {
        $contentTypeList = ['0' => __('label.SELECT_CONTENT_TYPE'), '0' => __('label.NONE'), '1' => __('label.IMAGE'), '2' => __('label.AUDIO'), '3' => __('label.VIDEO'), '4' => __('label.PDF')];
        $subjectArr = array('0' => __('label.SELECT_SUBJECT_OPT')) + Subject::orderBy('order', 'asc')
                        ->select(DB::raw("CONCAT(title, ' (',code, ')') as title"), 'id')->pluck('title', 'id')->toArray();
        $typeArr = array('0' => __('label.SELECT_QUESTION_TYPE_OPT')) + QuestionType::orderBy('id', 'asc')
                        ->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id')->toArray();
        return view('question.create')->with(compact('typeArr', 'subjectArr', 'contentTypeList'));
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        
        $rules = array(
            'subject_id' => 'required|numeric|not_in:0',
            'type_id' => 'required|numeric|not_in:0',
            'question' => 'required',
        );

        if ($request->type_id == '1') {
            $rules['opt_1'] = 'required';
            $rules['opt_2'] = 'required';
            $rules['opt_3'] = 'required';
            $rules['opt_4'] = 'required';
            $rules['mcq_answer'] = 'required|numeric';
        } else if ($request->type_id == '3') {
            $rules['ftb_answer'] = 'required';
        } else if ($request->type_id == '5') {
            $rules['tf_answer'] = 'required';
        }

        if (!empty($request->image)) {
            if ($request->file('image')) {
                $rules['image'] = 'max:2048|mimes:jpeg,png,gif,jpg';
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('question/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        #############  File Upload :: START #######################
        if ($request->content_type_id == '1') {
            if ($file = $request->file('image')) {
                $filePath = 'public/uploads/questionBank/image/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        } else if ($request->content_type_id == '2') {
            if ($file = $request->file('audio')) {
                $filePath = 'public/uploads/questionBank/audio/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        } else if ($request->content_type_id == '3') {
            if ($file = $request->file('video')) {
                $filePath = 'public/uploads/questionBank/video/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        } else if ($request->content_type_id == '4') {
            if ($file = $request->file('pdf')) {
                $filePath = 'public/uploads/questionBank/pdf/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        }
        ############# File Upload :: END #######################

        $question = new Question;
        $question->subject_id = $request->subject_id;
        $question->type_id = $request->type_id;
        $question->question = $request->question;
        $question->note = $request->note;
        $question->status = $request->status;
        $question->document = !empty($fileName) ? $fileName : '';
        $question->content_type_id = !empty($request->content_type_id) ? $request->content_type_id : '';

        if ($request->type_id == '1') {
            $question->opt_1 = $request->opt_1;
            $question->opt_2 = $request->opt_2;
            $question->opt_3 = $request->opt_3;
            $question->opt_4 = $request->opt_4;
            $question->mcq_answer = $request->mcq_answer;
        } else if ($request->type_id == '3') {
            $question->ftb_answer = $request->ftb_answer;
        } else if ($request->type_id == '5') {
            $question->tf_answer = $request->tf_answer;
        }
        
        $url = 'subject_id='.$request->subject_id.'&type_id='.$request->type_id;
        if ($question->save()) {
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $question->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 4;
            $action = "Create";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['question_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            Session::flash('success', __('label.QUESTION_CREATED_SUCESSFULLY'));
            return Redirect::to('question/create?'.$url);
        } else {
            Session::flash('error', __('label.QUESTION_COULD_NOT_BE_CREATED'));
            return Redirect::to('question/create');
        }
    }
    
    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();

        $target = Question::find($id);
        $subjectArr = array('0' => __('label.SELECT_SUBJECT_OPT')) + Subject::orderBy('order', 'asc')
                        ->select(DB::raw("CONCAT(title, ' (',code, ')') as title"), 'id')->pluck('title', 'id')->toArray();

        $typeArr = array('0' => __('label.SELECT_QUESTION_TYPE_OPT')) + QuestionType::orderBy('id', 'asc')
                        ->whereIn('id', array(1, 3, 4, 5))->pluck('name', 'id')->toArray();
        $contentTypeList = ['0' => __('label.SELECT_CONTENT_TYPE'), '0' => __('label.NONE'), '1' => __('label.IMAGE'), '2' => __('label.AUDIO'), '3' => __('label.VIDEO'), '4' => __('label.PDF')];

        $typeId = $target->type_id;
        return view('question.edit')->with(compact('target', 'typeArr', 'subjectArr', 'typeId', 'qpArr', 'contentTypeList'));
    }

    public function update(Request $request, $id) {
//        Helper::dump($request->id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $question = Question::find($id);
        $rules = array(
            'subject_id' => 'required|numeric|not_in:0',
            'type_id' => 'required|numeric|not_in:0',
            'question' => 'required',
        );

        if ($request->type_id == '1') {
            $rules['opt_1'] = 'required';
            $rules['opt_2'] = 'required';
            $rules['opt_3'] = 'required';
            $rules['opt_4'] = 'required';
            $rules['mcq_answer'] = 'required|numeric';
        } else if ($request->type_id == '3') {
            $rules['ftb_answer'] = 'required';
        } else if ($request->type_id == '5') {
            $rules['tf_answer'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $typeId = $request->type_id;
            return Redirect::to('question/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all()); //->with(compact('type_id'));
        }

        //Delete Previous file if new content type not match in previous content type.
        if ($request->file('image') !== '' || $request->file('audio') !== '' || $request->file('video') !== '' || $request->file('pdf') !== '') {
            if ($question->content_type_id != $request->content_type_id) {
                if ($question->content_type_id == '1') {
                    $prevfileName = 'public/uploads/questionBank/image/' . $question->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                } elseif ($question->content_type_id == '2') {
                    $prevfileName = 'public/uploads/questionBank/audio/' . $question->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                } elseif ($question->content_type_id == '3') {
                    $prevfileName = 'public/uploads/questionBank/video/' . $question->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                } elseif ($question->content_type_id == '4') {
                    $prevfileName = 'public/uploads/questionBank/pdf/' . $question->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                }
            }
        }

        ############# Image File :: START #######################
        if ($request->content_type_id == '1') {
            if ($file = $request->file('image')) {
                $prevfileName = 'public/uploads/questionBank/image/' . $question->document;
                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
                $filePath = 'public/uploads/questionBank/image/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        } else if ($request->content_type_id == '2') {
            if ($file = $request->file('audio')) {
                $prevfileName = 'public/uploads/questionBank/audio/' . $question->document;
                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
                $filePath = 'public/uploads/questionBank/audio/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        } else if ($request->content_type_id == '3') {
            if ($file = $request->file('video')) {
                $prevfileName = 'public/uploads/questionBank/video/' . $question->document;
                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
                $filePath = 'public/uploads/questionBank/video/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        } else if ($request->content_type_id == '4') {
            if ($file = $request->file('pdf')) {
                $prevfileName = 'public/uploads/questionBank/pdf/' . $question->document;
                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
                $filePath = 'public/uploads/questionBank/pdf/';
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
                $file->move($filePath, $fileName);
            }
        }


        $question->subject_id = $request->subject_id;
        $question->type_id = $request->type_id;
        $question->question = $request->question;
        $question->note = $request->note;
        $question->status = $request->status;
        $question->document = !empty($fileName) ? $fileName : $question->document;
        $question->content_type_id = !empty($request->content_type_id) ? $request->content_type_id : '';

        //now override data for appropriate type
        if ($request->type_id == '1') {
            $question->opt_1 = $request->opt_1;
            $question->opt_2 = $request->opt_2;
            $question->opt_3 = $request->opt_3;
            $question->opt_4 = $request->opt_4;
            $question->mcq_answer = $request->mcq_answer;
        } else if ($request->type_id == '3') {
            $question->ftb_answer = $request->ftb_answer;
        } else if ($request->type_id == '5') {
            $question->tf_answer = $request->tf_answer;
        }

        if ($question->save()) {
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 4;
            $action = "update";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['question_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            Session::flash('success', __('label.QUESTION_UPDATED_SUCCESSFULLY'));
            return Redirect::to('question' . $pageNumber);
        } else {
            Session::flash('error', __('label.QUESTION_COULD_NOT_BE_UPDATED'));
            return Redirect::to('question/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        
         //check dependency
            $dependencyArr = ['EpeMarkDetails' => 'question_id'];
            foreach ($dependencyArr as $model => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();

                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return Redirect::to('question');
                }
            }
            
        $target = Question::find($id);
        if ($target->delete()) {
            $target->deleted_by = Auth::user()->id;
            $target->save();
                    
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 4;
            $action = "Delete";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['question_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            if (!empty($target->document)) {
                if ($target->content_type_id == '1') {
                    $prevfileName = 'public/uploads/questionBank/image/' . $target->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                } elseif ($target->content_type_id == '2') {
                    $prevfileName = 'public/uploads/questionBank/audio/' . $target->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                } elseif ($target->content_type_id == '3') {
                    $prevfileName = 'public/uploads/questionBank/video/' . $target->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                } elseif ($target->content_type_id == '4') {
                    $prevfileName = 'public/uploads/questionBank/pdf/' . $target->document;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                }
            }

            Session::flash('success', __('label.QUESTION_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.QUESTION_COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('question');
    }

    public function getImage(Request $request, $fileName = null) {
        return '<img src="' . URL::to('public/uploads/questionBank/' . $fileName) . '" />';
    }

}
