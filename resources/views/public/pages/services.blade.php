@extends('layouts.public')

@section('title', 'Layanan Konsultasi')

@section('content')

    <section class="hero-section">

        <div class="container text-center">

            <span class="section-label mb-3">
                Layanan TemanDengar
            </span>

            <h1 class="hero-title mb-4">

                Ada banyak hal yang bisa
                <span>dibicarakan.</span>

            </h1>

            <p class="hero-description mx-auto">

                TemanDengar menyediakan ruang dukungan teman sebaya
                untuk berbagai pengalaman kehidupan mahasiswa.

            </p>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <div class="row g-4">

                @foreach ($services as $service)
                    <div class="col-md-6" data-reveal>

                        <div class="td-card">

                            <div class="td-icon lavender mb-4">

                                <i class="bi {{ $service['icon'] }}"></i>

                            </div>

                            <h4 class="fw-bold mb-3">
                                {{ $service['title'] }}
                            </h4>

                            <p class="text-muted-custom mb-0">
                                {{ $service['description'] }}
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </section>


    <section class="section-padding bg-soft">

        <div class="container">

            <div class="row justify-content-center text-center">

                <div class="col-lg-9">

                    <i class="bi bi-info-circle-fill text-primary fs-1"></i>

                    <h2 class="fw-bold mt-3 mb-4">
                        Memahami batasan layanan
                    </h2>

                    <p class="section-description">

                        TemanDengar merupakan platform dukungan teman sebaya.
                        Layanan ini tidak ditujukan untuk memberikan diagnosis,
                        terapi klinis, atau pengganti bantuan psikolog dan
                        tenaga kesehatan mental profesional.

                    </p>

                    <p class="section-description mb-0">

                        Apabila kebutuhan seseorang memerlukan penanganan
                        profesional atau berada dalam situasi darurat,
                        konselor perlu mengarahkan kepada layanan bantuan
                        yang sesuai.

                    </p>

                </div>

            </div>

        </div>

    </section>


    <section class="section-padding">

        <div class="container text-center">

            <h2 class="section-heading mb-3">
                Ingin mulai bercerita?
            </h2>

            <p class="section-description">
                Ajukan permintaan konsultasi melalui formulir TemanDengar.
            </p>

            <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-lg">

                Mulai Curhat

                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

    </section>

@endsection
