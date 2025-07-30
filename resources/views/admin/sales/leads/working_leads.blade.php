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
                    <li class="breadcrumb-item" aria-current="page">Working Leads</li>
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
            <div class="d-flex align-items-center justify-content-be
            
            tween mb-3">
                <h3 class="mb-0">Working Leads</h3>
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
                                    @if(auth()->guard('admin')->user()->hasPermission('change_lead_status'))
                                        <select class="form-select update-status" data-id="{{ $lead->id }}">
                                            <option value="1" {{ $lead->status == 1 ? 'selected' : '' }}>In Progress</option>
                                            <option value="2" {{ $lead->status == 2 ? 'selected' : '' }}>Closed</option>
                                            <option value="3" {{ $lead->status == 3 ? 'selected' : '' }}>Loss</option>
                                        </select>
                                    @endif
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Leads available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lossReasonModal" tabindex="-1" aria-labelledby="lossReasonLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="loss-reason-form">
        <div class="modal-header">
          <h5 class="modal-title">Loss Reason</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="loss-lead-id">
          <textarea class="form-control" id="loss-reason" placeholder="Enter reason" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="booking-details-form">
        <div class="modal-header">
          <h5 class="modal-title">Booking Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="booking-lead-id">
          <div class="mb-3">
            <label>Booking Reference ID</label>
            <input type="text" id="booking-reference" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Total</label>
            <input type="number" id="booking-total" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Markup %</label>
            <input type="number" id="markup-percent" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Markup Value</label>
            <input type="number" id="markup-value" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    let originalStatus = null;

    $('.update-status').on('focus', function () {
        originalStatus = $(this).val(); // Save current value to revert if cancelled
    });

    $('.update-status').on('change', function () {
        const newStatus = $(this).val();
        const leadId = $(this).data('id');

        if (newStatus == 3) {
            // Loss - show loss reason modal
            $('#loss-lead-id').val(leadId);
            $('#lossReasonModal').modal('show');
            $(this).val(originalStatus);
        } else if (newStatus == 2) {
            // Closed - show booking details modal
            $('#booking-lead-id').val(leadId);
            $('#bookingDetailsModal').modal('show');
            $(this).val(originalStatus);
        } else {
            // In Progress (or others)
            updateLeadStatus(leadId, newStatus);
        }
    });

    $('#loss-reason-form').on('submit', function (e) {
        e.preventDefault();
        const leadId = $('#loss-lead-id').val();
        const reason = $('#loss-reason').val().trim();

        if (!reason) {
            alert('Reason is required');
            return;
        }

        updateLeadStatus(leadId, 3, { reason });
        $('#lossReasonModal').modal('hide');
        $('#loss-reason').val('');
    });

    $('#booking-details-form').on('submit', function (e) {
        e.preventDefault();

        const leadId = $('#booking-lead-id').val();
        const ref = $('#booking-reference').val().trim();
        const total = $('#booking-total').val();
        const markupPercent = $('#markup-percent').val();
        const markupValue = $('#markup-value').val();

        if (!ref || !total || !markupPercent || !markupValue) {
            alert('All booking fields are required.');
            return;
        }

        updateLeadStatus(leadId, 2, {
            booking_reference: ref,
            total: total,
            markup_percent: markupPercent,
            markup_value: markupValue
        });

        $('#bookingDetailsModal').modal('hide');
        $('#booking-details-form')[0].reset();
        
    });

    function updateLeadStatus(id, status, extraData = {}) {
        $.ajax({
            url: "{{ route('admin.sales.change.lead') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            data: JSON.stringify({ id, status, ...extraData }),
            success: function (res) {
                $('#status-message').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${res.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $('.update-status[data-id="' + id + '"]').val(status);
            },
            error: function () {
                alert('Error updating status.');
            }
        });
    }
</script>
    
@endpush