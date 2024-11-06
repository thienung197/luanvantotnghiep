@extends('layouts.app')
@section('title', 'Hàng tồn kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Hàng tồn kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('employee.inventory') }}">Yêu cầu xuất hàng</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách hàng tồn kho
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
                    <th>Hình ảnh</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Đơn vị </th>
                    <th>Giá bán </th>
                    <th>Tình trạng</th>
                    <th>Tồn kho</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        // Lấy tổng số lượng có sẵn của tất cả các lô hàng cho sản phẩm này
                        $totalQuantityAvailable = 0;
                        foreach ($product->batches as $batch) {
                            $inventory = $batch->inventories->firstWhere('warehouse_id', Auth::user()->warehouse_id);
                            $quantityAvailable = $inventory ? $inventory->quantity_available : 0;
                            $totalQuantityAvailable += $quantityAvailable;
                        }
                    @endphp

                    <tr class="goods-issue-row" data-id="{{ $product->id }}">
                        <td>
                            <img src="{{ asset('upload/' . ($product->image ?? 'no-image.png')) }}" alt="Product Image"
                                width="50">
                        </td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->getUnitName() }}</td>
                        <td>{{ number_format($product->selling_price, 2) }}</td>
                        <td>{{ $product->status }}</td>
                        <td>{{ $totalQuantityAvailable }}</td>
                        <td class="btn-cell">
                            <button type="button" class="btn-toggle-details">Chi tiết</button>
                        </td>
                    </tr>

                    <!-- Dòng chi tiết của sản phẩm, hiển thị các lô hàng khi nhấn vào -->
                    <tr class="goods-issue-details" id="details-{{ $product->id }}" style="display: none;">
                        <td colspan="8">
                            <div class="details-container">
                                <strong>Thông tin các lô hàng</strong>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã lô</th>
                                            <th>Giá bán</th>
                                            <th>Ngày sản xuất</th>
                                            <th>Ngày hết hạn</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->batches as $batch)
                                            @php
                                                $inventory = $batch->inventories->firstWhere(
                                                    'warehouse_id',
                                                    Auth::user()->warehouse_id,
                                                );
                                                $quantityAvailable = $inventory ? $inventory->quantity_available : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $batch->code }}</td>
                                                <td>{{ number_format($batch->price, 2) }}</td>
                                                <td>{{ $batch->manufacturing_date }}</td>
                                                <td>{{ $batch->expiry_date }}</td>
                                                <td>{{ $quantityAvailable }}</td>
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
                    const productId = this.getAttribute("data-id");
                    const detailsRow = document.getElementById(`details-${productId}`);

                    // Toggle the display of the details row
                    if (detailsRow.style.display === "none" || detailsRow.style.display === "") {
                        detailsRow.style.display = "table-row";
                    } else {
                        detailsRow.style.display = "none";
                    }
                });
            });
        });
    </script>
@endpush
