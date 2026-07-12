<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();
        $role = $request->string('role')->toString();
        $status = $request->string('status')->toString();

        return view('admin.users.index', [
            'users' => User::query()
                ->with('profile')
                ->when($query, function ($builder) use ($query): void {
                    $builder->where(function ($nested) use ($query): void {
                        $nested->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('phone', 'like', "%{$query}%");
                    });
                })
                ->when($role, fn ($builder) => $builder->where('role', $role))
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'query' => $query,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'managedUser' => $user->load('profile'),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'role' => ['required', Rule::in(['admin', 'client'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        if ($user->is($request->user()) && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Vous ne pouvez pas retirer votre propre role administrateur.']);
        }

        if ($user->is($request->user()) && $validated['status'] !== 'active') {
            return back()->withErrors(['status' => 'Vous ne pouvez pas desactiver votre propre compte.']);
        }

        $user->update($validated);

        return back()->with('status', 'Utilisateur mis a jour.');
    }
}
