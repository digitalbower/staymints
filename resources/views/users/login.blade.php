@extends('users.layouts.master')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/user/css/all.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/user/css/aos.css')}}" />
<link rel="stylesheet" href="{{asset('assets/user/css/intlTelInput.css')}}" />
@endpush
@section('content')
<section class="login-page">
    <div class="container position-relative">
        <div class="row justify-content-between align-items-md-center">
            <div class="col-md-12 col-lg-12 col-xl-6 d-none d-xl-block">
                <div class="login-bg">
                    <img src="{{asset('assets/user/image/login-bg.png')}}" class="img-fluid" alt="">
                </div>
            </div>

            <div class="col-md-12 col-lg-12 col-xl-6">
                  <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div id="successToast" class="toast bg-success text-white" role="alert">
                        <div class="toast-body" id="successToastMessage">
                        </div>
                    </div>
                </div>
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
                    <div id="errorToast" class="toast text-white bg-danger" role="alert">
                        <div class="toast-body" id="errorToastMessage">
                        <!-- Message goes here -->
                        </div>
                    </div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

               
                <form method="POST" id="loginForm" action="{{ route('login.verify.otp') }}" class="login-sign-form login_process">
                    @csrf
                    <h3>Login to post an ad!</h3>
                    <p>Please enter your credential Details.</p>
                    <div class="form-group mt-4">
                        <label for="">Email Address</label>
                        <div class="position-relative">
                            <input type="email" id="loginemail" name="email" class="form-control mt-1" placeholder="">
                            <a href="javascript:void(0);" id="sendBtn" class="otp-send btn btn-primary">Send OTP</a>
                        </div>
                    </div>
                    <p id="loginOtpMessage" class="text-success mt-2 d-none"></p>
                    <div class="form-group mt-4">
                        <label for="">OTP</label>                        
                        <div class="d-flex align-items-center mt-1">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                        </div>
                         <input type="hidden" id="login_otp_full" name="otp">
                        @error('otp')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="redirect" value="{{ request()->input('redirect', '/') }}">
                    <button type="submit" class="btn btn-primary text-uppercase w-100 mt-5">Login</button>

                    <div class="mt-5 text-center">
                        <p>Or</p>
                        <div class="text-center my-5">
                            <a href="{{ url('auth/google?action=login') }}" class="google-account"><img src="{{asset('assets/user/image/google-icon.png')}}" class="img-fluid" alt="google"></a>
                        </div>
                        <p>Don't have an account? <a href="javascript:void(0);" class="sign-btn" id="sign-slide">Sign up</a></p>
                    </div>
                </form>
               
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div id="successToast" class="toast bg-success text-white" role="alert">
                        <div class="toast-body" id="successToastMessage">
                        </div>
                    </div>
                </div>
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
                    <div id="errorToast" class="toast text-white bg-danger" role="alert">
                        <div class="toast-body" id="errorToastMessage">
                        <!-- Message goes here -->
                        </div>
                    </div>
                </div>
                <form id="registerForm" method="POST" action="{{route('verify.otp')}}" class="login-sign-form signup_process">
                    @csrf
                    <h3>Create an Account</h3>
                    <p>Please enter your credential Details.</p>
                    <div class="form-group mt-4">
                        <label for="">Enter Your Full Name</label>
                        <input type="text" class="form-control mt-1" id="name" name="name" placeholder="Enter your name" value={{old('name')}}>
                    </div>

                    <div class="form-group mt-4">
                        <label class="d-block" for="">Enter Your Phone number</label>
                        <input type="text" id="mobile_code" name="phone" class="form-control mt-1" placeholder="Enter your Phone Number" value={{old('phone')}}>
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-4">
                        <label class="d-block" for="">Enter Your Email</label>
                        <div class="position-relative">
                            <input type="email" class="form-control mt-1" id="email" name="email" placeholder="Enter your Email" value={{old('email')}}>
                            <a href="javascript:void(0);" id="sendOtpBtn" class="otp-send btn btn-primary">Send OTP</a>
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
                    <p id="otpMessage" class="text-success mt-2 d-none"></p>
                    <div class="form-group mt-4">
                        <label for="">OTP</label>                        
                        <div class="d-flex align-items-center mt-1">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                            <input type="password" maxlength="1" name="otp[]" class="form-control text-center p-2 ms-2">
                        </div>
                        <input type="hidden" id="otp_full" name="otp">
                         @error('otp')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="redirect" value="{{ request()->input('redirect', '/') }}">
                    <button type="submit" class="btn btn-primary text-uppercase w-100 mt-5">Sign Up</button>
                   
                

                    <div class="mt-5 text-center">
                        <p>Or</p>
                        <div class="text-center my-5">
                            <a href="{{ url('auth/google?action=signup') }}" class="google-account"><img src="{{asset('assets/user/image/google-icon.png')}}" class="img-fluid" alt="google"></a>
                        </div>
                        <p>Already have an account? <a href="javascript:void(0);" class="login-btn">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{asset('assets/user/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/user/js/all.min.js')}}"></script>
<script src="{{asset('assets/user/js/intlTelInput-jquery.min.js')}}"></script>
<script>
$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 60) {
        $('header').addClass("fixed");
    } else {
        $('header').removeClass("fixed");
    }
});

$("#mobile_code").intlTelInput({
	initialCountry: "us",
	separateDialCode: true,
	// utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
});

$(".login-btn").click(function() {
    $(".signup_process").hide();
    $(".login_process").show();
});

$(".sign-btn").click(function() {
    $(".signup_process").show();
    $(".login_process").hide();
});

AOS.init({
  duration: 1000
});
</script>
<script>
$(document).ready(function () {
    // ✅ Auto-focus to next OTP input
    $('input[name="otp[]"]').on('input', function () {
        let index = $('input[name="otp[]"]').index(this);
        if ($(this).val().length === 1 && index < $('input[name="otp[]"]').length - 1) {
            $('input[name="otp[]"]').eq(index + 1).focus();
        }
    });
   $('#registerForm').on('submit', function (e) {
    let otp = '';
    let valid = true;

    $(this).find('input[name="otp[]"]').each(function () {
        const val = $(this).val().trim();
        if (val === '') {
            valid = false;
        }
        otp += val;
    });

    if (!valid || otp.length !== 6) {
        e.preventDefault(); // stop form from submitting
        toastr.error("Please enter all 6 digits of the OTP.");
        return false;
    }

    $(this).find('#otp_full').val(otp); // set full OTP in this form only
});

$('#loginForm').on('submit', function (e) {
    let otp = '';
    let valid = true;

    $(this).find('input[name="otp[]"]').each(function () {
        const val = $(this).val().trim();
        if (val === '') {
            valid = false;
        }
        otp += val;
    });

    if (!valid || otp.length !== 6) {
        e.preventDefault();
        toastr.error("Please enter all 6 digits of the OTP.");
        return false;
    }

    $(this).find('#login_otp_full').val(otp); // set full OTP in this form only
}); 
});
</script>
<script>
$(function () {
    // 🔹 Set up jQuery Validation for the registration form
    $('#registerForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10
            },
            email: {
                required: true,
                email: true
            },
            agree_terms: {
                required: true
            }
        },
        messages: {
            name: "Please enter your full name",
            phone: {
                required: "Phone number is required",
                digits: "Only numbers allowed",
                minlength: "Enter a valid phone number"
            },
            email: "Please enter a valid email address",
            agree_terms: "You must accept the terms and conditions"
        },
        errorElement: 'div',
        errorClass: 'text-danger'
    });

    // 🔹 On Send OTP button click
    $('#sendOtpBtn').click(function (e) {
        e.preventDefault();

        if ($('#registerForm').valid()) {
            let name = $('#name').val();
            let email = $('#email').val();
            let phone = $('#mobile_code').val();
            let agree_terms = $('#terms').is(':checked');
            let marketing = $('#marketing').is(':checked');

           
         $.ajax({
            url: "{{route('generate.otp')}}",
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({ 
                name: name,
                email: email,
                phone : phone,
                marketing: marketing,
                agree_terms : agree_terms
            }),
            success: function (response) {
                $('#otpMessage').removeClass('d-none').text("OTP sent to your email.");
            },
            error: function(xhr, status, error) {
                let errors = xhr.responseJSON?.errors;
                let message;

                if (errors) {
                    message = Object.values(errors).flat().join('<br>');
                } else {
                    message = "Something went wrong. Please try again.";
                }

                $('#errorToastMessage').html(message);
                let toast = new bootstrap.Toast(document.getElementById('errorToast'));
                toast.show();
            }
            });
        }
    });
});
</script>
<script>
$(function () {
    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: "Please enter a valid email address",
        },
        errorElement: 'div',
        errorClass: 'text-danger'
    });

    $('#sendBtn').click(function (e) {
        e.preventDefault();

        if ($('#loginForm').valid()) {
            let email = $('#loginemail').val();
           
         $.ajax({
            url: "{{route('login.generate.otp')}}",
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({ 
                email: email,
            }),
            success: function (response) {
                $('#loginOtpMessage').removeClass('d-none').text("OTP sent to your email.");
            },
            error: function(xhr, status, error) {
                let errors = xhr.responseJSON?.errors;
                let message;

                if (errors) {
                    message = Object.values(errors).flat().join('<br>');
                } else {
                    message = "Something went wrong. Please try again.";
                }

                $('#errorToastMessage').html(message);
                let toast = new bootstrap.Toast(document.getElementById('errorToast'));
                toast.show();
            }
            });
        }
    });
    


});
</script>

@endpush