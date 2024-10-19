@extends('layouts.app')
@section('title', 'Chỉnh sửa danh mục')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Chỉnh sửa danh mục
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('categories.index') }}">Danh mục</a> > <a href="">Chỉnh
                    sửa danh mục</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group input-div">
                <h4>Tên danh mục</h4>
                <input type="text" name="name" value="{{ old('name') ?? $category->name }}" class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            @if ($category->children->count() == 0)
                <div class="form-group input-div">
                    <h4 for="">Danh mục cha</h4>
                    <div class="row">
                        <select name="parent_id" id="" class="form-control">
                            <option value="">--- Chọn danh mục cha ---</option>
                            @foreach ($parentCategories as $item)
                                <option value="{{ $item->id }}"
                                    {{ (old('parent_id') ?? $category->parent_id) == $item->id ? 'selected ' : '' }}>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            @endif

            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-delete"><a href="{{ route('roles.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection
