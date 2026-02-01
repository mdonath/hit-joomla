import { beforeEach, describe, expect, jest } from "@jest/globals";
import { SELECTOR, LocatieFilter } from "./LocatieFilter";
import { $ } from "jquery";

describe("LocatieFilter", () => {
    let filter;

    const hitkiezer = {
        hit: {
            hitPlaatsen: [{ naam: "Alphen" }, { naam: "Zeeland" }],
        },
        updateEvent: jest.fn(() => {}),
    };

    beforeEach(() => {
        // leak jquery
        window.$ = $;
        // setup document body
        document.body.innerHTML = `
            <select id="filter_locatie">
                <option value="-1"></option>
            </select>`;
    });

    describe("vullen van de dropdowns", () => {
        it("vult de dropdown voor locatie filter met namen van hitplaatsen", () => {
            filter = new LocatieFilter(hitkiezer);
            const texts = $(SELECTOR)
                .children()
                .map((_, el) => el.text)
                .toArray();

            expect(texts).toEqual(["", "HIT Alphen", "HIT Zeeland"]);
        });
    });

    it("roept updateEvent aan bij update", () => {
        filter = new LocatieFilter(hitkiezer);
        filter.update();
        expect(hitkiezer.updateEvent).toHaveBeenCalled();
    });

    describe("filter", () => {
        it.each`
            locatieValue | plaats      | expected
            ${null}      | ${"Alphen"} | ${true}
            ${-1}        | ${"Alphen"} | ${true}
            ${"Alphen"}  | ${"Alphen"} | ${true}
            ${"Zeeland"} | ${"Alphen"} | ${false}
        `(
            "geeft $expected terug als locatie filter op $locatieValue staat en kamp plaats $plaats",
            ({ locatieValue, plaats, expected }) => {
                filter = new LocatieFilter(hitkiezer);
                setLocatie(locatieValue);
                filter.update();

                expect(filter.filter({ plaats })).toBe(expected);
            },
        );
    });

    function setLocatie(value) {
        $(SELECTOR).val(value);
    }
});
