<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
#[Fillable(['name', 'email', 'password', 'role', 'photo', 'address', 'nif'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    const TYPE_ADMIN = 'admin';
    const TYPE_MODERATOR = 'mod';
    const TYPE_GENERIC_USER = 'user';


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMod(): bool
    {
        return $this->role === 'mod';
    }



    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'mod']);
    }
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
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Str::title(Str::lower($value)),
        );
    }

}
