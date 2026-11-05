<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'TemanDengar') - TemanDengar</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container py-2">

            <a href="{{ route('home') }}" class="navbar-brand fw-bold text-primary">
                TemanDengar.
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-sm">
                    Mulai Curhat
                </a>

                <a href="{{ route('public.resources.index') }}" class="text-decoration-none text-secondary small">
                    Materi
                </a>

                <a href="{{ route('login') }}" class="text-decoration-none text-secondary small">
                    Login
                </a>
            </div>

        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="text-center py-4 mt-5 border-top bg-white">
        <p class="text-secondary small mb-0">
            &copy; {{ date('Y') }} TemanDengar.
            Ruang aman untuk didengar.
        </p>
    </footer>

</body>

</html>
