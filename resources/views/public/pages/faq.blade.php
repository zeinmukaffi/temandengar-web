@extends('layouts.public')

@section('title', 'FAQ')

@section('content')

    <section class="hero-section">

        <div class="container text-center">

            <span class="section-label mb-3">
                Frequently Asked Questions
            </span>

            <h1 class="hero-title mb-3">
                Ada yang ingin
                <span>kamu tanyakan?</span>
            </h1>

            <p class="hero-description mx-auto">
                Temukan informasi mengenai layanan dan cara kerja TemanDengar.
            </p>

        </div>

    </section>


    <section class="section-padding">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="accordion" id="faqAccordion">

                        @foreach ($faqs as $faq)
                            <div class="accordion-item">

                                <h2 class="accordion-header">

                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq{{ $loop->index }}" aria-expanded="false">

                                        {{ $faq['question'] }}

                                    </button>

                                </h2>

                                <div id="faq{{ $loop->index }}" class="accordion-collapse collapse"
                                    data-bs-parent="#faqAccordion">

                                    <div class="accordion-body text-muted-custom">

                                        {{ $faq['answer'] }}

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <div class="text-center mt-5">

                        <p class="text-muted-custom">
                            Masih memiliki pertanyaan?
                        </p>

                        <a href="{{ route('consultations.create') }}" class="btn btn-primary">

                            Hubungi melalui Pengajuan Konsultasi

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
