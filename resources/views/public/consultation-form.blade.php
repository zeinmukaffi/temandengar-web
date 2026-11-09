@extends('layouts.public')

@section('title', 'Mulai Curhat')

@section('content')

    <section class="hero-section">

        <div class="container">

            <div class="row justify-content-center text-center">

                <div class="col-lg-8">

                    <span class="section-label mb-3">
                        Start Your Story
                    </span>

                    <h1 class="hero-title mb-3">
                        Mulai dari satu
                        <span>cerita.</span>
                    </h1>

                    <p class="hero-description mx-auto">
                        Ceritakan kebutuhan konsultasimu. Konselor akan
                        meninjau permintaan dan menghubungimu untuk
                        membicarakan langkah berikutnya.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <section class="section-padding pt-0">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body p-4 p-lg-5">

                            <form action="{{ route('consultations.store') }}" method="POST">

                                @csrf

                                <h5 class="fw-bold mb-1">
                                    Informasi Kontak
                                </h5>

                                <p class="text-muted-custom small mb-4">
                                    Isi nama panggilan dan minimal satu kontak.
                                </p>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama Panggilan *
                                    </label>

                                    <input type="text" name="alias"
                                        class="form-control @error('alias') is-invalid @enderror"
                                        value="{{ old('alias') }}" required>

                                    @error('alias')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>


                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Email Aktif *
                                        </label>

                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="nama@email.com" required>

                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Nomor WhatsApp
                                        </label>

                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">

                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                </div>



                                <hr class="my-4">
                                {{-- <hr class="my-4"> --}}

                                {{-- COUNSELOR PREFERENCE --}}

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Pilih Konselor
                                    </label>

                                    <p class="text-secondary small">
                                        Kamu dapat memilih konselor yang ingin diajak berdiskusi.
                                        Pilihan ini merupakan preferensi dan akan dikonfirmasi
                                        setelah pengajuan ditinjau.
                                    </p>

                                    <select name="preferred_counselor_id"
                                        class="form-select @error('preferred_counselor_id') is-invalid @enderror">

                                        <option value="">
                                            Tidak ada preferensi konselor
                                        </option>

                                        @foreach ($counselors as $counselor)
                                            <option value="{{ $counselor->id }}" @selected(old('preferred_counselor_id') == $counselor->id)>

                                                {{ $counselor->name }}
                                                — {{ $counselor->headline ?: 'Konselor Sebaya' }}

                                            </option>
                                        @endforeach

                                    </select>

                                    @error('preferred_counselor_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @if ($counselors->isEmpty())
                                        <small class="text-secondary">
                                            Saat ini belum ada konselor yang membuka penerimaan
                                            permintaan konsultasi. Pengajuan tanpa preferensi
                                            tetap dapat dikirim.
                                        </small>
                                    @endif

                                </div>


                                <h5 class="fw-bold mb-1">
                                    Kebutuhan Konsultasi
                                </h5>


                                <p class="text-muted-custom small mb-4">
                                    Pilih topik dan ceritakan hal yang ingin dibicarakan.
                                </p>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Topik Konsultasi *
                                    </label>

                                    <select name="topic" class="form-select @error('topic') is-invalid @enderror"
                                        required>

                                        <option value="">Pilih topik</option>

                                        <option value="academic" @selected(old('topic') == 'academic')>
                                            Akademik dan Perkuliahan
                                        </option>

                                        <option value="personal" @selected(old('topic') == 'personal')>
                                            Pengembangan Diri
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
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Ceritakan Kebutuhanmu *
                                    </label>

                                    <textarea name="description" rows="5" maxlength="3000"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Tuliskan secara singkat hal yang ingin kamu diskusikan..." required>{{ old('description') }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>


                                <hr class="my-4">


                                <h5 class="fw-bold mb-1">
                                    Preferensi Konsultasi
                                </h5>

                                <p class="text-muted-custom small mb-4">
                                    Jadwal akhir akan dikonfirmasi bersama konselor.
                                </p>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Metode Konsultasi *
                                    </label>

                                    <select name="preferred_method" class="form-select" required>

                                        <option value="online" @selected(old('preferred_method', 'online') == 'online')>
                                            Online
                                        </option>

                                        <option value="offline" @selected(old('preferred_method') == 'offline')>
                                            Tatap Muka
                                        </option>

                                    </select>

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
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>


                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Preferensi Waktu
                                        </label>

                                        <select name="preferred_time" class="form-select">

                                            <option value="">Fleksibel</option>

                                            @foreach (['08:00-10:00', '10:00-12:00', '13:00-15:00', '15:00-17:00'] as $time)
                                                <option value="{{ $time }}" @selected(old('preferred_time') == $time)>

                                                    {{ str_replace('-', ' – ', $time) }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>


                                <div class="bg-soft rounded-4 p-3 mb-4">

                                    <h6 class="fw-bold">
                                        <i class="bi bi-shield-check text-primary me-1"></i>
                                        Informasi Privasi
                                    </h6>

                                    <p class="text-muted-custom small mb-2">
                                        Informasi yang kamu berikan digunakan
                                        untuk pengelolaan permintaan konsultasi.
                                        Jangan masukkan data yang tidak diperlukan.
                                    </p>

                                    <a href="{{ route('public.privacy') }}" class="small text-primary">
                                        Baca Kebijakan Privasi
                                    </a>

                                </div>

                                <div class="bg-light rounded-4 p-3 mb-3">

                                    <div class="form-check">

                                        <input type="checkbox" name="ai_processing_consent" value="1"
                                            id="ai_processing_consent" class="form-check-input"
                                            @checked(old('ai_processing_consent'))>

                                        <label for="ai_processing_consent" class="form-check-label fw-semibold">

                                            Saya bersedia memberikan izin pemrosesan cerita konsultasi
                                            menggunakan bantuan AI untuk membantu konselor menyiapkan respons.

                                        </label>

                                    </div>

                                    <p class="text-secondary small mb-0 mt-2">

                                        Persetujuan ini opsional dan tidak memengaruhi pengajuan konsultasi.
                                        Cerita hanya boleh dikirim ke layanan AI setelah pengelola
                                        menjelaskan penyedia layanan dan pemrosesan datanya.

                                    </p>

                                </div>


                                <div class="bg-light rounded-4 p-3 mb-3">

                                    <div class="form-check">

                                        <input type="checkbox" name="consent" value="1" id="consent"
                                            class="form-check-input @error('consent') is-invalid @enderror"
                                            @checked(old('consent')) required>

                                        <label for="consent" class="form-check-label fw-semibold">

                                            Saya telah membaca informasi privasi dan menyetujui
                                            penggunaan data untuk pengelolaan permintaan konsultasi.

                                        </label>

                                        @error('consent')
                                            <div class="text-danger small mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                <button type="submit" id="submit-consultation" class="btn btn-primary btn-lg w-100"
                                    disabled>
                                    Kirim Permintaan Konsultasi
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const consentCheckbox = document.getElementById('consent');
            const submitButton = document.getElementById('submit-consultation');

            function updateSubmitButton() {
                submitButton.disabled = !consentCheckbox.checked;
            }

            consentCheckbox.addEventListener('change', updateSubmitButton);

            // Periksa kondisi awal checkbox
            updateSubmitButton();

        });
    </script>

@endsection
