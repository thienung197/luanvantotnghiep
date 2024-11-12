@extends('layouts.app')
@section('title', 'Phiếu đề nghị nhập hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu đề nghị nhập hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu đề nghị nhập hàng</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('restock-request.create') }}">Thêm phiếu</a>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('goodsissues.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('goodsissues.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu đề nghị nhập hàng">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>

        <table class="table" id="table-list">
            <tr>
                <th>Mã đơn hàng</th>
                <th>Thời gian</th>
                <th>Tổng tiền hàng</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>

            {{-- @foreach ($goodsIssues as $goodsIssue)
                <tr class="goods-issue-row" data-id="{{ $goodsIssue->id }}">
                    <td>{{ $goodsIssue->code }}</td>
                    <td>{{ $goodsIssue->created_at }}</td>
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
                    <td class="btn-cell">
                        <a href="{{ route('goodsissues.edit', $goodsIssue->id) }}">
                            <img src="{{ asset('img/edit.png') }}" alt="">
                        </a>
                        <form action="{{ route('goodsissues.destroy', $goodsIssue->id) }}" method="POST"
                            id="form-delete{{ $goodsIssue->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $goodsIssue->id }}">
                            <img src="{{ asset('img/delete.png') }}" alt="">
                        </button>
                    </td>
                </tr>

                <tr class="goods-issue-details" id="details-{{ $goodsIssue->id }}" style="display: none;">
                    <td colspan="5">
                        <div class="details-container">
                            <strong>Thông tin đơn hàng</strong>
                            <p>Tên khách hàng:{{ $goodsIssue->getCustomerName() }}</p>
                            <p>Điện thoại: {{ $goodsIssue->getCustomerPhone() }}</p>
                            <p>Địa chỉ: {{ $goodsIssue->getCustomerAddress() }}</p>

                            <strong>Sản phẩm</strong>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã hàng</th>
                                        <th>Tên hàng</th>
                                        <th>Số lượng</th>
                                        <th>Giá bán</th>
                                        <th>Giảm giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($goodsIssue->goodsIssueDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->product_id }}</td>
                                            <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ number_format($detail->unit_price, 2) }}</td>
                                            <td>{{ number_format($detail->discount, 2) }}</td>
                                            <td>{{ number_format($detail->quantity * $detail->unit_price - $detail->discount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            @endforeach --}}
        </table>
        {{-- {{ $goodsReceipts->links() }} --}}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif

        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".goods-issue-row");

            rows.forEach(row => {
                row.addEventListener("click", function() {
                    const goodsIssueId = this.getAttribute("data-id");
                    const detailsRow = document.getElementById(`details-${goodsIssueId}`);

                    if (detailsRow.style.display === "none") {
                        detailsRow.style.display = "table-row";
                    } else {
                        detailsRow.style.display = "none";
                    }
                });
            });
        });
    </script>
@endpush
