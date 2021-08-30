@extends('merchant.layouts.app')

@section('page-title') Edit Business @stop

@section('breadcrumb1')/&nbsp; Settings @stop
@section('breadcrumb2')/&nbsp; Change Password @stop

@section('page-styles')
    <style>
        .important:after {
            content: ' *';
            color: red;
        }
        .field-icon {
            float: right;
            margin-right: 8px;
            margin-top: -26px;
            position: relative;
            cursor: pointer;
            z-index: 2;
            color: #555;
        }
    </style>
@stop

@section('main_content')
    <ul class="nav nav-tabs px-4" role="tablist" style="margin-bottom: 2rem;">
        <li class="nav-item">
            <a class="nav-link" href="{{route('settings.profile')}}" role="tab">Profile</a>
        </li>
        @role('MERCHANT|MERCHANT_ADMIN')
            <li class="nav-item">
                <a class="nav-link" href="{{route('settings.business')}}" role="tab">Business</a>
            </li>
        @endrole
        <li class="nav-item">
            <a class="nav-link" href="{{route('settings.documents')}}" role="tab">Compliance</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#" role="tab">Reset Password</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    {{--                <h4 class="card-title"></h4>--}}
                    <form class="forms-sample" method="POST" action="{{route('change.password')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="current_password" class="important">Current Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="current_password"
                                   name="current_password"
                                   required>
                            <span toggle="#current_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="important">New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="new_password"
                                   name="new_password"
                                   required>
                            <span toggle="#new_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password" class="important">Confirm New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="confirm_new_password"
                                   name="confirm_new_password"
                                   required>
                            <span toggle="#confirm_new_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Change</button>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        (function($) {

            $(".toggle-password").click(function() {

                $(this).toggleClass("fa-eye-slash fa-eye");
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
