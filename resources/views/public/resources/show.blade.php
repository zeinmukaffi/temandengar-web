@extends('layouts.public')

@section('title', $resource->title)

@section('content')

    @php
        $categoryLabels = [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Sosial dan Pertemanan',
            'organization' => 'Organisasi',
            'professional_support' => 'Bantuan Profesional',
            'general' => 'Umum',
        ];
    @endphp

    <section class="py-5">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <a href="{{ route('public.resources.index') }}" class="text-decoration-none text-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Materi

                    </a>

                    <div class="mt-4">

                        <span class="badge bg-primary-subtle text-primary mb-3">

                            {{ $categoryLabels[$resource->category] ?? $resource->category }}

                        </span>

                        <h1 class="fw-bold mb-3">

                            {{ $resource->title }}

                        </h1>

                        <p class="text-secondary small">

                            Dipublikasikan pada
                            {{ $resource->created_at->translatedFormat('d F Y') }}

                        </p>

                        <hr>

                        <div class="py-3">

                            <p class="text-secondary" style="white-space: pre-line;">

                                {{ $resource->description }}

                            </p>

                        </div>

                        <div class="bg-light rounded-4 p-4 mt-4">

                            <h5 class="fw-semibold">
                                Baca Materi Lengkap
                            </h5>

                            <p class="text-secondary small">
                                Kunjungi sumber informasi berikut untuk
                                membaca materi selengkapnya.
                            </p>

                            <a href="{{ $resource->resource_url }}" target="_blank" rel="noopener noreferrer"
                                class="btn btn-primary">

                                Buka Sumber Materi
                                <i class="bi bi-box-arrow-up-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
