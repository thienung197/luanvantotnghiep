@extends('layouts.app')
@section('title', 'Phiếu xuất kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Phiếu xuất kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('manager.goodsissues.order') }}">Yêu cầu xuất kho</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu xuất kho
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
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Thời gian</th>
                    <th>Tên khách hàng</th>
                    <th>Tổng tiền hàng</th>
                    <th>Trạng thái</th>
                    {{-- <th>Thao tác</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedGoodsIssues as $goodsIssueId => $batches)
                    @php
                        $goodsIssue = $batches->first()->goodsIssue;
                    @endphp
                    <tr class="goods-issue-row" data-id="{{ $goodsIssueId }}">
                        <td>{{ $goodsIssue->code }}</td>
                        <td>{{ $goodsIssue->created_at }}</td>
                        <td>{{ $goodsIssue->getCustomerName() }}</td>
                        <td>{{ number_format($totals[$goodsIssueId], 2) }} VNĐ</td>
                        <td>
                            @if ($goodsIssue->status == 'approved')
                                <span class="order-status">Đơn hàng được phân bổ đến kho</span>
                                {{-- @elseif($goodsIssue->status == 'shipping')
                                Đơn hàng của bạn đang được vận chuyển --}}
                            @endif
                        </td>
                    </tr>

                    <tr class="goods-issue-details" id="details-{{ $goodsIssueId }}" style="display: none;">
                        <td colspan="5">
                            <div class="details-container">
                                <strong class="order-label">Thông tin đơn hàng</strong>
                                <div class="customer-info-container">
                                    <p><span>Tên khách hàng:</span>{{ $goodsIssue->getCustomerName() }}</p>
                                    <p><span>Điện thoại:</span> {{ $goodsIssue->getCustomerPhone() }}</p>
                                    <p><span>Địa chỉ:</span> {{ $goodsIssue->getCustomerAddress() }}</p>
                                    <p style="display: none" id="customer_location_id">
                                        {{ $goodsIssue->getCustomerLocationId() }}
                                    </p>
                                </div>
                                {{-- <strong>Danh sách các sản phẩm</strong> --}}
                                <table class="table table-bordered table-product">
                                    <thead>
                                        <tr>
                                            <th>Mã hàng</th>
                                            <th>Tên hàng</th>
                                            <th>Số lượng</th>
                                            <th>Đơn vị tính</th>
                                            <th>Giá bán</th>
                                            <th>Giảm giá</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($batches as $batch)
                                            <tr>
                                                <td>{{ $batch->batch->code ?? 'N/A' }}</td>
                                                <td>{{ $batch->batch->product->name ?? 'N/A' }}</td>
                                                <td>{{ $batch->quantity }}</td>
                                                <td>{{ $batch->batch->product->unit->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($batch->unit_price, 2) }} VNĐ</td>
                                                <td>{{ number_format($batch->discount, 2) }} VNĐ</td>
                                                <td>{{ number_format($batch->quantity * $batch->unit_price - $batch->discount, 2) }}
                                                    VNĐ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p><span>Tổng tiền hàng: </span><span
                                        class="money">{{ number_format($totals[$goodsIssueId], 2) }} VNĐ</span></p>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {{ $goodsReceipts->links() }} --}}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.goods-issue-row');

            rows.forEach(row => {
                row.addEventListener('click', function() {
                    const goodsIssueId = this.dataset.id;
                    const detailsRow = document.getElementById(`details-${goodsIssueId}`);

                    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                        detailsRow.style.display = 'table-row';
                        row.style.backgroundColor = "rgb(230, 247, 236)";
                    } else {
                        detailsRow.style.display = 'none';
                        row.style.backgroundColor = "#fff";

                    }
                });
            });
        });
    </script>
@endpush
