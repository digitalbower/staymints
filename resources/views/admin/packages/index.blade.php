@extends('admin.layouts.master')
@section('title', 'Packages')
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                    <h5 class="mb-0 font-medium">Packages</h5>
                    </div>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Packages</a></li>
                    <li class="breadcrumb-item" aria-current="page">Packages</li>
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
                <h3 class="mb-0">Packages</h3>
                @if(auth()->guard('admin')->user()->hasPermission('create_package'))
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">Add New Package</a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $index => $package)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $package->package_name }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    @if(auth()->guard('admin')->user()->hasPermission('changestatus_packages'))
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status" type="checkbox" 
                                                data-id="{{ $package->id }}" 
                                                {{ $package->status ? 'checked' : '' }}>
                                        </div>
                                    @endif
                                    <!-- Edit Button -->
                                    @if(auth()->guard('admin')->user()->hasPermission('edit_package'))
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endif
                                    @if(auth()->guard('admin')->user()->hasPermission('delete_package'))
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this package?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @endif
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Packages available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $('.toggle-status').change(function () {
        var packageId = $(this).data('id');
        var newStatus = $(this).is(':checked') ? 1 : 0;
    
        $.ajax({
            url: "/admin/packages/change-status",
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf_token, 
            },
            data: JSON.stringify({
                id: packageId,
                status: newStatus
            }),
            success: function (response) {
                // Display the success message in a specific div
                $('#status-message').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            error: function (xhr) {
                let errorMessage = "Something went wrong! Please try again.";
                $('#status-message').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    });
</script>
    
@endpush