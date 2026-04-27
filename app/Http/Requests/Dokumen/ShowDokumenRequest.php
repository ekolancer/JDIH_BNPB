<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipeDok' => ['required', Rule::in(['Peraturan', 'Monografi', 'Artikel', 'Putusan'])],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9-]+$/'],
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->all(), [
            'tipeDok' => $this->route('tipeDok'),
            'slug' => $this->route('slug'),
        ]);
    }
}
