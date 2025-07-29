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
                <li class="breadcrumb-item" aria-current="page">Edit Admin Users</li>
                </ul>
            </div>
            </div>
        <h3 class="text-start mb-4">Edit User</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('admin.users.update',$user->id) }}" id="adminUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Password Confirmation</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
                <div class="mb-4">
                <label for="user_role_id" class="form-label">User Role</label>
                <select class="form-control" name="user_role_id" id="user_role_id">
                    <option value="">Select User Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->user_role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->role_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Assign Permissions</label>
                <div class="permissions-wrapper">
                <div class="row">
                    @php
                        $selectedPermissions = old('permissions', optional($user->role)->permissions ? $user->role->permissions->pluck('id')->toArray() : []);
                    @endphp
                    @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    id="permission_{{ $permission->id }}"
                                        {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                    {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-3">Cancel</a>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    $.validator.addMethod("pwcheck", function(value) {
        return /[A-Z]/.test(value) &&    // at least one uppercase letter
               /[a-z]/.test(value) &&    // at least one lowercase letter
               /[0-9]/.test(value) &&    // at least one digit
               /[@$!%*?&#]/.test(value); // at least one special character
    });
    // Custom checkbox group validator
    $.validator.addMethod("permissionRequired", function(value, element, param) {
        return $('input[name="permissions[]"]:checked').length > 0;
    }, "Please select at least one permission.");
    $("#adminUserForm").validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            user_role_id: { required: true},
            password: {
                minlength: {
                    param: 6,
                    depends: function(element) {
                        return $(element).val().length > 0;
                    }
                },
                pwcheck: {
                    depends: function(element) {
                        return $(element).val().length > 0;
                    }
                }
            },
            password_confirmation: {
                equalTo: "#password",
                required: {
                    depends: function(element) {
                        return $("#password").val().length > 0;
                    }
                }
            },
            'permissions[]': {
                permissionRequired: true
            }
        },
        messages: {
            name: { required: "Name is required", minlength: "Must be at least 3 characters" },
            email: { required: "Email is required", email: "Enter a valid email" },
            user_role_id: { required: "User Role is required"},
            password: {
                minlength: "Password must be at least 6 characters",
                pwcheck: "Password must contain uppercase, lowercase, number, and special character"
            },
            password_confirmation: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            },
            'permissions[]': {
                permissionRequired: "Please select at least one permission"
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "permissions[]") {
                error.appendTo(".permissions-wrapper");
            } else {
                error.insertAfter(element);
            }
        }
    });
});
</script>
@endpush
