@extends('admin.layouts.app')

@section('page-title') Add Administrator @stop

@section('breadcrumb1') <li class="breadcrumb-item">Administrators</li> @stop

@section('breadcrumb2')<li class="breadcrumb-item">Add Administrators</li>@stop

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
    @can('add administrator')
        <div class="row">
            <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
                <div class="card">
                    <div class="card-body">
                        {{--                <h4 class="card-title"></h4>--}}
                        <form class="forms-sample" method="POST" action="{{route('administrators.add')}}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="firstname" class="important">First Name</label>
                                <input type="text"
                                       class="form-control"
                                       required
                                       id="firstname"
                                       name="firstname">
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="important">Last Name</label>
                                <input type="text"
                                       class="form-control"
                                       required
                                       id="lastname"
                                       name="lastname">
                            </div>
                            <div class="form-group">
                                <label for="email" class="important">Email address</label>
                                <input type="email"
                                       class="form-control"
                                       required
                                       id="email"
                                       name="email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                       class="form-control"
                                       id="phone"
                                       name="phone">
                            </div>
                            <div class="form-group">
                                <label for="password" class="important">Password</label>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password">
                                <span toggle="#password" class="fa fa-eye-slash field-icon toggle-password"></span>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                            {{--                    <button class="btn btn-light">Cancel</button>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mt-5">
            <div class="col-md-12">
                <h4 class="text-center text-gray">You do not have permissions to view this page</h4>
            </div>
        </div>
    @endcan
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
