<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Pelmered\LaravelUlid\Concerns\FindByUlid;
use Pelmered\LaravelUlid\Concerns\HasUlid;
use Pelmered\LaravelUlid\Contracts\Ulidable;

class Post extends Model implements Ulidable
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    use FindByUlid, HasUlid, Notifiable;

    protected string $ulidPrefix = 'p_';

    protected int $ulidRandomLength = 12;
}
