@extends('admin.layouts.master')
@section('title', 'Seo')
@section('content')
  
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                <h5 class="mb-0 font-medium">Seo</h5>
                </div>
                <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Seo</a></li>
                <li class="breadcrumb-item" aria-current="page">Create Seo</li>
                </ul>
            </div>
            </div>
        <h3 class="text-start mb-4">Category Management</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('admin.categories.store') }}" id="categoryForm" method="POST">
            @csrf

            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" name="category_name" value="{{ old('category_name') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>Category Description</label>
                <textarea name="category_description" class="form-control">{{ old('category_description') }}</textarea>
            </div>
            <div class="mb-3">
                    <label for="image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-2 d-flex flex-wrap"></div>
                
                    <div id="image-error" class="text-danger mt-1" style="display: none;">Please upload an image.</div>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ms-3">Cancel</a>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
     $(document).ready(function () {
    $.validator.addMethod("extension", function (value, element, param) {
        if (!param) return false; // Prevent undefined error
        param = typeof param === "string" ? param.replace(/\s/g, "").split("|") : param;
        let ext = value.split(".").pop().toLowerCase();
        return this.optional(element) || param.includes(ext);
    }, "Invalid file type.");
    $("#categoryForm").validate({
        rules: {
            category_name: "required",
            image: {
                required: true,
                extension: "jpg|jpeg|png"
            },
        },
        messages: {
            category_name: "Category name is required",
            image: "Image is required",
        }
    });
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