@extends('merchant.layouts.app')

@section('page-title') Payment Gateway settings @stop

@section('breadcrumb1')/&nbsp; Settings @stop

@section('main_content')
    <div class="row">
        <div class="col-md-9 col-sm-9 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{route('sagepay.merchant.settings.update')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="callback_url">Callback URL</label>
                            <input type="text"
                                   value="{{ $hasSettings ?
                                        auth()->user()->business->sage_pay_settings->callback_url : ''}}"
                                   class="form-control"
                                   id="callback_url"
                                   name="callback_url">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
