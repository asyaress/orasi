<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorDevice;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminSecurityController extends Controller
{
    public function index(Request $request, TwoFactorService $twofa)
    {
        $user = $request->user();

        $devices = TwoFactorDevice::query()
            ->where('user_id', $user->id)
            ->orderByDesc('confirmed_at')
            ->orderByDesc('id')
            ->get();

        // Prepare new device secret in session (for add device flow)
        $secret = $request->session()->get('2fa:add-device:secret');
        if (!$secret) {
            $secret = $twofa->generateSecret();
            $request->session()->put('2fa:add-device:secret', $secret);
        }

        $qr = $twofa->makeQrPngDataUri($user, $secret);

        return view('admin.security.index', [
            'devices' => $devices,
            'qr' => $qr,
            'secret' => $secret,
        ]);
    }

    public function addDevice(Request $request, TwoFactorService $twofa)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:50'],
            'code' => ['required', 'string', 'min:6', 'max:10'],
        ]);

        $secret = (string) $request->session()->get('2fa:add-device:secret', '');
        if ($secret === '') {
            throw ValidationException::withMessages([
                'code' => 'Session setup device tidak ditemukan. Refresh halaman.',
            ]);
        }

        if (!$twofa->verifyCode($secret, $data['code'])) {
            throw ValidationException::withMessages([
                'code' => 'Kode Authenticator tidak valid.',
            ]);
        }

        TwoFactorDevice::create([
            'user_id' => $user->id,
            'name' => $data['name'] ?: 'Authenticator',
            'secret' => $twofa->encryptSecret($secret),
            'confirmed_at' => now(),
        ]);

        // generate next secret for another device
        $request->session()->forget('2fa:add-device:secret');

        return redirect()->route('admin.security.index')->with('success', 'Device Authenticator berhasil ditambahkan.');
    }

    public function removeDevice(Request $request, TwoFactorDevice $device)
    {
        $user = $request->user();
        if ($device->user_id !== $user->id) {
            abort(403);
        }

        $device->delete();

        // If removing last device, force logout and setup again next time.
        $hasAny = TwoFactorDevice::query()
            ->where('user_id', $user->id)
            ->whereNotNull('confirmed_at')
            ->exists();

        if (!$hasAny) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Semua device 2FA dihapus. Silakan setup ulang 2FA.');
        }

        return redirect()->route('admin.security.index')->with('success', 'Device berhasil dihapus.');
    }
}

