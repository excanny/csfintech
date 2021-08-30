<header class="main-nav">
    <div class="logo-wrapper"><a href="{{ url('/') }}">
            <img class="img-fluid for-light" src="{{ asset('images/sage_cloud.png') }}" width="180" alt="">
{{--            <b>{{ env('APP_NAME') }}</b>--}}
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"> </i></div>
    </div>
    <div class="logo-icon-wrapper"><a href="{{ url('/') }}"><img class="img-fluid" src="{{ asset('images/sage_cloud.png') }}" alt=""></a></div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn"><a href="{{route('admin.index')}}"><img class="img-fluid" src="{{ asset('assets/dashboard/images/logo/logo-icon.png') }}" alt=""></a>
                        <div class="mobile-back text-right"><span>Back</span></div>
                    </li>
                    <li><a class="nav-link" href="{{route('admin.index')}}"><i data-feather="home"></i><span>Dashboard</span>
                        </a>
                    </li>
                    @if(auth()->user()->can('add administrators') || auth()->user()->can('verify') || auth()->user()->can('initiate') || auth()->user()->can('authorise'))
                    @can('add administrators')
                        <li><a class="nav-link" href="{{route('administrators.view')}}"><i data-feather="users"></i><span>Administrators</span></a>
                        </li>
                    @endcan
                    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="users"></i><span>Merchants</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('merchants.view')}}">View Merchants</a></li>
                            <li><a href="{{route('merchants.view.add')}}">Add Merchants</a></li>
                            @can('verify')
                                <li><a href="{{route('business.verify.view')}}">Verify Business</a></li>
                            @endcan
                            @can('authorise')
                                <li><a href="{{route('business.authorise.view')}}">Authorise Business</a></li>
                            @endcan
                        </ul>
                    </li>
                    <li><a class="nav-link" href="{{route('transactions.view')}}"><i data-feather="credit-card"></i><span>Transactions</span></a>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="link"></i><span>Payment Gateway</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('sage_pay.transactions.view')}}">Transactions</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link" href="{{route('admin.disputes')}}">
                            <i data-feather="tag"></i>
                            <span>Disputes</span>
                        </a>
                    </li>
                    @can('authorise')
                        <li><a class="nav-link" href="{{route('view.requests')}}">
                                <i data-feather="airplay"></i>
                                <span>Top Up Requests</span>
                            </a>
                        </li>
                    @endcan

                    <li>
                        <a class="nav-link" href="{{route('admin.settings.profile')}}">
                            <i data-feather="settings"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li><a class="nav-link" href="{{ route('admin.settings.logs') }}">
                            <i data-feather="align-justify"></i>
                            <span>Logs</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
