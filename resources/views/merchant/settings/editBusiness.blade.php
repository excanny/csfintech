@extends('merchant.layouts.app')

@section('page-title') Edit Business @stop

@section('breadcrumb1')/&nbsp; Settings @stop
@section('breadcrumb2')/&nbsp; Edit Business @stop

@section('main_content')
    <ul class="nav nav-tabs px-4" role="tablist" style="margin-bottom: 2rem;">
        <li class="nav-item">
            <a class="nav-link" href="{{route('settings.profile')}}" role="tab">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#" role="tab">Business</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.documents') }}" role="tab">Compliance</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('settings.password')}}" role="tab">Reset Password</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Basic Information</h4>
                    <form class="forms-sample" method="POST" action="{{route('business.update.basic')}}">
                        <div class="form-group">
                            {{csrf_field()}}
                            <label for="name">Business Name</label>
                            <input type="text"
                                   value="{{isset($business->name) ? $business->name : ''}}"
                                   class="form-control"
                                   id="name"
                                   required
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="info">Brief Description</label>
                            <input type="text"
                                   value="{{isset($business->info) ? $business->info : ''}}"
                                   class="form-control"
                                   id="info"
                                   name="info">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="number"
                                   value="{{isset($business->phone) ? $business->phone : ''}}"
                                   class="form-control"
                                   id="phone"
                                   name="phone">
                        </div>
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="text"
                                   value="{{isset($business->website) ? $business->website : ''}}"
                                   class="form-control"
                                   id="website"
                                   name="website">
                        </div>
                        <div class="form-group">
                            <label for="address">Office Address</label>
                            <input type="text"
                                   value="{{isset($business->address) ? $business->address : ''}}"
                                   class="form-control"
                                   id="address"
                                   name="address">
                        </div>
                        <div class="form-group">
                            <input type="text"
                                   value="{{isset($business->city) ? $business->city : ''}}"
                                   class="form-control"
                                   id="city"
                                   name="city"
                                   placeholder="City">
                        </div>
                        <div class="form-group">
                            <input type="text"
                                   value="{{isset($business->state) ? $business->state : ''}}"
                                   class="form-control"
                                   id="state"
                                   name="state"
                                   placeholder="State">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text"
                                   value="{{isset($business->postal_code) ? $business->postal_code : ''}}"
                                   class="form-control"
                                   id="postal_code"
                                   name="postal_code">
                        </div>
                        <div class="form-group">
                            <label for="rc_number">RC Number</label>
                            <input type="text"
                                   value="{{isset($business->rc_number) ? $business->rc_number : ''}}"
                                   class="form-control"
                                   id="rc_number"
                                   name="rc_number">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Email and Bank Details</h4>
                    <form class="forms-sample" method="POST" action="{{route('business.update.emails')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="email">Business Email</label>
                            <input type="email"
                                   value="{{isset($business->email) ? $business->email : ''}}"
                                   class="form-control"
                                   id="email"
                                   required
                                   name="email">
                        </div>
                        <div class="form-group">
                            <label for="charge_back_email">Charge Back Email</label>
                            <input type="email"
                                   value="{{isset($business->charge_back_email) ? $business->charge_back_email : ''}}"
                                   class="form-control"
                                   id="charge_back_email"
                                   name="charge_back_email">
                        </div>
                        <div class="form-group">
                            <label for="bank_name">Bank</label>
                            <input type="text"
                                   value="{{isset($business->bank_name) ? $business->bank_name : old('bank_name')}}"
                                   class="form-control"
                                   id="bank_name"
                                   name="bank_name"
                                   placeholder="Bank Name">
                        </div>
                        <div class="form-group">
                            <input type="text"
                                   value="{{isset($business->bank_account_name) ? $business->bank_account_name : old('bank_account_name')}}"
                                   class="form-control"
                                   id="bank_account_name"
                                   name="bank_account_name"
                                   placeholder="Bank Account Name">
                        </div>
                        <div class="form-group">
                            <input type="number"
                                   value="{{isset($business->bank_account_number) ? $business->bank_account_number : old('bank_account_number')}}"
                                   class="form-control"
                                   id="bank_account_number"
                                   name="bank_account_number"
                                   placeholder="Bank Account Number">
                        </div>
                        <div class="form-group">
                            <input type="number"
                                   value="{{isset($business->bank_code) ? $business->bank_code : old('bank_code')}}"
                                   class="form-control"
                                   id="bank_code"
                                   name="bank_code"
                                   placeholder="Bank Code">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2" onclick="showLoader(this)">Save</button>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Internet</h4>
                    <form class="forms-sample" method="POST" action="{{route('business.update.internet')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="text"
                                   value="{{isset($business->facebook) ? $business->facebook : ''}}"
                                   class="form-control"
                                   id="facebook"
                                   name="facebook"
                                   placeholder="URL">
                        </div>
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="text"
                                   value="{{isset($business->twitter) ? $business->twitter : ''}}"
                                   class="form-control"
                                   id="twitter"
                                   name="twitter"
                                   placeholder="URL">
                        </div>
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="text"
                                   value="{{isset($business->instagram) ? $business->instagram : ''}}"
                                   class="form-control"
                                   id="instagram"
                                   name="instagram"
                                   placeholder="URL">
                        </div>
                        <div class="form-group">
                            <label for="linkedin">LinkedIn</label>
                            <input type="text"
                                   value="{{isset($business->linkedin) ? $business->linkedin : ''}}"
                                   class="form-control"
                                   id="linkedin"
                                   name="linkedin"
                                   placeholder="URL">
                        </div>
                        <div class="form-group">
                            <label for="youtube">Youtube</label>
                            <input type="text"
                                   value="{{isset($business->youtube) ? $business->youtube : ''}}"
                                   class="form-control"
                                   id="youtube"
                                   name="youtube"
                                   placeholder="URL">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Notification Settings</h4>
                    <form class="forms-sample" method="POST" action="{{route('business.update.notification')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="email">Minimum Balance Notification</label>
                            <input type="number"
                                   value="{{isset($business->alert_balance) ? $business->alert_balance : ''}}"
                                   class="form-control"
                                   placeholder="Set Value"
                                   id="alert_balance"
                                   name="alert_balance">
                            <p>NOTE: Leave empty to disable</p>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2" onclick="showLoader(this)">Save</button>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        const showLoader = (element) => {
            element.innerHTML = 'Saving...';
            element.insertAdjacentHTML( 'beforeend',' <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        };
    </script>
@stop
