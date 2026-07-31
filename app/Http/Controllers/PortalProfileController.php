<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateClientProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PortalProfileController extends Controller
{
    public function update(UpdateClientProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $profilePhotoPath = $user->profile_photo_path;

        if ($request->hasFile('profile_photo')) {
            if ($profilePhotoPath) {
                Storage::disk('public')->delete($profilePhotoPath);
            }

            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'profile_photo_path' => $profilePhotoPath,
            'role' => 'client',
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'account_type' => 'client',
                'type_client' => $validated['type_client'],
                'country' => $validated['country'] ?? null,
                'city' => $validated['city'] ?? null,
                'organization_name' => $validated['organization_name'] ?? null,
                'preferred_language' => $validated['preferred_language'],
            ],
        );

        return back()->with('profile_status', 'Votre profil client a été mis à jour.');
    }
}
