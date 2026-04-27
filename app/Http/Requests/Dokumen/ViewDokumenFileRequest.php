<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class ViewDokumenFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dokumen' => ['required'],
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->all(), [
            'dokumen' => $this->route('dokumen'),
        ]);
    }
}
