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
                <li class="breadcrumb-item" aria-current="page">Edit Footer</li>
                </ul>
            </div>
            </div>
            <h4 class="text-start mb-4">Edit Footer</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('admin.footers.update',$footer->id) }}" id="footerForm" method="POST">
            @csrf
            @method('PUT')

                <div class="mb-4">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-control" name="type" id="type">
                        <option value="">Select Type</option>
                        <option value="Quick Links" {{$footer->type == "Quick Links" ? 'selected' : '' }}>Quick Links</option>
                        <option value="Payment Partners" {{$footer->type == "Payment Partners" ? 'selected' : '' }}>Payment Partners</option>
                        <option value="Follow on Us" {{$footer->type =="Follow on Us" ? 'selected' : '' }}>Follow on Us</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="item_text" class="form-label">Item Text</label>
                    <input type="text" class="form-control" id="item_text" name="item_text" value="{{$footer->item_text }}">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control" id="link" name="link" value="{{$footer->link }}">
                </div>
                <div class="mb-3">
                    <label for="icon" class="form-label">Icon</label>
                    <select name="icon" class="form-control">
                        <option value="">-- Select Icon --</option>
                        <option value="fa-brands fa-facebook-f" {{$footer->icon == "fa-brands fa-facebook-f" ? 'selected' : '' }}>Facebook</option>
                        <option value="fa-brands fa-twitter" {{$footer->icon == "fa-brands fa-twitter" ? 'selected' : '' }}>Twitter</option>
                        <option value="fa-brands fa-linkedin-in" {{$footer->icon == "fa-brands fa-linkedin-in" ? 'selected' : '' }}>LinkedIn</option>
                        <option value="fa-brands fa-pinterest-p" {{$footer->icon == "fa-brands fa-pinterest-p" ? 'selected' : '' }}>Pinterest</option>

                        <option value="Skrill" {{$footer->icon == "Skrill" ? 'selected' : '' }}>Paypal</option>
                        <option value="Paypal" {{$footer->icon == "Paypal" ? 'selected' : '' }}>Skrill</option>
                        <option value="Visa" {{$footer->icon == "Visa" ? 'selected' : '' }}>Visa</option>
                        <option value="Apple Pay" {{$footer->icon == "Apple Pay" ? 'selected' : '' }}>Apple Pay</option>
                        <option value="Bitcoin" {{$footer->icon == "Bitcoin" ? 'selected' : '' }}>Bitcoin</option>></option>
                    
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.footers.index') }}" class="btn btn-secondary ms-3">Cancel</a>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    $("#footerForm").validate({
        rules: {
            type: { required: true},
            item_text: {
                required: {
                    depends: function (element) {
                        return $("#type").val() === "Quick Links";
                    }
                }
            },
            icon: {
                required: {
                    depends: function (element) {
                        const type = $("#type").val();
                        return type === "Payment Partners" || type === "Follow on Us";
                    }
                }
            },
            link: {
                required: {
                    depends: function (element) {
                        return $("#type").val() === "Quick Links";
                    }
                },
                regex: /^(https?:\/\/|\/)/
            }
        },
        messages: {
            type: { required: "Type is required"},
            item_text: { required: "Text is required"},
            link: { required: "Link is required", regex: "Link must start with http://, https:// or /"},
            icon: { required: "Icon is required"},
        }
    });

 $.validator.addMethod("regex", function (value, element, pattern) {
        return this.optional(element) || pattern.test(value);
    }, "Invalid format.");
});
</script>
@endpush
