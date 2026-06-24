<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorDevice extends Model
{
    protected $table = 'two_factor_devices';

    protected $fillable = [
        'user_id',
        'name',
        'secret',
        'confirmed_at',
        'last_used_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

