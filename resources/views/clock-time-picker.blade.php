@php
    $fieldWrapperView = $getFieldWrapperView();
    $datalistOptions = $getDatalistOptions();
    $disabledTimes = $getDisabledTimes();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $extraAttributeBag = $getExtraAttributeBag();
    $extraInputAttributeBag = $getExtraInputAttributeBag();
    $hasSeconds = $hasSeconds();
    $id = $getId();
    $isDisabled = $isDisabled();
    $isAutofocused = $isAutofocused();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $maxTime = $getMaxTime();
    $minTime = $getMinTime();
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
    $livewireKey = $getLivewireKey();
@endphp
<x-dynamic-component :component="$fieldWrapperView" :field="$field" :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center">
    <x-filament::input.wrapper :disabled="$isDisabled" :inline-prefix="$isPrefixInline" :inline-suffix="$isSuffixInline" :prefix="$prefixLabel" :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon" :prefix-icon-color="$prefixIconColor" :suffix="$suffixLabel" :suffix-actions="$suffixActions" :suffix-icon="$suffixIcon" :suffix-icon-color="$suffixIconColor"
        :valid="!$errors->has($statePath)" :attributes="\Filament\Support\prepare_inherited_attributes($extraAttributeBag)->class([
            'fi-fo-nepali-clock-time-picker',
        ])">
        <div x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-nepali-clock-time-picker', 'rohanadhikari/filament-nepali-datetime') }}"
            x-data="clockTimePickerFormComponent({
                defaultFocusedTime: @js($getDefaultFocusedTime()),
                hasSeconds: @js($hasSeconds),
                displayFormat: '{{ convert_date_format($getDisplayFormat())->to('day.js') }}',
                defaultView: @js(null),
                isAutofocused: @js($isAutofocused),
                locale: @js($getLocale()),
                shouldCloseOnTimeSelection: @js($shouldCloseOnTimeSelection()),
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            })" wire:ignore
            wire:key="{{ $livewireKey }}.{{ substr(md5(serialize([$disabledTimes, $isDisabled, $isReadOnly, $maxTime, $minTime, $hasSeconds])), 0, 64) }}"
            x-on:keydown.esc="isOpen() && $event.stopPropagation()" {{ $getExtraAlpineAttributeBag() }}>
            <input x-ref="maxTime" type="hidden" value="{{ $maxTime }}" />

            <input x-ref="minTime" type="hidden" value="{{ $minTime }}" />

            <input x-ref="disabledTimes" type="hidden" value="{{ json_encode($disabledTimes) }}" />

            <button x-ref="button" x-on:click="togglePanelVisibility()"
                x-on:keydown.enter.prevent.stop="
                        if (! $el.disabled) {
                            isOpen() ? focusNextView(true) : togglePanelVisibility()
                        }
                    "
                x-on:keydown.alt.arrow-left.prevent.stop="if (! $el.disabled) focusPrevView()"
                x-on:keydown.alt.arrow-right.prevent.stop="if (! $el.disabled) focusNextView()"
                x-on:keydown.arrow-left.prevent.stop="if (! $el.disabled) focusPrev()"
                x-on:keydown.arrow-right.prevent.stop="if (! $el.disabled) focusNext()"
                x-on:keydown.page-up.prevent.stop="if (! $el.disabled) focusPrevView()"
                x-on:keydown.page-down.prevent.stop="if (! $el.disabled) focusNextView()"
                x-on:keydown.home.prevent.stop="if (! $el.disabled) focusFirstView()"
                x-on:keydown.end.prevent.stop="if (! $el.disabled) focusLastView()"
                x-on:keydown.backspace.prevent.stop="if (! $el.disabled) clearState()"
                x-on:keydown.clear.prevent.stop="if (! $el.disabled) clearState()"
                x-on:keydown.delete.prevent.stop="if (! $el.disabled) clearState()" aria-label="{{ $placeholder }}"
                type="button" tabindex="-1" @disabled($isDisabled || $isReadOnly)
                {{ $getExtraTriggerAttributeBag()->class(['fi-fo-nepali-clock-time-picker-trigger']) }}>
                <input x-ref="displaytext" @disabled($isDisabled) readonly placeholder="{{ $placeholder }}"
                    wire:key="{{ $livewireKey }}.display-text" x-model="displayText"
                    @if ($id = $getId()) id="{{ $id }}" @endif @class(['fi-fo-nepali-clock-time-picker-display-text-input']) />
            </button>
            <div x-ref="panel" x-cloak x-float.placement.bottom-start.offset.flip.shift="{ offset: 8 }" wire:ignore
                wire:key="{{ $livewireKey }}.panel" @class(['fi-fo-nepali-clock-time-picker-panel'])>
                <div role="group" class="fi-fo-nepali-clock-time-picker-panel-header">
                    <div role="option" class="fi-fo-nepali-clock-time-picker-panel-header-tag" x-text="toNumber(hour)"
                        x-on:click="setView('hour')" x-bind:area-selected="view === 'hour'"
                        x-bind:class="{
                            'fi-selected': view === 'hour',
                        }">
                    </div>
                    <div class="fi-fo-nepali-clock-time-picker-panel-header-divider">:</div>
                    <div role="option" class="fi-fo-nepali-clock-time-picker-panel-header-tag"
                        x-text="toNumber(minute)" x-on:click="setView('minute')"
                        x-bind:area-selected="view === 'minute'"
                        x-bind:class="{
                            'fi-selected': view === 'minute',
                        }">
                    </div>
                    @if ($hasSeconds)
                        <div class="fi-fo-nepali-clock-time-picker-panel-header-divider">:</div>
                        <div class="fi-fo-nepali-clock-time-picker-panel-header-tag" x-text="toNumber(second)"
                            x-on:click="setView('second')" x-bind:area-selected="view === 'second'"
                            x-bind:class="{
                                'fi-selected': view === 'second',
                            }">
                        </div>
                    @endif
                    <div class="fi-fo-nepali-clock-time-picker-panel-header-tag-off" x-text="meridian"></div>
                </div>

                <div class="fi-fo-nepali-clock-time-picker-clock-wrapper">
                    <div x-ref="clock" class="fi-fo-nepali-clock-time-picker-clock">
                        <template x-if="view === 'hour'">
                            <div x-transition:enter.duration.500ms x-transition:leave.duration.400ms
                                x-transition:enter.scale.80 x-transition:leave.scale.90>
                                <template x-for="h in getLength(12, 1)" x-bind:key="h">
                                    <div class="fi-fo-nepali-clock-time-picker-clock-tag" :style="getMarkStyle(h, 12)"
                                        x-text="toNumber(h)" x-on:click="selectHour(h)"
                                        x-bind:aria-selected="h === hour"
                                        x-bind:class="{
                                            'fi-selected': h === hour,
                                            'fi-disabled': hourDisabled(h),
                                        }">
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="view === 'minute'">
                            <div x-transition:enter.duration.500ms x-transition:leave.duration.400ms
                                x-transition:enter.scale.80 x-transition:leave.scale.90>
                                <template x-for="m in getLength(60, 0)" x-bind:key="m">
                                    <div class="fi-fo-nepali-clock-time-picker-clock-tag" :style="getMarkStyle(m, 60)"
                                        x-text="m%5?'':toNumber(m)" x-on:click="selectMinute(m)"
                                        x-bind:aria-selected="m === minute"
                                        x-bind:class="{
                                            'fi-selected': m === minute,
                                            'fi-disabled': minuteDisabled(m),
                                        }">
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="view === 'second'">
                            <div x-transition:enter.duration.500ms x-transition:leave.duration.400ms
                                x-transition:enter.scale.80 x-transition:leave.scale.90>
                                <template x-for="s in getLength(60, 0)" x-bind:key="s">
                                    <div class="fi-fo-nepali-clock-time-picker-clock-tag" :style="getMarkStyle(s, 60)"
                                        x-text="s%5?'':toNumber(s)" x-on:click="selectSecond(s)"
                                        x-bind:aria-selected="s === second"
                                        x-bind:class="{
                                            'fi-selected': s === second,
                                            'fi-disabled': secondDisabled(s),
                                        }">
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div class="fi-fo-nepali-clock-time-picker-clock-hand" @pointerdown.prevent="isDragging = true"
                            @pointermove.window="onDragClockHand($event)" @pointerup.window="isDragging = false"
                            :style="{ transform: `translate(-50%, -100%) rotate(${handangle}deg)` }">
                            <div class="fi-fo-nepali-clock-time-picker-hand-indicator"></div>
                        </div>
                        <div class="fi-fo-nepali-clock-time-picker-clock-center-dot"></div>
                    </div>

                    <div class="fi-fo-nepali-clock-time-picker-clock-meridian">
                        <div class="fi-fo-nepali-clock-time-picker-clock-meridian-tag" x-on:click="setMeridian('AM')"
                            x-bind:class="{
                                'fi-selected': meridian === 'AM',
                            }">
                            AM
                        </div>
                        <div class="fi-fo-nepali-clock-time-picker-clock-meridian-tag" x-on:click="setMeridian('PM')"
                            x-bind:class="{
                                'fi-selected': meridian === 'PM',
                            }">
                            PM
                        </div>
                    </div>
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
