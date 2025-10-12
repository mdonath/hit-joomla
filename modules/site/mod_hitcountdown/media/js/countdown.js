import HitCounter from "./HitCounter.js";

if (!window.Joomla) {
    throw new Error('Joomla API was not properly initialised');
}

/**
 * A global function to start the countdown timer.
 */
window.startTimer = (endDate, someOffset, elementId) => {
    jQuery(document).ready(() => {
        new HitCounter(endDate, someOffset, elementId)
            .startTimer();
    });
};
