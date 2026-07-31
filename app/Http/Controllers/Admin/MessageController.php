<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Notifications\PortalStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $clientIds = Message::query()
            ->where(function ($query): void {
                $query->where('sender_id', auth()->id())
                    ->orWhere('recipient_id', auth()->id());
            })
            ->get(['sender_id', 'recipient_id'])
            ->flatMap(fn (Message $message): array => [$message->sender_id, $message->recipient_id])
            ->filter(fn (?int $id): bool => $id !== null && $id !== auth()->id())
            ->unique()
            ->values();

        $clients = User::query()
            ->where('role', 'client')
            ->where(function ($query) use ($clientIds): void {
                if ($clientIds->isNotEmpty()) {
                    $query->whereIn('id', $clientIds);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->with('profile')
            ->orderBy('name')
            ->get()
            ->map(function (User $client): User {
                $client->unread_messages_count = Message::where('sender_id', $client->id)
                    ->where('recipient_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();
                $client->latest_message = Message::where(function ($query) use ($client): void {
                    $query->where('sender_id', auth()->id())->where('recipient_id', $client->id);
                })->orWhere(function ($query) use ($client): void {
                    $query->where('sender_id', $client->id)->where('recipient_id', auth()->id());
                })->latest()->first();

                return $client;
            });

        return view('admin.messages.index', [
            'clients' => $clients,
            'allClients' => User::where('role', 'client')->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function show(User $client): View
    {
        abort_unless($client->role === 'client', 404);

        Message::where('sender_id', $client->id)
            ->where('recipient_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.messages.show', [
            'client' => $client->load('profile'),
            'messages' => $this->conversationWith($client)->get(),
        ]);
    }

    public function store(Request $request, User $client): RedirectResponse
    {
        abort_unless($client->role === 'client', 404);

        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:160'],
            'body' => ['required', 'string', 'min:5', 'max:5000'],
        ]);

        Message::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $client->id,
            'subject' => $validated['subject'] ?: 'Reponse JCA',
            'body' => $validated['body'],
        ]);

        $client->notify(new PortalStatusNotification(
            'Nouveau message JCA',
            'Vous avez recu un nouveau message dans votre espace client.',
            'message',
            route('portal.dashboard'),
        ));

        return back()->with('message_status', 'Message envoyé au client.');
    }

    private function conversationWith(User $client)
    {
        return Message::where(function ($query) use ($client): void {
            $query->where('sender_id', auth()->id())->where('recipient_id', $client->id);
        })->orWhere(function ($query) use ($client): void {
            $query->where('sender_id', $client->id)->where('recipient_id', auth()->id());
        })->oldest();
    }
}
