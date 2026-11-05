@extends('layouts.auth')

@section('title', 'Login Konselor')

@section('content')

    <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">

        <div class="card border-0 shadow-sm rounded-4" style="max-width: 420px; width: 100%;">

            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">TemanDengar.</h3>
                    <p class="text-secondary mb-0">
                        Selamat datang kembali, Konselor!
                    </p>
                </div>

                <form action="{{ route('login.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>

                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>

                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">

                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <button class="btn btn-primary w-100 py-2">
                        Login
                    </button>

                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-decoration-none small">
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>

    </div>

@endsection
