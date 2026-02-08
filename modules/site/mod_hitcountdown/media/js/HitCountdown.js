const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 3600;
const SECONDS_IN_DAY = 86400;

/**
 * A simple countdown timer that counts down to a specific date and time.
 */
export default class HitCountdown {
    #intervalID;

    #targetDate;
    #someOffset;
    #selectors;

    #finished = false;

    /**
     * Creates a HIT Counter with supplied parameters.
     *
     * @param {Date|string} targetDate
     * @param {int} someOffset
     * @param {string} elementId
     */
    constructor(targetDate, someOffset, elementId) {
        this.#targetDate = new Date(targetDate);
        this.#someOffset = someOffset;
        this.#selectors = new Selectors(elementId);

        this.#numberTransitionAllFields();
    }

    /**
     * Starts a countdown timer with a delay of 1 second.
     */
    startTimer = () => {
        if (!this.#finished) {
            this.#intervalID = setInterval(this.updateTimer.bind(this), 1000);
        }
    };

    /**
     * Stops the countdown timer.
     */
    stopTimer = () => {
        clearInterval(this.#intervalID);
    };

    /**
     * Updates the countdown timer and stops it when it reaches zero.
     */
    updateTimer = () => {
        const { days, hours, minutes, seconds, finished } =
            this.#calculateDifference();

        $(this.selectors.days).text(days);
        $(this.selectors.hours).text(hours);
        $(this.selectors.minutes).text(minutes);
        $(this.selectors.seconds).text(seconds);

        if (finished) {
            this.stopTimer();
        }
    };

    /**
     * Does not account for changes in DST between now and the target date.
     *
     * @returns Calculated difference between current date and target date in days, hours, minutes and seconds.
     */
    #calculateDifference = () => {
        const currentDate = new Date();
        currentDate.setTime(currentDate.getTime() + this.#someOffset * 3600000);

        const signedDiff = Math.floor((currentDate - this.#targetDate) / 1000);
        const finished = signedDiff >= 0;
        const diff = Math.abs(signedDiff);

        let seconds = diff;

        const days = Math.floor(diff / SECONDS_IN_DAY);
        seconds = diff - days * SECONDS_IN_DAY;

        const hours = Math.floor(seconds / SECONDS_IN_HOUR);
        seconds = seconds - hours * SECONDS_IN_HOUR;

        const minutes = Math.floor(seconds / SECONDS_IN_MINUTE);
        seconds = seconds - minutes * SECONDS_IN_MINUTE;

        return { days, hours, minutes, seconds, finished };
    };

    /**
     * Animates the transition of all fields (days, hours, minutes, seconds).
     */
    #numberTransitionAllFields = () => {
        let { days, hours, minutes, seconds, finished } =
            this.#calculateDifference();
        this.#finished = finished;
        seconds--;
        if (finished) {
            days = 0;
            hours = 0;
            minutes = 0;
            seconds = 0;
        }

        this.#numberTransition(this.#selectors.days, days);
        this.#numberTransition(this.#selectors.hours, hours);
        this.#numberTransition(this.#selectors.minutes, minutes);
        this.#numberTransition(this.#selectors.seconds, seconds);
    };

    /**
     * Animates the transition of a single field.
     *
     * @param {string} id The jQuery selector of the field to animate.
     * @param {number} endPoint The end point of the transition.
     */
    #numberTransition = (id, endPoint) => {
        $({ numberCount: $(id).text() }).animate(
            {
                numberCount: endPoint,
            },
            {
                duration: 1000,
                easing: "easeOutQuad",
                step: (now, tween) =>
                    $(id).text(Math.floor(tween.elem.numberCount)),
                complete: () => $(id).text(endPoint),
            },
        );
    };

    /**
     * Getter for selectors (for use in tests).
     */
    get selectors() {
        return this.#selectors;
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
        return "#" + id + " #" + part + " .number";
    };

    constructor(elementId) {
        this.days = Selectors.selector(elementId, "days");
        this.hours = Selectors.selector(elementId, "hours");
        this.minutes = Selectors.selector(elementId, "minutes");
        this.seconds = Selectors.selector(elementId, "seconds");
    }
}
