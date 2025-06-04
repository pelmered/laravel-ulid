<?php

use Carbon\Carbon;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\UlidService;
use Pelmered\LaravelUlid\ValueObject\Ulid;

beforeEach(function () {
    $this->ulidService = new UlidService;
});

it('generates ulid with default parameters', function () {
    $ulid = $this->ulidService->make();

    expect($ulid)
        ->toBeString()
        ->toHaveLength(Ulid::TIME_LENGTH + $this->ulidService->getDefaultRandomLength());
});

it('generates ulid with custom prefix', function () {
    $prefix = 'test_';
    $ulid = $this->ulidService->make(prefix: $prefix);

    expect($ulid)
        ->toBeString()
        ->toStartWith($prefix)
        ->toHaveLength(strlen($prefix) + Ulid::TIME_LENGTH + $this->ulidService->getDefaultRandomLength());
});

it('generates ulid with custom timestamp', function () {
    $timestamp = Carbon::create(2023, 1, 1, 12, 0, 0);
    $ulid1 = $this->ulidService->make(createdAt: $timestamp);
    $ulid2 = $this->ulidService->make(createdAt: $timestamp);

    // First part (time-based) should be identical
    expect(substr($ulid1, 0, Ulid::TIME_LENGTH))
        ->toBe(substr($ulid2, 0, Ulid::TIME_LENGTH));
});

it('generates ulid with custom lengths', function () {
    $timeLength = 8;
    $randomLength = 12;
    $ulid = $this->ulidService->make(randomLength: $randomLength);

    expect($ulid)
        ->toBeString()
        ->toHaveLength(Ulid::TIME_LENGTH + $randomLength);
});

it('validates ulid format correctly', function () {
    $mockModel = Mockery::mock(Ulidable::class);
    $mockModel->shouldReceive('getUlidPrefix')->andReturn('test_');
    $mockModel->shouldReceive('getUlidLength')->andReturn(31);

    $validUlid = 'test_'.str_repeat('a', 26);
    $invalidUlid = 'test_'.str_repeat('!', 22);

    expect(UlidService::isValidUlid($validUlid, $mockModel))->toBeTrue()
        ->and(UlidService::isValidUlid($invalidUlid, $mockModel))->toBeFalse();
});

it('creates ulid from model', function () {
    $timestamp = Carbon::now();
    $mockModel = Mockery::mock(Ulidable::class);
    $mockModel->shouldReceive('getCreatedAt')->andReturn($timestamp);
    $mockModel->shouldReceive('getUlidPrefix')->andReturn('test_');
    $mockModel->shouldReceive('getUlidTimeLength')->andReturn(10);
    $mockModel->shouldReceive('getUlidRandomLength')->andReturn(16);

    $ulid = UlidService::fromModel($mockModel);

    expect($ulid)->toBeObject()
        ->and(method_exists($ulid, 'format'))->toBeTrue();
});

it('checks if ulid is valid', function ($params, $valid) {

    expect(UlidService::isValidUlid($params['ulid'], $params['model'] ?? null, $params['prefix'] ?? null))->toBe($valid);

})->with([
    'valid' => [
        [
            'ulid' => str_repeat('a', 26),
            'model' => null,
            'prefix' => null,
        ],
        true,
    ],
    'valid with prefix' => [
        [
            'ulid' => 'test_'.str_repeat('a', 26),
            'model' => null,
            'prefix' => 'test_',
        ],
        true,
    ],
    'valid with a model' => [
        [
            'ulid' => 'u_'.str_repeat('a', 26),
            'model' => new \Workbench\App\Models\User,
        ],
        true,
    ],
    'too long with a model' => [
        [
            'ulid' => 'u_'.str_repeat('a', 27),
            'model' => new \Workbench\App\Models\User,
        ],
        false,
    ],
    'too short with a model' => [
        [
            'ulid' => 'u_'.str_repeat('a', 25),
            'model' => new \Workbench\App\Models\User,
        ],
        false,
    ],
    [
        [
            'ulid' => 'test_'.str_repeat('a', 26),
            'model' => null,
        ],
        false,
    ],
]);
