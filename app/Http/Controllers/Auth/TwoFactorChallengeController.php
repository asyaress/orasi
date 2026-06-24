<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorDevice;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorChallengeController extends Controller
{
    public function show(Request $request, TwoFactorService $twofa)
    {
        $userId = $request->session()->get('2fa:user:id');
        if (!$userId) {
            return redirect()->route('login');
        }

        /** @var User|null $user */
        $user = User::query()->find($userId);
        if (!$user || !$twofa->hasAnyDecryptableDevice($user)) {
            if ($user) {
                $twofa->resetForUser($user);
            }
            $request->session()->forget('2fa:user:id');

            return redirect()->route('login')
                ->with('warning', 'Perangkat 2FA perlu didaftarkan ulang. Silakan login dan setup 2FA lagi.');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request, TwoFactorService $twofa)
    {
        $userId = $request->session()->get('2fa:user:id');
        if (!$userId) {
            return redirect()->route('login');
        }

        /** @var User|null $user */
        $user = User::query()->find($userId);
        if (!$user) {
            $request->session()->forget('2fa:user:id');
            return redirect()->route('login');
        }

        $devices = TwoFactorDevice::query()
            ->where('user_id', $user->id)
            ->whereNotNull('confirmed_at')
            ->get();

        if ($devices->isEmpty() || !$twofa->hasAnyDecryptableDevice($user)) {
            $twofa->resetForUser($user);
            $request->session()->forget('2fa:user:id');

            return redirect()->route('login')
                ->with('warning', 'Perangkat 2FA perlu didaftarkan ulang. Silakan login dan setup 2FA lagi.');
        }

        $data = $request->validate([
            'type' => ['required', 'in:totp,recovery'],
            'code' => ['required', 'string', 'min:6', 'max:32'],
        ]);

        $ok = false;
        if ($data['type'] === 'recovery') {
            $ok = $twofa->consumeRecoveryCode($user, $data['code']);
        } else {
            foreach ($devices as $device) {
                $secret = $twofa->decryptSecret($device->secret);
                if ($secret && $twofa->verifyCode($secret, $data['code'])) {
                    $ok = true;
                    $device->last_used_at = now();
                    $device->save();
                    break;
                }
            }
        }

        if (!$ok) {
            throw ValidationException::withMessages([
                'code' => 'Kode tidak valid.',
            ]);
        }

        $request->session()->forget('2fa:user:id');
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }
}

