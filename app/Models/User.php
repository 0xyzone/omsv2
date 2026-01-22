<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Encashment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Andreia\FilamentUiSwitcher\Models\Traits\HasUiPreferences;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
use Spatie\Permission\Traits\HasRoles;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser, HasPasskeys, HasAvatar, MustVerifyEmail
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUiPreferences, InteractsWithPasskeys, TwoFactorAuthenticatable, HasRoles;

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ui_preferences' => 'array',
            'custom_fields' => 'array',
        ];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'mukhiya') {
            return $this->hasRole('super_admin');
        } elseif ($panel->getId() === 'taker') {
            return $this->hasRole(['taker', 'super_admin']);
        } elseif ($panel->getId() === 'maker') {
            return $this->hasRole(['maker']);
        } else {
            return false;
        }
    }

    /**
     * Get all of the enccashments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encashments(): HasMany
    {
        return $this->hasMany(Encashment::class);
    }

    /**
     * Get all of the orders for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
