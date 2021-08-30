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
            top: 1.3rem;
            cursor: pointer;
            z-index: 6;
            color: #555;
        }
    </style>
@stop

@section('admin_content')
    <div class="auth-form-transparent text-left p-3">
        {{--        <div class="brand-logo">--}}
        {{--            <img src="{{asset('images/logo.svg')}}" alt="logo">--}}
        {{--        </div>--}}
        <h4>Welcome!</h4>
        <h6 class="font-weight-light">Sign In</h6>
        <form class="pt-3" method="post" action="{{route('merchant.login')}}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="email" name="email" class="form-control form-control-lg border-left-0"
                           id="email" placeholder="Username" required>
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
            {{--            <div class="my-2 d-flex justify-content-between align-items-center">--}}
            {{--                <div class="form-check">--}}
            {{--                    <label class="form-check-label text-muted">--}}
            {{--                        <input type="checkbox" class="form-check-input">--}}
            {{--                        Keep me signed in--}}
            {{--                    </label>--}}
            {{--                </div>--}}
            {{--                <a href="#" class="auth-link text-black">Forgot password?</a>--}}
            {{--            </div>--}}
            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
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
