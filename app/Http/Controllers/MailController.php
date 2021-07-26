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

class MailController extends Controller {

    public function index() {
        

    	Mail::send('emails.test', array('key' => 'value'), function($message) {
                $message->to('iqbal.hossen@swapnoloke.com', 'Iqbal')->subject('Welcome!');
            });



        // $address = Input::get('address');
        
        // echo $address.'<br />';
        // if(mail($address, 'Hello TEST', 'This is a test message')){
        //     echo 'SUCCESS! Mail sent.';
        // }else{
        //     echo 'ERROR! Mail sending failed!';
        // }
                
        
    }

}
