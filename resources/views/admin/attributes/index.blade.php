@extends('layouts.app')
@section('title', 'Thuộc tính')

@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý thuộc tính
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Thuộc tính</a></p>
        </div>
    </div>
    <div>
        {{-- <a href="{{ route('attributes.create') }}">Thêm thuộc tính</a> --}}

        @include('admin.attributes.createAttributeModal')
        @include('admin.attributes.editAttributeModal')

        <div class="table_container">
            <div class="table_title">
                Danh sách thuộc tính
                <div class="btn-cs btn-add">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addAttributeModal">
                        Thêm thuộc tính
                    </button>
                </div>
            </div>
            <div class="table_filter-controls">
                <form action="{{ route('attributes.index') }}" method="GET">
                    {{-- <label for="">Hiển thị </label>
                    <select name="entries" id="entries" onchange="this.form.submit()">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                    </select>
                    mục --}}
                </form>
                <div class="table_search-box">
                    {{-- <form action="{{ route('attributes.index') }}" method="GET">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Nhập tên thuộc tính">
                        <button type="submit">Tìm </button>
                    </form> --}}
                </div>
            </div>
            <table class="table" id="table-list">
                <tr>
                    {{-- <th>STT</th> --}}
                    <th>Tên thuộc tính </th>
                    <th>Số thuộc tính </th>
                    <th>Thao tác</th>
                </tr>
                @php
                    $stt = ($attributes->currentPage() - 1) * $attributes->perPage() + 1;
                @endphp
                @foreach ($attributes as $attribute)
                    <tr>
                        {{-- <td>{{ $stt++ }}</td> --}}
                        <td>{{ $attribute->name }}</td>
                        <td>{{ $attribute->attributeValues->count() }}</td>
                        <td class="btn-cell">
                            <a href="{{ route('attributes.edit', $attribute->id) }}">
                                <img src="{{ asset('img/plus.png') }}" alt="">
                            </a>
                            <a data-bs-toggle="modal" data-bs-target="#editAttributeModal" class="btn-edit"
                                data-id="{{ $attribute->id }}"><img src="{{ asset('img/edit.png') }}" alt=""></a>

                            <form action="{{ route('attributes.destroy', $attribute->id) }}"
                                id="form-delete{{ $attribute->id }}" method="POST">
                                @csrf
                                @method('delete')
                            </form>
                            <button type="submit" class="btn-delete" data-id="{{ $attribute->id }}"><img
                                    src="{{ asset('img/delete.png') }}" alt=""></button>

                        </td>
                    </tr>
                @endforeach

            </table>
            {{ $attributes->links() }}
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
                $(document).on("click", ".btn-edit", function() {
                    let id = $(this).data("id");
                    $("#editAttributeModal").modal("show");
                    $.ajax({
                        type: "GET",
                        url: "/edit-attribute/" + id,
                        success: function(response) {
                            $("#attributeName").val(response.attribute.name);
                            $("#attributeCategoryId").val(response.attribute.category_id);
                            // $("#attributeId").val(response.attribute.id);
                            $("#editAttributeForm").attr("action", '/attributes/update/' + response
                                .attribute.id);
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
