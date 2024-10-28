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



            // Add the remaining columns like quantity, unit price, discount, etc.

            newRow.appendChild(idCell);
            newRow.appendChild(codeCell);
            newRow.appendChild(nameCell);
            newRow.appendChild(manufacturingDateCell);
            newRow.appendChild(expiryDateCell);
            // Append other cells here...

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
