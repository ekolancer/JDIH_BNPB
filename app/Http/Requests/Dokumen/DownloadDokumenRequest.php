<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class DownloadDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9-]+$/'],
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->all(), [
            'slug' => $this->route('slug'),
        ]);
    }
}
