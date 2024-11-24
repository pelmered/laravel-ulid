<?php

namespace Pelmered\LaravelUlid\Time;

use Illuminate\Database\Eloquent\Model;
use lewiscowles\core\Concepts\Time\TimeSourceInterface;

class ModelTimeSource implements TimeSourceInterface
{
    public function __construct(Model $model) {}

    public function getTime(): int
    {
        return time();
    }
}
