<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - TemanDengar</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container-fluid px-4">

            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold text-primary">
                TemanDengar.
            </a>

            <div class="d-flex align-items-center gap-3">

                <span class="small text-secondary">
                    {{ auth()->user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button class="btn btn-outline-danger btn-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <aside class="col-md-2 bg-white border-end min-vh-100 p-3">

                <p class="text-secondary small fw-semibold">
                    MENU UTAMA
                </p>

                <div class="nav flex-column gap-2">

                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-dark">
                        <i class="bi bi-grid me-2"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.consultations.index') }}" class="nav-link text-dark">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Permintaan Konsultasi
                    </a>

                    <a href="{{ route('admin.schedules.index') }}" class="nav-link text-dark">
                        <i class="bi bi-calendar-event me-2"></i>
                        Jadwal Konsultasi
                    </a>

                    <a href="{{ route('admin.resources.index') }}" class="nav-link text-dark">
                        <i class="bi bi-book me-2"></i>
                        Materi Pendukung
                    </a>

                </div>

            </aside>

            <main class="col-md-10 p-4">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </main>

        </div>
    </div>

</body>

</html>
