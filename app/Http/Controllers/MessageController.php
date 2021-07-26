<?php
namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\PhaseToSubject;
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

class MessageController extends Controller {


    public function index() {
        // load the view and pass the TAE index
		//Get Current date time
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');
        $currentDate = $nowDateObj->format('Y-m-d');
		/* scroll message start code */
						
		$scrollmessageList = Message::leftJoin('message_scope','message_scope.message_id', '=', 'message.id')
							->where('message.from_date','<=',DB::raw("'".$currentDate."'"))
                            ->where('message.to_date','>=',DB::raw("'".$currentDate."'"))
                            ->where('message.status', '1')
							->where('message_scope.scope_id',Session::get('program_id'))
                            ->orderBy('message.from_date', 'DESC')
							->get();				
	 $data['scrollmessageList'] = $scrollmessageList;
	/* scroll message end code */
        return view('message.index',$data);
    }

}

?>
