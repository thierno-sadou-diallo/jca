<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\JobPosting;
use App\Models\Partner;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'admin' => 'Administrateur',
            'client' => 'Client',
        ];

        foreach ($roles as $name => $label) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['label' => $label, 'description' => 'Rôle système JCA', 'created_at' => now(), 'updated_at' => now()],
            );
        }

        $permissions = [
            'manage_users' => 'Gérer les utilisateurs',
            'manage_roles' => 'Gérer les rôles et permissions',
            'manage_services' => 'Gérer les services',
            'manage_content' => 'Gérer pages, blog, actualités et FAQ',
            'manage_jobs' => 'Gérer les offres et candidatures',
            'manage_immigration_cases' => 'Gérer les dossiers immigration',
            'manage_appointments' => 'Gérer les rendez-vous',
            'manage_payments' => 'Gérer les paiements',
            'manage_documents' => 'Gérer les documents',
            'manage_partnerships' => 'Gérer partenaires et projets',
            'view_reports' => 'Consulter les statistiques',
            'access_portal' => 'Accéder à l’espace personnel',
        ];

        foreach ($permissions as $name => $label) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $name],
                ['label' => $label, 'created_at' => now(), 'updated_at' => now()],
            );
        }

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('permission_role')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
            ]);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@jca.local'],
            [
                'name' => 'Administrateur JCA',
                'password' => 'password',
                'role' => 'admin',
                'status' => 'active',
            ],
        );

        $client = User::updateOrCreate(
            ['email' => 'client@jca.local'],
            [
                'name' => 'Client Demo',
                'password' => 'password',
                'role' => 'client',
                'status' => 'active',
            ],
        );

        foreach ([$admin, $client] as $user) {
            $roleId = DB::table('roles')->where('name', $user->role)->value('id');
            DB::table('role_user')->updateOrInsert(['role_id' => $roleId, 'user_id' => $user->id]);
            DB::table('user_profiles')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'account_type' => $user->role === 'admin' ? 'admin' : 'client',
                    'type_client' => $user->role === 'admin' ? 'Institution' : 'Particulier',
                    'preferred_language' => 'fr',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }

        $categories = [
            ['name' => 'Immigration', 'slug' => 'immigration', 'summary' => 'Dossiers, visas, résidence, permis et citoyenneté.', 'sort_order' => 1],
            ['name' => 'Recrutement international', 'slug' => 'recrutement-international', 'summary' => 'Talents, employeurs, offres et intégration.', 'sort_order' => 2],
            ['name' => 'Coopération internationale', 'slug' => 'cooperation-internationale', 'summary' => 'Programmes, partenariats et financement.', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            $record = ServiceCategory::updateOrCreate(['slug' => $category['slug']], $category);

            Service::updateOrCreate(
                ['slug' => $category['slug'].'-accompagnement'],
                [
                    'service_category_id' => $record->id,
                    'title' => $category['name'].' - accompagnement',
                    'summary' => $category['summary'],
                    'body' => 'Service administrable depuis le back-office JCA.',
                    'status' => 'published',
                    'sort_order' => 1,
                ],
            );
        }

        Article::updateOrCreate(
            ['slug' => 'preparer-un-dossier-international-solide'],
            [
                'user_id' => $admin->id,
                'title' => 'Préparer un dossier international solide',
                'excerpt' => 'Les preuves, la cohérence et les délais sont les piliers d’un dossier fiable.',
                'body' => 'Article de démonstration prêt à être édité dans une future interface CRUD.',
                'status' => 'published',
                'published_at' => now(),
            ],
        );

        JobPosting::updateOrCreate(
            ['slug' => 'technicien-maintenance-international'],
            [
                'title' => 'Technicien maintenance international',
                'company_name' => 'Entreprise partenaire',
                'country' => 'Canada',
                'city' => 'Montreal',
                'sector' => 'Industrie',
                'contract_type' => 'Temps plein',
                'description' => 'Offre pilote pour valider le module emplois.',
                'requirements' => 'Expérience technique, rigueur et disponibilité internationale.',
                'status' => 'published',
                'published_at' => now(),
            ],
        );

        Partner::updateOrCreate(
            ['name' => 'Partenaire institutionnel demo'],
            [
                'type' => 'Institution',
                'country' => 'International',
                'summary' => 'Partenaire de démonstration pour le réseau JCA.',
                'is_featured' => true,
            ],
        );

        DB::table('pages')->updateOrInsert(
            ['slug' => 'jca-immigration-recrutement-cooperation'],
            [
                'user_id' => $admin->id,
                'title' => 'JCA - Immigration, recrutement international, coopération et développement international',
                'excerpt' => 'Cabinet international de conseil et d’accompagnement pour la mobilité, les talents et les projets à impact.',
                'body' => 'Page institutionnelle administrable pour le positionnement global de JCA.',
                'status' => 'published',
                'seo_title' => 'JCA | Immigration, recrutement international et coopération',
                'seo_description' => 'Solutions intégrées pour immigration, mobilité internationale, recrutement, coopération et développement durable.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        foreach ([
            ['Immigration', 'JCA garantit-il l’obtention d’un visa?', 'Non. JCA sécurise la stratégie et la qualité du dossier, mais la décision appartient aux autorités compétentes.'],
            ['Recrutement', 'Une entreprise peut-elle publier une offre?', 'Oui. Le portail permet de qualifier le besoin, publier une offre et suivre les candidatures.'],
            ['Coopération', 'JCA accompagne-t-il les projets institutionnels?', 'Oui. JCA intervient en conception, mobilisation de ressources, mise en œuvre, suivi et évaluation.'],
        ] as $index => $faq) {
            DB::table('faqs')->updateOrInsert(
                ['question' => $faq[1]],
                [
                    'category' => $faq[0],
                    'answer' => $faq[2],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }

        DB::table('testimonials')->updateOrInsert(
            ['author_name' => 'Organisation partenaire'],
            [
                'author_role' => 'Partenaire institutionnel',
                'organization' => 'Programme international',
                'locale' => 'fr',
                'quote' => 'JCA apporte une approche structurée, professionnelle et orientée impact.',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('newsletter_subscribers')->updateOrInsert(
            ['email' => 'veille@jca.local'],
            [
                'name' => 'Veille JCA',
                'locale' => 'fr',
                'status' => 'subscribed',
                'subscribed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('immigration_cases')->updateOrInsert(
            ['reference' => 'JCA-IMM-000001'],
            [
                'user_id' => $client->id,
                'program_type' => 'Résidence permanente',
                'destination_country' => 'Canada',
                'status' => 'received',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('appointments')->updateOrInsert(
            ['user_id' => $client->id, 'topic' => 'Consultation stratégique'],
            [
                'starts_at' => now()->addWeek(),
                'duration_minutes' => 45,
                'channel' => 'online',
                'status' => 'requested',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('cooperation_projects')->updateOrInsert(
            ['slug' => 'renforcement-capacites-demo'],
            [
                'title' => 'Renforcement des capacités institutionnelles',
                'country' => 'International',
                'sector' => 'Gouvernance',
                'status' => 'draft',
                'description' => 'Projet pilote pour le module coopération internationale.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('humanitarian_programs')->updateOrInsert(
            ['slug' => 'inclusion-femmes-jeunes-demo'],
            [
                'title' => 'Inclusion des femmes et des jeunes',
                'country' => 'International',
                'focus_area' => 'Inclusion sociale',
                'status' => 'draft',
                'description' => 'Programme pilote pour le module action humanitaire et développement durable.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('activity_logs')->insert([
            'user_id' => $admin->id,
            'action' => 'platform_seeded',
            'properties' => json_encode(['scope' => 'roles_permissions_modules']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
