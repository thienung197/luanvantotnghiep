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
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('style')
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
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/dashboard.png') }}" alt="">
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/user.png') }}" alt="">
                                    Người dùng
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/warehouse.png') }}" alt="">
                                    Nhà kho
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/partner.png') }}" alt="">
                                    Đối tác
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/category.png') }}" alt="">
                                    Danh mục
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/product.png') }}" alt="">
                                    Hàng hóa

                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/task.png') }}" alt="">
                                    Hoạt động
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
                                    Cài đặt
                                </div>
                            </a>
                        </li>
                        <li class="log-out">
                            <a href="">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/log-out.png') }}" alt="">
                                    Log out
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

                    </div>
                    <div id="navbar-right">
                        <div class="notification">
                            <img src="{{ asset('img/bell.png') }}" alt="">
                        </div>
                        <img class="avatar" src="{{ asset('img/example-avatar.png') }}" alt="">
                    </div>

                </div>
                @yield('content')
            </main>
            {{-- Main end  --}}
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
