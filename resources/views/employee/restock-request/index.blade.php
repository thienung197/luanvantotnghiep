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

    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu <div class="btn-cs btn-add"><a href="{{ route('restock-request.create') }}">Thêm phiếu</a></div>
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
                {{-- <div class="btn-cs btn-add">
                    <a href="{{ route('restock-request.create') }}">Thêm phiếu</a>
                </div> --}}
            </form>
            <div class="table_search-box">
                {{-- <form action="{{ route('goodsissues.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu đề nghị nhập hàng">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>

        <table class="table" id="table-list">
            <tr>
                <th>Mã đơn hàng</th>
                <th>Người gửi </th>
                <th>Trạng thái</th>
                <th>Ngày tạo </th>
                {{-- <th>Thao tác</th> --}}
            </tr>

            @foreach ($restockRequests as $restockRequest)
                <tr class="restock-request-row" data-id="{{ $restockRequest->id }}">
                    <td>{{ $restockRequest->code }}</td>
                    <td>{{ $restockRequest->getUserName() }}</td>
                    <td>
                        @if ($restockRequest->status == 'pending')
                            Phiếu yêu cầu đã được gửi đi
                        @elseif($restockRequest->status == 'in_review')
                            Phiếu yêu cầu được được xem xét
                        @elseif($restockRequest->status == 'reviewed')
                            Phiếu yêu cầu đã được xem xét
                        @endif
                    </td>
                    <td>{{ $restockRequest->created_at }}</td>
                </tr>

                <tr class="goods-issue-details" id="details-{{ $restockRequest->id }}" style="display: none;">
                    <td colspan="5">
                        <div class="details-container">
                            <h4>Các sản phẩm được đề nghị nhập hàng</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã hàng</th>
                                        <th>Tên hàng</th>
                                        <th>Số lượng</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($restockRequest->restockRequestDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->product->code }}</td>
                                            <td>{{ $detail->product->name }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>
                                                @if ($detail->status == 'rejected')
                                                    Sản phẩm bị từ chối
                                                @elseif ($detail->status == 'pending')
                                                    Sản phẩm đang chờ duyệt
                                                @else
                                                    Sản phẩm được duyệt
                                                @endif
                                            </td>
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

        // document.addEventListener("DOMContentLoaded", function() {
        //     const rows = document.querySelectorAll(".restock-request-row");

        //     rows.forEach(row => {
        //         row.addEventListener("click", function() {
        //             const stockRequestId = this.getAttribute("data-id");
        //             const detailsRow = document.getElementById(`details-${stockRequestId}`);

        //             if (detailsRow.style.display === "none") {
        //                 detailsRow.style.display = "table-row";
        //             } else {
        //                 detailsRow.style.display = "none";
        //             }
        //         });
        //     });
        // });
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".restock-request-row");
            rows.forEach(row => {
                row.addEventListener("click", function() {
                    const restockRequestId = this.getAttribute("data-id");
                    const detailsRow = document.getElementById(`details-${restockRequestId}`);
                    console.log(detailsRow);

                    if (detailsRow.style.display === "none") {
                        detailsRow.style.display = "table-row";
                    } else {
                        detailsRow.style.display = "none";
                    }
                })
            });
        })
    </script>
@endpush
