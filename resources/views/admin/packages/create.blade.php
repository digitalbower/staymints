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
                <li class="breadcrumb-item" aria-current="page">Create Package</li>
                </ul>
            </div>
            </div>
            <h4 class="text-start mb-4">Create Package</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('admin.packages.store') }}" id="packageForm" method="POST"  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="package_name" class="form-label">Package Name</label>
                    <input type="text" class="form-control" id="package_name" name="package_name" value="{{old('package_name') }}">
                </div>
                <div class="mb-4">
                    <label for="country_id" class="form-label">Country</label>
                    <select class="form-control" name="country_id" id="country_id">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $tag->id }}" {{ old('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="sales_person_id" class="form-label">Sales person</label>
                    <select class="form-control" name="sales_person_id" id="sales_person_id">
                        <option value="">Select Sales person</option>
                        @foreach ($sales_persons as $sales_person)
                            <option value="{{ $sales_person->id }}" {{ old('sales_person_id') == $sales_person->id ? 'selected' : '' }}>
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
                            <option value="{{ $type->id }}" {{ old('unit_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="starting_price" class="form-label">Starting price</label>
                    <input type="text" class="form-control" id="starting_price" name="starting_price" value="{{old('starting_price') }}">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Package Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-2 d-flex flex-wrap"></div>
                
                    <div id="image-error" class="text-danger mt-1" style="display: none;">Please upload an image.</div>
                </div>
                
                <div class="mb-3">
                    <label for="gallery" class="form-label">Package Gallery</label>
                    <input type="file" class="form-control" id="gallery" name="gallery[]" multiple accept="image/*">
                    <div id="images-preview" class="mt-2 d-flex flex-wrap"></div>
                    <div id="images-error" class="text-danger mt-1" style="display: none;">Please upload at least one valid image.</div>
                </div>
                <input type="hidden" name="old_gallery" id="old-gallery" value="{{ old('gallery') }}">
                <div class="mb-3">
                    <label for="video" class="form-label">Package Video</label>
                    <input type="file" class="form-control" id="video" name="video" accept="video/*">
                    
                    <!-- Video Preview -->
                    <div id="video-preview" class="mt-2 d-flex flex-wrap"></div>
                
                    <div id="video-error" class="text-danger mt-1" style="display: none;">Please upload a video.</div>
                </div>
                <div id="input-inclusions-group-wrapper">
                    <label for="included" class="form-label">Inclusions</label>
                    <div class="input-group mb-3 included">
                        <input type="text" name="inclusions[]" class="form-control" placeholder="Enter inclusion">
                        <button type="button" class="btn btn-success add-inclusions-btn">+</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration</label>
                    <input type="number" class="form-control" id="duration" name="duration" value="{{old('duration') }}">
                </div>
                <div class="mb-3">
                    <label for="group_size" class="form-label">Group size</label>
                    <input type="text" class="form-control" id="group_size" name="group_size" value="{{old('group_size') }}">
                </div>
                <div class="mb-3">
                    <label for="overview" class="form-label">Overview</label>
                    <textarea class="form-control" id="overview" name="overview">{{old('overview') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="highlights" class="form-label">Highlights</label>
                    <textarea class="form-control" id="highlights" name="highlights">{{old('highlights') }}</textarea>
                </div>
                <div id="input-included-group-wrapper">
                    <label for="included" class="form-label">Includes</label>
                    <div class="input-group mb-3 included">
                        <input type="text" name="included[]" class="form-control" placeholder="Enter includes">
                        <button type="button" class="btn btn-success add-included-btn">+</button>
                    </div>
                </div>
                <div id="input-excluded-group-wrapper">
                    <label for="included" class="form-label">Excluded</label>
                    <div class="input-group mb-3 excluded">
                        <input type="text" name="excluded[]" class="form-control" placeholder="Enter excludes">
                        <button type="button" class="btn btn-success add-excluded-btn">+</button>
                    </div>
                </div>
                <div id="input-extra-services-group-wrapper">
                    <label for="extra_services" class="form-label">Extra services</label>
                    <div class="input-group mb-3 extra-services">
                        <input type="text" name="extra_services[]" class="form-control" placeholder="Enter Extra services">
                        <button type="button" class="btn btn-success add-extra-services-btn">+</button>
                    </div>
                </div>
                <div id="itinerary-wrapper">
                    <label for="included" class="form-label">Itinerary</label>
                    <div class="row itinerary-item mb-3">
                        <div class="col-md-2">
                            <input type="number" name="itineraries[0][day_number]" class="form-control" placeholder="Day #"  value="1" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="itineraries[0][title]" class="form-control" placeholder="Title" >
                        </div>
                        <div class="col-md-5">
                            <textarea name="itineraries[0][description]" class="form-control" placeholder="Description" ></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success add-itinerary">+</button>
                        </div>
                    </div>
                    <div id="itinerary-error" class="text-danger mt-1"></div>
                </div>
                <div class="mb-3">

                    <!-- recommendation Toggle -->
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-recommendation" type="checkbox"  name="recommendation"
                        value="1"
                        {{ old('recommendation', 1) ? 'checked' : '' }}>
                        <label class="form-check-label">Recommendation</label>
                    </div>
                </div>
                <div class="mb-3">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="form-control">

            </div>
            <div class="mb-3">
                <label>OG Title</label>
                <input type="text" name="og_title" value="{{ old('og_title') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>OG Description</label>
                <input type="text" name="og_description" value="{{ old('og_description') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>OG Image</label>
                <input type="file" name="og_image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Schema (JSON-LD)</label>
                <textarea name="schema" class="form-control" rows="4">{{ old('schema') }}</textarea>
            </div>
                <button type="submit" class="btn btn-success">Create</button>
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
            starting_price: "required",
            image: "required",
            duration: "required",
            group_size: "required",
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
                required: true,
                extension: "jpg|jpeg|png|gif|svg"
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
            image: "Image is required",
            duration: "Duration is required",
            group_size: "Group size is required",
            overview: "Overview is required",
            highlights: "Highlights are required",
            "gallery[]": "At least one valid image is required",
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

    $('[name^="itineraries"]').each(function () {
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid');
            isValid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        $('#itinerary-error').html("Please fill in all itinerary fields.");
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


let itineraryIndex = 1;

$(document).on('click', '.add-itinerary', function () {
    let nextDayNumber = $('#itinerary-wrapper .itinerary-item').length + 1;
    let html = `
        <div class="row itinerary-item mb-3">
            <div class="col-md-2">
                <input type="number" name="itineraries[${itineraryIndex}][day_number]" class="form-control" readonly placeholder="Day #" value="${nextDayNumber}">
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
                        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle remove-image" data-index="${index}" aria-label="Close"></button>
                        <img src="${e.target.result}" class="img-thumbnail" width="100">
                    </div>`
                );
            };
            reader.readAsDataURL(file);
        });
    });

    // **Remove selected image**
    $(document).on("click", ".remove-image", function () {
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

</script>
@endpush
