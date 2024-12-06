@extends('layouts.app')
@section('title', 'Báo cáo xuất nhập ')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            báo cáo xuất nhập
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Báo cáo xuất nhập </a></p>
        </div>
    </div>

    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu báo cáo

        </div>
        <div class="btn-cs btn-add">
            <a href="{{ route('comprehensive-stock-report.create') }}">Thêm phiếu</a>
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
                        placeholder="Nhập tên phiếu báo cáo xuất nhập tồn">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>

        <table class="table" id="table-list">
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Người gửi </th>
                <th>Ngày tạo </th>
                {{-- <th>Thao tác</th> --}}
            </tr>

            @foreach ($comprehensiveStockReports as $comprehensiveStockReport)
                <tr class="comprehensive-stock-report-row" data-id="{{ $comprehensiveStockReport->id }}">
                    <td>{{ $comprehensiveStockReport->code }}</td>
                    <td><span class="order-status">{{ $comprehensiveStockReport->start_date }}</span></td>
                    <td><span class="order-status">{{ $comprehensiveStockReport->end_date }}</span></td>
                    <td>{{ $comprehensiveStockReport->getUserName() }}</td>
                    <td>{{ $comprehensiveStockReport->created_at }}</td>
                    {{-- <td class="btn-cell">
                            <a href="{{ route('comprehensive-stock-report.edit', $comprehensiveStockReport->id) }}">
                                <img src="{{ asset('img/edit.png') }}" alt="">
                            </a>
                            <form action="{{ route('comprehensive-stock-report.destroy', $comprehensiveStockReport->id) }}"
                                method="POST" id="form-delete{{ $comprehensiveStockReport->id }}">
                                @csrf
                                @method('delete')
                            </form>
                            <button type="submit" class="btn-delete" data-id="{{ $comprehensiveStockReport->id }}">
                                <img src="{{ asset('img/delete.png') }}" alt="">
                            </button>
                        </td> --}}
                </tr>

                <tr class="goods-issue-details" id="details-{{ $comprehensiveStockReport->id }}" style="display: none;">
                    <td colspan="5">
                        <div class="details-container">
                            <h4 class="order-label">Các sản phẩm được báo cáo xuất nhập</h4>
                            <table class="table table-bordered table-product">
                                <thead>
                                    <tr>
                                        <th>Mã hàng</th>
                                        <th>Tên hàng</th>
                                        <th>Đơn vị tính</th>
                                        {{-- <th>Tồn đầu kỳ</th> --}}
                                        <th>Nhập trong kỳ</th>
                                        <th>Xuất trong kỳ</th>
                                        {{-- <th>Tồn cuối kỳ</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($comprehensiveStockReport->comprehensiveStockReportDetails->isNotEmpty())
                                        @foreach ($comprehensiveStockReport->comprehensiveStockReportDetails as $detail)
                                            <tr>
                                                <td>{{ $detail->product->code }}</td>
                                                <td>{{ $detail->product->name }}</td>
                                                <td>{{ $detail->product->unit->name }}</td>
                                                {{-- <td>{{ $detail->beginning_inventory }}</td> --}}
                                                <td>{{ $detail->stock_in }}</td>
                                                <td>{{ $detail->stock_out }}</td>
                                                {{-- <td>{{ $detail->ending_inventory }}</td> --}}
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">No data available</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif

        document.addEventListener('DOMContentLoaded', () => {
            // Lấy tất cả các hàng trong bảng
            const rows = document.querySelectorAll('.comprehensive-stock-report-row');

            rows.forEach(row => {
                row.addEventListener('click', () => {
                    // Lấy ID của báo cáo từ thuộc tính data-id
                    const reportId = row.dataset.id;

                    // Lấy hàng chi tiết tương ứng
                    const detailsRow = document.getElementById(`details-${reportId}`);

                    if (detailsRow) {
                        // Kiểm tra trạng thái hiển thị
                        const isHidden = detailsRow.style.display === 'none';

                        // Ẩn tất cả các hàng chi tiết khác
                        document.querySelectorAll('.goods-issue-details').forEach(detail => {
                            detail.style.display = 'none';
                        });

                        // Hiển thị hoặc ẩn hàng chi tiết hiện tại
                        detailsRow.style.display = isHidden ? 'table-row' : 'none';
                    }
                });
            });
        });
    </script>
@endpush

@push('css')
    <style>
        .order-label {
            font-size: 20px;
        }
    </style>
@endpush
