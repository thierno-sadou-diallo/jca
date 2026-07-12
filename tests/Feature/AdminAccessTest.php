<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        User::factory()->create([
            'email' => 'admin@jca.local',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@jca.local',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Tableau de bord');
    }

    public function test_non_admin_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_menu_marks_current_section_active(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/admin/paiements')
            ->assertOk()
            ->assertSee('class="is-active" href="http://localhost/admin/paiements"', false)
            ->assertDontSee('class="is-active" href="http://localhost/admin"', false);
    }
}
