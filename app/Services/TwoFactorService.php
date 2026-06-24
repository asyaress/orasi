<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    public function generateSecret(): string
    {
        return app(Google2FA::class)->generateSecretKey();
    }

    public function getOtpAuthUrl(User $user, string $secret): string
    {
        $issuer = config('app.name', 'Orasi Unmul');
        $account = $user->email;

        return app(Google2FA::class)->getQRCodeUrl($issuer, $account, $secret);
    }

    public function makeQrPngDataUri(User $user, string $secret, int $size = 220): string
    {
        // Use pragmarx/google2fa-qrcode to generate inline PNG data-uri.
        $issuer = config('app.name', 'Orasi Unmul');
        $account = $user->email;

        $inline = app(\PragmaRX\Google2FAQRCode\Google2FA::class)->getQRCodeInline(
            $issuer,
            $account,
            $secret,
            $size
        );

        if (!is_string($inline) || $inline === '') {
            return '';
        }

        // If already a data-uri, keep it.
        if (str_starts_with($inline, 'data:image/')) {
            return $inline;
        }

        // Some backends return raw SVG markup (possibly with XML header). Normalize to data-uri.
        if (str_contains($inline, '<svg')) {
            $pos = strpos($inline, '<svg');
            $svg = $pos === false ? $inline : substr($inline, $pos);
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        }

        // Otherwise treat as binary PNG string.
        return 'data:image/png;base64,' . base64_encode($inline);
    }

    public function encryptSecret(string $secret): string
    {
        return Crypt::encryptString($secret);
    }

    public function decryptSecret(?string $encrypted): ?string
    {
        if (!$encrypted) {
            return null;
        }

        try {
            return Crypt::decryptString($encrypted);
        } catch (DecryptException) {
            return null;
        }
    }

    public function hasAnyDecryptableDevice(User $user): bool
    {
        return $user->twoFactorDevices()
            ->whereNotNull('confirmed_at')
            ->get()
            ->contains(fn ($device) => $this->decryptSecret($device->secret) !== null);
    }

    public function resetForUser(User $user): void
    {
        $user->twoFactorDevices()->delete();
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(10));
        }
        return $codes;
    }

    public function hashRecoveryCodes(array $codes): array
    {
        return array_map(fn ($c) => hash('sha256', $c), $codes);
    }

    public function verifyCode(string $secret, string $code): bool
    {
        $code = preg_replace('/\s+/', '', $code);
        if ($code === null || $code === '') {
            return false;
        }
        return (bool) app(Google2FA::class)->verifyKey($secret, $code);
    }

    public function consumeRecoveryCode(User $user, string $plainCode): bool
    {
        $stored = $user->two_factor_recovery_codes;
        if (!$stored) {
            return false;
        }

        $list = json_decode($stored, true);
        if (!is_array($list)) {
            return false;
        }

        $hash = hash('sha256', strtoupper(trim($plainCode)));
        $idx = array_search($hash, $list, true);
        if ($idx === false) {
            return false;
        }

        unset($list[$idx]);
        $user->two_factor_recovery_codes = json_encode(array_values($list));
        $user->save();

        return true;
    }
}

