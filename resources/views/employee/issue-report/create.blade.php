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
        <div class="search-result-container">
            <form action="" role="search">
                <div class="form-group input-div">
                    <input type="text" name="key" class="form-control search-input" placeholder="Nhập tên sản phẩm"
                        data-warehouse-id="{{ $warehouseId }}">
                    <div class="search-result" style="z-index:1;display:none">

                    </div>
                </div>
            </form>
            <form action="{{ route('goodsissues.store') }}" method="POST">
                <div class="content-10" class="table">
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
                </div>
                <input type="hidden" value="{{ $locationId }}" class="user_location">
                <div id="product-table-container">

                </div>
                {{-- <table id="product-table" class="table ">
                    <thead>
                        <th>Mã đơn hàng</th>
                        <th>Mã hàng</th>
                        <th>Tên hàng</th>
                        <th>Số lượng</th>
                        <th>Giá bán</th>
                        <th>Giảm giá</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </thead>
                    <tbody id="body-product-table">

                    </tbody>
                </table> --}}
        </div>
    </div>



    <div class="btn-controls">
        <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
        <div class="btn-cs btn-delete"><a href="{{ route('goodsissues.index') }}">Quay lại </a></div>
    </div>
    </form>
    </div>

@endsection

@push('js')
    <script>
        //xu ly kq tim kiem
        $(document).ready(function() {
            $(document).on("click", ".search-input", function(e) {
                let _text = $(this).val();
                if (_text.length > 0) {
                    $(".search-result").css("display", "block");
                }
            })
        })

        //ajax xu ly kq tim kiem
        $(document).on("input", ".search-input", function() {
            let _text = $(this).val();
            let _warehouseId = $(this).data('warehouse-id');

            if (_text.length > 0) {
                $.ajax({
                    url: "{{ route('ajax-search-goods-issue-batch') }}",
                    type: "get",
                    data: {
                        key: _text,
                        warehouse_id: _warehouseId
                    },
                    success: function(res) {

                        $(".search-result").html(res).css("display", "block");
                    }
                })
            } else {
                $(".search-result").css("display", "none");
            }
        })

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
                console.log(cartItems);
                document.getElementById('cartData').value = JSON.stringify(cartItems);
                document.getElementById('orderForm').submit();
            })


        })


        let indexRow = 0;
        $(".search-result").on("click", ".search-result-item", function() {
            // Create the table element
            let productTable = document.createElement("table");
            productTable.id = "product-table";
            productTable.classList.add("table");

            // Create the thead element
            let thead = document.createElement("thead");

            // Column headers
            const headers = [
                "Mã đơn hàng",
                "Mã hàng",
                "Tên hàng",
                "Số lượng",
                "Giá bán",
                "Giảm giá",
                "Thành tiền",
                "Xóa"
            ];

            // Loop through headers to create each <th>
            headers.forEach(headerText => {
                let th = document.createElement("th");
                th.textContent = headerText;
                thead.appendChild(th);
            });

            // Append thead to the table
            productTable.appendChild(thead);

            // Create the tbody element with id 'body-product-table'
            let tbody = document.createElement("tbody");
            tbody.id = "body-product-table";

            let goodsIssueBatchCode = $(this).find(".goods-issue-batch-code").data('goods-issue-batch-code');
            let status = $(this).find(".status").data('status');
            let createdAt = $(this).find(".created-at").data('created_at');
            let productCode = $(this).find(".product-code").data('product-code');
            let productName = $(this).find(".product-name").data('product-name');
            let productQuantity = $(this).find(".product-quantity").data('product-quantity');
            let productSellingPrice = $(this).find(".product-selling-price").data('product-selling-price');
            let productDiscount = $(this).find(".product-discount").data('product-discount');

            let newRow = document.createElement("tr");

            let goodsIssueBatchCodeCell = document.createElement("td");
            let goodsIssueBatchCodeText = document.createTextNode(goodsIssueBatchCode);
            goodsIssueBatchCodeCell.appendChild(goodsIssueBatchCodeText);
            newRow.appendChild(goodsIssueBatchCodeCell);

            // let createdAtCell = document.createElement("td");
            // let createdAtText = document.createTextNode(createdAt);
            // createdAtCell.appendChild(createdAtText);
            // newRow.appendChild(createdAtCell);

            let productCodeCell = document.createElement("td");
            let productCodeText = document.createTextNode(productCode);
            productCodeCell.appendChild(productCodeText);
            newRow.appendChild(productCodeCell);

            let productNameCell = document.createElement("td");
            let productNameText = document.createTextNode(productName);
            productNameCell.appendChild(productNameText);
            newRow.appendChild(productNameCell);

            let productQuantityCell = document.createElement("td");
            let productQuantityText = document.createTextNode(productQuantity);
            productQuantityCell.appendChild(productQuantityText);
            newRow.appendChild(productQuantityCell);

            let productSellingPriceCell = document.createElement("td");
            let productSellingPriceText = document.createTextNode(productSellingPrice);
            productSellingPriceCell.appendChild(productSellingPriceText);
            newRow.appendChild(productSellingPriceCell);

            let productDiscountCell = document.createElement("td");
            let productDiscountText = document.createTextNode(productDiscount);
            productDiscountCell.appendChild(productDiscountText);
            newRow.appendChild(productDiscountCell);

            let totalPriceCell = document.createElement("td");
            let totalPriceText = document.createTextNode("0");
            totalPriceCell.appendChild(totalPriceText);
            newRow.appendChild(totalPriceCell);


            // let statusCell = document.createElement("td");
            // let statusText = document.createTextNode(status);
            // statusCell.appendChild(statusText);
            // newRow.appendChild(statusCell);

            let removeCell = document.createElement("td");
            let removeBtn = document.createElement("button");
            removeBtn.classList.add("remove-product");
            removeBtn.textContent = "Xóa";
            removeBtn.style.width = "100px";
            removeCell.appendChild(removeBtn);
            newRow.appendChild(removeCell);
            tbody.appendChild(newRow);
            productTable.appendChild(tbody);

            // document.querySelector("#body-product-table").appendChild(newRow);
            document.getElementById("product-table-container").appendChild(productTable);

            $(".search-result").css("display", "none");
            indexRow++;

        });
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
