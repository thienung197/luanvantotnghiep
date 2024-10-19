@extends('layouts.app')
@section('title', 'Nhà cung cấp')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm nhà cung cấp
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('providers.index') }}">Nhà cung cấp</a> > <a href="">Thêm
                    nhà cung cấp</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('providers.store') }}" method="POST">
            @csrf

            <div class="form-group input-div">
                <h4>Tên nhà cung cấp</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Số điện thoại</h4>
                <input type="text" name="phone" value="{{ old('phone') }}" id="phone" class="form-control">
                @error('phone')
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
                <h4>Email</h4>
                <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control">
                @error('email')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-delete"><a href="{{ route('providers.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection
