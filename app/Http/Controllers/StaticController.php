<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 31.05.16
 * Time: 10:22
 */

namespace App\Http\Controllers;

use App\Deck;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class StaticController extends Controller {

    public function __construct() {

    }

    public function getPage($page_url, Request $request) {
        $page = Page::where('url', $page_url)->where('status', TRUE)->first();
        if ($page) {
            return view('static.template', ['page'=>$page, 'page_title'=>$page->title]);
        } else {
            abort(404);
        }
    }

}
