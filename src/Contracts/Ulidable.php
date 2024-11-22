<?php
namespace Pelmered\LaravelUlid\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Pelmered\LaravelUlid\Ulid;

interface Ulidable
{
    public function getUlidPrefix(): string;

    public function getCreatedAt(): ?Carbon;

    public function isValidUlid(string $ulid): bool;

    public function getUlidTimeLength(): int;

    public function getUlidRandomLength(): int;

    public function getUlidLength(): int;

    public function getUlidFormattingOptions(): array;
}
