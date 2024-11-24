<?php

namespace Pelmered\LaravelUlid\Contracts;

use Carbon\Carbon;

interface Ulidable
{
    public function getUlidPrefix(): string;

    public function getCreatedAt(): ?Carbon;

    public function getUlidTimeLength(): int;

    public function getUlidRandomLength(): int;

    public function getUlidLength(): int;

    public function getUlidFormattingOptions(): array;
}
