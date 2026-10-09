<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_the_profile_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard/profile')
            ->assertOk()
            ->assertSee('Informasi akun')
            ->assertSee('Kata sandi baru')
            ->assertSee('Log out');
    }

    public function test_user_can_update_name_and_email_without_changing_password(): void
    {
        $user = User::factory()->create(['password' => 'InitialPassword123!']);

        $this->actingAs($user)
            ->put('/dashboard/profile', [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
        $this->assertTrue(Hash::check('InitialPassword123!', $user->fresh()->password));
    }

    public function test_user_must_provide_current_password_to_change_password(): void
    {
        $user = User::factory()->create(['password' => 'InitialPassword123!']);

        $this->actingAs($user)
            ->put('/dashboard/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'current_password' => 'WrongPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('InitialPassword123!', $user->fresh()->password));
    }

    public function test_user_can_change_password_with_the_correct_current_password(): void
    {
        $user = User::factory()->create(['password' => 'InitialPassword123!']);

        $this->actingAs($user)
            ->put('/dashboard/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'current_password' => 'InitialPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertRedirect();

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }
}
