@extends('layouts.app')
@section('title', 'Phiếu nhập hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu nhập hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu nhập hàng</a></p>
        </div>
    </div>

    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu nhập hàng
        </div>
        <p>Danh sách các sản phẩm được yêu cầu nhập hàng, phân loại theo nhà cung cấp</p>
        @foreach ($approvedProducts as $provider)
            <h3>Tên nhà cung cấp:{{ $provider['provider_name'] }}</h3>
            <div class="suggested-products-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Đơn vị tính</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($provider['products'] as $product)
                            <tr>
                                <td>{{ $product['code'] }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['unit'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a
                href="{{ route('goodsreceipts.create', ['provider_id' => $provider['provider_id'], 'product_ids' => $provider['product_ids']]) }}">Đặt
                hàng</a>
        @endforeach

        <div class="table_filter-controls">
            {{-- <form action="{{ route('goodsreceipts.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form> --}}
            <div class="btn-cs btn-add">
                <a href="{{ route('goodsreceipts.create') }}">Thêm phiếu nhập hàng </a>
            </div>
            <div class="table_search-box">
                <form action="{{ route('goodsreceipts.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu nhập hàng">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Mã phiếu nhập hàng</th>
                <th>Nhà cung cấp</th>
                <th>Nhà kho</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            {{-- @php
                $stt = ($goodsReceipts->currentPage() - 1) * $goodsReceipts->perPage() + 1;
            @endphp --}}
            @foreach ($goodsReceipts as $goodsReceipt)
                <tr>
                    <td>{{ $goodsReceipt->code }}</td>
                    <td>{{ $goodsReceipt->getProviderName() }}</td>
                    <td>{{ $goodsReceipt->getWarehouseName() }}</td>
                    <td>{{ $goodsReceipt->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('goodsreceipts.edit', $goodsReceipt->id) }}"><img
                                src="{{ asset('img/edit.png') }}" alt=""></a>
                        <form action="{{ route('goodsreceipts.destroy', $goodsReceipt->id) }}" method="POST"
                            id="form-delete{{ $goodsReceipt->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $goodsReceipt->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>

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
