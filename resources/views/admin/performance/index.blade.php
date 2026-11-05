@extends('layouts.dashboard')

@section('title', 'Statistik Performa')

@section('content')

    {{-- HEADER --}}

    <div
        class="d-flex flex-column flex-md-row
            justify-content-between align-items-md-center
            gap-3 mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Statistik Performa
            </h3>

            <p class="text-secondary mb-0">
                Pantau perkembangan layanan konsultasi TemanDengar.
            </p>

        </div>

        <span class="badge bg-light text-secondary border p-2">

            <i class="bi bi-calendar3 me-1"></i>

            6 Bulan Terakhir

        </span>

    </div>


    {{-- STATISTIC CARDS --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL REQUESTS --}}

        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Total Permintaan
                        </span>

                        <div class="td-icon lavender">
                            <i class="bi bi-inbox"></i>
                        </div>

                    </div>

                    <h2 class="fw-bold mt-3 mb-1">
                        {{ number_format($stats['total_requests']) }}
                    </h2>

                    <small class="text-secondary">
                        Seluruh permintaan konsultasi
                    </small>

                </div>

            </div>

        </div>


        {{-- COMPLETED --}}

        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Sesi Selesai
                        </span>

                        <div class="td-icon mint">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                    <h2 class="fw-bold mt-3 mb-1">
                        {{ number_format($stats['completed']) }}
                    </h2>

                    <small class="text-secondary">
                        Konsultasi berhasil diselesaikan
                    </small>

                </div>

            </div>

        </div>


        {{-- UPCOMING --}}

        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Jadwal Mendatang
                        </span>

                        <div class="td-icon peach">
                            <i class="bi bi-calendar-event"></i>
                        </div>

                    </div>

                    <h2 class="fw-bold mt-3 mb-1">
                        {{ number_format($stats['upcoming']) }}
                    </h2>

                    <small class="text-secondary">
                        Sesi yang belum dilaksanakan
                    </small>

                </div>

            </div>

        </div>


        {{-- RATING --}}

        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <span class="text-secondary small">
                            Rata-rata Rating
                        </span>

                        <div class="td-icon lavender">
                            <i class="bi bi-star"></i>
                        </div>

                    </div>

                    <h2 class="fw-bold mt-3 mb-1">

                        {{ number_format($stats['average_rating'], 1) }}

                        <small class="text-warning fs-5">
                            <i class="bi bi-star-fill"></i>
                        </small>

                    </h2>

                    <small class="text-secondary">
                        Dari {{ $stats['total_feedback'] }} feedback
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- MONTHLY TREND --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="mb-4">

                <h5 class="fw-bold mb-1">
                    Tren Konsultasi Bulanan
                </h5>

                <p class="text-secondary small mb-0">
                    Perbandingan pengajuan baru dan sesi konsultasi selesai.
                </p>

            </div>

            <div class="performance-chart-wrapper">

                <canvas id="monthlyConsultationChart"></canvas>

            </div>

        </div>

    </div>


    {{-- TOPIC AND STATUS CHARTS --}}

    <div class="row g-4">

        {{-- TOPIC DISTRIBUTION --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        Distribusi Topik
                    </h5>

                    <p class="text-secondary small">
                        Topik yang diajukan oleh mahasiswa.
                    </p>

                    @if ($topicStats['total'] > 0)
                        <div class="performance-doughnut-wrapper">

                            <canvas id="topicChart"></canvas>

                        </div>
                    @else
                        <div class="chart-empty">

                            <i class="bi bi-pie-chart fs-1"></i>

                            <p class="small mt-2 mb-0">
                                Belum ada data topik konsultasi.
                            </p>

                        </div>
                    @endif

                </div>

            </div>

        </div>


        {{-- STATUS DISTRIBUTION --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        Distribusi Status
                    </h5>

                    <p class="text-secondary small">
                        Status seluruh permintaan konsultasi.
                    </p>

                    @if ($statusStats['total'] > 0)
                        <div class="performance-doughnut-wrapper">

                            <canvas id="statusChart"></canvas>

                        </div>
                    @else
                        <div class="chart-empty">

                            <i class="bi bi-bar-chart fs-1"></i>

                            <p class="small mt-2 mb-0">
                                Belum ada data status konsultasi.
                            </p>

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- CHART DATA --}}

    <script>
        window.temanDengarPerformance = @js([
    'monthly' => $monthlyStats,
    'topics' => $topicStats,
    'statuses' => $statusStats,
]);
    </script>

@endsection
