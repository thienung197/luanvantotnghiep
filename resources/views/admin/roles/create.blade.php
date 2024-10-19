@extends('layouts.app')
@section('title', 'Roles')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm nhóm vai trò
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('roles.index') }}">Nhóm vai trò</a> > <a href="">Thêm
                    nhóm</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group input-div">
                <h4>Nhập tên nhóm</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <h4 for="">Quyền hạn</h4>
                <div class="row">
                    @foreach ($permissions as $groupName => $permission)
                        <div class="col-md-3">
                            <div class="form-check lst-group-header">
                                <input type="checkbox" class="form-check-input group-checkbox"
                                    id="group-{{ $loop->index }}">
                                <h5 for="group-{{ $loop->index }}" class="custom-control-label">{{ $groupName }}</h5>
                            </div>
                            @foreach ($permission as $item)
                                <div class="form-check lst-group-content">
                                    <input type="checkbox" class="form-check-input group-{{ $loop->parent->index }}"
                                        name="permission_ids[]" value="{{ $item->id }}">
                                    <label for="" class="custom-control-lable">{{ $item->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-delete"><a href="{{ route('roles.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection
