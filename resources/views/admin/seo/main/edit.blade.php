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
                <li class="breadcrumb-item" aria-current="page">Edit Seo</li>
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


        <form action="{{ route('admin.seo.update',$seo->id) }}" id="seoMainForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Page Url</label>
                <input type="text" name="page_url" value="{{ $seo->page_url }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="{{ $seo->meta_title }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="{{ $seo->meta_description }}" class="form-control">

            </div>
            <div class="mb-3">
                <label>OG Title</label>
                <input type="text" name="og_title" value="{{ $seo->og_title }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>OG Description</label>
                <input type="text" name="og_description" value="{{ $seo->og_description }}" class="form-control">
            </div>
            <div class="mb-3">
                <label>OG Image</label>
                @if($seo->og_image)
                    <div><img src="{{ asset('storage/' . $seo->og_image) }}" width="100"></div>
                @endif
                <input type="file" name="og_image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Schema (JSON-LD)</label>
                <textarea name="schema" class="form-control" rows="4">{{ $seo->schema }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary ms-3">Cancel</a>
        </form>
    </div>
</div>
@endsection