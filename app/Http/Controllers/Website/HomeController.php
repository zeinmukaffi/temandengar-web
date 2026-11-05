<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resource;
use App\Models\Consultation;
use App\Models\ConsultationFeedback;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'completed' => Consultation::where(
                'session_status',
                'completed'
            )->count(),

            'counselors' => User::where(
                'is_public_profile',
                true
            )->count(),

            'resources' => Resource::where(
                'is_published',
                true
            )->count(),
        ];

        $counselors = User::query()
            ->where('is_public_profile', true)
            ->withCount([
                'consultations as completed_consultations_count' =>
                    fn($query) => $query->where(
                        'session_status',
                        'completed'
                    )
            ])
            ->latest()
            ->limit(3)
            ->get();

        $resources = Resource::where('is_published', true)
            ->latest()
            ->limit(3)
            ->get();

        $testimonials = ConsultationFeedback::query()
            ->where('is_published', true)
            ->where('consent_to_publish', true)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $services = $this->services();

        return view('public.home', compact(
            'stats',
            'counselors',
            'resources',
            'testimonials',
            'services'
        ));
    }

    private function services(): array
    {
        return [
            [
                'category' => 'academic',
                'icon' => 'bi-mortarboard',
                'color' => 'lavender',
                'title' => 'Akademik',
                'description' => 'Berbagi cerita seputar tugas, perkuliahan, dan manajemen waktu.',
            ],
            [
                'category' => 'personal',
                'icon' => 'bi-person-heart',
                'color' => 'peach',
                'title' => 'Pengembangan Diri',
                'description' => 'Ruang untuk berdiskusi mengenai motivasi dan kepercayaan diri.',
            ],
            [
                'category' => 'social',
                'icon' => 'bi-people',
                'color' => 'mint',
                'title' => 'Pertemanan',
                'description' => 'Menceritakan pengalaman dan dinamika hubungan sosial.',
            ],
            [
                'category' => 'organization',
                'icon' => 'bi-diagram-3',
                'color' => 'lavender',
                'title' => 'Organisasi',
                'description' => 'Berbagi pengalaman dalam organisasi dan aktivitas kampus.',
            ],
        ];
    }
}