<?php

use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PerformanceController;
use App\Http\Controllers\Admin\PipelineController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\ConsultationRequestController;
use App\Http\Controllers\Website\CounselorController as PublicCounselorController;
use App\Http\Controllers\Website\FeedbackController as PublicFeedbackController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\ResourceController as PublicResourceController;
use App\Http\Controllers\Website\TestimonialController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\PerformanceController;

// ========================================
// PUBLIC WEBSITE
// ========================================

Route::get('/', [
    HomeController::class,
    'index'
])->name('home');

Route::get('/konsultasi', [
    ConsultationRequestController::class,
    'create'
])->name('consultations.create');

Route::post('/konsultasi', [
    ConsultationRequestController::class,
    'store'
])
    ->middleware('throttle:5,1')
    ->name('consultations.store');

Route::get('/konsultasi/berhasil', [
    ConsultationRequestController::class,
    'success'
])->name('consultations.success');

// ========================================
// PUBLIC PAGES
// ========================================

Route::get('/tentang', [
    PageController::class,
    'about'
])->name('public.about');

Route::get('/layanan', [
    PageController::class,
    'services'
])->name('public.services');

Route::get('/faq', [
    PageController::class,
    'faq'
])->name('public.faq');

Route::get('/kebijakan-privasi', [
    PageController::class,
    'privacy'
])->name('public.privacy');

// ========================================
// PUBLIC CONSULTATION FEEDBACK
// ========================================

Route::get('/feedback/{consultation}', [
    PublicFeedbackController::class,
    'create'
])
    ->middleware('signed')
    ->name('public.feedback.create');

Route::post('/feedback/{consultation}', [
    PublicFeedbackController::class,
    'store'
])
    ->middleware('signed')
    ->name('public.feedback.store');


// ========================================
// COUNSELOR DIRECTORY
// ========================================

Route::get('/konselor', [
    PublicCounselorController::class,
    'index'
])->name('public.counselors.index');

Route::get('/konselor/{user}', [
    PublicCounselorController::class,
    'show'
])
    ->whereNumber('user')
    ->name('public.counselors.show');


// ========================================
// PUBLIC TESTIMONIAL
// ========================================

Route::get('/testimoni', [
    TestimonialController::class,
    'index'
])->name('public.testimonials.index');


// ========================================
// AUTHENTICATION
// ========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        LoginController::class,
        'create'
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'store'
    ])
        ->middleware('throttle:5,1')
        ->name('login.store');

});

// ========================================
// PUBLIC RESOURCE
// ========================================

Route::get('/materi', [
    PublicResourceController::class,
    'index'
])->name('public.resources.index');

Route::get('/materi/{resource}', [
    PublicResourceController::class,
    'show'
])->name('public.resources.show');

// ========================================
// COUNSELOR DASHBOARD
// ========================================

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('admin.dashboard');

    Route::post('/logout', [
        LoginController::class,
        'destroy'
    ])->name('logout');


    // Consultation Management

    Route::get('/dashboard/permintaan', [
        ConsultationController::class,
        'index'
    ])->name('admin.consultations.index');

    Route::get('/dashboard/permintaan/{consultationRequest}', [
        ConsultationController::class,
        'show'
    ])->name('admin.consultations.show');

    Route::patch('/dashboard/permintaan/{consultationRequest}/status', [
        ConsultationController::class,
        'updateStatus'
    ])->name('admin.consultations.update-status');

    Route::delete('/dashboard/permintaan/{consultationRequest}', [
        ConsultationController::class,
        'destroy'
    ])->name('admin.consultations.destroy');

    // ========================================
    // SCHEDULE MANAGEMENT
    // ========================================

    Route::get('/dashboard/jadwal', [
        ScheduleController::class,
        'index'
    ])->name('admin.schedules.index');

    // Create schedule from consultation request
    Route::get('/dashboard/permintaan/{consultationRequest}/jadwal/create', [
        ScheduleController::class,
        'create'
    ])->name('admin.schedules.create');

    Route::post('/dashboard/permintaan/{consultationRequest}/jadwal', [
        ScheduleController::class,
        'store'
    ])->name('admin.schedules.store');

    // Edit existing schedule
    Route::get('/dashboard/jadwal/{consultation}/edit', [
        ScheduleController::class,
        'edit'
    ])->name('admin.schedules.edit');

    Route::put('/dashboard/jadwal/{consultation}', [
        ScheduleController::class,
        'update'
    ])->name('admin.schedules.update');

    // Complete schedule
    Route::patch('/dashboard/jadwal/{consultation}/complete', [
        ScheduleController::class,
        'complete'
    ])->name('admin.schedules.complete');

    // Cancel schedule
    Route::patch('/dashboard/jadwal/{consultation}/cancel', [
        ScheduleController::class,
        'cancel'
    ])->name('admin.schedules.cancel');

    // ========================================
    // ADMIN RESOURCE MANAGEMENT
    // ========================================

    Route::get('/dashboard/materi', [
        AdminResourceController::class,
        'index'
    ])->name('admin.resources.index');

    Route::get('/dashboard/materi/create', [
        AdminResourceController::class,
        'create'
    ])->name('admin.resources.create');

    Route::post('/dashboard/materi', [
        AdminResourceController::class,
        'store'
    ])->name('admin.resources.store');

    Route::get('/dashboard/materi/{resource}/edit', [
        AdminResourceController::class,
        'edit'
    ])->name('admin.resources.edit');

    Route::put('/dashboard/materi/{resource}', [
        AdminResourceController::class,
        'update'
    ])->name('admin.resources.update');

    Route::delete('/dashboard/materi/{resource}', [
        AdminResourceController::class,
        'destroy'
    ])->name('admin.resources.destroy');

    Route::get('/dashboard/pipeline', [
        PipelineController::class,
        'index'
    ])->name('admin.pipeline.index');

    // ========================================
    // PERFORMANCE ANALYTICS
    // ========================================

    Route::get('/dashboard/statistik', [
        PerformanceController::class,
        'index'
    ])->name('admin.performance.index');

    // ========================================
    // FEEDBACK MANAGEMENT
    // ========================================

    Route::get('/dashboard/feedback', [
        AdminFeedbackController::class,
        'index'
    ])->name('admin.feedback.index');

    Route::patch('/dashboard/feedback/{feedback}/publication', [
        AdminFeedbackController::class,
        'updatePublication'
    ])->name('admin.feedback.update-publication');

    // ========================================
    // NOTIFICATION CENTER
    // ========================================

    Route::get('/dashboard/notifikasi', [
        NotificationController::class,
        'index'
    ])->name('admin.notifications.index');

    Route::post('/dashboard/notifikasi/{notification}/read', [
        NotificationController::class,
        'markAsRead'
    ])->name('admin.notifications.read');

    Route::post('/dashboard/notifikasi/read-all', [
        NotificationController::class,
        'markAllAsRead'
    ])->name('admin.notifications.read-all');
});