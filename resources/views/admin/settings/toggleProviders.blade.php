@extends('admin.layouts.app')

@section('page-title') Edit Business @stop

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
            <a class="nav-link active" href="{{route('admin.settings.providers')}}" role="tab">Toggle Providers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.queries') }}" role="tab">Re-Queries</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{route('admin.switch.providers')}}?type=airtime">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="airtime_providers" class="important">AIRTIME Providers(on: {{ \App\Model\Provider::airtime_current()->name ?? 'Not Available' }})</label>
                            <select type="password"
                                   class="form-control"
                                   id="airtime_providers"
                                   name="airtime_provider"
                                   required>
                                <option value="">Select A Provider</option>
                            @foreach($airtime_providers as $provider)
                                    <option {{$provider->status == \App\Model\Provider::$ACTIVE ? 'selected' : ''}}
                                            value="{{$provider->id}}">
                                        {{$provider->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Switch</button>
                    </form>

                    <br>
                    <br>
                    <br>

                    <form class="forms-sample" method="POST" action="{{route('admin.switch.providers')}}?type=data">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="data_providers" class="important">DATA Providers(on: {{ \App\Model\Provider::data_current()->name ?? 'Not Available' }})</label>
                            <select type="password"
                                    class="form-control"
                                    id="data_providers"
                                    name="data_provider"
                                    required>
                                <option value="">Select A Provider</option>
                            @foreach($data_providers as $provider)
                                    <option {{$provider->status == \App\Model\Provider::$ACTIVE ? 'selected' : ''}}
                                            value="{{$provider->id}}">
                                        {{$provider->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Switch</button>
                    </form>

                    <br>
                    <br>
                    <br>

                    <form class="forms-sample" method="POST" action="{{route('admin.switch.providers')}}?type=cabletv">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="cableTv_providers" class="important">CABLETV Providers(on: {{ \App\Model\Provider::cableTv_current()->name ?? 'Not Available' }})</label>
                            <select type="password"
                                    class="form-control"
                                    id="cableTv_providers"
                                    name="cableTv_provider"
                                    required>
                                <option value="">Select A Provider</option>
                            @foreach($cableTv_providers as $provider)
{{--                                    <option {{$provider->status == \App\Model\Provider::$ACTIVE ? 'selected' : ''}}--}}
{{--                                            value="{{$provider->id}}"--}}
{{--                                        {{$provider->name ==  'SHAGO' ? 'disabled' : ''}}--}}
{{--                                    >--}}
{{--                                        {{$provider->name}}--}}
{{--                                    </option> --}}
                                    <option {{$provider->status == \App\Model\Provider::$ACTIVE ? 'selected' : ''}}
                                            value="{{$provider->id}}"

                                    >
                                        {{$provider->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Switch</button>
                    </form>

                    <br>
                    <br>
                    <br>

                    <form class="forms-sample" method="POST" action="{{route('admin.switch.providers')}}?type=electricity">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="electricity_providers" class="important">ELECTRICITY Providers(on: {{ \App\Model\Provider::electricity_current()->name ?? 'Not Available' }})</label>
                            <select type="password"
                                    class="form-control"
                                    id="electricity_providers"
                                    name="electricity_provider"
                                    required>
                                <option value="">Select A Provider</option>
                            @foreach($electricity_providers as $provider)
                                    <option {{$provider->status == \App\Model\Provider::$ACTIVE ? 'selected' : ''}}
                                            value="{{$provider->id}}"
                                    >
                                        {{$provider->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Switch</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
@stop

{{--@section('page-scripts')--}}
{{--   --}}
{{--@stop--}}
