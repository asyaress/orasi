<?php

use App\Models\TwoFactorDevice;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        User::query()
            ->whereNotNull('two_factor_secret')
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    $exists = TwoFactorDevice::query()
                        ->where('user_id', $user->id)
                        ->whereNotNull('confirmed_at')
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    TwoFactorDevice::create([
                        'user_id' => $user->id,
                        'name' => 'Authenticator',
                        'secret' => $user->two_factor_secret,
                        'confirmed_at' => $user->two_factor_confirmed_at ?: now(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        // no-op (don't remove user devices)
    }
};

