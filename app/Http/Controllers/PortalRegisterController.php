<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortalRegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->filled('type_client') && $request->filled('account_type')) {
            $request->merge([
                'type_client' => $this->legacyAccountTypeToClientType($request->string('account_type')->toString()),
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['required', 'confirmed', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'type_client' => ['required', Rule::in(UserProfile::clientTypes())],
            'country' => ['nullable', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:120'],
            'organization_name' => ['nullable', 'string', 'max:160'],
        ], [
            'name.required' => 'Veuillez indiquer votre nom complet.',
            'email.unique' => 'Un compte existe deja avec cette adresse email.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caracteres.',
            'profile_photo.image' => 'La photo doit etre une image valide.',
            'profile_photo.max' => 'La photo ne doit pas depasser 2 Mo.',
            'type_client.required' => 'Veuillez choisir votre type de client.',
        ]);

        $profilePhotoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('profile-photos', 'public')
            : null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'profile_photo_path' => $profilePhotoPath,
            'password' => $validated['password'],
            'role' => 'client',
            'status' => 'active',
        ]);

        DB::table('user_profiles')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'account_type' => 'client',
                'type_client' => $validated['type_client'],
                'country' => $validated['country'] ?? null,
                'city' => $validated['city'] ?? null,
                'organization_name' => $validated['organization_name'] ?? null,
                'preferred_language' => app()->getLocale(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        $roleId = DB::table('roles')->where('name', 'client')->value('id');
        if ($roleId) {
            DB::table('role_user')->updateOrInsert(['role_id' => $roleId, 'user_id' => $user->id]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('portal.dashboard');
    }

    private function legacyAccountTypeToClientType(string $accountType): string
    {
        return match ($accountType) {
            'candidate' => UserProfile::TYPE_CANDIDAT,
            'company' => UserProfile::TYPE_ENTREPRISE,
            'institution_partner' => UserProfile::TYPE_INSTITUTION,
            default => UserProfile::TYPE_PARTICULIER,
        };
    }
}
