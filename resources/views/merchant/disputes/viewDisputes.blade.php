@extends('merchant.layouts.app')

@section('page-title') Disputes @stop

@section('breadcrumb1')/&nbsp; Disputes @stop

@section('main_content')
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div>
                        <p class="card-title mb-4 text-center">My Disputes</p>
                    </div>
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Subject</th>
                                <th>status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($disputes as $dispute)
                                <tr>
                                    <td>{{$dispute->reference}}</td>
                                    <td>{{$dispute->subject}}</td>
                                    <td>{!! $dispute->status == 0 ?
                                        '<span class="text-success"><b>OPEN</b></span>':
                                        '<span class="text-danger"><b>CLOSED</b></span>' !!}</td>
                                    <td>{{\Carbon\Carbon::parse($dispute->created_at)->format('d M, Y H:i:s A')}}</td>
                                    <td>
                                        <a href="{{ route('merchant.dispute.messages', $dispute->id) }}" title="View messages"
                                           class="text-success"><i data-feather="eye"></i></a>
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
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
