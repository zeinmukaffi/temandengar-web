<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationFeedback;

use Illuminate\Foundation\Testing\RefreshDatabase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_performance_page(): void
    {
        $this->get(route('admin.performance.index'))
            ->assertRedirect(route('login'));
    }

    public function test_counselor_can_access_performance_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.performance.index'))
            ->assertOk()
            ->assertSee('Statistik Performa');
    }

    public function test_performance_statistics_use_database_data(): void
    {
        $user = User::factory()->create();

        $request = ConsultationRequest::create([
            'ticket_number' => 'TD-2026-STAT01',
            'alias' => 'Mahasiswa Dummy',
            'email' => 'dummy@example.test',
            'topic' => 'academic',
            'description' => 'Pengujian statistik konsultasi.',
            'preferred_method' => 'online',
            'status' => 'completed',
            'consent_at' => now(),
        ]);

        $consultation = Consultation::create([
            'request_id' => $request->id,
            'counselor_id' => $user->id,
            'scheduled_at' => now()->subDays(2),
            'method' => 'online',
            'session_status' => 'completed',
            'completed_at' => now(),
        ]);

        ConsultationFeedback::create([
            'consultation_id' => $consultation->id,
            'display_name' => 'Mahasiswa',
            'rating' => 5,
            'comment' => 'Pengalaman yang baik.',
            'consent_to_publish' => false,
            'is_published' => false,
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.performance.index'));

        $response->assertOk();

        $response->assertViewHas('stats', function ($stats) {

            return $stats['total_requests'] === 1
                && $stats['completed'] === 1
                && $stats['average_rating'] === 5.0
                && $stats['total_feedback'] === 1;

        });

        $response->assertViewHas('topicStats', function ($stats) {

            return $stats['total'] === 1;

        });

        $response->assertViewHas('statusStats', function ($stats) {

            return $stats['total'] === 1;

        });
    }
}