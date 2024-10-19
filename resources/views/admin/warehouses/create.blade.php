@extends('layouts.app')
@section('title', 'Nhà kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm nhà kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('warehouses.index') }}">Nhà kho</a> > <a href="">Thêm nhà
                    kho</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf

            <div class="form-group input-div">
                <h4>Tên nhà kho</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Địa chỉ </h4>
                <input type="text" name="address" value="{{ old('address') }}" id="address" class="form-control">
                @error('address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Sức chứa</h4>
                <input type="number" name="capacity" value="{{ old('capacity') }}" id="capacity" class="form-control">
                @error('capacity')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Diện tích</h4>
                <input type="number" name="size" value="{{ old('size') }}" id="size" class="form-control">
                @error('size')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Bảo quản lạnh</h4>
                <select name="isRefrigerated" id="" class="form-control">
                    <option value="">--- Chọn ---</option>
                    <option value="1" {{ old('isRefrigerated') == '1' ? 'selected' : '' }}>Có</option>
                    <option value="0" {{ old('isRefrigerated') == '0' ? 'selected' : '' }}>Không</option>
                </select>
                @error('isRefrigerated')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-delete"><a href="{{ route('warehouses.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection
