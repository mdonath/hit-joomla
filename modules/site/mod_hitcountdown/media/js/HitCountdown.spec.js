import { jest } from "@jest/globals";
import { $ } from "jquery";
import moment from "moment";
import HitCountdown from "./HitCountdown";

describe("HitCountdown", () => {
    let hitCountdown;

    let endDate;
    const someOffset = 0;
    const elementId = "elementId";

    beforeEach(() => {
        // leak jquery
        window.$ = $;
        $.easing["easeOutQuad"] = (x) => 1 - (1 - x) * (1 - x);
        jest.useFakeTimers();
        $.fx.off = true;

        // setup document body
        document.body.innerHTML = `
            <div id="elementId">
                <ul id="mhc-countdown">
                    <li id="days">
                        <div class="number">0</div>
                    </li>
                    <li id="hours">
                        <div class="number">0</div>
                    </li>
                    <li id="minutes">
                        <div class="number">0</div>
                    </li>
                    <li id="seconds">
                        <div class="number">0</div>
                    </li>
                </ul>
            </div>`;

        endDate = moment()
            .add(10, "day")
            .add(11, "hour")
            .add(12, "minute")
            .add(14, "second");
    });

    it("should initialize the countdown to the correct values", () => {
        hitCountdown = new HitCountdown(endDate, someOffset, elementId);

        expect($(hitCountdown.selectors.days).text()).toBe("10");
        expect($(hitCountdown.selectors.hours).text()).toBe("11");
        expect($(hitCountdown.selectors.minutes).text()).toBe("12");
        expect($(hitCountdown.selectors.seconds).text()).toBe("13");
    });

    describe("startTimer", () => {
        it("should start the timer and decrement the seconds", () => {
            hitCountdown = new HitCountdown(endDate, someOffset, elementId);

            hitCountdown.startTimer();

            // Compensate for the duration of animation
            jest.advanceTimersByTime(1000);
            
            jest.advanceTimersByTime(1000);
            expect($(hitCountdown.selectors.seconds).text()).toBe("12");

            jest.advanceTimersByTime(5000);
            expect($(hitCountdown.selectors.seconds).text()).toBe("7");
        });

        it("should not start the timer if the targetDate has already been reached", () => {
            endDate = moment().subtract(1, "second");
            hitCountdown = new HitCountdown(endDate, someOffset, elementId);
            expect($(hitCountdown.selectors.seconds).text()).toBe("0");

            hitCountdown.startTimer();
            jest.advanceTimersByTime(5000);

            expect($(hitCountdown.selectors.seconds).text()).toBe("0");
        });
    });

    it("should stop the timer and display all zeroes", () => {
        // create timer with 2 seconds left
        endDate = moment().add(2, "second");
        hitCountdown = new HitCountdown(endDate, someOffset, elementId);
        // timer is initialized with 1 second less left to compensate for transition at the start
        expect($(hitCountdown.selectors.seconds).text()).toBe("1");

        // Compensate time for transition, but remaining seconds is still 1
        jest.advanceTimersByTime(1000);
        expect($(hitCountdown.selectors.seconds).text()).toBe("1");

        // start the timer and wait 1 second, it should now be 0
        hitCountdown.startTimer();
        jest.advanceTimersByTime(1000);
        expect($(hitCountdown.selectors.seconds).text()).toBe("0");

        // Stop the timer and wait 5 seconds, it should still be 0
        jest.advanceTimersByTime(5000);
        expect($(hitCountdown.selectors.seconds).text()).toBe("0");
    });

    it("should display all zeroes when already past targetDate", () => {
        // create timer with 1 second left
        endDate = moment()
            .subtract(3, "second")
            .subtract(4, "minute")
            .subtract(5, "hour")
            .subtract(6, "day");
        hitCountdown = new HitCountdown(endDate, someOffset, elementId);

        expect($(hitCountdown.selectors.days).text()).toBe("0");
        expect($(hitCountdown.selectors.hours).text()).toBe("0");
        expect($(hitCountdown.selectors.minutes).text()).toBe("0");
        expect($(hitCountdown.selectors.seconds).text()).toBe("0");
    });

    describe("offset", () => {
        it.each`
            offset | expectedResult
            ${0}   | ${"11"}
            ${5}   | ${"6"}
            ${-2}  | ${"13"}
        `(
            "should use the specified offset of $offset to shift the hours to $expectedResult",
            ({ offset, expectedResult }) => {
                hitCountdown = new HitCountdown(endDate, offset, elementId);

                expect($(hitCountdown.selectors.hours).text()).toBe(
                    expectedResult,
                );
            },
        );
    });
});
