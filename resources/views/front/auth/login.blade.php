@extends('front.layouts.app')

@section('title', 'Login')

@section('content')
    <!-- Start Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="breadcrumbs-content">
                        <h1 class="page-title">Login</h1>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <ul class="breadcrumb-nav">
                        <li><a href="{{ url('/') }}"><i class="lni lni-home"></i> Home</a></li>
                        <li>Login</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Account Login Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div class="register-form card shadow-sm p-4">
                        <div class="title">
                            <h3>Welcome Back</h3>
                            <p>Login to your account to manage your orders and track shipments.</p>
                        </div>

                        {{-- Session error from controller --}}
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form class="row" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="login-email">E-mail Address</label>
                                    <input
                                        class="form-control @error('email') is-invalid @enderror"
                                        type="email"
                                        id="login-email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="login-pass">Password</label>
                                    <input
                                        class="form-control @error('password') is-invalid @enderror"
                                        type="password"
                                        id="login-pass"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="forgot-pass" href="{{ route('password.request') }}">Forgot Password?</a>
                                @endif
                            </div>

                            <div class="button">
                                <button class="btn btn-primary w-100 py-2 shadow-sm" type="submit" style="font-weight: 600;">Login</button>
                            </div>

                            {{-- Start Social Login --}}
                            <div class="social-login mt-4">
                                <div class="or-divider d-flex align-items-center my-3">
                                    <div class="flex-grow-1 border-bottom"></div>
                                    <span class="mx-3 text-muted">Or login with</span>
                                    <div class="flex-grow-1 border-bottom"></div>
                                </div>

                                <div class="col-12">
                                    <a href="{{ route('front.socialite.login', 'google') }}" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2 shadow-sm" style="font-weight: 500;">
                                        <i class="lni lni-google me-2"></i> Google
                                    </a>
                                </div>
                            </div>
                            {{-- End Social Login --}}

                            <p class="outer-link mt-4 text-center">
                                Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register Now</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- أضف هذه الستايلات البسيطة في ملف الـ CSS الخاص بك أو في الـ layout --}}
<style>
    .or-divider {
        font-size: 0.9rem;
        color: #888;
    }
    .register-form {
        border-radius: 12px;
        background: #fff;
    }
    .forgot-pass {
        font-size: 0.9rem;
        text-decoration: none;
        color: #666;
    }
    .forgot-pass:hover {
        color: #0d6efd;
    }
    .btn-outline-danger:hover {
        background-color: #db4437;
        color: white;
    }
</style>