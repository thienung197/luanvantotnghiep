@extends('layouts.app')
@section('title', 'Nhóm người dùng')

@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý nhóm người dùng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Nhóm người dùng</a></p>
        </div>
    </div>

    <div class="table_container">
        <div class="table_title">
            Danh sách các nhóm
            <div class="btn-cs btn-add">
                <a href="{{ route('roles.create') }}">Thêm nhóm</a>
            </div>
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('roles.index') }}" method="GET">
                {{-- <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục --}}
            </form>
            <div class="table_search-box">
                {{-- <form action="{{ route('roles.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên nhóm">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                {{-- <th>STT</th> --}}
                <th>Tên nhóm người dùng </th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($roles->currentPage() - 1) * $roles->perPage() + 1;
            @endphp
            @foreach ($roles as $role)
                <tr>
                    {{-- <td>{{ $stt++ }}</td> --}}
                    <td>{{ $role->name }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('roles.edit', $role->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                            id="form-delete{{ $role->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $role->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>
                    </td>
                </tr>
            @endforeach

        </table>
        {{ $roles->links() }}
    </div>

@endsection
@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
