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
                <li class="breadcrumb-item" aria-current="page">Create Header</li>
                </ul>
            </div>
            </div>
            <h4 class="text-start mb-4">Create Header</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('admin.headers.store') }}" id="headerForm" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{old('name') }}">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control" id="link" name="link" value="{{old('link') }}">
                </div>
                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('admin.headers.index') }}" class="btn btn-secondary ms-3">Cancel</a>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    $("#headerForm").validate({
        rules: {
            name: { required: true},
            link: {
                required: true;
                regex: /^(https?:\/\/|\/)/
            }
        },
        messages: {
            name: { required: "Type is required"},
            name: { required: "Link is required", regex: "Link must start with http://, https:// or /"},
        }
    });

 $.validator.addMethod("regex", function (value, element, pattern) {
        return this.optional(element) || pattern.test(value);
    }, "Invalid format.");
});
</script>
@endpush
