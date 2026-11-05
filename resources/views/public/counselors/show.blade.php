@extends('layouts.public')

@section('title', 'Profil ' . $counselor->name)

@section('content')

    <section class="hero-section">

        <div class="container">

            <div class="row align-items-center justify-content-center">

                <div class="col-lg-8 text-center">

                    <div class="profile-avatar mx-auto mb-4" style="width:120px;height:120px;">

                        @if ($counselor->photo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($counselor->photo_path) }}"
                                alt="Foto {{ $counselor->name }}">
                        @else
                            {{ strtoupper(substr($counselor->name, 0, 1)) }}
                        @endif

                    </div>


                    <h1 class="fw-bold mb-2">
                        {{ $counselor->name }}
                    </h1>

                    <p class="text-muted-custom">

                        {{ $counselor->headline ?: 'Konselor Sebaya' }}

                    </p>

                    @if ($counselor->education)
                        <p class="small text-muted-custom">

                            <i class="bi bi-mortarboard me-1"></i>

                            {{ $counselor->education }}

                        </p>
                    @endif

                    <div class="d-flex justify-content-center flex-wrap gap-2">

                        @php
                            $categoryLabels = [
                                'academic' => 'Akademik',
                                'personal' => 'Pengembangan Diri',
                                'social' => 'Sosial dan Pertemanan',
                                'organization' => 'Organisasi',
                            ];
                        @endphp

                        @foreach ($counselor->specialties ?? [] as $specialty)
                            <span class="profile-specialty">

                                {{ $categoryLabels[$specialty] ?? $specialty }}

                            </span>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <span class="section-label mb-3">
                        Tentang Konselor
                    </span>

                    <h2 class="section-heading mb-4">
                        Mengenal {{ $counselor->name }}
                    </h2>

                    <p class="section-description">
                        {{ $counselor->bio ?: 'Informasi profil belum tersedia.' }}
                    </p>


                    <div class="row g-3 my-4">

                        <div class="col-sm-6">

                            <div class="td-card">

                                <i class="bi bi-check-circle text-success fs-3"></i>

                                <h6 class="fw-bold mt-3">
                                    {{ $counselor->completed_consultations_count }}
                                </h6>

                                <small class="text-muted-custom">
                                    Sesi konsultasi selesai
                                </small>

                            </div>

                        </div>

                        <div class="col-sm-6">

                            <div class="td-card">

                                <i class="bi bi-people text-primary fs-3"></i>

                                <h6 class="fw-bold mt-3">
                                    Konselor Sebaya
                                </h6>

                                <small class="text-muted-custom">
                                    Peran dalam TemanDengar
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="bg-soft p-4 rounded-4">

                        <h5 class="fw-bold">
                            Ingin mengajukan konsultasi?
                        </h5>

                        <p class="text-muted-custom small">
                            Profil ini memberikan informasi mengenai konselor.
                            Pengajuan konsultasi akan ditinjau oleh pengelola
                            sebelum jadwal ditentukan.
                        </p>

                        <a href="{{ route('consultations.create') }}" class="btn btn-primary">

                            Mulai Curhat

                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
