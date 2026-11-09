<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function specialties(): array
    {
        return [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Pertemanan dan Sosial',
            'organization' => 'Organisasi',
        ];
    }

    public function edit(Request $request): View
    {
        $user = $request->user();

        $specialties = $this->specialties();

        return view('admin.profile.edit', compact(
            'user',
            'specialties'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:160'],
            'education' => ['nullable', 'string', 'max:180'],
            'bio' => ['nullable', 'string', 'max:3000'],

            'specialties' => ['nullable', 'array'],
            'specialties.*' => [
                'string',
                Rule::in(array_keys($this->specialties())),
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_public_profile' => ['sometimes', 'boolean'],

            'is_accepting_consultations' => [
                'sometimes',
                'boolean',
            ],
        ]);

        $validated['specialties'] =
            $validated['specialties'] ?? [];

        $validated['is_public_profile'] =
            $request->boolean('is_public_profile');

        $validated['is_accepting_consultations'] =
            $request->boolean('is_accepting_consultations');

        unset($validated['photo']);

        // Upload foto profil baru
        if ($request->hasFile('photo')) {

            $oldPhoto = $user->photo_path;

            $newPhoto = $request->file('photo')
                ->store('counselor-profiles', 'public');

            $validated['photo_path'] = $newPhoto;

            // Hapus foto lama setelah foto baru berhasil disimpan
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

        }

        $user->update($validated);

        return back()->with(
            'success',
            'Profil konselor berhasil diperbarui.'
        );
    }

    public function removePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);

            $user->update([
                'photo_path' => null,
            ]);
        }

        return back()->with(
            'success',
            'Foto profil berhasil dihapus.'
        );
    }
}
