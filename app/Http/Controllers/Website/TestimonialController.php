<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ConsultationFeedback;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = ConsultationFeedback::query()
            ->where('is_published', true)
            ->where('consent_to_publish', true)
            ->whereNotNull('published_at')
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->latest('published_at')
            ->paginate(9);

        return view(
            'public.testimonials.index',
            compact('testimonials')
        );
    }
}