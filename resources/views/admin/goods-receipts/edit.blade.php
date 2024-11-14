@extends('layouts.app')
@section('title', 'Khách hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Cập nhật khách hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('customers.index') }}">Khách hàng</a> > <a href="">Cập
                    nhật khách hàng</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST" id="form-{{ $customer->id }}">
            @csrf
            @method('PUT')
            <div class="form-group input-div">
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') ?? $customer->name }}" id="name"
                    class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Giới tính</h4>
                <select name="gender" id="" class="form-control">
                    <option value="">---Chọn giới tính---</option>
                    <option value="male" {{ (old('gender') ?? $customer->gender) == 'male' ? 'selected' : '' }}>Nam
                    </option>
                    <option value="female" {{ (old('gender') ?? $customer->gender) == 'female' ? 'selected' : '' }}>Nữ
                    </option>
                </select>
                @error('gender')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Số điện thoại</h4>
                <input type="text" name="phone" value="{{ old('phone') ?? $customer->phone }}" id="phone"
                    class="form-control">
                @error('phone')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Địa chỉ </h4>
                <input type="text" name="address" value="{{ old('address') ?? $customer->address }}" id="address"
                    class="form-control">
                @error('address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit" data-id="{{ $customer->id }}">Lưu thay đổi</button>
                </div>
                <div class="btn-cs btn-delete"><a href="{{ route('customers.index') }}">Quay lại </a></div>
            </div>

        </form>
    </div>

@endsection
