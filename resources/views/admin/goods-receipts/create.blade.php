@extends('layouts.app')
@section('title', 'Phiếu nhập hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm phiếu nhập hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('goodsreceipts.create') }}">Phiếu nhập hàng</a> > <a
                    href="">Thêm phiếu nhập hàng</a>
            </p>
        </div>
    </div>

    <div class="content-10">
        @csrf

        <div class="form-group input-div">
            <h4>Người tạo</h4>
            <input type="hidden" name="creator_id" value="{{ Auth::user()->id }}" id="creator_id">
            <input type="text" value="{{ Auth::user()->name }}" readonly class="form-control">
            @error('creator_id')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group input-div">
            <h4>Mã phiếu nhập </h4>
            <input type="text" name="code" value="{{ old('code', $newCode) }}" id="code" class="form-control"
                readonly>
            @error('code')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div>
        <button id="show-products" class="btn btn-primary">Các sản phẩm được đề xuất</button>
        <div class="ajax-table-container">

        </div>
        {{-- <div class="form-group input-div">
            <h4>Nhà kho</h4>
            <input type="hidden" name="warehouse_id" value="{{ Auth::user()->warehouse_id }}" id="warehouse_id">
            <input type="text" value="{{ $warehouseName }}" readonly class="form-control">
            @error('warehouse_id')
                <div class="error message">{{ $message }}</div>
            @enderror
        </div> --}}
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
            <form action="{{ route('goodsreceipts.store') }}" method="POST">

                <table id="product-table" class="table ">
                    <thead>
                        <th>Mã hàng</th>
                        <th>Tên hàng</th>
                        <th>Ngày sản xuất</th>
                        <th>Hạn sử dụng</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
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
            <div class="btn-cs btn-delete"><a href="{{ route('goodsreceipts.index') }}">Quay lại </a></div>
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
            })
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".search-result-container").length) {
                    $(".search-result").css("display", "none");
                }
            })
        })

        $(document).ready(function() {
            $('#show-products').click(function() {
                fetchApprovedProducts();
            });

            function fetchApprovedProducts() {
                $.ajax({
                    url: "{{ route('approved-products') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log("Fetched products:", data);
                        createTable(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("There was an error fetching the products.");
                    }
                });
            }

            function createTable(products) {
                const container = document.querySelector(".ajax-table-container");

                container.innerHTML = '';

                const table = document.createElement("table");
                table.classList.add("table");

                const thead = document.createElement("thead");
                const headerRow = document.createElement("tr");

                const headers = ["Mã sản phẩm", "Tên sản phẩm", "Đơn vị tính", "Số lượng", "Đơn giá", "Giảm giá",
                    "Thành tiền",
                    "Xóa"
                ];
                headers.forEach(header => {
                    const th = document.createElement("th");
                    th.textContent = header;
                    headerRow.appendChild(th);
                });

                thead.appendChild(headerRow);
                table.appendChild(thead);

                const tbody = document.createElement("tbody");
                products.forEach(product => {
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

                    const unitCell = document.createElement("td");
                    unitCell.textContent = product.unit;
                    row.appendChild(unitCell);

                    const quantityCell = document.createElement("td");
                    const quantityInput = document.createElement("input");
                    quantityInput.classList.add("form-control")
                    quantityInput.setAttribute("type", "number");
                    quantityCell.appendChild(quantityInput);
                    row.appendChild(quantityCell);

                    const unitPriceCell = document.createElement("td");
                    const unitPriceInput = document.createElement("input");
                    unitPriceInput.classList.add("form-control")
                    unitPriceInput.setAttribute("type", "text");
                    unitPriceCell.appendChild(unitPriceInput);
                    row.appendChild(unitPriceCell);

                    const discountCell = document.createElement("td");
                    const discountInput = document.createElement("input");
                    discountInput.classList.add("form-control")
                    discountInput.setAttribute("type", "text");
                    discountCell.appendChild(discountInput);
                    discountInput.value = 0;
                    row.appendChild(discountCell);

                    const totalPriceCell = document.createElement("td");
                    const totalPriceInput = document.createElement("input");
                    totalPriceInput.classList.add("form-control")
                    totalPriceInput.setAttribute("type", "text");
                    totalPriceCell.appendChild(totalPriceInput);
                    row.appendChild(totalPriceCell);

                    const deleteCell = document.createElement("td");
                    deleteCell.classList.add("form-control");
                    const deleteButton = document.createElement("button");
                    deleteButton.classList.add("btn", "btn-danger");
                    deleteButton.textContent = "Xóa"
                    deleteButton.addEventListener("click", function() {
                        deleteRow(row);
                    })
                    deleteCell.appendChild(deleteButton);
                    row.appendChild(deleteCell);
                    tbody.appendChild(row);
                });


                table.appendChild(tbody);
                container.appendChild(table);
            }

            function deleteRow(row) {
                row.remove();
            }
        });




        // let indexRow = 0;
        // $(".search-result").on("click", ".search-result-item", function() {
        //     let name = $(this).find("h4").data("name");
        //     let code = $(this).find("p").data('code');
        //     let id = $(this).find("h6").data('id');
        //     let newRow = document.createElement("tr");

        //     let idCell = document.createElement("td");
        //     idCell.style.display = "none";
        //     let idInput = document.createElement("input");
        //     idInput.setAttribute("type", "hidden");
        //     idInput.setAttribute("name", `inputs[${indexRow}][product_id]`);
        //     idInput.value = id;
        //     idCell.appendChild(idInput);


        //     let codeCell = document.createElement("td");
        //     let codeInput = document.createElement("input");
        //     codeInput.setAttribute("type", "text");
        //     codeInput.setAttribute("name", `inputs[${indexRow}][code]`);
        //     codeInput.value = code;
        //     codeInput.style.width = "130px";
        //     codeCell.appendChild(codeInput);

        //     let nameCell = document.createElement("td");
        //     let nameInput = document.createElement("input");
        //     nameInput.setAttribute("type", "text");
        //     nameInput.setAttribute("name", `inputs[${indexRow}][name]`);
        //     nameInput.value = name;
        //     nameInput.style.width = "200px";
        //     nameCell.appendChild(nameInput);

        //     let manufacturingDateCell = document.createElement("td");
        //     let manufacturingDateInput = document.createElement("input");
        //     manufacturingDateInput.setAttribute("type", "date");
        //     manufacturingDateInput.setAttribute("name", `inputs[${indexRow}][manufacturing_date]`);
        //     manufacturingDateInput.style.width = "160px";
        //     manufacturingDateCell.appendChild(manufacturingDateInput);

        //     let expiryDateCell = document.createElement("td");
        //     let expiryDateInput = document.createElement("input");
        //     expiryDateInput.setAttribute("type", "date");
        //     expiryDateInput.setAttribute("name", `inputs[${indexRow}][expiry_date]`);
        //     expiryDateInput.style.width = "160px";
        //     expiryDateCell.appendChild(expiryDateInput);


        //     let quantityCell = document.createElement("td");
        //     let quantityControlDiv = document.createElement("div");
        //     quantityControlDiv.classList.add("quantity-control");
        //     let quantityInput = document.createElement("input");
        //     quantityInput.classList.add("quantity");
        //     quantityInput.setAttribute("type", "number");
        //     quantityInput.setAttribute("name", `inputs[${indexRow}][quantity]`);
        //     quantityInput.setAttribute("min", 1);
        //     quantityInput.style.width = "120px";
        //     quantityControlDiv.appendChild(quantityInput);
        //     quantityCell.appendChild(quantityControlDiv);

        //     let unitPriceCell = document.createElement("td");
        //     unitPriceInput = document.createElement("input");
        //     unitPriceInput.classList.add("unit-price");
        //     unitPriceInput.setAttribute("type", "number");
        //     unitPriceInput.setAttribute("name", `inputs[${indexRow}][unit-price]`);
        //     unitPriceInput.setAttribute("min", 0);
        //     unitPriceInput.style.width = "140px";
        //     unitPriceCell.appendChild(unitPriceInput);


        //     let discountCell = document.createElement("td");
        //     let discountInput = document.createElement("input");
        //     discountInput.classList.add("discount");
        //     discountInput.setAttribute("type", "number");
        //     discountInput.setAttribute("name", `inputs[${indexRow}][discount]`);
        //     discountInput.setAttribute("min", 0);
        //     discountInput.style.width = "140px";
        //     discountInput.value = 0;
        //     discountCell.appendChild(discountInput);

        //     let totalPriceCell = document.createElement("td");
        //     let totalPriceInput = document.createElement("input");
        //     totalPriceInput.classList.add("total-price");
        //     totalPriceInput.setAttribute("type", "number");
        //     totalPriceInput.setAttribute("min", 0);
        //     totalPriceInput.setAttribute("readonly", true);
        //     totalPriceInput.style.width = "140px";
        //     totalPriceCell.appendChild(totalPriceInput);

        //     let removeCell = document.createElement("td");
        //     let removeBtn = document.createElement("button");
        //     removeBtn.classList.add("remove-product");
        //     removeBtn.textContent = "Xóa";
        //     removeBtn.style.width = "100px";
        //     removeCell.appendChild(removeBtn);

        //     newRow.appendChild(idCell);
        //     newRow.appendChild(codeCell);
        //     newRow.appendChild(nameCell);
        //     newRow.appendChild(manufacturingDateCell);
        //     newRow.appendChild(expiryDateCell);
        //     newRow.appendChild(quantityCell);
        //     newRow.appendChild(unitPriceCell);
        //     newRow.appendChild(discountCell);
        //     newRow.appendChild(totalPriceCell);
        //     newRow.appendChild(removeCell);

        //     document.querySelector("#body-product-table").appendChild(newRow);
        //     $(".search-result").css("display", "none");
        //     indexRow++;
        // })

        //Ham cap nhat gia tri total-price
        // function updateTotalPrice(row) {
        //     let quantity = parseFloat(row.find(".quantity").val());
        //     let unitPrice = parseFloat(row.find(".unit-price").val());
        //     let discount = parseFloat(row.find(".discount").val());
        //     let totalPrice = quantity * unitPrice - discount;
        //     row.find(".total-price").val(totalPrice);
        // }
        // //Cap nhat gia tri total-price
        // $("#product-table").on("input", ".quantity,.unit-price,.discount", function() {
        //     updateTotalPrice($(this).closest("tr"));
        // })

        // //Ham cap nhat total_amount
        // function updateTotalAmount() {
        //     let sum = 0;
        //     $("#product-table tr").each(function() {
        //         let quantity = parseFloat($(this).find(".quantity").val());
        //         let unitPrice = parseFloat($(this).find(".unit-price").val());
        //         let discount = parseFloat($(this).find(".discount").val());
        //         let totalPrice = quantity * unitPrice - discount;
        //         if (!isNaN(totalPrice)) {
        //             sum += totalPrice;
        //         }
        //     })
        //     $(".total_amount").val(sum);
        // }

        // //Cap nhat total-amount
        // $("#product-table").on("input", ".quantity,.unit-price,.discount,.total-price", function() {
        //     updateTotalAmount();
        // })

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

        // $("#product-table").on("click", ".remove-product", function() {
        //     $(this).closest("tr").remove();
        //     updateTotalAmount();
        // })
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
