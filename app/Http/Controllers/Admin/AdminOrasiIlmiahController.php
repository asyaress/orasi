<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrasiIlmiah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrasiIlmiahController extends Controller
{
    public function index()
    {
        $items = OrasiIlmiah::query()
            ->withCount('guruBesars')
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view('admin.orasi-ilmiah.index', compact('items'));
    }

    public function create()
    {
        return view('admin.orasi-ilmiah.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateOrasi($request);

        $orasi = new OrasiIlmiah();
        $orasi->fill($data);
        $this->handleUploads($request, $orasi);
        $orasi->save();

        return redirect()->route('admin.orasi-ilmiah.index')->with('success', 'Orasi ilmiah berhasil ditambahkan.');
    }

    public function show(OrasiIlmiah $orasiIlmiah)
    {
        $orasiIlmiah->load(['guruBesars.fakultas', 'guruBesars.prodi']);

        $guruBesarTersedia = \App\Models\GuruBesar::query()
            ->belumDitugaskan()
            ->orderBy('nama')
            ->get();

        return view('admin.orasi-ilmiah.show', [
            'orasi' => $orasiIlmiah,
            'guruBesarTersedia' => $guruBesarTersedia,
        ]);
    }

    public function edit(OrasiIlmiah $orasiIlmiah)
    {
        return view('admin.orasi-ilmiah.edit', [
            'orasi' => $orasiIlmiah,
        ]);
    }

    public function update(Request $request, OrasiIlmiah $orasiIlmiah)
    {
        $data = $this->validateOrasi($request, $orasiIlmiah);

        $orasiIlmiah->fill($data);
        $this->handleUploads($request, $orasiIlmiah);
        $orasiIlmiah->save();

        return redirect()->route('admin.orasi-ilmiah.index')->with('success', 'Orasi ilmiah berhasil diperbarui.');
    }

    private function validateOrasi(Request $request, ?OrasiIlmiah $orasi = null): array
    {
        $data = $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('orasi_ilmiahs', 'tahun')->ignore($orasi?->id),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_pelaksanaan' => ['required', 'date'],
            'jenis' => ['required', 'in:Luring,Daring'],
            'pendaftaran_mulai' => ['nullable', 'date'],
            'pendaftaran_selesai' => ['nullable', 'date', 'after_or_equal:pendaftaran_mulai'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        return $data;
    }

    private function handleUploads(Request $request, OrasiIlmiah $orasi): void
    {
        $disk = 'public';

        $map = [
            'banner' => 'banner_path',
        ];

        foreach ($map as $input => $attr) {
            if ($request->hasFile($input)) {
                $path = $request->file($input)->store('orasi-ilmiah', $disk);
                $orasi->{$attr} = $path;
            }
        }
    }
}

