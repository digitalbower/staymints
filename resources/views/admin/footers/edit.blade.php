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
                        <option value="fab fa-facebook text-white" {{$footer->icon == "fab fa-facebook text-white" ? 'selected' : '' }}>Facebook</option>
                        <option value="fab fa-twitter text-white" {{$footer->icon == "fab fa-twitter text-white" ? 'selected' : '' }}>Twitter</option>
                        <option value="fab fa-linkedin text-white" {{$footer->icon == "fab fa-linkedin text-white" ? 'selected' : '' }}>LinkedIn</option>
                        <option value="fab fa-pinterest text-white" {{$footer->icon == "fab fa-pinterest text-white" ? 'selected' : '' }}>Pinterest</option>

                        <option value="https://img.icons8.com/?size=100&id=13611&format=png&color=000000" {{$footer->icon == "https://img.icons8.com/?size=100&id=13611&format=png&color=000000" ? 'selected' : '' }}>Paypal</option>
                        <option value="https://img.icons8.com/?size=100&id=61469&format=png&color=000000" {{$footer->icon == "https://img.icons8.com/?size=100&id=61469&format=png&color=000000" ? 'selected' : '' }}>Apple Pay</option>
                        <option value="https://img.icons8.com/?size=100&id=63192&format=png&color=000000" {{$footer->icon == "https://img.icons8.com/?size=100&id=63192&format=png&color=000000" ? 'selected' : '' }}>Bitcoin</option>
                        <option value="https://img.icons8.com/?size=100&id=13608&format=png&color=000000" {{$footer->icon == "https://img.icons8.com/?size=100&id=13608&format=png&color=000000" ? 'selected' : '' }}>Visa</option>
                        <option value="https://img.icons8.com/?size=100&id=EOscN9Kp2I6q&format=png&color=C850F2" {{$footer->icon == "https://img.icons8.com/?size=100&id=EOscN9Kp2I6q&format=png&color=C850F2" ? 'selected' : '' }}>Skrill</option>></option>
                    
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
