<?php

namespace Pelmered\LaravelUlid\Filament\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class UuidEntry extends TextEntry
{
    public static function make(string $name): static
    {
        return parent::make($name)
            ->copyable()
            ->formatStateUsing(function (string $state, Model $record) {
                return new HtmlString(substr($state, 0, 14).'<br/>'.substr($state, 14));
            });
    }
}
