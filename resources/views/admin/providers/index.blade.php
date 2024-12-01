@extends('layouts.app')
@section('title', 'Nhà cung cấp')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý nhà cung cấp
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Nhà cung cấp</a></p>
        </div>
    </div>

    @if (Session::has('message'))
        <script>
            toastr.success("{{ Session::get('message') }}")
        </script>
    @endif
    <div class="table_container">
        <div class="table_title">
            Danh sách nhà cung cấp
            <div class="btn-cs btn-add">
                <a href="{{ route('providers.create') }}">Thêm nhà cung cấp</a>
            </div>
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('providers.index') }}" method="GET">
                {{-- <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục --}}
            </form>
            <div class="table_search-box">
                {{-- <form action="{{ route('providers.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên nhà cung cấp">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Tên nhà cung cấp</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Trạng thái hoạt động</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($providers->currentPage() - 1) * $providers->perPage() + 1;
            @endphp
            @foreach ($providers as $provider)
                <tr>
                    <td>{{ $provider->name }}</td>
                    <td>{{ $provider->phone }}</td>
                    <td>{{ $provider->email }}</td>
                    <td>{{ $provider->getAddress() }}</td>
                    <td>{{ $provider->status == 'active' ? 'Đang hoạt động' : ($provider->status == 'inactive' ? 'Ngừng hoạt động' : 'Tạm ngưng hoạt động') }}
                    </td>
                    <td>{{ $provider->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('providers.edit', $provider->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('providers.destroy', $provider->id) }}" method="POST"
                            id="form-delete{{ $provider->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $provider->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>

                    </td>
                </tr>
            @endforeach

        </table>
        {{ $providers->links() }}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
