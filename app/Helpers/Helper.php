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
use App\GradingSystem;
use App\LogHistory;

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

    public static function dump($data) {
        if (is_object($data)) {
            echo "<pre>";
            print_r($data->toArray());
            exit;
        } else {
            echo "<pre>";
            print_r($data);
            exit;
        }
    }

//    public static func//put your code here
    public static function numberformat($num = 0) {
        return number_format($num, 2, '.', ',');
    }

//    public static function printDate($date = '0000-00-00') {
//
//        return date('F jS, Y', strtotime($date));
//    }

    public static function printDate($date = '0000-00-00') {
        if(!empty($date)){
             return date('j F, Y', strtotime($date));
        }
        return;
    }

    public static function formatDateTime($dateTime = '0000-00-00 00:00:00') {
        if(!empty($dateTime)){
             return date('j F, Y h:i A', strtotime($dateTime));
        }
        return ;
    }

    public static function printDateTime($dateTime = '0000-00-00 00:00:00') {

        return date('F jS, Y h:i A', strtotime($dateTime));
    }

    public static function dateFormat($date = '0000-00-00') {
        return date('j F, Y', strtotime($date));
    }

    public static function printDateFormat($date = '0000-00-00') {
        return date('d F Y \a\t g:i a', strtotime($date));
    }

    public static function formatDate($date = '0000-00-00') {
        if (!empty($date)) {
            return date('d F Y \a\t g:i a', strtotime($date));
        } else {
            return '';
        }
    }

    public static function jcscGrade($value = 0, $gradeArr = array()) {

        if (!empty($gradeArr)) {
            foreach ($gradeArr as $item) {
                if ($value >= $item['start_range'] && $value <= $item['end_range']) {
                    return $item['letter'];
                }
            }
        }

        return null;
    }

    public static function positionFormat($number = 0) {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . '<sup>th</sup>';
        } else {
            return $number . '<sup>' . $ends[$number % 10] . '</sup>';
        }
    }

    public static function cmp($a, $b = '') {
        return strcmp($a['total_mark'], $b['total_mark']);
    }

    public static function array_merge_myway() {
        $output = array();
        foreach (func_get_args() as $array) {
            foreach ($array as $key => $value) {
                $output[$key] = isset($output[$key]) ? array_merge($output[$key], $value) : $value;
            }
        }
        return $output;
    }

    // TAE OR EPE EXAM GET CHANCE 
    public static function chanceTaeOrEpe($student_id, $subject_id, $type, $examType) {
        $positionArr = ['1' => '1st', '2' => '2nd', '3' => '3rd'];

        $chanceCount = 0;
        if ($examType == 'TAE') {
            if ($type == '1' || $type == '3') {
                $chanceCount = TaeToStudent::join('tae', 'tae.id', '=', 'tae_to_student.tae_id')
                                ->where('tae_to_student.student_id', $student_id)
                                ->where('tae.subject_id', $subject_id)
                                ->where('tae.type', $type)->count();
            } elseif ($type == '2') {
                $chanceCount = TaeToStudent::join('tae', 'tae.id', '=', 'tae_to_student.tae_id')
                                ->where('tae_to_student.student_id', $student_id)
                                ->where('tae.subject_id', $subject_id)
                                ->where('tae.type', $type)->count();

                $chanceCount += 1;
            }
        } elseif ($examType == 'EPE') {
            if ($type == '1' || $type == '3') {
                $chanceCount = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                                ->where('epe_mark.student_id', $student_id)
                                ->where('epe.subject_id', $subject_id)
                                ->where('epe.type', $type)->count();
            } elseif ($type == '2') {
                $chanceCount = EpeMark::join('epe', 'epe.id', '=', 'epe_mark.epe_id')
                                ->where('epe_mark.student_id', $student_id)
                                ->where('epe.subject_id', $subject_id)
                                ->where('epe.type', $type)->count();
                $chanceCount += 1;
            }
        }

        return self::getChance($chanceCount);
    }

    //This function get the Position based on provided no of count
    //Example, 1=> 1st, 2=>2nd etc.
    public static function getChance($number = 0) {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . "<sup>th</sup> " . trans('english.CHANCE');
        else
            return $number . "<sup>" . $ends[$number % 10] . "</sup> " . trans('english.CHANCE');
    }

//EOF - getChance
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

    public static function findGrade($mark = 0) {
        $grades = GradingSystem::get();
        if ($grades->isNotEmpty()) {
            foreach ($grades as $grade) {
                if ($mark < 100) {
                    if ($grade->from_mark <= $mark && $grade->to_mark > $mark) {
                        return $grade->grade;
                    }
                } else {
                    if ($grade->from_mark <= $mark && $grade->to_mark >= $mark) {
                        return $grade->grade;
                    }
                }
            }
        }
    }

    public static function logGrnerate($userId, $loginDate, $typeId, $infoArr) {
        $userLogInfo = LogHistory::where(['user_id' => $userId, 'login_date' => $loginDate, 'type_id' =>$typeId])->first();
//        Helper::dump($userLogInfo);
        if (!empty($userLogInfo->login_info)) {
            $preLogInfo = json_decode($userLogInfo->login_info, true);
        }


        if (!empty($preLogInfo)) {
            $finalLogInfoJson = array_merge($preLogInfo, $infoArr);
            $logInfoJson = json_encode($finalLogInfoJson);
            $userLogInfo->login_info = $logInfoJson;
            if ($userLogInfo->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            $json_infoArr = json_encode($infoArr);
            $logHistory = new LogHistory;
            $logHistory->user_id = !empty($userId) ? $userId : '';
            $logHistory->login_date = !empty($loginDate) ? $loginDate : '';
            $logHistory->type_id = !empty($typeId) ? $typeId : '';
            $logHistory->login_info = $json_infoArr;
            if ($logHistory->save()) {
                return true;
            } else {
                return true;
            }
        }
    }
    
    

}
