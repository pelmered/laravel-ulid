<?php
namespace Pelmered\LaravelUlid;

use Carbon\Carbon;
use Pelmered\LaravelUlid\ValueObject\Ulid;

class UlidFactory
{
    // Crockford's Base32 alphabet.
    // All numbers abd capital letters, except I, L, O, Q for better human readability
    const string ALPHABET = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

    public function generateMonotonicUlid(
        Carbon|\DateTimeInterface|int|null $time = null,
        string $prefix = '',
        int $randomLength = 16,
        string $lastRandom = null
    ): Ulid {
        $time = $this->parseTime($time);
        $timePart = $this->base32Encode($time, Ulid::TIME_LENGTH);

        if ($lastRandom === null) {
            $randomPart = $this->generateRandomPart($randomLength);
        } else {
            $randomPart = $this->incrementRandom($lastRandom, $randomLength);
        }

        return new Ulid(
            $prefix,
            $timePart,
            $randomPart,
        );
    }

    public function parseTime($time): int
    {
        if (is_int($time))
        {
            return $time;
        }

        if ($time instanceof Carbon)
        {
            return $time->getPreciseTimestamp(3);
        }

        if ($time instanceof \DateTimeInterface)
        {
            return (int) Carbon::instance($time)->getPreciseTimestamp(3);
        }

        return (int) floor(microtime(true) * 1000);
    }

    private function base32Encode($number, int $length): string
    {
        bcscale(0);
        $base32 = '';

        do {
            $number = number_format($number, 0, '.', '');
            $remainder = (int) bcmod($number, 32);
            $base32 = self::ALPHABET[$remainder] . $base32;
            $number = bcdiv(bcsub($number, $remainder), 32);
        } while ($number > 0);

        return str_pad($base32, $length, '0', STR_PAD_LEFT);
    }

    private function generateRandomPart(int $length): string
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= self::ALPHABET[random_int(0, 31)];
        }
        return $random;
    }

    private function incrementRandom($lastRandom, $length): string
    {
        $value = $this->base32Decode($lastRandom);
        $value++;
        return $this->base32Encode($value, $length);
    }

    private function base32Decode(string $base32): float|int
    {
        $number = 0;
        $strLength = strlen($base32);

        for ($i = 0; $i < $strLength; $i++) {
            $number = $number * 32 + strpos(self::ALPHABET, $base32[$i]);
        }
        return $number;
    }
}
