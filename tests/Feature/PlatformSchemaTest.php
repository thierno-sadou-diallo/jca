<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\CooperationProject;
use App\Models\Faq;
use App\Models\HumanitarianProgram;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlatformSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_platform_tables_exist(): void
    {
        foreach ([
            'roles',
            'permissions',
            'role_user',
            'permission_role',
            'user_profiles',
            'candidate_profiles',
            'companies',
            'institutions',
            'immigration_cases',
            'appointments',
            'payments',
            'messages',
            'newsletter_subscribers',
            'pages',
            'faqs',
            'testimonials',
            'media_assets',
            'cooperation_projects',
            'humanitarian_programs',
            'activity_logs',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table [{$table}].");
        }

        $this->assertTrue(Schema::hasColumn('cooperation_projects', 'image_path'));
        $this->assertTrue(Schema::hasColumn('humanitarian_programs', 'image_path'));
    }

    public function test_authenticated_user_can_open_portal_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Espace personnel')
            ->assertSee('Feuille de route intelligente')
            ->assertSee('Votre prochain meilleur mouvement.')
            ->assertSee('Profil calibre')
            ->assertSee('Demande creee');
    }

    public function test_guest_portal_redirects_to_portal_login(): void
    {
        $this->get('/espace')
            ->assertRedirect('/connexion');
    }

    public function test_client_can_login_to_portal(): void
    {
        User::factory()->create([
            'email' => 'client@jca.local',
            'password' => 'password',
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->post('/connexion', [
            'email' => 'client@jca.local',
            'password' => 'password',
        ])->assertRedirect('/espace');
    }

    public function test_visitor_can_register_and_access_portal(): void
    {
        $this->post('/inscription', [
            'account_type' => 'client',
            'name' => 'Nouveau Client',
            'email' => 'new-client@example.com',
            'phone' => '+1 514 000 0000',
            'country' => 'Canada',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/espace');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new-client@example.com',
            'role' => 'client',
        ]);
    }

    public function test_registration_form_opens_even_when_user_has_session(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get('/inscription')
            ->assertOk()
            ->assertSee('Creer un compte');
    }

    public function test_client_can_upload_document_from_portal(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post('/espace/documents', [
                'title' => 'Passeport',
                'type' => 'Passeport',
                'document' => UploadedFile::fake()->create('passport.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('documents', [
            'user_id' => $user->id,
            'title' => 'Passeport',
            'visibility' => 'private',
        ]);
    }

    public function test_client_can_submit_request_from_portal_identity(): void
    {
        $user = User::factory()->create([
            'name' => 'Client Portal',
            'email' => 'portal-client@example.com',
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post('/demandes', [
                'name' => $user->name,
                'email' => $user->email,
                'topic' => 'Consultation strategique',
                'source' => 'portal',
                'page_slug' => 'espace',
                'message' => 'Je veux creer une nouvelle demande depuis mon espace client JCA.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('lead_requests', [
            'email' => 'portal-client@example.com',
            'source' => 'portal',
            'status' => 'new',
        ]);
    }

    public function test_client_sees_admin_response_in_portal(): void
    {
        $client = User::factory()->create([
            'email' => 'client-response@example.com',
            'role' => 'client',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $leadId = \DB::table('lead_requests')->insertGetId([
            'name' => $client->name,
            'email' => $client->email,
            'topic' => 'Immigration',
            'message' => 'Je souhaite suivre ma demande depuis mon espace client.',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->patch("/admin/demandes/{$leadId}", [
                'status' => 'contacted',
                'response_message' => 'Votre dossier est en analyse. Merci de deposer votre passeport.',
            ])
            ->assertRedirect();

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Votre dossier est en analyse');
    }

    public function test_portal_logout_redirects_to_home(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post('/deconnexion')
            ->assertRedirect('/');
    }

    public function test_client_can_send_review_and_admin_can_reply_then_delete(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($client)
            ->post('/espace/avis', [
                'quote' => 'JCA m a aide a clarifier mon projet et a organiser mes documents importants.',
            ])
            ->assertRedirect();

        $reviewId = \DB::table('testimonials')->where('user_id', $client->id)->value('id');

        $this->assertNotNull($reviewId);
        $this->assertDatabaseHas('testimonials', [
            'id' => $reviewId,
            'status' => 'pending',
            'is_published' => false,
        ]);

        $this->actingAs($admin)
            ->patch("/admin/avis/{$reviewId}", [
                'status' => 'published',
                'admin_response' => 'Merci pour votre retour. Nous restons disponibles pour la suite.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('testimonials', [
            'id' => $reviewId,
            'status' => 'published',
            'is_published' => true,
        ]);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Merci pour votre retour');

        $this->actingAs($admin)
            ->delete("/admin/avis/{$reviewId}")
            ->assertRedirect();

        $this->assertDatabaseMissing('testimonials', [
            'id' => $reviewId,
        ]);
    }

    public function test_client_and_admin_can_exchange_internal_messages(): void
    {
        $client = User::factory()->create([
            'email' => 'message-client@example.com',
            'role' => 'client',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($client)
            ->post('/espace/messages', [
                'subject' => 'Suivi de dossier',
                'body' => 'Bonjour, je souhaite connaitre les prochaines etapes de mon dossier.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'sender_id' => $client->id,
            'recipient_id' => $admin->id,
            'subject' => 'Suivi de dossier',
            'read_at' => null,
        ]);

        $this->actingAs($admin)
            ->get("/admin/messages/{$client->id}")
            ->assertOk()
            ->assertSee('Suivi de dossier')
            ->assertSee('prochaines etapes');

        $this->assertDatabaseMissing('messages', [
            'sender_id' => $client->id,
            'recipient_id' => $admin->id,
            'read_at' => null,
        ]);

        $this->actingAs($admin)
            ->post("/admin/messages/{$client->id}", [
                'subject' => 'Reponse JCA',
                'body' => 'Bonjour, votre dossier est en analyse. Nous reviendrons vers vous rapidement.',
            ])
            ->assertRedirect();

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Reponse JCA')
            ->assertSee('votre dossier est en analyse');
    }

    public function test_public_dynamic_content_pages_render_published_records(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        Article::create([
            'user_id' => $admin->id,
            'title' => 'Guide mobilite internationale',
            'slug' => 'guide-mobilite-internationale',
            'type' => Article::TYPE_BLOG,
            'excerpt' => 'Conseils pour preparer son projet.',
            'body' => 'Contenu du guide mobilite internationale.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Article::create([
            'user_id' => $admin->id,
            'title' => 'Nouvelle opportunite internationale',
            'slug' => 'nouvelle-opportunite-internationale',
            'type' => Article::TYPE_NEWS,
            'excerpt' => 'Annonce importante.',
            'body' => 'Contenu de l actualite.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Faq::create([
            'question' => 'Comment suivre mon dossier?',
            'answer' => 'Connectez-vous a votre espace client.',
            'category' => 'Espace client',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Partner::create([
            'name' => 'Institution Demo',
            'type' => 'Institution',
            'country' => 'Canada',
            'summary' => 'Partenaire institutionnel.',
            'is_featured' => true,
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Guide mobilite internationale')
            ->assertDontSee('Nouvelle opportunite internationale');

        $this->get('/actualites')
            ->assertOk()
            ->assertSee('Nouvelle opportunite internationale');

        $this->get('/articles/guide-mobilite-internationale')
            ->assertOk()
            ->assertSee('Contenu du guide mobilite internationale.');

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Comment suivre mon dossier?');

        $this->get('/partenaires')
            ->assertOk()
            ->assertSee('Institution Demo');
    }

    public function test_public_impact_pages_render_active_records_only(): void
    {
        $project = CooperationProject::create([
            'title' => 'Programme gouvernance locale',
            'slug' => 'programme-gouvernance-locale',
            'country' => 'Senegal',
            'sector' => 'Gouvernance',
            'status' => 'active',
            'starts_at' => now()->toDateString(),
            'description' => 'Projet actif de cooperation territoriale.',
            'indicators' => [
                ['value' => '12', 'label' => 'institutions mobilisees'],
            ],
        ]);

        $archivedProject = CooperationProject::create([
            'title' => 'Projet archive invisible',
            'slug' => 'projet-archive-invisible',
            'country' => 'France',
            'sector' => 'Education',
            'status' => 'archived',
        ]);

        $program = HumanitarianProgram::create([
            'title' => 'Parcours inclusion jeunesse',
            'slug' => 'parcours-inclusion-jeunesse',
            'country' => 'Canada',
            'focus_area' => 'Inclusion',
            'status' => 'active',
            'description' => 'Programme actif pour jeunes talents.',
            'impact_metrics' => [
                ['value' => '250', 'label' => 'personnes accompagnees'],
            ],
        ]);

        $draftProgram = HumanitarianProgram::create([
            'title' => 'Programme brouillon invisible',
            'slug' => 'programme-brouillon-invisible',
            'country' => 'Maroc',
            'focus_area' => 'Formation',
            'status' => 'draft',
        ]);

        $this->get('/projets-cooperation')
            ->assertOk()
            ->assertSee('Programme gouvernance locale')
            ->assertSee('Voir le projet')
            ->assertDontSee('Projet archive invisible');

        $this->get('/programmes-humanitaires')
            ->assertOk()
            ->assertSee('Parcours inclusion jeunesse')
            ->assertSee('Voir le programme')
            ->assertDontSee('Programme brouillon invisible');

        $this->get("/projets-cooperation/{$project->slug}")
            ->assertOk()
            ->assertSee('Projet actif de cooperation territoriale.')
            ->assertSee('institutions mobilisees');

        $this->get("/programmes-humanitaires/{$program->slug}")
            ->assertOk()
            ->assertSee('Programme actif pour jeunes talents.')
            ->assertSee('personnes accompagnees');

        $this->get("/projets-cooperation/{$archivedProject->slug}")
            ->assertNotFound();

        $this->get("/programmes-humanitaires/{$draftProgram->slug}")
            ->assertNotFound();
    }

    public function test_homepage_highlights_active_impact_content(): void
    {
        CooperationProject::create([
            'title' => 'Alliance education durable',
            'slug' => 'alliance-education-durable',
            'country' => 'Benin',
            'sector' => 'Education',
            'status' => 'active',
            'description' => 'Projet actif mis en avant sur la page d accueil.',
            'indicators' => [
                ['value' => '6', 'label' => 'ecoles partenaires'],
            ],
        ]);

        CooperationProject::create([
            'title' => 'Ancien projet cooperation',
            'slug' => 'ancien-projet-cooperation',
            'country' => 'Canada',
            'sector' => 'Gouvernance',
            'status' => 'archived',
        ]);

        HumanitarianProgram::create([
            'title' => 'Soutien insertion professionnelle',
            'slug' => 'soutien-insertion-professionnelle',
            'country' => 'France',
            'focus_area' => 'Employabilite',
            'status' => 'active',
            'description' => 'Programme actif mis en avant sur la page d accueil.',
            'impact_metrics' => [
                ['value' => '80', 'label' => 'beneficiaires'],
            ],
        ]);

        HumanitarianProgram::create([
            'title' => 'Programme non publie',
            'slug' => 'programme-non-publie',
            'country' => 'Maroc',
            'focus_area' => 'Formation',
            'status' => 'draft',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Alliance education durable')
            ->assertSee('Soutien insertion professionnelle')
            ->assertSee('ecoles partenaires')
            ->assertSee('beneficiaires')
            ->assertSee('Voir le projet')
            ->assertSee('Voir le programme')
            ->assertDontSee('Ancien projet cooperation')
            ->assertDontSee('Programme non publie');
    }

    public function test_english_locale_translates_full_rendered_pages(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->withSession(['locale' => 'en'])
            ->get('/')
            ->assertOk()
            ->assertSee('Create my space')
            ->assertSee('International advisory, talent mobility and impact projects.')
            ->assertDontSee('Creer mon espace');

        $this->withSession(['locale' => 'en'])
            ->get('/projets-cooperation')
            ->assertOk()
            ->assertSee('Cooperation projects')
            ->assertSee('Active projects');

        $this->actingAs($client)
            ->withSession(['locale' => 'en'])
            ->get('/espace')
            ->assertOk()
            ->assertSee('Personal space')
            ->assertSee('Billing')
            ->assertSee('Available modules')
            ->assertDontSee('Espace personnel');
    }
}
