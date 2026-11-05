@extends('layouts.public')

@section('title', 'Beranda')

@section('meta_description', 'TemanDengar merupakan ruang dukungan teman sebaya untuk mahasiswa yang ingin berbagi
    cerita dan mendapatkan dukungan.')

@section('content')

    @php
        $categoryLabels = [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Sosial dan Pertemanan',
            'organization' => 'Organisasi',
            'professional_support' => 'Bantuan Profesional',
            'general' => 'Umum',
        ];

        $faqPreview = [
            [
                'question' => 'Apakah saya perlu membuat akun?',
                'answer' => 'Tidak. Mahasiswa dapat mengajukan konsultasi tanpa membuat akun.',
            ],
            [
                'question' => 'Apakah konsultasi dilakukan secara online?',
                'answer' =>
                    'Konsultasi dapat dilakukan secara online atau tatap muka sesuai kesepakatan dengan konselor.',
            ],
            [
                'question' => 'Apakah TemanDengar memberikan diagnosis?',
                'answer' =>
                    'Tidak. TemanDengar merupakan layanan dukungan teman sebaya dan bukan pengganti bantuan profesional.',
            ],
        ];
    @endphp


    {{-- =======================================
    HERO SECTION
======================================= --}}

    <section class="hero-section">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6" data-reveal>

                    <span class="section-label mb-4">

                        <i class="bi bi-heart-pulse-fill"></i>

                        Peer Support for Students

                    </span>

                    <h1 class="hero-title mb-4">

                        Setiap cerita
                        <span>layak didengar.</span>

                    </h1>

                    <p class="hero-description mb-4">

                        Ada kalanya kamu hanya membutuhkan seseorang
                        yang mau mendengarkan. TemanDengar hadir sebagai
                        ruang berbagi dan mendapatkan dukungan dari
                        teman sebaya.

                    </p>

                    <div class="d-flex flex-wrap gap-3">

                        <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-lg">

                            Mulai Curhat

                            <i class="bi bi-arrow-right ms-2"></i>

                        </a>

                        <a href="{{ route('public.about') }}" class="btn btn-outline-primary btn-lg">

                            Kenali Kami

                        </a>

                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4">

                        <i class="bi bi-shield-check text-success fs-5"></i>

                        <small class="text-muted-custom">

                            Pengajuan konsultasi tanpa perlu membuat akun.

                        </small>

                    </div>

                </div>


                <div class="col-lg-6" data-reveal>

                    <div class="hero-visual">

                        <div class="hero-floating-card float-one">

                            <i class="bi bi-chat-heart-fill text-primary me-2"></i>

                            Ruang untuk bercerita

                        </div>


                        <div class="hero-main-card">

                            <div class="d-flex align-items-center gap-3 mb-4">

                                <div class="td-icon lavender">

                                    <i class="bi bi-chat-heart-fill"></i>

                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Kamu tidak sendiri.
                                    </h6>

                                    <small class="text-secondary">
                                        TemanDengar siap mendengarkan.
                                    </small>

                                </div>

                            </div>


                            <div class="bg-light rounded-4 p-3 mb-3">

                                <small class="text-secondary">
                                    Mahasiswa
                                </small>

                                <p class="small mb-0 mt-2">

                                    "Akhir-akhir ini aku merasa kesulitan
                                    membagi waktu antara kuliah dan organisasi."

                                </p>

                            </div>


                            <div class="bg-success-subtle rounded-4 p-3">

                                <small class="text-success fw-semibold">
                                    Dukungan teman sebaya
                                </small>

                                <p class="small mb-0 mt-2">

                                    "Terima kasih sudah mau berbagi.
                                    Kita bisa membicarakannya bersama."

                                </p>

                            </div>


                            <div class="mt-4 text-center">

                                <span class="badge rounded-pill text-bg-light text-primary p-2">

                                    <i class="bi bi-heart-fill me-1"></i>

                                    Listen · Understand · Support

                                </span>

                            </div>

                        </div>


                        <div class="hero-floating-card float-two">

                            <i class="bi bi-calendar-check text-success me-2"></i>

                            Online & Tatap Muka

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =======================================
    STATISTICS
======================================= --}}

    <section class="py-4 border-bottom">

        <div class="container">

            <div class="row g-4 text-center">

                <div class="col-4">

                    <h3 class="stat-value text-primary mb-1">
                        {{ $stats['counselors'] }}
                    </h3>

                    <small class="text-muted-custom">
                        Profil Konselor
                    </small>

                </div>

                <div class="col-4">

                    <h3 class="stat-value text-primary mb-1">
                        {{ $stats['completed'] }}
                    </h3>

                    <small class="text-muted-custom">
                        Sesi Selesai
                    </small>

                </div>

                <div class="col-4">

                    <h3 class="stat-value text-primary mb-1">
                        {{ $stats['resources'] }}
                    </h3>

                    <small class="text-muted-custom">
                        Materi Tersedia
                    </small>

                </div>

            </div>

        </div>

    </section>


    {{-- =======================================
    ABOUT PREVIEW
======================================= --}}

    <section class="section-padding">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-5" data-reveal>

                    <div class="rounded-4 p-5 text-center" style="background: var(--td-mint);">

                        <i class="bi bi-people-fill" style="font-size: 120px; color: #438C76;"></i>

                        <h5 class="fw-bold mt-4">
                            Bertumbuh bersama.
                        </h5>

                        <p class="text-muted-custom small mb-0">

                            Karena terkadang, dukungan terbaik dimulai
                            dari seseorang yang mau mendengarkan.

                        </p>

                    </div>

                </div>


                <div class="col-lg-7" data-reveal>

                    <span class="section-label mb-3">
                        Tentang TemanDengar
                    </span>

                    <h2 class="section-heading mb-4">

                        Bercerita adalah langkah kecil
                        untuk merasa lebih didengar.

                    </h2>

                    <p class="section-description">

                        Kehidupan mahasiswa tidak selalu berjalan mudah.
                        Ada tuntutan akademik, dinamika pertemanan,
                        kegiatan organisasi, hingga proses mengenal diri sendiri.

                    </p>

                    <p class="section-description">

                        TemanDengar menyediakan ruang dukungan teman sebaya
                        agar mahasiswa dapat berbagi pengalaman dan
                        membicarakan hal yang sedang dihadapi.

                    </p>

                    <a href="{{ route('public.about') }}" class="btn btn-outline-primary mt-2">

                        Pelajari Tentang Kami

                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =======================================
    SERVICES
======================================= --}}

    <section class="section-padding bg-soft">

        <div class="container">

            <div class="text-center mb-5" data-reveal>

                <span class="section-label mb-3">
                    Layanan Kami
                </span>

                <h2 class="section-heading">
                    Ceritakan apa yang sedang kamu hadapi.
                </h2>

                <p class="section-description">

                    Mulai dari kehidupan akademik hingga dinamika
                    kehidupan kampus.

                </p>

            </div>


            <div class="row g-4">

                @foreach ($services as $service)
                    <div class="col-sm-6 col-lg-3" data-reveal>

                        <div class="td-card">

                            <div class="td-icon {{ $service['color'] }} mb-4">

                                <i class="bi {{ $service['icon'] }}"></i>

                            </div>

                            <h5 class="fw-bold mb-3">

                                {{ $service['title'] }}

                            </h5>

                            <p class="text-muted-custom small mb-4">

                                {{ $service['description'] }}

                            </p>

                            <a href="{{ route('public.services') }}"
                                class="text-decoration-none text-primary fw-semibold small">

                                Pelajari

                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>
                @endforeach

            </div>

            <div class="text-center mt-5">

                <a href="{{ route('public.services') }}" class="btn btn-outline-primary">

                    Lihat Semua Layanan

                </a>

            </div>

        </div>

    </section>


    {{-- =======================================
    HOW IT WORKS
======================================= --}}

    <section class="section-padding">

        <div class="container">

            <div class="text-center mb-5" data-reveal>

                <span class="section-label mb-3">
                    Bagaimana Cara Kerjanya?
                </span>

                <h2 class="section-heading">
                    Tiga langkah untuk mulai bercerita.
                </h2>

            </div>


            <div class="row g-4">

                <div class="col-md-4" data-reveal>

                    <div class="td-card text-center">

                        <div class="step-number mb-4">
                            01
                        </div>

                        <h5 class="fw-bold">
                            Isi Formulir
                        </h5>

                        <p class="text-muted-custom small mb-0">

                            Ceritakan kebutuhanmu melalui formulir
                            pengajuan konsultasi.

                        </p>

                    </div>

                </div>


                <div class="col-md-4" data-reveal>

                    <div class="td-card text-center">

                        <div class="step-number mb-4">
                            02
                        </div>

                        <h5 class="fw-bold">
                            Dihubungi Konselor
                        </h5>

                        <p class="text-muted-custom small mb-0">

                            Konselor akan meninjau permintaan dan
                            menghubungimu untuk menyepakati jadwal.

                        </p>

                    </div>

                </div>


                <div class="col-md-4" data-reveal>

                    <div class="td-card text-center">

                        <div class="step-number mb-4">
                            03
                        </div>

                        <h5 class="fw-bold">
                            Mulai Konsultasi
                        </h5>

                        <p class="text-muted-custom small mb-0">

                            Bercerita melalui pertemuan online
                            atau tatap muka yang telah disepakati.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =======================================
    COUNSELOR PREVIEW
======================================= --}}

    <section class="section-padding bg-soft">

        <div class="container">

            <div class="d-flex flex-column flex-md-row justify-content-between
                    align-items-md-end gap-3 mb-5"
                data-reveal>

                <div>

                    <span class="section-label mb-3">
                        Kenali Konselor
                    </span>

                    <h2 class="section-heading mb-2">
                        Ada teman yang siap mendengarkan.
                    </h2>

                    <p class="section-description mb-0">
                        Kenali profil konselor sebaya TemanDengar.
                    </p>

                </div>

                <a href="{{ route('public.counselors.index') }}" class="btn btn-outline-primary">

                    Lihat Semua Konselor

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>


            <div class="row g-4">

                @forelse($counselors as $counselor)

                    <div class="col-md-6 col-lg-4" data-reveal>

                        <div class="td-card">

                            <div class="d-flex align-items-center gap-3 mb-4">

                                <div class="profile-avatar">

                                    @if ($counselor->photo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($counselor->photo_path) }}"
                                            alt="Foto {{ $counselor->name }}">
                                    @else
                                        {{ strtoupper(substr($counselor->name, 0, 1)) }}
                                    @endif

                                </div>

                                <div>

                                    <h5 class="fw-bold mb-1">
                                        {{ $counselor->name }}
                                    </h5>

                                    <small class="text-muted-custom">
                                        {{ $counselor->headline ?: 'Konselor Sebaya' }}
                                    </small>

                                </div>

                            </div>


                            <p class="text-muted-custom small">

                                {{ Str::limit($counselor->bio, 120) }}

                            </p>


                            <div class="d-flex flex-wrap gap-2 mb-4">

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

                        <div class="td-card text-center py-5">

                            <i class="bi bi-person-heart fs-1 text-primary"></i>

                            <h5 class="fw-bold mt-3">
                                Profil Konselor Segera Hadir
                            </h5>

                            <p class="text-muted-custom small mb-0">
                                Informasi konselor sedang dipersiapkan.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =======================================
    RESOURCES PREVIEW
======================================= --}}

    <section class="section-padding">

        <div class="container">

            <div class="d-flex justify-content-between align-items-end mb-5">

                <div>

                    <span class="section-label mb-3">
                        Knowledge Corner
                    </span>

                    <h2 class="section-heading">
                        Temukan wawasan baru.
                    </h2>

                    <p class="section-description mb-0">
                        Materi dan informasi pendukung kehidupan mahasiswa.
                    </p>

                </div>

                <a href="{{ route('public.resources.index') }}" class="btn btn-outline-primary d-none d-md-inline-block">

                    Semua Materi

                </a>

            </div>


            <div class="row g-4">

                @forelse($resources as $resource)
                    <div class="col-md-6 col-lg-4" data-reveal>

                        <div class="td-card">

                            <div class="td-icon lavender mb-4">

                                <i class="bi bi-journal-richtext"></i>

                            </div>

                            <small class="text-primary fw-semibold">
                                {{ $categoryLabels[$resource->category] ?? $resource->category }}
                            </small>

                            <h5 class="fw-bold mt-2">
                                {{ $resource->title }}
                            </h5>

                            <p class="text-muted-custom small">
                                {{ Str::limit($resource->description, 110) }}
                            </p>

                            <a href="{{ route('public.resources.show', $resource) }}"
                                class="text-primary text-decoration-none fw-semibold small">

                                Baca Selengkapnya

                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center p-5 bg-soft rounded-4">

                            <i class="bi bi-journal-text fs-1 text-secondary"></i>

                            <p class="text-muted-custom mt-3 mb-0">
                                Materi pendukung akan segera tersedia.
                            </p>

                        </div>

                    </div>
                @endforelse

            </div>

        </div>

    </section>


    {{-- =======================================
    TESTIMONIALS
======================================= --}}

    @if ($testimonials->isNotEmpty())

        <section class="section-padding bg-soft">

            <div class="container">

                <div class="text-center mb-5" data-reveal>

                    <span class="section-label mb-3">
                        Cerita Mereka
                    </span>

                    <h2 class="section-heading">
                        Setiap cerita memiliki arti.
                    </h2>

                </div>


                <div class="row g-4">

                    @foreach ($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-4" data-reveal>

                            <div class="td-card">

                                <div class="text-warning mb-3">

                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor

                                </div>

                                <p class="text-muted-custom">
                                    "{{ $testimonial->comment }}"
                                </p>

                                <h6 class="fw-bold mb-0">
                                    {{ $testimonial->display_name }}
                                </h6>

                                <small class="text-secondary">
                                    Pengguna TemanDengar
                                </small>

                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="text-center mt-4">

                    <a href="{{ route('public.testimonials.index') }}" class="btn btn-outline-primary">

                        Lihat Semua Testimoni

                    </a>

                </div>

            </div>

        </section>

    @endif


    {{-- =======================================
    FAQ PREVIEW
======================================= --}}

    <section class="section-padding">

        <div class="container">

            <div class="row g-5">

                <div class="col-lg-4" data-reveal>

                    <span class="section-label mb-3">
                        FAQ
                    </span>

                    <h2 class="section-heading mb-3">
                        Punya pertanyaan?
                    </h2>

                    <p class="section-description">
                        Temukan jawaban atas pertanyaan umum mengenai
                        layanan TemanDengar.
                    </p>

                    <a href="{{ route('public.faq') }}" class="btn btn-outline-primary">

                        Lihat Semua FAQ

                    </a>

                </div>


                <div class="col-lg-8" data-reveal>

                    <div class="accordion" id="homeFaq">

                        @foreach ($faqPreview as $faq)
                            <div class="accordion-item">

                                <h2 class="accordion-header">

                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#homeFaq{{ $loop->index }}">

                                        {{ $faq['question'] }}

                                    </button>

                                </h2>

                                <div id="homeFaq{{ $loop->index }}" class="accordion-collapse collapse"
                                    data-bs-parent="#homeFaq">

                                    <div class="accordion-body text-muted-custom small">

                                        {{ $faq['answer'] }}

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =======================================
    FINAL CTA
======================================= --}}

    <section class="pb-5">

        <div class="container">

            <div class="cta-section text-center" data-reveal>

                <i class="bi bi-chat-heart-fill fs-1 mb-3 d-block"></i>

                <h2 class="fw-bold mb-3">

                    Tidak perlu menunggu semuanya terasa berat.

                </h2>

                <p class="mb-4 opacity-75">

                    Mulai dengan satu cerita. Kami menyediakan ruang
                    untuk mendengarkan.

                </p>

                <a href="{{ route('consultations.create') }}" class="btn btn-light text-primary fw-semibold btn-lg">

                    Mulai Curhat

                    <i class="bi bi-arrow-right ms-2"></i>

                </a>

            </div>

        </div>

    </section>

@endsection
