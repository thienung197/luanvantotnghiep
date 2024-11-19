@extends('layouts.app')
@section('title', 'Phiếu yêu cầu nhập hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm phiếu yêu cầu nhập hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('stocktakes.index') }}">Phiếu yêu cầu nhập hàng</a> > <a
                    href="">Thêm phiếu yêu cầu nhập hàng</a>
            </p>
        </div>
    </div>

    <div class="content-10">
        <h2>Báo cáo xuất nhập tồn</h2>
        <form action="{{ route('comprehensive-stock-report.store') }}" method="POST">
            @csrf
            <input type="hidden" name="warehouse_id" value="{{ $warehouse_id }}">
            <input type="hidden" name="user_id" value="{{ $user_id }}">
            <p>
                Từ ngày:
                <input type="date" name="start_date" value="2024-11-01">
            </p>
            <p>
                Đến ngày:
                <input type="date" name="end_date" value="2024-11-30">
            </p>
            <div class="content">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã hàng</th>
                            <th>Tên hàng</th>
                            <th>Đơn vị tính</th>
                            <th>Tồn đầu kỳ</th>
                            <th>Nhập trong kỳ</th>
                            <th>Xuất trong kỳ</th>
                            <th>Tồn cuối kỳ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <input type="hidden" name="products[{{ $loop->index }}][product_id]"
                                    value="{{ $product['product_id'] }}">
                                <input type="hidden" name="products[{{ $loop->index }}][beginning_inventory]"
                                    value="{{ $product['beginning_inventory'] }}">
                                <input type="hidden" name="products[{{ $loop->index }}][stock_in]"
                                    value="{{ $product['total_received'] }}">
                                <input type="hidden" name="products[{{ $loop->index }}][stock_out]"
                                    value="{{ $product['total_issued'] }}">
                                <input type="hidden" name="products[{{ $loop->index }}][ending_inventory]"
                                    value="{{ $product['ending_inventory'] }}">
                                <td>{{ $product['product_code'] }}</td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['unit_name'] }}</td>
                                <td>{{ $product['beginning_inventory'] }}</td>
                                <td>{{ $product['total_received'] }}</td>
                                <td>{{ $product['total_issued'] }}</td>
                                <td>{{ $product['ending_inventory'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary">Lưu báo cáo</button>
        </form>
    </div>

    {{-- <div class="content-10">
      

            <form action="{{ route('stocktakes.store') }}" method="POST"> --}}

    {{-- <table id="product-table" class="table ">
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
                </table> --}}
    {{-- </div>
    </div> --}}


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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('select[name="restock_request_reason_id"]').on('change', function() {
            let reasonId = $(this).val();
            let warehouseId = $('#warehouse_id').val();
            console.log(warehouseId);

            if (reasonId == 3) {
                $.ajax({
                    url: "{{ route('restock.suggested-products') }}",
                    type: "GET",
                    data: {
                        reason_id: reasonId,
                        warehouse_id: warehouseId
                    },
                    success: function(products) {
                        console.log(products);

                        const table = document.createElement("table");
                        table.classList.add("product-table");
                        table.classList.add("table");
                        const thead = document.createElement("thead");
                        const tbody = document.createElement("tbody");
                        const headerRow = document.createElement("tr");
                        const headers = ["Mã sản phẩm", "Tên sản phẩm", "Đơn vị", "Số lượng tồn kho",
                            "Số lượng đặt dự kiến", "Xóa"
                        ];
                        headers.forEach(headerText => {
                            const th = document.createElement("th");
                            th.textContent = headerText;
                            headerRow.appendChild(th);
                        })
                        thead.appendChild(headerRow);

                        products.forEach((product, index) => {
                            const row = document.createElement("tr");

                            const idCell = document.createElement("td");
                            idCell.style.display = "none";
                            idCell.textContent = product.id;
                            row.appendChild(idCell);

                            const codeCell = document.createElement("td");
                            codeCell.textContent = product.code;
                            row.appendChild(codeCell);

                            const nameCell = document.createElement("td");
                            nameCell.textContent = product.name;
                            row.appendChild(nameCell);

                            const unitIdCell = document.createElement("td");
                            unitIdCell.textContent = product.unit_name;
                            row.appendChild(unitIdCell);

                            const availableQtyCell = document.createElement("td");
                            availableQtyCell.textContent = product.available_quantity;
                            row.appendChild(availableQtyCell);

                            const suggestedQtyCell = document.createElement("td");
                            suggestedQtyCell.textContent = product.suggested_quantity;
                            row.appendChild(suggestedQtyCell)

                            const actionCell = document.createElement("td");
                            const deleteBtn = document.createElement("button");
                            deleteBtn.textContent = "Delete";
                            deleteBtn.classList.add("delete-btn");
                            actionCell.appendChild(deleteBtn);
                            row.appendChild(actionCell);

                            deleteBtn.addEventListener("click", () => {
                                row.remove();
                                // products.splice(index, 1);
                            })

                            tbody.appendChild(row);
                        })

                        table.appendChild(thead);
                        table.appendChild(tbody);
                        $("#product-suggestions").html(table);

                    }
                })
            } else {
                $("#product-suggestions").html('');
            }
        })

        $(document).on("input", ".search-input", function() {
            var _text = $(this).val();
            var warehouseId = $("#warehouse_id").val();

            if (_text.length > 0) {
                $.ajax({
                    url: "{{ route('ajax-search-product-and-inventory-by-warehouse') }}",
                    type: "GET",
                    data: {
                        key: _text,
                        warehouse_id: 1
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

        $(document).ready(function() {
            $("#submit-request-btn").on("click", function(e) {
                e.preventDefault();
                let code = $("#submit_code").val();
                let user_id = $("#user_id").val();
                let warehouse_id = $("#warehouse_id").val();

                let products = [];
                $(".product-table tbody tr").each(function() {
                    let product = {
                        id: $(this).find("td:nth-child(1)").text(),
                        suggested_quantity: $(this).find("td:nth-child(6)").text()
                    };
                    products.push(product);

                })
                let data = {
                    code: code,
                    user_id: user_id,
                    warehouse_id: warehouse_id,
                    products: products,
                    // _token: $('meta[name="csrf-token"]').attr('content')
                };
                $("#data-submit").val(JSON.stringify(data));
                $("#form-submit").submit();
            })
        })

        $(document).ready(function() {
            $(".search-result").on("click", ".search-result-item", function() {
                let productId = $(this).find(".product-id").data("id");
                let productCode = $(this).find(".product-code").data("code");
                let productName = $(this).find("h4").data("name");
                let unit = $(this).find(".ajax-product-unit").data("unit");
                let inventoryQuantity = $(this).find(".ajax-product-inventory-quantity").data(
                    "inventory-quantity");
                let suggestedQuantity = $(this).find(".ajax-product-suggested-quantity").data(
                    "suggested-quantity");
                // let imageUrl = $(this).find("img").attr("src");

                let newRow = document.createElement('tr');

                // let imgCell = document.createElement('td');
                // let imgElement = document.createElement('img');
                // imgElement.src = imageUrl;
                // imgElement.alt = "Product Image";
                // imgElement.width = 50;
                // imgElement.height = 50;
                // imgCell.appendChild(imgElement);

                let idCell = document.createElement("td");
                idCell.textContent = productId;

                let codeCell = document.createElement('td');
                codeCell.textContent = productCode;

                let nameCell = document.createElement('td');
                nameCell.textContent = productName;

                let unitCell = document.createElement('td');
                unitCell.textContent = unit;

                // let priceCell = document.createElement('td');
                // priceCell.textContent = price;

                let inventoryCell = document.createElement('td');
                inventoryCell.textContent = inventoryQuantity;

                let suggestedCell = document.createElement('td');
                suggestedCell.textContent = suggestedQuantity;

                const actionCell = document.createElement("td");
                const deleteBtn = document.createElement("button");
                deleteBtn.textContent = "Delete";
                deleteBtn.classList.add("delete-btn");
                actionCell.appendChild(deleteBtn);

                deleteBtn.addEventListener("click", () => {
                    newRow.remove();
                    // products.splice(index, 1);
                })

                // newRow.appendChild(imgCell);
                newRow.appendChild(codeCell);
                newRow.appendChild(nameCell);
                newRow.appendChild(unitCell);
                // newRow.appendChild(priceCell);
                newRow.appendChild(inventoryCell);
                newRow.appendChild(suggestedCell);
                newRow.appendChild(actionCell);

                $(".product-table tbody").append(newRow);
                $(".search-result").css("display", "none");
            });
        });


        $(document).on('change', '.batch-select', function() {
            var batchId = $(this).val();
            var warehouseId = $(this).find(':selected').data('warehouse-id');
            var selectName = $(this).attr('name');
            var batchQuantityInputName = selectName.replace('batch_id', 'batch-quantity');

            if (batchId) {
                $.ajax({
                    url: '{{ route('ajax-batch-inventory') }}',
                    method: 'GET',
                    data: {
                        batch_id: batchId,
                        warehouse_id: 7
                    },
                    success: function(response) {

                        $('[name="' + batchQuantityInputName + '"]').val(response
                            .quantity_available);
                    },
                    error: function() {
                        alert('Không thể lấy thông tin tồn kho');
                    }
                });
            } else {
                $('batch-quantity').val('');
            }
        });

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



        // //Ham cap nhat amount_due
        // function updateAmountDue() {
        //     let totalAmount = parseFloat($(".total_amount").val()) || 0;
        //     let totalDiscount = parseFloat($(".total_discount").val()) || 0;
        //     let amountDue = totalAmount - totalDiscount;
        //     $(".amount_due").val(amountDue.toFixed(2));
        // }

        // //Cap nhat amount_due
        // $(document).on("input", ".total_amount,.total_discount", function() {
        //     updateAmountDue();
        // })

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
