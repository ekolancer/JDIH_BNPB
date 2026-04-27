<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetadataApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'        => ['nullable', 'integer', 'min:1'],
            'tipeDok'     => ['nullable', 'string', 'in:Peraturan,Monografi,Artikel,Putusan'],
            'tahun'       => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'judul'       => ['nullable', 'string', 'max:255'],
            'subjek'      => ['nullable', 'string', 'max:255'],
            'bidangHukum' => ['nullable', 'string', 'max:255'],
            'sort_by'     => ['nullable', 'string', 'in:id,tahun,judul,created_at'],
            'sort_order'  => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
