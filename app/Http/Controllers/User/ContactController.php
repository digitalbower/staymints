<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contactSubmit(Request $request){
          $data = $request->validate([
            'name' => 'required',
            'email' =>'required',
            'phone' =>'required',
            'message'=>'required',
        ]);
        ContactUs::create($data);

        return redirect()->route('home.contact')->with('success', 'Message send successfully');
    }
}
