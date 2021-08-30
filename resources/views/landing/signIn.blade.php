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
            top: 0.9rem;
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
    </style>
@stop

@section('content')
    <div class="auth-form-transparent text-left p-3">
        {{--        <div class="brand-logo">--}}
        {{--            <img src="{{asset('images/logo.svg')}}" alt="logo">--}}
        {{--        </div>--}}
        <h4 class="text-center font-weight-bold" style="color: #002061;">SIGN IN</h4>
{{--        <h6 class="font-weight-light"></h6>--}}
        <form class="pt-3" method="post" action="{{route('login')}}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           id="email" placeholder="Email" required>
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
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}"
                           id="password" placeholder="Password" required>
                    <span toggle="#password" class="mdi mdi-eye-off field-icon toggle-password"></span>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p style="font-size:.7925rem">Not a Merchant? <a href="{{url('register')}}">Sign Up here</a></p>
                </div>
                @if (Route::has('password.request'))
                <div>
                    <p style="font-size:.7925rem">
                        <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                    </p>
                </div>
                @endif
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
            </div>
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
