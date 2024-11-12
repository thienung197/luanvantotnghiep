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
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Thời gian</th>
                    <th>Tổng tiền hàng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
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
                        <td>{{ number_format($totals[$goodsIssueId], 2) }}</td>
                        <!-- Hiển thị tổng tiền hàng cho đơn hàng này -->
                        <td>
                            @if ($goodsIssue->status == 'approved')
                                Đơn hàng được phân bổ đến kho
                            @elseif($goodsIssue->status == 'shipping')
                                Đơn hàng của bạn đang được vận chuyển
                            @endif
                        </td>
                        <td class="btn-cell">
                            <a href="{{ route('goodsissues.edit', $goodsIssue->id) }}"><img
                                    src="{{ asset('img/edit.png') }}" alt="Edit"></a>
                            <form action="{{ route('goodsissues.destroy', $goodsIssue->id) }}" method="POST"
                                id="form-delete{{ $goodsIssue->id }}">
                                @csrf
                                @method('delete')
                            </form>
                            <button type="submit" class="btn-delete" data-id="{{ $goodsIssue->id }}"><img
                                    src="{{ asset('img/delete.png') }}" alt="Delete"></button>
                        </td>
                    </tr>

                    <!-- Chi tiết đơn hàng -->
                    <tr class="goods-issue-details" id="details-{{ $goodsIssueId }}" style="display: none;">
                        <td colspan="5">
                            <div class="details-container">
                                <strong>Thông tin đơn hàng</strong>
                                <p>Tên khách hàng: {{ $goodsIssue->customer->name ?? 'N/A' }}</p>
                                <p>Điện thoại: {{ $goodsIssue->customer->phone ?? 'N/A' }}</p>
                                <p>Địa chỉ: {{ $goodsIssue->customer->address ?? 'N/A' }}</p>

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
                                        @foreach ($batches as $batch)
                                            <tr>
                                                <td>{{ $batch->batch->code ?? 'N/A' }}</td>
                                                <td>{{ $batch->batch->product->name ?? 'N/A' }}</td>
                                                <td>{{ $batch->quantity }}</td>
                                                <td>{{ number_format($batch->unit_price, 2) }}</td>
                                                <td>{{ number_format($batch->discount, 2) }}</td>
                                                <td>{{ number_format($batch->quantity * $batch->unit_price - $batch->discount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
