import { beforeEach, describe, expect, jest } from "@jest/globals";
import { SELECTOR, OuderKindFilter } from "./OuderKindFilter";
import { $ } from "jquery";

describe("OuderKindFilter", () => {
    let filter;

    const hitkiezer = {
        hit: {},
        updateEvent: jest.fn(() => {}),
    };

    beforeEach(() => {
        // leak jquery
        window.$ = $;
        // setup document body
        document.body.innerHTML = `
            <select id="filter_ouderkind">
                <option value="-1"></option>
            </select>`;
    });

    describe("vullen van de dropdowns", () => {
        it("vult de dropdown voor ouder-kind filter met: leeg, ja, nee", () => {
            filter = new OuderKindFilter({});
            const texts = $(SELECTOR)
                .children()
                .map((_, el) => el.text)
                .toArray();

            expect(texts).toEqual(["", "Ja", "Nee"]);
        });
    });

    it("roept updateEvent aan bij update", () => {
        filter = new OuderKindFilter(hitkiezer);
        filter.update();
        expect(hitkiezer.updateEvent).toHaveBeenCalled();
    });

    describe("filter", () => {
        it.each`
            ouderKindValue | isouderkind | expected
            ${null}        | ${0}        | ${true}
            ${-1}          | ${0}        | ${true}
            ${0}           | ${0}        | ${true}
            ${1}           | ${0}        | ${false}
            ${null}        | ${1}        | ${true}
            ${-1}          | ${1}        | ${true}
            ${0}           | ${1}        | ${false}
            ${1}           | ${1}        | ${true}
        `(
            "geeft $expected terug als ouder-kind filter op $ouderKindValue staat en kamp isouderkind $isouderkind",
            ({ ouderKindValue, isouderkind, expected }) => {
                filter = new OuderKindFilter(hitkiezer);
                setOuderKind(ouderKindValue);
                filter.update();

                expect(filter.filter({ isouderkind })).toBe(expected);
            },
        );
    });

    describe("isFilterActief", () => {
        it.each`
            ouderKindValue | isouderkind | expected
            ${null}        | ${0}        | ${false}
            ${-1}          | ${0}        | ${false}
            ${0}           | ${0}        | ${false}
            ${1}           | ${0}        | ${false}
            ${null}        | ${1}        | ${false}
            ${-1}          | ${1}        | ${false}
            ${0}           | ${1}        | ${true}
            ${1}           | ${1}        | ${true}
        `(
            "geeft $expected terug als ouder-kind filter op $ouderKindValue staat en kamp isouderkind $isouderkind",
            ({ ouderKindValue, isouderkind, expected }) => {
                filter = new OuderKindFilter(hitkiezer);
                setOuderKind(ouderKindValue);
                filter.update();

                expect(filter.isFilterActief({ isouderkind })).toBe(expected);
            },
        );
    });

    const setOuderKind = (value) => {
        $(SELECTOR).val(value);
    };
});
