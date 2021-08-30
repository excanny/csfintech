@if($transaction->auth_status == \App\Model\SagePayTransaction::$IN_PROGRESS)
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="{{$url}}{{$merchantId}}/session.js"></script>
            <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
            <style>body{margin: 0;}iframe{border-width: 0}</style>
            <link rel="stylesheet" href="{{ asset('sage_pay/style/main.css') }}">
            <title>SagePay</title>
        </head>
        <div class="container" id="loader" style="position: absolute; z-index: -1000;">
            <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_o0pfz3qh.json"
                           background="transparent"
                           speed="1"
                           style="width: 300px; height: 300px;position: absolute;
    z-index: -1000;"  loop  autoplay>
            </lottie-player>
        </div>
        {!! $transaction->auth_html !!}
    </html>
    @else
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="{{$url}}{{$merchantId}}/session.js"></script>
        <script src="https://test-gateway.mastercard.com/static/threeDS/1.3.0/three-ds.min.js"></script>
        <style id="antiClickjack">body{display:none !important;}</style>
        <link rel="stylesheet" href="{{ asset('sage_pay/style/main.css') }}">
        <title>CapiFlex</title>
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
                        <h2 class="title">Please select a payment method</h2>

                        <div class="selection">
                            <div class="selection__option card" id="card" data-list="card-section">
                                <a href="#">
                                    <img src="{{ asset('sage_pay/img/card-color-ic.svg') }}" alt="" class="selection__option-img">
                                    <p class="selection__option-p">
                                        Card
                                    </p>
                                </a>
                            </div>
                            <div class="selection__option ussd" id="ussd" data-list="ussd-section">
                                <a href="#">
                                    <img src="{{ asset('sage_pay/img/ussd-color-ic.svg') }}" alt="" class="selection__option-img">
                                    <p class="selection__option-p">
                                        USSD
                                    </p>
                                </a>
                            </div>
                            <div id="qrcode-section" class="selection__option qr-code" data-list="qrcode-section">
                                <a href="#">
                                    <img src="{{ asset('sage_pay/img/qrcode-color-ic.svg') }}" alt="" class="selection__option-img">
                                    <p class="selection__option-p">
                                        QR Code
                                    </p>
                                </a>
                            </div>
                            <div id="transfer-section" class="selection__option bank-trans" data-list="transfer-section">
                                <a href="#">
                                    <img src="{{ asset('sage_pay/img/banktrans-ic.svg') }}" alt="" class="selection__option-img">
                                    <p class="selection__option-p">
                                        Bank Transfer
                                    </p>
                                </a>
                            </div>
                            <div id="bankpay-section" class="selection__option bank-pay" data-list="bankpay-section">
                                <a href="#">
                                    <img src="{{ asset('sage_pay/img/bankpay-color-ic.svg') }}" alt="" class="selection__option-img">
                                    <p class="selection__option-p">
                                        Pay from Bank
                                    </p>
                                </a>
                            </div>
                        </div>

{{--                        <a href="return" class="central">--}}
{{--                            <span class="return_span">--}}
{{--                                <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                <p>Return to merchant</p>--}}
{{--                            </span>--}}
{{--                        </a>--}}
                    </div>

                    <div id="card-section" class="section card-section">
                        <form action="javascript:void(0)" class="card-form">
                            <div class="form-group">
                                <label for="card-number" class="form-label" >Card Number</label>
                                <input type="text"
                                       id="card-number"
                                       class="input_field card-number"
                                       placeholder="0000 0000 0000 0000"
                                       aria-label="enter your card number"
                                       readonly>
                                <small style="font-size: 0.5rem;color: red;height: 11px" id="card-error"></small>
                            </div>
                            <div class="inputs">
                                <div style="display: flex; flex: 2;justify-content: space-between">
                                    <div class="form-group" style="flex:1">
                                        <label for="expiry-month" class="form-label">Exp Date</label>
                                        <input type="text"
                                               id="expiry-month"
                                               placeholder="MM"
                                               aria-label="two digit expiry month"
                                               class="input_field left" readonly>
                                    </div>
                                    <div class="form-group"  style="flex:2">
                                        <label for="expiry-month" class="form-label">&nbsp;</label>
                                        <input type="text"
                                               min="2016-01"
                                               id="expiry-year"
                                               placeholder="YYYY"
                                               aria-label="two digit expiry month"
                                               class="input_field left" readonly>
                                    </div>
                                </div>
                                <div class="form-group" style="flex: 1">
                                    <label for="security-code" class="form-label">CVV2 <span>(what is CVV?)</span></label>
                                    <input type="text" id="security-code" class="input_field right" readonly>
                                </div>
                            </div>
                            <div style="display: flex">
                                <div style="flex: 2;display: grid">
                                    <small style="font-size: 0.5rem;color: red;height: 11px" id="month-error"></small>
                                    <small style="font-size: 0.5rem;color: red;height: 11px" id="year-error"></small>
                                </div>
                                <div style="flex:1">
                                    <small style="font-size: 0.5rem;color: red;height: 11px" id="cvv-error"></small>
                                </div>
                            </div>
                            <div id="button_loader" style="display: none">
                                <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_o0pfz3qh.json"
                                               background="transparent"
                                               speed="1"
                                               style="width: 160px;
                                               height: 111px;
                                               position: absolute;
                                               left: 17rem;"
                                               loop  autoplay>
                                </lottie-player>
                            </div>
                            <div class="senders">
                                <div style="flex: 1"></div>
{{--                                <a href="#">--}}
{{--                                    <span class="return_span">--}}
{{--                                        <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                        <p>Return to merchant</p>--}}
{{--                                    </span>--}}
{{--                                </a>--}}
                                <div class="make_payment">
                                    <button onclick="pay()" value="" class="input_field pay">Make Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="qrcode-section" class="section qrcode-section">
                        <div class="content">
                            <img src="{{ asset('sage_pay/img/under_const.svg') }}" alt="" class="content__img">
                            <p class="content__text">coming soon, check back later...</p>

{{--                            <a href="return" class="central">--}}
{{--                                <span class="return_span">--}}
{{--                                    <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                    <p>Return to merchant</p>--}}
{{--                                </span>--}}
{{--                            </a>--}}
                        </div>
                    </div>

                    <div id="ussd-section" class="section ussd-section">
                        <div class="content">
                            <img src="{{ asset('sage_pay/img/under_const.svg') }}" alt="" class="content__img">
                            <p class="content__text">coming soon, check back later...</p>

{{--                            <a href="return" class="central">--}}
{{--                                <span class="return_span">--}}
{{--                                    <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                    <p>Return to merchant</p>--}}
{{--                                </span>--}}
{{--                            </a>--}}
                        </div>
                    </div>

                    <div id="bankpay-section" class="section bankpay-section">
                        <div class="content">
                            <img src="{{ asset('sage_pay/img/under_const.svg') }}" alt="" class="content__img">
                            <p class="content__text">coming soon, check back later...</p>

{{--                            <a href="return" class="central">--}}
{{--                                <span class="return_span">--}}
{{--                                    <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                    <p>Return to merchant</p>--}}
{{--                                </span>--}}
{{--                            </a>--}}
                        </div>
                    </div>

                    <div id="transfer-section" class="section transfer-section">
                        <div class="content">
                            <img src="{{ asset('sage_pay/img/under_const.svg') }}" alt="" class="content__img">
                            <p class="content__text">coming soon, check back later...</p>

{{--                            <a href="return" class="central">--}}
{{--                                <span class="return_span">--}}
{{--                                    <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                    <p>Return to merchant</p>--}}
{{--                                </span>--}}
{{--                            </a>--}}
                        </div>
                    </div>

                    <div id="trans-successful" class="section trans-successful">
                        <div class="content">
                            <div class="lottie_container">
                                <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_egrg9795.json"  background="transparent"  speed="1"  style="width: 100%; height: 100%"    autoplay></lottie-player>
                            </div>
                            <p class="content__text">Transaction Successful</p>

{{--                            <a href="return" class="central">--}}
{{--                                <span class="return_span">--}}
{{--                                    <img src="{{ asset('sage_pay/img/arrow-left.svg') }}" alt="">--}}
{{--                                    <p>Return to merchant</p>--}}
{{--                                </span>--}}
{{--                            </a>--}}
                        </div>
                    </div>


                    <!-- tabs -->
                    <div class="tabs" id="tabs-container">
                        <ul class="tabs__icons">
                            <li id="card-section-tab" class="tabs__icons-icon" data-tab="card-section">
                                <svg width="50" height="35" viewBox="0 0 50 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="card-ic">
                                        <path id="Vector" d="M44.5312 0.444946H5.46875C2.45332 0.444946 0 2.84211 0 5.78852V28.6896C0 31.636 2.45332 34.0331 5.46875 34.0331H44.5312C47.5467 34.0331 50 31.636 50 28.6896V5.78852C50 2.84211 47.5467 0.444946 44.5312 0.444946ZM5.46875 3.49842H44.5312C45.8235 3.49842 46.875 4.52581 46.875 5.78852V8.84199H3.125V5.78852C3.125 4.52581 4.17646 3.49842 5.46875 3.49842ZM44.5312 30.9797H5.46875C4.17646 30.9797 3.125 29.9523 3.125 28.6896V11.8955H46.875V28.6896C46.875 29.9523 45.8235 30.9797 44.5312 30.9797Z" fill="#7690C3"/>
                                        <path id="Vector_2" d="M10.9375 26.3995H9.375C8.51211 26.3995 7.8125 25.7159 7.8125 24.8727V23.346C7.8125 22.5028 8.51211 21.8192 9.375 21.8192H10.9375C11.8004 21.8192 12.5 22.5028 12.5 23.346V24.8727C12.5 25.7159 11.8004 26.3995 10.9375 26.3995Z" fill="#7690C3"/>
                                    </g>
                                </svg>
                                <p>Card</p>
                            </li>
                            <li id="ussd-section-tab" class="tabs__icons-icon" data-tab="ussd-section">
                                <svg width="37" height="46" viewBox="0 0 37 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="ussd-ic">
                                        <path id="Vector" d="M33.3541 21.5015L26.7723 15.0703V3.99421C26.7723 1.79182 24.9386 3.05176e-05 22.6846 3.05176e-05L4.88015 3.05176e-05C2.62617 3.05176e-05 0.792404 1.79182 0.792404 3.99421L0.792404 41.4508C0.792404 43.6532 2.62617 45.445 4.88015 45.445H32.6768C33.326 45.445 33.8849 44.9976 34.0127 44.3758L36.5519 32.0251C37.3451 28.1671 36.1497 24.2331 33.3541 21.5015V21.5015ZM4.88015 2.66282L22.6846 2.66282C23.4359 2.66282 24.0472 3.26008 24.0472 3.99421V21.1673C22.3759 19.5567 19.691 19.5656 18.0311 21.1874C16.3677 22.81 16.3597 25.4441 18.02 27.0774L23.2392 32.3793C22.1077 33.2044 20.642 34.6445 19.5893 37.0128H3.51757L3.51757 3.99421C3.51757 3.26008 4.12873 2.66282 4.88015 2.66282ZM3.51757 41.4508V39.6756H18.7085C18.4924 40.6092 18.3397 41.6414 18.2723 42.7822H4.88015C4.12873 42.7822 3.51757 42.1849 3.51757 41.4508V41.4508ZM33.8802 31.5006L31.5608 42.7822C29.3697 42.7822 23.2535 42.7822 21.0039 42.7822C21.2004 39.9542 22.0334 37.5842 23.4425 35.8736C24.6107 34.4553 25.8002 33.9468 25.8949 33.9079C26.7865 33.5961 27.1052 32.4653 26.3918 31.7406C19.9491 25.1959 19.9689 25.2158 19.9573 25.2046C19.3589 24.6198 19.3494 23.6641 19.9573 23.0711C20.558 22.4842 21.5345 22.4826 22.1372 23.0667C25.0178 25.9457 23.617 24.5642 28.658 29.4897C29.1901 30.0097 30.0529 30.0097 30.585 29.4897C31.1171 28.9698 31.1171 28.1268 30.585 27.6068L26.7723 23.8815V18.8361L31.4272 23.3844C33.5762 25.4842 34.4934 28.5184 33.8802 31.5006V31.5006Z" fill="#7690C3"/>
                                    </g>
                                </svg>
                                <p>USSD</p>
                            </li>
                            <li id="transfer-section-tab" class="tabs__icons-icon" data-tab="transfer-section">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M46.8883 18.6433C48.5042 18.6433 49.8188 17.3287 49.8188 15.7128V11.9184C49.8188 10.7152 49.0982 9.64881 47.9823 9.19976L26.0082 0.214807C25.2927 -0.073458 24.5183 -0.0683785 23.821 0.210704C23.8047 0.217249 24.1887 0.0602705 1.83645 9.19985C0.720613 9.64881 0 10.7153 0 11.9185V15.713C0 17.3289 1.31463 18.6435 2.93051 18.6435H4.68882V40.72H2.93051C1.31463 40.72 0 42.0347 0 43.6506V47.0695C0 48.6854 1.31463 50 2.93051 50H46.8882C48.5041 50 49.8187 48.6854 49.8187 47.0695V43.6506C49.8187 42.0347 48.5041 40.72 46.8882 40.72H45.1299V18.6435H46.8883V18.6433ZM46.8883 43.6504C46.8901 47.1475 46.8981 47.0693 46.8883 47.0693H2.93061V43.6504H46.8883ZM7.61943 40.7199V18.6433H10.9407V40.7199H7.61943ZM13.8712 40.7199V18.6433H20.3183V40.7199H13.8712ZM23.2488 40.7199V18.6433H26.5701V40.7199H23.2488ZM29.5006 40.7199V18.6433H35.9477V40.7199H29.5006ZM38.8782 40.7199V18.6433H42.1995V40.7199H38.8782ZM2.93061 15.7128C2.93061 11.6254 2.92621 11.9202 2.94087 11.9142L24.9094 2.93149L46.878 11.9142C46.8932 11.9202 46.8883 11.6351 46.8883 15.7128C46.4012 15.7128 3.50939 15.7128 2.93061 15.7128Z" fill="#7690C3"/>
                                    <path d="M24.9095 6.33521C24.1003 6.33521 23.4442 6.99125 23.4442 7.80046V10.9263C23.4442 11.7356 24.1003 12.3916 24.9095 12.3916C25.7187 12.3916 26.3747 11.7356 26.3747 10.9263V7.80046C26.3747 6.99125 25.7187 6.33521 24.9095 6.33521V6.33521Z" fill="#7690C3"/>
                                </svg>
                                <p>Transfer</p>
                            </li>
                            <li id="bankpay-section-tab" class="tabs__icons-icon" data-tab="bankpay-section">
                                <svg class="bank" width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="bank-pay-ic">
                                        <g id="Group">
                                            <path id="Vector" d="M47.8413 35.607C47.1838 35.0206 46.3584 34.7503 45.5177 34.8468C44.1059 35.0083 42.2573 35.3896 40.3001 35.7932C37.6572 36.3384 34.9244 36.902 32.8321 37.0327C31.0247 37.1454 27.7333 36.6745 26.7581 36.1637C26.1004 35.8192 25.7324 35.3966 25.5263 35.023H29.5826C31.2561 35.023 32.6175 33.6617 32.6175 31.9883V29.6051C32.6175 27.9318 31.2561 26.5705 29.5826 26.5705H18.4636C17.9911 26.556 12.3181 26.4491 8.3895 29.8002C7.69418 30.3933 7.14117 30.9536 6.70017 31.4629C6.23365 30.8799 5.51677 30.5054 4.71369 30.5054H1.08902C0.48757 30.5054 0 30.9929 0 31.5944V46.9792C0 47.2681 0.114814 47.5451 0.318927 47.7493C0.523248 47.9535 0.800274 48.0683 1.08902 48.0683L4.71359 48.0682C6.11635 48.0682 7.25754 46.927 7.25754 45.5243V44.6873C8.34148 44.7554 10.798 45.0236 15.9968 45.9996C22.1361 47.1524 25.967 47.5431 28.9852 47.543C31.1483 47.543 32.8944 47.3424 34.7727 47.0779C37.0946 46.7511 40.0149 45.925 42.5915 45.1961C43.7842 44.8588 44.9108 44.5401 45.9077 44.2887C48.594 43.6112 48.9952 41.9173 48.9952 40.9677V38.1934C48.9952 37.2185 48.5637 36.2516 47.8413 35.607ZM5.0796 45.5243C5.0796 45.726 4.91542 45.8901 4.71369 45.8901L2.17804 45.8902V32.6835H4.71359C4.91532 32.6835 5.0795 32.8476 5.0795 33.0493V45.5243H5.0796ZM46.8172 40.9678C46.8172 41.1991 46.8172 41.8131 45.3752 42.1768C44.3481 42.4358 43.2069 42.7586 41.9987 43.1004C39.489 43.8103 36.6444 44.6149 34.4691 44.9211C30.1839 45.5245 26.611 45.7765 16.3987 43.8591C11.1466 42.873 8.53978 42.5749 7.25754 42.5066V34.4208C7.29643 34.3721 7.33242 34.3204 7.36343 34.2638C7.64243 33.7548 8.33038 32.7134 9.80294 31.4574C13.1605 28.5935 18.3512 28.746 18.4024 28.7478C18.4165 28.7483 18.4306 28.7486 18.4447 28.7486H29.5828C30.0552 28.7486 30.4396 29.1329 30.4396 29.6052V31.9884C30.4396 32.4608 30.0552 32.8451 29.5828 32.8451H18.4447C17.8432 32.8451 17.3557 33.3327 17.3557 33.9341C17.3557 34.5356 17.8432 35.0231 18.4447 35.0231H23.2132C23.4372 35.9161 24.055 37.2066 25.7475 38.0931C27.2955 38.9041 31.135 39.3218 32.9678 39.2066C35.2137 39.0664 38.0232 38.487 40.7401 37.9265C42.6481 37.5329 44.4503 37.1611 45.7653 37.0108C46.0424 36.9794 46.2585 37.1138 46.3914 37.2324C46.654 37.4667 46.8173 37.8351 46.8173 38.1936V40.9678H46.8172Z" fill="#7690C3"/>
                                            <path id="Vector_2" d="M47.2072 1.9317H8.95718C7.4172 1.9317 6.16431 3.18459 6.16431 4.72457V22.6568C6.16431 24.1969 7.4172 25.4499 8.95718 25.4499H47.2072C48.7472 25.4499 50 24.1969 50 22.6568V4.72447C50 3.18459 48.7472 1.9317 47.2072 1.9317ZM41.8046 23.2717H14.362C14.0735 20.0248 11.549 17.4128 8.34245 16.9883V10.096C11.4505 9.68448 13.9173 7.2177 14.3287 4.10964H41.8379C42.2493 7.21708 44.715 9.68345 47.822 10.0958V16.9885C44.6166 17.4139 42.0931 20.0256 41.8046 23.2717ZM47.822 4.72447V7.89071C45.9179 7.51827 44.4145 6.01418 44.0428 4.10964H47.2072C47.5462 4.10974 47.822 4.38552 47.822 4.72447ZM8.95718 4.10974H12.1237C11.752 6.01501 10.2476 7.51941 8.34235 7.89123V4.72447C8.34245 4.38552 8.61823 4.10974 8.95718 4.10974ZM8.34245 22.6568V19.1932C10.3456 19.5841 11.9053 21.2272 12.1715 23.2717H8.95718C8.61823 23.2717 8.34245 22.9958 8.34245 22.6568ZM47.2072 23.2717H43.9951C44.2612 21.2279 45.8198 19.5851 47.8219 19.1936V22.6568C47.822 22.9958 47.5462 23.2717 47.2072 23.2717Z" fill="#7690C3"/>
                                            <path id="Vector_3" d="M28.1408 5.89978C23.8448 5.89978 20.3497 9.3947 20.3497 13.6906C20.3497 17.9867 23.8448 21.4817 28.1408 21.4817C32.4367 21.4817 35.9317 17.9867 35.9317 13.6906C35.9317 9.39481 32.4367 5.89978 28.1408 5.89978ZM28.1408 19.3037C25.0457 19.3037 22.5278 16.7858 22.5278 13.6907C22.5278 10.5958 25.0457 8.07792 28.1408 8.07792C31.2358 8.07792 33.7537 10.5958 33.7537 13.6907C33.7537 16.7857 31.2358 19.3037 28.1408 19.3037Z" fill="#7690C3"/>
                                            <path id="Vector_4" d="M15.603 10.341C13.7558 10.341 12.2532 11.8436 12.2532 13.6908C12.2532 15.5379 13.7558 17.0405 15.603 17.0405C17.4501 17.0405 18.9527 15.5379 18.9527 13.6908C18.9527 11.8436 17.4501 10.341 15.603 10.341ZM15.603 14.8624C14.9569 14.8624 14.4312 14.3369 14.4312 13.6907C14.4312 13.0446 14.9568 12.5189 15.603 12.5189C16.2491 12.5189 16.7747 13.0446 16.7747 13.6907C16.7747 14.3369 16.2491 14.8624 15.603 14.8624Z" fill="#7690C3"/>
                                            <path id="Vector_5" d="M40.6785 10.341C38.8314 10.341 37.3287 11.8436 37.3287 13.6908C37.3287 15.5379 38.8314 17.0405 40.6785 17.0405C42.5256 17.0405 44.0284 15.5379 44.0284 13.6908C44.0284 11.8436 42.5256 10.341 40.6785 10.341ZM40.6785 14.8624C40.0323 14.8624 39.5068 14.3369 39.5068 13.6907C39.5068 13.0446 40.0323 12.5189 40.6785 12.5189C41.3246 12.5189 41.8503 13.0446 41.8503 13.6907C41.8503 14.3369 41.3247 14.8624 40.6785 14.8624Z" fill="#7690C3"/>
                                        </g>
                                    </g>
                                </svg>
                                <p>With Bank</p>
                            </li>
                        </ul>
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
        {{--<script type="text/javascript" src="https://test-gateway.mastercard.com/form/version/59/merchant/{{$merchantId}}/session.js" defer></script>--}}
        <script src="{{ asset('sage_pay/js/app.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });

            if (self === top) {
                var antiClickjack = document.getElementById("antiClickjack");
                antiClickjack.parentNode.removeChild(antiClickjack);
            } else {
                top.location = self.location;
            }

            PaymentSession.configure({
                session : '{{ $transaction->session_id }}',
                fields: {
                    // ATTACH HOSTED FIELDS TO YOUR PAYMENT PAGE FOR A CREDIT CARD
                    card: {
                        number: "#card-number",
                        securityCode: "#security-code",
                        expiryMonth: "#expiry-month",
                        expiryYear: "#expiry-year",
                        // nameOnCard: "#cardholder-name"
                    }
                },
                //SPECIFY YOUR MITIGATION OPTION HERE
                frameEmbeddingMitigation: ["javascript"],
                callbacks: {
                    initialized: function(response) {
                        console.log(response);
                        // HANDLE INITIALIZATION RESPONSE
                        if (response.status == "system_error" && response.message == "Form Session not found or expired.") {
                            window.location = '/{{ encrypt('expired.'.$transaction->access_code) }}'
                        }
                        setTimeout(() => {
                            $('#loader').hide();
                            $('#payment_container').show();
                        }, 200)
                    },
                    formSessionUpdate: function(response) {
                        console.log(response, 'update');
                        // HANDLE RESPONSE FOR UPDATE SESSION
                        if (response.status) {
                            if ("ok" == response.status) {
                                console.log("Session updated with data: " + response.session.id);

                                //check if the security code was provided by the user
                                if (!response.sourceOfFunds.provided.card.securityCode) {
                                    $('#cvv-error').html("Security code not provided.");
                                    $('#button_loader').hide();
                                    return;
                                }

                                //check if the user entered a Mastercard credit card
                                if (response.sourceOfFunds.provided.card.scheme != 'MASTERCARD') {
                                    $('#card-error').html("Mastercard credit card not entered.");
                                    $('#button_loader').hide();
                                    return;
                                }

                                ThreeDS.configure({
                                    merchantId: "{{ $merchantId }}",
                                    sessionId: "{{ $transaction->session_id }}",
                                    // containerId: "ABC",
                                    callback: function() {
                                        if (ThreeDS.isConfigured())
                                            console.log("Done with configure");
                                    },
                                    configuration: {
                                        userLanguage: "en-US",
                                        wsVersion: 59
                                    }
                                });

                                initAuthPayer(response.device);

                            } else if ("fields_in_error" == response.status)  {

                                console.log("Session update failed with field errors.");
                                if (response.errors.cardNumber) {
                                    $('#card-error').html("Card number invalid or missing.");
                                    $('#button_loader').hide();
                                }
                                if (response.errors.expiryYear) {
                                    $('#year-error').html("Expiry year invalid or missing.");
                                    $('#button_loader').hide();
                                }
                                if (response.errors.expiryMonth) {
                                    $('#month-error').html("Expiry month invalid or missing.");
                                    $('#button_loader').hide();
                                }
                                if (response.errors.securityCode) {
                                    $('#cvv-error').html("Security code invalid.");
                                    $('#button_loader').hide();
                                }
                            } else if ("request_timeout" == response.status)  {
                                $('#card-error').html("Session update failed with request timeout: " + response.errors.message);
                                $('#button_loader').hide();
                            } else if ("system_error" == response.status)  {
                                $('#card-error').html("Session update failed with system error: " + response.errors.message);
                                $('#button_loader').hide();
                            }
                        } else {
                            $('#card-error').html("Session update failed: " + response);
                            $('#button_loader').hide();
                        }
                    }
                },
                interaction: {
                    displayControl: {
                        formatCard: "EMBOSSED",
                        invalidFieldCharacters: "REJECT"
                    }
                }
            });

            function pay() {
                // UPDATE THE SESSION WITH THE INPUT FROM HOSTED FIELDS
                // console.log(PaymentSession.updateSessionFromForm);
                $('#button_loader').show();
                $('#card-error').html("");
                $('#year-error').html("");
                $('#month-error').html("");
                $('#cvv-error').html("");
                PaymentSession.updateSessionFromForm('card');
            }

            function initAuthPayer(device) {
                var date = new Date();
                device.timeZone = date.getTimezoneOffset();
                device.colorDepth = screen.colorDepth;
                device.screenWidth = screen.width;
                device.screenHeight = screen.height;
                let data = {
                    id : "{{ $transaction->session_id }}",
                    device
                };
                $.ajax({
                    method : 'POST',
                    dataType : 'json',
                    url : '{{ route('sagepay.init.auth') }}',
                    data,
                    success : function (resp) {
                        console.log(resp);
                        if (resp.success) {
                            setTimeout(() => {
                                location.reload(true);
                            }, 1000)
                        }
                    },
                    error : function (resp) {
                        console.log('err', resp.responseJSON);
                        $('#card-error').html(resp.responseJSON.message + resp.responseJSON.error ?? '');
                        $('#button_loader').hide();
                        if (resp.responseJSON.abort) {
                            // setTimeout(() => {
                            //     location.reload(true);
                            // }, 3000)
                        }
                    }
                });
            }

            function initAuthPayerOld() {
                var optionalParams = {
                    sourceOfFunds: {
                        type: "CARD"
                    },
                    order: {
                        walletProvider: "MASTERPASS_ONLINE"
                    }
                };

                ThreeDS.initiateAuthentication("{{ $transaction->id }}", "{{ $transaction->reference }}", function (data) {
                    if (data && data.error) {
                        var error = data.error;
                        // Something bad happened, the error value will match what is returned by the Authentication API
                        console.error("error.code : ", error.code);
                        console.error("error.msg : ", error.msg);
                        console.error("error.result : ", error.result);
                        console.error("error.status : ", error.status);
                    }
                    else {
                        console.log("After Initiate 3DS ", data);

                        //data.response will contain information like gatewayRecommendation, authentication version, etc.
                        console.log("REST API raw response ", data.restApiResponse);
                        console.log("Correlation Id", data.correlationId);
                        console.log("Gateway Recommendation", data.gatewayRecommendation);
                        console.log("HTML Redirect Code", data.htmlRedirectCode);
                        console.log("Authentication Version", data.authenticationVersion);

                        switch (data.gatewayRecommendation) {
                            case "PROCEED":
                                authenticatePayer(); //merchant's method
                                break;
                            case "DO_NOT_PROCEED":
                                tryOtherPayment(); //merchant's method, you can offer the payer the option to try another payment method.
                                break;
                        }
                    }
                });
            }
        </script>
    </body>
@endif
</html>
