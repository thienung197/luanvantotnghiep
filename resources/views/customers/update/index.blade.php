@extends('layouts.app')
@section('title', 'Cập nhật thông tin')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Cập nhật thông tin
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('customers.update.index') }}">Cập nhật thông tin</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('customers.update.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="form-group input-div">
                <input type="file" accept="image/*" class="form-control" name="image">
                <div class="show-image">
                    <img src="{{ $user->images->count() > 0 ? asset('upload/' . $user->images->first()->url) : asset('upload/users/man.png') }}"
                        alt="">
                </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Tên người dùng</h4>
                <input type="text" name="name" value="{{ old('name') ?? $user->name }}" id="name"
                    class="form-control">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Giới tính</h4>
                <select name="gender" class="form-control">
                    <option value="">---Chọn giới tính---</option>
                    <option value="male" {{ (old('gender') ?? $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ (old('gender') ?? $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                </select>
                @error('gender')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Ngày sinh</h4>
                <input type="date" name="birth_date" class="form-control"
                    value="{{ old('birth_date') ?? $user->birth_date }}">
                @error('date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Số điện thoại</h4>
                <input type="text" name="phone" value="{{ old('phone') ?? $user->phone }}" id="phone"
                    class="form-control">
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Địa chỉ</h4>
                <input type="text" value="{{ $fullAddress }}" id="phone" class="form-control" readonly>
            </div>
            <button type="button" id="changeAddressBtn" class="btn btn-primary">
                Đổi địa chỉ
            </button>
            <div class="form-group input-div" id="addressSection" style="display:none;">
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
            <div class="form-group input-div" id="addressSection2" style="display:none;">
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
                <input type="email" name="email" value="{{ old('email') ?? $user->email }}" id="email"
                    class="form-control">
                @error('email')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <button type="button" id="changePasswordBtn" class="btn btn-primary">
                Đổi mật khẩu
            </button>
            <div class="form-group input-div" id="passwordSection" style="display:none;">
                <h4>Mật khẩu mới</h4>
                <input type="password" name="password" value="{{ old('password') }}" id="password"
                    class="form-control">
                @error('password')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                {{-- <div class="btn-cs btn-back"><a href="{{ route('users.index') }}">Quay lại </a></div> --}}
            </div>
        </form>
    </div>

@endsection

@push('js')
    <script>
        document.getElementById('changeAddressBtn').addEventListener('click', function() {
            const addressSection = document.getElementById('addressSection');
            const addressSection2 = document.getElementById('addressSection2');
            addressSection.style.display = (addressSection.style.display === 'none' || addressSection.style
                .display === '') ? 'block' : 'none';
            addressSection2.style.display = (addressSection2.style.display === 'none' || addressSection2.style
                .display === '') ? 'block' : 'none';
        });

        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            const passwordSection = document.getElementById('passwordSection');
            passwordSection.style.display = (passwordSection.style.display === 'none' || passwordSection.style
                .display === '') ? 'block' : 'none';
        });
        @if (Session::has('message'))
            toastr.success("{{ Session::get('message') }}");
        @endif
        const apiKey = "5b3ce3597851110001cf6248b3a0553228e34d53b6a25e785eb04563";

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

        function getProvinces(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const provinceID = selectedOption.value;
            const provinceName = selectedOption.getAttribute('data-name');

            document.getElementById('province_name').value = provinceName;
            getCoordinates();
            fetchDistricts(provinceID);
            document.getElementById('wards').innerHTML = `<option value=''>-- Chọn phường/xã --</option>`;
        }

        function getDistricts(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const districtID = selectedOption.value;
            const districtName = selectedOption.getAttribute('data-name');

            document.getElementById('district_name').value = districtName;
            getCoordinates();
            fetchWards(districtID);
        }

        document.getElementById('wards').addEventListener('change', function(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const wardName = selectedOption.getAttribute('data-name');

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

            const address = `${ward}, ${district}, ${province}`;
            console.log("Địa chỉ:", address);

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

                    document.getElementById("latitude").value = coords.lat;
                    document.getElementById("longitude").value = coords.lon;
                })
                .catch((error) => {
                    console.error("Lỗi:", error);
                    // alert(error.message);
                });
        }
    </script>
@endpush
