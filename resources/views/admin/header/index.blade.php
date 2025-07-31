@extends('admin.layouts.master')
@section('title', 'Headers')
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                    <h5 class="mb-0 font-medium">Headers</h5>
                    </div>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Headers</a></li>
                    <li class="breadcrumb-item" aria-current="page">Headers</li>
                    </ul>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-message">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div id="status-message"></div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="mb-0">Headers</h3>
                @if(auth()->guard('admin')->user()->hasPermission('create_header'))
                <a href="{{ route('admin.headers.create') }}" class="btn btn-primary">Add New Header</a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($headers as $index => $header)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $header->name ?? 'N/A' }}</td>
                            <td>{{ $header->link ?? 'N/A' }}</td>
                            <td class="d-flex align-items-center gap-2">
                                @if(auth()->guard('admin')->user()->hasPermission('edit_header'))
                                    <a href="{{ route('admin.headers.edit', $header) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                                @if(auth()->guard('admin')->user()->hasPermission('delete_header'))
                                <form action="{{ route('admin.headers.destroy', $header->id) }}" method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this header?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Headers available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
