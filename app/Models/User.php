<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get all borrowing records for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowingRecords()
    {
        return $this->hasMany(BorrowRecords::class);
    }

    /**
     * Get all ratings the user has submitted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Check if the user is an admin.
     * 
     * @return bool True if the user is an admin, false otherwise.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if the user is a client.
     * 
     * @return bool True if the user is a client, false otherwise.
     */
    public function isClient(): bool
    {
        return !$this->is_admin;
    }

    /**
     * Check if the user is the owner of the given resource (typically another user or entity).
     * 
     * @param User $user The user to check ownership against.
     * @return bool True if the authenticated user is the owner (i.e., the same user), false otherwise.
     */
    public function isOwner(User $user): bool
    {
        return $this->id === $user->id;
    }
}
