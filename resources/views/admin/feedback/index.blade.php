@extends('layouts.dashboard')

@section('title', 'Feedback & Rating')

@section('content')

    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Feedback & Rating
        </h3>

        <p class="text-secondary mb-0">
            Kelola penilaian dan testimoni mahasiswa.
        </p>

    </div>


    {{-- STATISTICS --}}

    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <small class="text-secondary">Total Feedback</small>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['total'] }}
                    </h2>

                </div>
            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <small class="text-secondary">Perlu Ditinjau</small>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['review'] }}
                    </h2>

                </div>
            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <small class="text-secondary">Dipublikasikan</small>

                    <h2 class="fw-bold mt-3 mb-0">
                        {{ $stats['published'] }}
                    </h2>

                </div>
            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <small class="text-secondary">Rata-rata Rating</small>

                    <h2 class="fw-bold mt-3 mb-0">

                        {{ number_format($stats['average_rating'], 1) }}

                        <i class="bi bi-star-fill text-warning fs-5"></i>

                    </h2>

                </div>
            </div>

        </div>

    </div>


    {{-- FILTER --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-3">

            <form method="GET" action="{{ route('admin.feedback.index') }}" class="row g-2">

                <div class="col-md-6">

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama atau nomor tiket...">

                </div>


                <div class="col-md-4">

                    <select name="status" class="form-select">

                        <option value="">Semua Feedback</option>

                        <option value="review" @selected(request('status') == 'review')>
                            Perlu Ditinjau
                        </option>

                        <option value="published" @selected(request('status') == 'published')>
                            Published
                        </option>

                        <option value="private" @selected(request('status') == 'private')>
                            Private
                        </option>

                    </select>

                </div>


                <div class="col-md-2">

                    <button class="btn btn-primary w-100">
                        Filter
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- FEEDBACK LIST --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="fw-bold mb-0">
                    Daftar Feedback
                </h5>

                <span class="text-secondary small">
                    {{ $feedbacks->total() }} data
                </span>

            </div>


            <div class="row g-3">

                @forelse($feedbacks as $feedback)

                    @php
                        $consultation = $feedback->consultation;
                        $requestData = $consultation?->consultationRequest;

                        $published = $feedback->is_published;
                        $hasConsent = $feedback->consent_to_publish;
                    @endphp

                    <div class="col-12">

                        <div class="border rounded-4 p-3 p-md-4">

                            <div
                                class="d-flex flex-column flex-md-row
                                    justify-content-between gap-3">

                                <div class="flex-grow-1">

                                    <div class="d-flex align-items-center gap-2 mb-2">

                                        <h6 class="fw-bold mb-0">
                                            {{ $feedback->display_name }}
                                        </h6>

                                        @if ($published)
                                            <span class="badge text-bg-success">
                                                Published
                                            </span>
                                        @elseif($hasConsent)
                                            <span class="badge text-bg-warning">
                                                Perlu Ditinjau
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary">
                                                Private
                                            </span>
                                        @endif

                                    </div>


                                    <div class="text-warning mb-2">

                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $feedback->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor

                                        <span class="text-dark small fw-semibold ms-1">

                                            {{ $feedback->rating }}/5

                                        </span>

                                    </div>


                                    @if ($feedback->comment)
                                        <p class="text-secondary mb-3" style="white-space: pre-line;">

                                            "{{ $feedback->comment }}"

                                        </p>
                                    @else
                                        <p class="text-secondary small fst-italic mb-3">
                                            Tidak ada komentar tertulis.
                                        </p>
                                    @endif


                                    <div class="small text-secondary">

                                        <div>
                                            Referensi:

                                            {{ $requestData?->ticket_number ?? '-' }}
                                        </div>

                                        <div class="mt-1">

                                            {{ $feedback->created_at->format('d M Y, H:i') }}

                                        </div>

                                    </div>

                                    <div class="mt-2">

                                        @if ($hasConsent)
                                            <span class="small text-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Mahasiswa mengizinkan publikasi komentar.

                                            </span>
                                        @else
                                            <span class="small text-secondary">

                                                <i class="bi bi-lock me-1"></i>

                                                Mahasiswa tidak memberikan izin publikasi.

                                            </span>
                                        @endif

                                    </div>

                                </div>


                                {{-- MODERATION ACTIONS --}}

                                <div class="d-flex flex-column gap-2" style="min-width: 155px;">

                                    @if ($published)
                                        <form action="{{ route('admin.feedback.update-publication', $feedback) }}"
                                            method="POST"
                                            onsubmit="return confirm('Sembunyikan testimoni ini dari website publik?')">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="is_published" value="0">

                                            <button class="btn btn-outline-danger w-100">

                                                <i class="bi bi-eye-slash me-1"></i>
                                                Sembunyikan

                                            </button>

                                        </form>
                                    @elseif($hasConsent && filled($feedback->comment))
                                        <form action="{{ route('admin.feedback.update-publication', $feedback) }}"
                                            method="POST"
                                            onsubmit="return confirm('Publikasikan komentar ini sebagai testimoni?')">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="is_published" value="1">

                                            <button class="btn btn-success w-100">

                                                <i class="bi bi-check-circle me-1"></i>
                                                Publikasikan

                                            </button>

                                        </form>
                                    @else
                                        <button class="btn btn-light w-100" disabled>

                                            <i class="bi bi-lock me-1"></i>
                                            Tidak Diizinkan

                                        </button>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center py-5">

                            <i class="bi bi-star fs-1 text-secondary"></i>

                            <h5 class="fw-bold mt-3">
                                Belum Ada Feedback
                            </h5>

                            <p class="text-secondary small">
                                Feedback mahasiswa akan tampil di sini
                                setelah mereka menyelesaikan konsultasi.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>


            <div class="mt-4">

                {{ $feedbacks->links() }}

            </div>

        </div>

    </div>

@endsection
