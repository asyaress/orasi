<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProdiController extends Controller
{
    public function index()
    {
        $items = Prodi::query()->with('fakultas')->orderBy('nama')->get();
        $fakultas = Fakultas::query()->orderBy('nama')->get();

        return view('admin.prodi.index', compact('items'));
    }

    public function create()
    {
        return view('admin.prodi.create', [
            'fakultas' => Fakultas::query()->where('is_active', true)->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['nama']);

        Prodi::create($data);

        return redirect()->route('admin.prodi.index')->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        return view('admin.prodi.edit', [
            'prodi' => $prodi,
            'fakultas' => Fakultas::query()->where('is_active', true)->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Prodi $prodi)
    {
        $data = $this->validateData($request, $prodi->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['nama']);

        $prodi->update($data);

        return redirect()->route('admin.prodi.index')->with('success', 'Prodi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return redirect()->route('admin.prodi.index')->with('success', 'Prodi berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'fakultas_id' => ['required', 'integer', 'exists:fakultas,id'],
            'kode' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:prodis,slug,' . ($id ?? 'NULL') . ',id'],
            'jenjang' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}

