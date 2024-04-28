<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\UserAction;
use Filament\Panel;
use App\Console\Commands\Generators\Model;

class User extends Authenticatable implements FilamentUser //,HasMedia
{
    use Notifiable;
    use HasApiTokens;
    use HasRoles;
    use HasFactory;
//    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_picture_path','is_email_verified','is_active'
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

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
        'last_active_at' => 'datetime:d-m-Y H:i:s',
        'email_verified_at' => 'datetime:d-m-Y H:i:s',
        'is_active' => 'boolean',
        'is_email_verified' => 'boolean',
        'is_initial_password_changed' => 'boolean',
    ];

    protected $with = [
//        'media',
        'roles'
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

    public function loadRelations(): self
    {
        return $this->load([
           'roles',
        ]);
    }

    public function markAsActive(): self
    {
        if (! $this->is_active) {
            $this->forceFill(['is_active' => true])->saveQuietly();
        }

        return $this->refresh();
    }

    public function markAsInactive(): self
    {
        if ($this->is_active) {
            $this->forceFill(['is_active' => false])->saveQuietly();
        }

        return $this->refresh();
    }


    public function userVerifications()
    {
        return $this->hasMany(UserVerify::class, "user_id","id");
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function emailChangeRequests()
    {
        return $this->hasMany(UserVerify::class, "user_id","id")->where("action_type", UserAction::CHANGE_EMAIL_REQUEST());
    }
}
