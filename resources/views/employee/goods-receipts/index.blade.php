@extends('layouts.app')
@section('title', 'Phiếu nhập kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu nhập kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu nhập kho</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsreceipts.create') }}">Thêm phiếu nhập kho</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu nhập kho
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('goodsreceipts.index') }}" method="GET">
                {{-- <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục --}}
            </form>
            <div class="table_search-box">
                {{-- <form action="{{ route('goodsreceipts.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu nhập kho">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Mã phiếu nhập kho</th>
                <th>Người phân phối</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Ngày phân phối </th>
            </tr>
            {{-- @php
                $stt = ($goodsReceipts->currentPage() - 1) * $goodsReceipts->perPage() + 1;
            @endphp --}}
            @foreach ($goodsReceipts as $goodsReceipt)
                <tr>
                    <td>{{ $goodsReceipt->code }}</td>
                    <td>{{ $goodsReceipt->getUserName() }}</td>
                    <td>{{ $goodsReceipt->goodsReceiptDetails->first()->product->name }}</td>
                    <td>{{ $goodsReceipt->goodsReceiptDetails->first()->quantity }}</td>
                    <td>{{ $goodsReceipt->created_at }}</td>

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
