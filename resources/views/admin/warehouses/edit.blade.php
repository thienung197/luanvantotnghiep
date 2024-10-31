@extends('layouts.app')
@section('title', 'Nhà kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Chỉnh sửa nhà kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('warehouses.index') }}">Nhà kho</a> > <a href="">Chỉnh
                    sửa nhà kho</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST" id="form-{{ $warehouse->id }}">
            @csrf
            @method('PUT')
            <div class="form-group input-div">
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') ?? $warehouse->name }}" id="name"
                    class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Sức chứa</h4>
                <input type="number" name="capacity" value="{{ old('capacity') ?? $warehouse->capacity }}" id="capacity"
                    class="form-control">
                @error('capacity')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Diện tích</h4>
                <input type="number" name="size" value="{{ old('size') ?? $warehouse->size }}" id="size"
                    class="form-control">
                @error('size')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Bảo quản lạnh</h4>
                <select name="isRefrigerated" id="" class="form-control">
                    <option value="">--- Chọn ---</option>
                    <option value="1"
                        {{ (old('isRefrigerated') ?? $warehouse->isRefrigerated) == '1' ? 'selected' : '' }}>
                        Có</option>
                    <option value="0"
                        {{ (old('isRefrigerated') ?? $warehouse->isRefrigerated) == '0' ? 'selected' : '' }}>Không</option>
                </select>
                @error('isRefrigerated')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Chọn địa chỉ</h4>
                <select id='provinces' onchange='getProvinces(event)'>
                    <option value=''>-- Chọn tỉnh / thành phố --</option>
                </select>
                <select id='districts' onchange='getDistricts(event)'>
                    <option value=''>-- Chọn quận / huyện --</option>
                </select>
                <select id='wards'>
                    <option value=''>-- Chọn phường / xã --</option>
                </select>
            </div>
            <div class="form-group input-div">
                <h4>Địa chỉ cụ thể</h4>
                <input type="text" name="street_address"
                    value="{{ old('street_address') ?? $warehouse->location->street_address }}" id="street_address"
                    class="form-control">
                @error('street_address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="province" id="province_name">
            <input type="hidden" name="district" id="district_name">
            <input type="hidden" name="ward" id="ward_name">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit" data-id="{{ $warehouse->id }}">Lưu thay đổi</button>
                </div>
                <div class="btn-cs btn-back"><a href="{{ route('warehouses.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection
