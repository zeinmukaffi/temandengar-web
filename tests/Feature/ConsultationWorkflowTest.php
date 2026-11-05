<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ConsultationRequest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createRequest(
        string $status = 'pending'
    ): ConsultationRequest {

        return ConsultationRequest::create([
            'ticket_number' => 'TD-2026-' . strtoupper(
                fake()->unique()->lexify('??????')
            ),
            'alias' => 'Mahasiswa Dummy',
            'email' => 'dummy@example.test',
            'phone' => null,
            'topic' => 'academic',
            'description' => 'Saya memiliki kesulitan dalam perkuliahan.',
            'preferred_method' => 'online',
            'status' => $status,
            'consent_at' => now(),
        ]);
    }

    public function test_student_can_submit_consultation_request(): void
    {
        $response = $this->post(route('consultations.store'), [
            'alias' => 'Andi',
            'email' => 'andi@example.test',
            'phone' => null,
            'topic' => 'academic',
            'description' => 'Saya kesulitan membagi waktu kuliah.',
            'preferred_method' => 'online',
            'consent' => '1',
        ]);

        $response->assertRedirect(route('consultations.success'));

        $this->assertDatabaseHas('consultation_requests', [
            'alias' => 'Andi',
            'status' => 'pending',
        ]);

        $response->assertSessionHas('ticket_number');
    }

    public function test_consultation_form_requires_contact_information(): void
    {
        $response = $this->post(route('consultations.store'), [
            'alias' => 'Andi',
            'topic' => 'academic',
            'description' => 'Saya ingin bercerita tentang kuliah.',
            'preferred_method' => 'online',
            'consent' => '1',
        ]);

        $response->assertSessionHasErrors(['email', 'phone']);
    }

    public function test_guest_cannot_access_counselor_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_counselor_can_access_consultation_list(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.consultations.index'))
            ->assertOk();
    }

    public function test_counselor_can_update_request_status(): void
    {
        $user = User::factory()->create();

        $consultationRequest = $this->createRequest('pending');

        $response = $this->actingAs($user)->patch(
            route('admin.consultations.update-status', $consultationRequest),
            ['status' => 'reviewing']
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('consultation_requests', [
            'id' => $consultationRequest->id,
            'status' => 'reviewing',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'request_status_updated',
            'user_id' => $user->id,
        ]);
    }

    public function test_counselor_can_create_consultation_schedule(): void
    {
        $user = User::factory()->create();

        $consultationRequest = $this->createRequest('reviewing');

        $response = $this->actingAs($user)->post(
            route('admin.schedules.store', $consultationRequest),
            [
                'scheduled_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
                'method' => 'online',
                'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            ]
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('consultations', [
            'request_id' => $consultationRequest->id,
            'counselor_id' => $user->id,
            'session_status' => 'scheduled',
        ]);

        $this->assertDatabaseHas('consultation_requests', [
            'id' => $consultationRequest->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_draft_resource_cannot_be_accessed_publicly(): void
    {
        $resource = \App\Models\Resource::create([
            'title' => 'Materi Dummy',
            'category' => 'academic',
            'description' => 'Materi untuk testing.',
            'resource_url' => 'https://example.com/materi',
            'is_published' => false,
        ]);

        $this->get(route('public.resources.show', $resource))
            ->assertNotFound();
    }
}