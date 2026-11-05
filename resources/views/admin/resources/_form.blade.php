@php
    $isEdit = isset($resource);

    $action = $isEdit ? route('admin.resources.update', $resource) : route('admin.resources.store');
@endphp

<form action="{{ $action }}" method="POST">

    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">

        <label class="form-label">
            Judul Materi *
        </label>

        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $resource->title ?? '') }}" placeholder="Contoh: Tips Mengelola Waktu Kuliah" required>

        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="mb-3">

        <label class="form-label">
            Kategori *
        </label>

        <select name="category" class="form-select @error('category') is-invalid @enderror" required>

            <option value="">Pilih kategori</option>

            @foreach ($categories as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $resource->category ?? '') == $value)>

                    {{ $label }}

                </option>
            @endforeach

        </select>

        @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="mb-3">

        <label class="form-label">
            Deskripsi
        </label>

        <textarea name="description" rows="4" maxlength="3000"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Tuliskan penjelasan singkat mengenai materi...">{{ old('description', $resource->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="mb-4">

        <label class="form-label">
            URL Materi *
        </label>

        <input type="url" name="resource_url" class="form-control @error('resource_url') is-invalid @enderror"
            value="{{ old('resource_url', $resource->resource_url ?? '') }}" placeholder="https://example.com/artikel"
            required>

        @error('resource_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <small class="text-secondary">
            Masukkan link artikel, dokumen, atau sumber informasi.
        </small>

    </div>

    <div class="form-check mb-4">

        <input type="checkbox" name="is_published" value="1" id="is_published" class="form-check-input"
            @checked(old('is_published', $resource->is_published ?? true))>

        <label class="form-check-label" for="is_published">
            Publikasikan materi sekarang
        </label>

        <div class="form-text">
            Jika tidak dicentang, materi akan disimpan sebagai draft.
        </div>

    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>

        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Materi' }}
    </button>

    <a href="{{ route('admin.resources.index') }}" class="btn btn-light">

        Batal

    </a>

</form>
