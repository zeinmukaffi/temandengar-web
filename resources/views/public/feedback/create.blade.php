@extends('layouts.public')

@section('title', 'Feedback Konsultasi')

@section('content')

    <section class="hero-section">

        <div class="container text-center">

            <span class="section-label mb-3">
                Your Experience Matters
            </span>

            <h1 class="hero-title mb-3">
                Bagaimana pengalaman
                <span>konsultasimu?</span>
            </h1>

            <p class="hero-description mx-auto">
                Terima kasih sudah berbagi cerita bersama TemanDengar.
                Kami menghargai setiap masukan yang kamu berikan.
            </p>

        </div>

    </section>


    <section class="section-padding pt-0">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body p-4 p-lg-5">

                            <div class="bg-soft rounded-4 p-3 mb-4">

                                <small class="text-secondary">
                                    NOMOR REFERENSI
                                </small>

                                <h6 class="fw-bold mb-0 mt-1">
                                    {{ $consultation->consultationRequest->ticket_number ?? 'TemanDengar' }}
                                </h6>

                            </div>


                            <form action="{{ $storeUrl }}" method="POST">

                                @csrf

                                {{-- DISPLAY NAME --}}

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Nama Tampilan
                                    </label>

                                    <input type="text" name="display_name" class="form-control"
                                        value="{{ old('display_name', 'Mahasiswa') }}" maxlength="100">

                                    <small class="text-secondary">
                                        Gunakan nama samaran. Jangan mencantumkan
                                        informasi identitas yang tidak diperlukan.
                                    </small>

                                </div>


                                {{-- RATING --}}

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Bagaimana kamu menilai pengalaman konsultasi?
                                    </label>

                                    <div class="rating-group mt-3" role="radiogroup" aria-label="Rating konsultasi">

                                        @for ($rating = 5; $rating >= 1; $rating--)
                                            <label class="rating-option">

                                                <input type="radio" name="rating" value="{{ $rating }}"
                                                    @checked(old('rating') == $rating) required>

                                                <span>
                                                    <i class="bi bi-star-fill"></i>
                                                </span>

                                            </label>
                                        @endfor

                                    </div>

                                    @error('rating')
                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="d-flex justify-content-between mt-2">

                                        <small class="text-secondary">
                                            Kurang Puas
                                        </small>

                                        <small class="text-secondary">
                                            Sangat Puas
                                        </small>

                                    </div>

                                </div>


                                {{-- COMMENT --}}

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Ceritakan pengalamanmu
                                    </label>

                                    <textarea name="comment" rows="5" maxlength="1000" class="form-control @error('comment') is-invalid @enderror"
                                        placeholder="Apa hal yang paling membantu dari sesi konsultasi ini?">{{ old('comment') }}</textarea>

                                    @error('comment')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <small class="text-secondary">
                                        Maksimal 1.000 karakter. Hindari
                                        mencantumkan cerita pribadi atau
                                        informasi identitas sensitif.
                                    </small>

                                </div>


                                {{-- PUBLICATION CONSENT --}}

                                <div class="bg-soft rounded-4 p-3 mb-4">

                                    <div class="form-check">

                                        <input type="checkbox" name="consent_to_publish" value="1"
                                            id="consent-to-publish" class="form-check-input" @checked(old('consent_to_publish'))>

                                        <label class="form-check-label fw-semibold" for="consent-to-publish">

                                            Izinkan komentar saya ditampilkan
                                            sebagai testimoni publik.

                                        </label>

                                    </div>

                                    <p class="text-secondary small mt-2 mb-0">

                                        Persetujuan ini bersifat opsional.
                                        Komentar hanya akan ditampilkan setelah
                                        ditinjau dan disetujui konselor.
                                        Rating tetap dapat diberikan tanpa
                                        menyetujui publikasi komentar.

                                    </p>

                                </div>


                                <button type="submit" class="btn btn-primary btn-lg w-100">

                                    Kirim Feedback

                                    <i class="bi bi-arrow-right ms-2"></i>

                                </button>

                            </form>

                        </div>

                    </div>


                    <p class="text-center text-secondary small mt-4">

                        Terima kasih telah menjadi bagian dari TemanDengar.

                    </p>

                </div>

            </div>

        </div>

    </section>

@endsection
