<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Country;
use App\Models\Package;
use App\Models\Tag;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index')->with(['packages'=>$packages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales_persons = Admin::where('user_role_id',3)->get();
        $tags = Tag::where('status',1)->get();
        $countries = Country::where('status',1)->get();
        $categories = Category::where('status',1)->get();
        $types = UnitType::where('status',1)->get();
        return view('admin.packages.create')->with(['sales_persons'=>$sales_persons,'tags'=>$tags,'countries'=>$countries,'categories'=>$categories,'types'=>$types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_name'=>'required', 
            'country_id' =>'required',
            'category_id' =>'required',
            'tag_id' =>'required',
            'sales_person_id' =>'required',
            'unit_type_id' =>'required',
            'starting_price' =>'required',
            'gallery' => 'required', 
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' =>'required',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt,webm|max:51200',
            'inclusions' => 'required|array',
            'inclusions.*' => 'required',
            'duration' =>'required',
            'overview' =>'required',
            'highlights' =>'required',
            'included' =>'required|array',
            'included.*' => 'required',
            'excluded' =>'required|array',
            'excluded.*' => 'required',
            'extra_services'=> 'required|array',
            'extra_services.*' => 'required',
            'itinerary_desc' =>'required',
            'itineraries' => 'required|array',
            'itineraries.*.day_number' => 'required',
            'itineraries.*.title' => 'required',
            'itineraries.*.description' => 'required',
            'meta_title' =>'required',
            'meta_description' =>'required',
        ],
        [
            'gallery.required' => 'Please upload at least one image.',
            'gallery.*.image' => 'Each file must be a valid image.', 
            'gallery.*.mimes' => 'Only JPG, JPEG, PNG, and GIF formats are allowed.', 
            'gallery.*.max' => 'Each image must not exceed 2MB.', 
        ]);

        $data = $request->all(); 
        $data['slug'] = Str::slug($request->package_name, '-');
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('packages/images', 'public');  
            $data['image'] = $path; 
        }

        if ($request->hasFile('gallery')) {
            $galleryArray = [];
    
            
            $files = is_array($request->file('gallery')) ? $request->file('gallery') : [$request->file('gallery')];
    
            foreach ($files as $image) {
                $path = $image->store('packages', 'public');  
                $galleryArray[] = $path;
            }
                
            $data['gallery'] = json_encode($galleryArray);
        }
        if ($request->hasFile('slide_show')) {
            $slideShowArray = [];
    
            
            $files = is_array($request->file('slide_show')) ? $request->file('slide_show') : [$request->file('slide_show')];
    
            foreach ($files as $image) {
                $path = $image->store('packages/slide_show', 'public');  
                $slideShowArray[] = $path;
            }
                
            $data['slide_show'] = json_encode($slideShowArray);
        }
        if ($request->hasFile('og_image')) {
            $og_image = $request->file('og_image');
            $path_og_image = $og_image->store('seo_images', 'public');  
            $data['og_image'] = $path_og_image; 
        } 
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('packages/videos', 'public');
            $data['video'] = $videoPath; // e.g. videos/filename.mp4
        }
        $data['inclusions'] = $request->inclusions;
        $data['included'] = $request->included;
        $data['excluded'] = $request->excluded;
        $data['extra_services'] = $request->extra_services;
        $package = Package::create($data); 
        foreach ($request->itineraries as $dayPlan) {
            $package->itineraries()->create([
                'package_id' => $package->id,
                'day_number' => $dayPlan['day_number'],
                'title'      =>$dayPlan['title'],
                'description' => $dayPlan['description'],
            ]);
        }
        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully!');
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
    public function edit(Package $package)
    {
        $sales_persons = Admin::where('user_role_id',3)->get();
        $tags = Tag::where('status',1)->get();
        $countries = Country::where('status',1)->get();
        $categories = Category::where('status',1)->get();
        $types = UnitType::where('status',1)->get();
        return view('admin.packages.edit')->with(['sales_persons'=>$sales_persons,'tags'=>$tags,'countries'=>$countries,'categories'=>$categories,'types'=>$types,'package'=>$package]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Package $package)
    {
        $request->validate([
            'package_name'=>'required', 
            'country_id' =>'required',
            'category_id' =>'required',
            'tag_id' =>'required',
            'sales_person_id' =>'required',
            'unit_type_id' =>'required',
            'starting_price' =>'required',
            'gallery' => 'nullable', 
            'gallery.*' => 'image|mimes:jpeg,png,jpg,|max:2048',
            'image' =>'nullable',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt,webm|max:51200',
            'slide_show' => 'nullable', 
            'slide_show.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'inclusions' => 'array',
            'inclusions.*' => 'nullable',
            'extra_services'=> 'array',
            'extra_services.*' => 'nullable',
            'duration' =>'required',
            'itinerary_desc' =>'required',
            'overview' =>'required',
            'highlights' =>'required',
            'included' =>'array',
            'included.*' => 'nullable',
            'excluded' =>'array',
            'excluded.*' => 'nullable',
            'itineraries.*.day_number' => 'nullable|required_with:itineraries.*.title,itineraries.*.description|integer',
            'itineraries.*.title' => 'nullable|required_with:itineraries.*.day_number,itineraries.*.description|string|max:255',
            'itineraries.*.description' => 'nullable|required_with:itineraries.*.day_number,itineraries.*.title|string',
            'meta_title' =>'required',
            'meta_description' =>'required',
        ],
        [
            'gallery.*.image' => 'Each file must be a valid image.', 
            'gallery.*.mimes' => 'Only JPG, JPEG and PNG formats are allowed.', 
            'gallery.*.max' => 'Each image must not exceed 2MB.', 
        ]);

        $data = $request->all(); 
        $data['slug'] = Str::slug($request->package_name, '-');
        $galleryArray = $package->gallery ? json_decode($package->gallery, true) : [];

        $removedImages = json_decode($request->input('removed_images'), true);

        $slideShowArray = $package->slide_show ? json_decode($package->slide_show, true) : [];

        $removedSlideImages = json_decode($request->input('removed_slide_images'), true);
        
        // Remove images from storage and gallery array
        if (!empty($removedImages)) {
            foreach ($removedImages as $path) {
                Storage::delete('public/' . $path); // Delete from disk
        
                // Remove from gallery array
                if (($key = array_search($path, $galleryArray)) !== false) {
                    unset($galleryArray[$key]);
                }
            }
        
            // Re-index the array to keep it clean
            $galleryArray = array_values($galleryArray);
        }

         // Remove images from storage and gallery array
        if (!empty($removedSlideImages)) {
            foreach ($removedSlideImages as $path) {
                Storage::delete('public/' . $path); // Delete from disk
        
                // Remove from gallery array
                if (($key = array_search($path, $slideShowArray)) !== false) {
                    unset($slideShowArray[$key]);
                }
            }
        
            // Re-index the array to keep it clean
            $slideShowArray = array_values($slideShowArray);
        }
        
        // Add new uploaded images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('packages', 'public');
                $galleryArray[] = $path;
            }
        }
        if ($request->hasFile('slide_show')) {
            foreach ($request->file('slide_show') as $image) {
                $path = $image->store('packages/slide_show', 'public');
                $slideShowArray[] = $path;
            }
        }
        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $image = $request->file('image');
            $path = $image->store('packages/images', 'public');  
            $data['image'] = $path; 
        }
        if ($request->hasFile('og_image')) {
            if ($package->og_image) {
                Storage::disk('public')->delete($package->og_image);
            }
            $og_image = $request->file('og_image');
            $path_og_image = $og_image->store('seo_images', 'public');  
            $data['og_image'] = $path_og_image; 
        }
        if ($request->hasFile('video')) {
            // Optionally delete old video
            if ($package->video && Storage::disk('public')->exists($package->video)) {
                Storage::disk('public')->delete($package->video);
            }

            // Upload new video
            $videoPath = $request->file('video')->store('videos', 'public');
            $data['video'] = $videoPath;
        }
        if (!empty($galleryArray)) {
            $data['gallery'] = json_encode($galleryArray);
        }
        if (!empty($slideShowArray)) {
            $data['slide_show'] = json_encode($slideShowArray);
        }
        $data['inclusions'] = array_filter($request->input('inclusions', []), fn($value) => trim($value) !== '');
        $data['included'] = array_filter($request->input('included', []), fn($value) => trim($value) !== '');
        $data['excluded'] = array_filter($request->input('excluded', []), fn($value) => trim($value) !== '');
        $data['extra_services'] = array_filter($request->input('extra_services', []), fn($value) => trim($value) !== '');
        $package->update($data);
        $itineraries = collect($request->input('itineraries', []))
            ->filter(fn($i) => !empty($i['day_number']) || !empty($i['title']) || !empty($i['description']))
            ->values()
            ->toArray();

        // Step 1: Delete all existing itineraries
        $package->itineraries()->delete();

        // Step 2: Insert fresh ones
        foreach ($itineraries as $dayPlan) {
            $package->itineraries()->create([
                'day_number' => $dayPlan['day_number'],
                'title' => $dayPlan['title'],
                'description' => $dayPlan['description'],
            ]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->itineraries()->delete();
        $package->delete(); 
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
    }
    public function changeStatus(Request $request)
    { 
        $package = Package::findOrFail($request->id); 
        $package->status = $request->status;
        $package->save();

        return response()->json(['message' => 'Package status updated successfully!']);
    }
}
