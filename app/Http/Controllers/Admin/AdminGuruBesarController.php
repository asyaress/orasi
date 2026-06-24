<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class AdminGuruBesarController extends Controller
{
    public function index()
    {
        $items = GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas', 'prodi'])
            ->orderBy('nama')
            ->get();

        return view('admin.guru-besar.index', compact('items'));
    }

    public function create(Request $request)
    {
        return view('admin.guru-besar.create', $this->formData($request));
    }

    public function store(Request $request)
    {
        $data = $this->validateGuruBesar($request);

        $gb = new GuruBesar();
        $gb->fill($data);
        $gb->sumber = $data['sumber'] ?? GuruBesar::SUMBER_MANUAL;

        if ($request->filled('orasi_ilmiah_id')) {
            $this->guardAssignToOrasi($gb, (int) $request->input('orasi_ilmiah_id'));
            $gb->orasi_ilmiah_id = (int) $request->input('orasi_ilmiah_id');
        }

        $this->handleUploads($request, $gb);
        $gb->save();

        return $this->redirectAfterSave($request, 'Guru Besar berhasil ditambahkan ke master data.');
    }

    public function edit(Request $request, GuruBesar $guruBesar)
    {
        return view('admin.guru-besar.edit', array_merge(
            ['guruBesar' => $guruBesar],
            $this->formData($request, $guruBesar)
        ));
    }

    public function update(Request $request, GuruBesar $guruBesar)
    {
        $data = $this->validateGuruBesar($request, $guruBesar);

        if ($request->has('orasi_ilmiah_id')) {
            $orasiId = $request->input('orasi_ilmiah_id') ?: null;
            if ($orasiId) {
                $this->guardAssignToOrasi($guruBesar, (int) $orasiId);
            }
            $guruBesar->orasi_ilmiah_id = $orasiId;
        }

        $guruBesar->fill($data);

        $this->handleUploads($request, $guruBesar);
        $guruBesar->save();

        return $this->redirectAfterSave($request, 'Data Guru Besar berhasil diperbarui.');
    }

    public function destroy(GuruBesar $guruBesar)
    {
        $orasiId = $guruBesar->orasi_ilmiah_id;
        $guruBesar->delete();

        if ($orasiId) {
            return redirect()
                ->route('admin.orasi-ilmiah.show', $orasiId)
                ->with('success', 'Guru Besar dihapus dari master data.');
        }

        return redirect()
            ->route('admin.guru-besar.index')
            ->with('success', 'Guru Besar berhasil dihapus.');
    }

    private function formData(Request $request, ?GuruBesar $guruBesar = null): array
    {
        return [
            'fakultas' => Fakultas::query()->where('is_active', true)->orderBy('nama')->get(),
            'prodis' => Prodi::query()->where('is_active', true)->orderBy('nama')->get(),
            'orasis' => OrasiIlmiah::query()->orderByDesc('tahun')->get(),
            'preselectOrasiId' => $request->query('orasi_ilmiah_id', $guruBesar?->orasi_ilmiah_id),
            'returnToOrasi' => $request->query('return') === 'orasi',
        ];
    }

    private function validateGuruBesar(Request $request, ?GuruBesar $guruBesar = null): array
    {
        $fakultasId = $request->input('fakultas_id');

        return $request->validate([
            'pegawai_id' => [
                'nullable',
                'string',
                'max:64',
                Rule::unique('guru_besars', 'pegawai_id')->ignore($guruBesar?->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'string', Rule::in([
                GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
                GuruBesar::JENIS_KELAMIN_PEREMPUAN,
            ])],
            'bidang_ilmu' => ['nullable', 'string', 'max:255'],
            'judul_orasi' => ['nullable', 'string', 'max:255'],
            'tmt' => ['nullable', 'date'],
            'sumber' => ['nullable', 'in:manual,api'],
            'fakultas_id' => ['nullable', 'integer', 'exists:fakultas,id'],
            'prodi_id' => [
                'nullable',
                'prohibited_without:fakultas_id',
                'integer',
                Rule::exists('prodis', 'id')->where(fn ($query) => $query->where('fakultas_id', $fakultasId)),
            ],
            'fakultas_snapshot' => ['nullable', 'string', 'max:255'],
            'prodi_snapshot' => ['nullable', 'string', 'max:255'],
            'orasi_ilmiah_id' => ['nullable', 'integer', 'exists:orasi_ilmiahs,id'],
            'foto_display_mode' => ['nullable', 'in:svg_bg_photo,png_full_overlay'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'file_orasi' => ['nullable', 'file', 'max:10240'],
            'ppt' => ['nullable', 'file', 'max:20480', 'mimes:ppt,pptx'],
            'piagam' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
            'sertifikat' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
        ]);
    }

    private function handleUploads(Request $request, GuruBesar $guruBesar): void
    {
        $this->storeUploadedFile($request, $guruBesar, 'foto', 'foto_path', 'foto');
        $this->storeUploadedFile($request, $guruBesar, 'file_orasi', 'file_orasi_path', 'naskah-orasi');
        $this->storeUploadedFile($request, $guruBesar, 'ppt', 'ppt_path', 'ppt-orasi');
        $this->storeUploadedFile($request, $guruBesar, 'piagam', 'piagam_path', 'piagam-orasi');
        $this->storeUploadedFile($request, $guruBesar, 'sertifikat', 'sertifikat_path', 'sertifikat-orasi');
    }

    private function storeUploadedFile(Request $request, GuruBesar $guruBesar, string $input, string $attribute, string $baseName): void
    {
        if (! $request->hasFile($input)) {
            return;
        }

        $file = $request->file($input);
        $extension = strtolower($file?->extension() ?: $file?->guessExtension() ?: 'bin');
        $filename = "{$baseName}.{$extension}";

        if ($guruBesar->{$attribute}) {
            Storage::disk('public')->delete($guruBesar->{$attribute});
        }

        $guruBesar->{$attribute} = $file->storeAs($guruBesar->storageFolderPath(), $filename, 'public');
    }

    private function guardAssignToOrasi(GuruBesar $guru, int $orasiIlmiahId): void
    {
        if ($guru->orasi_ilmiah_id && (int) $guru->orasi_ilmiah_id !== $orasiIlmiahId) {
            throw ValidationException::withMessages([
                'orasi_ilmiah_id' => 'Guru Besar ini sudah terdaftar di orasi lain. Lepas dulu dari orasi sebelumnya.',
            ]);
        }
    }

    private function redirectAfterSave(Request $request, string $message)
    {
        if ($request->input('return') === 'orasi' && $request->filled('orasi_ilmiah_id')) {
            return redirect()
                ->route('admin.orasi-ilmiah.show', $request->input('orasi_ilmiah_id'))
                ->with('success', $message);
        }

        return redirect()->route('admin.guru-besar.index')->with('success', $message);
    }
}
