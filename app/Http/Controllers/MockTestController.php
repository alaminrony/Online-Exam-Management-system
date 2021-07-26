<?php

namespace App\Http\Controllers;

use Validator;
use App\MockTest;
use App\Epe;
use App\Question;
use App\MockToQuestion;
use App\MockMark;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Input;
use Response;
use Image;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MockTestController extends Controller {

    public function index(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();

        //Get Current date time
        $nowDateObj = Carbon::now();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');

        $currentStudentId = Auth::user()->id;
        $epeId = $request->epe_id;
        $searchText = $request->search_text;

        //Form Array for Tabular presentation of Mock Test List
        $targetArr = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->leftJoin('subject', 'subject.id', '=', 'epe.subject_id')
                ->leftJoin(DB::raw('(select mock_id, count(id) as attempt FROM mock_mark group by mock_id ) as tmp_mark'), function($join) {
                    $join->on('mock_test.id', '=', 'tmp_mark.mock_id');
                })
                ->select('mock_test.*', DB::raw('IFNULL(tmp_mark.attempt,0) as attempt')
                , 'epe.title as epe_title', 'subject.title as subject_title');

        if (Auth::user()->group_id == 4) { //For DS
            $targetArr = $targetArr->join('subject_to_ds', function($join) use ($currentStudentId) {
                $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
            });
        }


        if (!empty($epeId)) {
            $targetArr = $targetArr->where('mock_test.epe_id', '=', $epeId);
        }

        if (!empty($searchText)) {
            $targetArr = $targetArr->where(function ($query) use ($searchText) {
                $query->where('mock_test.title', 'like', '%' . DB::raw("$searchText") . '%')
                        ->orWhere('mock_test.duration_hours', 'like', '%' . DB::raw("$searchText") . '%')
                        ->orWhere('mock_test.duration_minutes', 'like', '%' . DB::raw("$searchText") . '%');
            });
        }

        $targetArr = $targetArr->orderBy('mock_test.start_at', 'DESC')
                ->orderBy('subject.title', 'ASC')->with(array('subject'))
                ->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/rank?page=' . $page);
        }

        //For an Array to display the EPE list as Drop-down at the top of the Mock Test Table
        $epeList = Epe::join('subject', 'subject.id', '=', 'epe.subject_id');
        if (Auth::user()->group_id == 4) {
            $epeList = $epeList->join('subject_to_ds', function($join) use ($currentStudentId) {
                        $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                        $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                    })
                    ->select('epe.id as id', DB::raw('CONCAT(`subject`.`title`, " &raquo; ", `epe`.`title`) AS `title`'))
                    ->where('epe.type', '1')
                    ->where('epe.status', 1)
                    ->orderBy('epe.exam_date', 'DESC')
                    ->orderBy('subject.title', 'DESC');
        } else {
            //$epeList = Epe::where('status', 1)->where('type', '1')->pluck('title', 'id'); 
            $epeList = $epeList->where('epe.type', 1)
                    ->where('epe.status', 1)
                    ->orderBy('epe.exam_date', 'DESC')
                    ->select(DB::raw('CONCAT(`epe`.`title`) AS `title`, epe.id'));
        }
        $epeList = array('' => __('label.SELECT_EPE_OPT')) + $epeList->pluck('title', 'id')->toArray();

        return view('mocktest.index')->with(compact('targetArr', 'qpArr', 'epeList'));
    }

    public function filter(Request $request) {
        $url = 'search_text=' . $request->search_text . '&epe_id=' . $request->epe_id;
        return Redirect::to('mock_test?' . $url);
    }

    public function create(Request $request) {
        $currentStudentId = Auth::user()->id;
        //Get epe list
        if (Auth::user()->group_id == 4) {
            $epeList = Epe::join('subject_to_ds', function($join) use ($currentStudentId) {
                                $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                                $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                            })
                            ->select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                            ->where('type', '1')
                            ->where('status', 1)
                            ->orderBy('epe.exam_date', 'DESC')
                            ->pluck('title', 'id')->toArray();
        } else {
            $epeList = Epe::select('epe.id as id', DB::raw("CONCAT(epe.title,' | Date: ', DATE_FORMAT(epe.exam_date, '%M %d, %Y')) AS title"))
                            ->where('status', 1)
                            ->where('type', '1')
                            ->orderBy('epe.exam_date', 'DESC')
                            ->pluck('title', 'id')->toArray();
        }
        $epeList = array('' => __('label.SELECT_EPE_OPT')) + $epeList;
        return view('mocktest.create')->with(compact('epeList'));
    }

    //This function use for Create Mock Test Show subject
    public function showSubject(Request $request) {

        $courseId = $request->course_id;
        $partId = $request->part_id;
        //Get subject list

        if (Auth::user()->group_id == 4) {
            $subjectList = DB::table('subject_to_ds')
                    ->join('subject', 'subject.id', '=', 'subject_to_ds.subject_id')
                    ->join('phase_to_subject', function($join) {
                        $join->on('phase_to_subject.course_id', '=', 'subject_to_ds.course_id');
                        $join->on('phase_to_subject.phase_id', '=', 'subject_to_ds.phase_id');
                        $join->on('phase_to_subject.subject_id', '=', 'subject_to_ds.subject_id');
                    })
                    ->leftJoin('branch', 'branch.id', '=', 'phase_to_subject.branch_id')
                    ->where('subject_to_ds.course_id', '=', $courseId)
                    ->where('subject_to_ds.part_id', '=', $partId)
                    ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                    ->select(
                            'subject.id as id ', 'phase_to_subject.subject_id', 'branch.name as branch_name', 'branch.short_name as branch_short_name', DB::raw("CONCAT(subject.title,' » ', subject.code, IF(branch.name IS null,'', ' » '), IFNULL(branch.name,'')) AS title")
                    )
                    ->orderBy('subject.order', 'ASC')
                    ->get();
        } else {
            $subjectList = DB::table('subject')
                    ->select('subject.id as id ', DB::raw("CONCAT(subject.title,' » ', subject.code) AS title"))
                    ->orderBy('subject.order', 'ASC')
                    ->get();
        }

        if (empty($subjectList)) {
            return Response::json(array('success' => false, 'heading' => 'Not Found!', 'message' => __('label.NO_SUBJECT_OF_THIS_PART_IS_NOT_ASSIGN')), 401);
        }
        return Response::json(array('success' => true, 'subjects' => $subjectList), 200);
    }

    //This function use for Mock Test information show
    public function showMockTestInfo(Request $request) {

        $epeId = $request->epe_id;

        //Get EPE data
        $taeInfoObjArr = Epe::join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('epe.status', 1)
                ->where('epe.id', $epeId)
                ->select('epe.*', 'subject.title as subject_title')
                ->first();
        if (empty($taeInfoObjArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.EMPTY_DATA'), 'message' => __('label.NO_TAE_IS_CREATED_YET')), 401);
        }//if TAE submission deadline not set

        $data['taeInfoObjArr'] = $taeInfoObjArr;

        //Get Avaiable Objective Question
        $objectiveQuestionCount = Question::where('subject_id', $taeInfoObjArr->subject_id)->where('status', '1')->whereIn('type_id', [1, 3, 5])->count();

        if (empty($objectiveQuestionCount)) {
            return Response::json(array('success' => false, 'heading' => __('label.EMPTY_DATA'), 'message' => __('label.NO_OBJECTIVE_QUESTION_AVAILABLE_AT_QUESTION_BANK')), 401);
        }//if objective question not avaiable

        $data['objectiveQuestionCount'] = $objectiveQuestionCount;

        $hoursArr = array();
        for ($h = 0; $h <= 23; $h++) {
            $hoursArr[$h] = (strlen($h) === 1) ? '0' . $h : $h;
        }
        $data['hoursList'] = $hoursArr;

        $minutesArr = array();
        for ($m = 0; $m <= 59; $m++) {
            $minutesArr[$m] = (strlen($m) === 1) ? '0' . $m : $m;
        }
        $data['minutesList'] = $minutesArr;

        $returnHTML = view('mocktest/show_mock_test_info', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeMockTest(Request $request) {
//         Helper::dump($request->all());
        $epeId = $request->epe_id;
        $mockTestId = $request->id;
        if (!empty($mockTestId)) {
            //For mock Update
            $mockTestArr = MockTest::find($mockTestId);
            if (empty($mockTestArr)) {
                return Response::json(array('success' => false, 'heading' => __('label.UNAUTHORIZED_ACCESS'), 'message' => __('label.SOMETHING_WENT_WRONG')), 401);
            }
        }



        $rules = array(
            'epe_id' => 'required',
            'title' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'obj_no_question' => 'required|numeric|digits_between:1,100'
        );

        $messages = array(
            'epe_id.required' => 'Exam must be selected!',
            'obj_no_question.required' => 'Total number of questions field is required'
        );
        //'result_publish.required' => 'Result publish date is required'
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if ((strtotime($request->start_at)) > (strtotime($request->end_at))) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.START_DATE_TIME_SHOULD_BE_SMALLER_THAN_END_DATE_TIME')), 401);
        }

        if (empty($request->duration_hours) && empty($request->duration_minutes)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.EXAM_DURATION_HAS_NOT_SET_FOR_THIS_MOCK_TEST')), 401);
        }

        //Mock Test question Greater-than/Less than check
        if ($request->obj_no_question > $request->total_objective_questions) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.NUMBER_OF_QUESTION_SHOULD_BE_SMALLER_THAN_TOTAL_QUESTIONS')), 401);
        }

        if (empty($mockTestId)) {
            $mockTestArr = new MockTest;
        }//only for create

        $mockTestArr->epe_id = $epeId;
        $mockTestArr->title = $request->title;
        $mockTestArr->duration_hours = $request->duration_hours;
        $mockTestArr->duration_minutes = $request->duration_minutes;
        $mockTestArr->start_at = $request->start_at;
        $mockTestArr->end_at = $request->end_at;
        $mockTestArr->obj_no_question = $request->obj_no_question;
        $mockTestArr->obj_no_mandatory = $request->obj_no_mandatory;
        $mockTestArr->obj_auto_selected = $request->obj_auto_selected;
        $mockTestArr->status = $request->status;

        if ($mockTestArr->save()) {

            //get EPE Info
            $epeInfo = Epe::find($request->epe_id);

            //if auto selected is set to 1; select questions of this subject 
            //randomly and save in mock_to_question table
            if ($mockTestArr->obj_auto_selected == '1') {

                //in case of edit, just delete the previous question set
                if (!empty($request->id)) {
                    MockToQuestion::where('mock_id', $request->id)->delete();
                }


                //we implement the following logic only in case the total number of 
                //question is > 8; else matching questions will not be included
                //matching question will get priority here and then other all type of question

                $noOfRestQuestion = $request->obj_no_question;
                $questionArrPre = Question::where('subject_id', $epeInfo->subject_id)
                        ->where('status', '1')
                        ->whereIn('type_id', [1, 3, 5])
                        ->orderBy(DB::raw('RAND()'))->limit($noOfRestQuestion)
                        ->pluck('id');

                $questionArr = array();
                if (!empty($questionArrPre)) {
                    $i = 0;
                    foreach ($questionArrPre as $k => $v) {
                        $questionArr[$i]['mock_id'] = $mockTestArr->id;
                        $questionArr[$i]['question_id'] = $v;
                        $i++;
                    }
                }

                //echo '<pre>';print_r($questionArr);exit;
                if (!empty($questionArr)) {
                    MockToQuestion::insert($questionArr);
                }
            }

            if (!empty($mockTestId)) {
                return Response::json(array('success' => TRUE, 'data' => $request->title . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY')), 200);
            } else {
                return Response::json(array('success' => TRUE, 'data' => $request->title . __('label.HAS_BEEN_CREATED_SUCESSFULLY')), 200);
            }
        } else {
            if (!empty($mockTestId)) {
                return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => $request->title . __('label.COUD_NOT_BE_UPDATED')), 401);
            } else {
                return Response::json(array('success' => false, 'heading' => 'Insertion Failed', 'message' => $request->title . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY')), 401);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id) {
        $currentStudentId = Auth::user()->id;
        // Get the EPE Information
        $mockTestObjArr = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                        ->join('subject', 'subject.id', '=', 'epe.subject_id')
                        ->select('mock_test.*', 'epe.subject_id', 'subject.title as subject_title')
                        ->where('mock_test.id', $id)->first();

        $data['mockTestObjArr'] = $mockTestObjArr;

        //Get Avaiable Objective Question
        $objectiveQuestionCount = Question::where('subject_id', $mockTestObjArr->subject_id)->where('status', '1')->whereIn('type_id', [1, 3, 5])->count();
        $data['objectiveQuestionCount'] = $objectiveQuestionCount;

        //Get EPE List
        if (Auth::user()->group_id == 4) {
            $epeList = Epe::join('subject_to_ds', function($join) use ($currentStudentId) {
                                $join->on('epe.course_id', '=', 'subject_to_ds.course_id');
                                $join->on('epe.part_id', '=', 'subject_to_ds.part_id');
                                $join->on('epe.phase_id', '=', 'subject_to_ds.phase_id');
                                $join->on('epe.subject_id', '=', 'subject_to_ds.subject_id');
                                $join->on('subject_to_ds.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                            })
                            ->select('epe.id as id', 'epe.title as title')
                            ->where('type', '1')
                            ->where('status', 1)->pluck('title', 'id');
        } else if (Auth::user()->group_id == 3) {
            $epeList = Epe::join('course_to_ci', function($join) use ($currentStudentId) {
                                $join->on('epe.course_id', '=', 'course_to_ci.course_id');
                                $join->on('course_to_ci.user_id', '=', DB::raw("'" . $currentStudentId . "'"));
                            })
                            ->select('epe.id as id', 'epe.title as title')
                            ->where('status', 1)->where('type', '1')->pluck('title', 'id');
        } else {
            $epeList = Epe::where('status', 1)->where('type', '1')->pluck('title', 'id')->toArray();
        }

        $data['epeList'] = array('' => __('label.SELECT_EPE_OPT')) + $epeList;

        $hoursArr = array();
        for ($h = 0; $h <= 23; $h++) {
            $hoursArr[$h] = (strlen($h) === 1) ? '0' . $h : $h;
        }
        $data['hoursList'] = $hoursArr;

        $minutesArr = array();
        for ($m = 0; $m <= 59; $m++) {
            $minutesArr[$m] = (strlen($m) === 1) ? '0' . $m : $m;
        }
        $data['minutesList'] = $minutesArr;
        // show the edit form and pass the usere
        return view('mocktest/edit', $data);
    }

    public function questionSet(Request $request, $id) {

        $mockTestInfo = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('mock_test.id', $id)
                ->select('epe.subject_id', 'subject.title as subject_title', 'mock_test.*')
                ->first();
        $data['mockTestInfo'] = $mockTestInfo;

        $questionArr = Question::join('question_type', 'question_type.id', '=', 'question.type_id', 'left')
                ->leftJoin('mock_to_question', function($join) use($id) {
                    $join->on('mock_to_question.question_id', '=', 'question.id');
                    $join->where('mock_to_question.mock_id', '=', $id);
                })->select('question.id', 'question_type.name', 'question.question', 'question.document', 'question.content_type_id', 'mock_to_question.mock_id')
                ->where('question.subject_id', $mockTestInfo->subject_id)
                ->where('question.status', '1')
                ->whereIn('question.type_id', [1, 3, 5])
                ->orderBy('mock_to_question.question_id', 'desc')
                ->orderBy('question.id', 'asc')
                ->get();


        //Find out the count how many question is already selected
        $alreadySelected = 0;
        if (!empty($questionArr)) {
            foreach ($questionArr as $question) {
                if (!empty($question->mock_id)) {
                    $alreadySelected++;
                }
            }
        }


        $data['questions'] = $questionArr;
        $data['alreadySelected'] = $alreadySelected;

        return view('mocktest.questionset', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {

        //check dependency
        $dependencyArr = ['MockMark' => 'mock_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();

            if (!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                return Redirect::to('epe');
            }
        }
        // delete EPE table
        $mockTest = MockTest::where('id', '=', $id)->first();
        DB::beginTransaction();
        try {
            //Delete data from MockToQuestion table
            $mockToQuestion = MockToQuestion::where('mock_id', '=', $id)->delete();

            //Delete mock from mock test table
            $mockTest->delete();
            $mockTest->deleted_by = Auth::user()->id;
            $mockTest->save();

            DB::commit();

            Session::flash('success', $mockTest->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('mock_test');
        } catch (Exception $ex) {
            DB::rollback();
            Session::flash('error', $mockTest->title . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('mock_test');
        }
    }

    public function updatedQuestionSet(Request $request) {

        $questionId = $request->question_id;
        $mockId = $request->mock_id;
        $setId = $request->set_id;
        $totalNoque = $request->total_noque;


        $data = array();
        if (!empty($questionId)) {
            $i = 0;
            foreach ($questionId as $item) {
                $data[$i]['mock_id'] = $mockId;
                $data[$i]['set_id'] = 1;
                $data[$i]['question_id'] = $item;
                $i++;
            }
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => __('label.YOU_HAVE_TO_SELECTED_AT_LEAST') . $totalNoque . ' questions'), 401);
        }
        if ($i < $totalNoque) {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => __('label.YOU_HAVE_TO_SELECTED_AT_LEAST') . $totalNoque . ' questions'), 401);
        }
        //delete existing data
        MockToQuestion::where('mock_id', $mockId)->delete();
        $questionSet = false;
        if (!empty($data)) {
            $questionSet = MockToQuestion::insert($data);
        }

        if ($questionSet) {
            return Response::json(array('success' => TRUE, 'data' => 'Question Set ' . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'heading' => 'Updation Failed', 'message' => 'Question Set ' . __('label.COUD_NOT_BE_UPDATED')), 401);
        }
    }

    public function questionDetails(Request $request) {
        $mockId = $request->mock_id;

        $data['mockTestInfo'] = MockTest::join('epe', 'epe.id', '=', 'mock_test.epe_id')
                ->join('subject', 'subject.id', '=', 'epe.subject_id')
                ->where('mock_test.id', $mockId)
                ->select('subject.title as subject_title', 'epe.title as epe_title', 'mock_test.*')
                ->first();

        //Finding Multiple Choice Single Answer question
        $questionType1 = MockToQuestion::join('question', 'question.id', '=', 'mock_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 1)
                        ->where('mock_id', $mockId)->get();

        //Finding true or false question
        $questionType5 = MockToQuestion::join('question', 'question.id', '=', 'mock_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 5)
                        ->where('mock_id', $mockId)->get();

        //Finding Filling the Blank question
        $questionType3 = MockToQuestion::join('question', 'question.id', '=', 'mock_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 3)
                        ->where('mock_id', $mockId)->get();

        //Finding Matching question
        $questionType6 = MockToQuestion::join('question', 'question.id', '=', 'mock_to_question.question_id')
                        ->select('question.question', 'question.document', 'question.opt_1', 'question.opt_2', 'question.opt_3', 'question.opt_4', 'question.mcq_answer', 'question.ftb_answer', 'question.tf_answer', 'question.type_id', 'question.match_answer')
                        ->orderBy('question.type_id')
                        ->where('question.type_id', 6)
                        ->where('mock_id', $mockId)->get();

        $data['objective'] = $questionType1;
        $data['trueFalse'] = $questionType5;
        $data['fillingBlank'] = $questionType3;
        $data['matchingArr'] = $questionType6;
        $returnHTML = view('mocktest/question', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

}
