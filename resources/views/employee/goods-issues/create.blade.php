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

    {{-- <div class="content-10">
        <div class="search-result-container"> --}}
    {{-- <form action="" role="search">
                <div class="form-group input-div">
                    <input type="text" name="key" class="form-control search-input" placeholder="Nhập tên sản phẩm">
                    <div class="search-result" style="z-index:1;display:none">

                    </div>
                </div>
            </form> --}}
    {{-- <form action="{{ route('goodsissues.store') }}" method="POST">
                <div class="content-10" class="table"> --}}
    {{-- <h6>Chọn <span id="batch-product-name"></span> sản phẩm từ lô hàng </h6>
                    <table id="batch-table" class="table">
                        <thead>
                            <tr>
                                <th>Số lô</th>
                                <th>Ngày sản xuất</th>
                                <th>Ngày hết hạn</th>
                                <th>Số lượng có sẵn</th>
                                <th>Số lượng chọn</th>
                                <th>Xuất từ kho</th>
                            </tr>
                        </thead>
                        <tbody id="batch-tbody">
                        </tbody>
                    </table> --}}
    {{-- </div>
                <input type="hidden" value="{{ $locationId }}" class="user_location">


        </div>
    </div> --}}



    <div class="content-10">
        @csrf

        <div class="form-group input-div">
            <h4>Mã phiếu xuất </h4>
            <input type="text" name="code" value="{{ old('code') ?? $newCode }}" id="code" class="form-control"
                readonly>
            @error('code')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4> Tên Khách hàng</h4>
            <input type="hidden" name="customer_id" value="{{ old('customer_id') ?? $user->id }}" id="customer_id"
                class="form-control" readonly>
            {{ $user->name }}
            @error('customer_id')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4> Số điện thoại </h4>
            <input type="hidden" name="customer_phone" value="{{ old('customer_phone') ?? $user->phone }}"
                id="customer_phone" class="form-control" readonly>
            {{ $user->phone }}
            @error('customer_phone')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4> Địa chỉ</h4>
            <input type="hidden" name="customer_address" value="{{ old('customer_address') ?? $user->id }}"
                id="customer_address" class="form-control" readonly>
            @if ($user->location)
                @if ($user->location->street_address)
                    {{ $user->location->street_address }}-
                @endif
                {{ $user->location->ward }}-{{ $user->location->district }}-{{ $user->location->city }}
            @endif
            @error('customer_address')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="content-10">
        <h1>Giỏ hàng</h1>

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
                        <th>Thành tiền</th>
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
                            <td>{{ number_format($product->selling_price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                <input type="number" class="form-control" value="{{ $product->discount }}" min="0"
                                    step="1" id="discount-{{ $product->id }}"
                                    name="discount-{{ $product->id }}" />
                            </td>
                            <td>
                                <span id="total-{{ $product->id }}">
                                    {{ number_format(($product->selling_price - $product->discount) * 1, 0, ',', '.') }}
                                    VNĐ
                                </span>
                            </td>
                            <td>
                                <form
                                    action="
                                {{ route('cart.remove', $product->id) }}
                                 "
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group input-div text-end">
                <h4>Tổng tiền hàng</h4>
                <input type="number" name="total_amount" value="{{ old('total_amount') }}" class="total_amount"
                    class="form-control">
                @error('total_amount')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-end">
                <form action="{{ route('cart.storeOrder') }}" method="POST" id="orderForm">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customerId }}">
                    <input type="hidden" name="cartData" id="cartData">
                    <button type="submit" class="btn btn-success order-btn">Dặt hàng</button>
                </form>
            </div>
        @else
            <p>Giỏ hàng của bạn đang trống.</p>
        @endif
    </div>
    {{-- <div class="content-10">
        <div class="form-group input-div">
            <h4>Tổng tiền hàng</h4>
            <input type="number" name="total_amount" value="{{ old('total_amount') }}" class="total_amount"
                class="form-control">
            @error('total_amount')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="btn-controls">
            <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
            <div class="btn-cs btn-delete"><a href="{{ route('goodsissues.index') }}">Quay lại </a></div>
        </div>
        </form>
    </div> --}}

@endsection

@push('js')
    <script>
        @foreach ($products as $product)
            document.getElementById('quantity-{{ $product->id }}').addEventListener('input', function() {
                updateTotal({{ $product->id }});
            });

            document.getElementById('discount-{{ $product->id }}').addEventListener('input', function() {
                updateTotal({{ $product->id }});
            });
        @endforeach

        function updateTotal(productId) {
            var quantity = document.getElementById('quantity-' + productId).value;
            var discount = document.getElementById('discount-' + productId).value;
            var price = {{ $product->selling_price }};
            var total = (price - discount) * quantity;

            document.getElementById('total-' + productId).innerText = formatCurrency(total) + ' VNĐ';
        }

        function formatCurrency(value) {
            return value.toLocaleString('vi-VN');
        }
        //xu ly kq tim kiem
        $(document).ready(function() {
            $(document).on("click", ".search-input", function(e) {
                let _text = $(this).val();
                if (_text.length > 0) {
                    $(".search-result").css("display", "block");
                }
            })
        })



        //goi ham tim kiem
        $(document).on("input", ".search-input", function() {
            var _text = $(this).val();
            if (_text.length > 0) {
                $.ajax({
                    url: "{{ route('ajax-search-product') }}",
                    type: "GET",
                    data: {
                        key: _text
                    },
                    success: function(res) {
                        $(".search-result").html(res).css("display", "block");
                    }
                })
            } else {
                $(".search-result").css("display", "none");
            }
        });
        //xu ly kq tim kiem
        $(document).on("click", function(e) {
            if (!$(e.target).closest(".search-result-container").length) {
                $(".search-result").css("display", "none");
            }
        });

        //xu ly du lieu gui form
        document.addEventListener("DOMContentLoaded", function() {
            const checkoutButton = document.querySelector(".order-btn");
            checkoutButton.addEventListener("click", function(e) {
                e.preventDefault();
                const cartItems = [];
                @foreach ($products as $product)
                    let quantityItem_{{ $product->id }} = document.getElementById(
                        `quantity-{{ $product->id }}`).value;
                    let discountItem_{{ $product->id }} = document.getElementById(
                        `discount-{{ $product->id }}`).value;
                    cartItems.push({
                        product_id: {{ $product->id }},
                        quantity: quantityItem_{{ $product->id }},
                        unit_price: {{ $product->selling_price }},
                        discount: discountItem_{{ $product->id }} || 0,
                    })
                @endforeach
                document.getElementById('cartData').value = JSON.stringify(cartItems);
                document.getElementById('orderForm').submit();
            })


        })

        //Ham cap nhat gia tri total-price
        function updateTotalPrice(row) {
            let quantity = parseFloat(row.find(".quantity").val());
            let unitPrice = parseFloat(row.find(".unit-price").val());
            let discount = parseFloat(row.find(".discount").val());
            let totalPrice = quantity * unitPrice - discount;
            row.find(".total-price").val(totalPrice);
        }
        //Cap nhat gia tri total-price
        $("#product-table").on("input", ".quantity,.unit-price,.discount", function() {
            updateTotalPrice($(this).closest("tr"));
        })

        //Ham cap nhat total_amount
        function updateTotalAmount() {
            let sum = 0;
            $("#product-table tr").each(function() {
                let quantity = parseFloat($(this).find(".quantity").val());
                let unitPrice = parseFloat($(this).find(".unit-price").val());
                let discount = parseFloat($(this).find(".discount").val());
                let totalPrice = quantity * unitPrice - discount;
                if (!isNaN(totalPrice)) {
                    sum += totalPrice;
                }
            })
            $(".total_amount").val(sum);
        }

        //Cap nhat total-amount
        $("#product-table").on("input", ".quantity,.unit-price,.discount,.total-price", function() {
            updateTotalAmount();
        })

        //Ham cap nhat amount_due
        function updateAmountDue() {
            let totalAmount = parseFloat($(".total_amount").val()) || 0;
            let totalDiscount = parseFloat($(".total_discount").val()) || 0;
            let amountDue = totalAmount - totalDiscount;
            $(".amount_due").val(amountDue.toFixed(2));
        }

        //Cap nhat amount_due
        $(document).on("input", ".total_amount,.total_discount", function() {
            updateAmountDue();
        })

        $("#product-table").on("click", ".remove-product", function() {
            $(this).closest("tr").remove();
            updateTotalAmount();
        })
    </script>
@endpush

@push('css')
    <style>
        .search-result-container .form-group {
            position: relative;
        }

        .search-result {
            z-index: 1000;
            display: none;
            position: absolute;
            display: block;
            min-width: 500px;
            background: var(--color-white);
            border: 1px solid var(--color-default-light);
        }

        .search-result-item {
            border-bottom: 1px solid black;
            cursor: pointer;
            padding: 10px 5px;
            display: flex;
            align-items: center;
        }

        .search-result-item h4,
        h6 {
            margin: 0;
            font-size: 21px;
        }

        .search-result-item p {
            margin: 0;
        }

        .search-result-item img {
            width: 50px;
            margin-right: 15px;
        }

        .search-result-container table {
            margin: 40px 0;
        }

        tr th,
        tr td {
            text-align: center;
        }
    </style>
@endpush
