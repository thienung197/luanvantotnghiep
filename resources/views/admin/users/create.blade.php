@extends('layouts.app')
@section('title', 'Thêm người dùng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm người dùng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('users.index') }}">Người dùng</a> > <a href="">Thêm
                    người dùng</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
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
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Giới tính</h4>
                <select name="gender" id="" class="form-control">
                    <option value="">---Chọn giới tính---</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                </select>
                @error('gender')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Ngày sinh</h4>
                <input type="date" name="birth_date" id="" class="form-control" value="{{ old('birth_date') }}">
                @error('birth_date')
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
            <div class="form-group input-div">
                <h4>Mật khẩu</h4>
                <input type="password" name="password" value="{{ old('password') }}" id="password" class="form-control">
                @error('password')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4 for="">Vai trò</h4>
                <div class="row">
                    <div class="checkbox-container">
                        @foreach ($roles as $role)
                            <label for="">
                                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-back"><a href="{{ route('users.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection
