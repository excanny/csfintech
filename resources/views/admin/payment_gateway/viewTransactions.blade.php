@extends('admin.layouts.app')

@section('page-title') Payment Gateway Transactions @stop

@section('breadcrumb1')<li class="breadcrumb-item">Payment Gateway Transactions</li> @stop

@section('page-styles')
    <style>
        .modal-body {
            display: flex;
            justify-content: space-evenly;
        }

        .my-hover:hover {
            background: linear-gradient(to right, rgba(255, 102, 1, 0.14), rgba(0, 70, 253, 0.16)) !important;
            cursor: pointer;
        }

        li{
            margin-bottom: 3px;
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
                        onchange="window.location.href='/admin/pg/transactions/view?'
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
                <form method="post" action="{{route('sage_pay.transactions.filter')}}" class="row">
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
                    <div class="text-center">
                        <p class="card-title text-center">Payment Gateway Transactions</p>
                        <small class="text-center text-info">*Click row to view more details</small>
                    </div>
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Business</th>
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
                                    <td>{{$transaction->business->name}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->charge}}</td>
                                    <td>{{$transaction->net_amount}}</td>
                                    <td>{{$transaction->reference}}</td>
                                    <td>{{$transaction->external_reference}}</td>
                                    <td class="{{$transaction->color}}">
                                        <a href="javascript:void(0)"
                                           data-toggle="{{auth()->user()->hasRole('SUPER_ADMIN') ? 'modal' : ''}}"
                                           data-target="{{auth()->user()->hasRole('SUPER_ADMIN') ? '#transaction_status_modal' : ''}}"
                                           trans_id="{{ $transaction->id }}"
                                           status="{{ $transaction->status }}"
                                           onclick="changeStatus(this)"
                                           title="{{ auth()->user()->hasRole('SUPER_ADMIN') ? 'Click to change status' : '' }}"
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
    @if(auth()->user()->hasRole('SUPER_ADMIN'))
        <div class="modal fade" id="transaction_status_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Change Transaction Status To</h6>
                        <button type="button" style="float: right" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="change-status-container">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
                "iDisplayLength" : 25,
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );

        const changeStatus = ( element ) => {
            let status = element.getAttribute('status');
            let transaction_id = element.getAttribute('trans_id');
            let container = $('#change-status-container');
            container.empty();
            if (status === 'FAILED') {
                container.append(`<a class="text-white btn btn-success" onclick="updateStatus('SUCCESSFUL', ${transaction_id}, '${status}')">SUCCESSFUL</a>`);
                container.append(`<a class="btn btn-warning" onclick="updateStatus('PENDING', ${transaction_id}, '${status}')">PENDING</a>`);
            }
            if (status === 'SUCCESSFUL') {
                container.append(`<a class="text-white btn btn-danger" onclick="updateStatus('FAILED',${transaction_id}, '${status}')">FAILED</a>`);
                container.append(`<a class="btn btn-warning" onclick="updateStatus('PENDING', ${transaction_id}, '${status}')">PENDING</a>`);
            }
            if (status === 'PENDING') {
                container.append(`<a class="text-white btn btn-success" onclick="updateStatus('SUCCESSFUL', ${transaction_id}, '${status}')">SUCCESSFUL</a>`);
                container.append(`<a class="text-white btn btn-danger" onclick="updateStatus('FAILED', ${transaction_id}, '${status}')">FAILED</a>`);
            }
        };

        const viewMoreDetails = ( element ) => {
            let transaction = JSON.parse(element.getAttribute('tranx'));
            let container = $('#more_details_container');
            container.empty();
            container.append(`<li><b>Date:</b> ${transaction.created_at}</li>`);
            container.append(`<li><b>Business:</b> ${transaction.business.name}</li>`);
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

        const updateStatus = ( status, transaction_id, old_status ) => {
            let data = {
                status,
                transaction_id,
                old_status
            };
            $.ajax({
                type : 'POST',
                url : '/admin/pg/transactions/status/update',
                data : data,
                dataType: 'json',
                success : (resp) => {
                    if (resp.success) {
                        $('#modal').modal('toggle');
                        alert(resp.message);
                        location.reload(true);
                    }
                },
                error : (resp) => {
                    if (resp.success === false)
                        alert(resp.message);
                    else
                        alert('Failed to update status')
                }
            });
        };
    </script>
@stop
