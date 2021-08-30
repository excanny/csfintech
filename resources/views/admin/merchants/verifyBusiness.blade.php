@extends('admin.layouts.app')

@section('page-title') Initiated Businesses @stop

@section('breadcrumb1')<li class="breadcrumb-item">Verify Business </li>@stop

@section('main_content')
    @can('verify')
        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title mb-3">Initiated Businesses</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="businessesTable" class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Owner</th>
                                    <th>Info</th>
                                    <th>Team Size</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($businesses as $business)
                                    <tr>
                                        <td>{{$business->name}}</td>
                                        <td>{{$business->owner ? $business->owner->firstname : ''}}
                                            {{$business->owner ? $business->owner->lastname : ''}}</td>
                                        <td>{{$business->info}}</td>
                                        <td>{{count($business->users)}}</td>
                                        <td>{{$business->email}}</td>
                                        <td>{{$business->phone}}</td>
                                        <td>{{\Carbon\Carbon::parse($business->created_at)->format('d M, Y H:i:s A')}}</td>
                                        <td style="display: block">
                                            <div>
                                                <a href="{{route('business.view', $business->id)}}"
                                                   title="View Business"
                                                   class="mr-3">
                                                    <i data-feather="eye" class="text-success"></i>
                                                </a>
                                                <a href="{{route('business.verify', $business->id)}}"
                                                   title="Verify Business">
                                                    <i data-feather="check" class="icon-sm text-behance"></i>
                                                </a>
                                            </div>
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
    @else
        <div class="row mt-5">
            <div class="col-md-12">
                <h4 class="text-center text-gray">You do not have permissions to view this page</h4>
            </div>
        </div>
    @endcan
@stop

@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('#businessesTable').dataTable( {
                dom: 'Blfrtip',
                paging: true,
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
