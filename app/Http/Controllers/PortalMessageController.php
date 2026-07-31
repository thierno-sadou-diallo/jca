<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortalMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:160'],
            'body' => ['required', 'string', 'min:5', 'max:5000'],
        ], [
            'body.required' => 'Veuillez ecrire votre message.',
            'body.min' => 'Le message doit contenir au moins :min caracteres.',
        ]);

        $admin = User::where('role', 'admin')
            ->where('status', 'active')
            ->orderBy('id')
            ->first();

        if (! $admin) {
            return back()->withErrors(['body' => 'Aucun administrateur actif ne peut recevoir le message pour le moment.']);
        }

        Message::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $admin->id,
            'subject' => $validated['subject'] ?: 'Message client',
            'body' => $validated['body'],
        ]);

        return back()->with('message_status', 'Votre message a été envoyé à l’équipe JCA.');
    }
}
