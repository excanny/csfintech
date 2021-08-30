@extends('merchant.layouts.app')

@section('page-title') Wallet Top Up @stop

@section('breadcrumb1')/&nbsp; Wallet @stop
@section('breadcrumb2')/&nbsp; Top Up @stop

<style>
    .important:after {
        content: ' *';
        color: red;
    }
    label[for=image] {
        cursor: pointer;
    }

    label[for=image]:hover {
        color: orangered;
    }
</style>

@section('main_content')
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-center mb-5">
                        To fund your wallet, kindly make a transfer/deposit to the
                        account details below:
                    </p>
                    <h2 class="card-title">
                        Bank name : {{ $bank_details['bank_name'] }}
                    </h2>
                    <h2 class="card-title">
                        Account name : {{ $bank_details['account_name'] }}
                    </h2>
                    <h2 class="card-title">
                        Account number : {{ $bank_details['account_number'] }}
                    </h2>
                    <form class="forms-sample" method="POST" action="{{route('wallet.top-up.request')}}" enctype="multipart/form-data">
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
                        <div class="form-group">
                            <label for="name" class="important">Depositor's name</label>
                            <input type="text"
                                   class="form-control"
                                   required
                                   id="name"
                                   value="{{$user->firstname}} {{$user->lastname}}"
                                   name="name"
                                   placeholder="name">
                        </div>
                        <div class="form-group">
                            <label for="info">Additional Information</label>
                            <textarea
                                      class="form-control"
                                      id="info"
                                      name="info"
                                      required
                                      placeholder="Additional information"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image" title="Click to Upload Image">
                                Attach Image
                                <div>
                                    <img src="" alt="" id="display_image">
                                </div>
                            </label>
                            <input type="file"
                                   id="image"
                                   name="image"
                                   style="visibility: hidden"
                                   onchange="showUploadFile()"
                                   accept="image/jpeg">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        const showUploadFile = () => {
            // Get html elements
            let new_image = document.getElementById("image").files[0];
            let display_image = document.getElementById("display_image");
            display_image.width = '200';
            display_image.style.border = '2px solid #ff45004d';
            display_image.src = URL.createObjectURL(new_image);
        };
    </script>
@stop
