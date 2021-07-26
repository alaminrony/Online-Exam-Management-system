<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\UserGroup;
use App\Rank;
use App\Designation;
use App\Branch;
use App\PasswordSetup;
use App\Department;
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
use Jenssegers\Agent\Agent;

class UserController extends Controller {

    private $programId;

    public function __construct() {
//        Validator::extend('complexPassword', function($attribute, $value, $parameters) {
//            $password = $parameters[1];
//            if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[!@#$%^&*()])(?=\S*[\d])\S*$/', $password)) {
//                return true;
//            }
//            return false;
//        });
        //Check Upper Case
        Validator::extend('upperCase', function($attribute, $value, $parameters) {
            $password = $parameters[0];
            $passwordSetup = PasswordSetup::first();
            if ($passwordSetup->upper_case == '1') {
                if (preg_match('/[A-Z]/', $password)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        });
        //Check Lower Case
        Validator::extend('lowerCase', function($attribute, $value, $parameters) {
            $password = $parameters[0];
            $passwordSetup = PasswordSetup::first();
            if ($passwordSetup->lower_case == '1') {
                if (preg_match('/[a-z]/', $password)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        });
//
        Validator::extend('specialCharacter', function($attribute, $value, $parameters) {
            $password = $parameters[0];
            $passwordSetup = PasswordSetup::first();
            if ($passwordSetup->special_character == '1') {
                if (preg_match('/[!@#$%^&*()]/', $password)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        });
//
//
        Validator::extend('blankSpaces', function($attribute, $value, $parameters) {
            $password = $parameters[0];
            $passwordSetup = PasswordSetup::first();
            if ($passwordSetup->space_not_allowed == '1') {
                if (preg_match('/\s/', $password)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        });
    }

    public function index(Request $request) {
//        Helper::dump($request->all());
        //passing param for custom function
        $qpArr = $request->all();

        $searchText = $request->search;
        //Get user group list
        $groupList = UserGroup::orderBy('id');
        if (Auth::user()->group_id == 1) {
            $groupList = $groupList->where('id', '<>', 5);
        } elseif (Auth::user()->group_id == 2) {
            $groupList = $groupList->whereIn('id', [2, 3, 4]);
        } else {
            $groupList = $groupList->whereIn('id', [3, 4]);
        }
        $groupList = ['0' => __('label.SELECT_USER_GROUP_OPT')] + $groupList->pluck('name', 'id')->toArray();

        $rankList = ['0' => __('label.SELECT_RANK_OPT')] + Rank::orderBy('order', 'asc')->pluck('short_name', 'id')->toArray();
        $appointmentList = ['0' => __('label.SELECT_APPOINTMENT_OPT')] + Designation::where('status', '=', '1')->orderBy('title')->pluck('title', 'id')->toArray();

        $targetArr = User::with('UserGroup')
                ->join('branch','branch.id','=','users.branch_id')
                ->join('region', 'region.id', '=', 'branch.region_id')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id');
                
        if (Auth::user()->group_id == 1) {
            $targetArr = $targetArr->where('group_id', '<>', '5');
        } else if (Auth::user()->group_id == 6) {
            $targetArr = $targetArr->whereIn('group_id', array(3, 4));
        } elseif (Auth::user()->group_id == 2) {
            $targetArr = $targetArr->whereIn('group_id', [2, 3, 4]);
        } elseif (Auth::user()->group_id == 3) {
            $targetArr = $targetArr->whereIn('group_id', [3, 4]);
        } elseif (Auth::user()->group_id == 4) {
            $targetArr = $targetArr->where('group_id', Auth::user()->group_id);
        }

        if (!empty($request->fil_group_id)) {
            $targetArr = $targetArr->where('group_id', '=', $request->fil_group_id);
        }

        if (!empty($request->fil_rank_id)) {
            $targetArr = $targetArr->where('rank_id', '=', $request->fil_rank_id);
        }

        if (!empty($request->fil_designation_id)) {
            $targetArr = $targetArr->where('designation_id', '=', $request->fil_designation_id);
        }

       
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('username', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        $targetArr = $targetArr->select('users.*','region.name AS region_name'
                , 'cluster.name AS cluster_name','branch.name as branch_name')
                ->paginate(Session::get('paginatorCount'));
// Helper::dump($targetArr->toArray());
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/user?page=' . $page);
        }
//        Helper::dump($targetArr);
        return view('user.index')->with(compact('qpArr', 'targetArr', 'groupList'
                                , 'rankList', 'appointmentList'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->old();

        //get user group list
        $groupList = UserGroup::orderBy('id');
        if (Auth::user()->group_id == 1) {
            $groupList = $groupList->where('id', '<>', 5);
        } elseif (Auth::user()->group_id == 2) {
            $groupList = $groupList->whereIn('id', [2, 3, 4]);
        } elseif (Auth::user()->group_id == 6) {
            $groupList = $groupList->whereIn('id', [3, 4]);
        } elseif (Auth::user()->group_id == 3) {
            $groupList = $groupList->whereIn('id', [3, 4]);
        } else {
            $groupList = $groupList->whereIn('id', [4]);
        }
        $groupList = ['0' => __('label.SELECT_USER_GROUP_OPT')] + $groupList->pluck('name', 'id')->toArray();

        //Get rank list
        $rankList = ['0' => __('label.SELECT_RANK_OPT')] + Rank::where('status', '=', 'Active')->orderBy('order')->pluck('title', 'id')->toArray();

        //Get approinment list
        $appointmentList = ['0' => __('label.SELECT_APPOINTMENT_OPT')] + Designation::where('status', '=', '1')
                        ->orderBy('title')->pluck('title', 'id')->toArray();

        //Get Branch list
        $branchList = ['0' => __('label.SELECT_BRANCH_OPT')] + Branch::orderBy('order')
                        ->pluck('name', 'id')->toArray();

        $departmentList = ['0' => __('label.SELECT_DEPARTMENT_OPT')] + Department::orderBy('order')->pluck('name', 'id')->toArray();


        $passwordSetup = PasswordSetup::first();

        $status = ['active' => 'Active', 'inactive' => 'Inactive'];

        $supervisorList = $employeeTypeList = [];
        if (!empty($request->old('group_id'))) {
            $supervisorList = ['0' => __('label.SELECT_SUPERVISOR_OPT')] + User::where('group_id', '<=', $request->old('group_id'))
                            ->select('id', DB::raw("CONCAT(first_name, ' ',last_name) AS name"))
                            ->pluck('name', 'id')->toArray();

            if ($request->old('group_id') == 3) {
                $employeeTypeList = ['' => __('label.SELECT_EMPLOYEE_TYPE_OPT'), '1' => __('label.CUSTOMER_HEAD')
                    , '2' => __('label.BRANCH_MANAGER')
                    , '3' => __('label.REGION_HEAD')];
            }
        }
        return view('user.create')->with(compact('qpArr', 'groupList', 'rankList'
                                , 'appointmentList', 'branchList', 'status', 'supervisorList', 'employeeTypeList', 'passwordSetup', 'departmentList'));
    }

    public function getBranchData(Request $request) {
        $targetArr = Branch::join('region', 'region.id', '=', 'branch.region_id')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                ->select('region.name AS region', 'cluster.name AS cluster')
                ->where('branch.id', $request->branch_id)
                ->first();
        $viewData = view('user.ShowBranchData', compact('targetArr'))->render();
        return response()->json(['viewData' => $viewData]);
    }

    public function getData(Request $request) {
        $supervisorList = ['0' => __('label.SELECT_SUPERVISOR_OPT')] + User::where('group_id', '<=', $request->group_id)
                        ->select('id', DB::raw("CONCAT(first_name, ' ',last_name) AS name"))
                        ->pluck('name', 'id')->toArray();

        $employeeTypeList = [];
        if ($request->group_id == 3) {
            $employeeTypeList = ['' => __('label.SELECT_EMPLOYEE_TYPE_OPT'), '1' => __('label.CUSTOMER_HEAD')
                , '2' => __('label.BRANCH_MANAGER')
                , '3' => __('label.REGION_HEAD')];
        }

        $html = view('user.ShowData', compact('supervisorList', 'employeeTypeList'))->render();
        return response()->json(['html' => $html]);
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        $passwordSetup = PasswordSetup::first();
        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'branch_id' => 'required|not_in:0',
            'first_name' => 'required',
            'username' => 'required|alpha_num|min:4|max:45|unique:users',
            'email' => 'required|email|unique:users',
        ];
        
        if(!empty($request->password)){
            $rules = [
            'password' => 'required|confirmed|min:' . $passwordSetup->minimum_length . '|max:' . $passwordSetup->maximum_length . '|upper_case:' . $request->password . '|lower_case:' . $request->password . '|special_character:' . $request->password . '|blank_spaces:' . $request->password,
            'password_confirmation' => 'required',
        ];
        }

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }

        $messages = [
            'group_id.required' => 'Group must be selected!',
            'department_id.required' => 'Department must be selected!',
            'designation_id.required' => 'Designation must be selected!',
            'branch_id.required' => 'Branch must be selected!',
            'first_name.required' => 'Please give the first name',
            'username.required' => 'Please give the username',
            'username.unique' => 'That username is already taken',
            'password.upper_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_UPPERCASE'),
            'password.lower_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_LOWERCASE'),
            'password.special_character' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_SPECIAL_CHARECTER'),
            'password.blank_spaces' => __('label.BLANK_SPACE_NOT_ALLOWED'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
//        Helper::dump($validator->errors());
        if ($validator->fails()) {
            return redirect('user/create' . $pageNumber)
                            ->withInput($request->except('photo', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        //image resize and save
        $file = $request->crop_photo;
        if (!empty($file)) {
            $fileName = Auth::user()->id . uniqid() . ".png";
            $directory = public_path('/uploads/user/');
            $imageUrl = $directory . $fileName;
            Image::make($file)->resize(100, 100)->save($imageUrl);
        }

        $target = new User;
        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->employee_type = $request->employee_type;
        $target->user_id = $request->user_id;
        $target->rank_id = !empty($request->rank_id) ? $request->rank_id : '';
        $target->designation_id = $request->designation_id;
        $target->branch_id = $request->branch_id;

        $target->first_name = $request->first_name;
        $target->last_name = !empty($request->last_name) ? $request->last_name : '';
        $target->username = $request->username;
        $target->password = !empty($request->password)?Hash::make($request->password):Hash::make('City@123');
        $target->password_changed_at = date("Y-m-d");
        $target->first_login = '0';
        $target->email = $request->email;
        $target->phone_no = !empty($request->phone_no) ? $request->phone_no : NULL;

        $target->photo = !empty($fileName) ? $fileName : '';
        $target->status = $request->status;

        DB::beginTransaction();
        try {
            $target->save();
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $target->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 2;
            $action = "Create";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['user_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            DB::commit();
            Session::flash('success', $request->username . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return Redirect::to('user');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', $request->username . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return Redirect::to('user/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();

        $target = User::find($id);
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('user');
        }

        //get user group list
        $groupList = UserGroup::orderBy('id');
        if (Auth::user()->group_id == 1) {
            $groupList = $groupList->where('id', '<>', 5);
        } elseif (Auth::user()->group_id == 2) {
            $groupList = $groupList->whereIn('id', [2, 3, 4]);
        } elseif (Auth::user()->group_id == 6) {
            $groupList = $groupList->whereIn('id', [3, 4]);
        } elseif (Auth::user()->group_id == 3) {
            $groupList = $groupList->whereIn('id', [3, 4]);
        } else {
            $groupList = $groupList->whereIn('id', [4]);
        }
        $groupList = ['0' => __('label.SELECT_USER_GROUP_OPT')] + $groupList->pluck('name', 'id')->toArray();

        //Get rank list
        $rankList = ['0' => __('label.SELECT_RANK_OPT')] + Rank::where('status', '=', 'Active')->orderBy('order')->pluck('title', 'id')->toArray();

        //Get approinment list
        $appointmentList = ['0' => __('label.SELECT_APPOINTMENT_OPT')] + Designation::where('status', '=', '1')
                        ->orderBy('title')->pluck('title', 'id')->toArray();

        //Get Branch list
        $branchList = ['0' => __('label.SELECT_BRANCH_OPT')] + Branch::orderBy('order')
                        ->pluck('name', 'id')->toArray();

        $departmentList = ['0' => __('label.SELECT_DEPARTMENT_OPT')] + Department::orderBy('order')->pluck('name', 'id')->toArray();

        $status = ['active' => 'Active', 'inactive' => 'Inactive'];

        $supervisorList = $employeeTypeList = [];
//        if (!empty($request->old('group_id'))) {
        $supervisorList = ['0' => __('label.SELECT_SUPERVISOR_OPT')] + User::where('group_id', '<=', $target->group_id)
                        ->select('id', DB::raw("CONCAT(first_name, ' ',last_name) AS name"))
                        ->pluck('name', 'id')->toArray();

        if ($target->group_id == 3) {
            $employeeTypeList = ['' => __('label.SELECT_EMPLOYEE_TYPE_OPT'), '1' => __('label.CUSTOMER_HEAD')
                , '2' => __('label.BRANCH_MANAGER')
                , '3' => __('label.REGION_HEAD')];
        }

        return view('user.edit')->with(compact('qpArr', 'target', 'groupList', 'rankList'
                                , 'appointmentList', 'branchList', 'status', 'supervisorList', 'employeeTypeList', 'departmentList'));
    }

    public function update(Request $request, $id) {
//        Helper::dump($request->id);
        $target = User::find($id);
        $programId = 1;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $passwordSetup = PasswordSetup::first();

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'branch_id' => 'required|not_in:0',
            'first_name' => 'required',
            'username' => 'required|alpha_num|min:2|max:45|unique:users,username,' . $id,
            'email' => 'required|email',
        ];

        if (!empty($request->password)) {
            $rules['password'] = 'required|confirmed|min:' . $passwordSetup->minimum_length . '|max:' . $passwordSetup->maximum_length . '|upper_case:' . $request->password . '|lower_case:' . $request->password . '|special_character:' . $request->password . '|blank_spaces:' . $request->password;
            $rules['password_confirmation'] = 'required';
        }

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:2048|mimes:jpeg,png,gif,jpg';
        }

        $messages = [
            'group_id.required' => 'Group must be selected!',
            'department_id.required' => 'Depaarment must be selected!',
            'designation_id.required' => 'Approiment must be selected!',
            'branch_id.required' => 'Branch must be selected!',
            'first_name.required' => 'Please give the first name',
            'username.required' => 'Please give the username',
            'username.unique' => 'That username is already taken',
            'password.upper_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_UPPERCASE'),
            'password.lower_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_LOWERCASE'),
            'password.special_character' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_SPECIAL_CHARECTER'),
            'password.blank_spaces' => __('label.BLANK_SPACE_NOT_ALLOWED'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('user/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all)
                            ->withErrors($validator);
        }

        //image resize and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            if (!empty($target->photo)) {
                $prevfileName = 'public/uploads/user/' . $target->photo;
                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
            }
            $imgName = Auth::user()->id . uniqid() . ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }

        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->employee_type = $request->employee_type;
        $target->user_id = $request->user_id;
        $target->rank_id = !empty($request->rank_id) ? $request->rank_id : '';
        $target->designation_id = $request->designation_id;
        $target->branch_id = $request->branch_id;
        $target->first_name = $request->first_name;
        $target->last_name = !empty($request->last_name) ? $request->last_name : '';
        $target->phone_no = !empty($request->phone_no) ? $request->phone_no : $target->phone_no;
        $target->username = $request->username;
        $target->password = !empty($request->password) ? Hash::make($request->password) : $target->password;
        $target->email = $request->email;
        $target->photo = !empty($imgName) ? $imgName : $target->photo;
        $target->status = $request->status;

        DB::beginTransaction();
        try {
            $target->save();
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 2;
            $action = "Update";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['user_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            DB::commit();
            Session::flash('success', $request->username . ' ' . __('label.HAS_BEEN_UPDATED_SUCESSFULLY'));
            return Redirect::to('user' . $pageNumber);
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', $request->username . ' ' . __('label.COULD_NOT_BE_UPDATED_SUCESSFULLY'));
            return redirect('user/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = User::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
         //check dependency
        $dependencyArr = ['ExamToStudent'=>'employee_id','SubjectToDs' => 'user_id','MockMark'=>'employee_id','AttendeeRecord'=>'employee_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();
            
            if(!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                 return redirect('user' . $pageNumber);
            }
        }

        $fileName = 'public/uploads/user/' . $target->photo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

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
            $type = 2;
            $action = "Delete";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['user_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            Session::flash('error', $target->username . ' ' . __('label.HAS_BEEN_DELETED'));
        } else {
            Session::flash('error', $target->username . ' ' . __('label.COULD_NOT_BE_DELETED'));
        }
        return redirect('user' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search_text . '&fil_group_id=' . $request->fil_group_id
                . '&fil_rank_id=' . $request->fil_rank_id . '&fil_designation_id='
                . $request->fil_designation_id;
        return Redirect::to('user?' . $url);
    }

    public function changePassword(Request $request, $id, $param = null) {
        //passing param for custom function
        $qpArr = $request->all();

        $nextUrl = '';
        if ($param !== null) {
            $nextUrl = 'user?' . $param;
        } else {
            $nextUrl = 'user';
        }

        $passwordSetup = PasswordSetup::first();

        $userInfo = User::join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('designation', 'designation.id', '=', 'users.designation_id', 'left')
                ->where('users.id', $id)
                ->select('users.*', 'user_group.name as group_name'
                        , 'rank.title as rank_title', 'designation.title as designation_title'
                        , 'branch.name as branch_name')
                ->first();
        
        return view('user.changePassword')->with(compact('id', 'nextUrl', 'userInfo', 'qpArr', 'passwordSetup'));
    }

    public function updatePassword(Request $request) {

        //begin back same page after update password
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update password

        $nextUrl = $request->next_url;
        $target = User::find($request->id);
        $passwordSetup = PasswordSetup::first();
        $rules = [
            'password' => 'required|confirmed|min:' . $passwordSetup->minimum_length . '|max:' . $passwordSetup->maximum_length . '|upper_case:' . $request->password . '|lower_case:' . $request->password . '|special_character:' . $request->password . '|blank_spaces:' . $request->password,
            'password_confirmation' => 'required',
        ];

        $messages = [
            'password.upper_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_UPPERCASE'),
            'password.lower_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_LOWERCASE'),
            'password.special_character' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_SPECIAL_CHARECTER'),
            'password.blank_spaces' => __('label.BLANK_SPACE_NOT_ALLOWED'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('changePassword/' . $request->id)
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $target->password = Hash::make($request->password);
        if ($target->save()) {
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 3;
            $action = "Change Password";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['user_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            Session::flash('success', $target->username . ' ' . __('label.PASSWORD_CHANGE_SUCCESSFUL'));
            return Redirect::to('dashboard' . $pageNumber);
        } else {
            Session::flash('error', $user->username . ' ' . __('label.PASSWORD_COULDNOT_CHANGE'));
            return Redirect::to('changePassword/' . $request->id)->withInput($request->all());
        }
    }

    //User Active/Inactive Function
    public function active($id, $param = null) {

        if ($param !== null) {
            $url = 'user?' . $param;
        } else {
            $url = 'user';
        }
        $target = User::find($id);

        if ($target->status == 'active') {
            $target->status = 'inactive';
            $msgText = $target->username . __('label.SUCCESSFULLY_INACTIVATE');
        } else {
            $target->status = 'active';
            $msgText = $target->username . __('label.SUCCESSFULLY_ACTIVATE');
        }
        $target->save();
        // redirect
        Session::flash('success', $msgText);
        return Redirect::to($url);
    }

    public function details(Request $request) {
   
        $userId = !empty($request->user_id) ? $request->user_id : '';

        $target = User::join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('region', 'region.id', '=', 'branch.region_id', 'left')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id', 'left')
                ->join('designation', 'designation.id', '=', 'users.designation_id', 'left')
                ->join('department', 'department.id', '=', 'users.department_id', 'left')
                ->where('users.id', $userId)
                ->select('users.*', 'user_group.name as group_name'
                        , 'rank.title as rank_title', 'designation.title as designation_title'
                        , 'branch.name as branch_name','department.name as department_name','region.name as region_name'
                        ,'cluster.name as cluster_name')
                ->first();
         
//        Helper::dump($target);

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('user');
        }

        $html = view('user.showDetails', compact('target'))->render();
        return response()->json(['html' => $html]);
    }

    public function setRecordPerPage(Request $request) {
        $referrerArr = explode('?', URL::previous());
        $queryStr = '';
        if (!empty($referrerArr[1])) {
            $queryParam = explode('&', $referrerArr[1]);
            foreach ($queryParam as $item) {
                $valArr = explode('=', $item);
                if ($valArr[0] != 'page') {
                    $queryStr .= $item . '&';
                }
            }
        }

        $url = $referrerArr[0] . '?' . trim($queryStr, '&');

        if ($request->record_per_page > 999) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_LESS_THAN_999'));
            return redirect($url);
        }

        if ($request->record_per_page < 1) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_GREATER_THAN_1'));
            return redirect($url);
        }

        $request->session()->put('paginatorCount', $request->record_per_page);
        return redirect($url);
    }

    public function forceChangePassword(Request $request) {
        $passwordSetup = PasswordSetup::first();

        $userInfo = User::join('user_group', 'user_group.id', '=', 'users.group_id', 'inner')
                ->join('rank', 'rank.id', '=', 'users.rank_id', 'left')
                ->join('branch', 'branch.id', '=', 'users.branch_id', 'left')
                ->join('designation', 'designation.id', '=', 'users.designation_id', 'left')
                ->where('users.id', Auth::user()->id)
                ->select('users.*', 'user_group.name as group_name'
                        , 'rank.title as rank_title', 'designation.title as appointment_title'
                        , 'branch.name as branch_name')
                ->first();

        if ($request->type == '1') {
            Session::flash('error', __('label.YOUR_PASSWORD_HAS_BEEN_EXPIRED'));
        }
        return view('user.forceChangePassword')->with(compact('userInfo', 'passwordSetup'));
    }

    public function updateForcePassword(Request $request) {
        //begin back same page after update password
        $qpArr = $request->all();
        //end back same page after update password
        $target = User::find($request->id);
        $passwordSetup = PasswordSetup::first();
        $rules = [
            'password' => 'required|confirmed|min:' . $passwordSetup->minimum_length . '|max:' . $passwordSetup->maximum_length . '|upper_case:' . $request->password . '|lower_case:' . $request->password . '|special_character:' . $request->password . '|blank_spaces:' . $request->password,
            'password_confirmation' => 'required',
        ];

        $messages = [
            'password.upper_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_UPPERCASE'),
            'password.lower_case' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_LOWERCASE'),
            'password.special_character' => __('label.AT_LEAST_ONE_CHARECTER_SHOULD_BE_SPECIAL_CHARECTER'),
            'password.blank_spaces' => __('label.BLANK_SPACE_NOT_ALLOWED'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('changePassword/' . $request->id)
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $target->password = Hash::make($request->password);
        $target->password_changed_at = date("Y-m-d");
        $target->first_login = '1';
        if ($target->save()) {
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 3;
            $action = "Change Password";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['user_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];

            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            Auth::logout();
            Session::flash('success', $target->username . ' ' . __('label.PASSWORD_HAS_BEEN_CHANGED_SUCCESSFULLY'));
            return Redirect::to('login');
        } else {
            Session::flash('error', $target->username . ' ' . __('label.FORCE_PASSWORD_HAS_NOT_BEEN_CHANGED'));
            return Redirect::to('forceChangePassword/' . $request->id)->withInput($request->all());
        }
    }

}
