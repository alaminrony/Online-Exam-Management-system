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

class LibraryController extends Controller {

    public function index() {

        // load the view and pass the TAE index
        return view('library.index');
    }

    public function bookList($id = 0) {

        $fileArr = array(
            1 => 'CSTI-Library.pdf',
            2 => 'Central-Library.pdf'
        );

        $content = file_get_contents('public/pdf/'.$fileArr[$id]);
        return Response::make($content, 200, array('content-type' => 'application/pdf'));
    }

}
