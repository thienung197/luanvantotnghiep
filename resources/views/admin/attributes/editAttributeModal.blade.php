<div class="modal modal-cs fade" id="editAttributeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cập nhật thuộc tính</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/x.png') }}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <div class="content-10">
                    <form method="POST" id="editAttributeForm">
                        @csrf
                        {{-- <input type="hidden" id="attributeId" name="attributeId"> --}}
                        <div class="form-group input-div">
                            <h4>Nhập tên nhóm</h4>
                            <input type="text" value="{{ old('name') }}" id="attributeName" class="form-control"
                                name="name">
                            @error('name')
                                <div class="error message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group input-div">
                            <h4>Chọn danh mục</h4>
                            <select name="category_id" id="attributeCategoryId">
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
