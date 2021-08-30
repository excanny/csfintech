@extends('admin.layouts.app')

@section('page-title') Permissions @stop

@section('breadcrumb1')/&nbsp; Administrators @stop

@section('breadcrumb2')/&nbsp; View Permissions @stop

@section('main_content')
    @can('add administrator')
        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body text-center">
                        @if(count($userPermissions) > 0)
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="card-title">{{$user->firstname}} {{$user->lastname}} has the permission
                                        to do the following:</p>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <td>Permission</td>
                                            <td>Action</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($userPermissions as $permission)
                                            <tr>
                                                <td>{{$permission->name}}</td>
                                                <td><a class="btn btn-warning"
                                                       title="Strip permission off {{$user->firstname}}"
                                                       href="{{route('permissions.strip',[$user->id, 'permission_id'=>$permission->id])}}">
                                                        Strip</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-description">
                                        Add a permission below
                                    </p>
                                    <form style="width: max-content;margin: auto"
                                          class="form-inline"
                                          method="POST" action="{{route('permissions.add', $user->id)}}">
                                        @csrf
                                        <div class="form-group mr-3">
                                            <label class="text-gray mr-3" for="permissions">{{$user->firstname}} can</label>
                                            <select class="form-control"
                                                    id="permissions"
                                                    name="name" required>
                                                <option value="" disabled selected hidden>Select...</option>
                                                @foreach($permissions as $item)
                                                    <option value="{{$item->name}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">Assign</button>
                                        </div>
                                    </form>
                                </div>

                                {{--                            <a href="{{route('administrators.view.add')}}"--}}
                                {{--                               class="btn btn-primary"--}}
                                {{--                               role="button">Add Administrator</a>--}}

                            </div>
                        @else
                            <div class="text-center">
                                <p class="card-title">{{$user->firstname}} {{$user->lastname}} has no permissions</p>
                                <p class="card-description">
                                    Add a permission below
                                </p>
                                <form style="width: max-content;margin: auto"
                                      class="form-inline"
                                      method="POST" action="{{route('permissions.add', $user->id)}}">
                                    @csrf
                                    <div class="form-group mr-3">
                                        <label class="text-gray mr-3" for="permissions">{{$user->firstname}} can</label>
                                        <select class="form-control"
                                                id="permissions"
                                                name="name" required>
                                            <option value="" disabled selected hidden>Select...</option>
                                            @foreach($permissions as $item)
                                                <option value="{{$item->name}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Assign</button>
                                    </div>
                                </form>
                            </div>
                        @endif
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
