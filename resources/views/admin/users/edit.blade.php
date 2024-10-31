@extends('layouts.app')
@section('title', 'Chỉnh sửa người dùng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Chỉnh sửa người dùng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('users.index') }}">Người dùng</a> > <a href="">Chỉnh sửa
                    người dùng</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group input-div">
                <input type="file" accept="image/*" class="form-control" name="image">
                <div class="show-image">
                    <img src="{{ $user->images->count() > 0 ? asset('upload/' . $user->images->first()->url) : asset('upload/users/man.png') }}"
                        alt="">
                </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') ?? $user->name }}" id="name"
                    class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Giới tính</h4>
                <select name="gender" class="form-control">
                    <option value="">---Chọn giới tính---</option>
                    <option value="male" {{ (old('gender') ?? $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ (old('gender') ?? $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                </select>
                @error('gender')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Ngày sinh</h4>
                <input type="date" name="birth_date" class="form-control"
                    value="{{ old('birth_date') ?? $user->birth_date }}">
                @error('date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Số điện thoại</h4>
                <input type="text" name="phone" value="{{ old('phone') ?? $user->phone }}" id="phone"
                    class="form-control">
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Địa chỉ </h4>
                <input type="text" name="address" value="{{ old('address') ?? $user->address }}" id="address"
                    class="form-control">
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Email</h4>
                <input type="email" name="email" value="{{ old('email') ?? $user->email }}" id="email"
                    class="form-control">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Mật khẩu</h4>
                <input type="password" name="password" value="{{ old('password') }}" id="password" class="form-control">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Vai trò</h4>
                <div class="row">
                    <div class="checkbox-container">
                        @foreach ($roles as $role)
                            <label for="">
                                <input type="checkbox" name="role_ids[]" class="form-check-input"
                                    value="{{ $role->id }}"
                                    {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}>
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
