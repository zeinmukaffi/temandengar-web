@extends('layouts.public')

@section('title', 'Feedback Sudah Dikirim')

@section('content')

    <section class="section-padding bg-soft min-vh-100 d-flex align-items-center">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-6">

                    <div class="td-card text-center p-5">

                        <div class="td-icon mint mx-auto mb-4" style="width:75px;height:75px;font-size:32px;">

                            <i class="bi bi-check-lg"></i>

                        </div>

                        <h2 class="fw-bold mb-3">
                            Feedback sudah dikirim.
                        </h2>

                        <p class="text-muted-custom mb-4">

                            Terima kasih sudah meluangkan waktu
                            untuk memberikan penilaian.
                            Kami menghargai setiap masukanmu.

                        </p>

                        <a href="{{ route('home') }}" class="btn btn-primary">

                            Kembali ke Beranda

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
