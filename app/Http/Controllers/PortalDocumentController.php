<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LeadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortalDocumentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'type' => ['required', 'string', 'max:80'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:8192'],
        ], [
            'document.required' => 'Veuillez choisir un document.',
            'document.mimes' => 'Formats acceptes: PDF, JPG, PNG, DOC et DOCX.',
            'document.max' => 'Le document ne doit pas depasser 8 Mo.',
        ]);

        $path = Storage::disk('local')->putFile(
            'portal-documents/'.$request->user()->id,
            $validated['document'],
        );

        $lead = LeadRequest::create([
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'phone' => $request->user()->phone,
            'topic' => 'Depot de document',
            'message' => 'Le client a depose un document depuis son espace personnel: '.$validated['title'].'.',
            'source' => 'portal',
            'page_slug' => 'espace',
            'preferred_channel' => 'Email',
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'payload' => [
                'document_title' => $validated['title'],
                'document_type' => $validated['type'],
                'submitted_at' => now()->toIso8601String(),
            ],
        ]);

        Document::create([
            'user_id' => $request->user()->id,
            'lead_request_id' => $lead->id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'path' => $path,
            'visibility' => 'private',
        ]);

        return back()->with('document_status', 'Document depose avec succes.');
    }
}
