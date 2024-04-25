<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\UserAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVerify extends Model
{
    use HasFactory;

    public $table = 'users_verify';

    protected $fillable = [
        'user_id',
        'token',
        'expire_at',
        'draft_email',
        'action_type',
    ];

    protected $casts = [
        'expire_at' => 'datetime:d-m-Y H:i:s',
        "action_type" => UserAction::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
