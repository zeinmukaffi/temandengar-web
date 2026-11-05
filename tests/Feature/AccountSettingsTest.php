<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_account_settings(): void
    {
        $this->get(route('admin.settings.index'))
            ->assertRedirect(route('login'));
    }

    public function test_counselor_can_access_account_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Ubah Password');
    }

    public function test_counselor_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => 'OldPassword123',
        ]);

        $this->actingAs($user)
            ->put(route('admin.settings.password.update'), [
                'current_password' => 'OldPassword123',
                'password' => 'NewPassword456',
                'password_confirmation' => 'NewPassword456',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(
            Hash::check(
                'NewPassword456',
                $user->fresh()->password
            )
        );
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create([
            'password' => 'OldPassword123',
        ]);

        $this->actingAs($user)
            ->put(route('admin.settings.password.update'), [
                'current_password' => 'WrongPassword123',
                'password' => 'NewPassword456',
                'password_confirmation' => 'NewPassword456',
            ])
            ->assertSessionHasErrors('current_password');
    }

    public function test_new_password_must_be_confirmed(): void
    {
        $user = User::factory()->create([
            'password' => 'OldPassword123',
        ]);

        $this->actingAs($user)
            ->put(route('admin.settings.password.update'), [
                'current_password' => 'OldPassword123',
                'password' => 'NewPassword456',
                'password_confirmation' => 'DifferentPassword789',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_new_password_must_differ_from_old_password(): void
    {
        $user = User::factory()->create([
            'password' => 'OldPassword123',
        ]);

        $this->actingAs($user)
            ->put(route('admin.settings.password.update'), [
                'current_password' => 'OldPassword123',
                'password' => 'OldPassword123',
                'password_confirmation' => 'OldPassword123',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_dashboard_contains_logout_confirmation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('logoutConfirmModal')
            ->assertSee('Keluar dari Akun?');
    }
}
