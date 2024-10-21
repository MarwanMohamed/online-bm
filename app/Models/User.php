<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser //,HasMedia
{
    use Notifiable;
    use HasApiTokens;
//    use HasRoles;
    use HasFactory;

//    use InteractsWithMedia;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'last_active_at'
    ];

    protected $with = [
//        'media',
//        'roles'
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, request()));
    }

    /**
     * Define the media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')->singleFile();
    }


}
