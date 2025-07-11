@extends('admin.layouts.master')
@section('title', 'Admin Users')
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                    <h5 class="mb-0 font-medium">Admin Users</h5>
                    </div>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Admin Users</a></li>
                    <li class="breadcrumb-item" aria-current="page">Admin Users</li>
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
                <h3 class="mb-0">Admin Users</h3>
                @if(auth()->guard('admin')->user()->hasPermission('create_adminusers'))
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New Admin User</a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role?->role_name ?? 'N/A' }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    <!-- Edit Button -->
                                    @if(auth()->guard('admin')->user()->hasPermission('edit_adminusers'))
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endif
                                    @if(auth()->guard('admin')->user()->hasPermission('delete_adminusers'))
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @endif
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Admin Users available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
