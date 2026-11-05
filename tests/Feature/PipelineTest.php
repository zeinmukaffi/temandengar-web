<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ConsultationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PipelineTest extends TestCase
{
    use RefreshDatabase;

    private function createRequest(
        string $ticket,
        string $status
    ): ConsultationRequest {

        return ConsultationRequest::create([
            'ticket_number' => $ticket,
            'alias' => 'Mahasiswa Dummy',
            'email' => 'dummy@example.test',
            'topic' => 'academic',
            'description' => 'Testing pipeline konsultasi.',
            'preferred_method' => 'online',
            'status' => $status,
            'consent_at' => now(),
        ]);
    }

    public function test_guest_cannot_access_pipeline(): void
    {
        $this->get(route('admin.pipeline.index'))
            ->assertRedirect(route('login'));
    }

    public function test_counselor_can_access_pipeline(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.pipeline.index'))
            ->assertOk()
            ->assertSee('Pipeline Kasus');
    }

    public function test_pipeline_displays_requests_in_correct_columns(): void
    {
        $user = User::factory()->create();

        $this->createRequest('TD-2026-PIPE01', 'pending');
        $this->createRequest('TD-2026-PIPE02', 'reviewing');
        $this->createRequest('TD-2026-PIPE03', 'scheduled');
        $this->createRequest('TD-2026-PIPE04', 'completed');

        $response = $this->actingAs($user)
            ->get(route('admin.pipeline.index'));

        $response->assertOk();

        $response->assertSee('TD-2026-PIPE01');
        $response->assertSee('TD-2026-PIPE02');
        $response->assertSee('TD-2026-PIPE03');
        $response->assertSee('TD-2026-PIPE04');
    }

    public function test_pipeline_search_filters_requests(): void
    {
        $user = User::factory()->create();

        $this->createRequest('TD-2026-PIPE05', 'pending');
        $this->createRequest('TD-2026-PIPE06', 'reviewing');

        $response = $this->actingAs($user)->get(
            route('admin.pipeline.index', [
                'search' => 'PIPE05',
            ])
        );

        $response->assertOk();
        $response->assertSee('TD-2026-PIPE05');
        $response->assertDontSee('TD-2026-PIPE06');
    }
}