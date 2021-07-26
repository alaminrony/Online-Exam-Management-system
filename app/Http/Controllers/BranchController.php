<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\Region;
use App\Cluster;
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

class BranchController extends Controller {

    private $controller = 'Branch';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $searchText = $request->search_text;
        $targetArr = Branch::join('region', 'region.id', '=', 'branch.region_id')
                ->join('cluster', 'cluster.id', '=', 'branch.cluster_id')
                ->orderBy('order', 'asc')
                ->select('branch.*', DB::raw("CONCAT(region.name, ' (',region.short_name,')') AS region")
                , DB::raw("CONCAT(cluster.name, ' (',cluster.short_name,')') AS cluster"));
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('branch.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('branch.sol_id', 'LIKE', '%' . $searchText . '%');
            });
        }
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/branch?page=' . $page);
        }

        return view('branch.index')->with(compact('targetArr', 'qpArr'));
    }

    public function filter(Request $request) {
        $url = ' search_text=' . $request->search_text;
        return Redirect::to('branch?' . $url);
    }

    public function create(Request $request) {

        $regionList = ['0' => __('label.SELECT_REGION_OPT')] + Region::orderBy('order')
                        ->where('status', '1')
                        ->select('id', DB::raw("CONCAT(name, ' (',short_name,')') AS name"))
                        ->pluck('name', 'id')->toArray();

        $clusterList = ['0' => __('label.SELECT_CLUSTER_OPT')];

        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('branch.create')->with(compact('regionList', 'clusterList', 'orderList'));
    }

    public function getCluster(Request $request) {
        $clusterList = ['0' => __('label.SELECT_CLUSTER_OPT')] + Cluster::orderBy('order')
                        ->where('region_id', $request->region_id)
                        ->where('status', '1')
                        ->select('id', DB::raw("CONCAT(name, ' ( ',short_name,')') AS name"))
                        ->pluck('name', 'id')->toArray();

        $html = view('branch.ShowCluster', compact('clusterList'))->render();
        return response()->json(['html' => $html]);
    }

    public function store(Request $request) {
//         Helper::dump($request->all());
        $rules = array(
            'name' => 'required',
            'sol_id' => 'required',
            'order' => 'required|not_in:0',
            'region_id' => 'required|not_in:0',
            'cluster_id' => 'required|not_in:0',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('branch/create')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        $branch = new Branch;
        $branch->region_id = $request->region_id;
        $branch->cluster_id = $request->cluster_id;
        $branch->name = $request->name;
        $branch->sol_id = $request->sol_id;
        $branch->location = !empty($request->location) ? $request->location : '';
        $branch->order = $request->order;
        $branch->status = $request->status;
        if ($branch->save()) {
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $branch->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 5;
            $action = "Create";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['branch_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];

            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            Helper :: insertOrder($this->controller, $branch->order, $branch->id);
            Session::flash('success', __('label.BRANCH_CREATED_SUCESSFULLY'));
            return Redirect::to('branch');
        } else {
            Session::flash('error', __('label.BRANCH_COULD_NOT_BE_CREATED'));
            return Redirect::to('branch/create');
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();
        $target = Branch::find($id);
        $regionList = ['0' => __('label.SELECT_REGION_OPT')] + Region::orderBy('order')
                        ->where('status', '1')
                        ->select('id', DB::raw("CONCAT(name, ' » ',short_name) AS name"))
                        ->pluck('name', 'id')->toArray();

        $clusterList = ['0' => __('label.SELECT_CLUSTER_OPT')] + Cluster::orderBy('order')
                        ->where('region_id', $target->region_id)
                        ->where('status', '1')
                        ->select('id', DB::raw("CONCAT(name, ' » ',short_name) AS name"))
                        ->pluck('name', 'id')->toArray();

        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('branch.edit')->with(compact('target', 'qpArr', 'regionList', 'clusterList', 'orderList'));
    }

    public function update(Request $request, $id) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = array(
            'name' => 'required',
            'sol_id' => 'required',
            'order' => 'required|not_in:0',
            'region_id' => 'required|not_in:0',
            'cluster_id' => 'required|not_in:0',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('branch/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput($request->all());
        }

        // store
        $branch = Branch::find($id);
        $presentOrder = $branch->order;
        $branch->region_id = $request->region_id;
        $branch->cluster_id = $request->cluster_id;
        $branch->name = $request->name;
        $branch->sol_id = $request->sol_id;
        $branch->location = !empty($request->location) ? $request->location : '';
        $branch->order = $request->order;
        $branch->status = $request->status;

        if ($branch->save()) {
            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 5;
            $action = "Update";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['branch_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $branch->order, $branch->id, $presentOrder);
            }
            Session::flash('success', __('label.BRANCH_UPDATED_SUCCESSFULLY'));
            return Redirect::to('branch' . $pageNumber);
        } else {
            Session::flash('error', __('label.BRANCH_COULD_NOT_BE_UPDATED'));
            return Redirect::to('branch/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {

        //begin back same page after delete
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after delete
        //check dependency
        $dependencyArr = ['User' => 'branch_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();

            if (!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                return Redirect::to('branch' . $pageNumber);
            }
        }

        $target = Branch::find($id);

        if ($target->delete()) {
            $target->deleted_by = Auth::user()->id;
            $target->save();
            Helper :: deleteOrder($this->controller, $target->order);

            $agent = new Agent();
            $platform = $agent->platform();
            $browser = $agent->browser();
            $ipAddress = $request->ip();
            $adminId = Auth::user()->id;
            $targetId = $request->id;
            $loginDate = date("Y-m-d");
            $dateTime = date("Y-m-d H:i:s");
            $type = 5;
            $action = "Delete";
            $uniquid = uniqid();
            $infoArr = [$uniquid => ['branch_id' => $targetId, 'date_time' => $dateTime, 'action' => $action, 'operating_system' => $platform, 'browser' => $browser, 'ip_address' => $ipAddress]];
            Helper::logGrnerate($adminId, $loginDate, $type, $infoArr);
            
            Session::flash('success', __('label.BRANCH_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BRANCH_COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('branch' . $pageNumber);
    }

}
