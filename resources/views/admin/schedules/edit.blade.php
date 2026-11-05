@extends('layouts.dashboard')

@section('title', 'Edit Jadwal')

@section('content')

    <div class="mb-4">

        <a href="{{ route('admin.schedules.index') }}" class="text-decoration-none text-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Jadwal

        </a>

        <h3 class="fw-bold mt-3">Edit Jadwal Konsultasi</h3>

        <p class="text-secondary">
            {{ $consultation->consultationRequest->ticket_number }}
            — {{ $consultation->consultationRequest->alias }}
        </p>

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <form action="{{ route('admin.schedules.update', $consultation) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal dan Waktu *
                            </label>

                            <input type="datetime-local" name="scheduled_at"
                                class="form-control @error('scheduled_at') is-invalid @enderror"
                                value="{{ old('scheduled_at', $consultation->scheduled_at->format('Y-m-d\TH:i')) }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}" required>

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

                            <select name="method" id="method" class="form-select" required>

                                <option value="online" @selected(old('method', $consultation->method) == 'online')>
                                    Online
                                </option>

                                <option value="offline" @selected(old('method', $consultation->method) == 'offline')>
                                    Tatap Muka
                                </option>

                            </select>

                        </div>

                        <div class="mb-3" id="meeting-field">

                            <label class="form-label">
                                Link Google Meet
                            </label>

                            <input type="url" name="meeting_link"
                                value="{{ old('meeting_link', $consultation->meeting_link) }}" class="form-control">

                        </div>

                        <div class="mb-4" id="location-field">

                            <label class="form-label">
                                Lokasi Konsultasi
                            </label>

                            <input type="text" name="location" value="{{ old('location', $consultation->location) }}"
                                class="form-control">

                        </div>

                        <button class="btn btn-primary">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-light">
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
