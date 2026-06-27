<?php

namespace App\Http\Requests;

use App\Models\Pengumuman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertPengumumanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Pengumuman|null $pengumuman */
        $pengumuman = $this->route('pengumuman');

        return [
            'judul' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pengumumans', 'slug')->ignore($pengumuman?->id),
            ],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_cover' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_pinned' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'slug.unique' => 'Slug sudah digunakan oleh pengumuman lain.',
            'konten.required' => 'Konten pengumuman wajib diisi.',
            'cover.image' => 'Cover harus berupa gambar.',
            'cover.mimes' => 'Cover harus berformat JPG, PNG, atau WebP.',
            'cover.max' => 'Ukuran cover maksimal 5 MB.',
        ];
    }
}
