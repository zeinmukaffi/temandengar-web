@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-4">

        <h3 class="fw-bold">
            Dashboard
        </h3>

        <p class="text-secondary">
            Selamat datang kembali, {{ auth()->user()->name }}.
        </p>

    </div>

    <div class="row g-3">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <i class="bi bi-chat-left-text fs-2 text-primary"></i>

                    <h5 class="fw-bold mt-3">
                        Permintaan Konsultasi
                    </h5>

                    <p class="text-secondary small">
                        Lihat dan kelola pengajuan konsultasi
                        mahasiswa.
                    </p>

                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-primary">

                        Kelola Permintaan
                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection
