<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('users.index');
    }
    public function about(){
        return view('users.about');
    }
    public function login(){
        return view('users.login');
    }
    public function preview(){
        return view('users.preview');
    }
    public function packages(){
        return view('users.packages');
    }
    public function packageDetail(){
        return view('users.package_detail');
    }
    public function contact(){
        return view('users.contact');
    }
}
