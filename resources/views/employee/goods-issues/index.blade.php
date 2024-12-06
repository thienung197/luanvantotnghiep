@extends('layouts.app')
@section('title', 'Đơn hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý đơn hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Quản lý đơn hàng</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách đơn hàng của bạn
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('goodsissues.index') }}" method="GET">
                {{-- <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục --}}
            </form>
            <div class="table_search-box">
                {{-- <form action="{{ route('goodsissues.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu xuất hàng">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>

        <table class="table" id="table-list">
            {{-- <tr>
                <th>Mã đơn hàng</th>
                <th>Tổng tiền hàng</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
            </tr> --}}

            @foreach ($goodsIssues as $goodsIssue)
                {{-- <tr class="goods-issue-row" data-id="{{ $goodsIssue->id }}">
                    <td>{{ $goodsIssue->code }}</td>
                    <td>{{ $goodsIssue->getTotalAmount() }}</td>
                    <td>
                        @if ($goodsIssue->status == 'pending')
                            Đơn hàng của bạn đã được tạo và đang chờ xử lý
                        @elseif($goodsIssue->status == 'approved')
                            Đơn hàng của bạn đã được phê duyệt
                        @elseif($goodsIssue->status == 'processing')
                            Đơn hàng của bạn đang trong quá trình lấy hàng từ kho
                        @elseif($goodsIssue->status == 'shipping')
                            Đơn hàng của bạn đang được vận chuyển
                        @elseif($goodsIssue->status == 'delivered')
                            Đơn hàng của bạn đã được giao thành công
                        @endif
                    </td>
                    <td>{{ $goodsIssue->created_at }}</td>
                </tr> --}}

                <tr class="goods-issue-details" id="details-{{ $goodsIssue->id }}">
                    <td colspan="5">
                        <div class="details-container">
                            <p class="title ">Thông tin đơn hàng</p>
                            <div class="customer-order-info-container">
                                <div class="customer-info-container">
                                    <p><span>Tên khách hàng:</span>{{ $goodsIssue->getCustomerName() }}</p>
                                    <p><span>Điện thoại:</span> {{ $goodsIssue->getCustomerPhone() }}</p>
                                    <p><span>Địa chỉ:</span> {{ $goodsIssue->getCustomerAddress() }}</p>
                                </div>
                                <div class="order-info-container">
                                    <p> <span>Tình trạng đơn hàng: </span>
                                        @if ($goodsIssue->status == 'pending')
                                            <span class="order-status">Đơn hàng của bạn đã được tạo và đang chờ xử lý</span>
                                        @elseif($goodsIssue->status == 'approved')
                                            <span class="order-status">Đơn hàng của bạn đã được phê duyệt</span>
                                            {{-- @elseif($goodsIssue->status == 'processing')
                                            Đơn hàng của bạn đang trong quá trình lấy hàng từ kho
                                        @elseif($goodsIssue->status == 'shipping')
                                            Đơn hàng của bạn đang được vận chuyển
                                        @elseif($goodsIssue->status == 'delivered')
                                            Đơn hàng của bạn đã được giao thành công --}}
                                        @endif
                                    </p>
                                    <p><span>Thời gian đặt hàng: {{ $goodsIssue->created_at }}</span></p>
                                </div>
                            </div>

                            {{-- <strong>Sản phẩm</strong> --}}
                            <table class="table table-bordered table-product">
                                <thead>
                                    <tr>
                                        <th>Mã hàng</th>
                                        <th>Tên hàng</th>
                                        <th>Số lượng</th>
                                        <th>Giá bán (VNĐ)</th>
                                        <th>Giảm giá (VNĐ)</th>
                                        <th>Thành tiền (VNĐ)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($goodsIssue->goodsIssueDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->product->code }}</td>
                                            <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ number_format($detail->unit_price, 2) }} </td>
                                            <td>{{ number_format($detail->discount, 2) }} </td>
                                            <td>{{ number_format($detail->quantity * $detail->unit_price - $detail->discount, 2) }}

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p><span class="total-money">Tổng tiền hàng:
                                </span><span class="money">{{ $goodsIssue->getTotalAmount() }} VNĐ</span></p>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- {{ $goodsReceipts->links() }} --}}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush

@push('css')
    <style>
        .total-money span {
            font-weight: 600 !important;
        }



        .customer-order-info-container {
            display: flex;
            align-items: center;
        }

        .customer-info-container {
            width: 50%;
        }

        .details-container p {
            text-align: left;
            font-style: italic;
            color: var(--color-black);
        }

        .details-container p span {
            font-style: italic;
            color: var(--color-black);
            font-weight: 500;
            margin-right: 20px;
        }

        .details-container p.title {
            color: var(--color-black);
            font-weight: 600;
            font-size: 20px;
            font-style: italic;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".goods-issue-row");

            rows.forEach((row) => {
                row.addEventListener("click", function() {
                    const goodsIssueId = this.getAttribute("data-id");

                    const detailsRow = document.getElementById(
                        `details-${goodsIssueId}`
                    );

                    if (detailsRow.style.display === "none") {
                        detailsRow.style.display = "table-row";
                        row.style.backgroundColor = "rgb(230, 247, 236)";
                    } else {
                        detailsRow.style.display = "none";
                        row.style.backgroundColor = "#fff";
                    }
                });
            });
        });
    </script>
@endpush
