@extends('merchant.layouts.app')

@section('page-title') My Team @stop

@section('breadcrumb1')/&nbsp; My Team @stop

@section('main_content')
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                   <div style="display: flex; justify-content: space-between" class="mb-4">
                       <p class="card-title">Members</p>
                       <a href="{{route('team.view.add')}}"
                          class="btn btn-primary"
                          role="button">Add User</a>
                   </div>
                    <div class="table-responsive">
                        <table id="teamTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Date Added</th>
                                <th>Role</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teamMembers as $member)
                                <tr>
                                    <td>{{$member->firstname}} @if($member->hasRole('MERCHANT'))(Owner)@endif</td>
                                    <td>{{$member->lastname}}</td>
                                    <td>{{$member->phone}}</td>
                                    <td>{{$member->email}}</td>
                                    <td>{{\Carbon\Carbon::parse($member->created_at)->format('d M, Y H:i:s A')}}</td>
                                    <td>
                                        @if(!$member->hasRole('MERCHANT'))
                                            <select onchange="window.location='/merchant/team/user/role/{{base64_encode($member->id)}}/'+this.value" class="form-control">
                                                <option value="" hidden>Select...</option>
                                                <option value="{{ base64_encode("MERCHANT_ADMIN") }}" {{$member->hasRole('MERCHANT_ADMIN') ? 'selected' : ''}}>Administrator</option>
                                                <option value="{{ base64_encode("USER") }}" {{!$member->hasRole('MERCHANT_ADMIN') ? 'selected' : ''}}>Viewer</option>
                                            </select>
                                        @endif
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
            $('#teamTable').dataTable( {
                dom: 'Blfrtip',
                paging: true,
                buttons: [
                    'csv', 'excel', 'print'
                ]
            } );
        } );
    </script>
@stop
