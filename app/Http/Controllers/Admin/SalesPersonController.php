<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class SalesPersonController extends Controller
{
    public function getActiveLeads(){
        $leads = Booking::all(); 
        return view('admin.sales.leads.index')->with(['leads'=>$leads]);
    }
}
