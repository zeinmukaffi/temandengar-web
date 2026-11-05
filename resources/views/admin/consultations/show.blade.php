@extends('layouts.dashboard')

@section('title', 'Detail Konsultasi')

@section('content')

    @php
        $item = $consultationRequest;

        $topicLabels = [
            'academic' => 'Akademik dan Perkuliahan',
            'personal' => 'Masalah Pribadi',
            'social' => 'Pertemanan dan Sosial',
            'organization' => 'Organisasi dan Aktivitas',
            'other' => 'Lainnya',
        ];

        $statusLabels = [
            'pending' => 'Menunggu',
            'reviewing' => 'Ditinjau',
            'scheduled' => 'Terjadwal',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'referred' => 'Dirujuk',
            'cancelled' => 'Dibatalkan',
        ];
    @endphp

    <div class="mb-4">

        <a href="{{ route('admin.consultations.index') }}" class="text-decoration-none text-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Permintaan

        </a>

        <h3 class="fw-bold mt-3">Detail Permintaan Konsultasi</h3>

        <p class="text-secondary mb-0">
            {{ $item->ticket_number }}
        </p>

    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Informasi Mahasiswa
                    </h5>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            Nomor Tiket
                        </div>

                        <div class="col-sm-8 fw-semibold">
                            {{ $item->ticket_number }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            Nama Panggilan
                        </div>

                        <div class="col-sm-8">
                            {{ $item->alias }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            Email
                        </div>

                        <div class="col-sm-8">
                            {{ $item->email ?? '-' }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            WhatsApp
                        </div>

                        <div class="col-sm-8">
                            {{ $item->phone ?? '-' }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            Topik
                        </div>

                        <div class="col-sm-8">
                            {{ $topicLabels[$item->topic] ?? $item->topic }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            Metode
                        </div>

                        <div class="col-sm-8">
                            {{ ucfirst($item->preferred_method) }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 text-secondary">
                            Preferensi Tanggal
                        </div>

                        <div class="col-sm-8">
                            {{ $item->preferred_date?->format('d M Y') ?? 'Fleksibel' }}
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-4 text-secondary">
                            Preferensi Waktu
                        </div>

                        <div class="col-sm-8">
                            {{ $item->preferred_time ?? 'Fleksibel' }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Cerita Mahasiswa
                    </h5>

                    <div class="bg-light rounded-3 p-4">

                        <p class="mb-0" style="white-space: pre-line;">{{ $item->description }}</p>

                    </div>

                    <small class="text-secondary d-block mt-3">
                        Diajukan pada:
                        {{ $item->created_at->format('d M Y, H:i') }}
                    </small>

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Status Permintaan
                    </h5>

                    <span class="badge text-bg-secondary mb-4">
                        {{ $statusLabels[$item->status] ?? $item->status }}
                    </span>

                    @if (in_array($item->status, ['pending', 'reviewing']))
                        <form action="{{ route('admin.consultations.update-status', $item) }}" method="POST">

                            @csrf
                            @method('PATCH')

                            <label class="form-label">
                                Ubah Status
                            </label>

                            <select name="status" class="form-select mb-3" required>

                                @if ($item->status === 'pending')
                                    <option value="reviewing">Sedang Ditinjau</option>
                                    <option value="rejected">Tolak Permintaan</option>
                                    <option value="referred">Rujuk ke Profesional</option>
                                @elseif($item->status === 'reviewing')
                                    <option value="rejected">Tolak Permintaan</option>
                                    <option value="referred">Rujuk ke Profesional</option>
                                @endif

                            </select>

                            <button class="btn btn-primary w-100">
                                Simpan Status
                            </button>

                        </form>
                    @else
                        <p class="text-secondary small mb-0">
                            Status permintaan ini sudah tidak dapat
                            diubah melalui menu pengajuan.
                        </p>
                    @endif

                </div>

                {{-- Schedule Information --}}

                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-calendar-event me-2"></i>
                            Jadwal Konsultasi
                        </h5>

                        @if ($item->consultation)

                            <div class="mb-3">

                                <small class="text-secondary">Tanggal dan Waktu</small>

                                <p class="fw-semibold mb-0">
                                    {{ $item->consultation->scheduled_at->format('d M Y, H:i') }}
                                </p>

                            </div>

                            <div class="mb-3">

                                <small class="text-secondary">Metode</small>

                                <p class="fw-semibold mb-0">
                                    {{ ucfirst($item->consultation->method) }}
                                </p>

                            </div>

                            @if ($item->consultation->meeting_link)
                                <div class="mb-3">

                                    <small class="text-secondary">Link Meeting</small>

                                    <p class="mb-0">
                                        <a href="{{ $item->consultation->meeting_link }}" target="_blank"
                                            rel="noopener noreferrer">

                                            Buka Google Meet

                                        </a>
                                    </p>

                                </div>
                            @endif

                            @if ($item->consultation->location)
                                <div class="mb-3">

                                    <small class="text-secondary">Lokasi</small>

                                    <p class="mb-0">
                                        {{ $item->consultation->location }}
                                    </p>

                                </div>
                            @endif

                            @if ($item->consultation->session_status === 'scheduled')
                                <a href="{{ route('admin.schedules.edit', $item->consultation) }}"
                                    class="btn btn-outline-primary w-100">

                                    <i class="bi bi-pencil me-1"></i>
                                    Edit Jadwal

                                </a>
                            @else
                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($item->consultation->session_status) }}
                                </span>
                            @endif
                        @elseif(in_array($item->status, ['pending', 'reviewing']))
                            <p class="text-secondary small">
                                Permintaan ini belum memiliki jadwal konsultasi.
                            </p>

                            <a href="{{ route('admin.schedules.create', $item) }}" class="btn btn-primary w-100">

                                <i class="bi bi-calendar-plus me-1"></i>
                                Buat Jadwal

                            </a>
                        @else
                            <p class="text-secondary small mb-0">
                                Tidak ada jadwal konsultasi yang tersedia.
                            </p>

                        @endif

                    </div>

                </div>

            </div>


            @if (!$item->consultation)
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h6 class="fw-semibold">
                            Arsipkan Permintaan
                        </h6>

                        <p class="text-secondary small">
                            Permintaan akan disembunyikan dari daftar
                            aktif, tetapi datanya tidak langsung dihapus
                            dari database.
                        </p>

                        <form action="{{ route('admin.consultations.destroy', $item) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin mengarsipkan permintaan ini?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-outline-danger w-100">
                                <i class="bi bi-archive me-1"></i>
                                Arsipkan Permintaan
                            </button>

                        </form>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
