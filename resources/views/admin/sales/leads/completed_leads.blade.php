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
                    <li class="breadcrumb-item" aria-current="page">Closed Leads</li>
                    </ul>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="mb-0">Closed Leads</h3>
            </div>
            <div id="status-message"></div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Booking Refrence Id</th>
                            <th>Total</th>
                            <th>Markup %</th>
                            <th>Markup Value</th>
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
                                <td>{{ $lead->booking_reference }}</td>
                                <td>{{ $lead->total }}</td>
                                <td>{{ $lead->markup_percent }}</td>
                                <td>{{ $lead->markup_value }}</td>                        
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Leads available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
