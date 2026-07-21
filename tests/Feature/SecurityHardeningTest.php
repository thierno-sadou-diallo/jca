<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\CooperationProject;
use App\Models\HumanitarianProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_lead_requests_are_rate_limited(): void
    {
        $payload = [
            'name' => 'Marie Diallo',
            'email' => 'marie@example.com',
            'topic' => 'Immigration',
            'message' => 'Je souhaite evaluer mes options pour un projet de residence permanente au Canada.',
            'preferred_channel' => 'Email',
        ];

        $this->postJson('/demandes', $payload)->assertCreated();
        $this->postJson('/demandes', $payload)->assertCreated();
        $this->postJson('/demandes', $payload)->assertTooManyRequests();
    }

    public function test_registration_requires_admin_activation_by_default(): void
    {
        config(['auth.portal_auto_activate' => false]);

        $this->post('/inscription', [
            'name' => 'Client Test',
            'email' => 'client-test@example.com',
            'password' => 'Password12345',
            'password_confirmation' => 'Password12345',
            'type_client' => 'Particulier',
            'country' => 'Canada',
        ])->assertRedirect('/connexion');

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'client-test@example.com',
            'role' => 'client',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_dashboard_surfaces_pending_client_accounts(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::factory()->create([
            'role' => 'client',
            'status' => 'inactive',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Comptes a activer')
            ->assertSee('Valider les nouvelles inscriptions');
    }

    public function test_security_headers_use_nonce_and_block_inline_scripts_and_styles(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        $policy = (string) $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("script-src 'self' 'nonce-", $policy);
        $this->assertStringContainsString("style-src 'self'", $policy);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $policy);
        $this->assertStringNotContainsString("style-src 'self' 'unsafe-inline'", $policy);
    }

    public function test_sitemap_includes_published_dynamic_content_and_localized_urls(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        Article::create([
            'user_id' => $admin->id,
            'title' => 'Actualite immigration',
            'slug' => 'actualite-immigration',
            'type' => Article::TYPE_NEWS,
            'excerpt' => 'Mise a jour immigration.',
            'body' => 'Contenu publie.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        CooperationProject::create([
            'title' => 'Projet impact',
            'slug' => 'projet-impact',
            'country' => 'Canada',
            'sector' => 'Education',
            'status' => 'active',
        ]);

        HumanitarianProgram::create([
            'title' => 'Programme inclusion',
            'slug' => 'programme-inclusion',
            'country' => 'Canada',
            'focus_area' => 'Inclusion',
            'status' => 'active',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('http://localhost/articles/actualite-immigration', false)
            ->assertSee('http://localhost/en/articles/actualite-immigration', false)
            ->assertSee('http://localhost/projets-cooperation/projet-impact', false)
            ->assertSee('http://localhost/programmes-humanitaires/programme-inclusion', false)
            ->assertSee('http://localhost/fr/services', false);
    }
}
