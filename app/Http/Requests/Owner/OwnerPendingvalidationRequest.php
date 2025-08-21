<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerPendingvalidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autoriser uniquement les utilisateurs connectés (optionnel)
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'nif' => 'required|string|max:20',
            'stat' => 'required|string|max:20',
            'adresse' => 'required|string|max:100',
            'contact' => 'required|string|max:20',
            'document' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // 2 Mo
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:public,prive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est requis.',
            'name.max' => 'Le nom ne doit pas dépasser 100 caractères.',
            'nif.required' => 'Le NIF est requis.',
            'stat.required' => 'Le Stat est requis.',
            'adresse.required' => 'L’adresse est requise.',
            'contact.required' => 'Le contact est requis.',
            'document.mimes' => 'Le document doit être un fichier PDF, JPG ou PNG.',
            'document.max' => 'Le document ne doit pas dépasser 2 Mo.',
            'category_id.required' => 'La catégorie est requise.',
            'category_id.exists' => 'La catégorie sélectionnée est invalide.',
            'type.required' => 'Le type d’établissement est requis.',
            'type.in' => 'Le type doit être "public" ou "prive".',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name ? trim($this->name) : null,
            'nif' => $this->nif ? trim($this->nif) : null,
            'stat' => $this->stat ? trim($this->stat) : null,
            'adresse' => $this->adresse ? trim($this->adresse) : null,
            'contact' => $this->contact ? trim($this->contact) : null,
            'type' => $this->type ? trim($this->type) : 'public',
        ]);
    }
}
