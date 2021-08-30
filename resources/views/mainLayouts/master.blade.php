@include('mainLayouts.partials.header')

<body>
<div class="container-scroller">
    <div class="logo-wrapper ml-2 mt-2"><a href="{{ url('/') }}">
            <img class="img-fluid for-light" src="{{ asset('images/sage_cloud.png') }}" width="190">
{{--            <b>Sagecloud</b>--}}
        </a>
    </div>
    <div style="position: absolute;z-index: 3;left:25%;right:25%">@include('partials.message')</div>
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
            <div class="row flex-grow">
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    @yield('content')
                </div>
                <div class="col-lg-6 login-half-bg d-flex flex-row">
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

@include('mainLayouts.partials.footer')
</body>
