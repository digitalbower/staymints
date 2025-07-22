@extends('admin.layouts.master')
@section('title', 'Reviews')
@section('content')
  
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                <h5 class="mb-0 font-medium">Reviews</h5>
                </div>
                <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Reviews</a></li>
                <li class="breadcrumb-item" aria-current="page">Reviews</li>
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
                <h3 class="text-start">Reviews</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>reviewer Name</th>
                            <th>Reviewer Email</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $index => $review)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $review->package ? $review->package->package_name : 'No Package' }}</td>
                                <td>{{ $review->reviewer_name}}</td>
                                <td>{{ $review->reviewer_email}}</td>
                                <td>{{$review->average_rating}}</td>
                                <td class="d-flex align-items-center gap-2">

                                    @if(auth()->guard('admin')->user()->hasPermission('reply_review'))   
                                    <button class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#replyModal" 
                                            data-review-id="{{ $review->id }}" 
                                            data-reviewer-name = "{{$review->reviewer_name}}"
                                            data-review-content="{{ $review->review_description }}"
                                            data-reply="{{ $review->admin_reply }}">
                                        Reply
                                    </button>
                                    @endif
                                    <!-- Toggle Switch -->
                                    @if(auth()->guard('admin')->user()->hasPermission('delete_review'))   
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @endif
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Product reviews available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="replyForm" method="POST" action="{{ route('admin.reviews.reply') }}">
        @csrf
        <input type="hidden" name="review_id" id="review_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Reply to Review</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <div class="form-group">
                <label for="reply">Reviewer Name</label>
                <input class="form-control" id="review-name" readonly/>
            </div>
            <div class="form-group">
                <label for="reply">Review Description</label>
                <textarea class="form-control" id="review-description" readonly></textarea>
            </div>
            <div class="form-group">
                <label for="reply">Your Reply</label>
                <textarea class="form-control" name="admin_reply" id="reply_textarea" rows="4" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Submit Reply</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
    </form>
  </div>
</div>

@endsection
@push('scripts')
<script>
    $('.toggle-status').change(function () {
        var categoryId = $(this).data('id');
        var newStatus = $(this).is(':checked') ? 1 : 0;
    
        $.ajax({
            url: "/admin/categories/change-status",
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf_token, 
            },
            data: JSON.stringify({
                id: categoryId,
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
 <script>
document.addEventListener('DOMContentLoaded', function () {
    var replyModal = document.getElementById('replyModal');

    replyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var reviewId = button.getAttribute('data-review-id');
        var reviewerName = button.getAttribute('data-reviewer-name');
        var reviewContent = button.getAttribute('data-review-content');
        var existingReply = button.getAttribute('data-reply');

        document.getElementById('review_id').value = reviewId;
        document.getElementById('review-name').value = reviewerName;
        document.getElementById('review-description').value = reviewContent;
        document.getElementById('reply_textarea').value = existingReply || '';
    });
});
</script>   
@endpush