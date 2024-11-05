    @extends('layouts.app')
    @section('title', 'Phiếu kiểm kho')
    @section('content')
        <div class="content_header">
            <div class="content_header--title">
                Thêm phiếu kiểm kho
            </div>
            <div class="content_header--path">
                <img src="{{ asset('img/home.png') }}" alt="">
                <p><a href="">Home</a> > <a href="{{ route('stocktakes.index') }}">Phiếu kiểm kho</a> > <a
                        href="">Thêm phiếu kiểm kho</a>
                </p>
            </div>
        </div>

        <div class="content-10">
            <div class="search-result-container">
                <form action="" role="search">
                    <div class="form-group input-div">
                        <input type="text" name="key" class="form-control search-input"
                            placeholder="Nhập tên sản phẩm">
                        <div class="search-result" style="z-index:1">

                        </div>
                    </div>
                </form>
                <form action="{{ route('stocktakes.store') }}" method="POST">

                    <table id="product-table" class="table ">
                        <thead>
                            <th>Mã hàng</th>
                            <th>Tên hàng</th>
                            <th>Lô hàng</th>
                            <th>Đơn vị tính</th>
                            <th>Tồn kho</th>
                            <th>Thực tế</th>
                            <th>Số lượng lệch</th>
                            <th>Giá trị lệch</th>
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
                <input type="hidden" name="creator_id" value="{{ Auth::user()->id }}" id="creator_id">
                <input type="text" value="{{ Auth::user()->name }}" readonly class="form-control">
                @error('creator_id')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Mã phiếu kiểm kho </h4>
                <input type="text" name="code" value="{{ old('code', $newCode) }}" id="code" class="form-control"
                    readonly>
                @error('code')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Ngày kiểm kho</h4>
                <input type="date" name="date" class="form-control"
                    value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
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
            <div class="form-group input-div">
                <h4>Ghi chú</h4>
                <input type="text" name="notes" value="{{ old('notes') }}" id="notes" class="form-control">
                @error('notes')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="content-10">
            <div class="form-group input-div">
                <h4>Tổng thực tế</h4>
                <input type="number" name="actual_total" value="{{ old('actual_total') }}" class="actual_total"
                    class="form-control">
                @error('actual_total')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tổng lệch tăng </h4>
                <input type="number" name="total_increase_variance" value="{{ old('total_increase_variance') }}"
                    class="total_increase_variance" class="form-control">
                @error('total_increase_variance')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tổng lệch giảm</h4>
                <input type="number" name="total_decrease_variance" value="{{ old('total_decrease_variance') }}"
                    class="total_decrease_variance" class="form-control">
                @error('total_decrease_variance')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tổng chênh lệch</h4>
                <input type="number" name="total_variance" value="{{ old('total_variance') }}" class="total_variance"
                    class="form-control">
                @error('total_variance')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-delete"><a href="{{ route('stocktakes.index') }}">Quay lại </a></div>
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

            // $(document).on("input", ".search-input", function() {
            //     var _text = $(this).val();
            //     if (_text.length > 0) {
            //         $.ajax({
            //             url: "{{ route('ajax-search-product') }}",
            //             type: "GET",
            //             data: {
            //                 key: _text
            //             },
            //             success: function(res) {
            //                 $(".search-result").html(res).css("display", "block");
            //             }
            //         })
            //     } else {
            //         $(".search-result").css("display", "none");
            //     }
            // })

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
            })
            //hide search result when clicking outside ...
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".search-result-container").length) {
                    $(".search-result").css("display", "none");
                }
            })

            let indexRow = 0;
            $(".search-result").on("click", ".search-result-item", function() {
                let name = $(this).find("h4").data("name");
                let code = $(this).find(".product-code").data('product-code');
                let id = $(this).find(".product-id").data('id');
                let newRow = document.createElement("tr");
                let batchCode = $(this).find(".batch-code").data("batch-code");
                let unit = $(this).find(".product-unit").data("unit");
                let batchQuantity = $(this).find(".product-batch-quantity").data("batch-quantity");

                let price = $(this).find(".product-price").data("price");

                //id hang
                let idCell = document.createElement("td");
                idCell.style.display = "none";
                let idInput = document.createElement("input");
                idInput.setAttribute("type", "hidden");
                idInput.setAttribute("name", `inputs[${indexRow}][product_id]`);
                idInput.value = id;
                idCell.appendChild(idInput);

                //price
                let priceCell = document.createElement("td");
                priceCell.style.display = "none";
                let priceInput = document.createElement("input");
                priceInput.classList.add("price");
                priceInput.setAttribute("type", "hidden");
                priceInput.setAttribute("name", `inputs[${indexRow}][price]`);
                priceInput.value = price;

                priceCell.appendChild(priceInput);

                //ma hang
                let codeCell = document.createElement("td");
                let codeInput = document.createElement("input");
                codeInput.setAttribute("type", "text");
                codeInput.setAttribute("name", `inputs[${indexRow}][code]`);
                codeInput.value = code;
                codeInput.style.width = "130px";
                codeCell.appendChild(codeInput);

                //ten hang
                let nameCell = document.createElement("td");
                let nameInput = document.createElement("input");
                nameInput.setAttribute("type", "text");
                nameInput.setAttribute("name", `inputs[${indexRow}][name]`);
                nameInput.value = name;
                nameInput.style.width = "200px";
                nameCell.appendChild(nameInput);

                //lo hang
                let batchCodeCell = document.createElement("td");
                let batchCodeInput = document.createElement("input");
                batchCodeInput.setAttribute("type", "texgt");
                batchCodeInput.setAttribute("name", `inputs[${indexRow}][batch_id]`);
                batchCodeInput.value = batchCode;
                batchCodeCell.appendChild(batchCodeInput);

                //don vi tinh
                let unitCell = document.createElement("td");
                unitCell.textContent = unit;

                //ton kho
                let batchQuantityCell = document.createElement("td");
                batchQuantityInput = document.createElement("input");
                batchQuantityInput.classList.add("batch-quantity");
                batchQuantityInput.setAttribute("type", "number");
                batchQuantityInput.setAttribute("name", `inputs[${indexRow}][batch-quantity]`);
                batchQuantityInput.style.width = "140px";
                batchQuantityInput.value = batchQuantity;
                batchQuantityCell.appendChild(batchQuantityInput);

                //thuc te
                let actualQuantityCell = document.createElement("td");
                actualQuantityInput = document.createElement("input");
                actualQuantityInput.classList.add("actual-quantity");
                actualQuantityInput.setAttribute("type", "number");
                actualQuantityInput.setAttribute("name", `inputs[${indexRow}][actual-quantity]`);
                actualQuantityInput.style.width = "140px";
                actualQuantityCell.appendChild(actualQuantityInput);

                //so luong lech
                let quantityDifferenceCell = document.createElement("td");
                let quantityDifferenceInput = document.createElement("input");
                quantityDifferenceInput.classList.add("quantity-difference");
                quantityDifferenceInput.setAttribute("type", "number");
                quantityDifferenceInput.setAttribute("name", `inputs[${indexRow}][quantity-difference]`);
                quantityDifferenceInput.style.width = "140px";
                quantityDifferenceCell.appendChild(quantityDifferenceInput);

                //gia tri lech
                let valueDifferenceCell = document.createElement("td");
                let valueDifferenceInput = document.createElement("input");
                valueDifferenceInput.classList.add("value-difference");
                valueDifferenceInput.setAttribute("type", "number");
                valueDifferenceInput.setAttribute("readonly", true);
                valueDifferenceInput.style.width = "140px";
                valueDifferenceCell.appendChild(valueDifferenceInput);

                let removeCell = document.createElement("td");
                let removeBtn = document.createElement("button");
                removeBtn.classList.add("remove-product");
                removeBtn.textContent = "Xóa";
                removeBtn.style.width = "100px";
                removeCell.appendChild(removeBtn);

                newRow.appendChild(idCell);
                newRow.appendChild(priceCell);
                newRow.appendChild(codeCell);
                newRow.appendChild(nameCell);
                newRow.appendChild(batchCodeCell);
                newRow.appendChild(unitCell);
                newRow.appendChild(batchQuantityCell);
                newRow.appendChild(actualQuantityCell);
                newRow.appendChild(quantityDifferenceCell);
                newRow.appendChild(valueDifferenceCell);
                newRow.appendChild(removeCell);

                document.querySelector("#body-product-table").appendChild(newRow);
                $(".search-result").css("display", "none");
                indexRow++;
            })

            //Ham cap nhat value-amount
            function updateQuantityAndValue(row) {
                let batchQuantity = parseInt(row.find(".batch-quantity").val());
                let actualQuantity = parseInt(row.find(".actual-quantity").val());
                let price = parseFloat(row.find(".price").val());
                let quantityDifference = actualQuantity - batchQuantity;
                let valueDifference = price * quantityDifference;
                row.find(".quantity-difference").val(quantityDifference);
                row.find(".value-difference").val(valueDifference);
            }

            //Cap nhat quantity-value
            $("#product-table").on("input", ".batch-quantity,.actual-quantity", function() {
                updateQuantityAndValue($(this).closest("tr"));
            })


            function updateActualTotal() {
                let sum = 0;
                $("#product-table tr").each(function() {
                    let actual_quantity = parseInt($(this).find(".actual-quantity").val()) || 0;
                    let price = parseFloat($(this).find(".price").val()) || 0;
                    if (!isNaN(actual_quantity)) {
                        sum += actual_quantity * price;

                    }
                })
                $(".actual_total").val(sum);
            }

            $("#product-table").on("input", ".actual-quantity", function() {
                updateActualTotal();
            })

            function updateTotalVariance() {
                let sumIncrease = 0;
                let sumDecrease = 0;

                $("#product-table tr").each(function() {
                    let batchQuantity = parseInt($(this).find(".batch-quantity").val()) || 0;
                    let actualQuantity = parseInt($(this).find(".actual-quantity").val()) || 0;
                    let price = parseFloat($(this).find(".price").val()) || 0;

                    let quantityDifference = actualQuantity - batchQuantity;
                    let valueDifference = price * quantityDifference;

                    if (valueDifference > 0) {
                        sumIncrease += valueDifference;
                    } else {
                        sumDecrease += valueDifference;
                    }
                });

                $(".total_increase_variance").val(sumIncrease);
                $(".total_decrease_variance").val(sumDecrease);

                let totalVariance = sumIncrease + sumDecrease;
                $(".total_variance").val(totalVariance);
            }

            $("#product-table").on("input", ".actual-quantity", function() {
                updateTotalVariance();
            });



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
