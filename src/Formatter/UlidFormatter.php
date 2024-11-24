<?php

namespace Pelmered\LaravelUlid\Formatter;

class UlidFormatter
{
    public const string OPTION_LOWERCASE = 'lowercase';

    public const string OPTION_UPPERCASE = 'uppercase';

    protected $customFormatter;

    public function __construct(protected array $formattingOptions = []) {}

    public function format(string $prefix, string $time, string $random): string
    {
        //TODO: implement custom formatter support
        /*
        dump($this->customFormatter);
        $formatter = $this->getCustomFormatter();
        dump($formatter);
        */

        $ulid = sprintf('%s%s%s', $prefix, $time, $random);

        foreach ($this->formattingOptions as $option) {
            $ulid = match ($option) {
                self::OPTION_UPPERCASE => $prefix.strtoupper($time.$random),
                self::OPTION_LOWERCASE => $prefix.strtolower($time.$random),
                default => $prefix.$time.$random
            };
        }

        return $ulid;
    }

    public function formatUlidsUsing(callable $formatter): void
    {
        $this->customFormatter = $formatter;
    }

    public function getCustomFormatter(): ?\Closure
    {
        return $this->customFormatter;
    }
}
