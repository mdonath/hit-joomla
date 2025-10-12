const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 3600;
const SECONDS_IN_DAY = 86400;

/**
 * A simple countdown timer that counts down to a specific date.
 */
export default class HitCounter {

    constructor(endDate, someOffset, elementId) {
        this.targetDate = new Date(endDate);
        this.someOffset = someOffset;
        this.selectors = new Selectors(elementId);
    
        this.numberTransitionAllFields();
    }

    /**
     * Starts the timer with a delay of 1 second.
     */
    startTimer = () => {
        setTimeout(this.updateTimer.bind(this), 1000);
    }

    /**
     * Updates the timer every second.
     */
    updateTimer = () => {
        const { days, hours, minutes, seconds } = this.calculateDifference();

        jQuery(this.selectors.days).text(days);
        jQuery(this.selectors.hours).text(hours);
        jQuery(this.selectors.minutes).text(minutes);
        jQuery(this.selectors.seconds).text(seconds);

        setTimeout(this.updateTimer.bind(this), 1000);
    }

    /**
     * @returns Calculated difference between current date and target date in days, hours, minutes and seconds.
     */
    calculateDifference = () => {
        const currentDate = new Date();
        currentDate.setTime(currentDate.getTime() + this.someOffset * 3600000);

        const diff = Math.abs(Math.floor((currentDate - this.targetDate) / 1000));
        var seconds = diff;

        const days = Math.floor(diff / SECONDS_IN_DAY);
        seconds = diff - (days * SECONDS_IN_DAY);

        const hours = Math.floor(seconds / SECONDS_IN_HOUR);
        seconds = seconds - (hours * SECONDS_IN_HOUR);

        const minutes = Math.floor(seconds / SECONDS_IN_MINUTE);
        seconds = seconds - (minutes * SECONDS_IN_MINUTE);

        return { days, hours, minutes, seconds };
    }

    /**
     * Animates the transition of all fields (days, hours, minutes, seconds).
     */
    numberTransitionAllFields = () => {
        const { days, hours, minutes, seconds } = this.calculateDifference();

        this.numberTransition(this.selectors.days, days, 1000, "easeOutQuad");
        this.numberTransition(this.selectors.hours, hours, 1000, "easeOutQuad");
        this.numberTransition(this.selectors.minutes, minutes, 1000, "easeOutQuad");
        this.numberTransition(this.selectors.seconds, seconds, 1000, "easeOutQuad");
    }

    /**
     * Animates the transition of a single field.
     *
     * @param {string} id The jQuery selector of the field to animate.
     * @param {number} endPoint The end point of the transition.
     * @param {number} transitionDuration The duration of the transition in milliseconds.
     * @param {string} transitionEase The easing function to use for the transition.
     */
    numberTransition = (id, endPoint, transitionDuration, transitionEase) => {
        jQuery({ numberCount: $(id).text() })
            .animate(
                {   
                    numberCount: endPoint
                },
                {
                    duration: transitionDuration,
                    easing: transitionEase,
                    step: function () {
                        jQuery(id).text(Math.floor(this.numberCount));
                    },
                    complete: function () {
                        jQuery(id).text(this.numberCount);
                    }
                }
            );
    }

}

/**
 * A helper class to manage the jQuery selectors for the countdown parts.
 */
class Selectors {

    /**
     * Generates a jQuery selector for a specific part of the countdown.
     */
    static selector = (id, part) => {
        return '#' + id + ' #' + part + ' .number';
    }

    constructor(elementId) {
        this.days = Selectors.selector(elementId, 'days')
        this.hours = Selectors.selector(elementId, 'hours');
        this.minutes = Selectors.selector(elementId, 'minutes');
        this.seconds = Selectors.selector(elementId, 'seconds');
    }

}
