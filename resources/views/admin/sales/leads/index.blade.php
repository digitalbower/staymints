@extends('admin.layouts.master')
@section('title', 'Leads')
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                    <h5 class="mb-0 font-medium">Leads</h5>
                    </div>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Leads</a></li>
                    <li class="breadcrumb-item" aria-current="page">Active Leads</li>
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
                <h3 class="mb-0">Active Leads</h3>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $index => $lead)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $lead->first_name }}</td>
                                <td>{{ $lead->last_name }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    @if(auth()->guard('admin')->user()->hasPermission('assign_sales_person'))
                                    <a href="{{ route('admin.sales.assign', $lead) }}" class="btn btn-warning btn-sm">Assign</a>
                                    @endif
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Leads available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection