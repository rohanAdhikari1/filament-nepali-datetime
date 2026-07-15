# ClockTimePicker

`ClockTimePicker` provides an interactive clock-style time picker for Filament forms. It supports selecting hours, minutes, and optional seconds, with built-in disabled time ranges, min/max bounds, locale support, and keyboard navigation.

## Setup

If you use Filament Panels, you must configure a custom theme and include the picker CSS in your theme stylesheet.

```css
@import '../../../../vendor/rohanadhikari/filament-nepali-datetime/resources/css/clock-time-picker.css';
```

## Basic Usage

```php
use RohanAdhikari\FilamentNepaliDatetime\ClockTimePicker;
use Filament\Forms\Components\Actions\Action;
use RohanAdhikari\NepaliDate\NepaliDate;

ClockTimePicker::make('nepali_time')
    ->label('Nepali Time')
    ->defaultFocusedTime(NepaliDate::now())
    ->disabledTimes(['09:00 AM', '10:00 AM'])
    ->maxTime('01:00 PM')
    ->seconds(false)
    ->minutesStep(5)
    ->closeOnTimeSelection()
    ->locale('np')
    ->suffixAction(
        Action::make('now')
            ->action(fn (\Filament\Tables\Actions\Set $set) => $set('nepali_time', NepaliDate::now()))
    );
```

## Features

-   Interactive clock face for selecting time visually.
-   Supports hour, minute, and optional second selection.
-   `seconds(false)` disables second selection and uses `HH:mm` formatting.
-   `closeOnTimeSelection()` closes the picker after a time is chosen.
-   `disabledTimes()` blocks specific hour/minute/second values.
-   `minTime()` and `maxTime()` enforce selectable bounds.
-   `minutesStep()` configures minute increments (for example, 5-minute steps).
-   `locale('en')` and `locale('np')` support English and Nepali display.
-   Keyboard shortcuts for accessible navigation and selection.

## Available Methods

-   `label(string $label)` — Set the field label.
-   `defaultFocusedTime(CarbonInterface|NepaliDate|string|null $time)` — Set the initial focused time when the picker opens.
-   `disabledTimes(array $times)` — Disable explicit time values.
-   `maxTime(CarbonInterface|string|null $time)` — Set the maximum allowed time.
-   `minTime(CarbonInterface|string|null $time)` — Set the minimum allowed time.
-   `seconds(bool $condition = true)` — Enable or disable seconds selection.
-   `closeOnTimeSelection(bool $condition = true)` — Automatically close the picker after selection.
-   `locale(string|null $locale)` — Set locale to `en` or `np`.
-   `minutesStep(int $step)` — Set minute increment steps.
-   `suffixAction(Action $action)` — Add a suffix action button beside the input.
-   `extraTriggerAttributes(array $attributes, bool $merge = false)` — Add custom attributes to the trigger button.

## Time Formats

-   Default internal time format: `H:i:s`
-   Default display format is derived from `format()` or `displayFormat()`.
-   With `seconds(false)`, the format becomes `H:i`.

## Keyboard Shortcuts

The time picker uses keyboard bindings for fast navigation and selection.

-   `Enter` — Open the picker or select the current view/time when open.
-   `Alt + Arrow Left` — Focus the previous view.
-   `Alt + Arrow Right` — Focus the next view.
-   `Arrow Left` — Move to the previous enabled hour/minute/second.
-   `Arrow Right` — Move to the next enabled hour/minute/second.
-   `Page Up` — Move to the previous view.
-   `Page Down` — Move to the next view.
-   `Home` — Jump to the first view (hour view).
-   `End` — Jump to the last view (`minute` or `second`).
-   `Backspace` / `Delete` / `Clear` — Clear the selected time.

## Behavior

-   Clicking the hour, minute, or second labels switches the active selection view.
-   Double-clicking the clock face advances to the next view or selects the current time.
-   Dragging the clock hand updates the selected value continuously.
-   Disabled times, minTime, and maxTime are respected across keyboard navigation and clock interaction.

## Notes

-   If `defaultFocusedTime()` is provided, the picker opens focused on that value.
-   If `locale('np')` is used, the numbers and display text will follow Nepali localization rules.
-   Use `disabledTimes()` with the same format as `maxTime()` / `minTime()` to ensure consistent behavior.
