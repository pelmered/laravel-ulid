<?php

use Pelmered\LaravelUlid\Formatter\UlidFormatter;
use Pelmered\LaravelUlid\UlidService;
use Pelmered\LaravelUlid\ValueObject\Ulid;

return [
    'randomness_encoder' => \Pelmered\LaravelUlid\Randomizer\FloatRandomGenerator::class,
    'time_source' => \Pelmered\LaravelUlid\Time\StaticTimeSource::class,

    'time_length' => env('ULID_TIME_LENGTH', 10),

    'random_length' => env('ULID_RANDOM_LENGTH', 16),

    'formatter' => env('ULID_FORMATTER', UlidFormatter::class),
    'formatter_options' => env('ULID_FORMATTER_OPTIONS', [UlidFormatter::OPTION_UPPERCASE]),
];
