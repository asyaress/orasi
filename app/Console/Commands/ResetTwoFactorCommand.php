<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Console\Command;

class ResetTwoFactorCommand extends Command
{
    protected $signature = 'orasi:reset-two-factor {email? : Email admin yang akan di-reset 2FA-nya}';

    protected $description = 'Reset 2FA user (hapus device & secret) agar bisa setup ulang di environment ini';

    public function handle(TwoFactorService $twofa): int
    {
        $email = $this->argument('email');

        if ($email) {
            $user = User::query()->where('email', $email)->first();
            if (!$user) {
                $this->error("User tidak ditemukan: {$email}");

                return self::FAILURE;
            }

            $twofa->resetForUser($user);
            $this->info("2FA direset untuk {$user->email}. Login dan setup ulang via /two-factor/setup.");

            return self::SUCCESS;
        }

        if (!$this->confirm('Reset 2FA untuk SEMUA user admin?', false)) {
            $this->comment('Dibatalkan.');

            return self::SUCCESS;
        }

        $users = User::query()->where('is_admin', true)->get();
        foreach ($users as $user) {
            $twofa->resetForUser($user);
            $this->line("  - {$user->email}");
        }

        $this->info('Selesai. Semua admin perlu setup 2FA ulang setelah login.');

        return self::SUCCESS;
    }
}
