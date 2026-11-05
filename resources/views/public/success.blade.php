@extends('layouts.public')

@section('title', 'Pengajuan Berhasil')

@section('content')

    <section class="section-padding bg-soft min-vh-75 d-flex align-items-center">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-6">

                    <div class="td-card text-center p-5">

                        <div class="td-icon mint mx-auto mb-4" style="width:75px;height:75px;font-size:32px;">

                            <i class="bi bi-check-lg"></i>

                        </div>

                        <span class="section-label mb-3">
                            Submission Successful
                        </span>

                        <h2 class="fw-bold mb-3">
                            Ceritamu sudah kami terima.
                        </h2>

                        <p class="text-muted-custom">

                            Terima kasih telah mempercayai TemanDengar.
                            Permintaan konsultasimu berhasil dikirim
                            dan akan ditinjau oleh konselor.

                        </p>


                        <div class="bg-light rounded-4 p-4 my-4">

                            <small class="text-secondary fw-semibold">
                                NOMOR TIKET PENGAJUAN
                            </small>

                            <h3 class="fw-bold text-primary mt-2 mb-0">
                                {{ session('ticket_number') }}
                            </h3>

                        </div>


                        <p class="text-muted-custom small mb-4">

                            Simpan nomor tiket tersebut untuk referensi.
                            Konselor akan menghubungimu melalui kontak
                            yang telah diberikan.

                        </p>


                        <a href="{{ route('home') }}" class="btn btn-primary w-100">

                            Kembali ke Beranda

                            <i class="bi bi-house ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
