<div class="modal modal-cs fade" id="addAttributeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm thuộc tính</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                    <img src="{{ asset('img/x.png') }}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <div class="content-10">
                    <form action="{{ route('attributes.store') }}" method="POST">
                        @csrf
                        <div class="form-group input-div">
                            <h4>Nhập tên nhóm</h4>
                            <input type="text" name="name" value="{{ old('name') }}" id="name"
                                class="form-control">
                            @error('name')
                                <div class="error message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group input-div">
                            <h4>Chọn danh mục</h4>
                            <select name="category_id" id="">
                                <option value="">---Chọn danh mục---</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="error message">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Lưu thay đổi </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
            </div>
            </form>
        </div>
    </div>
</div>
