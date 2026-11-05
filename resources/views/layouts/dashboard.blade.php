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

                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                    data-bs-target="#logoutConfirmModal">

                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout

                </button>

            </div>

        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">


            <aside class="col-md-2 bg-white border-end p-3 sidebar dashboard-sidebar">

                {{-- OVERVIEW --}}
                <div class="sidebar-group">

                    <div class="sidebar-group-title">
                        OVERVIEW
                    </div>

                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>

                    </a>

                </div>


                {{-- CONSULTATION MANAGEMENT --}}
                <div class="sidebar-group">

                    <div class="sidebar-group-title">
                        MANAJEMEN KONSULTASI
                    </div>

                    <a href="{{ route('admin.pipeline.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.pipeline.*') ? 'active' : '' }}">

                        <i class="bi bi-kanban"></i>
                        <span>Pipeline Kasus</span>

                    </a>


                    <a href="{{ route('admin.consultations.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">

                        <i class="bi bi-chat-left-text"></i>
                        <span>Permintaan Konsultasi</span>

                    </a>


                    <a href="{{ route('admin.schedules.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">

                        <i class="bi bi-calendar-event"></i>
                        <span>Jadwal Konsultasi</span>

                    </a>

                </div>


                {{-- MONITORING --}}
                <div class="sidebar-group">

                    <div class="sidebar-group-title">
                        MONITORING
                    </div>

                    <a href="{{ route('admin.performance.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.performance.*') ? 'active' : '' }}">

                        <i class="bi bi-bar-chart-line"></i>
                        <span>Statistik Performa</span>

                    </a>


                    <a href="{{ route('admin.feedback.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">

                        <i class="bi bi-star"></i>
                        <span>Feedback & Rating</span>

                    </a>


                    <a href="{{ route('admin.notifications.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">

                        <i class="bi bi-bell"></i>
                        <span>Notifikasi</span>

                        @if ($unreadNotificationCount > 0)
                            <span class="badge text-bg-danger sidebar-count">

                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}

                            </span>
                        @endif

                    </a>

                </div>


                {{-- CONTENT --}}
                <div class="sidebar-group">

                    <div class="sidebar-group-title">
                        KONTEN
                    </div>

                    <a href="{{ route('admin.resources.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">

                        <i class="bi bi-book"></i>
                        <span>Materi Pendukung</span>

                    </a>

                </div>


                {{-- ACCOUNT SETTINGS --}}
                <div class="sidebar-group">

                    <div class="sidebar-group-title">
                        PENGATURAN AKUN
                    </div>

                    <a href="{{ route('admin.profile.edit') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">

                        <i class="bi bi-person-circle"></i>
                        <span>Profil Saya</span>

                    </a>


                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-link sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">

                        <i class="bi bi-gear"></i>
                        <span>Pengaturan Akun</span>

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

    {{-- LOGOUT CONFIRMATION MODAL --}}

    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content border-0 rounded-4">

                <div class="modal-body text-center p-4">

                    <div class="td-icon peach mx-auto mb-3">

                        <i class="bi bi-box-arrow-right"></i>

                    </div>

                    <h5 class="fw-bold" id="logoutConfirmLabel">
                        Keluar dari Akun?
                    </h5>

                    <p class="text-secondary small mb-4">

                        Apakah kamu yakin ingin mengakhiri
                        sesi TemanDengar?

                    </p>

                    <div class="d-flex gap-2">

                        <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <form action="{{ route('logout') }}" method="POST" class="flex-grow-1">

                            @csrf

                            <button type="submit" class="btn btn-danger w-100">

                                Ya, Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
