@extends('layouts.app')
@section('title', 'Phiếu xuất hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu xuất hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu xuất hàng</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu xuất hàng
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
                <th>Mã phiếu xuất hàng</th>
                <th>Khách hàng</th>
                <th>Nhà kho</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            {{-- @php
                $stt = ($goodsReceipts->currentPage() - 1) * $goodsReceipts->perPage() + 1;
            @endphp --}}
            @foreach ($goodsIssues as $goodsIssue)
                <tr>
                    <td>{{ $goodsIssue->code }}</td>
                    <td>{{ $goodsIssue->getProviderName() }}</td>
                    <td>{{ $goodsIssue->getWarehouseName() }}</td>
                    <td>{{ $goodsIssue->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('goodsissues.edit', $goodsIssue->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('goodsissues.destroy', $goodsIssue->id) }}" method="POST"
                            id="form-delete{{ $goodsIssue->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $goodsIssue->id }}"><img
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
