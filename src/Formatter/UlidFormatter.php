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
        // If we have a custom formatter, use it
        if (isset($this->customFormatter) && is_callable($this->customFormatter)) {
            return call_user_func($this->customFormatter, $prefix, $time, $random);
        }

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
