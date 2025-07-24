@extends('users.layouts.master')
@section('content')
<section class="login-page">
    <div class="container">
        <div class="row justify-content-between align-items-md-center">
            <div class="col-md-6 col-lg-6 col-xl-6 d-none d-xl-block">
                <div class="login-bg">
                    <img src="{{asset('assets/user/image/login-bg.png')}}" class="img-fluid" alt="">
                </div>
            </div>

            <div class="col-md-12 col-lg-12 col-xl-6">
                <form id="previewForm" method="POST" action="{{route('home.preview.submit')}}" class="login-sign-form">
                    @csrf
                    <h3>Create an Account</h3>
                    <p>Please enter your credential Details.</p>
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <div class="form-group mt-4">
                        <label for="">Enter Your Full Name</label>
                        <input type="text" class="form-control mt-1" value="{{$user->name}}" disabled>
                    </div>

                    <div class="form-group mt-4">
                        <label class="d-block" for="">Enter Your Phone number</label>
                        <input type="text" id="mobile_code" class="form-control mt-1" placeholder="Enter phone number" name="phone">
                    </div>

                    <div class="form-group mt-4">
                        <label class="d-block" for="">Enter Your Email</label>
                        <div class="position-relative">
                            <input type="email" name="email" class="form-control mt-1" placeholder="info@gmail.com" value="{{$user->email}}" disabled>
                        </div>
                    </div>

                     <div class="form-check mt-4 mb-2">
                        <input class="form-check-input" type="checkbox" name="agree_terms" id="terms" value="1" {{ old('agree_terms', 1) ? 'checked' : '' }} id="terms">
                        <label class="form-check-label" for="terms">
                            I am accepting all Terms & Conditions
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="marketing" id="marketing" value="1"  {{ old('marketing', 1) ? 'checked' : '' }} id="marketing">
                        <label class="form-check-label" for="marketing">
                            Are you interested to receive marketing communications
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary text-uppercase w-100 mt-5">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
// -----Country Code Selection
$("#mobile_code").intlTelInput({
	initialCountry: "us",
	separateDialCode: true,
	// utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
});

$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 60) {
        $('header').addClass("fixed");
    } else {
        $('header').removeClass("fixed");
    }
});
</script>
<script>
    $(function () {
    // 🔹 Set up jQuery Validation for the registration form
    $('#previewForm').validate({
        rules: {
            phone: {
                required: true,
                digits: true,
                minlength: 10
            },
            agree_terms: {
                required: true
            }
        },
        messages: {
            phone: {
                required: "Phone number is required",
                digits: "Only numbers allowed",
                minlength: "Enter a valid phone number"
            },
            agree_terms: "You must accept the terms and conditions"
        },
        errorElement: 'div',
        errorClass: 'text-danger'
    });
});
</script>
@endpush