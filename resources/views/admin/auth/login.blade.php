@extends('admin.auth.layouts.master')
@section('content')
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
      <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
        <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
      </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main relative">
      <div class="auth-wrapper v1 flex items-center w-full h-full min-h-screen">
        <div class="auth-form flex items-center justify-center grow flex-col min-h-screen relative p-6 ">
          <div class="w-full max-w-[350px] relative">
            <div class="auth-bg ">
              <span class="absolute top-[-100px] right-[-100px] w-[300px] h-[300px] block rounded-full bg-theme-bg-1 animate-[floating_7s_infinite]"></span>
              <span class="absolute top-[150px] right-[-150px] w-5 h-5 block rounded-full bg-primary-500 animate-[floating_9s_infinite]"></span>
              <span class="absolute left-[-150px] bottom-[150px] w-5 h-5 block rounded-full bg-theme-bg-1 animate-[floating_7s_infinite]"></span>
              <span class="absolute left-[-100px] bottom-[-100px] w-[300px] h-[300px] block rounded-full bg-theme-bg-2 animate-[floating_9s_infinite]"></span>
            </div>
            <div class="card sm:my-12  w-full shadow-none">
              <div class="card-body !p-10">
                <div class="text-center mb-8">
                  <a href="#"><img src="{{asset('assets/images/logo.svg')}}" alt="img" class="mx-auto auth-logo"/></a>
                </div>
                <h4 class="text-center font-medium mb-4">Login</h4>
                <form id="login" method="POST" action="{{ route('admin.login') }}">
                  @csrf
                <div class="mb-3">
                  <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email Address" value="{{ old('email') ?: Cookie::get('remember_email') }}">
                                    @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                </div>
                <div class="mb-4">
                  <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="Password" value="{{ old('password') ?: Cookie::get('remember_password') }}">
                                    @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                </div>
               
                <div class="flex mt-1 justify-between items-center flex-wrap">
                  <div class="form-check">
                    <input class="form-check-input input-primary" name="remember" type="checkbox" id="customCheckc1" {{ old('remember') || Cookie::get('remember_admin') == '1' ? 'checked' : '' }}/>
                    <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label>
                  </div>
                  {{-- <h6 class="font-normal text-primary-500 mb-0">
                    <a href="#"> Forgot Password? </a>
                  </h6> --}}
                </div>
                <div class="mt-4 text-center">
                  <button type="submit" class="btn btn-primary mx-auto shadow-2xl">Login</button>
                </div>
              </form>
                {{-- <div class="flex justify-between items-end flex-wrap mt-4">
                  <h6 class="font-medium mb-0">Don't have an Account?</h6>
                  <a href="register-v1.html" class="text-primary-500">Create Account</a>
                </div> --}}
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
  

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]">
    </div>

@endsection