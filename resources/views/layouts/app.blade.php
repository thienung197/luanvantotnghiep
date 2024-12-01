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
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
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
                    <img src="{{ asset('img/ware-master-high-resolution-logo.png') }}" alt="">
                    <div class="slogan">
                        <span>Ware Master</span>
                        <span>Warehouse Management</span>
                    </div>
                </div>
                <div id="menu-container">
                    <ul>
                        {{-- @can('is-admin')
                            <li class="{{ request()->routeIs('customer.dashboard') ? 'bg-blue' : '' }}">
                                <a href="{{ route('customer.dashboard') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/dashboard.png') }}" alt="">
                                        <span>Dashboard</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-manager')
                            <li class="{{ request()->routeIs('dashboard') ? 'bg-blue' : '' }}">
                                <a href="{{ route('dashboard') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/dashboard.png') }}" alt="">
                                        <span>Dashboard</span>
                                    </div>
                                </a>
                            </li>
                        @endcan --}}
                        @can('is-customer')
                            <li class="{{ request()->routeIs('customer.dashboard') ? 'bg-blue' : '' }}">
                                <a href="{{ route('customer.dashboard') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/dashboard.png') }}" alt="">
                                        <span>Trang chủ</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('users.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('users.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/user.png') }}" alt="">
                                        <span>Người dùng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('roles.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('roles.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/user.png') }}" alt="">
                                        <span>Nhóm vai trò </span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('warehouses.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('warehouses.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/warehouse.png') }}" alt="">
                                        <span>Danh sách nhà kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('inventories.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('inventories.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/warehouse.png') }}" alt="">
                                        <span>Hàng tồn kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-manager')
                            <li class="{{ request()->routeIs('employee.inventory') ? 'bg-blue' : '' }}">
                                <a href="{{ route('employee.inventory') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/warehouse.png') }}" alt="">
                                        <span>Hàng tồn kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('providers.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('providers.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/partner.png') }}" alt="">
                                        <span>Nhà cung cấp</span>
                                    </div>
                                    {{-- <img class="angle-down" src="{{ asset('img/angle-down.png') }}" alt=""> --}}
                                </a>
                                {{-- <ul class="sub-menu">
                                <li><a href="{{ route('providers.index') }}">Nhà cung cấp</a></li>
                                <li><a href="{{ route('customers.index') }}">Khách hàng</a></li>
                            </ul> --}}
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('customers.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('customers.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/category.png') }}" alt="">
                                        <span>Khách hàng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('categories.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('categories.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/category.png') }}" alt="">
                                        <span>Danh mục</span>
                                    </div>
                                </a>

                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('attributes.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('attributes.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/category.png') }}" alt="">
                                        <span>Thuộc tính</span>
                                    </div>
                                </a>

                            </li>
                        @endcan
                        @can('is-admin')
                            <li class="{{ request()->routeIs('products.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('products.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Hàng hóa</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-admin')
                            <li>
                                <a href="javascript:void(0)">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Nhập hàng</span>
                                    </div>
                                    <img class="angle-down" src="{{ asset('img/angle-down.png') }}" alt="">

                                </a>
                                <ul class="sub-menu">
                                    <li
                                        class="{{ request()->routeIs('goodsreceipts.index') || request()->routeIs('goodsreceipts.create') ? 'bg-blue' : '' }}">
                                        <a href="{{ route('goodsreceipts.index') }}">Đặt hàng</a>
                                    </li>
                                    <li class="{{ request()->routeIs('goodsreceipts.display') ? 'bg-blue' : '' }}">
                                        <a href="{{ route('goodsreceipts.display') }}">Danh sách phiếu đặt hàng</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('is-customer')
                            <li class="{{ request()->routeIs('goodsissues.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('goodsissues.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Quản lý đơn hàng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-admin')
                            <li class="{{ request()->routeIs('admin.goodsissues.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('admin.goodsissues.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Danh sách đơn hàng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-manager')
                            <li class="{{ request()->routeIs('manager.goodsissues.order') ? 'bg-blue' : '' }}">
                                <a href="{{ route('manager.goodsissues.order') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Phiếu xuất kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-manager')
                            <li class="{{ request()->routeIs('view-goods-receipts.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('view-goods-receipts.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Phiếu nhập kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-manager')
                            <li class="{{ request()->routeIs('restock-request.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('restock-request.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Phiếu đề nghị nhập hàng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-manager')
                            <li class="{{ request()->routeIs('comprehensive-stock-report.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('comprehensive-stock-report.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Báo cáo xuất nhập tồn</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-admin')
                            <li
                                class="{{ request()->routeIs('admin.comprehensive-stock-report.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('admin.comprehensive-stock-report.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Báo cáo xuất nhập tồn</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-admin')
                            <li class="{{ request()->routeIs('admin.restock-request.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('admin.restock-request.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Danh sách đề nghị nhập hàng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        {{-- @can('is-manager')
                            <li class="{{ request()->routeIs('issue-report.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('issue-report.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Báo cáo xuất kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan --}}

                        @can('is-customer')
                            <li class="{{ request()->routeIs('goodsissues.create') ? 'bg-blue' : '' }}">
                                <a href="{{ route('goodsissues.create') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Đặt hàng</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-customer')
                            <li class="{{ request()->routeIs('customers.update.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('customers.update.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Cập nhật thông tin</span>
                                    </div>
                                </a>
                            </li>
                        @endcan

                        @can('is-manager')
                            <li class="{{ request()->routeIs('customers.update.index') ? 'bg-blue' : '' }}">
                                <a href="{{ route('customers.update.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span>Cập nhật thông tin</span>
                                    </div>
                                </a>
                            </li>
                        @endcan


                        {{-- @can('is-manager')
                            <li class="{{ request()->routeIs('stocktakes.*') ? 'bg-blue' : '' }}">
                                <a href="{{ route('stocktakes.index') }}">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/product.png') }}" alt="">
                                        <span> Kiểm kho</span>
                                    </div>
                                </a>
                            </li>
                        @endcan --}}
                        {{-- <li>
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
                        </li> --}}

                        {{-- <li>
                                <a href="">
                                    <div class="flex-left-content">
                                        <img src="{{ asset('img/setting.png') }}" alt="">
                                        <span>Cài đặt</span>
                                    </div>
                                </a>
                            </li> --}}
                        <li class="log-out">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <div class="flex-left-content">
                                    <img src="{{ asset('img/log-out.png') }}" alt="">
                                    <span>Đăng xuất</span>
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
                    <div id="navbar-right" class="notification-container">
                        <div class="notification" id="notification-icon">
                            <img src="{{ asset('img/bell.png') }}" alt="" id="notification-bell">
                            <span class="notification-count" id="notification-count" style="display: none">0</span>
                        </div>
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="notification-header">
                                <h4>Thông báo</h4>
                                @if (Auth::check() && Auth::user()->warehouse_id)
                                    <input type="hidden" name="warehouse_id"
                                        value="{{ Auth::user()->warehouse_id }}" id="warehouse_id">
                                @else
                                    <input type="hidden" name="warehouse_id" value="" id="warehouse_id">
                                @endif
                                <div class="notification-status">
                                    <div class="notification-status--left">
                                        {{-- <button>Xem tất cả</button>
                                        <button>Chưa xem</button> --}}
                                    </div>
                                    {{-- <div class="notification-status--right">
                                        <p>Đánh giá là đã đọc</p>
                                    </div> --}}
                                </div>
                            </div>
                            <ul id="notification-list">

                            </ul>
                        </div>
                        <div class="user-info-container">
                            <img class="avatar" src="{{ asset('img/example-avatar.png') }}" alt="">
                            @if (Auth::check())
                                <span class="username">{{ Auth::user()->name }}</span>
                            @else
                                <span class="username">Khách</span>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="content">
                    @yield('content')
                </div>

        </div>
        {{-- <div class="footer">
            <div class="footer-left">
                Copyright &copy; 2024.All right reserved.
            </div>
            <div class="footer-right">
                <a href=""><span>About us</span></a>
                <a href=""><span>License</span></a>
                <span>Version 1.0.0</span>
            </div>
        </div> --}}
        </main>
        {{-- Main end  --}}
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- js cho bootstrap modal  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"
        integrity="sha512-7Pi/otdlbbCR+LnW+F7PwFcSDJOuUJB3OxtEHbg4vSMvzvJjde4Po1v4BR9Gdc9aXNUNFVUY+SK51wWT8WF0Gg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            const notificationIcon = $('#notification-icon');
            const notificationDropdown = $('#notification-dropdown');
            const notificationList = $('#notification-list');
            const notificationCount = $('#notification-count');
            const warehouseId = $("#warehouse_id").val();


            function loadUnreadNotificationCount() {
                $.ajax({
                    url: '{{ route('notifications.unread.count') }}',
                    method: 'GET',
                    data: {
                        warehouse_id: warehouseId
                    },
                    success: function(count) {
                        console.log(count);
                        if (count > 0) {
                            notificationCount.css("display", "block");
                            notificationCount.text(count);
                        } else {
                            notificationCount.css("display", "none");
                        }
                    },
                    error: function() {
                        console.log('Không thể lấy số lượng thông báo chưa đọc!');
                    }
                });
            }

            function loadUnreadNotifications() {
                $.ajax({
                    url: '{{ route('notifications.unread') }}',
                    method: 'GET',
                    data: {
                        warehouse_id: warehouseId
                    },
                    success: function(notifications) {

                        notificationList.empty();

                        if (notifications.length > 0) {
                            notifications.forEach(notification => {
                                notificationList.append(
                                    `<li class="notification-item">
                                <p>${notification.message}</p>
                            </li>`
                                );
                            });
                        } else {
                            notificationList.append(
                                '<li class="no-notification">Không có thông báo</li>');
                        }

                        // notificationCount.text(notifications.length);
                        notificationCount.css("display", "none");
                        if (notifications.length > 0) {
                            markAllNotificationsAsRead();
                        }
                    },
                    error: function() {
                        console.log('Không thể lấy thông báo!');
                    }
                });
            }

            function markAllNotificationsAsRead() {
                $.ajax({
                    url: '{{ route('notifications.read') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        warehouse_id: warehouseId
                    },
                    success: function() {
                        notificationCount.text('0');
                    }
                });
            }

            loadUnreadNotificationCount();

            notificationIcon.click(function() {
                notificationDropdown.toggle();
                if (notificationDropdown.is(':visible')) {
                    loadUnreadNotifications();
                }
            });
        });
    </script>
    {{-- @yield('js') --}}
    @stack('js')
</body>

</html>
