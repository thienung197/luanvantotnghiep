@extends('layouts.app')
@section('title', 'Nhà kho')
@section('content')
    <div class="content_header">
        <div class="content_header--title">
            Thêm nhà kho
        </div>
        <div class="content_header--path">
            <img src="{{ asset('img/home.png') }}" alt="">
            <p><a href="">Home</a> > <a href="{{ route('warehouses.index') }}">Nhà kho</a> > <a href="">Thêm nhà
                    kho</a>
            </p>
        </div>
    </div>
    <div class="content-10">
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf

            <div class="form-group input-div">
                <h4>Tên nhà kho</h4>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                @error('name')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group input-div">
                <h4>Sức chứa</h4>
                <input type="number" name="capacity" value="{{ old('capacity') }}" id="capacity" class="form-control">
                @error('capacity')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Diện tích</h4>
                <input type="number" name="size" value="{{ old('size') }}" id="size" class="form-control">
                @error('size')
                    <div class="error message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group input-div">
                <h4>Bảo quản lạnh</h4>
                <select name="isRefrigerated" id="" class="form-control">
                    <option value="">--- Chọn điều kiện bảo quản lạnh---</option>
                    <option value="1" {{ old('isRefrigerated') == '1' ? 'selected' : '' }}>Có</option>
                    <option value="0" {{ old('isRefrigerated') == '0' ? 'selected' : '' }}>Không</option>
                </select>
                @error('isRefrigerated')
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
            <div class="btn-controls">
                <div class="btn-cs btn-save"><button type="submit">Lưu thay đổi</button></div>
                <div class="btn-cs btn-back"><a href="{{ route('warehouses.index') }}">Quay lại </a></div>
            </div>
        </form>
    </div>

@endsection


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
