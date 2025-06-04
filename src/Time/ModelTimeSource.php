<?php

namespace Pelmered\LaravelUlid\Time;

use Illuminate\Database\Eloquent\Model;
use lewiscowles\core\Concepts\Time\TimeSourceInterface;
use Pelmered\LaravelUlid\Contracts\Ulidable;

class ModelTimeSource implements TimeSourceInterface
{
    public function __construct(protected Ulidable $ulidable) {}

    public function getTime(): int
    {
        return $this->ulidable->getCreatedAt()->getMicrosecond();
    }
}
