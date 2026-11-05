@extends('layouts.dashboard')

@section('title', 'Materi Pendukung')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">Materi Pendukung</h3>

            <p class="text-secondary mb-0">
                Kelola artikel dan informasi pendukung mahasiswa.
            </p>

        </div>

        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Tambah Materi

        </a>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <form method="GET" action="{{ route('admin.resources.index') }}" class="row g-2 mb-4">

                <div class="col-md-6">

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari judul atau kategori...">

                </div>

                <div class="col-md-4">

                    <select name="status" class="form-select">

                        <option value="">Semua Status</option>

                        <option value="published" @selected(request('status') == 'published')>
                            Published
                        </option>

                        <option value="draft" @selected(request('status') == 'draft')>
                            Draft
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
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $categoryLabels = [
                                'academic' => 'Akademik',
                                'personal' => 'Pengembangan Diri',
                                'social' => 'Sosial dan Pertemanan',
                                'organization' => 'Organisasi',
                                'professional_support' => 'Bantuan Profesional',
                                'general' => 'Umum',
                            ];
                        @endphp

                        @forelse($resources as $resource)
                            <tr>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $resource->title }}
                                    </div>

                                    <small class="text-secondary">
                                        {{ Str::limit($resource->description, 60) }}
                                    </small>

                                </td>

                                <td>
                                    {{ $categoryLabels[$resource->category] ?? $resource->category }}
                                </td>

                                <td>

                                    @if ($resource->is_published)
                                        <span class="badge text-bg-success">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            Draft
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $resource->created_at->format('d M Y') }}
                                </td>

                                <td>

                                    <div class="d-flex gap-1">

                                        <a href="{{ route('admin.resources.edit', $resource) }}"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                        <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus materi ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-5">

                                    <i class="bi bi-book fs-1 text-secondary"></i>

                                    <p class="text-secondary mt-2">
                                        Belum ada materi pendukung.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $resources->links() }}

        </div>

    </div>

@endsection
