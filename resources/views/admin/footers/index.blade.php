@extends('admin.layouts.master')
@section('title', 'Footers')
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                    <h5 class="mb-0 font-medium">Footers</h5>
                    </div>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Footers</a></li>
                    <li class="breadcrumb-item" aria-current="page">Footers</li>
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
                <h3 class="mb-0">Footers</h3>
                @if(auth()->guard('admin')->user()->hasPermission('create_footer'))
                <a href="{{ route('admin.footers.create') }}" class="btn btn-primary">Add New Footer</a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Item Text</th>
                            <th>Link</th>
                            <th>Icon</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($footers as $index => $footer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $footer->type }}</td>
                            <td>{{ $footer->item_text ?? 'N/A' }}</td>
                            <td>{{ $footer->link ?? 'N/A' }}</td>
                            <td>{{ $footer->icon ?? 'N/A' }}</td>
                            <td class="d-flex align-items-center gap-2">
                                @if(auth()->guard('admin')->user()->hasPermission('edit_footer'))
                                    <a href="{{ route('admin.footers.edit', $footer) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                                @if(auth()->guard('admin')->user()->hasPermission('delete_footer'))
                                <form action="{{ route('admin.footers.destroy', $footer->id) }}" method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this footer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Footers available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
