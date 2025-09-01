<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia, HasName, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasRoles, InteractsWithMedia, SoftDeletes, LogsActivity, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'is_active',
        'timezone',
        'preferences',
        'password',
        'created_by',
        'updated_by',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
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
            'last_login_at' => 'datetime',
            'last_login_ip' => 'string',
            'is_active' => 'boolean',
            'preferences' => 'array',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    public const ROLE_ADMIN = 'admin';
    public const ROLE_AUTHOR = 'author';
    public const ROLE_CONTRIBUTOR = 'contributor';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_SUBSCRIBER = 'subscriber';
    public const ROLE_USER = 'user';


    public static function defaultRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_AUTHOR => 'Author',
            self::ROLE_CONTRIBUTOR => 'Contributor',
            self::ROLE_EDITOR => 'Editor',
            self::ROLE_SUBSCRIBER => 'Subscriber',
            self::ROLE_USER => 'User',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['username', 'email', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function canAccessPanel(Panel $panel): bool

    {
        return true;
    }

    /**
     * Get the user who created this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the contents authored by this user.
     */
    public function authoredContents(): HasMany
    {
        return $this->hasMany(Content::class, 'author_id');
    }

    /**
     * Get the contents edited by this user.
     */
    public function editedContents(): HasMany
    {
        return $this->hasMany(Content::class, 'editor_id');
    }

    /**
     * Get the content revisions created by this user.
     */
    public function contentRevisions(): HasMany
    {
        return $this->hasMany(ContentRevision::class, 'author_id');
    }

    /**
     * Get the content likes by this user.
     */
    public function contentLikes(): HasMany
    {
        return $this->hasMany(ContentLike::class);
    }

    /**
     * Get the comments by this user.
     */
    public function contentComments(): HasMany
    {
        return $this->hasMany(ContentComment::class);
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}" ?: $this->username;
    }

    public function getFullNameAttribute(): string
    {
        return $this->getFilamentName();
    }

    /**
     * Register media collections for the user.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->useDisk('public');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->hasMedia('avatars')) {
            return $this->getFirstMediaUrl('avatars');
        }

        return $this->defaultAvatarUrl();
    }

    protected function defaultAvatarUrl(): string
    {
        $email = $this->email ?: 'default@example.com';
        $hash  = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon";
    }

    // Helper method untuk mendapatkan avatar
    public function getAvatar(): string
    {
        return $this->getFilamentAvatarUrl();
    }

    // Check if user is admin
    public function isAdministrator(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($user) {
            $user->clearMediaCollection('avatars');
        });
    }
}
