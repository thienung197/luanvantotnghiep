@extends('layouts.app')
@section('title', 'Phiếu đặt hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Phiếu đặt hàng và phân phối hàng hóa
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phân phối hàng hóa</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu đặt hàng
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('goodsissues.index') }}" method="GET">
                {{-- <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục --}}
            </form>
            <div class="table_search-box">
                {{-- <form action="{{ route('goodsissues.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu xuất hàng">
                    <button type="submit">Tìm </button>
                </form> --}}
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Mã phiếu mua hàng</th>
                <th>Thời gian</th>
                <th>Người tạo</th>
                <th>Nhà cung cấp</th>
                <th>Trạng thái</th>
                {{-- <th>Thao tác</th> --}}
            </tr>

            @foreach ($purchaseOrders as $purchaseOrder)
                <tr class="goods-issue-row" data-id="{{ $purchaseOrder->id }}">
                    <td>{{ $purchaseOrder->code }}</td>
                    <td>{{ $purchaseOrder->created_at }}</td>
                    <td>{{ $purchaseOrder->user->name }}</td>
                    <td>{{ $purchaseOrder->provider->name }}</td>
                    <td>
                        @if ($purchaseOrder->status == 'pending')
                            <span class="order-status" style="background-color: #aaae7c">Chưa được ghi nhận</span>
                        @elseif($purchaseOrder->status == 'processing')
                            <span class="order-status" style="background-color: yellow;color:green !important">Đang ghi nhận
                            </span>
                        @else
                            <span class="order-status">Đã ghi nhận xong </span>
                        @endif
                    </td>
                </tr>

                <tr class="goods-issue-details" id="details-{{ $purchaseOrder->id }}" style="display: none;">
                    <td colspan="5">
                        <div class="details-container">
                            @if ($purchaseOrder->recordedProducts->isNotEmpty())
                                <strong>Các hàng hóa đã phân phối</strong>

                                <table class="table table-bordered table-product"
                                    id="product-table-{{ $purchaseOrder->id }}">
                                    <thead>
                                        <tr>
                                            <th>Mã hàng</th>
                                            <th>Tên hàng</th>
                                            <th>Đơn vị tính</th>
                                            <th>Số lượng</th>
                                            <th>NSX - HSD (nếu có)</th>
                                            <th>Giá bán (Đơn vị VNĐ)</th>
                                            <th>Giảm giá (Đơn vị VNĐ)</th>
                                            <th>Thành tiền (Đơn vị VNĐ)</th>
                                            <th>Phân phối hàng hóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseOrder->recordedProducts as $product)
                                            <tr>
                                                <td>{{ $product->product_code }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->unit_name }}</td>
                                                <td>{{ $product->warehouse_quantity }}</td>
                                                <td>{{ $product->nsx }} - {{ $product->hsd }}</td>
                                                <td>{{ number_format($product->unit_price, 2) }}</td>
                                                <td>{{ number_format($product->discount, 2) }}</td>
                                                <td>{{ number_format($product->received_quantity * $product->unit_price - $product->discount, 2) }}
                                                </td>
                                                <td>{{ $product->warehouse_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                            @if ($purchaseOrder->purchaseOrderDetails->isNotEmpty())
                                <strong class="order-label">Ghi nhận và phân phối hàng hóa</strong>
                                <table class="table table-bordered table-product"
                                    id="product-table-{{ $purchaseOrder->id }}">
                                    <thead>
                                        <tr>
                                            <th>Mã hàng</th>
                                            <th>Tên hàng</th>
                                            <th>Đơn vị tính</th>
                                            <th>Số lượng</th>
                                            <th>NSX- HSD (nếu có)</th>
                                            <th>Giá bán<br> (Đơn vị VNĐ)</th>
                                            <th>Giảm giá <br> (Đơn vị VNĐ)</th>
                                            <th>Thành tiền <br> (Đơn vị VNĐ)</th>
                                            <th>Phân phối hàng hóa</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($purchaseOrder->purchaseOrderDetails as $detail)
                                            <form action="{{ route('goodsreceipts.store-receipt') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                                <input type="hidden" name="purchase_order"
                                                    value="{{ $purchaseOrder->id }}">
                                                <input type="hidden" name="provider_id"
                                                    value="{{ $purchaseOrder->provider->id }}">
                                                <input type="hidden" name="product_id" value="{{ $detail->product->id }}">

                                                <tr>

                                                    <td>{{ $detail->product->code }}</td>
                                                    <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->product->unit->name }}</td>
                                                    <td class="order-details">
                                                        Số lượng đặt:<br> <span
                                                            class="quantity">{{ $detail->quantity }}</span><br>
                                                        Số lượng giao:<br>
                                                        <input type="number" class="quantity-input"
                                                            name="delivered_quantity"
                                                            value="{{ $detail->delivered_quantity }}" min="0">
                                                    </td>
                                                    <td>
                                                        <div class="date-group">
                                                            <div class="date-field">
                                                                <label for="nsx">Ngày sản xuất:</label>
                                                                <input type="date" name="nsx" id="nsx"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="date-field">
                                                                <label for="hsd">Hạn sử dụng:</label>
                                                                <input type="date" name="hsd" id="hsd"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="unit-price" name="unit_price"
                                                            value="{{ $detail->unit_price }}" min="0">
                                                    </td>

                                                    <td>
                                                        <input type="number" class="discount" name="discount"
                                                            value="{{ $detail->discount }}" min="0">
                                                    </td>

                                                    <td>
                                                        <input type="number" class="total-price" name="total_price"
                                                            value="" readonly>
                                                    </td>
                                                    <td class="distribution-details">
                                                        {{-- @php
                                                        $filteredDistributions = $distributionData->where(
                                                            'product_id',
                                                            $detail->product->id,
                                                        );
                                                    @endphp
                                                    @if ($filteredDistributions->isNotEmpty()) --}}
                                                        <ul class="distribution-list">
                                                            @foreach ($detail->requestDetails as $distribution)
                                                                <li class="distribution-item">
                                                                    <span class="warehouse-name">
                                                                        {{ $warehouses->firstWhere('id', $distribution->warehouse_id)->name ?? 'N/A' }}:
                                                                    </span>
                                                                    <div class="distribution-info">
                                                                        <span class="distribution-request">
                                                                            Yêu cầu nhập: <span class="order-status">
                                                                                {{ $distribution->quantity }}</span>
                                                                        </span>
                                                                        <span class="distribution-allocated">
                                                                            Ngày yêu cầu:<span style="color: red">
                                                                                {{ $distribution->created_at }}</span>
                                                                        </span>

                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="distributions[{{ $distribution->warehouse_id }}][quantity]"
                                                                        value="{{ $distribution->quantity }}">
                                                                    <input type="hidden"
                                                                        name="distributions[{{ $distribution->warehouse_id }}][warehouse_id]"
                                                                        value="{{ $distribution->warehouse_id }}">
                                                                    <input type="hidden"
                                                                        name="distributions[{{ $distribution->warehouse_id }}][product_id]"
                                                                        value="{{ $detail->product->id }}">
                                                                    <input type="hidden"
                                                                        name="distributions[{{ $distribution->warehouse_id }}][unit_price]"
                                                                        value="{{ $detail->unit_price }}">
                                                                    <input type="hidden"
                                                                        name="distributions[{{ $distribution->warehouse_id }}][discount]"
                                                                        value="{{ $detail->discount }}">
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        {{-- @else
                                                        <p class="no-distribution-data">Không có dữ liệu phân phối</p>
                                                    @endif --}}
                                                    </td>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary btn-custom">Phân
                                                            phối</button>
                                                    </td>
                                                </tr>
                                            </form>
                                        @endforeach


                                    </tbody>
                                </table>
                            @endif
                            {{-- <p>Tổng tiền hàng:
                                {{ $purchaseOrder->total_amount }} 
                            </p> --}}
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- {{ $goodsReceipts->links() }} --}}
    </div>
@endsection

@push('css')
    <style>
        .distribution-info .order-status {
            padding: 1px 15px;
        }

        .batch-table input {
            border: none;
        }

        .suggestion-container {
            display: none;
        }

        table input {
            max-width: 120px;
            padding: 4px;
            border-radius: 5px;
        }

        .order-details {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .quantity {
            font-weight: bold;
            color: #007bff;
        }

        .quantity-input {
            width: 80px;
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            text-align: center;
            margin-top: 5px;
        }

        .quantity-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .distribution-details {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            padding: 10px;
            color: #333;
        }

        .distribution-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .distribution-item {
            margin-bottom: 15px;
            padding: 10px;
            /* background: #f9f9f9;
                                                                                                                                                                                                    border: 1px solid #ddd; */
            border-radius: 5px;
        }

        .warehouse-name {
            font-weight: bold;
            color: #007bff;
            font-size: 16px;
        }

        .distribution-info {
            margin-top: 5px;
            font-size: 13px;
            color: #555;
        }

        .distribution-request,
        .distribution-allocated {
            display: block;
            margin-bottom: 3px;
            font-size: 16px;
        }

        .no-distribution-data {
            font-style: italic;
            color: #999;
        }

        .distribution-details input[type="hidden"] {
            display: none;
        }

        .date-group {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .date-field {
            display: flex;
            flex-direction: column;
            font-size: 14px;
            color: #333;
        }

        .date-field label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .date-field input {
            width: 160px;
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
        }

        .date-field input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("tr");

            rows.forEach(row => {
                const quantityInput = row.querySelector(".quantity-input");
                const unitPriceInput = row.querySelector(".unit-price");
                const discountInput = row.querySelector(".discount");
                const totalPriceInput = row.querySelector(".total-price");
                if (!quantityInput || !unitPriceInput || !discountInput || !totalPriceInput) {
                    console.warn("Bỏ qua hàng vì thiếu phần tử cần thiết");
                    return;
                }

                function calculateTotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;

                    const total = (quantity * unitPrice) - discount;

                    totalPriceInput.value = total > 0 ? total.toFixed(2) : '';
                }

                quantityInput.addEventListener("input", calculateTotal);
                discountInput.addEventListener("input", calculateTotal);

                calculateTotal();
            });
        });

        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif

        // document.addEventListener('DOMContentLoaded', function() {
        //     const btnCreate = document.getElementById('btn-create');
        //     const suggestionContainer = document.querySelector('.suggestion-container');

        //     btnCreate.addEventListener('click', function() {
        //         if (suggestionContainer.style.display === 'none' || suggestionContainer.style.display ===
        //             '') {
        //             suggestionContainer.style.display = 'block';
        //         } else {
        //             suggestionContainer.style.display = 'none';
        //         }
        //     });
        // });

        $(document).on("click", "#btn-distribute", function(e) {
            let goodIssueId = $(".goods-issue-id").first().text().trim();
            console.log($(".goods-issue-id"));
            $("#goods-issue").val(goodIssueId);

            $('#distribution-form').submit();
        });

        $(document).on("click", "#btn-create", function() {
            let batchId = $("#goodIssueId").text().trim();
            let productsData = [];
            $(`#product-table-${batchId} tbody tr`).each(function() {

                let locationId = $("#customer_location_id").text().trim();
                let productId = $(this).find('td').eq(1).text().trim();
                let quantity = $(this).find('td').eq(4).text().trim();
                let unitPrice = $(this).find('td').eq(5).text().trim();
                let discount = $(this).find('td').eq(6).text().trim();
                let totalPrice = $(this).find('td').eq(7).text().trim();
                if (locationId && quantity && productId) {
                    productsData.push({
                        productId: productId,
                        quantity: quantity,
                        locationId: locationId,
                        unitPrice,
                        discount,
                        totalPrice
                    });

                }
            });
            console.log(productsData);

            if (productsData.length > 0) {
                fetchBatches(productsData);
            } else {
                console.error("No valid product data found.");
            }
        })

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
                        productInput.setAttribute("type", "hidden");
                        productInput.setAttribute("readonly", true);
                        productInput.setAttribute("name", `batchData[${productId}][product_id]`);
                        productInput.setAttribute("value", productId);
                        productCell.appendChild(productInput);
                        newRow.appendChild(productCell);

                        // Total Required Cell with Input
                        let totalRequiredCell = document.createElement("td");
                        let totalRequiredInput = document.createElement("input");
                        totalRequiredInput.setAttribute("type", "hidden");
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

                            let unitPriceCell = document.createElement("td");
                            let unitPriceInput = document.createElement("input");
                            unitPriceInput.setAttribute("type", "hidden");
                            unitPriceInput.setAttribute("readonly", true);
                            unitPriceInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][unit_price]`
                            );
                            unitPriceInput.setAttribute("value", batch.unitPrice);
                            unitPriceCell.appendChild(unitPriceInput);
                            batchRow.appendChild(unitPriceCell);

                            let discountCell = document.createElement("td");
                            let discountInput = document.createElement("input");
                            discountInput.setAttribute("type", "hidden");
                            discountInput.setAttribute("readonly", true);
                            discountInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][discount]`
                            );
                            discountInput.setAttribute("value", batch.discount);
                            discountCell.appendChild(discountInput);
                            batchRow.appendChild(discountCell);

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

        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".goods-issue-row");

            rows.forEach(row => {
                row.addEventListener("click", function() {
                    const purchaseOrderId = this.getAttribute("data-id");
                    const detailsRow = document.getElementById(`details-${purchaseOrderId}`);

                    if (detailsRow.style.display === "none") {
                        detailsRow.style.display = "table-row";
                        row.style.backgroundColor = "rgb(230, 247, 236)";
                    } else {
                        detailsRow.style.display = "none";
                        row.style.backgroundColor = "#fff";
                    }
                });
            });
        });
    </script>
@endpush
