import nepalidayjs from 'nepali-day-js'
import { localenumber } from 'nepali-day-js/locale'

export default function dateTimePickerFormComponent({
    defaultFocusedDate,
    displayFormat,
    firstDayOfWeek,
    isAutofocused,
    locale,
    shouldCloseOnDateSelection,
    state,
}) {
    return {
        daysInFocusedMonth: [],

        displayText: '',

        emptyDaysInFocusedMonth: [],

        focusedDate: null,

        focusedMonth: null,

        focusedYear: null,

        hour: null,

        isClearingState: false,

        minute: null,

        second: null,

        state,

        defaultFocusedDate,

        dayLabels: [],

        months: [],

        years: [],

        init() {
            this.$nextTick(() => {
                this.focusedDate ??=
                    this.getDefaultFocusedDate() ?? nepalidayjs()
                this.focusedMonth ??= this.focusedDate.month()
                this.focusedYear ??= this.focusedDate.year()
            })

            let date =
                this.getSelectedDate() ??
                this.getDefaultFocusedDate() ??
                nepalidayjs()

            if (this.getMaxDate() !== null && date.isAfter(this.getMaxDate())) {
                date = null
            } else if (
                this.getMinDate() !== null &&
                date.isBefore(this.getMinDate())
            ) {
                date = null
            }

            this.hour = date?.hour() ?? 0
            this.minute = date?.minute() ?? 0
            this.second = date?.second() ?? 0

            this.setDisplayText()
            this.setMonths()
            this.setYears()
            this.setDayLabels()

            if (isAutofocused) {
                this.$nextTick(() =>
                    this.togglePanelVisibility(this.$refs.button),
                )
            }

            this.$watch('focusedMonth', () => {
                this.focusedMonth = +this.focusedMonth

                if (this.focusedDate.month() === this.focusedMonth) {
                    return
                }

                this.focusedDate = this.focusedDate.month(this.focusedMonth)
            })

            this.$watch('focusedYear', () => {
                if (this.focusedYear?.length > 4) {
                    this.focusedYear = this.focusedYear.substring(0, 4)
                }

                if (!this.focusedYear || this.focusedYear?.length !== 4) {
                    return
                }

                let year = +this.focusedYear

                if (!Number.isInteger(year)) {
                    year = nepalidayjs().year()

                    this.focusedYear = year
                }

                if (this.focusedDate.year() === year) {
                    return
                }

                this.focusedDate = this.focusedDate.year(year)
            })

            this.$watch('focusedDate', () => {
                let month = this.focusedDate.month()
                let year = this.focusedDate.year()

                if (this.focusedMonth !== month) {
                    this.focusedMonth = month
                }

                if (this.focusedYear !== year) {
                    this.focusedYear = year
                }

                this.setupDaysGrid()
            })

            this.$watch('hour', () => {
                let hour = +this.hour

                if (!Number.isInteger(hour)) {
                    this.hour = 0
                } else if (hour > 23) {
                    this.hour = 0
                } else if (hour < 0) {
                    this.hour = 23
                } else {
                    this.hour = hour
                }

                if (this.isClearingState) {
                    return
                }

                let date = this.getSelectedDate() ?? this.focusedDate

                this.setState(date.hour(this.hour ?? 0))
            })

            this.$watch('minute', () => {
                let minute = +this.minute

                if (!Number.isInteger(minute)) {
                    this.minute = 0
                } else if (minute > 59) {
                    this.minute = 0
                } else if (minute < 0) {
                    this.minute = 59
                } else {
                    this.minute = minute
                }

                if (this.isClearingState) {
                    return
                }

                let date = this.getSelectedDate() ?? this.focusedDate

                this.setState(date.minute(this.minute ?? 0))
            })

            this.$watch('second', () => {
                let second = +this.second

                if (!Number.isInteger(second)) {
                    this.second = 0
                } else if (second > 59) {
                    this.second = 0
                } else if (second < 0) {
                    this.second = 59
                } else {
                    this.second = second
                }

                if (this.isClearingState) {
                    return
                }

                let date = this.getSelectedDate() ?? this.focusedDate

                this.setState(date.second(this.second ?? 0))
            })

            this.$watch('state', () => {
                if (this.state === undefined) {
                    return
                }

                let date = this.getSelectedDate()

                if (date === null) {
                    this.clearState()

                    return
                }

                if (
                    this.getMaxDate() !== null &&
                    date?.isAfter(this.getMaxDate())
                ) {
                    date = null
                }
                if (
                    this.getMinDate() !== null &&
                    date?.isBefore(this.getMinDate())
                ) {
                    date = null
                }

                const newHour = date?.hour() ?? 0
                if (this.hour !== newHour) {
                    this.hour = newHour
                }

                const newMinute = date?.minute() ?? 0
                if (this.minute !== newMinute) {
                    this.minute = newMinute
                }

                const newSecond = date?.second() ?? 0
                if (this.second !== newSecond) {
                    this.second = newSecond
                }

                this.setDisplayText()
            })
        },

        toNumber(num) {
            return localenumber(num, locale)
        },

        clearState() {
            this.isClearingState = true

            this.setState(null)

            this.hour = 0
            this.minute = 0
            this.second = 0

            this.$nextTick(() => (this.isClearingState = false))
        },

        dateIsDisabled(date) {
            if (
                this.$refs?.disabledDates &&
                JSON.parse(this.$refs.disabledDates.value ?? []).some(
                    (disabledDate) => {
                        disabledDate = nepalidayjs(disabledDate)

                        if (!disabledDate.isValid()) {
                            return false
                        }

                        return disabledDate.isSame(date)
                    },
                )
            ) {
                return true
            }
            if (this.getMaxDate() && date.isAfter(this.getMaxDate())) {
                return true
            }
            if (this.getMinDate() && date.isBefore(this.getMinDate())) {
                return true
            }

            return false
        },

        dayIsDisabled(day) {
            this.focusedDate ??= nepalidayjs()

            return this.dateIsDisabled(this.focusedDate.day(day))
        },

        dayIsSelected(day) {
            let selectedDate = this.getSelectedDate()

            if (selectedDate === null) {
                return false
            }

            this.focusedDate ??= nepalidayjs()

            return (
                selectedDate.day() === day &&
                selectedDate.month() === this.focusedDate.month() &&
                selectedDate.year() === this.focusedDate.year()
            )
        },

        dayIsToday(day) {
            let date = nepalidayjs()
            this.focusedDate ??= date

            return (
                date.day() === day &&
                date.month() === this.focusedDate.month() &&
                date.year() === this.focusedDate.year()
            )
        },

        focusPreviousDay() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.subDay()
        },

        focusPreviousWeek() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.subWeek()
        },

        focusPreviousMonth() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.subMonth()
        },

        focusPreviousYear() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.subYear()
        },

        focusNextDay() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.addDay()
        },

        focusNextWeek() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.addWeek()
        },

        focusNextMonth() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.addMonth()
        },

        focusNextYear() {
            this.focusedDate ??= nepalidayjs()

            this.focusedDate.addYear()
        },

        getDayLabels() {
            const labels = nepalidayjs.weekdaysShort(locale)

            if (firstDayOfWeek === 1) {
                return labels
            }

            return [
                ...labels.slice(firstDayOfWeek - 1),
                ...labels.slice(0, firstDayOfWeek - 1),
            ]
        },

        getMaxDate() {
            let date = nepalidayjs.maxDate(locale)
            if (this.$refs.maxDate.value) {
                date = nepalidayjs(this.$refs.maxDate.value)
            }
            return date.isValid() ? date : null
        },

        getMinDate() {
            let date = nepalidayjs.minDate(locale)
            if (this.$refs.minDate?.value) {
                date = nepalidayjs(this.$refs.minDate.value)
            }
            return date.isValid() ? date : null
        },

        getSelectedDate() {
            if (this.state === undefined) {
                return null
            }

            if (this.state === null) {
                return null
            }
            let date = nepalidayjs(this.state)

            if (!date.isValid()) {
                return null
            }

            return date
        },

        getDefaultFocusedDate() {
            if (this.defaultFocusedDate === null) {
                return null
            }

            let defaultFocusedDate = nepalidayjs(this.defaultFocusedDate)

            if (!defaultFocusedDate.isValid()) {
                return null
            }

            return defaultFocusedDate
        },

        togglePanelVisibility() {
            if (!this.isOpen()) {
                this.focusedDate =
                    this.getSelectedDate() ??
                    this.focusedDate ??
                    this.getMinDate() ??
                    nepalidayjs()

                this.setupDaysGrid()
            }

            this.$refs.panel.toggle(this.$refs.button)
        },

        selectDate(day = null) {
            if (day) {
                this.setFocusedDay(day)
            }

            this.focusedDate ??= nepalidayjs()

            this.setState(this.focusedDate)

            if (shouldCloseOnDateSelection) {
                this.togglePanelVisibility()
            }
        },

        setDisplayText() {
            this.displayText = this.getSelectedDate()
                ? this.getSelectedDate().setLocale(locale).format(displayFormat)
                : ''
        },

        setYears() {
            this.years = Array.from(
                { length: nepalidayjs.maxYear() - nepalidayjs.minYear() + 1 },
                (_, i) => nepalidayjs.minYear() + i,
            )
        },

        setMonths() {
            this.months = nepalidayjs.months(locale)
        },

        setDayLabels() {
            this.dayLabels = this.getDayLabels()
        },

        setupDaysGrid() {
            this.focusedDate ??= nepalidayjs()

            this.emptyDaysInFocusedMonth = Array.from(
                {
                    length: this.focusedDate
                        .day(8 - firstDayOfWeek)
                        .dayOfWeek(),
                },
                (_, i) => i + 1,
            )
            this.daysInFocusedMonth = Array.from(
                {
                    length: this.focusedDate.daysInMonth(),
                },
                (_, i) => i + 1,
            )
        },

        setFocusedDay(day) {
            this.focusedDate = (this.focusedDate ?? nepalidayjs()).day(day)
        },

        setState(date) {
            if (date === null) {
                this.state = null
                this.setDisplayText()

                return
            }
            if (this.dateIsDisabled(date)) {
                return
            }

            this.state = date
                .hour(this.hour ?? 0)
                .minute(this.minute ?? 0)
                .second(this.second ?? 0)
                .format('YYYY-MM-DD HH:mm:ss')

            this.setDisplayText()
        },

        isOpen() {
            return this.$refs.panel?.style.display === 'block'
        },
    }
}
