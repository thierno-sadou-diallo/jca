<?php

namespace Tests\Feature;

use App\Models\LeadRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LeadRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_lead_request_is_stored(): void
    {
        $response = $this->postJson('/demandes', [
            'name' => 'Marie Diallo',
            'email' => 'marie@example.com',
            'phone' => '+1 514 000 0000',
            'topic' => 'Immigration',
            'message' => 'Je souhaite evaluer mes options pour un projet de residence permanente au Canada.',
            'source' => 'public_form',
            'page_slug' => 'consultation',
            'preferred_channel' => 'Email',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('reference', 'JCA-000001');

        $this->assertDatabaseHas('lead_requests', [
            'name' => 'Marie Diallo',
            'email' => 'marie@example.com',
            'topic' => 'Immigration',
            'status' => 'new',
        ]);
    }

    public function test_invalid_lead_request_returns_validation_errors(): void
    {
        $response = $this->postJson('/demandes', [
            'name' => 'A',
            'email' => 'not-an-email',
            'topic' => 'Autre',
            'message' => 'Trop court',
            'website' => 'spam',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'topic', 'message', 'website'])
            ->assertJsonMissing(['message' => 'validation.min.string']);

        $this->assertSame(0, LeadRequest::count());
    }

    public function test_lead_request_can_include_documents(): void
    {
        Storage::fake('local');

        $response = $this->postJson('/demandes', [
            'name' => 'Jean Client',
            'email' => 'jean@example.com',
            'topic' => 'Immigration',
            'message' => 'Je souhaite deposer une demande avec mes documents pour analyse.',
            'documents' => [
                UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
            ],
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('documents', [
            'title' => 'cv.pdf',
            'visibility' => 'private',
        ]);
    }

    public function test_public_consultation_form_values_match_validation_rules(): void
    {
        $response = $this->postJson('/demandes', [
            'name' => 'Awa Partenaire',
            'email' => 'awa@example.com',
            'phone' => '+221 77 000 00 00',
            'topic' => 'Coopération internationale',
            'message' => 'Je souhaite proposer une collaboration institutionnelle avec JCA.',
            'source' => 'consultation',
            'page_slug' => 'consultation',
            'preferred_channel' => 'Téléphone',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('lead_requests', [
            'email' => 'awa@example.com',
            'topic' => 'Coopération internationale',
            'preferred_channel' => 'Téléphone',
        ]);
    }
}
