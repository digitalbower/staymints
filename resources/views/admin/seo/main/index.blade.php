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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-message">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="text-start">Seo Management</h3>
                @if(auth()->guard('admin')->user()->hasPermission('create_seo'))
                <a href="{{ route('admin.seo.create') }}" class="btn btn-primary">Add New Seo</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Page Url</th>
                            <th>Meta Title</th>
                            <th>Meta Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pages as $index => $page)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $page->page_url }}</td>
                                <td>{{ $page->meta_title }}</td>
                                <td>{{ $page->meta_description }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    <!-- Edit Button -->
                                    
                                    @if(auth()->guard('admin')->user()->hasPermission('edit_seo'))
              
                                    <a href="{{ route('admin.seo.edit',$page) }}" class="btn btn-warning btn-sm">Edit</a>
                               
                                    @endif
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection