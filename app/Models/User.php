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
     * Check if the authenticated user can perform an action on the given user.
     * 
     * @param User $user The user to check against.
     * @return bool True if the authenticated user is an admin or the same user, false otherwise.
     */
    public function hasAction(User $user): bool
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        return $authUser->hasRole('admin') || $authUser->id === $user->id;
    }

    /**
     * Check if the authenticated user is an admin.
     * 
     * @return bool True if the authenticated user is an admin, false otherwise.
     */
    public function isAdmin(): bool
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        return $authUser->hasRole('admin');
    }
}
