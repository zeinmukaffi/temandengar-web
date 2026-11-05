@extends('layouts.dashboard')

@section('title', 'Notifikasi')

@section('content')

    {{-- HEADER --}}

    <div
        class="d-flex flex-column flex-md-row
            justify-content-between align-items-md-center
            gap-3 mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Notifikasi
            </h3>

            <p class="text-secondary mb-0">
                Pantau aktivitas terbaru TemanDengar.
            </p>

        </div>


        @if ($unreadCount > 0)
            <form action="{{ route('admin.notifications.read-all') }}" method="POST">

                @csrf

                <button class="btn btn-outline-primary">

                    <i class="bi bi-check2-all me-1"></i>

                    Tandai Semua Dibaca

                </button>

            </form>
        @endif

    </div>


    {{-- NOTIFICATION SUMMARY --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <small class="text-secondary">
                        NOTIFIKASI BELUM DIBACA
                    </small>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $unreadCount }}
                    </h2>

                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    <span class="badge bg-primary-subtle text-primary p-2">

                        <i class="bi bi-bell me-1"></i>

                        Notification Center

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- NOTIFICATION LIST --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-3 p-md-4">

            <h5 class="fw-bold mb-4">
                Semua Notifikasi
            </h5>


            <div class="d-flex flex-column gap-2">

                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);

                        $icon = $data['icon'] ?? 'bi-bell';
                    @endphp


                    <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">

                        @csrf

                        <button type="submit"
                            class="notification-list-item
                               {{ $isUnread ? 'unread' : 'read' }}">

                            <div class="notification-icon">

                                <i class="bi {{ $icon }}"></i>

                            </div>


                            <div class="flex-grow-1 text-start">

                                <div
                                    class="d-flex flex-wrap
                                        justify-content-between
                                        align-items-center gap-2">

                                    <h6 class="fw-bold mb-1">

                                        {{ $data['title'] ?? 'Notifikasi' }}

                                    </h6>

                                    @if ($isUnread)
                                        <span class="badge text-bg-primary">
                                            Baru
                                        </span>
                                    @else
                                        <span class="small text-secondary">
                                            Sudah dibaca
                                        </span>
                                    @endif

                                </div>


                                <p class="text-secondary small mb-2">

                                    {{ $data['message'] ?? '' }}

                                </p>


                                <small class="text-secondary">

                                    <i class="bi bi-clock me-1"></i>

                                    {{ $notification->created_at->diffForHumans() }}

                                </small>

                            </div>


                            <i class="bi bi-chevron-right text-secondary"></i>

                        </button>

                    </form>

                @empty

                    <div class="text-center py-5">

                        <i class="bi bi-bell-slash fs-1 text-secondary"></i>

                        <h5 class="fw-bold mt-3">
                            Belum Ada Notifikasi
                        </h5>

                        <p class="text-secondary small">
                            Informasi aktivitas TemanDengar
                            akan muncul di halaman ini.
                        </p>

                    </div>
                @endforelse

            </div>


            <div class="mt-4">

                {{ $notifications->links() }}

            </div>

        </div>

    </div>

@endsection
