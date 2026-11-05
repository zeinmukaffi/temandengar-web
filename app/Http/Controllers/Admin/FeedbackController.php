<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationFeedback;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $query = ConsultationFeedback::with([
            'consultation.consultationRequest',
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('display_name', 'like', "%{$search}%")
                    ->orWhereHas(
                        'consultation.consultationRequest',
                        function ($subQuery) use ($search) {

                            $subQuery->where(
                                'ticket_number',
                                'like',
                                "%{$search}%"
                            )->orWhere(
                                    'alias',
                                    'like',
                                    "%{$search}%"
                                );

                        }
                    );

            });

        }

        if ($request->status === 'published') {

            $query->where('is_published', true);

        } elseif ($request->status === 'review') {

            $query->where('is_published', false)
                ->where('consent_to_publish', true);

        } elseif ($request->status === 'private') {

            $query->where('consent_to_publish', false);

        }

        $feedbacks = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        $stats = [
            'total' => ConsultationFeedback::count(),

            'published' => ConsultationFeedback::where(
                'is_published',
                true
            )->count(),

            'review' => ConsultationFeedback::where(
                'consent_to_publish',
                true
            )->where(
                    'is_published',
                    false
                )->count(),

            'average_rating' => round(
                (float) (
                    ConsultationFeedback::avg('rating') ?? 0
                ),
                1
            ),
        ];

        return view('admin.feedback.index', compact(
            'feedbacks',
            'stats'
        ));
    }


    public function updatePublication(
        Request $request,
        ConsultationFeedback $feedback
    ): RedirectResponse {

        $validated = $request->validate([
            'is_published' => [
                'required',
                'boolean',
            ],
        ]);

        $publish = (bool) $validated['is_published'];

        // Publikasi hanya boleh dilakukan jika mahasiswa memberi izin
        if (
            $publish &&
            (
                !$feedback->consent_to_publish ||
                blank($feedback->comment)
            )
        ) {

            return back()->withErrors([
                'publication' => 'Feedback tidak dapat dipublikasikan tanpa komentar dan persetujuan mahasiswa.',
            ]);

        }

        DB::transaction(function () use ($feedback, $publish) {

            $feedback->update([

                'is_published' => $publish,

                'published_at' => $publish
                    ? now()
                    : null,

            ]);

            ActivityLog::create([

                'user_id' => auth()->id(),

                'request_id' => $feedback->consultation->request_id,

                'action' => $publish
                    ? 'feedback_published'
                    : 'feedback_unpublished',

                'description' => $publish
                    ? 'Konselor mempublikasikan sebuah feedback.'
                    : 'Konselor menyembunyikan sebuah feedback.',

            ]);

        });

        return back()->with(
            'success',
            $publish
            ? 'Testimoni berhasil dipublikasikan.'
            : 'Testimoni berhasil disembunyikan.'
        );
    }
}