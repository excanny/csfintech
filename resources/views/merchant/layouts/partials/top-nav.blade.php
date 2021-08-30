<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/dashboard/images/sagecloud.svg') }}" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"> </i></div>
        </div>
        <div class="nav-right col-12 pull-right right-menu">
            <ul class="nav-menus">
                <li class="profile-nav onhover-dropdown p-0">
                    <div class="media profile-media">
{{--                        <img class="b-r-10" src="{{ asset('assets/dashboard/images/dashboard/profile.jpg') }}" alt="">--}}
                        <div class="media-body">
                            <span class="fa fa-angle-down">
                                <span class="font-roboto">
                                    {{ auth()->user()->firstname }}
                                </span>
                            </span>
                            @foreach(auth()->user()->roles as $role)
                                <p class="mb-0 font-roboto"><span class="middle">{{str_replace('_',' ',$role->name)}}</span></p>
                            @endforeach
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <li><a href="{{route('admin.settings.profile')}}"><i data-feather="settings"></i><span>Settings</span></a></li>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i data-feather="log-out"></i>Log out</a></li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
