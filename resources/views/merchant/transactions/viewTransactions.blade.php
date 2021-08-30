@extends('merchant.layouts.app')

@section('page-title') Transactions @stop

@section('breadcrumb1')/&nbsp; Transactions @stop

@section('page-styles')
    <style>
        table .btn {
            font-size: 13px;
            padding: 0.075rem 0.95rem;
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
                        onchange="window.location.href='/merchant/transactions?'
                            +this.value">
                    @if($option == 'date_range')
                        <option value="" selected>Date range</option>
                    @endif
                    <option value="" {{$option == 'today' ? 'selected' : ''}}>Today</option>
                    <option value="week" {{$option == 'week' ? 'selected' : ''}}>This Week</option>
                    <option value="month" {{$option == 'month' ? 'selected' : ''}}>This Month</option>
                    <option value="year" {{$option == 'year' ? 'selected' : ''}}>This Year</option>
                </select>
            </div>
            <div class="col-md-8 col-sm-8">
                <form method="post" action="{{route('merchant.transactions.filter')}}"
                      class="row">
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
                            <label for="reference" class="mr-2">Ext. Reference</label>
                            <input value="{{isset($filter['reference']) && !is_null($filter['reference']) ? $filter['reference'] : ''}}"
                                   type="text" name="reference" id="reference" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="status" class="mr-2">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="all" {{isset($filter['status']) && $filter['status'] === 'all' ? 'selected' : ''}}>All</option>
                                <option value="PENDING"{{isset($filter['status']) && $filter['status'] === 'PENDING' ? 'selected' : ''}}>PENDING</option>
                                <option value="SUCCESSFUL"{{isset($filter['status']) && $filter['status'] === 'SUCCESSFUL' ? 'selected' : ''}}>SUCCESSFUL</option>
                                <option value="FAILED"{{isset($filter['status']) && $filter['status'] === 'FAILED' ? 'selected' : ''}}>FAILED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="product" class="mr-2">Product</label>
                            <select name="product" id="product" class="form-control">
                                <option value="all" {{isset($filter['product']) && $filter['product'] === 'all' ? 'selected' : ''}}>All</option>
                                <option value="AIRTIME" {{isset($filter['product']) && $filter['product'] === 'AIRTIME' ? 'selected' : ''}}>AIRTIME</option>
                                <option value="DATA" {{isset($filter['product']) && $filter['product'] === 'DATA' ? 'selected' : ''}}>DATA</option>
                                <option value="CABLE-TV" {{isset($filter['product']) && $filter['product'] === 'CABLE-TV' ? 'selected' : ''}}>CABLE-TV</option>
                                <option value="ELECTRICITY" {{isset($filter['product']) && $filter['product'] === 'ELECTRICITY' ? 'selected' : ''}}>ELECTRICITY</option>
                                <option value="TRANSFER" {{isset($filter['product']) && $filter['product'] === 'TRANSFER' ? 'selected' : ''}}>TRANSFER</option>
                            </select>
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
                    <div>
                        <p class="card-title mb-4 text-center">Transactions</p>
                    </div>
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Charge</th>
                                <th>Net amount</th>
                                <th>Reference</th>
                                <th>Ext. Reference</th>
                                <th>Status</th>
                                <th>Info</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->type}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->charge}}</td>
                                    <td>{{$transaction->net_amount}}</td>
                                    <td>{{$transaction->reference}}</td>
                                    <td>{{$transaction->external_reference}}</td>
                                    <td class="{{$transaction->color}}">
                                        <strong>{{$transaction->status}}</strong>
                                    </td>
                                    <td>{{$transaction->info}}</td>
                                    <td>{{\Carbon\Carbon::parse($transaction->created_at)->format('d M, Y H:i:s A')}}</td>
                                    <td>
                                        <a href="{{ route('transaction.dispute', $transaction->reference) }}" class="btn btn-primary">
                                            Dispute
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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
                "iDisplayLength" : 25,
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
