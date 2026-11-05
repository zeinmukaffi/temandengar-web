@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')

    @php
        $currentSpecialties = old('specialties', $user->specialties ?? []);
    @endphp

    <div class="mb-4">

        <h3 class="fw-bold mb-1">Profil Saya</h3>

        <p class="text-secondary mb-0">
            Kelola informasi dan profil publik konselor.
        </p>

    </div>


    <div class="row g-4">

        {{-- PROFILE PREVIEW --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 text-center">

                    <div class="profile-avatar mx-auto mb-3" style="width:120px;height:120px;">

                        @if ($user->photo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($user->photo_path) }}"
                                alt="Foto {{ $user->name }}">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif

                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $user->name }}
                    </h5>

                    <p class="text-secondary small">
                        {{ $user->headline ?: 'Konselor Sebaya' }}
                    </p>

                    @if ($user->is_public_profile)
                        <span class="badge text-bg-success">
                            <i class="bi bi-globe me-1"></i>
                            Profil Publik Aktif
                        </span>
                    @else
                        <span class="badge text-bg-secondary">
                            <i class="bi bi-eye-slash me-1"></i>
                            Profil Privat
                        </span>
                    @endif

                    @if ($user->is_public_profile)
                        <div class="mt-3">

                            <a href="{{ route('public.counselors.show', $user) }}" target="_blank" rel="noopener noreferrer"
                                class="btn btn-outline-primary btn-sm">

                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Lihat Profil Publik

                            </a>

                        </div>
                    @endif

                </div>

            </div>


            <div class="card border-0 shadow-sm rounded-4 mt-3">

                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">
                        Foto Profil
                    </h6>

                    <form action="{{ route('admin.profile.remove-photo') }}" method="POST"
                        onsubmit="return confirm('Hapus foto profil?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" @disabled(!$user->photo_path)>

                            <i class="bi bi-trash me-1"></i>
                            Hapus Foto

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- PROFILE FORM --}}

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')


                        <h5 class="fw-bold mb-1">
                            Informasi Dasar
                        </h5>

                        <p class="text-secondary small mb-4">
                            Informasi yang akan digunakan pada profil konselor.
                        </p>


                        {{-- NAME --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Lengkap *
                            </label>

                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EMAIL --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email" value="{{ $user->email }}" class="form-control" readonly>

                            <small class="text-secondary">
                                Email dikelola melalui pengaturan akun.
                            </small>

                        </div>


                        {{-- HEADLINE --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Headline / Jabatan
                            </label>

                            <input type="text" name="headline" value="{{ old('headline', $user->headline) }}"
                                class="form-control @error('headline') is-invalid @enderror"
                                placeholder="Contoh: Konselor Sebaya">

                            @error('headline')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EDUCATION --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Pendidikan
                            </label>

                            <input type="text" name="education" value="{{ old('education', $user->education) }}"
                                class="form-control @error('education') is-invalid @enderror"
                                placeholder="Contoh: Mahasiswa Psikologi">

                            @error('education')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- BIO --}}

                        <div class="mb-4">

                            <label class="form-label">
                                Bio
                            </label>

                            <textarea name="bio" rows="5" maxlength="3000" class="form-control @error('bio') is-invalid @enderror"
                                placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>

                            @error('bio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <hr class="my-4">


                        {{-- PHOTO --}}

                        <h5 class="fw-bold mb-3">
                            Foto Profil
                        </h5>

                        <div class="mb-4">

                            <label class="form-label">
                                Upload Foto Baru
                            </label>

                            <input type="file" name="photo" id="photo" accept=".jpg,.jpeg,.png,.webp"
                                class="form-control @error('photo') is-invalid @enderror">

                            <small class="text-secondary">
                                JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                            </small>

                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div id="photo-preview-wrapper" class="mt-3 d-none">

                                <img id="photo-preview" src="" alt="Preview foto" class="rounded-4"
                                    style="width:110px;height:110px;object-fit:cover;">

                            </div>

                        </div>


                        <hr class="my-4">


                        {{-- SPECIALTIES --}}

                        <h5 class="fw-bold mb-1">
                            Spesialisasi
                        </h5>

                        <p class="text-secondary small mb-3">
                            Pilih bidang yang relevan dengan kompetensi
                            dan cakupan dukungan yang dapat diberikan.
                        </p>


                        <div class="row g-3 mb-4">

                            @foreach ($specialties as $value => $label)
                                <div class="col-md-6">

                                    <div class="form-check border rounded-3 p-3 ps-5">

                                        <input type="checkbox" name="specialties[]" value="{{ $value }}"
                                            id="specialty-{{ $value }}" class="form-check-input"
                                            @checked(in_array($value, $currentSpecialties))>

                                        <label for="specialty-{{ $value }}" class="form-check-label">

                                            {{ $label }}

                                        </label>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                        @error('specialties')
                            <div class="text-danger small mb-2">
                                {{ $message }}
                            </div>
                        @enderror


                        <hr class="my-4">


                        {{-- PUBLIC PROFILE --}}

                        <h5 class="fw-bold mb-2">
                            Visibilitas Profil
                        </h5>

                        <p class="text-secondary small">
                            Atur apakah profilmu dapat dilihat oleh pengunjung
                            website TemanDengar.
                        </p>

                        <div class="bg-soft rounded-4 p-3 mb-4">

                            <input type="hidden" name="is_public_profile" value="0">

                            <div class="form-check form-switch">

                                <input type="checkbox" name="is_public_profile" value="1" id="is_public_profile"
                                    class="form-check-input" @checked(old('is_public_profile', $user->is_public_profile))>

                                <label for="is_public_profile" class="form-check-label fw-semibold">

                                    Tampilkan Profil Saya di Website

                                </label>

                            </div>

                            <small class="text-secondary">
                                Jika dinonaktifkan, profil tidak dapat diakses
                                melalui direktori konselor publik.
                            </small>

                        </div>


                        {{-- ACTIONS --}}

                        <div class="d-flex flex-column flex-sm-row gap-2">

                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-save me-1"></i>

                                Simpan Perubahan

                            </button>

                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">

                                Batal

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const photoInput = document.getElementById('photo');
            const preview = document.getElementById('photo-preview');
            const wrapper = document.getElementById('photo-preview-wrapper');

            photoInput.addEventListener('change', function() {

                const file = this.files[0];

                if (!file) {
                    wrapper.classList.add('d-none');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    this.value = '';
                    wrapper.classList.add('d-none');
                    alert('File harus berupa gambar.');
                    return;
                }

                preview.src = URL.createObjectURL(file);
                wrapper.classList.remove('d-none');

            });

        });
    </script>

@endsection
