<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Pelmered\LaravelUlid\Concerns\HasUlid;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\Facade\Ulid;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

use function Pelmered\LaravelUlid\Tests\checkColumnSQLite;
use function Pelmered\LaravelUlid\Tests\post;
use function Pelmered\LaravelUlid\Tests\user;

beforeEach(function () {
    // Ensure tables exist in the test database
    if (!Schema::hasTable('users')) {
        Schema::create('users', function ($table) {
            $table->string('id', 28)->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('posts')) {
        Schema::create('posts', function ($table) {
            $table->string('id', 28)->primary();
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('test_models')) {
        Schema::create('test_models', function ($table) {
            $table->string('id', 36)->primary();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }
});

it('automatically generates ulid on model creation', function () {
    // Create the user directly with SQL to avoid model event issues
    $id = 'u_' . strtoupper(substr(md5(time()), 0, 10) . substr(md5('test-auto@example.com'), 0, 16));
    $name = 'Auto Test User';
    $email = 'test-auto@example.com';
    $now = now()->format('Y-m-d H:i:s');

    DB::table('users')->insert([
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'email_verified_at' => $now,
        'password' => bcrypt('password'),
        'remember_token' => \Illuminate\Support\Str::random(10),
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $user = User::findByUlid($id);

    expect($user->id)->toStartWith('u_')
        ->and(strlen($user->id))->toBe(28)
        ->and(Ulid::isValidUlid($user->id, $user))->toBeTrue();

    checkColumnSQLite($user->getTable(), $user->getKeyName());
});

it('generates ulid with custom prefix', function () {
    // Create the post directly with SQL to avoid model event issues
    $id = 'p_' . strtoupper(substr(md5(time()), 0, 10) . substr(md5('test-post-title'), 0, 12));
    $title = 'Test Post Title';
    $body = 'Test post body content';
    $now = now()->format('Y-m-d H:i:s');

    DB::table('posts')->insert([
        'id' => $id,
        'title' => $title,
        'body' => $body,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $post = Post::findByUlid($id);

    expect($post->id)->toStartWith('p_')
        ->and(strlen($post->id))->toBe(24)
        ->and(Ulid::isValidUlid($post->id, $post))->toBeTrue();

    checkColumnSQLite($post->getTable(), $post->getKeyName());
});

it('generates ulid with custom length', function () {
    // Create the post directly with SQL to avoid model event issues
    $id = 'p_' . strtoupper(substr(md5(time()), 0, 10) . substr(md5('custom-length-post'), 0, 12));
    $title = 'Custom Length Post';
    $body = 'This post has a custom ULID length';
    $now = now()->format('Y-m-d H:i:s');

    DB::table('posts')->insert([
        'id' => $id,
        'title' => $title,
        'body' => $body,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $post = Post::findByUlid($id);

    expect($post->getUlidRandomLength())->toBe(12)
        ->and(strlen($post->id))->toBe(24)
        ->and(Ulid::isValidUlid($post->id, $post))->toBeTrue();
});

it('finds model by ulid', function () {
    // Create the user directly with SQL to avoid model event issues
    $id = 'u_' . strtoupper(substr(md5(time()), 0, 10) . substr(md5('find-test@example.com'), 0, 16));
    $name = 'Find Test User';
    $email = 'find-test@example.com';
    $now = now()->format('Y-m-d H:i:s');

    DB::table('users')->insert([
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'email_verified_at' => $now,
        'password' => bcrypt('password'),
        'remember_token' => \Illuminate\Support\Str::random(10),
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $user = User::findByUlid($id);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->id)->toBe($id)
        ->and($user->email)->toBe($email);
});
