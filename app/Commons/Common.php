<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\AclUserGroupToAccess;
use App\Product;
use App\User;
use App\Department;
use App\Branch;
use App\Country;
use App\Buyer;
use App\Designation;
use App\Division;
use App\District;
use App\Thana;
use App\BuyerFactory;
use App\SalesPersonToProduct;
use App\SalesTarget;
use App\MeasureUnit;
use App\Lead;
use App\FollowUpHistory;
use App\ContactDesignation;
use Illuminate\Http\Request;

class Common {

//    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
//    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
//        , 1 => ['status' => 'Approved', 'label' => 'success']];

//    public static function userAccess() {
//        //ACL ACCESS LIST
//        $accessGroupArr = AclUserGroupToAccess::where('group_id', Auth::user()->group_id)
//                        ->select('*')->get();
//
//        $userAccessArr = [];
//        if (!$accessGroupArr->isEmpty()) {
//            foreach ($accessGroupArr as $item) {
//                $userAccessArr[$item->module_id][$item->access_id] = $item->access_id;
//            }
//        }
//        //ENDOF ACL ACCESS LIST
//        return $userAccessArr;
//    }

//    public static function groupHasRoleAccess($groupId) {
//        $accessGroupArr = AclUserGroupToAccess::where('group_id', $groupId)
//                        ->select('*')->get();
//        if ($groupId != 1 && $accessGroupArr->isEmpty()) {
//            return 1;
//        } else {
//            return 0;
//        }
//    }

    public static function getDivision(Request $request) {
        //country wise division
        $divisionArr = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', $request->country_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showDivision', compact('divisionArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getDistrict(Request $request) {
        //country wise division
        $districtArr = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->division_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showDistrict', compact('districtArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getThana(Request $request) {
        //country wise division
        $thanaArr = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $request->district_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showThana', compact('thanaArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function loadProductName(Request $request) {
        $query = "%" . $request->product_name . "%";
        $nameArr = Product::where('name', 'LIKE', $query)->get(['name']);

        $view = view('product.showProductName', compact('nameArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function newContactPerson() {
        $designationList = array('' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $view = view('supplier.newContactPerson', compact('designationList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function buyerContactPerson() {
        $designationList = array('' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $view = view('buyerContactPerson.showContactPerson', compact('designationList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function checkPrimaryFactory(Request $request) {
        $target = BuyerFactory::where('buyer_id', $request->buyer_id)->where('primary_factory', '1')->first();
        return response()->json(['name' => $target->name]);
        ;
    }

    //function
    public static function setOrLockSalesTarget(Request $request, $lockStatus, $successMessage, $failureMessage) {
        //get effective data
        $effectiveDate = date("Y-m-01", strtotime($request->effective_month));


        //validation
        $rules = [
            'effective_month' => 'required',
        ];
        //Helper::pr($request->all(), 1);
        $row = 0;
        $productList = Product::pluck('name', 'id')->toArray();
        foreach ($request->quantity as $productId => $quantity) {
            $rules['quantity.' . $productId] = 'required';
            $message['quantity.' . $productId . '.required'] = __('label.QUANTITY_IS_REQUIRED_FOR') . $productList[$productId];
            $row++;
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation

        foreach ($request->quantity as $productId => $quantity) {
            $setTraget[$productId]['quantity'] = isset($quantity) ? $quantity : 0;
        }
        foreach ($request->remarks as $productId => $remarks) {
            $setTraget[$productId]['remarks'] = isset($remarks) ? $remarks : '';
        }

        $target = json_encode($setTraget);
        //Helper::pr($target,1);
        $salesTarget = new SalesTarget;
        $salesTarget->sales_person_id = $request->sales_person_id;
        $salesTarget->target = $target;
        $salesTarget->effective_date = $effectiveDate;
        $salesTarget->total_quantity = $request->total_quantity;
        $salesTarget->lock_status = $lockStatus;


        SalesTarget::where('sales_person_id', $request->sales_person_id)
                ->where('effective_date', $effectiveDate)->delete();
        if ($salesTarget->save()) {
            return Response::json(array('heading' => 'Success', 'message' => $successMessage), 200);
        } else {
            return Response::json(array('success' => false, 'message' => $failureMessage), 401);
        }
    }

    public static function showSalesTarget(Request $request, $loadView) {
        $salesPersonList = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"), 'id')
                        ->orderBy('id', 'asc')->get()->pluck('full_name', 'id')->toArray();

        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime(date("Y-m-d")));
        $deadline = date("Y-m-t", strtotime(date("Y-m-d")));

        $salesPersonToProduct = SalesPersonToProduct::select('product')
                        ->where('sales_person_id', $request->sales_person_id)->first();

        $salesPersonToProductArr = [];
        if (!empty($salesPersonToProduct)) {
            $salesPersonToProductArr = explode(",", $salesPersonToProduct->product);
        }

        $productList = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.id', 'product.name', 'measure_unit.name as measure_unit_name')
                        ->whereIn('product.id', $salesPersonToProductArr)
                        ->orderBy('name', 'asc')->get();

        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();

        $targetArr = $quantity = $remarks = [];
        if (!empty($salesTarget)) {
            $targetArr = json_decode($salesTarget->target, true);
        }

        if (!empty($targetArr)) {
            foreach ($targetArr as $productId => $target) {
                $quantity[$productId] = $target['quantity'];
                $remarks[$productId] = $target['remarks'];
            }
        }

        $view = view('salesTarget.' . $loadView, compact('request', 'salesPersonList'
                        , 'effectiveDate', 'deadline', 'productList', 'salesTarget'
                        , 'targetArr', 'remarks', 'quantity'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getSalesTarget(Request $request, $loadView) {
        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime($request->effective_month));
        $deadline = date("Y-m-t", strtotime($request->effective_month));

        $salesPersonToProduct = SalesPersonToProduct::select('product')
                        ->where('sales_person_id', $request->sales_person_id)->first();

        $salesPersonToProductArr = [];
        if (!empty($salesPersonToProduct)) {
            $salesPersonToProductArr = explode(",", $salesPersonToProduct->product);
        }

        $productList = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.id', 'product.name', 'measure_unit.name as measure_unit_name')
                        ->whereIn('product.id', $salesPersonToProductArr)
                        ->orderBy('name', 'asc')->get();

        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();

        $targetArr = $quantity = $remarks = [];
        if (!empty($salesTarget)) {
            $targetArr = json_decode($salesTarget->target, true);
        }

        if (!empty($targetArr)) {
            foreach ($targetArr as $productId => $target) {
                $quantity[$productId] = $target['quantity'];
                $remarks[$productId] = $target['remarks'];
            }
        }

        $view = view('salesTarget.' . $loadView, compact('request', 'effectiveDate'
                        , 'deadline', 'productList', 'targetArr', 'salesTarget', 'remarks', 'quantity'))->render();
        
        $setsubmitLock = view('salesTarget.setSubmitLockbtn', compact('request', 'salesTarget','productList'))->render();
        return response()->json(['html' => $view, 'setsubmitLock' => $setsubmitLock]);
    }

    //function
    public static function loadLcValue(Request $request) {
        $lead = Lead::select('quantity')->where('id', $request->lead_id)->first();

        $view = view('order.loadLcValue', compact('lead'))->render();
        return response()->json(['html' => $view]);
    }

    //update this method

    public static function getOrderDetails(Request $request, $loadView) {

        $orderInfo = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('product', 'product.id', '=', 'inquiry.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                        ->join('brand', 'brand.id', '=', 'inquiry.brand_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->select('inquiry.order_no', 'inquiry.lc_value', 'inquiry.lc_date', 'inquiry.lc_no'
                                , 'inquiry.order_status', 'inquiry.note', 'inquiry.lc_draft_done'
                                , 'inquiry.lc_transmitted_copy_done', 'buyer.name as buyer_name', 'product.name as product_name'
                                , 'brand.name as brand_name', 'inquiry.id', 'inquiry.purchase_order_no', 'supplier.name as supplier_name'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                                , 'inquiry.creation_date', 'inquiry.confirmation_date', 'inquiry.order_cancel_remarks'
                                , 'inquiry.order_accomplish_remarks', 'measure_unit.name as measure_unit_name')
                        ->where('inquiry.id', $request->inquiry_id)->first();

        $view = view($loadView, compact('request', 'orderInfo'))->render();
        return response()->json(['html' => $view]);
    }

    //method
    //follow up 
    public static function getFollowUpModal(Request $request, $loadView) {
        $target = Lead::find($request->inquiry_id);
        $productInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->where('product.id', $target->product_id)->select('measure_unit.name as unit_name')
                ->first();
        $statusArr = ['0' => __('label.SELECT_STATUS_OPT'), '1' => __('label.DISCUSSION'), '2' => __('label.PRESENTATION'), '3' => __('label.MEETING')];
        $followUpPrevHistoryArr = FollowUpHistory::where('inquiry_id', $request->inquiry_id)->first();
        $pricingHistoryItem = true;
        if (!empty($followUpPrevHistoryArr)) {
            if (($followUpPrevHistoryArr->final_price_set == '1') || ($target->status != '1')) {
                $pricingHistoryItem = false;
            }
        } else {
            if ($target->status != '1') {
                $pricingHistoryItem = false;
            }
        }

        $finalArr = [];
        //Prepare Array for Date Wise History
        if (!empty($followUpPrevHistoryArr)) {
            $followUpHistoryArr = json_decode($followUpPrevHistoryArr->history, true);
            krsort($followUpHistoryArr);
            $i = 0;
            if (!empty($followUpHistoryArr)) {
                foreach ($followUpHistoryArr as $node) {
                    if ($node['finalUnitPrice'] == '2') {
                        $finalArr[$node['follow_up_date']][$i]['follow_up_date'] = $node['follow_up_date'];
                        $finalArr[$node['follow_up_date']][$i]['status'] = $node['status'];
                        $finalArr[$node['follow_up_date']][$i]['remarks'] = $node['remarks'];
                    } else {
                        $finalArr[$node['follow_up_date']][$i] = $node;
                    }
                    $i++;
                }
            }
        }
        krsort($finalArr);

        $view = view($loadView, compact('request', 'target', 'statusArr', 'finalArr'
                        , 'followUpPrevHistoryArr', 'productInfo', 'pricingHistoryItem'))->render();

        return response()->json(['html' => $view]);
    }

    public static function setFollowUpSave(Request $request) {
        $leadInfo = Lead::find($request->inquiry_id);
        $rules = $message = [];
        $rules = [
            'follow_up_date' => 'required',
            'status' => 'required|not_in:0',
            'remarks' => 'required',
        ];

        if ($request->offeredPriceSelected == '1') {
            $rules['offeredPrice'] = 'required';
        }

        if ($request->negotiatedPriceSelected == '1') {
            $rules['negotiatedPrice'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $dataArr = [];
        $target = FollowUpHistory::where('inquiry_id', $request->inquiry_id)->first();
        $uniqId = uniqid();
        //Prepare New Array to Follow Up
        $dataArr[$uniqId]['follow_up_date'] = Helper::dateFormatConvert($request->follow_up_date);
        $dataArr[$uniqId]['status'] = $request->status;
        $dataArr[$uniqId]['remarks'] = $request->remarks;
        $dataArr[$uniqId]['offeredPrice'] = ($request->offeredPriceSelected == '1') ? $request->offeredPrice : '';
        $dataArr[$uniqId]['negotiatedPrice'] = ($request->negotiatedPriceSelected == '1') ? $request->negotiatedPrice : '';


        if (!empty($target) && $target->final_price_set == '1') {
            $dataArr[$uniqId]['finalUnitPrice'] = '2';
        } else {
            $dataArr[$uniqId]['finalUnitPrice'] = ($request->finalUnitPriceSelected == '1') ? '1' : '0';
        }


        $jsonEncodeHistoryArr = '';

        //If Previous Data Available then use this condition
        if (!empty($target)) {
            $preHistoryArr = json_decode($target->history, true);
            $historyArr = array_merge($preHistoryArr, $dataArr);
            $jsonEncodeHistoryArr = json_encode($historyArr);
        } else {
            $target = new FollowUpHistory;
            $jsonEncodeHistoryArr = json_encode($dataArr);
        }

        //For new ddata insertion
        $target->history = $jsonEncodeHistoryArr;
        $target->inquiry_id = $request->inquiry_id;
        $target->final_price_set = !empty($target) ? ($target->final_price_set == '1' || $request->finalUnitPriceSelected == '1') ? '1' : '0' : '0';
        $target->close_follow_up = !empty($request->closeFollowUp) ? '1' : '0';
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

        if ($request->finalUnitPriceSelected == '1') {
            $leadInfo->unit_price = $request->negotiatedPrice;
            $leadInfo->total_price = $request->negotiatedPrice * $leadInfo->quantity;
            $insertion = $leadInfo->save();
            if (!$insertion) {
                return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUERY_UNIT_PRICE_AND_TOTAL_PRICE_COULD_NOT_BE_CREATED')], 401);
            }
        }
        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.FOLLOW_UP_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FOLLOW_UP_COULD_NOT_BE_CREATED')], 401);
        }
    }

}
