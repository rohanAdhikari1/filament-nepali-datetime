<?php

namespace RohanAdhikari\FilamentNepaliDatetime\TextColumn;

use Filament\Tables\Columns\TextColumn;

/**
 * @mixin TextColumn
 *
 * @method $this nepaliDate(string|callable|null $format = null, string|callable|null $timezone = null, string|callable|null $locale = null)
 * @method $this toNepaliDate(string|callable|null $format = null, string|callable|null $timezone = null, string|callable|null $locale = null)
 * @method $this nepaliDateTooltip(string|callable|null $format = null, string|callable|null $timezone = null, string|callable|null $locale = null)
 */
class TextColumnMixin
{
    use CanFormatStateInNepali;
}
