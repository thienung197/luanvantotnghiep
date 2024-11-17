@extends('layouts.app')
@section('title', 'Quản lý phiếu mua hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý phiếu mua hàng
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="">Phiếu xuất hàng</a></p>
        </div>
    </div>
    {{-- <div class="btn-cs btn-add">
        <a href="{{ route('goodsissues.create') }}">Thêm phiếu xuất hàng</a>
    </div> --}}
    <div class="table_container">
        <div class="table_title">
            Danh sách phiếu mua hàng
        </div>
        <div class="table_filter-controls">
            <form action="{{ route('goodsissues.index') }}" method="GET">
                <label for="">Hiển thị </label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                mục
            </form>
            <div class="table_search-box">
                <form action="{{ route('goodsissues.index') }}" method="GET">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập tên phiếu xuất hàng">
                    <button type="submit">Tìm </button>
                </form>
            </div>
        </div>
        <table class="table" id="table-list">
            <tr>
                <th>Mã phiếu mua hàng</th>
                <th>Thời gian</th>
                <th>Nhà cung cấp</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>

            @foreach ($purchaseOrders as $purchaseOrder)
                <tr class="goods-issue-row" data-id="{{ $purchaseOrder->id }}">
                    <td>{{ $purchaseOrder->code }}</td>
                    <td>{{ $purchaseOrder->created_at }}</td>
                    <td>{{ $purchaseOrder->provider->name }}</td>
                    <td>
                        @if ($purchaseOrder->status == 'created')
                            Đơn hàng đã được tạo và gửi đến nhà cung cấp
                        @elseif($purchaseOrder->status == 'fulfilled')
                            Đã tạo phiếu nhập kho
                        @endif
                    </td>
                    <td>
                        <button>Tạo phiếu nhập kho</button>
                    </td>
                    {{-- <td class="btn-cell">
                        <a href="{{ route('goodsissues.edit', $purchaseOrder->id) }}">
                            <img src="{{ asset('img/edit.png') }}" alt="">
                        </a>
                        <form action="{{ route('goodsissues.destroy', $purchaseOrder->id) }}" method="POST"
                            id="form-delete{{ $purchaseOrder->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $purchaseOrder->id }}">
                            <img src="{{ asset('img/delete.png') }}" alt="">
                        </button>
                    </td> --}}
                </tr>

                <tr class="goods-issue-details" id="details-{{ $purchaseOrder->id }}" style="display: none;">
                    <td colspan="5">
                        <div class="details-container">
                            <strong>Thông tin phiếu mua hàng</strong>
                            <table class="table table-bordered" id="product-table-{{ $purchaseOrder->id }}">
                                <thead>
                                    <tr>
                                        <th>Mã hàng</th>
                                        <th>Tên hàng</th>
                                        <th>Đơn vị tính</th>
                                        <th>Số lượng</th>
                                        <th>Giá bán</th>
                                        <th>Giảm giá</th>
                                        <th>Thành tiền</th>
                                        <th>Phân phối hàng hóa</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder->purchaseOrderDetails as $detail)
                                        <form action="{{ route('goodsreceipts.store-receipt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="provider_id"
                                                value="{{ $purchaseOrder->provider->id }}">
                                            <input type="hidden" name="product_id" value="{{ $detail->product->id }}">

                                            <tr>
                                                <td>{{ $detail->product->code }}</td>
                                                <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                                <td>{{ $detail->product->unit->name }}</td>
                                                <td>{{ $detail->quantity }}</td>
                                                <td class="goods-issue-unit-price">
                                                    {{ number_format($detail->unit_price, 2) }}</td>
                                                <td class="goods-issue-discount">{{ number_format($detail->discount, 2) }}
                                                </td>
                                                <td>{{ number_format($detail->quantity * $detail->unit_price - $detail->discount, 2) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $filteredDistributions = $distributionData->where(
                                                            'product_id',
                                                            $detail->product->id,
                                                        );
                                                    @endphp
                                                    @if ($filteredDistributions->isNotEmpty())
                                                        <ul>
                                                            @foreach ($filteredDistributions as $distribution)
                                                                <li>
                                                                    {{ $warehouses->firstWhere('id', $distribution->warehouse_id)->name ?? 'N/A' }}:
                                                                    <br />
                                                                    Yêu cầu: {{ $distribution->quantity }}
                                                                    Phân phối:
                                                                    <input type="number" style="width: 80px"
                                                                        value="{{ $distribution->quantity }}"
                                                                        name="distributions[{{ $distribution->warehouse_id }}][quantity]">

                                                                    <!-- Tạo các input ẩn cho warehouse_id, unit_price, discount -->
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
                                                    @else
                                                        Không có dữ liệu phân phối
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary">Phân phối</button>
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                            <p>Tổng tiền hàng: {{ $purchaseOrder->total_amount }}</p>
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
        .batch-table input {
            border: none;
        }

        .suggestion-container {
            display: none;
        }
    </style>
@endpush

@push('js')
    <script>
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
                    } else {
                        detailsRow.style.display = "none";
                    }
                });
            });
        });
    </script>
@endpush
