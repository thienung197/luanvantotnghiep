<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/ware-master-high-resolution-logo.png" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WareMaster')</title>

    {{-- link icon fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- link css  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('css')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar start  --}}
            <aside id="aside">
                <div id="logo-container">
                    <img src="{{ asset('/ware-master-high-resolution-logo.png') }}" alt="">
                    <div class="slogan">
                        <span>Ware Master</span>
                        <span>Warehouse Management</span>
                    </div>
                </div>
                <div id="menu-container">
                    <ul>
                        <li class="{{ request()->routeIs('dashboard') ? 'bg-blue' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/dashboard.png') }}" alt="">
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('users.*') ? 'bg-blue' : '' }}">
                            <a href="{{ route('users.index') }}">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/user.png') }}" alt="">
                                    <span>Người dùng</span>
                                </div>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('roles.*') ? 'bg-blue' : '' }}">
                            <a href="{{ route('roles.index') }}">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/user.png') }}" alt="">
                                    <span>Nhóm vai trò </span>
                                </div>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('warehouses.*') ? 'bg-blue' : '' }}">
                            <a href="{{ route('warehouses.index') }}">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/warehouse.png') }}" alt="">
                                    <span>Nhà kho</span>
                                </div>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('providers.*') ? 'bg-blue' : '' }}">
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/partner.png') }}" alt="">
                                    <span>Đối tác</span>
                                </div>
                                <img class="angle-down" src="{{ asset('img/angle-down.png') }}" alt="">
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('providers.index') }}">Nhà cung cấp</a></li>
                                <li><a href="{{ route('customers.index') }}">Khách hàng</a></li>
                            </ul>
                        </li>
                        <li class="{{ request()->routeIs('customers.*') ? 'bg-blue' : '' }}">
                            <a href="{{ route('customers.index') }}">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/category.png') }}" alt="">
                                    <span>Khách hàng</span>
                                </div>
                            </a>

                        </li>
                        <li class="{{ request()->routeIs('categories.*') ? 'bg-blue' : '' }}">
                            <a href="{{ route('categories.index') }}">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/category.png') }}" alt="">
                                    <span>Danh mục</span>
                                </div>
                            </a>

                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/product.png') }}" alt="">
                                    <span>Hàng hóa</span>

                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/task.png') }}" alt="">
                                    <span>Hoạt động</span>
                                </div>
                                <img class="angle-down" src="{{ asset('img/angle-down.png') }}" alt="">
                            </a>
                            <ul class="sub-menu">
                                <li><a href="">Nhập hàng</a></li>
                                <li><a href="">Xuất hàng</a></li>
                                <li><a href="">Kiểm kho</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/setting.png') }}" alt="">
                                    <span>Cài đặt</span>
                                </div>
                            </a>
                        </li>
                        <li class="log-out">
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/log-out.png') }}" alt="">
                                    <span>Log out</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                </div>

            </aside>
            {{-- Sidebar end  --}}
            {{-- Main start  --}}
            <main id="main">
                <div id="navbar">
                    <div id="navbar-left">
                        {{-- <p>Pages / <span>@yield('route1')</span></p>
                        <p>@yield('route2')</p> --}}
                    </div>
                    <div id="navbar-right">
                        <div class="notification">
                            <img src="{{ asset('img/bell.png') }}" alt="">
                        </div>
                        <img class="avatar" src="{{ asset('img/example-avatar.png') }}" alt="">
                    </div>

                </div>
                <div class="content">
                    @yield('content')
                </div>
                <div class="footer">
                    <div class="footer-left">
                        Copyright &copy; 2024.All right reserved.
                    </div>
                    <div class="footer-right">
                        <a href=""><span>About us</span></a>
                        <a href=""><span>License</span></a>
                        <span>Version 1.0.0</span>
                    </div>
                </div>
            </main>
            {{-- Main end  --}}
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- @yield('js') --}}
    @stack('js')
</body>

</html>
