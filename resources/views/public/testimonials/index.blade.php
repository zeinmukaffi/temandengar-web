@extends('layouts.public')

@section('title', 'Testimoni')

@section('content')

    <section class="hero-section">

        <div class="container text-center">

            <span class="section-label mb-3">
                Stories & Experiences
            </span>

            <h1 class="hero-title mb-3">

                Setiap pengalaman
                <span>memiliki cerita.</span>

            </h1>

            <p class="hero-description mx-auto">

                Temukan pengalaman yang telah disetujui untuk
                dibagikan oleh pengguna TemanDengar.

            </p>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <div class="row g-4">

                @forelse($testimonials as $testimonial)

                    <div class="col-md-6 col-lg-4">

                        <div class="td-card">

                            <div class="text-warning mb-3">

                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor

                            </div>

                            <p class="text-muted-custom">

                                "{{ $testimonial->comment }}"

                            </p>

                            <hr>

                            <h6 class="fw-bold mb-1">

                                {{ $testimonial->display_name }}

                            </h6>

                            <small class="text-secondary">

                                Pengguna TemanDengar

                            </small>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center bg-soft rounded-4 p-5">

                            <i class="bi bi-chat-square-heart fs-1 text-primary"></i>

                            <h5 class="fw-bold mt-3">

                                Belum Ada Testimoni

                            </h5>

                            <p class="text-muted-custom">

                                Pengalaman pengguna yang telah memberikan
                                persetujuan publikasi akan ditampilkan di sini.

                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

            <div class="mt-5">

                {{ $testimonials->links() }}

            </div>

        </div>

    </section>

@endsection
