@extends('admin.layouts.app')

@section('page-title') Administrators @stop

@section('breadcrumb1') <li class="breadcrumb-item">Administrators</li> @stop

@section('breadcrumb2')<li class="breadcrumb-item">View Administrators</li>@stop

@section('main_content')
    @can('add administrator')
        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between" class="mb-4">
                            <p class="card-title">Administrators</p>
                            <a href="{{route('administrators.view.add')}}"
                               class="btn btn-primary"
                               role="button">Add Administrator</a>
                        </div>
                        <div class="table-responsive">
                            <table id="adminsTable" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Date Added</th>
                                    <th>Permissions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($administrators as $admin)
                                    <tr>
                                        <td>{{$admin->firstname}}</td>
                                        <td>{{$admin->lastname}}</td>
                                        <td>{{$admin->phone}}</td>
                                        <td>{{$admin->email}}</td>
                                        <td>{{\Carbon\Carbon::parse($admin->created_at)->format('d M, Y H:i:s A')}}</td>
                                        <td>
                                            <a href="{{route('permissions.view', $admin->id)}}" role="button" class="btn btn-primary">View</a>
                                            <a href="{{route('administrators.remove', $admin->id)}}" role="button" class="btn btn-danger">Delete</a>
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
            $('#adminsTable').dataTable( {
                dom: 'Blfrtip',
                paging: true,
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
