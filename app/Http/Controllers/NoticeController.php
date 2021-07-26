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

class NoticeController extends Controller {

    public function __construct() {
        $this->beforeFilter('ciAdmin', array('except' => array('index')));
    }

    public function index() {
        $courseId = Input::get('course_id');
        $searchText = Input::get('search_text');
        
        $nowDateObj = new DateTime();
        $currentDate = $nowDateObj->format('Y-m-d');
        if(Session::get('program_id') == 1){
           
            if(Auth::user()->group_id == '5'){
                $noticeArr = Notice::join('course', 'course.id', '=', 'notice.course_id')
                        ->select('notice.id', 'notice.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course.title as course_name')
                        ->where('notice.program_id', Auth::user()->program_id)
                        ->where('notice.course_id', Auth::user()->studentBasicInfo->course_id)
                        ->where('notice.published_date','<=',DB::raw("'".$currentDate."'"))
                        ->where('notice.closing_date','>=',DB::raw("'".$currentDate."'"));

            }elseif(Auth::user()->group_id == '4'){
                $noticeArr = Notice::join('course', 'course.id', '=', 'notice.course_id')
                            ->join(DB::raw('(SELECT distinct subject_to_ds.course_id, subject_to_ds.user_id FROM `subject_to_ds`) tamp_ds'), function($join)
                            {
                                $join->on('notice.course_id', '=', 'tamp_ds.course_id');
                            })
                            ->select('notice.id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course.title as course_name', 'notice.course_id', 'tamp_ds.user_id')
                            ->where('tamp_ds.user_id', '=', DB::raw("'".Auth::user()->id."'"))
                            ->where('notice.program_id', '=', DB::raw("'1'"))
                            ->where('notice.published_date','<=',DB::raw("'".$currentDate."'"))
                            ->where('notice.closing_date','>=',DB::raw("'".$currentDate."'"));
                                 
             } elseif (Auth::user()->group_id == '3') {
                $noticeArr = Notice::join('course', 'course.id', '=', 'notice.course_id')
                                 ->join(DB::raw('(SELECT distinct course_to_ci.course_id, course_to_ci.user_id FROM `course_to_ci`) tamp_ci'), function($join)
                                {
                                    $join->on('notice.course_id', '=', 'tamp_ci.course_id');
                                })
                                ->select('notice.id', 'tamp_ci.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course.title as course_name')
                                ->where('tamp_ci.user_id', '=', DB::raw("'".Auth::user()->id."'"))
                                ->where('notice.program_id','=', DB::raw("'1'"))
                                ->where('notice.published_date','<=',DB::raw("'".$currentDate."'"))
                                ->where('notice.closing_date','>=',DB::raw("'".$currentDate."'"));
             } else {
                $noticeArr = Notice::join('course', 'course.id', '=', 'notice.course_id')
                            ->select('notice.id', 'notice.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course.title as course_name')
                            ->where('program_id', 1);
            }
        }else{
            if(Auth::user()->group_id == 5){
                $noticeArr = Notice::join('course2', 'course2.id', '=', 'notice.course_id')
                        ->select('notice.id', 'notice.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course2.title as course_name')
                        ->where('notice.program_id', Auth::user()->program_id)
                        ->where('notice.course_id', Auth::user()->studentBasicInfo->course_id)
                        ->where('notice.published_date','<=',DB::raw("'".$currentDate."'"))
                        ->where('notice.closing_date','>=',DB::raw("'".$currentDate."'"));
                        

            }else if(Auth::user()->group_id == '4'){
                $noticeArr = Notice::join('course2', 'course2.id', '=', 'notice.course_id')
                                ->join(DB::raw('(SELECT distinct course_to_ds2.course_id, course_to_ds2.user_id FROM `course_to_ds2`) tamp_ds2'), function($join)
                                {
                                    $join->on('notice.course_id', '=', 'tamp_ds2.course_id');
                                })
                                ->select('notice.id', 'notice.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course2.title as course_name')
                                ->where('tamp_ds2.user_id', '=', Auth::user()->id)
                                ->where('notice.program_id', 2)
                                ->where('notice.published_date','<=',DB::raw("'".$currentDate."'"))
                                ->where('notice.closing_date','>=',DB::raw("'".$currentDate."'"));
             } else if (Auth::user()->group_id == '3') {
                $noticeArr = Notice::join('course2', 'course2.id', '=', 'notice.course_id')
                                 ->join(DB::raw('(SELECT distinct course_to_ci2.course_id, course_to_ci2.user_id FROM `course_to_ci2`) tamp_ci2'), function($join)
                                {
                                    $join->on('notice.course_id', '=', 'tamp_ci2.course_id');
                                })
                                ->select('notice.id', 'notice.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course2.title as course_name')
                                ->where('tamp_ci2.user_id', '=', Auth::user()->id)
                                ->where('notice.program_id', 2)
                                ->where('notice.published_date','<=',DB::raw("'".$currentDate."'"))
                                ->where('notice.closing_date','>=',DB::raw("'".$currentDate."'"));
             } else {
                $noticeArr = Notice::join('course2', 'course2.id', '=', 'notice.course_id')
                             ->select('notice.id', 'notice.course_id', 'notice.title', 'notice.short_info', 'notice.description', 'notice.fileInfo', 'notice.published_date', 'notice.closing_date', 'notice.status', 'notice.created_at', 'course2.title as course_name')
                             ->where('program_id', 2);
                
                
            }
        }
        
        if (!empty($courseId)) {
            $noticeArr = $noticeArr->where('notice.course_id', '=', $courseId);
        }

        if (!empty($searchText)) {
            $noticeArr = $noticeArr->where(function ($query) use ($searchText) {
                $query->where('notice.title', 'LIKE', '%' . $searchText . '%')
                      ->orWhere('notice.short_info', 'LIKE', '%' . $searchText . '%')
                      ->orWhere('notice.description', 'LIKE', '%' . $searchText . '%');
            });
        }
        
        $noticeArr = $noticeArr->orderBy('notice.published_date', 'DESC')->paginate(Session::get('paginatorCount'));
        
        
        
        //echo '<pre>';print_r($noticeArr->toArray());exit;
        if (Session::get('program_id') == 1) {
            
            if (Auth::user()->group_id == 3) {
                $courseArr = Course::join('course_to_ci', 'course.id', '=', 'course_to_ci.course_id')
                        ->select('course.id as id', 'course.title as title')
                        ->where('course_to_ci.user_id', '=', Auth::user()->id)
                        ->where('course.status', 'active')
                        ->pluck('title', 'id');
            } else if (Auth::user()->group_id == 4) {
                $courseArr = Course::join('subject_to_ds', 'course.id', '=', 'subject_to_ds.course_id')
                        ->select('course.id as id', 'course.title as title')
                        ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                        ->where('course.status', 'active')
                        ->pluck('title', 'id');
            } else {
                $courseArr = Course::where('status', 'active')->orderBy('title')->pluck('title', 'id');
            }
            
            $courseArr = array('' => __('label.SELECT_COURSE_OPT')) + $courseArr;
            
        } elseif (Session::get('program_id') == 2) {
            $courseArr = array('0' => __('label.SELECT_COURSE_OPT')) + Course2::orderBy('order', 'asc')->where('status', '1')->pluck('title', 'id');
        }
        // load the view and pass the notice index
        return view('notice.index')->with(compact('noticeArr','courseArr'));
    }

    public function filter() {
        $courseId = Input::get('course_id');
        $searchText = Input::get('search_text');
        return Redirect::to('notice?course_id=' . $courseId . '&search_text=' . $searchText);
    }

    public function create() {
        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student
        
        if (Session::get('program_id') == 1) {
            if (Auth::user()->group_id == 3) {
                $courseArr = Course::join('course_to_ci', 'course.id', '=', 'course_to_ci.course_id')
                        ->select('course.id as id', 'course.title as title')
                        ->where('course_to_ci.user_id', '=', Auth::user()->id)
                        ->where('course.status', 'active')
                        ->pluck('title', 'id');
            } else if (Auth::user()->group_id == 4) {
                $courseArr = Course::join('subject_to_ds', 'course.id', '=', 'subject_to_ds.course_id')
                        ->select('course.id as id', 'course.title as title')
                        ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                        ->where('course.status', 'active')
                        ->pluck('title', 'id');
            } else {
                $courseArr = Course::where('status', 'active')->orderBy('title')->pluck('title', 'id');
            }
            
            $courseArr = array('' => __('label.SELECT_COURSE_OPT')) + $courseArr;
        } elseif (Session::get('program_id') == 2) {
            $courseArr = array('0' => __('label.SELECT_COURSE_OPT')) + Course2::orderBy('order', 'asc')->where('status', '1')->pluck('title', 'id');
        }

        return view('notice.create')->with(compact('courseArr'));
    }

    public function store() {

        

        $rules = array(
            'title' => 'required',
            'course_id' => 'required',
            'published_date' => 'required|date|before:closing_date',
            'closing_date' => 'required|date',
        );

        if (Input::hasFile('fileInfo')) {
            $rules['fileInfo'] = 'mimes:doc,pdf,docx,zip,jpeg,png,gif,jpg';
        }

        $message = array(
            'title.required' => 'Please give the notice title!',
            'course_id.required' => 'Please select course!',
            'published_date.required' => 'Please give published date !',
            'closing_date.required' => 'Please give closing date !',
        );

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Redirect::to('notice/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        //File upload
        $fileUpload = TRUE;
        $file_Name = FALSE;

        if (Input::hasFile('fileInfo')) {
            $file = Input::file('fileInfo');
            $destinationPath = public_path() . '/uploads/notice/';
            $filename = uniqid() . $file->getClientOriginalName();
            $uploadSuccess = Input::file('fileInfo')->move($destinationPath, $filename);
            if ($uploadSuccess) {
                $file_Name = TRUE;
            } else {
                $fileUpload = FALSE;
            }
        }

        if ($fileUpload === FALSE) {
            Session::flash('error', 'File Coul\'d not be uploaded');
            return Redirect::to('notice/create');
        }

        $notice = new Notice;
        $notice->course_id = Input::get('course_id');
        $notice->title = Input::get('title');
        $notice->description = Input::get('description');
        $notice->short_info = Input::get('short_info');
        $notice->published_date = Input::get('published_date');
        $notice->closing_date = Input::get('closing_date');
        $notice->status = Input::get('status');
        
        if (Session::get('program_id') == 1) {
            $notice->program_id = 1;
        } elseif (Session::get('program_id') == 2) {
            $notice->program_id = 2;
        }
        
        if ($file_Name !== FALSE) {
            $notice->fileInfo = $filename;
        }

        DB::beginTransaction();
        try {
            $notice->save();

            DB::commit();
            Session::flash('success', Input::get('title') . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('notice');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', Input::get('title') . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('notice');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student
        
        $notice = Notice::where('id', $id)->first();
        if (Session::get('program_id') == 1) {
            if (Auth::user()->group_id == 3) {
                $courseArr = Course::join('course_to_ci', 'course.id', '=', 'course_to_ci.course_id')
                        ->select('course.id as id', 'course.title as title')
                        ->where('course_to_ci.user_id', '=', Auth::user()->id)
                        ->where('course.status', 'active')
                        ->pluck('title', 'id');
            } else if (Auth::user()->group_id == 4) {
                $courseArr = Course::join('subject_to_ds', 'course.id', '=', 'subject_to_ds.course_id')
                        ->select('course.id as id', 'course.title as title')
                        ->where('subject_to_ds.user_id', '=', Auth::user()->id)
                        ->where('course.status', 'active')
                        ->pluck('title', 'id');
            } else {
                $courseArr = Course::where('status', 'active')->orderBy('title')->pluck('title', 'id');
            }
            
            $courseArr = array('' => __('label.SELECT_COURSE_OPT')) + $courseArr;
        } elseif (Session::get('program_id') == 2) {
            $courseArr = array('0' => __('label.SELECT_COURSE_OPT')) + Course2::orderBy('order', 'asc')->where('status', '1')->pluck('title', 'id');
        }
        // show the edit form and pass the supplier
        return view('notice.edit')->with(compact('notice', 'courseArr'));
    }

    public function update($id) {

        // validate
        $rules = array(
            'title' => 'required',
            'course_id' => 'required',
            'published_date' => 'required|date|before:closing_date',
            'closing_date' => 'required|date',
        );
        if (Input::hasFile('fileInfo')) {
            $rules['fileInfo'] = 'mimes:doc,pdf,docx,zip,jpeg,png,gif,jpg';
        }

        $message = array(
            'title.required' => 'Please give the notice title!',
            'course_id.required' => 'Please select course!',
            'published_date.required' => 'Please give published date !',
            'closing_date.required' => 'Please give closing date !',
        );

        $validator = Validator::make($request->all(), $rules, $message);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('notice/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        //File upload
        $fileUpload = TRUE;
        $fileName = FALSE;

        if (Input::hasFile('fileInfo')) {
            $file = Input::file('fileInfo');
            $destinationPath = public_path() . '/uploads/notice/';
            $filename = uniqid() . $file->getClientOriginalName();
            $uploadSuccess = Input::file('fileInfo')->move($destinationPath, $filename);
            if ($uploadSuccess) {
                $fileName = TRUE;
            } else {
                $fileUpload = FALSE;
            }
        }

        if ($fileUpload === FALSE) {
            Session::flash('error', 'File Coul\'d not be uploaded');
            return Redirect::to('notice/' . $id . '/edit');
        }

        $notice = Notice::find($id);
        $notice->title = Input::get('title');
        $notice->course_id = Input::get('course_id');
        $notice->description = Input::get('description');
        $notice->published_date = Input::get('published_date');
        $notice->closing_date = Input::get('closing_date');
        $notice->short_info = Input::get('short_info');
        $notice->status = Input::get('status');
        
        if (Session::get('program_id') == 1) {
            $notice->program_id = 1;
        } elseif (Session::get('program_id') == 2) {
            $notice->program_id = 2;
        }

        if ($fileName !== FALSE) {
            $notice->fileInfo = $filename;
        }

        if ($notice->save()) {
            Session::flash('success', Input::get('title') . __('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('notice');
        } else {
            Session::flash('error', Input::get('title') . __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('notice/' . $id . '/edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (in_array(Auth::user()->group_id, [5])) {
            return Redirect::to('dashboard/students');
        }//if group student
       
        // delete supplier table
        $notice = Notice::find($id);

        if ($notice->delete()) {
            Session::flash('success', $notice->title . __('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('notice');
        } else {
            Session::flash('error', $notice->title . __('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('notice');
        }
    }

    public function getDownload($id) {
        $filename = DB::table('notice')->select('fileInfo')->where('id', $id)->get();

        $request = Notice::find($id);
        $file = public_path() . '/uploads/notice/' . $request->fileInfo;

        $headers = [
            'Content-Type: application/octet-stream',
        ];
        #return Response::download($file, $id. '.' .$type, $headers); 
        return Response::download($file, $headers);
    }

}

?>