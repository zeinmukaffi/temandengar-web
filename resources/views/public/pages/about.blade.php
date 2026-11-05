@extends('layouts.public')

@section('title', 'Tentang Kami')

@section('content')

    <section class="hero-section">

        <div class="container py-4">

            <div class="row justify-content-center text-center">

                <div class="col-lg-8">

                    <span class="section-label mb-3">
                        Tentang TemanDengar
                    </span>

                    <h1 class="hero-title mb-4">
                        Karena setiap orang
                        <span>berhak didengar.</span>
                    </h1>

                    <p class="hero-description mx-auto">
                        TemanDengar hadir untuk menciptakan ruang dukungan
                        teman sebaya bagi mahasiswa dalam menghadapi
                        berbagai dinamika kehidupan perkuliahan.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <span class="section-label mb-3">
                        Latar Belakang
                    </span>

                    <h2 class="section-heading mb-4">
                        Menjadi tempat untuk berbagi cerita.
                    </h2>

                    <p class="section-description">

                        Masa perkuliahan merupakan bagian dari perjalanan
                        yang penuh pengalaman baru, tantangan, dan perubahan.

                    </p>

                    <p class="section-description">

                        Tidak semua tantangan mudah untuk dihadapi sendiri.
                        Terkadang, memiliki seseorang yang bersedia mendengarkan
                        dapat menjadi langkah awal untuk memahami situasi
                        yang sedang dialami.

                    </p>

                    <p class="section-description mb-0">

                        Melalui pendekatan konseling sebaya, TemanDengar
                        berusaha menyediakan ruang komunikasi yang suportif
                        bagi mahasiswa.

                    </p>

                </div>


                <div class="col-lg-6">

                    <div class="rounded-4 p-5 text-center" style="background: var(--td-lavender);">

                        <i class="bi bi-chat-heart-fill text-primary" style="font-size: 110px;"></i>

                        <h4 class="fw-bold mt-4">
                            Listen. Understand. Support.
                        </h4>

                        <p class="text-muted-custom small mb-0">
                            Mendengarkan, memahami, dan memberikan dukungan.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <section class="section-padding bg-soft">

        <div class="container">

            <div class="text-center mb-5">

                <span class="section-label mb-3">
                    Nilai Kami
                </span>

                <h2 class="section-heading">
                    Prinsip dalam setiap percakapan.
                </h2>

            </div>


            <div class="row g-4">

                <div class="col-md-4">

                    <div class="td-card text-center">

                        <div class="td-icon lavender mb-4">
                            <i class="bi bi-ear"></i>
                        </div>

                        <h5 class="fw-bold">Mendengarkan</h5>

                        <p class="text-muted-custom small mb-0">
                            Memberikan perhatian dan ruang bagi setiap
                            mahasiswa untuk menyampaikan ceritanya.
                        </p>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="td-card text-center">

                        <div class="td-icon mint mb-4">
                            <i class="bi bi-heart"></i>
                        </div>

                        <h5 class="fw-bold">Empati</h5>

                        <p class="text-muted-custom small mb-0">
                            Menghargai pengalaman dan sudut pandang
                            setiap orang tanpa menghakimi.
                        </p>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="td-card text-center">

                        <div class="td-icon peach mb-4">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <h5 class="fw-bold">Menghargai Privasi</h5>

                        <p class="text-muted-custom small mb-0">
                            Mengelola informasi konsultasi dengan
                            memperhatikan kerahasiaan dan batas akses.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <div class="cta-section text-center">

                <h2 class="fw-bold mb-3">
                    Mari mulai dari sebuah cerita.
                </h2>

                <p class="mb-4 opacity-75">
                    Temukan ruang untuk berbagi dan mendapatkan dukungan.
                </p>

                <a href="{{ route('consultations.create') }}" class="btn btn-light text-primary fw-semibold btn-lg">

                    Mulai Konsultasi

                </a>

            </div>

        </div>

    </section>

@endsection
