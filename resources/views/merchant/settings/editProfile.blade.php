@extends('merchant.layouts.app')

@section('page-title') Edit Profile @stop

@section('breadcrumb1')/&nbsp; Settings @stop
@section('breadcrumb2')/&nbsp; Edit Profile @stop

@section('main_content')
    <ul class="nav nav-tabs px-4" role="tablist" style="margin-bottom: 2rem;">
        <li class="nav-item">
            <a class="nav-link active" href="#" role="tab">Profile</a>
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
            <a class="nav-link" href="{{route('settings.password')}}" role="tab">Reset Password</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    {{--                <h4 class="card-title"></h4>--}}
                    <form class="forms-sample" method="POST" action="{{route('profile.update')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text"
                                   value="{{isset($user->firstname) ? $user->firstname : ''}}"
                                   class="form-control"
                                   required
                                   id="firstname"
                                   name="firstname">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text"
                                   value="{{isset($user->lastname) ? $user->lastname : ''}}"
                                   class="form-control"
                                   required
                                   id="lastname"
                                   name="lastname">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email"
                                   value="{{isset($user->email) ? $user->email : ''}}"
                                   class="form-control"
                                   required
                                   id="email"
                                   name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="number"
                                   class="form-control"
                                   value="{{isset($user->phone) ? $user->phone : ''}}"
                                   id="phone"
                                   name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
