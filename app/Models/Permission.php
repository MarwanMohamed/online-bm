<?php

namespace App\Models;

use App\Models\Lookup\Lookup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Lookup $category
 * @property-read string $name
 * @property-read string $display_name
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    protected $table = 'permissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'mandatory_permissions',
        'guard_name'
    ];

    protected $casts = [
        "mandatory_permissions"=>"array"
    ];

    /**
     * The permission category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'category_id');
    }

}
