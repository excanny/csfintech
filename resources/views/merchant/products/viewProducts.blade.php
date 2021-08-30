@extends('merchant.layouts.app')

@section('page-title') Products @stop

@section('breadcrumb1')/&nbsp; Products @stop

@section('page-styles')
    <style>
        .card .card-header {
            background-color: #eeebef;
            padding: 15px;
        }
        details:focus {
            outline-color: #b5a8b93b;
        }
        details div {
            margin-left: 1.5rem;
        }
        details li {
            text-transform: capitalize;
            list-style: disc;
            margin-left: 20px;
        }
        summary {
            border-radius: 6px;
            padding: 5px;
        }
        summary:focus {
            outline-color: #b5a8b93b;
        }
        details[open] summary ~ * {
            animation: sweep .6s ease-in-out;
        }
        @keyframes sweep {
            0%    {opacity: 0; margin-left: -20px}
            100%  {opacity: 1; margin-left: 1.5rem}
        }
    </style>
@stop

@section('main_content')
    <div class="row">
        <h4 class="card-title text-center col-md-12">
            My Products
        </h4>
        @foreach($userProducts as $product)
            <div class="col-md-4 mb-4 stretch-card">
                <div class="card">
                    <div class="card-header" id="header">
                        <details class="card-title">
                            <summary onclick="changeBg(this)" title="Click to view more">{{ $product['name'] }}</summary>
                            <div>
                                @if(isset($product['billers']))
                                    @if($product['name'] == 'TRANSFER')
                                        @if($product['is_flat'])
                                        Charge: {{ $product['flat_charge'] }}
                                            @else
                                            @foreach($product['billers'] as $key => $commissions)
                                                <ul><b>{{ $key }}</b>
                                                    @foreach($commissions as $k => $value)
                                                        <li>{{ $product['name'] != 'TRANSFER' ? 'Commission' : 'Charge' }}: {{ $value }}</li>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                        @endif
                                        @else
                                        @foreach($product['billers'] as $key => $commissions)
                                            <ul><b>{{ $key }}</b>
                                                @foreach($commissions as $k => $value)
                                                    <li>{{ $product['name'] != 'TRANSFER' ? 'Commission' : 'Charge' }}: {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    @endif
                                @else
                                    <ul>
                                        <li>{{ $product['name'] != 'TRANSFER' ? 'Commission' : 'Charge' }}: {{$product['merchant_commission']}}</li>
                                    </ul>
                                @endif
                            </div>
                        </details>
                    </div>
                    <div class="card-body">
                        <ul>
{{--                            <li><b>Charge:</b> {{$product['charge']}}</li>--}}
                            <li><b>{{ $product['name'] != 'TRANSFER' ? 'Commission Type' : 'Charge Type' }}:</b> {{$product['charge_type']}}</li>
                        </ul>
                        <hr>
                        <div class="text-center mt-4">
                            <p>Total Transactions: {{ $product['transactions_count'] }} ( &#8358;{{ number_format($product['transactions_volume']) }} )</p>
                            @if($product['name'] != 'TRANSFER')
                                <p>Total Commissions: {{ $product['commissions_count'] }} ( &#8358;{{ number_format($product['commissions_volume']) }} )</p>
                            @endif
                            <a href="{{ route('product.tranx', ['product' => $product['name']]) }}" class="btn btn-sm btn-primary">View Transactions</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('page-scripts')
    <script>
        const changeBg = element => {
            if(element.style.background == 'rgba(84, 73, 84, 0.09)')
                element.style.background = '#eeebef';
            else
                element.style.background = '#54495417';
        }
    </script>
@stop

