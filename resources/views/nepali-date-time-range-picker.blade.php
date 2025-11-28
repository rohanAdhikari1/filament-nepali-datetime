@php
    $fieldWrapperView = $getFieldWrapperView();
    $datalistOptions = $getDatalistOptions();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $extraAttributeBag = $getExtraAttributeBag();
    $extraInputAttributeBag = $getExtraInputAttributeBag();
    $hasTime = $hasTime();
    $hasSeconds = $hasSeconds();
    $id = $getId();
    $isDisabled = $isDisabled();
    $isAutofocused = $isAutofocused();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $maxDate = $getMaxDate();
    $minDate = $getMinDate();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixIconColor = $getPrefixIconColor();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixIconColor = $getSuffixIconColor();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
    $placeholder = $getPlaceholder();
    $isReadOnly = $isReadOnly();
    $isRequired = $isRequired();
    $isConcealed = $isConcealed();
    $step = $getStep();
    $type = $getType();
    $livewireKey = $getLivewireKey();
@endphp
<x-dynamic-component :component="$fieldWrapperView" :field="$field" :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center">
    <x-filament::input.wrapper :disabled="$isDisabled" :inline-prefix="$isPrefixInline" :inline-suffix="$isSuffixInline" :prefix="$prefixLabel" :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon" :prefix-icon-color="$prefixIconColor" :suffix="$suffixLabel" :suffix-actions="$suffixActions" :suffix-icon="$suffixIcon" :suffix-icon-color="$suffixIconColor"
        :valid="!$errors->has($statePath)" :attributes="\Filament\Support\prepare_inherited_attributes($extraAttributeBag)->class([
            'fi-fo-nepali-date-time-range-picker',
        ])">
        <div x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-nepali-datetime-picker', 'rohanadhikari/filament-nepali-datetime') }}"
            x-data="dateTimePickerFormComponent({
                defaultFocusedDate: @js($getDefaultFocusedDate()),
                displayFormat: '{{ convert_date_format($getDisplayFormat())->to('day.js') }}',
                firstDayOfWeek: {{ $getFirstDayOfWeek() }},
                isAutofocused: @js($isAutofocused),
                locale: @js($getLocale()),
                shouldCloseOnDateSelection: @js($shouldCloseOnDateSelection()),
                disableNavWhenOutOfRange: @js($getDisableNavWhenOutOfRange()),
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            })" wire:ignore
            wire:key="{{ $livewireKey }}.{{ substr(md5(serialize([$isDisabled, $isReadOnly, $maxDate, $minDate, $hasTime, $hasSeconds])), 0, 64) }}"
            x-on:keydown.esc="isOpen() && $event.stopPropagation()" {{ $getExtraAlpineAttributeBag() }}>
            <input x-ref="maxDate" type="hidden" value="{{ $maxDate }}" />

            <input x-ref="minDate" type="hidden" value="{{ $minDate }}" />

            <button x-ref="button" x-on:click="togglePanelVisibility()"
                x-on:keydown.enter.prevent.stop="
                        if (! $el.disabled) {
                            isOpen() ? selectDate() : togglePanelVisibility()   
                        }
                    "
                x-on:keydown.space.prevent.stop="if (! $el.disabled) selectFocusedDay()"
                x-on:keydown.alt.arrow-left.prevent.stop="if (! $el.disabled) focusPreviousMonth()"
                x-on:keydown.alt.arrow-right.prevent.stop="if (! $el.disabled) focusNextMonth()"
                x-on:keydown.alt.arrow-up.prevent.stop="if (! $el.disabled) focusPreviousYear()"
                x-on:keydown.alt.arrow-down.prevent.stop="if (! $el.disabled) focusNextYear()"
                x-on:keydown.arrow-left.prevent.stop="if (! $el.disabled) focusPreviousDay()"
                x-on:keydown.arrow-right.prevent.stop="if (! $el.disabled) focusNextDay()"
                x-on:keydown.arrow-up.prevent.stop="if (! $el.disabled) focusPreviousWeek()"
                x-on:keydown.arrow-down.prevent.stop="if (! $el.disabled) focusNextWeek()"
                x-on:keydown.home.prevent.stop="if (! $el.disabled) focusStartOfWeek()"
                x-on:keydown.end.prevent.stop="if (! $el.disabled) focusEndOfWeek()"
                x-on:keydown.page-up.prevent.stop="if (! $el.disabled) focusPreviousMonth()"
                x-on:keydown.page-down.prevent.stop="if (! $el.disabled) focusNextMonth()"
                x-on:keydown.shift.page-up.prevent.stop="if (! $el.disabled) focusPreviousYear()"
                x-on:keydown.shift.page-down.prevent.stop="if (! $el.disabled) focusNextYear()"
                x-on:keydown.backspace.prevent.stop="if (! $el.disabled) clearState()"
                x-on:keydown.clear.prevent.stop="if (! $el.disabled) clearState()"
                x-on:keydown.delete.prevent.stop="if (! $el.disabled) clearState()" aria-label="{{ $placeholder }}"
                type="button" tabindex="-1" @disabled($isDisabled || $isReadOnly)
                {{ $getExtraTriggerAttributeBag()->class(['fi-fo-date-time-picker-trigger']) }}>
                <input @disabled($isDisabled) readonly placeholder="{{ $placeholder }}"
                    wire:key="{{ $livewireKey }}.display-text" x-model="displayText"
                    @if ($id = $getId()) id="{{ $id }}" @endif @class(['fi-fo-date-time-picker-display-text-input']) />
            </button>

            <div x-ref="panel" x-cloak x-float.placement.bottom-start.offset.flip.shift="{ offset: 8 }" wire:ignore
                wire:key="{{ $livewireKey }}.panel" @class(['fi-fo-date-time-picker-panel'])>
                @if ($hasDate)
                    <div class="fi-fo-date-time-picker-panel-header">
                        <button x-show="isPrevActive" x-cloak type="button" x-on:click="focusPreviousMonth()">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem; width:1.2rem;" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <select x-model="focusedMonth" class="fi-fo-date-time-picker-month-select">
                            <template x-for="(month, index) in months">
                                <option x-bind:value="index + 1" x-text="month"></option>
                            </template>
                        </select>

                        <select x-model="focusedYear" class="fi-fo-date-time-picker-month-select">
                            <template x-for="year in years">
                                <option x-bind:value="year" x-text="toNumber(year)"></option>
                            </template>
                        </select>

                        <button x-show="isNextActive" x-cloak type="button" x-on:click="focusNextMonth()">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem; width:1.2rem;" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                    </div>

                    <div class="fi-fo-date-time-picker-calendar-header">
                        <template x-for="(day, index) in dayLabels" x-bind:key="index">
                            <div x-text="day" class="fi-fo-date-time-picker-calendar-header-day"></div>
                        </template>
                    </div>

                    <div role="grid" class="fi-fo-date-time-picker-calendar">
                        <template x-for="day in emptyDaysInFocusedMonth" x-bind:key="day">
                            <div></div>
                        </template>

                        <template x-for="day in daysInFocusedMonth" x-bind:key="day">
                            <div x-text="toNumber(day)" x-on:click="!dayIsDisabled(day) && selectDate(day)"
                                x-on:mouseenter="setFocusedDay(day)" role="option"
                                x-bind:aria-selected="focusedDate.day() === day"
                                x-bind:class="{
                                    'fi-fo-date-time-picker-calendar-day-today': dayIsToday(day),
                                    'fi-focused': focusedDate.day() === day,
                                    'fi-selected': dayIsSelected(day),
                                    'fi-disabled': dayIsDisabled(day),
                                }"
                                class="fi-fo-date-time-picker-calendar-day"></div>
                        </template>
                    </div>
                @endif

                @if ($hasTime)
                    <div class="fi-fo-date-time-picker-time-inputs">
                        <input max="23" min="0" step="{{ $getHoursStep() }}" type="number"
                            inputmode="numeric" x-model.debounce="hour" />

                        <span class="fi-fo-date-time-picker-time-input-separator">
                            :
                        </span>

                        <input max="59" min="0" step="{{ $getMinutesStep() }}" type="number"
                            inputmode="numeric" x-model.debounce="minute" />

                        @if ($hasSeconds)
                            <span class="fi-fo-date-time-picker-time-input-separator">
                                :
                            </span>

                            <input max="59" min="0" step="{{ $getSecondsStep() }}" type="number"
                                inputmode="numeric" x-model.debounce="second" />
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </x-filament::input.wrapper>

    @if ($datalistOptions)
        <datalist id="{{ $id }}-list">
            @foreach ($datalistOptions as $option)
                <option value="{{ $option }}" />
            @endforeach
        </datalist>
    @endif
</x-dynamic-component>
