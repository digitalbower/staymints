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
        <h3 class="text-start mb-4">Seo Management</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('admin.seo.store') }}" id="seoMainForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Page Url</label>
                <input type="text" name="page_url" value="{{ old('page_url') }}" class="form-control">
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
            <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary ms-3">Cancel</a>
        </form>
    </div>
</div>
@endsection