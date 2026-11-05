@extends('layouts.dashboard')

@section('title', 'Tambah Materi')

@section('content')

    <div class="mb-4">

        <a href="{{ route('admin.resources.index') }}" class="text-decoration-none text-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Materi

        </a>

        <h3 class="fw-bold mt-3">Tambah Materi</h3>

        <p class="text-secondary">
            Tambahkan materi pendukung untuk mahasiswa.
        </p>

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    @include('admin.resources._form')

                </div>

            </div>

        </div>

    </div>

@endsection
