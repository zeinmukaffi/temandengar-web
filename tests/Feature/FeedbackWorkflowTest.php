<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationFeedback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

class FeedbackWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createCompletedConsultation(
        User $user
    ): Consultation {

        $consultationRequest = ConsultationRequest::create([
            'ticket_number' => 'TD-2026-' . fake()->unique()->numerify('######'),
            'alias' => 'Mahasiswa Dummy',
            'email' => 'dummy@example.test',
            'topic' => 'academic',
            'description' => 'Testing feedback consultation.',
            'preferred_method' => 'online',
            'status' => 'completed',
            'consent_at' => now(),
        ]);

        return Consultation::create([
            'request_id' => $consultationRequest->id,
            'counselor_id' => $user->id,
            'scheduled_at' => now()->subDays(2),
            'method' => 'online',
            'session_status' => 'completed',
            'completed_at' => now(),
        ]);
    }


    public function test_guest_cannot_access_feedback_management(): void
    {
        $this->get(route('admin.feedback.index'))
            ->assertRedirect(route('login'));
    }


    public function test_student_can_open_valid_signed_feedback_link(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        $url = URL::temporarySignedRoute(
            'public.feedback.create',
            now()->addDays(30),
            ['consultation' => $consultation->id]
        );

        $this->get($url)
            ->assertOk()
            ->assertSee('Bagaimana pengalaman');
    }


    public function test_unsigned_feedback_link_is_rejected(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        $this->get(
            route('public.feedback.create', $consultation)
        )->assertForbidden();
    }


    public function test_student_can_submit_feedback(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        $url = URL::temporarySignedRoute(
            'public.feedback.store',
            now()->addDays(30),
            ['consultation' => $consultation->id]
        );

        $response = $this->post($url, [
            'display_name' => 'Mahasiswa',
            'rating' => 5,
            'comment' => 'Pengalaman konsultasi yang sangat baik.',
            'consent_to_publish' => '1',
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('consultation_feedback', [
            'consultation_id' => $consultation->id,
            'rating' => 5,
            'consent_to_publish' => true,
            'is_published' => false,
        ]);
    }


    public function test_student_can_submit_private_rating(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        $url = URL::temporarySignedRoute(
            'public.feedback.store',
            now()->addDays(30),
            ['consultation' => $consultation->id]
        );

        $this->post($url, [
            'rating' => 4,
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('consultation_feedback', [
            'consultation_id' => $consultation->id,
            'rating' => 4,
            'consent_to_publish' => false,
            'is_published' => false,
        ]);
    }


    public function test_feedback_cannot_be_submitted_twice(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        ConsultationFeedback::create([
            'consultation_id' => $consultation->id,
            'display_name' => 'Mahasiswa',
            'rating' => 5,
            'comment' => 'Feedback pertama.',
            'consent_to_publish' => false,
            'is_published' => false,
        ]);

        $url = URL::temporarySignedRoute(
            'public.feedback.create',
            now()->addDays(30),
            ['consultation' => $consultation->id]
        );

        $this->get($url)
            ->assertOk()
            ->assertSee('Feedback sudah dikirim');
    }


    public function test_counselor_cannot_publish_without_student_consent(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        $feedback = ConsultationFeedback::create([
            'consultation_id' => $consultation->id,
            'display_name' => 'Mahasiswa',
            'rating' => 5,
            'comment' => 'Komentar pribadi.',
            'consent_to_publish' => false,
            'is_published' => false,
        ]);

        $response = $this->actingAs($user)->patch(
            route('admin.feedback.update-publication', $feedback),
            ['is_published' => 1]
        );

        $response->assertSessionHasErrors(['publication']);

        $this->assertDatabaseHas('consultation_feedback', [
            'id' => $feedback->id,
            'is_published' => false,
        ]);
    }


    public function test_counselor_can_publish_feedback_with_consent(): void
    {
        $user = User::factory()->create();

        $consultation = $this->createCompletedConsultation($user);

        $feedback = ConsultationFeedback::create([
            'consultation_id' => $consultation->id,
            'display_name' => 'Mahasiswa',
            'rating' => 5,
            'comment' => 'Pengalaman yang sangat membantu.',
            'consent_to_publish' => true,
            'is_published' => false,
        ]);

        $response = $this->actingAs($user)->patch(
            route('admin.feedback.update-publication', $feedback),
            ['is_published' => 1]
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('consultation_feedback', [
            'id' => $feedback->id,
            'is_published' => true,
        ]);
    }
}