<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\EpeMark;
use App\EpeToQuestion;
use App\EpeMarkDetails;
use App\Exports\ExcelExport;
use App\LogHistory;
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
use DateTime;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LoginReportController extends Controller {

    private $controller = 'LoginReport';

    public function index(Request $request) {
//        Helper::dump($request->all());
        $userList = ['' => __('label.SELECT_USER_OPT')] + LogHistory::join('users', 'users.id', 'history_logging.user_id')
                        ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as user_name"), 'history_logging.user_id as user_id')
                        ->where('history_logging.type_id', 1)
                        ->pluck('user_name', 'user_id')
                        ->toArray();

        $targetArr = [];
        if ($request->generate == 'true') {
            $loginHistory = LogHistory::select('*')
                    ->whereBetween('history_logging.login_date', [$request->from_date, $request->to_date])
                    ->where('type_id', '1');
            if (!empty($request->user_id)) {
                $loginHistory = $loginHistory->where('history_logging.user_id', $request->user_id);
            }

            $loginHistory = $loginHistory->get();


            //Prepare Final Array
            $totalLogArr = [];
            if ($loginHistory->isNotEmpty()) {
                foreach ($loginHistory as $loginResult) {
                    $totalLogArr[$loginResult->login_date][$loginResult->user_id] = !empty($loginResult->login_info) ? json_decode($loginResult->login_info, true) : '';
                }
            }
            $i = 1;
            $targetArr=[];
            if (!empty($totalLogArr)) {
                foreach ($totalLogArr as $date => $userDataArr) {
                    foreach ($userDataArr as $userId => $userDetailsArr) {
                        foreach ($userDetailsArr as $uniquKey => $logData) {
                            $targetArr[$i]['date'] = $date;
                            $targetArr[$i]['affected_user_id'] = $userId;
                            $targetArr[$i]['ip_address'] = $logData['browser_ip'];
                            $targetArr[$i]['browser'] = $logData['browser'];
                            $targetArr[$i]['operating_system'] = $logData['operating_system'];
                            $targetArr[$i]['login_datetime'] = $logData['login_datetime'];
                            $targetArr[$i]['logout_datetime'] = $logData['logout_datetime'];
                            $i++;
                        }
                    }
                }
            }
           

        if ($request->view == 'print') {
            return view('logReport.loginReport.printLoginReport')->with(compact('targetArr','request'));
        } else if ($request->view == 'pdf') {
            $pdf = PDF::loadView('logReport.loginReport.printLoginReport', compact('targetArr','request'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('loginLogoutReport.pdf');
        } else if ($request->view == 'excel') {
            $viewFile = 'logReport.loginReport.printLoginReport';
            $downLoadFileName = 'loginLogoutReport.xlsx';
            $data['targetArr'] = $targetArr;
            $data['request'] = $request;
            return Excel::download(new ExcelExport($viewFile, $data), $downLoadFileName);
        }
        }
        return view('logReport.loginReport.loginReport')->with(compact('targetArr', 'userList', 'request'));
    }

    public function generate(Request $request) {
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];
        if (!empty($request->from_date) && !empty($request->to_date)) {
            if ($request->from_date > $request->to_date) {
                $rules = [
                    'to_date' => ['after_or_equal:from_date'],
                ];
            }
        }
        $validator = Validator::make($request->all(), $rules);

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        if ($validator->fails()) {
            return redirect('loginReport?' . $url)->withErrors($validator);
        }
        return redirect('loginReport?generate=true&' . $url);
    }

}
