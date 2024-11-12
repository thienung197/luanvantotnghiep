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
        <!-- Tab danh mục sản phẩm -->
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

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="categoryTabContent">
            <!-- Tab "Tất cả" -->
            <div class="tab-pane fade show active" id="category-all" role="tabpanel">
                <div class="row">
                    @foreach ($allProducts as $product)
                        <div class="col-md-3 mb-4"> <!-- 4 sản phẩm mỗi hàng -->
                            <div class="card h-100">
                                <img src="{{ $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png') }}"
                                    class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                                    <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}">
                                        Đặt hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Hiển thị phân trang cho tab "Tất cả" -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $allProducts->links() }}
                </div>
            </div>

            <!-- Các Tab danh mục sản phẩm -->
            @foreach ($categoriesWithProducts as $category)
                <div class="tab-pane fade" id="category-{{ $category->id }}" role="tabpanel">
                    <div class="row">
                        @foreach ($category->products as $product)
                            <div class="col-md-3 mb-4"> <!-- 4 sản phẩm mỗi hàng -->
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

                    <!-- Hiển thị phân trang cho mỗi danh mục -->
                    <div class="d-flex justify-content-center mt-4">
                        {{-- {{ $category->products->links() }} --}}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Nút Đặt hàng để chuyển đến trang đặt hàng -->
        <div class="text-end mt-4">
            <a href="{{ route('goodsissues.create') }}" class="btn btn-success">Đặt hàng</a>
        </div>
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
