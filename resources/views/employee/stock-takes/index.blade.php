@extends('layouts.app')
@section('title', 'Phiếu kiểm kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu kiểm kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu kiểm kho</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('stocktakes.create') }}">Thêm phiếu kiểm kho</a>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu kiểm kho
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('stocktakes.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('stocktakes.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu kiểm kho">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Mã phiếu kiểm kho</th>
                <th>Ngày kiểm kho</th>
                <th>Người tạo</th>
                <th>Nhà kho </th>
                <th>Ghi chú</th>
                <th>Thao tác</th>
            </tr>
            {{-- @php
                $stt = ($goodsReceipts->currentPage() - 1) * $goodsReceipts->perPage() + 1;
            @endphp --}}
            @foreach ($stockTakes as $stockTake)
                <tr>
                    <td>{{ $stockTake->code }}</td>
                    <td>{{ $stockTake->date }}</td>
                    <td>{{ $stockTake->getUserName() }}</td>
                    <td>{{ $stockTake->getWarehouseName() }}</td>
                    <td>{{ $stockTake->notes }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('stocktakes.edit', $stockTake->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('stocktakes.destroy', $stockTake->id) }}" method="POST"
                            id="form-delete{{ $stockTake->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $stockTake->id }}"><img
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
