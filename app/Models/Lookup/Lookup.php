<?php

namespace App\Models\Lookup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Lookup extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     */
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
        'extra_details' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LookupCategory::class);
    }


    /**
     * Get all lookup categories from the cache, or fetch them from database
     * and store the result in the cache forever.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Lookup\Lookup[]
     */
    public static function cached()
    {
        return Cache::rememberForever('lookupsList', function () {
            return Lookup::all();
        });
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function () {
            Cache::forget('lookupsList');
        });

        static::deleted(function () {
            Cache::forget('lookupsList');
        });
    }
}
