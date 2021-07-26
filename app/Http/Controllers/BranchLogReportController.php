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

class BranchLogReportController extends Controller {

    public function index(Request $request) {
//        Helper::dump($request->all());
        $targetArr = [];
        $userList = ['' => __('label.SELECT_USER_OPT')] + User::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as user_name"), 'users.id as user_id')
                        ->pluck('user_name', 'user_id')
                        ->toArray();
        $branchList = Branch::withTrashed()->pluck('name', 'id')->toArray();
        if ($request->generate == 'true') {
            $loginHistory = LogHistory::select('*')
                    ->whereBetween('history_logging.login_date', [$request->from_date, $request->to_date])
                    ->where('type_id', '5');
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
            if (!empty($totalLogArr)) {
                foreach ($totalLogArr as $date => $userDataArr) {
                    foreach ($userDataArr as $userId => $userDetailsArr) {
                        foreach ($userDetailsArr as $uniquKey => $logData) {
                            $targetArr[$i]['date'] = $date;
                            $targetArr[$i]['affected_branch_id'] = $logData['branch_id'];
                            $targetArr[$i]['reforming_user_id'] = $userId;
                            $targetArr[$i]['action'] = $logData['action'];
                            $targetArr[$i]['date_time'] = $logData['date_time'];
                            $targetArr[$i]['ip_address'] = $logData['ip_address'];
                            $targetArr[$i]['browser'] = $logData['browser'];
                            $targetArr[$i]['operating_system'] = $logData['operating_system'];
                            $i++;
                        }
                    }
                }
            }
        }

        if ($request->view == 'print') {
            return view('logReport.branchLogReport.printBranchLogReport')->with(compact('targetArr', 'request', 'userList', 'branchList'));
        } else if ($request->view == 'pdf') {

            $pdf = PDF::loadView('logReport.branchLogReport.printBranchLogReport', compact('targetArr', 'request', 'userList', 'branchList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('branchLogReport.pdf');
        } else if ($request->view == 'excel') {
            $viewFile = 'logReport.branchLogReport.printBranchLogReport';
            $downLoadFileName = 'branchLogReport.xlsx';
            $data['targetArr'] = $targetArr;
            $data['request'] = $request;
            $data['userList'] = $userList;
            $data['branchList'] = $branchList;
            return Excel::download(new ExcelExport($viewFile, $data), $downLoadFileName);
        }

        return view('logReport.branchLogReport.branchLog')->with(compact('targetArr', 'request', 'userList', 'branchList'));
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
            return redirect('branchLogReport?' . $url)->withErrors($validator);
        }
        return redirect('branchLogReport?generate=true&' . $url);
    }

}
