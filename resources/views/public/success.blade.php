@extends('layouts.public')

@section('title', 'Pengajuan Berhasil')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 text-center">

                    <div class="card-body p-5">

                        <i class="bi bi-check-circle-fill text-success" style="font-size: 65px;"></i>

                        <h2 class="fw-bold mt-4">
                            Pengajuan Berhasil!
                        </h2>

                        <p class="text-secondary">
                            Terima kasih telah mempercayai TemanDengar.
                            Permintaan konsultasimu telah kami terima.
                        </p>

                        <div class="bg-light rounded-3 p-4 my-4">

                            <small class="text-secondary">
                                NOMOR TIKET KONSULTASI
                            </small>

                            <h3 class="fw-bold text-primary mt-2">
                                {{ session('ticket_number') }}
                            </h3>

                        </div>

                        <p class="small text-secondary">
                            Simpan nomor tiket ini untuk keperluan
                            administrasi konsultasi. Konselor akan
                            menghubungimu melalui kontak yang diberikan.
                        </p>

                        <a href="{{ route('home') }}" class="btn btn-primary w-100">

                            Kembali ke Beranda

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
