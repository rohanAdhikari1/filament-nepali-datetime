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
    $isRangeLabelsEnabled = $getShowRangeLabels();
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
    $livewireKey = $getLivewireKey();
@endphp
<x-dynamic-component :component="$fieldWrapperView" :field="$field" :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center">
    <x-filament::input.wrapper :disabled="$isDisabled" :inline-prefix="$isPrefixInline" :inline-suffix="$isSuffixInline" :prefix="$prefixLabel" :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon" :prefix-icon-color="$prefixIconColor" :suffix="$suffixLabel" :suffix-actions="$suffixActions" :suffix-icon="$suffixIcon" :suffix-icon-color="$suffixIconColor"
        :valid="!$errors->has($statePath)" :attributes="\Filament\Support\prepare_inherited_attributes($extraAttributeBag)->class(['fi-fo-ndtr-picker'])">
        <div x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-nepali-datetime-range-picker', 'rohanadhikari/filament-nepali-datetime') }}"
            x-data="dateTimeRangePickerFormComponent({
                defaultFocusedDate: '',
                displayFormat: '{{ convert_date_format($getDisplayFormat())->to('day.js') }}',
                firstDayOfWeek: {{ $getFirstDayOfWeek() }},
                isAutofocused: @js($isAutofocused),
                locale: @js($getLocale()),
                shouldCloseOnDateSelection: @js(true),
                {{-- disableNavWhenOutOfRange: @js($getDisableNavWhenOutOfRange()), --}}
                disableNavWhenOutOfRange: @js(false),
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            })" wire:ignore
            wire:key="{{ $livewireKey }}.{{ substr(md5(serialize([$isDisabled, $isReadOnly, $maxDate, $minDate, $hasTime, $hasSeconds])), 0, 64) }}"
            {{-- x-on:keydown.esc="isOpen() && $event.stopPropagation()"  --}} {{ $getExtraAlpineAttributeBag() }}>

            <input x-ref="maxDate" type="hidden" value="{{ $maxDate }}" />

            <input x-ref="minDate" type="hidden" value="{{ $minDate }}" />

            <button x-ref="button" x-on:click="togglePanelVisibility()"
                x-on:keydown.enter.prevent.stop="
                        if (! $el.disabled) {
                            isOpen() ? selectDate() : togglePanelVisibility()   
                        }
                    "
                x-on:keydown.space.prevent.stop="if (! $el.disabled) selectDate()"{{-- 
                x-on:keydown.alt.arrow-left.prevent.stop="if (! $el.disabled) focusPreviousMonth()"
                x-on:keydown.alt.arrow-right.prevent.stop="if (! $el.disabled) focusNextMonth()"
                x-on:keydown.alt.arrow-up.prevent.stop="if (! $el.disabled) focusPreviousYear()"
                x-on:keydown.alt.arrow-down.prevent.stop="if (! $el.disabled) focusNextYear()" --}}
                x-on:keydown.arrow-left.prevent.stop="if (! $el.disabled) focusPreviousDay()"
                x-on:keydown.arrow-right.prevent.stop="if (! $el.disabled) focusNextDay()" {{-- x-on:keydown.arrow-up.prevent.stop="if (! $el.disabled) focusPreviousWeek()"
                x-on:keydown.arrow-down.prevent.stop="if (! $el.disabled) focusNextWeek()"
                x-on:keydown.home.prevent.stop="if (! $el.disabled) focusStartOfWeek()"
                x-on:keydown.end.prevent.stop="if (! $el.disabled) focusEndOfWeek()"
                x-on:keydown.page-up.prevent.stop="if (! $el.disabled) focusPreviousMonth()"
                x-on:keydown.page-down.prevent.stop="if (! $el.disabled) focusNextMonth()"
                x-on:keydown.shift.page-up.prevent.stop="if (! $el.disabled) focusPreviousYear()"
                x-on:keydown.shift.page-down.prevent.stop="if (! $el.disabled) focusNextYear()"
                x-on:keydown.backspace.prevent.stop="if (! $el.disabled) clearState()"
                x-on:keydown.clear.prevent.stop="if (! $el.disabled) clearState()"
                x-on:keydown.delete.prevent.stop="if (! $el.disabled) clearState()" aria-label="{{ $placeholder }}" --}}
                type="button" tabindex="-1" @disabled($isDisabled || $isReadOnly)
                {{ $getExtraTriggerAttributeBag()->class(['fi-fo-ndtr-picker-trigger']) }}>
                <input @disabled($isDisabled) readonly placeholder="{{ $placeholder }}"
                    wire:key="{{ $livewireKey }}.display-text" x-model="displayText"
                    @if ($id = $getId()) id="{{ $id }}" @endif @class(['fi-fo-ndtr-picker-display-text-input']) />
            </button>

            <div x-ref="panel" x-cloak x-float.placement.bottom-start.offset.flip.shift="{ offset: 8 }" wire:ignore
                wire:key="{{ $livewireKey }}.panel" @class(['fi-fo-ndtr-picker-panel'])>

                <div class="fi-fo-ndtr-picker-container">
                    @if ($isRangeLabelsEnabled)
                        <ul class="fi-fo-ndtr-picker-range-labels-section">
                            <li class="fi-fo-ndtr-picker-range-label">Today</li>
                            <li class="fi-fo-ndtr-picker-range-label">Yesterday</li>

                            <li class="fi-fo-ndtr-picker-range-label fi-selected">Custom Range</li>
                        </ul>
                    @endif

                    <div class="fi-fo-ndtr-picker-range-calendar-section">
                        <div class="fi-fo-ndtr-picker-range-calendar-section-header">
                            <button x-show="leftcalendar.isPrevActive" x-cloak type="button"
                                x-on:click="leftcalendar.focusPreviousMonth()">
                                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem; width:1.2rem;"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <select x-model="leftcalendar.focusedMonth" class="fi-fo-ndtr-picker-select">
                                <template x-for="(month, index) in months">
                                    <option x-bind:value="index + 1" x-text="month"></option>
                                </template>
                            </select>

                            <select x-model="leftcalendar.focusedYear" class="fi-fo-ndtr-picker-select">
                                <template x-for="year in years">
                                    <option x-bind:value="year" x-text="toNumber(year)"></option>
                                </template>
                            </select>

                            <button x-show="leftcalendar.isNextActive" x-cloak type="button"
                                x-on:click="leftcalendar.focusNextMonth()">
                                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem; width:1.2rem;"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        <div class="fi-fo-ndtr-picker-calendar-header">
                            <template x-for="(day, index) in dayLabels" x-bind:key="index">
                                <div x-text="day" class="fi-fo-ndtr-picker-calendar-header-day"></div>
                            </template>
                        </div>
                        <div role="grid" class="fi-fo-ndtr-picker-calendar">
                            <template x-for="day in leftcalendar.emptyDaysInFocusedMonth" x-bind:key="day">
                                <div></div>
                            </template>

                            <template x-for="day in leftcalendar.daysInFocusedMonth" x-bind:key="day">
                                <div x-text="toNumber(day)"
                                    x-on:click="!dayIsDisabled(day,leftcalendar.focusedMonth,leftcalendar.focusedYear) && leftcalendar.selectDate(day)"
                                    x-on:mouseenter="leftcalendar.setFocusedDay(day)" role="option"
                                    x-bind:class="{
                                        'fi-fo-ndtr-picker-calendar-day-today': leftcalendar.dayIsToday(day),
                                        'fi-start': isStartDate(day, leftcalendar.focusedMonth, leftcalendar
                                            .focusedYear),
                                        'fi-focused': isDateFocused(day, leftcalendar.focusedMonth, leftcalendar
                                            .focusedYear),
                                        'fi-end': isEndDate(day, leftcalendar.focusedMonth, leftcalendar
                                            .focusedYear),
                                        'fi-in-range': isInRange(day, leftcalendar.focusedMonth, leftcalendar
                                            .focusedYear),
                                        'fi-disabled': dayIsDisabled(day, leftcalendar.focusedMonth, leftcalendar
                                            .focusedYear),
                                    }"
                                    class="fi-fo-ndtr-picker-calendar-day"></div>
                            </template>
                        </div>
                    </div>

                    <div class="fi-fo-ndtr-picker-range-calendar-section">
                        <div class="fi-fo-ndtr-picker-range-calendar-section-header">
                            <button x-show="rightcalendar.isPrevActive" x-cloak type="button"
                                x-on:click="rightcalendar.focusPreviousMonth()">
                                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem; width:1.2rem;"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <select x-model="rightcalendar.focusedMonth" class="fi-fo-ndtr-picker-select">
                                <template x-for="(month, index) in months">
                                    <option x-bind:value="index + 1" x-text="month"></option>
                                </template>
                            </select>

                            <select x-model="rightcalendar.focusedYear" class="fi-fo-ndtr-picker-select">
                                <template x-for="year in years">
                                    <option x-bind:value="year" x-text="toNumber(year)"></option>
                                </template>
                            </select>

                            <button x-show="rightcalendar.isNextActive" x-cloak type="button"
                                x-on:click="rightcalendar.focusNextMonth()">
                                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem; width:1.2rem;"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        <div class="fi-fo-ndtr-picker-calendar-header">
                            <template x-for="(day, index) in dayLabels" x-bind:key="index">
                                <div x-text="day" class="fi-fo-ndtr-picker-calendar-header-day"></div>
                            </template>
                        </div>
                        <div role="grid" class="fi-fo-ndtr-picker-calendar">
                            <template x-for="day in rightcalendar.emptyDaysInFocusedMonth"
                                x-bind:key="day">
                                <div></div>
                            </template>

                            <template x-for="day in rightcalendar.daysInFocusedMonth" x-bind:key="day">
                                <div x-text="toNumber(day)"
                                    x-on:click="!dayIsDisabled(day,rightcalendar.focusedMonth,rightcalendar.focusedYear) && rightcalendar.selectDate(day)"
                                    x-on:mouseenter="rightcalendar.setFocusedDay(day)" role="option"
                                    x-bind:class="{
                                        'fi-fo-ndtr-picker-calendar-day-today': rightcalendar.dayIsToday(day),
                                        'fi-start': isStartDate(day, rightcalendar.focusedMonth, rightcalendar
                                            .focusedYear),
                                        'fi-focused': isDateFocused(day, rightcalendar.focusedMonth, rightcalendar
                                            .focusedYear),
                                        'fi-end': isEndDate(day, rightcalendar.focusedMonth, rightcalendar.focusedYear),
                                        'fi-in-range': isInRange(day, rightcalendar.focusedMonth, rightcalendar
                                            .focusedYear),
                                    
                                        'fi-disabled': dayIsDisabled(day, rightcalendar.focusedMonth, rightcalendar
                                            .focusedYear),
                                    }"
                                    class="fi-fo-ndtr-picker-calendar-day"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="fi-fo-ndtr-picker-footer">
                    //here is apply button, cancel button and selected date range display
                </div>

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
