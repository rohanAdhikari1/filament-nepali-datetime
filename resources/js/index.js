import nepalidayjs from 'nepali-day-js'
import { localenumber } from 'nepali-day-js/locale'

export default function dateTimePickerFormComponent({
    defaultFocusedDate,
    displayFormat,
    firstDayOfWeek,
    isAutofocused,
    locale,
    shouldCloseOnDateSelection,
    disableNavWhenOutOfRange,
    is12HourFormat,
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

        isPrevActive: true,

        isNextActive: true,

        minute: null,

        second: null,

        meridian: null,

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

            if (!this.dateIsInRange(date)) {
                date = null
            }

            this.hour = date?.hour() ?? 0
            this.minute = date?.minute() ?? 0
            this.second = date?.second() ?? 0

            if (is12HourFormat) {
                if (this.hour >= 12) {
                    this.meridian = 'pm'
                    if (this.hour > 12) this.hour -= 12
                } else {
                    this.meridian = 'am'
                    this.hour = this.hour === 0 ? 1 : this.hour
                }
            }

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

                if (disableNavWhenOutOfRange) {
                    this.checkDateRange()
                }
                this.setupDaysGrid()
            })

            this.$watch('hour', () => {
                let hour = +this.hour

                if (!Number.isInteger(hour)) {
                    this.hour = is12HourFormat ? 1 : 0
                } else if (is12HourFormat && (hour > 12 || hour < 1)) {
                    this.hour = 1
                } else if (!is12HourFormat && (hour > 23 || hour < 0)) {
                    this.hour = 0
                }

                if (this.isClearingState) return

                let date = this.getSelectedDate() ?? this.focusedDate

                let adjustedHour = this.hour
                if (is12HourFormat) {
                    if (this.meridian === 'pm' && adjustedHour !== 12)
                        adjustedHour += 12
                    if (this.meridian === 'am' && adjustedHour === 12)
                        adjustedHour = 0
                }

                this.setState(date.hour(adjustedHour))
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

            this.$watch('meridian', () => {
                if (!is12HourFormat || this.isClearingState) return
                let adjustedHour = this.hour
                if (this.meridian === 'pm' && adjustedHour !== 12)
                    adjustedHour += 12
                if (this.meridian === 'am' && adjustedHour === 12)
                    adjustedHour = 0
                this.setState(date.hour(adjustedHour))
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

                if (!this.dateIsInRange(date)) {
                    date = null
                }

                const newHour24 = date?.hour() ?? 0
                if (is12HourFormat) {
                    this.meridian = newHour24 >= 12 ? 'pm' : 'am'
                    this.hour = newHour24 % 12 || 1
                } else if (this.hour !== newHour24) {
                    this.hour = newHour24
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

            this.hour = is12HourFormat ? 1 : 0
            this.minute = 0
            this.second = 0
            if (is12HourFormat) this.meridian = 'am'
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

        dateIsInRange(date) {
            if (
                this.getMaxDate() !== null &&
                date?.isAfter(this.getMaxDate())
            ) {
                return false
            }
            if (
                this.getMinDate() !== null &&
                date?.isBefore(this.getMinDate())
            ) {
                return false
            }
            return true
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
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.subtract(1, 'day'))
            ) {
                return
            }
            this.focusedDate.subDay()
        },

        focusPreviousWeek() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.subtract(1, 'week'))
            ) {
                return
            }
            this.focusedDate.subWeek()
        },

        focusPreviousMonth() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.subtract(1, 'month'))
            ) {
                return
            }

            this.focusedDate.subMonth()
        },

        focusPreviousYear() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.subtract(1, 'year'))
            ) {
                return
            }

            this.focusedDate.subYear()
        },

        focusNextDay() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.add(1, 'day'))
            ) {
                return
            }

            this.focusedDate.addDay()
        },

        focusNextWeek() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.add(1, 'week'))
            ) {
                return
            }
            this.focusedDate.addWeek()
        },

        focusNextMonth() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.add(1, 'month'))
            ) {
                return
            }

            this.focusedDate.addMonth()
        },

        focusNextYear() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(this.focusedDate.add(1, 'year'))
            ) {
                return
            }

            this.focusedDate.addYear()
        },

        focusStartOfWeek() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(
                    this.focusedDate.subtract(
                        this.focusedDate.dayOfWeek() - 1,
                        'week',
                    ),
                )
            ) {
                return
            }

            this.focusedDate.subDays(this.focusedDate.dayOfWeek() - 1)
        },
        focusEndOfWeek() {
            this.focusedDate ??= nepalidayjs()
            if (
                disableNavWhenOutOfRange &&
                !this.dateIsInRange(
                    this.focusedDate.add(
                        7 - this.focusedDate.dayOfWeek(),
                        'week',
                    ),
                )
            ) {
                return
            }

            this.focusedDate.addDays(7 - this.focusedDate.dayOfWeek())
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

        checkDateRange() {
            let nextMonthDate = this.focusedDate.add(1, 'month'),
                prevMonthDate = this.focusedDate.subtract(1, 'month')
            this.isNextActive = true
            this.isPrevActive = true
            if (!this.dateIsInRange(nextMonthDate)) {
                this.isNextActive = false
            }
            if (!this.dateIsInRange(prevMonthDate)) {
                this.isPrevActive = false
            }
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

            let adjustedHour = this.hour ?? 0
            if (is12HourFormat) {
                if (this.meridian === 'pm' && adjustedHour !== 12)
                    adjustedHour += 12
                if (this.meridian === 'am' && adjustedHour === 12)
                    adjustedHour = 0
            }

            this.state = date
                .hour(adjustedHour)
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
