@extends('layouts.public')

@section('title', 'Materi Pendukung')

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

            <div class="text-center mb-5">

                <span class="badge bg-success-subtle text-success mb-3">
                    TemanDengar Resources
                </span>

                <h2 class="fw-bold">
                    Ruang Belajar dan Informasi
                </h2>

                <p class="text-secondary">
                    Temukan informasi dan materi yang dapat membantu
                    perjalanan perkuliahanmu.
                </p>

            </div>

            <div class="row g-4">

                @forelse($resources as $resource)
                    <div class="col-md-6 col-lg-4">

                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <span class="badge bg-primary-subtle text-primary mb-3">

                                    {{ $categoryLabels[$resource->category] ?? $resource->category }}

                                </span>

                                <h5 class="fw-bold">

                                    {{ $resource->title }}

                                </h5>

                                <p class="text-secondary small">

                                    {{ Str::limit($resource->description, 120) }}

                                </p>

                                <div class="mt-auto">

                                    <a href="{{ route('public.resources.show', $resource) }}"
                                        class="text-decoration-none fw-semibold">

                                        Baca Selengkapnya

                                        <i class="bi bi-arrow-right ms-1"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center py-5">

                            <i class="bi bi-book fs-1 text-secondary"></i>

                            <p class="text-secondary mt-3">
                                Belum ada materi yang dipublikasikan.
                            </p>

                        </div>

                    </div>
                @endforelse

            </div>

            <div class="mt-4">

                {{ $resources->links() }}

            </div>

        </div>

    </section>

@endsection
