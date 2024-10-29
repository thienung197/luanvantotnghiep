@extends('layouts.app')
@section('title', 'Phiếu xuất hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm phiếu xuất hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('goodsissues.index') }}">Phiếu xuất hàng</a> > <a
                    href="">Thêm phiếu xuất hàng</a>
            </p>
        </div>
    </div>

    <div class="content-10">
        <div class="search-result-container">
            <form action="" role="search">
                <div class="form-group input-div">
                    <input type="text" name="key" class="form-control search-input" placeholder="Nhập tên sản phẩm">
                    <div class="search-result" style="z-index:1">

                    </div>
                </div>
            </form>
            <form action="{{ route('goodsissues.store') }}" method="POST">

                <table id="product-table" class="table ">
                    <thead>
                        <th>Mã hàng</th>
                        <th>Tên hàng</th>
                        <th>Ngày sản xuất</th>
                        <th>Hạn sử dụng</th>
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

    <div class="content-10" class="table">
        <h6>Chọn <span id="batch-product-name"></span> sản phẩm từ lô hàng </h6>
        <table id="batch-table">
            <thead>
                <tr>
                    <th>Số lô</th>
                    <th>Ngày sản xuất</th>
                    <th>Ngày hết hạn</th>
                    <th>Số lượng có sẵn</th>
                    <th>Số lượng chọn</th>
                </tr>
            </thead>
            <tbody id="batch-tbody">
            </tbody>
        </table>
    </div>

    <div class="content-10">
        @csrf

        <div class="form-group input-div">
            <h4>Người tạo </h4>
            <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
            @error('name')
                <div class="error message">{{ $creator_id }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4>Mã phiếu xuất </h4>
            <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
            @error('code')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4>Khách hàng</h4>
            <select name="customer_id" id="" class="form-control">
                <option value="">---Chọn khách hàng---</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
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
        </div>
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
        <div class="form-group input-div">
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
        //hien KQ tim kiem
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
                    url: "{{ route('ajax-search-batch') }}",
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
            let productsData = []; // Khởi tạo mảng cho dữ liệu sản phẩm

            // Lấy dữ liệu từ tất cả các hàng liên quan
            $('.quantity').each(function() {
                let row = $(this).closest("tr");
                let productId = row.find('input[name^="inputs["][name$="[product_id]"]').val();
                let quantity = $(this).val();

                // Chỉ thêm nếu productId và quantity là hợp lệ
                if (productId && quantity) {
                    productsData.push({
                        productId: productId,
                        quantity: quantity
                    });
                }
            });

            // Gọi fetchBatches với mảng productsData nếu nó không rỗng
            if (productsData.length > 0) {
                fetchBatches(productsData); // Truyền mảng productsData
            } else {
                console.error("No valid product data found."); // Ghi lại lỗi nếu không có dữ liệu
            }
        });

        // Hàm fetchBatches được điều chỉnh
        function fetchBatches(productsData) {
            $.ajax({
                url: "{{ route('fetch-batches') }}",
                method: "get",
                data: {
                    productsData: JSON.stringify(productsData) // Gửi dưới dạng chuỗi JSON
                },
                success: function(res) {
                    let batchTbody = document.getElementById("batch-tbody");
                    batchTbody.innerHTML = ""; // Xóa các hàng cũ

                    // Lặp qua từng sản phẩm
                    res.batches.forEach(productBatches => {
                        let productId = productBatches.productId; // Lấy ID sản phẩm
                        let totalQuantityRequired = productBatches.batches.reduce((total, batch) =>
                            total + batch.quantity, 0); // Tính tổng số lượng cần

                        // Thêm hàng cho sản phẩm
                        let newRow = document.createElement("tr");

                        // Thêm ô cho ID sản phẩm
                        let productCell = document.createElement("td");
                        productCell.textContent =
                        productId; // Có thể thay đổi để hiển thị tên sản phẩm nếu cần
                        newRow.appendChild(productCell);

                        // Thêm ô cho tổng số lượng cần
                        let totalRequiredCell = document.createElement("td");
                        totalRequiredCell.textContent = totalQuantityRequired;
                        newRow.appendChild(totalRequiredCell);

                        // Thêm hàng mới cho sản phẩm
                        batchTbody.appendChild(newRow);

                        // Thêm từng lô hàng cho sản phẩm
                        productBatches.batches.forEach((batch, index) => {
                            let batchRow = document.createElement("tr");

                            // Thêm ô cho số lô
                            let batchIdCell = document.createElement("td");
                            batchIdCell.textContent = batch.batch_id;
                            batchRow.appendChild(batchIdCell);

                            // Thêm ô cho ngày sản xuất
                            let manufacturingDateCell = document.createElement("td");
                            manufacturingDateCell.textContent = batch.manufacturing_date ??
                                'N/A';
                            batchRow.appendChild(manufacturingDateCell);

                            // Thêm ô cho ngày hết hạn
                            let expiryDateCell = document.createElement("td");
                            expiryDateCell.textContent = batch.expiry_date ?? 'N/A';
                            batchRow.appendChild(expiryDateCell);

                            // Thêm ô cho số lượng có sẵn
                            let availableQuantityCell = document.createElement("td");
                            availableQuantityCell.textContent = batch
                            .quantity; // Số lượng trong kho
                            batchRow.appendChild(availableQuantityCell);

                            // Tạo ô cho số lượng chọn
                            let quantityCell = document.createElement("td");
                            let quantityInput = document.createElement("input");
                            quantityInput.setAttribute("type", "number");
                            quantityInput.setAttribute("class", "batch-quantity");
                            quantityInput.setAttribute("name",
                                `inputs[${productId}][batches][${index}][quantity]`);
                            quantityInput.setAttribute("value", batch.quantity);
                            quantityInput.setAttribute("min", 1);
                            quantityInput.setAttribute("max", batch
                            .quantity); // Giới hạn tối đa bằng số lượng có sẵn
                            quantityCell.appendChild(quantityInput);

                            // Tạo ô ẩn cho ID lô hàng
                            let hiddenBatchIdInput = document.createElement("input");
                            hiddenBatchIdInput.setAttribute("type", "hidden");
                            hiddenBatchIdInput.setAttribute("name",
                                `inputs[${productId}][batches][${index}][batch_id]`);
                            hiddenBatchIdInput.setAttribute("value", batch.batch_id);
                            quantityCell.appendChild(hiddenBatchIdInput);

                            batchRow.appendChild(quantityCell);

                            // Thêm hàng lô vào bảng
                            batchTbody.appendChild(batchRow);
                        });
                    });
                },
                error: function(err) {
                    console.error("Error fetching batches: ", err);
                    // Có thể thông báo cho người dùng nếu cần
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

            let manufacturingDateCell = document.createElement("td");
            let manufacturingDateInput = document.createElement("input");
            manufacturingDateInput.setAttribute("type", "text");
            manufacturingDateInput.setAttribute("name", `inputs[${indexRow}][manufacturing_date]`);
            manufacturingDateInput.value = manufacturingDate;
            manufacturingDateInput.style.width = "160px";
            manufacturingDateCell.appendChild(manufacturingDateInput);

            let expiryDateCell = document.createElement("td");
            let expiryDateInput = document.createElement("input");
            expiryDateInput.setAttribute("type", "text");
            expiryDateInput.setAttribute("name", `inputs[${indexRow}][expiry_date]`);
            expiryDateInput.value = expiryDate;
            expiryDateInput.style.width = "160px";
            expiryDateCell.appendChild(expiryDateInput);

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
            newRow.appendChild(codeCell);
            newRow.appendChild(nameCell);
            newRow.appendChild(manufacturingDateCell);
            newRow.appendChild(expiryDateCell);
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
