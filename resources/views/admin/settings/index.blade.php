@extends('layouts.dashboard')

@section('title', 'Pengaturan Akun')

@section('content')

    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Pengaturan Akun
        </h3>

        <p class="text-secondary mb-0">
            Kelola keamanan dan kredensial akun konselor.
        </p>

    </div>


    <div class="row g-4">

        {{-- ACCOUNT INFORMATION --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="td-icon lavender mb-3">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <h5 class="fw-bold">
                        Informasi Akun
                    </h5>

                    <p class="text-secondary small">
                        Informasi akun yang sedang digunakan.
                    </p>

                    <div class="mb-3">

                        <small class="text-secondary">
                            Nama
                        </small>

                        <p class="fw-semibold mb-0">
                            {{ auth()->user()->name }}
                        </p>

                    </div>

                    <div class="mb-3">

                        <small class="text-secondary">
                            Email
                        </small>

                        <p class="fw-semibold mb-0">
                            {{ auth()->user()->email }}
                        </p>

                    </div>

                    <div>

                        <small class="text-secondary">
                            Status
                        </small>

                        <p class="mb-0">

                            <span class="badge text-bg-success">
                                Aktif
                            </span>

                        </p>

                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm rounded-4 mt-3">

                <div class="card-body p-4">

                    <i class="bi bi-info-circle fs-4 text-primary"></i>

                    <h6 class="fw-bold mt-3">
                        Tips Keamanan
                    </h6>

                    <ul class="text-secondary small ps-3 mb-0">

                        <li class="mb-2">
                            Gunakan password yang sulit ditebak.
                        </li>

                        <li class="mb-2">
                            Jangan membagikan password kepada orang lain.
                        </li>

                        <li class="mb-2">
                            Jangan menggunakan password yang sama
                            dengan akun lain.
                        </li>

                        <li>
                            Logout setelah menggunakan perangkat bersama.
                        </li>

                    </ul>

                </div>

            </div>

        </div>


        {{-- CHANGE PASSWORD --}}

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div class="td-icon mint">

                            <i class="bi bi-key"></i>

                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Ubah Password
                            </h5>

                            <p class="text-secondary small mb-0">
                                Perbarui password untuk menjaga keamanan akun.
                            </p>

                        </div>

                    </div>


                    <form action="{{ route('admin.settings.password.update') }}" method="POST">

                        @csrf
                        @method('PUT')


                        {{-- CURRENT PASSWORD --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Password Lama *
                            </label>

                            <div class="input-group">

                                <input type="password" name="current_password" id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Masukkan password lama" autocomplete="current-password" required>

                                <button type="button" class="btn btn-outline-secondary password-toggle"
                                    data-target="current_password" aria-label="Tampilkan password">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                            @error('current_password')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- NEW PASSWORD --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Password Baru *
                            </label>

                            <div class="input-group">

                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password baru" autocomplete="new-password" required>

                                <button type="button" class="btn btn-outline-secondary password-toggle"
                                    data-target="password" aria-label="Tampilkan password">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                            @error('password')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-secondary d-block mt-2">

                                Minimal 8 karakter, menggunakan huruf
                                kapital, huruf kecil, dan angka.

                            </small>

                        </div>


                        {{-- CONFIRM PASSWORD --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Konfirmasi Password Baru *
                            </label>

                            <div class="input-group">

                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="Ulangi password baru" autocomplete="new-password"
                                    required>

                                <button type="button" class="btn btn-outline-secondary password-toggle"
                                    data-target="password_confirmation" aria-label="Tampilkan password">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                        </div>


                        <hr class="my-4">


                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-shield-check me-1"></i>

                            Perbarui Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.password-toggle').forEach(button => {

                button.addEventListener('click', function() {

                    const input = document.getElementById(
                        this.dataset.target
                    );

                    const icon = this.querySelector('i');

                    const isPassword = input.type === 'password';

                    input.type = isPassword ? 'text' : 'password';

                    icon.classList.toggle('bi-eye', !isPassword);

                    icon.classList.toggle('bi-eye-slash', isPassword);

                    this.setAttribute(
                        'aria-label',
                        isPassword ? 'Sembunyikan password' : 'Tampilkan password'
                    );

                });

            });

        });
    </script>

@endsection
