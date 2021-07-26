<?php

namespace App\Http\Controllers;

use Validator;
use App\Gallery;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Image;
use Input;
use Illuminate\Http\Request;

class GalleryController extends Controller {
    
    private $controller = 'Gallery';

    public function index(Request $request) {
        $qpArr = $request->all();
        $targetArr = Gallery::orderBy('order')->paginate(Session::get('paginatorCount'));
        return view('gallery.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('gallery.create')->with(compact('qpArr','orderList'));
    }

    public function store(Request $request) {
   
       
        $rules = array();
        if ($request->hasFile('photo')) {
            $rules['photo'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }
        if ($request->hasFile('thumb')) {
            $rules['thumb'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }
        
        $rules['order'] = 'required|not_in:0';
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('gallery/create')
                            ->withErrors($validator);
        }

        //Gallery photo upload
        $photoUpload = TRUE;
        $photoName = FALSE;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $destinationPath = public_path() . '/uploads/gallery/originalImage/';
            $photofilename = uniqid() . '.png';
            $uploadSuccess =$request->photo->move($destinationPath, $photofilename);
            if ($uploadSuccess) {
                $photoName = TRUE;
            } else {
                $photoUpload = FALSE;
            }
        }

        if ($photoUpload === FALSE) {
            Session::flash('error', 'Photo Coul\'d not be uploaded');
            return Redirect::to('gallery/create');
        }

        ///Gallery Thumb upload
        $thumbUpload = TRUE;
        $thumbName = FALSE;
        if ($request->hasFile('thumb')) {
            $file = $request->file('thumb');
            $destinationPath = public_path() . '/uploads/gallery/thumb/';
            $thumbfilename = uniqid() . '.png';
            $uploadSuccess = $request->thumb->move($destinationPath, $thumbfilename);
            if ($uploadSuccess) {
                $thumbName = TRUE;
            } else {
                $thumbUpload = FALSE;
            }
        }

        if ($thumbUpload === FALSE) {
            Session::flash('error', 'Thumb Coul\'d not be uploaded');
            return Redirect::to('gallery/create')
                            ->withInput(Input::except(array('thumb')));
        }


        $gallery = new Gallery;
        if ($photoName !== FALSE) {
            $gallery->photo = $photofilename;
        }
        if ($thumbName !== FALSE) {
            $gallery->thumb = $thumbfilename;
        }
        $gallery->order = $request->order;
        $gallery->status = $request->status;
        $gallery->home = $request->home;

        if ($gallery->save()) {
            Helper :: insertOrder($this->controller, $request->order, $gallery->id);
            Session::flash('success', __('label.GALLERY'). __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('gallery');
        } else {
            Session::flash('error',  __('label.GALLERY').__('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
        }
    }

    public function filter() {
        $groupId = Input::get('group_id');
        $rankId = Input::get('rank_id');
        $appointmentIid = Input::get('appointment_id');
        $searchText = Input::get('search_text');
        return Redirect::to('users?group_id=' . $groupId . '&rank_id=' . $rankId . '&appointment_id=' . $appointmentIid . '&search_text=' . $searchText);
    }

    public function edit($id) {
        // get the gallery
        $gallery = Gallery::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        $data['gallery'] = $gallery;
        $data['orderList'] = $orderList;
        return view('gallery.edit', $data);
    }

    public function update(Request $request,$id) {
        // validate

        $rules = array();
        if ($request->hasFile('photo')) {
            $rules['photo'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }
        if ($request->hasFile('thumb')) {
            $rules['thumb'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }
        
        $rules['order'] = 'required|not_in:0';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('gallery/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->except('photo', 'thumb'));
        }


        //Gallery photo upload
        $photoUpload = TRUE;
        $photoName = FALSE;
        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $destinationPath = public_path() . '/uploads/gallery/originalImage/';
            $photofilename = uniqid() . '.png';
            $uploadSuccess = $request->photo->move($destinationPath, $photofilename);
            if ($uploadSuccess) {
                $photoName = TRUE;
            } else {
                $photoUpload = FALSE;
            }
        }

        if ($photoUpload === FALSE) {
            Session::flash('error', 'Photo Coul\'d not be uploaded');
            return Redirect::to('gallery/create')
                            ->withInput($request->except(array('photo')));
        }

        ///Gallery Thumb upload
        $thumbUpload = TRUE;
        $thumbName = FALSE;
        if ($request->hasFile('thumb')) {
            $file = $request->file('thumb');
            $destinationPath = public_path() . '/uploads/gallery/thumb/';
            $thumbfilename = uniqid() . '.png';
            $uploadSuccess = $request->thumb->move($destinationPath, $thumbfilename);
            if ($uploadSuccess) {
                $thumbName = TRUE;
            } else {
                $thumbUpload = FALSE;
            }
        }

        if ($thumbUpload === FALSE) {
            Session::flash('error', 'Thumb Coul\'d not be uploaded');
            return Redirect::to('gallery/create')
                            ->withInput($request->except(array('thumb')));
        }

        $gallery = Gallery::find($id);
        $presentOrder = $gallery->order;

        if ($photoName !== FALSE) {
            $userExistsOrginalFile = public_path() . '/uploads/gallery/originalImage/' . $gallery->photo;
            if (file_exists($userExistsOrginalFile)) {
                File::delete($userExistsOrginalFile);
            }//if user uploaded success
        }//if file uploaded success

        if ($thumbName !== FALSE) {
            $userExistsOrginalFile = public_path() . '/uploads/gallery/thumb/' . $gallery->thumb;
            if (file_exists($userExistsOrginalFile)) {
                File::delete($userExistsOrginalFile);
            }//if user uploaded success
        }//if file uploaded success



        $gallery->order = $request->order;
        $gallery->status = $request->status;
        $gallery->home = $request->home;


        if ($photoName !== FALSE) {
            $gallery->photo = $photofilename;
        }
        if ($thumbName !== FALSE) {
            $gallery->thumb = $thumbfilename;
        }


        if ($gallery->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $gallery->id, $presentOrder);
            }
            Session::flash('success', __('label.GALLERY').__('label.HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('gallery');
        } else {
            Session::flash('error', __('label.GALLERY'). __('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('gallery/' . $id . '/edit');
        }
    }

    public function destroy($id) {

        // delete user table
        $gallery = Gallery::where('id', '=', $id)->first();
        $userExistsOrginalFile = public_path() . '/uploads/gallery/originalImage/' . $gallery->photo;
        if (file_exists($userExistsOrginalFile)) {
            File::delete($userExistsOrginalFile);
        }//if user uploaded success

        $userExistsThumbnailFile = public_path() . '/uploads/gallery/thumb/' . $gallery->thumb;
        if (file_exists($userExistsThumbnailFile)) {
            File::delete($userExistsThumbnailFile);
        }//if user uploaded success

        if ($gallery->delete()) {
            $gallery->deleted_by = Auth::user()->id;
            $gallery->save();
            Helper :: deleteOrder($this->controller, $gallery->order);
            Session::flash('success', __('label.GALLERY').__('label.HAS_BEEN_DELETED_SUCCESSFULLY'));
            return Redirect::to('gallery');
        } else {
            Session::flash('error', __('label.GALLERY').__('label.COULD_NOT_BE_DELETED'));
            return Redirect::to('gallery');
        }
    }

}
