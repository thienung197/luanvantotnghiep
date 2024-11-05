<table class="table" id="table-list">
    <tr>
        <th>Ảnh sản phẩm</th>
        <th>Tên hàng hóa</th>
        <th>Mã hàng hóa </th>
        <th>Đơn vị </th>
        <th>Tình trạng hàng hóa</th>
        <th>Tình trạng bảo quản</th>
        <th>Ngày tạo </th>
        <th>Thao tác</th>
    </tr>
    @foreach ($products as $product)
        <tr>
            <td><img width="100" height="100"
                    src="{{ $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png') }}"
                    alt=""></td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->code }}</td>
            <td>{{ $product->getUnitName() }}</td>
            <td>{{ $product->status == 'active' ? 'Còn hàng' : ($product->status == 'out_of_stock' ? 'Ngừng hoạt động' : 'Ngừng kinh doanh') }}
            </td>
            <td>{{ $product->refrigerated === 1 ? 'Bảo quản lạnh' : 'Điều kiện thường' }}</td>
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
