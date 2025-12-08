import nepalidayjs from 'nepali-day-js'
import { localenumber } from 'nepali-day-js/locale'

export default function dateTimeRangePickerFormComponent({
    linkedcalendars,
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
        daysInStartFocusedMonth: [],

        daysInEndFocusedMonth: [],

        emptyDaysInStartFocusedMonth: [],

        emptyDaysInEndFocusedMonth: [],

        displayText: '',

        startFocusedDate: null,

        focusedDate: null,

        startFocusedMonth: null,

        endFocusedMonth: null,

        startFocusedYear: null,

        endFocusedYear: null,

        isClearingState: false,

        isStartPrevActive: true,

        isEndPrevActive: true,

        isStartNextActive: true,

        isEndNextActive: true,

        startHour: null,

        endHour: null,

        startMinute: null,

        endMinute: null,

        startSecond: null,

        endSecond: null,

        startMeridian: null,

        endMeridian: null,

        state,

        dayLabels: [],

        months: [],

        years: [],

        init() {
            this.$nextTick(() => {
                let date =
                    this.getDefaultStartFocusedDate() ??
                    nepalidayjs().sub(1, 'month')
                this.startFocusedMonth ??= date.month()
                this.startFocusedYear ??= date.year()

                this.endFocusedDate ??=
                    this.getDefaultEndFocusedDate() ?? nepalidayjs()
                this.endFocusedMonth ??= this.endFocusedDate.month()
                this.endFocusedYear ??= this.endFocusedDate.year()
            })

            let startDate =
                this.getSelectedStartDate() ??
                this.getDefaultStartFocusedDate() ??
                nepalidayjs().sub(1, 'month')

            let endDate =
                this.getSelectedEndDate() ??
                this.getDefaultEndFocusedDate() ??
                nepalidayjs()

            if (!this.dateIsInRange(startDate)) {
                startDate = null
            }
            if (!this.dateIsInRange(endDate)) {
                endDate = null
            }

            this.startHour = startDate?.hour() ?? 0
            this.startMinute = startDate?.minute() ?? 0
            this.startSecond = startDate?.second() ?? 0

            this.endHour = endDate?.hour() ?? 0
            this.endMinute = endDate?.minute() ?? 0
            this.endSecond = endDate?.second() ?? 0

            if (is12HourFormat) {
                if (this.startHour >= 12) {
                    this.startMeridian = 'pm'
                    if (this.startHour > 12) this.startHour -= 12
                } else {
                    this.startMeridian = 'am'
                    this.startHour = this.startHour === 0 ? 1 : this.startHour
                }
                if (this.endHour >= 12) {
                    this.endMeridian = 'pm'
                    if (this.endHour > 12) this.endHour -= 12
                } else {
                    this.endMeridian = 'am'
                    this.endHour = this.endHour === 0 ? 1 : this.endHour
                }
            }
            this.setMonths()
            this.setYears()
            this.setDayLabels()
            // this.setDisplayText()

            if (isAutofocused) {
                this.$nextTick(() =>
                    this.togglePanelVisibility(this.$refs.button),
                )
            }

            // this.$watch('focusedMonth', () => {
            //     this.focusedMonth = +this.focusedMonth

            //     if (this.focusedDate.month() === this.focusedMonth) {
            //         return
            //     }

            //     this.focusedDate = this.focusedDate.month(this.focusedMonth)
            // })

            // this.$watch('focusedYear', () => {
            //     if (this.focusedYear?.length > 4) {
            //         this.focusedYear = this.focusedYear.substring(0, 4)
            //     }

            //     if (!this.focusedYear || this.focusedYear?.length !== 4) {
            //         return
            //     }

            //     let year = +this.focusedYear

            //     if (!Number.isInteger(year)) {
            //         year = nepalidayjs().year()

            //         this.focusedYear = year
            //     }

            //     if (this.focusedDate.year() === year) {
            //         return
            //     }

            //     this.focusedDate = this.focusedDate.year(year)
            // })

            // this.$watch('focusedDate', () => {
            //     let month = this.focusedDate.month()
            //     let year = this.focusedDate.year()

            //     if (this.focusedMonth !== month) {
            //         this.focusedMonth = month
            //     }

            //     if (this.focusedYear !== year) {
            //         this.focusedYear = year
            //     }

            //     if (disableNavWhenOutOfRange) {
            //         this.checkDateRange()
            //     }
            //     this.setupDaysGrid()
            // })

            // this.$watch('hour', () => {
            //     let hour = +this.hour

            //     if (!Number.isInteger(hour)) {
            //         this.hour = is12HourFormat ? 1 : 0
            //     } else if (is12HourFormat && (hour > 12 || hour < 1)) {
            //         this.hour = 1
            //     } else if (!is12HourFormat && (hour > 23 || hour < 0)) {
            //         this.hour = 0
            //     }

            //     if (this.isClearingState) return

            //     let date = this.getSelectedDate() ?? this.focusedDate

            //     let adjustedHour = this.hour
            //     if (is12HourFormat) {
            //         if (this.meridian === 'pm' && adjustedHour !== 12)
            //             adjustedHour += 12
            //         if (this.meridian === 'am' && adjustedHour === 12)
            //             adjustedHour = 0
            //     }

            //     this.setState(date.hour(adjustedHour))
            // })

            // this.$watch('minute', () => {
            //     let minute = +this.minute

            //     if (!Number.isInteger(minute)) {
            //         this.minute = 0
            //     } else if (minute > 59) {
            //         this.minute = 0
            //     } else if (minute < 0) {
            //         this.minute = 59
            //     } else {
            //         this.minute = minute
            //     }

            //     if (this.isClearingState) {
            //         return
            //     }

            //     let date = this.getSelectedDate() ?? this.focusedDate

            //     this.setState(date.minute(this.minute ?? 0))
            // })

            // this.$watch('second', () => {
            //     let second = +this.second

            //     if (!Number.isInteger(second)) {
            //         this.second = 0
            //     } else if (second > 59) {
            //         this.second = 0
            //     } else if (second < 0) {
            //         this.second = 59
            //     } else {
            //         this.second = second
            //     }

            //     if (this.isClearingState) {
            //         return
            //     }

            //     let date = this.getSelectedDate() ?? this.focusedDate

            //     this.setState(date.second(this.second ?? 0))
            // })

            // this.$watch('meridian', () => {
            //     if (!is12HourFormat || this.isClearingState) return
            //     let adjustedHour = this.hour
            //     if (this.meridian === 'pm' && adjustedHour !== 12)
            //         adjustedHour += 12
            //     if (this.meridian === 'am' && adjustedHour === 12)
            //         adjustedHour = 0
            //     this.setState(date.hour(adjustedHour))
            // })

            // this.$watch('state', () => {
            //     if (this.state === undefined) {
            //         return
            //     }

            //     let date = this.getSelectedDate()

            //     if (date === null) {
            //         this.clearState()

            //         return
            //     }

            //     if (!this.dateIsInRange(date)) {
            //         date = null
            //     }

            //     const newHour24 = date?.hour() ?? 0
            //     if (is12HourFormat) {
            //         this.meridian = newHour24 >= 12 ? 'pm' : 'am'
            //         this.hour = newHour24 % 12 || 1
            //     } else if (this.hour !== newHour24) {
            //         this.hour = newHour24
            //     }

            //     const newMinute = date?.minute() ?? 0
            //     if (this.minute !== newMinute) {
            //         this.minute = newMinute
            //     }

            //     const newSecond = date?.second() ?? 0
            //     if (this.second !== newSecond) {
            //         this.second = newSecond
            //     }

            //     this.setDisplayText()
            // })
        },

        toNumber(num) {
            return localenumber(num, locale)
        },

        clearState() {
            this.isClearingState = true

            this.setState(null)
            let hour = 0
            if (is12HourFormat) {
                hour = 1
                this.startMeridian = 'am'
                this.endMeridian = 'am'
            }
            this.startHour = this.endHour = hour
            this.startMinute = this.endMinute = 0
            this.startSecond = this.endSecond = 0
            this.$nextTick(() => (this.isClearingState = false))
        },

        dateIsDisabled(date) {
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

        startDayIsDisabled(day) {
            this.startFocusedDate ??= nepalidayjs().sub(1, 'month')

            return this.dateIsDisabled(this.startFocusedDate.day(day))
        },

        endDayIsDisabled(day) {
            this.endFocusedDate ??= nepalidayjs().sub(1, 'month')

            return this.dateIsDisabled(this.endFocusedDate.day(day))
        },

        startDayIsSelected(day) {
            let selectedDate = this.getSelectedDate()

            if (selectedDate === null) {
                return false
            }

            this.startFocusedDate ??= nepalidayjs().sub(1, 'month')

            return (
                selectedDate.day() === day &&
                selectedDate.month() === this.startFocusedDate.month() &&
                selectedDate.year() === this.startFocusedDate.year()
            )
        },

        endDayIsSelected(day) {
            let selectedDate = this.getSelectedDate()

            if (selectedDate === null) {
                return false
            }

            this.startFocusedDate ??= nepalidayjs().sub(1, 'month')

            return (
                selectedDate.day() === day &&
                selectedDate.month() === this.startFocusedDate.month() &&
                selectedDate.year() === this.startFocusedDate.year()
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
                const custom = nepalidayjs(this.$refs.maxDate.value)
                if (custom.isValid()) date = custom
            }
            return date
        },

        getMinDate() {
            let date = nepalidayjs.minDate(locale)
            if (this.$refs.minDate?.value) {
                const custom = nepalidayjs(this.$refs.minDate.value)
                if (custom.isValid()) date = custom
            }
            return date
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

        getDefaultStartFocusedDate() {
            if (this.defaultStartFocusedDate === null) {
                return null
            }

            let defaultFocusedDate = nepalidayjs(this.defaultStartFocusedDate)
            if (!defaultFocusedDate.isValid()) {
                return null
            }

            return defaultFocusedDate
        },

        getDefaultEndFocusedDate() {
            if (this.defaultEndFocusedDate === null) {
                return null
            }

            let defaultFocusedDate = nepalidayjs(this.defaultEndFocusedDate)
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
