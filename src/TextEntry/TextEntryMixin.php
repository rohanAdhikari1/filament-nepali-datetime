<?php

namespace RohanAdhikari\FilamentNepaliDatetime\TextEntry;

use Filament\Infolists\Components\TextEntry;

/**
 * @mixin TextEntry
 *
 * @method $this nepaliDate(string|callable|null $format = null, string|callable|null $timezone = null, string|callable|null $locale = null)
 * @method $this toNepaliDate(string|callable|null $format = null, string|callable|null $timezone = null, string|callable|null $locale = null)
 * @method $this nepaliDateTooltip(string|callable|null $format = null, string|callable|null $timezone = null, string|callable|null $locale = null)
 */
class TextEntryMixin
{
    use CanFormatStateInNepali;
}
