@extends('layouts.public')

@section('title', 'Form Konsultasi')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="mb-4">
                    <h2 class="fw-bold">Mulai Ceritakan</h2>

                    <p class="text-secondary">
                        Isi formulir berikut agar konselor dapat
                        memahami kebutuhan konsultasimu.
                    </p>
                </div>

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4 p-lg-5">

                        <form action="{{ route('consultations.store') }}" method="POST">

                            @csrf

                            {{-- Nama Panggilan --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Panggilan *
                                </label>

                                <input type="text" name="alias"
                                    class="form-control @error('alias') is-invalid @enderror" value="{{ old('alias') }}"
                                    placeholder="Masukkan nama panggilan" required>

                                @error('alias')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Email --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="nama@email.com">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- WhatsApp --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Nomor WhatsApp
                                </label>

                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx">

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-secondary">
                                    Isi minimal salah satu: email atau WhatsApp.
                                </small>

                            </div>

                            <hr class="my-4">

                            {{-- Topik --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Topik Konsultasi *
                                </label>

                                <select name="topic" class="form-select @error('topic') is-invalid @enderror" required>

                                    <option value="">Pilih topik</option>

                                    <option value="academic" @selected(old('topic') == 'academic')>
                                        Akademik dan Perkuliahan
                                    </option>

                                    <option value="personal" @selected(old('topic') == 'personal')>
                                        Masalah Pribadi
                                    </option>

                                    <option value="social" @selected(old('topic') == 'social')>
                                        Pertemanan dan Sosial
                                    </option>

                                    <option value="organization" @selected(old('topic') == 'organization')>
                                        Organisasi dan Aktivitas
                                    </option>

                                    <option value="other" @selected(old('topic') == 'other')>
                                        Lainnya
                                    </option>

                                </select>

                                @error('topic')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Cerita --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Ceritakan Kebutuhanmu *
                                </label>

                                <textarea name="description" rows="5" maxlength="3000"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Ceritakan secara singkat apa yang ingin kamu diskusikan..." required>{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <hr class="my-4">

                            {{-- Metode --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Preferensi Konsultasi *
                                </label>

                                <select name="preferred_method"
                                    class="form-select @error('preferred_method') is-invalid @enderror" required>

                                    <option value="online" @selected(old('preferred_method', 'online') == 'online')>
                                        Online
                                    </option>

                                    <option value="offline" @selected(old('preferred_method') == 'offline')>
                                        Tatap Muka
                                    </option>

                                </select>

                                @error('preferred_method')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Preferensi Tanggal
                                    </label>

                                    <input type="date" name="preferred_date" min="{{ date('Y-m-d') }}"
                                        value="{{ old('preferred_date') }}"
                                        class="form-control @error('preferred_date') is-invalid @enderror">

                                    @error('preferred_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Preferensi Waktu
                                    </label>

                                    <select name="preferred_time" class="form-select">

                                        <option value="">Fleksibel</option>

                                        <option value="08:00-10:00" @selected(old('preferred_time') == '08:00-10:00')>
                                            08.00–10.00
                                        </option>

                                        <option value="10:00-12:00" @selected(old('preferred_time') == '10:00-12:00')>
                                            10.00–12.00
                                        </option>

                                        <option value="13:00-15:00" @selected(old('preferred_time') == '13:00-15:00')>
                                            13.00–15.00
                                        </option>

                                        <option value="15:00-17:00" @selected(old('preferred_time') == '15:00-17:00')>
                                            15.00–17.00
                                        </option>

                                    </select>

                                </div>

                            </div>

                            {{-- Persetujuan --}}
                            <div class="mb-4">

                                <div class="form-check">

                                    <div class="bg-light rounded-3 p-3 mb-3">

                                        <h6 class="fw-semibold">
                                            <i class="bi bi-shield-lock me-1"></i>
                                            Privasi dan Kerahasiaan
                                        </h6>

                                        <p class="text-secondary small mb-0">

                                            Informasi yang kamu berikan akan digunakan untuk
                                            mengelola permintaan konsultasi dan menghubungimu
                                            terkait layanan TemanDengar. Jangan cantumkan
                                            informasi pribadi yang tidak diperlukan.

                                        </p>

                                    </div>

                                    <input type="checkbox" name="consent" value="1" id="consent"
                                        class="form-check-input @error('consent') is-invalid @enderror"
                                        @checked(old('consent'))>

                                    <label class="form-check-label" for="consent">

                                        Saya menyetujui penggunaan data
                                        yang saya berikan untuk keperluan
                                        pengelolaan konsultasi.

                                    </label>

                                    @error('consent')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3">

                                Kirim Permintaan Konsultasi

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
