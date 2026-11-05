<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => ConsultationRequest::count(),

            'pending' => ConsultationRequest::where(
                'status',
                'pending'
            )->count(),

            'reviewing' => ConsultationRequest::where(
                'status',
                'reviewing'
            )->count(),

            'scheduled' => ConsultationRequest::where(
                'status',
                'scheduled'
            )->count(),

            'completed' => ConsultationRequest::where(
                'status',
                'completed'
            )->count(),
        ];

        $upcomingSchedules = Consultation::with([
            'consultationRequest'
        ])
            ->where('session_status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $recentRequests = ConsultationRequest::latest()
            ->limit(5)
            ->get();

        $monthlyCompleted = Consultation::where(
            'session_status',
            'completed'
        )
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'upcomingSchedules',
            'recentRequests',
            'monthlyCompleted'
        ));
    }
}