@extends('layouts.app')
@section('title', 'Cập nhật hàng hóa')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Cập nhật hàng hóa
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('customers.index') }}">Hàng hóa</a> > <a href="">Cập nhật
                    hàng hóa</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group input-div">
                <input type="file" accept="image/*" class="form-control" name="image">
                <div class="show-image">
                    <img src="{{ $product->images->count() > 0 ? asset('upload/products/' . $product->images->first()->url) : asset('upload/products/man.png') }}"
                        alt="">
                </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Mã hàng hóa</h4>
                <input type="text" name="code" value="{{ old('code') ?? $product->code }}" id="code"
                    class="form-control">
                @error('code')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tên hàng hóa</h4>
                <input type="text" name="name" value="{{ old('name') ?? $product->name }}" id="name"
                    class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Danh mục</h4>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">---Chọn danh mục---</option>
                    @foreach ($categories as $category)
                        <option {{ old('$category_id') ?? $product->category_id ? 'selected' : '' }}
                            value="{{ $category->id }}">{{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group input-div">
                <h4>Mô tả </h4>
                <input type="text" name="description" value="{{ old('discription') ?? $product->description }}"
                    id="address" class="form-control">
                @error('address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Đơn vị </h4>
                <select name="unit_id" id="" class="form-control">
                    <option value="">---Chọn đơn vị ---</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') ?? $product->unit_id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4> Tình trạng bảo quản</h4>
                <select name="refrigerated" id="" class="form-control">
                    <option value="">---Chọn tình trạng bảo quản ---</option>
                    <option value="1" {{ (old('refrigerated') ?? $product->refrigerated) === 1 ? 'selected' : '' }}>Có
                        bảo quản lạnh
                    </option>
                    <option value="0" {{ (old('refrigerated') ?? $product->refrigerated) === 0 ? 'selected' : '' }}>
                        Không bảo quản
                        lạnh</option>
                </select>
                @error('refrigerated')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4> Trạng thái sản phẩm</h4>
                <select name="status" id="" class="form-control">
                    <option value="">---Chọn tình trạng bảo quản ---</option>
                    <option value="active" {{ (old('status') ?? $product->status) === 'active' ? 'selected' : '' }}>
                        Còn hàng
                    </option>
                    <option value="inactive" {{ (old('status') ?? $product->status) === 'inactive' ? 'selected' : '' }}>
                        Hết hàng</option>
                </select>
                @error('status')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
            <div id="attributes_container">
            </div>
    </div>
    <div class="btn-controls">
        <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
        <div class="btn-cs btn-delete"><a href="{{ route('roles.index') }}">Quay lại </a></div>
    </div>
    </form>
    </div>

@endsection


@push('js')
    <script>
        document.getElementById('category_id').addEventListener('change', function() {
            let categoryId = this.value;
            let attributesContainer = document.getElementById('attributes_container');

            if (!categoryId) {
                attributesContainer.innerHTML = '';
                return;
            }

            fetch(`/categories/${categoryId}/attributes`)
                .then(response => response.json())
                .then(data => {
                    attributesContainer.innerHTML = '';

                    if (data.error) {
                        attributesContainer.innerHTML = `<p>${data.error}</p>`;
                        return;
                    }

                    data.forEach(attribute => {
                        let attributeDiv = document.createElement('div');
                        attributeDiv.classList.add('form-group', 'input-div');

                        let label = document.createElement('h4');
                        label.innerText = attribute.name;
                        attributeDiv.appendChild(label);

                        let select = document.createElement('select');
                        select.name = 'attributes_values[]';
                        select.classList.add('form-control');

                        attribute.attribute_values.forEach(value => {
                            let option = document.createElement('option');
                            option.value = value.id;
                            option.textContent = value.value;
                            select.appendChild(option);
                        });

                        attributeDiv.appendChild(select);
                        attributesContainer.appendChild(attributeDiv);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    attributesContainer.innerHTML = `<p>Không thể tải thuộc tính.</p>`;
                });
        });
    </script>
@endpush
