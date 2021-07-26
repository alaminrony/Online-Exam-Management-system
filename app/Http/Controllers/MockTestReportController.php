<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\EpeMark;
use App\EpeToQuestion;
use App\EpeMarkDetails;
use App\Exports\ExcelExport;
use App\Epe;
use App\MockTest;
use App\MockMark;
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

class MockTestReportController extends Controller {

    private $controller = 'MockTestReport';

    public function index(Request $request) {
//        Helper::dump($request->all());
        $mockInfoArr = MockTest::select('mock_test.id', DB::raw("CONCAT(mock_test.title,' | Date: ', DATE_FORMAT(mock_test.created_at, '%M %d, %Y')) AS mock_test_title"));
        if (Auth::user()->group_id == '3') {
            $mockInfoArr->join('epe', 'epe.id', '=', 'mock_test.epe_id')
                    ->join('epe_mark', 'epe_mark.epe_id', '=', 'epe.id')
                    ->where('epe_mark.employee_id', Auth::user()->id);
        }

        $mockInfoArr = $mockInfoArr->pluck('mock_test_title', 'id')
                ->toArray();

        $employeeArr =  MockTest::select(DB::raw("CONCAT(users.first_name,'',users.last_name,'(',users.username,')') as employee_name"), 'mock_mark.employee_id as id')
                        ->join('mock_mark', 'mock_mark.mock_id', '=', 'mock_test.id')
                        ->join('users', 'users.id', '=', 'mock_mark.employee_id')
                        ->where('mock_test.id', $request->mock_id)
                        ->pluck('employee_name', 'id')
                        ->toArray();

        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')]+$employeeArr;

//        $employeeArr = MockMark::join('users', 'users.id', '=', 'mock_mark.employee_id')
//                        ->select(DB::raw("CONCAT(users.first_name,'',users.last_name,'(',users.username,')') as employee_name"), 'mock_mark.employee_id as employee_id')->pluck('employee_name', 'employee_id')->toArray();
//        Helper::dump($employeeArr);
        $mockTestResult = MockMark::join('users', 'users.id', '=', 'mock_mark.employee_id')
                ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name,'(',users.username,')') as employee_name"), 'mock_mark.mock_id', 'mock_mark.employee_id'
                , 'mock_mark.total_mark', 'mock_mark.pass_mark', 'mock_mark.converted_mark');
        if (Auth::user()->group_id == '3') {
            $mockTestResult = $mockTestResult->where('mock_mark.employee_id', Auth::user()->id);
        }

        if (!empty($request->mock_id)) {
            $mockTestResult = $mockTestResult->where('mock_mark.mock_id', $request->mock_id);
        }
        if (!empty($request->employee_id)) {
            $mockTestResult = $mockTestResult->where('mock_mark.employee_id', $request->employee_id);
        }

        $downloadFileName = 'mockTestResult-' . date('d-m-Y');
        if ($request->view == 'print') {
            $mockTestResult = $mockTestResult->get();
            return view('report.mockTestResult.printMockTestReport')->with(compact('mockTestResult', 'request', 'mockInfoArr', 'employeeArr'));
        } else if ($request->view == 'pdf') {
            $mockTestResult = $mockTestResult->get();
            $pdf = PDF::loadView('report.mockTestResult.printMockTestReport', compact('mockTestResult', 'request', 'mockInfoArr', 'employeeArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($downloadFileName . '.pdf');
        } else if ($request->view == 'excel') {
            $mockTestResult = $mockTestResult->get();
            $viewFile = 'report.mockTestResult.printMockTestReport';
            $downloadFileName = $downloadFileName . '.xlsx';
            $data['mockTestResult'] = $mockTestResult;
            $data['request'] = $request;
            $data['mockInfoArr'] = $mockInfoArr;
            $data['employeeArr'] = $employeeArr;
            return Excel::download(new ExcelExport($viewFile, $data), $downloadFileName);
        }

        $mockTestResult = $mockTestResult->get();
//        Helper::dump($mockTestResult->toArray());
        return view('report.mockTestResult.mockTestReport', compact('mockInfoArr', 'employeeArr', 'mockTestResult', 'request'));
    }

    public function getEmployee(Request $request) {
        $employeeArr = ['' => __('label.SELECT_EMPLOYEE_OPT')] + MockTest::select(DB::raw("CONCAT(users.first_name,'',users.last_name,'(',users.username,')') as employee_name"), 'mock_mark.employee_id as id')
                        ->join('mock_mark', 'mock_mark.mock_id', '=', 'mock_test.id')
                        ->join('users', 'users.id', '=', 'mock_mark.employee_id')
                        ->where('mock_test.id', $request->mock_id)
                        ->pluck('employee_name', 'id')->toArray();

        $data['employeeArr'] = $employeeArr;

        $returnHTML = view('report.mockTestResult.getEmployee', $data)->render();
        return Response::json(array('success' => true, 'html' => $returnHTML));
    }

    public function generate(Request $request) {
        $rules = [
            'mock_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        $url = '&mock_id=' . $request->mock_id . '&employee_id=' . $request->employee_id;
        if ($validator->fails()) {
            return redirect('mockTestReport?' . $url)->withErrors($validator);
        }
        return redirect('mockTestReport?generate=true&' . $url);
    }

}
