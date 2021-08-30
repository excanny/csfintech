@extends('mainLayouts.master')
@section('page-title') Reset Password @stop
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
        <h4 class="text-center font-weight-bold" style="color: #002061;">{{ __('Reset Password') }}</h4>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form class="pt-3" method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                        </div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="my-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
