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
                <form action="" class="login-sign-form">
                    <h3>Create an Account</h3>
                    <p>Please enter your credential Details.</p>
                    <div class="form-group mt-4">
                        <label for="">Enter Your Full Name</label>
                        <input type="text" class="form-control mt-1" placeholder="johnsm" disabled>
                    </div>

                    <div class="form-group mt-4">
                        <label class="d-block" for="">Enter Your Phone number</label>
                        <input type="text" id="mobile_code" class="form-control mt-1" placeholder="(00) 123 456 7890" name="name" disabled>
                    </div>

                    <div class="form-group mt-4">
                        <label class="d-block" for="">Enter Your Email</label>
                        <div class="position-relative">
                            <input type="email" class="form-control mt-1" placeholder="info@gmail.com" disabled>
                            <!-- <a href="javascript:void(0);" class="otp-send btn btn-primary">Send OTP</a> -->
                        </div>
                    </div>

                    <div class="form-check mt-4 mb-2">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked disabled>
                        <label class="form-check-label" for="flexCheckDefault">
                            I am accepting all Terms & Conditions
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultone" disabled>
                        <label class="form-check-label" for="flexCheckDefaultone">
                            Are you interested to receive marketing communications
                        </label>
                    </div>
                    
                    <button type="button" class="btn btn-primary text-uppercase w-100 mt-5">Sign Up</button>

                    <!-- <div class="mt-5 text-center">
                        <p>Already have an account? <a href="javascript:void(0);" class="login-btn">Login</a></p>
                    </div> -->
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
@endpush