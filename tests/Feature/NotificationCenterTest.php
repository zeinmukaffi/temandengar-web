<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Notifications\TemanDengarNotification;

use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;


    private function createNotification(User $user): void
    {
        $user->notify(
            new TemanDengarNotification(
                'test_event',
                'Notifikasi Testing',
                'Ini merupakan notifikasi untuk testing.',
                route('admin.notifications.index', [], false),
                'bi-bell'
            )
        );
    }


    public function test_guest_cannot_access_notification_center(): void
    {
        $this->get(route('admin.notifications.index'))
            ->assertRedirect(route('login'));
    }


    public function test_counselor_can_access_notification_center(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.notifications.index'))
            ->assertOk()
            ->assertSee('Notifikasi');
    }


    public function test_new_consultation_request_creates_notification(): void
    {
        $user = User::factory()->create();

        $response = $this->post(
            route('consultations.store'),
            [
                'alias' => 'Mahasiswa Dummy',
                'email' => 'dummy@example.test',
                'topic' => 'academic',
                'description' => 'Saya ingin berdiskusi tentang perkuliahan.',
                'preferred_method' => 'online',
                'consent' => '1',
            ]
        );

        $response->assertRedirect(route('consultations.success'));

        $this->assertSame(
            1,
            $user->notifications()->count()
        );

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => TemanDengarNotification::class,
        ]);

    }


    public function test_notification_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();

        $this->createNotification($user);

        $notification = $user->notifications()->first();

        $response = $this->actingAs($user)->post(
            route('admin.notifications.read', $notification->id)
        );

        $response->assertRedirect(
            route('admin.notifications.index')
        );

        $this->assertNotNull(
            $notification->fresh()->read_at
        );

    }


    public function test_counselor_cannot_read_another_users_notification(): void
    {
        $owner = User::factory()->create();

        $otherUser = User::factory()->create();

        $this->createNotification($owner);

        $notification = $owner->notifications()->first();

        $this->actingAs($otherUser)->post(
            route('admin.notifications.read', $notification->id)
        )->assertNotFound();

        $this->assertNull(
            $notification->fresh()->read_at
        );

    }


    public function test_counselor_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();

        $this->createNotification($user);

        $this->createNotification($user);

        $this->assertSame(
            2,
            $user->unreadNotifications()->count()
        );

        $response = $this->actingAs($user)->post(
            route('admin.notifications.read-all')
        );

        $response->assertSessionHasNoErrors();

        $this->assertSame(
            0,
            $user->fresh()->unreadNotifications()->count()
        );

    }


    public function test_notification_dropdown_shows_unread_count(): void
    {
        $user = User::factory()->create();

        $this->createNotification($user);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertSee('Notifikasi Testing');

        $response->assertSee('1 Baru');

    }

}