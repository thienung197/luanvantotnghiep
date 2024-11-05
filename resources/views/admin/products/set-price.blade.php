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
    <div class="set-price-section">
        <div class="content-10">
            <div class="form-group input-div">
                <h4>Danh mục</h4>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="0" selected>Tất cả</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="content-10">
            <div class="form-group input-div">
                <h4>Tồn kho</h4>
                <label>
                    <input type="radio" name="option" value="option1">
                    Tất cả
                </label>
                <br>
                <label>
                    <input type="radio" name="option" value="option2">
                    Dưới định mức tồn
                </label>
                <br>
                <label>
                    <input type="radio" name="option" value="option3">
                    Vượt định mức tồn
                </label>
                <br>
                <label>
                    <input type="radio" name="option" value="option4">
                    Còn hàng trong kho
                </label>
                <label>
                    <input type="radio" name="option" value="option4">
                    Hết hàng trong kho
                </label>
            </div>
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
            <thead>
                <tr>
                    <th>Ảnh sản phẩm</th>
                    <th>Tên hàng hóa</th>
                    <th>Mã hàng hóa </th>
                    <th>Đơn vị </th>
                    <th>Tồn kho</th>
                    <th>Giá nhập gần nhất</th>
                    <th>Giá bán </th>
                    {{-- <th>Thao tác</th> --}}
                </tr>
            </thead>
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
                    {{-- <td class="btn-cell">
                        <a href="{{ route('products.edit', $product->id) }}"><img src="{{ asset('img/edit.png') }}"
                                alt=""></a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit"><img src="{{ asset('img/delete.png') }}" alt=""></button>
                        </form>
                    </td> --}}
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


        $(document).on('change', '#category_id', function() {
            let categoryId = $(this).val();

            $.ajax({
                url: '{{ route('products.filterByCategory') }}',
                method: 'GET',
                data: {
                    category_id: categoryId
                },
                success: function(response) {
                    let tbody = $('#table-list tbody');
                    tbody.empty();

                    response.products.forEach(product => {
                        let row = $('<tr></tr>');
                        row.append(
                            `<td><img width="100" height="100" src="${product.image}" alt=""></td>`
                        );
                        row.append(`<td>${product.name}</td>`);
                        row.append(`<td>${product.code}</td>`);
                        row.append(`<td>${product.unit}</td>`);
                        row.append(`<td>${product.status}</td>`);
                        row.append(`<td>${product.refrigerated}</td>`);
                        row.append(`<td>${product.created_at}</td>`);
                        row.append(`
                    <td class="btn-cell">
                        <a href="{{ url('products/edit') }}/${product.id}"><img src="{{ asset('img/edit.png') }}" alt=""></a>
                        <form action="{{ url('products/destroy') }}/${product.id}" method="POST" style="display:inline;">
                            @csrf
                            @method('delete')
                            <button type="submit"><img src="{{ asset('img/delete.png') }}" alt=""></button>
                        </form>
                    </td>
                `);

                        tbody.append(row);
                    });
                },
                error: function(xhr) {
                    console.log("Error:", xhr);
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
        .set-price-section {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .set-price-section .content-10 {
            width: 30%;
            margin: ;
            : 0 40px 0 0;
        }
    </style>
@endpush
