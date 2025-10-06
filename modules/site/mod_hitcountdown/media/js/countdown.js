if (!window.Joomla) {
  throw new Error('Joomla API was not properly initialised');
}

const DAYS_SELECTOR = '#days .number';
const HOURS_SELECTOR = '#hours .number';
const MINUTES_SELECTOR = '#minutes .number';
const SECONDS_SELECTOR = '#seconds .number';

const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 3600;
const SECONDS_IN_DAY = 86400;

const { endDate, someOffset } = Joomla.getOptions('mod_hitcountdown.vars');

const targetDate = new Date(endDate);

jQuery(document).ready(() => {
    const { days, hours, minutes, seconds } = calculateDifference();

    numberTransition(DAYS_SELECTOR, days, 1000, "easeOutQuad");
    numberTransition(HOURS_SELECTOR, hours, 1000, "easeOutQuad");
    numberTransition(MINUTES_SELECTOR, minutes, 1000, "easeOutQuad");
    numberTransition(SECONDS_SELECTOR, seconds, 1000, "easeOutQuad");

    setTimeout(updateTimer, 1001);
});

const updateTimer = () => {
    const { days, hours, minutes, seconds } = calculateDifference();

    jQuery(DAYS_SELECTOR).text(days);
    jQuery(HOURS_SELECTOR).text(hours);
    jQuery(MINUTES_SELECTOR).text(minutes);
    jQuery(SECONDS_SELECTOR).text(seconds);

    setTimeout(updateTimer, 1000);
};

const calculateDifference = () => {
    const currentDate = new Date();
    currentDate.setTime(currentDate.getTime() + someOffset * 3600000);

    const diff = Math.abs(Math.floor((currentDate - targetDate) / 1000));
    var seconds = diff;

    const days = Math.floor(diff / SECONDS_IN_DAY);
    seconds = diff - (days * SECONDS_IN_DAY);

    const hours = Math.floor(seconds / SECONDS_IN_HOUR);
    seconds = seconds - (hours * SECONDS_IN_HOUR);

    const minutes = Math.floor(seconds / SECONDS_IN_MINUTE);
    seconds = seconds - (minutes * SECONDS_IN_MINUTE);

    return { days, hours, minutes, seconds };
};

const numberTransition = (id, endPoint, transitionDuration, transitionEase) => {
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
};
