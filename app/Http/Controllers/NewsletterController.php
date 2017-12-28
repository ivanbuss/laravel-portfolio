<?php

namespace App\Http\Controllers;

use App\NewsletterEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function postSave(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        NewsletterEmail::create([
            'email'=>$request->get('newsletter_email')
        ]);

        return redirect()->back();
    }

    public function validator(array $data) {
        return Validator::make($data, [
            'newsletter_email' => 'required|email|max:255|unique:newsletter_emails,email',
        ]);
    }

}
