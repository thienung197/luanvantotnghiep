@extends('layouts.app')
@section('title', 'Phiếu yêu cầu')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Danh sách yêu cầu xuất hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('manager.goodsissues.order') }}">Yêu cầu xuất hàng</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu yêu cầu
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
                        placeholder="Nhập tên phiếu xuất hàng">
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
            @foreach ($goodsIssueBatches as $goodsIssueBatch)
                <tr class="goods-issue-row" data-id="{{ $goodsIssueBatch->id }}">
                    <td>{{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->code }}</td>
                    <td>{{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->created_at }}</td>
                    <td>{{ number_format($totalAmount, 2) }}</td>
                    <td>Đơn hàng đã được phân bổ cho kho</td>
                    <td class="btn-cell">
                        <a href="{{ route('goodsissues.edit', $goodsIssueBatch->goodsIssueDetail->goodsIssue->id) }}"><img
                                src="{{ asset('img/edit.png') }}" alt=""></a>
                        <form
                            action="{{ route('goodsissues.destroy', $goodsIssueBatch->goodsIssueDetail->goodsIssue->id) }}"
                            method="POST" id="form-delete{{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete"
                            data-id="{{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>
                    </td>
                </tr>
                <tr class="goods-issue-details" id="details-{{ $goodsIssueBatch->id }}" style="display: none;">
                    <td colspan="5">
                        <div class="details-container">
                            <strong>Thông tin đơn hàng</strong>
                            <p>Tên khách hàng: {{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->getCustomerName() }}</p>
                            <p>Điện thoại: {{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->getCustomerPhone() }}</p>
                            <p>Địa chỉ: {{ $goodsIssueBatch->goodsIssueDetail->goodsIssue->getCustomerAddress() }}</p>

                            <strong>Thông tin đơn hàng (Batch)</strong>
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
                                    @foreach ($goodsIssueBatch->goodsIssueDetail->filteredGoodsIssueBatches as $batch)
                                        <tr>
                                            <td>{{ $goodsIssueBatch->goodsIssueDetail->product_id }}</td>
                                            <td>{{ $goodsIssueBatch->goodsIssueDetail->product->name ?? 'N/A' }}</td>

                                            <td>{{ $batch->quantity }}</td>
                                            <td>{{ number_format($goodsIssueBatch->goodsIssueDetail->unit_price, 2) }}</td>
                                            <td>{{ number_format($goodsIssueBatch->goodsIssueDetail->discount, 2) }}</td>
                                            <td>{{ number_format($batch->quantity * $goodsIssueBatch->goodsIssueDetail->unit_price - $goodsIssueBatch->goodsIssueDetail->discount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
