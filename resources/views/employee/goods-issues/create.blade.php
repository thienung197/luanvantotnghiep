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
                    <input type="text" name="key" class="form-control search-input" placeholder="Nhập tên sản phẩm">
                    <div class="search-result" style="z-index:1;display:none">

                    </div>
                </div>
            </form>
            <form action="{{ route('goodsissues.store') }}" method="POST">
                <div class="content-10" class="table">
                    <h6>Chọn <span id="batch-product-name"></span> sản phẩm từ lô hàng </h6>
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
                    </table>
                </div>
                <input type="hidden" value="{{ $locationId }}" class="user_location">
                <table id="product-table" class="table ">
                    <thead>
                        <th>Mã hàng</th>
                        <th>Tên hàng</th>
                        {{-- <th>Ngày sản xuất</th>
                        <th>Hạn sử dụng</th> --}}
                        <th>Số lượng</th>
                        <th>Giá bán</th>
                        <th>Giảm giá</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </thead>
                    <tbody id="body-product-table">

                    </tbody>
                </table>
        </div>
    </div>



    <div class="content-10">
        @csrf

        {{-- <div class="form-group input-div">
            <h4>Người tạo</h4>
            <input type="hidden" name="creator_id" value="{{ old('creator_id') ?? $user->id }}" id="creator_id"
                class="form-control" readonly>
            {{ $user->name }}
            @error('creator_id')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div> --}}
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
            @if ($user->location->street_addres)
                {{ $user->location->street_addres }}-
            @endif
            {{ $user->location->ward }}-{{ $user->location->district }}-{{ $user->location->city }}
            @error('customer_address')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        {{-- <div class="form-group input-div">
            <h4>Nhà kho</h4>
            <select name="warehouse_id" id="" class="form-control">
                <option value="">---Chọn nhà kho---</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ old('warehouse') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}</option>
                @endforeach
            </select>
            @error('warehouse_id')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div> --}}
    </div>
    <div class="content-10">
        <div class="form-group input-div">
            <h4>Tổng tiền hàng</h4>
            <input type="number" name="total_amount" value="{{ old('total_amount') }}" class="total_amount"
                class="form-control">
            @error('total_amount')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        {{-- <div class="form-group input-div">
            <h4>Giảm giá </h4>
            <input type="number" name="total_discount" value="{{ old('total_discount') }}" class="total_discount"
                class="form-control">
            @error('total_discount')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4>Số tiền phải trả</h4>
            <input type="number" name="amount_due" value="{{ old('amount_due') }}" class="amount_due"
                class="form-control">
            @error('amount_due')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div> --}}
        <div class="btn-controls">
            <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
            <div class="btn-cs btn-delete"><a href="{{ route('goodsissues.index') }}">Quay lại </a></div>
        </div>
        </form>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".search-input", function(e) {
                let _text = $(this).val();
                if (_text.length > 0) {
                    $(".search-result").css("display", "block");
                }
            })
        })
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

        $(document).on("click", function(e) {
            if (!$(e.target).closest(".search-result-container").length) {
                $(".search-result").css("display", "none");
            }
        });

        $(document).on("blur", ".quantity", function(e) {
            let productsData = [];

            $('.quantity').each(function() {
                let row = $(this).closest("tr");
                let productId = row.find('input[name^="inputs["][name$="[product_id]"]').val();
                let quantity = $(this).val();
                let locationId = $(".user_location").val();

                if (productId && quantity) {
                    productsData.push({
                        productId: productId,
                        quantity: quantity,
                        locationId: locationId
                    });
                }
            });

            if (productsData.length > 0) {
                fetchBatches(productsData);

            } else {
                console.error("No valid product data found.");
            }
        });

        function fetchBatches(productsData) {
            $.ajax({
                url: "{{ route('fetch-batches') }}",
                method: "get",
                data: {
                    productsData: JSON.stringify(productsData)
                },
                success: function(res) {
                    console.log(res);

                    let batchTbody = document.getElementById("batch-tbody");
                    batchTbody.innerHTML = "";

                    res.batches.forEach(productBatches => {
                        console.log(productBatches);

                        let productId = productBatches.productId;
                        let totalQuantityRequired = productBatches.batches.reduce((total, batch) =>
                            total + batch.quantity, 0);

                        let newRow = document.createElement("tr");

                        // Product ID Cell with Input
                        let productCell = document.createElement("td");
                        let productInput = document.createElement("input");
                        productInput.setAttribute("type", "text");
                        productInput.setAttribute("readonly", true); // Read-only input for product ID
                        productInput.setAttribute("name", `batchData[${productId}][product_id]`);
                        productInput.setAttribute("value", productId);
                        productCell.appendChild(productInput);
                        newRow.appendChild(productCell);

                        // Total Required Cell with Input
                        let totalRequiredCell = document.createElement("td");
                        let totalRequiredInput = document.createElement("input");
                        totalRequiredInput.setAttribute("type", "text");
                        totalRequiredInput.setAttribute("readonly", true);
                        totalRequiredInput.setAttribute("name",
                            `batchData[${productId}][total_quantity_required]`);
                        totalRequiredInput.setAttribute("value", totalQuantityRequired);
                        totalRequiredCell.appendChild(totalRequiredInput);
                        newRow.appendChild(totalRequiredCell);

                        batchTbody.appendChild(newRow);

                        // Create Rows for Each Batch
                        productBatches.batches.forEach((batch, index) => {
                            let batchRow = document.createElement("tr");

                            // Batch ID Cell with Input
                            let batchIdCell = document.createElement("td");
                            let batchIdInput = document.createElement("input");
                            batchIdInput.setAttribute("type", "text");
                            batchIdInput.setAttribute("readonly", true);
                            batchIdInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][batch_id]`);
                            batchIdInput.setAttribute("value", batch.batch_id);
                            batchIdCell.appendChild(batchIdInput);
                            batchRow.appendChild(batchIdCell);

                            // Manufacturing Date Cell with Input
                            let manufacturingDateCell = document.createElement("td");
                            let manufacturingDateInput = document.createElement("input");
                            manufacturingDateInput.setAttribute("type", "text");
                            manufacturingDateInput.setAttribute("readonly", true);
                            manufacturingDateInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][manufacturing_date]`
                            );
                            manufacturingDateInput.setAttribute("value", batch
                                .manufacturing_date ?? 'N/A');
                            manufacturingDateCell.appendChild(manufacturingDateInput);
                            batchRow.appendChild(manufacturingDateCell);

                            // Expiry Date Cell with Input
                            let expiryDateCell = document.createElement("td");
                            let expiryDateInput = document.createElement("input");
                            expiryDateInput.setAttribute("type", "text");
                            expiryDateInput.setAttribute("readonly", true);
                            expiryDateInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][expiry_date]`);
                            expiryDateInput.setAttribute("value", batch.expiry_date ?? 'N/A');
                            expiryDateCell.appendChild(expiryDateInput);
                            batchRow.appendChild(expiryDateCell);

                            // Available Quantity Cell with Input
                            let availableQuantityCell = document.createElement("td");
                            let availableQuantityInput = document.createElement("input");
                            availableQuantityInput.setAttribute("type", "text");
                            availableQuantityInput.setAttribute("readonly", true);
                            availableQuantityInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][quantity_available]`
                            );
                            availableQuantityInput.setAttribute("value", batch.quantity);
                            availableQuantityCell.appendChild(availableQuantityInput);
                            batchRow.appendChild(availableQuantityCell);

                            // Quantity to Take Cell with Editable Input
                            let quantityCell = document.createElement("td");
                            let quantityInput = document.createElement("input");
                            quantityInput.setAttribute("type", "number");
                            quantityInput.setAttribute("class", "batch-quantity");
                            quantityInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][quantity]`);
                            quantityInput.setAttribute("value", batch.quantity);
                            quantityInput.setAttribute("min", 1);
                            quantityInput.setAttribute("max", batch.quantity);
                            quantityCell.appendChild(quantityInput);
                            batchRow.appendChild(quantityCell);

                            // Warehouse ID Cell with Input
                            let warehouseCell = document.createElement("td");
                            let warehouseInput = document.createElement("input");
                            warehouseInput.setAttribute("type", "text");
                            warehouseInput.setAttribute("readonly", true);
                            warehouseInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][warehouse_id]`);
                            warehouseInput.setAttribute("value", batch.warehouse);
                            warehouseCell.appendChild(warehouseInput);
                            batchRow.appendChild(warehouseCell);

                            batchTbody.appendChild(batchRow);
                        });
                    });
                },
                error: function(err) {
                    console.error("Error fetching batches: ", err);
                }
            });
        }

        let indexRow = 0;
        $(".search-result").on("click", ".search-result-item", function() {
            let name = $(this).find("h4").data("name");
            let code = $(this).find("p").data('code');
            let manufacturingDate = $(this).find(".product_manufacturing_date").data('manufacturing');
            let expiryDate = $(this).find(".product_expiry_date").data('expiry');
            let quantityAvailable = $(this).find(".product_quantity_available").data('quantity');
            let batchId = $(this).find(".product_batch_id").data('batch');
            let id = $(this).find("h6").data('id');
            let locationId = $(".user_location").val();

            let newRow = document.createElement("tr");

            let idCell = document.createElement("td");
            idCell.style.display = "none";
            let idInput = document.createElement("input");
            idInput.setAttribute("type", "hidden");
            idInput.setAttribute("name", `inputs[${indexRow}][product_id]`);
            idInput.value = id;
            idCell.appendChild(idInput);

            let batchIdCell = document.createElement("td");
            batchIdCell.style.display = "none";
            let batchIdInput = document.createElement("input");
            batchIdInput.setAttribute("type", "hidden");
            batchIdInput.setAttribute("name", `inputs[${indexRow}][batch_id]`);
            batchIdInput.value = batchId;
            batchIdCell.appendChild(batchIdInput);

            let userLocationIdCell = document.createElement("td");
            userLocationIdCell.style.display = "none";
            userLocationInput = document.createElement("input");
            userLocationInput.setAttribute("type", "hidden");
            userLocationInput.setAttribute("name", `inputs[${indexRow}][user_location_id]`);
            userLocationInput.value = locationId;
            userLocationIdCell.appendChild(userLocationInput);

            let codeCell = document.createElement("td");
            let codeInput = document.createElement("input");
            codeInput.setAttribute("type", "text");
            codeInput.setAttribute("name", `inputs[${indexRow}][code]`);
            codeInput.value = code;
            codeInput.style.width = "130px";
            codeCell.appendChild(codeInput);

            let nameCell = document.createElement("td");
            let nameInput = document.createElement("input");
            nameInput.setAttribute("type", "text");
            nameInput.setAttribute("name", `inputs[${indexRow}][name]`);
            nameInput.value = name;
            nameInput.style.width = "200px";
            nameCell.appendChild(nameInput);


            let quantityCell = document.createElement("td");
            let quantityControlDiv = document.createElement("div");
            quantityControlDiv.classList.add("quantity-control");
            let quantityInput = document.createElement("input");
            quantityInput.classList.add("quantity");
            quantityInput.setAttribute("type", "number");
            quantityInput.setAttribute("name", `inputs[${indexRow}][quantity]`);
            quantityInput.setAttribute("min", 1);
            quantityInput.style.width = "120px";
            quantityControlDiv.appendChild(quantityInput);
            quantityCell.appendChild(quantityControlDiv);

            let unitPriceCell = document.createElement("td");
            unitPriceInput = document.createElement("input");
            unitPriceInput.classList.add("unit-price");
            unitPriceInput.setAttribute("type", "number");
            unitPriceInput.setAttribute("name", `inputs[${indexRow}][unit-price]`);
            unitPriceInput.setAttribute("min", 0);
            unitPriceInput.style.width = "140px";
            unitPriceCell.appendChild(unitPriceInput);


            let discountCell = document.createElement("td");
            let discountInput = document.createElement("input");
            discountInput.classList.add("discount");
            discountInput.setAttribute("type", "number");
            discountInput.setAttribute("name", `inputs[${indexRow}][discount]`);
            discountInput.setAttribute("min", 0);
            discountInput.style.width = "140px";
            discountCell.appendChild(discountInput);

            let totalPriceCell = document.createElement("td");
            let totalPriceInput = document.createElement("input");
            totalPriceInput.classList.add("total-price");
            totalPriceInput.setAttribute("type", "number");
            totalPriceInput.setAttribute("min", 0);
            totalPriceInput.setAttribute("readonly", true);
            totalPriceInput.style.width = "140px";
            totalPriceCell.appendChild(totalPriceInput);

            let removeCell = document.createElement("td");
            let removeBtn = document.createElement("button");
            removeBtn.classList.add("remove-product");
            removeBtn.textContent = "Xóa";
            removeBtn.style.width = "100px";
            removeCell.appendChild(removeBtn);


            newRow.appendChild(idCell);
            newRow.appendChild(batchIdCell);
            newRow.appendChild(userLocationIdCell);
            newRow.appendChild(codeCell);
            newRow.appendChild(nameCell);
            // newRow.appendChild(manufacturingDateCell);
            // newRow.appendChild(expiryDateCell);
            newRow.appendChild(quantityCell);
            newRow.appendChild(unitPriceCell);
            newRow.appendChild(discountCell);
            newRow.appendChild(totalPriceCell);
            newRow.appendChild(removeCell);

            document.querySelector("#body-product-table").appendChild(newRow);
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
