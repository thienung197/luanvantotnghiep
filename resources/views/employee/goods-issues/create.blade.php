@extends('layouts.app')
@section('title', 'Đặt hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Đặt hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('goodsissues.create') }}">Đặt hàng</a>
            </p>
        </div>
    </div>


    <div class="content-10">
        <h3>Thông tin của khách hàng</h3>
        <h6><span>Tên khách hàng:</span> {{ $user->name }}</h6>
        <h6><span>Số điện thoại:</span> {{ $user->phone }}</h6>
        <h6><span>Địa chỉ:</span>
            @if ($user->location)
                @if ($user->location->street_address)
                    {{ $user->location->street_address }},
                @endif
                {{ $user->location->ward }}, {{ $user->location->district }}, {{ $user->location->city }}
            @endif
        </h6>
    </div>
    <div class="content-10">
        <h3>Giỏ hàng</h3>

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($products->count() > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Mã hàng</th>
                        <th>Tên hàng</th>
                        <th>Số lượng</th>
                        <th>Giá bán</th>
                        <th>Giảm giá</th>
                        <th style="min-width: 260px !important;text-align:center">Thành tiền </th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png') }}"
                                    width="50">
                            </td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <input type="number" class="form-control" value="1" min="1"
                                    id="quantity-{{ $product->id }}">
                            </td>
                            <td>
                                <input type="number" class="form-control" id="price-{{ $product->id }}"
                                    value="{{ $product->selling_price }}">
                            </td>
                            <td>
                                <input type="number" class="form-control" value="{{ $product->discount ?? 0 }}"
                                    min="0" step="1" id="discount-{{ $product->id }}"
                                    name="discount-{{ $product->id }}">
                            </td>
                            <td style="text-align: center">
                                <span id="total-{{ $product->id }}">
                                    {{ ($product->selling_price - $product->discount) * 1 }}
                                    VNĐ
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('cart.remove', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total-amount-container">
                <span></span>
                <span id="total-amount">Tổng tiền hàng: </span>
            </div>
            <div class="button-submit-container">
                <span></span>
                <form action="{{ route('cart.storeOrder') }}" method="POST" id="orderForm">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customerId }}">
                    <input type="hidden" name="cartData" id="cartData">
                    <button type="submit" class="btn btn-success order-btn">Đặt hàng</button>
                </form>
            </div>
        @else
            <p class="cart-out">Giỏ hàng của bạn đang trống.</p>
        @endif
    </div>
@endsection

@push('js')
    <script>
        function formatCurrency(value) {
            return value.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }


        updateTotalAmount();

        function updateTotal(productId) {
            let quantity = parseFloat(document.getElementById('quantity-' + productId).value);
            let price = parseFloat(document.getElementById('price-' + productId).value.replace(/[^0-9.-]+/g, ""));
            let discount = parseFloat(document.getElementById('discount-' + productId).value);

            if (isNaN(quantity)) quantity = 1;
            if (isNaN(price)) price = 0;
            if (isNaN(discount)) discount = 0;

            let total = (price - discount) * quantity;

            document.getElementById('total-' + productId).innerText = total + ' VNĐ';

            updateTotalAmount();
        }

        function updateTotalAmount() {
            let totalAmount =
                0;
            @foreach ($products as $product)
                {
                    let productTotal = parseFloat(document.getElementById('total-{{ $product->id }}').innerText.replace(
                        /[^0-9.-]+/g, ""));
                    totalAmount += productTotal;
                }
            @endforeach
            console.log(totalAmount);
            document.getElementById('total-amount').innerText = 'Tổng tiền hàng: ' + totalAmount + ' VNĐ';
        }

        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($products as $product)
                document.getElementById('quantity-{{ $product->id }}').addEventListener('input', function() {
                    updateTotal({{ $product->id }});
                });
                document.getElementById('price-{{ $product->id }}').addEventListener('input', function() {
                    updateTotal({{ $product->id }});
                });
                document.getElementById('discount-{{ $product->id }}').addEventListener('input', function() {
                    updateTotal({{ $product->id }});
                });
            @endforeach

            // Xử lý dữ liệu gửi form
            const checkoutButton = document.querySelector(".order-btn");
            checkoutButton.addEventListener("click", function(e) {
                e.preventDefault();
                const cartItems = [];
                @foreach ($products as $product)
                    {
                        let quantityItem = document.getElementById(`quantity-{{ $product->id }}`)
                            .value;
                        let discountItem = document.getElementById(`discount-{{ $product->id }}`)
                            .value;
                        cartItems.push({
                            product_id: {{ $product->id }},
                            quantity: quantityItem,
                            unit_price: {{ $product->selling_price }},
                            discount: discountItem || 0,
                        });
                    }
                @endforeach
                document.getElementById('cartData').value = JSON.stringify(cartItems);
                document.getElementById('orderForm').submit();
            });
        });
    </script>
@endpush


@push('css')
    <style>
        tr th {
            text-align: center;
        }

        .total-amount-container,
        .button-submit-container {
            display: flex;
        }

        .total-amount-container span:first-child {
            width: 70%;
        }

        .button-submit-container span {
            width: 90%;
        }

        .button-submit-container form {
            margin: 20px 0;
        }

        .button-submit-container form button {
            padding: 10px 17px;
            font-size: 20px;
        }

        .total-amount-container span:last-child {
            color: var(--color-black);
            font-size: 22px;
            font-style: italic;
            font-weight: 600;
        }

        .cart-out {
            padding: 100px;
            text-align: center;
        }

        input[type="number"] {
            text-align: end;
        }
    </style>
@endpush
