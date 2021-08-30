<header class="main-nav">
    <div class="logo-wrapper"><a href="{{ url('/') }}">
            <img class="img-fluid for-light" src="{{ asset('images/sage_cloud.png') }}" width="180"  alt="">
{{--            <b>{{ env('APP_NAME') }}</b>--}}
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"> </i></div>
    </div>
    <div class="logo-icon-wrapper"><a href="{{ url('/') }}"><img class="img-fluid" src="{{ asset('images/sage_cloud.png') }}" width="180" alt=""></a></div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn"><a href="{{route('merchant.index')}}"><img class="img-fluid" src="{{ asset('assets/dashboard/images/logo/logo-icon.png') }}" alt=""></a>
                        <div class="mobile-back text-right"><span>Back</span></div>
                    </li>
                    <li><a class="nav-link" href="{{route('merchant.index')}}"><i data-feather="home"></i><span>Dashboard</span>
                        </a>
                    </li>
                    @role('MERCHANT|MERCHANT_ADMIN')
                        <li><a class="nav-link" href="{{route('team.view')}}"><i data-feather="users"></i><span>My Team</span>
                            </a>
                        </li>
                    @endrole
                    <li><a class="nav-link" href="{{route('merchant.transactions')}}"><i data-feather="credit-card"></i><span>Transactions</span></a>
                    </li>
                    @if(auth()->user()->business->getProduct('PAYMENT GATEWAY')['status'])
                        <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="link"></i><span>Payment Gateway</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="{{route('sagepay.merchant.transactions')}}">Transactions</a></li>
                                <li><a href="{{route('sagepay.merchant.settings')}}">Settings</a></li>
                            </ul>
                        </li>
                    @endif
                    <li><a class="nav-link" href="{{route('wallet.view')}}">
                            <i data-feather="gift"></i>
                            <span>Wallet</span>
                        </a>
                    </li>
                    <li><a class="nav-link" href="{{route('products.view')}}">
                            <i data-feather="airplay"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li><a class="nav-link" href="{{route('merchant.disputes')}}">
                            <i data-feather="tag"></i>
                            <span>Disputes</span>
                        </a>
                    </li>
                    <li><a class="nav-link" href="{{route('settings.profile')}}">
                            <i data-feather="settings"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    @impersonating($guard = null)
                        <li><a class="nav-link bg-color4" href="{{route('impersonate.leave')}}">
                                <i data-feather="log-out" style="color: #fff"></i>
                                <span style="color: #fff">Leave Merchant</span>
                            </a>
                        </li>
                    @endImpersonating

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
