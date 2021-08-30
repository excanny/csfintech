@extends('admin.layouts.app')

@section('page-title') Authorised Merchants @stop

@section('breadcrumb1')<li class="breadcrumb-item">Merchants</li> @stop
@section('breadcrumb2')<li class="breadcrumb-item">View Authorised Merchants </li>@stop

@section('page-styles')
    <style>
        svg {
            vertical-align: middle;
        }
        .my-hover:hover {
            background: linear-gradient(to right, rgba(255, 102, 1, 0.14), rgba(0, 70, 253, 0.16)) !important;
            cursor: pointer;
        }
    </style>
@stop

@section('main_content')
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between" class="mb-4">
                        <p class="card-title">Authorised Merchants</p>
                        @can('initiate')
                            <a href="{{route('merchants.view.add')}}"
                               class="btn btn-primary"
                               role="button">Add Merchant</a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table id="merchantsTable" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Business Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                                <th>Team Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($authorizedMerchants as $merchant)
                                <tr class="my-hover"
                                    title="Click to View {{$merchant->business->name}}"
                                    onclick="window.location= '{{route('business.view', $merchant->business->id)}}'">
                                    <td>{{$merchant->firstname}}</td>
                                    <td>{{$merchant->lastname}}</td>
                                    <td>{{$merchant->business->name}}</td>
                                    <td>{{$merchant->phone}}</td>
                                    <td>{{$merchant->email}}</td>
                                    <td>{{\Carbon\Carbon::parse($merchant->created_at)->format('d M, Y H:i:s A')}}</td>
                                    <td>
                                        <a href="{{route('business.view', $merchant->business->id)}}"
                                           title="View {{$merchant->business->name}}"
                                           class="mr-3">
                                            <i data-feather="eye" class="text-success"></i>
                                        </a>
                                        <a href="{{route('business.deactivate', $merchant->business->id)}}"
                                           title="Deactivate {{$merchant->business->name}}"
                                           class="mr-3">
                                            <i data-feather="slash" class="text-danger"></i>
                                        </a>
                                    </td>
                                    <td>{{count($merchant->business->users)}}</td>
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
            $('#merchantsTable').dataTable( {
                dom: 'Blfrtip',
                paging: true,
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
