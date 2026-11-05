<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountSettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            'current_password.required' =>
                'Password lama wajib diisi.',

            'current_password.current_password' =>
                'Password lama tidak sesuai.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.confirmed' =>
                'Konfirmasi password tidak cocok.',

            'password.different' =>
                'Password baru harus berbeda dari password lama.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Refresh session ID after changing credentials
        $request->session()->regenerate();

        return back()->with(
            'success',
            'Password berhasil diperbarui.'
        );
    }
}
