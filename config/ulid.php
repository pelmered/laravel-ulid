<?php

use Pelmered\LaravelUlid\Ulid;

return [
    'randomness_encoder' => \Pelmered\LaravelUlid\Randomizer\FloatRandomGenerator::class,
    'time_source' => \Pelmered\LaravelUlid\Time\StaticTimeSource::class,

    'time_length' => 10,

    'random_length' => 16,

    'formatting_options' => [Ulid::OPTION_UPPERCASE],
];
