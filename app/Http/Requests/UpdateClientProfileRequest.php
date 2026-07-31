<?php

namespace App\Http\Requests;

use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'type_client' => ['required', Rule::in(UserProfile::clientTypes())],
            'country' => ['nullable', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:120'],
            'organization_name' => ['nullable', 'string', 'max:160'],
            'preferred_language' => ['required', Rule::in(['fr', 'en'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Veuillez indiquer votre nom complet ou votre organisation.',
            'type_client.required' => 'Veuillez choisir votre type de client.',
            'type_client.in' => 'Le type de client selectionne est invalide.',
            'profile_photo.image' => 'La photo doit être une image valide.',
            'profile_photo.max' => 'La photo ne doit pas depasser 2 Mo.',
            'preferred_language.in' => 'La langue préférée doit être FR ou EN.',
        ];
    }
}
