@extends('admin.layouts.app')

@section('page-title') View Business @stop

@section('breadcrumb1')<li class="breadcrumb-item">View Merchants </li>@stop
@section('breadcrumb2')<li class="breadcrumb-item">Business </li>@stop

@section('page-styles')
    <style>
        .card-body p.mb-3 {
            border-bottom: 1px solid #f3f3f3;
            padding-bottom: 0.7rem;
        }
        .card .card-header {
            background-color: #eeebef;
            padding: 20px 20px 5px;
        }
        .card .card-body {
            padding: 20px 25px;
        }
        .nm-l:nth-child(even) {
            margin-left: 0;
            margin-right: 0;
            padding: 0;
        }
        .nm-l:nth-child(odd) {
            margin-left: 0;
            padding: 0;
            margin-right: 0;
        }
        .nm-l:nth-child(odd) div:nth-child(3) {
            /*padding-right: 0;*/
            margin-right: 0;
        }
        a {
            color: black;
        }
    </style>
@stop

@section('main_content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body dashboard-tabs p-0">
                    <div class="tab-content py-0 px-0">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                            <div class="d-flex flex-wrap justify-content-xl-between">
                                <div class="d-block border-md-right flex-grow-1 align-items-center p-3 item text-center">
                                    <i class="mdi mdi-credit-card-multiple icon-lg text-twitter"></i>
                                    <br>
                                    <div class="d-flex flex-column justify-content-around">
                                        <small class="mb-1 text-muted">Wallet Balance</small>
                                        <h5 class="mb-0">
                                            <a href="{{url('admin/business/view/wallet-transactions', $business->id)}}"
                                               title="View {{$business->name}} Wallet Transactions">
                                                &#8358;{{number_format($business->wallet->balance)}}
                                            </a>
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-block border-md-right flex-grow-1 align-items-center p-3 item text-center">
                                    <i class="mdi mdi-credit-card-multiple icon-lg text-twitter"></i>
                                    <br>
                                    <div class="d-flex flex-column justify-content-around">
                                        <small class="mb-1 text-muted">Total Transactions</small>
                                        <h5 class="mb-0">{{count($business->transactions)}} (&#8358;{{number_format($transactions_volume)}})</h5>
                                    </div>
                                </div>
                                <div class="d-block border-md-right flex-grow-1 align-items-center p-3 item text-center">
                                    <i class="mdi mdi-credit-card-multiple icon-lg text-success"></i>
                                    <br>
                                    <div class="d-flex flex-column justify-content-around">
                                        <small class="mb-1 text-muted">Commission Transactions</small>
                                        <h5 class="mb-0">{{count($business->commissionTransactions)}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @can('authorise')
            <div class="col-md-12 col-sm-12 col-lg-12 mb-4 text-center">
                <a class="btn btn-primary" href="{{url('/admin/impersonate/'. $business->id)}}">Log in as Business</a>
            </div>
        @endcan
    </div>
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 mb-4">
                <a href="{{ route('business.product.tranx', ['product' => $product['name'], 'id' => $business->id])}}">
                    <div class="card text-center">
                        <div class="card-header">
                            <p class="card-title">
                                {{$product['name']}}
                            </p>
                        </div>
                        <div class="card-body">
                            <p class="card-description">
                                Total Volume: <b>&#8358;{{ number_format($product['transactions_volume']) }}</b>
                            </p>
                            <p class="card-description">
                                Merchant's Commission: <b>&#8358;{{ number_format($product['merchant_commissions']) }}</b>
                            </p>
                            <p class="card-description">
                                Commission Earned: <b>&#8358;{{ number_format($product['vas_commissions']) }}</b>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row mb-5">
        <div class="col-md-8 col-sm-8" style="margin: auto">
            <a href="{{ route('business.documents', ['id' => $business->id]) }}"
               class="btn btn-primary"
               style="width: 100%; color: #fff">
                View Compliance Documents Uploaded by {{$business->name}}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Basic information</p>
                    </div>
                    <div class="card-body">
                        <p class="mb-3"><b>Name:</b> &emsp;{{$business->name}}</p>
                        <p class="mb-3"><b>Info:</b> &emsp;{{$business->info}}</p>
                        <p class="mb-3"><b>Phone:</b> &emsp;{{$business->phone}}</p>
                        <p class="mb-3"><b>Email:</b> &emsp;{{$business->email}}</p>
                        <p class="mb-3"><b>Website:</b> &emsp;{{$business->website}}</p>
                        <p class="mb-3"><b>Address:</b> &emsp;{{$business->address}}</p>
                        <p class="mb-3"><b>City:</b> &emsp;{{$business->city}}</p>
                        <p class="mb-3"><b>State:</b> &emsp;{{$business->state}}</p>
                        <p class="mb-3"><b>Postal Code:</b> &emsp;{{$business->phone}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Other information</p>
                    </div>
                    <div class="card-body">
                        <p class="mb-3"><b>Charge Back Email:</b> &emsp;{{$business->charge_back_email}}</p>
                        <p class="mb-3"><b>RC Number:</b> &emsp;{{$business->rc_number}}</p>
                    </div>
                </div>
            </div>
            <div class="stretch-card mt-3">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Internet</p>
                    </div>
                    <div class="card-body">
                        <p class="mb-3"><b>Twitter:</b> &emsp;{{$business->twitter}}</p>
                        <p class="mb-3"><b>Facebook:</b> &emsp;{{$business->facebook}}</p>
                        <p class="mb-3"><b>Instagram:</b> &emsp;{{$business->instagram}}</p>
                        <p class="mb-3"><b>LinkedIn:</b> &emsp;{{$business->linkedin}}</p>
                        <p class="mb-3"><b>Youtube:</b> &emsp;{{$business->youtube}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title text-center">Products</p>
                    </div>
                    <div class="card-body">
                        <form action="{{route('business.update.fees', $business->owner->id)}}">
                            @csrf
                            @foreach($products as $product)
                                <h5 class="col-md-12 col-sm-12 text-center"><b>{{ $product['name'] }}</b></h5>
                                <div class="row mb-4" style="border-bottom: 1px solid #f3f3f3;">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label for="firstname">
                                            {{$product['name'] == 'TRANSFER' || $product['name'] == 'PAYMENT GATEWAY' ? 'Charge Type': 'Commission Type'}}
                                        </label>
                                        @if($product['slug'] == 'payment_gateway')
                                            <select class="form-control"
                                                    id="charge_type" name="charge_type_{{$product['slug']}}">
                                                    <option value="{{$product['charge_type']}}" selected>PERCENTAGE</option>
                                            </select>
                                        @else
                                            <select class="form-control"
                                                    id="charge_type" name="charge_type_{{$product['slug']}}">
                                                @if($product['charge_type'] == 'FIXED')
                                                    <option value="{{$product['charge_type']}}" selected>FIXED</option>
                                                    <option value="PERCENTAGE">PERCENTAGE</option>
                                                @else
                                                    <option value="FIXED">FIXED</option>
                                                    <option value="{{$product['charge_type']}}" selected>PERCENTAGE</option>
                                                @endif
                                            </select>
                                        @endif
                                    </div>
                                    @if($product['name'] == 'TRANSFER')
                                        <h6 class="col-md-12 text-center">FLAT CHARGE OPTIONS</h6>
                                        <div class="form-check col-md-12 col-sm-12 mb-2">
                                            <label class="form-check-label ml-3">
                                                @if($product['is_flat'])
                                                    <input type="checkbox"
                                                           class="form-check-input" name="is_flat_{{ $product['slug'] }}"
                                                           checked>
                                                    Enable Flat Charge
                                                @else
                                                    <input type="checkbox"
                                                           class="form-check-input" name="is_flat_{{ $product['slug'] }}">
                                                    Enable Flat Charge
                                                @endif
                                                <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label for="flat_charge">Flat Charge</label>
                                            <input type="number"
                                                   value="{{ $product['flat_charge'] }}"
                                                   class="form-control"
                                                   id="flat_charge"
                                                   step="any"
                                                   name="flat_charge_{{ $product['slug'] }}">
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label for="flat_vas_commission">VAS Commission</label>
                                            <input type="number"
                                                   value="{{ $product['flat_vas_commission'] }}"
                                                   class="form-control"
                                                   id="flat_vas_commission"
                                                   step="any"
                                                   name="flat_vas_commission_{{ $product['slug'] }}">
                                        </div>
                                    @endif

                                    @if(isset($product['billers']) && sizeof($product['billers']) > 0)
                                        @if($product['name'] == 'TRANSFER')
                                            <h6 class="col-md-12 text-center">BANDED CHARGE OPTIONS</h6>
                                        @endif
                                        @foreach($product['billers'] as $name => $commissions)
                                            <div class="col-md-6 col-sm-6 row nm-l">
                                                <b style="border-bottom: 1px solid #eaeaeab8"
                                                    class="col-md-12 col-sm-12 text-center mb-2">{{ $name }}</b>
                                                <div class="form-group col-md-6 col-sm-6">
                                                    <label for="charges">{{$product['name'] == 'TRANSFER' ? 'Charge': 'Commission'}}</label>
                                                    <input type="number"
                                                           value="{{ $commissions['merchant_commission'] }}"
                                                           class="form-control"
                                                           id="charges"
                                                           step="any"
                                                           name="merchant_commission_{{ $product['slug'] }}_{{ $name }}">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-6">
                                                    <label for="charges">VAS Commission</label>
                                                    <input type="number"
                                                           value="{{ $commissions['vas_commission'] }}"
                                                           class="form-control"
                                                           id="charges"
                                                           step="any"
                                                           name="vas_commission_{{ $product['slug']}}_{{ $name }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="form-group col-md-4 col-sm-4" style="display:{{$product['name'] == 'PAYMENT GATEWAY' ? 'none' : ''}}">
                                            <label for="merchant_commission">{{$product['name'] == 'PAYMENT GATEWAY' ? 'Charge' : 'Commission'}}</label>
                                            <input type="number"
                                                   value="{{ $product['merchant_commission'] }}"
                                                   class="form-control"
                                                   id="merchant_commission"
                                                   step="any"
                                                   name="merchant_commission_{{ $product['slug'] }}">
                                        </div>
                                        @if($product['name'] == 'PAYMENT GATEWAY')
                                            <div class="form-group col-md-4 col-sm-4">
                                                <label for="charge">Charge</label>
                                                <input type="number"
                                                       value="{{ $product['charge'] }}"
                                                       class="form-control"
                                                       id="charge"
                                                       step="any"
                                                       name="charge_{{ $product['slug'] }}">
                                            </div>
                                            <div class="form-group col-md-4 col-sm-4">
                                                <label for="cap">Cap</label>
                                                <input type="number"
                                                       value="{{ $product['cap'] }}"
                                                       class="form-control"
                                                       id="cap"
                                                       step="any"
                                                       name="cap_{{ $product['slug'] }}">
                                            </div>
                                        @endif
                                        <div class="form-group col-md-4 col-sm-4">
                                            <label for="charge">VAS Commission</label>
                                            <input type="number"
                                                   value="{{ $product['vas_commission'] }}"
                                                   class="form-control"
                                                   id="charge"
                                                   step="any"
                                                   name="vas_commission_{{ $product['slug'] }}">
                                        </div>
                                    @endif
                                    <div class="form-check col-md-12 col-sm-12 mb-2 ml-3">
                                            <label class="form-check-label">
                                                @if($product['status'])
                                                    <input type="checkbox"
                                                           class="form-check-input" name="status_{{ $product['slug'] }}"
                                                            checked>
                                                    Active
                                                @else
                                                    <input type="checkbox"
                                                           class="form-check-input" name="status_{{ $product['slug'] }}">
                                                    Active
                                                @endif
                                                <i class="input-helper"></i></label>
                                        </div>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary form-control text-center">Update Product Fees For {{ $business->name }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        // $(document).ready(function() {
        //     let form = $("form");
        //
        //     form.submit(function (e) {
        //         e.preventDefault();
        //     });
        //
        //     form.click(function () {
        //
        //     });
        //
        // });
    </script>
@stop
