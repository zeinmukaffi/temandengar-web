@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <section class="py-5">
        <div class="container py-lg-5">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <span class="badge bg-success-subtle text-success mb-3">
                        Peer Counseling
                    </span>

                    <h1 class="display-5 fw-bold mb-4">
                        Tidak harus menghadapi semuanya sendirian.
                    </h1>

                    <p class="text-secondary mb-4">
                        TemanDengar adalah ruang untuk bercerita,
                        berbagi pengalaman, dan mendapatkan dukungan
                        dari teman sebaya.
                    </p>

                    <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-lg">
                        Mulai Curhat
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                </div>

                <div class="col-lg-6">

                    <div class="bg-white rounded-4 shadow-sm p-5 text-center">

                        <i class="bi bi-chat-heart text-primary" style="font-size: 100px;"></i>

                        <h4 class="fw-semibold mt-3">
                            Cerita kamu berarti.
                        </h4>

                        <p class="text-secondary mb-0">
                            Ada teman yang siap mendengarkan.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection
