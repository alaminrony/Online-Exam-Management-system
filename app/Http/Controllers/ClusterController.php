<?php

namespace App\Http\Controllers;

use Validator;
use App\Region;
use App\Cluster;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Illuminate\Http\Request;

class ClusterController extends Controller {

    private $controller = 'Cluster';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Cluster::join('region', 'region.id', '=', 'cluster.region_id')
                        ->select('cluster.*', DB::raw("CONCAT(region.name, ' (',region.short_name,')') AS region"))->orderBy('order', 'asc');

        //begin filtering
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = Cluster::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('cluster.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('cluster.short_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('cluster.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/cluster?page=' . $page);
        }

        return view('cluster.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $regionList = ['0' => __('label.SELECT_REGION_OPT')] + Region::orderBy('order')
                        ->where('status', '1')
                        ->select('id', DB::raw("CONCAT(name, ' (',short_name,')') AS name"))
                        ->pluck('name', 'id')->toArray();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('cluster.create')->with(compact('qpArr', 'orderList', 'regionList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:cluster',
                    'short_name' => 'required',
                    'region_id' => 'required|not_in:0',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('cluster/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Cluster;
        $target->region_id = $request->region_id;
        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.CLUSTER_CREATED_SUCCESSFULLY'));
            return redirect('cluster');
        } else {
            Session::flash('error', __('label.CLUSTER_COULD_NOT_BE_CREATED'));
            return redirect('cluster/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Cluster::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('cluster');
        }

        $regionList = ['0' => __('label.SELECT_REGION_OPT')] + Region::orderBy('order')
                        ->where('status', '1')
                        ->select('id', DB::raw("CONCAT(name, ' » ',short_name) AS name"))
                        ->pluck('name', 'id')->toArray();

        //passing param for custom function
        $qpArr = $request->all();
        return view('cluster.edit')->with(compact('target', 'qpArr', 'orderList','regionList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Cluster::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:cluster,name,' . $id,
                    'region_id' => 'required|not_in:0',
                    'short_name' => 'required|unique:cluster,short_name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('cluster/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->region_id = $request->region_id;
        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.CLUSTER_UPDATED_SUCCESSFULLY'));
            return redirect('cluster' . $pageNumber);
        } else {
            Session::flash('error', __('label.CLUSTER_COULD_NOT_BE_UPDATED'));
            return redirect('cluster/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Cluster::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
          //check dependency
        $dependencyArr = ['Branch' => 'cluster_id'];
        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();
            
            if(!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                 return Redirect::to('cluster'.$pageNumber);
            }
        }

        //Dependency
//        $dependencyArr = [
//            'User' => ['1' => 'cluster_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
//                    return redirect('cluster' . $pageNumber);
//                }
//            }
//        }

        if ($target->delete()) {
            $target->deleted_by = Auth::user()->id;
            $target->save();
            
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.CLUSTER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CLUSTER_COULD_NOT_BE_DELETED'));
        }
        return redirect('cluster' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('cluster?' . $url);
    }

}
