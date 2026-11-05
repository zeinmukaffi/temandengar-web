@extends('layouts.public')

@section('title', 'Profil Konselor')

@section('content')

    <section class="hero-section">

        <div class="container text-center">

            <span class="section-label mb-3">
                Counselor Directory
            </span>

            <h1 class="hero-title mb-3">
                Kenali teman yang siap
                <span>mendengarkan.</span>
            </h1>

            <p class="hero-description mx-auto">

                Kenali profil konselor sebaya yang tersedia
                di TemanDengar.

            </p>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <form method="GET" action="{{ route('public.counselors.index') }}" class="mb-5">

                <div class="row justify-content-center">

                    <div class="col-lg-7">

                        <div class="input-group">

                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-lg" placeholder="Cari nama atau informasi konselor...">

                            <button class="btn btn-primary px-4">

                                <i class="bi bi-search"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </form>


            <div class="row g-4">

                @forelse($counselors as $counselor)

                    <div class="col-md-6 col-lg-4" data-reveal>

                        <div class="td-card text-center">

                            <div class="profile-avatar mx-auto mb-3">

                                @if ($counselor->photo_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($counselor->photo_path) }}"
                                        alt="Foto {{ $counselor->name }}">
                                @else
                                    {{ strtoupper(substr($counselor->name, 0, 1)) }}
                                @endif

                            </div>


                            <h5 class="fw-bold mb-1">
                                {{ $counselor->name }}
                            </h5>

                            <p class="text-muted-custom small">
                                {{ $counselor->headline ?: 'Konselor Sebaya' }}
                            </p>


                            @if ($counselor->education)
                                <p class="text-muted-custom small">
                                    <i class="bi bi-mortarboard me-1"></i>

                                    {{ $counselor->education }}
                                </p>
                            @endif


                            <p class="text-muted-custom small">
                                {{ Str::limit($counselor->bio, 120) }}
                            </p>


                            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">

                                @foreach ($counselor->specialties ?? [] as $specialty)
                                    <span class="profile-specialty">

                                        {{ $categoryLabels[$specialty] ?? $specialty }}

                                    </span>
                                @endforeach

                            </div>


                            <a href="{{ route('public.counselors.show', $counselor) }}"
                                class="btn btn-outline-primary w-100">

                                Lihat Profil

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center py-5">

                            <i class="bi bi-person-search fs-1 text-secondary"></i>

                            <h5 class="fw-bold mt-3">
                                Profil belum ditemukan.
                            </h5>

                            <p class="text-muted-custom">
                                Coba kata kunci lainnya atau kembali lagi nanti.
                            </p>

                            <a href="{{ route('public.counselors.index') }}" class="btn btn-outline-primary">

                                Reset Pencarian

                            </a>

                        </div>

                    </div>

                @endforelse

            </div>


            <div class="mt-5">

                {{ $counselors->links() }}

            </div>

        </div>

    </section>

@endsection
