@extends('merchant.layouts.app')

@section('page-title') Transactions @stop

@section('breadcrumb1')<li class="breadcrumb-item"><a href="{{route('products.view')}}">Products</a></li> @stop
@section('breadcrumb2')<li class="breadcrumb-item">{{$_GET['product']}} Transactions</li> @stop

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
                        onchange="window.location.href='/merchant/product/tranx?product={{$_GET['product']}}&'
                            +this.value">
                    @if($product['option'] == 'filter')
                        <option value="" selected>Filter</option>
                    @endif
                    <option value="" {{$product['option'] == 'today' ? 'selected' : ''}}>Today</option>
                    <option value="week" {{$product['option'] == 'week' ? 'selected' : ''}}>This Week</option>
                    <option value="month" {{$product['option'] == 'month' ? 'selected' : ''}}>This Month</option>
                    <option value="year" {{$product['option'] == 'year' ? 'selected' : ''}}>This Year</option>
                </select>
            </div>
            <div class="col-md-8 col-sm-8">
                <form method="post" action="{{route('product.tranx.filter',
                        ['product' => $_GET['product']])}}" class="row">
                    @csrf
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="from_date" class="mr-2">View From</label>
                            <input value="{{isset($product['from_date']) ? $product['from_date'] :''}}" name="from_date" type="date" id="from_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="to_date" class="mr-2">To</label>
                            <input value="{{isset($product['to_date']) ? $product['to_date'] :''}}" type="date" name="to_date" id="to_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="reference" class="mr-2">Reference</label>
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
                        <p class="card-title text-center">{{$_GET['product']}} Transactions ( &#8358;{{number_format(collect($product['transactions'])->sum('amount'))}} )</p>
                    </div>
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Business</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Charge</th>
                                <th>Net amount</th>
                                <th>Reference</th>
                                <th>Ext. Reference</th>
                                <th>Status</th>
                                <th>Info</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product['transactions'] as $transaction)
                                <tr>
                                    <td>{{$transaction->business->name}}</td>
                                    <td>{{$transaction->type}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->charge}}</td>
                                    <td>{{$transaction->net_amount}}</td>
                                    <td>{{$transaction->reference}}</td>
                                    <td>{{$transaction->external_reference}}</td>
                                    <td class="{{$transaction->color}}">
                                        <a href="javascript:void(0)"
                                           style="color: inherit">
                                            <strong>{{$transaction->status}}</strong>
                                        </a>
                                    </td>
                                    <td>{{$transaction->info}}</td>
                                    <td>{{\Carbon\Carbon::parse($transaction->created_at)->format('d M, Y H:i:s A')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    Merchant Commission Transactions   --}}
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <p class="card-title text-center">{{ $_GET['product'] }} Commission Transactions ( &#8358;{{number_format(collect($product['merchant_commissions'])->sum('amount'))}} )</p>
                    </div>
                    <div class="table-responsive">
                        <table id="commissionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Prev balance</th>
                                <th>New balance</th>
                                <th>Product</th>
                                <th>Info</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product['merchant_commissions'] as $transaction)
                                <tr>
                                    <td>{{$transaction->type}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->prev_balance}}</td>
                                    <td>{{$transaction->new_balance}}</td>
                                    <td>{{$transaction->product}}</td>
                                    <td>{{$transaction->info}}</td>
                                    <td>{{\Carbon\Carbon::parse($transaction->created_at)->format('d M, Y H:i:s A')}}</td>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#transactionsTable').dataTable( {
                dom: 'Blfrtip',
                paging: true,
                "order": [],
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );

            $('#commissionsTable').dataTable( {
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
