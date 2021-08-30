@extends('merchant.layouts.app')

@section('page-title') Wallet Top Up @stop

@section('breadcrumb1')/&nbsp; Wallet @stop
@section('breadcrumb2')/&nbsp; Top Up @stop

<style>
    .important:after {
        content: ' *';
        color: red;
    }
</style>

@section('main_content')
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" action="{{ route('wallet.capicollect.topup') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="amount" class="important">How much do you want to top up</label>
                            <input type="number"
                                   class="form-control"
                                   required
                                   id="amount"
                                   name="amount"
                                   placeholder="Amount">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2 topUp">Top up</button>
                    </form>
                    <!--Paystack Topup -->
{{--                    <form class="forms-sample" action="javascript:void(0)">--}}
{{--                        @csrf--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="amount" class="important">How much do you want to top up</label>--}}
{{--                            <input type="number"--}}
{{--                                   class="form-control"--}}
{{--                                   required--}}
{{--                                   id="amount"--}}
{{--                                   name="amount"--}}
{{--                                   placeholder="Amount">--}}
{{--                        </div>--}}
{{--                        <button type="submit" class="btn btn-primary mr-2 topUp">Top up</button>--}}
{{--                    </form>--}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-scripts')
{{--    <script src="https://js.paystack.co/v1/inline.js"></script>--}}
{{--    <script src="{{ asset('assets/dashboard-custom/js/pay.js') }}"></script>--}}
@stop
