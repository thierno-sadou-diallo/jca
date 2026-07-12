<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LeadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LeadRequestController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:2', 'max:120'],
                'email' => ['required', 'email:rfc', 'max:160'],
                'phone' => ['nullable', 'string', 'max:40'],
                'topic' => [
                    'required',
                    'string',
                    Rule::in([
                        'Immigration',
                        'Recrutement international',
                        'Cooperation internationale',
                        'Partenariat',
                        'Consultation strategique',
                    ]),
                ],
                'message' => ['required', 'string', 'min:20', 'max:3000'],
                'source' => ['nullable', 'string', 'max:60'],
                'page_slug' => ['nullable', 'string', 'max:80'],
                'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
                'preferred_channel' => ['nullable', 'string', Rule::in(['Email', 'WhatsApp', 'Telephone'])],
                'documents' => ['nullable', 'array', 'max:5'],
                'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:8192'],
                'website' => ['prohibited'],
            ],
            [
                'name.required' => 'Veuillez indiquer votre nom complet.',
                'name.min' => 'Le nom complet doit contenir au moins :min caracteres.',
                'name.max' => 'Le nom complet ne doit pas depasser :max caracteres.',
                'email.required' => 'Veuillez indiquer votre adresse email.',
                'email.email' => 'Veuillez indiquer une adresse email valide.',
                'email.max' => 'L adresse email ne doit pas depasser :max caracteres.',
                'phone.max' => 'Le telephone ne doit pas depasser :max caracteres.',
                'topic.required' => 'Veuillez choisir un motif de demande.',
                'topic.in' => 'Le motif choisi n est pas valide.',
                'message.required' => 'Veuillez decrire votre demande.',
                'message.min' => 'Le message doit contenir au moins :min caracteres pour nous aider a comprendre votre besoin.',
                'message.max' => 'Le message ne doit pas depasser :max caracteres.',
                'preferred_date.date' => 'La date souhaitee n est pas valide.',
                'preferred_date.after_or_equal' => 'La date souhaitee doit etre aujourd hui ou une date future.',
                'preferred_channel.in' => 'Le canal prefere choisi n est pas valide.',
                'documents.max' => 'Vous pouvez joindre au maximum :max documents.',
                'documents.*.mimes' => 'Formats acceptes: PDF, JPG, PNG, DOC et DOCX.',
                'documents.*.max' => 'Chaque document ne doit pas depasser 8 Mo.',
                'website.prohibited' => 'Votre demande n a pas pu etre envoyee.',
            ],
        );

        $lead = LeadRequest::create([
            ...$validated,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'payload' => [
                'locale' => app()->getLocale(),
                'submitted_at' => now()->toIso8601String(),
            ],
        ]);

        foreach ($request->file('documents', []) as $file) {
            $path = Storage::disk('local')->putFile('lead-documents/'.$lead->id, $file);

            Document::create([
                'lead_request_id' => $lead->id,
                'title' => $file->getClientOriginalName(),
                'type' => $file->getClientOriginalExtension(),
                'path' => $path,
                'visibility' => 'private',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Votre demande a ete recue. JCA vous recontactera rapidement avec les prochaines etapes.',
                'reference' => 'JCA-'.str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT),
            ], 201);
        }

        return back()->with('lead_success', 'Votre demande a ete recue. JCA vous recontactera rapidement.');
    }
}
