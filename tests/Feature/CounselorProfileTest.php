<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CounselorProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_management(): void
    {
        $this->get(route('admin.profile.edit'))
            ->assertRedirect(route('login'));
    }

    public function test_counselor_can_access_profile_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya');
    }

    public function test_counselor_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('admin.profile.update'), [
                'name' => 'Zein Demo',
                'headline' => 'Konselor Sebaya',
                'education' => 'Mahasiswa',
                'bio' => 'Menyediakan ruang dukungan teman sebaya.',
                'specialties' => ['academic', 'social'],
                'is_public_profile' => '1',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Zein Demo',
            'headline' => 'Konselor Sebaya',
            'is_public_profile' => true,
        ]);

        $this->assertEquals(
            ['academic', 'social'],
            $user->fresh()->specialties
        );
    }

    public function test_counselor_can_hide_public_profile(): void
    {
        $user = User::factory()->create([
            'is_public_profile' => true,
        ]);

        $this->actingAs($user)
            ->put(route('admin.profile.update'), [
                'name' => $user->name,
                'is_public_profile' => '0',
            ])
            ->assertSessionHasNoErrors();

        $this->assertFalse(
            $user->fresh()->is_public_profile
        );

        $this->get(route('public.counselors.show', $user))
            ->assertNotFound();
    }
}