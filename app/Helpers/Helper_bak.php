<?php

use Illuminate\Support\Facades\DB;
use App\DailyProduct;
use App\ProductCheckInDetails;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use App\DailyProductDetails;
use App\Product;
use Illuminate\Support\Facades\Auth;
use App\Configuration;
use App\AclUserGroupToAccess;

class Helper {

    //function for back same page after update,delete,cancel
    public static function queryPageStr($qpArr) {
        //link for same page after query
        $qpStr = '';
        if (!empty($qpArr)) {
            $qpStr .= '?';
            foreach ($qpArr as $key => $value) {
                if ($value != '') {
                    $qpStr .= $key . '=' . $value . '&';
                }
            }
            $qpStr = trim($qpStr, '&');
            return $qpStr;
        }
    }

    public static function printDate($date = '0000-00-00') {
        return date('F jS, Y', strtotime($date));
    }

    public static function printDateFormat($date = '0000-00-00') {
        return date('d F Y \a\t g:i a', strtotime($date));
    }

    public static function getEventTypeArr() {
        $eventTypeArr = ['1' => __('label.EVENT'), '2' => __('label.CONSIDERATION')];
        return $eventTypeArr;
    }

    // public static function getMonthArr() {
    // $eventTypeArr = ['1' => __('label.EVENT'), '2' => __('label.CONSIDERATION')];
    // return $eventTypeArr
    // }
//function for getOrderList
    public static function getOrderList($model = null, $operation = null, $parentId = null, $parentName = null) {

        /*
         * Operation :: 1 = Create, 2= Edit
         */
        $namespacedModel = '\\App\\' . $model;
        $targetArr = $namespacedModel::select(array(DB::raw('COUNT(id) as total')));
        if (!empty($parentId)) {
            $targetArr = $targetArr->where($parentName, $parentId);
        }
        $targetArr = $targetArr->first();
        $count = $targetArr->total;

        //in case of Create, always Increment the number of element in order 
        //to accomodate new Data
        if ($operation == '1') {
            $count++;
        }
        return array_combine(range(1, $count), range(1, $count));
    }

    //function for Insert order
    public static function insertOrder($model = null, $order = null, $id = null, $parentId = null, $parentName = null) {
        $namespacedModel = '\\App\\' . $model;
        $namespacedModel::where('id', $id)->update(['order' => $order]);
        $target = $namespacedModel::where('id', '!=', $id)->where('order', '>=', $order);
        if (!empty($parentId)) {
            $target = $target->where($parentName, $parentId);
        }
        $target = $target->update(['order' => DB::raw('`order`+ 1')]);
    }

    // function for Update Order
    public static function updateOrder($model = null, $newOrder = null, $id = null, $presentOrder = null, $parentId = null, $parentName = null) {
        $namespacedModel = '\\App\\' . $model;
        $namespacedModel::where('id', $id)->update(['order' => $newOrder]);

        //condition for order range
        $target = $namespacedModel::where('id', '!=', $id);
        if (!empty($parentId)) {
            $target = $target->where($parentName, $parentId);
        }

        if ($presentOrder < $newOrder) {
            //$namespacedModel::where('id', '!=', $id)->where('order', '>=', $presentOrder)->where('order', '<=', $newOrder)->update(['order' => DB::raw('`order`- 1')]);
            $target = $target->where('order', '>=', $presentOrder)->where('order', '<=', $newOrder)->update(['order' => DB::raw('`order`- 1')]);
        } else {
            $target = $target->where('order', '>=', $newOrder)->where('order', '<=', $presentOrder)->update(['order' => DB::raw('`order`+ 1')]);
        }
    }

    public static function deleteOrder($model = null, $order = null, $parentId = null, $parentName = null) {
        $namespacedModel = '\\App\\' . $model;
        $target = $namespacedModel::where('order', '>=', $order);
        if (!empty($parentId)) {
            $target = $target->where($parentName, $parentId);
        }

        $target = $target->update(['order' => DB::raw('`order`- 1')]);
    }

//    public static function numberformat($num = 0, $digit = 3) {
//        return number_format($num, $digit, '.', ',');
//    }
    //put your code here
    public static function numberformat($num = 0) {
        return number_format($num, 2, '.', ',');
    }

    public static function printDateTime($date = '0000-00-00 00:00:00') {
        return date('d/m/y H:i', strtotime($date));
    }

    public static function printOnlyDate($date = '0000-00-00') {
        return date('d/m/y', strtotime($date));
    }

    //For make Print any data
    public static function pr($data, $number) {
        echo "<pre>";
        print_r($data);
        if ($number == '1') {
            return exit;
        } else {
            return false;
        }
    }

    public static function dateFormat($date = '0000-00-00') {
        return date('d/m/Y', strtotime($date));
    }

    public static function unitConversion($totalQtyStr = "") {
        $pos = strpos($totalQtyStr, ".");
        if ($pos === false) {
            $kgAmnt = $totalQtyStr;
            $gmAmntArr = "";
        } else {
            $totalQtyArr = explode(".", $totalQtyStr);
            $kgAmnt = $totalQtyArr[0];
            $gmAmntArr = $totalQtyArr[1];
        }

        $kgFinalAmntStr = '';
        if ($kgAmnt > 0) {
            $kgFinalAmntStr = (int) $kgAmnt . " " . __('label.UNIT_KG');
        }


        if ($pos !== false) { //If decimal point exists
            $totalAmntStr = str_pad($gmAmntArr, 6, "0", STR_PAD_RIGHT);

            $gmStr = substr($totalAmntStr, 0, 3); //Subtract gram aamount
            $gmFinalAmntStr = "";
            if ($gmStr > 0) {
                $gmFinalAmntStr = (int) $gmStr . " " . __('label.GM');
            }
            $miliGmStr = substr($totalAmntStr, 3, 3); //Subtract miligram aamount
            $mgFinalAmntStr = "";
            if ($miliGmStr > 0) {
                $mgFinalAmntStr = (int) $miliGmStr . " " . __('label.MG');
            }

            $qtyTotalDetail = $kgFinalAmntStr . " " . $gmFinalAmntStr . " " . $mgFinalAmntStr;
        } else {
            $qtyTotalDetail = $kgFinalAmntStr;
        }

        return $qtyTotalDetail;
    }

    public static function getAccessList() {
        //Get User Group Access
        $userGroupToAccessArr = AclUserGroupToAccess::select('acl_user_group_to_access.module_id', 'acl_user_group_to_access.access_id')
                        ->where('acl_user_group_to_access.group_id', '=', Auth::user()->group_id)
                        ->orderBy('acl_user_group_to_access.module_id', 'asc')
                        ->orderBy('acl_user_group_to_access.access_id', 'asc')->get();

        //echo '<pre>';print_r($userGroupToAccessArr->toArray());exit;
        //User_group_Module_to_Access Table
        if (!$userGroupToAccessArr->isEmpty()) {
            foreach ($userGroupToAccessArr as $ma) {
                $moduleToGroupAccessListArr[$ma->module_id][] = $ma->access_id;
            }
        }


        $value = "Hello";
        //session_start();
        //$_SESSION['variableName'] =  $value;
        //echo Session::get('variableName');
        //exit;
        Session::put('moduleToGroupAccessListArr', $moduleToGroupAccessListArr);
        //echo '<pre>';print_r(Session::get('variableName'));
    }

    public static function formatDate($dateTime = '0000-00-00 00:00:00') {
        return date('d F Y', strtotime($dateTime));
    }

    public static function getMachineType() {
        $machineTypeArr = ['1' => __('label.MANUAL'), '2' => __('label.AUTOMATIC')];
        return $machineTypeArr;
    }

    public static function getCustomerType() {
        $machineTypeArr = ['1' => __('label.BONDED'), '2' => __('label.COMMERCIAL')];
        return $machineTypeArr;
    }

    public static function arrayToString($array = []) {
        $string = '';
        if (!empty($array)) {
            $string = implode(',', $array);
        }
        return $string;
    }

    public static function stringToArray($string = null) {
        $array = [];
        if (!empty($string)) {
            $array = explode(',', $string);
        }
        return $array;
    }

    //new function
    public static function dateFormatConvert($date = '0000-00-00') {
        return date('Y-m-d', strtotime($date));
    }

    public static function numberFormat2Digit($num = 0) {
        if (empty($num)) {
            $num = 0;
        }
        return number_format($num, 2, '.', ',');
    }

}
