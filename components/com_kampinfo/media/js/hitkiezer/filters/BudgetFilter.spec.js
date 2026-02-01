import { beforeEach, describe, expect, it, jest } from "@jest/globals";
import { SELECTOR, BudgetFilter } from "./BudgetFilter";
import { $ } from "jquery";

describe("BudgetFilter", () => {
    let filter;

    const hitkiezer = {
        hit: {
            hitPlaatsen: [
                { naam: "Alphen", kampen: [{ deelnamekosten: 99 }] },
                { naam: "Dwingeloo", kampen: [{ deelnamekosten: 99 }] },
                { naam: "Nijmegen", kampen: [{ deelnamekosten: 100 }] },
                { naam: "Zeeland", kampen: [{ deelnamekosten: 200 }] },
            ],
        },
        updateEvent: jest.fn(() => {}),
    };

    beforeEach(() => {
        // leak jquery
        window.$ = $;
        // setup document body
        document.body.innerHTML = `
            <select id="filter_budget">
                <option value="-1"></option>
            </select>`;
    });

    describe("vullen van de dropdowns", () => {
        it("vult de dropdown voor budget filter met juiste text in opties", () => {
            filter = new BudgetFilter(hitkiezer);
            const texts = $(SELECTOR)
                .children()
                .map((_, el) => el.text)
                .toArray();

            expect(texts.length).toEqual(6);

            expect(texts).toEqual([
                "",
                "90 tot 110",
                "100 tot 120",
                "180 tot 200",
                "190 tot 210",
                "200 tot 220",
            ]);
        });

        it("vult de dropdown voor budget filter met juiste value in opties", () => {
            filter = new BudgetFilter(hitkiezer);
            const values = $(SELECTOR)
                .children()
                .map((_, el) => el.value)
                .toArray();

            expect(values.length).toEqual(6);

            expect(values).toEqual(["-1", "100", "110", "190", "200", "210"]);
        });
    });

    it("roept updateEvent aan bij update", () => {
        filter = new BudgetFilter(hitkiezer);
        filter.update();
        expect(hitkiezer.updateEvent).toHaveBeenCalled();
    });

    describe("score", () => {
        it.each`
            filterValue | deelnamekosten | expectedScore
            ${null}     | ${150}         | ${0.0}
            ${-1}       | ${150}         | ${0.0}
            ${110}      | ${100}         | ${1.8181}
            ${200}      | ${180}         | ${1.8}
            ${200}      | ${220}         | ${2.2}
        `(
            "geeft score $expectedScore terug als budget filter op $filterValue staat en kamp deelnamekosten $deelnamekosten",
            ({ filterValue, deelnamekosten, expectedScore }) => {
                filter = new BudgetFilter(hitkiezer);
                setBudget(filterValue);
                filter.update();

                const score = filter.score({ deelnamekosten }, 0);
                expect(score).toBeCloseTo(expectedScore);
            },
        );
    });

    describe("filter", () => {
        it.each([5, 10, 100, 200, 300])(
            "laat kamp door met prijs %i als budget filter op -1 staat",
            (deelnamekosten) => {
                filter = new BudgetFilter(hitkiezer);
                setBudget(-1);
                filter.update();

                expect(filter.filter({ deelnamekosten })).toBe(true);
            },
        );

        it.each([
            { deelnamekosten: 50, expected: false },
            { deelnamekosten: 100, expected: true },
        ])(
            "laat kamp door=$expected met prijs $deelnamekosten als budget filter op 110 staat",
            ({ deelnamekosten, expected }) => {
                filter = new BudgetFilter(hitkiezer);
                setBudget(110);
                filter.update();

                expect(filter.filter({ deelnamekosten })).toBe(expected);
            },
        );
    });

    const setBudget = (value) => {
        $(SELECTOR).val(value);
    };
});
