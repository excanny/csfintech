<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('sage_pay/style/main.css') }}">
        <title>SagePay</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container" id="loader">
            <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_o0pfz3qh.json"
                           background="transparent"
                           speed="1"
                           style="width: 300px; height: 300px;"  loop  autoplay>
            </lottie-player>
        </div>
        <div class="container" id="payment_container" style="display: none;">
            <div class="sp__logo">
                <img src="{{ asset('sage_pay/img/capitalsage.svg') }}" alt="sagepay" class="sp__logo-img">
            </div>

            <div class="gateway__conatiner">
                <!-- header -->
                <div class="header">
                    <div class="pane">
                        <div class="pane__left">
                            <p class="bold">{{ $transaction->customer_email }}</p>
                            <p class="small">{{ $transaction->customer_phone ?? '' }}</p>
                        </div>
                        <div class="pane__right">
                            <p class="small">Amount</p>
                            <p class="bold amount">₦ {{ number_format($transaction->amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="gateway__conatiner-main">
                    <div id="paymet-selection" class="payment-selection">
                        <div id="trans-successful" class="section trans-successful">
                            <div class="content">
                                <div class="lottie_container">
                                    <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_egrg9795.json"  background="transparent"  speed="1"  style="width: 100%; height: 100%"    autoplay></lottie-player>
                                </div>
                                <p class="content__text">Transaction Successful</p>
                            </div>
                        </div>

{{--                        <a href="return" class="central">--}}
{{--                            <span class="return_span">--}}
{{--                                <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                <p>Return to merchant</p>--}}
{{--                            </span>--}}
{{--                        </a>--}}
                    </div>

                </div>
            </div>

            <div class="powered_by">
                <img src="{{ asset('sage_pay/img/padlock.svg') }}" alt="padlock">
                <h3>Secure Payment By</h3>
                <img src="{{ asset('sage_pay/img/capiflex.jpg') }}" width="60" alt="sagecloud" class="sc_logo">
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <script src="{{ asset('sage_pay/js/app.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                showSuccess();
                setTimeout(() => {
                    $('#loader').hide();
                    $('#payment_container').show();
                }, 50);

                setTimeout(() => {
                   window.location = '{{ $transaction->callback_url }}';
                }, 3000);
            });
        </script>
    </body>
</html>
