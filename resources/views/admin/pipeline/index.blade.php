@extends('layouts.dashboard')

@section('title', 'Pipeline Kasus')

@section('content')

    @php
        $statusColors = [
            'pending' => 'warning',
            'reviewing' => 'info',
            'scheduled' => 'primary',
            'completed' => 'success',
        ];

        $topicLabels = [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Pertemanan',
            'organization' => 'Organisasi',
            'other' => 'Lainnya',
        ];
    @endphp

    <div
        class="d-flex flex-column flex-md-row
            justify-content-between align-items-md-center
            gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">Pipeline Kasus</h3>

            <p class="text-secondary mb-0">
                Pantau perkembangan permintaan konsultasi.
            </p>
        </div>

        <a href="{{ route('admin.consultations.index') }}" class="btn btn-outline-primary">

            <i class="bi bi-list-ul me-1"></i>
            Daftar Permintaan

        </a>

    </div>


    {{-- FILTER --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-3">

            <form method="GET" action="{{ route('admin.pipeline.index') }}" class="row g-2">

                <div class="col-md-6">

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama atau nomor tiket...">

                </div>

                <div class="col-md-4">

                    <select name="topic" class="form-select">

                        <option value="">Semua Topik</option>

                        @foreach ($topicLabels as $value => $label)
                            <option value="{{ $value }}" @selected(request('topic') === $value)>

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

        </div>

    </div>


    {{-- KANBAN BOARD --}}

    <div class="pipeline-board">

        @foreach ($columns as $status => $column)
            <div class="pipeline-column">

                <div class="pipeline-column-header">

                    <div class="d-flex align-items-center gap-2">

                        <span class="pipeline-indicator
                        pipeline-indicator-{{ $status }}">
                        </span>

                        <h6 class="fw-bold mb-0">
                            {{ $column['label'] }}
                        </h6>

                    </div>

                    <span class="badge text-bg-{{ $statusColors[$status] }}">
                        {{ $column['count'] }}
                    </span>

                </div>


                <div class="pipeline-column-body">

                    @forelse($column['items'] as $item)
                        <a href="{{ route('admin.consultations.show', $item) }}" class="pipeline-card text-decoration-none">

                            <div class="d-flex justify-content-between mb-3">

                                <span class="small fw-semibold text-primary">
                                    {{ $item->ticket_number }}
                                </span>

                                <i class="bi bi-arrow-up-right text-secondary"></i>

                            </div>

                            <h6 class="fw-bold text-dark mb-1">
                                {{ $item->alias }}
                            </h6>

                            <p class="text-secondary small mb-3">
                                {{ $topicLabels[$item->topic] ?? $item->topic }}
                            </p>

                            <div
                                class="d-flex justify-content-between
                                    align-items-center gap-2">

                                <small class="text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </small>

                                <span class="badge text-bg-{{ $statusColors[$status] }}">
                                    {{ $column['label'] }}
                                </span>

                            </div>

                        </a>

                    @empty

                        <div class="pipeline-empty">

                            <i class="bi bi-inbox fs-3"></i>

                            <p class="small mb-0 mt-2">
                                Belum ada permintaan.
                            </p>

                        </div>
                    @endforelse


                    @if ($column['count'] > 20)
                        <a href="{{ route('admin.consultations.index', array_merge(['status' => $status], request()->only('search'))) }}"
                            class="btn btn-light btn-sm w-100">

                            Lihat semua {{ $column['count'] }} data

                        </a>
                    @endif

                </div>

            </div>
        @endforeach

    </div>


    {{-- OTHER STATUS --}}

    <div class="mt-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div
                class="card-body p-3 d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center gap-2">

                <div>

                    <h6 class="fw-bold mb-1">
                        Status Lainnya
                    </h6>

                    <small class="text-secondary">
                        Permintaan ditolak, dirujuk, atau dibatalkan.
                    </small>

                </div>

                <span class="badge text-bg-secondary">
                    {{ $otherStatusesCount }} permintaan
                </span>

                <a href="{{ route('admin.consultations.index') }}" class="btn btn-outline-secondary btn-sm">

                    Lihat Daftar Permintaan
                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>

        </div>

    </div>

@endsection
