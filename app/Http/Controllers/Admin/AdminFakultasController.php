<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminFakultasController extends Controller
{
    public function index()
    {
        $items = Fakultas::query()->orderBy('nama')->get();

        return view('admin.fakultas.index', compact('items'));
    }

    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['nama']);

        Fakultas::create($data);

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas)
    {
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $data = $this->validateData($request, $fakultas->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['nama']);

        $fakultas->update($data);

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas)
    {
        $fakultas->delete();
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:fakultas,kode,' . ($id ?? 'NULL') . ',id'],
            'nama' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:fakultas,slug,' . ($id ?? 'NULL') . ',id'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}

