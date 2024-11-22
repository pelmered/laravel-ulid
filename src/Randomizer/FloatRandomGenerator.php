<?php
namespace Pelmered\LaravelUlid\Randomizer;

use lewiscowles\core\Concepts\Random\Source\RandomFloatInterface;
use Random\Randomizer;

class FloatRandomGenerator implements RandomFloatInterface
{
    public function generate(): float
    {
        return (new Randomizer())->getFloat(0, 1, \Random\IntervalBoundary::OpenOpen);
    }
}
