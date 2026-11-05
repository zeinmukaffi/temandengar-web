<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_be_opened(): void
    {
        $this->get(route('home'))
            ->assertOk();
    }

    public function test_public_information_pages_can_be_opened(): void
    {
        $this->get(route('public.about'))->assertOk();

        $this->get(route('public.services'))->assertOk();

        $this->get(route('public.faq'))->assertOk();

        $this->get(route('public.privacy'))->assertOk();

        $this->get(route('public.testimonials.index'))->assertOk();
    }

    public function test_public_counselor_directory_only_shows_public_profiles(): void
    {
        User::factory()->create([
            'name' => 'Konselor Publik',
            'is_public_profile' => true,
        ]);

        User::factory()->create([
            'name' => 'Konselor Privat',
            'is_public_profile' => false,
        ]);

        $response = $this->get(route('public.counselors.index'));

        $response->assertOk();

        $response->assertSee('Konselor Publik');

        $response->assertDontSee('Konselor Privat');
    }

    public function test_private_counselor_profile_cannot_be_opened(): void
    {
        $user = User::factory()->create([
            'is_public_profile' => false,
        ]);

        $this->get(route('public.counselors.show', $user))
            ->assertNotFound();
    }

    public function test_public_counselor_profile_can_be_opened(): void
    {
        $user = User::factory()->create([
            'name' => 'Konselor Demo',
            'headline' => 'Konselor Sebaya',
            'bio' => 'Profil demonstrasi.',
            'specialties' => ['academic'],
            'is_public_profile' => true,
        ]);

        $this->get(route('public.counselors.show', $user))
            ->assertOk()
            ->assertSee('Konselor Demo');
    }
}