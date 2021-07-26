<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Branch;
use App\Message;
use App\Configuration;
use App\Gallery;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Response;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index() {

        //Get Current date time
        $nowDateObj = new DateTime();
        $currentDateTime = $nowDateObj->format('Y-m-d H:i:s');
        $currentDate = $nowDateObj->format('Y-m-d');
        /* scroll message start code */
        $scrollmessageList = Message::leftJoin('message_scope', 'message_scope.message_id', '=', 'message.id')
                ->where('message.from_date', '<=', DB::raw("'" . $currentDate . "'"))
                ->where('message.to_date', '>=', DB::raw("'" . $currentDate . "'"))
                ->where('message.status', '1')
                ->where('message_scope.scope_id', 3)
                ->orderBy('message.from_date', 'DESC')
                ->get();
        $data['scrollmessageList'] = $scrollmessageList;

        $configurationArr = Configuration::first();
        $data['configurationArr'] = $configurationArr;

        $str = $configurationArr->about_us;
        $aboutUs = $this->truncateString($str, 700, true) . "\n";
        $data['aboutUs'] = $aboutUs;

        $galleryArr = Gallery::where('home', 1)->where('status', '=', '1')->orderBy('order', 'ASC')->limit(3)->get();
        $data['galleryArr'] = $galleryArr;

         return view('home.home_page',$data);
    }

    public function aboutUs() {
        $configurationArr = Configuration::first();
        $data['configurationArr'] = $configurationArr;
        return View('home.about_us', $data);
    }

    public function history() {
        $configurationArr = Configuration::first();
        $data['configurationArr'] = $configurationArr;
        return View('home.history', $data);
    }

    public function gallery() {
        $configurationArr = Configuration::first();
        $data['configurationArr'] = $configurationArr;
        $galleryArr = Gallery::where('status', '=', '1')->orderBy('order', 'ASC')->paginate(__('label.PAGINATION_GALLERY_COUNT'));
        $data['galleryArr'] = $galleryArr;

        return View('home.photo_gallery', $data);
    }

    private function truncateString($str, $chars, $to_space, $replacement = "...") {
        if ($chars > strlen($str))
            return $str;

        $str = substr($str, 0, $chars);
        $space_pos = strrpos($str, " ");
        if ($to_space && $space_pos >= 0)
            $str = substr($str, 0, strrpos($str, " "));

        return($str . $replacement);
    }

    public function ejournal() {

        $file = 'public/pdf/E-Journal.pdf';
        $content = file_get_contents($file);
        return Response::make($content, 200, array('content-type' => 'application/pdf'));
    }

}
