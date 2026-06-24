<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function verify(?string $token, ?string $ip = null): bool
    {
        if (!config('services.recaptcha.enabled', true)) {
            return true;
        }

        $secret = (string) config('services.recaptcha.secret_key');

        if ($secret === '') {
            // If not configured yet, fail closed for admin login.
            return false;
        }

        if (!$token) {
            return false;
        }

        $resp = Http::asForm()
            ->timeout(8)
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $ip,
            ]);

        if (!$resp->ok()) {
            return false;
        }

        return (bool) ($resp->json('success') ?? false);
    }
}

