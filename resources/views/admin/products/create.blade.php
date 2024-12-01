@extends('layouts.app')
@section('title', 'Thêm hàng hóa')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm hàng hóa
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('products.index') }}">Hàng hóa</a> > <a href="">Thêm
                    hàng hóa</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group input-div">
                <input type="file" accept="image/*" class="form-control" name="image">
                <div class="show-image">
                    <img src="" alt="">
                </div>
                @error('image')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Mã hàng hóa</h4>
                <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
                @error('code')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tên hàng hóa</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Danh mục</h4>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">---Chọn danh mục---</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group input-div">
                <h4>Mô tả </h4>
                <input type="text" name="address" value="{{ old('address') }}" id="address" class="form-control">
                @error('address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Đơn vị </h4>
                <select name="unit_id" id="" class="form-control">
                    <option value="">---Chọn đơn vị ---</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
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
                    <option value="1" {{ old('refrigerated') === 1 ? 'selected' : '' }}>Có bảo quản lạnh
                    </option>
                    <option value="0" {{ old('refrigerated') === 0 ? 'selected' : '' }}>Không bảo quản
                        lạnh</option>
                </select>
                @error('refrigerated')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div id="attributes_container">
            </div>
            {{-- @foreach ($attributes as $attribute)
                <div class="form-group">
                    <label for="">{{ $attribute->name }}</label>
                    <select name="attributes_values[]" id="" class="form-control">
                        @foreach ($attribute->attributeValues as $value)
                            <option value="{{ $value->id }}">{{ $value->value }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach --}}
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Tạo hàng hóa</button></div>
                {{-- <div class="btn-cs btn-delete"><a href="{{ route('products.index') }}">Quay lại </a></div> --}}
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
