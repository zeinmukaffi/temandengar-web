<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="@yield('meta_description', 'TemanDengar adalah platform dukungan dan konsultasi teman sebaya bagi mahasiswa.')">

    <meta name="theme-color" content="#6259D9">

    <title>@yield('title', 'Beranda') | TemanDengar</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

</head>

<body>

    <a href="#main-content" class="visually-hidden-focusable position-absolute top-0 start-0 p-3 bg-white">
        Lewati ke konten utama
    </a>

    {{-- NAVBAR --}}

    <nav class="navbar navbar-expand-xl td-navbar sticky-top">

        <div class="container">

            <a href="{{ route('home') }}" class="td-brand">

                <span class="td-brand-mark">
                    <i class="bi bi-chat-heart-fill"></i>
                </span>

                Teman<span>Dengar.</span>

            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#tdNavbar" aria-controls="tdNavbar" aria-expanded="false" aria-label="Buka navigasi">

                <i class="bi bi-list fs-2"></i>

            </button>

            <div class="collapse navbar-collapse" id="tdNavbar">

                <ul class="navbar-nav mx-auto align-items-xl-center gap-xl-1 mt-3 mt-xl-0">

                    <li class="nav-item">

                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            Beranda
                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('public.about') }}"
                            class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}">
                            Tentang
                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('public.services') }}"
                            class="nav-link {{ request()->routeIs('public.services') ? 'active' : '' }}">
                            Layanan
                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('public.counselors.index') }}"
                            class="nav-link {{ request()->routeIs('public.counselors.*') ? 'active' : '' }}">
                            Konselor
                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('public.resources.index') }}"
                            class="nav-link {{ request()->routeIs('public.resources.*') ? 'active' : '' }}">
                            Materi
                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('public.faq') }}"
                            class="nav-link {{ request()->routeIs('public.faq') ? 'active' : '' }}">
                            FAQ
                        </a>

                    </li>

                </ul>

                <div class="d-flex flex-column flex-xl-row gap-2 mt-3 mt-xl-0 mb-3 mb-xl-0">

                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">

                        Login Konselor

                    </a>

                    <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-sm">

                        Mulai Curhat
                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>

    </nav>


    {{-- FLASH MESSAGE --}}

    @if (session('success'))
        <div class="container mt-3">

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        </div>
    @endif


    {{-- MAIN CONTENT --}}

    <main id="main-content">

        @yield('content')

    </main>


    {{-- FOOTER --}}

    <footer class="td-footer">

        <div class="container">

            <div class="row g-4 pb-5">

                <div class="col-lg-4">

                    <a href="{{ route('home') }}" class="td-brand text-white">

                        <span class="td-brand-mark">
                            <i class="bi bi-chat-heart-fill"></i>
                        </span>

                        Teman<span>Dengar.</span>

                    </a>

                    <p class="mt-3 pe-lg-4">

                        Ruang untuk bercerita, berbagi pengalaman,
                        dan mendapatkan dukungan dari teman sebaya.

                    </p>

                </div>

                <div class="col-6 col-lg-2">

                    <h6 class="fw-bold mb-3">Jelajahi</h6>

                    <div class="d-flex flex-column gap-3">

                        <a href="{{ route('public.about') }}">Tentang Kami</a>

                        <a href="{{ route('public.services') }}">Layanan</a>

                        <a href="{{ route('public.counselors.index') }}">Konselor</a>

                        <a href="{{ route('public.resources.index') }}">Materi</a>

                    </div>

                </div>

                <div class="col-6 col-lg-3">

                    <h6 class="fw-bold mb-3">Informasi</h6>

                    <div class="d-flex flex-column gap-3">

                        <a href="{{ route('public.faq') }}">FAQ</a>

                        <a href="{{ route('public.testimonials.index') }}">Testimoni</a>

                        <a href="{{ route('public.privacy') }}">Kebijakan Privasi</a>

                        <a href="{{ route('login') }}">Portal Konselor</a>

                    </div>

                </div>

                <div class="col-lg-3">

                    <h6 class="fw-bold mb-3">Butuh Teman Cerita?</h6>

                    <p>
                        Mulai dengan mengisi formulir konsultasi
                        dan ceritakan kebutuhanmu.
                    </p>

                    <a href="{{ route('consultations.create') }}"
                        class="btn btn-light btn-sm text-primary fw-semibold">

                        Mulai Curhat
                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

            <div
                class="border-top border-secondary pt-4
                        d-flex flex-column flex-md-row
                        justify-content-between gap-2">

                <p class="mb-0">
                    &copy; {{ date('Y') }} TemanDengar. All rights reserved.
                </p>

                <p class="mb-0 small">
                    Ruang aman untuk didengar, teman untuk bercerita.
                </p>

            </div>

        </div>

    </footer>


    @stack('scripts')

</body>

</html>
