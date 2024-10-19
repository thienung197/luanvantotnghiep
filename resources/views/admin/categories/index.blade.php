@extends('layouts.app')
@section('title', 'Danh mục')

@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý danh mục
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Danh mục</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('categories.create') }}">Thêm danh mục</a>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách danh mục
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('categories.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('categories.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên danh mục">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Danh mục cha</th>
                <th>Tên danh mục</th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($categories->currentPage() - 1) * $categories->perPage() + 1;
            @endphp
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->getParentName() }}</td>
                    <td>{{ $category->name }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('categories.edit', $category->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                            id="form-delete{{ $category->id }}">
                            @csrf
                            @method('delete')

                        </form>
                        <button type="submit" class="btn-delete" data-id={{ $category->id }}><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>
                    </td>
                </tr>
            @endforeach

        </table>
        {{ $categories->links() }}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
