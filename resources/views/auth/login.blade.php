@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center login-container">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Đăng nhập') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-1"></div>

                                <label for="email" class="col-md-3 col-form-label ">{{ __('Email') }}</label>

                                <div class="col-md-8">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-1"></div>
                                <label for="password" class="col-md-3 col-form-label ">{{ __('Mật khẩu') }}</label>

                                <div class="col-md-8">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary login-btn">
                                        {{ __('Đăng nhập') }}
                                    </button>
                                    @if (Route::has('register'))
                                        <a class="btn btn-link register-link"
                                            href="{{ route('register') }}">{{ __('Đăng ký ngay') }}</a>
                                    @endif

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
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

@push('css')
    <style>
        .card {
            border: none;
            margin-bottom: 20px;
            width: 850px;
        }

        .login-btn {
            font-size: 20px;
            padding: 8px 18px;
        }

        .register-link {
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
