@extends('layouts.dashboard')

@section('title', 'Buat Jadwal')

@section('content')

    <div class="mb-4">

        <a href="{{ route('admin.consultations.show', $consultationRequest) }}" class="text-decoration-none text-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Detail Permintaan

        </a>

        <h3 class="fw-bold mt-3">Buat Jadwal Konsultasi</h3>

        <p class="text-secondary">
            Atur waktu konsultasi bersama mahasiswa.
        </p>

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="bg-light rounded-3 p-3 mb-4">

                        <small class="text-secondary">
                            NOMOR TIKET
                        </small>

                        <h6 class="fw-bold mb-0">
                            {{ $consultationRequest->ticket_number }}
                        </h6>

                        <small class="text-secondary">
                            {{ $consultationRequest->alias }}
                        </small>

                    </div>

                    <form action="{{ route('admin.schedules.store', $consultationRequest) }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal dan Waktu Konsultasi *
                            </label>

                            <input type="datetime-local" name="scheduled_at"
                                class="form-control @error('scheduled_at') is-invalid @enderror"
                                value="{{ old('scheduled_at') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>

                            @error('scheduled_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Metode Konsultasi *
                            </label>

                            <select name="method" class="form-select @error('method') is-invalid @enderror" id="method"
                                required>

                                <option value="online" @selected(old('method', $consultationRequest->preferred_method) == 'online')>
                                    Online
                                </option>

                                <option value="offline" @selected(old('method', $consultationRequest->preferred_method) == 'offline')>
                                    Tatap Muka
                                </option>

                            </select>

                            @error('method')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3" id="meeting-field">

                            <label class="form-label">
                                Link Google Meet
                            </label>

                            <input type="url" name="meeting_link"
                                class="form-control @error('meeting_link') is-invalid @enderror"
                                value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/...">

                            <small class="text-secondary">
                                Opsional. Bisa juga menggunakan WhatsApp Call.
                            </small>

                            @error('meeting_link')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-4" id="location-field">

                            <label class="form-label">
                                Lokasi Konsultasi *
                            </label>

                            <input type="text" name="location"
                                class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}"
                                placeholder="Contoh: Ruang Konseling Kampus">

                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calendar-check me-1"></i>
                            Simpan Jadwal
                        </button>

                        <a href="{{ route('admin.consultations.show', $consultationRequest) }}" class="btn btn-light">
                            Batal
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const method = document.getElementById('method');
            const meetingField = document.getElementById('meeting-field');
            const locationField = document.getElementById('location-field');

            function toggleMethodFields() {
                meetingField.style.display =
                    method.value === 'online' ? 'block' : 'none';

                locationField.style.display =
                    method.value === 'offline' ? 'block' : 'none';
            }

            method.addEventListener('change', toggleMethodFields);
            toggleMethodFields();
        });
    </script>

@endsection
