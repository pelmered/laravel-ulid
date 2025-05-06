<?php
namespace Pelmered\LaravelUlid\Filament\Fields;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class UlidColumn extends TextColumn
{
    public static function make(string $name = 'id'): static
    {
        $static = parent::make($name);

        $static->label('ULID')
            ->searchable()
            ->copyable()
            ->tooltip(fn (Model $record): string => $record->{$name})
            ->formatStateUsing(fn (Model $record): string => '...'.substr($record->{$name}, -5));

        return $static;
    }
}
