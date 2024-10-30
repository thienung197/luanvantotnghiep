@extends('layouts.app')
@section('title', 'Khách hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý khách hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Khách hàng</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <a href="{{ route('customers.create') }}">Thêm khách hàng</a>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách khách hàng
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('customers.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('customers.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên khách hàng">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Tên khách hàng</th>
                <th>Giới tính</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($customers->currentPage() - 1) * $customers->perPage() + 1;
            @endphp
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->gender }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->getAddress() }}</td>
                    <td>{{ $customer->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('customers.edit', $customer->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                            id="form-delete{{ $customer->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $customer->id }}"><img
                                src="{{ asset('img/delete.png') }}" alt=""></button>

                    </td>
                </tr>
            @endforeach

        </table>
        {{ $customers->links() }}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
