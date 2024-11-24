<?php
namespace Pelmered\LaravelUlid\Formatter;

use Pelmered\LaravelUlid\ValueObject\Ulid;

class UlidFormatter
{
    public const string OPTION_LOWERCASE = 'lowercase';
    public const string OPTION_UPPERCASE = 'uppercase';

    protected $customFormatter;


    public function __construct( protected array $formattingOptions = [])
    {


    }

    //protected function format(string $ulid): string
    public function format(string $prefix, string $time, string $random): string
    {
        /*
        dump($this->customFormatter);
        $formatter = $this->getCustomFormatter();
        dump($formatter);
        */

        $ulid = sprintf('%s%s%s', $prefix, $time, $random);

        foreach($this->formattingOptions as $option) {
            $ulid = match($option) {
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
