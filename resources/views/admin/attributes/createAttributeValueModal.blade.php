<div class="modal modal-cs fade" id="addAttributeValueModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm giá trị</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                    <img src="{{ asset('img/x.png') }}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <div class="content-10">
                    <form action="{{ route('attributes.storeValue', ['attribute' => $attribute->id]) }}" method="POST">
                        @csrf
                        <div class="form-group input-div">
                            <h4>Nhập tên giá trị </h4>
                            <input type="text" name="value" value="{{ old('name') }}" id="name"
                                class="form-control">
                            @error('name')
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
