@extends('merchant.layouts.app')

@section('page-title') Transactions @stop

@section('breadcrumb1')/&nbsp; Transactions @stop

@section('page-styles')
    <style>
        table .btn {
            font-size: 13px;
            padding: 0.075rem 0.95rem;
        }
        .my-hover:hover {
            background: linear-gradient(to right, rgba(255, 102, 1, 0.14), rgba(0, 70, 253, 0.16)) !important;
            cursor: pointer;
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
                        onchange="window.location.href='/merchant/pg/transactions?'
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
                <form method="post" action="{{route('sagepay.merchant.transactions.filter')}}"
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
                            <button type="submit" class="btn btn-primary filter-button">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p class="card-title text-center">Payment Gateway Transactions</p>
                        <small class="text-center text-info">*Click row to view more details</small>
                    </div>
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
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
                            @foreach($transactions as $transaction)
                                <tr class="my-hover"
                                    data-toggle="modal"
                                    data-target="#more_details"
                                    tranx="{{ $transaction }}"
                                    onclick="viewMoreDetails(this)"
                                    title="Click to View More Details">
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="more_details">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Transaction Details</h6>
                    <button type="button" style="float: right" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <ul id="more_details_container">

                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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

        const viewMoreDetails = ( element ) => {
            let transaction = JSON.parse(element.getAttribute('tranx'));
            let container = $('#more_details_container');
            container.empty();
            container.append(`<li><b>Date:</b> ${transaction.created_at}</li>`);
            container.append(`<li><b>Reference:</b> ${transaction.reference}</li>`);
            container.append(`<li><b>Ext. Reference:</b> ${transaction.external_reference}</li>`);
            container.append(`<li><b>Customer email:</b> ${transaction.customer_email}</li>`);
            container.append(`<li><b>Amount:</b> ${transaction.amount}</li>`);
            container.append(`<li><b>Charge:</b> ${transaction.charge}</li>`);
            container.append(`<li><b>Net Amount:</b> ${transaction.net_amount}</li>`);
            container.append(`<li><b>Info:</b> ${transaction.info}</li>`);
            container.append(`<li><b>Authentication Status:</b> ${transaction.auth_status ? transaction.auth_status.replace('_', ' ') : 'Authentication not initiated by customer'}</li>`);
            container.append(`<li><b>Overall Status:</b> ${transaction.status}</li>`);
            container.append(`<li><b>Browser:</b> ${transaction.browser ?? 'Not available'}</li>`);
            container.append(`<li><b>IP Address:</b> ${transaction.ip_address}</li>`);
            if (transaction.browser_details) {
                container.append(`<li><b>Browser Screen Width:</b> ${JSON.parse(transaction.browser_details).screenWidth}</li>`);
                container.append(`<li><b>Browser Screen Height:</b> ${JSON.parse(transaction.browser_details).screenHeight}</li>`);
            }
            else {
                container.append(`<li><b>Browser Screen Width:</b> Not available</li>`);
                container.append(`<li><b>Browser Screen Height:</b> Not available</li>`);
            }
        };
    </script>
@stop
