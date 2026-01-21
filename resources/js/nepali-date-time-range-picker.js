import nepalidayjs from 'nepali-day-js'
import { localenumber } from 'nepali-day-js/locale'

function basicNepaliCalendar({
    defaultFocusedDate,
    firstDayOfWeek,
    disableNavWhenOutOfRange,
}) {
    return {
        daysInFocusedMonth: [],
        emptyDaysInFocusedMonth: [],
        focusedDate: null,
        focusedMonth: null,
        focusedYear: null,
        isPrevActive: true,
        isNextActive: true,
        defaultFocusedDate,
        disableNavWhenOutOfRange,
        actions: {},

        setUp() {
            const defaultDate = this.getDefaultFocusedDate() ?? nepalidayjs()
            this.focusedMonth = defaultDate.month()
            this.focusedYear = defaultDate.year()
        },

        afterHydated() {},

        focusedMonthUpdated() {
            this.setupDaysGrid()
        },

        focusedYearUpdated() {
            this.setupDaysGrid()
        },

        setupDaysGrid() {
            if (this.focusedMonth === null || this.focusedYear === null) return
            const date = nepalidayjs()
                .year(this.focusedYear)
                .month(this.focusedMonth)
                .day(1)
            this.emptyDaysInFocusedMonth = Array.from(
                {
                    length: date.day(8 - firstDayOfWeek).dayOfWeek(),
                },
                (_, i) => i + 1,
            )
            this.daysInFocusedMonth = Array.from(
                { length: date.daysInMonth() },
                (_, i) => i + 1,
            )
        },

        getDefaultFocusedDate() {
            if (this.defaultFocusedDate === null) return null
            const date = nepalidayjs(this.defaultFocusedDate)
            return date.isValid() ? date : null
        },

        focusPreviousMonth() {
            if (this.focusedMonth === 1) {
                this.focusedMonth = 12
                this.focusedYear--
            } else {
                this.focusedMonth--
            }
        },

        focusNextMonth() {
            if (this.focusedMonth === 12) {
                this.focusedMonth = 1
                this.focusedYear++
            } else {
                this.focusedMonth++
            }
        },

        dayIsToday(day) {
            let date = nepalidayjs()
            return (
                date.day() === day &&
                date.month() === this.focusedMonth &&
                date.year() === this.focusedYear
            )
        },

        setFocusedDay(day) {
            this.actions.setFocusedDate(
                day,
                this.focusedMonth,
                this.focusedYear,
            )
        },

        selectDate(day) {
            this.setFocusedDay(day)
            this.actions.selectDate()
        },
    }
}

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
        displayText: '',

        isClearingState: false,

        leftcalendar: basicNepaliCalendar({
            firstDayOfWeek,
            defaultFocusedDate: null,
            disableNavWhenOutOfRange,
            actions: {
                setFocusedDate: this.setFocusedDate,
                selectDate: this.selectDate,
            },
        }),

        rightcalendar: basicNepaliCalendar({
            firstDayOfWeek,
            defaultFocusedDate: null,
            disableNavWhenOutOfRange,
            actions: {
                setFocusedDate: this.setFocusedDate,
                selectDate: this.selectDate,
            },
        }),

        startDate: null,

        endDate: null,

        focusedDate: null,

        state,

        dayLabels: [],

        months: [],

        years: [],

        init() {
            this.$nextTick(() => {
                this.leftcalendar.setUp()
                this.rightcalendar.setUp()
                this.focusedDate = nepalidayjs()
            })
            this.leftcalendar.actions = {
                setFocusedDate: this.setFocusedDate.bind(this),
                selectDate: this.selectDate.bind(this),
            }
            this.rightcalendar.actions = {
                setFocusedDate: this.setFocusedDate.bind(this),
                selectDate: this.selectDate.bind(this),
            }

            this.setMonths()
            this.setYears()
            this.setDayLabels()

            if (isAutofocused) {
                this.$nextTick(() =>
                    this.togglePanelVisibility(this.$refs.button),
                )
            }

            this.$watch('leftcalendar.focusedMonth', () => {
                this.leftcalendar.focusedMonthUpdated()
            })
            this.$watch('rightcalendar.focusedMonth', () => {
                this.rightcalendar.focusedMonthUpdated()
            })

            this.$watch('leftcalendar.focusedYear', () => {
                this.leftcalendar.focusedYearUpdated()
            })

            this.$watch('rightcalendar.focusedYear', () => {
                this.rightcalendar.focusedYearUpdated()
            })

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

        dayIsDisabled(day, month, year) {
            const min = this.getMinDate()
            const max = this.getMaxDate()
            if (max) {
                if (
                    year > max.year() ||
                    (year === max.year() && month > max.month()) ||
                    (year === max.year() &&
                        month === max.month() &&
                        day > max.date())
                ) {
                    return true
                }
            }
            if (min) {
                if (
                    year < min.year() ||
                    (year === min.year() && month < min.month()) ||
                    (year === min.year() &&
                        month === min.month() &&
                        day < min.date())
                ) {
                    return true
                }
            }
            return false
        },

        isStartDate(day, month, year) {
            if (this.startDate === null) return false
            return (
                this.startDate.day() === day &&
                this.startDate.month() === month &&
                this.startDate.year() === year
            )
        },

        isEndDate(day, month, year) {
            if (this.endDate === null) return false
            return (
                this.endDate.day() === day &&
                this.endDate.month() === month &&
                this.endDate.year() === year
            )
        },

        isDateFocused(day, month, year) {
            if (this.focusedDate === null) return false
            return (
                this.focusedDate.day() === day &&
                this.focusedDate.month() === month &&
                this.focusedDate.year() === year
            )
        },

        isInRange(day, month, year) {
            if (
                this.startDate === null ||
                (this.endDate === null && this.focusedDate === null)
            ) {
                return false
            }
            const date = this.focusedDate.year(year).month(month).day(day)
            return (
                date.isAfter(this.startDate) &&
                (this.endDate !== null
                    ? date.isBefore(this.endDate)
                    : date.isBefore(this.focusedDate))
            )
        },

        // dateIsInRange(date) {
        //     if (
        //         this.getMaxDate() !== null &&
        //         date?.isAfter(this.getMaxDate())
        //     ) {
        //         return false
        //     }
        //     if (
        //         this.getMinDate() !== null &&
        //         date?.isBefore(this.getMinDate())
        //     ) {
        //         return false
        //     }
        //     return true
        // },

        focusPreviousDay() {
            this.focusedDate ??= nepalidayjs()
            // if (
            //     disableNavWhenOutOfRange &&
            //     !this.dateIsInRange(this.focusedDate.subtract(1, 'day'))
            // ) {
            //     return
            // }
            this.focusedDate.subDay()
        },

        focusNextDay() {
            this.focusedDate ??= nepalidayjs()
            this.focusedDate.addDay()
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

        togglePanelVisibility() {
            if (!this.isOpen()) {
                this.leftcalendar.setupDaysGrid()
                this.rightcalendar.setupDaysGrid()
            }

            this.$refs.panel.toggle(this.$refs.button)
        },

        selectDate() {
            if (
                this.startDate === null ||
                (this.startDate !== null && this.endDate !== null)
            ) {
                this.startDate = nepalidayjs(this.focusedDate)
                this.endDate = null
            } else if (this.startDate !== null && this.endDate === null) {
                if (this.focusedDate.isBefore(this.startDate)) {
                    this.endDate = nepalidayjs(this.startDate)
                    this.startDate = nepalidayjs(this.focusedDate)
                } else {
                    this.endDate = nepalidayjs(this.focusedDate)
                }
            }
            this.setDisplayText()
            //here set state and displaey text
            // this.setState(this.startDate, this.endDate)

            // if (shouldCloseOnDateSelection) {
            //     this.togglePanelVisibility()
            // }
        },

        setDisplayText() {
            // this.displayText = this.state
            //     ? this.getSelectedDate().setLocale(locale).format(displayFormat)
            //     : ''
            this.displayText =
                this.startDate?.setLocale(locale).format(displayFormat) +
                ' - ' +
                this.endDate?.setLocale(locale).format(displayFormat)
        },

        setYears() {
            this.years = Array.from(
                { length: nepalidayjs.maxYear() - nepalidayjs.minYear() + 1 },
                (_, i) => nepalidayjs.minYear() + i,
            )
        },

        setFocusedDate(day, month, year) {
            this.focusedDate = (this.focusedDate ?? nepalidayjs())
                .day(day)
                .year(year)
                .month(month)
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
