<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainSeo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeoManagementController extends Controller
{
    public function  index(){
        $pages = MainSeo::get();
        return view('admin.seo.main.index', compact('pages'));
    }
    public function create()
    {
        return view('admin.seo.main.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'page_url' => 'required|string|unique:main_seos,page_url',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:255',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schema' => 'nullable|string',
        ]);

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo_images', 'public');
        }

        MainSeo::create($data);

        return redirect()->route('admin.seo.index')->with('success', 'Main SEO Pages created successfully');
    }
    public function edit(MainSeo $seo)
    {
        return view('admin.seo.main.edit', compact('seo'));
    }

    public function update(Request $request, MainSeo $seo)
    {
        $data = $request->validate([
            'page_url' => [
                'required',
                'string',
                Rule::unique('main_seos', 'page_url')->ignore($seo->id),
            ],
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:255',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schema' => 'nullable|string',
        ]);

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo_images', 'public');
        }

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'Main SEO Pages updated successfully');
    }
}
