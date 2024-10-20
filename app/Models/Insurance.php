<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Insurance extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ad_id', 'id');
    }

    public function getArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area');
    }

    public function getStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'com_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'policy_ref', 'policy_id')
            ->latest('id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
