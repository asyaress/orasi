<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminOrasiGuruBesarController extends Controller
{
    public function attach(Request $request, OrasiIlmiah $orasiIlmiah): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'guru_besar_id' => ['required_without:guru_besar_ids', 'nullable', 'integer', 'exists:guru_besars,id'],
            'guru_besar_ids' => ['required_without:guru_besar_id', 'nullable', 'array', 'min:1'],
            'guru_besar_ids.*' => ['integer', 'exists:guru_besars,id'],
        ]);

        $ids = collect($data['guru_besar_ids'] ?? [])
            ->when($request->filled('guru_besar_id'), fn ($c) => $c->push((int) $data['guru_besar_id']))
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            throw ValidationException::withMessages([
                'guru_besar_id' => 'Pilih minimal satu guru besar.',
            ]);
        }

        $attached = [];
        $skipped = [];
        $errors = [];

        foreach ($ids as $id) {
            $result = $this->assignGuruToOrasi((int) $id, $orasiIlmiah);
            if ($result['status'] === 'attached') {
                $attached[] = $result['guru'];
            } elseif ($result['status'] === 'skipped') {
                $skipped[] = $result['message'];
            } else {
                $errors[] = $result['message'];
            }
        }

        $message = $this->buildAttachMessage($orasiIlmiah, $attached, $skipped, $errors);
        $success = count($attached) > 0;

        if ($request->wantsJson()) {
            $guru = $attached[0] ?? null;

            return response()->json([
                'success' => $success,
                'message' => $message,
                'guru' => $guru ? $this->guruPayload($guru, $orasiIlmiah) : null,
                'count' => count($attached),
                'skipped' => $skipped,
                'errors' => $errors,
            ], $success ? 200 : 422);
        }

        return redirect()
            ->route('admin.orasi-ilmiah.show', $orasiIlmiah)
            ->with($success ? 'success' : (count($errors) > 0 ? 'error' : 'warning'), $message);
    }

    public function detach(Request $request, OrasiIlmiah $orasiIlmiah, GuruBesar $guruBesar): JsonResponse|RedirectResponse
    {
        if ((int) $guruBesar->orasi_ilmiah_id !== (int) $orasiIlmiah->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru besar tidak terdaftar di orasi ini.',
                ], 404);
            }
            abort(404);
        }

        $nama = $guruBesar->nama;
        $guruBesar->orasi_ilmiah_id = null;
        $guruBesar->save();
        $guruBesar->load(['fakultas', 'prodi']);

        $message = "{$nama} dilepas dari Orasi {$orasiIlmiah->tahun} (data master tetap ada).";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'guru' => $this->guruPayload($guruBesar, $orasiIlmiah),
            ]);
        }

        return redirect()
            ->route('admin.orasi-ilmiah.show', $orasiIlmiah)
            ->with('success', $message);
    }

    /** @return array{status: string, guru?: GuruBesar, message?: string} */
    private function assignGuruToOrasi(int $guruId, OrasiIlmiah $orasiIlmiah): array
    {
        $guru = GuruBesar::query()->with(['fakultas', 'prodi'])->find($guruId);

        if (! $guru) {
            return ['status' => 'error', 'message' => "Guru besar #{$guruId} tidak ditemukan."];
        }

        if ($guru->orasi_ilmiah_id && (int) $guru->orasi_ilmiah_id !== (int) $orasiIlmiah->id) {
            $tahunLain = OrasiIlmiah::query()->find($guru->orasi_ilmiah_id)?->tahun;

            return [
                'status' => 'error',
                'message' => "{$guru->nama} sudah terdaftar di Orasi ".($tahunLain ?: 'lain').'. Lepas dulu dari orasi tersebut.',
            ];
        }

        if ((int) $guru->orasi_ilmiah_id === (int) $orasiIlmiah->id) {
            return ['status' => 'skipped', 'message' => "{$guru->nama} sudah ada di orasi ini."];
        }

        $guru->orasi_ilmiah_id = $orasiIlmiah->id;
        $guru->save();

        return ['status' => 'attached', 'guru' => $guru->fresh(['fakultas', 'prodi'])];
    }

    private function buildAttachMessage(OrasiIlmiah $orasi, array $attached, array $skipped, array $errors): string
    {
        $parts = [];

        if (count($attached) > 0) {
            $parts[] = count($attached) === 1
                ? "{$attached[0]->nama} berhasil ditugaskan ke Orasi {$orasi->tahun}."
                : count($attached).' guru besar berhasil ditugaskan.';
        }

        if ($skipped !== []) {
            $parts[] = implode(' ', $skipped);
        }

        if ($errors !== []) {
            $parts[] = implode(' ', $errors);
        }

        if ($parts === []) {
            return 'Tidak ada perubahan.';
        }

        return implode(' ', $parts);
    }

    private function guruPayload(GuruBesar $guru, OrasiIlmiah $orasi): array
    {
        return [
            'id' => $guru->id,
            'nama' => $guru->nama,
            'html_available' => view('admin.guru-besar._assign-card', [
                'guruBesar' => $guru,
                'list' => 'available',
            ])->render(),
            'html_assigned' => view('admin.guru-besar._assign-card', [
                'guruBesar' => $guru,
                'list' => 'assigned',
            ])->render(),
            'edit_url' => route('admin.guru-besar.edit', $guru),
            'detach_url' => route('admin.orasi-ilmiah.guru-besar.detach', [$orasi, $guru]),
        ];
    }
}
