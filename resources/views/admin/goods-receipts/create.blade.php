@extends('layouts.app')
@section('title', 'Đặt hàng nhà cung cấp')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Đặt hàng nhà cung cấp
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('goodsreceipts.create') }}">Đặt hàng nhà cung cấp</a> > <a
                    href="">Thêm phiếu </a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <h3>Thông tin phiếu đặt hàng nhà cung cấp</h3>
        <h6><span>Tên người tạo :</span> {{ Auth::user()->name }}</h6>
        <h6><span>Mã phiếu:</span> {{ $newCode }}</h6>
        <h6><span>Ngày tạo phiếu: </span>
            {{ \Carbon\Carbon::now()->format('Y-m-d') }}
        </h6>
        <h6><span>Gửi đến nhà cung cấp: </span>{{ $provider->name }}</h6>
    </div>
    <div class="content-10">
        <div class="search-result-container">
            {{-- <form action="" role="search">
                <div class="form-group input-div">
                    <input type="text" name="key" class="form-control search-input" placeholder="Nhập tên sản phẩm">
                    <div class="search-result" style="z-index:1" style="border: 1px solid red">

                    </div>
                </div>
            </form> --}}
            <form action="{{ route('goodsreceipts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="creator_id" value="{{ Auth::user()->id }}" id="creator_id">
                <input type="hidden" name="code" value="{{ old('code', $newCode) }}" id="code" class="form-control"
                    readonly>
                <input type="hidden" name="provider_id" value="{{ old('provider_id', $provider->id) }}" id="provider_id"
                    class="form-control" readonly>
                <table id="product-table" class="table ">
                    <thead>
                        <th>Mã hàng</th>
                        <th>Tên hàng</th>
                        <th>Đơn vị tính</th>
                        <th>Số lượng</th>
                        <th>Xóa</th>
                    </thead>
                    <tbody id="body-product-table">
                        @foreach ($approvedProduct as $key => $product)
                            <tr data-id="{{ $key }}">
                                <td style="display: none">
                                    <input type="hidden" name="products[{{ $key }}][id]"
                                        value="{{ $product['product_id'] }}">
                                </td>
                                <td>{{ $product['product_code'] }}</td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['product_unit'] }}</td>
                                <td><input type="number" class="form-control"
                                        name="products[{{ $key }}][quantity]"
                                        value="{{ $product['totalQuantity'] }}"></td>
                                <td><button class="btn btn-danger delete-row-btn">Xóa</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" id="btn-order">Đặt hàng</button>
            </form>

        </div>
    </div>

@endsection
@push('css')
    <style>
        .content-10 h6 span {
            width: 20%;
        }

        #btn-order {
            padding: 7px 12px;
            background-color: var(--color-green);
            color: var(--color-white);
            border-radius: 4px;
            margin-left: 90%;
            margin-top: 20px;
        }

        .search-result {
            max-height: 350px;
        }
    </style>
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".search-input", function(e) {
                let _text = $(this).val();
                let providerId = $("#provider_id").val();
                // if (_text.length > 0) {
                $.ajax({
                    url: "{{ route('ajax-search-product-by-provider') }}",
                    type: "GET",
                    data: {
                        key: _text,
                        provider_id: providerId
                    },
                    success: function(res) {
                        $(".search-result").html(res).css("display", "block");
                    }
                })
                // } else {
                //     $(".search-result").css("display", "none");
                // }
            })
            $(document).on("input", ".search-input", function() {
                let _text = $(this).val();
                let providerId = $("#provider_id").val();

                // if (_text.length > 0) {
                $.ajax({
                    url: "{{ route('ajax-search-product-by-provider') }}",
                    type: "GET",
                    data: {
                        key: _text,
                        provider_id: providerId
                    },
                    success: function(res) {
                        $(".search-result").html(res).css("display", "block");
                    }
                })
                // } else {
                //     $(".search-result").css("display", "none");
                // }
            })
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".search-result-container").length) {
                    $(".search-result").css("display", "none");
                }
            })
        })

        //Tao nut xoa
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButton = document.querySelectorAll(".delete-row-btn");
            deleteButton.forEach(button => {
                button.addEventListener("click", function() {
                    const row = this.closest("tr");
                    row.remove();
                })
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

            // function deleteRow(row) {
            //     row.remove();
            // }
        });


        $(".search-result").on("click", ".search-result-item", function() {
            let name = $(this).find("h4").data("name");
            let code = $(this).find("p[data-code]").data("code");
            let unit = $(this).find("p[data-unit]").data("unit");
            let id = $(this).find("h6").data("id");

            const row = document.createElement("tr");

            const idCell = document.createElement("td");
            idCell.style.display = "none";
            const idInput = document.createElement("input");
            idInput.classList.add("form-control");
            idInput.setAttribute("type", "number");
            idInput.setAttribute("name", `products[${id}][id]`);
            idInput.value = id;
            idCell.appendChild(idInput);
            row.appendChild(idCell);

            const codeCell = document.createElement("td");
            codeCell.textContent = code;
            row.appendChild(codeCell);

            const nameCell = document.createElement("td");
            nameCell.textContent = name;
            row.appendChild(nameCell);

            const unitCell = document.createElement("td");
            unitCell.textContent = unit;
            row.appendChild(unitCell);


            const quantityCell = document.createElement("td");
            const quantityInput = document.createElement("input");
            quantityInput.classList.add("form-control");
            quantityInput.setAttribute("type", "number");
            quantityInput.setAttribute("name", `products[${id}][quantity]`);
            quantityCell.appendChild(quantityInput);
            row.appendChild(quantityCell);

            const deleteCell = document.createElement("td");
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger", "delete-row-btn");
            deleteButton.textContent = "Xóa";
            deleteCell.appendChild(deleteButton);
            row.appendChild(deleteCell);

            deleteButton.addEventListener("click", function(e) {
                e.preventDefault();
                row.remove();
            })

            const tbody = $("#product-table tbody");
            tbody.append(row);
            $(".search-result").css("display", "none");
        })
    </script>
@endpush

@push('css')
    <style>
        table .form-control {
            text-align: center;
            font-size: 20px;
        }

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
