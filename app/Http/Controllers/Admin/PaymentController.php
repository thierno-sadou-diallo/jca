<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        return view('admin.payments.index', [
            'payments' => Payment::query()
                ->with(['user', 'appointment'])
                ->when($status, fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'status' => $status,
            'statuses' => Payment::statuses(),
        ]);
    }

    public function create(): View
    {
        return view('admin.payments.create', [
            'payment' => new Payment([
                'reference' => $this->newReference(),
                'currency' => 'CAD',
                'status' => Payment::STATUS_PENDING,
            ]),
            'clients' => $this->clients(),
            'appointments' => collect(),
            'statuses' => Payment::statuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['reference'] = $validated['reference'] ?: $this->newReference();
        $validated['payload'] = $this->payload($validated);

        unset($validated['note'], $validated['payment_url']);

        $payment = Payment::create($validated);

        return redirect()->route('admin.payments.edit', $payment)->with('status', 'Paiement créé.');
    }

    public function edit(Payment $payment): View
    {
        return view('admin.payments.edit', [
            'payment' => $payment->load(['user', 'appointment']),
            'clients' => $this->clients(),
            'appointments' => Appointment::where('user_id', $payment->user_id)->latest()->limit(20)->get(),
            'statuses' => Payment::statuses(),
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $this->validated($request, $payment);
        $validated['reference'] = $validated['reference'] ?: $payment->reference;
        $validated['payload'] = $this->payload($validated);

        unset($validated['note'], $validated['payment_url']);

        $payment->update($validated);

        return back()->with('status', 'Paiement mis a jour.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Payment $payment = null): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'reference' => ['nullable', 'string', 'max:80', Rule::unique('payments', 'reference')->ignore($payment?->id)],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'currency' => ['required', 'string', 'size:3'],
            'provider' => ['nullable', 'string', 'max:60'],
            'status' => ['required', Rule::in(array_keys(Payment::statuses()))],
            'note' => ['nullable', 'string', 'max:1000'],
            'payment_url' => ['nullable', 'url', 'max:500'],
        ]);
    }

    /**
     * @param array<string, mixed> $validated
     * @return array<string, string|null>
     */
    private function payload(array $validated): array
    {
        return [
            'note' => $validated['note'] ?? null,
            'payment_url' => $validated['payment_url'] ?? null,
        ];
    }

    private function newReference(): string
    {
        do {
            $reference = 'JCA-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Payment::where('reference', $reference)->exists());

        return $reference;
    }

    private function clients()
    {
        return User::where('role', 'client')->orderBy('name')->get(['id', 'name', 'email']);
    }
}
