<div style="background-color: #dcf1fc" class="main-menu menu-fixed menu-accordion menu-shadow menu-dark"
    data-scroll-to-active="true">
    <div class="navbar-header" style="height: unset !important;">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto" style="margin: 0 auto;">
                <a class="navbar-brand" href="#">
                    <span class="brand-logo"><img alt="logo"
                            src="{{ asset('dashboard/app-assets/images/logo/Logo.png') }}"
                            style="max-width: 100% !important;max-height: 100%  !important; margin: 0 auto; display: flex;" />
                    </span>

                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul style="background-color: #214152" class="navigation navigation-main" id="main-menu-navigation"
            data-menu="menu-navigation">
            {{--            <li class="nav-item "> --}}
            {{--                <a class="d-flex align-items-center" href="#"> --}}
            {{--                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" --}}
            {{--                         class="bi bi-boxes" viewBox="0 0 16 16"> --}}
            {{--                        <path --}}
            {{--                            d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z"/> --}}
            {{--                    </svg> --}}
            {{--                    <span class="menu-title text-truncate" --}}
            {{--                          data-i18n="Charts">@lang('main') --}}
            {{--                    </span> --}}
            {{--                </a> --}}
            {{--            </li> --}}
            @can('admin')
                <li class="nav-item has-sub  " style="">
                    <a class="d-flex align-items-center" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-pie-chart">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                        </svg>
                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('admins')</span></a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('managers.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('managers.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('admins')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('roles.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('roles')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('content')
                <li class="nav-item has-sub  " style="">
                    <a class="d-flex align-items-center" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box2-fill" viewBox="0 0 16 16">
                            <path
                                d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4h-8.5ZM15 4.667V5H1v-.333L1.5 4h6V1h1v3h6l.5.667Z" />
                        </svg>
                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('Content Management')</span></a>
                    <ul class="menu-content">

                        <li class="nav-item {{ request()->routeIs('content.getHeroSection') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('content.getHeroSection') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('Section Hero')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('content.getJourneySection') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('content.getJourneySection') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('Section Our Journey')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('content.getServicesSection') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('content.getServicesSection') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('Section Our Service')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('content.getFeaturesSection') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('content.getFeaturesSection') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('Section Our Features')</span>
                            </a>
                        </li>
                    </ul>

                </li>
            @endcan
            @can('users')
                <li class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}  " style="">
                    <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                        <i class="fa fa-user" style="font-size:24px;"></i>
                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('users')</span></a>
                </li>
            @endcan
            @can(Auth::user('admin')->can('category'))
                <li class="nav-item {{ request()->routeIs('categories.index') ? 'active' : '' }} ">
                    <a class="d-flex align-items-center" href="{{ route('categories.index') }}">
                        <i data-feather="file-text"></i><span class="menu-title text-truncate">@lang('categories')</span>
                    </a>
                </li>
            @endcan
            @can('product')
                <li class="nav-item has-sub  " style="">
                    <a class="d-flex align-items-center" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box2-fill" viewBox="0 0 16 16">
                            <path
                                d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4h-8.5ZM15 4.667V5H1v-.333L1.5 4h6V1h1v3h6l.5.667Z" />
                        </svg>
                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('products')</span></a>
                    <ul class="menu-content">

                        <li class="nav-item {{ request()->routeIs('products.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('products.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('products')</span>
                            </a>
                        </li>
                    </ul>

                </li>
            @endcan
            @can('city')
                <li class="nav-item has-sub  " style="">
                    <a class="d-flex align-items-center" href="#">
                        <i class="fa fa-map" style="font-size:24px;"></i>

                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('deliveries')</span></a>
                    <ul class="menu-content">

                        <li class="nav-item {{ request()->routeIs('deliveries.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('deliveries')</span>
                            </a>
                        </li>
                    </ul>

                </li>
            @endcan
            @can('contact-us')
                <li class="nav-item has-sub  " style="">
                    @php $count= \App\Models\Contact::query()->where('view',1)->count() @endphp
                    <a class="d-flex align-items-center" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-messenger" viewBox="0 0 16 16">
                            <path
                                d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.48.48 0 0 1 .578-.002l1.869 1.402a1.2 1.2 0 0 0 1.735-.32l2.35-3.728c.226-.358-.214-.761-.551-.506L9.728 7.381a.48.48 0 0 1-.578.002L7.281 5.98a1.2 1.2 0 0 0-1.735.32z" />
                        </svg>
                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('contact us')
                            <i class="text-danger" id="counthelps">{{ $count == 0 ? '' : $count }} </i>
                        </span>
                    </a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('contacts.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('contacts.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('contact us')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('contacts.texts') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('contacts.texts') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('texts')</span>
                            </a>
                        </li>

                    </ul>

                </li>
            @endcan

        </ul>
    </div>
</div>
