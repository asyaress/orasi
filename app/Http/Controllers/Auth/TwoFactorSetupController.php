<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorDevice;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorSetupController extends Controller
{
    public function show(Request $request, TwoFactorService $twofa)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $hasDevice = $user->twoFactorDevices()->whereNotNull('confirmed_at')->exists();
        if ($hasDevice) {
            return redirect()->route('admin.dashboard');
        }

        // generate secret for this session if not existing
        $secret = $request->session()->get('2fa:setup:secret');
        if (!$secret) {
            $secret = $twofa->generateSecret();
            $request->session()->put('2fa:setup:secret', $secret);

            // Generate recovery codes once (if user doesn't have it yet)
            if (!$user->two_factor_recovery_codes) {
                $recovery = $twofa->generateRecoveryCodes();
                $request->session()->put('2fa:setup:recovery', $recovery);
            }
        }

        $qr = $twofa->makeQrPngDataUri($user, $secret);

        return view('auth.two-factor-setup', [
            'qr' => $qr,
            'secret' => $secret,
            'recovery' => (array) $request->session()->get('2fa:setup:recovery', []),
        ]);
    }

    public function confirm(Request $request, TwoFactorService $twofa)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $hasDevice = $user->twoFactorDevices()->whereNotNull('confirmed_at')->exists();
        if ($hasDevice) {
            return redirect()->route('admin.dashboard');
        }

        $data = $request->validate([
            'code' => ['required', 'string', 'min:6', 'max:10'],
        ]);

        $secret = (string) $request->session()->get('2fa:setup:secret', '');
        if ($secret === '') {
            throw ValidationException::withMessages([
                'code' => 'Setup session tidak ditemukan. Refresh halaman setup.',
            ]);
        }

        if (!$twofa->verifyCode($secret, $data['code'])) {
            throw ValidationException::withMessages([
                'code' => 'Kode 2FA tidak valid.',
            ]);
        }

        // Store recovery codes only if not set yet
        if (!$user->two_factor_recovery_codes) {
            $recovery = (array) $request->session()->get('2fa:setup:recovery', []);
            $user->two_factor_recovery_codes = json_encode($twofa->hashRecoveryCodes($recovery));
        }
        $user->two_factor_confirmed_at = $user->two_factor_confirmed_at ?: now(); // legacy, keep filled
        $user->save();

        TwoFactorDevice::create([
            'user_id' => $user->id,
            'name' => 'Authenticator',
            'secret' => $twofa->encryptSecret($secret),
            'confirmed_at' => now(),
        ]);

        // clear setup session and force re-login with OTP
        $request->session()->forget(['2fa:setup:secret', '2fa:setup:recovery']);
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', '2FA berhasil disetup. Silakan login ulang dan masukkan kode Authenticator.');
    }
}

