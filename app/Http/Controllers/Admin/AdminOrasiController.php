<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orasi;
use Illuminate\Http\Request;

class AdminOrasiController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');
        $status = (string) $request->query('status', '');

        $query = Orasi::query()->orderByDesc('tanggal_pelaksanaan');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('pegawai_nama', 'like', "%{$q}%")
                    ->orWhere('fakultas', 'like', "%{$q}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $orasis = $query->paginate(10)->withQueryString();

        return view('admin.orasi.index', [
            'orasis' => $orasis,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('admin.orasi.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateOrasi($request);

        $orasi = new Orasi();
        $orasi->fill($data);

        $this->handleUploads($request, $orasi);
        $orasi->save();

        return redirect()->route('admin.orasi.index')->with('success', 'Orasi berhasil ditambahkan.');
    }

    public function show(Orasi $orasi)
    {
        return view('admin.orasi.show', [
            'orasi' => $orasi,
        ]);
    }

    public function edit(Orasi $orasi)
    {
        return view('admin.orasi.edit', [
            'orasi' => $orasi,
        ]);
    }

    public function update(Request $request, Orasi $orasi)
    {
        $data = $this->validateOrasi($request);

        $orasi->fill($data);
        $this->handleUploads($request, $orasi);
        $orasi->save();

        return redirect()->route('admin.orasi.index')->with('success', 'Orasi berhasil diperbarui.');
    }

    private function validateOrasi(Request $request): array
    {
        return $request->validate([
            'urutan' => ['nullable', 'integer', 'min:1'],
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_pelaksanaan' => ['required', 'date'],
            'jenis' => ['required', 'in:Luring,Daring'],
            'pendaftaran_mulai' => ['nullable', 'date'],
            'pendaftaran_selesai' => ['nullable', 'date', 'after_or_equal:pendaftaran_mulai'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published,archived'],

            // API consume (placeholder) + fallback manual snapshot
            'pegawai_id' => ['nullable', 'string', 'max:64'],
            'pegawai_nama' => ['nullable', 'string', 'max:255'],
            'bidang_ilmu' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'tmt' => ['nullable', 'date'],
        ]);
    }

    private function handleUploads(Request $request, Orasi $orasi): void
    {
        $disk = 'public';

        $map = [
            'banner' => 'banner_path',
            'foto' => 'foto_path',
            'file_orasi' => 'file_orasi_path',
            'ppt' => 'ppt_path',
            'piagam' => 'piagam_path',
        ];

        foreach ($map as $input => $attr) {
            if ($request->hasFile($input)) {
                $path = $request->file($input)->store('orasi', $disk);
                $orasi->{$attr} = $path;
            }
        }
    }
}

