@extends('merchant.layouts.app')

@section('page-title') Wallet @stop

@section('breadcrumb1')/&nbsp; Wallet @stop
@section('breadcrumb2')/&nbsp; My Wallet @stop

@section('page-styles')
    <style>
        @media screen and (min-width: 900px) {
            .filter-button {
                margin-top: 1.8rem;
                width: -webkit-fill-available;
            }
        }
    </style>
@stop

@section('main_content')
    <div class="row">
        <div class="col-md-8 col-sm-8 col-12 grid-margin">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Balance</h4>
                    <h2><b>{{ number_format($wallet->balance, 2) }}</b></h2>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Account Number</h4>
                    <h2><b>{{ $wallet->account_number }}</b></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display:flex">
                    <div style="flex: 4">
                        <h4 class="card-title">Commission</h4>
                        <h2><b>{{ number_format($wallet->commission, 2) }}</b></h2>
                    </div>
                    <div style="flex: 1; display: flex">
                        @role('MERCHANT|MERCHANT_ADMIN')
                            <button class="btn mb-2 btn-primary" style="align-self: center"
                                    data-toggle="modal" data-target="#commission_transfer_modal">
                                Transfer
                            </button>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
        @role('MERCHANT|MERCHANT_ADMIN')
            <div class="modal fade" id="commission_transfer_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-center">Transfer Commission</h6>
                            <button type="button" style="float: right" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{route('wallet.transfer')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="target" class="important">Transfer To</label>
                                    <select class="form-control"
                                            id="target"
                                            name="target"
                                            required>
                                        <option hidden value="">
                                            Select...
                                        </option>
                                        <option value="wallet">My Wallet</option>
                                        <option value="bank">My Bank</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="amount" class="important">Amount</label>
                                    <input class="form-control"
                                           id="amount"
                                           name="amount"
                                           required>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" id="transfer" onclick="showLoader(this)">
                                        Transfer Commission
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        @endrole
        <div class="col-md-4 col-sm-4 col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('wallet.top-up.view') }}" class="btn btn-primary mb-5" style="width:100%">
                        <i data-feather="credit-card"></i> <br>
                        <span style="font-size: 1rem">Top up Online</span>
                    </a>
                    <a href="{{route('wallet.top-up.form')}}" class="btn btn-primary mb-5" style="width:100%">
                        <i data-feather="chevrons-right"></i><br>
                        <span style="font-size: 1rem">Top up with transfer</span>
                    </a>
                    <a href="{{route('wallet.view.requests')}}" class="btn btn-dark" style="width:100%">
                        <span>View All Top Up Requests</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 grid-margin">
            <div class="card">
                <div class="card-header">
                    <div class="col-md-12 mb-3 row">
                        <div class="col-md-4 col-sm-4 form-inline">
                            <label for="permissions" class="mr-2">Viewing </label>
                            <select class="form-control"
                                    id="permissions"
                                    name="name"
                                    onchange="window.location.href='/merchant/wallet/view/?'
                                    +this.value">
                                @if($option == 'filter')
                                    <option value="" selected>Filter</option>
                                @endif
                                <option value="" {{$option == 'today' ? 'selected' : ''}}>Today</option>
                                <option value="week" {{$option == 'week' ? 'selected' : ''}}>This Week</option>
                                <option value="month" {{$option == 'month' ? 'selected' : ''}}>This Month</option>
                                <option value="year" {{$option == 'year' ? 'selected' : ''}}>This Year</option>
                            </select>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <form method="post" action="{{ route('wallet.filter.tranx') }}" class="row">
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
                    <h4 class="card-title text-center">
                        Transaction history
                    </h4>

                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Previous Balance</th>
                                <th>New Balance</th>
                                <th>Type</th>
                                <th>Info</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($transactions) && count($transactions) > 0)
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{$transaction->amount}}</td>
                                        <td>{{$transaction->prev_balance}}</td>
                                        <td>{{$transaction->new_balance}}</td>
                                        <td>{{$transaction->type}}</td>
                                        <td>{{$transaction->info}}</td>
                                        <td>{{\Carbon\Carbon::parse($transaction->created_at)->format('d M, Y H:i:s A')}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('#transactionsTable').dataTable( {
                dom: 'Blfrtip',
                paging: true,
                "order": [],
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );

        const showLoader = (element) => {
            element.innerHTML = 'Transferring...';
            element.insertAdjacentHTML( 'beforeend',' <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        };
    </script>
@stop
