import HitCountdown from "./HitCountdown.js";

if (!window.Joomla) {
    throw new Error("Joomla API was not properly initialised");
}

/**
 * A global function to start the countdown timer.
 */
window.startTimer = (endDate, someOffset, elementId) => {
    $(() => {
        new HitCountdown(endDate, someOffset, elementId).startTimer();
    });
};
