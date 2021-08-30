@extends('admin.layouts.app')

@section('page-title') Top Up Requests @stop

@section('breadcrumb1')<li class="breadcrumb-item">Top Up Requests</li>  @stop

@section('page-styles')
    <style>
        svg {
            vertical-align: middle;
        }
    </style>
@stop

@section('main_content')
    <div class="row">
        <div class="col-md-12 col-sm-12 grid-margin">
            <div class="card text-center">
                <div class="card-header">
                    <h4 class="card-title">
                        Top Up Requests History
                    </h4>

                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Depositor's Name</th>
                                <th>Status</th>
                                <th>Additional Info</th>
                                <th>Proof of Payment</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{$request->amount}}</td>
                                    <td>{{$request->name}}</td>
                                    <td class="text-{{$request->color}}">
                                        <strong>{{$request->status}}</strong>
                                    </td>
                                    <td>{{$request->info}}</td>
                                    <td><a href="{{ !is_null($request->image_url) ? url('storage/'.$request->image_url) : ''}}"
                                           title="Click to view"
                                           target="_blank">
                                            <img width="50" src="{{ asset('storage/'.$request->image_url) }}" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                           @if($request->status == \App\Model\WalletTopUpRequest::$PENDING)
                                                <a href="{{route('wallet.requests.approve', $request->id)}}"
                                                   title="Approve Request" class="mr-3">
                                                    <i data-feather="check"></i>
                                                </a>
                                               <a href="{{route('wallet.requests.reject', $request->id)}}"
                                                  title="Reject Request">
                                                   <i data-feather="slash" class="text-danger"></i>
                                               </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($request->created_at)->format('d M, Y H:i:s A')}}</td>
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
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
