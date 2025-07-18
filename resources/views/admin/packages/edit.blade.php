@extends('admin.layouts.master')
@section('title', 'Packages')
@push('styles')
<style>
/* ✅ Make bold/strong appear bold */
.note-editable b,
.note-editable strong {
    font-weight: bold !important;
}

/* ✅ Make italic/em show as italic */
.note-editable i,
.note-editable em {
    font-style: italic !important;
}

/* ✅ Make underline appear */
.note-editable u {
    text-decoration: underline !important;
}

/* ✅ Make ordered and unordered lists visible */
.note-editable ol,
.note-editable ul {
    list-style: inside !important;
    padding-left: 20px;
}

/* ✅ Ensure list items display properly */
.note-editable li {
    display: list-item;
}

/* Optional: override span bold if used inline */
.note-editable span[style*="font-weight: bold"] {
    font-weight: bold !important;
}
</style>
@endpush
@section('content')
  
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                <h5 class="mb-0 font-medium">Package</h5>
                </div>
                <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Packages</a></li>
                <li class="breadcrumb-item" aria-current="page">Edit Package</li>
                </ul>
            </div>
            </div>
            <h4 class="text-start mb-4">Edit Package</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('admin.packages.update', $package->id) }}" id="packageForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

                <div class="mb-3">
                    <label for="package_name" class="form-label">Package Name</label>
                    <input type="text" class="form-control" id="package_name" name="package_name" value="{{$package->package_name }}">
                </div>
                <div class="mb-4">
                    <label for="country_id" class="form-label">Country</label>
                    <select class="form-control" name="country_id" id="country_id">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ $package->country_id == $country->id ? 'selected' : '' }}>
                                {{ $country->country_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $package->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="tag_id" class="form-label">Tags</label>
                    <select class="form-control" name="tag_id" id="tag_id">
                        <option value="">Select Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ $package->tag_id == $tag->id ? 'selected' : '' }}>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="sales_person_id" class="form-label">Contract Person</label>
                    <select class="form-control" name="sales_person_id" id="sales_person_id">
                        <option value="">Select Contract Person</option>
                        @foreach ($sales_persons as $sales_person)
                            <option value="{{ $sales_person->id }}" {{ $package->sales_person_id == $sales_person->id ? 'selected' : '' }}>
                                {{ $sales_person->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="unit_type_id" class="form-label">Unit Types</label>
                    <select class="form-control" name="unit_type_id" id="unit_type_id">
                        <option value="">Select Unit Type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ $package->unit_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="starting_price" class="form-label">Starting price</label>
                    <input type="text" class="form-control" id="starting_price" name="starting_price" value="{{$package->starting_price }}">
                </div>
                <div class="mb-3">
                    <div id="existing-image-preview" class="mt-2 d-flex flex-wrap">
                    @if (!empty($package->image))
                        <div class="position-relative me-2 mb-2">
                            <img src="{{ asset('storage/' . $package->image) }}" class="img-thumbnail" width="100">
                        </div>
                    @endif
                    </div>
                    <label for="image" class="form-label">Package Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-2 d-flex flex-wrap"></div>
                
                    <div id="image-error" class="text-danger mt-1" style="display: none;">Please upload an image.</div>
                </div>
                
                <div class="mb-3">
                    <div id="existing-images">
                    @if(!empty($package->gallery))
                        @foreach(json_decode($package->gallery, true) as $index => $image)
                            <div class="image-wrapper" style="display:inline-block; position:relative; margin:5px;" data-index="{{ $index }}" data-path="{{ $image }}">
                            <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" width="100">
                            <button type="button" class="btn-close remove-existing-image" data-index="{{ $index }}"></button>
                            </div>
                        @endforeach
                    @endif
                    </div>
                    <input type="hidden" name="removed_images" id="removed_images" value="">
                    <label for="gallery" class="form-label">package Gallery</label>
                    <input type="file" class="form-control" id="gallery" name="gallery[]" multiple accept="image/*">
                    <div id="images-preview" class="d-flex flex-wrap mt-3"></div>
                    <div id="images-error" class="text-danger mt-1" style="display: none;">Please upload at least one valid image.</div>
                </div>
               
                <div class="mb-3">
                    @if($package->video)
                        <video width="100" height="100" controls>
                            <source src="{{ asset('storage/' . $package->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                    <label for="video" class="form-label">Package Video</label>
                    <input type="file" class="form-control" id="video" name="video" accept="video/*">
                    
                    <!-- Video Preview -->
                    <div id="video-preview" class="mt-2 d-flex flex-wrap"></div>
                
                    <div id="video-error" class="text-danger mt-1" style="display: none;">Please upload a video.</div>
                </div>
                 <div class="mb-3">
                    <div id="existing-slide-images">
                    @if(!empty($package->slide_show))
                        @foreach(json_decode($package->slide_show, true) as $index => $slide)
                            <div class="slide-wrapper" style="display:inline-block; position:relative; margin:5px;" data-index="{{ $index }}" data-path="{{ $slide }}">
                            <img src="{{ asset('storage/' . $slide) }}" class="img-thumbnail" width="100">
                            <button type="button" class="btn-close remove-existing-slide-image" data-index="{{ $index }}"></button>
                            </div>
                        @endforeach
                    @endif
                    </div>
                    <input type="hidden" name="removed_slide_images" id="removed_slide_images" value="">
                    <label for="slide_show" class="form-label">Package Gallery More</label>
                    <input type="file" class="form-control" id="slide_show" name="slide_show[]" multiple accept="image/*">
                    <div id="slide-preview" class="d-flex flex-wrap mt-3"></div>
                    <div id="slide-error" class="text-danger mt-1" style="display: none;">Please upload valid image.</div>
                </div>
                <div id="input-inclusions-group-wrapper">
                    <label for="included" class="form-label">Inclusions</label>
                    @foreach($package->inclusions as $index => $ineitem)
                    <div class="input-group mb-3 included">
                        <input type="text" name="inclusions[]" class="form-control" value="{{$ineitem}}">
                        <button type="button" class="btn btn-danger remove-inclusions-btn">-</button>
                    </div>
                    @endforeach
                    <div class="input-group mb-3 included">
                        <input type="text" name="inclusions[]" class="form-control" placeholder="Enter inclusion">
                        <button type="button" class="btn btn-success add-inclusions-btn">+</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (Nights)</label>
                    <input type="number" class="form-control" id="duration" name="duration" value="{{$package->duration }}">
                </div>
                <div class="mb-3">
                    <label for="group_size" class="form-label">Group size</label>
                    <input type="text" class="form-control" id="group_size" name="group_size" value="{{$package->group_size }}">
                </div>
                <div class="mb-3">
                    <label for="overview" class="form-label">Overview</label>
                    <textarea class="form-control" id="overview" name="overview">{{$package->overview }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="highlights" class="form-label">Highlights</label>
                    <textarea class="form-control" id="highlights" name="highlights">{{$package->highlights }}</textarea>
                </div>
                <div id="input-included-group-wrapper">
                    <label for="included" class="form-label">Includes</label>
                    @foreach($package->included as $index => $item)
                    <div class="input-group mb-3 included">
                     
                        <input type="text" name="included[]" class="form-control" value="{{ $item }}"  placeholder="Enter includes">
                         <button type="button" class="btn btn-danger remove-included-btn">-</button>
                    </div>
                    @endforeach
                    <div class="input-group mb-3 included">
                        <input type="text" name="included[]" class="form-control"  placeholder="Enter includes">
                            
                        <button type="button" class="btn btn-success add-included-btn">+</button>
                    </div>
                </div>
                <div id="input-excluded-group-wrapper">
                    <label for="excluded" class="form-label">Excluded</label>
                    @foreach($package->excluded as $index => $eitem)
                    <div class="input-group mb-3 excluded">
                        <input type="text" name="excluded[]" class="form-control" value="{{ $eitem }}">
                        <button type="button" class="btn btn-danger remove-excluded-btn">-</button>
                    </div>
                     @endforeach
                    <div class="input-group mb-3 excluded">
                        <input type="text" name="excluded[]" class="form-control" placeholder="Enter excludes">
                        <button type="button" class="btn btn-success add-excluded-btn">+</button>
                    </div>
                </div>
                 <div id="input-extra-services-group-wrapper">
                    <label for="extra_services" class="form-label">Extra services</label>
                    @foreach($package->extra_services as $index => $exitem)
                    <div class="input-group mb-3 excluded">
                        <input type="text" name="extra_services[]" class="form-control" value="{{ $exitem }}">
                        <button type="button" class="btn btn-danger remove-extra-services-btn">-</button>
                    </div>
                     @endforeach
                    <div class="input-group mb-3 extra_services">
                        <input type="text" name="extra_services[]" class="form-control" placeholder="Enter extra services">
                        <button type="button" class="btn btn-success add-extra-services-btn">+</button>
                    </div>
                </div>
                   <div class="mb-3">
                    <label for="itinerary_desc" class="form-label">Itinerary Description</label>
                    <textarea class="form-control" id="itinerary_desc" name="itinerary_desc">{{ $package->itinerary_desc }}</textarea>
                </div>
                <div id="itinerary-wrapper">
                    <label for="included" class="form-label">Itinerary</label>
                    @if($package->itineraries->count() > 0)
                    @foreach($package->itineraries as $i => $itinerary)
                    <div class="row itinerary-item mb-3">
                        <input type="hidden" name="itineraries[{{$i}}][id]" value="{{$itinerary->id}}">
                        <div class="col-md-2">
                            <input type="number" name="itineraries[{{$i}}][day_number]" class="form-control" placeholder="Day #" value="{{$itinerary->day_number}}" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="itineraries[{{$i}}][title]" class="form-control" placeholder="Title" value="{{$itinerary->title}}">
                        </div>
                        <div class="col-md-5">
                            <textarea name="itineraries[{{$i}}][description]" class="form-control" placeholder="Description">{{$itinerary->description}}</textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-itinerary">-</button>
                        </div>
                    </div>
                    @endforeach
                    @else
                        <!-- Show one empty row if no data -->
                        <div class="row itinerary-item mb-3">
                            <div class="col-md-2">
                                <input type="number" name="itineraries[0][day_number]" class="form-control" placeholder="Day #" value="1" readonly>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="itineraries[0][title]" class="form-control" placeholder="Title">
                            </div>
                            <div class="col-md-5">
                                <textarea name="itineraries[0][description]" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-itinerary">-</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" id="add-itinerary-btn" class="btn btn-success mb-3">+ Add Itinerary</button>
                    
                <div id="itinerary-error" class="text-danger mt-1"></div>
               
                <div class="mb-3">

                    <!-- recommendation Toggle -->
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-recommendation" type="checkbox"  name="recommendation"
                       value="1"
                        {{ $package->recommendation == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">Recommendation</label>
                    </div>
                </div>
                <div class="mb-3">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="{{ $package->meta_title }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="{{ $package->meta_description }}" class="form-control">

            </div>
            <div class="mb-3">
                <label>OG Title</label>
                <input type="text" name="og_title" value="{{ $package->og_title }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>OG Description</label>
                <input type="text" name="og_description" value="{{ $package->og_description }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>OG Image</label>
                @if($package->og_image)
                        <div><img src="{{ asset('storage/' . $package->og_image) }}" width="100"></div>
                @endif
                <input type="file" name="og_image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Schema (JSON-LD)</label>
                <textarea name="schema" class="form-control" rows="4">{{ $package->schema }}</textarea>
            </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary ms-3">Cancel</a>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
     $(document).ready(function() {
    $('#overview').summernote({
        height: 200,
        toolbar: [
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
        ],
        callbacks: {
            onChange: function(contents) {
            $(this).val(contents);  // sync HTML content back to textarea
            $(this).valid();        // trigger validation on textarea, not editor div
            }
        }
        
    });
    $('#highlights').summernote({
        height: 200,
        toolbar: [
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
        ],
        callbacks: {
            onChange: function(contents) {
            $(this).val(contents);  // sync HTML content back to textarea
            $(this).valid();        // trigger validation on textarea, not editor div
            }
        }
    
    });
});
$(document).ready(function () {
            $.validator.addMethod("filesize", function (value, element, param) {
        let files = element.files;
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > param) {
                return false;
            }
        }
        return true;
    });
    $.validator.addMethod("extension", function (value, element, param) {
        if (!param) return false; // Prevent undefined error
        param = typeof param === "string" ? param.replace(/\s/g, "").split("|") : param;
        let ext = value.split(".").pop().toLowerCase();
        return this.optional(element) || param.includes(ext);
    }, "Invalid file type.");
    $("#packageForm").validate({
        ignore: ":hidden:not(textarea),.note-editor *",
        rules: {
            package_name: { required: true},
             country_id: "required",
            category_id: "required",
            tag_id: "required",
            sales_person_id: "required",
            unit_type_id: "required",
            image: {
                extension: "jpg|jpeg|png"
            },
            starting_price: "required",
            duration: "required",
            itinerary_desc: "required",
            overview:  { 
                required: function (textarea) {
                    // Get Summernote content
                    var editorContent = $('#overview').summernote('isEmpty');
                    return editorContent;
                }
            },
            highlights:  {
                        required: function (textarea) {
                        // Get Summernote content
                        var editorContent = $('#highlights').summernote('isEmpty');
                        return editorContent;
                    }
            },

            // gallery[] validation
            "gallery[]": {
                extension: "jpg|jpeg|png|gif|svg",
                filesize: 2 * 1024 * 1024
            },
            "slide_show[]": {
                extension: "jpg|jpeg|png"
            },
            // inclusions[]
            "inclusions[]": {
                required: true
            },

            // included[]
            "included[]": {
                required: true
            },

            // excluded[]
            "excluded[]": {
                required: true
            },
            meta_title:{ required: true },
            meta_description:{ required: true }
        },
        messages: {
            package_name: "Package name is required",
            country_id: "Country is required",
            category_id: "Category is required",
            tag_id: "Tag is required",
            sales_person_id: "Sales person is required",
            unit_type_id: "Unit type is required",
            starting_price: "Starting price is required",
            duration: "Duration is required",
            itinerary_desc: "Itinerary description is required",
            overview: "Overview is required",
            highlights: "Highlights are required",
            "inclusions[]": "Please add at least one inclusion",
            "included[]": "Please add at least one included item",
            "excluded[]": "Please add at least one excluded item"
        },
         errorElement: 'div', // wrap errors in <div>
        errorClass: 'text-danger mt-1', // bootstrap style
        errorPlacement: function (error, element) {
            if (element.hasClass('summernote')) {
                    error.insertAfter(element.siblings('.note-editor'));
            } else {
                    error.insertAfter(element);
            }
        }
    });
});
$('#packageForm').on('submit', function (e) {
    let isValid = true;

    $('.itinerary-item').each(function () {
        let hasId = $(this).find('input[name*="[id]"]').length > 0;
        let day = $(this).find('[name*="[day_number]"]');
        let title = $(this).find('[name*="[title]"]');
        let desc = $(this).find('[name*="[description]"]');

        // Trim values
        let dayVal = day.val().trim();
        let titleVal = title.val().trim();
        let descVal = desc.val().trim();

        // Only validate if no ID and any field is filled or completely empty
        if (!hasId && (dayVal || titleVal || descVal)) {
            if (!dayVal || !titleVal || !descVal) {
                isValid = false;

                if (!dayVal) day.addClass('is-invalid'); else day.removeClass('is-invalid');
                if (!titleVal) title.addClass('is-invalid'); else title.removeClass('is-invalid');
                if (!descVal) desc.addClass('is-invalid'); else desc.removeClass('is-invalid');
            }
        } else {
            // Clean up if existing or fully filled
            day.removeClass('is-invalid');
            title.removeClass('is-invalid');
            desc.removeClass('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        $('#itinerary-error').html("Please fill in all fields for new itineraries.");
        $('html, body').animate({
            scrollTop: $('.is-invalid').first().offset().top - 100
        }, 500);
    }
});




$(document).ready(function () {
    // Handle add button
    $(document).on('click', '.add-inclusions-btn', function () {
        let inputGroup = `
            <div class="input-group mb-2 inclusions">
                <input type="text" name="inclusions[]" class="form-control" placeholder="Enter inclusion">
                <button type="button" class="btn btn-danger remove-inclusions-btn">-</button>
            </div>
        `;
        $('#input-inclusions-group-wrapper').append(inputGroup);
    });

    // Handle remove button
    $(document).on('click', '.remove-inclusions-btn', function () {
        $(this).closest('.inclusions').remove();
    });
       $(document).on('click', '.add-included-btn', function () {
        let inputGroup = `
            <div class="input-group mb-2 included">
                <input type="text" name="included[]" class="form-control" placeholder="Enter includes">
                <button type="button" class="btn btn-danger remove-included-btn">-</button>
            </div>
        `;
        $('#input-included-group-wrapper').append(inputGroup);
    });

    // Handle remove button
    $(document).on('click', '.remove-included-btn', function () {
        $(this).closest('.included').remove();
    });
      $(document).on('click', '.add-excluded-btn', function () {
        let inputGroup = `
            <div class="input-group mb-2 excluded">
                <input type="text" name="excluded[]" class="form-control" placeholder="Enter excludes">
                <button type="button" class="btn btn-danger remove-excluded-btn">-</button>
            </div>
        `;
        $('#input-excluded-group-wrapper').append(inputGroup);
    });

    // Handle remove button
    $(document).on('click', '.remove-excluded-btn', function () {
        $(this).closest('.excluded').remove();
    });

              $(document).on('click', '.add-extra-services-btn', function () {
        let inputGroup = `
            <div class="input-group mb-2 extra_services">
                <input type="text" name="extra_services[]" class="form-control" placeholder="Enter Extra Services">
                <button type="button" class="btn btn-danger remove-extra-services-btn">-</button>
            </div>
        `;
        $('#input-extra-services-group-wrapper').append(inputGroup);
    });

    // Handle remove button
    $(document).on('click', '.remove-extra-services-btn', function () {
        $(this).closest('.extra-services').remove();
    });
});

 let itineraryIndex = {{ $package->itineraries->count() > 0 ? $package->itineraries->count() : 1 }};

$('#add-itinerary-btn').on('click', function () {
    let nextDayNumber = $('#itinerary-wrapper .itinerary-item').length + 1;

    let html = `
        <div class="row itinerary-item mb-3">
            <div class="col-md-2">
                <input type="number" name="itineraries[${itineraryIndex}][day_number]" class="form-control" placeholder="Day #" required readonly value="${nextDayNumber}">
            </div>
            <div class="col-md-3">
                <input type="text" name="itineraries[${itineraryIndex}][title]" class="form-control" placeholder="Title" required>
            </div>
            <div class="col-md-5">
                <textarea name="itineraries[${itineraryIndex}][description]" class="form-control" placeholder="Description" required></textarea>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-itinerary">-</button>
            </div>
        </div>
    `;
    $('#itinerary-wrapper').append(html);
    itineraryIndex++;
});

$(document).on('click', '.remove-itinerary', function () {
    $(this).closest('.itinerary-item').remove();

    // Reindex after removal
    itineraryIndex = 0;
    $('#itinerary-wrapper .itinerary-item').each(function () {
        $(this).find('input, textarea').each(function () {
            let name = $(this).attr('name');
            if (name) {
                let newName = name.replace(/itineraries\[\d+\]/, `itineraries[${itineraryIndex}]`);
                $(this).attr('name', newName);
            }
        });
        itineraryIndex++;
    });

    // If no itinerary rows remain, add an empty one to keep user able to input
    if($('#itinerary-wrapper .itinerary-item').length === 0){
         let nextDayNumber = $('#itinerary-wrapper .itinerary-item').length + 1;
        let html = `
            <div class="row itinerary-item mb-3">
                <div class="col-md-2">
                    <input type="number" name="itineraries[0][day_number]" class="form-control" placeholder="Day #" readonly value="${nextDayNumber}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="itineraries[0][title]" class="form-control" placeholder="Title">
                </div>
                <div class="col-md-5">
                    <textarea name="itineraries[0][description]" class="form-control" placeholder="Description"></textarea>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-itinerary">-</button>
                </div>
            </div>
        `;
        $('#itinerary-wrapper').append(html);
        itineraryIndex = 1;
    }
});
let selectedFiles = [];
    let removedExistingImages = [];

    // ========== NEW IMAGE HANDLING ==========

    // On file selection
    $("#gallery").on("change", function (event) {
        let files = Array.from(event.target.files); // Convert FileList to array
        console.log("New files:", files);
        
        // Append new files to the global array
        selectedFiles = [...selectedFiles, ...files];

        console.log("All selected files:", selectedFiles);

        $("#images-preview").empty(); // Clear preview to avoid duplicates

        // Display all selected files
        selectedFiles.forEach(function (file, index) {  // Use function() to access index properly
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#images-preview").append(
                    `<div class="m-2 d-inline-block position-relative image-preview" data-index="${index}">
                        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle remove-gallery-image" data-index="${index}" aria-label="Close"></button>
                        <img src="${e.target.result}" class="img-thumbnail" width="100">
                    </div>`
                );
            };
            reader.readAsDataURL(file);
        });
    });

    // **Remove selected image**
    $(document).on("click", ".remove-gallery-image", function () {
        let index = $(this).data("index");
        selectedFiles.splice(index, 1); // Remove from array
        $(this).parent().remove(); // Remove from preview

        console.log("Updated selected files:", selectedFiles);
    });


    // ========== EXISTING IMAGE HANDLING ==========
    window.removeExistingImage = function(index) {
        const wrapper = document.querySelector(`#existing-images .image-wrapper[data-index='${index}']`);
        if (wrapper) {
            const path = wrapper.getAttribute('data-path');
            removedExistingImages.push(path);
            wrapper.remove();
            document.getElementById('removed_images').value = JSON.stringify(removedExistingImages);
        }
    };
    
    $(document).on('click', '.remove-existing-image', function () {
        const index = $(this).data('index');
        window.removeExistingImage(index); // or directly put the logic here
    });
    

    // ========== FINAL FORM PREP ==========

    $("form").on("submit", function (e) {
        let fileInput = document.getElementById("gallery");
        let dataTransfer = new DataTransfer(); // Create a new file list

        selectedFiles.forEach(function (file) {
            dataTransfer.items.add(file); // Add remaining images
        });

        fileInput.files = dataTransfer.files; // Attach filtered images to input

        console.log("Final file count before submission:", fileInput.files.length);
    });
  $('#image').on('change', function(event) {
        let previewContainer = $('#image-preview');
        previewContainer.html(''); // Clear existing previews
        
        if (this.files.length > 0) {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                let previewHtml = `
                    <div class="m-2 d-inline-block position-relative image-preview">
                        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle remove-image" aria-label="Close"></button>
                        <img src="${e.target.result}" class="img-thumbnail" width="100">
                    </div>
                `;
                previewContainer.html(previewHtml);
            };

            reader.readAsDataURL(file);
        }
    });

    // Handle removing the image preview
    $(document).on('click', '.remove-image', function() {
        $('#image-preview').html('');
        $('#image').val(''); // Reset file input
    });


     let selectedSlideFiles = [];
    let removedExistingSlideImages = [];

    // ========== NEW IMAGE HANDLING ==========

    // On file selection
    $("#slide_show").on("change", function (event) {
        let files = Array.from(event.target.files); // Convert FileList to array
        console.log("New files:", files);
        
        // Append new files to the global array
        selectedSlideFiles = [...selectedSlideFiles, ...files];

        console.log("All selected files:", selectedSlideFiles);

        $("#slide-preview").empty(); // Clear preview to avoid duplicates

        // Display all selected files
        selectedSlideFiles.forEach(function (file, index) {  // Use function() to access index properly
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#slide-preview").append(
                    `<div class="m-2 d-inline-block position-relative slide-preview" data-index="${index}">
                        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle remove-slide-image" data-index="${index}" aria-label="Close"></button>
                        <img src="${e.target.result}" class="img-thumbnail" width="100">
                    </div>`
                );
            };
            reader.readAsDataURL(file);
        });
    });

    // **Remove selected image**
    $(document).on("click", ".remove-slide-image", function () {
        let index = $(this).data("index");
        selectedSlideFiles.splice(index, 1); // Remove from array
        $(this).parent().remove(); // Remove from preview

        console.log("Updated selected files:", selectedFiles);
    });



    // ========== FINAL FORM PREP ==========

    $("form").on("submit", function (e) {
        let fileInput = document.getElementById("slide_show");
        let dataTransfer = new DataTransfer(); // Create a new file list

        selectedSlideFiles.forEach(function (file) {
            dataTransfer.items.add(file); // Add remaining images
        });

        fileInput.files = dataTransfer.files; // Attach filtered images to input

        console.log("Final file count before submission:", fileInput.files.length);
    });
    
    // ========== EXISTING IMAGE HANDLING ==========
    window.removedExistingSlideImages = function(index) {
        const slideWrapper = document.querySelector(`#existing-slide-images .slide-wrapper[data-index='${index}']`);
        if (slideWrapper) {
            const path = slideWrapper.getAttribute('data-path');
            removedExistingSlideImages.push(path);
            slideWrapper.remove();
            document.getElementById('removed_slide_images').value = JSON.stringify(removedExistingSlideImages);
        }
    };
    
    $(document).on('click', '.remove-existing-slide-image', function () {
        const index = $(this).data('index');
        window.removedExistingSlideImages(index); // or directly put the logic here
    });
    
    
    
</script>
@endpush
