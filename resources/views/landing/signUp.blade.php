@extends('mainLayouts.master')

@section('page-title') Sign In @stop

@section('page-styles')
    <style>
        .important:after {
            content: ' *';
            color: red;
        }
        .field-icon {
            position: absolute;
            right: 0.5rem;
            top: 1rem;
            cursor: pointer;
            z-index: 6;
            color: #555;
        }
        .logo-wrapper {
            position: absolute;
            z-index: 3;
        }
        .logo-wrapper a {
            color: #ee2b47;
        }
        .logo-wrapper a:hover {
            color: #851926;
            cursor: pointer;
            text-decoration: none;
        }
        .form-control-lg {
            height: 3.1rem;
        }
        .auth .login-half-bg {
            margin-top: 7rem;
        }
        .auth form .form-group {
            margin-bottom: 1rem;
        }
        .auth.auth-img-bg .auth-form-transparent {
            margin: 5% auto auto;
        }
    </style>
@stop

@section('content')
    <div class="auth-form-transparent text-left p-3">
        {{--        <div class="brand-logo">--}}
        {{--            <img src="{{asset('images/logo.svg')}}" alt="logo">--}}
        {{--        </div>--}}
        <h4 class="text-center font-weight-bold" style="color: #002061;">BECOME A MERCHANT</h4>
{{--        <h6 class="font-weight-light"></h6>--}}
        <form class="pt-3" method="post" action="{{route('register')}}">
            @csrf
            <div class="form-group">
                <label for="business_name">Name of Business</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-folder-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name="business_name" value="{{old('business_name')}}"
                           class="form-control form-control-lg border-left-0"
                           id="business_name" placeholder="Name of Business" required>
                </div>
            </div>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name="firstname" value="{{old('firstname')}}"
                           class="form-control form-control-lg border-left-0"
                           id="firstname" placeholder="First Name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name="lastname" value="{{old('lastname')}}"
                           class="form-control form-control-lg border-left-0"
                           id="lastname" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-email-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="email" name="email" value="{{old('email')}}"
                           class="form-control form-control-lg border-left-0"
                           id="email" placeholder="Email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-cellphone text-primary"></i>
                      </span>
                    </div>
                    <input type="number" name="phone" value="{{old('phone')}}" class="form-control form-control-lg border-left-0"
                           id="phone" placeholder="Phone" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" name="password" class="form-control form-control-lg border-left-0"
                           id="password" placeholder="Password" required>
                    <span toggle="#password" class="mdi mdi-eye-off field-icon toggle-password"></span>
                </div>
            </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                               <p style="font-size:.7925rem">Already a Merchant? <a href="{{route('login')}}">Sign in here</a></p>
                            </div>
{{--                            <a href="#" class="auth-link text-black">Forgot password?</a>--}}
                        </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">REGISTER</button>
            </div>
            {{--            <div class="mb-2 d-flex">--}}
            {{--                <button type="button" class="btn btn-facebook auth-form-btn flex-grow mr-1">--}}
            {{--                    <i class="mdi mdi-facebook mr-2"></i>Facebook--}}
            {{--                </button>--}}
            {{--                <button type="button" class="btn btn-google auth-form-btn flex-grow ml-1">--}}
            {{--                    <i class="mdi mdi-google mr-2"></i>Google--}}
            {{--                </button>--}}
            {{--            </div>--}}
            {{--            <div class="text-center mt-4 font-weight-light">--}}
            {{--                Don't have an account? <a href="register-2.html" class="text-primary">Create</a>--}}
            {{--            </div>--}}
        </form>
    </div>
@stop

@section('page-scripts')
    <script>
        (function($) {

            $(".toggle-password").click(function() {

                $(this).toggleClass("mdi-eye-off mdi-eye");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

        })(jQuery);
    </script>
@stop
