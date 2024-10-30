@extends('layouts.app')
@section('title', 'Nhà kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý nhà kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Nhà kho</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('warehouses.create') }}">Thêm nhà kho</a>
    </div>
    @if (Session::has('message'))
        <script>
            toastr.success("{{ Session::get('message') }}")
        </script>
    @endif
    <div class="table_container">
        <div class="table_title">
            Danh sách nhà kho
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('warehouses.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('warehouses.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên nhà cung cấp">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Tên nhà cung cấp</th>
                <th>Địa chỉ</th>
                <th>Sức chứa</th>
                <th>Diện tích (m <sup>2</sup>)</th>
                <th>Bảo quản lạnh</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($warehouses->currentPage() - 1) * $warehouses->perPage() + 1;
            @endphp
            @foreach ($warehouses as $warehouse)
                <tr>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->getAddress() }}</td>
                    <td>{{ $warehouse->capacity }}</td>
                    <td>{{ $warehouse->size }}</td>
                    <td>{{ $warehouse->isRefrigerated == 1 ? 'Có' : 'Không' }}
                    </td>
                    <td>{{ $warehouse->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST"
                            id="form-delete{{ $warehouse->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $warehouse->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>

                    </td>
                </tr>
            @endforeach

        </table>
        {{ $warehouses->links() }}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
