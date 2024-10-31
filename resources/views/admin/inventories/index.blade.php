@extends('layouts.app')
@section('title', 'Hàng tồn kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý hàng tồn kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('inventories.index') }}">Hàng tồn kho</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <div class="warehouse-tabs">
            @foreach ($warehouses as $warehouse)
                <button class="tab" onclick="showWarehouseDetails({{ $warehouse->id }})">{{ $warehouse->name }}</button>
            @endforeach
        </div>
        <div class="warehouse-details" id="warehouse-details">
            <div class="table_container">
                <div class="table_title">
                    Hàng tồn kho
                </div>
                <table class="table table-list" id="inventory-table">
                    <thead>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Tồn kho</th>
                            <th>Giá bán</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function showWarehouseDetails(warehouseId) {
            $.ajax({
                url: '/warehouse/details/' + warehouseId,
                method: "GET",
                success: function(res) {
                    $("#inventory-table tbody").empty();

                    if (res.products && res.products.length > 0) {
                        res.products.forEach(product => {
                            $("#inventory-table tbody").append(`<tr>
                        <td>${product.code}</td>
                        <td>${product.name}</td>
                        <td>${product.quantity_available}</td>
                        <td>${product.price}</td>
                    </tr>`);
                        });
                    } else {
                        $("#inventory-table tbody").html(
                            "<tr><td colspan='4'>Không có sản phẩm nào trong kho!</td></tr>");
                    }
                },
                error: function() {
                    $("#inventory-table tbody").html(
                        "<tr><td colspan='4'>Không có dữ liệu hàng tồn kho!</td></tr>");
                }
            });
        }
    </script>
@endpush

@push('css')
    <style>
        .warehouse-tabs {
            display: flex;
            border-bottom: 2px solid #ccc;
            margin-bottom: 10px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
        }

        .tab:hover {
            background-color: #f1f1f1;
        }

        .warehouse-details {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
@endpush
