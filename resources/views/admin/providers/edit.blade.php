@extends('layouts.app')
@section('title', 'Nhà cung cấp')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Chỉnh sửa nhà cung cấp
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('providers.index') }}">Nhà cung cấp</a> > <a href="">Chỉnh
                    sửa nhà cung cấp</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('providers.update', $provider->id) }}" method="POST" id="form-{{ $provider->id }}">
            @csrf
            @method('PUT')
            <div class="form-group input-div">
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') ?? $provider->name }}" id="name"
                    class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Số điện thoại</h4>
                <input type="text" name="phone" value="{{ old('phone') ?? $provider->phone }}" id="phone"
                    class="form-control">
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Email</h4>
                <input type="email" name="email" value="{{ old('email') ?? $provider->email }}" id="email"
                    class="form-control">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
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
                    value="{{ old('street_address') ?? $provider->location->street_address }}" id="street_address"
                    class="form-control">
                @error('street_address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit" data-id="{{ $provider->id }}">Lưu thay đổi</button>

                </div>
                <div class="btn-cs btn-delete"><a href="{{ route('providers.index') }}">Quay lại </a></div>

            </div>
        </form>
    </div>

@endsection
