@extends('admin.layouts.app')

@section('page-title') Transactions @stop

@section('breadcrumb1')<li class="breadcrumb-item"><a href="{{route('business.view', $business->id)}}">View Business</a></li> @stop
@section('breadcrumb2')<li class="breadcrumb-item">{{$business->name}} Wallet Transactions</li> @stop

@section('page-styles')
    <style>
        .modal-body {
            display: flex;
            justify-content: space-evenly;
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
    <div class="row">
        <div class="col-md-12 mb-3 row">
            <div class="col-md-4 col-sm-4 form-inline">
                <label for="permissions" class="mr-2">Viewing </label>
                <select class="form-control"
                        id="permissions"
                        name="name"
                        onchange="window.location.href='/admin/business/view/wallet-transactions/{{$business->id}}?'
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
                <form method="post" action="{{ route('business.filter.wallet.tranx', ['id' => $business->id]) }}" class="row">
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
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <p class="card-title text-center">{{$business->name}} Wallet Transactions</p>
                    </div>
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
                            @if(isset($wallet_transactions) && count($wallet_transactions) > 0)
                                @foreach($wallet_transactions as $transaction)
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
    </script>
@stop
