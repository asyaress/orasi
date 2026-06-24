<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RecaptchaService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login', [
            'recaptchaEnabled' => (bool) config('services.recaptcha.enabled', true),
            'recaptchaSiteKey' => (string) config('services.recaptcha.site_key'),
        ]);
    }

    public function store(Request $request, RecaptchaService $recaptcha, TwoFactorService $twofa)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => [(bool) config('services.recaptcha.enabled', true) ? 'required' : 'nullable', 'string'],
        ]);

        if (!$recaptcha->verify($credentials['g-recaptcha-response'] ?? null, $request->ip())) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'reCAPTCHA tidak valid. Coba lagi.',
            ]);
        }

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], (bool) $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        // Force admin only
        if (!$user->is_admin) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak memiliki akses admin.',
            ]);
        }

        // If 2FA not configured yet, redirect to setup
        if (!$user->two_factor_confirmed_at) {
            return redirect()->route('two-factor.setup');
        }

        // Secrets encrypted with a different APP_KEY cannot be decrypted — force re-setup.
        if (!$twofa->hasAnyDecryptableDevice($user)) {
            $twofa->resetForUser($user);

            return redirect()->route('two-factor.setup')
                ->with('warning', 'Perangkat 2FA perlu didaftarkan ulang karena kunci aplikasi berubah.');
        }

        // 2FA challenge required after password login
        $request->session()->put('2fa:user:id', $user->id);
        Auth::logout();

        return redirect()->route('two-factor.challenge');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

