@extends('merchant.layouts.app')

@section('page-title') Dashboard @stop

@section('page-heading') <span class="p-0" id="greeting"></span><span>, {{$user->firstname}}</span>@stop

{{--@section('page-description') Your Dashboard. @stop--}}

@section('breadcrumb1')<li class="breadcrumb-item">Dashboard</li>@stop

@section('page-styles')
    <style>
        #transactionsTable, #transactionsTable th, #transactionsTable td{
            white-space: normal;
            word-break: break-word;
            padding: 10px 1px;
            font-size: 0.8rem;
        }
        .fa-cube {
            /*background: linear-gradient(to right, #7366ff 10%, #a927f9 100%);*/
            font-size: 28px;
        }
        @media screen and (min-width: 900px) {
            .filter-button {
                margin-top: 1.8rem;
                width: -webkit-fill-available;
            }
        }
    </style>
@stop

@section('main_content')
    <div class="col-md-12 mb-3 row">
        <div class="col-md-4 col-sm-4 form-inline">
        </div>
        <div class="col-md-8 col-sm-8">
            <form method="post" action="{{ route('merchant.index.filter') }}" class="row">
                @csrf
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="from_date" class="mr-2">View From</label>
                        <input value="{{isset($dates['from_date']) ? $dates['from_date'] :''}}" name="from_date" type="date" id="from_date" class="form-control">
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="to_date" class="mr-2">To</label>
                        <input value="{{isset($dates['to_date']) ? $dates['to_date'] :''}}" type="date" name="to_date" id="to_date" class="form-control">
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary filter-button">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row size-column">
        <div class="col-xl-12 box-col-12 xl-100">
            <div class="row dash-chart">
                <div class="col-md-12 col-sm-12 col-xl-12 text-center">
                    @if($business->status == 'ACTIVE')
                        <p class="text-success">Your Business is active</p>
                    @elseif($business->status == 'VERIFIED')
                        <p class="active text-warning">Your Business is verified,
                            It has to be authorized to become active</p>
                    @else
                        <p class="text-danger">Your Business is inactive,
                            It has to be verified and authorized to become active</p>
                    @endif
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Business name</span></p>
                                    <h4 class="f-w-500 mb-0 f-26"><span>{{ $business->name }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden" style="min-height: 149px;">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Business email</span></p>
                                    <h6 class="f-w-500 mb-0" style="word-break: break-word"><span>{{ $business->email }}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Wallet</span></p>
                                    <h4 class="f-w-500 mb-0 f-26">&#8358;<span class="counter">{{ number_format($business->wallet->balance) }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Total Transactions {{$business->transaction_option == 'date_range' ? '(Date Range)': ''}}</span></p>
                                    <h4 class="f-w-500 mb-0 f-26"><span class="counter">{{ $business->transactions_count }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Total Transactions This Year</span><span class="f-w-700 font-primary ml-2"></span></p>
                                    <h4 class="f-w-500 mb-0 f-26 counter">{{ $business->transactionsThisYear }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Total Transactions This Month</span><span class="f-w-700 font-primary ml-2"></span></p>
                                    <h4 class="f-w-500 mb-0 f-26 counter">{{ $business->transactionsThisMonth }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 box-col-4 col-md-4">
                    <div class="card o-hidden">
                        <div class="card-header card-no-border">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-spin fa-cog"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <p><span class="f-w-500 font-roboto">Team Count</span></p>
                                    <h4 class="f-w-500 mb-0 f-26"><span>{{ count($business->users) }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(sizeof($products) > 0)
                <div class="row size-column">
                    <h5 class="col-md-12 text-center mb-4 mt-4">Total Transactions By Products {{$business->transaction_option == 'date_range' ? '(Date Range)': ''}}</h5>
                    @foreach($products as $product)
                        <div class="col-xl-4 box-col-4 col-lg-6 col-md-4">
                            <div class="card o-hidden">
                                <div class="card-body">
                                    <div class="ecommerce-widgets media">
                                        <div class="media-body">
                                            <p class="f-w-500 font-roboto">{{$product['name']}}</p>
                                            @if($business->transaction_option != '')
                                                {{--                                            <span class="f-26">&#8358;</span>--}}
                                            @endif
                                            <span class="f-w-500 mb-0 f-26"><span class="">{{ number_format($product['total_transactions']) }}</span></span>
                                        </div>
                                        <div class="ecommerce-box light-bg-primary"><i class="fa fa-cube" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-xl-12 xl-100 box-col-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">RECENT TRANSACTIONS</h5>
                            <div class="responsive-tbl">
                                <div class="item">
                                    <div class="table-responsive product-list">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Business</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Charge</th>
                                                <th>Net amount</th>
                                                <th>Reference</th>
                                                <th>Status</th>
                                                <th>Channel</th>
                                                <th>Info</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($recent_transactions as $transaction)
                                                <tr>
                                                    <td>{{$business->name}}</td>
                                                    <td>{{$transaction->type}}</td>
                                                    <td>{{$transaction->amount}}</td>
                                                    <td>{{$transaction->charge}}</td>
                                                    <td>{{$transaction->net_amount}}</td>
                                                    <td>{{$transaction->reference}}</td>
                                                    <td>{{$transaction->status}}</td>
                                                    <td>{{$transaction->channel}}</td>
                                                    <td>{{$transaction->info}}</td>
                                                    <td style="white-space: nowrap">{{\Carbon\Carbon::parse($transaction->created_at)->format('d M, Y H:i:s A')}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
