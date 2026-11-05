<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ConsultationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IntegrationPrivacyTest extends TestCase
{
    use RefreshDatabase;

    private function submitRequest(array $extra = [])
    {
        return $this->post(route('consultations.store'), array_merge([
            'alias' => 'Mahasiswa Dummy',
            'email' => 'dummy@example.test',
            'topic' => 'academic',
            'description' => 'Saya ingin berdiskusi tentang perkuliahan.',
            'preferred_method' => 'online',
            'consent' => '1',
        ], $extra));
    }

    public function test_ai_consent_is_false_by_default(): void
    {
        $this->submitRequest()
            ->assertRedirect(route('consultations.success'));

        $request = ConsultationRequest::latest()->first();

        $this->assertFalse($request->ai_processing_consent);

        $this->assertNull($request->ai_processing_consented_at);
    }

    public function test_student_can_optionally_consent_to_ai_processing(): void
    {
        $this->submitRequest([
            'ai_processing_consent' => '1',
        ])->assertRedirect(route('consultations.success'));

        $request = ConsultationRequest::latest()->first();

        $this->assertTrue($request->ai_processing_consent);

        $this->assertNotNull($request->ai_processing_consented_at);
    }

    public function test_student_can_submit_request_without_ai_consent(): void
    {
        $response = $this->submitRequest();

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('consultation_requests', [
            'email' => 'dummy@example.test',
            'ai_processing_consent' => false,
        ]);
    }
}