@extends('layouts.dashboard')

@section('title', 'Permintaan Konsultasi')

@section('content')

    <div class="mb-4">

        <h3 class="fw-bold">Permintaan Konsultasi</h3>

        <p class="text-secondary mb-0">
            Kelola seluruh pengajuan konsultasi mahasiswa.
        </p>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <form method="GET" action="{{ route('admin.consultations.index') }}" class="row g-2 mb-4">

                <div class="col-md-6">

                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Cari nomor tiket atau nama...">

                </div>

                <div class="col-md-4">

                    <select name="status" class="form-select">

                        <option value="">Semua Status</option>

                        @foreach ([
            'pending' => 'Menunggu',
            'reviewing' => 'Ditinjau',
            'scheduled' => 'Terjadwal',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'referred' => 'Dirujuk',
            'cancelled' => 'Dibatalkan',
        ] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>
                        Filter
                    </button>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Nomor Tiket</th>
                            <th>Nama</th>
                            <th>Topik</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($requests as $item)
                            @php
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'reviewing' => 'Ditinjau',
                                    'scheduled' => 'Terjadwal',
                                    'completed' => 'Selesai',
                                    'rejected' => 'Ditolak',
                                    'referred' => 'Dirujuk',
                                    'cancelled' => 'Dibatalkan',
                                ];

                                $statusColors = [
                                    'pending' => 'warning',
                                    'reviewing' => 'info',
                                    'scheduled' => 'primary',
                                    'completed' => 'success',
                                    'rejected' => 'danger',
                                    'referred' => 'secondary',
                                    'cancelled' => 'dark',
                                ];
                            @endphp

                            <tr>

                                <td class="fw-semibold">
                                    {{ $item->ticket_number }}
                                </td>

                                <td>
                                    {{ $item->alias }}
                                </td>

                                <td>
                                    {{ ucfirst($item->topic) }}
                                </td>

                                <td>
                                    {{ $item->created_at->format('d M Y, H:i') }}
                                </td>

                                <td>

                                    <span class="badge text-bg-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$item->status] ?? $item->status }}
                                    </span>

                                </td>

                                <td>

                                    <a href="{{ route('admin.consultations.show', $item) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <i class="bi bi-inbox fs-1 text-secondary"></i>

                                    <p class="text-secondary mt-2 mb-0">
                                        Belum ada permintaan konsultasi.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $requests->links() }}

            </div>

        </div>

    </div>

@endsection
