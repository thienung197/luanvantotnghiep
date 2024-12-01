@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __(' Đăng ký') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label ">{{ __('Tên khách hàng') }}</label>

                                <div class="col-md-8">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label ">{{ __('Email') }}</label>

                                <div class="col-md-8">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label ">{{ __('Mật khẩu') }}</label>

                                <div class="col-md-8">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label ">{{ __('Nhập lại mật khẩu') }}</label>

                                <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary register-btn">
                                        {{ __('Đăng ký') }}
                                    </button>
                                    @if (Route::has('login'))
                                        <a class="btn btn-link login-link"
                                            href="{{ route('login') }}">{{ __('Đăng nhập ngay') }}</a>
                                    @endif

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class=" img-container">
            <img src="{{ asset('/img/ware-master-high-resolution-logo.png') }}" alt="">
            <p>Hệ thống quản lý nhà kho</p>
            <p>WareMaster</p>
            <img src="{{ asset('/img/login.webp') }}" alt="">
        </div>
    </div>
@endsection
{{-- @section('content')
    <div class="content-10">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group input-div">
                <input type="file" accept="image/*" class="form-control" name="image">
                <div class="show-image">
                    <img src="" alt="">
                </div>
                @error('image')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Giới tính</h4>
                <select name="gender" id="" class="form-control">
                    <option value="">---Chọn giới tính---</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                </select>
                @error('gender')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Ngày sinh</h4>
                <input type="date" name="birth_date" id="" class="form-control"
                    value="{{ old('birth_date') }}">
                @error('birth_date')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Số điện thoại</h4>
                <input type="text" name="phone" value="{{ old('phone') }}" id="phone" class="form-control">
                @error('phone')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Chọn địa chỉ</h4>
                <select id='provinces' onchange='getProvinces(event)'>
                    <option value=''>-- Chọn tỉnh / thành phố --</option>
                </select>
                <select id='districts' onchange='getDistricts(event)'>
                    <option value=''>-- Chọn quận / huyện --</option>
                </select>
                <select id='wards'>
                    <option value=''>-- Chọn phường / xã --</option>
                </select>
            </div>
            <div class="form-group input-div">
                <h4>Địa chỉ cụ thể</h4>
                <input type="text" name="street_address" value="{{ old('street_address') }}" id="street_address"
                    class="form-control">
                @error('street_address')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="province" id="province_name">
            <input type="hidden" name="district" id="district_name">
            <input type="hidden" name="ward" id="ward_name">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <div class="form-group input-div">
                <h4>Email</h4>
                <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control">
                @error('email')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Mật khẩu</h4>
                <input type="password" name="password" value="{{ old('password') }}" id="password"
                    class="form-control">
                @error('password')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-back"><a href="{{ route('users.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>
@endsection --}}
@push('css')
    <style>
        .card {
            border: none;
            margin-bottom: 20px;
            width: 850px;
        }

        .register-btn {
            font-size: 20px;
            padding: 8px 18px;
        }

        .login-link {
            margin-left: 10px;
            font-size: 20px;
        }

        label {
            font-size: 28px !important;
            color: var(--color-black);
            font-weight: 500;
        }

        .form-control {
            border: unset;
            border-radius: unset;
            font-size: 20px;
            color: #000;
        }


        input {
            border: none;
            border-bottom: 1px solid var(--color-black) !important;
        }

        .form-control:focus {
            box-shadow: unset;
            color: #000;
            font-size: 20px;

        }

        .card-header {
            border: none;
            color: var(--color-black);
            font-weight: 600;
            font-size: 56px;
            background: var(--color-white);
            margin: 42px 128px 42px 331px;
        }

        .card-body .row {
            margin-right: 300px;
            width: 100%;

        }

        .img-container {
            background: rgb(46, 32, 248);
            padding: 380px 0;
            border-radius: 25px;
            padding: 60px 10px;
        }

        .img-container img:last-child {
            border-radius: unset;
            width: 90%;
        }

        .img-container img {
            width: 150px;
            margin-left: 50%;
            transform: translateX(-50%);
            border-radius: 30px;
        }

        .img-container p {
            color: var(--color-white);
            font-size: 42px;
            margin-left: 206px;
            font-weight: 600;
        }

        .img-container p:nth-child(3) {
            margin-left: 340px;
        }

        .container {
            min-width: 100%;
            display: flex;
            align-items: center;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            background-color: var(--color-white);
        }

        .container>div {
            width: 50%;
        }

        #aside {
            display: none;
        }

        #navbar {
            display: none;
        }

        #main {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
@push('js')
    <script>
        const apiKey = "5b3ce3597851110001cf6248b3a0553228e34d53b6a25e785eb04563"; // Sử dụng API key của bạn

        // Fetching provinces data and appending to the select box
        fetch('https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1')
            .then(response => response.json())
            .then(data => {
                let provinces = data.data.data;
                provinces.map(value => {
                    document.getElementById('provinces').innerHTML +=
                        `<option value='${value.code}' data-name='${value.name}'>${value.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Lỗi khi gọi API:', error);
            });

        // Fetching districts based on selected province
        function fetchDistricts(provinceID) {
            fetch(`https://vn-public-apis.fpo.vn/districts/getByProvince?provinceCode=${provinceID}&limit=-1`)
                .then(response => response.json())
                .then(data => {
                    let districts = data.data.data;
                    document.getElementById('districts').innerHTML = `<option value=''>-- Chọn quận/huyện --</option>`;
                    if (districts !== undefined) {
                        districts.map(value => {
                            document.getElementById('districts').innerHTML +=
                                `<option value='${value.code}' data-name='${value.name}'>${value.name}</option>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gọi API:', error);
                });
        }

        // Fetching wards based on selected district
        function fetchWards(districtID) {
            fetch(`https://vn-public-apis.fpo.vn/wards/getByDistrict?districtCode=${districtID}&limit=-1`)
                .then(response => response.json())
                .then(data => {
                    let wards = data.data.data;
                    document.getElementById('wards').innerHTML = `<option value=''>-- Chọn phường/xã --</option>`;
                    if (wards !== undefined) {
                        wards.map(value => {
                            document.getElementById('wards').innerHTML +=
                                `<option value='${value.code}' data-name='${value.name}'>${value.name}</option>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gọi API:', error);
                });
        }

        // Handle province change and update hidden field
        function getProvinces(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const provinceID = selectedOption.value;
            const provinceName = selectedOption.getAttribute('data-name');

            // Set the province name into hidden field
            document.getElementById('province_name').value = provinceName;
            getCoordinates();
            fetchDistricts(provinceID);
            document.getElementById('wards').innerHTML = `<option value=''>-- Chọn phường/xã --</option>`;
        }

        // Handle district change and update hidden field
        function getDistricts(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const districtID = selectedOption.value;
            const districtName = selectedOption.getAttribute('data-name');

            // Set the district name into hidden field
            document.getElementById('district_name').value = districtName;
            getCoordinates();
            fetchWards(districtID);
        }

        // Handle ward change and update hidden field
        document.getElementById('wards').addEventListener('change', function(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const wardName = selectedOption.getAttribute('data-name');

            // Set the ward name into hidden field
            document.getElementById('ward_name').value = wardName;
            getCoordinates()
        });

        function getCoordinates() {
            const province = document.getElementById("province_name").value;
            const district = document.getElementById("district_name").value;
            const ward = document.getElementById("ward_name").value;

            // if (!province || !district || !ward) {
            //     alert("Vui lòng chọn đầy đủ địa điểm.");
            //     return;
            // }

            // Tạo địa chỉ cho địa điểm
            const address = `${ward}, ${district}, ${province}`;
            console.log("Địa chỉ:", address);

            // Lấy tọa độ cho địa điểm
            fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(
      address
    )}&format=json&limit=1`
                )
                .then((response) => response.json())
                .then((data) => {
                    if (data.length === 0) {
                        throw new Error("Không tìm thấy tọa độ cho địa điểm.");
                    }

                    const coords = {
                        lat: data[0].lat,
                        lon: data[0].lon
                    };
                    console.log("Tọa độ:", coords);

                    // Lưu tọa độ vào các trường input ẩn
                    document.getElementById("latitude").value = coords.lat;
                    document.getElementById("longitude").value = coords.lon;
                    // alert("Tọa độ đã được lưu thành công.");
                })
                .catch((error) => {
                    console.error("Lỗi:", error);
                    alert(error.message);
                });
        }
    </script>
@endpush
