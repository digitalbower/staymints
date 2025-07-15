<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index')->with(['categories'=>$categories]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required',
            'image' =>'required',
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('categories/images', 'public');  
            $data['image'] = $path; 
        }
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'New category created successfully');
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
    public function edit(Category $category)
    {
        return view('admin.category.edit')->with(['category'=>$category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'category_name' => 'required',
            'image' =>'nullable',
        ]);
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $image = $request->file('image');
            $path = $image->store('categories/images', 'public');  
            $data['image'] = $path; 
        }
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
    public function changeStatus(Request $request)
    { 
        $category = Category::findOrFail($request->id); 
        $category->status = $request->status;
        $category->save();

        return response()->json(['message' => 'Category status updated successfully!']);
    }
}
