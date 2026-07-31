<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\LeadRequest;
use App\Models\User;
use App\Models\ImmigrationCase;
use App\Models\JobApplication;
use App\Models\Appointment;
use App\Models\CooperationProject;
use App\Models\HumanitarianProgram;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminOperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_a_job_visible_on_public_board(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post('/admin/emplois', [
                'title' => 'Infirmier international',
                'company_name' => 'Clinique partenaire',
                'country' => 'Canada',
                'city' => 'Quebec',
                'sector' => 'Sante',
                'contract_type' => 'Temps plein',
                'description' => 'Nous recherchons un profil qualifie avec experience clinique et capacite d integration internationale.',
                'requirements' => 'Diplome, experience et disponibilite.',
                'status' => 'published',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('job_postings', [
            'title' => 'Infirmier international',
            'status' => 'published',
        ]);

        Auth::logout();

        $this->get('/emplois')
            ->assertOk()
            ->assertSee('Infirmier international')
            ->assertSee('Créer un compte pour postuler');
    }

    public function test_admin_can_process_a_lead_request(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $lead = LeadRequest::create([
            'name' => 'Amina Traore',
            'email' => 'amina@example.com',
            'topic' => 'Immigration',
            'message' => 'Je souhaite etre accompagnee pour une procedure de mobilite internationale.',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->patch("/admin/demandes/{$lead->id}", [
                'status' => 'contacted',
                'admin_note' => 'Appel WhatsApp a planifier.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('lead_requests', [
            'id' => $lead->id,
            'status' => 'contacted',
        ]);
    }

    public function test_admin_creates_availability_and_client_books_slot(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $date = now()->addWeek()->startOfWeek()->addDay()->format('Y-m-d');

        $this->actingAs($admin)
            ->post('/admin/disponibilites', [
                'date' => $date,
                'time' => '10:00',
                'duration_minutes' => 45,
            ])
            ->assertRedirect();

        $slotId = DB::table('appointment_slots')->where('status', 'available')->value('id');
        $this->assertNotNull($slotId);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee((string) now()->year)
            ->assertSee('10:00');

        $this->actingAs($client)
            ->post('/espace/rendez-vous', [
                'slot_id' => $slotId,
                'week_choice' => 'current',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('lead_requests', [
            'email' => $client->email,
            'topic' => 'Demande de rendez-vous',
            'preferred_date' => $date.' 00:00:00',
        ]);

        $this->assertDatabaseHas('appointment_slots', [
            'id' => $slotId,
            'status' => 'booked',
        ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $client->id,
            'status' => 'confirmed',
        ]);

        $this->actingAs($admin)
            ->get('/admin/demandes')
            ->assertOk()
            ->assertSee('Demande de rendez-vous')
            ->assertSee('10:00');

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertDontSee('name="slot_id" value="'.$slotId.'"', false);
    }

    public function test_admin_can_reschedule_appointment(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $appointment = Appointment::create([
            'user_id' => $client->id,
            'topic' => 'Consultation strategique',
            'starts_at' => now()->addDays(3),
            'duration_minutes' => 45,
            'channel' => 'online',
            'status' => Appointment::STATUS_CONFIRMED,
        ]);

        $newDate = now()->addDays(7)->setTime(14, 30);

        $this->actingAs($admin)
            ->get('/admin/rendez-vous')
            ->assertOk()
            ->assertSee('Consultation strategique');

        $this->actingAs($admin)
            ->patch("/admin/rendez-vous/{$appointment->id}", [
                'topic' => 'Consultation strategique',
                'starts_at' => $newDate->format('Y-m-d H:i:s'),
                'duration_minutes' => 60,
                'channel' => 'Zoom',
                'status' => Appointment::STATUS_RESCHEDULED,
                'notes' => 'Rendez-vous reporte avec un lien Zoom a transmettre.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => Appointment::STATUS_RESCHEDULED,
            'channel' => 'Zoom',
            'duration_minutes' => 60,
        ]);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Reporte')
            ->assertSee('lien Zoom');
    }

    public function test_client_receives_and_reads_portal_notifications(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $appointment = Appointment::create([
            'user_id' => $client->id,
            'topic' => 'Consultation strategique',
            'starts_at' => now()->addDays(2),
            'duration_minutes' => 45,
            'channel' => 'online',
            'status' => Appointment::STATUS_CONFIRMED,
        ]);

        $this->actingAs($admin)
            ->patch("/admin/rendez-vous/{$appointment->id}", [
                'topic' => 'Consultation strategique',
                'starts_at' => now()->addDays(4)->format('Y-m-d H:i:s'),
                'duration_minutes' => 45,
                'channel' => 'Teams',
                'status' => Appointment::STATUS_RESCHEDULED,
                'notes' => 'Votre rendez-vous est reporte sur Teams.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $client->id,
            'notifiable_type' => User::class,
            'read_at' => null,
        ]);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Rendez-vous mis a jour')
            ->assertSee('reporte sur Teams');

        $this->actingAs($client)
            ->post('/espace/notifications/lues')
            ->assertRedirect();

        $this->assertSame(0, $client->fresh()->unreadNotifications()->count());
    }

    public function test_admin_can_see_uploaded_client_documents_and_respond(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($client)
            ->post('/espace/documents', [
                'title' => 'Passeport principal',
                'type' => 'Passeport',
                'document' => UploadedFile::fake()->create('passeport.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect();

        $lead = LeadRequest::where('email', $client->email)
            ->where('topic', 'Depot de document')
            ->firstOrFail();

        $this->assertDatabaseHas('documents', [
            'lead_request_id' => $lead->id,
            'user_id' => $client->id,
            'title' => 'Passeport principal',
        ]);

        $this->actingAs($admin)
            ->get('/admin/demandes')
            ->assertOk()
            ->assertSee('Depot de document')
            ->assertSee('1');

        $this->actingAs($admin)
            ->get("/admin/demandes/{$lead->id}")
            ->assertOk()
            ->assertSee('Passeport principal')
            ->assertSee('Télécharger')
            ->assertSee('Réponse professionnelle au client');

        $this->actingAs($admin)
            ->patch("/admin/demandes/{$lead->id}", [
                'status' => 'contacted',
                'admin_note' => 'Document lisible.',
                'response_message' => 'Bonjour, votre document a bien ete recu. Nous poursuivons l analyse de votre dossier.',
            ])
            ->assertRedirect();

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Réponse disponible')
            ->assertSee('Bonjour, votre document a bien ete recu.');
    }

    public function test_admin_can_review_client_documents(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($client)
            ->post('/espace/documents', [
                'title' => 'CV international',
                'type' => 'CV',
                'document' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect();

        $documentId = \DB::table('documents')->where('user_id', $client->id)->value('id');
        $this->assertNotNull($documentId);

        $this->actingAs($admin)
            ->get('/admin/documents')
            ->assertOk()
            ->assertSee('CV international')
            ->assertSee('En attente');

        $this->actingAs($admin)
            ->patch("/admin/documents/{$documentId}", [
                'status' => 'new_version_requested',
                'admin_note' => 'Merci de transmettre une version signee et plus recente.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('documents', [
            'id' => $documentId,
            'status' => 'new_version_requested',
            'admin_note' => 'Merci de transmettre une version signee et plus recente.',
            'reviewed_by' => $admin->id,
        ]);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Nouvelle version demandée')
            ->assertSee('version signee');
    }

    public function test_admin_can_manage_client_profiles(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'name' => 'Client Entreprise',
            'email' => 'client-entreprise@example.com',
            'role' => 'client',
            'status' => 'active',
        ]);

        $client->profile()->create([
            'account_type' => 'client',
            'type_client' => 'Entreprise',
            'organization_name' => 'Groupe Horizon',
            'country' => 'Canada',
            'preferred_language' => 'fr',
        ]);

        LeadRequest::create([
            'name' => $client->name,
            'email' => $client->email,
            'topic' => 'Partenariat',
            'message' => 'Nous souhaitons initier un partenariat avec JCA.',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get('/admin/clients?type_client=Entreprise')
            ->assertOk()
            ->assertSee('Client Entreprise')
            ->assertSee('Groupe Horizon');

        $this->actingAs($admin)
            ->get("/admin/clients/{$client->id}")
            ->assertOk()
            ->assertSee('Fiche client')
            ->assertSee('Partenariat')
            ->assertSee('Message');

        $this->actingAs($admin)
            ->patch("/admin/clients/{$client->id}", [
                'status' => 'suspended',
                'type_client' => 'Partenaire',
                'organization_name' => 'Horizon Partners',
                'country' => 'France',
                'city' => 'Paris',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $client->id,
            'status' => 'suspended',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $client->id,
            'type_client' => 'Partenaire',
            'organization_name' => 'Horizon Partners',
            'country' => 'France',
            'city' => 'Paris',
        ]);
    }

    public function test_admin_can_create_and_update_immigration_case(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $lead = LeadRequest::create([
            'name' => $client->name,
            'email' => $client->email,
            'topic' => 'Residence permanente',
            'message' => 'Je souhaite ouvrir un dossier immigration.',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get("/admin/dossiers-immigration/creer?lead_id={$lead->id}")
            ->assertOk()
            ->assertSee('Residence permanente');

        $this->actingAs($admin)
            ->post('/admin/dossiers-immigration', [
                'user_id' => $client->id,
                'lead_request_id' => $lead->id,
                'program_type' => 'Residence permanente',
                'destination_country' => 'Canada',
                'status' => ImmigrationCase::STATUS_RECEIVED,
                'submitted_at' => now()->toDateString(),
                'note' => 'Dossier ouvert apres analyse de la demande initiale.',
            ])
            ->assertRedirect();

        $case = ImmigrationCase::where('user_id', $client->id)->firstOrFail();

        $this->assertDatabaseHas('lead_requests', [
            'id' => $lead->id,
            'status' => 'converted',
        ]);

        $this->assertDatabaseHas('case_status_histories', [
            'immigration_case_id' => $case->id,
            'status' => ImmigrationCase::STATUS_RECEIVED,
        ]);

        $this->actingAs($admin)
            ->patch("/admin/dossiers-immigration/{$case->id}", [
                'program_type' => 'Residence permanente',
                'destination_country' => 'Canada',
                'status' => ImmigrationCase::STATUS_IN_ANALYSIS,
                'submitted_at' => now()->toDateString(),
                'decision_at' => null,
                'note' => 'Le dossier est en analyse documentaire.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('immigration_cases', [
            'id' => $case->id,
            'status' => ImmigrationCase::STATUS_IN_ANALYSIS,
        ]);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee($case->reference)
            ->assertSee('En analyse')
            ->assertSee('analyse documentaire');
    }

    public function test_client_can_apply_to_job_and_admin_can_review_application(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $job = JobPosting::create([
            'title' => 'Soudeur international',
            'slug' => 'soudeur-international',
            'company_name' => 'Entreprise partenaire',
            'country' => 'Canada',
            'city' => 'Quebec',
            'sector' => 'Industrie',
            'contract_type' => 'Temps plein',
            'description' => 'Poste technique pour candidat qualifie.',
            'requirements' => 'CV et experience pertinente.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->actingAs($client)
            ->post("/emplois/{$job->id}/postuler", [
                'country' => 'Maroc',
                'phone' => '+212 600 000 000',
                'resume' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
                'message' => 'Je suis disponible pour une mission internationale.',
            ])
            ->assertRedirect();

        $application = JobApplication::where('user_id', $client->id)->firstOrFail();

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'job_posting_id' => $job->id,
            'status' => JobApplication::STATUS_NEW,
        ]);

        $this->actingAs($admin)
            ->get('/admin/candidatures')
            ->assertOk()
            ->assertSee('Soudeur international')
            ->assertSee($client->email);

        $this->actingAs($admin)
            ->patch("/admin/candidatures/{$application->id}", [
                'status' => JobApplication::STATUS_PREQUALIFIED,
                'admin_note' => 'Profil prequalifie. Un entretien sera planifie.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => JobApplication::STATUS_PREQUALIFIED,
            'reviewed_by' => $admin->id,
        ]);

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Soudeur international')
            ->assertSee('Préqualifiée')
            ->assertSee('entretien sera planifie');
    }

    public function test_admin_can_manage_articles_and_faqs(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post('/admin/articles', [
                'title' => 'Preparer son dossier Canada',
                'type' => 'blog',
                'excerpt' => 'Conseils pratiques pour structurer un dossier solide.',
                'body' => 'Un dossier solide repose sur les preuves, la coherence et les delais.',
                'status' => 'published',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('articles', [
            'title' => 'Preparer son dossier Canada',
            'slug' => 'preparer-son-dossier-canada',
            'status' => 'published',
            'user_id' => $admin->id,
        ]);

        $articleId = \DB::table('articles')->where('slug', 'preparer-son-dossier-canada')->value('id');

        $this->actingAs($admin)
            ->patch("/admin/articles/{$articleId}", [
                'title' => 'Preparer son dossier Canada',
                'type' => 'blog',
                'excerpt' => 'Version enrichie.',
                'body' => 'Version mise a jour avec des conseils plus precis.',
                'status' => 'draft',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('articles', [
            'id' => $articleId,
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->actingAs($admin)
            ->post('/admin/faqs', [
                'question' => 'Puis-je suivre mon dossier en ligne?',
                'answer' => 'Oui, l espace client permet de suivre les demandes, documents et messages.',
                'category' => 'Portail client',
                'sort_order' => 10,
                'is_published' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', [
            'question' => 'Puis-je suivre mon dossier en ligne?',
            'category' => 'Portail client',
            'sort_order' => 10,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_manage_partners(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post('/admin/partenaires', [
                'name' => 'Universite Internationale Demo',
                'type' => 'Universite',
                'country' => 'Canada',
                'website' => 'https://example.org',
                'summary' => 'Partenaire academique pour la mobilite internationale.',
                'logo' => UploadedFile::fake()->image('logo.png', 300, 160),
                'is_featured' => 1,
            ])
            ->assertRedirect();

        $partnerId = \DB::table('partners')->where('name', 'Universite Internationale Demo')->value('id');
        $this->assertNotNull($partnerId);

        $this->assertDatabaseHas('partners', [
            'id' => $partnerId,
            'type' => 'Universite',
            'country' => 'Canada',
            'is_featured' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/partenaires?type=Universite')
            ->assertOk()
            ->assertSee('Universite Internationale Demo');

        $this->actingAs($admin)
            ->patch("/admin/partenaires/{$partnerId}", [
                'name' => 'Universite Internationale Demo',
                'type' => 'Institution',
                'country' => 'France',
                'website' => 'https://example.org',
                'summary' => 'Partenaire institutionnel mis a jour.',
                'is_featured' => 0,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('partners', [
            'id' => $partnerId,
            'type' => 'Institution',
            'country' => 'France',
            'is_featured' => false,
        ]);
    }

    public function test_admin_can_update_site_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->patch('/admin/parametres', [
                'brand_name' => 'JCA International',
                'brand_tagline' => 'Mobilite, talents et cooperation',
                'footer_description' => 'Plateforme internationale de conseil et accompagnement.',
                'contact_email' => 'hello@jca.test',
                'contact_phone' => '+1 514 555 0101',
                'whatsapp' => '+15145550101',
                'address' => 'Montreal, Canada',
                'footer_signature' => 'Construire des ponts durables.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('site_settings', [
            'key' => 'brand_name',
            'value' => 'JCA International',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('JCA International')
            ->assertSee('hello@jca.test')
            ->assertSee('Construire des ponts durables.');
    }

    public function test_admin_can_view_reports_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        LeadRequest::create([
            'name' => $client->name,
            'email' => $client->email,
            'topic' => 'Immigration',
            'message' => 'Je souhaite etre accompagne.',
            'status' => 'new',
        ]);

        Appointment::create([
            'user_id' => $client->id,
            'topic' => 'Consultation strategique',
            'starts_at' => now()->addDay(),
            'duration_minutes' => 45,
            'channel' => 'online',
            'status' => Appointment::STATUS_CONFIRMED,
        ]);

        $this->actingAs($admin)
            ->get('/admin/statistiques')
            ->assertOk()
            ->assertSee('Statistiques')
            ->assertSee('Demandes par mois')
            ->assertSee('Types de clients')
            ->assertSee('Rendez-vous');
    }

    public function test_admin_can_manage_user_accounts(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'name' => 'Compte Client',
            'email' => 'compte-client@example.com',
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/admin/utilisateurs?role=client')
            ->assertOk()
            ->assertSee('Compte Client');

        $this->actingAs($admin)
            ->patch("/admin/utilisateurs/{$client->id}", [
                'name' => 'Compte Client Suspendu',
                'phone' => '+1 514 000 1111',
                'role' => 'client',
                'status' => 'suspended',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $client->id,
            'name' => 'Compte Client Suspendu',
            'phone' => '+1 514 000 1111',
            'role' => 'client',
            'status' => 'suspended',
        ]);

        $this->actingAs($admin)
            ->patch("/admin/utilisateurs/{$admin->id}", [
                'name' => $admin->name,
                'phone' => $admin->phone,
                'role' => 'client',
                'status' => 'active',
            ])
            ->assertSessionHasErrors('role');
    }

    public function test_admin_can_manage_impact_projects_and_programs(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post('/admin/projets-cooperation', [
                'title' => 'Renforcement des capacites locales',
                'country' => 'Senegal',
                'sector' => 'Gouvernance',
                'status' => 'active',
                'starts_at' => now()->toDateString(),
                'ends_at' => now()->addMonths(6)->toDateString(),
                'description' => 'Projet de cooperation pour renforcer les capacites institutionnelles.',
                'image' => UploadedFile::fake()->image('cooperation.jpg', 900, 520),
                'indicators' => [
                    ['value' => '12', 'label' => 'institutions mobilisees'],
                    ['value' => '4', 'label' => 'regions ciblees'],
                    ['value' => '', 'label' => ''],
                ],
            ])
            ->assertRedirect();

        $project = CooperationProject::where('slug', 'renforcement-des-capacites-locales')->firstOrFail();
        $this->assertNotNull($project->image_path);
        Storage::disk('public')->assertExists($project->image_path);
        $this->assertSame('12', $project->indicators[0]['value']);
        $this->assertSame('institutions mobilisees', $project->indicators[0]['label']);

        $this->actingAs($admin)
            ->get('/admin/projets-cooperation?status=active')
            ->assertOk()
            ->assertSee('Renforcement des capacites locales');

        $this->actingAs($admin)
            ->patch("/admin/projets-cooperation/{$project->id}", [
                'title' => 'Renforcement des capacites locales',
                'country' => 'Senegal',
                'sector' => 'Gouvernance locale',
                'status' => 'completed',
                'starts_at' => now()->toDateString(),
                'ends_at' => now()->addMonths(6)->toDateString(),
                'description' => 'Projet finalise.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cooperation_projects', [
            'id' => $project->id,
            'status' => 'completed',
            'sector' => 'Gouvernance locale',
        ]);

        $this->actingAs($admin)
            ->post('/admin/programmes-humanitaires', [
                'title' => 'Inclusion femmes et jeunes',
                'country' => 'Canada',
                'focus_area' => 'Inclusion sociale',
                'status' => 'active',
                'description' => 'Programme humanitaire pilote.',
                'image' => UploadedFile::fake()->image('humanitaire.jpg', 900, 520),
                'impact_metrics' => [
                    ['value' => '250', 'label' => 'personnes accompagnees'],
                    ['value' => '8', 'label' => 'ateliers terrain'],
                ],
            ])
            ->assertRedirect();

        $program = HumanitarianProgram::where('slug', 'inclusion-femmes-et-jeunes')->firstOrFail();
        $this->assertNotNull($program->image_path);
        Storage::disk('public')->assertExists($program->image_path);
        $this->assertSame('250', $program->impact_metrics[0]['value']);
        $this->assertSame('personnes accompagnees', $program->impact_metrics[0]['label']);

        $this->actingAs($admin)
            ->get('/admin/programmes-humanitaires?status=active')
            ->assertOk()
            ->assertSee('Inclusion femmes et jeunes');

        $this->actingAs($admin)
            ->patch("/admin/programmes-humanitaires/{$program->id}", [
                'title' => 'Inclusion femmes et jeunes',
                'country' => 'Canada',
                'focus_area' => 'Employabilite',
                'status' => 'archived',
                'description' => 'Programme archive.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('humanitarian_programs', [
            'id' => $program->id,
            'status' => 'archived',
            'focus_area' => 'Employabilite',
        ]);
    }

    public function test_admin_can_create_payment_visible_to_client(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post('/admin/paiements', [
                'user_id' => $client->id,
                'reference' => 'JCA-TEST-001',
                'amount' => '350.00',
                'currency' => 'CAD',
                'provider' => 'Stripe',
                'status' => Payment::STATUS_PENDING,
                'note' => 'Frais de consultation strategique.',
                'payment_url' => 'https://pay.example.test/jca-test-001',
            ])
            ->assertRedirect();

        $payment = Payment::where('reference', 'JCA-TEST-001')->firstOrFail();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'user_id' => $client->id,
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get('/admin/paiements?status=pending')
            ->assertOk()
            ->assertSee('JCA-TEST-001')
            ->assertSee('Stripe');

        $this->actingAs($client)
            ->get('/espace')
            ->assertOk()
            ->assertSee('Facturation')
            ->assertSee('JCA-TEST-001')
            ->assertSee('Frais de consultation strategique.')
            ->assertSee('Ouvrir le lien de paiement');

        $this->actingAs($admin)
            ->patch("/admin/paiements/{$payment->id}", [
                'user_id' => $client->id,
                'reference' => 'JCA-TEST-001',
                'amount' => '350.00',
                'currency' => 'CAD',
                'provider' => 'Stripe',
                'status' => Payment::STATUS_PAID,
                'note' => 'Paiement confirme.',
                'payment_url' => 'https://pay.example.test/jca-test-001',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => Payment::STATUS_PAID,
        ]);
    }
}
