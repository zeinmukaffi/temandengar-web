<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselorController extends Controller
{
    public function index(Request $request): View
    {
        $counselors = User::query()
            ->where('is_public_profile', true)
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('headline', 'like', "%{$search}%")
                        ->orWhere('education', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%");

                });

            })
            ->withCount([
                'consultations as completed_consultations_count' =>
                    fn($query) => $query->where(
                        'session_status',
                        'completed'
                    )
            ])
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $categoryLabels = [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Sosial dan Pertemanan',
            'organization' => 'Organisasi',
        ];

        return view('public.counselors.index', compact('counselors', 'categoryLabels'));
    }

    public function show(User $user): View
    {
        abort_unless($user->is_public_profile, 404);

        $user->loadCount([
            'consultations as completed_consultations_count' =>
                fn($query) => $query->where(
                    'session_status',
                    'completed'
                )
        ]);

        return view('public.counselors.show', [
            'counselor' => $user,
        ]);
    }
}