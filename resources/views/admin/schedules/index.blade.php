@extends('layouts.dashboard')

@section('title', 'Jadwal Konsultasi')

@section('content')

    <div class="mb-4">

        <h3 class="fw-bold">Jadwal Konsultasi</h3>

        <p class="text-secondary">
            Kelola seluruh jadwal konsultasi mahasiswa.
        </p>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <form method="GET" action="{{ route('admin.schedules.index') }}" class="row g-2 mb-4">

                <div class="col-md-6">

                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Cari tiket atau nama mahasiswa...">

                </div>

                <div class="col-md-4">

                    <select name="status" class="form-select">

                        <option value="">Semua Status</option>

                        <option value="scheduled" @selected(request('status') == 'scheduled')>
                            Terjadwal
                        </option>

                        <option value="completed" @selected(request('status') == 'completed')>
                            Selesai
                        </option>

                        <option value="cancelled" @selected(request('status') == 'cancelled')>
                            Dibatalkan
                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">
                        Filter
                    </button>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Nomor Tiket</th>
                            <th>Mahasiswa</th>
                            <th>Tanggal Konsultasi</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($schedules as $schedule)
                            @php
                                $labels = [
                                    'scheduled' => 'Terjadwal',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];

                                $colors = [
                                    'scheduled' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp

                            <tr>

                                <td class="fw-semibold">
                                    {{ $schedule->consultationRequest->ticket_number }}
                                </td>

                                <td>
                                    {{ $schedule->consultationRequest->alias }}
                                </td>

                                <td>
                                    {{ $schedule->scheduled_at->format('d M Y, H:i') }}
                                </td>

                                <td>
                                    {{ ucfirst($schedule->method) }}
                                </td>

                                <td>

                                    <span class="badge text-bg-{{ $colors[$schedule->session_status] ?? 'secondary' }}">
                                        {{ $labels[$schedule->session_status] ?? $schedule->session_status }}
                                    </span>

                                </td>

                                <td>

                                    <div class="d-flex gap-1">

                                        @if ($schedule->session_status === 'scheduled')
                                            <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit jadwal">

                                                <i class="bi bi-pencil"></i>

                                            </a>

                                            <form action="{{ route('admin.schedules.complete', $schedule) }}" method="POST"
                                                onsubmit="return confirm('Tandai konsultasi ini sebagai selesai?')">

                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-sm btn-outline-success" title="Selesaikan">

                                                    <i class="bi bi-check-lg"></i>

                                                </button>

                                            </form>

                                            <form action="{{ route('admin.schedules.cancel', $schedule) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin membatalkan jadwal ini?')">

                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-sm btn-outline-danger" title="Batalkan">

                                                    <i class="bi bi-x-lg"></i>

                                                </button>

                                            </form>
                                        @else
                                            <a href="{{ route('admin.consultations.show', $schedule->consultationRequest) }}"
                                                class="btn btn-sm btn-outline-secondary">

                                                Detail

                                            </a>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <i class="bi bi-calendar-x fs-1 text-secondary"></i>

                                    <p class="text-secondary mt-2 mb-0">
                                        Belum ada jadwal konsultasi.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $schedules->links() }}

        </div>

    </div>

@endsection
