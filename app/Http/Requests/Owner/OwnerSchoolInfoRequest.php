<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerSchoolInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
            'cover_photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096',
            ],
            // Validation pour date_creation : facultative, format date
            'date_creation' => [
                'nullable',
                'date',
            ],
            // Validation pour nombre_etudiants : entier positif ou nul
            'nombre_etudiants' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'La latitude est obligatoire.',
            'latitude.numeric' => 'La latitude doit être un nombre.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.required' => 'La longitude est obligatoire.',
            'longitude.numeric' => 'La longitude doit être un nombre.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
            'logo.image' => 'Le logo doit être une image.',
            'logo.mimes' => 'Le logo doit être au format : jpeg, png, jpg, webp.',
            'logo.max' => 'Le logo ne doit pas dépasser 2 Mo.',
            'cover_photo.image' => 'La photo de couverture doit être une image.',
            'cover_photo.mimes' => 'La photo de couverture doit être au format : jpeg, png, jpg, webp.',
            'cover_photo.max' => 'La photo de couverture ne doit pas dépasser 4 Mo.',
            'date_creation.date' => 'La date de création doit être une date valide.',
            'nombre_etudiants.integer' => 'Le nombre d\'étudiants doit être un nombre entier.',
            'nombre_etudiants.min' => 'Le nombre d\'étudiants ne peut pas être négatif.',
        ];
    }
}
