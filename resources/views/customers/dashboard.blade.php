@extends('layouts.app')
@section('title', 'Cập nhật thông tin');
@section('content')
    <div id="navbar" class="show">
        <div id="navbar-left">

        </div>
        <div id="navbar-right">
            <div class="notification">
                <img src="{{ asset('img/bell.png') }}" alt="">

            </div>
            <img class="avatar" src="{{ asset('img/example-avatar.png') }}" alt="">
            @if (Auth::check())
                <span class="username">{{ Auth::user()->name }}</span>
            @else
                <span class="username">Khách</span>
            @endif
        </div>
    </div>
    <div class="container">
        <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-all" data-bs-toggle="tab" data-bs-target="#category-all"
                    type="button" role="tab">
                    Tất cả
                </button>
            </li>

            @foreach ($categoriesWithProducts as $category)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-{{ $category->id }}" data-bs-toggle="tab"
                        data-bs-target="#category-{{ $category->id }}" type="button" role="tab">
                        {{ $category->name }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mt-3" id="categoryTabContent">
            <div class="tab-pane fade show active" id="category-all" role="tabpanel">
                <div class="row">
                    @foreach ($paginatedProducts as $product)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="{{ $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png') }}"
                                    class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <h4>Tồn kho: {{ $product->totalStock }}</h4>
                                    <p class="card-text">{{ number_format($product->selling_price, 0, ',', '.') }} VNĐ</p>
                                    <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}">
                                        Đặt hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $paginatedProducts->links() }}
                </div>
            </div>

            @foreach ($categoriesWithProducts as $category)
                <div class="tab-pane fade" id="category-{{ $category->id }}" role="tabpanel">
                    <div class="row">
                        @foreach ($category->products as $product)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <img src="{{ $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png') }}"
                                        class="card-img-top">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                                        <button class="btn btn-primary add-to-cart-btn"
                                            data-product-id="{{ $product->id }}">
                                            Đặt hàng
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                    </div>
                </div>
            @endforeach
        </div>

        {{-- <div class="text-end mt-4">
            <a href="{{ route('goodsissues.create') }}" class="btn btn-success order-btn">Đặt hàng</a>
        </div> --}}
    </div>
@endsection

@push('css')
    <style>
        #navbar {
            display: none;
        }

        #navbar.show {
            display: flex;
        }

        .order-btn {
            position: fixed;
            bottom: 103px;
            right: 123px;
            font-size: 27px;
            padding: 20px 10px;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener("click", function() {
                    this.textContent = "Đã thêm vào giỏ hàng";
                    this.classList.add("diabled");
                    this.disabled = true;
                    const productId = this.getAttribute('data-product-id');
                    fetch('/card/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log("Sản phẩm đã được thêm vào giỏ hàng!");

                        })
                        .catch(err => console.error('Error: ', err))
                });
            });
        });
    </script>
@endpush
