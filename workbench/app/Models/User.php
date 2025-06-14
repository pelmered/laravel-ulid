<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Pelmered\LaravelUlid\Concerns\FindByUlid;
use Pelmered\LaravelUlid\Concerns\HasUlid;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Workbench\Database\Factories\UserFactory;

class User extends Authenticatable implements Ulidable
{
    use FindByUlid, HasFactory, HasUlid, Notifiable;

    protected string $ulidPrefix = 'u_';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
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
     * The attributes that should be cast.
     *
     * @var list<string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
