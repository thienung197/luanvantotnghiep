@extends('layouts.app')
@section('title', 'Thuộc tính')

@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý giá trị thuộc tính
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('attributes.index') }}">Thuộc tính</a> > <a href="">Quản
                    lý giá trị thuộc tính</a>
            </p>
        </div>
    </div>
    <div class="content_header">
        <div class="content_header--title">
            Tên thuộc tính:
            <span>{{ $attribute->name }}</span>
        </div>
    </div>
    <div>
        {{-- <a href="{{ route('attributes.create') }}">Thêm thuộc tính</a> --}}
        <div class="btn-cs btn-add">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttributeValueModal">
                Thêm giá trị
            </button>
        </div>
        @include('admin.attributes.createAttributeValueModal')
        <div class="table_container">
            <div class="table_title">
                Danh sách giá trị thuộc tính
            </div>
            <div class="table_filter-controls">
                <form action="{{ route('attributes.index') }}" method="GET">
                    <label for="">Hiển thị </label>
                    <select name="entries" id="entries" onchange="this.form.submit()">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                    </select>
                    mục
                </form>
                <div class="table_search-box">
                    <form action="{{ route('attributes.index') }}" method="GET">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Nhập tên thuộc tính">
                        <button type="submit">Tìm </button>
                    </form>
                </div>
            </div>
            <input type="hidden" name="" id="attributeId" value="{{ $attributeId }}">
            <table class="table" id="table-list">
                <tr>
                    {{-- <th>STT</th> --}}
                    <th>Giá trị thuộc tính </th>
                    <th>Thao tác</th>
                </tr>
                @php
                    // $stt = ($attributeValues->currentPage() - 1) * $attributeValues->perPage() + 1;
                @endphp
                @foreach ($attributeValues as $attributeValue)
                    <tr>
                        {{-- <td>{{ $stt++ }}</td> --}}
                        <td>{{ $attributeValue->value }}</td>
                        <td class="btn-cell">
                            <a data-bs-toggle="modal" data-bs-target="#editAttributeValueModal" class="btn-edit"
                                data-id="{{ $attributeValue->id }}"><img src="{{ asset('img/edit.png') }}"
                                    alt=""></a>
                            @include('admin.attributes.editAttributeValueModal')

                            <form
                                action="{{ route('attributes.destroyValues', ['attribute' => $attribute->id, 'value' => $attributeValue->id]) }}"
                                id="form-delete{{ $attributeValue->id }}" method="POST">
                                @csrf
                                @method('delete')
                            </form>
                            <button type="submit" class="btn-delete" data-id="{{ $attributeValue->id }}"><img
                                    src="{{ asset('img/delete.png') }}" alt=""></button>

                        </td>
                    </tr>
                @endforeach

            </table>
            {{-- {{ $attributeValues->links() }} --}}
        </div>

    @endsection
    @push('js')
        <script>
            @if (Session::has('message'))
                toastr.success("{{ Session::get('message') }}");
            @endif
        </script>
        <script>
            $(document).ready(function() {
                let attributeId = document.querySelector("#attributeId").value;
                $(document).on("click", ".btn-edit", function() {
                    let id = $(this).data("id");
                    $("#editAttributeModal").modal("show");
                    $.ajax({
                        type: "GET",
                        url: "/attribute/" + attributeId + "/edit/" + id,
                        success: function(response) {
                            $("#attributeValueName").val(response.attributeValue.value);

                            $("#editAttributeValueForm").attr("action", '/attribute/' +
                                attributeId + '/update/' +
                                response
                                .attributeValue.id);
                        }
                    })
                })
            })
        </script>
    @endpush

    @push('css')
        <style>
            .btn-cell a:first-child img {
                width: 48px;
                height: 48px;
            }
        </style>
    @endpush
