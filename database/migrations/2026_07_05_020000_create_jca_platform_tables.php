<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table): void {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('account_type', 40)->default('client')->index();
            $table->string('country', 80)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('preferred_language', 10)->default('fr');
            $table->json('preferences')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->string('current_country', 80)->nullable();
            $table->string('target_country', 80)->nullable();
            $table->string('sector', 120)->nullable()->index();
            $table->string('availability', 80)->nullable();
            $table->string('resume_path')->nullable();
            $table->json('languages')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_experiences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('company')->nullable();
            $table->string('country', 80)->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_educations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('degree');
            $table->string('institution')->nullable();
            $table->string('country', 80)->nullable();
            $table->year('graduation_year')->nullable();
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector', 120)->nullable()->index();
            $table->string('country', 80)->nullable();
            $table->string('website')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('institutions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('type', 80)->nullable()->index();
            $table->string('country', 80)->nullable();
            $table->string('website')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('immigration_cases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('program_type', 120)->index();
            $table->string('destination_country', 80)->nullable()->index();
            $table->string('status', 40)->default('new')->index();
            $table->date('submitted_at')->nullable();
            $table->date('decision_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('case_status_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('immigration_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 40)->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('topic', 120);
            $table->timestamp('starts_at')->nullable()->index();
            $table->unsignedSmallInteger('duration_minutes')->default(45);
            $table->string('channel', 40)->default('online');
            $table->string('status', 40)->default('requested')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('CAD');
            $table->string('provider', 60)->nullable();
            $table->string('status', 40)->default('pending')->index();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('locale', 10)->default('fr');
            $table->string('status', 30)->default('subscribed')->index();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('seo_metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category', 80)->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('author_name');
            $table->string('author_role')->nullable();
            $table->string('organization')->nullable();
            $table->text('quote');
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });

        Schema::create('cooperation_projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('country', 80)->nullable()->index();
            $table->string('sector', 120)->nullable()->index();
            $table->string('status', 40)->default('draft')->index();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->longText('description')->nullable();
            $table->json('indicators')->nullable();
            $table->timestamps();
        });

        Schema::create('humanitarian_programs', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('country', 80)->nullable()->index();
            $table->string('focus_area', 120)->nullable()->index();
            $table->string('status', 40)->default('draft')->index();
            $table->longText('description')->nullable();
            $table->json('impact_metrics')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 120)->index();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        foreach ([
            'activity_logs',
            'humanitarian_programs',
            'cooperation_projects',
            'media_assets',
            'testimonials',
            'faqs',
            'pages',
            'newsletter_subscribers',
            'messages',
            'payments',
            'appointments',
            'case_status_histories',
            'immigration_cases',
            'institutions',
            'companies',
            'candidate_educations',
            'candidate_experiences',
            'candidate_profiles',
            'user_profiles',
            'role_user',
            'permission_role',
            'permissions',
            'roles',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
