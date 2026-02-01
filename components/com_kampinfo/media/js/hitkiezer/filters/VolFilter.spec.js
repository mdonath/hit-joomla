import { beforeEach, describe, expect } from "@jest/globals";
import { VolFilter } from "./VolFilter";

describe("VolFilter", () => {
    describe("filter", () => {
        let filter;

        beforeEach(() => {
            filter = new VolFilter({});
        });

        it.each`
            gereserveerd | maximumAantalDeelnemers | aantalSubgroepen | maximumAantalSubgroepjes | expected
            ${0}         | ${10}                   | ${0}             | ${0}                     | ${true}
            ${5}         | ${10}                   | ${0}             | ${0}                     | ${true}
            ${10}        | ${10}                   | ${0}             | ${0}                     | ${false}
            ${20}        | ${10}                   | ${0}             | ${0}                     | ${false}
            ${0}         | ${10}                   | ${5}             | ${5}                     | ${false}
        `(
            "geeft $expected terug als kamp $gereserveerd reserveringen heeft en een maximum van $maximumAantalDeelnemers en $aantalSubgroepen subgroepen van de maximaal $maximumAantalSubgroepen",
            ({
                gereserveerd,
                maximumAantalDeelnemers,
                aantalSubgroepen,
                maximumAantalSubgroepjes,
                expected,
            }) => {
                const kamp = {
                    gereserveerd,
                    maximumAantalDeelnemers,
                    aantalSubgroepen,
                    maximumAantalSubgroepjes,
                };
                expect(filter.filter(kamp)).toBe(expected);
            },
        );
    });
});
