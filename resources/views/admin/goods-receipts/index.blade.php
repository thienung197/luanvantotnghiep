@extends('layouts.app')
@section('title', 'Phiếu nhập hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu nhập hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu nhập hàng</a></p>
        </div>
    </div>

    <div class="table_container" style="min-height:400px">
        <div class="table_title">
            Danh sách phiếu nhập hàng
        </div>
        <p>Danh sách các sản phẩm được yêu cầu nhập hàng, phân loại theo nhà cung cấp</p>
        @foreach ($approvedProducts as $provider)
            <h3 class="provider-name">Tên nhà cung cấp:{{ $provider['provider_name'] }}</h3>
            <div class="suggested-products-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Đơn vị tính</th>
                            <th>Tổng số lượng đặt</th>
                            <th>Yêu cầu nhập từ nhà kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{ route('goodsreceipts.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider_id" value="{{ $provider['provider_id'] }}">
                            @foreach ($provider['products'] as $product)
                                <tr>
                                    <td style="display: none">{{ $product['id'] }}</td>
                                    <td>{{ $product['code'] }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['unit'] }}</td>
                                    <td>{{ $product['totalQuantity'] }}</td>
                                    <td>
                                        @if (!empty($product['restock_details']))
                                            @foreach ($product['restock_details'] as $detail)
                                                <li>
                                                    <strong>Nhà kho: </strong> {{ $detail['warehouse_name'] }}<br />
                                                    <strong>Số lượng yêu cầu: </strong> {{ $detail['quantity'] }}
                                                </li>
                                            @endforeach
                                        @else
                                        @endif
                                    </td>
                                    <td>
                                        <input type="hidden" name="products[{{ $product['id'] }}][product_id]"
                                            value="{{ $product['id'] }}">
                                        <input type="hidden" name="products[{{ $product['id'] }}][product_code]"
                                            value="{{ $product['code'] }}">
                                        <input type="hidden" name="products[{{ $product['id'] }}][product_name]"
                                            value="{{ $product['name'] }}">
                                        <input type="hidden" name="products[{{ $product['id'] }}][product_unit]"
                                            value="{{ $product['unit'] }}">
                                        <input type="hidden" name="products[{{ $product['id'] }}][totalQuantity]"
                                            value="{{ $product['totalQuantity'] }}">
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>


            <button type="submit" class="btn btn-primary">Đặt hàng</button>
            </form>
        @endforeach

    @endsection

    @push('css')
        <style>
            .provider-name {
                color: #000;
                text-align: center;
                margin: 17px 0;
                font-weight: 500;
            }
        </style>
    @endpush

    @push('js')
        <script>
            @if (Session::has('message'))
                toastr.success("{{ Session::get('message') }}");
            @endif
        </script>
    @endpush
