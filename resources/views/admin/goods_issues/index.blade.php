@extends('layouts.app')
@section('title', 'Quản lý đơn hàng')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Quản lý đơn hàng
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
            Danh sách đơn hàng
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
                <th>Mã đơn hàng</th>
                {{-- <th>Mã khách hàng</th> --}}
                <th>Tên khách hàng</th>
                <th>Thời gian</th>
                <th>Tổng tiền hàng</th>
                <th>Trạng thái</th>
                <th>Người duyệt</th>
                {{-- <th>Thao tác</th> --}}
            </tr>

            @foreach ($goodsIssues as $goodsIssue)
                <tr class="goods-issue-row" data-id="{{ $goodsIssue->id }}">
                    <td>{{ $goodsIssue->code }}</td>
                    {{-- <td>{{ $goodsIssue->getCustomerCode() }}</td> --}}
                    <td>{{ $goodsIssue->getCustomerName() }}</td>
                    <td>{{ $goodsIssue->created_at }}</td>
                    <td>{{ $goodsIssue->getTotalAmount() }}</td>
                    <td>
                        @if ($goodsIssue->status == 'pending')
                            Đơn hàng chưa được xử lý
                        @elseif($goodsIssue->status == 'approved')
                            Đơn hàng đã được phê duyệt
                        @elseif($goodsIssue->status == 'processing')
                            Đơn hàng đang trong quá trình lấy hàng từ kho
                        @elseif($goodsIssue->status == 'shipping')
                            Đơn hàng đang được vận chuyển
                        @elseif($goodsIssue->status == 'delivered')
                            Đơn hàng đã được giao thành công
                        @endif
                    </td>
                    <td>
                        @if ($goodsIssue->approvedByUser)
                            {{ $goodsIssue->approvedByUser->name }}
                        @else
                            Chưa có người phê duyệt
                        @endif
                    </td>
                    {{-- <td class="btn-cell">
                        <a href="{{ route('goodsissues.edit', $goodsIssue->id) }}">
                            <img src="{{ asset('img/edit.png') }}" alt="">
                        </a>
                        <form action="{{ route('goodsissues.destroy', $goodsIssue->id) }}" method="POST"
                            id="form-delete{{ $goodsIssue->id }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button type="submit" class="btn-delete" data-id="{{ $goodsIssue->id }}">
                            <img src="{{ asset('img/delete.png') }}" alt="">
                        </button>
                    </td> --}}
                </tr>

                <tr class="goods-issue-details" id="details-{{ $goodsIssue->id }}" style="display: none;">
                    <td colspan="7">
                        <div class="details-container">
                            <strong>Thông tin đơn hàng</strong>
                            <div class="customer-info-container">
                                <p><span>Tên khách hàng:</span>{{ $goodsIssue->getCustomerName() }}</p>
                                <p><span>Điện thoại:</span> {{ $goodsIssue->getCustomerPhone() }}</p>
                                <p><span>Địa chỉ:</span> {{ $goodsIssue->getCustomerAddress() }}</p>
                                <p style="display: none" id="customer_location_id">
                                    {{ $goodsIssue->getCustomerLocationId() }}


                                </p>
                            </div>
                            <p style="display: none" id="goodIssueId">{{ $goodsIssue->id }}</p>
                            <strong>Sản phẩm</strong>
                            <table class="table table-bordered " id="product-table-{{ $goodsIssue->id }}">
                                <thead>
                                    <tr>
                                        <th>Mã hàng</th>
                                        <th>Tên hàng</th>
                                        <th>Số lượng</th>
                                        <th>Giá bán</th>
                                        <th>Giảm giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($goodsIssue->goodsIssueDetails as $detail)
                                        <tr>
                                            <td style="display: none" class="goods-issue-id">{{ $detail->goods_issue_id }}
                                            </td>
                                            <td style="display: none">{{ $detail->product->id }}</td>
                                            <td>{{ $detail->product->code }}</td>
                                            <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td class="goods-issue-unit-price">{{ number_format($detail->unit_price, 2) }}
                                            </td>
                                            <td class="goods-issue-discount">{{ number_format($detail->discount, 2) }}</td>
                                            <td>{{ number_format($detail->quantity * $detail->unit_price - $detail->discount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex button-container">
                                <button id="btn-create">Đề xuất</button>
                                <button id="btn-distribute">Phân kho </button>
                            </div>
                            <div id="warehouse-details-container">
                                <strong>Bảng khoảng cách từ nhà kho đến vị trí khách hàng, tính bằng km</strong>
                            </div>
                            <div id="batch-details-container">
                                <strong>Thông tin về các lô hàng tại có nhà kho</strong>
                            </div>
                            <div class="suggestion-container">
                                <strong>Đề xuất phân phối lô hàng từ nhà kho</strong>

                                <form action="{{ route('admin.goodsissue.store') }}" method="POST" id="distribution-form">
                                    @csrf
                                    <input type="hidden" name="goods-issue" id="goods-issue">

                                    {{-- <h6>Chọn <span id="batch-product-name"></span> sản phẩm từ lô hàng </h6> --}}
                                    <table id="batch-table-{{ $goodsIssue->id }}" class="table table-border batch-table">
                                        <thead>
                                            <tr>
                                                <th>Số lô</th>
                                                <th>Ngày sản xuất</th>
                                                <th>Ngày hết hạn</th>
                                                <th>Số lượng có sẵn</th>
                                                <th>Số lượng chọn</th>
                                                <th>Đơn giá</th>
                                                <th>Giảm giá</th>
                                                <th>Xuất từ kho</th>
                                            </tr>
                                        </thead>
                                        <tbody id="batch-tbody">
                                        </tbody>
                                    </table>
                                </form>
                            </div>
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
        .table-list tr td {
            padding: 0;
        }

        .details-container {
            padding: 20px;
        }

        .batch-table input {
            border: none;
        }

        .suggestion-container {
            display: none;
        }



        .customer-info-container {
            width: 50%;
        }


        .form-control {
            color: var(--color-black);
        }

        .button-container button {
            padding: 10px 20px;
            border-radius: 20px;
            background-color: var(--color-green);
            color: var(--color-white);
            margin: 10px;
        }
    </style>
@endpush

@push('js')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const btnCreate = document.getElementById('btn-create');
            const suggestionContainer = document.querySelector('.suggestion-container');

            btnCreate.addEventListener('click', function() {
                if (suggestionContainer.style.display === 'none' || suggestionContainer.style.display ===
                    '') {
                    suggestionContainer.style.display = 'block';
                } else {
                    suggestionContainer.style.display = 'none';
                }
            });
        });

        $(document).on("click", "#btn-distribute", function(e) {
            let goodIssueId = $(".goods-issue-id").first().text().trim();
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

            if (productsData.length > 0) {
                fetchBatches(productsData);
            } else {
                console.error("No valid product data found.");
            }
        })

        function createWarehouseDetail(warehouseDetails) {
            const tableContainer = document.getElementById("table-container");

            const table = document.createElement("table");
            table.classList.add("table", "table-bordered");

            const thead = document.createElement("thead");
            const headerRow = document.createElement("tr");

            const headerName = document.createElement("th");
            headerName.textContent = "Tên nhà kho";
            headerRow.appendChild(headerName);

            const headerLocation = document.createElement("th");
            headerLocation.textContent = "Vị trí nhà kho";
            headerRow.appendChild(headerLocation);

            const headerDistance = document.createElement("th");
            headerDistance.textContent = "Khoảng cách đến vị trí khách hàng (km)";
            headerRow.appendChild(headerDistance);

            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement("tbody");

            warehouseDetails.forEach(warehouse => {
                const row = document.createElement("tr");

                const nameCell = document.createElement("td");
                nameCell.textContent = warehouse.name;
                row.appendChild(nameCell);

                const locationCell = document.createElement("td");
                const location = warehouse.location;
                const addressParts = [];

                if (location.street_address && location.street_address !== 'N/A') {
                    addressParts.push(location.street_address);
                }

                if (location.ward && location.ward !== 'N/A') {
                    addressParts.push(location.ward);
                }

                if (location.district && location.district !== 'N/A') {
                    addressParts.push(location.district);
                }

                if (location.city && location.city !== 'N/A') {
                    addressParts.push(location.city);
                }

                locationCell.textContent = addressParts.join(', ');

                row.appendChild(locationCell);

                const distanceCell = document.createElement("td");
                distanceCell.textContent = warehouse.distance;
                row.appendChild(distanceCell);

                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            document.querySelector("#warehouse-details-container").innerHTML = '';
            document.querySelector("#warehouse-details-container").appendChild(table);
        }

        function createBatchDetail(groupedBatchDetails) {
            const container = document.getElementById('batch-details-container');

            // Tạo phần header cho tab
            const tabList = document.createElement('ul');
            tabList.classList.add('nav', 'nav-tabs');
            container.appendChild(tabList);

            // Tạo phần nội dung cho tab
            const tabContent = document.createElement('div');
            tabContent.classList.add('tab-content');
            container.appendChild(tabContent);

            let isFirstTab = true;

            Object.keys(groupedBatchDetails).forEach((productName, index) => {
                const productData = groupedBatchDetails[productName];

                // Tạo tab
                const tabItem = document.createElement('li');
                tabItem.classList.add('nav-item');
                const tabLink = document.createElement('a');
                tabLink.classList.add('nav-link');
                tabLink.href = `#tab-${index}`;
                tabLink.setAttribute('data-toggle', 'tab');
                tabLink.textContent = productName;
                if (isFirstTab) {
                    tabLink.classList.add('active');
                }
                tabItem.appendChild(tabLink);
                tabList.appendChild(tabItem);

                // Tạo nội dung cho tab
                const tabPane = document.createElement('div');
                tabPane.classList.add('tab-pane', 'fade');
                if (isFirstTab) {
                    tabPane.classList.add('show', 'active');
                    isFirstTab = false;
                }
                tabPane.id = `tab-${index}`;

                // Tạo bảng
                const table = document.createElement('table');
                table.classList.add('table', 'table-bordered');

                // Header của bảng
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                const headers = [
                    'Nhà kho',
                    'Số lô',
                    'Ngày hết hạn',
                    'Ngày sản xuất',
                    'Số lượng'
                ];
                headers.forEach(headerText => {
                    const th = document.createElement('th');
                    th.textContent = headerText;
                    headerRow.appendChild(th);
                });
                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Body của bảng
                const tbody = document.createElement('tbody');
                productData.forEach(item => {
                    const row = document.createElement('tr');

                    const warehouseCell = document.createElement('td');
                    warehouseCell.textContent = item.warehouse_name;
                    row.appendChild(warehouseCell);

                    const batchCodeCell = document.createElement('td');
                    batchCodeCell.textContent = item.batch_code;
                    row.appendChild(batchCodeCell);

                    const expiryDateCell = document.createElement('td');
                    expiryDateCell.textContent = item.expiry_date || 'N/A';
                    row.appendChild(expiryDateCell);

                    const manufacturingDateCell = document.createElement('td');
                    manufacturingDateCell.textContent = item.manufacturing_date || 'N/A';
                    row.appendChild(manufacturingDateCell);

                    const quantityCell = document.createElement('td');
                    quantityCell.textContent = item.quantity_available;
                    row.appendChild(quantityCell);

                    tbody.appendChild(row);
                });
                table.appendChild(tbody);
                tabPane.appendChild(table);

                tabContent.appendChild(tabPane);
            });
        }

        function fetchBatches(productsData) {
            $.ajax({
                url: "{{ route('fetch-batches') }}",
                method: "get",
                data: {
                    productsData: JSON.stringify(productsData)
                },
                success: function(res) {
                    console.log(res.batches);

                    const warehouseDetails = res.warehouseDetails;
                    const batchDetails = res.batchDetails;
                    createWarehouseDetail(warehouseDetails);
                    createBatchDetail(batchDetails);
                    let batchTbody = document.getElementById("batch-tbody");
                    batchTbody.innerHTML = "";

                    res.batches.forEach(productBatches => {

                        let productId = productBatches.productId;

                        let totalQuantityRequired = productBatches.batches.reduce((total, batch) =>
                            total + batch.quantityToTake, 0);
                        console.log(totalQuantityRequired);

                        let newRow = document.createElement("tr");
                        newRow.style.display = "none";

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
                        console.log(totalQuantityRequired);

                        totalRequiredInput.setAttribute("value", totalQuantityRequired);
                        totalRequiredCell.appendChild(totalRequiredInput);
                        newRow.appendChild(totalRequiredCell);

                        batchTbody.appendChild(newRow);

                        productBatches.batches.forEach((batch, index) => {
                            let batchRow = document.createElement("tr");



                            let batchIdCell = document.createElement("td");
                            batchIdCell.style.display = "none";
                            let batchIdInput = document.createElement("input");
                            batchIdInput.setAttribute("type", "text");
                            batchIdInput.setAttribute("readonly", true);
                            batchIdInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][batch_id]`);
                            batchIdInput.setAttribute("value", batch.batch_id);
                            batchIdInput.classList.add("form-control");

                            batchIdCell.appendChild(batchIdInput);
                            batchRow.appendChild(batchIdCell);

                            let batchCodeCell = document.createElement("td");
                            let batchCodeInput = document.createElement("input");
                            batchCodeInput.setAttribute("type", "text");
                            batchCodeInput.setAttribute("readonly", true);
                            batchCodeInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][batch_code]`);
                            batchCodeInput.setAttribute("value", batch.batch_code);
                            batchCodeInput.classList.add("form-control");
                            batchCodeInput.style.textAlign = "right";
                            batchCodeCell.appendChild(batchCodeInput);
                            batchRow.appendChild(batchCodeCell);

                            // Ngay sx
                            let manufacturingDateCell = document.createElement("td");
                            let manufacturingDateInput = document.createElement("input");
                            manufacturingDateInput.setAttribute("type", "text");
                            manufacturingDateInput.setAttribute("readonly", true);
                            manufacturingDateInput.classList.add("form-control");
                            manufacturingDateInput.style.textAlign = "right";
                            manufacturingDateInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][manufacturing_date]`
                            );
                            manufacturingDateInput.setAttribute("value", batch
                                .manufacturing_date ?? 'N/A');
                            manufacturingDateCell.appendChild(manufacturingDateInput);
                            batchRow.appendChild(manufacturingDateCell);

                            //Han sd
                            let expiryDateCell = document.createElement("td");
                            let expiryDateInput = document.createElement("input");
                            expiryDateInput.setAttribute("type", "text");
                            expiryDateInput.setAttribute("readonly", true);
                            expiryDateInput.classList.add("form-control");
                            expiryDateInput.style.textAlign = "right";
                            expiryDateInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][expiry_date]`);
                            expiryDateInput.setAttribute("value", batch.expiry_date ?? 'N/A');
                            expiryDateCell.appendChild(expiryDateInput);
                            batchRow.appendChild(expiryDateCell);

                            // Available Quantity Cell with Input
                            let availableQuantityCell = document.createElement("td");
                            let availableQuantityInput = document.createElement("input");
                            availableQuantityInput.setAttribute("type", "text");
                            availableQuantityInput.classList.add("form-control");
                            availableQuantityInput.style.textAlign = "right";
                            availableQuantityInput.setAttribute("readonly", true);
                            availableQuantityInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][quantity_available]`
                            );
                            availableQuantityInput.setAttribute("value", batch
                                .quantityAvailable);
                            availableQuantityCell.appendChild(availableQuantityInput);
                            batchRow.appendChild(availableQuantityCell);

                            // Quantity to Take Cell with Editable Input
                            let quantityCell = document.createElement("td");
                            let quantityInput = document.createElement("input");
                            quantityInput.setAttribute("type", "number");
                            quantityInput.setAttribute("class", "batch-quantity");
                            quantityInput.classList.add("form-control");
                            quantityInput.style.textAlign = "right";
                            quantityInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][quantity]`);
                            quantityInput.setAttribute("value", batch.quantityToTake);
                            quantityInput.setAttribute("min", 1);
                            quantityInput.setAttribute("max", batch.quantity);
                            quantityCell.appendChild(quantityInput);
                            batchRow.appendChild(quantityCell);

                            let unitPriceCell = document.createElement("td");
                            let unitPriceInput = document.createElement("input");
                            unitPriceInput.setAttribute("type", "number");
                            // unitPriceInput.setAttribute("class", "batch-unitPrice");
                            unitPriceInput.classList.add("form-control");
                            unitPriceInput.style.textAlign = "right";
                            unitPriceInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][unit_price]`);
                            let unitPrice = parseFloat(batch.unitPrice.replace(/,/g, '') ||
                                0); // Loại bỏ dấu phẩy
                            unitPriceInput.setAttribute("value", unitPrice);
                            unitPriceCell.appendChild(unitPriceInput);
                            batchRow.appendChild(unitPriceCell);

                            let discountCell = document.createElement("td");
                            let discountInput = document.createElement("input");
                            discountInput.setAttribute("type", "number");
                            // discountInput.setAttribute("class", "batch-discount");
                            discountInput.classList.add("form-control");
                            discountInput.style.textAlign = "right";
                            discountInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][discount]`);
                            let discount = parseFloat(batch.discount.replace(/,/g, '') ||
                                0); // Loại bỏ dấu phẩy
                            discountInput.setAttribute("value", discount);
                            discountCell.appendChild(discountInput);
                            batchRow.appendChild(discountCell);

                            // Warehouse ID Cell with Input
                            let warehouseCell = document.createElement("td");
                            let warehouseInput = document.createElement("input");
                            warehouseInput.setAttribute("type", "text");
                            warehouseInput.setAttribute("readonly", true);
                            warehouseInput.classList.add("form-control");
                            warehouseInput.style.textAlign = "right";
                            warehouseInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][warehouse]`);
                            warehouseInput.setAttribute("value", batch.warehouse);
                            warehouseCell.appendChild(warehouseInput);
                            batchRow.appendChild(warehouseCell);

                            let warehouseIdCell = document.createElement("td");
                            warehouseIdCell.style.display = "none";
                            let warehouseIdInput = document.createElement("input");
                            warehouseIdInput.setAttribute("type", "text");
                            warehouseIdInput.setAttribute("readonly", true);
                            warehouseIdInput.classList.add("form-control");
                            warehouseIdInput.style.textAlign = "right";
                            warehouseIdInput.setAttribute("name",
                                `batchData[${productId}][batches][${index}][warehouse_id]`);
                            warehouseIdInput.setAttribute("value", batch.warehouseId);
                            warehouseIdCell.appendChild(warehouseIdInput);
                            batchRow.appendChild(warehouseIdCell);

                            batchTbody.appendChild(batchRow);
                        });
                    });
                },
                error: function(err) {
                    console.error("Error fetching batches: ", err);
                }
            });
        }
        //thiet lap goods issue detail
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".goods-issue-row");

            rows.forEach(row => {
                row.addEventListener("click", function() {
                    const goodsIssueId = this.getAttribute("data-id");
                    const detailsRow = document.getElementById(`details-${goodsIssueId}`);
                    const goodsIssueRow = document.querySelector(
                        `.goods-issue-row[data-id="${goodsIssueId}"]`);

                    if (detailsRow.style.display === "none") {
                        goodsIssueRow.style.backgroundColor = "rgb(230, 247, 236)";
                        goodsIssueRow.style.border = "2px solid green";
                        goodsIssueRow.style.borderBottom = "none";
                        detailsRow.style.display = "table-row";
                        detailsRow.style.border = "2px solid green";
                        detailsRow.style.borderTop = "none";
                    } else {
                        detailsRow.style.display = "none";
                    }
                });
            });
        });
    </script>
@endpush
