@extends('layouts.app')
@section('title', 'Quản lý hàng hóa')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý hàng hóa
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Người dùng</a></p>
        </div>
    </div>
    <div class="btn-cs btn-add">
        <div class="select-category">
            <h4>Chọn loại hàng hóa</h4>
            <form action="{{ route('products.create') }}" method="GET">
                <select name="category_id" id="category" class="form-control">
                    <option value="">Chọn loại hàng hóa</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Thêm hàng hóa</button>

            </form>
        </div>
    </div>
    <div class="table_container">
        <div class="table_title">
            Danh sách hàng hóa
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('products.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('products.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên hàng hóa">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                {{-- <th>STT</th> --}}
                <th>Ảnh sản phẩm</th>
                <th>Tên hàng hóa</th>
                <th>Mã hàng hóa </th>
                <th>Đơn vị </th>
                <th>Tình trạng hàng hóa</th>
                <th>Tình trạng bảo quản</th>
                <th>Ngày tạo </th>
                <th>Thao tác</th>
            </tr>
            @php
                $stt = ($products->currentPage() - 1) * $products->perPage() + 1;
            @endphp
            @foreach ($products as $product)
                <tr>
                    {{-- <td>{{ $stt++ }}</td> --}}
                    <td><img width="100" height="100"
                            src="{{ $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png') }}"
                            alt=""></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->getUnitName() }}</td>
                    <td>{{ $product->status == 'active' ? 'Còn hàng' : ($product->status == 'out_of_stock' ? 'Ngừng hoạt động' : 'Ngừng kinh doanh') }}
                    </td>
                    <td>{{ $product->refrigerated === 1 ? 'Bảo quản lạnh' : 'Điều kiện thường' }}
                    </td>
                    <td>{{ $product->created_at }}</td>
                    <td class="btn-cell">
                        <a href="{{ route('products.edit', $product->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit"><img src="{{ asset('img/delete.png') }}" alt=""></button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </table>
        {{ $products->links() }}
    </div>
@endsection

@push('js')
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
    </script>
@endpush
