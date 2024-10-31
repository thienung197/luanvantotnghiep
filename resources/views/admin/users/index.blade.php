@extends('layouts.app')
@section('title', 'Quản lý người dùng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý người dùng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Người dùng</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('users.create') }}">Thêm người dùng</a>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách người dùng
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('users.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('users.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên người dùng">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                {{-- <th>STT</th> --}}
                <th>Ảnh đại diện</th>
                <th>Tên người dùng </th>
                <th>Giới tính</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Trạng thái hoạt động</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($users->currentPage() - 1) * $users->perPage() + 1;
            @endphp
            @foreach ($users as $user)
                <tr>
                    {{-- <td>{{ $stt++ }}</td> --}}
                    <td><img width="100" height="100"
                            src="{{ $user->images->count() > 0 ? asset('upload/' . $user->images->first()->url) : asset('upload/man.png') }}"
                            alt=""></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->gender == 'male' ? 'Nam' : 'Nữ' }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->status == 'active' ? 'Đang hoạt động' : ($user->status == 'inactive' ? 'Ngừng hoạt động' : 'Bị khóa') }}
                    </td>
                    <td>{{ $user->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('users.edit', $user->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                            id="form-delete{{ $user->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $user->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>
                    </td>
                </tr>
            @endforeach

        </table>
        {{ $users->links() }}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
