@extends('layouts.public')

@section('title', 'Materi Pendukung')

@section('content')

    <section class="hero-section">

        <div class="container text-center">

            <span class="section-label mb-3">
                Knowledge Corner
            </span>

            <h1 class="hero-title mb-3">
                Wawasan untuk
                <span>bertumbuh bersama.</span>
            </h1>

            <p class="hero-description mx-auto">
                Temukan artikel dan informasi yang dapat membantu
                perjalanan kehidupan perkuliahanmu.
            </p>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <form method="GET" action="{{ route('public.resources.index') }}" class="row g-2 mb-5">

                <div class="col-md-7">

                    <input type="text" name="search" class="form-control form-control-lg"
                        value="{{ request('search') }}" placeholder="Cari materi atau artikel...">

                </div>

                <div class="col-md-3">

                    <select name="category" class="form-select form-select-lg">

                        <option value="">Semua Kategori</option>

                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(request('category') === $value)>

                                {{ $label }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary btn-lg w-100">
                        Cari
                    </button>

                </div>

            </form>


            <div class="row g-4">

                @forelse($resources as $resource)
                    <div class="col-md-6 col-lg-4" data-reveal>

                        <div class="td-card">

                            <div class="td-icon lavender mb-4">

                                <i class="bi bi-journal-richtext"></i>

                            </div>

                            <small class="text-primary fw-semibold">

                                {{ $categories[$resource->category] ?? $resource->category }}

                            </small>

                            <h5 class="fw-bold mt-2">
                                {{ $resource->title }}
                            </h5>

                            <p class="text-muted-custom small">
                                {{ Str::limit($resource->description, 130) }}
                            </p>

                            <small class="text-secondary d-block mb-3">

                                {{ $resource->created_at->translatedFormat('d F Y') }}

                            </small>

                            <a href="{{ route('public.resources.show', $resource) }}"
                                class="text-primary text-decoration-none fw-semibold small">

                                Baca Selengkapnya

                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="bg-soft rounded-4 text-center p-5">

                            <i class="bi bi-search fs-1 text-secondary"></i>

                            <h5 class="fw-bold mt-3">
                                Materi tidak ditemukan.
                            </h5>

                            <p class="text-muted-custom mb-3">
                                Coba kata kunci atau kategori lainnya.
                            </p>

                            <a href="{{ route('public.resources.index') }}" class="btn btn-outline-primary">

                                Reset Filter

                            </a>

                        </div>

                    </div>
                @endforelse

            </div>


            <div class="mt-5">
                {{ $resources->links() }}
            </div>

        </div>

    </section>

@endsection
