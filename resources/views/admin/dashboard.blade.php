@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-4">

        <h3 class="fw-bold">Dashboard</h3>

        <p class="text-secondary">
            Selamat datang kembali, {{ auth()->user()->name }}.
        </p>

    </div>

    {{-- STATISTICS --}}

    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Total Permintaan
                        </span>

                        <i class="bi bi-inbox fs-4 text-primary"></i>

                    </div>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['total'] }}
                    </h2>

                    <small class="text-secondary">
                        Seluruh pengajuan konsultasi
                    </small>

                </div>

            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Menunggu
                        </span>

                        <i class="bi bi-clock fs-4 text-warning"></i>

                    </div>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['pending'] }}
                    </h2>

                    <small class="text-secondary">
                        Belum ditinjau konselor
                    </small>

                </div>

            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Terjadwal
                        </span>

                        <i class="bi bi-calendar-check fs-4 text-primary"></i>

                    </div>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['scheduled'] }}
                    </h2>

                    <small class="text-secondary">
                        Konsultasi telah dijadwalkan
                    </small>

                </div>

            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Selesai
                        </span>

                        <i class="bi bi-check-circle fs-4 text-success"></i>

                    </div>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['completed'] }}
                    </h2>

                    <small class="text-secondary">
                        Konsultasi telah diselesaikan
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- MONTHLY SUMMARY --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-3">

                <div class="bg-success-subtle rounded-3 p-3">
                    <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
                </div>

                <div>

                    <h5 class="fw-bold mb-1">
                        {{ $monthlyCompleted }}
                    </h5>

                    <p class="text-secondary small mb-0">
                        Konsultasi selesai bulan {{ now()->translatedFormat('F Y') }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- RECENT REQUESTS --}}

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="fw-bold mb-0">
                            Permintaan Terbaru
                        </h5>

                        <a href="{{ route('admin.consultations.index') }}" class="text-decoration-none small">

                            Lihat Semua

                        </a>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Topik</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($recentRequests as $item)
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'reviewing' => 'Ditinjau',
                                            'scheduled' => 'Terjadwal',
                                            'completed' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            'referred' => 'Dirujuk',
                                            'cancelled' => 'Dibatalkan',
                                        ];

                                        $statusColors = [
                                            'pending' => 'warning',
                                            'reviewing' => 'info',
                                            'scheduled' => 'primary',
                                            'completed' => 'success',
                                            'rejected' => 'danger',
                                            'referred' => 'secondary',
                                            'cancelled' => 'dark',
                                        ];
                                    @endphp

                                    <tr>

                                        <td>

                                            <div class="fw-semibold">
                                                {{ $item->alias }}
                                            </div>

                                            <small class="text-secondary">
                                                {{ $item->ticket_number }}
                                            </small>

                                        </td>

                                        <td>
                                            {{ $item->topic }}
                                        </td>

                                        <td>

                                            <span class="badge text-bg-{{ $statusColors[$item->status] ?? 'secondary' }}">

                                                {{ $statusLabels[$item->status] ?? $item->status }}

                                            </span>

                                        </td>

                                        <td>

                                            <a href="{{ route('admin.consultations.show', $item) }}"
                                                class="btn btn-sm btn-light">

                                                <i class="bi bi-arrow-right"></i>

                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center text-secondary py-4">

                                            Belum ada permintaan konsultasi.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- UPCOMING SCHEDULES --}}

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="fw-bold mb-0">
                            Jadwal Terdekat
                        </h5>

                        <a href="{{ route('admin.schedules.index') }}" class="text-decoration-none small">

                            Lihat Semua

                        </a>

                    </div>


                    @forelse($upcomingSchedules as $schedule)
                        <div class="border-bottom py-3">

                            <div class="d-flex justify-content-between">

                                <span class="fw-semibold">
                                    {{ $schedule->consultationRequest->alias }}
                                </span>

                                <span class="badge text-bg-primary">
                                    Terjadwal
                                </span>

                            </div>

                            <div class="small text-secondary mt-2">

                                <i class="bi bi-calendar-event me-1"></i>

                                {{ $schedule->scheduled_at->translatedFormat('d F Y') }}

                            </div>

                            <div class="small text-secondary mt-1">

                                <i class="bi bi-clock me-1"></i>

                                {{ $schedule->scheduled_at->format('H:i') }} WIB

                            </div>

                            <div class="small text-secondary mt-1">

                                <i class="bi bi-camera-video me-1"></i>

                                {{ ucfirst($schedule->method) }}

                            </div>

                            <div class="mt-2">

                                <a href="{{ route('admin.consultations.show', $schedule->consultationRequest) }}"
                                    class="small text-decoration-none">

                                    Lihat Detail

                                </a>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-secondary py-4">

                            <i class="bi bi-calendar-x fs-2"></i>

                            <p class="small mt-2 mb-0">
                                Belum ada jadwal konsultasi mendatang.
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </div>

    </div>

@endsection
