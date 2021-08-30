@include('admin.layouts.partials.header')

<body>
    <div class="container-scroller">
        <!-- tap on top starts-->
        <div class="tap-top"><i data-feather="chevrons-up"></i></div>
        <!-- tap on tap ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper compact-wrapper" id="pageWrapper">
            <!-- Page Header Start-->
            @include('admin.layouts.partials.top-nav')
            <!-- Page Header Ends                              -->
            <!-- Page Body Start-->
            <div class="page-body-wrapper sidebar-icon">
                <!-- Page Sidebar Start-->
                @include('admin.layouts.partials.side-nav')
                <!-- Page Sidebar Ends-->
                <div class="page-body">
                    <div class="container-fluid">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3>@yield('page-heading')</h3>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{route('admin.index')}}"><i data-feather="home"></i></a></li>
                                        @yield('breadcrumb1')
                                        @yield('breadcrumb2')
                                        @yield('breadcrumb3')
                                    </ol>
                                </div>
                                <div class="col-3"></div>
                                <div class="col-3">
                                    @yield('toggle-dashboard')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Container-fluid starts-->
                    <div class="container-fluid">
                        @include('partials.message')
                        @yield('main_content')
                    </div>
                    <!-- Container-fluid Ends-->
                </div>
                <!-- footer start-->
                @include('admin.layouts.partials.bottom-nav')
            </div>
        </div>
    </div>

@include('admin.layouts.partials.footer')
</body>
