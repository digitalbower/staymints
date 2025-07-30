<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesPersonController extends Controller
{
    public function getActiveLeads(){
        $leads = Booking::where('status', 0)->get(); 
        return view('admin.sales.leads.active_leads')->with(['leads'=>$leads]);
    }
    public function getWorkingLeads(){
        $leads = Booking::where('sales_person_id',Auth::guard('admin')->user()->id)->where('status',1)->get(); 
        return view('admin.sales.leads.working_leads')->with(['leads'=>$leads]);
    }
    public function getCompletedLeads(){
        $leads = Booking::where('sales_person_id',Auth::guard('admin')->user()->id)->where('status',2,)->get(); 
        return view('admin.sales.leads.completed_leads')->with(['leads'=>$leads]);
    }
    public function getLossLeads(){
        $leads = Booking::where('sales_person_id',Auth::guard('admin')->user()->id)->where('status',3)->get(); 
        return view('admin.sales.leads.loss_leads')->with(['leads'=>$leads]);
    }
    public function assignSalesPerson(Request $request){ 
        $booking = Booking::find($request->id);
        $booking->sales_person_id = Auth()->guard('admin')->user()->id;
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['message' => 'Lead assigned successfully!']);
        
    }
    public function changeWorkingLeads(Request $request){ 
        $booking = Booking::find($request->id);
        $booking->status = $request->status;
        $booking->sales_person_id = Auth()->guard('admin')->user()->id;

         if ($request->status == 3) {
            $booking->loss_reason = $request->reason;
        } elseif ($request->status == 2) {
            $booking->booking_reference = $request->booking_reference;
            $booking->total = $request->total;
            $booking->markup_percent = $request->markup_percent;
            $booking->markup_value = $request->markup_value;
        }

        $booking->save();

        return response()->json(['message' => 'Lead assigned successfully!']);
        
    }
}
