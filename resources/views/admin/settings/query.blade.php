@extends('admin.layouts.app')

@section('page-title') Re-QUeries @stop

@section('breadcrumb1') <li class="breadcrumb-item">Settings</li> @stop
@section('breadcrumb2') <li class="breadcrumb-item">Change Password</li> @stop

@section('page-styles')
    <style>
        .important:after {
            content: ' *';
            color: red;
        }
        .field-icon {
            float: right;
            margin-right: 8px;
            margin-top: -26px;
            position: relative;
            cursor: pointer;
            z-index: 2;
            color: #555;
        }
    </style>
@stop

@section('main_content')
    <ul class="nav nav-tabs px-4" role="tablist" style="margin-bottom: 2rem;">
        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.settings.profile')}}" role="tab">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.settings.password')}}" role="tab">Reset Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.settings.providers')}}" role="tab">Toggle Providers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.settings.queries') }}" role="tab">Re-Queries</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    @forelse($queries as $query)
                        <br>
                        {{ $query->transaction->reference }} - <strong style="color: {{$query->transaction->color}}">{{$query->transaction->status}}</strong> - {{ $query->created_at }}
                        <br>
                        <hr>
                        @empty
                        No Pending Query
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop

{{--@section('page-scripts')--}}
{{--   --}}
{{--@stop--}}
