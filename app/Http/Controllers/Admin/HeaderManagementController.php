<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Header;
use Illuminate\Http\Request;

class HeaderManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $headers = Header::where('status',1)->get();
        return view('admin.header.index')->with(['headers'=>$headers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.header.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
                'name'=>'required',
                'link'=>['required','regex:/^(https?:\/\/|\/)/']
            ]);

        $data = $request->all();
        Header::create($data);
        return redirect()->route('admin.headers.index')->with('success', 'Header Menu created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Header $header)
    {
        return view('admin.header.edit')->with(['header'=>$header]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Header $header)
    {
        $request->validate([
                'name'=>'required',
                'link'=>['required','regex:/^(https?:\/\/|\/)/']
        ]);

        $data = $request->all();
        $header->update($data);
        return redirect()->route('admin.headers.index')->with('success', 'Header Menu updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Header $header)
    {
        $header->delete();
        return redirect()->route('admin.headers.index')->with('success', 'Header Menu deleted successfully!');
    }
}
