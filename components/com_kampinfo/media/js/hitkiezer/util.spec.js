import { describe, expect, it } from "@jest/globals";
import moment from "moment";

import { fuzzyIndicatieVol, isVol, laatstBijgewerktOp } from "./util.js";

describe("util", () => {
    describe("laatstBijgewerktOp", () => {
        it("laatstBijgewerktOp", () => {
            const result = laatstBijgewerktOp();
            expect(result).toBe("");
        });

        it("laatstBijgewerktOp", () => {
            window.inschrijvingen = { timestamp: "2026-01-31T01:02:03" };
            const result = laatstBijgewerktOp();
            expect(result).toBe("Laatst bijgewerkt op: 31-1-2026 01:02:03");
        });
    });

    describe("fuzzyIndicatieVol", () => {
        it.each`
            startInschrijvingOffset | expectedResult  | waarom
            ${10}                   | ${""}           | ${"de inschrijving nog niet begonnen is"}
            ${-5}                   | ${"HET IS VOL"} | ${"de inschrijving net begonnen is"}
            ${-15}                  | ${""}           | ${"de loterij bezig is"}
            ${-25}                  | ${"HET IS VOL"} | ${"de loterij klaar is en de tweede fase is begonnen"}
            ${-40}                  | ${""}           | ${"de inschrijving helemaal voorbij is"}
        `(
            "toont '$expectedResult' omdat $waarom",
            ({ startInschrijvingOffset, expectedResult, waarom }) => {
                // 10 dagen fase 1, 10 dagen loterij, 10 dagen fase 2
                const kamp = {
                    startInschrijving: createDateWithOffset(
                        startInschrijvingOffset,
                    ),
                    startLoterij: createDateWithOffset(
                        startInschrijvingOffset + 10,
                    ),
                    eindLoterij: createDateWithOffset(
                        startInschrijvingOffset + 20,
                    ),
                    eindInschrijving: createDateWithOffset(
                        startInschrijvingOffset + 30,
                    ),
                    fuzzyIndicatieVol: "HET IS VOL",
                };

                const result = fuzzyIndicatieVol(kamp);

                expect(result).toBe(expectedResult);
            },
        );

        const createDateWithOffset = (offset) => {
            return moment().add(offset, "days").toISOString();
        };
    });

    describe("isVol", () => {
        it.each`
            gereserveerd | maximumAantalDeelnemers | aantalSubgroepen | maximumAantalSubgroepjes | expected
            ${15}        | ${20}                   | ${0}             | ${0}                     | ${false}
            ${20}        | ${20}                   | ${0}             | ${0}                     | ${true}
            ${15}        | ${20}                   | ${5}             | ${10}                    | ${false}
            ${15}        | ${20}                   | ${10}            | ${10}                    | ${true}
        `(
            "should return $expected when gereserveerd is $gereserveerd and maximumAantalDeelnemers is $maximumAantalDeelnemers",
            ({
                gereserveerd,
                maximumAantalDeelnemers,
                aantalSubgroepen,
                maximumAantalSubgroepjes,
                expected,
            }) => {
                const kamp = {
                    gereserveerd: gereserveerd,
                    maximumAantalDeelnemers: maximumAantalDeelnemers,
                    aantalSubgroepen: aantalSubgroepen,
                    maximumAantalSubgroepjes: maximumAantalSubgroepjes,
                };
                expect(isVol(kamp)).toBe(expected);
            },
        );
    });
});
