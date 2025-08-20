<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerSchoolRequest extends FormRequest
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
            'name'        => 'required|string|max:100',
            'nif'         => 'required|string|max:20',
            'stat'        => 'required|string|max:20',
            'adresse'     => 'required|string|max:100',
            'contact'     => 'nullable|string|max:100',
            'category_id' => 'required|integer|exists:categories,id',
            'type' => ['required', 'in:public,prive'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Le nom est requis.',
            'name.max'             => 'Le nom ne doit pas dépasser 30 caractères.',
            'nif.required'         => 'Le NIF est requis.',
            'stat.required'        => 'Le Stat est requis.',
            'adresse.required'     => 'L\'adresse est requise.',
            'category_id.required' => 'La catégorie est requise.',
            'category_id.exists'   => 'La catégorie sélectionnée est invalide.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name'        => $this->name ? trim($this->name) : null,
            'nif'         => $this->nif ? trim($this->nif) : null,
            'stat'        => $this->stat ? trim($this->stat) : null,
            'adresse'     => $this->adresse ? trim($this->adresse) : null,
            'contact'     => $this->contact ? trim($this->contact) : null,
            'category_id' => $this->category_id ? (int) $this->category_id : null,
            'type'        => $this->type ? trim($this->type) : null,
            'type'        => $this->type ? trim($this->type) : null,
        ]);
    }
}
