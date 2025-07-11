<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $footers = Footer::where('status',1)->get();
        return view('admin.footers.index')->with(['footers' => $footers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.footers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'item_text' => 'required_if:type,Quick Links|string|nullable',
            'icon' => 'required_if:type,Payment Partners,Follow on Us|string|nullable',
            'link' => ['required_if:type,Quick Links','nullable', 'regex:/^(https?:\/\/|\/)/'],
        ]);
        $data = $request->all();
        Footer::create($data);
        return redirect()->route('admin.footers.index')->with('success', 'Footer created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Footer $footer)
    {
        return view('admin.footers.edit')->with(['footer' => $footer]);;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Footer $footer)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'item_text' => 'required_if:type,Quick Links|string|nullable',
            'icon' => 'required_if:type,Payment Partners,Follow Us|string|nullable',
            'link' => ['required_if:type,Quick Links','nullable', 'regex:/^(https?:\/\/|\/)/'],
        ]);
        $data = $request->all();
        $footer->update($data);
        return redirect()->route('admin.footers.index')->with('success', 'Footer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Footer $footer)
    {
        $footer->delete(); 
        return redirect()->route('admin.footers.index')->with('success', 'Footer deleted successfully!');
    }
}
