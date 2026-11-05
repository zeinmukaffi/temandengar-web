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

                {{-- NOTIFICATION DROPDOWN --}}

                <div class="dropdown">

                    <button class="btn btn-light notification-bell" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="Buka notifikasi">

                        <i class="bi bi-bell fs-5"></i>

                        @if ($unreadNotificationCount > 0)
                            <span class="notification-badge">

                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}

                            </span>
                        @endif

                    </button>


                    <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0">

                        <div
                            class="p-3 border-bottom d-flex
                        justify-content-between align-items-center">

                            <h6 class="fw-bold mb-0">
                                Notifikasi
                            </h6>

                            <span class="badge bg-primary-subtle text-primary">

                                {{ $unreadNotificationCount }} Baru

                            </span>

                        </div>


                        <div class="notification-dropdown-list">

                            @forelse($recentNotifications as $notification)
                                @php
                                    $data = $notification->data;
                                @endphp

                                <form action="{{ route('admin.notifications.read', $notification->id) }}"
                                    method="POST">

                                    @csrf

                                    <button type="submit"
                                        class="notification-dropdown-item
                                {{ is_null($notification->read_at) ? 'unread' : '' }}">

                                        <div class="notification-icon">

                                            <i class="bi {{ $data['icon'] ?? 'bi-bell' }}"></i>

                                        </div>

                                        <div class="flex-grow-1 text-start">

                                            <div class="fw-semibold small mb-1">

                                                {{ $data['title'] ?? 'Notifikasi' }}

                                            </div>

                                            <p class="text-secondary small mb-1">

                                                {{ $data['message'] ?? '' }}

                                            </p>

                                            <small class="text-secondary">

                                                {{ $notification->created_at->diffForHumans() }}

                                            </small>

                                        </div>

                                        @if (is_null($notification->read_at))
                                            <span class="notification-dot"></span>
                                        @endif

                                    </button>

                                </form>

                            @empty

                                <div class="text-center p-4">

                                    <i class="bi bi-bell-slash fs-3 text-secondary"></i>

                                    <p class="small text-secondary mt-2 mb-0">
                                        Belum ada notifikasi.
                                    </p>

                                </div>
                            @endforelse

                        </div>


                        <div class="border-top p-2 d-flex gap-2">

                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-light btn-sm flex-grow-1">

                                Lihat Semua

                            </a>


                            @if ($unreadNotificationCount > 0)
                                <form action="{{ route('admin.notifications.read-all') }}" method="POST">

                                    @csrf

                                    <button class="btn btn-outline-primary btn-sm">

                                        <i class="bi bi-check2-all"></i>

                                    </button>

                                </form>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- COUNSELOR PROFILE NAME --}}

                <span class="small text-secondary d-none d-sm-inline">

                    {{ auth()->user()->name }}

                </span>


                {{-- LOGOUT --}}

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

            <aside class="col-md-2 bg-white border-end p-3 sidebar">

                <p class="text-secondary small fw-semibold">
                    MENU UTAMA
                </p>

                <div class="nav flex-column gap-2">

                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-dark">
                        <i class="bi bi-grid me-2"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.pipeline.index') }}" class="nav-link text-dark">
                        <i class="bi bi-kanban me-2"></i>
                        Pipeline Kasus
                    </a>

                    <a href="{{ route('admin.performance.index') }}" class="nav-link text-dark">
                        <i class="bi bi-bar-chart-line me-2"></i>
                        Statistik Performa
                    </a>

                    <a href="{{ route('admin.consultations.index') }}" class="nav-link text-dark">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Permintaan Konsultasi
                    </a>

                    <a href="{{ route('admin.schedules.index') }}" class="nav-link text-dark">
                        <i class="bi bi-calendar-event me-2"></i>
                        Jadwal Konsultasi
                    </a>

                    <a href="{{ route('admin.notifications.index') }}" class="nav-link text-dark">

                        <i class="bi bi-bell me-2"></i>

                        Notifikasi

                        @if ($unreadNotificationCount > 0)
                            <span class="badge text-bg-danger ms-auto">

                                {{ $unreadNotificationCount }}

                            </span>
                        @endif

                    </a>

                    <a href="{{ route('admin.resources.index') }}" class="nav-link text-dark">
                        <i class="bi bi-book me-2"></i>
                        Materi Pendukung
                    </a>

                    <a href="{{ route('admin.feedback.index') }}" class="nav-link text-dark">

                        <i class="bi bi-star me-2"></i>
                        Feedback & Rating

                    </a>

                </div>

            </aside>

            <main class="col-md-10 p-4 dashboard-main">

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
