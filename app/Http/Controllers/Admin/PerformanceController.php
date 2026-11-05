<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationFeedback;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function index(): View
    {
        // ==============================
        // STATISTIC CARDS
        // ==============================

        $stats = [
            'total_requests' => ConsultationRequest::count(),

            'completed' => Consultation::where(
                'session_status',
                'completed'
            )->count(),

            'upcoming' => Consultation::where(
                'session_status',
                'scheduled'
            )
                ->where('scheduled_at', '>=', now())
                ->count(),

            'average_rating' => round(
                (float) (ConsultationFeedback::avg('rating') ?? 0),
                1
            ),

            'total_feedback' => ConsultationFeedback::count(),
        ];


        // ==============================
        // MONTHLY CONSULTATION TREND
        // ==============================

        $monthlyRows = collect(range(5, 0))->map(function ($offset) {

            $month = now()
                ->startOfMonth()
                ->subMonths($offset);

            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return [
                'label' => $month->translatedFormat('M Y'),

                'requests' => ConsultationRequest::whereBetween(
                    'created_at',
                    [$start, $end]
                )->count(),

                'completed' => Consultation::where(
                    'session_status',
                    'completed'
                )
                    ->whereNotNull('completed_at')
                    ->whereBetween(
                        'completed_at',
                        [$start, $end]
                    )
                    ->count(),
            ];
        });

        $monthlyStats = [
            'labels' => $monthlyRows->pluck('label')->values(),

            'requests' => $monthlyRows->pluck('requests')->values(),

            'completed' => $monthlyRows->pluck('completed')->values(),
        ];


        // ==============================
        // TOPIC DISTRIBUTION
        // ==============================

        $topicLabels = [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Pertemanan',
            'organization' => 'Organisasi',
            'other' => 'Lainnya',
        ];

        $topicRows = ConsultationRequest::query()
            ->selectRaw('topic, COUNT(*) as total')
            ->groupBy('topic')
            ->get();

        $topicStats = [
            'labels' => $topicRows->map(
                fn($item) => $topicLabels[$item->topic] ?? $item->topic
            )->values(),

            'values' => $topicRows->pluck('total')->values(),

            'total' => $topicRows->sum('total'),
        ];


        // ==============================
        // STATUS DISTRIBUTION
        // ==============================

        $statusLabels = [
            'pending' => 'Menunggu',
            'reviewing' => 'Ditinjau',
            'scheduled' => 'Terjadwal',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'referred' => 'Dirujuk',
            'cancelled' => 'Dibatalkan',
        ];

        $statusRows = ConsultationRequest::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        $statusStats = [
            'labels' => $statusRows->map(
                fn($item) => $statusLabels[$item->status] ?? $item->status
            )->values(),

            'values' => $statusRows->pluck('total')->values(),

            'total' => $statusRows->sum('total'),
        ];


        return view('admin.performance.index', compact(
            'stats',
            'monthlyStats',
            'topicStats',
            'statusStats'
        ));
    }
}