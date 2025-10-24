import { localenumber } from 'nepali-day-js/locale'

export default function clockTimePickerFormComponent({
    defaultFocusedTime,
    hasSeconds,
    displayFormat,
    defaultView,
    isAutofocused,
    locale,
    shouldCloseOnTimeSelection,
    state,
}) {
    return {
        displayText: '',

        view: null,

        isDragging: false,

        hour: null,

        minute: null,

        second: null,

        meridian: null,

        state,

        shortHours: [],

        hours: [],

        minutes: [],

        seconds: [],

        handangle: 0,

        init() {
            this.view = defaultView ?? 'hour'
            const timeArray = this.parseTimeToArray(defaultFocusedTime) || [
                1, 0, 0,
            ]
            let [hour, minute, second] = timeArray
            this.meridian = hour >= 12 ? 'PM' : 'AM'
            this.hour = hour % 12 || 12
            this.minute = minute
            this.second = second

            this.setDisplayText()
            if (isAutofocused) {
                this.$nextTick(() =>
                    this.togglePanelVisibility(this.$refs.button),
                )
            }
        },

        toNumber(num) {
            return localenumber(String(num).padStart(2, '0'), locale)
        },

        parseTimeToArray(timeStr) {
            if (!timeStr) return null

            return timeStr.split(':').map(Number)
        },

        timeArrayToSeconds([h, m, s]) {
            return h * 3600 + m * 60 + s
        },

        formatTimeWithFormatString(timeArray, format) {
            if (!timeArray || !format) return ''
            let [hour, minute, second] = timeArray
            let meridian = hour >= 12 ? 'PM' : 'AM'
            hour = hour % 12 || 12
            const replacements = {
                HH: String(timeArray[0]).padStart(2, '0'),
                H: String(timeArray[0]),
                hh: String(hour).padStart(2, '0'),
                h: String(hour),
                mm: String(minute).padStart(2, '0'),
                m: String(minute),
                ss: String(second).padStart(2, '0'),
                s: String(second),
                A: meridian,
                a: meridian.toLowerCase(),
            }

            let formatted = format
            Object.keys(replacements).forEach((token) => {
                formatted = formatted.replace(
                    new RegExp(token, 'g'),
                    replacements[token],
                )
            })
            return formatted
        },

        clearState() {
            this.setState(null)
            this.hour = 1
            this.minute = 0
            this.second = 0
            this.meridian = 'AM'
        },

        onDragClockHand(e) {
            if (!this.isDragging) return
            const rect = this.$refs.clock.getBoundingClientRect()
            const cx = rect.left + rect.width / 2
            const cy = rect.top + rect.height / 2
            const dx = e.clientX - cx
            const dy = e.clientY - cy
            let angle = Math.atan2(dy, dx) * (180 / Math.PI) + 90
            if (angle < 0) angle += 360
            const step = 360 / this.getCurrentUnitMax()
            let unitvalue = Math.round(angle / step)
            this.setCurrentUnitValue(unitvalue)
        },

        focusNext() {
            this.setCurrentUnitValue(this.getCurrentUnitValue() + 1)
        },

        focusPrev() {
            this.setCurrentUnitValue(this.getCurrentUnitValue() - 1)
        },

        focusNextView(select = false) {
            const nextViews = {
                hour: 'minute',
                minute: hasSeconds ? 'second' : null,
            }

            if (nextViews[this.view]) {
                this.setView(nextViews[this.view])
            } else if (select) {
                this.selectTime()
                this.resetView()
            }
        },

        focusPrevView() {
            const prevViews = {
                second: 'minute',
                minute: 'hour',
            }

            if (prevViews[this.view]) {
                this.setView(prevViews[this.view])
            }
        },

        isTimeDisabled(timeArray) {
            if (!timeArray) return true
            if (
                this.$refs?.disabledTimes &&
                JSON.parse(this.$refs.disabledTimes.value ?? []).some(
                    (disabled) => {
                        const disabledSeconds =
                            this.timeArrayToSeconds(disabled)

                        return timeSeconds === disabledSeconds
                    },
                )
            ) {
                return true
            }
            if (this.disabledTimes?.length) {
                for (let disabled of this.disabledTimes) {
                    const disabledSeconds = this.timeArrayToSeconds(disabled)
                    if (timeSeconds === disabledSeconds) return true
                }
            }
            const timeSeconds = this.timeArrayToSeconds(timeArray)
            const minTimeArray = this.getMinTime()
            if (minTimeArray) {
                const minSeconds = this.timeArrayToSeconds(minTimeArray)
                if (timeSeconds < minSeconds) return true
            }
            const maxTimeArray = this.getMaxTime()
            if (maxTimeArray) {
                const maxSeconds = this.timeArrayToSeconds(maxTimeArray)
                if (timeSeconds > maxSeconds) return true
            }

            return false
        },

        hourDisabled(hour) {
            return this.isTimeDisabled([hour, this.minute, this.second])
        },

        minuteDisabled(minute) {
            return this.isTimeDisabled([this.hour, minute, this.second])
        },

        secondDisabled(second) {
            return this.isTimeDisabled([this.hour, this.minute, second])
        },

        getMaxTime() {
            const timeStr = this.$refs.maxTime?.value
            return this.parseTimeToArray(timeStr)
        },

        getMinTime() {
            const timeStr = this.$refs.minTime?.value
            return this.parseTimeToArray(timeStr)
        },

        getSelectedTimeArray() {
            let hour = this.hour
            if (this.meridian?.toUpperCase() === 'PM' && hour !== 12) hour += 12
            if (this.meridian?.toUpperCase() === 'AM' && hour === 12) hour = 0
            return [hour, this.minute, this.second]
        },

        getSelectedTime() {
            if (this.state === undefined) {
                return null
            }

            if (this.state === null) {
                return null
            }

            return time
        },

        getSelectedTimeFormatted(format = 'HH:mm:ss') {
            return this.formatTimeWithFormatString(
                this.getSelectedTimeArray(),
                format,
            )
        },

        getCurrentUnitMax() {
            let unit = this.view
            if (unit === 'hour') return 12
            return 60
        },

        getCurrentUnitValue() {
            let unit = this.view
            if (unit === 'hour') return this.hour
            if (unit === 'minute') return this.minute
            return this.second
        },

        getMarkStyle(n, totalMarks = 12, radiusPercent = 40) {
            const angle = (n / totalMarks) * 360
            const rad = (angle * Math.PI) / 180
            const x = 50 + radiusPercent * Math.sin(rad)
            const y = 50 - radiusPercent * Math.cos(rad)
            return `left: ${x}%; top: ${y}%; transform: translate(-50%, -50%);`
        },
        togglePanelVisibility() {
            if (!this.isOpen()) {
                this.setupTimeLayout()
            }

            this.$refs.panel.toggle(this.$refs.button)
        },

        setCurrentUnitValue(value) {
            let unit = this.view
            if (unit === 'hour') {
                this.setHour(value)
                return
            }
            if (unit === 'minute') {
                this.setMinute(value)
                return
            }
            if (unit === 'second') {
                this.setSecond(value)
                return
            }
        },

        setDisplayText() {
            if (this.state === null) {
                this.displayText = ''
                return
            }
            let time = this.parseTimeToArray(this.state) || [1, 0, 0]
            this.displayText = localenumber(
                this.formatTimeWithFormatString(time, displayFormat),
                locale,
            )
        },

        getLength(length, min) {
            return Array.from(
                {
                    length: length,
                },
                (_, i) => i + min,
            )
        },

        updateHandAngle() {
            const totalMarks = this.getCurrentUnitMax()
            const newAngle = (this.getCurrentUnitValue() / totalMarks) * 360
            const diff = newAngle - (this.handangle % 360)
            if (diff < -180) {
                this.handangle += 360 + diff
            } else if (diff > 180) {
                this.handangle -= 360 - diff
            } else {
                this.handangle += diff
            }
        },

        setupTimeLayout() {
            this.shortHours = this.getLength(12, 1)
            this.hours = this.getLength(12, 13)
            this.minutes = this.getLength(60, 0)
            this.seconds = this.getLength(60, 0)
            this.updateHandAngle()
        },

        selectTime() {
            if (this.isTimeDisabled(this.getSelectedTimeArray())) {
                return
            }
            this.setState(this.getSelectedTimeFormatted())
            if (shouldCloseOnTimeSelection) {
                this.togglePanelVisibility()
            }
        },

        setHour(hour) {
            if (this.hour === hour) {
                return
            }
            let adjustedHour = ((hour % 12) + 12) % 12 || 12
            this.hour = adjustedHour
            this.updateHandAngle()
        },

        selectHour(hour) {
            this.setHour(hour)
            this.reFocusInput()
            if (shouldCloseOnTimeSelection) this.focusNextView(true)
        },

        setMinute(minute) {
            if (this.minute === minute) {
                return
            }
            let adjustedMinute = ((minute % 60) + 60) % 60
            this.minute = adjustedMinute
            this.updateHandAngle()
        },

        selectMinute(minute) {
            this.setMinute(minute)
            this.reFocusInput()
            if (shouldCloseOnTimeSelection) this.focusNextView(true)
        },

        setSecond(second) {
            if (this.second === second) {
                return
            }
            let adjustedSecond = ((second % 60) + 60) % 60
            this.second = adjustedSecond
            this.updateHandAngle()
        },

        selectSecond(second) {
            this.setSecond(second)
            this.reFocusInput()
            if (shouldCloseOnTimeSelection) this.focusNextView(true)
        },

        setMeridian(meridian) {
            this.meridian = meridian
            this.reFocusInput()
        },

        setView(view) {
            if (this.view === view) {
                return
            }
            this.view = view
            this.updateHandAngle()
            this.reFocusInput()
        },

        resetView() {
            this.view = defaultView ?? 'hour'
        },

        setState(date) {
            if (date === null) {
                this.state = null
                this.setDisplayText()
                return
            }
            this.state = date
            this.setDisplayText()
        },

        reFocusInput() {
            this.$nextTick(() => this.$refs.displaytext?.focus())
        },

        isOpen() {
            return this.$refs.panel?.style.display === 'block'
        },
    }
}
